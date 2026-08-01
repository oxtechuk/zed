import { Check } from "lucide-react";
import type { IWinnerValueProps } from "../../interfaces/IWinnerValueProps";

export default function WinnerValue({
  value,
  isWinner,
}: IWinnerValueProps) {
  return (
    <div
      className={`flex h-full items-center justify-center gap-2.5 font-extrabold ${
        isWinner ? "text-[#0F172A]" : "text-gray-700"
      }`}
    >
      {isWinner && (
        <span className="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#E5C287] text-[#0A1628] shadow-xs">
          <Check size={13} strokeWidth={3} />
        </span>
      )}
      <span>{value}</span>
    </div>
  );
}
