import { useTranslation } from "react-i18next";
import { X, Users } from "lucide-react";
import { APP_IMAGES, getImageUrl } from "../../constants/app-images";
import type { ICompareCarCardProps } from "../../interfaces/ICompareCarCardProps";
import LazyImg from "../LazyImg";

function getSpecVal(car: any, key: string, altKey?: string): string {
  if (altKey && car[altKey]) {
    const v = car[altKey];
    if (v != null && typeof v === "string" && v.trim() !== "") return v.trim();
  }

  const searchKey = key.toLowerCase();
  const altSearchKey = altKey ? altKey.toLowerCase() : "";

  // 1. Check array format in car.specs (e.g. [{ label: "Fuel Type", value: "ديزل" }, { label: "Horsepower", value: "204 HP" }, { label: "Seats", value: "5" }])
  if (Array.isArray(car?.specs)) {
    const spec = car.specs.find((s: any) => {
      const l = (s.label || s.name || "").toLowerCase();
      return l === searchKey || l.includes(searchKey) || (altSearchKey && (l === altSearchKey || l.includes(altSearchKey)));
    });
    if (spec?.value != null && String(spec.value).trim() !== "") {
      return String(spec.value).trim();
    }
  }

  // 2. Check object format in car.specs
  if (car?.specs && typeof car.specs === "object" && !Array.isArray(car.specs)) {
    for (const [k, v] of Object.entries(car.specs)) {
      const l = k.toLowerCase();
      if ((l === searchKey || l.includes(searchKey) || (altSearchKey && (l === altSearchKey || l.includes(altSearchKey)))) && v != null) {
        return String(v).trim();
      }
    }
  }

  // 3. Check array format in car.specifications
  if (Array.isArray(car?.specifications)) {
    const spec = car.specifications.find((s: any) => {
      const l = (s.name || s.label || "").toLowerCase();
      return l === searchKey || l.includes(searchKey) || (altSearchKey && (l === altSearchKey || l.includes(altSearchKey)));
    });
    if (spec?.value != null && String(spec.value).trim() !== "") {
      return String(spec.value).trim();
    }
  }

  return "";
}

export default function CompareCarCard({
  car,
  label,
  onRemove,
}: ICompareCarCardProps) {
  const { t, i18n } = useTranslation();

  const fuelType =
    getSpecVal(car, "Fuel Type", "fuel") || (car as any).fuel_type || t("carDetails.specs.gasoline", { defaultValue: "بنزين" });
  const rawSeats = getSpecVal(car, "Seats", "seats") || (car as any).seats || "5";
  const displaySeats = String(rawSeats).replace(/[^\d]/g, "") || String(rawSeats);
  const rawHp = getSpecVal(car, "Horsepower", "hp") || (car as any).hp || "340";
  const hpValue = String(rawHp).replace(/hp/i, "").trim() || "340";

  const carTitle = `${car.brand?.name ? car.brand.name + " " : ""}${car.name ?? ""}`.trim();
  const imageUrl = getImageUrl(car.main_image) || APP_IMAGES.CAR_PLACEHOLDER;

  return (
    <div
      dir={i18n.dir()}
      className="relative overflow-hidden rounded-[24px] sm:rounded-[28px] bg-[#0B1736] text-white shadow-xl transition-all duration-300 hover:shadow-2xl border border-white/10 w-full max-w-[480px] mx-auto select-none"
    >
      {/* Top Image Banner Area */}
      <div className="relative h-[210px] sm:h-[240px] w-full overflow-hidden flex items-end justify-end p-5 bg-[#12224A]">
        <LazyImg
          src={imageUrl}
          alt={car.name}
          className="absolute inset-0 h-full w-full object-contain p-2 transition-transform duration-500 hover:scale-105"
        />

        {/* Dark Gradient Overlay for Title Visibility */}
        <div className="absolute inset-0 bg-gradient-to-t from-[#0B1736] via-[#0B1736]/60 to-black/30 pointer-events-none" />

        {/* Close/Remove Button - Top Left / Start */}
        {onRemove && (
          <button
            type="button"
            onClick={onRemove}
            className="absolute top-4 start-4 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#101828] shadow-md transition-all duration-200 hover:bg-gray-100 hover:scale-105 active:scale-95 cursor-pointer"
            aria-label="Remove car"
          >
            <X size={18} strokeWidth={2.5} />
          </button>
        )}

        {/* Top Right / End Badge */}
        <div className="absolute top-4 end-4 z-20 rounded-full bg-[#F3C77C] px-4 py-1.5 text-[12px] sm:text-[13px] font-black text-[#101828] shadow-sm">
          {t("carCard.mostRequested", { defaultValue: "الأكثر طلباً" })}
        </div>

        {/* Car Label & Main Title Overlay */}
        <div className="relative z-10 w-full text-end">
          {label && (
            <span className="block text-[13px] sm:text-[14px] font-bold text-[#F3C77C] mb-0.5">
              {label}
            </span>
          )}
          <h3
            className="text-[20px] sm:text-[24px] font-black text-white leading-tight truncate"
            title={carTitle}
          >
            {carTitle}
          </h3>
        </div>
      </div>

      {/* Bottom Spec Pills Section */}
      <div className="p-4 sm:p-5 pt-3.5 bg-[#0B1736]">
        <div className="grid grid-cols-3 gap-3">
          {/* Seats Pill */}
          <div className="flex flex-col items-center justify-center gap-1 rounded-2xl bg-[#1A2B56] hover:bg-[#1E3263] transition-colors h-[72px] sm:h-[80px] p-2 text-white">
            <Users size={18} className="text-white/60" />
            <span className="text-[16px] sm:text-[18px] font-black text-white leading-none">
              {displaySeats}
            </span>
          </div>

          {/* Fuel Type Pill */}
          <div className="flex flex-col items-center justify-center rounded-2xl bg-[#1A2B56] hover:bg-[#1E3263] transition-colors h-[72px] sm:h-[80px] p-2 text-white">
            <span className="text-[14px] sm:text-[16px] font-black text-white leading-none text-center truncate w-full px-1">
              {fuelType}
            </span>
          </div>

          {/* Horsepower Pill */}
          <div className="flex flex-col items-center justify-center rounded-2xl bg-[#1A2B56] hover:bg-[#1E3263] transition-colors h-[72px] sm:h-[80px] p-2 text-white">
            <span className="text-[18px] sm:text-[20px] font-black text-[#F3C77C] leading-none">
              {hpValue}
            </span>
            <span className="text-[10px] font-bold text-white/50 uppercase mt-1">
              HP
            </span>
          </div>
        </div>
      </div>
    </div>
  );
}
