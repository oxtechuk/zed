import { useTranslation } from "react-i18next";
import { Clock } from "lucide-react";
import { NavLink } from "react-router-dom";
import { useCountdown } from "../../hooks/useCountdown";
import { APP_IMAGES, getImageUrl } from "../../constants/app-images";
import type { IFeaturedOfferBannerProps } from "../../interfaces/IFeaturedOfferBannerProps";

export default function FeaturedOfferBanner(props: Partial<IFeaturedOfferBannerProps> = {}) {
  const {
    id = 1,
    image,
    image_mobile,
    background_image,
    title,
    description,
    tag,
    badge,
    ends_at,
    button_text,
    button_url,
    hero,
  } = props;

  const { t, i18n } = useTranslation();

  const rawEndsAt = hero?.ends_at || ends_at;
  const { days, hours, minutes, seconds } = useCountdown(rawEndsAt);

  const tagNames: Record<string, string> = {
    popular: t("offersPage.grid.categories.popular", { defaultValue: "الشائعة" }),
    exclusive: t("offersPage.grid.categories.exclusive", { defaultValue: "عرض حصري" }),
    new: t("offersPage.grid.categories.new", { defaultValue: "جديد" }),
    limited: t("offersPage.grid.categories.limited", { defaultValue: "محدود" }),
  };

  const activeTag = hero?.tag || tag || badge;
  const tagLabel = activeTag
    ? tagNames[activeTag] || activeTag
    : t("offersPage.grid.categories.limited", { defaultValue: "محدود" });

  const displayTitle = hero?.title || title || t("offersPage.featured.title", { defaultValue: "عرض رمضان الاستثنائي" });
  const displayDesc = hero?.subtitle || description || t("offersPage.featured.description", { defaultValue: "تمويل بدون أرباح لأول 6 أشهر" });
  const displayButtonText = hero?.button_text || button_text || t("offersPage.hero.primaryButton", { defaultValue: "اطلع على العرض" });
  const displayButtonUrl = hero?.button_url || button_url || `/cars?offerId=${id}`;

  const countdownParts = [
    {
      value: String(days ?? 4).padStart(2, "0"),
      label: t("offersPage.countdown.days", { defaultValue: "يوم" }),
    },
    {
      value: String(hours ?? 23).padStart(2, "0"),
      label: t("offersPage.countdown.hours", { defaultValue: "ساعة" }),
    },
    {
      value: String(minutes ?? 40).padStart(2, "0"),
      label: t("offersPage.countdown.minutes", { defaultValue: "دقيقة" }),
    },
    {
      value: String(seconds ?? 3).padStart(2, "0"),
      label: t("offersPage.countdown.seconds", { defaultValue: "ثانية" }),
    },
  ];

  const desktopImageRaw = hero?.image || image || background_image;
  const mobileImageRaw = hero?.image_mobile || image_mobile || desktopImageRaw;

  const desktopBgUrl = desktopImageRaw ? getImageUrl(desktopImageRaw) : APP_IMAGES.OFFERS_SECTION_BG;
  const mobileBgUrl = mobileImageRaw ? getImageUrl(mobileImageRaw) : desktopBgUrl;

  return (
    <div
      dir={i18n.dir()}
      className="relative w-full rounded-[24px] sm:rounded-[32px] text-white overflow-hidden shadow-2xl mb-10 select-none bg-[#0B1736] min-h-[250px] sm:min-h-[280px] flex items-center"
    >
      {/* Desktop Background */}
      <div
        className="hidden md:block absolute inset-0 bg-cover bg-center transition-all duration-500"
        style={{ backgroundImage: `url("${desktopBgUrl}")` }}
      />

      {/* Mobile Background */}
      <div
        className="block md:hidden absolute inset-0 bg-cover bg-center transition-all duration-500"
        style={{ backgroundImage: `url("${mobileBgUrl}")` }}
      />

      {/* Dark Navy Gradient Overlay */}
      <div className="absolute inset-0 bg-gradient-to-r from-[#0B1736]/90 via-[#0B1736]/70 to-[#0B1736]/35 pointer-events-none" />

      {/* Main Inner Flex Container */}
      <div className="relative z-10 flex flex-col md:flex-row items-center justify-between w-full px-6 py-8 sm:px-10 sm:py-10 gap-8 text-start">
        {/* Left Side (RTL) / Main Content */}
        <div className="flex flex-col items-start max-w-xl">
          {/* Tag Pill */}
          <span className="inline-block bg-[#F3C77C] text-[#0B1736] text-[12px] sm:text-[13px] font-black px-4 py-1 rounded-full mb-3 shadow-xs">
            {tagLabel}
          </span>

          {/* Title */}
          <h2 className="text-[26px] sm:text-[32px] md:text-[36px] font-black text-white leading-tight mb-2">
            {displayTitle}
          </h2>

          {/* Description */}
          <p className="text-[14px] sm:text-[16px] font-semibold text-white/80 leading-relaxed mb-6">
            {displayDesc}
          </p>

          {/* CTA Button */}
          <NavLink
            to={displayButtonUrl}
            className="h-[48px] sm:h-[52px] px-8 rounded-2xl sm:rounded-[18px] bg-[#F3C77C] hover:bg-[#E2B66B] text-[14px] sm:text-[15px] font-black text-[#0B1736] transition-all duration-200 active:scale-95 shadow-md inline-flex items-center justify-center"
          >
            {displayButtonText}
          </NavLink>
        </div>

        {/* Right Side (RTL) / Countdown Section */}
        <div className="flex flex-col items-start md:items-start shrink-0">
          {/* Notice Header */}
          <div className="flex items-center gap-1.5 text-white/70 text-[12px] sm:text-[13px] font-bold mb-1">
            <Clock size={14} className="text-[#F3C77C]" />
            <span>{t("offersPage.countdown.requestEnds", { defaultValue: "العرض ينتهي قريباً" })}</span>
          </div>

          {/* Sublabel */}
          <span className="block text-white/40 text-[11px] sm:text-[12px] font-semibold mb-3">
            {t("offersPage.countdown.endsIn", { defaultValue: "ينتهي العرض خلال" })}
          </span>

          {/* 4 Timer Boxes */}
          <div className="flex items-center gap-2.5 sm:gap-3" dir="ltr">
            {countdownParts.map((part) => (
              <div key={part.label} className="flex flex-col items-center">
                <div className="flex h-14 w-14 sm:h-16 sm:w-16 items-center justify-center rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md text-[20px] sm:text-[24px] font-black text-white shadow-xs">
                  {part.value}
                </div>
                <span className="mt-1.5 text-[10px] sm:text-[11px] font-extrabold text-white/60">
                  {part.label}
                </span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
