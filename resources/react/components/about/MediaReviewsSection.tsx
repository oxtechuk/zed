import { useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { ChevronLeft, ChevronRight, Play, X } from "lucide-react";
import type { ITestimonialItem } from "../../interfaces/ITestimonialItem";
import LazyImg from "../LazyImg";

interface MediaReviewsSectionProps {
  testimonials: ITestimonialItem[];
}

export default function MediaReviewsSection({ testimonials }: MediaReviewsSectionProps) {
  const { i18n, t } = useTranslation();
  const scrollRef = useRef<HTMLDivElement>(null);
  const [activeMedia, setActiveMedia] = useState<{ type: "video" | "image"; url: string } | null>(null);

  if (testimonials.length === 0) return null;

  const scroll = (direction: "prev" | "next") => {
    if (!scrollRef.current) return;
    const container = scrollRef.current;
    const cardWidth = 300 + 24; // Card width + gap
    const isRtl = i18n.dir() === "rtl";

    // In RTL, "next" means scrolling left (negative values), in LTR it means scrolling right (positive values).
    const scrollMultiplier = direction === "next" ? 1 : -1;
    const directionMultiplier = isRtl ? -1 : 1;

    container.scrollBy({
      left: cardWidth * scrollMultiplier * directionMultiplier,
      behavior: "smooth",
    });
  };

  const handleCardClick = (item: ITestimonialItem) => {
    if (item.reviewVideo) {
      setActiveMedia({ type: "video", url: item.reviewVideo });
    } else if (item.reviewImage) {
      setActiveMedia({ type: "image", url: item.reviewImage });
    }
  };

  return (
    <section
      dir={i18n.dir()}
      className="bg-[#0B1120] px-6 py-20 text-white relative overflow-hidden"
    >
      {/* Decorative Background Elements */}
      <div className="absolute top-0 right-0 w-96 h-96 bg-[var(--brand-primary-color)] opacity-5 blur-[120px] rounded-full pointer-events-none" />
      <div className="absolute bottom-0 left-0 w-96 h-96 bg-[var(--brand-secondary-color)] opacity-5 blur-[120px] rounded-full pointer-events-none" />

      <div className="mx-auto max-w-[1200px]">
        {/* Header Section */}
        <div className="mb-12 flex items-end justify-between">
          <div className="text-start">
            <span className="mb-3 block text-sm font-bold tracking-wider text-[#FF5B2E] uppercase">
              {t("aboutPage.testimonials.badge")}
            </span>
            <h2 className="text-3xl font-black md:text-4xl text-white">
              {i18n.language === "ar" ? (
                <>
                  ماذا يقول <span className="text-[#2FA3DC]">عملائنا؟</span>
                </>
              ) : (
                <>
                  What Our <span className="text-[#2FA3DC]">Clients Say?</span>
                </>
              )}
            </h2>
          </div>

          {/* Navigation Buttons */}
          <div className="flex gap-3">
            <button
              onClick={() => scroll("prev")}
              className="flex h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white transition-all duration-300 hover:bg-[#2FA3DC] hover:border-[#2FA3DC] active:scale-95 shadow-md cursor-pointer"
              aria-label="Previous slide"
            >
              {i18n.dir() === "rtl" ? <ChevronRight size={22} /> : <ChevronLeft size={22} />}
            </button>
            <button
              onClick={() => scroll("next")}
              className="flex h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white transition-all duration-300 hover:bg-[#2FA3DC] hover:border-[#2FA3DC] active:scale-95 shadow-md cursor-pointer"
              aria-label="Next slide"
            >
              {i18n.dir() === "rtl" ? <ChevronLeft size={22} /> : <ChevronRight size={22} />}
            </button>
          </div>
        </div>

        {/* Carousel Container */}
        <div
          ref={scrollRef}
          className="no-scrollbar flex gap-6 overflow-x-auto scroll-smooth pb-6 pt-2"
          style={{ scrollSnapType: "x mandatory" }}
        >
          {testimonials.map((item) => (
            <div
              key={item.id}
              onClick={() => handleCardClick(item)}
              className="group relative h-[420px] w-[280px] min-w-[280px] overflow-hidden rounded-3xl bg-[#1E293B] shadow-lg cursor-pointer transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)]"
              style={{ scrollSnapAlign: "start" }}
            >
              {/* Review Media (Image fallback or Poster) */}
              <LazyImg
                src={item.reviewImage || item.avatar}
                alt={item.name}
                className="h-full w-full object-cover transition-all duration-700 group-hover:scale-110 opacity-80 group-hover:opacity-95"
              />

              {/* Dark Gradient Overlay */}
              <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent transition-opacity duration-300" />

              {/* Play Button Overlay for Videos */}
              {item.reviewVideo && (
                <div className="absolute inset-0 flex items-center justify-center">
                  <div className="flex h-16 w-16 items-center justify-center rounded-full bg-white/20 backdrop-blur-md text-white transition-all duration-300 group-hover:scale-110 group-hover:bg-[#2FA3DC] shadow-lg">
                    <Play className="ml-1 text-white fill-white" size={24} />
                  </div>
                </div>
              )}

              {/* Content overlay */}
              <div className="absolute bottom-0 left-0 right-0 p-6 text-start">
                <div className="flex items-center gap-3 mb-2">
                  <img
                    src={item.avatar}
                    alt={item.name}
                    className="h-9 w-9 rounded-full object-cover border border-white/20"
                  />
                  <div>
                    <h4 className="text-sm font-bold text-white line-clamp-1">{item.name}</h4>
                    <p className="text-[11px] text-white/60 line-clamp-1">{item.job}</p>
                  </div>
                </div>
                <p className="text-xs text-white/80 line-clamp-2 leading-relaxed italic">
                  &ldquo;{item.text}&rdquo;
                </p>
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Lightbox / Video Player Modal */}
      {activeMedia && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4 backdrop-blur-sm transition-all duration-300">
          <button
            onClick={() => setActiveMedia(null)}
            className="absolute top-6 right-6 flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition-all duration-200 cursor-pointer"
            aria-label="Close"
          >
            <X size={24} />
          </button>

          <div className="relative max-h-[85vh] max-w-full md:max-w-2xl w-full bg-[#0F172A] rounded-2xl overflow-hidden shadow-2xl border border-white/10 animate-in fade-in zoom-in duration-300">
            {activeMedia.type === "video" ? (
              <video
                src={activeMedia.url}
                className="w-full aspect-[9/16] max-h-[80vh] md:aspect-video object-contain"
                controls
                autoPlay
              />
            ) : (
              <img
                src={activeMedia.url}
                alt="Review details"
                className="mx-auto max-h-[80vh] object-contain w-full"
              />
            )}
          </div>
        </div>
      )}
    </section>
  );
}
