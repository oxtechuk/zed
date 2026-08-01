import { useState, useMemo } from "react";
import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
import { APP_IMAGES, getImageUrl } from "../constants/app-images";
import HomeHero from "../components/HomeHero";
import CarFinder from "../components/CarFinder";
import FeaturedCarsSection from "../components/FeaturedCarsSection";
import OffersSection from "../components/OffersSection";
import CarsShowcaseSection from "../components/CarsShowcaseSection";
import FinanceSolutionsSection from "../components/FinanceSolutionsSection";
import BudgetCarsSection from "../components/BudgetCarsSection";
import BrandsSection from "../components/BrandsSection";
import { getHomePageData, getCars } from "../services/api";
import { useLanguageStore } from "../store/language.store";
import type { CarItem, BrandInfo, FilterPrice } from "../types/home.types";
import type { CarCardProps } from "../components/CarCard";
import type { CarFinderValues } from "../interfaces/ICarFinderProps";
import { formatPrice } from "../utils/format";
import { useSEO } from "../utils/useSEO";
import type { IOfferCardProps } from "../interfaces/IOfferCardProps";
import type { IBrandCardProps } from "../interfaces/IBrandCardProps";
import type { IBudgetRange } from "../interfaces/IBudgetCarsSectionProps";

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
      badgeText: car.is_current_year ? String(car.year) : undefined,
    };
  } catch {
    return null;
  }
}

function mapBrandToCardProps(brand: BrandInfo): IBrandCardProps {
  return {
    id: brand.id,
    name: brand.name,
    logo: getImageUrl(brand.logo) || APP_IMAGES.BRAND_PLACEHOLDER,
  };
}

function formatPriceValue(price: number): string {
  return price.toLocaleString();
}

function mapFilterPricesToRanges(prices?: FilterPrice[]): IBudgetRange[] | undefined {
  if (!prices?.length) return undefined;
  return prices.map((p) => {
    const label = p.max == null
      ? `${formatPriceValue(p.min)}+`
      : `${formatPriceValue(p.min)} - ${formatPriceValue(p.max)}`;
    const value = p.max == null ? `${p.min}-plus` : `${p.min}-${p.max}`;
    return { label, value };
  });
}

const getFallbackOffers = (t: (key: string) => string): IOfferCardProps[] => [
  {
    image: APP_IMAGES.OFFER1,
    title: "عرض اليوم الوطني السعودي",
    description: "2026",
    buttonText: t("homePage.fallbackOfferButton"),
    buttonTo: "/cars?offerId=1",
  },
  {
    image: APP_IMAGES.OFFER1,
    title: "تمويل سيارات ميسر وبدون دفعة أولى",
    description: "2026",
    buttonText: t("homePage.fallbackOfferButton"),
    buttonTo: "/cars?offerId=2",
  },
  {
    image: APP_IMAGES.OFFER1,
    title: "عرض استيراد السيارات الفاخرة",
    description: "2026",
    buttonText: t("homePage.fallbackOfferButton"),
    buttonTo: "/cars?offerId=3",
  },
];

