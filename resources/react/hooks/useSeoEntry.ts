import { useQuery } from "@tanstack/react-query";
import { useLocation } from "react-router-dom";
import { getSeoEntry } from "../services/api";
import { useLanguageStore } from "../store/language.store";

/** SEO copy (extra text, FAQ, image alt) for the current page; Arabic only. */
export function useSeoEntry() {
  const { pathname } = useLocation();
  const language = useLanguageStore((s) => s.language);
  let path = pathname;
  try {
    path = decodeURIComponent(pathname);
  } catch {
    // keep the raw path
  }

  const { data } = useQuery({
    queryKey: ["seo-entry", path],
    queryFn: () => getSeoEntry(path),
    enabled: language === "ar",
    staleTime: 10 * 60 * 1000,
  });

  return language === "ar" ? data ?? null : null;
}
