import { useTranslation } from "react-i18next";
import { Check } from "lucide-react";
import { SiWhatsapp } from "react-icons/si";
import { CarRequestHeader } from "./CarRequestHeader";
import type { ICarRequestSuccessProps } from "../../interfaces/ICarRequestSuccessProps";

export function CarRequestSuccess({
    activeCar,
    phone,
    whatsappHref,
    onBackToCars,
    direction,
}: ICarRequestSuccessProps) {
    const { t } = useTranslation();
    const carLabel = activeCar
        ? `${activeCar.brand?.name || ""} ${activeCar.name}`.trim()
        : "";

    return (
        <main dir={direction} className="min-h-screen w-full bg-[#F8FAFC]">
            <CarRequestHeader />

            <div className="w-full bg-[#F8FAFC] py-12 md:py-20 flex items-center justify-center">
                <div className="mx-auto flex w-full max-w-[560px] flex-col items-center px-4 text-center">
                    <div className="flex h-[92px] w-[92px] items-center justify-center rounded-full bg-[#FFF9F0] border border-[#FDE8D0] mb-5 shadow-2xs">
                        <Check size={40} strokeWidth={2.5} className="text-[#EDC98E]" />
                    </div>

                    <p className="text-[13px] font-bold text-[#EDC98E] mb-2">
                        {t("carRequest.success.badge", "تم الإرسال")}
                    </p>

                    <h2 className="text-[32px] md:text-[38px] font-black text-[#0B1736] mb-4">
                        {t("carRequest.success.title", "طلبك في المراجعة!")}
                    </h2>

                    <p className="text-[15px] text-[#64748B] font-medium leading-relaxed mb-1">
                        {t("carRequest.success.description", "تم استلام طلب تمويل {{carLabel}}", { carLabel })}
                    </p>

                    <p className="text-[14px] text-[#64748B] font-medium leading-relaxed mb-8">
                        {t("carRequest.success.contact", "سيتواصل معك أحد متخصصينا على {{phone}} خلال ساعات العمل.", { phone })}
                    </p>

                    <a
                        href={whatsappHref}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="w-full max-w-[420px] h-[52px] rounded-[16px] bg-[#22C55E] hover:bg-[#16A34A] text-[16px] font-extrabold text-white flex items-center justify-center gap-2.5 transition-all shadow-sm mb-3.5 cursor-pointer"
                    >
                        <SiWhatsapp size={22} />
                        <span>{t("carRequest.success.whatsapp", "تابع طلبك عبر واتساب")}</span>
                    </a>

                    <button
                        type="button"
                        onClick={onBackToCars}
                        className="w-full max-w-[420px] h-[52px] rounded-[16px] border border-gray-200 bg-white hover:bg-gray-50 text-[15px] font-extrabold text-[#64748B] hover:text-[#0B1736] flex items-center justify-center gap-2 transition-all cursor-pointer shadow-2xs"
                    >
                        <span>{t("carRequest.success.backToCars", "العودة لتصفح السيارات")}</span>
                        <span className="text-[16px]">{direction === "rtl" ? "←" : "→"}</span>
                    </button>
                </div>
            </div>
        </main>
    );
}
