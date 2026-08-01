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
    { label: t("offersPage.grid.categories.all"), value: "all" },
    { label: t("offersPage.grid.categories.popular"), value: "popular" },
    { label: t("offersPage.grid.categories.exclusive"), value: "exclusive" },
    { label: t("offersPage.grid.categories.new"), value: "new" },
    { label: t("offersPage.grid.categories.limited"), value: "limited" },
  ];

  const resolvedCategories = categories ?? defaultCategories;

  // Determine the featured offer (e.g. Ramadan Offer) to display on the main landing category
  const showFeatured = activeCategory === "all" && offers.length > 0;
  const featuredOffer = showFeatured
    ? (offers.find((o) => o.tag === "limited") || offers[0])
    : null;

  return (
    <section dir={i18n.dir()} className="w-full bg-[#F4F6F9] py-14">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {/* Featured Offer Banner inside grid section container */}
        {featuredOffer && (
          <FeaturedOfferBanner {...featuredOffer} />
        )}

        {/* Filters Tabs - centered as in the screenshot */}
        <div className="mb-10 flex justify-center">
          <div className="flex flex-wrap items-center gap-3">
            {resolvedCategories.map((category) => {
              const isActive = category.value === activeCategory;

              return (
                <button
                  key={category.value}
                  type="button"
                  onClick={() => onCategoryChange?.(category.value)}
                  className={`h-[38px] rounded-full px-6 text-[14px] font-bold shadow-sm transition-all duration-200 ${
                    isActive
                      ? "bg-[#07111F] text-white"
                      : "bg-white text-[#6B7280] border border-gray-100 hover:bg-[#07111F] hover:text-white"
                  }`}
                >
                  {category.label}
                </button>
              );
            })}
          </div>
        </div>

        {/* Offers Grid */}
        <div className="grid grid-cols-1 gap-x-8 gap-y-10 md:grid-cols-2 lg:grid-cols-3">
          {offers.map((offer) => (
            <OfferListCard key={offer.id} {...offer} />
          ))}
        </div>

        {/* Load More */}
        {hasMore && (
          <div className="mt-14 flex justify-center">
            <button
              type="button"
              onClick={onLoadMore}
              className="inline-flex h-[44px] items-center justify-center gap-2 rounded-full bg-white px-7 text-[15px] font-bold text-[#07111F] border border-gray-100 shadow-sm transition hover:bg-[#07111F] hover:text-white"
            >
              {loadMoreText || t("offersPage.grid.loadMore")}
              <ArrowDown size={17} />
            </button>
          </div>
        )}
      </div>
    </section>
  );
}
