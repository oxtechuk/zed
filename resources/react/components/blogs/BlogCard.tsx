import { useTranslation } from "react-i18next";
import { ArrowLeft, ArrowRight, Clock, Calendar } from "lucide-react";
import { NavLink } from "react-router-dom";
import type { IBlogCardProps } from "../../interfaces/IBlogCardProps";

export default function BlogCard({
  image,
  category,
  date,
  readTime,
  title,
  description,
  readMoreTo,
}: IBlogCardProps) {
  const { t, i18n } = useTranslation();
  const isRTL = i18n.dir() === "rtl";

  return (
    <article
      dir={i18n.dir()}
      className="w-full bg-white border border-[#E7E9EF] rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-[0_12px_32px_rgba(0,0,0,0.08)] flex flex-col justify-between group"
    >
      <div>
        {/* Blog Image */}
        <div className="overflow-hidden h-[192px] w-full bg-[#F3F4F6]">
          <img
            src={image}
            alt={title}
            className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
            loading="lazy"
          />
        </div>

        {/* Content details */}
        <div className="p-6">
          {/* Meta row: date, read time, category badge */}
          <div className="flex flex-wrap items-center justify-end gap-3 text-[10px] font-normal text-[#667085]">
            <span>{date}</span>
            <span className="flex items-center gap-1">
              <Clock size={12} className="text-[#667085]" />
              {readTime}
            </span>
            {category && (
              <span className="bg-[#EDC98E]/10 text-[#EDC98E] font-black px-2.5 py-1 rounded-full text-[10px]">
                {category}
              </span>
            )}
          </div>

          {/* Title */}
          <h3 className="mt-3 text-[16px] font-black leading-[1.4] text-[#16254F] line-clamp-2">
            <NavLink to={readMoreTo}>{title}</NavLink>
          </h3>

          {/* Description */}
          <p className="mt-2 text-[12px] leading-relaxed text-[#667085] line-clamp-2">
            {description}
          </p>

          {/* Read More Link */}
          <div className="mt-4">
            <NavLink
              to={readMoreTo}
              className="inline-flex items-center gap-1.5 text-[12px] font-black text-[#EDC98E] hover:text-[#16254F] transition"
            >
              <span>{t("blogPage.readMore", { defaultValue: "اقرأ المزيد" })}</span>
              {isRTL ? <ArrowLeft size={14} /> : <ArrowRight size={14} />}
            </NavLink>
          </div>
        </div>
      </div>
    </article>
  );
}
