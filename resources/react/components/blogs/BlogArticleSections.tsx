import type { IBlogArticleSectionsProps } from "../../interfaces/IBlogArticleSectionsProps";

export default function BlogArticleSections({ sections }: IBlogArticleSectionsProps) {
  return (
    <div className="space-y-8">
      {sections.map((section, index) => (
        <article
          key={index}
          className="rounded-[16px] bg-white px-6 py-7 shadow-sm md:px-10"
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
              <ul className="space-y-3 ps-5">
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
  );
}
