import { useEffect } from "react";
import { useSettingsStore } from "../store/settings.store";

export function useSEO(
  title: string,
  description?: string,
  image?: string,
  url?: string,
  exactTitle: boolean = false
): void {
  const settings = useSettingsStore((s) => s.settings);
  const siteName = settings?.site_name || "زاد كابيتال";

  useEffect(() => {
    const pageTitle = exactTitle ? title : `${siteName} | ${title}`;
    document.title = pageTitle;

    // Clean canonical URL: Origin + Path only (strip query string, hash, trailing slashes)
    const canonicalUrl = url || (typeof window !== "undefined"
      ? `${window.location.origin}${window.location.pathname.replace(/\/+$/, "") || "/"}`
      : "");

    setMeta("description", description ?? "");
    setMeta("og:title", pageTitle);
    setMeta("og:description", description ?? "");
    setMeta("og:image", image ?? "");
    setMeta("og:url", canonicalUrl);
    setCanonical(canonicalUrl);
    setMeta("og:type", "website");
    setMeta("twitter:card", "summary_large_image");
    setMeta("twitter:title", pageTitle);
    setMeta("twitter:description", description ?? "");
    setMeta("twitter:image", image ?? "");
  }, [title, description, image, url, siteName, exactTitle]);
}

function setCanonical(href: string): void {
  if (!href) return;
  let link = document.querySelector<HTMLLinkElement>('link[rel="canonical"]');
  if (!link) {
    link = document.createElement("link");
    link.setAttribute("rel", "canonical");
    document.head.appendChild(link);
  }
  link.setAttribute("href", href);
}

function setMeta(name: string, content: string): void {
  if (!content) return;
  let el = document.querySelector<HTMLMetaElement>(`meta[name="${name}"], meta[property="${name}"]`);
  if (!el) {
    el = document.createElement("meta");
    if (name.startsWith("og:") || name.startsWith("twitter:")) {
      el.setAttribute("property", name);
    } else {
      el.setAttribute("name", name);
    }
    document.head.appendChild(el);
  }
  el.setAttribute("content", content);
}

