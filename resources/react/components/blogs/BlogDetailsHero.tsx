import { useTranslation } from "react-i18next";
import { Clock, CalendarDays } from "lucide-react";
import type { IBlogDetailsHeroProps } from "../../interfaces/IBlogDetailsHeroProps";

export default function BlogDetailsHero({
  category,
  title,
  date,
  readTime,
}: IBlogDetailsHeroProps) {
  const { t, i18n } = useTranslation();

  return (
    <section
      dir={i18n.dir()}
      className="w-full bg-[#080E1E] py-6 md:py-8 text-white"
    >
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col items-start text-start w-full">
          {/* Top Label */}
          <span className="text-[#EDC98E] text-[13px] md:text-[14px] font-bold mb-2">
            {t("blogPage.details.heroBadge")}
          </span>

          {/* Main Title */}
          <h1 className="text-[22px] sm:text-[28px] md:text-[36px] font-black leading-[1.3] text-white tracking-tight mb-4 w-full">
            {title}
          </h1>

          {/* Metadata Row */}
          <div className="flex flex-wrap items-center gap-4 text-[12px] sm:text-[13px] text-white/70 font-medium">
            {/* Category Pill */}
            {category && (
              <span className="inline-flex items-center rounded-full bg-[#064E3B]/80 border border-[#10B981]/30 px-3.5 py-1 text-[12px] font-bold text-[#34D399]">
                {category}
              </span>
            )}

            {/* Reading Time */}
            {readTime && (
              <div className="flex items-center gap-1.5 text-white/80">
                <Clock size={14} className="text-white/60" />
                <span>{readTime}</span>
              </div>
            )}

            {/* Publication Date */}
            {date && (
              <div className="flex items-center gap-1.5 text-white/80">
                <CalendarDays size={14} className="text-white/60" />
                <span>{date}</span>
              </div>
            )}
          </div>
        </div>
      </div>
    </section>
  );
}
