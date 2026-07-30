import { useState, useEffect, useCallback, useMemo } from "react";
import { useTranslation } from "react-i18next";
import { ChevronLeft, ChevronRight } from "lucide-react";
import CarCard from "./CarCard";
import type { IFeaturedCarsSectionProps } from "../interfaces/IFeaturedCarsSectionProps";

function getItemsPerPage(width: number): number {
  if (width >= 1024) return 3; // 3 columns for desktop since sidebar is removed!
  if (width >= 640) return 2;
  return 1;
}

export default function FeaturedCarsSection({
  titleBlue,
  titleOrange,
  description,
  cars,
  backgroundImage,
  className = "",
  itemsPerPage: controlledItemsPerPage,
  emptyMessage,
}: IFeaturedCarsSectionProps) {
  const [currentSlide, setCurrentSlide] = useState(0);
  const [isPaused, setIsPaused] = useState(false);
  const [itemsPerPage, setItemsPerPage] = useState(() =>
    controlledItemsPerPage ?? getItemsPerPage(window.innerWidth)
  );
  const { i18n } = useTranslation();
  const isRTL = i18n.dir() === "rtl";

  useEffect(() => {
    if (controlledItemsPerPage != null) {
      setItemsPerPage(controlledItemsPerPage);
      setCurrentSlide(0);
      return;
    }
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
  }, [controlledItemsPerPage]);

  const slideGap = 24;

  const slides = useMemo(() => {
    if (!cars.length) return [];
    const result: typeof cars[] = [];
    for (let i = 0; i < cars.length; i += itemsPerPage) {
      result.push(cars.slice(i, i + itemsPerPage));
    }
    return result;
  }, [cars, itemsPerPage]);

  const totalSlides = slides.length;
  const cardWidth = `calc((100% - ${slideGap * (itemsPerPage - 1)}px) / ${itemsPerPage})`;

  const goToSlide = useCallback(
    (index: number) => {
      setCurrentSlide((index + totalSlides) % totalSlides);
    },
    [totalSlides]
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
    }, 5000);

    return () => clearInterval(id);
  }, [isPaused, totalSlides]);

  if (!cars.length) {
    if (emptyMessage) {
      return (
        <section dir={i18n.dir()} className={`relative w-full overflow-hidden bg-[#FAFBFC] py-14 ${className}`}>
          <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div className="flex min-h-[200px] items-center justify-center">
              <p className="text-lg text-gray-400">{emptyMessage}</p>
            </div>
          </div>
        </section>
      );
    }
    return null;
  }

  return (
    <section
      dir={i18n.dir()}
      className={`relative w-full overflow-hidden bg-[#FAFBFC] py-10 ${className}`}
      style={backgroundImage ? {
        backgroundImage: `url(${backgroundImage})`,
        backgroundSize: "cover",
        backgroundPosition: "center",
      } : undefined}
      onMouseEnter={() => setIsPaused(true)}
      onMouseLeave={() => setIsPaused(false)}
      onFocus={() => setIsPaused(true)}
      onBlur={() => setIsPaused(false)}
    >
      {backgroundImage && <div className="absolute inset-0 bg-[#051023]/90" />}
      <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {/* Section Header with Navigation Arrows on Left (in RTL) */}
        <div className="mb-8 flex items-end justify-between">
          <div className="text-right">
            <h2 className="text-[24px] font-extrabold text-[#0F172A] md:text-[32px]">
              {titleBlue}
              {titleOrange && (
                <span className="text-[var(--brand-secondary-color)]">
                  {" "}
                  {titleOrange}
                </span>
              )}
            </h2>
            {description && (
              <p className="mt-2 max-w-xl text-[14px] text-gray-500">
                {description}
              </p>
            )}
          </div>

          {/* Slider Navigation Arrows in Header */}
          {totalSlides > 1 && (
            <div className="flex items-center gap-2">
              <button
                type="button"
                onClick={isRTL ? nextSlide : prevSlide}
                className="flex h-10 w-10 items-center justify-center rounded-[10px] border border-[#E5E9F0] bg-white text-[#4B5563] transition hover:border-[#0F1E36] hover:bg-[#0F1E36] hover:text-white"
              >
                <ChevronLeft size={20} />
              </button>
              <button
                type="button"
                onClick={isRTL ? prevSlide : nextSlide}
                className="flex h-10 w-10 items-center justify-center rounded-[10px] border border-[#E5E9F0] bg-white text-[#4B5563] transition hover:border-[#0F1E36] hover:bg-[#0F1E36] hover:text-white"
              >
                <ChevronRight size={20} />
              </button>
            </div>
          )}
        </div>

        {/* Carousel Grid */}
        <div className="relative overflow-hidden">
          <div
            className="flex transition-transform duration-500 ease-in-out"
            style={{ transform: `translateX(${isRTL ? "" : "-"}${currentSlide * 100}%)` }}
          >
            {slides.map((slide, sIdx) => (
              <div
                key={sIdx}
                className="flex shrink-0 w-full gap-[24px]"
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
      </div>
    </section>
  );
}
