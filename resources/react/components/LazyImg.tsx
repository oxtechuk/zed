import {
  type ImgHTMLAttributes,
  type SyntheticEvent,
  useEffect,
  useRef,
  useState,
} from "react";

const PLACEHOLDER =
  "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";

type LazyImgStatus = "pending" | "revealed";

export interface ILazyImgProps extends ImgHTMLAttributes<HTMLImageElement> {
  skeletonClassName?: string;
}

export default function LazyImg({
  src,
  className,
  style,
  onLoad,
  onError,
  skeletonClassName = "",
  ...rest
}: ILazyImgProps) {
  const imgRef = useRef<HTMLImageElement>(null);
  const [status, setStatus] = useState<LazyImgStatus>("pending");

  useEffect(() => {
    const el = imgRef.current;
    if (!el) return;

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          el.src = src as string;
          observer.unobserve(el);
        }
      },
      { rootMargin: "200px" },
    );

    observer.observe(el);
    return () => observer.disconnect();
  }, [src]);

  const handleLoad = (event: SyntheticEvent<HTMLImageElement>) => {
    setStatus("revealed");
    onLoad?.(event);
  };

  const handleError = (event: SyntheticEvent<HTMLImageElement>) => {
    setStatus("revealed");
    onError?.(event);
  };

  const showSkeleton = status !== "revealed";

  return (
    <img
      {...rest}
      ref={imgRef}
      src={PLACEHOLDER}
      onLoad={handleLoad}
      onError={handleError}
      className={`${className ?? ""} ${showSkeleton ? `lazy-img-skeleton ${skeletonClassName}` : ""}`}
      style={{
        ...style,
        ...(showSkeleton ? { width: "100%", height: "100%" } : {}),
      }}
    />
  );
}
