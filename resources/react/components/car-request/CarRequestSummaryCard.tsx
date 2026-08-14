import { useState, useRef, useEffect } from "react";
import { useTranslation } from "react-i18next";
import { Plus } from "lucide-react";
import { getImageUrl, APP_IMAGES } from "../../constants/app-images";
import { formatPrice } from "../../utils/format";
import { CarDropdownSelector } from "./CarDropdownSelector";
import { CarColorPicker } from "./CarColorPicker";
import { CarTermSelector } from "./CarTermSelector";
import type { ICarRequestSummaryProps } from "../../interfaces/ICarRequestSummaryProps";

export function CarRequestSummaryCard({
    cars,
    activeCar,
    loadingCars,
    selectedCarId,
    onSelectCarId,
    selectedColor,
    onSelectColor,
    term,
    onChangeTerm,
    calculatedInstallment,
    carColors,
    customCarName,
    onCustomCarNameChange,
}: ICarRequestSummaryProps) {
    const { t } = useTranslation();
    const [isCarDropdownOpen, setIsCarDropdownOpen] = useState(false);
    const carDropdownRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            if (
                carDropdownRef.current &&
                !carDropdownRef.current.contains(event.target as Node)
            ) {
                setIsCarDropdownOpen(false);
            }
        };
        document.addEventListener("mousedown", handleClickOutside);
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, []);

    const fuelDefault = t("carRequest.summary.fuelDefault", "بنزين");
    const transmissionDefault = t("carRequest.summary.transmissionDefault", "أوتوماتيك");

    const fuelType = activeCar
        ? activeCar.fuel_type ||
          (!Array.isArray(activeCar.specs) && activeCar.specs
              ? (activeCar.specs as any).fuel
              : "") ||
          fuelDefault
        : "";

    const transmissionType = activeCar
        ? activeCar.transmission ||
          (!Array.isArray(activeCar.specs) && activeCar.specs
              ? (activeCar.specs as any).gearbox
              : "") ||
          transmissionDefault
        : "";

    return (
        <div className="lg:col-span-4 flex flex-col gap-6 lg:sticky lg:top-8">
            <div className="bg-white border border-[#E5E9F0] rounded-[24px] p-6 shadow-sm flex flex-col gap-6">
                {/* Car Selector Dropdown */}
                <CarDropdownSelector
                    cars={cars}
                    activeCar={activeCar}
                    loadingCars={loadingCars}
                    selectedCarId={selectedCarId}
                    onSelectCarId={onSelectCarId}
                    isCarDropdownOpen={isCarDropdownOpen}
                    setIsCarDropdownOpen={setIsCarDropdownOpen}
                    carDropdownRef={carDropdownRef}
                    customCarName={customCarName}
                    onCustomCarNameChange={onCustomCarNameChange}
                />

                {/* Active Car Details Card */}
                {loadingCars ? (
                    <div className="border border-gray-100 rounded-2xl p-4 bg-gray-50 flex flex-col items-center animate-pulse">
                        <div className="h-40 w-full rounded-xl bg-gray-200 mb-4" />
                        <div className="h-5 w-36 rounded bg-gray-200 mb-2" />
                        <div className="h-3 w-24 rounded bg-gray-200 mb-4" />
                        <div className="w-full border-t border-gray-200/60 pt-4 flex flex-col gap-2">
                            <div className="h-3 w-20 rounded bg-gray-200 mb-1" />
                            <div className="flex gap-2.5">
                                {Array.from({ length: 5 }).map((_, i) => (
                                    <div key={i} className="w-9 h-9 rounded-full bg-gray-200" />
                                ))}
                            </div>
                        </div>
                    </div>
                ) : activeCar ? (
                    <div className="border border-gray-100 rounded-2xl p-4 bg-gray-50 flex flex-col items-center animate-in fade-in duration-200">
                        <div className="h-40 w-full overflow-hidden rounded-xl bg-white mb-4 border border-gray-100 flex items-center justify-center">
                            {activeCar.id === 9999 ? (
                                <div className="text-gray-300">
                                    <Plus size={48} className="stroke-[1.5]" />
                                </div>
                            ) : (
                                <img
                                    src={
                                        getImageUrl(activeCar.main_image) ||
                                        APP_IMAGES.CAR_PLACEHOLDER
                                    }
                                    alt={activeCar.name}
                                    className="h-full max-w-full object-contain"
                                />
                            )}
                        </div>

                        <h3 className="text-[18px] font-black text-[#0F172A] text-center mb-1">
                            {activeCar.id === 9999
                                ? t("carRequest.summary.customCarSelected", "طلب سيارة مخصصة")
                                : `${activeCar.brand?.name} ${activeCar.name}`
                            }
                        </h3>
                        <p className="text-[12px] text-gray-400 font-bold text-center mb-4">
                            {activeCar.id === 9999
                                ? t("carRequest.summary.customCarDesc", "سيارة غير مدرجة بالمعرض")
                                : `${activeCar.year} . ${fuelType} . ${transmissionType}`
                            }
                        </p>

                        {/* Colors Selector */}
                        <CarColorPicker
                            carColors={carColors}
                            selectedColor={selectedColor}
                            onSelectColor={onSelectColor}
                        />
                    </div>
                ) : null}

                {/* Finance Term Selector */}
                <CarTermSelector term={term} onChangeTerm={onChangeTerm} />

                {/* Price and Installment box */}
                {activeCar && (
                    <div className="rounded-2xl bg-[#0F172A] p-5 text-white relative overflow-hidden text-start">
                        <div className="absolute top-0 end-0 w-24 h-24 bg-[#EDC98E]/5 blur-2xl rounded-full" />

                        <div className="flex justify-between items-center border-b border-white/5 pb-3 mb-3">
                            <div>
                                <span className="text-[11px] text-white/50 font-bold block">
                                    {t("carRequest.summary.carPrice", "سعر السيارة")}
                                </span>
                                <strong className="text-[18px] font-black text-white">
                                    {activeCar.id === 9999
                                        ? t("carRequest.summary.toBeDetermined", "يحدد لاحقاً")
                                        : formatPrice(activeCar.current_price, "white")
                                    }
                                </strong>
                            </div>
                            <div className="text-end">
                                <span className="text-[11px] text-[#EDC98E] font-bold block">
                                    {t("carRequest.summary.estimatedInstallment", "القسط التقديري")}
                                </span>
                                <strong className="text-[20px] font-black text-[#EDC98E]">
                                    {activeCar.id === 9999
                                        ? t("carRequest.summary.toBeDetermined", "يحدد لاحقاً")
                                        : formatPrice(calculatedInstallment, "#EDC98E")
                                    }
                                </strong>
                            </div>
                        </div>

                        <p className="text-[10px] text-white/40 font-semibold leading-relaxed">
                            {activeCar.id === 9999
                                ? t(
                                    "carRequest.summary.customCarDisclaimer",
                                    "* سيتم حساب السعر النهائي والقسط بعد مراجعة طلبك والتواصل معك لتأكيد المواصفات المطلوبة.",
                                  )
                                : t(
                                    "carRequest.summary.disclaimer",
                                    "* الأرقام تقديرية بناءً على نسبة فائدة تمويلية 4.5%. التمويل النهائي يخضع للتقييم الائتماني من الجهة الممولة.",
                                  )
                            }
                        </p>
                    </div>
                )}
            </div>
        </div>
    );
}
