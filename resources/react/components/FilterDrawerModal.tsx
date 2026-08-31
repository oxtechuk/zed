import { useState, useEffect } from "react";
import { useTranslation } from "react-i18next";
import { Search, X } from "lucide-react";
import type { IFilterDrawerModalProps } from "../interfaces/IFilterDrawerModalProps";
import type { FilterValues } from "../types/cars.types";
import { DEFAULT_FILTER_VALUES } from "../types/cars.types";
import { formatPrice } from "../utils/format";

interface FilterContentProps {
  local: FilterValues;
  brands: IFilterDrawerModalProps["brands"];
  fuelOptions: IFilterDrawerModalProps["fuelOptions"];
  maxPriceLimit: number;
  isRTL: boolean;
  toggleBrand: (id: number) => void;
  updateField: <K extends keyof FilterValues>(key: K, value: FilterValues[K]) => void;
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  t: any;
}

function FilterContent({ local, brands, fuelOptions, maxPriceLimit, isRTL, toggleBrand, updateField, t }: FilterContentProps) {
  return (
    <>
      {/* Brands */}
      <div className="px-5 py-5">
        <h3 className="mb-3 text-[13px] font-black text-[#16254F]">{t("carFinder.brand", { defaultValue: "العلامة التجارية" })}</h3>
        <div className="flex flex-col gap-2.5">
          {brands.map((brand) => (
            <label key={brand.id} className="flex cursor-pointer items-center gap-2.5 text-[13px] font-bold text-[#374151]">
              <input
                type="checkbox"
                checked={local.brandId === brand.id}
                onChange={() => toggleBrand(brand.id)}
                className="h-4 w-4 rounded border-gray-300 accent-[#16254F]"
              />
              {brand.name}
            </label>
          ))}
        </div>
      </div>

      {/* Fuel Type */}
      <div className="px-5 py-5">
        <h3 className="mb-3 text-[13px] font-black text-[#16254F]">{t("carFinder.type", { defaultValue: "نوع الوقود" })}</h3>
        <div className="flex flex-col gap-3">
          <label className="flex cursor-pointer items-center gap-2.5 text-[13px] font-bold text-[#374151]">
            <input type="radio" name="fuelType" value="all" checked={local.fuelType === "all"} onChange={() => updateField("fuelType", "all")} className="h-4 w-4 accent-[#16254F]" />
            {t("allCarsFilterBar.all", { defaultValue: "الكل" })}
          </label>
          {fuelOptions.map((fuelItem, idx) => {
            const fuelValue = typeof fuelItem === "object" && fuelItem !== null ? fuelItem.value : String(fuelItem ?? "");
            const fuelCount = typeof fuelItem === "object" && fuelItem !== null ? fuelItem.count : undefined;
            if (!fuelValue) return null;
            return (
              <label key={`${fuelValue}-${idx}`} className="flex cursor-pointer items-center justify-between text-[13px] font-bold text-[#374151]">
                <div className="flex items-center gap-2.5">
                  <input
                    type="radio"
                    name="fuelType"
                    value={fuelValue}
                    checked={local.fuelType === fuelValue}
                    onChange={() => updateField("fuelType", fuelValue)}
                    className="h-4 w-4 accent-[#16254F]"
                  />
                  <span>{fuelValue}</span>
                </div>
                {fuelCount !== undefined && (
                  <span className="text-[11px] font-bold text-gray-400">({fuelCount})</span>
                )}
              </label>
            );
          })}
        </div>
      </div>

      {/* Max Price */}
      <div className="px-5 py-5">
        <h3 className="mb-3 text-[13px] font-black text-[#16254F]">{t("carsSidebarFilter.maxPrice", { defaultValue: "السعر الأقصى" })}</h3>
        <p className="mb-3 text-[18px] font-black text-[#EDC98E]">
          {local.priceMax === Infinity
            ? t("allCarsFilterBar.all", { defaultValue: "الكل" })
            : formatPrice(local.priceMax, "#EDC98E")}
        </p>
        <input
          type="range"
          min={100000}
          max={maxPriceLimit}
          step={50000}
          value={local.priceMax >= maxPriceLimit ? maxPriceLimit : local.priceMax}
          onChange={(e) => updateField("priceMax", Number(e.target.value))}
          onMouseUp={(e) => {
            const val = Number((e.target as HTMLInputElement).value);
            updateField("priceMax", val >= maxPriceLimit ? Infinity : val);
          }}
          onTouchEnd={(e) => {
            const val = Number((e.target as HTMLInputElement).value);
            updateField("priceMax", val >= maxPriceLimit ? Infinity : val);
          }}
          className="w-full accent-[#EDC98E]"
        />
        <div className="mt-1 flex justify-between text-[11px] text-gray-400 font-bold">
          <span>{maxPriceLimit.toLocaleString(isRTL ? "ar-SA" : "en-US")}</span>
          <span>100,000</span>
        </div>
      </div>
    </>
  );
}

