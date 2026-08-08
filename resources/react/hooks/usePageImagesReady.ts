import { useEffect, useState } from "react";

interface IUsePageImagesReadyOptions {
  timeout?: number;
  max?: number;
}

/**
 * Returns true once the page data has finished loading (isLoading === false)
 * AND the given images have been downloaded (or the timeout elapsed).
 * Once it returns true, it stays true for the lifetime of the component.
 */
export function usePageImagesReady(
  isLoading: boolean,
  urls: string[],
  options: IUsePageImagesReadyOptions = {},
): boolean {
  const { timeout = 4000, max = 20 } = options;
  const [ready, setReady] = useState(false);

  useEffect(() => {
    if (ready) return;
    if (isLoading) return;

    const unique = Array.from(new Set(urls.filter(Boolean))).slice(0, max);

    if (unique.length === 0) {
      setReady(true);
      return;
    }

    let cancelled = false;
    let remaining = unique.length;

    const settleTimer = setTimeout(() => {
      if (!cancelled) setReady(true);
    }, timeout);

    const onDone = () => {
      if (cancelled) return;
      remaining -= 1;
      if (remaining <= 0) setReady(true);
    };

    unique.forEach((url) => {
      const img = new Image();
      img.onload = onDone;
      img.onerror = onDone;
      img.src = url;
    });

    return () => {
      cancelled = true;
      clearTimeout(settleTimer);
    };
  }, [ready, isLoading, urls, timeout, max]);

  return ready;
}
