import { useCallback, useState } from "react";
import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
import { toast } from "react-toastify";
import ContactUsSection from "../components/contact-us/ContactUsSection";
import FaqSection from "../components/contact-us/FaqSection";
import ContactPageSkeleton from "../components/skeletons/ContactPageSkeleton";
import { APP_IMAGES } from "../constants/app-images";
import { getFaqs, submitContactForm } from "../services/api";
import { useLanguageStore } from "../store/language.store";
import { contactFormValuesToRequest } from "../utils/contact";
import { useSEO } from "../utils/useSEO";
import { usePageImagesReady } from "../hooks/usePageImagesReady";
import type { IContactFormValues } from "../interfaces/IContactFormValues";
import type { IFaqItem } from "../interfaces/IFaqItem";

export default function ContactPage() {
  const { t } = useTranslation();
  useSEO(t("nav.contact"), t("contactPage.contactUs.description"));
  const language = useLanguageStore((state) => state.language);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [formKey, setFormKey] = useState(0);

  const { data: faqs, isLoading } = useQuery<IFaqItem[]>({
    queryKey: ["faqs", language],
    queryFn: getFaqs,
  });

  const imagesReady = usePageImagesReady(isLoading, [APP_IMAGES.CONTACT_US_HERO]);

  const handleSubmit = useCallback(
    async (values: IContactFormValues) => {
      setIsSubmitting(true);
      try {
        await submitContactForm(contactFormValuesToRequest(values));
        toast.success(t("contactPage.contactUs.successToast"));
        setFormKey((key) => key + 1);
      } catch {
        toast.error(t("contactPage.contactUs.errorToast"));
      } finally {
        setIsSubmitting(false);
      }
    },
    [t],
  );

  if (isLoading || !imagesReady) {
    return <ContactPageSkeleton />;
  }

  return (
    <>
      <ContactUsSection
        key={formKey}
        eyebrow={t("contactPage.contactUs.eyebrow")}
        title={t("contactPage.contactUs.title")}
        description={t("contactPage.contactUs.description")}
        isSubmitting={isSubmitting}
        onSubmit={handleSubmit}
      />

      <FaqSection eyebrow={t("contactPage.faq.eyebrow")} faqs={faqs ?? []} />
    </>
  );
}
