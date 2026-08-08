import type { IContactInfoCardProps } from "../../interfaces/IContactInfoCardProps";

export default function ContactInfoCard({
  icon,
  label,
  value,
  href,
  variant = "default",
  valueDirection,
}: IContactInfoCardProps) {
  const isWhatsapp = variant === "whatsapp";

  const cardClasses = `rounded-2xl p-5 flex items-center gap-4 ${
    isWhatsapp
      ? "bg-[#064E3B]/80 border border-[#047857]/40"
      : "bg-[#1E293B]/90 border border-white/5"
  }${href ? " transition hover:scale-[1.02]" : ""}`;

  const content = (
    <>
      <div
        className={`flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ${
          isWhatsapp ? "bg-[#059669] text-white" : "bg-white/5"
        }`}
      >
        {icon}
      </div>
      <div>
        <span
          className={`text-[12px] font-bold block ${isWhatsapp ? "text-emerald-400" : "text-white/50"}`}
        >
          {label}
        </span>
        <strong className="text-[15px] font-black text-white block mt-0.5" dir={valueDirection}>
          {value}
        </strong>
      </div>
    </>
  );

  return href ? (
    <a href={href} target="_blank" rel="noreferrer" className={cardClasses}>
      {content}
    </a>
  ) : (
    <div className={cardClasses}>{content}</div>
  );
}
