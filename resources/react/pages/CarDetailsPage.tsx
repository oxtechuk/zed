import { useMemo } from "react";
import { useParams } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
import { useLanguageStore } from "../store/language.store";
import CarDetailsHero from "../components/car-details/CarDetailsHero";
import CarDetailsSpecs from "../components/car-details/CarDetailsSpecs";
import FeaturedCarsSection from "../components/FeaturedCarsSection";
import FinanceSolutionsSection from "../components/FinanceSolutionsSection";
import { getCarBySlug } from "../services/api/cars.service";
import { getFinanceSettings } from "../services/api";
import { getImageUrl, APP_IMAGES } from "../constants/app-images";
import { formatPrice } from "../utils/format";
import { useSEO } from "../utils/useSEO";
import type { ICarItem } from "../types/home.types";
import type { ICarCardProps } from "../interfaces/ICarCardProps";

function specValue(car: ICarItem, key: string, altKey?: string): string {
  if (altKey) {
    const v = (car as any)[altKey];
    if (v != null && typeof v === "string") return v;
  }
  if (typeof car.specs === "object" && !Array.isArray(car.specs)) {
    const v = (car.specs as Record<string, unknown>)[key];
    if (v != null && typeof v === "string") return v;
  }
  return "";
}

function mapRelatedCar(car: ICarItem): ICarCardProps | null {
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
      fuelType: specValue(car, "fuel", "fuel_type"),
      transmission: specValue(car, "gearbox", "transmission"),
      seats: specValue(car, "seats"),
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

export default function CarDetailsPage() {
  const { t } = useTranslation();
  useSEO(t("nav.cars"), t("carDetails.hero.metaDescription"));
  const language = useLanguageStore((s) => s.language);
  const { slug } = useParams<{ slug: string }>();

  const { data: car, isLoading, isError } = useQuery({
    queryKey: ["car-details", slug, language],
    queryFn: () => getCarBySlug(slug!),
    enabled: !!slug,
    retry: 1,
  });

  const { data: financeData } = useQuery({
    queryKey: ["finance-solution", language],
    queryFn: getFinanceSettings,
    staleTime: 5 * 60 * 1000,
  });


  const relatedCars = useMemo(
    () =>
      car?.related_cars
        ?.map(mapRelatedCar)
        .filter((c): c is ICarCardProps => c !== null) ?? [],
    [car?.related_cars],
  );

  if (isLoading) {
    return (
      <div className="flex items-center justify-center py-20 text-xl font-bold text-gray-500">
        {t("carDetails.page.loading")}
      </div>
    );
  }

  if (isError) {
    return (
      <div className="flex items-center justify-center py-20 text-xl font-bold text-red-500">
        {t("carDetails.page.error")}
      </div>
    );
  }

  if (!car) {
    return (
      <div className="flex items-center justify-center py-20 text-xl font-bold text-gray-500">
        {t("carDetails.page.notFound")}
      </div>
    );
  }

  const saving = car.cash_price - car.current_price;

  return (
    <>
      <script type="application/ld+json">
        {JSON.stringify({
          "@context": "https://schema.org",
          "@type": "Product",
          name: car.name,
          description: car.description?.replace(/<[^>]*>/g, "").slice(0, 200),
          image: getImageUrl(car.main_image),
          brand: { "@type": "Brand", name: car.brand?.name },
          offers: {
            "@type": "Offer",
            price: car.current_price,
            priceCurrency: "SAR",
            availability: "https://schema.org/InStock",
          },
        })}
      </script>
      <CarDetailsHero
        id={car.id}
        title={car.name}
        description={car.description}
        mainImage={car.main_image}
        images={car.images ?? []}
        exteriorImages={car.exterior_images}
        interiorImages={car.interior_images}
        price={car.current_price}
        oldPrice={saving > 0 ? car.cash_price : undefined}
        monthlyInstallment={car.min_installment}
        savingAmount={saving > 0 ? saving : undefined}
        colors={(car.colors ?? []).map((c) => ({ name: c.name, value: c.hex, image: c.image }))}
        orderTo="/contact"
        financeTo="/finance-calculator"
        fuelType={car.specs?.fuel || car.specs?.fuel_type || undefined}
        transmission={car.specs?.gearbox || car.specs?.transmission || undefined}
        seats={car.specs?.seats || undefined}
        horsepower={car.specs?.hp || car.specs?.power || undefined}
        type={car.type || undefined}
        year={car.year || undefined}
        brandName={car.brand?.name || undefined}
      />
      {/* Specifications & Features Grid */}
      <section className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 lg:px-8 ">
        <div className="grid grid-cols-1 gap-10 lg:grid-cols-12">
          {/* Specifications Card (aligns with Gallery column width) */}
          <div className="lg:col-span-8">
            <CarDetailsSpecs
              showOnly="specs"
              specifications={car.specifications}
              specs={car.specs}
              type={car.type}
              year={car.year}
              availabilityStatus={car.availability_status}
            />
          </div>

          {/* Features List (aligns with Sidebar Calculator width) */}
          <div className="lg:col-span-4">
            <CarDetailsSpecs
              showOnly="features"
              featuresList={car.features_list}
              safetyFeatures={car.safety_features}
              specs={car.specs}
            />
          </div>
        </div>
      </section>

      {relatedCars.length > 0 && (
        <FeaturedCarsSection
          titleBlue={t("carDetails.relatedCars.titleBlue")}
          titleOrange={t("carDetails.relatedCars.titleOrange")}
          description={t("carDetails.relatedCars.description")}
          buttonText={t("carDetails.relatedCars.buttonText")}
          buttonTo="/cars"
          cars={relatedCars}
          itemsPerPage={3}
        />
      )}


    </>
  );
}
