import { useTranslation } from "react-i18next";
import { ArrowDown } from "lucide-react";
import OfferListCard from "./OfferListCard";
import FeaturedOfferBanner from "./FeaturedOfferBanner";
import type { IOffersGridSectionProps } from "../../interfaces/IOffersGridSectionProps";

export default function OffersGridSection({
  offers,
  categories,
  activeCategory = "all",
  onCategoryChange,
  loadMoreText,
  hasMore,
  onLoadMore,
}: IOffersGridSectionProps) {
  const { i18n, t } = useTranslation();

  const defaultCategories = [
    { label: t("offersPage.grid.categories.all", "الكل"), value: "all" },
    { label: t("offersPage.grid.categories.popular", "الشائعة"), value: "popular" },
    { label: t("offersPage.grid.categories.exclusive", "عروض حصريـة"), value: "exclusive" },
    { label: t("offersPage.grid.categories.new", "عروض جديدة"), value: "new" },
    { label: t("offersPage.grid.categories.limited", "لفترة محدودة"), value: "limited" },
  ];

  const resolvedCategories = categories ?? defaultCategories;

  const showFeatured = activeCategory === "all";
  const featuredOffer = offers.length > 0
    ? (offers.find((o) => o.tag === "limited") || offers[0])
    : null;

  return (
    <section dir={i18n.dir()} className="w-full bg-[#FAFAFB] py-12 md:py-16">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {/* Featured Banner */}
        {showFeatured && (
          <FeaturedOfferBanner {...(featuredOffer || {})} />
        )}

        {/* Categories Tabs Filter */}
        <div className="mb-10 flex flex-wrap items-center justify-center gap-2">
          {resolvedCategories.map((category) => {
            const isActive = category.value === activeCategory;

            return (
              <button
                key={category.value}
                type="button"
                onClick={() => onCategoryChange?.(category.value)}
                className={`h-[36px] rounded-full px-4 text-[14px] font-bold transition-all duration-200 ${
                  isActive
                    ? "bg-[#16254F] text-white"
                    : "border border-[#E7E9EF] bg-white text-[#667085] hover:bg-[#16254F] hover:text-white hover:border-[#16254F]"
                }`}
              >
                {category.label}
              </button>
            );
          })}
        </div>

        {/* Offers Grid */}
        <div className="grid grid-cols-1 gap-x-8 gap-y-10 md:grid-cols-2 lg:grid-cols-3">
          {offers.map((offer) => (
            <OfferListCard key={offer.id} {...offer} />
          ))}
        </div>

        {/* Load More Button */}
        {hasMore && (
          <div className="mt-14 flex justify-center">
            <button
              type="button"
              onClick={onLoadMore}
              className="inline-flex h-[46px] items-center justify-center gap-2 rounded-2xl bg-white px-8 text-[15px] font-bold text-[#16254F] border border-[#E7E9EF] shadow-sm transition duration-200 hover:bg-[#16254F] hover:text-white hover:border-[#16254F]"
            >
              {loadMoreText || t("offersPage.grid.loadMore", "عرض المزيد من العروض")}
              <ArrowDown size={17} />
            </button>
          </div>
        )}
      </div>
    </section>
  );
}
