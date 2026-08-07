import { useState, useCallback, useMemo } from "react";
import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
import { toast } from "react-toastify";
import ContactUsSection from "../components/contact-us/ContactUsSection";
import FaqSection from "../components/contact-us/FaqSection";
import MediaReviewsSection from "../components/about/MediaReviewsSection";
import TestimonialsSection from "../components/about/TestimonialsSection";
import { submitContactForm, getFaqs, getAboutPageData } from "../services/api";
import { useLanguageStore } from "../store/language.store";
import { getImageUrl, APP_IMAGES } from "../constants/app-images";
import { useSEO } from "../utils/useSEO";
import type { IFaqItem } from "../interfaces/IFaqItem";
import type { ITestimonialItem } from "../interfaces/ITestimonialItem";
import type { IAboutData } from "../interfaces/IAboutData";

export default function ContactPage() {
    const { t } = useTranslation();
    useSEO(t("nav.contact"), t("contactPage.contactUs.description"));
    const language = useLanguageStore((s) => s.language);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [formKey, setFormKey] = useState(0);

    const { data: faqs } = useQuery<IFaqItem[]>({
        queryKey: ["faqs", language],
        queryFn: getFaqs,
    });

    const { data: aboutData } = useQuery<IAboutData>({
        queryKey: ["about", language],
        queryFn: getAboutPageData,
    });

    const testimonials: ITestimonialItem[] = useMemo(() => {
        const apiTestimonials = aboutData?.testimonials ?? [];
        if (!apiTestimonials.length) return [];
        return apiTestimonials.map((t) => ({
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

    const handleSubmit = useCallback(
        async (values: {
            fullName: string;
            phone: string;
            email: string;
            subject: string;
            country: string;
            message: string;
        }) => {
            setIsSubmitting(true);
            try {
                await submitContactForm({
                    name: values.fullName,
                    phone: values.phone,
                    email: values.email,
                    subject: values.subject,
                    country: values.country,
                    message: values.message,
                });
                toast.success(t("contactPage.contactUs.successToast"));
                setFormKey((k) => k + 1);
            } catch {
                toast.error(t("contactPage.contactUs.errorToast"));
            } finally {
                setIsSubmitting(false);
            }
        },
        [t],
    );

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

            <FaqSection
                eyebrow={t("contactPage.faq.eyebrow")}
                titleBlack={t("contactPage.faq.titleBlack")}
                titleOrange={t("contactPage.faq.titleOrange")}
                description={t("contactPage.faq.description")}
                buttonText={t("contactPage.faq.buttonText")}
                buttonHref="/contact"
                faqs={faqs ?? []}
            />
        </>
    );
}
