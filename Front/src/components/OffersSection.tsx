import { useState, useEffect } from "react";
import { useTranslation } from "react-i18next";
import { useNavigate } from "react-router-dom";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { useLanguageStore } from "../store/language.store";
import type { IOffersSectionProps } from "../interfaces/IOffersSectionProps";

export default function OffersSection({
  titleWhite,
  titleOrange,
  offers = [],
}: IOffersSectionProps) {
  const { i18n } = useTranslation();
  const navigate = useNavigate();
  const direction = useLanguageStore((s) => s.direction);
  const isRTL = direction === "rtl";

  const [currentSlide, setCurrentSlide] = useState(0);

  useEffect(() => {
    if (offers.length <= 1) return;
    const interval = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % offers.length);
    }, 6000);
    return () => clearInterval(interval);
  }, [offers.length]);

  const handlePrev = () =>
    setCurrentSlide((prev) => (prev - 1 + offers.length) % offers.length);
  const handleNext = () =>
    setCurrentSlide((prev) => (prev + 1) % offers.length);

  return (
    <section
      dir={i18n.dir()}
      className="relative w-full overflow-hidden bg-[#010915] py-16"
    >
      {/* Subtle background glow */}
      <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-blue-900/10 via-transparent to-transparent pointer-events-none" />

      <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {/* Section Header */}
        {(titleWhite || titleOrange) && (
          <div className="mb-8 text-start">
            <h2 className="text-[26px] font-black text-white md:text-[38px]">
              {titleWhite}
              {titleOrange && (
                <span className="text-[#E5C287]"> {titleOrange}</span>
              )}
            </h2>
          </div>
        )}

        {/* Slider */}
        {offers.length > 0 && (
          <div className="relative w-full h-[300px] sm:h-[340px] md:h-[380px] rounded-[28px] overflow-hidden shadow-2xl shadow-black/40">
            {/* Slides wrapper */}
            <div
              className="flex h-full transition-transform duration-500 ease-in-out"
              style={{
                transform: `translateX(${isRTL ? "" : "-"}${currentSlide * 100}%)`,
              }}
            >
              {offers.map((offer, index) => (
                <div
                  key={index}
                  className="relative h-full w-full shrink-0 select-none overflow-hidden"
                  style={{ backgroundColor: "#CC4E3C" }}
                >
                  {/* Geometric diamond tile pattern overlay */}
                  <div
                    className="absolute inset-0 opacity-25 pointer-events-none"
                    style={{
                      backgroundImage:
                        "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='56'%3E%3Cpolygon points='28,2 54,28 28,54 2,28' fill='none' stroke='%23000' stroke-width='1.5' stroke-opacity='0.35'/%3E%3C/svg%3E\")",
                      backgroundSize: "56px 56px",
                    }}
                  />

                  {/* Vignette */}
                  <div className="absolute inset-0 bg-gradient-to-b from-black/15 via-transparent to-black/40 pointer-events-none" />

                  {/* National Day Badge (top-right) */}
                  <div className="absolute top-5 end-5 z-20 hidden md:flex flex-col items-center bg-[#0B4D35] border border-white/20 rounded-xl px-4 py-2 shadow-sm pointer-events-none">
                    <span className="text-[13px] font-black text-white leading-tight">عزّنا بطبعنا</span>
                    <span className="text-[8px] font-bold text-white/75 uppercase tracking-widest mt-0.5">
                      اليوم الوطني السعودي ٩٥
                    </span>
                  </div>

                  {/* Top text (year + car name) */}
                  <div className="absolute top-7 start-0 end-0 px-6 text-center z-10 pointer-events-none">
                    {offer.description && (
                      <p className="text-[12px] font-extrabold text-white/80 uppercase tracking-[0.18em] mb-1">
                        {offer.description}
                      </p>
                    )}
                    <h3 className="text-[20px] sm:text-[26px] md:text-[32px] font-black leading-snug text-white drop-shadow">
                      {offer.title}
                    </h3>
                  </div>

                  {/* Car image – floating over the bottom edge */}
                  <div className="absolute bottom-[-14px] start-1/2 -translate-x-1/2 flex justify-center w-full z-10 pointer-events-none">
                    <img
                      src={offer.image}
                      alt={offer.title ?? "Offer"}
                      loading="eager"
                      className="h-[175px] sm:h-[210px] md:h-[245px] w-auto object-contain drop-shadow-2xl"
                    />
                  </div>

                  {/* Previous / Next arrows (bottom-start) */}
                  <div className="absolute bottom-5 start-5 md:start-8 z-20 flex gap-2" dir="ltr">
                    <button
                      type="button"
                      onClick={handlePrev}
                      aria-label="Previous"
                      className="flex h-10 w-10 items-center justify-center rounded-full bg-[#0F1E36]/50 text-white border border-white/15 hover:bg-[#0F1E36]/75 transition-colors active:scale-95 shadow"
                    >
                      <ChevronLeft size={18} />
                    </button>
                    <button
                      type="button"
                      onClick={handleNext}
                      aria-label="Next"
                      className="flex h-10 w-10 items-center justify-center rounded-full bg-[#0F1E36]/50 text-white border border-white/15 hover:bg-[#0F1E36]/75 transition-colors active:scale-95 shadow"
                    >
                      <ChevronRight size={18} />
                    </button>
                  </div>

                  {/* Dot indicators (bottom-center) */}
                  <div className="absolute bottom-6 start-1/2 -translate-x-1/2 z-20 flex gap-1.5">
                    {offers.map((_, idx) => (
                      <button
                        key={idx}
                        type="button"
                        aria-label={`Go to slide ${idx + 1}`}
                        onClick={() => setCurrentSlide(idx)}
                        className={`h-1.5 rounded-full transition-all duration-300 ${
                          idx === currentSlide
                            ? "w-8 bg-[#E5C287]"
                            : "w-2.5 bg-white/40 hover:bg-white/60"
                        }`}
                      />
                    ))}
                  </div>

                  {/* CTA button (bottom-end) */}
                  <div className="absolute bottom-5 end-5 md:end-8 z-20">
                    <button
                      type="button"
                      onClick={() => navigate(offer.buttonTo)}
                      className="h-10 px-6 rounded-full bg-[#E5C287] text-[13px] font-black text-[#0A1628] hover:bg-[#D8B478] shadow-md transition active:scale-95 whitespace-nowrap"
                    >
                      {offer.buttonText || "اكتشف العرض"}
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>
    </section>
  );
}
