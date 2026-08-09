import { useEffect, useState } from "react";
import { useTranslation } from "react-i18next";
import { toast } from "react-toastify";
import { calculateFinance, submitCalculatorLead } from "../../services/api";
import { formatPrice } from "../../utils/format";
import { useSettingsStore } from "../../store/settings.store";
import type { ICalculateData } from "../../interfaces/ICalculatorTypes";
import type { IStepTwoCalculatorProps } from "../../interfaces/IStepTwoCalculatorProps";
import CalculatorResultCard from "./CalculatorResultCard";

export default function StepTwoCalculator({
    selectedCar,
    selectedColor,
    salary,
    setSalary,
    downPayment,
    setDownPayment,
    term,
    setTerm,
    personalInfo,
    carId,
    onBack,
    onSubmitSuccess,
}: IStepTwoCalculatorProps) {
    const { t, i18n } = useTranslation();
    const settings = useSettingsStore((s) => s.settings);
    const [calcResult, setCalcResult] = useState<ICalculateData | null>(null);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const minDownPayment = Math.round(selectedCar.price * 0.1);
    const maxDownPayment = Math.round(selectedCar.price * 0.5);

    const downPaymentPercent = Math.round(
        (downPayment * 100) / selectedCar.price,
    );

    useEffect(() => {
        calculateFinance({
            car_id: carId,
            down_payment_percentage: downPaymentPercent,
            period_months: term,
            bank_id: 2,
        })
            .then(setCalcResult)
            .catch(() => {});
    }, [carId, downPaymentPercent, term]);

    const monthlyPayment = calcResult?.monthly_payment ?? 0;
    const loanAmount = calcResult?.loan_amount ?? 0;
    const totalPayment = calcResult?.total_payment ?? 0;
    const totalInterest = calcResult?.total_interest ?? 0;

    const handleSubmitLead = async () => {
        setIsSubmitting(true);
        try {
            await submitCalculatorLead({
                name: personalInfo.fullName,
                phone: personalInfo.phone,
                city: personalInfo.city,
                purpose: "شراء",
                salary: Number(salary),
                monthly_obligations: Number(personalInfo.obligations),
                car_ids: [carId],
                notes: t("financeCalculator.step3.leadNotes", {
                    color: selectedColor,
                    salary,
                    downPayment,
                    defaultValue: `اللون المطلوبة: ${selectedColor} | الراتب: ${salary} | الدفعة الأولى: ${downPayment}`,
                }),
                preferred_bank_id: 2,
            });
            onSubmitSuccess();
        } catch (error: any) {
            console.error("Submission error:", error);
            const apiMessage = error.response?.data?.message;
            const validationErrors = error.response?.data?.errors;
            if (validationErrors) {
                const firstError = Object.values(validationErrors)[0];
                if (Array.isArray(firstError) && firstError.length > 0) {
                    toast.error(firstError[0] as string);
                    return;
                }
            }
            toast.error(
                apiMessage ||
                    t(
                        "financeCalculator.step3.errorToast",
                        "حدث خطأ أثناء إرسال الطلب، يرجى المحاولة لاحقاً",
                    ),
            );
        } finally {
            setIsSubmitting(false);
        }
    };

    const isRtl = i18n.dir() === "rtl";
    const sliderTrackStyle = (val: number, min: number, max: number) => {
        const percent = Math.min(
            100,
            Math.max(0, ((val - min) / (max - min)) * 100),
        );
        const direction = isRtl ? "to left" : "to right";
        return {
            background: `linear-gradient(${direction}, #16254F ${percent}%, #E2E8F0 ${percent}%)`,
        };
    };

    const whatsappNum =
        settings?.contact?.whatsapp?.replace(/\D/g, "") ?? "966500000000";
    const carName = selectedCar.brand
        ? `${selectedCar.brand} ${selectedCar.name}`.trim()
        : selectedCar.name;
    const whatsappMsg = encodeURIComponent(
        t("financeCalculator.step3.whatsappMessage", {
            carName,
            monthlyPayment,
            defaultValue: `مرحباً، أود الاستفسار عن تمويل سيارة (${carName}) بقسط شهري مقدر ${monthlyPayment} ريال.`,
        }),
    );
    const whatsappHref = `https://wa.me/${whatsappNum}?text=${whatsappMsg}`;

    return (
        <div
            dir={i18n.dir()}
            className="w-full max-w-4xl mx-auto flex flex-col gap-6 select-none"
        >
            {/* TOP WHITE CARD */}
            <div className="rounded-[28px] border border-[#E5E9F0] bg-white p-6 md:p-10 shadow-xs">
                {/* Header Row */}
                <div className="flex items-center justify-between border-b border-gray-100/80 pb-5 mb-8">
                    <div className="text-start">
                        <span className="text-[13px] font-extrabold text-[#EDC98E] block mb-1">
                            {t(
                                "financeCalculator.step3.badge",
                                "الخطوة الثانية",
                            )}
                        </span>
                        <h2 className="text-[26px] md:text-[30px] font-black text-[#16254F] leading-tight">
                            {t(
                                "financeCalculator.step3.title",
                                "تفاصيل التمويل",
                            )}
                        </h2>
                    </div>
                    <button
                        type="button"
                        onClick={onBack}
                        className="flex items-center gap-1.5 text-[14px] font-bold text-[#94A3B8] hover:text-[#16254F] transition-colors cursor-pointer"
                    >
                        <span>{t("financeCalculator.step3.back", "رجوع")}</span>
                        <span className="text-[16px]">{isRtl ? "←" : "→"}</span>
                    </button>
                </div>

                {/* 2-Column Form Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    {/* Column 1: Car Details & Salary */}
                    <div className="flex flex-col gap-8 text-start">
                        {/* Car Details */}
                        <div className="flex flex-col">
                            <span className="text-[13px] font-extrabold text-[#64748B] mb-1">
                                {t(
                                    "financeCalculator.step3.carDetails",
                                    "تفاصيل السيارة",
                                )}
                            </span>
                            <div className="flex items-baseline gap-3 flex-wrap">
                                <span className="text-[18px] md:text-[20px] font-black text-[#16254F]">
                                    {carName}
                                </span>
                                <span className="text-[14px] md:text-[15px] font-bold text-[#64748B]">
                                    {formatPrice(selectedCar.price, "#64748B")}
                                </span>
                            </div>
                        </div>

                        {/* Salary Slider */}
                        <div className="flex flex-col">
                            <div className="flex justify-between items-baseline mb-2">
                                <span className="text-[14px] font-extrabold text-[#374151]">
                                    {t(
                                        "financeCalculator.step3.salary",
                                        "الراتب الشهري",
                                    )}
                                </span>
                                <strong className="text-[16px] md:text-[18px] font-black text-[#EDC98E]">
                                    {formatPrice(salary, "#EDC98E")}
                                </strong>
                            </div>
                            <input
                                type="range"
                                min={3000}
                                max={50000}
                                step={500}
                                value={salary}
                                onChange={(e) =>
                                    setSalary(Number(e.target.value))
                                }
                                style={sliderTrackStyle(salary, 3000, 50000)}
                                className="h-[6px] w-full cursor-pointer rounded-lg appearance-none accent-[#16254F]"
                            />
                        </div>
                    </div>

                    {/* Column 2: Down Payment & Finance Term */}
                    <div className="flex flex-col gap-8 text-start">
                        {/* Down Payment Slider */}
                        <div className="flex flex-col">
                            <div className="flex justify-between items-baseline mb-2">
                                <span className="text-[14px] font-extrabold text-[#374151]">
                                    {t(
                                        "financeCalculator.step3.downPayment",
                                        "الدفعة الأولى",
                                    )}
                                </span>
                                <strong className="text-[16px] md:text-[18px] font-black text-[#EDC98E]">
                                    {formatPrice(downPayment, "#EDC98E")}
                                </strong>
                            </div>
                            <input
                                type="range"
                                min={minDownPayment}
                                max={maxDownPayment}
                                step={1000}
                                value={downPayment}
                                onChange={(e) =>
                                    setDownPayment(Number(e.target.value))
                                }
                                style={sliderTrackStyle(
                                    downPayment,
                                    minDownPayment,
                                    maxDownPayment,
                                )}
                                className="h-[6px] w-full cursor-pointer rounded-lg appearance-none accent-[#16254F]"
                            />
                        </div>

                        {/* Finance Term Months */}
                        <div className="flex flex-col">
                            <div className="flex justify-start items-baseline gap-1.5 mb-3">
                                <span className="text-[14px] font-extrabold text-[#374151]">
                                    {t(
                                        "financeCalculator.step3.termTitle",
                                        "مدة التمويل:",
                                    )}
                                </span>
                                <span className="text-[14px] font-black text-[#EDC98E]">
                                    {t("financeCalculator.step3.month", {
                                        term,
                                        defaultValue: `${term} شهر`,
                                    })}
                                </span>
                            </div>
                            <div className="grid grid-cols-6 gap-2">
                                {[24, 36, 48, 60, 72, 84].map((option) => (
                                    <button
                                        key={option}
                                        type="button"
                                        onClick={() => setTerm(option)}
                                        className={`flex h-11 items-center justify-center rounded-xl text-[14px] font-extrabold transition-all duration-200 cursor-pointer ${
                                            option === term
                                                ? "bg-[#16254F] text-white scale-105 shadow-md"
                                                : "bg-[#F8FAFC] border border-[#E2E8F0] text-gray-500 hover:border-gray-400"
                                        }`}
                                    >
                                        {option}
                                    </button>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* BOTTOM DARK NAVY CARD */}
            <CalculatorResultCard
                monthlyPayment={monthlyPayment}
                loanAmount={loanAmount}
                totalPayment={totalPayment}
                totalInterest={totalInterest}
                whatsappHref={whatsappHref}
                isSubmitting={isSubmitting}
                onSubmitLead={handleSubmitLead}
            />
        </div>
    );
}
