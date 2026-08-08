import type { INavigationButtonProps } from "../../interfaces/INavigationButtonProps";

export default function NavigationButton({
  children,
  onClick,
  ariaLabel,
}: INavigationButtonProps) {
  return (
    <button
      type="button"
      onClick={onClick}
      aria-label={ariaLabel}
      className={[
        "flex h-[42px] w-[42px]",
        "items-center justify-center",
        "rounded-[9px]",
        "border border-white/35",
        "bg-transparent",
        "text-white",
        "transition duration-300",
        "hover:border-white/70",
        "hover:bg-white/[0.06]",
      ].join(" ")}
    >
      {children}
    </button>
  );
}
