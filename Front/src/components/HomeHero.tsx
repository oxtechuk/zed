import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { useLanguageStore } from "../store/language.store";
import { getImageUrl } from "../constants/app-images";
import type { IHomeHeroProps } from "../interfaces/IHomeHeroProps";

export default function HomeHero({
  slides = [],
  promoCards = [],
}: IHomeHeroProps) {
  const direction = useLanguageStore((s) => s.direction);
  const isRTL = direction === "rtl";
  const navigate = useNavigate();
  const [currentSlide, setCurrentSlide] = useState(0);

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
    <section className="w-full bg-[#FAFBFC] pb-10 pt-2" dir={direction}>
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {/* ── Main Slideshow Slider ── */}
        {slides.length > 0 && (
          <div className="relative overflow-hidden rounded-[24px] shadow-sm bg-[#051023] h-[200px] md:h-[400px]">
            {/* Slides Wrapper */}
            <div
              className="flex h-full transition-transform duration-500 ease-in-out"
              style={{ transform: `translateX(${isRTL ? "" : "-"}${currentSlide * 100}%)` }}
            >
              {slides.map((slide) => {
                const bgImg = getImageUrl(slide.image);
                return (
                  <div
                    key={slide.id}
                    className="relative h-full w-full shrink-0 flex items-center justify-between px-10 md:px-20 text-white"
                    dir={direction}
                    style={{
                      backgroundImage: `linear-gradient(to left, rgba(5,16,35,0.85), rgba(5,16,35,0.4)), url(${bgImg})`,
                      backgroundSize: "cover",
                      backgroundPosition: "center",
                    }}
                  >
                    {/* Text overlays / Left side (RTL) */}
                    <div className="max-w-xl z-10 text-right flex flex-col items-start justify-center h-full">
                      {slide.badge && (
                        <span className="mb-3 inline-block rounded-full bg-[#E5C287] px-3.5 py-1 text-[12px] font-bold text-[#051023]">
                          {slide.badge}
                        </span>
                      )}
                      <h2 className="text-[22px] font-extrabold leading-[1.3] md:text-[38px] text-white">
                        {slide.title}
                      </h2>
                      <p className="mt-2 text-[13px] text-white/80 md:text-[16px] max-w-[480px]">
                        {slide.description}
                      </p>
                      {slide.button_text && (
                        <button
                          type="button"
                          onClick={() => navigate(slide.button_url || "/cars")}
                          className="mt-5 rounded-full bg-[#E5C287] px-6 py-2.5 text-[14px] font-bold text-[#051023] shadow-sm hover:bg-[#D9B477] transition"
                        >
                          {slide.button_text}
                        </button>
                      )}
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
                  className="absolute left-4 top-1/2 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-sm transition hover:bg-white/20"
                >
                  <ChevronLeft size={24} />
                </button>
                <button
                  type="button"
                  onClick={isRTL ? handlePrev : handleNext}
                  className="absolute right-4 top-1/2 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-sm transition hover:bg-white/20"
                >
                  <ChevronRight size={24} />
                </button>

                {/* Indicators / Dots */}
                <div className="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10" dir="ltr">
                  {slides.map((_, idx) => (
                    <button
                      key={idx}
                      type="button"
                      onClick={() => setCurrentSlide(idx)}
                      className={`h-2 rounded-full transition-all duration-300 ${
                        idx === currentSlide ? "w-6 bg-[#E5C287]" : "w-2 bg-white/40"
                      }`}
                    />
                  ))}
                </div>
              </>
            )}
          </div>
        )}

        {/* ── Promo Cards Row (Triple column banner) ── */}
        {promoCards.length > 0 && (
          <div className="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
            {promoCards.map((card, idx) => {
              const cardImg = getImageUrl(card.image);
              return (
                <div
                  key={idx}
                  onClick={() => navigate(card.button?.url || "/cars")}
                  className="relative cursor-pointer overflow-hidden rounded-[20px] shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 bg-[#051023] h-[160px] md:h-[180px] w-full"
                  style={{
                    backgroundImage: `url(${cardImg})`,
                    backgroundSize: "cover",
                    backgroundPosition: "center",
                  }}
                >
                  {/* Subtle inner overlay for text legibility */}
                  <div className="absolute inset-0 bg-black/10 hover:bg-black/20 transition-colors" />

                  {/* If there's an explicit action button or overlay text */}
                  <div className="absolute bottom-4 right-4 z-10">
                    {card.button?.text && (
                      <span className="rounded-full bg-[#0F1E36] px-5 py-1.5 text-[12px] font-bold text-white shadow hover:bg-[#1C2E4D]">
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
