import { ArrowLeft, ArrowRight } from "lucide-react";
import { useTranslation } from "react-i18next";
import { APP_IMAGES } from "../../constants/app-images";
import type { IAboutHeroProps } from "../../interfaces/IAboutHeroProps";

export default function AboutHero({
    badgeText,
    titleWhite,
    titleBlue,
    subtitle,
    stats,
}: IAboutHeroProps) {
    const { i18n, t } = useTranslation();
    const isRTL = i18n.dir() === "rtl";

    return (
        <section
            dir={i18n.dir()}
            className={[
                "relative w-full overflow-hidden pt-5",
                "bg-[#080E1E] text-white",
            ].join(" ")}
        >
            {/* Background image */}
            <div
                aria-hidden="true"
                className="pointer-events-none absolute inset-0 bg-cover bg-center bg-no-repeat"
                style={{
                    backgroundImage: `url('${APP_IMAGES.CONTACT_US_HERO}')`,
                }}
            />

            {/* Soft center glow */}
            <div
                aria-hidden="true"
                className={[
                    "pointer-events-none absolute inset-0",
                    "bg-[radial-gradient(circle_at_center,rgba(22,37,79,0.32),transparent_62%)]",
                ].join(" ")}
            />

            {/* Bottom fade */}
            <div className="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#080E1E] via-transparent to-[#080E1E]/30" />

            <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {/* Main content */}
                <div className="mx-auto flex max-w-[860px] flex-col items-center text-center">
                    {/* Badge */}
                    {badgeText && (
                        <p className="text-[12px] font-bold text-[var(--brand-secondary-color)] sm:text-[13px]">
                            {badgeText}
                        </p>
                    )}

                    {/* Heading */}
                    <h1
                        className={[
                            "mt-4 text-[34px] font-extrabold leading-[1.25]",
                            "text-white",
                            "sm:text-[42px]",
                            "lg:text-[48px]",
                        ].join(" ")}
                    >
                        {titleWhite}

                        {titleBlue && (
                            <>
                                {" "}
                                <span className="text-[var(--brand-secondary-color)]">
                                    {titleBlue}
                                </span>
                            </>
                        )}
                    </h1>

                    {/* Subtitle */}
                    {subtitle && (
                        <p className="mt-4 max-w-[720px] text-[14px] leading-7 text-white/45 sm:text-[15px] pt-4">
                            {subtitle}
                        </p>
                    )}

                    {/* Buttons */}
                    <div className="mt-8 grid w-full max-w-[560px] grid-cols-1 gap-3 sm:grid-cols-2">
                        {/* Primary */}
                        <a
                            href="/finance-calculator"
                            className={[
                                "flex h-[52px] items-center justify-center gap-3",
                                "rounded-[10px]",
                                "bg-[var(--brand-secondary-color)]",
                                "px-7 text-[14px] font-bold text-dark",
                                "transition duration-300",
                                "hover:brightness-105",
                            ].join(" ")}
                        >
                            <span>{t("aboutPage.hero.buttonPrimary")}</span>

                            {isRTL ? (
                                <ArrowLeft size={16} strokeWidth={1.8} />
                            ) : (
                                <ArrowRight size={16} strokeWidth={1.8} />
                            )}
                        </a>

                        {/* Secondary */}
                        <a
                            href="/contact"
                            className={[
                                "flex h-[52px] items-center justify-center",
                                "rounded-[10px]",
                                "border border-white/15",
                                "bg-transparent px-7",
                                "text-[14px] font-bold text-white",
                                "transition duration-300",
                                "hover:border-white/30",
                                "hover:bg-white/[0.04]",
                            ].join(" ")}
                        >
                            {t("aboutPage.hero.buttonSecondary")}
                        </a>
                    </div>
                </div>

                {/* Stats */}
                {stats?.length > 0 && (
                    <div
                        className={[
                            "mt-9 grid grid-cols-2",
                            "border-t border-white/[0.06]",
                            "md:grid-cols-4",
                        ].join(" ")}
                    >
                        {stats.slice(0, 4).map((stat, index) => (
                            <div
                                key={`${stat.label}-${index}`}
                                className={[
                                    "relative flex min-h-[92px] flex-col",
                                    "items-center justify-center px-4 py-5",
                                    index > 0
                                        ? "md:border-s md:border-white/[0.06]"
                                        : "",
                                    index % 2 !== 0
                                        ? "max-md:border-s max-md:border-white/[0.06]"
                                        : "",
                                    index >= 2
                                        ? "max-md:border-t max-md:border-white/[0.06]"
                                        : "",
                                ].join(" ")}
                            >
                                <strong className="text-[27px] font-extrabold leading-none text-[var(--brand-secondary-color)] sm:text-[30px]">
                                    {stat.value}
                                </strong>

                                <span className="mt-2 text-[12px] font-bold text-white/75">
                                    {stat.label}
                                </span>

                                {stat.description && (
                                    <span className="mt-1 text-[10px] text-white/30">
                                        {stat.description}
                                    </span>
                                )}
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </section>
    );
}
