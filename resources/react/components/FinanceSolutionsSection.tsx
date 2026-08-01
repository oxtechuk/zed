import { useTranslation } from "react-i18next";
import type { IFinanceSolutionsSectionProps } from "../interfaces/IFinanceSolutionsSectionProps";

export default function FinanceSolutionsSection({
  titleOrange,
  steps = [],
  className = "",
}: IFinanceSolutionsSectionProps) {
  const { i18n } = useTranslation();
  const direction = i18n.dir();

  return (
    <section
      dir={direction}
      className={`w-full bg-[#FAFBFC] py-14 border-t border-[#E5E9F0] ${className}`}
    >
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {/* Title */}
        <h2 className="mb-14 text-center text-[26px] font-extrabold text-[#0F172A]">
          {titleOrange || "استلم سيارتك في 4 خطوات"}
        </h2>

        {/* Steps Grid */}
        <div className="relative grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
          {/* Optional horizontal connecting line for desktop */}
          <div className="absolute top-[20px] left-[12%] right-[12%] hidden h-[2px] bg-[#EEF2F6] lg:block z-0" />

          {steps.map((step, idx) => (
            <div
              key={idx}
              className="relative z-10 flex flex-col items-center text-center px-4"
            >
              {/* Step Number Circle */}
              <div className="flex h-[42px] w-[90px] items-center justify-center rounded-full bg-white border-2 border-[#E5C287] shadow-sm mb-4">
                <span className="text-[18px] font-extrabold text-[#E5C287]">
                  {step.number || `0${idx + 1}`}
                </span>
              </div>

              {/* Title */}
              <h3 className="mb-2 text-[16px] font-extrabold text-[#0F172A]">
                {step.title}
              </h3>

              {/* Description */}
              <p className="text-[13px] leading-6 text-gray-500 max-w-[220px]">
                {step.description}
              </p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
