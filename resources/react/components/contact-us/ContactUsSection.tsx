import { useTranslation } from "react-i18next";
import ContactFormCard from "./ContactFormCard";
import ContactHeroSection from "./ContactHeroSection";
import ContactSidebar from "./ContactSidebar";
import { useContactInfo } from "../../hooks/useContactInfo";
import type { IContactUsSectionProps } from "../../interfaces/IContactUsSectionProps";

export default function ContactUsSection({
  eyebrow,
  title,
  description,
  isSubmitting,
  onSubmit,
}: IContactUsSectionProps) {
  const { i18n } = useTranslation();
  const { contact, branches } = useContactInfo();

  return (
    <div className="w-full flex flex-col" dir={i18n.dir()}>
      <ContactHeroSection
        eyebrow={eyebrow}
        title={title}
        description={description}
        contact={contact}
      />

      <section className="w-full bg-[#F3F4F6] pb-16 pt-16">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            <div className="lg:col-span-8">
              <ContactFormCard
                isSubmitting={isSubmitting}
                whatsappNumber={contact.whatsappNumber}
                onSubmit={onSubmit}
              />
            </div>
            <ContactSidebar branches={branches} />
          </div>
        </div>
      </section>
    </div>
  );
}
