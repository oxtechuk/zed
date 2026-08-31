import {
  type ImgHTMLAttributes,
  type SyntheticEvent,
  useEffect,
  useRef,
  useState,
} from "react";

type LazyImgStatus = "idle" | "loading" | "revealed";

export interface ILazyImgProps extends ImgHTMLAttributes<HTMLImageElement> {
  skeletonClassName?: string;
  containerClassName?: string;
  rootMargin?: string;
  fallbackSrc?: string;
}

export default function LazyImg({
  src,
  className = "",
  containerClassName = "",
  style,
  onLoad,
  onError,
  skeletonClassName = "",
  loading = "lazy",
  rootMargin = "250px",
  fallbackSrc,
  ...rest
}: ILazyImgProps) {
  const containerRef = useRef<HTMLDivElement>(null);
  const imgRef = useRef<HTMLImageElement>(null);
  const [inView, setInView] = useState(() => loading === "eager");
  const [status, setStatus] = useState<LazyImgStatus>(() => (loading === "eager" ? "loading" : "idle"));
  const [currentSrc, setCurrentSrc] = useState<string | undefined>(src);

  useEffect(() => {
    setCurrentSrc(src);
  }, [src]);

  useEffect(() => {
    if (loading === "eager") {
      setInView(true);
      setStatus("loading");
      return;
    }

    if (inView) return;
    const el = containerRef.current;
    if (!el) return;

    if (typeof IntersectionObserver === "undefined") {
      setInView(true);
      setStatus("loading");
      return;
    }

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setInView(true);
          setStatus("loading");
          observer.disconnect();
        }
      },
      { rootMargin, threshold: 0.01 }
    );

    observer.observe(el);

    return () => observer.disconnect();
  }, [loading, inView, rootMargin]);

  useEffect(() => {
    if (!inView || !currentSrc) return;
    const el = imgRef.current;
    if (el && el.complete && el.naturalWidth > 0) {
      setStatus("revealed");
    }
  }, [currentSrc, inView]);

  const handleLoad = (event: SyntheticEvent<HTMLImageElement>) => {
    setStatus("revealed");
    onLoad?.(event);
  };

  const handleError = (event: SyntheticEvent<HTMLImageElement>) => {
    if (fallbackSrc && currentSrc !== fallbackSrc) {
      setCurrentSrc(fallbackSrc);
      return;
    }
    setStatus("revealed");
    onError?.(event);
  };

  const isPending = status !== "revealed";

  return (
    <div
      ref={containerRef}
      className={`relative overflow-hidden ${containerClassName} ${isPending ? `lazy-img-skeleton ${skeletonClassName}` : ""}`}
    >
      {inView && currentSrc && (
        <img
          {...rest}
          ref={imgRef}
          src={currentSrc}
          loading={loading}
          decoding="async"
          onLoad={handleLoad}
          onError={handleError}
          className={`${className} transition-opacity duration-500 ${status === "revealed" ? "opacity-100" : "opacity-0"}`}
          style={style}
        />
      )}
    </div>
  );
}
