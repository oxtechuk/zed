import { useTranslation } from "react-i18next";
import type { ICarColorPickerProps } from "../../interfaces/ICarColorPickerProps";

export function CarColorPicker({
    carColors,
    selectedColor,
    onSelectColor,
}: ICarColorPickerProps) {
    const { t } = useTranslation();

    return (
        <div className="w-full text-start border-t border-gray-200/60 pt-4">
            <span className="text-[13px] font-extrabold text-[#374151] block mb-3">
                {t("carRequest.summary.preferredColor", "اللون المطلوب")}
            </span>
            <div className="flex flex-wrap gap-2.5">
                {carColors.map((color, idx) => (
                    <button
                        key={idx}
                        type="button"
                        onClick={() => onSelectColor(color.name)}
                        className={`w-9 h-9 rounded-full border-2 transition-all relative ${
                            selectedColor === color.name
                                ? "border-[#EDC98E] scale-110 shadow-sm"
                                : "border-gray-200 hover:border-gray-300"
                        }`}
                        style={{
                            backgroundColor: color.hex,
                        }}
                        title={color.name}
                    >
                        {selectedColor === color.name && (
                            <span
                                className="absolute inset-0 flex items-center justify-center text-[10px]"
                                style={{
                                    color:
                                        color.hex === "#FFFFFF"
                                            ? "#0F172A"
                                            : "#FFFFFF",
                                }}
                            >
                                ✓
                            </span>
                        )}
                    </button>
                ))}
            </div>
        </div>
    );
}
