import { useState } from "react";
import { useTranslation } from "react-i18next";
import { toast } from "react-toastify";
import FormField from "./FormField";
import SelectBox from "./SelectBox";
import type { IStepOneFormProps } from "../../interfaces/IStepOneFormProps";

export default function StepOneForm({ onNext }: IStepOneFormProps) {
  const { t, i18n } = useTranslation();
  const [fullName, setFullName] = useState("");
  const [phone, setPhone] = useState("");
  const [city, setCity] = useState("");
  const [obligations, setObligations] = useState("");
  const [acceptTerms, setAcceptTerms] = useState(false);

  const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    if (!fullName || !phone || !city || !obligations) {
      toast.error(t("financeCalculator.validation.fillRequired", "يرجى تعبئة جميع الحقول المطلوبة"));
      return;
    }
    if (!acceptTerms) {
      toast.error(t("financeCalculator.validation.acceptTerms", "يجب الموافقة على الشروط وسياسة الخصوصية للمتابعة"));
      return;
    }
    onNext({
      fullName,
      phone,
      city,
      obligations,
      email: "",
      salary: "",
      message: "",
    });
  };

  const inputClasses =
    "h-[50px] w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-[14px] font-medium text-[#0F172A] outline-none transition placeholder:text-gray-400 focus:border-[#0F172A] focus:bg-white focus:ring-2 focus:ring-[#0F172A]/10 text-start";

  return (
    <div dir={i18n.dir()} className="w-full max-w-3xl mx-auto">
      <form
        onSubmit={handleSubmit}
        className="rounded-[24px] border border-[#E5E9F0] bg-white px-6 py-8 shadow-sm md:px-10"
      >
        <div className="text-start pt-4">
          <span className="text-[12px] font-extrabold text-[#EDC98E] block mb-1">
            {t("financeCalculator.step1.badge", "الخطوة الأولى")}
          </span>
          <h2 className="text-[22px] font-black text-[#0F172A]">
            {t("financeCalculator.step1.title", "معلوماتك الشخصية")}
          </h2>
        </div>

        <div className="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
          <FormField label={t("financeCalculator.step1.fullName", "الاسم الكامل")} required>
            <input
              className={inputClasses}
              placeholder={t("financeCalculator.step1.fullNamePlaceholder", "محمد عبدالله...")}
              type="text"
              required
              value={fullName}
              onChange={(e) => setFullName(e.target.value)}
            />
          </FormField>

          <FormField label={t("financeCalculator.step1.phone", "رقم الجوال")} required>
            <input
              className={`${inputClasses} text-start`}
              placeholder={t("financeCalculator.step1.phonePlaceholder", "05xxxxxxxx")}
              type="tel"
              required
              dir="ltr"
              value={phone}
              onChange={(e) => setPhone(e.target.value)}
            />
          </FormField>

          <FormField label={t("financeCalculator.step1.city", "المدينة")} required>
            <SelectBox
              placeholder={t("financeCalculator.step1.selectCity", "اختر مدينتك")}
              value={city}
              onChange={setCity}
            />
          </FormField>

          <FormField label={t("financeCalculator.step1.obligations", "الالتزامات الشهرية")} required>
            <input
              className={inputClasses}
              placeholder={t("financeCalculator.step1.obligationsPlaceholder", "0")}
              type="number"
              required
              value={obligations}
              onChange={(e) => setObligations(e.target.value)}
            />
          </FormField>
        </div>

        {/* Accept terms checkbox */}
        <div
          onClick={() => setAcceptTerms(!acceptTerms)}
          className="mt-8 flex items-start gap-3 text-start cursor-pointer select-none"
        >
          <input
            type="checkbox"
            id="acceptTerms"
            checked={acceptTerms}
            readOnly
            className="mt-1 h-4 w-4 shrink-0 rounded border-gray-300 text-[#0F172A] focus:ring-[#0F172A] cursor-pointer"
          />
          <label className="text-[13px] font-semibold text-gray-500 leading-relaxed cursor-pointer">
            {t("financeCalculator.step1.agreeText", "أوافق على ")}
            <a
              href="/privacy"
              target="_blank"
              onClick={(e) => e.stopPropagation()}
              className="text-[#0F172A] underline hover:text-black"
            >
              {t("financeCalculator.step1.privacyPolicy", "سياسة الخصوصية")}
            </a>
            {t("financeCalculator.step1.and", " و ")}
            <a
              href="/terms"
              target="_blank"
              onClick={(e) => e.stopPropagation()}
              className="text-[#0F172A] underline hover:text-black"
            >
              {t("financeCalculator.step1.termsOfUse", "شروط الاستخدام")}
            </a>
            {t("financeCalculator.step1.communicationConsent", "، وأوافق على التواصل معي من قبل فريق زاد كابيتال.")}
          </label>
        </div>

        {/* Submit Button */}
        <button
          type="submit"
          className="mt-8 flex h-[52px] w-full items-center justify-center gap-2 rounded-xl bg-[#0F172A] text-[15px] font-extrabold text-white transition hover:opacity-95 hover:scale-[1.01] active:scale-95 shadow-sm cursor-pointer"
        >
          <span>{t("financeCalculator.step1.continue", "متابعة")}</span>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="14"
            height="14"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2.5"
            strokeLinecap="round"
            strokeLinejoin="round"
            className="rtl:rotate-180"
          >
            <line x1="5" y1="12" x2="19" y2="12" />
            <polyline points="12 5 19 12 12 19" />
          </svg>
        </button>
      </form>
    </div>
  );
}
