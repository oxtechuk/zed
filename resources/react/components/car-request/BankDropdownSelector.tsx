import { useState, useMemo, useRef, useEffect } from "react";
import { useTranslation } from "react-i18next";
import { ChevronDown, Check, Building2 } from "lucide-react";
import type { IBankDropdownSelectorProps } from "../../interfaces/IBankDropdownSelectorProps";

export function BankDropdownSelector({
    banks,
    selectedBankId,
    onSelectBankId,
    loadingBanks,
}: IBankDropdownSelectorProps) {
    const { t } = useTranslation();
    const [isOpen, setIsOpen] = useState(false);
    const [searchTerm, setSearchTerm] = useState("");
    const dropdownRef = useRef<HTMLDivElement | null>(null);

    const inputClasses =
        "h-[50px] w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-[14px] font-medium text-[#0F172A] outline-none transition placeholder:text-gray-400 focus:border-[#0F172A] focus:bg-white focus:ring-2 focus:ring-[#0F172A]/10";

    // Click outside handler
    useEffect(() => {
        function handleClickOutside(event: MouseEvent) {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
                setIsOpen(false);
            }
        }
        document.addEventListener("mousedown", handleClickOutside);
        return () => document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    // Filter banks based on search term
    const filteredBanks = useMemo(() => {
        if (!searchTerm.trim()) return banks;
        const term = searchTerm.toLowerCase().trim();
        return banks.filter((bank) => bank.name.toLowerCase().includes(term));
    }, [banks, searchTerm]);

    const activeBank = useMemo(() => {
        return banks.find((b) => b.id === selectedBankId) || null;
    }, [banks, selectedBankId]);

    return (
        <div className="flex flex-col text-start relative" ref={dropdownRef}>
            <label className="text-[14px] font-extrabold text-[#374151] mb-2">
                {t("carRequest.form.preferredBank", "البنك المفضل")}
            </label>
            <div className="relative">
                <button
                    type="button"
                    disabled={loadingBanks}
                    onClick={() => setIsOpen((prev) => !prev)}
                    className={`${inputClasses} flex items-center justify-between gap-3 text-start cursor-pointer border transition-all ${
                        isOpen
                            ? "border-[#0F172A] bg-white ring-2 ring-[#0F172A]/10"
                            : "border-[#E2E8F0] bg-[#F8FAFC]"
                    }`}
                >
                    {loadingBanks ? (
                        <span className="text-gray-400">
                            {t("carRequest.form.loadingBanks", "جاري تحميل البنوك...")}
                        </span>
                    ) : activeBank ? (
                        <div className="flex items-center gap-2 overflow-hidden">
                            <Building2 size={16} className="text-[#0F172A] shrink-0" />
                            <span className="truncate font-bold text-[#0F172A] text-[14px]">
                                {activeBank.name}
                            </span>
                        </div>
                    ) : (
                        <span className="text-gray-400">
                            {t("carRequest.form.selectBankPlaceholder", "اختر البنك المفضل...")}
                        </span>
                    )}
                    <ChevronDown
                        size={18}
                        className={`shrink-0 text-[#8A8F99] transition-transform duration-200 ${
                            isOpen ? "rotate-180 text-[#0F172A]" : ""
                        }`}
                    />
                </button>

                {isOpen && !loadingBanks && (
                    <div className="absolute bottom-full md:top-full md:bottom-auto z-30 mt-2 mb-2 md:mb-0 w-full max-h-64 overflow-y-auto rounded-2xl border border-[#E2E8F0] bg-white p-2 shadow-xl animate-in fade-in zoom-in-95 duration-150 flex flex-col gap-1">
                        {/* Search Box */}
                        <div className="sticky top-0 bg-white pb-2 pt-1 px-1 border-b border-gray-100 z-10">
                            <input
                                type="text"
                                placeholder={t("carRequest.form.searchBankPlaceholder", "ابحث باسم البنك...")}
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                onClick={(e) => e.stopPropagation()} // Prevent dropdown closing on click
                                className="w-full h-[38px] px-3 rounded-xl border border-gray-200 text-[13px] font-bold outline-none transition focus:border-[#0F172A]"
                            />
                        </div>

                        {/* Banks list */}
                        <div className="flex-1 overflow-y-auto max-h-44 flex flex-col gap-1">
                            {filteredBanks.length > 0 ? (
                                filteredBanks.map((bank) => {
                                    const isSelected = bank.id === selectedBankId;
                                    return (
                                        <button
                                            key={bank.id}
                                            type="button"
                                            onClick={() => {
                                                onSelectBankId(bank.id);
                                                setIsOpen(false);
                                                setSearchTerm("");
                                            }}
                                            className={`flex w-full items-center justify-between rounded-xl p-2.5 text-start transition-colors ${
                                                isSelected
                                                    ? "bg-[#0F172A] text-white"
                                                    : "hover:bg-[#F1F5F9] text-[#0F172A]"
                                            }`}
                                        >
                                            <div className="flex items-center gap-2 truncate">
                                                <Building2 size={15} className={`shrink-0 ${isSelected ? "text-[#EDC98E]" : "text-gray-400"}`} />
                                                <span className="text-[14px] font-bold truncate">
                                                    {bank.name}
                                                </span>
                                            </div>
                                            {isSelected && (
                                                <Check
                                                    size={16}
                                                    className="shrink-0 text-[#EDC98E]"
                                                />
                                            )}
                                        </button>
                                    );
                                })
                            ) : (
                                <div className="text-center py-6 text-gray-400 font-bold text-[13px]">
                                    {t("carRequest.form.noBanksFound", "لا توجد نتائج مطابقة")}
                                </div>
                            )}
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
