import { useState, useEffect, useCallback, useMemo } from "react";
import { useTranslation } from "react-i18next";

import Button from "./button";
import CarCard from "./CarCard";
import SlideArrow from "./SlideArrow";
import type {
  IBudgetCarsSectionProps,
  IBudgetRange,
} from "../interfaces/IBudgetCarsSectionProps";

function getItemsPerPage(width: number): number {
  if (width >= 1024) return 4;
  if (width >= 640) return 2;
  return 1;
}

function useDefaultRanges(t: (key: string) => string): IBudgetRange[] {
  return [
    { label: t("budgetCars.ranges.0"), value: "3000-4000" },
    { label: t("budgetCars.ranges.1"), value: "4000-6000" },
    { label: t("budgetCars.ranges.2"), value: "7000-9000" },
    { label: t("budgetCars.ranges.3"), value: "9000-plus" },
  ];
}

export default function BudgetCarsSection({
  titleBlue,
  titleOrange,
  description,
  buttonText,
  buttonTo,
  cars,
  ranges,
  activeRange = "3000-4000",
  onRangeChange,
}: IBudgetCarsSectionProps) {
  const [currentSlide, setCurrentSlide] = useState(0);
  const [isPaused, setIsPaused] = useState(false);
  const [itemsPerPage, setItemsPerPage] = useState(() =>
    getItemsPerPage(window.innerWidth),
  );
  const { t, i18n } = useTranslation();
  const defaultRanges = useDefaultRanges(t);
  const resolvedRanges = ranges ?? defaultRanges;
  const isRTL = i18n.dir() === "rtl";

  useEffect(() => {
    function handleResize() {
      const newCount = getItemsPerPage(window.innerWidth);
      setItemsPerPage((prev) => {
        if (prev !== newCount) {
          setCurrentSlide(0);
        }
        return newCount;
      });
    }

    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  const slideGap = 28;

  const slides = useMemo(() => {
    if (!cars.length) return [];
    const remainder = cars.length % itemsPerPage;
    const padded = remainder === 0 ? cars : [...cars, ...cars.slice(0, itemsPerPage - remainder)];
    const result: typeof cars[] = [];
    for (let i = 0; i < padded.length; i += itemsPerPage) {
      result.push(padded.slice(i, i + itemsPerPage));
    }
    return result;
  }, [cars, itemsPerPage]);

  const totalSlides = slides.length;
  const cardWidth = `calc((100% - ${slideGap * (itemsPerPage - 1)}px) / ${itemsPerPage})`;

  const goToSlide = useCallback(
    (index: number) => {
      setCurrentSlide((index + totalSlides) % totalSlides);
    },
    [totalSlides],
  );

  const nextSlide = useCallback(() => {
    goToSlide(currentSlide + 1);
  }, [currentSlide, goToSlide]);

  const prevSlide = useCallback(() => {
    goToSlide(currentSlide - 1);
  }, [currentSlide, goToSlide]);

  useEffect(() => {
    if (isPaused || totalSlides <= 1) return;

    const id = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % totalSlides);
    }, 4000);

    return () => clearInterval(id);
  }, [isPaused, totalSlides]);

  if (!cars.length) return null;

  return (
    <section
      dir={i18n.dir()}
      className="w-full py-16"
      onMouseEnter={() => setIsPaused(true)}
      onMouseLeave={() => setIsPaused(false)}
    >
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-10 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
          <div className={isRTL ? "text-right" : "text-left"}>
            <h2 className="text-[26px] font-bold leading-tight md:text-[40px]">
              <span className="text-[var(--brand-primary-color)]">
                {titleBlue}
              </span>{" "}
              <span className="text-[var(--brand-secondary-color)]">
                {titleOrange}
              </span>
            </h2>

            <p className="mt-4 max-w-2xl text-[14px] leading-6 text-[#667085] md:text-[16px] md:leading-7">
              {description}
            </p>
          </div>

          {/* Slider Navigation Arrows in Header */}
          {totalSlides > 1 && (
            <div dir="ltr" className="flex items-center gap-6">
              <SlideArrow
                direction="prev"
                onClick={isRTL ? nextSlide : prevSlide}
                className="bg-[#16254F]! text-white! border-transparent! hover:bg-[#16254F]/85!"
              />
              <SlideArrow
                direction="next"
                onClick={isRTL ? prevSlide : nextSlide}
                className="bg-[#16254F]! text-white! border-transparent! hover:bg-[#16254F]/85!"
              />
            </div>
          )}
        </div>

        {/* Budget Filters */}
        <div className="mb-9 flex flex-wrap items-center justify-center gap-3">
          {/* "All" Filter Button */}
          <button
            type="button"
            onClick={() => onRangeChange?.("all")}
            className={`flex h-[46px] items-center justify-center rounded-full border px-6 text-[14px] font-bold transition-all duration-300 ${
              activeRange === "all" || !activeRange
                ? "border-[#16254F] bg-[#16254F] text-white shadow-[0_4px_12px_rgba(22,37,79,0.15)]"
                : "border-[#E5E7EB] bg-white text-[#4F5A6B] hover:border-gray-300 hover:text-[#16254F]"
            }`}
          >
            {t("allCarsFilterBar.all") || "الكل"}
          </button>

          {resolvedRanges.map((range) => {
            const isActive = range.value === activeRange;

            // Render label helper
            const renderRangeLabel = (label: string) => {
              if (label.includes("﷼") || label.includes("SAR") || label.includes("ر.س")) {
                return label;
              }
              const currency = isRTL ? "ر.س" : "SAR";
              return isRTL ? `${currency} ${label}` : `${label} ${currency}`;
            };

            return (
              <button
                key={range.value}
                type="button"
                onClick={() => onRangeChange?.(range.value)}
                className={`flex h-[46px] items-center justify-center rounded-full border px-6 text-[14px] font-bold transition-all duration-300 ${
                  isActive
                    ? "border-[#16254F] bg-[#16254F] text-white shadow-[0_4px_12px_rgba(22,37,79,0.15)]"
                    : "border-[#E5E7EB] bg-white text-[#4F5A6B] hover:border-gray-300 hover:text-[#16254F]"
                }`}
              >
                {renderRangeLabel(range.label)}
              </button>
            );
          })}
        </div>

        {/* Carousel */}
        <div className="relative overflow-hidden">
          <div
            className="flex transition-transform duration-300 ease-in-out"
            style={{ transform: `translateX(${isRTL ? "" : "-"}${currentSlide * 100}%)` }}
          >
            {slides.map((slide, sIdx) => (
              <div
                key={sIdx}
                className="flex shrink-0 w-full gap-[28px]"
                dir={isRTL ? "rtl" : "ltr"}
              >
                {slide.map((car, cIdx) => (
                  <div
                    key={`${car.id}-${sIdx}-${cIdx}`}
                    className="shrink-0"
                    style={{ width: cardWidth }}
                  >
                    <CarCard {...car} />
                  </div>
                ))}
              </div>
            ))}
          </div>
        </div>

        {/* Center Button at the Bottom */}
        <div className="mt-12 flex justify-center">
          <Button
            to={buttonTo}
            bgColor="bg-[var(--brand-primary-color)]"
            className="w-full px-6 py-2.5 text-[13px] md:w-auto md:px-8 md:py-3 md:text-[15px]"
          >
            {buttonText}
          </Button>
        </div>
      </div>
    </section>
  );
}
