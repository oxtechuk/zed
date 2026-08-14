import { useEffect, useState, useMemo, useRef, FormEvent } from "react";
import { useSearchParams, useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { toast } from "react-toastify";
import { getCars } from "../services/api/cars.service";
import { submitBooking } from "../services/api/booking.service";
import { getBanks } from "../services/api/calculator.service";
import { useLanguageStore } from "../store/language.store";
import { useSettingsStore } from "../store/settings.store";
import type { CarItem } from "../types/home.types";
import type { ICarRequestFormData } from "../interfaces/ICarRequestFormData";
import type { ICarOption } from "../interfaces/ICarOption";
import type { ICarColorOption } from "../interfaces/ICarColorOption";
import {
    SAUDI_CITY_KEYS,
    EMPLOYER_TYPE_KEYS,
    SERVICE_DURATION_KEYS,
    DEFAULT_COLOR_OPTIONS,
} from "../constants/car-request.constants";
import { FALLBACK_WHATSAPP_NUMBER } from "../constants/contact.constants";

export function useCarRequest() {
    const { t } = useTranslation();
    const navigate = useNavigate();
    const [searchParams] = useSearchParams();
    const direction = useLanguageStore((s) => s.direction);
    const settings = useSettingsStore((s) => s.settings);

    // Cars list
    const [cars, setCars] = useState<CarItem[]>([]);
    const [loadingCars, setLoadingCars] = useState(true);

    // Selected Car & Options
    const [selectedCarId, setSelectedCarId] = useState<number>(0);
    const [selectedColor, setSelectedColor] = useState<string>("أبيض");
    const [term, setTerm] = useState<number>(60);
    const [customCarName, setCustomCarName] = useState<string>("");

    // Banks list & selection
    const [banks, setBanks] = useState<any[]>([]);
    const [loadingBanks, setLoadingBanks] = useState(true);
    const [selectedBankId, setSelectedBankId] = useState<number | null>(null);

    // Form State
    const [formData, setFormData] = useState<ICarRequestFormData>({
        fullName: "",
        phone: "",
        city: "",
        employerType: "",
        yearsOfService: 1,
        salary: "",
        obligations: "",
        hasPersonalLoan: false,
        hasMortgageLoan: false,
        hasSimahDefault: false,
        hasTrafficViolations: false,
    });

    const [isSubmitting, setIsSubmitting] = useState(false);
    const [isSuccess, setIsSuccess] = useState(false);
    const [bookingId, setBookingId] = useState<number | null>(null);

    // Dropdown state
    const [isCarDropdownOpen, setIsCarDropdownOpen] = useState(false);
    const carDropdownRef = useRef<HTMLDivElement>(null);

    // Close dropdown on outside click
    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            if (
                carDropdownRef.current &&
                !carDropdownRef.current.contains(event.target as Node)
            ) {
                setIsCarDropdownOpen(false);
            }
        };
        document.addEventListener("mousedown", handleClickOutside);
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, []);

    // Load cars on mount
    useEffect(() => {
        getCars()
            .then((res) => {
                setCars(res.data);
                setLoadingCars(false);

                const carIdParam = searchParams.get("car_id");
                if (carIdParam) {
                    const parsedId = parseInt(carIdParam, 10);
                    if (res.data.some((c) => c.id === parsedId)) {
                        setSelectedCarId(parsedId);
                    }
                } else if (res.data.length > 0) {
                    setSelectedCarId(res.data[0].id);
                }
            })
            .catch(() => {
                setLoadingCars(false);
            });
    }, [searchParams]);

    // Load banks on mount
    useEffect(() => {
        getBanks()
            .then((res) => {
                setBanks(res);
                setLoadingBanks(false);
            })
            .catch(() => {
                setLoadingBanks(false);
            });
    }, []);

    // Active car object
    const activeCar = useMemo(() => {
        if (selectedCarId === 9999) {
            return {
                id: 9999,
                name: t("carRequest.customCar.placeholderName", "طلب سيارة غير مدرجة بالمعرض"),
                slug: "custom-car",
                brand: { name: t("carRequest.customCar.placeholderBrand", "سيارة مخصصة"), logo: "" },
                year: 2026,
                cash_price: 0,
                min_down_payment: 0,
                min_installment: 0,
                is_active: false,
                colors: null,
            } as any;
        }
        return cars.find((c) => c.id === selectedCarId) || null;
    }, [cars, selectedCarId, t]);

    // Installment calculation
    const calculatedInstallment = useMemo(() => {
        if (!activeCar) return 0;
        const baseInstallment =
            activeCar.min_installment ||
            Math.round(activeCar.current_price * 0.02);
        return Math.round((baseInstallment * 60) / term);
    }, [activeCar, term]);

    // Colors list with translated names
    const carColors = useMemo<ICarColorOption[]>(() => {
        if (!activeCar || !activeCar.colors || activeCar.colors.length === 0) {
            return DEFAULT_COLOR_OPTIONS.map((c) => ({
                name: t(`carRequest.colors.${c.key}`, c.name),
                hex: c.hex,
            }));
        }
        return activeCar.colors.map((c) => ({
            name: c.name,
            hex: c.hex || "#CCCCCC",
        }));
    }, [activeCar, t]);

    // Auto select first color when car colors update
    useEffect(() => {
        if (carColors.length > 0) {
            setSelectedColor(carColors[0].name);
        }
    }, [carColors]);

    // Translated select options
    const saudiCities = useMemo<ICarOption[]>(() => {
        return SAUDI_CITY_KEYS.map((city) => ({
            value: city.value,
            label: t(`carRequest.cities.${city.key}`, city.value),
        }));
    }, [t]);

    const employerTypes = useMemo<ICarOption[]>(() => {
        return EMPLOYER_TYPE_KEYS.map((emp) => ({
            value: emp.value,
            label: t(`carRequest.employerTypes.${emp.key}`, emp.value),
        }));
    }, [t]);

    const serviceDurations = useMemo<ICarOption[]>(() => {
        const labels: Record<string, string> = {
            "1Month": "شهر",
            "2Months": "شهران",
            "3Months": "3 أشهر",
            "moreThan3Months": "أكثر من 3 أشهر",
        };
        return SERVICE_DURATION_KEYS.map((dur) => ({
            value: dur.value,
            label: t(`carRequest.serviceDurations.${dur.key}`, labels[dur.key]),
        }));
    }, [t]);

    const handleFormChange = <K extends keyof ICarRequestFormData>(
        key: K,
        value: ICarRequestFormData[K],
    ) => {
        setFormData((prev) => ({ ...prev, [key]: value }));
    };

    const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (!selectedCarId) {
            toast.error(
                t("carRequest.toasts.selectCarFirst", "الرجاء اختيار سيارة أولاً"),
            );
            return;
        }
        if (
            !formData.fullName ||
            !formData.phone ||
            !formData.city ||
            !formData.employerType ||
            !formData.salary ||
            !formData.obligations
        ) {
            toast.error(
                t(
                    "carRequest.toasts.fillRequired",
                    "الرجاء تعبئة جميع الحقول المطلوبة",
                ),
            );
            return;
        }

        if (selectedCarId === 9999 && !customCarName.trim()) {
            toast.error(
                t(
                    "carRequest.toasts.fillCustomCarName",
                    "الرجاء إدخال اسم ومواصفات السيارة المطلوبة",
                ),
            );
            return;
        }

        const saudiPhoneRegex = /^05\d{8}$/;
        if (!saudiPhoneRegex.test(formData.phone)) {
            toast.error(
                t(
                    "carRequest.toasts.invalidPhone",
                    "الرجاء إدخال رقم جوال سعودي صحيح مكون من 10 أرقام ويبدأ بـ 05 (مثال: 05xxxxxxxx)",
                ),
            );
            return;
        }

        setIsSubmitting(true);
        try {
            const activeBank = banks.find((b) => b.id === selectedBankId);
            const bankDetails = activeBank ? activeBank.name : 'غير محدد';

            const carDetailsText = selectedCarId === 9999
                ? `طلب سيارة مخصصة: ${customCarName}`
                : (activeCar ? `${activeCar.brand?.name} ${activeCar.name}` : '');

            const notesText = `${carDetailsText} | البنك المفضل: ${bankDetails} | مدة التمويل: ${term} month | اللون المطلوب: ${selectedColor} | جهة العمل: ${formData.employerType} | مدة الخدمة بالوظيفة: ${formData.yearsOfService} شهر | الراتب: ${formData.salary} | الالتزامات: ${formData.obligations}`;

            const bookingResponse = await submitBooking({
                car_id: selectedCarId,
                client_name: formData.fullName,
                client_phone: formData.phone,
                duration_years: Math.round(term / 12),
                notes: notesText,
                booking_type: "purchase",
                location: formData.city,
                calculator_bank_id: selectedBankId,
            });

            setBookingId(bookingResponse?.data?.booking_id ?? bookingResponse?.booking_id ?? null);
            setIsSuccess(true);
            toast.success(
                t(
                    "carRequest.toasts.submitSuccess",
                    "تم إرسال طلبك بنجاح! سيتواصل معك أحد مستشارينا قريباً.",
                ),
            );
        } catch (error: any) {
            console.error("Submission error:", error);
            const apiMessage = error.response?.data?.message;
            toast.error(
                apiMessage ||
                    t(
                        "carRequest.toasts.submitError",
                        "حدث خطأ أثناء إرسال الطلب، يرجى المحاولة لاحقاً",
                    ),
            );
        } finally {
            setIsSubmitting(false);
        }
    };

    const carLabel = selectedCarId === 9999
        ? customCarName
        : (activeCar
            ? `${activeCar.brand?.name || ""} ${activeCar.name}`.trim()
            : "");
    const whatsappRaw = settings?.contact?.whatsapp || settings?.contact?.phone || FALLBACK_WHATSAPP_NUMBER;
    const whatsappNum = whatsappRaw.replace(/\D/g, "");
    const bookingRef = bookingId ? `#${bookingId}` : "";
    const whatsappDefaultMsg = bookingId
        ? `مرحباً، أنا ${formData.fullName} أرغب في متابعة طلب التمويل رقم ${bookingRef} لسيارة ${carLabel}.`
        : `مرحباً، أرغب في متابعة طلب التمويل المرسل لسيارة ${carLabel}.`;
    const whatsappMsg = encodeURIComponent(whatsappDefaultMsg);
    const whatsappHref = whatsappNum
        ? `https://wa.me/${whatsappNum}?text=${whatsappMsg}`
        : "#";

    return {
        direction,
        cars,
        loadingCars,
        selectedCarId,
        setSelectedCarId,
        activeCar,
        selectedColor,
        setSelectedColor,
        term,
        setTerm,
        customCarName,
        setCustomCarName,
        banks,
        loadingBanks,
        selectedBankId,
        setSelectedBankId,
        formData,
        handleFormChange,
        isSubmitting,
        isSuccess,
        isCarDropdownOpen,
        setIsCarDropdownOpen,
        carDropdownRef,
        calculatedInstallment,
        carColors,
        saudiCities,
        employerTypes,
        serviceDurations,
        handleSubmit,
        carLabel,
        whatsappHref,
        onBackToCars: () => navigate("/cars"),
    };
}
