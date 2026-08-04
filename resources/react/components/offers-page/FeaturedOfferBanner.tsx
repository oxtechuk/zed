import { useTranslation } from "react-i18next";
import { Clock } from "lucide-react";
import { NavLink } from "react-router-dom";
import { useCountdown } from "../../hooks/useCountdown";

interface FeaturedOfferBannerProps {
  id: string | number;
  image?: string;
  title: string;
  description: string;
  tag?: string;
  ends_at?: string;
}

export default function FeaturedOfferBanner({
  id,
  image,
  title,
  description,
  tag,
  ends_at,
}: FeaturedOfferBannerProps) {
  const { t, i18n } = useTranslation();
  const { days, hours, minutes, seconds } = useCountdown(ends_at);

  const tagNames: Record<string, string> = {
    popular: t("offersPage.grid.categories.popular", "الشائعة"),
    exclusive: t("offersPage.grid.categories.exclusive", "عرض حصري"),
    new: t("offersPage.grid.categories.new", "جديد"),
    limited: t("offersPage.grid.categories.limited", "لفترة محدودة"),
  };

  const tagLabel = tag ? (tagNames[tag] || tag) : t("offersPage.grid.categories.limited", "لفترة محدودة");

  const countdownParts = [
    { value: days, label: t("offersPage.countdown.days", "يوم") },
    { value: hours, label: t("offersPage.countdown.hours", "ساعة") },
    { value: minutes, label: t("offersPage.countdown.minutes", "دقيقة") },
    { value: seconds, label: t("offersPage.countdown.seconds", "ثانية") },
  ];

  return (
    <div
      dir={i18n.dir()}
      className="relative w-full rounded-[24px] text-white overflow-hidden shadow-2xl mb-10"
      style={{
        backgroundImage: image
          ? `linear-gradient(270deg, rgba(22,37,79,0.98) 0%, rgba(22,37,79,0.70) 50%, rgba(0,0,0,0) 100%), url(${image})`
          : "linear-gradient(90deg, #16254F 0%, #0D1730 60%, #080E1E 100%)",
        backgroundSize: "cover",
        backgroundPosition: "center",
      }}
    >
      <div className="relative z-10 flex flex-col items-center gap-8 px-6 py-8 md:flex-row md:items-center md:justify-between md:px-12 md:py-10 text-start">
        {/* Content: title / description / CTA */}
        <div className="flex w-full flex-col items-start md:w-auto">
          {/* Top row: countdown notice + tag pill (stacked, right-aligned) */}
          <div className="flex flex-col items-start gap-2">
            <div className="flex items-center gap-1.5 text-white/50 text-xs">
              <span>{t("offersPage.countdown.requestEnds", "العرض ينتهي قريباً")}</span>
              <Clock size={12} />
            </div>
            <span className="inline-block bg-[#EDC98E] text-[#16254F] text-xs font-black px-3 py-1.5 rounded-full">
              {tagLabel}
            </span>
          </div>

          <h2 className="mt-4 text-[26px] md:text-[32px] font-black text-white leading-tight">
            {title}
          </h2>

          <p className="mt-1 max-w-md text-[15px] md:text-[16px] text-white/60 leading-relaxed">
            {description}
          </p>

          <NavLink
            to={`/cars?offerId=${id}`}
            className="mt-5 inline-flex h-[48px] items-center justify-center rounded-2xl bg-[#EDC98E] px-7 text-[14px] font-black text-[#16254F] shadow-lg transition duration-200 hover:bg-white hover:scale-[1.02]"
          >
            {t("offersPage.hero.primaryButton", "اطلع على العرض")}
          </NavLink>
        </div>

        {/* Countdown Timer */}
        <div className="flex w-full flex-col items-center md:w-auto md:items-end">
          <span className="block text-white/40 text-xs font-semibold mb-3">
            {t("offersPage.countdown.endsIn", "ينتهي العرض خلال")}
          </span>
          <div className="flex items-start gap-3" dir="ltr">
            {countdownParts.map((part) => (
              <div key={part.label} className="flex flex-col items-center">
                <div className="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/15 bg-white/10 text-[24px] font-black text-white">
                  {part.value}
                </div>
                <span className="mt-1 text-[10px] text-white/40">{part.label}</span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
