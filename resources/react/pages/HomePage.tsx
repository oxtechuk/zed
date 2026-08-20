import { useState, useMemo } from "react";
import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
import { APP_IMAGES, getImageUrl } from "../constants/app-images";
import HomeHero from "../components/HomeHero";
import CarFinder from "../components/CarFinder";
import FeaturedCarsSection from "../components/FeaturedCarsSection";
import BrandsSection from "../components/BrandsSection";
import BudgetCarsSection from "../components/BudgetCarsSection";
import { getHomePageData, getCars } from "../services/api";
import { useLanguageStore } from "../store/language.store";
import type { ICarItem, IBrandInfo } from "../types/home.types";
import type { ICarCardProps } from "../interfaces/ICarCardProps";
import type { CarFinderValues } from "../interfaces/ICarFinderProps";
import { formatPrice } from "../utils/format";
import { useSEO } from "../utils/useSEO";
import type { IBrandCardProps } from "../interfaces/IBrandCardProps";
import BrandsCarousel from "../components/BrandsCarousel";
import HeroSkeleton from "../components/skeletons/HeroSkeleton";
import BudgetCarsSkeleton from "../components/skeletons/BudgetCarsSkeleton";
import CarFinderSkeleton from "../components/skeletons/CarFinderSkeleton";
import BrandsCarouselSkeleton from "../components/skeletons/BrandsCarouselSkeleton";
import CarsGridSkeleton from "../components/skeletons/CarsGridSkeleton";
import LazySection from "../components/LazySection";
import { useEffect } from "react";

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
      badgeText: car.is_current_year ? String(car.year) : undefined,
      badgeColor: car.badge_color ?? undefined,
    };
  } catch {
    return null;
  }
}

function mapBrandToCardProps(brand: IBrandInfo): IBrandCardProps {
  return {
    id: brand.id,
    name: brand.name,
    logo: getImageUrl(brand.logo) || APP_IMAGES.BRAND_PLACEHOLDER,
  };
}

