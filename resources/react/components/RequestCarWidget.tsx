import { Link } from "react-router-dom";
import { useLanguageStore } from "../store/language.store";
import { useSettingsStore } from "../store/settings.store";
import { getImageUrl } from "../constants/app-images";

export default function RequestCarWidget() {
  const direction = useLanguageStore((s) => s.direction);
  const language = useLanguageStore((s) => s.language);
  const settings = useSettingsStore((s) => s.settings);

  const tooltipText = language === "ar" ? "طلب سيارة مخصص لك" : "Request a Custom Car";
  const badgeText = language === "ar" ? "طلب سيارة" : "Request Car";

  // Check if custom uploaded SVG/icon exists from Settings
  const customIconUrl = settings?.request_car_icon ? getImageUrl(settings.request_car_icon) : null;

  return (
    <div
      className={`fixed z-50 transition-all duration-300 ${
        direction === "rtl" ? "left-6 md:left-8" : "right-6 md:right-8"
      } bottom-40 md:bottom-24`}
    >
      {/* Golden/Blue Ambient Pulse */}
      <span className="absolute -inset-1 rounded-full bg-[#EDC98E] opacity-30 animate-ping pointer-events-none"></span>

      {/* Action Button */}
      <Link
        to="/request-car"
        className="group relative flex items-center justify-center w-14 h-14 bg-[#051023] text-white rounded-full shadow-2xl border-2 border-[#EDC98E]/50 hover:border-[#EDC98E] hover:shadow-[#EDC98E]/30 hover:shadow-2xl transition-all duration-300 hover:scale-110 active:scale-95 overflow-visible"
        title={tooltipText}
      >
        {customIconUrl ? (
          <img
            src={customIconUrl}
            alt="Request Car"
            className="w-8 h-8 object-contain transition-transform duration-300 group-hover:scale-110"
          />
        ) : (
          /* Geometric Golden Zad Monogram SVG */
          <svg
            className="w-8 h-8 transition-transform duration-300 group-hover:scale-110"
            viewBox="0 0 100 100"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <defs>
              <linearGradient id="zadGoldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stopColor="#FFF2D1" />
                <stop offset="30%" stopColor="#F5D089" />
                <stop offset="70%" stopColor="#D8A952" />
                <stop offset="100%" stopColor="#B38029" />
              </linearGradient>
              <linearGradient id="zadGoldGradSoft" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" stopColor="#FDE6B0" />
                <stop offset="100%" stopColor="#D29E40" />
              </linearGradient>
            </defs>

            {/* Top Dot / Square Accent */}
            <rect x="22" y="44" width="16" height="15" rx="2" fill="url(#zadGoldGrad)" />

            {/* Left Vertical Bar (Zad Accent) */}
            <rect x="38" y="32" width="13" height="34" rx="2.5" fill="url(#zadGoldGradSoft)" />

            {/* Center Vertical Pillar */}
            <rect x="56" y="24" width="13" height="42" rx="2.5" fill="url(#zadGoldGrad)" />

            {/* Right Vertical Pillar */}
            <rect x="74" y="24" width="13" height="42" rx="2.5" fill="url(#zadGoldGradSoft)" />

            {/* Bottom Horizontal Base Bar */}
            <rect x="22" y="69" width="65" height="13" rx="2.5" fill="url(#zadGoldGrad)" />
          </svg>
        )}

        {/* Small Notification Indicator Ring */}
        <span className="absolute -top-1 -right-1 flex h-4 w-4">
          <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
          <span className="relative inline-flex rounded-full h-4 w-4 bg-red-600 border-2 border-[#051023] justify-center items-center">
            <span className="w-1.5 h-1.5 bg-white rounded-full"></span>
          </span>
        </span>

        {/* Hover Tooltip Pill */}
        <span
          className={`absolute hidden md:group-hover:flex items-center gap-1.5 bg-[#051023]/95 text-white text-xs font-bold px-3.5 py-2 rounded-xl shadow-2xl border border-[#EDC98E]/40 whitespace-nowrap -top-12 transition-all duration-300`}
        >
          <span className="w-2 h-2 rounded-full bg-[#EDC98E]"></span>
          {badgeText}
        </span>
      </Link>
    </div>
  );
}
