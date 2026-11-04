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

  const brandName = car.brand?.name || "";
  const carName = car.name || "";
  const carTitle = brandName && carName.toLowerCase().startsWith(brandName.toLowerCase())
    ? carName
    : `${brandName} ${carName}`.trim();
  const imageUrl = getImageUrl(car.main_image) || APP_IMAGES.CAR_PLACEHOLDER;

  return (
    <div
      dir={i18n.dir()}
      className="relative overflow-hidden rounded-[24px] sm:rounded-[28px] bg-[#0B1736] text-white shadow-xl transition-all duration-300 hover:shadow-2xl border border-white/10 w-full max-w-[480px] mx-auto select-none p-5 sm:p-6 flex flex-col gap-4"
    >
      {/* Car Image Square Box (مربع صورة السيارة) */}
      <div className="relative w-full h-[210px] sm:h-[230px] rounded-2xl bg-[#F8FAFC] border border-white/10 flex items-center justify-center p-3 overflow-hidden shadow-sm group">
        {/* Close/Remove Button - Top Start */}
        {onRemove && (
          <button
            type="button"
            onClick={onRemove}
            className="absolute top-3.5 start-3.5 z-20 flex h-9 w-9 items-center justify-center rounded-full bg-white text-[#101828] shadow-md transition-all duration-200 hover:bg-gray-100 hover:scale-105 active:scale-95 cursor-pointer"
            aria-label="Remove car"
          >
            <X size={17} strokeWidth={2.5} />
          </button>
        )}

        {/* Top End Badge */}
        <div className="absolute top-3.5 end-3.5 z-20 rounded-full bg-[#F3C77C] px-3.5 py-1 text-[11px] sm:text-[12px] font-black text-[#101828] shadow-sm">
          {t("carCard.mostRequested", { defaultValue: "الأكثر طلباً" })}
        </div>

        {/* The Car Image */}
        <LazyImg
          src={imageUrl}
          alt={car.name}
          fallbackSrc={APP_IMAGES.CAR_PLACEHOLDER}
          className="h-full w-full object-contain p-2 transition-transform duration-500 group-hover:scale-105"
          containerClassName="h-full w-full flex items-center justify-center"
        />
      </div>

      {/* Car Label & Title */}
      <div className="text-start">
        {label && (
          <span className="block text-[13px] sm:text-[14px] font-bold text-[#F3C77C] mb-1">
            {label}
          </span>
        )}
        <h3
          className="text-[20px] sm:text-[23px] font-black text-white leading-tight truncate"
          title={carTitle}
        >
          {carTitle}
        </h3>
      </div>

      {/* Bottom Spec Pills Section */}
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
  );
}
