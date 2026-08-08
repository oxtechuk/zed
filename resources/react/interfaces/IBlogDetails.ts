import type { IBlogPost } from "./IBlogPost";

export interface IBlogDetails extends IBlogPost {
  content: string;
  meta_title: string | null;
  meta_description: string | null;
  meta_keywords: string | null;
  is_featured: boolean;
  related_posts: IBlogPost[];
}
