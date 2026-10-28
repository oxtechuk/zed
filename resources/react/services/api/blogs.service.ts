import type { BlogApiResponse, BlogDetailsApiResponse, BlogDetails } from "../../types/blogs.types";
import type { IBlogPost } from "../../interfaces/IBlogPost";
import api from "./http";

export async function getBlogs(
  page = 1,
  perPage = 3,
  category?: string,
  tag?: string
): Promise<BlogApiResponse> {
  const response = await api.get<BlogApiResponse>("store/blog", {
    params: {
      page,
      per_page: perPage,
      category: category === "all" ? undefined : category,
      tag: tag === "all" ? undefined : tag,
    },
  });
  return response.data;
}

export async function getBlogBySlug(slug: string): Promise<BlogDetails> {
  const response = await api.get<BlogDetailsApiResponse>(`store/blog/${slug}`);
  return response.data.data;
}

/** Published articles that link to the car page. */
export async function getCarArticles(carSlug: string): Promise<IBlogPost[]> {
  const response = await api.get<{ success: boolean; data: IBlogPost[] }>(
    `store/cars/${encodeURIComponent(carSlug)}/articles`,
  );
  return response.data.data ?? [];
}
