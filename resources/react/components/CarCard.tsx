import { useTranslation } from "react-i18next";
import { useNavigate } from "react-router-dom";
import { ArrowUpRight, Users } from "lucide-react";
import type { ICarCardProps } from "../interfaces/ICarCardProps";
import LazyImg from "./LazyImg";

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
    model,
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
    loading,
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

    const cleanType =
        type &&
        !/^[a-z0-9]+-[a-f0-9]{4,}$/i.test(type) &&
        !type.includes("6a7") &&
        !type.includes("family-") &&
        !type.includes("fs-")
            ? type
            : "";
    const displayModel = model || cleanType;

    return (
        <article
            dir={i18n.dir()}
            onClick={() => navigate(detailsTo)}
            className="group relative mx-auto w-full max-w-[355px] cursor-pointer overflow-hidden rounded-2xl border border-[#E7E9EF] bg-white transition-all duration-300 hover:-translate-y-1.5 hover:shadow-lg text-start"
        >
            {/* Newly Arrived Badge */}
            <div
                className="absolute top-3 end-3 z-10 rounded-full px-3 py-1 text-[11px] font-black shadow-xs"
                style={{
                    backgroundColor: badgeStyle.bg,
                    color: badgeStyle.text,
                }}
            >
                {displayBadge}
            </div>

            {/* Image Area */}
            <div className="relative h-[240px] overflow-hidden bg-[#F8FAFC]">
                <LazyImg
                    src={image}
                    alt={`${brand} ${name}`}
                    loading={loading}
                    className="h-full w-full object-cover object-bottom transition-transform duration-500 group-hover:scale-105"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent" />

                {/* Hover / Touch "عرض الآن" Overlay Pill */}
                <div className="absolute inset-0 bg-black/25 opacity-0 transition-all duration-300 group-hover:opacity-100 flex items-center justify-center pointer-events-none z-10">
                    <div className="flex items-center gap-1.5 rounded-full bg-white/95 backdrop-blur-md px-4 py-2 text-[13px] font-black text-[#16254F] shadow-xl transform translate-y-3 group-hover:translate-y-0 transition-all duration-300">
                        <span>
                            {t("carCard.viewNow", {
                                defaultValue: "عرض الآن",
                            })}
                        </span>
                        <ArrowUpRight size={15} strokeWidth={2.5} />
                    </div>
                </div>
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

                    {displayModel && (
                        <span className="inline-block rounded-xl bg-[#F3F4F6] px-2.5 py-1 text-[10px] font-semibold text-[#667085] whitespace-nowrap">
                            {displayModel}
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
                    {/* Order Button (Wide & Primary) */}
                    <button
                        type="button"
                        onClick={() => navigate(`/request-car?car_id=${id}`)}
                        className="h-10 flex-1 px-4 rounded-xl bg-[#16254F] hover:bg-[#0F1E36] text-white text-[13px] font-black transition-all duration-200 active:scale-95 flex items-center justify-center gap-1.5 shadow-sm cursor-pointer whitespace-nowrap"
                    >
                        <span>
                            {reserveText ??
                                t("carCard.reserve", {
                                    defaultValue: "اطلبها الآن",
                                })}
                        </span>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="13"
                            height="13"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            strokeWidth="2.5"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            className="rtl:rotate-180 shrink-0"
                        >
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>

                    {/* Compare Button (Text Only) */}
                    <button
                        type="button"
                        onClick={(e) => {
                            e.stopPropagation();
                            navigate(`/compare?slug=${slug ?? ""}`);
                        }}
                        className="h-10 px-4 rounded-xl border border-[#E7E9EF] bg-[#F8FAFC] hover:bg-[#F1F5F9] hover:border-[#16254F]/30 text-[#475569] hover:text-[#16254F] text-[13px] font-black transition-all duration-200 active:scale-95 flex items-center justify-center shrink-0 shadow-2xs cursor-pointer whitespace-nowrap"
                        title={t("carCard.compare", { defaultValue: "مقارنة" })}
                    >
                        <span>{t("carCard.compare", { defaultValue: "مقارنة" })}</span>
                    </button>
                </div>
            </div>
        </article>
    );
}
