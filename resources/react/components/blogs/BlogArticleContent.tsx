import { useTranslation } from "react-i18next";
import type { IBlogArticleContentProps } from "../../interfaces/IBlogArticleContentProps";
import BlogArticleHTML from "./BlogArticleHTML";
import BlogArticleSections from "./BlogArticleSections";

export default function BlogArticleContent({
  sections,
  content,
}: IBlogArticleContentProps) {
  const { i18n } = useTranslation();
  
  const hasHTML = Boolean(
    content && (
      content.includes("<p") || 
      content.includes("<div") || 
      content.includes("<h") || 
      content.includes("<ul") || 
      content.includes("<ol") || 
      content.includes("<span") ||
      content.includes("<strong")
    )
  );

  return (
    <section dir={i18n.dir()} className=" bg-[#F0F2F5] py-8">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {hasHTML && content ? (
          <BlogArticleHTML content={content} />
        ) : (
          <BlogArticleSections sections={sections} />
        )}
      </div>
    </section>
  );
}
