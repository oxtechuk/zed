import { useTranslation } from "react-i18next";
import AboutInfoCard from "./AboutInfoCard";
import type { IAboutStorySectionProps } from "../../interfaces/IAboutStorySectionProps";

export default function AboutStorySection({
  title,
  paragraphs,
  cards,
}: IAboutStorySectionProps) {
  const { i18n } = useTranslation();

  return (
    <section dir={i18n.dir()} className="w-full bg-white py-20 border-b border-[#E5E7EB]">
      <div className="mx-auto max-w-7xl px-6 sm:px-8 lg:px-12">
        {/* Optional Title Section (Only shown if configured dynamically) */}
        {title && paragraphs.length > 0 && title !== "قصة الشركة" && (
          <div className="mx-auto max-w-4xl text-center mb-16">
            <h2 className="text-[26px] font-extrabold leading-[1.6] text-[#07111F] md:text-[32px]">
              {title}
            </h2>
            <div className="mt-5 space-y-4">
              {paragraphs.map((paragraph, index) => (
                <p
                  key={index}
                  className="text-[16px] leading-8 text-[#4B5563]"
                >
                  {paragraph}
                </p>
              ))}
            </div>
          </div>
        )}

        {/* 3-Column minimalist grid layout */}
        {cards.length > 0 && (
          <div className="mx-auto grid max-w-6xl grid-cols-1 gap-12 sm:grid-cols-2 md:grid-cols-3 md:gap-16 lg:gap-24">
            {cards.map((card) => (
              <AboutInfoCard key={card.title} {...card} />
            ))}
          </div>
        )}
      </div>
    </section>
  );
}
