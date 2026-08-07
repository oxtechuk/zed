import { useState } from "react";
import { useTranslation } from "react-i18next";
import { Send, Phone, Mail, MapPin, MessageCircle, Clock } from "lucide-react";
import type { IContactFormValues } from "../../interfaces/IContactFormValues";
import type { IContactUsSectionProps } from "../../interfaces/IContactUsSectionProps";
import { useSettingsStore } from "../../store/settings.store";

const subjects = [
  "استفسار عام",
  "طلب تمويل",
  "حجز سيارة",
  "استيراد سيارة",
  "شكوى",
  "أخرى"
];

export default function ContactUsSection({
  eyebrow,
  title,
  description,
  isSubmitting,
  onSubmit,
}: IContactUsSectionProps) {
  const { i18n, t } = useTranslation();
  const settings = useSettingsStore((s) => s.settings);

  const branches = settings?.about_branches && settings.about_branches.length > 0
    ? settings.about_branches
    : [
        {
          name: "طريق الملك فهد، العليا",
          address: "الرياض، المملكة العربية السعودية",
          map_link: "https://maps.google.com/?q=العليا+الرياض",
          working_hours: "الأحد - الخميس: 9:00 ص - 9:00 م\nالجمعة: 4:00 م - 9:00 م\nالسبت: 10:00 ص - 8:00 م"
        }
      ];

  const whatsappNumber = settings?.contact?.whatsapp
    ? settings.contact.whatsapp.replace(/\D/g, "")
    : "966500000000";
  
  const [values, setValues] = useState<IContactFormValues>({
    fullName: "",
    email: "",
    phone: "",
    country: "saudi-arabia",
    subject: "استفسار عام",
    message: "",
  });

  const maxMessageLength = 500;

  const updateField = <K extends keyof IContactFormValues>(
    key: K,
    value: IContactFormValues[K],
  ) => {
    setValues((prev) => ({
      ...prev,
      [key]: value,
    }));
  };

  const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    onSubmit?.(values);
  };

  const inputClasses =
    "h-[50px] w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-[14px] font-medium text-[#0F172A] outline-none transition placeholder:text-gray-400 focus:border-[#0F172A] focus:bg-white focus:ring-2 focus:ring-[#0F172A]/10";

  return (
    <div className="w-full flex flex-col" dir={i18n.dir()}>
      
      {/* 1. Hero Header Banner */}
      <section className="w-full bg-[#0F172A] pt-16 pb-24 text-white text-center relative overflow-hidden">
        <div className="absolute top-0 right-0 w-96 h-96 bg-[#EDC98E]/5 blur-[90px] rounded-full pointer-events-none" />
        
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

          {/* 4 Overlapping Contact Info Cards */}
          <div className="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-start">
            
            {/* WhatsApp Card */}
            <a
              href="https://wa.me/966500000000"
              target="_blank"
              rel="noreferrer"
              className="rounded-2xl bg-[#064E3B]/80 border border-[#047857]/40 p-5 flex items-center gap-4 transition hover:scale-[1.02]"
            >
              <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#059669] text-white">
                <MessageCircle size={20} />
              </div>
              <div>
                <span className="text-[12px] text-emerald-400 font-bold block">واتساب</span>
                <strong className="text-[15px] font-black text-white block mt-0.5">رد فوري</strong>
              </div>
            </a>

            {/* Phone Card */}
            <a
              href="tel:+966500000000"
              className="rounded-2xl bg-[#1E293B]/90 border border-white/5 p-5 flex items-center gap-4 transition hover:scale-[1.02]"
            >
              <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/5 text-[#EDC98E]">
                <Phone size={18} />
              </div>
              <div>
                <span className="text-[12px] text-white/50 font-bold block">اتصل الآن</span>
                <strong className="text-[15px] font-black text-white block mt-0.5">+966 55 000 0000</strong>
              </div>
            </a>

            {/* Email Card */}
            <a
              href="mailto:info@zadcapital.sa"
              className="rounded-2xl bg-[#1E293B]/90 border border-white/5 p-5 flex items-center gap-4 transition hover:scale-[1.02]"
            >
              <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/5 text-white">
                <Mail size={18} />
              </div>
              <div>
                <span className="text-[12px] text-white/50 font-bold block">البريد الإلكتروني</span>
                <strong className="text-[15px] font-black text-white block mt-0.5">info@zadcapital.sa</strong>
              </div>
            </a>

            {/* Location Card */}
            <div className="rounded-2xl bg-[#1E293B]/90 border border-white/5 p-5 flex items-center gap-4">
              <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/5 text-white">
                <MapPin size={18} />
              </div>
              <div>
                <span className="text-[12px] text-white/50 font-bold block">الموقع</span>
                <strong className="text-[15px] font-black text-white block mt-0.5">الرياض، السعودية</strong>
              </div>
            </div>

          </div>
        </div>
      </section>

      {/* 2. Main Layout Section */}
      <section className="w-full bg-[#F3F4F6] pb-16 pt-16">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
            {/* Form Column (Right on desktop) */}
            <div className="lg:col-span-8 order-1 lg:order-2">
              <form
                onSubmit={handleSubmit}
                className="rounded-3xl border border-[#E5E9F0] bg-white shadow-sm overflow-hidden text-start"
              >
                {/* Form Card Header Banner */}
                <div className="bg-[#1E293B] px-8 py-6 text-white">
                  <span className="text-[11px] font-extrabold text-[#EDC98E] uppercase tracking-wider block mb-1">
                    نموذج التواصل
                  </span>
                  <h3 className="text-[20px] font-black leading-tight text-white">
                    أرسل لنا رسالة
                  </h3>
                  <p className="text-[12px] text-white/60 font-semibold mt-1">
                    سنجيب عليك خلال ساعات عمل
                  </p>
                </div>

                <div className="p-8">
                  {/* Name and Phone side-by-side */}
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div className="flex flex-col text-start">
                      <label className="text-[13px] font-bold text-[#374151] mb-2">
                        الاسم الكامل <span className="text-red-500">*</span>
                      </label>
                      <input
                        type="text"
                        required
                        value={values.fullName}
                        onChange={(e) => updateField("fullName", e.target.value)}
                        placeholder="محمد عبدالله"
                        className={inputClasses}
                      />
                    </div>
                    <div className="flex flex-col text-start">
                      <label className="text-[13px] font-bold text-[#374151] mb-2">
                        رقم الجوال <span className="text-red-500">*</span>
                      </label>
                      <input
                        type="tel"
                        required
                        value={values.phone}
                        onChange={(e) => updateField("phone", e.target.value)}
                        placeholder="050 000 0000"
                        className={`${inputClasses} text-start`}
                        dir="ltr"
                      />
                    </div>
                  </div>

                  {/* Email */}
                  <div className="mt-6 flex flex-col text-start">
                    <label className="text-[13px] font-bold text-[#374151] mb-2">
                      البريد الإلكتروني
                    </label>
                    <input
                      type="email"
                      value={values.email}
                      onChange={(e) => updateField("email", e.target.value)}
                      placeholder="name@domain.com"
                      className={`${inputClasses} text-start`}
                      dir="ltr"
                    />
                  </div>

                  {/* Subject Tag Selection */}
                  <div className="mt-6 flex flex-col text-start">
                    <label className="text-[13px] font-bold text-[#374151] mb-2">
                      موضوع الرسالة <span className="text-red-500">*</span>
                    </label>
                    <div className="flex flex-wrap gap-2">
                      {subjects.map((subj) => {
                        const isSelected = values.subject === subj;
                        return (
                          <button
                            key={subj}
                            type="button"
                            onClick={() => updateField("subject", subj)}
                            className={`px-4 py-2 text-[13px] font-bold rounded-xl transition-all duration-300 border ${
                              isSelected
                                ? "bg-[#0F172A] border-[#0F172A] text-white scale-105"
                                : "bg-[#F8FAFC] border-[#E2E8F0] text-gray-500 hover:border-gray-400"
                            }`}
                          >
                            {subj}
                          </button>
                        );
                      })}
                    </div>
                  </div>

                  {/* Message textarea */}
                  <div className="mt-6 flex flex-col text-start">
                    <label className="text-[13px] font-bold text-[#374151] mb-2">
                      الرسالة <span className="text-red-500">*</span>
                    </label>
                    <textarea
                      required
                      value={values.message}
                      maxLength={maxMessageLength}
                      onChange={(e) => updateField("message", e.target.value)}
                      placeholder="اكتب تفاصيل رسالتك هنا..."
                      className={`${inputClasses} min-h-[140px] resize-none py-4 leading-relaxed`}
                    />
                    <div className="mt-2 text-start text-[11px] text-[#8A8F99] font-semibold">
                      {values.message.length} / {maxMessageLength} {t("contactPage.contactUs.charCount")}
                    </div>
                  </div>

                  {/* Form actions */}
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
                      href={`https://wa.me/${whatsappNumber}?text=استفسار من نموذج الاتصال بشأن موضوع: ${encodeURIComponent(values.subject)}`}
                      target="_blank"
                      rel="noreferrer"
                      className="h-[50px] px-6 bg-[#25D366] text-white text-[15px] font-extrabold rounded-xl flex items-center justify-center gap-2 transition hover:bg-[#20ba59] hover:scale-[1.01] active:scale-95 shadow-sm"
                    >
                      <MessageCircle size={16} />
                      <span>واتساب</span>
                    </a>
                  </div>
                </div>
              </form>
            </div>

            {/* Sidebar Column (Left on desktop) */}
            <div className="lg:col-span-4 order-2 lg:order-1 flex flex-col gap-6 text-start">
              {branches.map((branch, index) => (
                <div key={index} className="flex flex-col gap-6 w-full">
                  {/* Map Card */}
                  <div className="rounded-3xl border border-[#E5E9F0] bg-white p-6 shadow-sm flex flex-col items-center text-center">
                    <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-[#EAF1FA] text-[#034EA2] mb-4">
                      <MapPin size={22} />
                    </div>
                    <strong className="text-[17px] font-black text-[#0F172A]">{branch.name}</strong>
                    <span className="text-[13px] text-gray-400 font-bold mt-1">{branch.address}</span>
                    
                    {branch.map_link && (
                      <a
                        href={branch.map_link}
                        target="_blank"
                        rel="noreferrer"
                        className="mt-5 w-full h-11 border border-[#E5E9F0] text-[#0F172A] text-[13px] font-bold rounded-xl flex items-center justify-center gap-1.5 transition hover:bg-[#F8FAFC] active:scale-95"
                      >
                        <span>افتح في خرائط جوجل</span>
                        <ArrowRightIcon />
                      </a>
                    )}
                  </div>

                  {/* Working Hours Card */}
                  {branch.working_hours && (
                    <div className="rounded-3xl bg-[#0F172A] p-6 text-white shadow-md flex flex-col relative overflow-hidden">
                      <div className="absolute top-0 right-0 w-24 h-24 bg-[#EDC98E]/5 blur-2xl rounded-full" />
                      
                      <div className="flex items-center gap-3 border-b border-white/5 pb-4 mb-4">
                        <Clock size={20} className="text-[#EDC98E]" />
                        <strong className="text-[16px] font-black text-[#EDC98E]">ساعات العمل</strong>
                      </div>

                      <div className="flex flex-col gap-3.5">
                        {branch.working_hours.split("\n").map((line, lIdx) => {
                          const parts = line.split(":");
                          if (parts.length >= 2) {
                            const day = parts[0].trim();
                            const hours = parts.slice(1).join(":").trim();
                            return (
                              <div key={lIdx} className="flex items-center justify-between text-[13px] font-semibold text-white/80">
                                <span>{day}</span>
                                <strong className="text-white">{hours}</strong>
                              </div>
                            );
                          }
                          return (
                            <div key={lIdx} className="text-[13px] font-semibold text-white/80">
                              {line}
                            </div>
                          );
                        })}
                      </div>

                      {/* Footer status */}
                      <div className="mt-6 pt-4 border-t border-white/5 flex items-center justify-start gap-2.5">
                        <span className="relative flex h-2 w-2">
                          <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span className="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span className="text-[12px] text-emerald-400 font-bold">واتساب متاح 24/7</span>
                      </div>
                    </div>
                  )}
                </div>
              ))}
            </div>

          </div>
        </div>
      </section>

    </div>
  );
}

function ArrowRightIcon() {
  return (
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
      <line x1="5" y1="12" x2="19" y2="12" />
      <polyline points="12 5 19 12 12 19" />
    </svg>
  );
}
