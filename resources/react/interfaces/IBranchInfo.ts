import type { IWorkingHourLine } from "./IWorkingHourLine";

export interface IBranchInfo {
  name: string;
  address: string;
  mapLink: string;
  workingHours: IWorkingHourLine[];
}
