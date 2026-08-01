import { useState, useCallback } from "react";
import { useTranslation } from "react-i18next";
import {
  ArrowLeft,
  ArrowRight,
  Users,
  Scale,
  MessageCircle,
  Phone,
} from "lucide-react";
import { getImageUrl } from "../../constants/app-images";
import { formatPrice } from "../../utils/format";
import type { ICarColor, ICarDetailsHeroProps } from "../../interfaces/ICarDetailsHeroProps";

export default function CarDetailsHero({
  title,
  description,
  images,
  exteriorImages,
  interiorImages,
  price,
  monthlyInstallment,
  fuelType,
  transmission,
  seats,
  horsepower,
  type,
  year,
  brandName,
}: ICarDetailsHeroProps) {
  const { t, i18n } = useTranslation();
  const [activeImage, setActiveImage] = useState(0);
  const [viewType, setViewType] = useState<"inside" | "outside">("inside");
  const [selectedColor, setSelectedColor] = useState<ICarColor | null>(null);
  
  // Interactive month term state
  const [selectedMonth, setSelectedMonth] = useState(60);

  const currentImages =
    viewType === "inside"
      ? interiorImages?.length
        ? interiorImages.map(getImageUrl)
        : images.map(getImageUrl)
      : exteriorImages?.length
        ? exteriorImages.map(getImageUrl)
        : images.map(getImageUrl);

  const colorImage = selectedColor?.image ? getImageUrl(selectedColor.image) : null;
  const currentImage = colorImage ?? currentImages[activeImage];
  const totalImages = currentImages.length;

  const handleViewChange = useCallback((view: "inside" | "outside") => {
    setViewType(view);
    setActiveImage(0);
    setSelectedColor(null);
  }, []);

  const handleNext = () => {
    setActiveImage((prev) => (prev === totalImages - 1 ? 0 : prev + 1));
  };

  const handlePrev = () => {
    setActiveImage((prev) => (prev === 0 ? totalImages - 1 : prev - 1));
  };

  // Dynamic installment calculation based on base min_installment (assumed at 60 months)
  const calculatedInstallment = Math.round((monthlyInstallment * 60) / selectedMonth);

  return (
    <div className="w-full flex flex-col">
      {/* Breadcrumb Bar */}
      <div className="w-full bg-[#0F172A] py-3.5 text-white text-[13px] border-b border-white/5">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-start flex items-center gap-2">
          <a href="/" className="opacity-60 hover:opacity-100 hover:text-[#EDC98E] transition-colors">
            {t("nav.home")}
          </a>
          <span className="opacity-30">/</span>
          <a href="/cars" className="opacity-60 hover:opacity-100 hover:text-[#EDC98E] transition-colors">
            {t("nav.cars")}
          </a>
          <span className="opacity-30">/</span>
          <span className="font-bold opacity-90 truncate">
            {brandName} {title}
          </span>
        </div>
      </div>

      {/* Main Details Section */}
      <section dir={i18n.dir()} className="w-full bg-[#F3F4F6] py-10 md:py-16">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          {/* Desktop 2-column layout (Gallery on right, Sidebar on left) */}
          <div className="grid grid-cols-1 items-start gap-10 lg:grid-cols-12">
            
            {/* 1. Gallery Side (Renders first on mobile, right on desktop) */}
            <div className="lg:col-span-7 flex flex-col order-1">
              
              {/* Inside/Outside toggles */}
              <div className="mb-5 grid h-[56px] grid-cols-2 rounded-xl bg-white p-1.5 border border-[#E5E9F0] shadow-sm">
                <button
                  type="button"
                  onClick={() => handleViewChange("inside")}
                  className={`rounded-lg text-[15px] font-bold transition-all duration-300 ${
                    viewType === "inside"
                      ? "bg-[#EAF1FA] text-[#0F172A]"
                      : "text-[#5F6672]"
                  }`}
                >
                  {t("carDetails.hero.insideView")}
                </button>
                <button
                  type="button"
                  onClick={() => handleViewChange("outside")}
                  className={`rounded-lg text-[15px] font-bold transition-all duration-300 ${
                    viewType === "outside"
                      ? "bg-[#EAF1FA] text-[#0F172A]"
                      : "text-[#5F6672]"
                  }`}
                >
                  {t("carDetails.hero.outsideView")}
                </button>
              </div>

              {/* Main Image View */}
              <div className="relative overflow-hidden rounded-[24px] bg-white border border-[#E5E9F0] shadow-sm flex items-center justify-center p-6 min-h-[340px] md:min-h-[440px]">
                <img
                  src={currentImage}
                  alt={title}
                  className="max-h-[300px] md:max-h-[400px] w-auto object-contain transition-all duration-500"
                  loading="lazy"
                />

                {/* Left/Right Overlays */}
                {totalImages > 1 && !colorImage && (
                  <>
                    <button
                      type="button"
                      onClick={handlePrev}
                      className="absolute left-4 top-1/2 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/20 backdrop-blur-md text-[#0F172A] border border-white/30 transition hover:bg-white/40 active:scale-95"
                    >
                      <ArrowLeft size={18} />
                    </button>
                    <button
                      type="button"
                      onClick={handleNext}
                      className="absolute right-4 top-1/2 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/20 backdrop-blur-md text-[#0F172A] border border-white/30 transition hover:bg-white/40 active:scale-95"
                    >
                      <ArrowRight size={18} />
                    </button>
                  </>
                )}
              </div>

              {/* Thumbnails Row */}
              {totalImages > 1 && !colorImage && (
                <div className="mt-4 flex flex-wrap justify-center gap-3">
                  {currentImages.slice(0, 5).map((image, index) => (
                    <button
                      key={index}
                      type="button"
                      onClick={() => setActiveImage(index)}
                      className={`h-16 w-20 overflow-hidden rounded-xl border bg-white p-1 transition-all duration-300 ${
                        activeImage === index
                          ? "border-[#EDC98E] ring-2 ring-[#EDC98E]/20 scale-105"
                          : "border-[#E5E9F0] hover:border-gray-400"
                      }`}
                    >
                      <img
                        src={image}
                        className="h-full w-full object-contain rounded-lg"
                        alt={`${title} thumb ${index}`}
                      />
                    </button>
                  ))}
                </div>
              )}

              {/* Category Badge */}
              {type && (
                <div className="mt-8 flex justify-start">
                  <span className="inline-block bg-white border border-[#E5E9F0] text-[#64748B] text-[12px] font-bold px-3 py-1.5 rounded-lg">
                    {type}
                  </span>
                </div>
              )}

              {/* Brand and Model Year Small Row */}
              <p className="mt-4 text-start text-[13px] font-extrabold text-[#EDC98E] uppercase tracking-wider leading-none">
                {brandName} - {year}
              </p>

              {/* Car Title */}
              <h1 className="mt-2.5 text-start text-[28px] font-black leading-tight text-[#0F172A] md:text-[38px]">
                {title}
              </h1>

              {/* Badges Row */}
              <div className="mt-4 flex flex-wrap gap-2.5">
                {fuelType && (
                  <span className="inline-flex items-center bg-white border border-[#E5E9F0] text-[#475569] text-[13px] font-extrabold px-3.5 py-2 rounded-xl shadow-xs">
                    {fuelType}
                  </span>
                )}
                {transmission && (
                  <span className="inline-flex items-center bg-white border border-[#E5E9F0] text-[#475569] text-[13px] font-extrabold px-3.5 py-2 rounded-xl shadow-xs">
                    {transmission}
                  </span>
                )}
                {seats && (
                  <span className="inline-flex items-center gap-1.5 bg-white border border-[#E5E9F0] text-[#475569] text-[13px] font-extrabold px-3.5 py-2 rounded-xl shadow-xs">
                    <Users size={14} className="text-[#94A3B8]" />
                    <span>{seats}</span>
                  </span>
                )}
                {horsepower && (
                  <span className="inline-flex items-center bg-white border border-[#E5E9F0] text-[#475569] text-[13px] font-extrabold px-3.5 py-2 rounded-xl shadow-xs">
                    {horsepower}
                  </span>
                )}
              </div>

              {/* Description Paragraph */}
              {description && (
                <p className="mt-6 text-start text-[16px] leading-[1.8] text-[#475569] font-medium">
                  {description}
                </p>
              )}
            </div>

            {/* 2. Sidebar Calculator Side (Renders second on mobile, left on desktop) */}
            <div className="lg:col-span-5 flex flex-col order-2 lg:sticky lg:top-8">
              
              {/* Main Finance Calculator Card */}
              <div className="rounded-[24px] bg-[#0F172A] p-7 text-white shadow-xl relative overflow-hidden">
                <div className="absolute top-0 right-0 w-48 h-48 bg-[#EDC98E]/5 blur-[70px] rounded-full pointer-events-none" />

                {/* Cash Price Display */}
                <div className="text-start">
                  <span className="text-[13px] text-white/50 font-bold block mb-1">
                    {t("carCard.cashPrice")}
                  </span>
                  <strong className="text-[32px] font-black leading-none text-white tracking-tight">
                    {formatPrice(price, "white")}
                  </strong>
                </div>

                {/* Term months selection */}
                <div className="mt-7 text-start">
                  <span className="text-[13px] text-white/50 font-bold block mb-3.5">
                    {t("carDetails.calculator.monthsTerm", { defaultValue: "مدة التمويل بالأشهر" })}
                  </span>
                  <div className="flex gap-2.5">
                    {[60, 48, 36, 24, 12].map((month) => (
                      <button
                        key={month}
                        type="button"
                        onClick={() => setSelectedMonth(month)}
                        className={`flex h-10 w-12 items-center justify-center rounded-xl text-[14px] font-extrabold transition-all duration-300 ${
                          selectedMonth === month
                            ? "bg-[#EDC98E] text-[#0F172A] scale-105 shadow-md"
                            : "bg-[#1E293B] hover:bg-[#2A3B56] text-white"
                        }`}
                      >
                        {month}
                      </button>
                    ))}
                  </div>
                </div>

                {/* Dynamic Monthly Installment */}
                <div className="mt-8 text-start border-t border-white/5 pt-6">
                  <span className="text-[13px] text-white/50 font-bold block mb-2">
                    {t("carDetails.calculator.monthlyInstallment", { defaultValue: "القسط الشهري المقدر" })}
                  </span>
                  <div className="text-[26px] font-black text-[#EDC98E] leading-none tracking-tight">
                    {formatPrice(calculatedInstallment, "#EDC98E")}
                    <span className="text-[13px] text-white/50 font-extrabold ms-2">
                      / شهر - معدل أرباح 4.9%
                    </span>
                  </div>
                </div>

                {/* Apply buttons */}
                <a
                  href={`/contact?car=${encodeURIComponent(brandName + " " + title)}&installment=${calculatedInstallment}&term=${selectedMonth}`}
                  className="mt-6 flex h-[52px] w-full items-center justify-center rounded-xl bg-[#EDC98E] text-[15px] font-extrabold text-[#0F172A] transition-all duration-300 hover:scale-[1.01] hover:shadow-[0_12px_25px_rgba(237,201,142,0.15)] active:scale-95"
                >
                  {t("carDetails.calculator.submit", { defaultValue: "قدم طلب التمويل الآن" })}
                </a>

                {/* Social Actions Row */}
                <div className="mt-3.5 grid grid-cols-2 gap-3">
                  <a
                    href={`https://wa.me/966500000000?text=أرغب في الاستفسار عن سيارة ${brandName} ${title}`}
                    target="_blank"
                    rel="noreferrer"
                    className="flex h-[46px] items-center justify-center gap-2 rounded-xl bg-[#25D366] text-[14px] font-bold text-white transition hover:bg-[#20ba59] hover:scale-[1.01] active:scale-95"
                  >
                    <MessageCircle size={16} />
                    <span>واتساب</span>
                  </a>
                  <a
                    href="tel:+966500000000"
                    className="flex h-[46px] items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 text-[14px] font-bold text-white transition hover:bg-white/10 hover:scale-[1.01] active:scale-95"
                  >
                    <Phone size={16} />
                    <span>اتصل</span>
                  </a>
                </div>
              </div>

              {/* Compare Card (Cream layout) */}
              <a
                href={`/compare?car1=${encodeURIComponent(title)}`}
                className="mt-4 flex items-center justify-between rounded-[20px] bg-[#FFF4E4] p-5 border border-[#FBEFDF] text-[#92400E] transition-all duration-300 hover:scale-[1.01] hover:shadow-xs group"
              >
                <div className="flex items-center gap-3">
                  <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-white/60 text-[#92400E]">
                    <Scale size={18} />
                  </div>
                  <span className="text-[15px] font-extrabold text-[#92400E]">
                    {t("carDetails.hero.compareWithOther", { defaultValue: "قارن مع سيارة أخرى" })}
                  </span>
                </div>
                <ArrowLeft size={18} className="text-[#92400E] transition-transform duration-300 group-hover:-translate-x-1" />
              </a>

              {/* Disclaimer */}
              <p className="mt-4 text-start text-[11px] leading-relaxed text-[#94A3B8] font-semibold px-2">
                * الأرقام تقديرية بمعدل 4.9% سنوياً. التمويل يخضع لشروط الموافقة لبنك العميل. للحصول على عرض نهائي، تواصل مع فريقنا.
              </p>
            </div>

          </div>
        </div>
      </section>
    </div>
  );
}