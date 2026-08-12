import { useState, useMemo } from "react";
import { useTranslation } from "react-i18next";
import { useSearchParams } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import { Search, SlidersHorizontal, ChevronDown } from "lucide-react";
import { APP_IMAGES, getImageUrl } from "../constants/app-images";
import CarsResultsGrid from "../components/CarsResultsGrid";
import FilterDrawerModal from "../components/FilterDrawerModal";
import { getCars, getCarsMeta } from "../services/api/cars.service";
import type { ICarCardProps } from "../interfaces/ICarCardProps";
import { formatPrice } from "../utils/format";
import { useSEO } from "../utils/useSEO";
import type { IFilterValues, ICarsQueryParams } from "../types/cars.types";
import { DEFAULT_FILTER_VALUES } from "../types/cars.types";
import type { ICarItem } from "../types/home.types";
import AllCarsPageSkeleton from "../components/skeletons/AllCarsPageSkeleton";
import { usePageImagesReady } from "../hooks/usePageImagesReady";

const PAGE_SIZE = 9;

const SPEC_KEY_MAP: Record<string, string> = {
  "Fuel Type": "fuel",
  Transmission: "gearbox",
  seats: "seats",
};

function getSpecValue(specs: ICarItem["specs"], label: string): string {
  if (Array.isArray(specs)) {
    const spec = specs.find((s) => "label" in s && s.label === label);
    const v = spec?.value;
    return v != null && typeof v === "string" ? v : "";
  }
  if (specs && typeof specs === "object") {
    const key = SPEC_KEY_MAP[label];
    const v = key ? (specs as Record<string, unknown>)[key] : undefined;
    return v != null && typeof v === "string" ? v : "";
  }
  return "";
}

function mapCarToCardProps(car: ICarItem): ICarCardProps | null {
  try {
    const slug = car.slug?.trim();
    if (!slug) return null;
    return {
      id: car.id,
      image: getImageUrl(car.main_image) || APP_IMAGES.CAR_PLACEHOLDER,
      brand: car.brand?.name ?? "",
      name: car.name ?? "",
      year: String(car.year ?? ""),
      type: car.type ?? "",
      slug,
      fuelType: getSpecValue(car.specs, "Fuel Type") || car.fuel_type || "",
      transmission:
        getSpecValue(car.specs, "Transmission") || car.transmission || "",
      seats: getSpecValue(car.specs, "seats") || car.seats || "",
      oldPrice:
        car.current_price != null && car.current_price < (car.cash_price ?? 0)
          ? formatPrice(car.cash_price ?? 0, "var(--brand-primary-color)")
          : undefined,
      price: formatPrice(
        car.current_price ?? car.cash_price ?? 0,
        "var(--brand-primary-color)",
      ),
      monthlyPrice: formatPrice(
        car.min_installment ?? 0,
        "var(--brand-secondary-color)",
      ),
      detailsTo: `/cars/${slug}`,
      badgeColor: car.badge_color ?? undefined,
    };
  } catch {
    return null;
  }
}

