import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { useLanguageStore } from "../store/language.store";
import { getImageUrl } from "../constants/app-images";
import type { IHomeHeroProps } from "../interfaces/IHomeHeroProps";
import frameHero from "../assets/Framehero.svg";

export default function HomeHero({
  slides = [],
  promoCards = [],
}: IHomeHeroProps) {
  const direction = useLanguageStore((s) => s.direction);
  const isRTL = direction === "rtl";
  const navigate = useNavigate();
  const [currentSlide, setCurrentSlide] = useState(0);
  const [isMobile, setIsMobile] = useState(() => typeof window !== "undefined" && window.innerWidth < 640);

  useEffect(() => {
    function handleResize() {
      setIsMobile(window.innerWidth < 640);
    }
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  // Auto-slide effect
  useEffect(() => {
    if (slides.length <= 1) return;
    const interval = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length);
    }, 5000);
    return () => clearInterval(interval);
  }, [slides.length]);

  const handlePrev = () => {
    setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length);
  };

  const handleNext = () => {
    setCurrentSlide((prev) => (prev + 1) % slides.length);
  };

  return (
    <section className="w-full pb-10 pt-2" dir={direction}>
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {/* ── Main Slideshow Slider ── */}
        {slides.length > 0 && (
          <div className="relative overflow-hidden rounded-2xl shadow-sm bg-[#051023] h-[220px] sm:h-[320px] md:h-[420px] lg:h-[480px]">
            {/* Slides Wrapper */}
            <div
              className="flex h-full transition-transform duration-500 ease-in-out"
              style={{ transform: `translateX(${isRTL ? "" : "-"}${currentSlide * 100}%)` }}
            >
              {slides.map((slide, idx) => {
                const targetImage = isMobile && slide.image_mobile ? slide.image_mobile : slide.image;
                const bgImg = getImageUrl(targetImage);
                return (
                  <div
                    key={slide.id}
                    className="relative h-full w-full shrink-0 flex items-center justify-between px-6 sm:px-12 md:px-20 text-white overflow-hidden bg-[#051023]"
                    dir={direction}
                  >
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
                  className="absolute top-1/2 -translate-y-1/2 left-2 sm:left-4 z-20 flex h-8 w-8 sm:h-10 sm:w-10 items-center justify-center rounded-xl md:rounded-2xl bg-black/30 text-white backdrop-blur-xs transition hover:bg-black/50 active:scale-95"
                  aria-label="Previous Slide"
                >
                  <ChevronLeft className="h-5 w-5 sm:h-6 sm:w-6" />
                </button>
                <button
                  type="button"
                  onClick={isRTL ? handlePrev : handleNext}
                  className="absolute top-1/2 -translate-y-1/2 right-2 sm:right-4 z-20 flex h-8 w-8 sm:h-10 sm:w-10 items-center justify-center rounded-xl md:rounded-2xl bg-black/30 text-white backdrop-blur-xs transition hover:bg-black/50 active:scale-95"
                  aria-label="Next Slide"
                >
                  <ChevronRight className="h-5 w-5 sm:h-6 sm:w-6" />
                </button>

                {/* Indicators / Dots */}
                <div className="absolute bottom-3 sm:bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5 sm:gap-2 z-10" dir="ltr">
                  {slides.map((_, idx) => (
                    <button
                      key={idx}
                      type="button"
                      onClick={() => setCurrentSlide(idx)}
                      className={`h-[4px] sm:h-[6px] rounded-full transition-all duration-300 ${
                        idx === currentSlide
                          ? "w-[40px] sm:w-[65px] md:w-[90px] bg-[#DFC675]"
                          : "w-[16px] sm:w-[24px] md:w-[32px] bg-[#D9D9D9]/80 hover:bg-white"
                      }`}
                    />
                  ))}
                </div>
              </>
            )}
          </div>
        )}

        {/* ── Promo Cards Row (2 columns on mobile, all cards in 3 columns on desktop) ── */}
        {promoCards.length > 0 && (
          <div className="mt-4 sm:mt-8 grid grid-cols-2 gap-2.5 sm:gap-6 lg:grid-cols-3">
            {promoCards.map((card, idx) => {
              const cardImg = getImageUrl(card.image);
              return (
                <div
                  key={idx}
                  onClick={() => navigate(card.button?.url || "/cars")}
                  className={`relative cursor-pointer transition-all duration-300 hover:-translate-y-1.5 w-full aspect-[403/320] mx-auto select-none overflow-hidden ${
                    idx >= 2 ? "hidden lg:block" : ""
                  }`}
                >
                  {cardImg && (
                    <img
                      src={cardImg}
                      alt={card.title || "Promo Card"}
                      loading="eager"
                      decoding="async"
                      className="h-full w-full object-contain object-center pointer-events-none"
                    />
                  )}
                  {/* Action Button centered in the bottom notch */}
                  <div className="absolute bottom-[5%] sm:bottom-[7%] left-0 right-0 flex justify-center z-10">
                    {card.button?.text && (
                      <span className="rounded-lg sm:rounded-xl bg-[#0A1628] px-2.5 sm:px-7 py-1 sm:py-2.5 text-[9.5px] sm:text-[12.5px] font-black text-white hover:bg-[#1E293B] shadow-sm transition-colors whitespace-nowrap active:scale-95">
                        {card.button.text}
                      </span>
                    )}
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </div>
    </section>
  );
}
