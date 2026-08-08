import { ChevronDown } from "lucide-react";
import { useTranslation } from "react-i18next";
import { INPUT_CLASSES } from "../../constants/calculator.constants";
import type { ISelectBoxProps } from "../../interfaces/ISelectBoxProps";

export default function SelectBox({ placeholder, value, onChange }: ISelectBoxProps) {
  const { t } = useTranslation();

  return (
    <div className="relative">
      <select
        value={value}
        onChange={(event) => onChange(event.target.value)}
        className={`${INPUT_CLASSES} appearance-none pe-12 text-start cursor-pointer`}
      >
        <option value="">{placeholder}</option>
        <option value={t("financeCalculator.cities.riyadh", "الرياض")}>
          {t("financeCalculator.cities.riyadh", "الرياض")}
        </option>
        <option value={t("financeCalculator.cities.jeddah", "جدة")}>
          {t("financeCalculator.cities.jeddah", "جدة")}
        </option>
        <option value={t("financeCalculator.cities.dammam", "الدمام")}>
          {t("financeCalculator.cities.dammam", "الدمام")}
        </option>
      </select>
      <ChevronDown
        size={18}
        className="pointer-events-none absolute end-4 top-1/2 -translate-y-1/2 text-[#8A8F99]"
      />
    </div>
  );
}
