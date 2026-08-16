import { ChevronDown } from "lucide-react";
import { useTranslation } from "react-i18next";
import { INPUT_CLASSES } from "../../constants/calculator.constants";
import { SAUDI_CITY_KEYS } from "../../constants/car-request.constants";
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
        {SAUDI_CITY_KEYS.map((city) => (
          <option key={city.key} value={city.value}>
            {t(`financeCalculator.cities.${city.key}`, city.value)}
          </option>
        ))}
      </select>
      <ChevronDown
        size={18}
        className="pointer-events-none absolute end-4 top-1/2 -translate-y-1/2 text-[#8A8F99]"
      />
    </div>
  );
}
