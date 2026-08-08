import AboutHero from "../components/about/AboutHero";
import AboutStorySection from "../components/about/AboutStorySection";
import PartnersSection from "../components/about/PartnersSection";
import TestimonialsSection from "../components/about/TestimonialsSection";
import MediaReviewsSectionvid from "../components/about/MediaReviewsSectionvid";
import AboutPageSkeleton from "../components/skeletons/AboutPageSkeleton";
import { useAboutPageData } from "../hooks/useAboutPageData";

export default function AboutPage() {
    const {
        hero,
        sections,
        stats,
        storySection,
        partners,
        textTestimonials,
        t,
        isLoading,
    } = useAboutPageData();

    if (isLoading) {
        return <AboutPageSkeleton />;
    }

    return (
        <>
            <AboutHero
                badgeText={hero?.badge?.trim() || t("aboutPage.hero.badge")}
                titleWhite={
                    hero?.title1?.trim() || t("aboutPage.hero.titleWhite")
                }
                titleBlue={
                    hero?.title2?.trim() || t("aboutPage.hero.titleBlue")
                }
                subtitle={
                    hero?.subtitle?.trim() || t("aboutPage.hero.subtitle")
                }
                description={
                    hero?.description?.trim() || t("aboutPage.hero.description")
                }
                stats={stats}
            />

            <AboutStorySection {...storySection} />
            <MediaReviewsSectionvid />

            <PartnersSection
                eyebrow={
                    sections?.partners?.badge?.trim() ||
                    t("aboutPage.partners.eyebrow")
                }
                titleBlack={
                    sections?.partners?.title1?.trim() ||
                    t("aboutPage.partners.titleBlack")
                }
                titleBlue={
                    sections?.partners?.title2?.trim() ||
                    t("aboutPage.partners.titleBlue")
                }
                description={
                    sections?.partners?.subtitle?.trim() ||
                    t("aboutPage.partners.description")
                }
                partners={partners}
            />

            <TestimonialsSection
                badge={
                    sections?.testimonials?.badge?.trim() ||
                    t("aboutPage.testimonials.badge")
                }
                titleBlack={
                    sections?.testimonials?.title1?.trim() ||
                    t("aboutPage.testimonials.titleBlack")
                }
                titleBlue={
                    sections?.testimonials?.title2?.trim() ||
                    t("aboutPage.testimonials.titleBlue")
                }
                ratingText={
                    sections?.testimonials?.rating_text?.trim() || undefined
                }
                testimonials={textTestimonials}
            />
        </>
    );
}
