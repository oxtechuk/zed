import { type ReactNode, useEffect, useRef, useState } from "react";

interface ILazySectionProps {
  fallback: ReactNode;
  children: ReactNode;
  rootMargin?: string;
  className?: string;
  minHeight?: string | number;
}

export default function LazySection({
  fallback,
  children,
  rootMargin = "250px",
  className = "",
  minHeight,
}: ILazySectionProps) {
  const [isVisible, setIsVisible] = useState(false);
  const containerRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (isVisible) return;
    const element = containerRef.current;
    if (!element) return;

    if (typeof IntersectionObserver === "undefined") {
      setIsVisible(true);
      return;
    }

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsVisible(true);
          observer.disconnect();
        }
      },
      { rootMargin, threshold: 0.01 }
    );

    observer.observe(element);

    return () => observer.disconnect();
  }, [isVisible, rootMargin]);

  return (
    <div
      ref={containerRef}
      className={className}
      style={!isVisible && minHeight ? { minHeight: typeof minHeight === "number" ? `${minHeight}px` : minHeight } : undefined}
    >
      {isVisible ? children : fallback}
    </div>
  );
}
