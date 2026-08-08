import type { ITestimonialItem } from "../../interfaces/ITestimonialItem";
import RatingStars from "./RatingStars";
import LazyImg from "../LazyImg";

export default function TestimonialCard({
  testimonial,
}: {
  testimonial: ITestimonialItem & { isActive?: boolean };
}) {
  const isActive = testimonial.isActive;

  // Setup dynamic theme colors
  const cardBg = isActive
    ? "bg-[#EDC98E] text-[#010915] border-transparent scale-[1.03] shadow-[0_20px_50px_rgba(237,201,142,0.15)]"
    : "bg-[#0E1726] text-white border-white/5 shadow-md";

  const textColor = isActive ? "text-[#010915]/90" : "text-white/80";
  const subtextColor = isActive ? "text-[#010915]/60" : "text-white/50";
  const borderDivider = isActive ? "border-[#010915]/10" : "border-white/10";
  const avatarRing = isActive ? "ring-[#010915]/10" : "ring-white/10";
  const starColor = isActive ? "#010915" : "#EDC98E";

  return (
    <article
      className={`flex min-h-[240px] flex-col justify-between rounded-3xl border p-8 transition-all duration-500 hover:-translate-y-1 ${cardBg}`}
    >
      <div>
        {/* Star Ratings aligned to start (right in RTL, left in LTR) */}
        <div className="mb-6 flex justify-start">
          <RatingStars fillColor={starColor} rating={testimonial.rating} />
        </div>

        {/* Testimonial Quote Text */}
        <p className={`text-start text-[15px] leading-8 font-medium italic ${textColor}`}>
          &ldquo;{testimonial.text}&rdquo;
        </p>
      </div>

      {/* Client Info Section */}
      <div className={`mt-6 border-t pt-5 ${borderDivider}`}>
        <div className="flex items-center justify-start gap-4">
          <LazyImg
            src={testimonial.avatar}
            alt={testimonial.name}
            className={`h-11 w-11 rounded-full object-cover ring-4 ${avatarRing}`}
          />
          <div className="text-start">
            <h3 className={`font-extrabold text-[15px] ${isActive ? "text-[#010915]" : "text-white"}`}>
              {testimonial.name}
            </h3>
            <p className={`mt-0.5 text-xs font-semibold ${subtextColor}`}>
              {testimonial.job}
            </p>
          </div>
        </div>
      </div>
    </article>
  );
}
