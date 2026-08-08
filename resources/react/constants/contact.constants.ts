import type { IContactFormValues } from "../interfaces/IContactFormValues";

export const CONTACT_SUBJECTS: Array<{ value: string; translationKey: string }> = [
  { value: "general_inquiry", translationKey: "contactPage.contactUs.subjects.general_inquiry" },
  { value: "financing_request", translationKey: "contactPage.contactUs.subjects.financing_request" },
  { value: "car_booking", translationKey: "contactPage.contactUs.subjects.car_booking" },
  { value: "car_import", translationKey: "contactPage.contactUs.subjects.car_import" },
  { value: "complaint", translationKey: "contactPage.contactUs.subjects.complaint" },
  { value: "other", translationKey: "contactPage.contactUs.subjects.other" },
];

export const CONTACT_MESSAGE_MAX_LENGTH = 500;

export const DEFAULT_COUNTRY_VALUE = "saudi-arabia";

export const DEFAULT_SUBJECT_VALUE = "general_inquiry";

export const FALLBACK_WHATSAPP_NUMBER = "966500000000";

export const FALLBACK_PHONE_HREF = "tel:+966500000000";

export const FALLBACK_EMAIL_HREF = "mailto:info@zadcapital.sa";

export function createInitialContactFormValues(): IContactFormValues {
  return {
    fullName: "",
    email: "",
    phone: "",
    country: DEFAULT_COUNTRY_VALUE,
    subject: DEFAULT_SUBJECT_VALUE,
    message: "",
  };
}
