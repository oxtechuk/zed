import type { ReactNode } from "react";
import { useTranslation } from "react-i18next";
import {
  Mail,
  MapPin,
  Phone,
} from "lucide-react";
import { NavLink } from "react-router-dom";

import type { IFooterProps } from "../interfaces/IFooterProps";
import { useSettingsStore } from "../store/settings.store";
import { useLanguageStore } from "../store/language.store";
import { getSocialIcon } from "../utils/social-icons";
import LazyImg from "./LazyImg";

interface FooterLink {
  label: string;
  to: string;
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

  const direction = useLanguageStore(
    (state) => state.direction,
  );

  const settings = useSettingsStore(
    (state) => state.settings,
  );

  const phone =
    settings?.contact?.phone ??
    propPhone;

  const email =
    settings?.contact?.email ??
    propEmail;

  const address =
    settings?.contact?.address ??
    propAddress;

  const copyright =
    settings?.footer_text ??
    propCopyright;

  const description =
    settings?.footer_description ??
    t("footer.description", {
      defaultValue:
        "المنصة الأولى لتمويل السيارات الفاخرة في المملكة العربية السعودية — تجمع بين الخبرة المالية والشغف بالسيارات.",
    });

  const socialLinks =
    settings?.social_media?.length
      ? settings.social_media.map(
          (social) => ({
            name:
              social.link ??
              social.icon ??
              "",
            icon:
              social.icon ??
              "",
            url:
              social.link ??
              "",
          }),
        )
      : propSocialLinks;

  const serviceLinks: FooterLink[] = [
    {
      label: t(
        "footer.services.finance",
        {
          defaultValue:
            "تمويل السيارات",
        },
      ),
      to: "/finance-calculator",
    },
    {
      label: t(
        "footer.services.cash",
        {
          defaultValue: "شراء نقدي",
        },
      ),
      to: "/cars",
    },
    {
      label: t(
        "footer.services.booking",
        {
          defaultValue:
            "حجز السيارات",
        },
      ),
      to: "/contact",
    },
    {
      label: t(
        "footer.services.custom",
        {
          defaultValue: "طلب مخصص",
        },
      ),
      to: "/contact",
    },
  ];

