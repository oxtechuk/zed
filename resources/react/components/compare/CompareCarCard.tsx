import { useTranslation } from "react-i18next";
import { X } from "lucide-react";
import { APP_IMAGES, getImageUrl } from "../../constants/app-images";
import type { ICompareCarCardProps } from "../../interfaces/ICompareCarCardProps";

const SPEC_KEY_MAP: Record<string, string> = {
  "Fuel Type": "fuel",
  Transmission: "gearbox",
  seats: "seats",
};

function getSpecValue(specs: any, label: string): string {
  if (Array.isArray(specs)) {
    const spec = specs.find((s) => "label" in s && s.label === label);
    const v = spec?.value;
    return v != null && typeof v === "string" ? v : "";
  }
  if (specs && typeof specs === "object") {
    const key = SPEC_KEY_MAP[label];
    const v = key ? (specs as Record<string, unknown>)[key] : undefined;
    return v != null && typeof v === "string" ? v : "";
  }
  return "";
}

export default function CompareCarCard({
  car,
  label,
  onRemove,
}: ICompareCarCardProps) {
  const { i18n } = useTranslation();

  const fuelType = getSpecValue(car.specs, "Fuel Type") || car.specs["fuel"] || "بنزين";
  const transmission = getSpecValue(car.specs, "Transmission") || car.specs["gearbox"] || "أوتوماتيك";
  const seats = getSpecValue(car.specs, "seats") || car.specs["seats"] || "5";
  const displaySeats = String(seats).includes("مقاعد") ? seats : `${seats} مقاعد`;

  // Specs array for bottom row inside the card
  const specList = [
    transmission,
    fuelType,
    displaySeats,
  ].filter(Boolean);

  return (
    <div
      dir={i18n.dir()}
      className="relative overflow-hidden rounded-[24px] border border-gray-200 bg-white shadow-xs p-3 transition-all duration-300 hover:shadow-md"
    >
      {/* "الأكثر طلباً" Tag - Top Right */}
      <div className="absolute top-5 end-5 z-10 rounded-lg bg-[#FFF4E4] border border-[#FFE4D6]/30 px-3 py-1 text-[11px] font-black text-[#D97706]">
        الأكثر طلباً
      </div>

      {/* Close/Remove Button - Top Left */}
      {onRemove && (
        <button
          type="button"
          onClick={onRemove}
          className="absolute top-5 start-5 z-10 flex h-7 w-7 items-center justify-center rounded-full bg-white text-gray-400 shadow-sm border border-gray-100 hover:text-gray-700 transition"
          aria-label="Remove car"
        >
          <X size={15} strokeWidth={2.5} />
        </button>
      )}

      {/* Image Block */}
      <div className="flex h-[180px] items-center justify-center p-6 mt-4 select-none">
        <img
          src={getImageUrl(car.main_image) || APP_IMAGES.CAR_PLACEHOLDER}
          alt={car.name}
          className="h-full w-full object-contain pointer-events-none"
          loading="lazy"
        />
      </div>

      {/* Bottom Half - Dark Navy Spec Details Panel */}
      <div className="bg-[#0F172A] text-white rounded-[20px] p-5 mt-2 text-start">
        {label && (
          <span className="text-[11px] font-black text-[#E5C287] uppercase tracking-wider block mb-1">
            {label}
          </span>
        )}
        <h3 className="text-[18px] font-black text-white leading-tight truncate mb-4" title={`${car.brand?.name} ${car.name}`}>
          {car.brand?.name} {car.name}
        </h3>

        {/* 3 Spec Pills Inline Row */}
        <div className="flex items-center gap-2">
          {specList.map((spec, i) => (
            <span
              key={i}
              className="flex-1 bg-white/10 px-2.5 py-2 rounded-xl text-[12px] font-extrabold text-center text-white/90 truncate"
            >
              {spec}
            </span>
          ))}
        </div>
      </div>
    </div>
  );
}
