import type { IBrandInfo } from "../types/home.types";
import type { FilterValues } from "../types/cars.types";

export interface IFilterDrawerModalProps {
  isOpen: boolean;
  onClose: () => void;
  filters: FilterValues;
  onApply: (filters: FilterValues) => void;
  brands: IBrandInfo[];
  fuelOptions: string[];
  maxPriceLimit?: number;
}
