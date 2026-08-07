import { useTranslation } from "react-i18next";
import type { IOffersPageHeroProps } from "../../interfaces/IOffersPageHeroProps";

export default function OffersPageHero({
  badgeText,
  title,
  description,
}: IOffersPageHeroProps) {
  const { i18n } = useTranslation();

  return (
    <section
      dir={i18n.dir()}
      className="relative w-full bg-[#080E1E] text-white py-7 md:py-9 overflow-hidden"
    >
      {/* Background Subtle Overlay Glow */}
      <div className="absolute inset-0 bg-gradient-to-b from-[#080E1E] via-[#0D1730] to-[#080E1E] opacity-90 pointer-events-none" />

      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="flex flex-col items-start justify-center text-start">
          <span className="block text-[13px] font-extrabold text-[#EDC98E] uppercase tracking-wider mb-1.5">
            {badgeText}
          </span>

          <h1
            className="text-[26px] font-black leading-tight text-white md:text-[34px] max-w-3xl"
            dangerouslySetInnerHTML={{ __html: title }}
          />

          <p className="mt-2 max-w-2xl text-[14px] md:text-[15px] leading-relaxed text-white/70">
            {description}
          </p>
        </div>
      </div>
    </section>
  );
}
