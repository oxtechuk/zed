import { useState } from "react";
import { useTranslation } from "react-i18next";
import FaqAccordion from "./FaqAccordion";
import type { IFaqSectionProps } from "../../interfaces/IFaqSectionProps";

export default function FaqSection({
  eyebrow,
  faqs,
}: IFaqSectionProps) {
  const { i18n, t } = useTranslation();
  const [openId, setOpenId] = useState<string | number | null>(
    faqs[0]?.id ?? null,
  );

  const toggleFaq = (id: string | number) => {
    setOpenId((current) => (current === id ? null : id));
  };

  return (
    <section dir={i18n.dir()} className="w-full bg-white py-16">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        {/* Header Block */}
        <div className="text-start mb-10">
          <span className="text-[13px] font-extrabold text-[#EDC98E] uppercase tracking-wider">
            {eyebrow || t("contactPage.faq.eyebrow", { defaultValue: "أسئلة شائعة" })}
          </span>
          <h2 className="mt-2 text-[28px] font-black text-[#0F172A] md:text-[36px]">
            {t("contactPage.faq.title", { defaultValue: "هل لديك سؤال؟" })}
          </h2>
        </div>

        {/* Accordions List */}
        <div className="space-y-4">
          {faqs.map((faq) => (
            <FaqAccordion
              key={faq.id}
              faq={faq}
              isOpen={faq.id === openId}
              onToggle={toggleFaq}
            />
          ))}
        </div>

      </div>
    </section>
  );
}
