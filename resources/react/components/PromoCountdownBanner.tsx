import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { Clock, ArrowLeft, ArrowRight, Sparkles } from "lucide-react";
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
    const isRtl = direction === "rtl";

    const [imageError, setImageError] = useState(false);
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
    const sideTitle = banner.subtitle || "";
    const miniTag = banner.extra_tag || "";
    const badgeText = banner.badge || "";
    const descriptionText = banner.description || "";
    const disclaimerText = banner.disclaimer || "";

    const formatNum = (n: number) => String(n).padStart(2, "0");

    const ArrowIcon = isRtl ? ArrowLeft : ArrowRight;

    return (
        <section className="w-full py-4 sm:py-6" dir={direction}>
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    className="relative overflow-hidden rounded-2xl sm:rounded-3xl text-white shadow-2xl transition-all duration-300 hover:shadow-[0_20px_50px_rgba(0,0,0,0.35)] border border-white/10"
                    style={{
                        background: customBg
                            ? `url("${customBg}") center/cover no-repeat`
                            : "linear-gradient(135deg, #0d0a1a 0%, #200d30 35%, #420d3b 70%, #68133f 100%)",
                    }}
                >
                    {/* Contrast Gradient Overlay for readability with custom background */}
                    {customBg ? (
                        <div
                            className={`absolute inset-0 pointer-events-none ${
                                isRtl
                                    ? "bg-gradient-to-l from-[#080B16]/95 via-[#080B16]/80 to-[#080B16]/40"
                                    : "bg-gradient-to-r from-[#080B16]/95 via-[#080B16]/80 to-[#080B16]/40"
                            }`}
                        />
                    ) : (
                        <>
                            {/* Decorative ambient glowing orbs */}
                            <div className="absolute -top-24 -start-20 w-80 h-80 bg-[#EDC98E]/15 rounded-full blur-3xl pointer-events-none" />
                            <div className="absolute -bottom-24 -end-20 w-80 h-80 bg-[#ED8EB7]/15 rounded-full blur-3xl pointer-events-none" />
                        </>
                    )}

                    <div className="absolute inset-0 bg-white/[0.02] backdrop-blur-[1px] pointer-events-none" />

                    {/* Main Content Layout */}
                    <div className="relative z-10 p-6 sm:p-8 md:p-10 lg:p-12 flex flex-col lg:flex-row items-center justify-between gap-6 lg:gap-10">
                        
                        {/* 1. Content & Actions Block (Start Column) */}
                        <div className="flex-1 min-w-0 flex flex-col items-center lg:items-start text-center lg:text-start w-full">
                            
                            {/* Top Badges / Tags Row */}
                            <div className="flex flex-wrap items-center justify-center lg:justify-start gap-2.5 mb-3">
                                {badgeText && (
                                    <span className="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black bg-gradient-to-r from-red-600 to-rose-600 text-white shadow-md">
                                        <Sparkles size={12} />
                                        {badgeText}
                                    </span>
                                )}

                                {miniTag && (
                                    <span className="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#FDE047]/15 text-[#FDE047] border border-[#FDE047]/30 backdrop-blur-xs">
                                        {miniTag}
                                    </span>
                                )}

                                {sideTitle && (
                                    <span className="inline-block text-xs sm:text-sm font-bold text-white/80">
                                        {sideTitle}
                                    </span>
                                )}
                            </div>

                            {/* Main Title */}
                            <h2 className="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black text-white leading-tight tracking-tight drop-shadow-md mb-2 max-w-2xl">
                                {mainTitle}
                            </h2>

                            {/* Description / Subtext */}
                            {descriptionText && (
                                <p className="text-xs sm:text-sm md:text-base text-white/85 font-medium leading-relaxed mb-4 max-w-xl line-clamp-2">
                                    {descriptionText}
                                </p>
                            )}

                            {/* CTA Action & Disclaimer Row */}
                            <div className="flex flex-wrap items-center justify-center lg:justify-start gap-4 mt-2">
                                <button
                                    type="button"
                                    onClick={() => navigate(targetUrl)}
                                    className="h-11 sm:h-12 px-7 sm:px-9 rounded-xl sm:rounded-2xl bg-gradient-to-r from-[#EDC98E] to-[#E2B66B] hover:from-[#F7D8A4] hover:to-[#EDC98E] text-[#16254F] text-sm sm:text-[15px] font-black transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap"
                                >
                                    <span>{buttonText}</span>
                                    <ArrowIcon size={16} className="transition-transform group-hover:translate-x-0.5" />
                                </button>

                                {disclaimerText && (
                                    <span className="text-[11px] sm:text-xs text-white/70 font-medium">
                                        {disclaimerText}
                                    </span>
                                )}
                            </div>
                        </div>

                        {/* 2. Glassmorphism Countdown Card (End Column) */}
                        <div className="shrink-0 flex flex-col items-center justify-center bg-black/40 backdrop-blur-xl border border-white/20 rounded-2xl sm:rounded-3xl p-4 sm:p-5 md:p-6 shadow-2xl w-full sm:w-auto">
                            
                            {/* Card Header with Urgency Label & Optional Custom Image */}
                            <div className="flex items-center gap-2.5 mb-3.5 text-center">
                                {customImage && !imageError ? (
                                    <img
                                        src={customImage}
                                        alt={sideTitle || "Promo"}
                                        onError={() => setImageError(true)}
                                        className="h-7 w-7 object-contain drop-shadow-md"
                                    />
                                ) : (
                                    <Clock size={16} className="text-[#FDE047] animate-pulse" />
                                )}
                                <span className="text-xs sm:text-sm font-extrabold text-white/90 tracking-wide">
                                    {t("banner.endsIn", { defaultValue: "ينتهي العرض خلال" })}
                                </span>
                            </div>

                            {/* 4 Countdown Digits */}
                            <div className="flex items-center justify-center gap-2 sm:gap-3" dir="ltr">
                                {/* Days */}
                                <div className="flex flex-col items-center justify-center w-14 sm:w-16 md:w-[72px] h-15 sm:h-17 md:h-[76px] rounded-xl sm:rounded-2xl bg-white/10 hover:bg-white/15 border border-white/25 shadow-lg transition-colors">
                                    <span className="text-xl sm:text-2xl md:text-3xl font-black leading-none text-white tracking-tight">
                                        {formatNum(timeLeft.days)}
                                    </span>
                                    <span className="text-[10px] sm:text-[11px] font-bold text-white/75 mt-1">
                                        {t("banner.days", { defaultValue: "يوم" })}
                                    </span>
                                </div>

                                {/* Hours */}
                                <div className="flex flex-col items-center justify-center w-14 sm:w-16 md:w-[72px] h-15 sm:h-17 md:h-[76px] rounded-xl sm:rounded-2xl bg-white/10 hover:bg-white/15 border border-white/25 shadow-lg transition-colors">
                                    <span className="text-xl sm:text-2xl md:text-3xl font-black leading-none text-white tracking-tight">
                                        {formatNum(timeLeft.hours)}
                                    </span>
                                    <span className="text-[10px] sm:text-[11px] font-bold text-white/75 mt-1">
                                        {t("banner.hours", { defaultValue: "ساعة" })}
                                    </span>
                                </div>

                                {/* Minutes */}
                                <div className="flex flex-col items-center justify-center w-14 sm:w-16 md:w-[72px] h-15 sm:h-17 md:h-[76px] rounded-xl sm:rounded-2xl bg-white/10 hover:bg-white/15 border border-white/25 shadow-lg transition-colors">
                                    <span className="text-xl sm:text-2xl md:text-3xl font-black leading-none text-white tracking-tight">
                                        {formatNum(timeLeft.minutes)}
                                    </span>
                                    <span className="text-[10px] sm:text-[11px] font-bold text-white/75 mt-1">
                                        {t("banner.minutes", { defaultValue: "دقيقة" })}
                                    </span>
                                </div>

                                {/* Seconds */}
                                <div className="flex flex-col items-center justify-center w-14 sm:w-16 md:w-[72px] h-15 sm:h-17 md:h-[76px] rounded-xl sm:rounded-2xl bg-white/10 hover:bg-white/15 border border-[#FDE047]/40 shadow-lg transition-colors">
                                    <span className="text-xl sm:text-2xl md:text-3xl font-black leading-none text-[#FDE047] tracking-tight">
                                        {formatNum(timeLeft.seconds)}
                                    </span>
                                    <span className="text-[10px] sm:text-[11px] font-bold text-[#FDE047]/90 mt-1">
                                        {t("banner.seconds", { defaultValue: "ثانية" })}
                                    </span>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </section>
    );
}

