import { useTranslation } from "react-i18next";
import { FINANCE_TERMS } from "../../constants/car-request.constants";
import type { ICarTermSelectorProps } from "../../interfaces/ICarTermSelectorProps";

export function CarTermSelector({
    term,
    onChangeTerm,
}: ICarTermSelectorProps) {
    const { t } = useTranslation();

    return (
        <div className="text-start">
            <span className="text-[13px] font-extrabold text-[#374151] block mb-3">
                {t("carRequest.summary.financeTerm", "مدة التمويل:")}{" "}
                <span className="text-[#EDC98E] font-black">
                    {t("carRequest.summary.months", "{{term}} شهر", { term })}
                </span>
            </span>
            <div className="grid grid-cols-5 gap-2">
                {[12, 24, 36, 48, 60].map((month) => (
                    <button
                        key={month}
                        type="button"
                        onClick={() => onChangeTerm(month)}
                        className={`flex h-11 items-center justify-center rounded-xl text-[13px] font-extrabold transition-all ${
                            term === month
                                ? "bg-[#0F172A] text-white scale-105 shadow-md"
                                : "bg-[#F8FAFC] border border-[#E2E8F0] text-gray-500 hover:border-gray-400"
                        }`}
                    >
                        {month}
                    </button>
                ))}
            </div>
        </div>
    );
}
