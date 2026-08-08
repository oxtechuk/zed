import { useTranslation } from "react-i18next";
import { APP_IMAGES } from "../../constants/app-images";
import type { ICarRequestHeaderProps } from "../../interfaces/ICarRequestHeaderProps";

export function CarRequestHeader({
    badgeKey = "carRequest.header.badge",
    titleKey = "carRequest.header.title",
    subtitleKey = "carRequest.header.subtitle",
}: ICarRequestHeaderProps) {
    const { t } = useTranslation();

    return (
        <section className="w-full bg-[#080E1E] py-6 md:py-8 text-white relative overflow-hidden border-b border-white/5 select-none">
            <div
                className="absolute inset-0 bg-cover bg-center opacity-30 mix-blend-overlay pointer-events-none"
                style={{ backgroundImage: `url(${APP_IMAGES.CONTACT_US_HERO})` }}
            />
            <div className="absolute inset-0 bg-gradient-to-r from-[#080E1E] via-[#080E1E]/90 to-[#080E1E]/70 pointer-events-none" />

            <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col items-start text-start">
                <span className="text-[13px] md:text-[14px] font-extrabold text-[#EDC98E] block mb-1">
                    {t(badgeKey, "طلب سيارة")}
                </span>
                <h1 className="text-[28px] sm:text-[34px] md:text-[42px] font-black text-white leading-tight mb-2">
                    {t(titleKey, "قدّم طلبك الان")}
                </h1>
                <p className="text-[14px] md:text-[15px] text-white/70 font-medium">
                    {t(subtitleKey, "قدم طلب للحصول على سيارتك الان")}
                </p>
            </div>
        </section>
    );
}
