import { useState, useEffect, useCallback, useMemo } from "react";
import { useTranslation } from "react-i18next";
import { Star } from "lucide-react";
import TestimonialCard from "./TestimonialCard";
import TestimonialStatsGrid from "./TestimonialStatsGrid";
import type { ITestimonialsSectionProps } from "../../interfaces/ITestimonialsSectionProps";
import type { ITestimonialStat } from "../../interfaces/ITestimonialStat";

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
        return t("aboutPage.testimonials.stats", {
            returnObjects: true,
        }) as ITestimonialStat[];
    }, [t]);

    if (total === 0) return null;

    // Build a 3-card window: [prev, active, next]
    const visibleIndices = [
        (activeIndex - 1 + total) % total,
        activeIndex,
        (activeIndex + 1) % total,
    ];

    return (
        <section
            dir={i18n.dir()}
            className="bg-[#010915] px-3 py-8 mb-30 text-white relative overflow-hidden"
        >
            {/* Background glow overlay */}
            <div className="absolute bottom-0 end-0 w-96 h-64 bg-[#080E1E] opacity-10 blur-[150px] rounded-full pointer-events-none" />

            <div className="mx-auto max-w-[1200px] relative z-10">
                {/* Carousel Grid */}
                <div className="grid items-center gap-4 lg:grid-cols-[1fr_1.18fr_1fr]">
                    {visibleIndices.map((tIdx, col) => (
                        <div
                            key={tIdx}
                            className={
                                col === 1
                                    ? "relative z-10"
                                    : "hidden lg:block opacity-50 scale-[0.96] origin-center"
                            }
                        >
                            <TestimonialCard
                                testimonial={{
                                    ...testimonials[tIdx],
                                    isActive: col === 1,
                                }}
                            />
                        </div>
                    ))}
                </div>

                {/* Slider Dots */}
                <div className="mt-6 flex items-center justify-center gap-2">
                    {testimonials.map((_, i) => (
                        <button
                            key={i}
                            type="button"
                            onClick={() => setActiveIndex(i)}
                            className={`h-2 rounded-full transition-all duration-300 ${
                                i === activeIndex
                                    ? "w-7 bg-[#EDC98E]"
                                    : "w-2 bg-white/20"
                            }`}
                            aria-label={t(
                                "aboutPage.testimonials.goToTestimonial",
                                {
                                    number: i + 1,
                                },
                            )}
                        />
                    ))}
                </div>

                {/* Bottom Testimonial Stats Grid */}
                <TestimonialStatsGrid stats={bottomStats} />
            </div>
        </section>
    );
}
