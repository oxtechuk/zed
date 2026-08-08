import type { ISelectedCar } from "./ISelectedCar";
import type { IPersonalInfo } from "./IPersonalInfo";

export interface IStepTwoCalculatorProps {
  selectedCar: ISelectedCar;
  selectedColor: string;
  salary: number;
  setSalary: (val: number) => void;
  downPayment: number;
  setDownPayment: (val: number) => void;
  term: number;
  setTerm: (val: number) => void;
  personalInfo: IPersonalInfo;
  carId: number;
  onBack: () => void;
  onSubmitSuccess: () => void;
}
