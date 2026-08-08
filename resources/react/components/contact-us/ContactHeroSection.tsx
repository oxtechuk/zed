import { useTranslation } from "react-i18next";
import { Mail, MapPin, MessageCircle, Phone } from "lucide-react";
import ContactInfoCard from "./ContactInfoCard";
import { APP_IMAGES } from "../../constants/app-images";
import type { IContactHeroSectionProps } from "../../interfaces/IContactHeroSectionProps";
import type { IContactInfoCardProps } from "../../interfaces/IContactInfoCardProps";

export default function ContactHeroSection({
  eyebrow,
  title,
  description,
  contact,
}: IContactHeroSectionProps) {
  const { t } = useTranslation();

  const infoCards: IContactInfoCardProps[] = [
    {
      icon: <MessageCircle size={20} />,
      label: t("contactPage.contactUs.labels.whatsapp"),
      value: contact.whatsappDisplay,
      href: contact.whatsappHref,
      variant: "whatsapp",
      valueDirection: "ltr",
    },
    {
      icon: <Phone size={18} className="text-[#EDC98E]" />,
      label: t("contactPage.contactUs.labels.callNow"),
      value: contact.phoneDisplay,
      href: contact.phoneHref,
      valueDirection: "ltr",
    },
    {
      icon: <Mail size={18} />,
      label: t("contactPage.contactUs.labels.email"),
      value: contact.emailDisplay,
      href: contact.emailHref,
    },
    {
      icon: <MapPin size={18} />,
      label: t("contactPage.contactUs.labels.location"),
      value: contact.addressDisplay,
    },
  ];

  return (
    <section
      className="w-full pt-16 pb-24 text-white text-start relative overflow-hidden bg-cover bg-center bg-no-repeat"
      style={{ backgroundImage: `url('${APP_IMAGES.CONTACT_US_HERO}')` }}
    >
      <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <span className="text-[14px] font-extrabold text-[#EDC98E] uppercase tracking-wider">
          {eyebrow}
        </span>
        <h1 className="mt-3 text-[32px] font-black text-white leading-tight md:text-[44px]">
          {title}
        </h1>
        <p className="mt-4 mx-auto max-w-xl text-[16px] leading-relaxed text-gray-400">
          {description}
        </p>

        <div className="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-start">
          {infoCards.map((card) => (
            <ContactInfoCard key={card.label} {...card} />
          ))}
        </div>
      </div>
    </section>
  );
}
