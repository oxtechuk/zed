import { useMemo, useState } from "react";
import { useTranslation } from "react-i18next";
import { useInfiniteQuery } from "@tanstack/react-query";
import OffersGridSection from "../components/offers-page/OffersGridSection";
import OffersPageHero from "../components/offers-page/OffersPageHero";
import OffersPageSkeleton from "../components/skeletons/OffersPageSkeleton";
import { getOffers } from "../services/api";
import { useLanguageStore } from "../store/language.store";
import { APP_IMAGES, getImageUrl } from "../constants/app-images";
import { offerToCardProps } from "../utils/offers";
import { useSEO } from "../utils/useSEO";

const PAGE_SIZE = 6;

export default function OffersPage() {
  const { t } = useTranslation();
  useSEO(t("nav.offers"), t("offersPage.hero.description"));
  const language = useLanguageStore((s) => s.language);
  const [activeCategory, setActiveCategory] = useState<string>("all");

  const {
    data: offersResponse,
    fetchNextPage,
    hasNextPage,
    isFetchingNextPage,
    isLoading,
  } = useInfiniteQuery({
    queryKey: ["offers", language, activeCategory],
    queryFn: ({ pageParam }) => getOffers(pageParam as number, PAGE_SIZE, activeCategory),
    initialPageParam: 1,
    getNextPageParam: (lastPage) =>
      lastPage.meta.current_page < lastPage.meta.last_page
        ? lastPage.meta.current_page + 1
        : undefined,
  });

  const hero = offersResponse?.pages?.[0]?.meta.hero;

  const offers = useMemo(() => {
    if (!offersResponse?.pages) return [];
    return offersResponse.pages.flatMap((page) =>
      page.data.map((offer) => offerToCardProps(offer, t))
    );
  }, [offersResponse, t]);

  if (isLoading) {
    return <OffersPageSkeleton />;
  }

  return (
    <>
      <OffersPageHero
        image={getImageUrl(hero?.image ?? null) || APP_IMAGES.OFFER_PLACEHOLDER}
        badgeText={hero?.badge || t("offersPage.hero.badge")}
        title={hero?.title || t("offersPage.hero.title")}
        description={hero?.subtitle || t("offersPage.hero.description")}
      />

      <OffersGridSection
        hero={hero}
        offers={offers}
        activeCategory={activeCategory}
        onCategoryChange={setActiveCategory}
        loadMoreText={
          isFetchingNextPage
            ? t("blogPage.latestArticles.loading")
            : t("offersPage.grid.loadMore")
        }
        hasMore={!!hasNextPage}
        onLoadMore={() => fetchNextPage()}
      />


    </>
  );
}
