import { useState } from "react";
import { useTranslation } from "react-i18next";
import { MessageCircle, Send } from "lucide-react";
import SubjectChips from "./SubjectChips";
import { buildWhatsAppUrl } from "../../utils/contact";
import {
  CONTACT_MESSAGE_MAX_LENGTH,
  CONTACT_SUBJECTS,
  createInitialContactFormValues,
} from "../../constants/contact.constants";
import type { IContactFormCardProps } from "../../interfaces/IContactFormCardProps";
import type { IContactFormValues } from "../../interfaces/IContactFormValues";
import type { ISubjectOption } from "../../interfaces/ISubjectOption";

export default function ContactFormCard({
  isSubmitting,
  whatsappNumber,
  onSubmit,
}: IContactFormCardProps) {
  const { t } = useTranslation();
  const [values, setValues] = useState<IContactFormValues>(createInitialContactFormValues);

  const subjects: ISubjectOption[] = CONTACT_SUBJECTS.map((subject) => ({
    value: subject.value,
    label: t(subject.translationKey),
  }));

  const updateField = <K extends keyof IContactFormValues>(key: K, value: IContactFormValues[K]) => {
    setValues((prev) => ({
      ...prev,
      [key]: value,
    }));
  };

  const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    onSubmit?.(values);
  };

  const selectedSubjectLabel =
    subjects.find((subject) => subject.value === values.subject)?.label || values.subject;
  const whatsappLink = buildWhatsAppUrl(
    whatsappNumber,
    t("contactPage.contactUs.whatsappMessage", { subject: selectedSubjectLabel }),
  );

  const inputClasses =
    "h-[50px] w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-[14px] font-medium text-[#0F172A] outline-none transition placeholder:text-gray-400 focus:border-[#0F172A] focus:bg-white focus:ring-2 focus:ring-[#0F172A]/10";

  return (
    <form
      onSubmit={handleSubmit}
      className="rounded-3xl border border-[#E5E9F0] bg-white shadow-sm overflow-hidden text-start"
    >
      <div className="bg-[#1E293B] px-8 py-6 text-white">
        <span className="text-[11px] font-extrabold text-[#EDC98E] uppercase tracking-wider block mb-1">
          {t("contactPage.contactUs.labels.formTag")}
        </span>
        <h3 className="text-[20px] font-black leading-tight text-white">
          {t("contactPage.contactUs.labels.formTitle")}
        </h3>
        <p className="text-[12px] text-white/60 font-semibold mt-1">
          {t("contactPage.contactUs.labels.formSubtitle")}
        </p>
      </div>

      <div className="p-8">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div className="flex flex-col text-start">
            <label className="text-[13px] font-bold text-[#374151] mb-2">
              {t("contactPage.contactUs.fullNameLabel")} <span className="text-red-500">*</span>
            </label>
            <input
              type="text"
              required
              value={values.fullName}
              onChange={(event) => updateField("fullName", event.target.value)}
              placeholder={t("contactPage.contactUs.fullNamePlaceholder")}
              className={inputClasses}
            />
          </div>
          <div className="flex flex-col text-start">
            <label className="text-[13px] font-bold text-[#374151] mb-2">
              {t("contactPage.contactUs.phoneLabel")} <span className="text-red-500">*</span>
            </label>
            <input
              type="tel"
              required
              value={values.phone}
              onChange={(event) => updateField("phone", event.target.value)}
              placeholder={t("contactPage.contactUs.phonePlaceholder")}
              className={`${inputClasses} text-start`}
              dir="ltr"
            />
          </div>
        </div>

        <div className="mt-6 flex flex-col text-start">
          <label className="text-[13px] font-bold text-[#374151] mb-2">
            {t("contactPage.contactUs.emailLabel")}
          </label>
          <input
            type="email"
            value={values.email}
            onChange={(event) => updateField("email", event.target.value)}
            placeholder={t("contactPage.contactUs.emailPlaceholder")}
            className={`${inputClasses} text-start`}
            dir="ltr"
          />
        </div>

        <div className="mt-6 flex flex-col text-start">
          <label className="text-[13px] font-bold text-[#374151] mb-2">
            {t("contactPage.contactUs.subjectLabel")} <span className="text-red-500">*</span>
          </label>
          <SubjectChips
            value={values.subject}
            options={subjects}
            onChange={(subject) => updateField("subject", subject)}
          />
        </div>

        <div className="mt-6 flex flex-col text-start">
          <label className="text-[13px] font-bold text-[#374151] mb-2">
            {t("contactPage.contactUs.messageLabel")} <span className="text-red-500">*</span>
          </label>
          <textarea
            required
            value={values.message}
            maxLength={CONTACT_MESSAGE_MAX_LENGTH}
            onChange={(event) => updateField("message", event.target.value)}
            placeholder={t("contactPage.contactUs.messagePlaceholder")}
            className={`${inputClasses} min-h-[140px] resize-none py-4 leading-relaxed`}
          />
          <div className="mt-2 text-start text-[11px] text-[#8A8F99] font-semibold">
            {values.message.length} / {CONTACT_MESSAGE_MAX_LENGTH}{" "}
            {t("contactPage.contactUs.charCount")}
          </div>
        </div>

        <div className="mt-8 flex gap-3">
          <button
            type="submit"
            disabled={isSubmitting}
            className="flex-1 h-[50px] bg-[#0F172A] text-white text-[15px] font-extrabold rounded-xl flex items-center justify-center gap-2 transition hover:opacity-95 disabled:opacity-50 hover:scale-[1.01] active:scale-95 shadow-sm"
          >
            <Send size={16} />
            <span>
              {isSubmitting
                ? t("contactPage.contactUs.submittingText")
                : t("contactPage.contactUs.submitText")}
            </span>
          </button>
          <a
            href={whatsappLink}
            target="_blank"
            rel="noreferrer"
            className="h-[50px] px-6 bg-[#25D366] text-white! text-[15px] font-extrabold rounded-xl flex items-center justify-center gap-2 transition hover:bg-[#20ba59] hover:scale-[1.01] active:scale-95 shadow-sm"
          >
            <MessageCircle size={16} />
            <span>{t("contactPage.contactUs.labels.whatsapp")}</span>
          </a>
        </div>
      </div>
    </form>
  );
}
