import { useEffect, useMemo, useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { toast } from "react-toastify";
import { calculateFinance, getBanks, submitCalculatorLead } from "../../services/api";
import { formatPrice } from "../../utils/format";
import { useSettingsStore } from "../../store/settings.store";
import { trackCalculatorLead } from "../../services/analytics";
import type { IBankItem, ICalculateData } from "../../interfaces/ICalculatorTypes";
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
    const [banks, setBanks] = useState<IBankItem[]>([]);
    const [selectedBankId, setSelectedBankId] = useState<number | undefined>(undefined);
    const [calcResult, setCalcResult] = useState<ICalculateData | null>(null);
    const [isSubmitting, setIsSubmitting] = useState(false);

    // Auto-capture lead upon reaching Step 3
    const autoCapturedRef = useRef(false);

    // Fetch active banks on mount
    useEffect(() => {
        getBanks()
            .then((data) => {
                setBanks(data);
                if (data.length > 0 && !selectedBankId) {
                    setSelectedBankId(data[0].id);
                }
            })
            .catch(() => {
                setBanks([]);
            });
    }, []);

    // Get selected bank rate
    const selectedBank = banks.find((b) => b.id === selectedBankId);
    const annualRate = selectedBank
        ? Number(selectedBank.annual_rate)
        : Number(settings?.calculator_default_interest_rate ?? 4.5);

    const carPrice = selectedCar?.price || 0;
    const minDownPayment = 0;
    const maxDownPayment = carPrice > 0 ? Math.round(carPrice * 0.5) : 100000;
    const downPaymentPercent = carPrice > 0 ? (downPayment / carPrice) * 100 : 0;

    // Client-side fallback calculation
    const clientCalc = useMemo(() => {
        const loan = Math.max(0, carPrice - downPayment);
        const monthlyRate = annualRate / 12 / 100;
        let monthly = 0;
        if (monthlyRate > 0 && term > 0) {
            const compounded = Math.pow(1 + monthlyRate, term);
            const denom = compounded - 1;
            monthly = denom > 0 ? (loan * (monthlyRate * compounded)) / denom : loan / term;
        } else if (term > 0) {
            monthly = loan / term;
        }
        const total = monthly * term;
        const interest = total - loan;
        return {
            loanAmount: Math.round(loan),
            monthlyPayment: Math.round(monthly),
            totalPayment: Math.round(total),
            totalInterest: Math.max(0, Math.round(interest)),
        };
    }, [carPrice, downPayment, annualRate, term]);

    // Query backend calculate endpoint
    useEffect(() => {
        if (!carId) return;

        calculateFinance({
            car_id: carId,
            down_payment_percentage: downPaymentPercent,
            period_months: term,
            bank_id: selectedBankId,
        })
            .then((res) => {
                if (res) {
                    setCalcResult(res);
                }
            })
            .catch((err) => {
                console.debug("Backend calculation fallback:", err);
            });
    }, [carId, downPaymentPercent, term, selectedBankId]);

    const monthlyPayment = calcResult?.monthly_payment ?? clientCalc.monthlyPayment;
    const loanAmount = calcResult?.loan_amount ?? clientCalc.loanAmount;
    const totalPayment = calcResult?.total_payment ?? clientCalc.totalPayment;
    const totalInterest = calcResult?.total_interest ?? clientCalc.totalInterest;

    // Auto-capture lead upon reaching Step 3 (even before clicking submit)
    useEffect(() => {
        if (!personalInfo || !personalInfo.fullName || !personalInfo.phone || autoCapturedRef.current) return;

        autoCapturedRef.current = true;

        submitCalculatorLead({
            name: personalInfo.fullName,
            phone: personalInfo.phone,
            city: personalInfo.city,
            purpose: "شراء",
            salary: Number(salary),
            monthly_obligations: Number(personalInfo.obligations),
            car_ids: carId ? [carId] : [],
            notes: `[تسجيل تلقائي عند الوصول لحاسبة التمويل] اللون: ${selectedColor} | الراتب: ${salary} | الدفعة: ${downPayment}`,
            preferred_bank_id: selectedBankId,
            monthly_installment: monthlyPayment,
            down_payment: downPayment,
            period_months: term,
            preferred_color: selectedColor,
            employer_type: personalInfo.employerType,
            has_mortgage_loan: personalInfo.hasMortgageLoan,
            has_personal_loan: personalInfo.hasPersonalLoan,
            has_traffic_violations: personalInfo.hasTrafficViolations,
            has_simah_default: personalInfo.hasSimahDefault,
        })
            .then(() => {
                trackCalculatorLead({
                    carName: selectedCar ? `${selectedCar.brand || ''} ${selectedCar.name}`.trim() : undefined,
                    salary: Number(salary),
                    monthlyInstallment: monthlyPayment,
                });
            })
            .catch((err) => {
                console.debug("Background calculator lead auto-capture:", err);
            });
    }, [personalInfo, carId]);

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
                car_ids: carId ? [carId] : [],
                notes: t("financeCalculator.step3.leadNotes", {
                    color: selectedColor,
                    salary,
                    downPayment,
                    defaultValue: `اللون المطلوب: ${selectedColor} | الراتب: ${salary} | الدفعة الأولى: ${downPayment}`,
                }),
                preferred_bank_id: selectedBankId,
                monthly_installment: monthlyPayment,
                down_payment: downPayment,
                period_months: term,
                preferred_color: selectedColor,
            });

            // Trigger Pixel & GTM Calculator Lead events
            trackCalculatorLead({
                carName: selectedCar ? `${selectedCar.brand || ''} ${selectedCar.name}`.trim() : undefined,
                salary: Number(salary),
                monthlyInstallment: monthlyPayment,
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
        const range = max - min;
        const percent = range > 0 ? Math.min(100, Math.max(0, ((val - min) / range) * 100)) : 0;
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
                                "الخطوة الثالثة",
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
                                max={maxDownPayment || 100000}
                                step={1000}
                                value={downPayment}
                                onChange={(e) =>
                                    setDownPayment(Number(e.target.value))
                                }
                                style={sliderTrackStyle(
                                    downPayment,
                                    minDownPayment,
                                    maxDownPayment || 100000,
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
                            <div className="grid grid-cols-5 gap-2">
                                {[12, 24, 36, 48, 60].map((option) => (
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
