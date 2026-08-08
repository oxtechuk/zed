import { Eye, Target } from "lucide-react";
import type { IIconBoxProps } from "../../interfaces/IIconBoxProps";

export default function IconBox({ icon }: IIconBoxProps) {
  const Icon = icon === "target" ? Target : Eye;

  return (
    <div className="flex h-[54px] w-[54px] shrink-0 items-center justify-center rounded-[12px] border border-[var(--brand-secondary-color)]/35 bg-[var(--brand-secondary-color)]/10 text-[var(--brand-secondary-color)]">
      <Icon size={30} strokeWidth={2.4} />
    </div>
  );
}
