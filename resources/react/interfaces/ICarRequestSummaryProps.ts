import type { CarItem } from "../types/home.types";
import type { ICarColorOption } from "./ICarColorOption";

export interface ICarRequestSummaryProps {
    cars: CarItem[];
    activeCar: CarItem | null;
    loadingCars: boolean;
    selectedCarId: number;
    onSelectCarId: (id: number) => void;
    selectedColor: string;
    onSelectColor: (color: string) => void;
    term: number;
    onChangeTerm: (term: number) => void;
    calculatedInstallment: number;
    carColors: ICarColorOption[];
    customCarName?: string;
    onCustomCarNameChange?: (name: string) => void;
}
