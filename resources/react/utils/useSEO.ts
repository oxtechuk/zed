import { useEffect } from "react";
import { useSettingsStore } from "../store/settings.store";

const SSR_META_NAMES = [
  "description",
  "og:title",
  "og:description",
  "og:image",
  "og:url",
  "og:type",
  "twitter:title",
  "twitter:description",
  "twitter:image",
];

interface ServerSeo {
  path: string;
  title: string;
  canonical: string;
  metas: Record<string, string>;
}

function normalizePath(path: string): string {
  let decoded = path;
  try {
    decoded = decodeURIComponent(path);
  } catch {
    // keep the raw path if it is not valid URI encoding
  }
  return decoded.replace(/\/+$/, "") || "/";
}

// Snapshot of the meta tags the server rendered for the first URL, taken
// before any component touches the document head.
const serverSeo: ServerSeo | null = (() => {
  if (typeof document === "undefined") return null;
  const marker = document.querySelector<HTMLMetaElement>('meta[name="ssr-seo-path"]');
  if (!marker) return null;
  const metas: Record<string, string> = {};
  for (const name of SSR_META_NAMES) {
    const el = document.querySelector<HTMLMetaElement>(`meta[name="${name}"], meta[property="${name}"]`);
    if (el?.content) metas[name] = el.content;
  }
  return {
    path: normalizePath(marker.content),
    title: document.title,
    canonical: document.querySelector<HTMLLinkElement>('link[rel="canonical"]')?.getAttribute("href") ?? "",
    metas,
  };
})();

function isServerRenderedPath(): boolean {
  return serverSeo !== null && normalizePath(window.location.pathname) === serverSeo.path;
}

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
    // The server already rendered the right meta for this URL (what search
    // engines index). Keep it, and only use the page values after in-app navigation.
    if (isServerRenderedPath() && serverSeo) {
      document.title = serverSeo.title;
      for (const [name, content] of Object.entries(serverSeo.metas)) setMeta(name, content);
      setCanonical(serverSeo.canonical);
      return;
    }

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

