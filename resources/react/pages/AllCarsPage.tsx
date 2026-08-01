import { useState, useMemo } from "react";
import { useTranslation } from "react-i18next";
import { useSearchParams } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import { Search, SlidersHorizontal, ChevronDown } from "lucide-react";
import { APP_IMAGES, getImageUrl } from "../constants/app-images";
import CarsResultsGrid from "../components/CarsResultsGrid";
import { getCars } from "../services/api/cars.service";
import type { CarCardProps } from "../components/CarCard";
import { formatPrice } from "../utils/format";
import { useSEO } from "../utils/useSEO";
import type { FilterValues, CarsQueryParams } from "../types/cars.types";
import { DEFAULT_FILTER_VALUES } from "../types/cars.types";
import type { CarItem } from "../types/home.types";

const PAGE_SIZE = 20;

const SPEC_KEY_MAP: Record<string, string> = {
  "Fuel Type": "fuel",
  Transmission: "gearbox",
  seats: "seats",
};

function getSpecValue(specs: CarItem["specs"], label: string): string {
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

function mapCarToCardProps(car: CarItem): CarCardProps | null {
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
    };
  } catch {
    return null;
  }
}

export default function AllCarsPage() {
  const { t, i18n } = useTranslation();
  useSEO(t("nav.cars"), t("allCarsHero.description"));
  const [searchParams] = useSearchParams();
  const offerId = searchParams.get("offerId");

  const [filters, setFilters] = useState<FilterValues>(DEFAULT_FILTER_VALUES);
  const [currentPage, setCurrentPage] = useState(1);
  const [searchValue, setSearchValue] = useState("");
  const [sortBy, setSortBy] = useState<string>("");
  const [showSortDropdown, setShowSortDropdown] = useState(false);

  const staticTypeFilters = [
    { label: "الكل", value: "all" },
    { label: "كهربائية", value: "electric" },
    { label: "فاخرة", value: "luxury" },
    { label: "رياضية", value: "sport" },
    { label: "سيدان", value: "sedan" },
    { label: "SUV", value: "suv" },
  ];

  const sortingOptions = [
    { label: "الأحدث", value: "" },
    { label: "السعر: من الأقل للأعلى", value: "price_asc" },
    { label: "السعر: من الأعلى للأقل", value: "price_desc" },
    { label: "الموديل: الأحدث أولاً", value: "year_desc" },
  ];

  function buildParams(): CarsQueryParams {
    const params: CarsQueryParams = {};
    if (filters.brandId !== null) params.brands = [filters.brandId];
    if (filters.type !== "all") params.type = filters.type as any;
    if (filters.year) params.year = filters.year;
    if (filters.priceMin > 0) params.min_price = filters.priceMin;
    if (filters.priceMax < 200000) params.max_price = filters.priceMax;
    if (filters.search) params.search = filters.search;
    if (offerId) params.offer_id = Number(offerId);

    // Sorting
    if (sortBy) {
      params.sort = sortBy as any;
    }

    // Server-side pagination params
    params.page = currentPage;
    params.per_page = PAGE_SIZE;

    return params;
  }

  const { data: carsResponse, isLoading } = useQuery({
    queryKey: ["cars-data", i18n.language, filters, currentPage, offerId, sortBy],
    queryFn: () => getCars(buildParams()),
    staleTime: 2 * 60 * 1000, // 2 minutes
    retry: 1,
  });

  const allCars = useMemo(() => {
    if (carsResponse?.data) {
      return carsResponse.data
        .map(mapCarToCardProps)
        .filter(Boolean) as CarCardProps[];
    }
    return [];
  }, [carsResponse]);

  const totalPages = carsResponse?.meta?.last_page ?? 1;
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

  return (
    <main dir={i18n.dir()} className="min-h-screen bg-[#F3F4F6]">
      {/* ── Page Header (Dark Navy Banner) ── */}
      <section className="w-full bg-[#0F172A] py-14 text-white text-center relative overflow-hidden">
        <div className="absolute top-0 right-0 w-80 h-80 bg-[#EDC98E]/5 blur-2xl rounded-full pointer-events-none" />
        <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <span className="text-[13px] font-extrabold text-[#EDC98E] uppercase tracking-wider block mb-2">
            معرض السيارات
          </span>
          <h1 className="text-[30px] font-black text-white leading-tight md:text-[38px]">
            تصفح السيارات
          </h1>
        </div>
      </section>

      {/* ── Filter & Search Bar ── */}
      <section className="sticky top-[76px] z-30 border-b border-[#E5E9F0] bg-white shadow-xs py-4">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          {/* Row 1: Search, Sort, Filter */}
          <div className="flex flex-col gap-3.5 sm:flex-row sm:items-center sm:justify-between">
            
            {/* Search Input */}
            <div className="relative w-full sm:max-w-[400px]">
              <input
                type="text"
                value={searchValue}
                placeholder="ابحث عن سيارة..."
                onChange={(e) => handleSearch(e.target.value)}
                className="h-[46px] w-full rounded-full border border-gray-200 bg-[#F8FAFC] px-5 pr-12 text-[14px] text-[#0F172A] outline-none placeholder:text-gray-400 focus:border-[#0F172A] focus:bg-white focus:ring-2 focus:ring-[#0F172A]/10 transition-all duration-200"
              />
              <Search
                size={18}
                className="absolute right-4.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
              />
            </div>

            {/* Sort & Filter buttons */}
            <div className="flex items-center gap-2.5 relative">
              {/* Sort Dropdown Selector */}
              <div className="relative">
                <button
                  type="button"
                  onClick={() => setShowSortDropdown(!showSortDropdown)}
                  className="flex h-[46px] items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 text-[14px] font-extrabold text-[#0F172A] transition hover:border-[#0F172A] active:scale-95"
                >
                  <span>
                    {sortingOptions.find((opt) => opt.value === sortBy)?.label || "ترتيب حسب"}
                  </span>
                  <ChevronDown size={16} className="text-gray-400" />
                </button>

                {showSortDropdown && (
                  <>
                    <div
                      className="fixed inset-0 z-40"
                      onClick={() => setShowSortDropdown(false)}
                    />
                    <div className="absolute right-0 mt-2 w-52 rounded-xl border border-[#E5E9F0] bg-white p-1.5 shadow-lg z-50 text-start">
                      {sortingOptions.map((opt) => (
                        <button
                          key={opt.value}
                          type="button"
                          onClick={() => handleSortSelect(opt.value)}
                          className={`w-full rounded-lg px-4 py-2.5 text-[13px] font-extrabold transition-colors text-right block ${
                            opt.value === sortBy
                              ? "bg-[#0F172A] text-white"
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
                className="flex h-[46px] items-center gap-2 rounded-xl bg-[#0F172A] px-5 text-[14px] font-extrabold text-white transition hover:bg-[#1E293B] active:scale-95"
              >
                <SlidersHorizontal size={16} />
                <span>الفلاتر</span>
              </button>
            </div>

          </div>

          {/* Row 2: Category capsules tabs + dynamic count */}
          <div className="mt-4 pt-4 border-t border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-3 text-start">
            <div className="flex flex-wrap gap-2">
              {staticTypeFilters.map((filter) => {
                const isActive = filter.value === filters.type;
                return (
                  <button
                    key={filter.value}
                    type="button"
                    onClick={() => handleTypeFilter(filter.value)}
                    className={`h-[36px] rounded-full px-5 text-[13px] font-extrabold transition-all duration-300 ${
                      isActive
                        ? "bg-[#0F172A] text-white scale-105 shadow-xs"
                        : "bg-[#F1F5F9] text-gray-500 hover:bg-[#0F172A] hover:text-white"
                    }`}
                  >
                    {filter.label}
                  </button>
                );
              })}
            </div>

            <span className="text-[13px] font-black text-gray-400">
              {isLoading ? "جاري التحميل..." : `${totalCarsCount} سيارات`}
            </span>
          </div>

        </div>
      </section>

      {/* ── Cars Grid Content ── */}
      <section className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        {isLoading ? (
          <div className="py-24 text-center">
            <div className="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite] text-[#0F172A]" />
            <p className="mt-4 text-sm font-extrabold text-gray-400">جاري تحميل السيارات...</p>
          </div>
        ) : allCars.length > 0 ? (
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
    </main>
  );
}
