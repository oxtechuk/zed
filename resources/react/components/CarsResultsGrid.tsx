import CarCard from "./CarCard";
import type { ICarsResultsGridProps } from "../interfaces/ICarsResultsGridProps";
import { ChevronLeft, ChevronRight } from "lucide-react";

export default function CarsResultsGrid({
  cars,
  currentPage,
  totalPages,
  onPageChange,
}: ICarsResultsGridProps) {

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
        {cars.map((car) => (
          <CarCard key={car.id} {...car} />
        ))}
      </div>

      {/* Pagination */}
      {totalPages > 1 && (
        <div className="mt-14 flex items-center justify-center gap-2.5" dir="ltr">
          {/* Previous Button */}
          <button
            type="button"
            onClick={handlePrev}
            disabled={currentPage === 1}
            className="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition hover:border-[#0F172A] hover:text-[#0F172A] disabled:cursor-not-allowed disabled:opacity-40"
          >
            <ChevronLeft size={16} />
          </button>

          {/* Page Numbers */}
          {getPageNumbers().map((page, idx) => {
            const isEllipsis = page === "...";
            const isActive = page === currentPage;

            if (isEllipsis) {
              return (
                <span
                  key={`ellipsis-${idx}`}
                  className="flex h-10 w-10 items-center justify-center text-gray-400 font-bold"
                >
                  ...
                </span>
              );
            }

            return (
              <button
                key={`page-${page}`}
                type="button"
                onClick={() => onPageChange(page as number)}
                className={`flex h-10 w-10 items-center justify-center rounded-full border text-[14px] font-extrabold transition ${
                  isActive
                    ? "border-[#0F172A] bg-[#0F172A] text-white scale-105 shadow-xs"
                    : "border-gray-200 bg-white text-gray-500 hover:border-[#0F172A] hover:text-[#0F172A]"
                }`}
              >
                {page}
              </button>
            );
          })}

          {/* Next Button */}
          <button
            type="button"
            onClick={handleNext}
            disabled={currentPage === totalPages}
            className="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition hover:border-[#0F172A] hover:text-[#0F172A] disabled:cursor-not-allowed disabled:opacity-40"
          >
            <ChevronRight size={16} />
          </button>
        </div>
      )}
    </section>
  );
}
