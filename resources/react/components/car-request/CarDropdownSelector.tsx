import { useState, useMemo } from "react";
import { useTranslation } from "react-i18next";
import { ChevronDown, Check, Plus } from "lucide-react";
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
    customCarName = "",
    onCustomCarNameChange,
}: ICarDropdownSelectorProps) {
    const { t } = useTranslation();
    const [searchTerm, setSearchTerm] = useState("");

    const inputClasses =
        "h-[50px] w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-[14px] font-medium text-[#0F172A] outline-none transition placeholder:text-gray-400 focus:border-[#0F172A] focus:bg-white focus:ring-2 focus:ring-[#0F172A]/10";

    // Filter cars based on search term
    const filteredCars = useMemo(() => {
        if (!searchTerm.trim()) return cars;
        const term = searchTerm.toLowerCase().trim();
        return cars.filter((car) => {
            const brandName = car.brand?.name || "";
            const carName = car.name || "";
            const modelName = car.model || "";
            return (
                brandName.toLowerCase().includes(term) ||
                carName.toLowerCase().includes(term) ||
                modelName.toLowerCase().includes(term)
            );
        });
    }, [cars, searchTerm]);

    // Show custom option ONLY when searchTerm is not empty and no cars are matched, OR if it's already selected
    const showCustomOption = useMemo(() => {
        return (searchTerm.trim() !== "" && filteredCars.length === 0) || selectedCarId === 9999;
    }, [searchTerm, filteredCars.length, selectedCarId]);

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
                            {activeCar.id === 9999 ? (
                                <div className="h-8 w-11 rounded-md bg-[#0F172A]/5 border border-[#0F172A]/10 shrink-0 flex items-center justify-center text-[#0F172A]">
                                    <Plus size={14} className="stroke-[3]" />
                                </div>
                            ) : (
                                <img
                                    src={
                                        getImageUrl(activeCar.main_image) ||
                                        APP_IMAGES.CAR_PLACEHOLDER
                                    }
                                    alt={activeCar.name}
                                    className="h-8 w-11 object-contain rounded-md bg-white border border-gray-200 p-0.5 shrink-0"
                                />
                            )}
                            <span className="truncate font-extrabold text-[#0F172A] text-[14px]">
                                {activeCar.id === 9999
                                    ? t("carRequest.summary.customCarSelected", "طلب سيارة مخصصة")
                                    : `${activeCar.brand?.name} ${activeCar.name} (${activeCar.year})`
                                }
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
                    <div className="absolute top-full start-0 end-0 z-30 mt-2 max-h-80 overflow-y-auto rounded-2xl border border-[#E2E8F0] bg-white p-2 shadow-xl animate-in fade-in zoom-in-95 duration-150 flex flex-col gap-1">
                        {/* Search Box */}
                        <div className="sticky top-0 bg-white pb-2 pt-1 px-1 border-b border-gray-100 z-10">
                            <input
                                type="text"
                                placeholder={t("carRequest.summary.searchCarPlaceholder", "ابحث باسم السيارة أو الموديل...")}
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                onClick={(e) => e.stopPropagation()} // Prevent dropdown closing on click
                                className="w-full h-[38px] px-3 rounded-xl border border-gray-200 text-[13px] font-bold outline-none transition focus:border-[#0F172A]"
                            />
                        </div>

                        {/* Cars list */}
                        <div className="flex-1 overflow-y-auto max-h-48 flex flex-col gap-1">
                            {filteredCars.length > 0 ? (
                                filteredCars.map((car) => {
                                    const isSelected = car.id === selectedCarId;
                                    return (
                                        <button
                                            key={car.id}
                                            type="button"
                                            onClick={() => {
                                                onSelectCarId(car.id);
                                                setIsCarDropdownOpen(false);
                                                setSearchTerm("");
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
                                })
                            ) : (
                                !showCustomOption && (
                                    <div className="text-center py-6 text-gray-400 font-bold text-[13px]">
                                        {t("carRequest.summary.noCarsFound", "لا توجد نتائج مطابقة")}
                                    </div>
                                )
                            )}
                        </div>

                        {/* Special Custom Option at the Bottom */}
                        {showCustomOption && (
                            <div className="sticky bottom-0 bg-white pt-2 mt-1 border-t border-gray-100 z-10">
                                <button
                                    type="button"
                                    onClick={() => {
                                        onSelectCarId(9999);
                                        setIsCarDropdownOpen(false);
                                        setSearchTerm("");
                                    }}
                                    className={`flex w-full items-center gap-3 rounded-xl p-2.5 text-start transition-all border ${
                                        selectedCarId === 9999
                                            ? "bg-[#0F172A] text-white border-transparent shadow-sm"
                                            : "bg-[#0F172A]/5 hover:bg-[#0F172A]/10 text-[#0F172A] border-[#0F172A]/10"
                                    }`}
                                >
                                    <div className={`h-10 w-14 rounded-lg shrink-0 flex items-center justify-center border transition-all ${
                                        selectedCarId === 9999
                                            ? "bg-white/10 border-white/20 text-[#EDC98E]"
                                            : "bg-white border-[#0F172A]/10 text-[#0F172A]"
                                    }`}>
                                        <Plus size={18} className="stroke-[3]" />
                                    </div>
                                    <div className="flex flex-col flex-1 overflow-hidden">
                                        <span className={`text-[13px] font-extrabold ${selectedCarId === 9999 ? "text-[#EDC98E]" : "text-[#0F172A]"}`}>
                                            {t("carRequest.summary.carNotFound", "السيارة غير موجودة؟ قدّم طلبك")}
                                        </span>
                                        <span className={`text-[11px] font-bold ${selectedCarId === 9999 ? "text-white/60" : "text-gray-500"}`}>
                                            {t("carRequest.summary.carNotFoundDesc", "أدخل مواصفات سيارتك وسنقوم بتوفيرها")}
                                        </span>
                                    </div>
                                    {selectedCarId === 9999 && (
                                        <Check
                                            size={18}
                                            className="shrink-0 text-[#EDC98E]"
                                        />
                                    )}
                                </button>
                            </div>
                        )}
                    </div>
                )}
            </div>

            {/* Custom/Unlisted Car Name Input */}
            {selectedCarId === 9999 && (
                <div className="mt-3 animate-in fade-in slide-in-from-top-2 duration-200">
                    <label className="text-[12px] font-extrabold text-[#0F172A] bg-[#0F172A]/5 border border-[#0F172A]/10 px-2.5 py-1 rounded-lg inline-block mb-1.5">
                        {t("carRequest.customCar.label", "اكتب اسم ومواصفات السيارة المطلوبة")}
                    </label>
                    <input
                        type="text"
                        required
                        value={customCarName}
                        onChange={(e) => onCustomCarNameChange?.(e.target.value)}
                        placeholder={t("carRequest.customCar.placeholder", "مثال: إيسوزو دي ماكس 2025")}
                        className="h-[46px] w-full rounded-xl border border-[#E2E8F0] bg-white px-4 text-[14px] font-bold text-[#0F172A] outline-none transition placeholder:text-gray-400 focus:border-[#0F172A] focus:ring-2 focus:ring-[#0F172A]/10"
                    />
                </div>
            )}
        </div>
    );
}
