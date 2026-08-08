import { useMemo } from "react";
import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
import { useParams } from "react-router-dom";
import { getBlogBySlug } from "../services/api";
import { useLanguageStore } from "../store/language.store";
import { APP_IMAGES, getImageUrl } from "../constants/app-images";
import { formatBlogDate, formatBlogReadTime, parseBlogContent, postToCardProps } from "../utils/blog";
import { useSEO } from "../utils/useSEO";
import type { IBlogPost } from "../interfaces/IBlogPost";
import type { IBlogCategory } from "../interfaces/IBlogCategory";

export function useBlogDetailsPage() {
  const { t, i18n } = useTranslation();
  useSEO(t("nav.blog"), t("blogPage.details.metaDescription"));
  const { slug } = useParams<{ slug: string }>();
  const language = useLanguageStore((s) => s.language);

  const {
    data: blog,
    isLoading,
    isError,
  } = useQuery({
    queryKey: ["blog", slug, language],
    queryFn: () => getBlogBySlug(slug!),
    enabled: Boolean(slug),
  });

  const date = useMemo(
    () => (blog ? formatBlogDate(blog.published_at, language) : ""),
    [blog, language]
  );

  const readTime = useMemo(
    () => (blog ? formatBlogReadTime(blog.reading_time, language, t) : ""),
    [blog, language, t]
  );

  const sections = useMemo(
    () => (blog ? parseBlogContent(blog.content) : []),
    [blog]
  );

  const relatedArticles = useMemo(
    () =>
      blog?.related_posts?.map((post: IBlogPost) =>
        postToCardProps(post, language, t)
      ) ?? [],
    [blog, language, t]
  );

  const category = useMemo(
    () => (blog?.categories?.map((c: IBlogCategory) => c.name).join(", ") || ""),
    [blog]
  );

  const authorName = useMemo(
    () => blog?.employee?.name || t("blogPage.hero.featuredPost.author.name"),
    [blog, t]
  );

  const authorRole = useMemo(
    () => blog?.employee?.role || t("blogPage.hero.featuredPost.author.role"),
    [blog, t]
  );

  const authorImage = useMemo(
    () => getImageUrl(blog?.employee?.avatar ?? null) || APP_IMAGES.BLOG_AUTHOR_PLACEHOLDER,
    [blog]
  );

  const heroImage = useMemo(
    () => getImageUrl(blog?.thumbnail ?? null) || APP_IMAGES.BLOG_PLACEHOLDER,
    [blog]
  );

  return {
    blog,
    category,
    authorName,
    authorRole,
    authorImage,
    heroImage,
    date,
    readTime,
    sections,
    relatedArticles,
    isLoading,
    isError,
    dir: i18n.dir(),
    t,
  };
}
