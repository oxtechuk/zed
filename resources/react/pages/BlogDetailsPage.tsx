import { useMemo } from "react";
import BlogArticleContent from "../components/blogs/BlogArticleContent";
import BlogDetailsError from "../components/blogs/BlogDetailsError";
import BlogDetailsHero from "../components/blogs/BlogDetailsHero";
import RelatedArticlesSection from "../components/blogs/RelatedArticlesSection";
import BlogDetailsPageSkeleton from "../components/skeletons/BlogDetailsPageSkeleton";
import { useBlogDetailsPage } from "../hooks/useBlogDetailsPage";
import { usePageImagesReady } from "../hooks/usePageImagesReady";

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

  const imageUrls = useMemo(() => {
    const related = relatedArticles.map((article) => article.image).filter(Boolean);
    return [heroImage, ...related] as string[];
  }, [heroImage, relatedArticles]);

  const imagesReady = usePageImagesReady(isLoading, imageUrls);

  if (isLoading || !imagesReady) {
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
