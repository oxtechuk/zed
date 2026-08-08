import { useMemo } from "react";
import BlogHeroSection from "../components/blogs/BlogHeroSection";
import BlogCategoryFilter from "../components/blogs/BlogCategoryFilter";
import BlogGrid from "../components/blogs/BlogGrid";
import BlogPagination from "../components/blogs/BlogPagination";
import BlogsPageSkeleton from "../components/skeletons/BlogsPageSkeleton";
import { useBlogsPage } from "../hooks/useBlogsPage";
import { usePageImagesReady } from "../hooks/usePageImagesReady";

export default function BlogsPage() {
  const {
    i18n,
    isRTL,
    activeCategory,
    categories,
    articles,
    isLoading,
    currentPage,
    lastPage,
    handleCategoryChange,
    handlePageChange,
  } = useBlogsPage();

  const imageUrls = useMemo(
    () => articles.map((article) => article.image).filter(Boolean) as string[],
    [articles],
  );
  const imagesReady = usePageImagesReady(isLoading, imageUrls);

  if (isLoading || !imagesReady) {
    return <BlogsPageSkeleton />;
  }

  return (
    <main dir={i18n.dir()} className="min-h-screen bg-[#FAFAFB]">
      {/* ── Page Header ── */}
      <BlogHeroSection />

      {/* ── Filter Category Tabs ── */}
      <BlogCategoryFilter
        categories={categories}
        activeCategory={activeCategory}
        onCategoryChange={handleCategoryChange}
      />

      {/* ── Blog Grid & Pagination ── */}
      <section className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 pb-20">
        <BlogGrid articles={articles} isLoading={isLoading} />
        {!isLoading && (
          <BlogPagination
            currentPage={currentPage}
            lastPage={lastPage}
            onPageChange={handlePageChange}
            isRTL={isRTL}
          />
        )}
      </section>
    </main>
  );
}
