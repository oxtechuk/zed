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
          <div className="relative overflow-hidden rounded-2xl shadow-sm bg-[#051023] h-[200px] md:h-[400px]">
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
                  className="absolute left-4 top-1/2 -translate-y-1/2 flex h-12 w-12 items-center justify-center rounded-2xl bg-white/30 text-white transition hover:bg-white/40"
                >
                  <ChevronLeft size={24} />
                </button>
                <button
                  type="button"
                  onClick={isRTL ? handlePrev : handleNext}
                  className="absolute right-4 top-1/2 -translate-y-1/2 flex h-12 w-12 items-center justify-center rounded-2xl bg-white/30 text-white transition hover:bg-white/40"
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
                      className={`h-[6px] rounded-full transition-all duration-300 ${
                        idx === currentSlide ? "w-[90px] bg-[#DFC675]" : "w-[32px] bg-[#D9D9D9]"
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
          <div className="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
            {promoCards.map((card, idx) => {
              const cardImg = getImageUrl(card.image);
              return (
                <div
                  key={idx}
                  onClick={() => navigate(card.button?.url || "/cars")}
                  className="relative cursor-pointer transition-all duration-300 hover:-translate-y-1.5 w-full aspect-[403/320] max-w-[340px] sm:max-w-[380px] md:max-w-[400px] mx-auto select-none"
                  style={{
                    backgroundImage: `url(${frameHero})`,
                    backgroundSize: "contain",
                    backgroundPosition: "center",
                    backgroundRepeat: "no-repeat",
                  }}
                >
                  {/* Text Details at the top */}
                  <div className="absolute top-[32px] left-0 right-0 px-6 text-center text-white">
                    {card.badge && (
                      <span className="mb-2 inline-block rounded-full bg-[#FFF4E4] border border-[#FFE4D6]/20 px-3 py-1 text-[11px] font-black text-[#D97706]">
                        {card.badge}
                      </span>
                    )}
                    <h3 className="text-[17px] md:text-[19px] font-black leading-tight text-white">
                      {card.title}
                    </h3>
                    <p className="mt-1 text-[11px] md:text-[12px] text-white/80 max-w-[260px] mx-auto line-clamp-2">
                      {card.subtitle}
                    </p>
                  </div>

                  {/* Floating Image in the middle */}
                  <div className="absolute bottom-[60px] left-0 right-0 flex justify-center px-4">
                    <img
                      src={cardImg}
                      alt={card.title}
                      className="h-[95px] md:h-[105px] w-auto object-contain drop-shadow-md select-none pointer-events-none transition-transform duration-300 hover:scale-105"
                    />
                  </div>

                  {/* Action Button centered in the bottom notch */}
                  <div className="absolute bottom-[22px] left-0 right-0 flex justify-center">
                    {card.button?.text && (
                      <span className="rounded-xl bg-[#0A1628] px-7 py-2.5 text-[12.5px] font-black text-white hover:bg-[#1E293B] shadow-sm transition-colors whitespace-nowrap active:scale-95">
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
