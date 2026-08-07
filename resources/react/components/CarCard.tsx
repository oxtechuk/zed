import { useTranslation } from "react-i18next";
import { useNavigate } from "react-router-dom";
import { ArrowUpRight, Scale, Users } from "lucide-react";
import type { ICarCardProps } from "../interfaces/ICarCardProps";

export type { ICarCardProps as CarCardProps };

const BADGE_COLOR_MAP: Record<string, { bg: string; text: string }> = {
    "#EDC98E": { bg: "#EDC98E", text: "#16254F" },
    "#ED8EB7": { bg: "#ED8EB7", text: "#A30D5D" },
    "#90ED8E": { bg: "#90ED8E", text: "#1B7A1B" },
};

const DEFAULT_BADGE = { bg: "#EDC98E", text: "#16254F" };

export default function CarCard({
    id,
    image,
    brand,
    name,
    year,
    type,
    fuelType,
    transmission,
    seats,
    price,
    monthlyPrice,
    detailsTo,
    slug,
    compareText,
    reserveText,
    badgeText,
    badgeColor,
}: ICarCardProps) {
    const { t, i18n } = useTranslation();
    const navigate = useNavigate();

    const displayBadge =
        badgeText || t("carCard.newlyArrived", { defaultValue: "وصل حديثاً" });
    const badgeStyle =
        (badgeColor && BADGE_COLOR_MAP[badgeColor.toUpperCase()]) ||
        DEFAULT_BADGE;
    const displaySeats =
        seats && !String(seats).includes("مقاعد") ? `${seats} مقاعد` : seats;

    return (
        <article
            dir={i18n.dir()}
            onClick={() => navigate(detailsTo)}
            className="relative mx-auto w-full max-w-[355px] cursor-pointer overflow-hidden rounded-2xl border border-[#E7E9EF] bg-white transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md text-start"
        >
            {/* Newly Arrived Badge */}
            <div
                className="absolute top-3 end-3 z-10 rounded-full px-3 py-1 text-[11px] font-black"
                style={{
                    backgroundColor: badgeStyle.bg,
                    color: badgeStyle.text,
                }}
            >
                {displayBadge}
            </div>

            {/* Image Area */}
            <div className="relative h-[208px] overflow-hidden">
                <img
                    src={image}
                    alt={`${brand} ${name}`}
                    className="h-full w-full object-cover"
                    loading="lazy"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent" />
            </div>

            {/* Info Block */}
            <div className="p-5">
                {/* Brand + Name + Type Badge */}
                <div className="flex items-start justify-between gap-3">
                    <div className="flex-1 text-start">
                        <p className="mb-1 text-[11px] font-semibold leading-none text-[#667085]">
                            {brand} · {year}
                        </p>
                        <h3
                            className="truncate text-[16px] font-black leading-tight text-[#16254F]"
                            title={`${brand} ${name}`}
                        >
                            {name}
                        </h3>
                    </div>

                    {type && (
                        <span className="inline-block rounded-xl bg-[#F3F4F6] px-2.5 py-1 text-[10px] font-semibold text-[#667085] whitespace-nowrap">
                            {type}
                        </span>
                    )}
                </div>

                {/* Specs Badges */}
                <div className="mt-4 flex flex-wrap items-center justify-start gap-1.5">
                    {fuelType && (
                        <span className="inline-flex items-center rounded-xl bg-[#F3F4F6] px-2.5 py-1 text-[11px] font-semibold text-[#667085]">
                            {fuelType}
                        </span>
                    )}
                    {displaySeats && (
                        <span className="inline-flex items-center gap-1 rounded-xl bg-[#F3F4F6] px-2.5 py-1 text-[11px] font-semibold text-[#667085]">
                            <Users size={12} className="text-[#667085]" />
                            <span>{displaySeats}</span>
                        </span>
                    )}
                    {transmission && (
                        <span className="inline-flex items-center rounded-xl bg-[#F3F4F6] px-2.5 py-1 text-[11px] font-semibold text-[#667085]">
                            {transmission}
                        </span>
                    )}
                </div>

                {/* Pricing Block */}
                <div className="mt-4 border-t border-[#E7E9EF] pt-3">
                    <div className="flex items-start justify-between">
                        {/* Cash Price */}
                        <div className="text-start">
                            <p className="mb-1 text-[10px] font-normal text-[#667085]">
                                {t("carCard.cashPrice")}
                            </p>
                            <p className="text-[18px] font-black leading-none text-[#16254F]">
                                {price}
                            </p>
                        </div>

                        {/* Monthly Payment */}
                        <div className="text-start">
                            <p className="mb-1 text-[10px] font-normal text-[#667085]">
                                {t("carCard.monthlyPayment")}
                            </p>
                            <p className="text-[16px] font-black leading-none text-[#EDC98E]">
                                {monthlyPrice}
                            </p>
                        </div>
                    </div>
                </div>

                {/* Action Row */}
                <div
                    className="mt-4 flex items-center gap-2"
                    onClick={(e) => e.stopPropagation()}
                >
                    {/* Order Button */}
                    <button
                        type="button"
                        onClick={() => navigate(`/request-car?car_id=${id}`)}
                        className="h-10 flex-1 rounded-2xl bg-[#16254F] text-[14px] font-bold text-white transition hover:bg-[#0F1E36] active:scale-95 flex items-center justify-center gap-1.5"
                    >
                        <span>
                            {reserveText ??
                                t("carCard.reserve", {
                                    defaultValue: "اطلبها الآن",
                                })}
                        </span>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="14"
                            height="14"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            strokeWidth="2.5"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            className="rtl:rotate-180"
                        >
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>

                    {/* Compare Button */}
                    <button
                        type="button"
                        onClick={(e) => {
                            e.stopPropagation();
                            navigate(`/compare?slug=${slug ?? ""}`);
                        }}
                        className="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-[#E7E9EF] text-[#667085] transition hover:border-[#16254F] hover:text-[#16254F] active:scale-95"
                        title={compareText ?? t("carCard.compare")}
                    >
                        <Scale size={16} />
                    </button>

                    {/* Details / Arrow Link Button */}
                    <button
                        type="button"
                        onClick={() => navigate(detailsTo)}
                        className="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-[#E7E9EF] text-[#667085] transition hover:border-[#16254F] hover:text-[#16254F] active:scale-95"
                    >
                        <ArrowUpRight size={16} />
                    </button>
                </div>
            </div>
        </article>
    );
}
