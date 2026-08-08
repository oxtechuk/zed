import { useTranslation } from "react-i18next";
import type { IColorPickerProps } from "../../interfaces/IColorPickerProps";

export default function ColorPicker({
  availableColors,
  selectedColor,
  setSelectedColor,
}: IColorPickerProps) {
  const { t } = useTranslation();

  return (
    <div className="mb-8 text-start">
      <span className="text-[13px] font-bold text-[#374151] block mb-3.5">
        {t("financeCalculator.step2Car.requestedColor", "اللون المطلوب")}
      </span>
      <div className="flex flex-wrap gap-3.5 justify-start">
        {availableColors.map((color) => {
          const isSelected = selectedColor === color.name;
          return (
            <button
              key={color.name}
              type="button"
              onClick={() => setSelectedColor(color.name)}
              title={color.name}
              className={`h-8 w-8 rounded-full border p-[2px] transition-transform duration-200 hover:scale-115 flex items-center justify-center ${
                isSelected ? "border-[#0f172a] scale-110" : "border-gray-300"
              }`}
            >
              <span
                className="block h-full w-full rounded-full border border-black/10 shadow-xs"
                style={{ backgroundColor: color.hex }}
              />
            </button>
          );
        })}
      </div>
    </div>
  );
}
