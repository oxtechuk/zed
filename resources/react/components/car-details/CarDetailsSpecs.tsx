import { useMemo } from "react";
import { useTranslation } from "react-i18next";
import { Check } from "lucide-react";
import type { ICarDetailsSpecsProps } from "../../interfaces/ICarDetailsSpecsProps";

export default function CarDetailsSpecs({
  specifications = [],
  featuresList = [],
  safetyFeatures = [],
  specs,
  availabilityStatus,
  type,
  year,
  showOnly,
}: ICarDetailsSpecsProps) {
  const { t } = useTranslation();

  // Combine standard features and safety features for a complete equipments list
  const allFeatures = [...(featuresList ?? []), ...(safetyFeatures ?? [])];

  // Dynamically map specification rows from backend specifications prop
  const specRows = useMemo(() => {
    const rawSpecs = specifications ?? [];
    if (rawSpecs.length > 0) {
      const dynamicRows = rawSpecs.map((spec: any) => {
        let val = spec.value;
        if (!val && specs) {
          const nameLower = (spec.name || "").toLowerCase();
          if (nameLower.includes("محرك") || nameLower.includes("engine")) {
            val = specs.engine || specs.engine_type;
          } else if (
            nameLower.includes("ناقل") ||
            nameLower.includes("حركة") ||
            nameLower.includes("gear") ||
            nameLower.includes("transmission")
          ) {
            val = specs.gearbox || specs.transmission;
          } else if (nameLower.includes("وقود") || nameLower.includes("fuel")) {
            val = specs.fuel || specs.fuel_type;
          } else if (nameLower.includes("مقاعد") || nameLower.includes("seat")) {
            val = specs.seats ? `${specs.seats} مقاعد` : null;
          } else if (
            nameLower.includes("قوة") ||
            nameLower.includes("hp") ||
            nameLower.includes("power")
          ) {
            val = specs.hp || specs.power;
          }
        }
        return {
          label: spec.name,
          value: val || "—",
        };
      });

      return [
        ...dynamicRows,
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
    }

    return [
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
  }, [specifications, specs, type, year, availabilityStatus, t]);

  if (showOnly === "specs") {
    return (
      <div className="flex flex-col rounded-2xl bg-white border border-[#E7E9EF] shadow-xs overflow-hidden text-start w-full">
        <div className="border-b border-[#E7E9EF] px-6 py-4">
          <h2 className="text-[18px] font-black text-[#16254F]">
            {t("carDetails.specs.technicalSpecs", { defaultValue: "المواصفات التقنية" })}
          </h2>
        </div>

        <div className="flex flex-col">
          {specRows.map((row, idx) => (
            <div
              key={idx}
              className="flex items-center justify-between px-6 py-4 border-b border-[#E7E9EF] last:border-0 hover:bg-[#F8FAFC] transition-colors"
            >
              <span className="text-[14px] text-[#667085] font-bold">{row.label}</span>
              <span
                className={`text-[14px] font-black ${
                  row.label === "التوفر" || row.label === "Availability" || row.label === t("carDetails.specs.availability", { defaultValue: "التوفر" })
                    ? "text-[#009966] font-extrabold"
                    : "text-[#16254F]"
                }`}
              >
                {row.value}
              </span>
            </div>
          ))}
        </div>
      </div>
    );
  }

  if (showOnly === "features") {
    return (
      <div className="flex flex-col rounded-2xl bg-white border border-[#E7E9EF] shadow-xs p-6 text-start w-full">
        <h2 className="text-[18px] font-black text-[#16254F]">
          {t("carDetails.specs.featuresEquip", { defaultValue: "المميزات والتجهيزات" })}
        </h2>

        <div className="mt-5 flex flex-col gap-3">
          {allFeatures.map((item, idx) => (
            <div key={idx} className="flex items-center gap-3">
              <div className="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#EDC98E]/15 text-[#EDC98E]">
                <Check size={12} strokeWidth={3} />
              </div>
              <span className="text-[14px] font-semibold text-[#16254F] leading-tight">
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
    );
  }

  return (
    <section className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 lg:px-8 border-t border-gray-200 bg-white">
      <div className="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-20">
        {/* 1. Technical Specs Column */}
        <div className="flex flex-col rounded-2xl bg-white border border-[#E7E9EF] shadow-xs overflow-hidden text-start">
          <div className="border-b border-[#E7E9EF] px-6 py-4">
            <h2 className="text-[18px] font-black text-[#16254F]">
              {t("carDetails.specs.technicalSpecs", { defaultValue: "المواصفات التقنية" })}
            </h2>
          </div>

          <div className="flex flex-col">
            {specRows.map((row, idx) => (
              <div
                key={idx}
                className="flex items-center justify-between px-6 py-4 border-b border-[#E7E9EF] last:border-0 hover:bg-[#F8FAFC] transition-colors"
              >
                <span className="text-[14px] text-[#667085] font-bold">{row.label}</span>
                <span
                  className={`text-[14px] font-black ${
                    row.label === "التوفر" || row.label === "Availability" || row.label === t("carDetails.specs.availability", { defaultValue: "التوفر" })
                      ? "text-[#009966] font-extrabold"
                      : "text-[#16254F]"
                  }`}
                >
                  {row.value}
                </span>
              </div>
            ))}
          </div>
        </div>

        {/* 2. Features & Equipments Column */}
        <div className="flex flex-col rounded-2xl bg-white border border-[#E7E9EF] shadow-xs p-6 text-start">
          <h2 className="text-[18px] font-black text-[#16254F]">
            {t("carDetails.specs.featuresEquip", { defaultValue: "المميزات والتجهيزات" })}
          </h2>

          <div className="mt-5 flex flex-col gap-3">
            {allFeatures.map((item, idx) => (
              <div key={idx} className="flex items-center gap-3">
                <div className="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#EDC98E]/15 text-[#EDC98E]">
                  <Check size={12} strokeWidth={3} />
                </div>
                <span className="text-[14px] font-semibold text-[#16254F] leading-tight">
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
