import { ChevronDown } from "lucide-react";
import type { IFaqItem } from "../../interfaces/IFaqItem";

interface FaqAccordionProps {
  faq: IFaqItem;
  isOpen: boolean;
  onToggle: (id: string | number) => void;
}

export default function FaqAccordion({ faq, isOpen, onToggle }: FaqAccordionProps) {
  return (
    <div
      className={`overflow-hidden rounded-2xl border transition-all duration-300 bg-white ${
        isOpen
          ? "border-gray-200 shadow-xs"
          : "border-gray-100"
      }`}
    >
      <button
        type="button"
        onClick={() => onToggle(faq.id)}
        className="flex w-full items-center justify-between gap-5 px-6 py-5 text-end"
      >
        <span className="text-[16px] font-extrabold leading-relaxed text-[#0F172A]">
          {faq.question}
        </span>

        <span
          className={`flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-50 text-gray-500 transition-transform duration-300 ${
            isOpen ? "rotate-180 bg-[#FFF4E4] text-[#D97706]" : ""
          }`}
        >
          <ChevronDown size={16} strokeWidth={2.5} />
        </span>
      </button>

      {isOpen && (
        <div className="px-6 pb-6 text-start">
          <div className="mb-4 h-px w-full bg-gray-100" />
          <p className="text-[14px] leading-relaxed text-gray-500 font-semibold">
            {faq.answer}
          </p>
        </div>
      )}
    </div>
  );
}