  return (
    <footer
      dir={direction}
      className="w-full bg-[#080E1E] pb-[96px] text-white lg:pb-0"
    >
      <div className="mx-auto max-w-[1440px] px-5 sm:px-8 lg:px-12 xl:px-[58px]">
        {/* Main footer */}
        <div
          className={[
            "grid grid-cols-1 gap-12",
            "pb-20 pt-16",
            "sm:grid-cols-2",
            "lg:grid-cols-[1.25fr_0.75fr_0.75fr_1fr]",
            "lg:gap-16 lg:pb-[92px] lg:pt-[72px]",
          ].join(" ")}
        >
          {/* Brand */}
          <section className="flex min-w-0 flex-col items-start text-start">
            {logoSrc && (
              <NavLink
                to="/"
                className="inline-flex"
                aria-label={logoAlt}
              >
                <LazyImg
                  src={logoSrc}
                  alt={logoAlt}
                  className="h-[54px] w-auto max-w-[120px] object-contain"
                />
              </NavLink>
            )}

            <p className="mt-7 max-w-[310px] text-[14px] leading-8 text-white/45">
              {description}
            </p>

            {socialLinks &&
              socialLinks.length > 0 && (
                <div className="mt-7 flex items-center gap-4">
                  {socialLinks.map(
                    (social, index) => {
                      if (!social.url) {
                        return null;
                      }

                      return (
                        <a
                          key={`${social.name}-${index}`}
                          href={social.url}
                          target="_blank"
                          rel="noopener noreferrer"
                          aria-label={
                            social.name ||
                            `Social ${index + 1}`
                          }
                          className={[
                            "flex h-[40px] w-[40px]",
                            "items-center justify-center",
                            "rounded-full",
                            "transition duration-300",
                            "hover:-translate-y-1",
                            "hover:scale-105",
                          ].join(" ")}
                        >
                          {getSocialIcon(
                            social.icon,
                          )}
                        </a>
                      );
                    },
                  )}
                </div>
              )}
          </section>

          {/* Services */}
          <FooterColumn
            title={t(
              "footer.services.title",
              {
                defaultValue:
                  "الخدمات",
              },
            )}
          >
            <nav className="flex flex-col items-start gap-4">
              {serviceLinks.map((link) => (
                <FooterColumnLink
                  key={`${link.to}-${link.label}`}
                  label={link.label}
                  to={link.to}
                />
              ))}
            </nav>
          </FooterColumn>

          {/* Company */}
          <FooterColumn
            title={t("footer.company", {
              defaultValue: "الشركة",
            })}
          >
            <nav className="flex flex-col items-start gap-4">
              {quickLinks.map((link) => (
                <FooterColumnLink
                  key={`${link.to}-${link.label}`}
                  label={link.label}
                  to={link.to}
                />
              ))}
            </nav>
          </FooterColumn>

          {/* Contact */}
          <FooterColumn
            title={t(
              "footer.contactUs",
              {
                defaultValue:
                  "تواصل معنا",
              },
            )}
          >
            <div className="flex flex-col items-start gap-5">
              {phone && (
                <ContactItem
                  icon={
                    <Phone
                      size={17}
                      strokeWidth={1.8}
                    />
                  }
                  value={phone}
                  href={`tel:${normalizePhone(
                    phone,
                  )}`}
                  dir="ltr"
                />
              )}

              {email && (
                <ContactItem
                  icon={
                    <Mail
                      size={17}
                      strokeWidth={1.8}
                    />
                  }
                  value={email}
                  href={`mailto:${email}`}
                />
              )}

              {address && (
                <ContactItem
                  icon={
                    <MapPin
                      size={17}
                      strokeWidth={1.8}
                    />
                  }
                  value={address}
                />
              )}
            </div>
          </FooterColumn>
        </div>

        {/* Bottom */}
        <div className="border-t border-white/[0.055] py-8">
          <div className="flex flex-col items-center justify-between gap-5 text-center text-[12px] text-white/25 md:flex-row md:text-start">
            {/* Policies */}
            <div className="flex flex-wrap items-center justify-center gap-x-8 gap-y-3">
              <NavLink
                to="/terms"
                className="transition-colors duration-300 hover:text-white/70"
              >
                {t(
                  "footer.termsAndConditions",
                )}
              </NavLink>

              <NavLink
                to="/privacy"
                className="transition-colors duration-300 hover:text-white/70"
              >
                {t(
                  "footer.privacyPolicy",
                )}
              </NavLink>
            </div>

            {/* Copyright */}
            <p>{copyright}</p>
          </div>
        </div>
      </div>
    </footer>
  );
}

interface FooterColumnProps {
  title: string;
  children: ReactNode;
}

function FooterColumn({
  title,
  children,
}: FooterColumnProps) {
  return (
    <section className="flex min-w-0 flex-col items-start">
      <h3 className="mb-7 text-start text-[15px] font-bold text-white/80">
        {title}
      </h3>

      <div className="w-full">
        {children}
      </div>
    </section>
  );
}

interface FooterColumnLinkProps {
  label: string;
  to: string;
}

function FooterColumnLink({
  label,
  to,
}: FooterColumnLinkProps) {
  return (
    <NavLink
      to={to}
      className="text-[14px] text-white/38 transition-colors duration-300 hover:text-white/75"
    >
      {label}
    </NavLink>
  );
}

interface ContactItemProps {
  icon: ReactNode;
  value: string;
  href?: string;
  dir?: "ltr" | "rtl" | "auto";
}

function ContactItem({
  icon,
  value,
  href,
  dir = "auto",
}: ContactItemProps) {
  const content = (
    <div className="group flex items-center gap-3">
      <span className="flex h-[22px] w-[22px] shrink-0 items-center justify-center text-[var(--brand-secondary-color)]">
        {icon}
      </span>

      <span
        dir={dir}
        className="break-words text-start text-[14px] leading-6 text-white/38 transition-colors duration-300 group-hover:text-white/70"
      >
        {value}
      </span>
    </div>
  );

  if (!href) {
    return content;
  }

  return (
    <a
      href={href}
      className="inline-flex"
    >
      {content}
    </a>
  );
}

function normalizePhone(
  phone: string,
): string {
  return phone.replace(/[^\d+]/g, "");
}