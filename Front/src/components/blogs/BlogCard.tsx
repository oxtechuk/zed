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
      className="w-full bg-white border border-[#E5E9F0] rounded-[24px] p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md flex flex-col justify-between"
    >
      <div>
        {/* Blog Image */}
        <div className="overflow-hidden rounded-[16px] mb-4">
          <img
            src={image}
            alt={title}
            className="h-[220px] w-full object-cover"
            loading="lazy"
          />
        </div>

        {/* Categories / Date badge row */}
        <div className="mb-3.5 flex flex-wrap items-center gap-4 text-[13px] text-gray-500 font-medium">
          {category && (
            <span className="bg-[#FFF2EB] text-[#FF9E3D] font-extrabold px-3 py-1.5 rounded-full text-[12px]">
              {category}
            </span>
          )}
          <span className="flex items-center gap-1.5">
            <Clock size={14} className="text-gray-400" />
            {readTime}
          </span>
          <span className="flex items-center gap-1.5">
            <Calendar size={14} className="text-gray-400" />
            {date}
          </span>
        </div>

        {/* Title */}
        <h3 className="text-[18px] md:text-[20px] font-extrabold leading-[1.5] text-[#07111F] mb-2 line-clamp-2 hover:text-[#FF9E3D] transition">
          <NavLink to={readMoreTo}>{title}</NavLink>
        </h3>

        {/* Description */}
        <p className="text-[14px] leading-relaxed text-gray-500 line-clamp-3 mb-4">
          {description}
        </p>
      </div>

      {/* Read More button */}
      <div className="mt-2 text-right">
        <NavLink
          to={readMoreTo}
          className="inline-flex items-center gap-1.5 text-[15px] font-extrabold text-[#FF9E3D] hover:text-[#07111F] transition"
        >
          <span>{t("blogPage.readMore", { defaultValue: "اقرأ المزيد" })}</span>
          {isRTL ? <ArrowLeft size={16} /> : <ArrowRight size={16} />}
        </NavLink>
      </div>
    </article>
  );
}
