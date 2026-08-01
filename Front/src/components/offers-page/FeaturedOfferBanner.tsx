import { useTranslation } from "react-i18next";
import { Clock } from "lucide-react";
import { NavLink } from "react-router-dom";
import { APP_IMAGES } from "../../constants/app-images";
import { useCountdown } from "../../hooks/useCountdown";

interface FeaturedOfferBannerProps {
  id: string | number;
  title: string;
  description: string;
  tag?: string;
  ends_at?: string;
}

export default function FeaturedOfferBanner({
  id,
  title,
  description,
  tag,
  ends_at,
}: FeaturedOfferBannerProps) {
  const { t, i18n } = useTranslation();
  const isRtl = i18n.dir() === "rtl";
  const { days, hours, minutes, seconds } = useCountdown(ends_at);

  // Map tag to name
  const tagNames: Record<string, string> = {
    popular: t("offersPage.grid.categories.popular"),
    exclusive: t("offersPage.grid.categories.exclusive"),
    new: t("offersPage.grid.categories.new"),
    limited: t("offersPage.grid.categories.limited"),
  };

  const tagLabel = tag ? (tagNames[tag] || tag) : t("offersPage.grid.categories.limited");

  return (
    <div
      dir={i18n.dir()}
      className="relative w-full rounded-[24px] bg-gradient-to-r from-[#16254F] to-[#08111F] text-white overflow-hidden shadow-xl min-h-[340px] flex flex-col lg:flex-row items-center p-6 md:p-12 mb-12"
    >
      {/* Decorative Moon & Calligraphy Image (eid.png) on the Left (or background) */}
      <div
        className={`absolute top-0 bottom-0 ${isRtl ? 'left-0' : 'right-0'} w-full lg:w-1/2 opacity-20 lg:opacity-30 pointer-events-none z-0`}
        style={{
          backgroundImage: `url(${APP_IMAGES.EID})`,
          backgroundSize: 'contain',
          backgroundPosition: isRtl ? 'left center' : 'right center',
          backgroundRepeat: 'no-repeat'
        }}
      />

      {/* Main Content Area */}
      <div className="relative z-10 w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-start mb-8 lg:mb-0">
        <span className="inline-block bg-[#EDC98E] text-[#07111F] text-xs font-extrabold px-4 py-1.5 rounded-full shadow-sm">
          {tagLabel}
        </span>

        <h2 className="mt-5 text-[28px] md:text-[38px] font-extrabold text-white leading-tight">
          {title}
        </h2>

        <p className="mt-3 text-base md:text-lg text-gray-300 max-w-lg leading-relaxed">
          {description}
        </p>

        <NavLink
          to={`/cars?offerId=${id}`}
          className="mt-8 inline-flex h-[48px] items-center justify-center rounded-full bg-[#EDC98E] px-8 text-[16px] font-bold text-[#07111F] shadow-lg transition hover:bg-white hover:scale-105"
        >
          {t("offersPage.hero.primaryButton")}
        </NavLink>
      </div>

      {/* Countdown Timer Area */}
      <div className="relative z-10 w-full lg:w-1/2 flex flex-col items-center lg:items-end">
        {/* Header/Subtext */}
        <div className="flex items-center gap-2 text-gray-300 font-bold mb-2">
          <Clock size={18} className="text-[#EDC98E]" />
          <span className="text-sm md:text-base">{t("offersPage.countdown.requestEnds")}</span>
        </div>

        <span className="text-xs text-gray-400 mb-6 font-medium">
          {t("offersPage.countdown.endsIn")}
        </span>

        {/* 4 circles countdown */}
        <div className="flex items-center gap-4 md:gap-5" dir="ltr">
          {/* Days */}
          <div className="flex flex-col items-center">
            <div className="w-[60px] h-[60px] md:w-[72px] md:h-[72px] rounded-full border-2 border-white/20 bg-white/5 flex items-center justify-center text-[22px] md:text-[26px] font-black text-white shadow-inner">
              {days}
            </div>
            <span className="mt-2 text-xs md:text-sm font-semibold text-gray-300">
              {t("offersPage.countdown.days")}
            </span>
          </div>

          {/* Hours */}
          <div className="flex flex-col items-center">
            <div className="w-[60px] h-[60px] md:w-[72px] md:h-[72px] rounded-full border-2 border-white/20 bg-white/5 flex items-center justify-center text-[22px] md:text-[26px] font-black text-white shadow-inner">
              {hours}
            </div>
            <span className="mt-2 text-xs md:text-sm font-semibold text-gray-300">
              {t("offersPage.countdown.hours")}
            </span>
          </div>

          {/* Minutes */}
          <div className="flex flex-col items-center">
            <div className="w-[60px] h-[60px] md:w-[72px] md:h-[72px] rounded-full border-2 border-white/20 bg-white/5 flex items-center justify-center text-[22px] md:text-[26px] font-black text-white shadow-inner">
              {minutes}
            </div>
            <span className="mt-2 text-xs md:text-sm font-semibold text-gray-300">
              {t("offersPage.countdown.minutes")}
            </span>
          </div>

          {/* Seconds */}
          <div className="flex flex-col items-center">
            <div className="w-[60px] h-[60px] md:w-[72px] md:h-[72px] rounded-full border-2 border-white/20 bg-[#EDC98E]/10 flex items-center justify-center text-[22px] md:text-[26px] font-black text-[#EDC98E] shadow-inner">
              {seconds}
            </div>
            <span className="mt-2 text-xs md:text-sm font-semibold text-gray-300">
              {t("offersPage.countdown.seconds")}
            </span>
          </div>
        </div>
      </div>
    </div>
  );
}
