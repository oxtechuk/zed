import { useState, useMemo } from "react";
import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
import { getBlogs } from "../services/api";
import { useLanguageStore } from "../store/language.store";
import { postToCardProps } from "../utils/blog";
import { useSEO } from "../utils/useSEO";
import type { IBlogFilterCategory } from "../interfaces/IBlogFilterCategory";

export function useBlogsPage() {
  const { t, i18n } = useTranslation();
  useSEO(t("nav.blog"), t("blogPage.hero.description"));
  const language = useLanguageStore((s) => s.language);
  const isRTL = i18n.dir() === "rtl";

  const [activeCategory, setActiveCategory] = useState("all");
  const [page, setPage] = useState(1);

  const PAGE_SIZE = 6;

  const { data: blogResponse, isLoading } = useQuery({
    queryKey: ["blogs", language, page, activeCategory, PAGE_SIZE],
    queryFn: () => getBlogs(page, PAGE_SIZE, activeCategory),
  });

  const categories: IBlogFilterCategory[] = useMemo(() => {
    const defaultAll: IBlogFilterCategory = {
      label: t("blogPage.categories.all", { defaultValue: language === "ar" ? "الكل" : "All" }),
      value: "all",
    };
    if (!blogResponse?.meta?.categories || blogResponse.meta.categories.length === 0) {
      return [defaultAll];
    }
    const backendCategories = blogResponse.meta.categories.map((cat) => ({
      label: cat.name,
      value: cat.slug,
    }));
    return [defaultAll, ...backendCategories];
  }, [blogResponse?.meta?.categories, language, t]);

  const articles = useMemo(() => {
    if (!blogResponse?.data) return [];
    return blogResponse.data.map((post) => postToCardProps(post, language, t));
  }, [blogResponse, language, t]);

  const handleCategoryChange = (category: string) => {
    setActiveCategory(category);
    setPage(1);
  };

  const handlePageChange = (newPage: number) => {
    setPage(newPage);
  };

  const lastPage = blogResponse?.meta?.last_page || 1;
  const currentPage = blogResponse?.meta?.current_page || 1;

  return {
    t,
    i18n,
    isRTL,
    language,
    activeCategory,
    categories,
    articles,
    isLoading,
    currentPage,
    lastPage,
    handleCategoryChange,
    handlePageChange,
  };
}
