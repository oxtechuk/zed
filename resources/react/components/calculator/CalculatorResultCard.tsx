import { useTranslation } from "react-i18next";
import { SiWhatsapp } from "react-icons/si";
import { formatPrice } from "../../utils/format";
import type { ICalculatorResultCardProps } from "../../interfaces/ICalculatorResultCardProps";

export default function CalculatorResultCard({
    monthlyPayment,
    loanAmount,
    totalPayment,
    totalInterest,
    whatsappHref,
    isSubmitting,
    onSubmitLead,
}: ICalculatorResultCardProps) {
    const { t } = useTranslation();

    return (
        <div className="rounded-[28px] bg-[#16254F] p-6 md:p-8 text-white shadow-xl relative overflow-hidden">
            <div className="absolute top-0 end-0 w-36 h-36 bg-[#EDC98E]/5 blur-2xl rounded-full pointer-events-none" />

            {/* Result Title */}
            <span className="text-[15px] font-black text-white block text-start mb-6 border-b border-white/10 pb-3">
                {t("financeCalculator.step3.summaryTitle", "نتيجة الحساب")}
            </span>

            {/* 4 Stat Columns Grid */}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-6 text-start mb-8">
                {/* 1. Monthly Payment */}
                <div>
                    <span className="text-[12px] md:text-[13px] text-white/60 font-bold block mb-1">
                        {t(
                            "financeCalculator.step3.monthlyPayment",
                            "القسط الشهري",
                        )}
                    </span>
                    <strong className="text-[22px] md:text-[26px] font-black text-[#EDC98E] block">
                        {formatPrice(monthlyPayment, "#EDC98E")}
                    </strong>
                </div>

                {/* 2. Finance Amount */}
                <div>
                    <span className="text-[12px] md:text-[13px] text-white/60 font-bold block mb-1">
                        {t(
                            "financeCalculator.step3.financeAmount",
                            "مبلغ التمويل",
                        )}
                    </span>
                    <strong className="text-[20px] md:text-[24px] font-black text-white block">
                        {formatPrice(loanAmount, "white")}
                    </strong>
                </div>

                {/* 3. Total Payment */}
                <div>
                    <span className="text-[12px] md:text-[13px] text-white/60 font-bold block mb-1">
                        {t(
                            "financeCalculator.step3.totalFinance",
                            "إجمالي المدفوعات",
                        )}
                    </span>
                    <strong className="text-[20px] md:text-[24px] font-black text-white block">
                        {formatPrice(totalPayment, "white")}
                    </strong>
                </div>

                {/* 4. Total Interest */}
                <div>
                    <span className="text-[12px] md:text-[13px] text-white/60 font-bold block mb-1">
                        {t(
                            "financeCalculator.step3.totalProfit",
                            "إجمالي الأرباح",
                        )}
                    </span>
                    <strong className="text-[20px] md:text-[24px] font-black text-white block">
                        {formatPrice(totalInterest, "white")}
                    </strong>
                </div>
            </div>

            {/* Action Buttons Row */}
            <div className="flex items-center gap-3">
                {/* Main Submit Lead Button */}
                <button
                    type="button"
                    onClick={onSubmitLead}
                    disabled={isSubmitting}
                    className="flex-1 h-[52px] bg-[#EDC98E] hover:bg-[#e4be81] text-[#16254F] text-[16px] font-black rounded-[16px] flex items-center justify-center transition-all cursor-pointer shadow-sm disabled:opacity-50 active:scale-95"
                >
                    {isSubmitting
                        ? t(
                              "financeCalculator.step3.submitting",
                              "جاري إرسال الطلب...",
                          )
                        : t(
                              "financeCalculator.step3.submitLead",
                              "قدّم طلب التمويل الآن",
                          )}
                </button>
            </div>
            {/* WhatsApp Button */}
            <a
                href={whatsappHref}
                target="_blank"
                rel="noreferrer"
                className="h-[52px] w-[52px] shrink-0 bg-[#22C55E] hover:bg-[#16A34A] text-white rounded-[16px] flex items-center justify-center transition-all cursor-pointer shadow-sm active:scale-95"
                title={t(
                    "financeCalculator.step3.whatsapp",
                    "تواصل عبر واتساب",
                )}
            >
                <SiWhatsapp size={24} />
            </a>
            {/* Disclaimer Note */}
            <p className="mt-5 text-center text-[11px] md:text-[12px] text-white/50 font-medium leading-relaxed">
                {t(
                    "financeCalculator.step3.disclaimer",
                    "* الأرقام تقديرية بمعدل أرباح 4.5% سنوياً. يرجى التواصل للحصول على عرض نهائي معتمد.",
                )}
            </p>
        </div>
    );
}
