import { useTranslation } from "react-i18next";
import type { IOffersPageHeroProps } from "../../interfaces/IOffersPageHeroProps";

export default function OffersPageHero({
  image,
  badgeText,
  title,
  description,
}: IOffersPageHeroProps) {
  const { i18n } = useTranslation();
  const isRtl = i18n.dir() === "rtl";

  return (
    <section
      dir={i18n.dir()}
      className="relative w-full bg-[#07111F] text-white py-16 md:py-24 overflow-hidden"
    >
      {/* Background Radial Glow */}
      <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-blue-900/20 via-transparent to-transparent pointer-events-none" />

      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="flex flex-col-reverse lg:flex-row items-center justify-between gap-10">
          {/* Content */}
          <div className={`w-full lg:w-1/2 flex flex-col justify-center text-center ${isRtl ? 'lg:text-right lg:items-start' : 'lg:text-left lg:items-start'} items-center`}>
            <span className="inline-flex items-center rounded-full bg-[#FF9E3D]/10 px-4 py-1.5 text-[14px] font-bold text-[#FF9E3D] border border-[#FF9E3D]/20">
              {badgeText}
            </span>

            <h1 className="mt-5 text-[32px] font-extrabold leading-[1.3] text-white md:text-[46px]">
              {title}
            </h1>

            <p className="mt-4 max-w-xl text-[16px] md:text-[18px] leading-8 text-gray-300">
              {description}
            </p>
          </div>

          {/* Image container with gradient fade */}
          <div className="relative w-full lg:w-1/2 flex justify-center lg:justify-end">
            <div className="relative h-[240px] w-full max-w-[480px] sm:h-[300px] lg:h-[320px] overflow-hidden">
              <img
                src={image}
                alt={title}
                className="h-full w-full object-contain"
                loading="eager"
              />
              {/* Fade Overlay in desktop vs mobile */}
              <div
                className={`absolute inset-0 bg-gradient-to-t from-[#07111F] via-[#07111F]/10 to-transparent lg:bg-gradient-to-r lg:from-[#07111F] lg:to-transparent`}
              />
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
