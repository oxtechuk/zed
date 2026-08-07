import type { IBlogCategory } from "./IBlogCategory";
import type { IBlogEmployee } from "./IBlogEmployee";

export interface IBlogPost {
  id: number;
  title: string;
  slug: string;
  thumbnail: string | null;
  excerpt: string;
  published_at: string;
  employee: IBlogEmployee;
  categories: IBlogCategory[];
  reading_time: number;
  tag?: string | null;
}
