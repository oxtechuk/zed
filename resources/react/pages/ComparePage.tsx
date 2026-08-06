import { useState } from "react";
import { useSearchParams, useNavigate } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import { useTranslation } from "react-i18next";
import CompareCarCard from "../components/compare/CompareCarCard";
import CompareTable from "../components/compare/CompareTable";
import CompareSummary from "../components/compare/CompareSummary";
import CarSelect from "../components/compare/CarSelect";
import LoadingSlot from "../components/compare/LoadingSlot";
import EmptySlot from "../components/compare/EmptySlot";
import { useSEO } from "../utils/useSEO";
import { getCarBySlug, compareCars } from "../services/api/cars.service";
import { formatPrice } from "../utils/format";

export default function ComparePage() {
    const { t, i18n } = useTranslation();
    useSEO(t("pageTitles.compare"), t("comparePage.compareDescription"));
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();
    const initialSlug = searchParams.get("slug") || "";

    const [car1Slug, setCar1Slug] = useState(initialSlug);
    const [car2Slug, setCar2Slug] = useState("");

    const [showSearch1, setShowSearch1] = useState(!initialSlug);
    const [showSearch2, setShowSearch2] = useState(false);

    const { data: car1, isLoading: isLoading1 } = useQuery({
        queryKey: ["compare-car1", car1Slug],
        queryFn: () => getCarBySlug(car1Slug),
        enabled: !!car1Slug,
    });

    const { data: car2, isLoading: isLoading2 } = useQuery({
        queryKey: ["compare-car2", car2Slug],
        queryFn: () => getCarBySlug(car2Slug),
        enabled: !!car2Slug,
    });

    const { data: compareData } = useQuery({
        queryKey: ["compare-result", car1Slug, car2Slug],
        queryFn: () => compareCars([car1Slug, car2Slug]),
        enabled: !!car1Slug && !!car2Slug,
    });

    const handleSelectCar1 = (slug: string) => {
        setCar1Slug(slug);
        setShowSearch1(false);
    };

    const handleRemoveCar1 = () => {
        setCar1Slug("");
        setShowSearch1(true);
    };

    const handleSelectCar2 = (slug: string) => {
        setCar2Slug(slug);
        setShowSearch2(false);
    };

    const handleRemoveCar2 = () => {
        setCar2Slug("");
        setShowSearch2(true);
    };

    return (
        <div
            dir={i18n.dir()}
            className="min-h-screen overflow-x-hidden bg-[#F3F4F6]"
        >
            {/* ── Page Header (Dark Navy Banner) ── */}
            <section className="w-full bg-[#0F172A] py-14 text-white text-center relative overflow-hidden">
                <div className="absolute top-0 right-0 w-80 h-80 bg-[#EDC98E]/5 blur-2xl rounded-full pointer-events-none" />
                <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <span className="text-[13px] font-extrabold text-[#EDC98E] uppercase tracking-wider block mb-2">
                        {t("comparePage.badge", { defaultValue: "مقارنة السيارات" })}
                    </span>
                    <h1 className="text-[30px] font-black text-white leading-tight md:text-[38px]">
                        {t("comparePage.title", { defaultValue: "قارن بين سيارتين" })}
                    </h1>
                    <p className="mt-3 text-[14px] text-gray-400 max-w-xl mx-auto font-extrabold leading-relaxed">
                        {t("comparePage.description", { defaultValue: "اختر سيارتين لمقارنة مواصفاتهما والتكاليف جنباً إلى جنب" })}
                    </p>
                </div>
            </section>

            {/* ── Compare Cards Selection Grid ── */}
            <div className="relative z-20 py-12 px-6">
                <div className="mx-auto max-w-[960px]">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        {/* Slot 1 */}
                        <div>
                            {car1Slug && car1 ? (
                                <CompareCarCard
                                    car={car1}
                                    label={t("comparePage.carOne", { defaultValue: "السيارة الأولى" })}
                                    onRemove={handleRemoveCar1}
                                />
                            ) : car1Slug && isLoading1 ? (
                                <LoadingSlot />
                            ) : showSearch1 ? (
                                <CarSelect
                                    selectedSlug={car1Slug}
                                    onSelect={handleSelectCar1}
                                    onCancel={() => setShowSearch1(false)}
                                    dir={i18n.dir()}
                                />
                            ) : (
                                <EmptySlot
                                    onClick={() => setShowSearch1(true)}
                                />
                            )}
                        </div>

                        {/* Slot 2 */}
                        <div>
                            {car2Slug && car2 ? (
                                <CompareCarCard
                                    car={car2}
                                    label={t("comparePage.carTwo", { defaultValue: "السيارة الثانية" })}
                                    onRemove={handleRemoveCar2}
                                />
                            ) : car2Slug && isLoading2 ? (
                                <LoadingSlot />
                            ) : showSearch2 ? (
                                <CarSelect
                                    selectedSlug={car2Slug}
                                    onSelect={handleSelectCar2}
                                    onCancel={() => setShowSearch2(false)}
                                    dir={i18n.dir()}
                                />
                            ) : (
                                <EmptySlot
                                    onClick={() => setShowSearch2(true)}
                                />
                            )}
                        </div>
                    </div>
                </div>
            </div>

            {/* ── Winner Summary Block ── */}
            {car1Slug &&
                car2Slug &&
                compareData &&
                compareData.length > 0 &&
                car1 &&
                car2 && (
                    <CompareSummary
                        sections={compareData}
                        car1Name={`${car1.name}`}
                        car2Name={`${car2.name}`}
                    />
                )}

            {/* ── Compare Table ── */}
            {car1Slug &&
                car2Slug &&
                compareData &&
                compareData.length > 0 &&
                car1 &&
                car2 && (
                    <div className="mx-auto max-w-7xl px-6 pb-8">
                        <CompareTable
                            sections={compareData}
                            car1Name={`${car1.name}`}
                            car2Name={`${car2.name}`}
                        />
                    </div>
                )}

            {/* ── Bottom Navy Action/Pricing Cards ── */}
            {car1Slug && car2Slug && car1 && car2 && (
                <div className="mx-auto max-w-7xl px-6 pb-20">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {/* Card 1 Action */}
                        <div className="bg-[#0B1736] text-white rounded-[24px] sm:rounded-[28px] p-6 sm:p-7 shadow-xl border border-white/10 flex flex-col justify-between gap-6">
                            {/* Top Info Row */}
                            <div className="flex items-start justify-between gap-4">
                                {/* Right (start): Car Title & Cash Price */}
                                <div className="text-start">
                                    <span className="text-[13px] sm:text-[14px] font-semibold text-white/60 block mb-1 truncate max-w-[220px] sm:max-w-[280px]">
                                        {car1.name}
                                    </span>
                                    <span className="text-[13px] sm:text-[14px] font-bold text-white/80 block mb-0.5">
                                        {t("carCard.cashPrice", {
                                            defaultValue: "السعر",
                                        })}
                                    </span>
                                    <strong className="text-[22px] sm:text-[26px] font-black leading-none text-white">
                                        {formatPrice(
                                            car1.current_price ??
                                                car1.cash_price ??
                                                0,
                                            "white",
                                        )}
                                    </strong>
                                </div>

                                {/* Left (Start): Installment */}
                                <div className="text-start">
                                    <span className="text-[13px] sm:text-[14px] font-medium text-white/50 block mb-1">
                                        {t("carCard.monthlyPayment", {
                                            defaultValue: "القسط من",
                                        })}
                                    </span>
                                    <strong className="text-[22px] sm:text-[26px] font-black leading-none text-[#F3C77C]">
                                        {formatPrice(
                                            car1.min_installment ?? 0,
                                            "#F3C77C",
                                        )}
                                    </strong>
                                </div>
                            </div>

                            {/* Bottom Buttons Row */}
                            <div className="flex items-center gap-3 sm:gap-4">
                                <button
                                    type="button"
                                    onClick={() =>
                                        navigate(`/cars/${car1.slug}`)
                                    }
                                    className="flex-1 h-[48px] sm:h-[52px] rounded-2xl sm:rounded-[18px] bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-[14px] sm:text-[16px] font-black text-white transition-all duration-200 active:scale-95 flex items-center justify-center cursor-pointer"
                                >
                                    {t("comparePage.carDetails", {
                                        defaultValue: "تفاصيل السيارة",
                                    })}
                                </button>
                                <button
                                    type="button"
                                    onClick={() =>
                                        navigate(
                                            `/finance-calculator?carId=${car1.id}`,
                                        )
                                    }
                                    className="flex-1 h-[48px] sm:h-[52px] rounded-2xl sm:rounded-[18px] bg-[#F3C77C] hover:bg-[#E2B66B] text-[14px] sm:text-[16px] font-black text-[#0B1736] transition-all duration-200 active:scale-95 flex items-center justify-center cursor-pointer shadow-sm font-bold"
                                >
                                    {t("carCard.reserve", {
                                        defaultValue: "اطلبها الآن",
                                    })}
                                </button>
                            </div>
                        </div>

                        {/* Card 2 Action */}
                        <div className="bg-[#0B1736] text-white rounded-[24px] sm:rounded-[28px] p-6 sm:p-7 shadow-xl border border-white/10 flex flex-col justify-between gap-6">
                            {/* Top Info Row */}
                            <div className="flex items-start justify-between gap-4">
                                {/* Right (start): Car Title & Cash Price */}
                                <div className="text-start">
                                    <span className="text-[13px] sm:text-[14px] font-semibold text-white/60 block mb-1 truncate max-w-[220px] sm:max-w-[280px]">
                                        {car2.name}
                                    </span>
                                    <span className="text-[13px] sm:text-[14px] font-bold text-white/80 block mb-0.5">
                                        {t("carCard.cashPrice", {
                                            defaultValue: "السعر",
                                        })}
                                    </span>
                                    <strong className="text-[22px] sm:text-[26px] font-black leading-none text-white">
                                        {formatPrice(
                                            car2.current_price ??
                                                car2.cash_price ??
                                                0,
                                            "white",
                                        )}
                                    </strong>
                                </div>

                                {/* Left (Start): Installment */}
                                <div className="text-start">
                                    <span className="text-[13px] sm:text-[14px] font-medium text-white/50 block mb-1">
                                        {t("carCard.monthlyPayment", {
                                            defaultValue: "القسط من",
                                        })}
                                    </span>
                                    <strong className="text-[22px] sm:text-[26px] font-black leading-none text-[#F3C77C]">
                                        {formatPrice(
                                            car2.min_installment ?? 0,
                                            "#F3C77C",
                                        )}
                                    </strong>
                                </div>
                            </div>

                            {/* Bottom Buttons Row */}
                            <div className="flex items-center gap-3 sm:gap-4">
                                <button
                                    type="button"
                                    onClick={() =>
                                        navigate(`/cars/${car2.slug}`)
                                    }
                                    className="flex-1 h-[48px] sm:h-[52px] rounded-2xl sm:rounded-[18px] bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-[14px] sm:text-[16px] font-black text-white transition-all duration-200 active:scale-95 flex items-center justify-center cursor-pointer"
                                >
                                    {t("comparePage.carDetails", {
                                        defaultValue: "تفاصيل السيارة",
                                    })}
                                </button>

                                <button
                                    type="button"
                                    onClick={() =>
                                        navigate(
                                            `/finance-calculator?carId=${car2.id}`,
                                        )
                                    }
                                    className="flex-1 h-[48px] sm:h-[52px] rounded-2xl sm:rounded-[18px] bg-[#F3C77C] hover:bg-[#E2B66B] text-[14px] sm:text-[16px] font-black text-[#0B1736] transition-all duration-200 active:scale-95 flex items-center justify-center cursor-pointer shadow-sm font-bold"
                                >
                                    {t("carCard.reserve", {
                                        defaultValue: "اطلبها الآن",
                                    })}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
