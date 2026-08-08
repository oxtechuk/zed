import type { ICarColorOption } from "./ICarColorOption";

export interface ICarColorPickerProps {
    carColors: ICarColorOption[];
    selectedColor: string;
    onSelectColor: (color: string) => void;
}
