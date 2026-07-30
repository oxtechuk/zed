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
  seats,
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
      className="relative mx-auto w-full max-w-[340px] cursor-pointer overflow-hidden rounded-[24px] border border-[#E5E9F0] bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md"
    >
      {/* Newly Arrived Badge */}
      <div className="absolute top-4 left-4 z-10 rounded-full bg-[#E5C287] px-3.5 py-1.5 text-[12px] font-extrabold text-[#0A1628]">
        {displayBadge}
      </div>

      {/* Image Area */}
      <div className="relative flex h-[190px] items-center justify-center pt-4">
        <img
          src={image}
          alt={`${brand} ${name}`}
          className="max-h-full max-w-[85%] object-contain"
          loading="lazy"
        />
      </div>

      {/* Info Block */}
      <div className="mt-4 text-right">
        {/* Brand + Name + Type Badge */}
        <div className="flex items-start justify-between gap-2">
          <div className="flex-1">
            <p className="text-[12px] text-[#64748B] font-bold leading-none mb-1.5">
              {brand} · {year}
            </p>
            <h3 className="text-[20px] font-extrabold text-[#0F172A] leading-tight truncate" title={`${brand} ${name}`}>
              {name}
            </h3>
          </div>
          {type && (
            <span className="inline-block bg-[#F1F5F9] text-[#64748B] text-[11px] font-bold px-3 py-1 rounded-[8px] whitespace-nowrap">
              {type}
            </span>
          )}
        </div>

        {/* Specs Badges */}
        <div className="flex flex-wrap gap-1.5 mt-3.5">
          {transmission && (
            <span className="inline-flex items-center bg-[#F3F4F6] text-[#4B5563] text-[12px] font-semibold px-3 py-1.5 rounded-[12px]">
              {transmission}
            </span>
          )}
          {seats && (
            <span className="inline-flex items-center gap-1 bg-[#F3F4F6] text-[#4B5563] text-[12px] font-semibold px-3 py-1.5 rounded-[12px]">
              <Users size={13} className="text-[#6B7280]" />
              <span>{seats}</span>
            </span>
          )}
          {fuelType && (
            <span className="inline-flex items-center bg-[#F3F4F6] text-[#4B5563] text-[12px] font-semibold px-3 py-1.5 rounded-[12px]">
              {fuelType}
            </span>
          )}
        </div>

        {/* Divider Line */}
        <hr className="border-t border-[#EEF2F6] my-4" />

        {/* Pricing Block */}
        <div className="flex items-center justify-between mb-5">
          {/* Cash Price */}
          <div className="text-right">
            <p className="text-[11px] text-[#9CA3AF] mb-0.5">{t("carCard.cashPrice")}</p>
            <p className="text-[19px] font-extrabold text-[#0F172A]">
              {price}
            </p>
          </div>

          {/* Monthly Payment */}
          <div className="text-left">
            <p className="text-[11px] text-[#9CA3AF] mb-0.5">{t("carCard.monthlyPayment")}</p>
            <p className="text-[19px] font-extrabold text-[#E5C287]">
              {monthlyPrice}
            </p>
          </div>
        </div>

        {/* Action Row */}
        <div className="flex items-center gap-2" onClick={(e) => e.stopPropagation()}>
          {/* Compare Button */}
          <button
            type="button"
            onClick={(e) => {
              e.stopPropagation();
              navigate(`/compare?slug=${slug ?? ""}`);
            }}
            className="flex h-[48px] w-[48px] shrink-0 items-center justify-center rounded-full border border-[#E5E7EB] bg-white text-[#4B5563] transition hover:border-[#0F172A] hover:text-[#0F172A] hover:shadow-sm"
            title={compareText ?? t("carCard.compare")}
          >
            <Scale size={18} />
          </button>

          {/* Arrow Link Button */}
          <button
            type="button"
            onClick={() => navigate(detailsTo)}
            className="flex h-[48px] w-[48px] shrink-0 items-center justify-center rounded-full border border-[#E5E7EB] bg-white text-[#4B5563] transition hover:border-[#0F172A] hover:text-[#0F172A] hover:shadow-sm"
          >
            <ArrowUpRight size={18} />
          </button>

          {/* Reserve / Order Button */}
          <button
            type="button"
            onClick={() => navigate(detailsTo)}
            className="flex-1 h-[48px] rounded-[16px] bg-[#0F1E36] text-[15px] font-bold text-white transition hover:bg-[#1A2E4E] hover:shadow-sm flex items-center justify-center"
          >
            {reserveText ?? t("carCard.reserve", { defaultValue: "اطلبها الان" })}
          </button>
        </div>
      </div>
    </article>
  );
}
