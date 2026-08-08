import { APP_IMAGES, getImageUrl } from "../constants/app-images";
import type { IBlogPost } from "../interfaces/IBlogPost";
import type { IBlogArticleSection } from "../interfaces/IBlogArticleSection";

export function formatBlogDate(iso: string, language: string): string {
  const date = new Date(iso);
  const locale = language === "ar" ? "ar-SA" : "en-US";
  return date.toLocaleDateString(locale, {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}

export function formatBlogReadTime(
  minutes: number,
  language: string,
  t?: (key: string, options?: Record<string, unknown>) => string
): string {
  if (t) {
    if (minutes < 1) return t("blogPage.details.readTime.lessThanMinute");
    if (minutes === 1) return t("blogPage.details.readTime.oneMinute");
    if (minutes === 2) return t("blogPage.details.readTime.twoMinutes");
    return t("blogPage.details.readTime.minutes", { count: minutes });
  }

  if (language === "ar") {
    if (minutes < 1) return "أقل من دقيقة";
    if (minutes === 1) return "دقيقة واحدة";
    if (minutes === 2) return "دقيقتان";
    return `${minutes} دقائق`;
  }
  return `${minutes} min read`;
}

export function postToCardProps(
  post: IBlogPost,
  language: string,
  t: (key: string, options?: Record<string, unknown>) => string
) {
  return {
    id: post.id,
    image: getImageUrl(post.thumbnail) || APP_IMAGES.BLOG_PLACEHOLDER,
    category:
      post.categories.map((c) => c.name).join(", ") ||
      t("blogPage.hero.featuredPost.category"),
    categorySlug:
      post.categories.map((c) => c.slug).join(", "),
    date: formatBlogDate(post.published_at, language),
    readTime: formatBlogReadTime(post.reading_time, language, t),
    title: post.title || t("blogPage.hero.featuredPost.title"),
    description: post.excerpt || t("blogPage.hero.featuredPost.description"),
    authorName:
      post.employee?.name || t("blogPage.hero.featuredPost.author.name"),
    authorRole:
      post.employee?.role || t("blogPage.hero.featuredPost.author.role"),
    authorImage:
      getImageUrl(post.employee?.avatar ?? null) || APP_IMAGES.BLOG_AUTHOR_PLACEHOLDER,
    readMoreTo: `/blog/${post.slug}`,
    tag: post.tag ?? undefined,
  };
}

export function parseBlogContent(content: string): IBlogArticleSection[] {
  const blocks = content.split(/\r?\n\r?\n/).filter(Boolean);
  const sections: IBlogArticleSection[] = [];
  let currentSection: IBlogArticleSection | null = null;

  for (const block of blocks) {
    const lines = block.split(/\r?\n/).filter(Boolean);
    const firstLine = lines[0].trim();
    const isTitle =
      /^\d+\.\s/.test(firstLine) ||
      (/^[^.!?]{1,30}:\s*$/.test(firstLine) && lines.length > 0);

    if (isTitle) {
      currentSection = {
        title: firstLine,
        highlight: true,
        paragraphs: [],
        list: [],
      };
      for (let i = 1; i < lines.length; i++) {
        currentSection.paragraphs.push(lines[i].trim());
      }
      sections.push(currentSection);
    } else if (currentSection) {
      if (lines.length > 1) {
        if (!currentSection.list) currentSection.list = [];
        currentSection.list.push(...lines.map((l) => l.trim()));
      } else {
        currentSection.paragraphs.push(firstLine);
      }
    } else {
      currentSection = { paragraphs: lines.map((l) => l.trim()) };
      sections.push(currentSection);
    }
  }

  return sections;
}
