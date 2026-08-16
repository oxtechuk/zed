import { useCallback, useRef } from "react";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { useTranslation } from "react-i18next";
import { APP_IMAGES } from "../../constants/app-images";
import type { IStaticReviewVideo } from "../../interfaces/IStaticReviewVideo";
import type { IMediaReviewsSectionVidProps } from "../../interfaces/IMediaReviewsSectionVidProps";
import MaskedVideoCard from "./MaskedVideoCard";
import NavigationButton from "./NavigationButton";

const REVIEW_VIDEOS: IStaticReviewVideo[] = [
    {
        id: 1,
        src: "/videos/reviews/review-1.mp4",
        poster: APP_IMAGES.VID_MUSK_POSTER,
    },
    {
        id: 2,
        src: "/videos/reviews/review-2.mp4",
        poster: APP_IMAGES.VID_MUSK_POSTER,
    },
    {
        id: 3,
        src: "/videos/reviews/review-3.mp4",
        poster: APP_IMAGES.VID_MUSK_POSTER,
    },
    {
        id: 4,
        src: "/videos/reviews/review-4.mp4",
        poster: APP_IMAGES.VID_MUSK_POSTER,
    },
    {
        id: 5,
        src: "/videos/reviews/review-5.mp4",
        poster: APP_IMAGES.VID_MUSK_POSTER,
    },
    {
        id: 6,
        src: "/videos/reviews/review-5.mp4",
        poster: APP_IMAGES.VID_MUSK_POSTER,
    },
    {
        id: 7,
        src: "/videos/reviews/review-5.mp4",
        poster: APP_IMAGES.VID_MUSK_POSTER,
    },
    {
        id: 8,
        src: "/videos/reviews/review-5.mp4",
        poster: APP_IMAGES.VID_MUSK_POSTER,
    },
];

export default function MediaReviewsSectionvid({
    title,
    className = "",
    videos,
}: IMediaReviewsSectionVidProps) {
    const { t, i18n } = useTranslation();

    const displayVideos = videos && videos.length > 0 ? videos : REVIEW_VIDEOS;

    const direction = i18n.dir();
    const isRTL = direction === "rtl";

    const trackRef = useRef<HTMLDivElement | null>(null);

    const scrollByPage = useCallback(
        (directionType: "next" | "previous") => {
            const track = trackRef.current;

            if (!track) return;

            const distance = track.clientWidth || 1000;
            const movement = directionType === "next" ? distance : -distance;

            track.scrollBy({
                left: isRTL ? -movement : movement,
                behavior: "smooth",
            });
        },
        [isRTL],
    );

    return (
        <section
            dir={direction}
            className={[
                "relative w-full overflow-hidden",
                "bg-[#080E1E]",
                "text-white",
                "py-12 sm:py-16 lg:py-20",
                className,
            ].join(" ")}
        >
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {/* Header */}
                <div className="flex items-center justify-between gap-6">
                    {/* Title */}
                    <h2
                        className={[
                            "text-start",
                            "text-[18px] font-extrabold",
                            "sm:text-[20px]",
                            "text-[var(--brand-secondary-color)]",
                        ].join(" ")}
                    >
                        {title ?? t("aboutPage.mediaReviews.title")}
                    </h2>

                    {/* Navigation Buttons */}
                    <div className="flex shrink-0 items-center gap-3">
                        <NavigationButton
                            onClick={() => scrollByPage("previous")}
                            ariaLabel={t("aboutPage.mediaReviews.previous")}
                        >
                            {isRTL ? (
                                <ChevronRight size={19} strokeWidth={1.7} />
                            ) : (
                                <ChevronLeft size={19} strokeWidth={1.7} />
                            )}
                        </NavigationButton>

                        <NavigationButton
                            onClick={() => scrollByPage("next")}
                            ariaLabel={t("aboutPage.mediaReviews.next")}
                        >
                            {isRTL ? (
                                <ChevronLeft size={19} strokeWidth={1.7} />
                            ) : (
                                <ChevronRight size={19} strokeWidth={1.7} />
                            )}
                        </NavigationButton>
                    </div>
                </div>

                {/* Videos Carousel (4 cards per page) */}
                <div className="relative mt-10">
                    <div
                        ref={trackRef}
                        className={[
                            "flex items-stretch gap-5",
                            "overflow-x-auto",
                            "scroll-smooth",
                            "pb-2",
                            "snap-x snap-mandatory",
                            "[-ms-overflow-style:none]",
                            "[scrollbar-width:none]",
                            "[&::-webkit-scrollbar]:hidden",
                        ].join(" ")}
                    >
                        {displayVideos.map((video, index) => (
                            <MaskedVideoCard
                                key={video.id}
                                video={video}
                                index={index}
                            />
                        ))}
                    </div>
                </div>
            </div>
        </section>
    );
}