export default function FilterDrawerModal({
  isOpen,
  onClose,
  filters,
  onApply,
  brands,
  fuelOptions,
  maxPriceLimit = 2000000,
}: IFilterDrawerModalProps) {
  const { t, i18n } = useTranslation();
  const isRTL = i18n.dir() === "rtl";
  const [local, setLocal] = useState<FilterValues>(filters);

  useEffect(() => {
    if (isOpen) { setLocal(filters); }
  }, [isOpen, filters]);

  useEffect(() => {
    document.body.style.overflow = isOpen ? "hidden" : "";
    return () => { document.body.style.overflow = ""; };
  }, [isOpen]);

  const toggleBrand = (id: number) => {
    const updated = { ...local, brandId: local.brandId === id ? null : id };
    setLocal(updated);
  };

  const updateField = <K extends keyof FilterValues>(key: K, value: FilterValues[K]) => {
    const updated = { ...local, [key]: value };
    setLocal(updated);
  };

  const handleApply = () => {
    onApply(local);
    onClose();
  };

  const handleReset = () => {
    const updated = { ...DEFAULT_FILTER_VALUES, search: filters.search, priceMax: Infinity };
    setLocal(updated);
    onApply(updated);
  };

  const contentProps = { local, brands, fuelOptions, maxPriceLimit, isRTL, toggleBrand, updateField, t };

  return (
    <>
      {/* Backdrop */}
      <div
        className={`fixed inset-0 z-[90] bg-black/50 backdrop-blur-xs transition-opacity duration-300 ${isOpen ? "opacity-100" : "opacity-0 pointer-events-none"}`}
        onClick={onClose}
      />

      {/* Mobile: side drawer */}
      <div
        dir={i18n.dir()}
        className={`lg:hidden fixed top-0 z-[100] h-full w-full max-w-sm bg-white shadow-2xl transition-transform duration-300 ease-in-out flex flex-col ${isRTL ? "right-0" : "left-0"} ${isOpen ? "translate-x-0" : isRTL ? "translate-x-full" : "-translate-x-full"}`}
      >
        <div className="flex items-center justify-between border-b border-gray-100 px-5 py-4 shrink-0">
          <h2 className="text-[15px] font-black text-[#16254F]">{t("carFinder.resetButton", { defaultValue: "الفلاتر" })}</h2>
          <button type="button" onClick={onClose} className="flex h-8 w-8 items-center justify-center rounded-full text-gray-400 transition hover:bg-gray-100 cursor-pointer">
            <X size={18} />
          </button>
        </div>

        <div className="flex-1 overflow-y-auto divide-y divide-gray-100 pb-2">
          <FilterContent {...contentProps} />
        </div>

        {/* Mobile Actions Footer - Elevated with extra bottom padding */}
        <div className="border-t border-gray-100 bg-white p-4 pb-10 sm:pb-5 shrink-0 flex items-center gap-2.5 shadow-[0_-4px_16px_rgba(0,0,0,0.06)]">
          <button
            type="button"
            onClick={handleApply}
            className="flex-1 h-[48px] rounded-xl bg-[#16254F] text-white text-[14px] font-bold flex items-center justify-center gap-2 shadow-[0_4px_12px_rgba(22,37,79,0.2)] hover:bg-[#0F1E36] active:scale-98 transition-all cursor-pointer"
          >
            <Search size={16} />
            <span>{t("carFinder.searchButton", { defaultValue: "بحث" })}</span>
          </button>
          <button
            type="button"
            onClick={handleReset}
            className="h-[48px] px-4 rounded-xl border border-gray-200 bg-gray-50 text-[#64748B] hover:text-[#16254F] hover:bg-gray-100 text-[13px] font-bold active:scale-98 transition-all cursor-pointer shrink-0"
          >
            {t("carsSidebarFilter.resetFilters", { defaultValue: "إعادة ضبط" })}
          </button>
        </div>
      </div>

      {/* Desktop: centered popup */}
      {isOpen && (
        <div
          dir={i18n.dir()}
          className="hidden lg:flex fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-[100] w-[calc(100%-4rem)] max-w-7xl max-h-[65vh] flex-col rounded-2xl bg-white shadow-2xl overflow-hidden"
        >
          <div className="flex items-center justify-between border-b border-gray-100 px-6 py-4 shrink-0">
            <h2 className="text-[15px] font-black text-[#16254F]">{t("carFinder.resetButton", { defaultValue: "الفلاتر" })}</h2>
            <button type="button" onClick={onClose} className="flex h-8 w-8 items-center justify-center rounded-full text-gray-400 transition hover:bg-gray-100 cursor-pointer">
              <X size={18} />
            </button>
          </div>
          <div className="flex-1 overflow-y-auto grid grid-cols-3 divide-x divide-x-reverse divide-gray-100">
            <FilterContent {...contentProps} />
          </div>
          <div className="border-t border-gray-100 bg-white px-6 py-4 shrink-0 flex items-center justify-between">
            <button
              type="button"
              onClick={handleReset}
              className="text-[13px] font-bold text-[#64748B] hover:text-[#16254F] underline underline-offset-2 transition-colors cursor-pointer"
            >
              {t("carsSidebarFilter.resetFilters", { defaultValue: "إعادة ضبط الفلاتر" })}
            </button>
            <button
              type="button"
              onClick={handleApply}
              className="h-[44px] px-8 rounded-xl bg-[#16254F] text-white text-[14px] font-bold flex items-center justify-center gap-2 shadow-[0_4px_12px_rgba(22,37,79,0.2)] hover:bg-[#0F1E36] active:scale-98 transition-all cursor-pointer"
            >
              <Search size={16} />
              <span>{t("carFinder.searchButton", { defaultValue: "بحث" })}</span>
            </button>
          </div>
        </div>
      )}
    </>
  );
}
