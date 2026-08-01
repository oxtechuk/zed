import { useState, useEffect, useCallback, useMemo } from "react";
import { useTranslation } from "react-i18next";
import { Star } from "lucide-react";
import TestimonialCard from "./TestimonialCard";
import type { ITestimonialsSectionProps } from "../../interfaces/ITestimonialsSectionProps";

interface ITestimonialStat {
  value: string;
  label: string;
}

export default function TestimonialsSection({
  badge,
  titleBlack,
  titleBlue,
  ratingText,
  testimonials,
}: ITestimonialsSectionProps) {
  const { i18n, t } = useTranslation();
  const [activeIndex, setActiveIndex] = useState(0);
  const total = testimonials.length;

  const next = useCallback(() => {
    setActiveIndex((prev) => (prev + 1) % total);
  }, [total]);

  useEffect(() => {
    if (total < 2) return;
    const id = setInterval(next, 4000);
    return () => clearInterval(id);
  }, [next, total]);

  const bottomStats = useMemo(() => {
    return t("aboutPage.testimonials.stats", { returnObjects: true }) as ITestimonialStat[];
  }, [t]);

  if (total === 0) return null;

  // Build a 3-card window: [prev, active, next]
  const visibleIndices = [
    (activeIndex - 1 + total) % total,
    activeIndex,
    (activeIndex + 1) % total,
  ];

  return (
    <section dir={i18n.dir()} className="bg-[#010915] px-6 py-24 text-white relative overflow-hidden">
      {/* Background glow overlay */}
      <div className="absolute bottom-0 right-0 w-96 h-96 bg-[var(--brand-primary-color)] opacity-10 blur-[150px] rounded-full pointer-events-none" />

      <div className="mx-auto max-w-[1200px] relative z-10">
        {/* Header Row */}
        <div className="mb-16 flex items-end justify-between gap-8 max-md:flex-col-reverse max-md:items-start">
          <div className="text-start">
            <span className="mb-3 block text-sm font-bold tracking-wider text-[#EDC98E] uppercase">
              {badge}
            </span>
            <h2 className="text-4xl font-black text-white max-sm:text-3xl">
              {titleBlack} <span className="text-[#EDC98E]">{titleBlue}</span>
            </h2>
          </div>
          <div className="pt-8 text-[#EDC98E]">
            <div className="flex items-center gap-2 text-lg font-extrabold">
              <Star size={18} fill="#EDC98E" strokeWidth={0} />
              <span>
                {ratingText || t("aboutPage.testimonials.ratingText")}
              </span>
            </div>
          </div>
        </div>

        {/* Carousel Grid */}
        <div className="grid items-center gap-8 lg:grid-cols-[1fr_1.22fr_1fr]">
          {visibleIndices.map((tIdx, col) => (
            <div key={tIdx} className={col === 1 ? "relative z-10" : "hidden lg:block opacity-65"}>
              <TestimonialCard
                testimonial={{ ...testimonials[tIdx], isActive: col === 1 }}
              />
            </div>
          ))}
        </div>

        {/* Slider Dots */}
        <div className="mt-10 flex items-center justify-center gap-2">
          {testimonials.map((_, i) => (
            <button
              key={i}
              type="button"
              onClick={() => setActiveIndex(i)}
              className={`h-2 rounded-full transition-all duration-300 ${
                i === activeIndex ? "w-7 bg-[#EDC98E]" : "w-2 bg-white/20"
              }`}
              aria-label={t("aboutPage.testimonials.goToTestimonial", {
                number: i + 1,
              })}
            />
          ))}
        </div>

        {/* Bottom Testimonial Stats Grid */}
        {bottomStats && bottomStats.length > 0 && (
          <div className="mt-24 grid grid-cols-2 gap-5 md:grid-cols-4">
            {bottomStats.map((stat, idx) => (
              <div
                key={idx}
                className="flex flex-col items-center justify-center rounded-[20px] border border-white/5 bg-[#0E1726]/40 backdrop-blur-sm py-8 px-4 text-center transition-all duration-300 hover:border-white/10"
              >
                <strong className="text-[28px] font-black text-white md:text-[34px] leading-none">
                  {stat.value}
                </strong>
                <span className="mt-3 text-[13px] font-bold text-white/55">
                  {stat.label}
                </span>
              </div>
            ))}
          </div>
        )}
      </div>
    </section>
  );
}
