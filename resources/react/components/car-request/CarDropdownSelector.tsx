import { useTranslation } from "react-i18next";
import { ChevronDown, Check } from "lucide-react";
import { getImageUrl, APP_IMAGES } from "../../constants/app-images";
import type { ICarDropdownSelectorProps } from "../../interfaces/ICarDropdownSelectorProps";

export function CarDropdownSelector({
    cars,
    activeCar,
    loadingCars,
    selectedCarId,
    onSelectCarId,
    isCarDropdownOpen,
    setIsCarDropdownOpen,
    carDropdownRef,
}: ICarDropdownSelectorProps) {
    const { t } = useTranslation();
    const inputClasses =
        "h-[50px] w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-[14px] font-medium text-[#0F172A] outline-none transition placeholder:text-gray-400 focus:border-[#0F172A] focus:bg-white focus:ring-2 focus:ring-[#0F172A]/10";

    return (
        <div className="flex flex-col text-start" ref={carDropdownRef}>
            <label className="text-[14px] font-extrabold text-[#374151] mb-2">
                {t("carRequest.summary.selectCar", "اختر السيارة")}
            </label>
            <div className="relative">
                <button
                    type="button"
                    disabled={loadingCars}
                    onClick={() => setIsCarDropdownOpen((prev) => !prev)}
                    className={`${inputClasses} flex items-center justify-between gap-3 text-start cursor-pointer border transition-all ${
                        isCarDropdownOpen
                            ? "border-[#0F172A] bg-white ring-2 ring-[#0F172A]/10"
                            : "border-[#E2E8F0] bg-[#F8FAFC]"
                    }`}
                >
                    {loadingCars ? (
                        <span className="text-gray-400">
                            {t("carRequest.summary.loadingCars", "جاري تحميل السيارات...")}
                        </span>
                    ) : activeCar ? (
                        <div className="flex items-center gap-3 overflow-hidden">
                            <img
                                src={
                                    getImageUrl(activeCar.main_image) ||
                                    APP_IMAGES.CAR_PLACEHOLDER
                                }
                                alt={activeCar.name}
                                className="h-8 w-11 object-contain rounded-md bg-white border border-gray-200 p-0.5 shrink-0"
                            />
                            <span className="truncate font-extrabold text-[#0F172A] text-[14px]">
                                {activeCar.brand?.name} {activeCar.name} ({activeCar.year})
                            </span>
                        </div>
                    ) : (
                        <span className="text-gray-400">
                            {t("carRequest.summary.selectCarPlaceholder", "اختر سيارة...")}
                        </span>
                    )}
                    <ChevronDown
                        size={18}
                        className={`shrink-0 text-[#8A8F99] transition-transform duration-200 ${
                            isCarDropdownOpen ? "rotate-180 text-[#0F172A]" : ""
                        }`}
                    />
                </button>

                {isCarDropdownOpen && !loadingCars && (
                    <div className="absolute top-full start-0 end-0 z-30 mt-2 max-h-64 overflow-y-auto rounded-2xl border border-[#E2E8F0] bg-white p-2 shadow-xl animate-in fade-in zoom-in-95 duration-150">
                        {cars.map((car) => {
                            const isSelected = car.id === selectedCarId;
                            return (
                                <button
                                    key={car.id}
                                    type="button"
                                    onClick={() => {
                                        onSelectCarId(car.id);
                                        setIsCarDropdownOpen(false);
                                    }}
                                    className={`flex w-full items-center gap-3 rounded-xl p-2.5 text-start transition-colors ${
                                        isSelected
                                            ? "bg-[#0F172A] text-white"
                                            : "hover:bg-[#F1F5F9] text-[#0F172A]"
                                    }`}
                                >
                                    <img
                                        src={
                                            getImageUrl(car.main_image) ||
                                            APP_IMAGES.CAR_PLACEHOLDER
                                        }
                                        alt={car.name}
                                        className={`h-10 w-14 object-contain rounded-lg p-1 shrink-0 ${
                                            isSelected
                                                ? "bg-white/10 border border-white/20"
                                                : "bg-gray-50 border border-gray-200"
                                        }`}
                                    />
                                    <div className="flex flex-col flex-1 overflow-hidden">
                                        <span
                                            className={`text-[14px] font-extrabold truncate ${
                                                isSelected
                                                    ? "text-white"
                                                    : "text-[#0F172A]"
                                            }`}
                                        >
                                            {car.brand?.name} {car.name}
                                        </span>
                                        <span
                                            className={`text-[12px] font-bold ${
                                                isSelected
                                                    ? "text-white/70"
                                                    : "text-gray-400"
                                            }`}
                                        >
                                            {t("carRequest.summary.modelYear", "موديل {{year}}", { year: car.year })}
                                        </span>
                                    </div>
                                    {isSelected && (
                                        <Check
                                            size={18}
                                            className="shrink-0 text-[#EDC98E]"
                                        />
                                    )}
                                </button>
                            );
                        })}
                    </div>
                )}
            </div>
        </div>
    );
}
