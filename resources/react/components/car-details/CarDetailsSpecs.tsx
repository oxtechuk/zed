import { useTranslation } from "react-i18next";
import { Check } from "lucide-react";
import type { ICarDetailsSpecsProps } from "../../interfaces/ICarDetailsSpecsProps";

export default function CarDetailsSpecs({
  featuresList,
  safetyFeatures,
  specs,
  availabilityStatus,
  type,
  year,
}: ICarDetailsSpecsProps) {
  const { t, i18n } = useTranslation();

  // Combine standard features and safety features for a complete equipments list
  const allFeatures = [...featuresList, ...(safetyFeatures ?? [])];

  // Define structured specification rows matching the mockup layout
  const specRows = [
    {
      label: t("carDetails.specs.engine", { defaultValue: "المحرك" }),
      value: specs?.engine || specs?.engine_type || "—",
    },
    {
      label: t("carDetails.specs.hp", { defaultValue: "القوة" }),
      value: specs?.hp || specs?.power || "—",
    },
    {
      label: t("carDetails.specs.fuel", { defaultValue: "نوع الوقود" }),
      value: specs?.fuel || specs?.fuel_type || "—",
    },
    {
      label: t("carDetails.specs.seats", { defaultValue: "عدد المقاعد" }),
      value: specs?.seats ? `${specs.seats} مقاعد` : "—",
    },
    {
      label: t("carDetails.specs.gearbox", { defaultValue: "ناقل الحركة" }),
      value: specs?.gearbox || specs?.transmission || "—",
    },
    {
      label: t("carDetails.specs.category", { defaultValue: "الفئة" }),
      value: type || "—",
    },
    {
      label: t("carDetails.specs.year", { defaultValue: "سنة الصنع" }),
      value: year || "—",
    },
    {
      label: t("carDetails.specs.availability", { defaultValue: "التوفر" }),
      value: availabilityStatus || "متوفر",
    },
  ];

  return (
    <section
      dir={i18n.dir()}
      className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 lg:px-8 border-t border-gray-200 bg-white"
    >
      <div className="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-20">
        
        {/* 1. Technical Specs Column (Renders on the right in RTL desktop) */}
        <div className="flex flex-col text-start">
          <h2 className="text-[20px] font-black text-[#0F172A] mb-8 border-b-2 border-[#EDC98E] pb-3 inline-block max-w-[200px]">
            {t("carDetails.specs.technicalSpecs", { defaultValue: "المواصفات التقنية" })}
          </h2>
          
          <div className="flex flex-col rounded-2xl bg-white border border-[#E5E9F0] shadow-xs overflow-hidden">
            {specRows.map((row, idx) => (
              <div
                key={idx}
                className="flex items-center justify-between px-6 py-4 border-b border-[#EEF2F6] last:border-0 hover:bg-[#F8FAFC] transition-colors"
              >
                <span className="text-[14px] text-gray-500 font-bold">{row.label}</span>
                <span
                  className={`text-[14px] font-black ${
                    row.label === "التوفر" || row.label === "Availability"
                      ? "text-green-600 font-extrabold"
                      : "text-[#0F172A]"
                  }`}
                >
                  {row.value}
                </span>
              </div>
            ))}
          </div>
        </div>

        {/* 2. Features & Equipments Column (Renders on the left in RTL desktop) */}
        <div className="flex flex-col text-start">
          <h2 className="text-[20px] font-black text-[#0F172A] mb-8 border-b-2 border-[#EDC98E] pb-3 inline-block max-w-[240px]">
            {t("carDetails.specs.featuresEquip", { defaultValue: "المميزات والتجهيزات" })}
          </h2>
          
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6">
            {allFeatures.map((item, idx) => (
              <div key={idx} className="flex items-center gap-3 py-1">
                <div className="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#FFF4E4] text-[#D97706]">
                  <Check size={11} strokeWidth={3.5} />
                </div>
                <span className="text-[14px] font-semibold text-[#475569] leading-tight">
                  {item.name}
                </span>
              </div>
            ))}
            {allFeatures.length === 0 && (
              <p className="text-[14px] text-gray-400 font-medium italic">
                لا تتوفر ميزات إضافية مسجلة لهذه السيارة.
              </p>
            )}
          </div>
        </div>

      </div>
    </section>
  );
}
