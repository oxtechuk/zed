import { useTranslation } from "react-i18next";
import type { IOffersPageHeroProps } from "../../interfaces/IOffersPageHeroProps";

export default function OffersPageHero({
  title,
  description,
}: IOffersPageHeroProps) {
  const { i18n } = useTranslation();

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
          <div className="w-full flex flex-col justify-center text-center items-center">


            <h1 className="mt-5 text-[32px] font-extrabold leading-[1.3] text-white md:text-[46px]">
              {title}
            </h1>

            <p className="mt-4 max-w-xl text-[16px] md:text-[18px] leading-8 text-gray-300">
              {description}
            </p>
          </div>
        </div>
    </div>
  </section>
  );
}
