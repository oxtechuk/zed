import type { RefObject, Dispatch, SetStateAction } from "react";
import type { CarItem } from "../types/home.types";

export interface ICarDropdownSelectorProps {
    cars: CarItem[];
    activeCar: CarItem | null;
    loadingCars: boolean;
    selectedCarId: number;
    onSelectCarId: (id: number) => void;
    isCarDropdownOpen: boolean;
    setIsCarDropdownOpen: Dispatch<SetStateAction<boolean>>;
    carDropdownRef: RefObject<HTMLDivElement | null>;
}
