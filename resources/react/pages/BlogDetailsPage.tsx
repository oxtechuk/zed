import { useMemo } from "react";
import BlogArticleContent from "../components/blogs/BlogArticleContent";
import BlogDetailsError from "../components/blogs/BlogDetailsError";
import BlogDetailsHero from "../components/blogs/BlogDetailsHero";
import RelatedArticlesSection from "../components/blogs/RelatedArticlesSection";
import BlogDetailsPageSkeleton from "../components/skeletons/BlogDetailsPageSkeleton";
import { useBlogDetailsPage } from "../hooks/useBlogDetailsPage";

export default function BlogDetailsPage() {
  const {
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
    t,
  } = useBlogDetailsPage();

  if (isLoading) {
    return <BlogDetailsPageSkeleton />;
  }

  if (isError || !blog) {
    return <BlogDetailsError />;
  }

  return (
    <>
      <BlogDetailsHero
        category={category}
        title={blog.title}
        authorName={authorName}
        authorRole={authorRole}
        authorImage={authorImage}
        date={date}
        readTime={readTime}
        image={heroImage}
      />

      <BlogArticleContent sections={sections} content={blog.content} />

      {relatedArticles.length > 0 && (
        <RelatedArticlesSection
          title={t("blogPage.details.related.title")}
          articles={relatedArticles}
        />
      )}
    </>
  );
}
