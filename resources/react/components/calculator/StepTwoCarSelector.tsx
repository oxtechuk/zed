import { useState, useMemo } from "react";
import { useTranslation } from "react-i18next";
import { Search, ChevronDown, X } from "lucide-react";
import { toast } from "react-toastify";
import { useCarSearch } from "../../hooks/useCarSearch";
import { getImageUrl, APP_IMAGES } from "../../constants/app-images";
import { formatPrice } from "../../utils/format";
import type { CarItem } from "../../types/home.types";
import type { IStepTwoCarSelectorProps } from "../../interfaces/IStepTwoCarSelectorProps";
import LazyImg from "../LazyImg";
import ColorPicker from "./ColorPicker";
import CarPriceBanner from "./CarPriceBanner";

export default function StepTwoCarSelector({
  selectedCarId,
  selectedCar,
  onCarSelect,
  colors,
  selectedColor,
  setSelectedColor,
  onNext,
  onBack,
}: IStepTwoCarSelectorProps) {
  const { t, i18n } = useTranslation();
  const [showSearch, setShowSearch] = useState(false);
  const { searchQuery, setSearchQuery, searchResults, setSearchResults, searching } =
    useCarSearch(showSearch);

  const fallbackColorOptions = useMemo(
    () => [
      { name: t("financeCalculator.colors.white", "أبيض"), hex: "#FFFFFF" },
      { name: t("financeCalculator.colors.black", "أسود"), hex: "#111827" },
      { name: t("financeCalculator.colors.silver", "فضي"), hex: "#D1D5DB" },
      { name: t("financeCalculator.colors.gray", "رمادي"), hex: "#6B7280" },
      { name: t("financeCalculator.colors.navy", "كحلي"), hex: "#1E293B" },
      { name: t("financeCalculator.colors.red", "أحمر"), hex: "#EF4444" },
      { name: t("financeCalculator.colors.blue", "أزرق"), hex: "#3B82F6" },
      { name: t("financeCalculator.colors.gold", "ذهبي"), hex: "#EDC98E" },
      { name: t("financeCalculator.colors.darkGreen", "أخضر داكن"), hex: "#064E3B" },
    ],
    [t]
  );

  const availableColors = colors.length > 0 ? colors : fallbackColorOptions;

  const handleNext = () => {
    if (!selectedCarId) {
      toast.error(t("financeCalculator.validation.selectCar", "الرجاء اختيار سيارة للمتابعة"));
      return;
    }
    onNext();
  };

  const inputClasses =
    "h-[50px] w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-[14px] font-medium text-[#0F172A] outline-none transition placeholder:text-gray-400 focus:border-[#0F172A] focus:bg-white focus:ring-2 focus:ring-[#0F172A]/10 text-start";

  return (
    <div dir={i18n.dir()} className="w-full max-w-3xl mx-auto">
      <div className="rounded-[24px] border border-[#E5E9F0] bg-white px-6 py-8 shadow-sm md:px-10">
        <div className="text-start mb-6">
          <h2 className="text-[22px] font-black text-[#16254F]">
            {t("financeCalculator.step2Car.title", "اختر السيارة")}
          </h2>
          <p className="text-[13px] text-gray-400 font-bold mt-1">
            {t("financeCalculator.step2Car.subtitle", "حدد السيارة التي تريد تمويلها")}
          </p>
        </div>

        {/* Search Input / Selector Dropdown */}
        <div className="relative mb-6">
          {showSearch ? (
            <div className="relative z-25">
              <input
                type="text"
                placeholder={t("financeCalculator.step2Car.searchPlaceholder", "اكتب اسم السيارة، الماركة، أو الموديل...")}
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className={`${inputClasses} ps-12`}
                autoFocus
              />
              {searching ? (
                <div className="pointer-events-none absolute start-4 top-1/2 -translate-y-1/2">
                  <div className="h-4 w-4 animate-spin rounded-full border-2 border-[#D5DBE3] border-t-[#0F172A]" />
                </div>
              ) : (
                <Search
                  size={18}
                  className="pointer-events-none absolute start-4 top-1/2 -translate-y-1/2 text-[#8A8F99]"
                />
              )}
              <button
                type="button"
                onClick={() => {
                  setShowSearch(false);
                  setSearchQuery("");
                  setSearchResults([]);
                }}
                className="absolute end-4 top-1/2 -translate-y-1/2 text-[#8A8F99] hover:text-[#5F6672] cursor-pointer"
              >
                <X size={18} />
              </button>

              {/* Search Results list overlay */}
              {searchResults.length > 0 && (
                <div className="absolute inset-x-0 z-30 mt-2 max-h-[250px] overflow-y-auto rounded-2xl border border-[#E5E9F0] bg-white shadow-xl">
                  {searchResults.map((car) => (
                    <button
                      key={car.id}
                      type="button"
                      onClick={() => {
                        onCarSelect(car);
                        setShowSearch(false);
                        setSearchQuery("");
                        setSearchResults([]);
                      }}
                      className="flex w-full items-center gap-4 px-5 py-4 text-start transition hover:bg-[#F8FAFC] border-b border-[#EEF2F6] last:border-0 cursor-pointer"
                    >
                      <LazyImg
                        src={getImageUrl(car.main_image) || APP_IMAGES.CAR_PLACEHOLDER}
                        alt={car.name}
                        className="h-12 w-16 object-contain rounded-lg bg-gray-50 border p-1"
                      />
                      <div className="flex-1">
                        <p className="text-[12px] text-gray-400 font-bold leading-none mb-1">
                          {car.brand?.name || ""}
                        </p>
                        <p className="text-[15px] font-black text-[#0F172A] leading-tight">
                          {car.name} {car.year}
                        </p>
                      </div>
                      <strong className="text-[15px] font-black text-[#EDC98E]">
                        {formatPrice(car.current_price, "#EDC98E")}
                      </strong>
                    </button>
                  ))}
                </div>
              )}
              {!searching && searchResults.length === 0 && (
                <div className="absolute inset-x-0 z-30 mt-2 p-5 rounded-2xl border border-[#E5E9F0] bg-white text-center text-[13px] text-gray-400 font-bold shadow-lg">
                  {t("financeCalculator.step2Car.noResults", "لا توجد سيارات مطابقة للبحث")}
                </div>
              )}
            </div>
          ) : selectedCarId > 0 ? (
            <div
              onClick={() => setShowSearch(true)}
              className="flex items-center justify-between rounded-2xl border border-[#E5E9F0] bg-white p-4 cursor-pointer hover:border-gray-400 transition"
            >
              <div className="flex items-center gap-4">
                <LazyImg
                  src={selectedCar.image || APP_IMAGES.CAR_PLACEHOLDER}
                  alt={selectedCar.name}
                  className="h-10 w-14 object-contain rounded-lg"
                />
                <div className="text-start">
                  <p className="text-[15px] font-black text-[#0F172A] leading-tight">
                    {selectedCar.name}
                  </p>
                  <p className="text-[12px] text-gray-400 font-bold leading-none mt-1">
                    {selectedCar.model} - {selectedCar.brand}
                  </p>
                </div>
              </div>
              <ChevronDown size={18} className="text-[#8A8F99]" />
            </div>
          ) : (
            <button
              type="button"
              onClick={() => setShowSearch(true)}
              className="flex h-[52px] w-full items-center justify-between rounded-2xl border border-[#E5E9F0] bg-white px-5 text-[14px] font-bold text-gray-400 transition hover:border-gray-400 cursor-pointer"
            >
              <span>{t("financeCalculator.step2Car.selectCarPrompt", "اختر السيارة التي تريد تمويلها...")}</span>
              <ChevronDown size={18} className="text-[#8A8F99]" />
            </button>
          )}
        </div>

        {/* Car Image Preview */}
        {selectedCarId > 0 && (
          <div className="mb-6 rounded-[20px] bg-white border border-[#E5E9F0] shadow-xs flex items-center justify-center min-h-[220px] md:min-h-[300px] overflow-hidden p-0">
            <img
              src={selectedCar.image}
              alt={selectedCar.name}
              className="w-full h-full object-contain"
            />
          </div>
        )}

        {/* Color Circle Options */}
        <ColorPicker
          availableColors={availableColors}
          selectedColor={selectedColor}
          setSelectedColor={setSelectedColor}
        />

        {/* Car Price Banner Card */}
        {selectedCarId > 0 && <CarPriceBanner price={selectedCar.price} />}

        {/* Continue Button */}
        <button
          type="button"
          onClick={handleNext}
          className="flex h-[52px] w-full items-center justify-center gap-2 rounded-xl bg-[#16254F] text-[15px] font-extrabold text-white transition hover:opacity-95 hover:scale-[1.01] active:scale-95 shadow-sm cursor-pointer"
        >
          <span>{t("financeCalculator.step2Car.next", "التالي")}</span>
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
      </div>
    </div>
  );
}
