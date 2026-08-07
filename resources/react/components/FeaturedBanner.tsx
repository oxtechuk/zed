import { useNavigate } from "react-router-dom";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { useLanguageStore } from "../store/language.store";
import { getImageUrl } from "../constants/app-images";
import type { IFeaturedBannerProps } from "../interfaces/IFeaturedBannerProps";

export default function FeaturedBanner({ banner }: IFeaturedBannerProps) {
    const navigate = useNavigate();
    const direction = useLanguageStore((s) => s.direction);

    if (!banner?.title) return null;

    const backgroundImage = getImageUrl(
        banner.background_image || banner.image || "",
    );
    const { button } = banner;

    return (
        <section className="w-full pb-6" dir={direction}>
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    className="relative h-[220px] overflow-hidden rounded-2xl bg-[#051023] md:h-[400px]"
                    style={
                        backgroundImage
                            ? {
                                  backgroundImage: `linear-gradient(to left, rgba(5,16,35,0.9) 0%, rgba(5,16,35,0.45) 100%), url(${backgroundImage})`,
                                  backgroundSize: "cover",
                                  backgroundPosition: "center",
                              }
                            : undefined
                    }
                >
                    <div className="relative z-10 flex h-full max-w-2xl flex-col items-start justify-center gap-3 p-8 md:gap-4 md:p-14">
                        {button?.text && button.url && (
                            <button
                                type="button"
                                onClick={() => navigate(button.url!)}
                                className="mt-2 rounded-2xl bg-[#EDC98E] px-8 py-3 text-[14px] font-bold text-[#16254F] transition hover:bg-[#DDB976] md:hidden"
                            >
                                {button.text}
                            </button>
                        )}
                    </div>

                    <div
                        className="absolute bottom-8 left-8 z-10 hidden items-center gap-8 md:flex"
                        aria-hidden="true"
                    >
                        <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#16254F] text-white">
                            <ChevronLeft size={20} />
                        </div>
                        <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#16254F] text-white">
                            <ChevronRight size={20} />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
