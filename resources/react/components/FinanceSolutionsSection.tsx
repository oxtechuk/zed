import { useState, useEffect, useRef, useCallback } from "react";
import { useTranslation } from "react-i18next";
import { Link } from "react-router-dom";
import {
  Calculator,
  Car,
  Landmark,
  ShieldCheck,
  SlidersHorizontal,
  Headphones,
  ChevronLeft,
  ChevronRight,
} from "lucide-react";
import { useSettingsStore } from "../store/settings.store";
import type {
  IFinanceSolutionsSectionProps,
  IFinanceSlideItem,
} from "../interfaces/IFinanceSolutionsSectionProps";

const DEFAULT_SLIDES: IFinanceSlideItem[] = [
  {
    id: 1,
    badge: "جميع السيارات موديل 2026",
    installmentLabel: "قسط يبدأ من",
    installmentAmount: "1,200",
    installmentPeriod: "ريال شهرياً",
    title: "سيارتك الجديدة",
    titleHighlight: "أقرب مما تتوقع",
    description:
      "احسب قسطك واختر السيارة المناسبة لراتبك وقدّم طلبك إلكترونياً بسهولة.",
    primaryBtnText: "احسب قسطك الآن",
    primaryBtnUrl: "/finance-calculator",
    secondaryBtnText: "تصفح السيارات",
    secondaryBtnUrl: "/cars",
    image: "/images/finance-banner-cars.jpg",
    features: [
      {
        icon: "landmark",
        text: "شراكات مع أشهر جهات التمويل",
      },
      {
        icon: "shield",
        text: "تقديم رقمي سريع وآمن",
      },
      {
        icon: "sliders",
        text: "خيارات متعددة تناسب راتبك",
      },
      {
        icon: "headphones",
        text: "متابعة طلبك بسهولة",
      },
    ],
  },
  {
    id: 2,
    badge: "عروض تمويل حصرية 0% دفعة أولى",
    installmentLabel: "قسط يبدأ من",
    installmentAmount: "999",
    installmentPeriod: "ريال شهرياً",
    title: "تمويل سيارتك",
    titleHighlight: "بأقل هامش ربح",
    description:
      "حلول تمويلية مرنة متوافقة مع الشريعة الإسلامية بالتعاون مع كبرى البنوك والجهات التمويلية في المملكة.",
    primaryBtnText: "احسب قسطك الآن",
    primaryBtnUrl: "/finance-calculator",
    secondaryBtnText: "استكشف العروض",
    secondaryBtnUrl: "/offers",
    image: "/images/finance-banner-cars-2.jpg",
    features: [
      {
        icon: "landmark",
        text: "معتمد من الهيئة الشرعية",
      },
      {
        icon: "shield",
        text: "موافقة فورية بدون تحويل راتب",
      },
      {
        icon: "sliders",
        text: "فترات سداد مرنة تصل لـ 5 سنوات",
      },
      {
        icon: "headphones",
        text: "استشارات تمويلية مجانية",
      },
    ],
  },
];

function renderFeatureIcon(iconName?: string) {
  switch (iconName) {
    case "landmark":
      return <Landmark size={20} className="text-[#EDC98E] shrink-0" />;
    case "shield":
      return <ShieldCheck size={20} className="text-[#EDC98E] shrink-0" />;
    case "sliders":
      return <SlidersHorizontal size={20} className="text-[#EDC98E] shrink-0" />;
    case "headphones":
    default:
      return <Headphones size={20} className="text-[#EDC98E] shrink-0" />;
  }
}