export default function Home() {
  const { t } = useTranslation();
  useSEO(t("nav.home"), t("hero.description"));
  const language = useLanguageStore((s) => s.language);
  const [filters, setFilters] = useState<CarFinderValues | null>(null);
  const [activeBudgetRange, setActiveBudgetRange] = useState("all");

  const { data, isLoading } = useQuery({
    queryKey: ["home-data", language],
    queryFn: getHomePageData,
  });

  const { data: filteredData } = useQuery({
    queryKey: ["filtered-cars", filters, language],
    queryFn: () =>
      getCars({
        ...(filters!.brandId && { brands: [Number(filters!.brandId)] }),
        ...(filters!.modelId && { model_id: Number(filters!.modelId) }),
        ...(filters!.typeId && { type: Number(filters!.typeId) }),
        ...(filters!.categoryId && { category_id: Number(filters!.categoryId) }),
        ...(filters!.year && { year: filters!.year }),
        ...(filters!.search && { search: filters!.search, q: filters!.search }),
        per_page: 12,
      }),
    enabled: !!filters,
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
        .filter(Boolean) as ICarCardProps[],
    [data?.featured_cars],
  );
  const brands = useMemo(
    () => (data?.brands ?? []).map(mapBrandToCardProps),
    [data?.brands],
  );
  const filteredCarCards = useMemo(
    () =>
      (filteredData?.data ?? [])
        .map(mapCarToCardProps)
        .filter(Boolean) as ICarCardProps[],
    [filteredData],
  );
  const filterBrands = data?.filter_brands ?? [];
  const filterTypes = data?.filter_types ?? [];
  const filterCategories = data?.filter_categories ?? [];
  const filterYears = data?.filter_years ?? [];

  const offerCarCards = useMemo(
    () =>
      (data?.offer_cars ?? [])
        .map(mapCarToCardProps)
        .filter(Boolean) as ICarCardProps[],
    [data?.offer_cars],
  );

  const budgetRangeItems = useMemo(
    () =>
      (data?.budget_ranges ?? []).map((range) => ({
        label: range.label,
        value: `${range.min}-${range.max ?? "plus"}`,
      })),
    [data],
  );

  // Preload primary hero slide image for optimal LCP performance without blocking UI render
  useEffect(() => {
    const firstHero = data?.hero_slides?.[0];
    if (firstHero) {
      const heroUrl = getImageUrl(firstHero.image || firstHero.image_mobile);
      if (heroUrl) {
        const link = document.createElement("link");
        link.rel = "preload";
        link.as = "image";
        link.href = heroUrl;
        document.head.appendChild(link);
        return () => {
          if (document.head.contains(link)) {
            document.head.removeChild(link);
          }
        };
      }
    }
  }, [data?.hero_slides]);

  const activeRangeValue = activeBudgetRange === "all" ? "all" : (budgetRangeItems.find((range) => range.value === activeBudgetRange)?.value ?? "all");

  const activeBudgetCars = useMemo(
    () => {
      if (activeRangeValue === "all") {
        const allCars: ICarItem[] = [];
        const seenIds = new Set<number>();
        (data?.budget_ranges ?? []).forEach((range) => {
          (range.cars ?? []).forEach((car) => {
            if (!seenIds.has(car.id)) {
              seenIds.add(car.id);
              allCars.push(car);
            }
          });
        });
        return allCars.map(mapCarToCardProps).filter(Boolean) as ICarCardProps[];
      }

      const range = (data?.budget_ranges ?? []).find(
        (r) => `${r.min}-${r.max ?? "plus"}` === activeRangeValue,
      );

      return (range?.cars ?? [])
        .map(mapCarToCardProps)
        .filter(Boolean) as ICarCardProps[];
    },
    [data, activeRangeValue],
  );

  return (
    <>
      {isLoading ? (
        <HeroSkeleton />
      ) : (
        <HomeHero slides={data?.hero_slides || []} />
      )}

      <LazySection
        fallback={<BudgetCarsSkeleton />}
        rootMargin="200px"
        minHeight={520}
      >
        {isLoading ? (
          <BudgetCarsSkeleton />
        ) : (
          <BudgetCarsSection
            titleBlue={data?.page_sections?.budget?.title?.trim() || t("budgetCars.titleBlue")}
            titleOrange=""
            description={data?.page_sections?.budget?.description?.trim() || t("budgetCars.description")}
            buttonText={data?.page_sections?.budget?.button_text?.trim() || t("budgetCars.buttonText")}
            buttonTo={data?.page_sections?.budget?.button_url || "/cars"}
            cars={activeBudgetCars}
            ranges={budgetRangeItems}
            activeRange={activeRangeValue}
            onRangeChange={setActiveBudgetRange}
          />
        )}
      </LazySection>

      <LazySection
        fallback={<CarFinderSkeleton />}
        rootMargin="200px"
        minHeight={120}
      >
        {isLoading ? (
          <CarFinderSkeleton />
        ) : (
          <CarFinder
            onSearch={handleCarFinderSearch}
            onReset={handleCarFinderReset}
            brands={filterBrands}
            types={filterTypes}
            categories={filterCategories}
            years={filterYears}
            filterTitle={data?.page_sections?.filter?.title?.trim()}
          />
        )}
      </LazySection>

      <LazySection
        fallback={<BrandsCarouselSkeleton />}
        rootMargin="250px"
        minHeight={120}
      >
        {isLoading ? (
          <BrandsCarouselSkeleton />
        ) : (
          <BrandsCarousel brands={brands} />
        )}
      </LazySection>

      <LazySection
        fallback={
          <section className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <CarsGridSkeleton />
          </section>
        }
        rootMargin="250px"
        minHeight={450}
      >
        {isLoading ? (
          <section className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <CarsGridSkeleton />
          </section>
        ) : (
          <FeaturedCarsSection
            titleBlue={data?.page_sections?.featured_cars?.title?.trim() || t("featuredCars.titleBlue")}
            titleOrange={data?.page_sections?.featured_cars?.badge?.trim() || ""}
            description=""
            buttonText={data?.page_sections?.featured_cars?.button_text?.trim() || t("featuredCars.buttonText")}
            buttonTo="/cars"
            cars={filters ? filteredCarCards : featuredCars}
            itemsPerPage={filters ? 4 : undefined}
            emptyMessage={filters ? t("featuredCars.noCars") : undefined}
          />
        )}
      </LazySection>

      {!isLoading && offerCarCards.length > 0 && (
        <LazySection
          fallback={
            <section className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
              <CarsGridSkeleton />
            </section>
          }
          rootMargin="250px"
          minHeight={450}
        >
          <FeaturedCarsSection
            titleBlue={data?.page_sections?.offers?.title?.trim() || t("offers.alsoTitle")}
            titleOrange=""
            description=""
            buttonText={data?.page_sections?.offers?.button_text?.trim() || t("offers.buttonText")}
            buttonTo={data?.page_sections?.offers?.button_url || "/offers"}
            cars={offerCarCards}
          />
        </LazySection>
      )}
    </>
  );
}
