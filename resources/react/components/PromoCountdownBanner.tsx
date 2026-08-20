import { useState, useEffect, useMemo } from "react";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { useLanguageStore } from "../store/language.store";
import { getImageUrl } from "../constants/app-images";
import type { IPromoCountdownBannerProps } from "../interfaces/IPromoCountdownBannerProps";

interface TimeLeft {
    days: number;
    hours: number;
    minutes: number;
    seconds: number;
    isExpired: boolean;
}

function calculateTimeLeft(targetDateStr?: string): TimeLeft {
    if (!targetDateStr) {
        // Fallback default: 19 days from now
        return { days: 19, hours: 7, minutes: 14, seconds: 4, isExpired: false };
    }

    const difference = new Date(targetDateStr).getTime() - new Date().getTime();

    if (difference <= 0) {
        return { days: 0, hours: 0, minutes: 0, seconds: 0, isExpired: true };
    }

    return {
        days: Math.floor(difference / (1000 * 60 * 60 * 24)),
        hours: Math.floor((difference / (1000 * 60 * 60)) % 24),
        minutes: Math.floor((difference / 1000 / 60) % 60),
        seconds: Math.floor((difference / 1000) % 60),
        isExpired: false,
    };
}

export default function PromoCountdownBanner({ banner }: IPromoCountdownBannerProps) {
    const { t } = useTranslation();
    const navigate = useNavigate();
    const direction = useLanguageStore((s) => s.direction);

    const [timeLeft, setTimeLeft] = useState<TimeLeft>(() =>
        calculateTimeLeft(banner?.countdown_end)
    );

    useEffect(() => {
        setTimeLeft(calculateTimeLeft(banner?.countdown_end));

        const timer = setInterval(() => {
            setTimeLeft(calculateTimeLeft(banner?.countdown_end));
        }, 1000);

        return () => clearInterval(timer);
    }, [banner?.countdown_end]);

    if (!banner || banner.is_active === false) {
        return null;
    }

    const customBg = banner.background_image ? getImageUrl(banner.background_image) : "";
    const customImage = banner.image ? getImageUrl(banner.image) : "";
    const targetUrl = banner.button_url || banner.button?.url || "/offers";
    const buttonText = banner.button_text || banner.button?.text || t("banner.viewOffers", { defaultValue: "اطلع على العروض" });
    const mainTitle = banner.title || t("banner.mainTitle", { defaultValue: "تمويل بدون أرباح لأول 6 أشهر*" });
    const sideTitle = banner.subtitle || t("banner.sideTitle", { defaultValue: "رمضان الاستثنائية" });
    const miniTag = banner.extra_tag || t("banner.miniTag", { defaultValue: "عروض" });
    const badgeText = banner.badge || t("banner.badge", { defaultValue: "عرض محدود" });
    const disclaimerText = banner.disclaimer || banner.description || t("banner.disclaimer", { defaultValue: "*تطبق الشروط والأحكام" });

    const formatNum = (n: number) => String(n).padStart(2, "0");

    return (
        <section className="w-full py-5 sm:py-8" dir={direction}>
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    className="relative overflow-hidden rounded-2xl sm:rounded-[28px] text-white shadow-2xl transition-all duration-300 hover:shadow-[0_20px_50px_rgba(0,0,0,0.3)]"
                    style={{
                        background: customBg
                            ? `url(${customBg}) center/cover no-repeat`
                            : "linear-gradient(105deg, #18082e 0%, #3a0b3f 28%, #5d0f48 55%, #7e1644 78%, #8e1a3e 100%)",
                    }}
                >
                    {/* Decorative Background Lighting & Star Glow */}
                    <div className="absolute -top-24 -start-24 w-80 h-80 bg-[#EDC98E]/15 rounded-full blur-3xl pointer-events-none" />
                    <div className="absolute -bottom-24 -end-24 w-80 h-80 bg-[#ED8EB7]/15 rounded-full blur-3xl pointer-events-none" />
                    <div className="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none" />

                    {/* Content Grid */}
                    <div className="relative z-10 p-6 sm:p-9 md:p-12 lg:p-14 min-h-[180px] sm:min-h-[210px] md:min-h-[230px] flex flex-col lg:flex-row items-center justify-between gap-6 lg:gap-10">
                        
                        {/* 1. Left Section (RTL): Lantern / Icon & Ramadan Special Offers Title */}
                        <div className="flex items-center gap-4 sm:gap-5 shrink-0 text-start w-full lg:w-auto justify-center lg:justify-start">
                            {customImage ? (
                                <img
                                    src={customImage}
                                    alt={sideTitle}
                                    className="h-16 sm:h-20 md:h-24 w-auto object-contain shrink-0 drop-shadow-lg"
                                />
                            ) : (
                                <div className="relative flex items-center justify-center shrink-0">
                                    {/* Golden Glow Backdrop */}
                                    <div className="absolute w-16 h-16 bg-[#FDE047]/25 rounded-full blur-lg" />
                                    {/* Lantern & Crescent Moon SVG Graphic */}
                                    <svg
                                        className="w-12 h-16 sm:w-16 sm:h-20 relative z-10 drop-shadow-[0_0_15px_rgba(251,191,36,0.6)]"
                                        viewBox="0 0 64 80"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        {/* Hanging Cord */}
                                        <line x1="32" y1="0" x2="32" y2="12" stroke="#FDE047" strokeWidth="2" strokeLinecap="round" />
                                        {/* Lantern Cap Top */}
                                        <path d="M26 12C26 10 38 10 38 12L42 20H22L26 12Z" fill="#FBBF24" stroke="#F59E0B" strokeWidth="1" />
                                        {/* Lantern Glass Body */}
                                        <path d="M22 20L18 42L25 56H39L46 42L42 20H22Z" fill="url(#glassGrad)" stroke="#FBBF24" strokeWidth="1.5" />
                                        {/* Inner Flame Glow */}
                                        <circle cx="32" cy="38" r="7" fill="#FEF08A" filter="url(#glowFilter)" />
                                        <path d="M32 30C32 30 28 35 28 38C28 40.2 29.8 42 32 42C34.2 42 36 40.2 36 38C36 35 32 30 32 30Z" fill="#F59E0B" />
                                        {/* Lantern Bottom Base */}
                                        <path d="M25 56L22 62H42L39 56H25Z" fill="#FBBF24" stroke="#D97706" strokeWidth="1" />
                                        <circle cx="32" cy="65" r="2.5" fill="#FDE047" />
                                        {/* Crescent Moon */}
                                        <path d="M47 18C44 23 46 29 51 32C46 32 41 27 42 21C42.5 19.8 44.5 18.5 47 18Z" fill="#FDE047" />
                                        
                                        <defs>
                                            <linearGradient id="glassGrad" x1="18" y1="20" x2="46" y2="56" gradientUnits="userSpaceOnUse">
                                                <stop stopColor="#FEF08A" stopOpacity="0.8" />
                                                <stop offset="0.5" stopColor="#F59E0B" stopOpacity="0.4" />
                                                <stop offset="1" stopColor="#78350F" stopOpacity="0.8" />
                                            </linearGradient>
                                            <filter id="glowFilter" x="20" y="26" width="24" height="24" filterUnits="userSpaceOnUse">
                                                <feGaussianBlur stdDeviation="3" />
                                            </filter>
                                        </defs>
                                    </svg>
                                </div>
                            )}

                            <div>
                                {miniTag && (
                                    <span className="block text-[13px] sm:text-[14px] md:text-[15px] font-bold text-[#FDE047] tracking-wider mb-1 opacity-95">
                                        {miniTag}
                                    </span>
                                )}
                                <h2 className="text-[21px] sm:text-[26px] md:text-[30px] font-black leading-tight bg-gradient-to-r from-[#FFF] via-[#FDE047] to-[#FBBF24] bg-clip-text text-transparent drop-shadow-md">
                                    {sideTitle}
                                </h2>
                            </div>
                        </div>

                        {/* 2. Middle Section: Countdown Timer */}
                        <div className="flex flex-col items-center justify-center shrink-0">
                            <span className="text-[13px] sm:text-[14px] font-bold text-white/85 mb-2.5">
                                {t("banner.endsIn", { defaultValue: "ينتهي العرض خلال" })}
                            </span>
                            
                            <div className="flex items-center gap-2.5 sm:gap-3">
                                {/* Days */}
                                <div className="flex flex-col items-center justify-center w-[58px] sm:w-[68px] md:w-[76px] h-[62px] sm:h-[72px] md:h-[80px] rounded-xl sm:rounded-2xl bg-black/45 backdrop-blur-md border border-white/20 shadow-xl">
                                    <span className="text-[22px] sm:text-[28px] md:text-[32px] font-black leading-none text-white tracking-tight">
                                        {formatNum(timeLeft.days)}
                                    </span>
                                    <span className="text-[11px] sm:text-[12px] font-semibold text-white/75 mt-1.5">
                                        {t("banner.days", { defaultValue: "يوم" })}
                                    </span>
                                </div>

                                {/* Hours */}
                                <div className="flex flex-col items-center justify-center w-[58px] sm:w-[68px] md:w-[76px] h-[62px] sm:h-[72px] md:h-[80px] rounded-xl sm:rounded-2xl bg-black/45 backdrop-blur-md border border-white/20 shadow-xl">
                                    <span className="text-[22px] sm:text-[28px] md:text-[32px] font-black leading-none text-white tracking-tight">
                                        {formatNum(timeLeft.hours)}
                                    </span>
                                    <span className="text-[11px] sm:text-[12px] font-semibold text-white/75 mt-1.5">
                                        {t("banner.hours", { defaultValue: "ساعة" })}
                                    </span>
                                </div>

                                {/* Minutes */}
                                <div className="flex flex-col items-center justify-center w-[58px] sm:w-[68px] md:w-[76px] h-[62px] sm:h-[72px] md:h-[80px] rounded-xl sm:rounded-2xl bg-black/45 backdrop-blur-md border border-white/20 shadow-xl">
                                    <span className="text-[22px] sm:text-[28px] md:text-[32px] font-black leading-none text-white tracking-tight">
                                        {formatNum(timeLeft.minutes)}
                                    </span>
                                    <span className="text-[11px] sm:text-[12px] font-semibold text-white/75 mt-1.5">
                                        {t("banner.minutes", { defaultValue: "دقيقة" })}
                                    </span>
                                </div>

                                {/* Seconds */}
                                <div className="flex flex-col items-center justify-center w-[58px] sm:w-[68px] md:w-[76px] h-[62px] sm:h-[72px] md:h-[80px] rounded-xl sm:rounded-2xl bg-black/45 backdrop-blur-md border border-white/20 shadow-xl">
                                    <span className="text-[22px] sm:text-[28px] md:text-[32px] font-black leading-none text-[#FDE047] tracking-tight">
                                        {formatNum(timeLeft.seconds)}
                                    </span>
                                    <span className="text-[11px] sm:text-[12px] font-semibold text-white/75 mt-1.5">
                                        {t("banner.seconds", { defaultValue: "ثانية" })}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {/* 3. Right Section (RTL): Badge + Main Headline + CTA Button + Disclaimer */}
                        <div className="flex flex-col items-center lg:items-end text-center lg:text-end shrink-0 max-w-lg">
                            {/* Badge */}
                            {badgeText && (
                                <span className="inline-block px-3.5 py-1 mb-2.5 rounded-full text-[11px] sm:text-[12px] font-black bg-[#DC2626] text-white shadow-md">
                                    {badgeText}
                                </span>
                            )}

                            {/* Headline */}
                            <h3 className="text-[19px] sm:text-[23px] md:text-[26px] font-black text-white leading-snug drop-shadow-sm mb-3.5">
                                {mainTitle}
                            </h3>

                            {/* Button */}
                            <button
                                type="button"
                                onClick={() => navigate(targetUrl)}
                                className="h-11 sm:h-12 px-7 sm:px-9 rounded-full bg-[#EDC98E] hover:bg-[#F5D8A4] active:scale-95 text-[#16254F] text-[14px] sm:text-[15px] font-black transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105 flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap"
                            >
                                <span>{buttonText}</span>
                            </button>

                            {/* Disclaimer */}
                            {disclaimerText && (
                                <span className="text-[11px] sm:text-[12px] text-white/70 font-medium mt-2.5">
                                    {disclaimerText}
                                </span>
                            )}
                        </div>

                    </div>
                </div>
            </div>
        </section>
    );
}