export default function FinanceSolutionsSection({
  titleOrange,
  titleBlue,
  description,
  buttonText,
  buttonTo,
  slides: propSlides,
  banner,
  className = "",
}: IFinanceSolutionsSectionProps) {
  const { t, i18n } = useTranslation();
  const direction = i18n.dir();
  const isRtl = direction === "rtl";

  // Build active slides list with support for settings/props
  const slides: IFinanceSlideItem[] = (propSlides && propSlides.length > 0)
    ? propSlides
    : DEFAULT_SLIDES.map((slide, idx) => {
        if (idx === 0 && (titleOrange || titleBlue || description || buttonText)) {
          return {
            ...slide,
            title: titleBlue || slide.title,
            titleHighlight: titleOrange || slide.titleHighlight,
            description: description || slide.description,
            primaryBtnText: buttonText || slide.primaryBtnText,
            primaryBtnUrl: buttonTo || slide.primaryBtnUrl,
            image: banner?.image || banner?.background_image || slide.image,
          };
        }
        return slide;
      });

  const [currentIndex, setCurrentIndex] = useState(0);
  const [isHovered, setIsHovered] = useState(false);
  const touchStartX = useRef<number | null>(null);
  const touchEndX = useRef<number | null>(null);

  const totalSlides = slides.length;

  const nextSlide = useCallback(() => {
    setCurrentIndex((prev) => (prev + 1) % totalSlides);
  }, [totalSlides]);

  const prevSlide = useCallback(() => {
    setCurrentIndex((prev) => (prev - 1 + totalSlides) % totalSlides);
  }, [totalSlides]);

  // Auto-play timer (6 seconds), pauses on hover
  useEffect(() => {
    if (totalSlides <= 1 || isHovered) return;
    const interval = setInterval(nextSlide, 6000);
    return () => clearInterval(interval);
  }, [totalSlides, isHovered, nextSlide]);

  // Touch swipe handling
  const handleTouchStart = (e: React.TouchEvent) => {
    touchStartX.current = e.touches[0].clientX;
  };

  const handleTouchMove = (e: React.TouchEvent) => {
    touchEndX.current = e.touches[0].clientX;
  };

  const handleTouchEnd = () => {
    if (!touchStartX.current || !touchEndX.current) return;
    const diff = touchStartX.current - touchEndX.current;
    if (Math.abs(diff) > 50) {
      if (diff > 0) {
        // Swiped left
        if (isRtl) prevSlide();
        else nextSlide();
      } else {
        // Swiped right
        if (isRtl) nextSlide();
        else prevSlide();
      }
    }
    touchStartX.current = null;
    touchEndX.current = null;
  };

  const currentSlide = slides[currentIndex] || slides[0];

  return (
    <section
      dir={direction}
      className={`w-full py-8 md:py-12 ${className}`}
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
      onTouchStart={handleTouchStart}
      onTouchMove={handleTouchMove}
      onTouchEnd={handleTouchEnd}
    >
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {/* Main Banner Card */}
        <div className="relative overflow-hidden rounded-[24px] sm:rounded-[32px] bg-[#080E1E] text-white border border-white/10 shadow-2xl transition-all duration-500">
          {/* Ambient Lighting Background Accents */}
          <div className="pointer-events-none absolute -top-24 end-0 h-96 w-96 rounded-full bg-[#EDC98E]/10 blur-[110px]" />
          <div className="pointer-events-none absolute -bottom-24 start-0 h-96 w-96 rounded-full bg-[#1E40AF]/20 blur-[130px]" />
          <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(237,201,142,0.06),transparent_60%)]" />

          {/* Banner Main Grid */}
          <div className="relative z-10 p-6 sm:p-10 lg:p-14">
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">
              {/* ── Content Column (Right in RTL, Left in LTR) ── */}
              <div className="lg:col-span-6 flex flex-col items-start text-start order-2 lg:order-1">
                {/* Main Heading with Gold Accent */}
                <h2 className="text-[32px] sm:text-[42px] lg:text-[48px] font-black leading-[1.2] text-white tracking-tight">
                  <span>{currentSlide.title || "سيارتك الجديدة"}</span>
                  {currentSlide.titleHighlight && (
                    <span className="block text-[#EDC98E] mt-1 drop-shadow-sm">
                      {currentSlide.titleHighlight}
                    </span>
                  )}
                </h2>

                {/* Subtitle / Description */}
                <p className="mt-4 sm:mt-5 text-[15px] sm:text-[17px] leading-relaxed text-gray-300 font-medium max-w-xl">
                  {currentSlide.description ||
                    "احسب قسطك واختر السيارة المناسبة لراتبك وقدّم طلبك إلكترونياً بسهولة."}
                </p>

                {/* CTA Action Buttons */}
                <div className="mt-7 sm:mt-9 flex flex-wrap items-center gap-3.5 sm:gap-4 w-full sm:w-auto">
                  {/* Primary Calculator Button */}
                  <Link
                    to={currentSlide.primaryBtnUrl || "/finance-calculator"}
                    className="flex-1 sm:flex-initial h-[50px] sm:h-[54px] px-6 sm:px-8 rounded-2xl bg-[#EDC98E] text-[#16254F] font-black text-[14px] sm:text-[15px] flex items-center justify-center gap-2.5 transition-all duration-300 hover:bg-[#e2bc7c] hover:scale-[1.02] active:scale-95 shadow-[0_8px_20px_rgba(237,201,142,0.2)] text-decoration-none"
                  >
                    <Calculator size={18} strokeWidth={2.5} />
                    <span>
                      {currentSlide.primaryBtnText ||
                        t("financeSolutions.calculateNow", {
                          defaultValue: "احسب قسطك الآن",
                        })}
                    </span>
                  </Link>

                  {/* Secondary Cars Catalog Button */}
                  <Link
                    to={currentSlide.secondaryBtnUrl || "/cars"}
                    className="flex-1 sm:flex-initial h-[50px] sm:h-[54px] px-5 sm:px-7 rounded-2xl bg-[#0F1E3B]/90 hover:bg-[#16294F] border border-white/15 hover:border-[#EDC98E]/50 text-white font-bold text-[14px] sm:text-[15px] flex items-center justify-center gap-2.5 transition-all duration-300 hover:scale-[1.02] active:scale-95 text-decoration-none"
                  >
                    <Car size={18} className="text-[#EDC98E]" />
                    <span>
                      {currentSlide.secondaryBtnText ||
                        t("financeSolutions.browseCars", {
                          defaultValue: "تصفح السيارات",
                        })}
                    </span>
                  </Link>
                </div>
              </div>

              {/* ── Cars Visual & Installment Badge Column (Left in RTL, Right in LTR) ── */}
              <div className="lg:col-span-6 flex flex-col items-center lg:items-end justify-center order-1 lg:order-2 w-full">
                {/* Top Badge & Installment Row */}
                <div className="w-full flex items-center justify-between gap-4 mb-4 sm:mb-6 px-1">
                  {/* Left: Model Year / Highlight Badge */}
                  <div className="text-start">
                    <span className="text-[12px] sm:text-[13px] font-bold text-gray-400 block leading-tight">
                      {currentSlide.badge || "جميع السيارات موديل 2026"}
                    </span>
                  </div>

                  {/* Right: Prominent Red Installment Pill Badge */}
                  <div className="flex flex-col items-end text-end">
                    <span className="text-[12px] sm:text-[13px] font-medium text-white/80 block mb-1">
                      {currentSlide.installmentLabel || "قسط يبدأ من"}
                    </span>
                    <div className="inline-flex items-center gap-2">
                      <span className="inline-block bg-[#E11D48] text-white font-black text-[22px] sm:text-[28px] leading-none px-3.5 py-1.5 rounded-xl shadow-md border border-red-400/30 tracking-tight">
                        {currentSlide.installmentAmount || "1,200"}
                      </span>
                    </div>
                    <span className="text-[11px] sm:text-[12px] font-bold text-white/70 mt-1 block">
                      {currentSlide.installmentPeriod || "ريال شهرياً"}
                    </span>
                  </div>
                </div>

                {/* Main Cars Lineup Image Container */}
                <div className="relative w-full overflow-hidden rounded-2xl flex items-center justify-center min-h-[200px] sm:min-h-[250px] md:min-h-[290px] group">
                  <img
                    key={currentSlide.image}
                    src={currentSlide.image || "/images/finance-banner-cars.jpg"}
                    alt={currentSlide.title || "Finance Cars Banner"}
                    className="w-full h-full object-cover sm:object-contain max-h-[320px] transition-transform duration-700 group-hover:scale-[1.03]"
                    loading="lazy"
                    onError={(e) => {
                      (e.currentTarget as HTMLImageElement).src =
                        "/images/finance-banner-cars.jpg";
                    }}
                  />
                  {/* Bottom Floor Shadow/Reflective Overlay */}
                  <div className="pointer-events-none absolute inset-x-0 bottom-0 h-14 bg-gradient-to-t from-[#080E1E] via-[#080E1E]/40 to-transparent" />
                </div>
              </div>
            </div>

            {/* ── Bottom Trust Features Strip ── */}
            <div className="mt-8 sm:mt-10 pt-6 sm:pt-8 border-t border-white/10">
              <div className="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                {(currentSlide.features || DEFAULT_SLIDES[0].features!).map(
                  (feature, fIdx) => (
                    <div
                      key={fIdx}
                      className="flex items-center gap-3 p-2.5 sm:p-3 rounded-2xl bg-white/[0.03] hover:bg-white/[0.07] border border-white/5 transition-all duration-300 text-start"
                    >
                      <div className="flex h-10 w-10 sm:h-11 sm:w-11 items-center justify-center rounded-xl bg-white/[0.07] border border-white/10 shrink-0">
                        {renderFeatureIcon(feature.icon)}
                      </div>
                      <span className="text-[12px] sm:text-[13px] font-bold text-gray-200 leading-snug">
                        {feature.text}
                      </span>
                    </div>
                  ),
                )}
              </div>
            </div>

            {/* ── Slider Controls (If multiple slides exist) ── */}
            {totalSlides > 1 && (
              <div className="mt-6 sm:mt-8 flex items-center justify-between pt-2">
                {/* Pagination Indicator Dots */}
                <div className="flex items-center gap-2">
                  {slides.map((_, idx) => (
                    <button
                      key={idx}
                      type="button"
                      onClick={() => setCurrentIndex(idx)}
                      className={`h-2.5 rounded-full transition-all duration-300 cursor-pointer ${
                        currentIndex === idx
                          ? "w-8 bg-[#EDC98E]"
                          : "w-2.5 bg-white/20 hover:bg-white/40"
                      }`}
                      aria-label={`Slide ${idx + 1}`}
                    />
                  ))}
                </div>

                {/* Arrow Navigation Buttons */}
                <div className="flex items-center gap-2">
                  <button
                    type="button"
                    onClick={isRtl ? nextSlide : prevSlide}
                    className="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-full bg-white/10 hover:bg-[#EDC98E] hover:text-[#16254F] text-white border border-white/15 transition-all duration-200 active:scale-95 cursor-pointer backdrop-blur-md"
                    aria-label="Previous Slide"
                  >
                    {isRtl ? <ChevronRight size={18} /> : <ChevronLeft size={18} />}
                  </button>

                  <button
                    type="button"
                    onClick={isRtl ? prevSlide : nextSlide}
                    className="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-full bg-white/10 hover:bg-[#EDC98E] hover:text-[#16254F] text-white border border-white/15 transition-all duration-200 active:scale-95 cursor-pointer backdrop-blur-md"
                    aria-label="Next Slide"
                  >
                    {isRtl ? <ChevronLeft size={18} /> : <ChevronRight size={18} />}
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </section>
  );
}
