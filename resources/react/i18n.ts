import i18n from "i18next";
import { initReactI18next } from "react-i18next";
import HttpBackend from "i18next-http-backend";

const savedLang = localStorage.getItem("language") || "ar";
const base = import.meta.env.BASE_URL || "/";
const cleanBase = base.endsWith("/") ? base : `${base}/`;

i18n
  .use(HttpBackend)
  .use(initReactI18next)
  .init({
    lng: savedLang,
    fallbackLng: "en",
    debug: true,
    backend: {
      loadPath: `${cleanBase}locales/{{lng}}.json`,
    },
    interpolation: {
      escapeValue: false,
    },
  });

export default i18n;
