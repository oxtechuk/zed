import { useState } from "react";
import { useTranslation } from "react-i18next";
import {
  CalendarDays,
  Car,
  Gauge,
  RotateCcw,
  Search,
  Tag,
} from "lucide-react";
import Select from "./Select";
import type { ICarFinderProps } from "../interfaces/ICarFinderProps";

function getLocalizedName(
  name: string | Record<string, string> | undefined,
  lang: string,
): string {
  if (!name) return "";
  if (typeof name === "string") return name;
  return name[lang] ?? name["en"] ?? "";
}

export default function CarFinder({
  onSearch,
  onReset,
  brands = [],
  types = [],
  categories = [],
  years = [],
  filterTitle,
}: ICarFinderProps) {
  const { t, i18n } = useTranslation();
  const [brandId, setBrandId] = useState("");
  const [typeId, setTypeId] = useState("");
  const [categoryId, setCategoryId] = useState("");
  const [yearVal, setYearVal] = useState("");
  const [searchText, setSearchText] = useState("");

  const handleSearch = () => {
    onSearch?.({
      brandId,
      typeId,
      categoryId,
      year: yearVal,
      search: searchText,
    });
  };

  const handleReset = () => {
    setBrandId("");
    setTypeId("");
    setCategoryId("");
    setYearVal("");
    setSearchText("");
    onReset?.();
  };

  return (
    <section
      dir={i18n.dir()}
      className="w-full bg-[#FAFBFC] py-10"
    >
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 className="mb-6 text-center text-[26px] font-extrabold text-[#0F172A]">
          {filterTitle || t("carFinder.title", { defaultValue: "ابحث عن سيارتك المثالية" })}
        </h2>

        {/* Search Bar Container card */}
        <div className="rounded-[24px] border border-[#E5E9F0] bg-white p-6 shadow-sm">
          {/* Grid of selects */}
          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {/* Brand */}
            <div>
              <label className="mb-2 block text-right text-[12px] font-bold text-[#64748B]">
                {t("carFinder.brand")}
              </label>
              <Select
                searchable
                placeholder={t("carFinder.brandPlaceholder")}
                value={brandId}
                onChange={setBrandId}
                options={brands.map((b) => ({
                  label: getLocalizedName(b.name, i18n.language),
                  value: String(b.id),
                }))}
                icon={<Tag size={16} className="text-[#FF4A4A]" />}
                className="h-[48px] rounded-[12px] border border-[#E5E7EB] bg-[#FAFBFC] text-sm text-[#111827] focus:ring-1"
              />
            </div>

            {/* Type */}
            <div>
              <label className="mb-2 block text-right text-[12px] font-bold text-[#64748B]">
                {t("carFinder.type")}
              </label>
              <Select
                searchable
                placeholder={t("carFinder.typePlaceholder")}
                value={typeId}
                onChange={setTypeId}
                options={types.map((t) => ({
                  label: getLocalizedName(t.name, i18n.language),
                  value: String(t.id),
                }))}
                icon={<Car size={16} className="text-[#B84DFF]" />}
                className="h-[48px] rounded-[12px] border border-[#E5E7EB] bg-[#FAFBFC] text-sm text-[#111827] focus:ring-1"
              />
            </div>

            {/* Category */}
            <div>
              <label className="mb-2 block text-right text-[12px] font-bold text-[#64748B]">
                {t("carFinder.category")}
              </label>
              <Select
                searchable
                placeholder={t("carFinder.categoryPlaceholder")}
                value={categoryId}
                onChange={setCategoryId}
                options={categories.map((c) => ({
                  label: getLocalizedName(c.name, i18n.language),
                  value: String(c.id),
                }))}
                icon={<CalendarDays size={16} className="text-[#3B82F6]" />}
                className="h-[48px] rounded-[12px] border border-[#E5E7EB] bg-[#FAFBFC] text-sm text-[#111827] focus:ring-1"
              />
            </div>

            {/* Year */}
            <div>
              <label className="mb-2 block text-right text-[12px] font-bold text-[#64748B]">
                {t("carFinder.year")}
              </label>
              <Select
                searchable
                placeholder={t("carFinder.yearPlaceholder")}
                value={yearVal}
                onChange={setYearVal}
                options={years.map((y) => {
                  const val = typeof y === "string" ? y : y?.year ?? String(y);
                  return { label: val, value: val };
                })}
                icon={<Gauge size={16} className="text-[#FF3FB4]" />}
                className="h-[48px] rounded-[12px] border border-[#E5E7EB] bg-[#FAFBFC] text-sm text-[#111827] focus:ring-1"
              />
            </div>
          </div>

          {/* Text search & Action Buttons */}
          <div className="mt-5 flex flex-col gap-4 lg:flex-row">
            {/* Search input */}
            <div className="relative flex-1">
              <input
                type="text"
                value={searchText}
                onChange={(e) => setSearchText(e.target.value)}
                placeholder={t("carFinder.searchPlaceholder", { defaultValue: "ابحث عن ماركة سيارتك، الموديل أو مواصفات..." })}
                onKeyDown={(e) => e.key === "Enter" && handleSearch()}
                className="h-[48px] w-full rounded-[12px] border border-[#E5E7EB] bg-[#FAFBFC] px-5 pr-11 text-sm text-[#111827] outline-none placeholder:text-[#9CA3AF] focus:border-[var(--brand-primary-color)] focus:ring-2 focus:ring-[rgba(41,155,224,0.15)]"
              />
              <Search
                size={18}
                className="absolute right-4 top-1/2 -translate-y-1/2 text-[#9CA3AF]"
              />
            </div>

            {/* Action Buttons */}
            <div className="flex items-center gap-2">
              {/* Reset button (kuhli/navy) */}
              <button
                type="button"
                onClick={handleReset}
                className="flex h-[48px] items-center justify-center gap-2 rounded-[12px] bg-[#0F1E36] px-6 text-sm font-bold text-white transition hover:bg-[#1C2E4D]"
              >
                <RotateCcw size={15} />
                <span>{t("carFinder.resetButton", { defaultValue: "إعادة ضبط" })}</span>
              </button>

              {/* Search button (golden) */}
              <button
                type="button"
                onClick={handleSearch}
                className="h-[48px] rounded-[12px] bg-[#E5C287] px-8 text-sm font-bold text-[#0A1628] transition hover:bg-[#D9B477]"
              >
                {t("carFinder.searchButton", { defaultValue: "بحث" })}
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
