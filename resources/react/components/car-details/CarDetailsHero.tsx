import { useState, useCallback, useMemo } from "react";
import { useTranslation } from "react-i18next";
import {
    ArrowLeft,
    ArrowRight,
    Users,
    Scale,
    MessageCircle,
    Phone,
} from "lucide-react";
import { getImageUrl } from "../../constants/app-images";
import { formatPrice } from "../../utils/format";
import type {
    ICarColor,
    ICarDetailsHeroProps,
} from "../../interfaces/ICarDetailsHeroProps";

export default function CarDetailsHero({
    title,
    description,
    images,
    exteriorImages,
    interiorImages,
    mainImage,
    price,
    monthlyInstallment,
    fuelType,
    transmission,
    seats,
    horsepower,
    type,
    year,
    brandName,
}: ICarDetailsHeroProps) {
    const { t, i18n } = useTranslation();
    const [activeImage, setActiveImage] = useState(0);
    const [selectedColor, setSelectedColor] = useState<ICarColor | null>(null);

    // Interactive month term state
    const [selectedMonth, setSelectedMonth] = useState(60);

    const currentImages = useMemo(() => {
        const baseList = images.map(getImageUrl);

        if (mainImage) {
            const mainImgUrl = getImageUrl(mainImage);
            return [
                mainImgUrl,
                ...baseList.filter((img) => img !== mainImgUrl),
            ];
        }

        return baseList;
    }, [images, mainImage]);

    const colorImage = selectedColor?.image
        ? getImageUrl(selectedColor.image)
        : null;
    const currentImage = colorImage ?? currentImages[activeImage];
    const totalImages = currentImages.length;

    const handleNext = () => {
        setActiveImage((prev) => (prev === totalImages - 1 ? 0 : prev + 1));
    };

    const handlePrev = () => {
        setActiveImage((prev) => (prev === 0 ? totalImages - 1 : prev - 1));
    };

    // Dynamic installment calculation based on base min_installment (assumed at 60 months)
    const calculatedInstallment = Math.round(
        (monthlyInstallment * 60) / selectedMonth,
    );

    return (
        <div className="w-full flex flex-col">
            {/* Breadcrumb Bar */}
            <div className="w-full bg-[#080E1E] py-3.5 text-white text-[13px] border-b border-white/5">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-start flex items-center gap-2">
                    <a
                        href="/"
                        className="opacity-60 hover:opacity-100 hover:text-[#EDC98E] transition-colors"
                    >
                        {t("nav.home")}
                    </a>
                    <span className="opacity-30">/</span>
                    <a
                        href="/cars"
                        className="opacity-60 hover:opacity-100 hover:text-[#EDC98E] transition-colors"
                    >
                        {t("nav.cars")}
                    </a>
                    <span className="opacity-30">/</span>
                    <span className="font-bold opacity-90 truncate">
                        {brandName} {title}
                    </span>
                </div>
            </div>

            {/* Main Details Section */}
            <section
                dir={i18n.dir()}
                className="w-full bg-[#F3F4F6] py-10 md:py-16"
            >
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    {/* Desktop 2-column layout (Gallery on right, Sidebar on left) */}
                    <div className="grid grid-cols-1 items-start gap-10 lg:grid-cols-12">
                        {/* 1. Gallery Side (Renders first on mobile, right on desktop) */}
                        <div className="lg:col-span-8 flex flex-col order-1">
                            {/* Main Gallery Container */}
                            <div className="order-2 lg:order-1 mt-8 lg:mt-0 relative overflow-hidden rounded-[24px] bg-white border border-[#E7E9EF] shadow-sm flex items-center justify-center min-h-[380px] md:min-h-[480px] w-full">
                                {/* Main Image */}
                                <img
                                    src={currentImage}
                                    alt={title}
                                    className="absolute inset-0 w-full h-full object-cover transition-all duration-500"
                                    loading="lazy"
                                />

                                {/* Bottom Overlay containing Thumbnails and Navigation Arrows */}
                                {totalImages > 1 && !colorImage && (
                                    <div className="absolute bottom-0 left-0 right-0 pt-20 pb-6 md:pb-8 flex items-center justify-center bg-gradient-to-t from-[#16254F]/90 via-[#16254F]/40 to-transparent z-10">
                                        {/* Left Button (Prev) */}
                                        <button
                                            type="button"
                                            onClick={handlePrev}
                                            className="absolute left-4 md:left-6 flex h-10 w-10 md:h-12 md:w-12 items-center justify-center rounded-full bg-white/10 backdrop-blur-md text-white border border-white/20 transition hover:bg-white/25 active:scale-95 cursor-pointer z-20"
                                        >
                                            <ArrowLeft size={20} />
                                        </button>

                                        {/* Thumbnails Row */}
                                        <div className="flex items-center gap-2 md:gap-3 overflow-x-auto no-scrollbar max-w-[calc(100%-110px)] md:max-w-[calc(100%-150px)] py-1 z-10 justify-center">
                                            {currentImages.map(
                                                (image, index) => (
                                                    <button
                                                        key={index}
                                                        type="button"
                                                        onClick={() =>
                                                            setActiveImage(
                                                                index,
                                                            )
                                                        }
                                                        className={`h-12 w-16 md:h-16 md:w-22 overflow-hidden rounded-xl border transition-all duration-300 shrink-0 ${
                                                            activeImage ===
                                                            index
                                                                ? "border-[#EDC98E] ring-2 ring-[#EDC98E]/30 scale-105"
                                                                : "border-white/10 hover:border-white/30"
                                                        }`}
                                                    >
                                                        <img
                                                            src={image}
                                                            className="h-full w-full object-cover bg-white"
                                                            alt={`${title} thumb ${index}`}
                                                        />
                                                    </button>
                                                ),
                                            )}
                                        </div>

                                        {/* Right Button (Next) */}
                                        <button
                                            type="button"
                                            onClick={handleNext}
                                            className="absolute right-4 md:right-6 flex h-10 w-10 md:h-12 md:w-12 items-center justify-center rounded-full bg-white/10 backdrop-blur-md text-white border border-white/20 transition hover:bg-white/25 active:scale-95 cursor-pointer z-20"
                                        >
                                            <ArrowRight size={20} />
                                        </button>
                                    </div>
                                )}
                            </div>

                            {/* Car Info Card */}
                            <div className="order-1 lg:order-2 mt-0 lg:mt-8 bg-white border border-[#E7E9EF] rounded-[24px] p-6 md:p-8 shadow-xs flex flex-col gap-5">
                                {/* Top Row: Brand & Year (right in RTL) and Category Badge (left in RTL) */}
                                <div className="flex items-center justify-between gap-4">
                                    {/* Brand and Model Year */}
                                    <p className="text-start text-[13px] font-extrabold text-[#EDC98E] uppercase tracking-wider leading-none">
                                        {brandName} · {year}
                                    </p>

                                    {/* Category Badge */}
                                    {type && (
                                        <span className="inline-block bg-[#F1F5F9] text-[#64748B] text-[12px] font-bold px-3.5 py-1.5 rounded-full">
                                            {type}
                                        </span>
                                    )}
                                </div>

                                {/* Car Title */}
                                <h1 className="text-start text-[28px] font-black leading-tight text-[#16254F] md:text-[38px]">
                                    {title}
                                </h1>

                                {/* Badges Row */}
                                <div className="flex flex-wrap gap-2.5 justify-start">
                                    {fuelType && (
                                        <span className="inline-flex items-center bg-[#F1F5F9] text-[#475569] text-[13px] font-extrabold px-3.5 py-2 rounded-full">
                                            {fuelType}
                                        </span>
                                    )}
                                    {transmission && (
                                        <span className="inline-flex items-center bg-[#F1F5F9] text-[#475569] text-[13px] font-extrabold px-3.5 py-2 rounded-full">
                                            {transmission}
                                        </span>
                                    )}
                                    {seats && (
                                        <span className="inline-flex items-center gap-1.5 bg-[#F1F5F9] text-[#475569] text-[13px] font-extrabold px-3.5 py-2 rounded-full">
                                            <Users
                                                size={14}
                                                className="text-[#94A3B8]"
                                            />
                                            <span>
                                                {/[a-zA-Z\u0600-\u06FF]/.test(
                                                    seats,
                                                )
                                                    ? seats
                                                    : `${seats} ${i18n.language === "ar" ? "مقاعد" : "Seats"}`}
                                            </span>
                                        </span>
                                    )}
                                    {horsepower && (
                                        <span className="inline-flex items-center bg-[#F1F5F9] text-[#475569] text-[13px] font-extrabold px-3.5 py-2 rounded-full">
                                            {/hp/i.test(horsepower)
                                                ? horsepower
                                                : `${horsepower} HP`}
                                        </span>
                                    )}
                                </div>

                                {/* Description Paragraph */}
                                {description && (
                                    <p className="text-start text-[15px] md:text-[16px] leading-[1.8] text-[#475569] font-medium">
                                        {description}
                                    </p>
                                )}
                            </div>


                        </div>

                        {/* 2. Sidebar Calculator Side (Renders second on mobile, left on desktop) */}
                        <div className="lg:col-span-4 flex flex-col order-2 lg:sticky lg:top-8">
                            {/* Main Finance Calculator Card */}
                            <div className="rounded-[24px] bg-[#16254F] p-7 text-white shadow-xl relative overflow-hidden">
                                <div className="absolute top-0 right-0 w-48 h-48 bg-[#EDC98E]/5 blur-[70px] rounded-full pointer-events-none" />

                                {/* Cash Price Display */}
                                <div className="text-start">
                                    <span className="text-[13px] text-white/50 font-bold block mb-1">
                                        {t("carCard.cashPrice")}
                                    </span>
                                    <strong className="text-[32px] font-black leading-none text-white tracking-tight">
                                        {formatPrice(price, "white")}
                                    </strong>
                                </div>

                                {/* Term months selection */}
                                <div className="mt-7 text-start">
                                    <span className="text-[13px] text-white/50 font-bold block mb-3.5">
                                        {t("carDetails.calculator.monthsTerm", {
                                            defaultValue: "مدة التمويل بالأشهر",
                                        })}
                                    </span>
                                    <div className="flex gap-2">
                                        {[24, 36, 48, 60, 72, 84].map(
                                            (month) => (
                                                <button
                                                    key={month}
                                                    type="button"
                                                    onClick={() =>
                                                        setSelectedMonth(month)
                                                    }
                                                    className={`flex h-9 flex-1 items-center justify-center rounded-xl text-[11px] font-black transition-all duration-300 ${
                                                        selectedMonth === month
                                                            ? "bg-[#EDC98E] text-[#16254F] scale-105 shadow-md"
                                                            : "bg-white/[0.08] hover:bg-white/[0.14] text-white"
                                                    }`}
                                                >
                                                    {month}
                                                </button>
                                            ),
                                        )}
                                    </div>
                                </div>

                                {/* Dynamic Monthly Installment */}
                                <div className="mt-6 rounded-2xl border border-white/[0.08] bg-white/[0.05] p-4 text-center">
                                    <span className="text-[12px] text-white/35 font-normal block">
                                        {t(
                                            "carDetails.calculator.monthlyInstallment",
                                            {
                                                defaultValue:
                                                    "القسط الشهري المقدر",
                                            },
                                        )}
                                    </span>
                                    <div className="mt-1 text-[36px] font-black text-[#EDC98E] leading-none tracking-tight">
                                        {formatPrice(
                                            calculatedInstallment,
                                            "#EDC98E",
                                        )}
                                    </div>
                                    <span className="mt-1 text-[12px] text-white/25 font-normal block">
                                        {t("carDetails.calculator.rateDisclaimer", {
                                            defaultValue: "ريال / شهر · بمعدل أرباح 4.5٪",
                                        })}
                                    </span>
                                </div>

                                {/* Apply buttons */}
                                <a
                                    href={`/contact?car=${encodeURIComponent(brandName + " " + title)}&installment=${calculatedInstallment}&term=${selectedMonth}`}
                                    className="mt-5 flex h-[48px] w-full items-center justify-center rounded-2xl bg-[#EDC98E] text-[14px] font-black text-[#16254F] transition-all duration-300 hover:scale-[1.01] hover:shadow-[0_12px_25px_rgba(237,201,142,0.15)] active:scale-95"
                                >
                                    {t("carDetails.calculator.submit", {
                                        defaultValue: "قدم طلب التمويل الآن",
                                    })}
                                </a>

                                {/* Social Actions Row */}
                                <div className="mt-3.5 grid grid-cols-2 gap-3">
                                    <a
                                        href={`https://wa.me/966500000000?text=أرغب في الاستفسار عن سيارة ${brandName} ${title}`}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="flex h-[46px] items-center justify-center gap-2 rounded-xl bg-[#25D366] text-[14px] font-bold text-white transition hover:bg-[#20ba59] hover:scale-[1.01] active:scale-95"
                                    >
                                        <MessageCircle size={16} />
                                        <span>{t("contact.whatsapp", { defaultValue: "واتساب" })}</span>
                                    </a>
                                    <a
                                        href="tel:+966500000000"
                                        className="flex h-[46px] items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 text-[14px] font-bold text-white transition hover:bg-white/10 hover:scale-[1.01] active:scale-95"
                                    >
                                        <Phone size={16} />
                                        <span>{t("contact.call", { defaultValue: "اتصل" })}</span>
                                    </a>
                                </div>
                            </div>

                            {/* Compare Card */}
                            <a
                                href={`/compare?car1=${encodeURIComponent(title)}`}
                                className="mt-4 flex items-center justify-between rounded-2xl bg-[#F9EEDC] p-4 border border-[#E7E9EF] text-[#16254F] transition-all duration-300 hover:scale-[1.01] hover:shadow-xs group"
                            >
                                <div className="flex items-center gap-3">
                                    <div className="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#FAFAFB] border border-[#E7E9EF] text-[#667085]">
                                        <Scale size={16} />
                                    </div>
                                    <div className="text-start">
                                        <span className="block text-[14px] font-black text-[#16254F]">
                                            {t(
                                                "carDetails.hero.compareWithOther",
                                                {
                                                    defaultValue:
                                                        "قارن مع سيارة أخرى",
                                                },
                                            )}
                                        </span>
                                        <span className="block text-[10px] text-[#16254F]/70">
                                            {t(
                                                "carDetails.hero.compareSelect",
                                                {
                                                    defaultValue:
                                                        "اختر سيارة للمقارنة",
                                                },
                                            )}
                                        </span>
                                    </div>
                                </div>
                                {i18n.dir() === "rtl" ? (
                                    <ArrowLeft
                                        size={16}
                                        className="text-[#16254F] transition-transform duration-300 group-hover:-translate-x-1"
                                    />
                                ) : (
                                    <ArrowRight
                                        size={16}
                                        className="text-[#16254F] transition-transform duration-300 group-hover:translate-x-1"
                                    />
                                )}
                            </a>
                            {/* Disclaimer */}
                            <div className="mt-4 rounded-2xl border border-[#16254F]/20 bg-[#16254F]/[0.08] p-4">
                                <p className="text-start text-[12px] leading-relaxed text-[#16254F]/70 font-normal">
                                    {t("carDetails.hero.disclaimer", {
                                        defaultValue:
                                            "* الأرقام تقديرية بمعدل 4.5٪ سنوياً. التمويل مشروط بموافقة البنك الممول. للحصول على عرض نهائي، تواصل مع فريقنا.",
                                    })}
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
}
