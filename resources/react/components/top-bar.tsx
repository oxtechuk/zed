import { useTranslation } from "react-i18next";
import { Phone, Mail, MapPin, Globe } from "lucide-react";
import type { ITopBarProps } from "../interfaces/ITopBar";
import { useSettingsStore } from "../store/settings.store";

export default function TopBar({
  phone: propPhone,
  email: propEmail,
  location: propLocation,
  onLanguageToggle,
}: ITopBarProps) {
  const { t, i18n } = useTranslation();
  const settings = useSettingsStore((s) => s.settings);
  const phone = settings?.contact?.phone || propPhone;
  const email = settings?.contact?.email || propEmail;
  const location = settings?.contact?.address || propLocation;

  return (
    <div className="hidden md:block w-full bg-[#051023] text-white text-xs py-2 border-b border-white/10">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between gap-4">
          {/* Contact Info (Phone & Email) */}
          <div className="flex items-center gap-6" dir={i18n.dir()}>
            {phone && (
              <a href={`tel:${phone}`} className="flex items-center gap-2 hover:text-[#EDC98E] transition-colors">
                <Phone size={14} className="text-[#EDC98E]" />
                <span dir="ltr" className="font-semibold">{phone}</span>
              </a>
            )}
            {email && (
              <a href={`mailto:${email}`} className="flex items-center gap-2 hover:text-[#EDC98E] transition-colors">
                <Mail size={14} className="text-[#EDC98E]" />
                <span className="font-semibold">{email}</span>
              </a>
            )}
          </div>

          {/* Location & Language Toggle */}
          <div className="flex items-center gap-6" dir={i18n.dir()}>
            {location && (
              <div className="flex items-center gap-2 text-white/80">
                <MapPin size={14} className="text-[#EDC98E]" />
                <span className="font-semibold">{location}</span>
              </div>
            )}
            <button
              type="button"
              onClick={onLanguageToggle}
              className="flex items-center gap-1.5 rounded-lg border border-white/20 bg-white/5 px-2.5 py-1 text-xs font-bold text-white transition hover:bg-white/15 hover:border-white/40 active:scale-95"
            >
              <Globe size={13} className="text-[#EDC98E]" />
              <span>{i18n.language === "ar" ? "English" : "العربية"}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
