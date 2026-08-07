import { useTranslation } from "react-i18next";
import { ArrowLeft, ArrowRight, Clock } from "lucide-react";
import { NavLink } from "react-router-dom";
import { APP_IMAGES, getImageUrl } from "../../constants/app-images";
import type { IBlogCardProps } from "../../interfaces/IBlogCardProps";

export default function BlogCard({
    image,
    category,
    date,
    readTime,
    title,
    description,
    readMoreTo,
}: Partial<IBlogCardProps>) {
    const { t, i18n } = useTranslation();
    const isRTL = i18n.dir() === "rtl";

    const cardImage = getImageUrl(image ?? null) || APP_IMAGES.BLOG_PLACEHOLDER;
    const linkTarget = readMoreTo || "#";

    return (
        <article
            dir={i18n.dir()}
            className="w-full bg-white rounded-[24px] sm:rounded-[28px] overflow-hidden border border-gray-200 flex flex-col h-full shadow-xs hover:shadow-xl transition-all duration-300 group select-none"
        >
            {/* Blog Image */}
            <div className="overflow-hidden h-[210px] sm:h-[230px] w-full bg-[#0B1736]">
                <img
                    src={cardImage}
                    alt={title ?? "Blog Post"}
                    className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                    loading="lazy"
                />
            </div>

            {/* Content Details */}
            <div className="p-6 sm:p-7 flex flex-col flex-grow text-start">
                {/* Meta row: category badge, read time, date */}
                <div className="flex flex-wrap items-center justify-start gap-3 mb-3 text-[13px] sm:text-[14px] text-gray-500 font-medium">
                    {category && (
                        <span className="bg-[#FFF8EE] text-[#F3C77C] font-black px-4 py-1 rounded-full text-[12px] sm:text-[13px]">
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
                <h3 className="text-[20px] sm:text-[22px] font-bold leading-tight text-[#16254F] mb-2 line-clamp-2 text-start">
                    <NavLink
                        to={linkTarget}
                        className="hover:text-[#F3C77C] transition-colors"
                    >
                        {title}
                    </NavLink>
                </h3>

                {/* Description */}
                <p className="text-[14px] sm:text-[15px] font-bold leading-relaxed text-gray-500 line-clamp-2 mb-5 text-start flex-grow">
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
                            {t("blogPage.readMore", {
                                defaultValue: "اقرأ المزيد",
                            })}
                        </span>
                    </NavLink>
                </div>
            </div>
        </article>
    );
}
