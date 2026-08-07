import { useTranslation } from "react-i18next";
import { ChevronLeft, ChevronRight } from "lucide-react";
import type { IBlogPaginationProps } from "../../interfaces/IBlogPaginationProps";

export default function BlogPagination({
  currentPage,
  lastPage,
  onPageChange,
  isRTL: isRTLProp,
}: IBlogPaginationProps) {
  const { i18n } = useTranslation();
  const isRTL = isRTLProp ?? (i18n.dir() === "rtl");

  if (lastPage <= 1) return null;

  const pages: (number | string)[] = [];
  if (lastPage <= 5) {
    for (let i = 1; i <= lastPage; i++) pages.push(i);
  } else {
    if (currentPage <= 3) {
      pages.push(1, 2, 3, "...", lastPage);
    } else if (currentPage >= lastPage - 2) {
      pages.push(1, "...", lastPage - 2, lastPage - 1, lastPage);
    } else {
      pages.push(1, "...", currentPage - 1, currentPage, currentPage + 1, "...", lastPage);
    }
  }

  const PrevIcon = isRTL ? ChevronRight : ChevronLeft;
  const NextIcon = isRTL ? ChevronLeft : ChevronRight;

  return (
    <div className="mt-14 flex items-center justify-center gap-2.5" dir={isRTL ? "rtl" : "ltr"}>
      {/* Previous page arrow */}
      <button
        type="button"
        onClick={() => currentPage > 1 && onPageChange(currentPage - 1)}
        disabled={currentPage === 1}
        className="w-11 h-11 rounded-2xl flex items-center justify-center border border-[#3B4874]/30 bg-[#F9FAFC] text-[#16254F] hover:bg-[#16254F] hover:text-white hover:border-[#16254F] disabled:opacity-40 disabled:hover:bg-[#F9FAFC] disabled:hover:text-[#16254F] disabled:hover:border-[#3B4874]/30 transition cursor-pointer"
      >
        <PrevIcon size={18} />
      </button>

      {/* Page buttons */}
      {pages.map((p, idx) => {
        if (p === "...") {
          return (
            <div
              key={`dots-${idx}`}
              className="w-11 h-11 rounded-2xl flex items-center justify-center border border-[#3B4874]/30 bg-[#F9FAFC] text-[#7A8299] text-sm font-semibold select-none"
            >
              ...
            </div>
          );
        }
        const isActive = p === currentPage;
        return (
          <button
            key={`page-${p}`}
            type="button"
            onClick={() => onPageChange(p as number)}
            className={`w-11 h-11 rounded-2xl flex items-center justify-center border text-sm font-bold transition cursor-pointer ${
              isActive
                ? "bg-[#16254F] border-[#16254F] text-white shadow-sm"
                : "bg-[#F9FAFC] border-[#3B4874]/30 text-[#4A5578] hover:bg-[#16254F] hover:text-white hover:border-[#16254F]"
            }`}
          >
            {p}
          </button>
        );
      })}

      {/* Next page arrow */}
      <button
        type="button"
        onClick={() => currentPage < lastPage && onPageChange(currentPage + 1)}
        disabled={currentPage === lastPage}
        className="w-11 h-11 rounded-2xl flex items-center justify-center border border-[#3B4874]/30 bg-[#F9FAFC] text-[#16254F] hover:bg-[#16254F] hover:text-white hover:border-[#16254F] disabled:opacity-40 disabled:hover:bg-[#F9FAFC] disabled:hover:text-[#16254F] disabled:hover:border-[#3B4874]/30 transition cursor-pointer"
      >
        <NextIcon size={18} />
      </button>
    </div>
  );
}
