import { useTranslation } from "react-i18next";
import { useNavigate } from "react-router-dom";
import { ArrowUpRight, Scale, Users } from "lucide-react";
import type { ICarCardProps } from "../interfaces/ICarCardProps";

export type { ICarCardProps as CarCardProps };

export default function CarCard({
  image,
  brand,
  name,
  year,
  type,
  fuelType,
  transmission,
  price,
  monthlyPrice,
  detailsTo,
  slug,
  compareText,
  reserveText,
  badgeText,
}: ICarCardProps) {
  const { t, i18n } = useTranslation();
  const navigate = useNavigate();

  // Dynamic badge text fallback
  const displayBadge = badgeText || t("carCard.newlyArrived", { defaultValue: "وصل حديثاً" });

  return (
    <article
      dir={i18n.dir()}
      onClick={() => navigate(detailsTo)}
      className="relative mx-auto w-full max-w-[350px] cursor-pointer overflow-hidden rounded-[28px] border border-[#E5E9F0] bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md text-start"
    >
      {/* Newly Arrived Badge */}
      <div className="absolute top-5 start-5 z-10 rounded-lg bg-[#FFF4E4] border border-[#FFE4D6]/30 px-3.5 py-1.5 text-[11px] font-black text-[#D97706]">
        {displayBadge}
      </div>

      {/* Image Area */}
      <div className="relative flex h-[190px] items-center justify-center pt-4">
        <img
          src={image}
          alt={`${brand} ${name}`}
          className="max-h-full max-w-[90%] object-contain"
          loading="lazy"
        />
      </div>

      {/* Info Block */}
      <div className="mt-5">
        {/* Brand + Name + Type Badge */}
        <div className="flex items-start justify-between gap-3">
          <div className="flex-1">
            <p className="text-[12px] text-gray-400 font-extrabold leading-none mb-1.5">
              {brand} · {year}
            </p>
            <h3 className="text-[19px] font-black text-[#0F172A] leading-tight truncate" title={`${brand} ${name}`}>
              {name}
            </h3>
          </div>
          {type && (
            <span className="inline-block bg-[#F1F5F9] text-[#64748B] text-[11px] font-bold px-3 py-1 rounded-lg whitespace-nowrap">
              {type}
            </span>
          )}
        </div>

        {/* Specs Badges */}
        <div className="flex flex-wrap gap-2 mt-4">
          {transmission && (
            <span className="inline-flex items-center bg-[#F8FAFC] border border-gray-100 text-gray-500 text-[12px] font-extrabold px-3 py-1.5 rounded-xl">
              {transmission}
            </span>
          )}
          {/* Default seats to 5 if not set to match screenshot */}
          <span className="inline-flex items-center gap-1.5 bg-[#F8FAFC] border border-gray-100 text-gray-500 text-[12px] font-extrabold px-3 py-1.5 rounded-xl">
            <Users size={13} className="text-gray-400" />
            <span>5 مقاعد</span>
          </span>
          {fuelType && (
            <span className="inline-flex items-center bg-[#F8FAFC] border border-gray-100 text-gray-500 text-[12px] font-extrabold px-3 py-1.5 rounded-xl">
              {fuelType}
            </span>
          )}
        </div>

        {/* Divider Line */}
        <hr className="border-t border-[#EEF2F6] my-5" />

        {/* Pricing Block */}
        <div className="flex items-center justify-between mb-5">
          {/* Cash Price */}
          <div>
            <p className="text-[11px] text-gray-400 font-bold mb-1">{t("carCard.cashPrice")}</p>
            <p className="text-[20px] font-black text-[#0F172A]">
              {price}
            </p>
          </div>

          {/* Monthly Payment */}
          <div className="text-end">
            <p className="text-[11px] text-gray-400 font-bold mb-1">{t("carCard.monthlyPayment")}</p>
            <p className="text-[20px] font-black text-[#EDC98E]">
              {monthlyPrice}
            </p>
          </div>
        </div>

        {/* Action Row */}
        <div className="flex items-center gap-2.5" onClick={(e) => e.stopPropagation()}>
          {/* Order Button first in HTML so in RTL it displays on the right side */}
          <button
            type="button"
            onClick={() => navigate(detailsTo)}
            className="flex-1 h-[48px] rounded-2xl bg-[#0F172A] text-[15px] font-extrabold text-white transition hover:bg-[#1E293B] hover:shadow-xs flex items-center justify-center gap-1.5 active:scale-95"
          >
            <span>{reserveText ?? t("carCard.reserve", { defaultValue: "اطلبها الآن" })}</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" className="rtl:rotate-180">
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
            className="flex h-[48px] w-[48px] shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition hover:border-[#0F172A] hover:text-[#0F172A] hover:shadow-xs active:scale-95"
            title={compareText ?? t("carCard.compare")}
          >
            <Scale size={17} />
          </button>

          {/* Details / Arrow Link Button */}
          <button
            type="button"
            onClick={() => navigate(detailsTo)}
            className="flex h-[48px] w-[48px] shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition hover:border-[#0F172A] hover:text-[#0F172A] hover:shadow-xs active:scale-95"
          >
            <ArrowUpRight size={17} />
          </button>
        </div>
      </div>
    </article>
  );
}
