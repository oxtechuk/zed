import { useState } from "react";
import { NavLink, useLocation } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { useLanguageStore } from "../store/language.store";
import { useSettingsStore } from "../store/settings.store";
import { mobileNavItems } from "../constants/navigation";
import { APP_IMAGES, getImageUrl } from "../constants/app-images";
import { X, Info, Phone, Newspaper, Languages, MapPin } from "lucide-react";
import LazyImg from "./LazyImg";

const menuLinks = [
  { labelKey: "nav.about", to: "/about", icon: Info },
  { labelKey: "nav.contact", to: "/contact", icon: Phone },
  { labelKey: "mobileNav.blog", to: "/blog", icon: Newspaper },
];

export default function MobileBottomNav() {
  const { t } = useTranslation();
  const direction = useLanguageStore((s) => s.direction);
  const location = useLocation();
  const settings = useSettingsStore((s) => s.settings);
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const isRTL = direction === "rtl";

  return (
    <>
      <nav
        dir={direction}
        className="fixed bottom-0 left-0 right-0 z-50 block md:hidden"
      >
        <div className="relative mx-4 mb-4 flex h-[72px] items-center justify-around rounded-[24px] bg-[#0F172A]/90 backdrop-blur-lg px-2 shadow-[0_20px_50px_rgba(15,23,42,0.3)] border border-white/10">
          {mobileNavItems.map((item) => {
            const Icon = item.icon;

            const isActive =
              item.to === "/"
                ? location.pathname === "/"
                : item.isMenu
                  ? menuLinks.some((link) => location.pathname === link.to)
                  : location.pathname.startsWith(item.to);

            const baseItemClass =
              "relative flex h-full flex-1 flex-col items-center justify-center text-center transition-all duration-300 w-16 focus:outline-none";

            if (item.isMenu) {
              return (
                <button
                  key={item.to}
                  type="button"
                  onClick={() => setSidebarOpen(true)}
                  className={baseItemClass}
                >
                  <div
                    className={`transition-all duration-300 ${isActive
                        ? "text-[#EDC98E] scale-110 -translate-y-1"
                        : "text-slate-400 hover:text-white"
                      }`}
                  >
                    <Icon size={22} strokeWidth={2} />
                  </div>

                  <span
                    className={`text-[10px] font-bold mt-1 transition-all duration-300 ${isActive ? "text-[#EDC98E]" : "text-slate-400"
                      }`}
                  >
                    {t(item.labelKey)}
                  </span>

                  {isActive && (
                    <span className="absolute bottom-1 w-1 h-1 rounded-full bg-[#EDC98E] shadow-[0_0_8px_#EDC98E] animate-pulse" />
                  )}
                </button>
              );
            }

            return (
              <NavLink key={item.to} to={item.to} className={baseItemClass}>
                <div
                  className={`transition-all duration-300 ${isActive
                      ? "text-[#EDC98E] scale-110 -translate-y-1"
                      : "text-slate-400 hover:text-white"
                    }`}
                >
                  <Icon size={22} strokeWidth={2} />
                </div>

                <span
                  className={`text-[10px] font-bold mt-1 transition-all duration-300 ${isActive ? "text-[#EDC98E]" : "text-slate-400"
                    }`}
                >
                  {t(item.labelKey)}
                </span>

                {isActive && (
                  <span className="absolute bottom-1 w-1 h-1 rounded-full bg-[#EDC98E] shadow-[0_0_8px_#EDC98E] animate-pulse" />
                )}
              </NavLink>
            );
          })}
        </div>
      </nav>

      {sidebarOpen && (
        <div
          className="fixed inset-0 z-[60] bg-black/40 md:hidden"
          onClick={() => setSidebarOpen(false)}
        />
      )}

      <div
        className={`fixed top-0 bottom-0 z-[70] w-[75vw] max-w-[320px] bg-white shadow-2xl transition-transform duration-300 md:hidden ${isRTL ? "right-0" : "left-0"
          } ${sidebarOpen
            ? "translate-x-0"
            : isRTL
              ? "translate-x-full"
              : "-translate-x-full"
          }`}
      >
        <div className="flex h-full flex-col">
          <div className="flex items-center justify-between px-5 pt-5 pb-3">
            <LazyImg
              src={getImageUrl(settings?.logo ?? null) || APP_IMAGES.LOGO}
              alt="Zed Capital"
              className="h-20 w-auto object-contain"
            />
            <button
              type="button"
              onClick={() => setSidebarOpen(false)}
              className="text-[#34495E]"
            >
              <X size={22} />
            </button>
          </div>

          <div className="flex-1 overflow-y-auto px-5 pb-6">
            <div className="flex flex-col gap-1">
              {menuLinks.map((link) => {
                const LinkIcon = link.icon;
                const isActive = location.pathname === link.to;

                return (
                  <NavLink
                    key={link.to}
                    to={link.to}
                    onClick={() => setSidebarOpen(false)}
                    className={`flex items-center gap-3 rounded-xl px-4 py-3.5 text-[15px] font-bold leading-none transition ${isActive
                        ? "bg-[var(--brand-primary-color)]/10 text-[var(--brand-primary-color)]"
                        : "text-[#07111F] hover:bg-[#F0F2F5]"
                      }`}
                  >
                    <LinkIcon size={20} strokeWidth={2} />
                    {t(link.labelKey)}
                  </NavLink>
                );
              })}
            </div>

            <div className="mt-6 border-t border-[#D9DEE7] pt-4 flex flex-col gap-1">
              <span className="flex items-center gap-3 rounded-xl px-4 py-3.5 text-[15px] font-bold leading-none text-[#07111F]">
                <MapPin size={20} strokeWidth={2} />
                {t("topbar.locationValue")}
              </span>
              <button
                type="button"
                onClick={() => {
                  const { language, setLanguage } = useLanguageStore.getState();
                  setLanguage(language === "en" ? "ar" : "en");
                  setSidebarOpen(false);
                }}
                className="flex w-full items-center gap-3 rounded-xl px-4 py-3.5 text-[15px] font-bold leading-none text-[#07111F] transition hover:bg-[#F0F2F5]"
              >
                <Languages size={20} strokeWidth={2} />
                {t("topbar.language")}
              </button>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
