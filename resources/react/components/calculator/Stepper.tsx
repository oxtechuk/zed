import { Check, ChevronLeft, ChevronRight } from "lucide-react";
import { useTranslation } from "react-i18next";
import type { IStepperProps } from "../../interfaces/IStepperProps";
import type { IStepCircleProps } from "../../interfaces/IStepCircleProps";

function StepCircle({ number, label, active, done }: IStepCircleProps) {
  return (
    <div className="flex items-center gap-2">
      <span
        className={`flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-[14px] font-black transition-all duration-300 ${
          done
            ? "bg-emerald-500 text-white"
            : active
              ? "bg-[#0F172A] text-white"
              : "bg-[#E2E8F0] text-gray-400"
        }`}
      >
        {done ? <Check size={16} strokeWidth={3} /> : number}
      </span>
      <span
        className={`text-[13px] font-extrabold whitespace-nowrap transition-colors duration-300 ${
          active || done ? "text-[#0F172A]" : "text-gray-400"
        }`}
      >
        {label}
      </span>
    </div>
  );
}

export default function Stepper({ activeStep }: IStepperProps) {
  const { t, i18n } = useTranslation();
  const isRtl = i18n.dir() === "rtl";

  return (
    <div className="mx-auto flex max-w-xl items-center justify-center gap-4 px-4 overflow-x-auto pb-2">
      <StepCircle
        number={1}
        label={t("financeCalculator.stepper.step1", "بياناتك")}
        active={activeStep === 1}
        done={activeStep > 1}
      />
      <span
        className={`shrink-0 text-[16px] font-black transition-colors duration-300 ${
          activeStep > 1 ? "text-emerald-500" : "text-[#D5DBE3]"
        }`}
      >
        {isRtl ? (
          <ChevronLeft size={18} strokeWidth={2.5} />
        ) : (
          <ChevronRight size={18} strokeWidth={2.5} />
        )}
      </span>
      <StepCircle
        number={2}
        label={t("financeCalculator.stepper.step2", "تفاصيل السيارة")}
        active={activeStep === 2}
        done={activeStep > 2}
      />
      <span
        className={`shrink-0 text-[16px] font-black transition-colors duration-300 ${
          activeStep > 2 ? "text-emerald-500" : "text-[#D5DBE3]"
        }`}
      >
        {isRtl ? (
          <ChevronLeft size={18} strokeWidth={2.5} />
        ) : (
          <ChevronRight size={18} strokeWidth={2.5} />
        )}
      </span>
      <StepCircle
        number={3}
        label={t("financeCalculator.stepper.step3", "تفاصيل التمويل")}
        active={activeStep === 3}
        done={activeStep > 3}
      />
    </div>
  );
}
