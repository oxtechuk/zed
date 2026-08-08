import type { CarItem, ICarColor } from "../types/home.types";
import type { ISelectedCar } from "./ISelectedCar";

export interface IStepTwoCarSelectorProps {
  selectedCarId: number;
  selectedCar: ISelectedCar;
  onCarSelect: (car: CarItem) => void;
  colors: ICarColor[];
  selectedColor: string;
  setSelectedColor: (color: string) => void;
  onNext: () => void;
  onBack: () => void;
}
