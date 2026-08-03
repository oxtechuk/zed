import { useTranslation } from "react-i18next";
import { Mail, MapPin, Phone } from "lucide-react";
import { NavLink } from "react-router-dom";
import type { IFooterProps } from "../interfaces/IFooterProps";
import { useSettingsStore } from "../store/settings.store";
import { useLanguageStore } from "../store/language.store";
import { getSocialIcon } from "../utils/social-icons";

interface FooterColumnLinkProps {
  label: string;
  to: string;
}

function FooterColumnLink({ label, to }: FooterColumnLinkProps) {
  return (
    <NavLink
      to={to}
      className="block text-[14px] text-white/65 transition hover:text-white"
    >
      {label}
    </NavLink>
  );
}

function FooterColTitle({ title }: { title: string }) {
  return (
    <h3 className="mb-5 text-[15px] font-bold text-white">{title}</h3>
  );
}

export default function Footer({
  logoSrc,
  logoAlt = "Logo",
  quickLinks,
  socialLinks: propSocialLinks,
  phone: propPhone,
  email: propEmail,
  address: propAddress,
  copyright: propCopyright,
}: IFooterProps) {
  const { t } = useTranslation();
  const direction = useLanguageStore((s) => s.direction);
  const settings = useSettingsStore((s) => s.settings);

  const phone = settings?.contact?.phone ?? propPhone;
  const email = settings?.contact?.email ?? propEmail;
  const address = settings?.contact?.address ?? propAddress;
  const copyright = settings?.footer_text ?? propCopyright;
  const description = settings?.footer_description ?? t("footer.description", {
    defaultValue:
      "المنصة الأولى لتمويل السيارات الفاخرة في المملكة العربية السعودية — تجمع بين الخبرة المالية والشغف بالسيارات.",
  });
  const socialLinks = settings?.social_media?.length
    ? settings.social_media.map((sm) => ({
        name: sm.icon,
        icon: sm.icon,
        url: sm.link,
      }))
    : propSocialLinks;

  // Services links (static list — can be made dynamic later)
  const serviceLinks: FooterColumnLinkProps[] = [
    { label: t("footer.services.finance", { defaultValue: "تمويل السيارات" }), to: "/finance-calculator" },
    { label: t("footer.services.cash", { defaultValue: "شراء نقدي" }), to: "/cars" },
    { label: t("footer.services.booking", { defaultValue: "حجز السيارات" }), to: "/contact" },
    { label: t("footer.services.custom", { defaultValue: "طلب مخصص" }), to: "/contact" },
  ];

  return (
    <footer className="w-full bg-[#080E1E] text-white pb-[96px] lg:pb-0">
      <div className="mx-auto max-w-[1440px] px-6 lg:px-[92px]">
        {/* ── Main Grid ── */}
        <div
          className="grid grid-cols-1 gap-10 py-14 sm:grid-cols-2 lg:grid-cols-4 lg:gap-14 lg:py-[72px]"
          dir={direction}
        >
          {/* Col 1 – Logo + Description + Social */}
          <div className="flex flex-col items-start">
            <img
              src={logoSrc}
              alt={logoAlt}
              className="mb-5 h-[56px] w-auto object-contain"
              loading="lazy"
            />
            <p className="text-[13px] leading-7 text-white/55">{description}</p>

            {/* Social Icons */}
            <div className="mt-6 flex items-center gap-3">
              {(socialLinks ?? []).map((social) => (
                <a
                  key={social.name}
                  href={social.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label={social.name}
                  className="flex h-[40px] w-[40px] items-center justify-center rounded-full bg-white/10 transition hover:bg-[var(--brand-primary-color)] hover:scale-105"
                >
                  {getSocialIcon(social.icon)}
                </a>
              ))}
            </div>
          </div>

          {/* Col 2 – خدمات */}
          <div>
            <FooterColTitle title={t("footer.services.title", { defaultValue: "الخدمات" })} />
            <div className="flex flex-col gap-3">
              {serviceLinks.map((link) => (
                <FooterColumnLink key={link.to + link.label} {...link} />
              ))}
            </div>
          </div>

          {/* Col 3 – الشركة (quick links) */}
          <div>
            <FooterColTitle title={t("footer.company", { defaultValue: "الشركة" })} />
            <div className="flex flex-col gap-3">
              {quickLinks.map((link) => (
                <FooterColumnLink key={link.to} label={link.label} to={link.to} />
              ))}
            </div>
          </div>

          {/* Col 4 – تواصل معنا */}
          <div>
            <FooterColTitle title={t("footer.contactUs")} />
            <div className="flex flex-col gap-4">
              {phone && (
                <a
                  href={`tel:${phone}`}
                  className="flex items-center gap-3 text-[14px] text-white/65 transition hover:text-white"
                  dir="ltr"
                >
                  <span className="flex h-[36px] w-[36px] shrink-0 items-center justify-center rounded-full bg-white/10">
                    <Phone size={16} />
                  </span>
                  <span className="text-right">{phone}</span>
                </a>
              )}
              {email && (
                <a
                  href={`mailto:${email}`}
                  className="flex items-center gap-3 text-[14px] text-white/65 transition hover:text-white"
                >
                  <span className="flex h-[36px] w-[36px] shrink-0 items-center justify-center rounded-full bg-white/10">
                    <Mail size={16} />
                  </span>
                  <span>{email}</span>
                </a>
              )}
              {address && (
                <div className="flex items-start gap-3 text-[14px] text-white/65">
                  <span className="mt-0.5 flex h-[36px] w-[36px] shrink-0 items-center justify-center rounded-full bg-white/10">
                    <MapPin size={16} />
                  </span>
                  <span>{address}</span>
                </div>
              )}
            </div>
          </div>
        </div>

        {/* ── Bottom Bar ── */}
        <div className="border-t border-white/10 py-6">
          <div
            className="flex flex-col items-center justify-between gap-4 text-[13px] text-white/40 md:flex-row"
            dir={direction}
          >
            <p>{copyright}</p>

            <div className="flex items-center gap-6">
              <NavLink to="/privacy" className="transition hover:text-white">
                {t("footer.privacyPolicy")}
              </NavLink>
              <NavLink to="/terms" className="transition hover:text-white">
                {t("footer.termsAndConditions")}
              </NavLink>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
}