import {
  type ImgHTMLAttributes,
  type SyntheticEvent,
  useEffect,
  useRef,
  useState,
} from "react";

type LazyImgStatus = "pending" | "revealed";

export interface ILazyImgProps extends ImgHTMLAttributes<HTMLImageElement> {
  skeletonClassName?: string;
  containerClassName?: string;
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
  ...rest
}: ILazyImgProps) {
  const imgRef = useRef<HTMLImageElement>(null);
  const [status, setStatus] = useState<LazyImgStatus>("pending");

  useEffect(() => {
    setStatus("pending");
    const el = imgRef.current;
    if (el && el.complete && el.naturalWidth > 0) {
      setStatus("revealed");
    }
  }, [src]);

  const handleLoad = (event: SyntheticEvent<HTMLImageElement>) => {
    setStatus("revealed");
    onLoad?.(event);
  };

  const handleError = (event: SyntheticEvent<HTMLImageElement>) => {
    setStatus("revealed");
    onError?.(event);
  };

  const isPending = status === "pending";

  return (
    <div className={`relative overflow-hidden ${containerClassName} ${isPending ? `lazy-img-skeleton ${skeletonClassName}` : ""}`}>
      <img
        {...rest}
        ref={imgRef}
        src={src}
        loading={loading}
        decoding="async"
        onLoad={handleLoad}
        onError={handleError}
        className={`${className} transition-opacity duration-300 ${status === "revealed" ? "opacity-100" : "opacity-0"}`}
        style={style}
      />
    </div>
  );
}
