import type { IBlogMeta } from "./IBlogMeta";
import type { IBlogPost } from "./IBlogPost";

export interface IBlogApiResponse {
  success: boolean;
  message: string;
  data: IBlogPost[];
  errors: null;
  meta: IBlogMeta;
}
