import type { IBranchInfo } from "./IBranchInfo";
import type { IContactInfo } from "./IContactInfo";

export interface IUseContactInfoResult {
  contact: IContactInfo;
  branches: IBranchInfo[];
}