export default function Home() {
  const { t } = useTranslation();
  useSEO(t("nav.home"), t("hero.description"));
  const language = useLanguageStore((s) => s.language);
  const [filters, setFilters] = useState<CarFinderValues | null>(null);
  const [activeBudgetRange, setActiveBudgetRange] = useState<string | null>(null);

  function parseRangeValue(value: string): { min_price?: number; max_price?: number } {
    if (value.endsWith("-plus")) {
      const min = Number(value.replace("-plus", ""));
      return { min_price: min };
    }
    const parts = value.split("-");
    if (parts.length === 2) {
      return { min_price: Number(parts[0]), max_price: Number(parts[1]) };
    }
    return {};
  }

  const { data, isLoading } = useQuery({
    queryKey: ["home-data", language],
    queryFn: getHomePageData,
  });

  const { data: filteredData } = useQuery({
    queryKey: ["filtered-cars", filters, language],
    queryFn: () =>
      getCars({
        ...(filters!.brandId && { brands: [Number(filters!.brandId)] }),
        ...(filters!.typeId && { type: Number(filters!.typeId) }),
        ...(filters!.categoryId && { category_id: Number(filters!.categoryId) }),
        ...(filters!.year && { year: filters!.year }),
        ...(filters!.search && { search: filters!.search, q: filters!.search }),
        per_page: 12,
      }),
    enabled: !!filters,
  });

  const { data: budgetFilteredData } = useQuery({
    queryKey: ["budget-cars", activeBudgetRange, language],
    queryFn: () =>
      getCars({
        ...parseRangeValue(activeBudgetRange!),
        per_page: 12,
      }),
    enabled: !!activeBudgetRange,
  });

  const handleCarFinderSearch = (values: CarFinderValues) => {
    setFilters(values);
  };

  const handleCarFinderReset = () => {
    setFilters(null);
  };

  const featuredCars = useMemo(
    () =>
      (data?.featured_cars ?? [])
        .map(mapCarToCardProps)
        .filter(Boolean) as CarCardProps[],
    [data?.featured_cars],
  );
  const showcaseCars = useMemo(
    () =>
      (data?.highlighted_cars ?? [])
        .map(mapCarToCardProps)
        .filter(Boolean) as CarCardProps[],
    [data?.highlighted_cars],
  );
  const budgetCars = useMemo(
    () =>
      (data?.bento_cars ?? [])
        .map(mapCarToCardProps)
        .filter(Boolean) as CarCardProps[],
    [data?.bento_cars],
  );
  const budgetFilteredCars = useMemo(
    () =>
      (budgetFilteredData?.data ?? [])
        .map(mapCarToCardProps)
        .filter(Boolean) as CarCardProps[],
    [budgetFilteredData],
  );
  const brands = useMemo(
    () => (data?.brands ?? []).map(mapBrandToCardProps),
    [data?.brands],
  );
  const filteredCarCards = useMemo(
    () =>
      (filteredData?.data ?? [])
        .map(mapCarToCardProps)
        .filter(Boolean) as CarCardProps[],
    [filteredData],
  );
  const filterBrands = data?.filter_brands ?? [];
  const filterTypes = data?.filter_types ?? [];
  const filterCategories = data?.filter_categories ?? [];
  const filterYears = data?.filter_years ?? [];

  if (isLoading) {
    return (
      <div className="flex min-h-[400px] items-center justify-center">
        <div className="h-10 w-10 animate-spin rounded-full border-4 border-[var(--brand-primary-color)] border-t-transparent" />
      </div>
    );
  }

  return (
    <>
      <HomeHero
        slides={data?.hero_slides || []}
        promoCards={data?.promo_cards || []}
      />

      <CarFinder
        onSearch={handleCarFinderSearch}
        onReset={handleCarFinderReset}
        brands={filterBrands}
        types={filterTypes}
        categories={filterCategories}
        years={filterYears}
        filterTitle={data?.page_sections?.filter?.title?.trim()}
      />

      <BrandsSection
        titleBlue=""
        titleOrange=""
        description=""
        buttonText=""
        buttonTo=""
        brands={brands}
        simple={true}
      />

      <FeaturedCarsSection
        titleBlue={data?.page_sections?.featured_cars?.title?.trim() || t("featuredCars.titleOrange")}
        titleOrange={data?.page_sections?.featured_cars?.badge?.trim() || t("featuredCars.titleBlue")}
        description={data?.page_sections?.featured_cars?.subtitle?.trim() || t("featuredCars.description")}
        buttonText={data?.page_sections?.featured_cars?.button_text?.trim() || t("featuredCars.buttonText")}
        buttonTo="/cars"
        cars={filters ? filteredCarCards : featuredCars}
        itemsPerPage={filters ? 4 : undefined}
        emptyMessage={filters ? t("featuredCars.noCars") : undefined}
      />

      <OffersSection
        backgroundImage={APP_IMAGES.BG_IMAGE}
        titleWhite={data?.page_sections?.offers?.badge?.trim() || t("offers.titleWhite")}
        titleOrange={data?.page_sections?.offers?.title?.trim() || t("offers.titleOrange")}
        buttonText={data?.page_sections?.offers?.button_text?.trim() || t("offers.buttonText")}
        buttonTo="/offers"
        offers={
          data?.active_offers?.length
            ? data.active_offers.map((o) => ({
                image: getImageUrl(o.image) || APP_IMAGES.OFFER1,
                title: o.title,
                description: o.description || "2026",
                buttonText: t("homePage.fallbackOfferButton"),
                buttonTo: `/cars?offerId=${o.id}`,
              }))
            : getFallbackOffers(t)
        }
      />

      <CarsShowcaseSection
        titleBlue={data?.page_sections?.highlighted_cars?.badge?.trim() || t("carsShowcase.titleBlue")}
        titleOrange={data?.page_sections?.highlighted_cars?.title?.trim() || t("carsShowcase.titleOrange")}
        description={data?.page_sections?.highlighted_cars?.subtitle?.trim() || t("carsShowcase.description")}
        buttonText={data?.page_sections?.highlighted_cars?.button_text?.trim() || t("carsShowcase.buttonText")}
        buttonTo="/cars"
        cars={showcaseCars}
      />

      <BudgetCarsSection
        titleBlue={data?.page_sections?.budget?.badge?.trim() || t("budgetCars.titleBlue")}
        titleOrange={data?.page_sections?.budget?.title?.trim() || t("budgetCars.titleOrange")}
        description={data?.page_sections?.budget?.description?.trim() || t("budgetCars.description")}
        buttonText={data?.page_sections?.budget?.button_text?.trim() || t("budgetCars.buttonText")}
        buttonTo="/cars"
        cars={activeBudgetRange ? budgetFilteredCars : budgetCars}
        activeRange={activeBudgetRange ?? undefined}
        onRangeChange={(value) =>
          setActiveBudgetRange((prev) => (prev === value ? null : value))
        }
        ranges={mapFilterPricesToRanges(data?.filter_prices)}
      />

      <FinanceSolutionsSection
        titleBlue=""
        titleOrange={data?.page_sections?.finance?.title?.trim() || t("financeSolutions.titleOrange")}
        description=""
        buttonText=""
        buttonTo=""
        stats={[]}
        features={[]}
        steps={data?.finance_steps || []}
      />
    </>
  );
}
