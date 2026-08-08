import type { ICarColorOption } from "../interfaces/ICarColorOption";

export const SAUDI_CITY_KEYS = [
    { value: "الرياض", key: "riyadh" },
    { value: "جدة", key: "jeddah" },
    { value: "الدمام", key: "dammam" },
    { value: "مكة المكرمة", key: "makkah" },
    { value: "المدينة المنورة", key: "madinah" },
    { value: "الخبر", key: "khobar" },
    { value: "الجبيل", key: "jubail" },
    { value: "الهفوف", key: "hofuf" },
    { value: "الطائف", key: "taif" },
    { value: "تبوك", key: "tabuk" },
    { value: "بريدة", key: "buraidah" },
    { value: "خميس مشيط", key: "khamis_mushait" },
    { value: "حائل", key: "hail" },
    { value: "نجران", key: "najran" },
    { value: "أبها", key: "abha" },
    { value: "جيزان", key: "jizan" },
] as const;

export const EMPLOYER_TYPE_KEYS = [
    { value: "government", key: "government" },
    { value: "military", key: "military" },
    { value: "company", key: "company" },
    { value: "institution", key: "institution" },
    { value: "retired", key: "retired" },
] as const;

export const SERVICE_DURATION_KEYS = [
    { value: 0.5, key: "under1Year" },
    { value: 1, key: "1Year" },
    { value: 2, key: "2Years" },
    { value: 3, key: "3YearsPlus" },
] as const;

export const DEFAULT_COLOR_OPTIONS: (ICarColorOption & { key: string })[] = [
    { name: "كحلي", key: "navy", hex: "#1E293B" },
    { name: "رمادي", key: "gray", hex: "#6B7280" },
    { name: "فضي", key: "silver", hex: "#D1D5DB" },
    { name: "أسود", key: "black", hex: "#111827" },
    { name: "أبيض", key: "white", hex: "#FFFFFF" },
    { name: "أخضر داكن", key: "darkGreen", hex: "#064E3B" },
    { name: "ذهبي", key: "gold", hex: "#EDC98E" },
    { name: "أزرق", key: "blue", hex: "#3B82F6" },
    { name: "أحمر", key: "red", hex: "#EF4444" },
];

export const FINANCE_TERMS = [24, 36, 48, 60] as const;
