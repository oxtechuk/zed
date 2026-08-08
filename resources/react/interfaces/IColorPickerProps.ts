import type { ICarColor } from "../types/home.types";

export interface IColorPickerProps {
  availableColors: ICarColor[];
  selectedColor: string;
  setSelectedColor: (color: string) => void;
}
