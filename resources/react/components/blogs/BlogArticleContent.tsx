import { useTranslation } from "react-i18next";
import type { IBlogArticleContentProps } from "../../interfaces/IBlogArticleContentProps";

export default function BlogArticleContent({
  sections,
  content,
}: IBlogArticleContentProps) {
  const { i18n } = useTranslation();
  
  const hasHTML = content && (
    content.includes("<p") || 
    content.includes("<div") || 
    content.includes("<h") || 
    content.includes("<ul") || 
    content.includes("<ol") || 
    content.includes("<span") ||
    content.includes("<strong")
  );

  return (
    <section dir={i18n.dir()} className="w-full bg-[#F0F2F5] py-8">
      <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {hasHTML ? (
          <article
            className="rounded-[16px] border border-[#D9DEE7] bg-white px-6 py-8 shadow-sm md:px-10 blog-content-html"
            dangerouslySetInnerHTML={{ __html: content }}
          />
        ) : (
          <div className="space-y-8">
            {sections.map((section, index) => (
              <article
                key={index}
                className="rounded-[16px] border border-[#D9DEE7] bg-white px-6 py-7 shadow-sm md:px-10"
              >
                {section.title && (
                  <h2
                    className={`mb-5 text-[20px] font-extrabold leading-8 ${
                      section.highlight
                        ? "text-[var(--brand-secondary-color)]"
                        : "text-[#07111F]"
                    }`}
                    dangerouslySetInnerHTML={{ __html: section.title }}
                  />
                )}

                <div className="space-y-4 text-[17px] leading-9 text-[#111827]">
                  {section.paragraphs.map((paragraph, paragraphIndex) => (
                    <div
                      key={paragraphIndex}
                      dangerouslySetInnerHTML={{ __html: paragraph }}
                    />
                  ))}

                  {section.list && (
                    <ul className="space-y-3 pr-5">
                      {section.list.map((item, itemIndex) => (
                        <li
                          key={itemIndex}
                          className="list-disc"
                          dangerouslySetInnerHTML={{ __html: item }}
                        />
                      ))}
                    </ul>
                  )}
                </div>
              </article>
            ))}
          </div>
        )}
      </div>
    </section>
  );
}
