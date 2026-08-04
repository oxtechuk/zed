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

  const tagNames: Record<string, string> = {
    popular: t("offersPage.grid.categories.popular", "الشائعة"),
    exclusive: t("offersPage.grid.categories.exclusive", "عرض حصري"),
    new: t("offersPage.grid.categories.new", "جديد"),
    limited: t("offersPage.grid.categories.limited", "لفترة محدودة"),
  };

  const tagLabel = tag ? (tagNames[tag] || tag) : null;

  return (
    <article
      dir={i18n.dir()}
      className="w-full bg-white rounded-2xl overflow-hidden border border-[#E7E9EF] flex flex-col h-full hover:shadow-[0_12px_32px_rgba(0,0,0,0.09)] transition duration-300 group"
    >
      {/* Image Container with Badge */}
      <div className="relative h-[220px] w-full overflow-hidden bg-[#F2F4F7]">
        {tagLabel && (
          <span
            className={`absolute top-4 ${
              isRtl ? "right-4" : "left-4"
            } z-10 bg-[#EDC98E] text-[#16254F] text-xs font-bold px-3.5 py-1.5 rounded-full shadow-sm`}
          >
            {tagLabel}
          </span>
        )}
        <img
          src={image}
          alt={title}
          className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
          loading="lazy"
        />
      </div>

      {/* Content Details */}
      <div className="p-6 flex flex-col flex-grow">
        <h3 className="text-[16px] font-black leading-[1.4] text-[#16254F] transition">
          {title}
        </h3>

        <p className="mt-1 text-[14px] leading-6 text-[#667085] line-clamp-2 flex-grow">
          {description}
        </p>

        {/* Countdown Timer Blocks */}
        {!isExpired ? (
          <div className="flex items-center gap-2 mt-4" dir={isRtl ? "rtl" : "ltr"}>
            {/* Days */}
            <div className="flex-1 flex flex-col items-center bg-[#FAFAFB] py-2 rounded-xl border border-[#E7E9EF]">
              <span className="text-[#16254F] font-black text-[14px]">{days}</span>
              <span className="text-[#667085] text-[9px] mt-0.5">{t("offersPage.countdown.days", "يوم")}</span>
            </div>

            {/* Hours */}
            <div className="flex-1 flex flex-col items-center bg-[#FAFAFB] py-2 rounded-xl border border-[#E7E9EF]">
              <span className="text-[#16254F] font-black text-[14px]">{hours}</span>
              <span className="text-[#667085] text-[9px] mt-0.5">{t("offersPage.countdown.hours", "ساعة")}</span>
            </div>

            {/* Minutes */}
            <div className="flex-1 flex flex-col items-center bg-[#FAFAFB] py-2 rounded-xl border border-[#E7E9EF]">
              <span className="text-[#16254F] font-black text-[14px]">{minutes}</span>
              <span className="text-[#667085] text-[9px] mt-0.5">{t("offersPage.countdown.minutes", "دقيقة")}</span>
            </div>
          </div>
        ) : (
          <div className="flex items-center justify-center bg-[#FAFAFB] py-3 rounded-xl border border-[#E7E9EF] mt-4">
            <span className="text-[#667085] font-semibold text-xs">{t("offersPage.grid.card.continuous", "مستمر حتى نفاذ الكمية")}</span>
          </div>
        )}

        {/* CTA Button */}
        <NavLink
          to={buttonTo}
          style={{color:"#fff"}}
          className="w-full inline-flex h-[44px] items-center justify-center rounded-2xl bg-[#16254F] text-white font-bold text-sm transition duration-200 hover:bg-[#EDC98E] hover:text-[#16254F] mt-4"
        >
          {t("offersPage.grid.card.benefit", "استفد من العرض")}
        </NavLink>
      </div>
    </article>
  );
}
