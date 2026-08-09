import { useTranslation } from "react-i18next";
import { ArrowLeft, ArrowRight, Clock } from "lucide-react";
import { NavLink } from "react-router-dom";
import { APP_IMAGES, getImageUrl } from "../../constants/app-images";
import type { IBlogCardProps } from "../../interfaces/IBlogCardProps";
import LazyImg from "../LazyImg";

export default function BlogCard({
  image,
  category,
  date,
  readTime,
  title,
  description,
  readMoreTo,
  loading,
}: Partial<IBlogCardProps>) {
  const { t, i18n } = useTranslation();
  const isRTL = i18n.dir() === "rtl";

  const cardImage = getImageUrl(image ?? null) || APP_IMAGES.BLOG_PLACEHOLDER;
  const linkTarget = readMoreTo || "#";

  return (
    <article
      dir={i18n.dir()}
      className="w-[355px] h-[400px] mx-auto bg-white rounded-[24px] sm:rounded-[28px] overflow-hidden border border-gray-200 flex flex-col shadow-xs hover:shadow-xl transition-all duration-300 group select-none"
    >
      {/* Blog Image */}
      <div className="h-[192px] w-[353px] overflow-hidden bg-[#0B1736]">
        <LazyImg
          src={cardImage}
          alt={title ?? t("blogPage.details.postAlt")}
          loading={loading}
          className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
        />
      </div>

      {/* Content Details */}
      <div className="p-5 flex flex-col flex-grow text-start">
        {/* Meta row: category badge, read time, date */}
        <div className="flex flex-wrap items-center justify-start gap-2 mb-2 text-[10px] sm:text-[10px] text-gray-500 font-medium">
          {category && (
            <span className="bg-[#FFF8EE] text-[#F3C77C] font-black px-4 py-1 rounded-full text-[10px] sm:text-[10px]">
              {category}
            </span>
          )}

          {readTime && (
            <span className="flex items-center gap-1.5">
              <Clock size={15} className="text-gray-400" />
              {readTime}
            </span>
          )}

          {date && <span>{date}</span>}
        </div>

        {/* Title */}
        <h3 className="text-[16px] sm:text-[16px] font-bold leading-tight text-[#16254F] mb-2 line-clamp-2 text-start">
          <NavLink
            to={linkTarget}
            className="hover:text-[#F3C77C] transition-colors"
          >
            {title}
          </NavLink>
        </h3>

        {/* Description */}
        <p className="text-[12px] sm:text-[12px] leading-relaxed text-gray-500 line-clamp-2 mb-4 text-start flex-grow">
          {description}
        </p>

        {/* Read More Link */}
        <div className="mt-auto text-[#EDC98E] text-end">
          <NavLink
            to={linkTarget}
            className="inline-flex items-center gap-2 text-[15px] sm:text-[16px] font-black text-[#EDC98E] hover:text-[#E2B66B] transition-colors"
          >
            {isRTL ? (
              <ArrowLeft size={16} />
            ) : (
              <ArrowRight size={16} />
            )}
            <span>
              {t("blogPage.readMore")}
            </span>
          </NavLink>
        </div>
      </div>
    </article>
  );
}
