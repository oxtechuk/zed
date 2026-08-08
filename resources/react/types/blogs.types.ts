import type { IBlogApiResponse } from "../interfaces/IBlogApiResponse";
import type { IBlogCategory } from "../interfaces/IBlogCategory";
import type { IBlogDetails } from "../interfaces/IBlogDetails";
import type { IBlogDetailsApiResponse } from "../interfaces/IBlogDetailsApiResponse";
import type { IBlogEmployee } from "../interfaces/IBlogEmployee";
import type { IBlogHero } from "../interfaces/IBlogHero";
import type { IBlogMeta } from "../interfaces/IBlogMeta";
import type { IBlogPost } from "../interfaces/IBlogPost";

export type {
  IBlogCategory,
  IBlogEmployee,
  IBlogPost,
  IBlogHero,
  IBlogMeta,
  IBlogApiResponse,
  IBlogDetails,
  IBlogDetailsApiResponse,
};

// Legacy type aliases starting without I for backward compatibility
export type BlogEmployee = IBlogEmployee;
export type BlogCategory = IBlogCategory;
export type BlogPost = IBlogPost;
export type BlogHero = IBlogHero;
export type BlogMeta = IBlogMeta;
export type BlogApiResponse = IBlogApiResponse;
export type BlogDetails = IBlogDetails;
export type BlogDetailsApiResponse = IBlogDetailsApiResponse;

