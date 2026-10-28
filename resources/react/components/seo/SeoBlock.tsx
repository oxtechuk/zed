import { useEffect, useState, type ReactNode } from "react";
import { Link } from "react-router-dom";
import { ChevronDown } from "lucide-react";
import { useSeoEntry } from "../../hooks/useSeoEntry";

/** Inline markup used in the SEO copy: **bold** and [text](url). */
function renderInline(text: string): ReactNode[] {
  const parts: ReactNode[] = [];
  const pattern = /\*\*([^*]+)\*\*|\[([^\]]+)\]\(([^)]+)\)/g;
  let last = 0;
  let match: RegExpExecArray | null;
  while ((match = pattern.exec(text)) !== null) {
    if (match.index > last) parts.push(text.slice(last, match.index));
    if (match[1]) {
      parts.push(<strong key={match.index} className="font-extrabold text-[#0F172A]">{match[1]}</strong>);
    } else if (match[3].startsWith("/")) {
      parts.push(<Link key={match.index} to={match[3]} className="font-bold text-[#16254F] underline underline-offset-4">{match[2]}</Link>);
    } else {
      parts.push(<a key={match.index} href={match[3]} className="font-bold text-[#16254F] underline underline-offset-4">{match[2]}</a>);
    }
    last = match.index + match[0].length;
  }
  if (last < text.length) parts.push(text.slice(last));
  return parts;
}

/** Block markup: "### heading", "- item", "1. item", blank line between paragraphs. */
function renderIntro(text: string): ReactNode[] {
  const blocks: ReactNode[] = [];
  let list: { ordered: boolean; items: string[] } | null = null;
  const flush = () => {
    if (!list) return;
    const Tag = list.ordered ? "ol" : "ul";
    blocks.push(
      <Tag key={`l${blocks.length}`} className={`${list.ordered ? "list-decimal" : "list-disc"} ps-6 space-y-2 text-[15px] md:text-[16px] leading-[1.8] text-[#475569] font-medium`}>
        {list.items.map((item, i) => <li key={i}>{renderInline(item)}</li>)}
      </Tag>,
    );
    list = null;
  };

  text.split(/\r?\n/).forEach((raw) => {
    const line = raw.trim();
    const bullet = line.match(/^-\s+(.*)$/);
    const numbered = line.match(/^\d+\.\s+(.*)$/);
    if (bullet || numbered) {
      const ordered = !!numbered;
      if (list && list.ordered !== ordered) flush();
      list ??= { ordered, items: [] };
      list.items.push((bullet ?? numbered)![1]);
      return;
    }
    flush();
    if (!line) return;
    const heading = line.match(/^#{1,6}\s+(.*)$/);
    if (heading) {
      blocks.push(<h2 key={`h${blocks.length}`} className="text-[22px] md:text-[24px] font-black text-[#0F172A]">{heading[1]}</h2>);
    } else {
      blocks.push(<p key={`p${blocks.length}`} className="text-[15px] md:text-[16px] leading-[1.8] text-[#475569] font-medium">{renderInline(line)}</p>);
    }
  });
  flush();
  return blocks;
}

/**
 * Extra text and FAQ for the current page (seo_entries), shown above the footer.
 * Answers stay in the DOM when collapsed so search engines read them.
 */
export default function SeoBlock() {
  const entry = useSeoEntry();
  const [openIndex, setOpenIndex] = useState<number | null>(0);

  useEffect(() => {
    setOpenIndex(0);
    if (!entry) return;
    if (entry.meta_title) document.title = entry.meta_title;
    if (entry.meta_description) {
      document.querySelector('meta[name="description"]')?.setAttribute("content", entry.meta_description);
    }
  }, [entry]);

  if (!entry || (!entry.intro && entry.faq.length === 0)) return null;

  return (
    <section dir="rtl" className="w-full bg-white py-16">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-12">
        {entry.intro && <div className="space-y-4 text-start">{renderIntro(entry.intro)}</div>}

        {entry.faq.length > 0 && (
          <div>
            <div className="text-start mb-8">
              <span className="text-[13px] font-extrabold text-[#EDC98E] uppercase tracking-wider">
                أسئلة شائعة
              </span>
              <h2 className="mt-2 text-[28px] font-black text-[#0F172A]">
                إجابات سريعة قبل ما تطلب
              </h2>
            </div>
            <div className="space-y-4">
              {entry.faq.map((item, index) => {
                const isOpen = index === openIndex;
                return (
                  <div
                    key={index}
                    className={`overflow-hidden rounded-2xl border transition-all duration-300 bg-white ${isOpen ? "border-gray-200 shadow-xs" : "border-gray-100"}`}
                  >
                    <button
                      type="button"
                      onClick={() => setOpenIndex(isOpen ? null : index)}
                      aria-expanded={isOpen}
                      className="flex w-full items-center justify-between gap-5 px-6 py-5 text-start"
                    >
                      <h3 className="text-[16px] leading-relaxed text-[#0F172A]">{item.q}</h3>
                      <span
                        className={`flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-50 text-gray-500 transition-transform duration-300 ${isOpen ? "rotate-180 bg-[#FFF4E4] text-[#D97706]" : ""}`}
                      >
                        <ChevronDown size={16} strokeWidth={2.5} />
                      </span>
                    </button>
                    <div className={`px-6 pb-6 text-start ${isOpen ? "" : "hidden"}`}>
                      <div className="mb-4 h-px w-full bg-gray-100" />
                      <p className="text-[14px] leading-relaxed text-gray-500 font-semibold">{item.a}</p>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        )}
      </div>
    </section>
  );
}
