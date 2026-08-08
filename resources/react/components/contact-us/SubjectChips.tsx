import type { ISubjectChipsProps } from "../../interfaces/ISubjectChipsProps";

export default function SubjectChips({ value, options, onChange }: ISubjectChipsProps) {
  return (
    <div className="flex flex-wrap gap-2">
      {options.map((option) => {
        const isSelected = value === option.value;
        return (
          <button
            key={option.value}
            type="button"
            onClick={() => onChange(option.value)}
            className={`px-4 py-2 text-[13px] font-bold rounded-xl transition-all duration-300 border ${
              isSelected
                ? "bg-[#0F172A] border-[#0F172A] text-white scale-105"
                : "bg-[#F8FAFC] border-[#E2E8F0] text-gray-500 hover:border-gray-400"
            }`}
          >
            {option.label}
          </button>
        );
      })}
    </div>
  );
}
