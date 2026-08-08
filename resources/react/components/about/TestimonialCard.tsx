import type { ITestimonialCardProps } from "../../interfaces/ITestimonialCardProps";
import RatingStars from "./RatingStars";
import LazyImg from "../LazyImg";

export default function TestimonialCard({
    testimonial,
}: ITestimonialCardProps) {
    const isActive = testimonial.isActive;

    const cardBg = isActive
        ? "bg-[#EDC98E] text-[#010915] border-transparent shadow-[0_24px_60px_rgba(237,201,142,0.18)]"
        : "bg-[#0D1826] text-white border-white/5 shadow-md";

    const textColor = isActive ? "text-[#16254F]" : "text-white/45";
    const subtextColor = isActive ? "text-[#1a1200]/55" : "text-white/45";
    const nameColor = isActive ? "text-[#010915]" : "text-white!";
    const starColor = isActive ? "#16254F" : "#EDC98E";
    const avatarRing = isActive ? "ring-[#c9a055]/30" : "ring-white/10";
    const dividerColor = isActive ? "border-[#010915]/15" : "border-white/10";

    return (
        <article
            className={`flex flex-col rounded-2xl border p-7  transition-all duration-500 ${cardBg}`}
        >
            {/* Stars — centered */}
            <div className="mb-5 flex justify-start">
                <RatingStars
                    fillColor={starColor}
                    rating={testimonial.rating}
                />
            </div>

            {/* Quote text — centered */}
            <p
                className={`text-center text-[14px] leading-7 font-medium flex-1 ${textColor}`}
            >
                &ldquo;{testimonial.text}&rdquo;
            </p>

            {/* Author row — avatar then user info with top divider */}
            <div className={`mt-6 pt-5 border-t ${dividerColor} flex items-center gap-3`}>
                <LazyImg
                    src={testimonial.avatar}
                    alt={testimonial.name}
                    className={`h-12 w-12 rounded-full object-cover ring-4 shrink-0 ${avatarRing}`}
                />
                <div className="text-start flex-1">
                    <h3 className={`font-black text-[15px] ${nameColor}`}>
                        {testimonial.name}
                    </h3>
                    <p
                        className={`mt-0.5 text-xs font-semibold ${subtextColor}`}
                    >
                        {testimonial.job}
                    </p>
                </div>
            </div>
        </article>
    );
}
