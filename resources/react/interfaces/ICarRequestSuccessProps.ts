import type { CarItem } from "../types/home.types";

export interface ICarRequestSuccessProps {
    activeCar: CarItem | null;
    phone: string;
    whatsappHref: string;
    onBackToCars: () => void;
    direction: "rtl" | "ltr";
}
