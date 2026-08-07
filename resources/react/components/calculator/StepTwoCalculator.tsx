import { useEffect, useState } from "react";
import { useTranslation } from "react-i18next";
import { toast } from "react-toastify";
import { MessageCircle } from "lucide-react";
import { calculateFinance, submitCalculatorLead } from "../../services/api";
import { formatPrice } from "../../utils/format";
import Stepper from "./Stepper";
import type { ICalculateData } from "../../interfaces/ICalculatorTypes";
import type { ISelectedCar } from "../../interfaces/ISelectedCar";
import type { IPersonalInfo } from "../../interfaces/IPersonalInfo";

interface IStepTwoCalculatorProps {
  selectedCar: ISelectedCar;
  selectedColor: string;
  salary: number;
  setSalary: (val: number) => void;
  downPayment: number;
  setDownPayment: (val: number) => void;
  term: number;
  setTerm: (val: number) => void;
  personalInfo: IPersonalInfo;
  carId: number;
  onBack: () => void;
  onSubmitSuccess: () => void;
}

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
  const [calcResult, setCalcResult] = useState<ICalculateData | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  // Down Payment range constraints: 10% to 50% of the car price
  const minDownPayment = Math.round(selectedCar.price * 0.1);
  const maxDownPayment = Math.round(selectedCar.price * 0.5);

  const downPaymentPercent = Math.round((downPayment * 100) / selectedCar.price);

  useEffect(() => {
    // Perform dynamic finance calculations via backend API
    calculateFinance({
      car_id: carId,
      down_payment_percentage: downPaymentPercent,
      period_months: term,
      bank_id: 2, // Default bank ID
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
        email: personalInfo.email || `${personalInfo.fullName.replace(/\s+/g, '')}@zed.com`,
        city: personalInfo.city,
        purpose: "شراء",
        salary: Number(salary),
        monthly_obligations: Number(personalInfo.obligations),
        car_ids: [carId],
        notes: `اللون المطلوبة: ${selectedColor} | الراتب: ${salary} | الدفعة الأولى: ${downPayment}`,
        preferred_bank_id: 2,
      });
      onSubmitSuccess();
    } catch (error: any) {
      console.error("Submission error:", error);
      const apiMessage = error.response?.data?.message;
      const validationErrors = error.response?.data?.errors;
      if (validationErrors) {
        console.error("Validation errors:", validationErrors);
        const firstError = Object.values(validationErrors)[0];
        if (Array.isArray(firstError) && firstError.length > 0) {
          toast.error(firstError[0]);
          return;
        }
      }
      toast.error(apiMessage || t("financeCalculator.step2.errorToast", { defaultValue: "حدث خطأ أثناء إرسال الطلب، يرجى المحاولة لاحقاً" }));
    } finally {
      setIsSubmitting(false);
    }
  };

  const sliderTrackStyle = (val: number, min: number, max: number) => {
    const percent = ((val - min) / (max - min)) * 100;
    return {
      background: `linear-gradient(to left, #0F172A ${percent}%, #E2E8F0 ${percent}%)`,
    };
  };

  return (
    <div dir={i18n.dir()} className="w-full max-w-3xl mx-auto">
      <div className="rounded-[24px] border border-[#E5E9F0] bg-white px-6 py-8 shadow-sm md:px-10">
        <div className="mb-8">
          <Stepper activeStep={3} />
        </div>

        {/* Back navigation */}
        <button
          type="button"
          onClick={onBack}
          className="mb-6 flex items-center gap-1.5 text-[13px] font-bold text-gray-500 hover:text-[#0F172A] transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" className="rotate-180 rtl:rotate-0">
            <line x1="5" y1="12" x2="19" y2="12" />
            <polyline points="12 5 19 12 12 19" />
          </svg>
          <span>رجوع</span>
        </button>

        <div className="text-start mb-6">
          <span className="text-[12px] font-extrabold text-[#EDC98E] block mb-1">
            الخطوة الثانية
          </span>
          <h2 className="text-[22px] font-black text-[#0F172A]">تفاصيل التمويل</h2>
          <p className="text-[13px] text-gray-500 font-bold mt-1">
            تفاصيل السيارة: <strong className="text-[#0F172A] font-black">{selectedCar.name}</strong>
          </p>
        </div>

        {/* Sliders and months selector */}
        <div className="space-y-8 mt-8">
          
          {/* Down Payment Slider */}
          <div className="flex flex-col text-start">
            <div className="flex justify-between items-baseline mb-2">
              <label className="text-[14px] font-extrabold text-[#374151]">الدفعة الأولى</label>
              <strong className="text-[18px] font-black text-[#EDC98E]">
                {formatPrice(downPayment, "#EDC98E")}
              </strong>
            </div>
            <input
              type="range"
              min={minDownPayment}
              max={maxDownPayment}
              step={1000}
              value={downPayment}
              onChange={(e) => setDownPayment(Number(e.target.value))}
              style={sliderTrackStyle(downPayment, minDownPayment, maxDownPayment)}
              className="h-[6px] w-full cursor-pointer rounded-lg appearance-none accent-[#0F172A]"
            />
            <div className="flex justify-between text-[11px] text-gray-400 font-bold mt-2">
              <span>{formatPrice(minDownPayment, "gray")}</span>
              <span>{formatPrice(maxDownPayment, "gray")}</span>
            </div>
          </div>

          {/* Salary Slider */}
          <div className="flex flex-col text-start">
            <div className="flex justify-between items-baseline mb-2">
              <label className="text-[14px] font-extrabold text-[#374151]">الراتب الشهري</label>
              <strong className="text-[18px] font-black text-[#EDC98E]">
                {formatPrice(salary, "#EDC98E")}
              </strong>
            </div>
            <input
              type="range"
              min={3000}
              max={50000}
              step={500}
              value={salary}
              onChange={(e) => setSalary(Number(e.target.value))}
              style={sliderTrackStyle(salary, 3000, 50000)}
              className="h-[6px] w-full cursor-pointer rounded-lg appearance-none accent-[#0F172A]"
            />
            <div className="flex justify-between text-[11px] text-gray-400 font-bold mt-2">
              <span>{formatPrice(3000, "gray")}</span>
              <span>{formatPrice(50000, "gray")}</span>
            </div>
          </div>

          {/* Finance Term Months */}
          <div className="flex flex-col text-start">
            <label className="text-[14px] font-extrabold text-[#374151] mb-3">
              مدة التمويل: <span className="text-[#EDC98E] font-black">{term} شهر</span>
            </label>
            <div className="flex flex-wrap gap-2.5">
              {[24, 36, 48, 60, 72, 84].map((option) => (
                <button
                  key={option}
                  type="button"
                  onClick={() => setTerm(option)}
                  className={`flex h-11 w-14 items-center justify-center rounded-xl text-[14px] font-extrabold transition-all duration-300 ${
                    option === term
                      ? "bg-[#0F172A] text-white scale-105 shadow-md"
                      : "bg-[#F8FAFC] border border-[#E2E8F0] text-gray-500 hover:border-gray-400"
                  }`}
                >
                  {option}
                </button>
              ))}
            </div>
          </div>

        </div>

        {/* Results Card */}
        <div className="mt-10 rounded-[24px] bg-[#0F172A] p-6 text-white shadow-xl relative overflow-hidden">
          <div className="absolute top-0 right-0 w-36 h-36 bg-[#EDC98E]/5 blur-2xl rounded-full" />
          
          <span className="text-[11px] font-extrabold text-white/50 uppercase tracking-wider block text-start mb-4 border-b border-white/5 pb-2">
            نتيجة الحساب
          </span>

          <div className="grid grid-cols-2 md:grid-cols-4 gap-6 text-start">
            <div>
              <span className="text-[12px] text-white/50 font-bold block mb-1">القسط الشهري</span>
              <strong className="text-[20px] font-black text-[#EDC98E] tracking-tight block">
                {formatPrice(monthlyPayment, "#EDC98E")}
              </strong>
            </div>
            <div>
              <span className="text-[12px] text-white/50 font-bold block mb-1">مبلغ التمويل</span>
              <strong className="text-[18px] font-bold text-white block">
                {formatPrice(loanAmount, "white")}
              </strong>
            </div>
            <div>
              <span className="text-[12px] text-white/50 font-bold block mb-1">إجمالي المدفوعات</span>
              <strong className="text-[18px] font-bold text-white block">
                {formatPrice(totalPayment, "white")}
              </strong>
            </div>
            <div>
              <span className="text-[12px] text-white/50 font-bold block mb-1">إجمالي الأرباح</span>
              <strong className="text-[18px] font-bold text-white block">
                {formatPrice(totalInterest, "white")}
              </strong>
            </div>
          </div>

          {/* Action Row */}
          <div className="mt-8 flex flex-col md:flex-row gap-3">
            <button
              type="button"
              onClick={handleSubmitLead}
              disabled={isSubmitting}
              className="flex-1 h-[50px] bg-[#EDC98E] text-[#0F172A] text-[15px] font-extrabold rounded-xl flex items-center justify-center transition-all duration-300 hover:scale-[1.01] hover:shadow-lg disabled:opacity-50 active:scale-95"
            >
              {isSubmitting ? "جاري إرسال الطلب..." : "قدم طلب التمويل الآن"}
            </button>
            <a
              href={`https://wa.me/966500000000?text=أرغب في الاستفسار عن تمويل سيارة ${selectedCar.brand} ${selectedCar.name} بقسط شهري مقدر ${monthlyPayment}`}
              target="_blank"
              rel="noreferrer"
              className="h-[50px] w-full md:w-[50px] shrink-0 bg-[#25D366] text-white rounded-xl flex items-center justify-center transition hover:bg-[#20ba59] active:scale-95"
            >
              <MessageCircle size={20} />
            </a>
          </div>

          <p className="mt-4 text-center text-[10px] text-white/40 font-semibold leading-relaxed">
            * الأرقام تقديرية بمعدل أرباح 4.5% سنوياً. يرجى التواصل للحصول على عرض نهائي معتمد.
          </p>
        </div>

      </div>
    </div>
  );
}
