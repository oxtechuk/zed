import { useState, useEffect } from "react";
import { useTranslation } from "react-i18next";
import { CalendarDays, Car, Gauge, Search, Tag } from "lucide-react";
import Select from "./Select";
import type { ICarFinderProps } from "../interfaces/ICarFinderProps";
import { getApiBaseUrl } from "../constants/axios.constants";

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
    const isRTL = i18n.dir() === "rtl";
    const [brandId, setBrandId] = useState("");
    const [modelId, setModelId] = useState("");
    const [models, setModels] = useState<{ id: number; name: string }[]>([]);
    const [isLoadingModels, setIsLoadingModels] = useState(false);
    const [typeId, setTypeId] = useState("");
    const [categoryId, setCategoryId] = useState("");
    const [yearVal, setYearVal] = useState("");
    const [searchText, setSearchText] = useState("");
    const [filtersOpen, setFiltersOpen] = useState(false);

    useEffect(() => {
        setModelId("");
        if (!brandId) {
            setModels([]);
            return;
        }
        setIsLoadingModels(true);
        const apiBase = getApiBaseUrl().replace(/\/+$/, "");
        fetch(`${apiBase}/store/brands/${brandId}/models`)
            .then((res) => res.json())
            .then((res) => {
                if (res.success && Array.isArray(res.data)) {
                    setModels(res.data);
                } else {
                    setModels([]);
                }
            })
            .catch(() => setModels([]))
            .finally(() => setIsLoadingModels(false));
    }, [brandId]);

    const handleSearch = () => {
        onSearch?.({
            brandId,
            modelId,
            typeId,
            categoryId,
            year: yearVal,
            search: searchText,
        });
    };

    const handleReset = () => {
        setBrandId("");
        setModelId("");
        setTypeId("");
        setCategoryId("");
        setYearVal("");
        setSearchText("");
        onReset?.();
    };

    return (
        <section dir={i18n.dir()} className="w-full bg-[#F8F4FD] py-10">
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 className="mb-8 text-center text-[24px] font-bold text-[#101828] md:text-[30px]">
                    {filterTitle ||
                        t("carFinder.title", {
                            defaultValue: "ابحث عن سيارتك المثالية",
                        })}
                </h2>

                {/* Search Row: Filters + Search buttons + Text input */}
                <div className="flex flex-col gap-4 lg:flex-row lg:items-center">
                    {/* Search input */}
                    <div className="relative flex-1">
                        <input
                            type="text"
                            value={searchText}
                            onChange={(e) => setSearchText(e.target.value)}
                            placeholder={t("carFinder.searchPlaceholder", {
                                defaultValue:
                                    "ابحث عن سيارة بالاسم، العلامة التجارية، الموديل، أو المواصفات...",
                            })}
                            onKeyDown={(e) =>
                                e.key === "Enter" && handleSearch()
                            }
                            className={`h-[52px] w-full rounded-2xl border border-[#DADBDD] bg-white px-5 ${
                                isRTL ? "pr-12" : "pl-12"
                            } text-[14px] text-[rgba(22,37,79,0.70)] outline-none placeholder:text-[rgba(22,37,79,0.70)] focus:border-[var(--brand-primary-color)] focus:ring-2 focus:ring-[rgba(22,37,79,0.15)]`}
                        />
                        <Search
                            size={20}
                            className={`absolute ${
                                isRTL ? "right-4" : "left-4"
                            } top-1/2 -translate-y-1/2 text-[rgba(22,37,79,0.70)]`}
                        />
                    </div>

                    {/* Action Buttons */}
                    <div className="flex items-center gap-3 shrink-0">
                        <button
                            type="button"
                            onClick={handleSearch}
                            className="h-[52px] rounded-2xl bg-[#EDC98E] px-8 text-[14px] font-bold text-[#16254F] transition hover:bg-[#D9B477]"
                        >
                            {t("carFinder.searchButton", {
                                defaultValue: "بحث",
                            })}
                        </button>
                        <button
                            type="button"
                            onClick={() => setFiltersOpen((prev) => !prev)}
                            className="flex h-[52px] items-center justify-center gap-2 rounded-2xl bg-[#16254F] px-5 text-[14px] font-bold text-white transition hover:bg-[#0F1E36]"
                        >
                            <span>
                                {t("carFinder.resetButton", {
                                    defaultValue: "الفلاتر",
                                })}
                            </span>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            >
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                            </svg>
                        </button>
                    </div>
                </div>

                {/* Filter Selects Panel */}
                {filtersOpen && (
                    <div className="mt-6 rounded-2xl border border-[#E7E9EF] bg-white p-6 shadow-sm">
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                            {/* Brand */}
                            <div>
                                <label className={`mb-2 block text-[12px] font-bold text-[#64748B] ${isRTL ? "text-right" : "text-left"}`}>
                                    {t("carFinder.brand")}
                                </label>
                                <Select
                                    searchable
                                    placeholder={t(
                                        "carFinder.brandPlaceholder",
                                    )}
                                    value={brandId}
                                    onChange={setBrandId}
                                    options={brands.map((b) => ({
                                        label: getLocalizedName(
                                            b.name,
                                            i18n.language,
                                        ),
                                        value: String(b.id),
                                    }))}
                                    icon={
                                        <Tag
                                            size={16}
                                            className="text-[#FF4A4A]"
                                        />
                                    }
                                    className="h-[48px] rounded-[12px] border border-[#E5E7EB] bg-[#FAFBFC] text-sm text-[#111827] focus:ring-1"
                                />
                            </div>

                            {/* Model */}
                            <div>
                                <label className={`mb-2 block text-[12px] font-bold text-[#64748B] ${isRTL ? "text-right" : "text-left"}`}>
                                    {t("carFinder.model", { defaultValue: "الموديل" })}
                                </label>
                                <Select
                                    searchable
                                    placeholder={
                                        isLoadingModels
                                            ? t("carFinder.loading", { defaultValue: "جاري التحميل..." })
                                            : t("carFinder.modelPlaceholder", { defaultValue: "اختر الموديل" })
                                    }
                                    value={modelId}
                                    onChange={setModelId}
                                    disabled={!brandId || isLoadingModels}
                                    options={models.map((m) => ({
                                        label: m.name,
                                        value: String(m.id),
                                    }))}
                                    icon={
                                        <Tag
                                            size={16}
                                            className="text-[#FF4A4A] opacity-60"
                                        />
                                    }
                                    className="h-[48px] rounded-[12px] border border-[#E5E7EB] bg-[#FAFBFC] text-sm text-[#111827] focus:ring-1"
                                />
                            </div>

                            {/* Type */}
                            <div>
                                <label className={`mb-2 block text-[12px] font-bold text-[#64748B] ${isRTL ? "text-right" : "text-left"}`}>
                                    {t("carFinder.type")}
                                </label>
                                <Select
                                    searchable
                                    placeholder={t("carFinder.typePlaceholder")}
                                    value={typeId}
                                    onChange={setTypeId}
                                    options={types.map((t) => ({
                                        label: getLocalizedName(
                                            t.name,
                                            i18n.language,
                                        ),
                                        value: String(t.id),
                                    }))}
                                    icon={
                                        <Car
                                            size={16}
                                            className="text-[#B84DFF]"
                                        />
                                    }
                                    className="h-[48px] rounded-[12px] border border-[#E5E7EB] bg-[#FAFBFC] text-sm text-[#111827] focus:ring-1"
                                />
                            </div>

                            {/* Category */}
                            <div>
                                <label className={`mb-2 block text-[12px] font-bold text-[#64748B] ${isRTL ? "text-right" : "text-left"}`}>
                                    {t("carFinder.category")}
                                </label>
                                <Select
                                    searchable
                                    placeholder={t(
                                        "carFinder.categoryPlaceholder",
                                    )}
                                    value={categoryId}
                                    onChange={setCategoryId}
                                    options={categories.map((c) => ({
                                        label: getLocalizedName(
                                            c.name,
                                            i18n.language,
                                        ),
                                        value: String(c.id),
                                    }))}
                                    icon={
                                        <CalendarDays
                                            size={16}
                                            className="text-[#3B82F6]"
                                        />
                                    }
                                    className="h-[48px] rounded-[12px] border border-[#E5E7EB] bg-[#FAFBFC] text-sm text-[#111827] focus:ring-1"
                                />
                            </div>

                            {/* Year */}
                            <div>
                                <label className={`mb-2 block text-[12px] font-bold text-[#64748B] ${isRTL ? "text-right" : "text-left"}`}>
                                    {t("carFinder.year")}
                                </label>
                                <Select
                                    searchable
                                    placeholder={t("carFinder.yearPlaceholder")}
                                    value={yearVal}
                                    onChange={setYearVal}
                                    options={years.map((y) => {
                                        const val =
                                            typeof y === "string"
                                                ? y
                                                : (y?.year ?? String(y));
                                        return { label: val, value: val };
                                    })}
                                    icon={
                                        <Gauge
                                            size={16}
                                            className="text-[#FF3FB4]"
                                        />
                                    }
                                    className="h-[48px] rounded-[12px] border border-[#E5E7EB] bg-[#FAFBFC] text-sm text-[#111827] focus:ring-1"
                                />
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </section>
    );
}
