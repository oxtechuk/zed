import { useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { ChevronLeft, ChevronRight } from "lucide-react";
import type { IMediaReviewsSectionProps } from "../../interfaces/IMediaReviewsSectionProps";
import type { ITestimonialItem } from "../../interfaces/ITestimonialItem";
import MediaReviewCard from "./MediaReviewCard";
import MediaLightboxModal from "./MediaLightboxModal";

export default function MediaReviewsSection({ testimonials }: IMediaReviewsSectionProps) {
  const { i18n, t } = useTranslation();
  const scrollRef = useRef<HTMLDivElement>(null);
  const [activeMedia, setActiveMedia] = useState<{ type: "video" | "image"; url: string } | null>(null);

  if (testimonials.length === 0) return null;

  const isRTL = i18n.dir() === "rtl";

  const scroll = (direction: "prev" | "next") => {
    if (!scrollRef.current) return;
    const container = scrollRef.current;
    const cardWidth = 300 + 24; // Card width + gap

    // In RTL, "next" means scrolling left (negative values), in LTR it means scrolling right (positive values).
    const scrollMultiplier = direction === "next" ? 1 : -1;
    const directionMultiplier = isRTL ? -1 : 1;

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
      <div className="absolute top-0 end-0 w-96 h-96 bg-[var(--brand-primary-color)] opacity-5 blur-[120px] rounded-full pointer-events-none" />
      <div className="absolute bottom-0 start-0 w-96 h-96 bg-[var(--brand-secondary-color)] opacity-5 blur-[120px] rounded-full pointer-events-none" />

      <div className="mx-auto max-w-[1200px]">
        {/* Header Section */}
        <div className="mb-12 flex items-end justify-between">
          <div className="text-start">
            <span className="mb-3 block text-sm font-bold tracking-wider text-[#FF5B2E] uppercase">
              {t("aboutPage.testimonials.badge")}
            </span>
            <h2 className="text-3xl font-black md:text-4xl text-white">
              {t("aboutPage.mediaReviews.titleBlack")}{" "}
              <span className="text-[#2FA3DC]">
                {t("aboutPage.mediaReviews.titleBlue")}
              </span>
            </h2>
          </div>

          {/* Navigation Buttons */}
          <div className="flex gap-3">
            <button
              onClick={() => scroll("prev")}
              className="flex h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white transition-all duration-300 hover:bg-[#2FA3DC] hover:border-[#2FA3DC] active:scale-95 shadow-md cursor-pointer"
              aria-label={t("aboutPage.mediaReviews.previous")}
            >
              {isRTL ? <ChevronRight size={22} /> : <ChevronLeft size={22} />}
            </button>
            <button
              onClick={() => scroll("next")}
              className="flex h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white transition-all duration-300 hover:bg-[#2FA3DC] hover:border-[#2FA3DC] active:scale-95 shadow-md cursor-pointer"
              aria-label={t("aboutPage.mediaReviews.next")}
            >
              {isRTL ? <ChevronLeft size={22} /> : <ChevronRight size={22} />}
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
            <MediaReviewCard
              key={item.id}
              item={item}
              onClick={handleCardClick}
            />
          ))}
        </div>
      </div>

      {/* Lightbox / Video Player Modal */}
      <MediaLightboxModal
        activeMedia={activeMedia}
        onClose={() => setActiveMedia(null)}
      />
    </section>
  );
}
