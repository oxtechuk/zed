import type { IContactFormData } from "../interfaces/IContactApiResponse";
import type { IContactFormValues } from "../interfaces/IContactFormValues";
import type { IWorkingHourLine } from "../interfaces/IWorkingHourLine";

export function sanitizeDigits(value: string): string {
  return value.replace(/\D/g, "");
}

export function buildWhatsAppUrl(number: string, text?: string): string {
  const url = `https://wa.me/${number}`;
  return text ? `${url}?text=${encodeURIComponent(text)}` : url;
}

export function buildTelHref(phone: string): string {
  return `tel:${phone.replace(/[^\d+]/g, "")}`;
}

export function buildMailtoHref(email: string): string {
  return `mailto:${email}`;
}

export function parseWorkingHours(input: string | IWorkingHourLine[]): IWorkingHourLine[] {
  if (Array.isArray(input)) {
    return input.filter((line) => line.day || line.hours);
  }
  if (!input) return [];
  return input
    .split("\n")
    .map((line) => {
      const separatorIndex = line.indexOf(":");
      if (separatorIndex === -1) {
        return { day: line.trim(), hours: "" };
      }
      return {
        day: line.slice(0, separatorIndex).trim(),
        hours: line.slice(separatorIndex + 1).trim(),
      };
    })
    .filter((line) => line.day || line.hours);
}

export function contactFormValuesToRequest(values: IContactFormValues): IContactFormData {
  return {
    name: values.fullName,
    phone: values.phone,
    email: values.email,
    subject: values.subject,
    country: values.country,
    message: values.message,
  };
}
