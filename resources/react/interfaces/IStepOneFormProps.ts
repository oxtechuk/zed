import type { IPersonalInfo } from "./IPersonalInfo";

export interface IStepOneFormProps {
  onNext: (info: IPersonalInfo) => void;
}
