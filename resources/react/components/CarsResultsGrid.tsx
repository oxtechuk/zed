import { useTranslation } from "react-i18next";
import CarCard from "./CarCard";
import type { ICarsResultsGridProps } from "../interfaces/ICarsResultsGridProps";
import { ChevronLeft, ChevronRight } from "lucide-react";

export default function CarsResultsGrid({
  cars,
  currentPage,
  totalPages,
  onPageChange,
}: ICarsResultsGridProps) {
  const { i18n } = useTranslation();
  const isRTL = i18n.dir() === "rtl";

  const handlePrev = () => {
    if (currentPage > 1) {
      onPageChange(currentPage - 1);
    }
  };

  const handleNext = () => {
    if (currentPage < totalPages) {
      onPageChange(currentPage + 1);
    }
  };

  const getPageNumbers = () => {
    const pages: (number | string)[] = [];
    if (totalPages <= 6) {
      for (let i = 1; i <= totalPages; i++) {
        pages.push(i);
      }
    } else {
      if (currentPage <= 3) {
        pages.push(1, 2, 3, 4, "...", totalPages);
      } else if (currentPage >= totalPages - 2) {
        pages.push(1, "...", totalPages - 3, totalPages - 2, totalPages - 1, totalPages);
      } else {
        pages.push(1, "...", currentPage - 1, currentPage, currentPage + 1, "...", totalPages);
      }
    }
    return pages;
  };

  return (
    <section className="w-full">
      {/* Cars Grid */}
      <div className="grid grid-cols-1 gap-7 md:grid-cols-2 xl:grid-cols-3 justify-items-center">
        {cars.map((car, idx) => (
          <CarCard key={car.id} {...car} loading={idx < 3 ? "eager" : "lazy"} />
        ))}
      </div>

      {/* Pagination */}
      {totalPages > 1 && (
        <div className="mt-14 flex items-center justify-center gap-2" dir={i18n.dir()}>
          {/* Previous */}
          <button
            type="button"
            onClick={handlePrev}
            disabled={currentPage === 1}
            className="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#D1D5DB] bg-white text-[#16254F] transition hover:border-[#16254F] disabled:cursor-not-allowed disabled:opacity-40"
          >
            {isRTL ? <ChevronRight size={16} /> : <ChevronLeft size={16} />}
          </button>

          {/* Page Numbers */}
          {getPageNumbers().map((page, idx) => {
            const isEllipsis = page === "...";
            const isActive = page === currentPage;

            if (isEllipsis) {
              return (
                <span
                  key={`ellipsis-${idx}`}
                  className="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#D1D5DB] bg-white text-[13px] font-black text-gray-400"
                >
                  ···
                </span>
              );
            }

            return (
              <button
                key={`page-${page}`}
                type="button"
                onClick={() => onPageChange(page as number)}
                className={`flex h-11 w-11 items-center justify-center rounded-2xl border text-[14px] font-black transition ${
                  isActive
                    ? "border-[#16254F] bg-[#16254F] text-white"
                    : "border-[#D1D5DB] bg-white text-[#374151] hover:border-[#16254F] hover:text-[#16254F]"
                }`}
              >
                {page}
              </button>
            );
          })}

          {/* Next */}
          <button
            type="button"
            onClick={handleNext}
            disabled={currentPage === totalPages}
            className="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#D1D5DB] bg-white text-[#16254F] transition hover:border-[#16254F] disabled:cursor-not-allowed disabled:opacity-40"
          >
            {isRTL ? <ChevronLeft size={16} /> : <ChevronRight size={16} />}
          </button>
        </div>
      )}
    </section>
  );
}
