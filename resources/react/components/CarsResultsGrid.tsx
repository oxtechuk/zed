import { useTranslation } from "react-i18next";
import { Link, useSearchParams } from "react-router-dom";
import CarCard from "./CarCard";
import type { ICarsResultsGridProps } from "../interfaces/ICarsResultsGridProps";
import { ChevronLeft, ChevronRight } from "lucide-react";

export default function CarsResultsGrid({
  cars,
  currentPage,
  totalPages,
  onPageChange,
}: ICarsResultsGridProps) {
  const { i18n, t } = useTranslation();
  const isRTL = i18n.dir() === "rtl";
  const [searchParams] = useSearchParams();

  const getPageLink = (page: number) => {
    const params = new URLSearchParams(searchParams);
    if (page <= 1) {
      params.delete("page");
    } else {
      params.set("page", String(page));
    }
    const qs = params.toString();
    return `/cars${qs ? `?${qs}` : ""}`;
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
          {currentPage > 1 ? (
            <Link
              to={getPageLink(currentPage - 1)}
              onClick={() => onPageChange(currentPage - 1)}
              className="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#D1D5DB] bg-white text-[#16254F] transition hover:border-[#16254F]"
              aria-label={t("common.previous", { defaultValue: "السابق" })}
            >
              {isRTL ? <ChevronRight size={16} /> : <ChevronLeft size={16} />}
            </Link>
          ) : (
            <span
              className="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#D1D5DB] bg-white text-[#16254F] opacity-40 cursor-not-allowed"
              aria-disabled="true"
            >
              {isRTL ? <ChevronRight size={16} /> : <ChevronLeft size={16} />}
            </span>
          )}

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

            const pageNum = page as number;

            return (
              <Link
                key={`page-${page}`}
                to={getPageLink(pageNum)}
                onClick={() => onPageChange(pageNum)}
                className={`flex h-11 w-11 items-center justify-center rounded-2xl border text-[14px] font-black transition ${
                  isActive
                    ? "border-[#16254F] bg-[#16254F] text-white"
                    : "border-[#D1D5DB] bg-white text-[#374151] hover:border-[#16254F] hover:text-[#16254F]"
                }`}
                aria-label={`صفحة ${pageNum}`}
                aria-current={isActive ? "page" : undefined}
              >
                {page}
              </Link>
            );
          })}

          {/* Next */}
          {currentPage < totalPages ? (
            <Link
              to={getPageLink(currentPage + 1)}
              onClick={() => onPageChange(currentPage + 1)}
              className="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#D1D5DB] bg-white text-[#16254F] transition hover:border-[#16254F]"
              aria-label={t("common.next", { defaultValue: "التالي" })}
            >
              {isRTL ? <ChevronLeft size={16} /> : <ChevronRight size={16} />}
            </Link>
          ) : (
            <span
              className="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#D1D5DB] bg-white text-[#16254F] opacity-40 cursor-not-allowed"
              aria-disabled="true"
            >
              {isRTL ? <ChevronLeft size={16} /> : <ChevronRight size={16} />}
            </span>
          )}
        </div>
      )}
    </section>
  );
}
