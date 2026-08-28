import { useMemo } from "react";
import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
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

export function useAboutPageData() {
  const { t } = useTranslation();
  useSEO(t("nav.about"), t("aboutPage.hero.description"));
  const language = useLanguageStore((s) => s.language);

  const { data: aboutData, isLoading } = useQuery<IAboutData>({
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
      : (t("aboutPage.story.paragraphs", {
          returnObjects: true,
        }) as string[]);

    return {
      title: story?.title?.trim() || t("aboutPage.story.title"),
      paragraphs,
      cards: [
        {
          title:
            story?.vision_title?.trim() ||
            t("aboutPage.story.visionTitle"),
          description:
            story?.vision_text?.trim() ||
            t("aboutPage.story.visionText"),
          variant: "light",
          icon: "eye",
        },
        {
          title:
            story?.mission_title?.trim() ||
            t("aboutPage.story.missionTitle"),
          description:
            story?.mission_text?.trim() ||
            t("aboutPage.story.missionText"),
          variant: "dark",
          icon: "target",
        },
        {
          title:
            story?.values_title?.trim() ||
            t("aboutPage.story.valuesTitle"),
          description:
            story?.values_text?.trim() ||
            t("aboutPage.story.valuesText"),
          variant: "light",
          icon: "eye",
        },
      ],
    };
  }, [story, t, language]);

  const partners: IPartnerCardProps[] = useMemo(() => {
    const api = aboutData?.partners ?? [];
    if (!api.length) {
      return [];
    }
    return api.map((p) => ({
      id: p.id,
      name: p.name,
      logo: getImageUrl(p.logo) || APP_IMAGES.BRAND_PLACEHOLDER,
      link: p.link || undefined,
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
    return api.map((item) => ({
      id: item.id,
      name: item.name,
      job: item.title,
      text: item.content,
      avatar: getImageUrl(item.image) || APP_IMAGES.AVATAR_PLACEHOLDER,
      rating: item.rating,
      reviewImage: getImageUrl(item.review_image) || undefined,
      reviewVideo: getImageUrl(item.review_video) || undefined,
      type: item.type || (item.review_video ? "video" : "text"),
    }));
  }, [aboutData]);

  const mediaTestimonials = useMemo(() => {
    return testimonials.filter((t) => t.type === "video" || Boolean(t.reviewVideo));
  }, [testimonials]);

  const textTestimonials = useMemo(() => {
    return testimonials.filter((t) => t.type === "text" || !t.reviewVideo);
  }, [testimonials]);

  return {
    hero,
    sections,
    stats,
    storySection,
    partners,
    locations,
    mediaTestimonials,
    textTestimonials,
    t,
    isLoading,
  };
}
