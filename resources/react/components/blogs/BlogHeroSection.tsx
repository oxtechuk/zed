import { useTranslation } from "react-i18next";
import OffersPageHero from "../offers-page/OffersPageHero";
import { APP_IMAGES } from "../../constants/app-images";
import type { IBlogHeroSectionProps } from "../../interfaces/IBlogHeroSectionProps";

export default function BlogHeroSection({
  badgeText,
  title,
  description,
  image,
}: IBlogHeroSectionProps) {
  const { t } = useTranslation();

  return (
    <OffersPageHero
      image={image || APP_IMAGES.BLOG_PLACEHOLDER}
      badgeText={badgeText || t("blogPage.hero.badge", { defaultValue: "المدونة" })}
      title={title || t("blogPage.hero.title", { defaultValue: "نصائح ومقالات" })}
      description={
        description ||
        t("blogPage.hero.description", {
          defaultValue: "فريقنا المتخصص جاهز للرد على جميع استفساراتك",
        })
      }
    />
  );
}
