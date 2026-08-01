import { Check } from "lucide-react";

type Step = 1 | 2 | 3 | 4;

interface StepCircleProps {
  number: number;
  label: string;
  active: boolean;
  done: boolean;
}

function StepCircle({ number, label, active, done }: StepCircleProps) {
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

export default function Stepper({ activeStep }: { activeStep: Step }) {
  return (
    <div className="mx-auto flex max-w-xl items-center justify-center gap-4 px-4 overflow-x-auto pb-2" dir="rtl">
      <StepCircle
        number={1}
        label="بياناتك"
        active={activeStep === 1}
        done={activeStep > 1}
      />
      <span
        className={`h-px w-[40px] sm:w-[60px] shrink-0 transition-colors duration-300 ${
          activeStep > 1 ? "bg-emerald-500" : "bg-[#D5DBE3]"
        }`}
      />
      <StepCircle
        number={2}
        label="تفاصيل السيارة"
        active={activeStep === 2}
        done={activeStep > 2}
      />
      <span
        className={`h-px w-[40px] sm:w-[60px] shrink-0 transition-colors duration-300 ${
          activeStep > 2 ? "bg-emerald-500" : "bg-[#D5DBE3]"
        }`}
      />
      <StepCircle
        number={3}
        label="تفاصيل التمويل"
        active={activeStep === 3}
        done={activeStep > 3}
      />
    </div>
  );
}
