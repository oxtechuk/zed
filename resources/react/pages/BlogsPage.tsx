import { useState, useMemo } from "react";
import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
import { ChevronLeft, ChevronRight } from "lucide-react";
import BlogCard from "../components/blogs/BlogCard";
import OffersPageHero from "../components/offers-page/OffersPageHero";
import { getBlogs } from "../services/api";
import { useLanguageStore } from "../store/language.store";
import { postToCardProps } from "../utils/blog";
import { useSEO } from "../utils/useSEO";
import LoadingSpinner from "../components/LoadingSpinner";
import { APP_IMAGES } from "../constants/app-images";

export default function BlogsPage() {
  const { t, i18n } = useTranslation();
  useSEO(t("nav.blog"), t("blogPage.hero.description"));
  const language = useLanguageStore((s) => s.language);
  const isRTL = i18n.dir() === "rtl";
  
  const [activeCategory, setActiveCategory] = useState("all");
  const [page, setPage] = useState(1);

  // Categories based on tags as shown in the screenshot
  const categories = useMemo(() => [
    { label: language === "ar" ? "الكل" : "All", value: "all" },
    { label: language === "ar" ? "شائع" : "Popular", value: "popular" },
    { label: language === "ar" ? "حصري" : "Exclusive", value: "exclusive" },
    { label: language === "ar" ? "جديد" : "New", value: "new" },
    { label: language === "ar" ? "محدود" : "Limited", value: "limited" },
  ], [language]);

  const { data: blogResponse, isLoading } = useQuery({
    queryKey: ["blogs", language, page, activeCategory],
    queryFn: () => getBlogs(page, 9, activeCategory),
  });

  const articles = useMemo(() => {
    if (!blogResponse?.data) return [];
    return blogResponse.data.map((post) => postToCardProps(post, language, t));
  }, [blogResponse, language, t]);

  const handleCategoryChange = (category: string) => {
    setActiveCategory(category);
    setPage(1);
  };

  const lastPage = blogResponse?.meta?.last_page || 1;
  const currentPage = blogResponse?.meta?.current_page || 1;

  const renderPagination = () => {
    if (lastPage <= 1) return null;

    const pages = [];
    if (lastPage <= 5) {
      for (let i = 1; i <= lastPage; i++) pages.push(i);
    } else {
      if (currentPage <= 3) {
        pages.push(1, 2, 3, "...", lastPage);
      } else if (currentPage >= lastPage - 2) {
        pages.push(1, "...", lastPage - 2, lastPage - 1, lastPage);
      } else {
        pages.push(1, "...", currentPage - 1, currentPage, currentPage + 1, "...", lastPage);
      }
    }

    return (
      <div className="mt-14 flex items-center justify-center gap-2" dir="ltr">
        {/* Previous page arrow */}
        <button
          onClick={() => currentPage > 1 && setPage(currentPage - 1)}
          disabled={currentPage === 1}
          className="w-10 h-10 rounded-full flex items-center justify-center border border-[#E7E9EF] bg-white text-gray-500 hover:bg-[#16254F] hover:text-white disabled:opacity-40 disabled:hover:bg-white disabled:hover:text-gray-500 transition cursor-pointer"
        >
          {isRTL ? <ChevronRight size={18} /> : <ChevronLeft size={18} />}
        </button>

        {/* Page buttons */}
        {pages.map((p, idx) => {
          if (p === "...") {
            return (
              <span
                key={`dots-${idx}`}
                className="w-10 h-10 flex items-center justify-center text-gray-400 font-bold"
              >
                ...
              </span>
            );
          }
          const isActive = p === currentPage;
          return (
            <button
              key={`page-${p}`}
              onClick={() => setPage(p as number)}
              className={`w-10 h-10 rounded-full flex items-center justify-center border text-sm font-bold transition cursor-pointer ${
                isActive
                  ? "bg-[#16254F] border-[#16254F] text-white"
                  : "bg-white border-[#E7E9EF] text-gray-700 hover:bg-[#16254F] hover:text-white"
              }`}
            >
              {p}
            </button>
          );
        })}

        {/* Next page arrow */}
        <button
          onClick={() => currentPage < lastPage && setPage(currentPage + 1)}
          disabled={currentPage === lastPage}
          className="w-10 h-10 rounded-full flex items-center justify-center border border-[#E7E9EF] bg-white text-gray-500 hover:bg-[#16254F] hover:text-white disabled:opacity-40 disabled:hover:bg-white disabled:hover:text-gray-500 transition cursor-pointer"
        >
          {isRTL ? <ChevronLeft size={18} /> : <ChevronRight size={18} />}
        </button>
      </div>
    );
  };

  return (
    <main dir={i18n.dir()} className="min-h-screen bg-[#FAFAFB]">
      {/* ── Page Header ── */}
      <OffersPageHero
        image={APP_IMAGES.BLOG_PLACEHOLDER}
        badgeText={t("nav.blog", { defaultValue: "المدونة" })}
        title={t("blogPage.hero.title", { defaultValue: "نصائح ومقالات" })}
        description={t("blogPage.hero.description", { defaultValue: "فريقنا المتخصص جاهز للرد على جميع استفساراتك" })}
      />

      {/* ── Filter Category Tabs ── */}
      <section className="pt-10 pb-4">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="flex flex-wrap items-center justify-center gap-2">
            {categories.map((category) => {
              const isActive = category.value === activeCategory;
              return (
                <button
                  key={category.value}
                  type="button"
                  onClick={() => handleCategoryChange(category.value)}
                  className={`h-[36px] rounded-full px-4 text-[14px] font-bold transition duration-200 cursor-pointer ${
                    isActive
                      ? "bg-[#16254F] text-white"
                      : "border border-[#E7E9EF] bg-white text-[#667085] hover:bg-[#16254F] hover:text-white hover:border-[#16254F]"
                  }`}
                >
                  {category.label}
                </button>
              );
            })}
          </div>
        </div>
      </section>

      {/* ── Blog Grid ── */}
      <section className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 pb-20">
        {isLoading ? (
          <div className="py-24 flex justify-center">
            <LoadingSpinner />
          </div>
        ) : articles.length > 0 ? (
          <>
            <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
              {articles.map((article) => (
                <BlogCard key={article.id} {...article} />
              ))}
            </div>

            {/* Pagination Component */}
            {renderPagination()}
          </>
        ) : (
          <div className="py-24 text-center">
            <p className="text-lg font-medium text-gray-400">
              {t("blogPage.noArticles", { defaultValue: "لا توجد مقالات متوفرة حالياً." })}
            </p>
          </div>
        )}
      </section>
    </main>
  );
}
