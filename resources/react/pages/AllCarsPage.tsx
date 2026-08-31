import { useState, useMemo, useEffect, useRef } from "react";
import { useTranslation } from "react-i18next";
import { useSearchParams, useNavigate } from "react-router-dom";
import { useQuery, keepPreviousData } from "@tanstack/react-query";
import { Search, SlidersHorizontal, ChevronDown, X, Loader2 } from "lucide-react";
import { APP_IMAGES, getImageUrl } from "../constants/app-images";
import CarsResultsGrid from "../components/CarsResultsGrid";
import FilterDrawerModal from "../components/FilterDrawerModal";
import { getCars, getCarsMeta, searchCars } from "../services/api/cars.service";
import type { ICarCardProps } from "../interfaces/ICarCardProps";
import { formatPrice } from "../utils/format";
import { useSEO } from "../utils/useSEO";
import type { IFilterValues, ICarsQueryParams } from "../types/cars.types";
import { DEFAULT_FILTER_VALUES } from "../types/cars.types";
import type { ICarItem } from "../types/home.types";
import AllCarsPageSkeleton from "../components/skeletons/AllCarsPageSkeleton";
import LazyImg from "../components/LazyImg";

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
      model: car.model ?? "",
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
  const navigate = useNavigate();
  const isRTL = i18n.dir() === "rtl";
  useSEO(t("nav.cars"), t("allCarsHero.description"));
  const [searchParams] = useSearchParams();
  const offerId = searchParams.get("offerId");

  const [filters, setFilters] = useState<IFilterValues>(DEFAULT_FILTER_VALUES);
  const [currentPage, setCurrentPage] = useState(1);
  const [searchValue, setSearchValue] = useState("");
  const [suggestions, setSuggestions] = useState<ICarItem[]>([]);
  const [showSuggestions, setShowSuggestions] = useState(false);
  const [isSearchingSuggestions, setIsSearchingSuggestions] = useState(false);
  const [sortBy, setSortBy] = useState<string>("");
  const [showSortDropdown, setShowSortDropdown] = useState(false);
  const [showFilterModal, setShowFilterModal] = useState(false);

  const searchContainerRef = useRef<HTMLDivElement>(null);
  const debounceTimerRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  const sortingOptions = [
    { label: t("allCarsPage.sortLatest", { defaultValue: "الأحدث" }), value: "", sort_by: "", order: "" },
    { label: t("allCarsPage.sortPriceAsc", { defaultValue: "السعر: من الأقل للأعلى" }), value: "price_asc", sort_by: "price", order: "asc" },
    { label: t("allCarsPage.sortPriceDesc", { defaultValue: "السعر: من الأعلى للأقل" }), value: "price_desc", sort_by: "price", order: "desc" },
    { label: t("allCarsPage.sortYearDesc", { defaultValue: "الموديل: الأحدث أولاً" }), value: "year_desc", sort_by: "year", order: "desc" },
  ];

  // Close suggestions when clicking outside
  useEffect(() => {
    function handleClickOutside(event: MouseEvent | TouchEvent) {
      if (
        searchContainerRef.current &&
        !searchContainerRef.current.contains(event.target as Node)
      ) {
        setShowSuggestions(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    document.addEventListener("touchstart", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
      document.removeEventListener("touchstart", handleClickOutside);
    };
  }, []);

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

  const { data: carsResponse, isLoading, isFetching } = useQuery({
    queryKey: ["cars-data", i18n.language, filters, currentPage, offerId, sortBy],
    queryFn: () => getCars(buildParams()),
    staleTime: 2 * 60 * 1000,
    placeholderData: keepPreviousData,
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

  const handleModelFilter = (modelId: number | null) => {
    setFilters((prev) => ({
      ...prev,
      modelId: prev.modelId === modelId ? null : modelId,
    }));
    setCurrentPage(1);
  };

  const handleTypeFilter = (value: string) => {
    setFilters((prev) => ({ ...prev, type: value }));
    setCurrentPage(1);
  };

  const handleSearchChange = (value: string) => {
    setSearchValue(value);

    if (debounceTimerRef.current) {
      clearTimeout(debounceTimerRef.current);
    }

    if (!value.trim()) {
      setSuggestions([]);
      setShowSuggestions(false);
      setIsSearchingSuggestions(false);
      setFilters((prev) => ({ ...prev, search: "" }));
      setCurrentPage(1);
      return;
    }

    setShowSuggestions(true);
    setIsSearchingSuggestions(true);

    debounceTimerRef.current = setTimeout(async () => {
      // 1. Fetch autocomplete suggestions
      try {
        const results = await searchCars(value.trim());
        setSuggestions(results || []);
      } catch {
        setSuggestions([]);
      } finally {
        setIsSearchingSuggestions(false);
      }

      // 2. Apply search filter to the cars query smoothly
      setFilters((prev) => ({ ...prev, search: value.trim() }));
      setCurrentPage(1);
    }, 400);
  };

  const handleClearSearch = () => {
    if (debounceTimerRef.current) {
      clearTimeout(debounceTimerRef.current);
    }
    setSearchValue("");
    setSuggestions([]);
    setShowSuggestions(false);
    setIsSearchingSuggestions(false);
    setFilters((prev) => ({ ...prev, search: "" }));
    setCurrentPage(1);
  };

  const handleSelectSuggestion = (car: ICarItem) => {
    setShowSuggestions(false);
    if (car.slug) {
      navigate(`/cars/${car.slug}`);
    } else {
      setSearchValue(car.name);
      setFilters((prev) => ({ ...prev, search: car.name }));
      setCurrentPage(1);
    }
  };

  const handleSearchSubmit = (e?: React.FormEvent) => {
    if (e) e.preventDefault();
    if (debounceTimerRef.current) {
      clearTimeout(debounceTimerRef.current);
    }
    setShowSuggestions(false);
    setFilters((prev) => ({ ...prev, search: searchValue.trim() }));
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

  if (isLoading && !carsResponse) {
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
            
            {/* Search Input with Autocomplete Suggestions */}
            <div ref={searchContainerRef} className="relative w-full sm:max-w-[400px]">
              <form onSubmit={handleSearchSubmit} className="relative">
                <input
                  type="text"
                  value={searchValue}
                  placeholder={t("allCarsPage.searchPlaceholder", { defaultValue: "ابحث عن سيارة، ماركة، أو موديل..." })}
                  onChange={(e) => handleSearchChange(e.target.value)}
                  onFocus={() => {
                    if (searchValue.trim().length > 0) {
                      setShowSuggestions(true);
                    }
                  }}
                  onKeyDown={(e) => {
                    if (e.key === "Escape") {
                      setShowSuggestions(false);
                    }
                  }}
                  className={`h-[46px] w-full rounded-2xl border border-[#E7E9EF] bg-white px-5 ${
                    isRTL ? "pr-11 pl-10" : "pl-11 pr-10"
                  } text-[14px] text-[#16254F] outline-none placeholder:text-gray-400 focus:border-[#16254F] focus:bg-white focus:ring-2 focus:ring-[#16254F]/10 transition-all duration-200`}
                />
                
                {/* Search / Loading Icon */}
                <div className={`absolute ${isRTL ? "right-3.5" : "left-3.5"} top-1/2 -translate-y-1/2 pointer-events-none flex items-center`}>
                  {isSearchingSuggestions ? (
                    <Loader2 size={18} className="animate-spin text-[#16254F]" />
                  ) : (
                    <Search size={18} className="text-gray-400" />
                  )}
                </div>

                {/* Clear search button (X) */}
                {searchValue.length > 0 && (
                  <button
                    type="button"
                    onClick={handleClearSearch}
                    className={`absolute ${isRTL ? "left-3.5" : "right-3.5"} top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition p-1 cursor-pointer`}
                    title={t("common.clear", { defaultValue: "مسح" })}
                  >
                    <X size={16} />
                  </button>
                )}
              </form>

              {/* Suggestions Dropdown */}
              {showSuggestions && searchValue.trim().length > 0 && (
                <div className="absolute inset-x-0 top-full mt-2 z-50 rounded-2xl border border-[#E5E9F0] bg-white shadow-2xl overflow-hidden max-h-[360px] overflow-y-auto">
                  {isSearchingSuggestions && suggestions.length === 0 ? (
                    <div className="p-5 text-center flex items-center justify-center gap-2 text-[13px] font-bold text-gray-400">
                      <Loader2 size={16} className="animate-spin text-[#16254F]" />
                      <span>{t("common.searching", { defaultValue: "جاري البحث عن المقترحات..." })}</span>
                    </div>
                  ) : suggestions.length > 0 ? (
                    <div className="py-2 divide-y divide-[#F1F3F7]">
                      <div className="px-4 py-1.5 text-[11px] font-black uppercase tracking-wider text-gray-400">
                        {t("allCarsPage.suggestionsTitle", { defaultValue: "النتائج والمقترحات" })}
                      </div>
                      {suggestions.slice(0, 6).map((car) => (
                        <button
                          key={car.id}
                          type="button"
                          onClick={() => handleSelectSuggestion(car)}
                          className="flex w-full items-center gap-3.5 px-4 py-2.5 text-start transition hover:bg-[#F8FAFC] group cursor-pointer"
                        >
                          <LazyImg
                            src={getImageUrl(car.main_image) || APP_IMAGES.CAR_PLACEHOLDER}
                            alt={car.name}
                            className="h-11 w-15 object-contain rounded-lg bg-gray-50 border border-gray-100 p-1 shrink-0"
                          />
                          <div className="flex-1 min-w-0">
                            <p className="text-[11px] text-gray-400 font-bold leading-none mb-1 truncate">
                              {car.brand?.name || ""}
                            </p>
                            <p className="text-[13px] font-extrabold text-[#16254F] leading-tight truncate group-hover:text-[#EDC98E] transition-colors">
                              {car.name} {car.year ? car.year : ""}
                            </p>
                          </div>
                          {(car.current_price ?? car.cash_price) != null && (
                            <span className="text-[12px] font-black text-[#16254F] shrink-0">
                              {formatPrice(car.current_price ?? car.cash_price ?? 0, "var(--brand-primary-color)")}
                            </span>
                          )}
                        </button>
                      ))}

                      {/* View all search results button */}
                      <button
                        type="button"
                        onClick={() => handleSearchSubmit()}
                        className="w-full px-4 py-2.5 text-center text-[12px] font-black text-[#16254F] bg-gray-50 hover:bg-gray-100 transition-colors block cursor-pointer"
                      >
                        {t("allCarsPage.viewAllResultsFor", {
                          defaultValue: `عرض جميع النتائج لـ "${searchValue}"`,
                          query: searchValue,
                        })}
                      </button>
                    </div>
                  ) : (
                    <div className="p-5 text-center text-[13px] font-bold text-gray-400">
                      {t("allCarsPage.noSuggestionsFound", { defaultValue: "لا توجد نتائج مطابقة لبحثك" })}
                    </div>
                  )}
                </div>
              )}
            </div>

            {/* Sort & Filter buttons */}
            <div className="flex items-center gap-2.5 relative">
              {/* Sort Dropdown Selector */}
              <div className="relative">
                <button
                  type="button"
                  onClick={() => setShowSortDropdown(!showSortDropdown)}
                  className="flex h-[46px] items-center gap-2 rounded-2xl border border-[#E7E9EF] bg-white px-5 text-[14px] font-extrabold text-[#16254F] transition hover:border-[#16254F] active:scale-95 cursor-pointer"
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
                          className={`w-full rounded-lg px-4 py-2.5 text-[13px] font-extrabold transition-colors block text-start cursor-pointer ${
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
                className="relative flex h-[46px] items-center gap-2 rounded-2xl bg-[#16254F] px-5 text-[14px] font-extrabold text-white transition hover:bg-[#0F1E36] active:scale-95 cursor-pointer"
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

          {/* Row 2: Car Model capsules tabs */}
          <div className="mt-4 pt-4 border-t border-gray-100 text-start">
            <div className="flex flex-nowrap gap-2 overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0 pb-1 no-scrollbar scroll-smooth">
              <button
                type="button"
                onClick={() => handleModelFilter(null)}
                className={`h-[36px] shrink-0 rounded-full px-5 text-[13px] font-extrabold transition-all duration-200 active:scale-95 cursor-pointer whitespace-nowrap ${
                  filters.modelId === null
                    ? "bg-[#16254F] text-white scale-105 shadow-xs ring-2 ring-[#16254F]/20"
                    : "border border-[#E7E9EF] bg-white text-[#667085] hover:bg-[#16254F] hover:text-white hover:border-[#16254F]"
                }`}
              >
                {t("allCarsFilterBar.all", { defaultValue: "الكل" })}
              </button>

              {(metaData?.filter_models ?? [])
                .filter((m) => !filters.brandId || m.brand_id === filters.brandId)
                .map((model) => {
                  const label =
                    typeof model.name === "object"
                      ? (model.name[i18n.language] || Object.values(model.name)[0])
                      : model.name;
                  const isActive = filters.modelId === model.id;
                  return (
                    <button
                      key={model.id}
                      type="button"
                      onClick={() => handleModelFilter(model.id)}
                      className={`h-[36px] shrink-0 rounded-full px-5 text-[13px] font-extrabold transition-all duration-200 active:scale-95 cursor-pointer whitespace-nowrap ${
                        isActive
                          ? "bg-[#16254F] text-white scale-105 shadow-xs ring-2 ring-[#16254F]/20"
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
        <div className="mb-4 flex items-center justify-between">
          <span className="block text-[13px] font-black text-gray-400">
            {t("allCarsPage.carsCount", { defaultValue: `${totalCarsCount} سيارات`, count: totalCarsCount })}
          </span>
          {isFetching && !isLoading && (
            <div className="flex items-center gap-1.5 text-[12px] font-bold text-gray-400 animate-pulse">
              <Loader2 size={13} className="animate-spin" />
              <span>{t("common.updating", { defaultValue: "جاري التحديث..." })}</span>
            </div>
          )}
        </div>
        <div className={`transition-opacity duration-200 ${isFetching ? "opacity-70 pointer-events-none" : "opacity-100"}`}>
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
        </div>
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
