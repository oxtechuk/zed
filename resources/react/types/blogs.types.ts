import type { IBlogApiResponse } from "../interfaces/IBlogApiResponse";
import type { IBlogCategory } from "../interfaces/IBlogCategory";
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
};

// Legacy type aliases starting without I for backward compatibility
export type BlogEmployee = IBlogEmployee;
export type BlogCategory = IBlogCategory;
export type BlogPost = IBlogPost;
export type BlogHero = IBlogHero;
export type BlogMeta = IBlogMeta;
export type BlogApiResponse = IBlogApiResponse;

export interface IBlogDetails extends IBlogPost {
  content: string;
  meta_title: string | null;
  meta_description: string | null;
  meta_keywords: string | null;
  is_featured: boolean;
  related_posts: IBlogPost[];
}

export type BlogDetails = IBlogDetails;

export interface IBlogDetailsApiResponse {
  success: boolean;
  message: string;
  data: IBlogDetails;
  errors: null;
  meta: null;
}

export type BlogDetailsApiResponse = IBlogDetailsApiResponse;
