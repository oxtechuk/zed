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
        <h2 className="mb-14 text-center text-[24px] font-black text-[#16254F] md:text-[28px]">
          {titleOrange || "استلم سيارتك في 4 خطوات"}
        </h2>

        {/* Steps Grid */}
        <div className="relative grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
          {/* Optional horizontal connecting line for desktop */}
          <div className="absolute top-[32px] left-[12%] right-[12%] hidden h-[1px] bg-[#E7E9EF] lg:block z-0" />

          {steps.map((step, idx) => (
            <div
              key={idx}
              className="relative z-10 flex flex-col items-center text-center px-4"
            >
              {/* Step Number Circle */}
              <div className="mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border border-[#E7E9EF] bg-white shadow-[0px_1px_2px_-1px_rgba(0,0,0,0.10),0px_1px_3px_rgba(0,0,0,0.10)]">
                <span className="text-[24px] font-black text-[#EDC98E]">
                  {step.number || `0${idx + 1}`}
                </span>
              </div>

              {/* Title */}
              <h3 className="mb-2 text-[18px] font-bold text-[#16254F]">
                {step.title}
              </h3>

              {/* Description */}
              <p className="text-[14px] leading-6 text-[#667085] max-w-[255px]">
                {step.description}
              </p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
