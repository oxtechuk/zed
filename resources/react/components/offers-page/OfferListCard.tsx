import { useTranslation } from "react-i18next";
import { NavLink } from "react-router-dom";
import { useCountdown } from "../../hooks/useCountdown";
import { APP_IMAGES, getImageUrl } from "../../constants/app-images";
import type { IOfferListCardProps } from "../../interfaces/IOfferListCardProps";

export default function OfferListCard({
    id,
    image,
    title,
    description,
    tag,
    ends_at,
    buttonTo,
}: Partial<IOfferListCardProps>) {
    const { i18n, t } = useTranslation();
    const { days, hours, minutes } = useCountdown(ends_at);

    const tagNames: Record<string, string> = {
        popular: t("offersPage.grid.categories.popular", {
            defaultValue: "الشائعة",
        }),
        exclusive: t("offersPage.grid.categories.exclusive", {
            defaultValue: "عرض حصري",
        }),
        new: t("offersPage.grid.categories.new", { defaultValue: "جديد" }),
        limited: t("offersPage.grid.categories.limited", {
            defaultValue: "محدود",
        }),
    };

    const tagLabel = tag
        ? tagNames[tag] || tag
        : t("offersPage.grid.categories.limited", { defaultValue: "محدود" });
    const cardImage =
        getImageUrl(image ?? null) || APP_IMAGES.OFFERS_SECTION_BG;
    const linkTarget = buttonTo || `/cars?offerId=${id}`;

    const countdownBoxes = [
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
    ];

    return (
        <article
            dir={i18n.dir()}
            className="w-full bg-white rounded-[24px] sm:rounded-[28px] overflow-hidden border border-gray-200 flex flex-col h-full shadow-xs hover:shadow-xl transition-all duration-300 group select-none"
        >
            {/* Top Image Section with Badge */}
            <div className="relative h-[210px] sm:h-[230px] w-full overflow-hidden bg-[#0B1736]">
                {tagLabel && (
                    <span className="absolute top-4 start-4 z-10 bg-[#F3C77C] text-[#0B1736] text-[12px] sm:text-[13px] font-black px-4 py-1.5 rounded-full shadow-xs">
                        {tagLabel}
                    </span>
                )}
                <img
                    src={cardImage}
                    alt={title ?? "Offer"}
                    className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                    loading="lazy"
                />
            </div>

            {/* Content Details */}
            <div className="p-5 sm:p-6 flex flex-col flex-grow text-start">
                {/* Title */}
                <h3 className="text-[20px] sm:text-[22px] font-black text-[#16254F] leading-tight mb-1 text-start">
                    {title ??
                        t("offersPage.featured.title", {
                            defaultValue: "عرض رمضان الاستثنائي",
                        })}
                </h3>

                {/* Subtitle / Description */}
                <p className="text-[14px] sm:text-[15px] font-semibold text-gray-500 leading-relaxed mb-5 text-start flex-grow">
                    {description ??
                        t("offersPage.featured.description", {
                            defaultValue: "تمويل بدون أرباح لأول 6 أشهر",
                        })}
                </p>

                {/* 3 Countdown Boxes Grid */}
                <div className="grid grid-cols-3 gap-3 mb-5 pt-6" dir="ltr">
                    {countdownBoxes.map((box) => (
                        <div
                            key={box.label}
                            className="flex flex-col items-center justify-center bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl py-2.5 sm:py-3"
                        >
                            <span className="text-[18px] sm:text-[20px] font-black text-[#0B1736] leading-none mb-1">
                                {box.value}
                            </span>
                            <span className="text-[11px] font-extrabold text-gray-400">
                                {box.label}
                            </span>
                        </div>
                    ))}
                </div>

                {/* Bottom Action Button */}
                <NavLink
                    to={linkTarget}
                    className="w-full h-[48px] sm:h-[52px] rounded-2xl sm:rounded-[18px] bg-[#16254F] hover:bg-[#12224A] text-white! font-black text-[14px] sm:text-[15px] transition-all duration-200 active:scale-98 flex items-center justify-center shadow-md cursor-pointer"
                >
                    {t("offersPage.grid.card.benefit", {
                        defaultValue: "استفد من العرض",
                    })}
                </NavLink>
            </div>
        </article>
    );
}
