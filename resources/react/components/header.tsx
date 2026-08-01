import { NavLink, useLocation } from "react-router-dom";
import { useLanguageStore } from "../store/language.store";
import type { IHeaderProps } from "../interfaces/IHeaderProps";

function isActivePath(path: string, currentPath: string): boolean {
  if (path === "/") return currentPath === "/";
  return currentPath.startsWith(path);
}

export default function Header({
  logoSrc,
  logoAlt = "Logo",
  navItems,
  ctaText,
  ctaPath,
}: IHeaderProps) {
  const direction = useLanguageStore((s) => s.direction);
  const { pathname } = useLocation();

  return (
    <header className="w-full bg-white border-b border-[#E5E9F0] shadow-sm">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
          className="h-[76px] flex items-center justify-between gap-6"
          dir={direction}
        >
          {/* Logo */}
          <NavLink to="/" className="flex items-center shrink-0">
            <img
              src={logoSrc}
              alt={logoAlt}
              className="h-[52px] w-auto object-contain"
              loading="lazy"
            />
          </NavLink>

          {/* Navigation */}
          <nav className="hidden md:flex items-center justify-center gap-7 lg:gap-9 text-[15px]">
            {navItems.map((item) => {
              const isActive = isActivePath(item.path, pathname);
              return (
                <NavLink
                  key={item.path}
                  to={item.path}
                  className={`relative py-1 transition-colors ${
                    isActive
                      ? "text-[#0F172A] font-extrabold after:absolute after:bottom-[-2px] after:left-0 after:right-0 after:h-[2.5px] after:bg-[#0F172A] after:rounded-full"
                      : "text-[#64748B] hover:text-[#0F172A] font-bold"
                  }`}
                >
                  {item.label}
                </NavLink>
              );
            })}
          </nav>

          {/* CTA Button - Golden Amber Pill */}
          <NavLink
            to={ctaPath}
            className="inline-flex h-[44px] items-center justify-center rounded-full bg-[#E5C287] px-7 text-[15px] font-extrabold text-[#0A1628] shadow-sm transition hover:bg-[#D8B478] hover:shadow"
          >
            {ctaText}
          </NavLink>
        </div>
      </div>
    </header>
  );
}
