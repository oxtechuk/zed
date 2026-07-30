import { useTranslation } from "react-i18next";
import { NavLink } from "react-router-dom";
import { useCountdown } from "../../hooks/useCountdown";
import type { IOfferListCardProps } from "../../interfaces/IOfferListCardProps";

export default function OfferListCard({
  image,
  title,
  description,
  tag,
  ends_at,
  buttonTo,
}: IOfferListCardProps) {
  const { i18n, t } = useTranslation();
  const isRtl = i18n.dir() === "rtl";
  const { days, hours, minutes, isExpired } = useCountdown(ends_at);

  // Map tag to badge name
  const tagNames: Record<string, string> = {
    popular: t("offersPage.grid.categories.popular"),
    exclusive: t("offersPage.grid.categories.exclusive"),
    new: t("offersPage.grid.categories.new"),
    limited: t("offersPage.grid.categories.limited"),
  };

  const tagLabel = tag ? (tagNames[tag] || tag) : null;

  return (
    <article
      dir={i18n.dir()}
      className="w-full bg-white rounded-[20px] overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.04)] border border-gray-100/30 flex flex-col h-full hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition duration-300"
    >
      {/* Image container with absolute badge */}
      <div className="relative h-[210px] w-full overflow-hidden bg-gray-50">
        {tagLabel && (
          <span
            className={`absolute top-4 ${
              isRtl ? "right-4" : "left-4"
            } z-10 bg-[#FF9E3D] text-[#07111F] text-xs font-black px-3.5 py-1.5 rounded-full shadow-sm`}
          >
            {tagLabel}
          </span>
        )}
        <img
          src={image}
          alt={title}
          className="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
          loading="lazy"
        />
      </div>

      {/* Content details */}
      <div className="p-6 flex flex-col flex-grow">
        <h3 className="text-[18px] md:text-[20px] font-extrabold leading-[1.4] text-[#07111F] hover:text-[#FF9E3D] transition">
          {title}
        </h3>

        <p className="mt-2 text-sm leading-6 text-[#6B7280] line-clamp-2 flex-grow">
          {description}
        </p>

        {/* Countdown timer blocks (days, hours, minutes) */}
        {!isExpired ? (
          <div className="flex items-center gap-3 mt-5" dir={isRtl ? "rtl" : "ltr"}>
            {/* Days */}
            <div className="flex-1 flex flex-col items-center bg-[#F4F6F9] py-2.5 rounded-[12px] border border-gray-100/50 shadow-xs">
              <span className="text-[#07111F] font-extrabold text-[15px]">{days}</span>
              <span className="text-[#8A8F99] text-[10px] font-bold mt-0.5">{t("offersPage.countdown.days")}</span>
            </div>

            {/* Hours */}
            <div className="flex-1 flex flex-col items-center bg-[#F4F6F9] py-2.5 rounded-[12px] border border-gray-100/50 shadow-xs">
              <span className="text-[#07111F] font-extrabold text-[15px]">{hours}</span>
              <span className="text-[#8A8F99] text-[10px] font-bold mt-0.5">{t("offersPage.countdown.hours")}</span>
            </div>

            {/* Minutes */}
            <div className="flex-1 flex flex-col items-center bg-[#F4F6F9] py-2.5 rounded-[12px] border border-gray-100/50 shadow-xs">
              <span className="text-[#07111F] font-extrabold text-[15px]">{minutes}</span>
              <span className="text-[#8A8F99] text-[10px] font-bold mt-0.5">{t("offersPage.countdown.minutes")}</span>
            </div>
          </div>
        ) : (
          <div className="flex items-center justify-center bg-[#F4F6F9] py-3.5 rounded-[12px] border border-gray-100/50 mt-5">
            <span className="text-gray-500 font-bold text-xs">{t("offersPage.grid.card.continuous")}</span>
          </div>
        )}

        {/* Full-width Call to Action Button */}
        <NavLink
          to={buttonTo}
          className="w-full inline-flex h-[46px] items-center justify-center rounded-[12px] bg-[#0C1A30] text-white font-extrabold text-sm shadow-sm transition duration-200 hover:bg-[#FF9E3D] hover:text-[#07111F] hover:scale-[1.01] mt-5"
        >
          {t("offersPage.grid.card.benefit")}
        </NavLink>
      </div>
    </article>
  );
}
