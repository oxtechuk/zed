import { useState, useEffect, useCallback } from "react";
import { Link } from "react-router-dom";
import { ChevronLeft, ChevronRight, Calculator, Car } from "lucide-react";
import { useTranslation } from "react-i18next";
import { useLanguageStore } from "../store/language.store";
import { getImageUrl } from "../constants/app-images";
import type { IHomeHeroProps } from "../interfaces/IHomeHeroProps";

export default function HomeHero({ slides = [] }: IHomeHeroProps) {
  const { t } = useTranslation();
  const direction = useLanguageStore((s) => s.direction);
  const isRTL = direction === "rtl";
  const [currentSlide, setCurrentSlide] = useState(0);
  const [isMobile, setIsMobile] = useState(
    () => typeof window !== "undefined" && window.innerWidth < 640,
  );

  useEffect(() => {
    function handleResize() {
      setIsMobile(window.innerWidth < 640);
    }
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  const totalSlides = slides.length;

  const handlePrev = useCallback(() => {
    setCurrentSlide((prev) => (prev - 1 + totalSlides) % totalSlides);
  }, [totalSlides]);

  const handleNext = useCallback(() => {
    setCurrentSlide((prev) => (prev + 1) % totalSlides);
  }, [totalSlides]);

  // Auto-slide effect
  useEffect(() => {
    if (totalSlides <= 1) return;
    const interval = setInterval(handleNext, 5000);
    return () => clearInterval(interval);
  }, [totalSlides, handleNext]);

  if (slides.length === 0) return null;

  return (
    <section className="w-full pb-8 pt-0" dir={direction}>
      {/* Full-width Slideshow Slider Container */}
      <div className="relative w-full overflow-hidden bg-[#051023] h-[340px] sm:h-[420px] md:h-[480px] lg:h-[540px]">
        {/* Slides Wrapper */}
        <div
          className="flex h-full transition-transform duration-500 ease-in-out"
          style={{
            transform: `translateX(${isRTL ? "" : "-"}${currentSlide * 100}%)`,
          }}
        >
          {slides.map((slide, idx) => {
            const targetImage =
              isMobile && slide.image_mobile ? slide.image_mobile : slide.image;
            const bgImg = getImageUrl(targetImage);

            return (
              <div
                key={slide.id || idx}
                className="relative h-full w-full shrink-0 flex items-center justify-between text-white overflow-hidden bg-[#051023]"
                dir={direction}
              >
                {/* Background Image */}
                {bgImg && (
                  <img
                    src={bgImg}
                    alt={slide.title || "Hero Banner"}
                    loading={idx === 0 ? "eager" : "lazy"}
                    fetchPriority={idx === 0 ? "high" : "auto"}
                    decoding="async"
                    className="absolute inset-0 h-full w-full object-cover object-center pointer-events-none"
                  />
                )}

                {/* Content Overlay Grid */}
                <div className="relative z-10 mx-auto max-w-7xl px-6 sm:px-10 lg:px-12 w-full h-full flex flex-col justify-center items-start text-start">
                  <div className="max-w-xl">
                    {slide.badge && (
                      <span className="inline-block px-3 py-1 mb-3 rounded-full text-[12px] font-black bg-[#EDC98E] text-[#16254F] shadow-sm">
                        {slide.badge}
                      </span>
                    )}

                    {slide.title && (
                      <h1 className="text-[26px] sm:text-[36px] md:text-[44px] font-black text-white leading-tight drop-shadow-sm">
                        {slide.title}
                      </h1>
                    )}

                    {(slide.subtitle || slide.description) && (
                      <p className="mt-2.5 sm:mt-3.5 text-[14px] sm:text-[16px] text-gray-200 font-medium leading-relaxed max-w-lg line-clamp-2 sm:line-clamp-3">
                        {slide.subtitle || slide.description}
                      </p>
                    )}

                    {/* Two CTA Action Buttons */}
                    <div className="mt-6 sm:mt-8 flex flex-wrap items-center gap-3 sm:gap-4">
                      {/* Button 1: احسب تمويلك */}
                      <Link
                        to="/finance-calculator"
                        className="h-[46px] sm:h-[50px] px-6 sm:px-7 rounded-xl sm:rounded-2xl bg-[#EDC98E] text-[#16254F] hover:bg-[#e2bc7c] font-black text-[13px] sm:text-[15px] flex items-center justify-center gap-2 shadow-[0_8px_20px_rgba(237,201,142,0.2)] hover:scale-105 transition-all duration-300 active:scale-95 text-decoration-none"
                      >
                        <Calculator size={18} strokeWidth={2.5} />
                        <span>
                          {t("hero.calculateFinance", {
                            defaultValue: "احسب تمويلك",
                          })}
                        </span>
                      </Link>

                      {/* Button 2: تصفح السيارات */}
                      <Link
                        to="/cars"
                        className="h-[46px] sm:h-[50px] px-5 sm:px-6 rounded-xl sm:rounded-2xl bg-[#0B1528]/80 hover:bg-[#16254F] border border-white/20 text-white font-bold text-[13px] sm:text-[15px] flex items-center justify-center gap-2 hover:scale-105 transition-all duration-300 active:scale-95 text-decoration-none backdrop-blur-sm"
                      >
                        <Car size={18} className="text-[#EDC98E]" />
                        <span>
                          {t("hero.browseCars", {
                            defaultValue: "تصفح السيارات",
                          })}
                        </span>
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            );
          })}
        </div>

        {/* Slider Navigation Arrows */}
        {slides.length > 1 && (
          <>
            <button
              type="button"
              onClick={isRTL ? handleNext : handlePrev}
              className="absolute top-1/2 -translate-y-1/2 left-3 sm:left-6 z-20 flex h-9 w-9 sm:h-11 sm:w-11 items-center justify-center rounded-full bg-black/40 hover:bg-[#EDC98E] hover:text-[#16254F] text-white backdrop-blur-md transition-all duration-200 active:scale-95 cursor-pointer border border-white/10"
              aria-label="Previous Slide"
            >
              <ChevronLeft className="h-5 w-5 sm:h-6 sm:w-6" />
            </button>
            <button
              type="button"
              onClick={isRTL ? handlePrev : handleNext}
              className="absolute top-1/2 -translate-y-1/2 right-3 sm:right-6 z-20 flex h-9 w-9 sm:h-11 sm:w-11 items-center justify-center rounded-full bg-black/40 hover:bg-[#EDC98E] hover:text-[#16254F] text-white backdrop-blur-md transition-all duration-200 active:scale-95 cursor-pointer border border-white/10"
              aria-label="Next Slide"
            >
              <ChevronRight className="h-5 w-5 sm:h-6 sm:w-6" />
            </button>

            {/* Indicators / Dots */}
            <div
              className="absolute bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 flex gap-1.5 sm:gap-2 z-20"
              dir="ltr"
            >
              {slides.map((_, idx) => (
                <button
                  key={idx}
                  type="button"
                  onClick={() => setCurrentSlide(idx)}
                  className={`h-[4px] sm:h-[6px] rounded-full transition-all duration-300 cursor-pointer ${idx === currentSlide
                      ? "w-[40px] sm:w-[65px] md:w-[90px] bg-[#EDC98E]"
                      : "w-[16px] sm:w-[24px] md:w-[32px] bg-white/40 hover:bg-white/80"
                    }`}
                  aria-label={`Slide ${idx + 1}`}
                />
              ))}
            </div>
          </>
        )}
      </div>
    </section>
  );
}
