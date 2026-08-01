import { useTranslation } from "react-i18next";
import { ArrowLeft, ArrowRight } from "lucide-react";
import type { IAboutHeroProps } from "../../interfaces/IAboutHeroProps";

export default function AboutHero({
  badgeText,
  titleWhite,
  titleBlue,
  subtitle,
  stats,
}: IAboutHeroProps) {
  const { i18n, t } = useTranslation();
  const isRtl = i18n.dir() === "rtl";

  return (
    <section
      dir={i18n.dir()}
      className="relative w-full overflow-hidden bg-[#010915] pb-24 pt-28 text-white text-center"
    >
      {/* Radial Spotlights for glowing highlights */}
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(0,98,241,0.22),transparent_40%),radial-gradient(circle_at_70%_30%,rgba(0,98,241,0.22),transparent_40%)]" />

      {/* Background Car Silhouette Overlay (Using home_hero.png for style matching) */}
      <div
        className="absolute inset-0 bg-cover bg-center opacity-[0.07] pointer-events-none mix-blend-screen"
        style={{ backgroundImage: "url('/images/home_hero.png')" }}
      />

      {/* Gradient to smooth out the background car image edges */}
      <div className="absolute inset-0 bg-gradient-to-t from-[#010915] via-transparent to-[#010915]" />

      <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div className="mx-auto flex max-w-4xl flex-col items-center">
          {/* Badge */}
          <div className="mb-8 inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/5 px-5 py-2 text-[13px] font-medium text-[#EDC98E] backdrop-blur-md">
            <span className="h-2 w-2 rounded-full bg-[#EDC98E] animate-pulse" />
            {badgeText}
          </div>

          {/* Heading */}
          <h1 className="text-[36px] font-black leading-[1.3] text-white md:text-[54px] tracking-tight">
            {titleWhite}
            {titleBlue && (
              <span className="text-[var(--brand-primary-color)]">
                {" "}{titleBlue}
              </span>
            )}
          </h1>

          {/* Subtitle */}
          <p className="mt-4 max-w-2xl text-[16px] leading-[1.7] text-white/70 md:text-[19px]">
            {subtitle}
          </p>

          {/* Buttons Group */}
          <div className="mt-10 flex flex-wrap justify-center gap-5">
            <a
              href="/finance"
              className="flex h-[50px] items-center gap-3 rounded-full bg-[#EDC98E] px-8 text-[15px] font-extrabold text-[#010915] transition-all duration-300 hover:scale-[1.03] hover:shadow-[0_12px_30px_rgba(237,201,142,0.25)] hover:opacity-95"
            >
              <span>{t("aboutPage.hero.buttonPrimary")}</span>
              {isRtl ? <ArrowLeft size={16} /> : <ArrowRight size={16} />}
            </a>
            <a
              href="/contact"
              className="flex h-[50px] items-center gap-3 rounded-full border border-white/15 bg-white/5 px-8 text-[15px] font-bold text-white transition-all duration-300 hover:bg-white/10 hover:border-white/30 hover:scale-[1.01]"
            >
              <span>{t("aboutPage.hero.buttonSecondary")}</span>
            </a>
          </div>

          {/* Stats Section */}
          {stats.length > 0 && (
            <div className="mt-20 grid w-full grid-cols-2 gap-y-12 md:grid-cols-4 border-t border-white/10 pt-16">
              {stats.map((stat) => (
                <div key={stat.label} className="flex flex-col items-center">
                  <span className="text-[34px] font-black text-white md:text-[44px] leading-none">
                    {stat.value}
                  </span>
                  <span className="mt-3 text-[14px] font-extrabold text-white/90">
                    {stat.label}
                  </span>
                  <span className="mt-1 text-[12px] text-white/50">
                    {stat.description}
                  </span>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </section>
  );
}