export default function AllCarsPage() {
  const { t, i18n } = useTranslation();
  const isRTL = i18n.dir() === "rtl";
  useSEO(t("nav.cars"), t("allCarsHero.description"));
  const [searchParams] = useSearchParams();
  const offerId = searchParams.get("offerId");

  const [filters, setFilters] = useState<IFilterValues>(DEFAULT_FILTER_VALUES);
  const [currentPage, setCurrentPage] = useState(1);
  const [searchValue, setSearchValue] = useState("");
  const [sortBy, setSortBy] = useState<string>("");
  const [showSortDropdown, setShowSortDropdown] = useState(false);
  const [showFilterModal, setShowFilterModal] = useState(false);

  const sortingOptions = [
    { label: t("allCarsPage.sortLatest", { defaultValue: "الأحدث" }), value: "", sort_by: "", order: "" },
    { label: t("allCarsPage.sortPriceAsc", { defaultValue: "السعر: من الأقل للأعلى" }), value: "price_asc", sort_by: "price", order: "asc" },
    { label: t("allCarsPage.sortPriceDesc", { defaultValue: "السعر: من الأعلى للأقل" }), value: "price_desc", sort_by: "price", order: "desc" },
    { label: t("allCarsPage.sortYearDesc", { defaultValue: "الموديل: الأحدث أولاً" }), value: "year_desc", sort_by: "year", order: "desc" },
  ];

  function buildParams(): ICarsQueryParams {
    const params: ICarsQueryParams = {};
    if (filters.brandId !== null) params.brands = [filters.brandId];
    if (filters.modelId !== null) params.model_id = filters.modelId;
    if (filters.type !== "all") params.type = filters.type as any;
    if (filters.year) params.year = filters.year;
    if (filters.priceMin > 0) params.min_price = filters.priceMin;
    if (filters.priceMax > 0 && filters.priceMax < maxPriceLimit) params.max_price = filters.priceMax;
    if (filters.search) params.search = filters.search;
    if (offerId) params.offer_id = Number(offerId);

    // Sorting
    if (sortBy) {
      const selectedSort = sortingOptions.find((opt) => opt.value === sortBy);
      if (selectedSort?.sort_by) {
        params.sort_by = selectedSort.sort_by;
        params.order = selectedSort.order as "asc" | "desc";
      }
    }

    // Server-side pagination params
    params.page = currentPage;
    params.per_page = PAGE_SIZE;

    return params;
  }

  const { data: metaData } = useQuery({
    queryKey: ["cars-meta"],
    queryFn: () => getCarsMeta(),
    staleTime: 10 * 60 * 1000,
  });

  const { data: carsResponse, isLoading } = useQuery({
    queryKey: ["cars-data", i18n.language, filters, currentPage, offerId, sortBy],
    queryFn: () => getCars(buildParams()),
    staleTime: 2 * 60 * 1000,
    retry: 1,
  });

  const activeFilterCount = [
    filters.brandId !== null,
    filters.fuelType !== "all",
    filters.seats !== "all",
    filters.priceMax < DEFAULT_FILTER_VALUES.priceMax,
  ].filter(Boolean).length;

  const totalPages = carsResponse?.meta?.last_page ?? 1;

  const allCars = useMemo(() => {
    if (carsResponse?.data) {
      return carsResponse.data
        .map(mapCarToCardProps)
        .filter(Boolean) as ICarCardProps[];
    }
    return [];
  }, [carsResponse]);

  const brands = metaData?.filter_brands ?? [];
  const fuelOptions = metaData?.filter_fuels ?? [];
  const maxPriceLimit = metaData?.filter_prices?.length
    ? Math.max(...metaData.filter_prices.map((p) => p.max ?? p.min))
    : 2000000;
  const totalCarsCount = carsResponse?.meta?.total ?? 0;

  const handleTypeFilter = (value: string) => {
    setFilters((prev) => ({ ...prev, type: value }));
    setCurrentPage(1);
  };

  const handleSearch = (value: string) => {
    setSearchValue(value);
    setFilters((prev) => ({ ...prev, search: value }));
    setCurrentPage(1);
  };

  const handleSortSelect = (val: string) => {
    setSortBy(val);
    setCurrentPage(1);
    setShowSortDropdown(false);
  };

  const handleApplyFilters = (newFilters: IFilterValues) => {
    setFilters((prev) => ({ ...newFilters, search: prev.search }));
    setCurrentPage(1);
  };

  const pageImageUrls = useMemo(
    () =>
      (carsResponse?.data ?? [])
        .map((car) => getImageUrl(car.main_image))
        .filter(Boolean) as string[],
    [carsResponse],
  );
  const imagesReady = usePageImagesReady(isLoading, pageImageUrls);

  if (isLoading || !imagesReady) {
    return <AllCarsPageSkeleton />;
  }

  return (
    <main dir={i18n.dir()} className="min-h-screen bg-[#F3F4F6]">
      {/* Page Header Banner */}
      <section className="w-full bg-[#080E1E] py-10 text-white text-start relative overflow-hidden">
        <div className="absolute top-0 right-0 w-80 h-80 bg-[#EDC98E]/5 blur-2xl rounded-full pointer-events-none" />
        <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <span className="text-[13px] font-extrabold text-[#EDC98E] uppercase tracking-wider block mb-2">
            {t("allCarsPage.badge", { defaultValue: "معرض السيارات" })}
          </span>
          <h1 className="text-[30px] font-black text-white leading-tight md:text-[38px]">
            {t("allCarsPage.title", { defaultValue: "تصفح السيارات" })}
          </h1>
        </div>
      </section>

      {/* Filter & Search Bar */}
      <section className="z-30 border-b border-[#E5E9F0] bg-white shadow-xs py-4">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          {/* Row 1: Search, Sort, Filter */}
          <div className="flex flex-col gap-3.5 sm:flex-row sm:items-center sm:justify-between">
            
            {/* Search Input */}
            <div className="relative w-full sm:max-w-[400px]">
              <input
                type="text"
                value={searchValue}
                placeholder={t("allCarsPage.searchPlaceholder", { defaultValue: "ابحث عن سيارة..." })}
                onChange={(e) => handleSearch(e.target.value)}
                className={`h-[46px] w-full rounded-2xl border border-[#E7E9EF] bg-white px-5 ${
                  isRTL ? "pr-12" : "pl-12"
                } text-[14px] text-[#16254F] outline-none placeholder:text-gray-400 focus:border-[#16254F] focus:bg-white focus:ring-2 focus:ring-[#16254F]/10 transition-all duration-200`}
              />
              <Search
                size={18}
                className={`absolute ${
                  isRTL ? "right-4.5" : "left-4.5"
                } top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none`}
              />
            </div>

            {/* Sort & Filter buttons */}
            <div className="flex items-center gap-2.5 relative">
              {/* Sort Dropdown Selector */}
              <div className="relative">
                <button
                  type="button"
                  onClick={() => setShowSortDropdown(!showSortDropdown)}
                  className="flex h-[46px] items-center gap-2 rounded-2xl border border-[#E7E9EF] bg-white px-5 text-[14px] font-extrabold text-[#16254F] transition hover:border-[#16254F] active:scale-95"
                >
                  <span>
                    {sortingOptions.find((opt) => opt.value === sortBy)?.label || t("allCarsPage.sortBy", { defaultValue: "ترتيب حسب" })}
                  </span>
                  <ChevronDown size={16} className="text-gray-400" />
                </button>

                {showSortDropdown && (
                  <>
                    <div
                      className="fixed inset-0 z-40"
                      onClick={() => setShowSortDropdown(false)}
                    />
                    <div className={`absolute ${isRTL ? "right-0" : "left-0"} mt-2 w-52 rounded-xl border border-[#E7E9EF] bg-white p-1.5 shadow-lg z-50 text-start`}>
                      {sortingOptions.map((opt) => (
                        <button
                          key={opt.value}
                          type="button"
                          onClick={() => handleSortSelect(opt.value)}
                          className={`w-full rounded-lg px-4 py-2.5 text-[13px] font-extrabold transition-colors block text-start ${
                            opt.value === sortBy
                              ? "bg-[#16254F] text-white"
                              : "text-[#374151] hover:bg-gray-50"
                          }`}
                        >
                          {opt.label}
                        </button>
                      ))}
                    </div>
                  </>
                )}
              </div>

              {/* Filter drawer trigger button */}
              <button
                type="button"
                onClick={() => setShowFilterModal(true)}
                className="relative flex h-[46px] items-center gap-2 rounded-2xl bg-[#16254F] px-5 text-[14px] font-extrabold text-white transition hover:bg-[#0F1E36] active:scale-95"
              >
                <SlidersHorizontal size={16} />
                <span>{t("carFinder.resetButton", { defaultValue: "الفلاتر" })}</span>
                {activeFilterCount > 0 && (
                  <span className={`absolute ${isRTL ? "-right-1.5" : "-left-1.5"} -top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-[#EDC98E] text-[10px] font-black text-[#16254F]`}>
                    {activeFilterCount}
                  </span>
                )}
              </button>
            </div>

          </div>

          {/* Row 2: Category capsules tabs */}
          <div className="mt-4 pt-4 border-t border-gray-100 text-start">
            <div className="flex flex-nowrap gap-2 overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0 pb-1">
              {[{ id: null, name: t("allCarsFilterBar.all", { defaultValue: "الكل" }), slug: "all" }, ...(metaData?.filter_types ?? [])].map((filter) => {
                const value = filter.slug === "all" ? "all" : filter.slug;
                const label = typeof filter.name === "object" ? Object.values(filter.name)[0] : filter.name;
                const isActive = value === filters.type;
                return (
                  <button
                    key={filter.slug}
                    type="button"
                    onClick={() => handleTypeFilter(value)}
                    className={`h-[36px] shrink-0 rounded-full px-5 text-[13px] font-extrabold transition-all duration-300 ${
                      isActive
                        ? "bg-[#16254F] text-white scale-105 shadow-xs"
                        : "border border-[#E7E9EF] bg-white text-[#667085] hover:bg-[#16254F] hover:text-white hover:border-[#16254F]"
                    }`}
                  >
                    {label}
                  </button>
                );
              })}
            </div>
          </div>

        </div>
      </section>

      {/* Cars Grid Content */}
      <section className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <span className="mb-4 block text-[13px] font-black text-gray-400">
          {t("allCarsPage.carsCount", { defaultValue: `${totalCarsCount} سيارات`, count: totalCarsCount })}
        </span>
        {allCars.length > 0 ? (
          <CarsResultsGrid
            cars={allCars}
            currentPage={currentPage}
            totalPages={totalPages}
            onPageChange={(p) => {
              setCurrentPage(p);
              window.scrollTo({ top: 0, behavior: "smooth" });
            }}
          />
        ) : (
          <div className="py-24 text-center">
            <p className="text-[15px] font-extrabold text-gray-400">
              {t("allCarsPage.noCarsMatch", { defaultValue: "لا توجد سيارات مطابقة لبحثك حالياً." })}
            </p>
          </div>
        )}
      </section>

      <FilterDrawerModal
        isOpen={showFilterModal}
        onClose={() => setShowFilterModal(false)}
        filters={filters}
        onApply={handleApplyFilters}
        brands={brands}
        fuelOptions={fuelOptions}
        maxPriceLimit={maxPriceLimit}
      />
    </main>
  );
}
