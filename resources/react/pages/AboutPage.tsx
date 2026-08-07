import { useMemo } from "react";
import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
import AboutHero from "../components/about/AboutHero";
import AboutStorySection from "../components/about/AboutStorySection";
import DealerCtaSection from "../components/about/DealerCtaSection";
import LocationsSection from "../components/about/LocationsSection";
import PartnersSection from "../components/about/PartnersSection";
import TestimonialsSection from "../components/about/TestimonialsSection";
import MediaReviewsSection from "../components/about/MediaReviewsSection";
import { getAboutPageData } from "../services/api";
import { useLanguageStore } from "../store/language.store";
import { APP_IMAGES, getImageUrl } from "../constants/app-images";
import { useSEO } from "../utils/useSEO";
import type { IAboutStat } from "../interfaces/IAboutStat";
import type { IAboutStorySectionProps } from "../interfaces/IAboutStorySectionProps";
import type { IPartnerCardProps } from "../interfaces/IPartnerCardProps";
import type { ILocationItem } from "../interfaces/ILocationItem";
import type { ITestimonialItem } from "../interfaces/ITestimonialItem";
import type { IAboutData } from "../interfaces/IAboutData";

export default function AboutPage() {
  const { t } = useTranslation();
  useSEO(t("nav.about"), t("aboutPage.hero.description"));
  const language = useLanguageStore((s) => s.language);

  const { data: aboutData } = useQuery<IAboutData>({
    queryKey: ["about", language],
    queryFn: getAboutPageData,
  });

  const sections = aboutData?.page_sections;
  const hero = sections?.hero;
  const story = sections?.story;

  const stats: IAboutStat[] = useMemo(() => {
    if (aboutData?.about_stats?.length) {
      return aboutData.about_stats;
    }
    return t("aboutPage.stats", { returnObjects: true }) as IAboutStat[];
  }, [aboutData, t]);

  const storySection: IAboutStorySectionProps = useMemo(() => {
    const paragraphs = story?.content?.trim()
      ? story.content.split(/\r?\n\r?\n/).filter(Boolean)
      : (t("aboutPage.story.paragraphs", { returnObjects: true }) as string[]);

    return {
      title: story?.title?.trim() || t("aboutPage.story.title"),
      paragraphs,
      cards: [
        {
          title: story?.vision_title?.trim() || t("aboutPage.story.visionTitle"),
          description: story?.vision_text?.trim() || t("aboutPage.story.visionText"),
          variant: "light",
          icon: "eye",
        },
        {
          title: story?.mission_title?.trim() || t("aboutPage.story.missionTitle"),
          description: story?.mission_text?.trim() || t("aboutPage.story.missionText"),
          variant: "dark",
          icon: "target",
        },
        {
          title: story?.values_title?.trim() || t("aboutPage.story.valuesTitle"),
          description: story?.values_text?.trim() || t("aboutPage.story.valuesText"),
          variant: "light",
          icon: "eye",
        },
      ],
    };
  }, [story, t]);

  const partners: IPartnerCardProps[] = useMemo(() => {
    const api = aboutData?.partners ?? [];
    if (!api.length) {
      return [];
    }
    return api.map((p) => ({
      id: p.id,
      name: p.name,
      logo: getImageUrl(p.logo) || APP_IMAGES.BRAND_PLACEHOLDER,
    }));
  }, [aboutData]);

  const locations: ILocationItem[] = useMemo(() => {
    const api = aboutData?.about_branches ?? [];
    if (!api.length) return [];
    return api.map((branch, idx) => ({
      id: branch.city + idx,
      city: branch.city,
      branchType: branch.name,
      address: branch.address,
      phone: branch.phone,
      workingHours: branch.working_hours,
      mapLink: branch.map_link,
      label: idx === 0 ? t("aboutPage.locations.mainBranch") : undefined,
    }));
  }, [aboutData, t]);

  const testimonials: ITestimonialItem[] = useMemo(() => {
    const api = aboutData?.testimonials ?? [];
    if (!api.length) return [];
    return api.map((t) => ({
      id: t.id,
      name: t.name,
      job: t.title,
      text: t.content,
      avatar: getImageUrl(t.image) || APP_IMAGES.AVATAR_PLACEHOLDER,
      rating: t.rating,
      reviewImage: getImageUrl(t.review_image) || undefined,
      reviewVideo: getImageUrl(t.review_video) || undefined,
      type: t.type || "text",
    }));
  }, [aboutData]);

  const mediaTestimonials = useMemo(() => {
    return testimonials.filter((t) => t.type === "video");
  }, [testimonials]);

  const textTestimonials = useMemo(() => {
    return testimonials.filter((t) => t.type === "text");
  }, [testimonials]);

  return (
    <>
      <AboutHero
        badgeText={hero?.badge?.trim() || t("aboutPage.hero.badge")}
        titleWhite={hero?.title1?.trim() || t("aboutPage.hero.titleWhite")}
        titleBlue={hero?.title2?.trim() || t("aboutPage.hero.titleBlue")}
        subtitle={hero?.subtitle?.trim() || t("aboutPage.hero.subtitle")}
        description={hero?.description?.trim() || t("aboutPage.hero.description")}
        stats={stats}
      />

      <AboutStorySection {...storySection} />

      <MediaReviewsSection testimonials={mediaTestimonials} />

      <PartnersSection
        eyebrow={
          sections?.partners?.badge?.trim() || t("aboutPage.partners.eyebrow")
        }
        titleBlack={
          sections?.partners?.title1?.trim() || t("aboutPage.partners.titleBlack")
        }
        titleBlue={
          sections?.partners?.title2?.trim() || t("aboutPage.partners.titleBlue")
        }
        description={
          sections?.partners?.subtitle?.trim() || t("aboutPage.partners.description")
        }
        partners={partners}
      />

      <DealerCtaSection
        title={sections?.dealer?.title?.trim() || t("aboutPage.dealer.title")}
        description={
          sections?.dealer?.description?.trim() || t("aboutPage.dealer.description")
        }
        primaryButtonText={
          sections?.dealer?.partner_button_text?.trim() ||
          t("aboutPage.dealer.primaryButton")
        }
        primaryButtonTo={
          sections?.dealer?.partner_button_link?.trim() || "/partner"
        }
        secondaryButtonText={
          sections?.dealer?.contact_button_text?.trim() ||
          t("aboutPage.dealer.secondaryButton")
        }
        secondaryButtonTo="/contact"
      />

      <LocationsSection
        titleBlack={
          sections?.locations?.title1?.trim() || t("aboutPage.locations.titleBlack")
        }
        titleBlue={
          sections?.locations?.title2?.trim() || t("aboutPage.locations.titleBlue")
        }
        locations={locations}
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
        ratingText={sections?.testimonials?.rating_text?.trim() || undefined}
        testimonials={textTestimonials}
      />

    </>
  );
}
