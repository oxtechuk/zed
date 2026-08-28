import { Link } from "react-router-dom";
import { useLanguageStore } from "../store/language.store";

export default function RequestCarWidget() {
  const direction = useLanguageStore((s) => s.direction);
  const language = useLanguageStore((s) => s.language);

  const tooltipText = language === "ar" ? "طلب سيارة جديدة" : "Request a Car";
  const badgeText = language === "ar" ? "طلب سيارة" : "Request Car";

  return (
    <div
      className={`fixed z-50 transition-all duration-300 ${
        direction === "rtl" ? "left-6 md:left-8" : "right-6 md:right-8"
      } bottom-40 md:bottom-24`}
    >
      {/* Pulse Ring Glow */}
      <span className="absolute -inset-1 rounded-full bg-blue-500 opacity-30 animate-ping pointer-events-none"></span>

      {/* Action Button */}
      <Link
        to="/request-car"
        className="group relative flex items-center justify-center w-14 h-14 bg-gradient-to-tr from-[#0F172A] via-[#1E293B] to-[#2563EB] text-white rounded-full shadow-xl border border-blue-400/30 hover:border-blue-400 hover:shadow-blue-500/25 hover:shadow-2xl transition-all duration-300 hover:scale-110 active:scale-95"
        title={tooltipText}
      >
        {/* Car Icon */}
        <svg
          className="w-7 h-7 text-white transition-transform duration-300 group-hover:scale-110"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          strokeWidth="1.8"
          strokeLinecap="round"
          strokeLinejoin="round"
        >
          <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2" />
          <circle cx="7" cy="17" r="2" />
          <path d="M9 17h6" />
          <circle cx="17" cy="17" r="2" />
        </svg>

        {/* Small Notification Dot / Sparkle */}
        <span className="absolute -top-1 -right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full flex items-center justify-center">
          <span className="w-1.5 h-1.5 bg-white rounded-full"></span>
        </span>

        {/* Hover Tooltip Pill */}
        <span
          className={`absolute hidden md:group-hover:flex items-center gap-1.5 bg-[#0F172A]/95 text-white text-xs font-bold px-3 py-2 rounded-xl shadow-xl border border-slate-700/60 whitespace-nowrap -top-12 transition-all duration-300`}
        >
          <svg className="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
          {badgeText}
        </span>
      </Link>
    </div>
  );
}
