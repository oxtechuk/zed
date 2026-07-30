import { useState, useMemo } from "react";
import { useTranslation } from "react-i18next";
import { useSearchParams } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import { Search, SlidersHorizontal, ChevronDown } from "lucide-react";
import { APP_IMAGES, getImageUrl } from "../constants/app-images";
import CarsResultsGrid from "../components/CarsResultsGrid";
import { getCarsMeta, getCarTypes } from "../services/api/cars.service";
import { getCars } from "../services/api/cars.service";
import { getHomePageData } from "../services/api";
import { useLanguageStore } from "../store/language.store";
import type { CarCardProps } from "../components/CarCard";
import { formatPrice } from "../utils/format";
import { useSEO } from "../utils/useSEO";
import type { FilterValues, CarsQueryParams } from "../types/cars.types";
import { DEFAULT_FILTER_VALUES } from "../types/cars.types";
import type { CarItem } from "../types/home.types";

const PAGE_SIZE = 9;

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
  const language = useLanguageStore((s) => s.language);
  const [searchParams] = useSearchParams();
  const offerId = searchParams.get("offerId");

  const { data: homeData } = useQuery({
    queryKey: ["home-data", language],
    queryFn: getHomePageData,
    staleTime: 5 * 60 * 1000,
  });

  const { data: carsMeta } = useQuery({
    queryKey: ["cars-meta", language],
    queryFn: getCarsMeta,
    staleTime: 5 * 60 * 1000,
  });

  const { data: carTypes } = useQuery({
    queryKey: ["car-types"],
    queryFn: getCarTypes,
    staleTime: 5 * 60 * 1000,
  });

  const [filters, setFilters] = useState<FilterValues>(DEFAULT_FILTER_VALUES);
  const [currentPage, setCurrentPage] = useState(1);
  const [searchValue, setSearchValue] = useState("");
  const [_sortAsc, setSortAsc] = useState(true);

  const typeFilters = [
    { label: t("allCarsFilterBar.all"), value: "all" },
    ...(carTypes?.map((ct) => ({ label: ct.name, value: String(ct.id) })) ?? []),
  ];

  function buildParams(): CarsQueryParams {
    const params: CarsQueryParams = {};
    if (filters.brandId !== null) params.brands = [filters.brandId];
    if (filters.type !== "all") params.type = Number(filters.type);
    if (filters.year) params.year = filters.year;
    if (filters.priceMin > 0) params.min_price = filters.priceMin;
    if (filters.priceMax < 200000) params.max_price = filters.priceMax;
    if (filters.search) params.search = filters.search;
    if (offerId) params.offer_id = Number(offerId);
    return params;
  }

  const { data: carsResponse } = useQuery({
    queryKey: ["cars-data", language, filters, currentPage, offerId],
    queryFn: () => getCars(buildParams()),
    staleTime: 5 * 60 * 1000,
    retry: 1,
  });

  const allCars = useMemo(() => {
    if (carsResponse) {
      return carsResponse.data
        .map(mapCarToCardProps)
        .filter(Boolean) as CarCardProps[];
    }
    const fallback = homeData?.featured_cars ?? homeData?.bento_cars ?? [];
    return fallback.map(mapCarToCardProps).filter(Boolean) as CarCardProps[];
  }, [carsResponse, homeData?.featured_cars, homeData?.bento_cars]);

  const filteredCars = useMemo(() => {
    let result = allCars.slice();
    if (filters.transmission !== "all") {
      result = result.filter((c) => c.transmission === filters.transmission);
    }
    if (filters.fuelType !== "all") {
      result = result.filter((c) => c.fuelType === filters.fuelType);
    }
    return result;
  }, [allCars, filters]);

  const totalPages = Math.max(1, Math.ceil(filteredCars.length / PAGE_SIZE));
  const safePage = Math.min(currentPage, totalPages);
  const pagedCars = filteredCars.slice(
    (safePage - 1) * PAGE_SIZE,
    safePage * PAGE_SIZE,
  );

  const handleTypeFilter = (value: string) => {
    setFilters((prev) => ({ ...prev, type: value }));
    setCurrentPage(1);
  };

  const handleSearch = (value: string) => {
    setSearchValue(value);
    setFilters((prev) => ({ ...prev, search: value }));
    setCurrentPage(1);
  };

  // Resolve page title/breadcrumb from API or fallback
  const pageTitle =
    carsMeta?.hero_title_line1?.trim() ||
    carsMeta?.hero_title_line2_highlight?.trim() ||
    t("nav.cars");

  const breadcrumb =
    carsMeta?.hero_badge?.trim() || t("allCarsPage.breadcrumb", { defaultValue: "معرض السيارات" });

  return (
    <main dir={i18n.dir()} className="min-h-screen bg-[#F0F2F5]">
      {/* ── Page Header ── */}
      <section className="bg-white px-4 py-8 sm:px-6 lg:px-8">
        <div className="mx-auto max-w-7xl">
          <p className="mb-1 text-sm text-[#9CA3AF]">{breadcrumb}</p>
          <h1 className="text-3xl font-bold text-[#111827] sm:text-4xl">
            {pageTitle}
          </h1>
        </div>
      </section>

      {/* ── Filter Bar ── */}
      <section className="sticky top-[35px] z-30 border-b border-[#DDE3EA] bg-white shadow-sm">
        <div className="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
          {/* Row 1: search + sort + filter */}
          <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            {/* Search */}
            <div className="relative w-full sm:max-w-[380px]">
              <input
                type="text"
                value={searchValue}
                placeholder={t("allCarsFilterBar.searchPlaceholder")}
                onChange={(e) => handleSearch(e.target.value)}
                className="h-[46px] w-full rounded-full border border-[#D1D5DB] bg-white px-5 pr-12 text-[14px] text-[#111827] outline-none placeholder:text-[#9CA3AF] focus:border-[var(--brand-primary-color)] focus:ring-2 focus:ring-[rgba(41,155,224,0.15)]"
              />
              <Search
                size={18}
                className="absolute right-4 top-1/2 -translate-y-1/2 text-[#9CA3AF]"
              />
            </div>

            {/* Sort + Filter buttons */}
            <div className="flex items-center gap-2">
              <button
                type="button"
                className="flex h-[46px] items-center gap-2 rounded-[10px] border border-[#D1D5DB] bg-white px-4 text-[14px] font-medium text-[#374151] transition hover:border-[var(--brand-primary-color)] hover:text-[var(--brand-primary-color)]"
                onClick={() => setSortAsc((prev) => !prev)}
              >
                <span>{t("allCarsPage.sort", { defaultValue: "ترتيب حسب" })}</span>
                <ChevronDown size={16} />
              </button>

              <button
                type="button"
                className="flex h-[46px] items-center gap-2 rounded-[10px] border border-[#D1D5DB] bg-white px-4 text-[14px] font-medium text-[#374151] transition hover:border-[var(--brand-primary-color)] hover:text-[var(--brand-primary-color)]"
              >
                <SlidersHorizontal size={16} />
                <span>{t("allCarsPage.filterBtn", { defaultValue: "المصادر" })}</span>
              </button>
            </div>
          </div>

          {/* Row 2: type tabs + count */}
          <div className="mt-3 flex flex-wrap items-center justify-between gap-3">
            <div className="flex flex-wrap gap-2">
              {typeFilters.map((filter) => {
                const isActive = filter.value === filters.type;
                return (
                  <button
                    key={filter.value}
                    type="button"
                    onClick={() => handleTypeFilter(filter.value)}
                    className={`h-[36px] rounded-[8px] px-5 text-[13px] font-semibold transition ${
                      isActive
                        ? "bg-[#111827] text-white"
                        : "bg-[#F3F4F6] text-[#5B6470] hover:bg-[#111827] hover:text-white"
                    }`}
                  >
                    {filter.label}
                  </button>
                );
              })}
            </div>

            <span className="text-[13px] text-[#6B7280]">
              {filteredCars.length}{" "}
              {t("allCarsPage.carsCount", { defaultValue: "سيارة" })}
            </span>
          </div>
        </div>
      </section>

      {/* ── Cars Grid ── */}
      <section className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {pagedCars.length > 0 ? (
          <CarsResultsGrid
            cars={pagedCars}
            currentPage={safePage}
            totalPages={totalPages}
            onPageChange={(p) => {
              setCurrentPage(p);
              window.scrollTo({ top: 0, behavior: "smooth" });
            }}
          />
        ) : (
          <div className="py-24 text-center">
            <p className="text-lg font-medium text-gray-400">
              {t("allCarsPage.noCarsMatch")}
            </p>
          </div>
        )}
      </section>
    </main>
  );
}
