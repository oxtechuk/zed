import type { IBlogDetails } from "./IBlogDetails";

export interface IBlogDetailsApiResponse {
  success: boolean;
  message: string;
  data: IBlogDetails;
  errors: null;
  meta: null;
}
