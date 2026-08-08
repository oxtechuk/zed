import type { ISubjectOption } from "./ISubjectOption";

export interface ISubjectChipsProps {
  value: string;
  options: ISubjectOption[];
  onChange: (value: string) => void;
}
