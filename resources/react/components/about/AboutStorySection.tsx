import { useTranslation } from "react-i18next";

import AboutInfoCard from "./AboutInfoCard";
import type { IAboutStorySectionProps } from "../../interfaces/IAboutStorySectionProps";

export default function AboutStorySection({ cards }: IAboutStorySectionProps) {
    const { i18n } = useTranslation();

    if (!cards?.length) {
        return null;
    }

    return (
        <section
            dir={i18n.dir()}
            className="w-full border-b border-[#E9E9E9] bg-[#FAFAFB] py-12 sm:py-20 lg:py-24"
        >
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    className={[
                        "grid grid-cols-1",
                        "gap-12",
                        "md:grid-cols-3",
                        "md:gap-10",
                        "lg:gap-16",
                    ].join(" ")}
                >
                    {cards.slice(0, 3).map((card, index) => (
                        <AboutInfoCard key={index} {...card} />
                    ))}
                </div>
            </div>
        </section>
    );
}
