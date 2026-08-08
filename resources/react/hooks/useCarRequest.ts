import { useEffect, useState, useMemo, useRef, FormEvent } from "react";
import { useSearchParams, useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { toast } from "react-toastify";
import { getCars } from "../services/api/cars.service";
import { submitCalculatorLead } from "../services/api/calculator.service";
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

    // Active car object
    const activeCar = useMemo(() => {
        return cars.find((c) => c.id === selectedCarId) || null;
    }, [cars, selectedCarId]);

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
        return SERVICE_DURATION_KEYS.map((dur) => ({
            value: dur.value,
            label: t(`carRequest.serviceDurations.${dur.key}`),
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

        setIsSubmitting(true);
        try {
            const notesText = t(
                "carRequest.notesTemplate",
                "طلب سيارة مباشر | مدة التمويل: {{term}} شهر | القسط المقدر: {{installment}} ريال",
                { term, installment: calculatedInstallment },
            );

            await submitCalculatorLead({
                name: formData.fullName,
                phone: formData.phone,
                email: `${formData.phone}@zed.com`,
                city: formData.city,
                salary: Number(formData.salary),
                monthly_obligations: Number(formData.obligations),
                car_ids: [selectedCarId],
                preferred_color: selectedColor,
                employer_type: formData.employerType,
                years_of_service: formData.yearsOfService,
                has_personal_loan: formData.hasPersonalLoan,
                has_mortgage_loan: formData.hasMortgageLoan,
                has_simah_default: formData.hasSimahDefault,
                has_traffic_violations: formData.hasTrafficViolations,
                notes: notesText,
            });

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

    const carLabel = activeCar
        ? `${activeCar.brand?.name || ""} ${activeCar.name}`.trim()
        : "";
    const whatsappNum = settings?.contact?.whatsapp?.replace(/\D/g, "") ?? "";
    const whatsappDefaultMsg = t(
        "carRequest.whatsappMsg",
        "مرحباً، أرسلت طلب سيارة مخصصة ({{carLabel}}) وأود المتابعة.",
        { carLabel },
    );
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
