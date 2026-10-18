import { useMemo } from "react";
import { useParams, Link } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
import { getCars, getCarsMeta } from "../services/api/cars.service";
import { useSEO } from "../utils/useSEO";
import CarCard from "../components/CarCard";
import CarsGridSkeleton from "../components/skeletons/CarsGridSkeleton";
import type { ICarCardProps } from "../interfaces/ICarCardProps";
import type { ICarItem } from "../types/home.types";
import { formatPrice } from "../utils/format";
import { APP_IMAGES, getImageUrl } from "../constants/app-images";
import { ChevronRight, ChevronLeft, ShieldCheck, Calculator, Sparkles } from "lucide-react";

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
      fuelType: car.fuel_type || "",
      transmission: car.transmission || "",
      seats: car.seats || "",
      price: formatPrice(car.current_price ?? car.cash_price ?? 0, "var(--brand-primary-color)"),
      monthlyPrice: formatPrice(car.min_installment ?? 0, "var(--brand-secondary-color)"),
      detailsTo: `/cars/${slug}`,
      badgeColor: car.badge_color ?? undefined,
    };
  } catch {
    return null;
  }
}

export default function BrandLandingPage() {
  const { t, i18n } = useTranslation();
  const isRTL = i18n.dir() === "rtl";
  const { brandSlug } = useParams<{ brandSlug: string }>();

  // Fetch Meta to resolve brand name
  const { data: metaData } = useQuery({
    queryKey: ["cars-meta"],
    queryFn: () => getCarsMeta(),
    staleTime: 10 * 60 * 1000,
  });

  const currentBrand = useMemo(() => {
    if (!metaData?.filter_brands) return null;
    return metaData.filter_brands.find(
      (b) => b.slug === brandSlug || String(b.id) === brandSlug
    );
  }, [metaData, brandSlug]);

  const brandName = useMemo(() => {
    if (!currentBrand) {
      if (!brandSlug) return "السيارات";
      return brandSlug.replace(/-/g, " ").replace(/\b\w/g, (c) => c.toUpperCase());
    }
    if (typeof currentBrand.name === "object") {
      return (currentBrand.name as any)[i18n.language] || Object.values(currentBrand.name)[0];
    }
    return currentBrand.name;
  }, [currentBrand, brandSlug, i18n.language]);

  // Fetch cars for this brand
  const { data: carsResponse, isLoading } = useQuery({
    queryKey: ["brand-landing-cars", brandSlug, i18n.language],
    queryFn: () => getCars({ brands: [currentBrand?.id ?? brandSlug!], per_page: 24 }),
    enabled: !!brandSlug,
    staleTime: 5 * 60 * 1000,
  });

  const cars = useMemo(() => {
    if (!carsResponse?.data) return [];
    return carsResponse.data.map(mapCarToCardProps).filter(Boolean) as ICarCardProps[];
  }, [carsResponse]);

  const minInstallment = useMemo(() => {
    if (!carsResponse?.data || carsResponse.data.length === 0) return null;
    const installments = carsResponse.data
      .map((c) => c.min_installment)
      .filter((i): i is number => typeof i === "number" && i > 0);
    return installments.length > 0 ? Math.min(...installments) : null;
  }, [carsResponse]);

  const pageTitle = useMemo(() => {
    const minPart = minInstallment
      ? ` · أقساط تبدأ من ${Math.round(minInstallment).toLocaleString("en-US")} ريال`
      : "";
    return `سيارات ${brandName} بالتقسيط 2026${minPart}`;
  }, [brandName, minInstallment]);

  const pageDescription = useMemo(() => {
    return `تصفح أحدث عروض وموديلات سيارات ${brandName} بالتقسيط في السعودية من زاد كابيتال. احسب قسطك الشهري واستفد من عروض التمويل الميسرة بدون دفعة أولى.`;
  }, [brandName]);

  useSEO(pageTitle, pageDescription, currentBrand?.logo ? getImageUrl(currentBrand.logo) : undefined, undefined, true);

  return (
    <main dir={i18n.dir()} className="min-h-screen bg-[#F8FAFC] pb-20">
      {/* Hero Banner */}
      <section className="bg-gradient-to-b from-[#16254F] to-[#0F1E36] text-white py-14 sm:py-20 px-4 sm:px-6 lg:px-8">
        <div className="mx-auto max-w-7xl">
          {/* Breadcrumbs */}
          <nav className="mb-6 flex items-center gap-2 text-[13px] text-gray-300">
            <Link to="/" className="hover:text-white transition">الرئيسية</Link>
            {isRTL ? <ChevronLeft size={14} /> : <ChevronRight size={14} />}
            <Link to="/brands" className="hover:text-white transition">الماركات</Link>
            {isRTL ? <ChevronLeft size={14} /> : <ChevronRight size={14} />}
            <span className="text-[#EDC98E] font-bold">{brandName}</span>
          </nav>

          <div className="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div className="max-w-2xl text-start">
              <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[12px] font-black bg-[#EDC98E] text-[#16254F] mb-3 shadow-xs">
                <Sparkles size={13} />
                <span>عروض تمويل 2026</span>
              </span>
              <h1 className="text-[28px] sm:text-[42px] font-black leading-tight drop-shadow-sm">
                سيارات {brandName} بالتقسيط 2026
              </h1>
              <p className="mt-3 text-[14px] sm:text-[16px] text-gray-300 leading-relaxed">
                اكتشف كافة فئات وموديلات {brandName} المتاحة للتمويل الفوري عبر زاد كابيتال. أقل هامش ربح وأقساط مرنة تناسب دخلك الشهري.
              </p>
            </div>

            {currentBrand?.logo && (
              <div className="h-24 w-32 rounded-2xl bg-white/10 backdrop-blur-md p-3 flex items-center justify-center border border-white/20 shrink-0">
                <img
                  src={getImageUrl(currentBrand.logo)}
                  alt={brandName}
                  className="max-h-full max-w-full object-contain brightness-0 invert"
                />
              </div>
            )}
          </div>
        </div>
      </section>

      {/* Cars Grid */}
      <section className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 -mt-6">
        <div className="rounded-2xl bg-white p-6 shadow-sm border border-[#E7E9EF] mb-8 flex items-center justify-between">
          <p className="text-[14px] font-black text-[#16254F]">
            الموديلات المتاحة: <span className="text-[#EDC98E]">{cars.length}</span> سيارات
          </p>
          <Link
            to="/finance-calculator"
            className="inline-flex items-center gap-1.5 text-[13px] font-black text-[#16254F] hover:text-[#0F1E36] transition"
          >
            <Calculator size={15} />
            <span>احسب قسط سيارتك الآن</span>
          </Link>
        </div>

        {isLoading ? (
          <CarsGridSkeleton />
        ) : cars.length > 0 ? (
          <div className="grid grid-cols-1 gap-7 md:grid-cols-2 xl:grid-cols-3 justify-items-center">
            {cars.map((car) => (
              <CarCard key={car.id} {...car} />
            ))}
          </div>
        ) : (
          <div className="rounded-3xl bg-white p-12 text-center border border-gray-100 my-8">
            <p className="text-[16px] font-bold text-gray-400 mb-4">
              لا توجد موديلات معروضة حالياً لماركة {brandName}.
            </p>
            <Link
              to="/request-car"
              className="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#16254F] text-white text-[14px] font-black transition"
            >
              <span>طلب سيارة مخصصة</span>
            </Link>
          </div>
        )}
      </section>
    </main>
  );
}
