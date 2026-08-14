import { useTranslation } from "react-i18next";
import { Sparkles, ChevronDown, Send } from "lucide-react";
import type { ICarRequestFormProps } from "../../interfaces/ICarRequestFormProps";
import { BankDropdownSelector } from "./BankDropdownSelector";

export function CarRequestForm({
    formData,
    onChange,
    onSubmit,
    isSubmitting,
    saudiCities,
    employerTypes,
    serviceDurations,
    banks,
    selectedBankId,
    onSelectBankId,
    loadingBanks,
}: ICarRequestFormProps) {
    const { t } = useTranslation();

    const inputClasses =
        "h-[50px] w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-[14px] font-medium text-[#0F172A] outline-none transition placeholder:text-gray-400 focus:border-[#0F172A] focus:bg-white focus:ring-2 focus:ring-[#0F172A]/10";

    return (
        <div className="lg:col-span-8">
            <div className="bg-white border border-[#E5E9F0] rounded-[24px] p-6 md:p-8 shadow-sm flex flex-col gap-6">
                <div className="text-start border-b border-gray-100 pb-4">
                    <h2 className="text-[22px] font-black text-[#0F172A] flex items-center gap-2">
                        <Sparkles className="text-[#EDC98E]" size={22} />
                        <span>{t("carRequest.form.title", "بياناتك الشخصية")}</span>
                    </h2>
                    <p className="text-[13px] text-gray-400 font-bold mt-1">
                        {t(
                            "carRequest.form.subtitle",
                            "يرجى إدخال معلوماتك الشخصية بدقة لتسجيل طلبك",
                        )}
                    </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {/* الاسم الكامل */}
                    <div className="flex flex-col text-start">
                        <label className="text-[14px] font-extrabold text-[#374151] mb-2">
                            {t("carRequest.form.fullName", "الاسم الكامل")}{" "}
                            <span className="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            required
                            className={inputClasses}
                            placeholder={t(
                                "carRequest.form.fullNamePlaceholder",
                                "محمد عبدالله...",
                            )}
                            value={formData.fullName}
                            onChange={(e) => onChange("fullName", e.target.value)}
                        />
                    </div>

                    {/* رقم الجوال */}
                    <div className="flex flex-col text-start">
                        <label className="text-[14px] font-extrabold text-[#374151] mb-2">
                            {t("carRequest.form.phone", "رقم الجوال")}{" "}
                            <span className="text-red-500">*</span>
                        </label>
                        <input
                            type="tel"
                            required
                            dir="ltr"
                            className={`${inputClasses} text-start`}
                            placeholder={t(
                                "carRequest.form.phonePlaceholder",
                                "05xxxxxxxx",
                            )}
                            value={formData.phone}
                            onChange={(e) => {
                                const cleaned = e.target.value.replace(/\D/g, "").slice(0, 10);
                                onChange("phone", cleaned);
                            }}
                        />
                    </div>

                    {/* المدينة */}
                    <div className="flex flex-col text-start">
                        <label className="text-[14px] font-extrabold text-[#374151] mb-2">
                            {t("carRequest.form.city", "المدينة")}{" "}
                            <span className="text-red-500">*</span>
                        </label>
                        <div className="relative">
                            <select
                                value={formData.city}
                                onChange={(e) => onChange("city", e.target.value)}
                                className={`${inputClasses} appearance-none pe-12`}
                                required
                            >
                                <option value="">
                                    {t("carRequest.form.selectCity", "اختر مدينتك")}
                                </option>
                                {saudiCities.map((cityOpt) => (
                                    <option
                                        key={cityOpt.value}
                                        value={cityOpt.value}
                                    >
                                        {cityOpt.label}
                                    </option>
                                ))}
                            </select>
                            <ChevronDown
                                size={18}
                                className="pointer-events-none absolute end-4 top-1/2 -translate-y-1/2 text-[#8A8F99]"
                            />
                        </div>
                    </div>

                    {/* جهة العمل */}
                    <div className="flex flex-col text-start">
                        <label className="text-[14px] font-extrabold text-[#374151] mb-2">
                            {t("carRequest.form.employerType", "جهة العمل")}{" "}
                            <span className="text-red-500">*</span>
                        </label>
                        <div className="relative">
                            <select
                                value={formData.employerType}
                                onChange={(e) =>
                                    onChange("employerType", e.target.value)
                                }
                                className={`${inputClasses} appearance-none pe-12`}
                                required
                            >
                                <option value="">
                                    {t(
                                        "carRequest.form.selectEmployerType",
                                        "اختر قطاع عملك",
                                    )}
                                </option>
                                {employerTypes.map((emp) => (
                                    <option key={emp.value} value={emp.value}>
                                        {emp.label}
                                    </option>
                                ))}
                            </select>
                            <ChevronDown
                                size={18}
                                className="pointer-events-none absolute end-4 top-1/2 -translate-y-1/2 text-[#8A8F99]"
                            />
                        </div>
                    </div>

                    {/* مدة الخدمة بالوظيفة */}
                    <div className="flex flex-col text-start">
                        <label className="text-[14px] font-extrabold text-[#374151] mb-2">
                            {t("carRequest.form.serviceDuration", "مدة الخدمة بالوظيفة")}{" "}
                            <span className="text-red-500">*</span>
                        </label>
                        <div className="relative">
                            <select
                                value={formData.yearsOfService}
                                onChange={(e) =>
                                    onChange(
                                        "yearsOfService",
                                        Number(e.target.value),
                                    )
                                }
                                className={`${inputClasses} appearance-none pe-12`}
                                required
                            >
                                {serviceDurations.map((duration) => (
                                    <option
                                        key={duration.value}
                                        value={duration.value}
                                    >
                                        {duration.label}
                                    </option>
                                ))}
                            </select>
                            <ChevronDown
                                size={18}
                                className="pointer-events-none absolute end-4 top-1/2 -translate-y-1/2 text-[#8A8F99]"
                            />
                        </div>
                    </div>

                    {/* صافي الراتب */}
                    <div className="flex flex-col text-start">
                        <label className="text-[14px] font-extrabold text-[#374151] mb-2">
                            {t("carRequest.form.salary", "صافي الراتب")}{" "}
                            <span className="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            required
                            className={inputClasses}
                            placeholder={t(
                                "carRequest.form.salaryPlaceholder",
                                "مثال: 8500",
                            )}
                            value={formData.salary}
                            onChange={(e) => onChange("salary", e.target.value)}
                        />
                    </div>

                    {/* البنك المفضل */}
                    <BankDropdownSelector
                        banks={banks}
                        selectedBankId={selectedBankId}
                        onSelectBankId={onSelectBankId}
                        loadingBanks={loadingBanks}
                    />

                    {/* الالتزامات الشهرية */}
                    <div className="flex flex-col text-start">
                        <label className="text-[14px] font-extrabold text-[#374151] mb-2">
                            {t("carRequest.form.obligations", "الالتزامات الشهرية")}{" "}
                            <span className="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            required
                            className={inputClasses}
                            placeholder={t(
                                "carRequest.form.obligationsPlaceholder",
                                "اكتب إجمالي الالتزامات الشهرية",
                            )}
                            value={formData.obligations}
                            onChange={(e) =>
                                onChange("obligations", e.target.value)
                            }
                        />
                    </div>
                </div>

                {/* Toggles Panel */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                    {/* قرض شخصي */}
                    <div className="flex flex-col text-start">
                        <label className="text-[14px] font-extrabold text-[#374151] mb-2">
                            {t("carRequest.form.hasPersonalLoan", "قرض شخصي")}{" "}
                            <span className="text-red-500">*</span>
                        </label>
                        <div className="grid grid-cols-2 gap-3">
                            <button
                                type="button"
                                onClick={() => onChange("hasPersonalLoan", true)}
                                className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                                    formData.hasPersonalLoan
                                        ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                                        : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                                }`}
                            >
                                {t("carRequest.form.yes", "نعم")}
                            </button>
                            <button
                                type="button"
                                onClick={() => onChange("hasPersonalLoan", false)}
                                className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                                    !formData.hasPersonalLoan
                                        ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                                        : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                                }`}
                            >
                                {t("carRequest.form.no", "لا")}
                            </button>
                        </div>
                    </div>

                    {/* قرض عقاري */}
                    <div className="flex flex-col text-start">
                        <label className="text-[14px] font-extrabold text-[#374151] mb-2">
                            {t("carRequest.form.hasMortgageLoan", "قرض عقاري")}{" "}
                            <span className="text-red-500">*</span>
                        </label>
                        <div className="grid grid-cols-2 gap-3">
                            <button
                                type="button"
                                onClick={() => onChange("hasMortgageLoan", true)}
                                className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                                    formData.hasMortgageLoan
                                        ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                                        : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                                }`}
                            >
                                {t("carRequest.form.yes", "نعم")}
                            </button>
                            <button
                                type="button"
                                onClick={() => onChange("hasMortgageLoan", false)}
                                className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                                    !formData.hasMortgageLoan
                                        ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                                        : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                                }`}
                            >
                                {t("carRequest.form.no", "لا")}
                            </button>
                        </div>
                    </div>

                    {/* تعثر سمة */}
                    <div className="flex flex-col text-start">
                        <label className="text-[14px] font-extrabold text-[#374151] mb-2">
                            {t(
                                "carRequest.form.hasSimahDefault",
                                "هل لديك تعثر في سمة؟",
                            )}{" "}
                            <span className="text-red-500">*</span>
                        </label>
                        <div className="grid grid-cols-2 gap-3">
                            <button
                                type="button"
                                onClick={() => onChange("hasSimahDefault", true)}
                                className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                                    formData.hasSimahDefault
                                        ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                                        : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                                }`}
                            >
                                {t("carRequest.form.yes", "نعم")}
                            </button>
                            <button
                                type="button"
                                onClick={() => onChange("hasSimahDefault", false)}
                                className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                                    !formData.hasSimahDefault
                                        ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                                        : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                                }`}
                            >
                                {t("carRequest.form.no", "لا")}
                            </button>
                        </div>
                    </div>

                    {/* مخالفات مرورية */}
                    <div className="flex flex-col text-start">
                        <label className="text-[14px] font-extrabold text-[#374151] mb-2">
                            {t(
                                "carRequest.form.hasTrafficViolations",
                                "هل لديك مخالفات مرورية؟",
                            )}{" "}
                            <span className="text-red-500">*</span>
                        </label>
                        <div className="grid grid-cols-2 gap-3">
                            <button
                                type="button"
                                onClick={() =>
                                    onChange("hasTrafficViolations", true)
                                }
                                className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                                    formData.hasTrafficViolations
                                        ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                                        : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                                }`}
                            >
                                {t("carRequest.form.yes", "نعم")}
                            </button>
                            <button
                                type="button"
                                onClick={() =>
                                    onChange("hasTrafficViolations", false)
                                }
                                className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                                    !formData.hasTrafficViolations
                                        ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                                        : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                                }`}
                            >
                                {t("carRequest.form.no", "لا")}
                            </button>
                        </div>
                    </div>
                </div>

                {/* Submit Button */}
                <button
                    type="submit"
                    disabled={isSubmitting}
                    className="mt-6 flex h-[54px] w-full items-center justify-center gap-2 rounded-xl bg-[#0F172A] text-[16px] font-extrabold text-white transition hover:opacity-95 disabled:opacity-50 hover:scale-[1.01] active:scale-95 shadow-sm cursor-pointer"
                >
                    <Send size={18} />
                    <span>
                        {isSubmitting
                            ? t("carRequest.form.submitting", "جاري إرسال طلبك...")
                            : t("carRequest.form.submit", "إرسال طلب السيارة")}
                    </span>
                </button>
            </div>
        </div>
    );
}
