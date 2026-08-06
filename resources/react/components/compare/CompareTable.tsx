import { useState } from "react";
import { useTranslation } from "react-i18next";
import type { ICompareTableProps } from "../../interfaces/ICompareTableProps";
import WinnerValue from "./WinnerValue";

export default function CompareTable({
  sections,
  car1Name,
  car2Name,
}: ICompareTableProps) {
  const { t, i18n } = useTranslation();

  if (!sections.length) return null;

  const tabs = [...new Set(sections.map((s) => s.title))];
  const [activeTab, setActiveTab] = useState("all");

  const tableSections = sections
    .filter((s) => activeTab === "all" || s.title === activeTab)
    .map((s) => ({
      title: s.title,
      rows: s.rows.map((r) => ({
        label: r.label,
        carOneValue: r.val1,
        carTwoValue: r.val2,
        winner:
          r.winner === 1
            ? ("carOne" as const)
            : r.winner === 2
              ? ("carTwo" as const)
              : undefined,
      })),
    }));

  return (
    <section dir={i18n.dir()} className="mx-auto w-full max-w-[1200px] pb-12">
      {/* Tabs Filter Bar - Premium Pill Style */}
      <div className="mb-6 flex flex-wrap items-center justify-between gap-3 text-start">
        <div className="flex flex-wrap gap-2">
          <button
            type="button"
            onClick={() => setActiveTab("all")}
            className={`h-9 rounded-full px-5 text-[13px] font-black transition-all ${
              activeTab === "all"
                ? "bg-[#0F172A] text-white scale-105 shadow-xs"
                : "bg-white border border-gray-200 text-gray-500 hover:border-[#0F172A] hover:text-[#0F172A]"
            }`}
          >
            {t("comparePage.all", { defaultValue: "الكل" })}
          </button>
          {tabs.map((tab) => (
            <button
              key={tab}
              type="button"
              onClick={() => setActiveTab(tab)}
              className={`h-9 rounded-full px-5 text-[13px] font-black transition-all ${
                activeTab === tab
                  ? "bg-[#0F172A] text-white scale-105 shadow-xs"
                  : "bg-white border border-gray-200 text-gray-500 hover:border-[#0F172A] hover:text-[#0F172A]"
              }`}
            >
              {tab}
            </button>
          ))}
        </div>
      </div>

      {/* Table Header Wrapper */}
      <div className="overflow-hidden rounded-[24px] border border-gray-200 bg-white shadow-xs">
        {/* Table Header Row */}
        <div className="grid grid-cols-[1.2fr_1.4fr_1.4fr] bg-[#F8FAFC] border-b border-gray-200 text-[13px] font-black text-gray-400 max-md:grid-cols-[1fr_1fr_1fr]">
          <div className="flex min-h-[58px] items-center px-6 text-[#6B7280]">
            {t("comparePage.criterion", { defaultValue: "المعيار" })}
          </div>
          <div className="flex min-h-[58px] items-center justify-center gap-2 px-5 text-center border-r border-l border-gray-100 text-[#0F172A]">
            <span className="h-2.5 w-2.5 shrink-0 rounded-full bg-[#E5C287]" />
            <span className="line-clamp-1">{car1Name}</span>
          </div>
          <div className="flex min-h-[58px] items-center justify-center gap-2 px-5 text-center text-[#0F172A]">
            <span className="h-2.5 w-2.5 shrink-0 rounded-full bg-[#A0AEC0]" />
            <span className="line-clamp-1">{car2Name}</span>
          </div>
        </div>

        {/* Table Sections Content */}
        <div className="divide-y divide-gray-100">
          {tableSections.map((section, si) => (
            <div key={`${section.title}-${si}`}>
              {section.rows.map((row) => (
                <div
                  key={row.label}
                  className="grid min-h-[56px] grid-cols-[1.2fr_1.4fr_1.4fr] border-b border-gray-100 text-[14px] last:border-b-0 max-md:grid-cols-[1fr_1fr_1fr]"
                >
                  {/* Spec Label Column */}
                  <div className="flex items-center bg-[#F8FAFC] px-6 font-extrabold text-gray-500">
                    {row.label}
                  </div>

                  {/* Car 1 Value Column */}
                  <div
                    className={`flex items-center justify-center px-4 border-r border-l border-gray-100 ${
                      row.winner === "carOne" ? "bg-[#FFF9F2]" : "bg-white"
                    }`}
                  >
                    <WinnerValue
                      value={row.carOneValue}
                      isWinner={row.winner === "carOne"}
                    />
                  </div>

                  {/* Car 2 Value Column */}
                  <div
                    className={`flex items-center justify-center px-4 ${
                      row.winner === "carTwo" ? "bg-[#FFF9F2]" : "bg-white"
                    }`}
                  >
                    <WinnerValue
                      value={row.carTwoValue}
                      isWinner={row.winner === "carTwo"}
                    />
                  </div>
                </div>
              ))}
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
