import { useState, useEffect, useCallback, useMemo } from "react";
import { useTranslation } from "react-i18next";

import Button from "./button";
import CarCard from "./CarCard";
import SlideArrow from "./SlideArrow";
import type { IFeaturedCarsSectionProps } from "../interfaces/IFeaturedCarsSectionProps";

function getItemsPerPage(width: number): number {
    if (width >= 1024) return 4;
    if (width >= 640) return 2;
    return 1;
}

export default function FeaturedCarsSection({
    titleBlue,
    buttonText,
    buttonTo,
    cars,
    backgroundImage,
    className = "",
    itemsPerPage: controlledItemsPerPage,
    emptyMessage,
}: IFeaturedCarsSectionProps) {
    const [currentSlide, setCurrentSlide] = useState(0);
    const [isPaused, setIsPaused] = useState(false);
    const [itemsPerPage, setItemsPerPage] = useState(
        () => controlledItemsPerPage ?? getItemsPerPage(window.innerWidth),
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

    const slideGap = 28;

    const maxSlideIndex = Math.max(
        0,
        cars.length > 1 && cars.length <= itemsPerPage
            ? 1
            : cars.length - itemsPerPage
    );
    const totalSlides = maxSlideIndex + 1;
    const cardWidth = `calc((100% - ${slideGap * (itemsPerPage - 1)}px) / ${itemsPerPage})`;

    const hasPrev = currentSlide > 0;
    const hasNext = currentSlide < maxSlideIndex;

    const nextSlide = useCallback(() => {
        setCurrentSlide((prev) => Math.min(prev + 1, maxSlideIndex));
    }, [maxSlideIndex]);

    const prevSlide = useCallback(() => {
        setCurrentSlide((prev) => Math.max(prev - 1, 0));
    }, []);

    useEffect(() => {
        if (isPaused || cars.length <= itemsPerPage) return;

        const id = setInterval(() => {
            setCurrentSlide((prev) => {
                if (prev >= maxSlideIndex) return 0;
                return prev + 1;
            });
        }, 4000);

        return () => clearInterval(id);
    }, [isPaused, cars.length, itemsPerPage, maxSlideIndex]);

    if (!cars.length) {
        if (emptyMessage) {
            return (
                <section
                    dir={i18n.dir()}
                    className={`relative w-full overflow-hidden py-14 ${className}`}
                >
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="flex min-h-[200px] items-center justify-center">
                            <p className="text-lg text-[#6EA9F5]">
                                {emptyMessage}
                            </p>
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
            className={`relative w-full overflow-hidden py-14 ${className}`}
            style={
                backgroundImage
                    ? {
                          backgroundImage: `url(${backgroundImage})`,
                          backgroundSize: "cover",
                          backgroundPosition: "center",
                      }
                    : undefined
            }
            onMouseEnter={() => setIsPaused(true)}
            onMouseLeave={() => setIsPaused(false)}
            onFocus={() => setIsPaused(true)}
            onBlur={() => setIsPaused(false)}
        >
            {backgroundImage && (
                <div className="absolute inset-0" />
            )}
            <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="mb-10 flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
                    <div className={isRTL ? "text-right" : "text-left"}>
                        <p className="text-[32px] text-[var(--brand-primary-color)] md:text-[32px] font-bold">
                            {titleBlue}
                        </p>
                    </div>
                    {totalSlides > 1 && (
                        <div dir="ltr" className="flex items-center justify-center gap-6">
                            <SlideArrow
                                direction="prev"
                                onClick={isRTL ? nextSlide : prevSlide}
                                className={
                                    (isRTL ? !hasNext : !hasPrev)
                                        ? "bg-[#16254F]/30! text-white/40! border-transparent! cursor-not-allowed"
                                        : "bg-[#16254F]! text-white! border-transparent! hover:bg-[#16254F]/85!"
                                }
                            />
                            <SlideArrow
                                direction="next"
                                onClick={isRTL ? prevSlide : nextSlide}
                                className={
                                    (isRTL ? !hasPrev : !hasNext)
                                        ? "bg-[#16254F]/30! text-white/40! border-transparent! cursor-not-allowed"
                                        : "bg-[#16254F]! text-white! border-transparent! hover:bg-[#16254F]/85!"
                                }
                            />
                        </div>
                    )}
                </div>

                <div className="overflow-hidden">
                    <div
                        className="flex transition-transform duration-300 ease-in-out"
                        style={{
                            transform: `translateX(${isRTL ? "" : "-" }calc(${currentSlide} * (100% + ${slideGap}px) / ${itemsPerPage}))`,
                            gap: `${slideGap}px`
                        }}
                    >
                        {cars.map((car, idx) => (
                            <div
                                key={car.id}
                                className="h-full shrink-0"
                                style={{ width: cardWidth }}
                            >
                                <CarCard {...car} />
                            </div>
                        ))}
                    </div>
                </div>

                <div className="mt-10 flex justify-center">
                    <Button
                        to={buttonTo}
                        className="w-full px-6 py-2.5 text-[13px] md:w-auto md:px-8 md:py-3 md:text-[15px]"
                    >
                        {buttonText}
                    </Button>
                </div>
            </div>
        </section>
    );
}
