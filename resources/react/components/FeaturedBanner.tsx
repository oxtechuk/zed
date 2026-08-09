import { useNavigate } from "react-router-dom";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { useLanguageStore } from "../store/language.store";
import { getImageUrl } from "../constants/app-images";
import type { IFeaturedBannerProps } from "../interfaces/IFeaturedBannerProps";

export default function FeaturedBanner({ banner }: IFeaturedBannerProps) {
    const navigate = useNavigate();
    const direction = useLanguageStore((s) => s.direction);

    if (!banner) return null;

    const bannerImage = getImageUrl(banner.image || banner.background_image || "");
    const { button } = banner;
    const hasClickableUrl = !!button?.url;

    if (!bannerImage && !banner.title) return null;

    return (
        <section className="w-full pb-6" dir={direction}>
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {bannerImage ? (
                    <div
                        onClick={() => hasClickableUrl && navigate(button!.url!)}
                        className={`relative overflow-hidden rounded-2xl shadow-sm transition hover:opacity-95 ${
                            hasClickableUrl ? "cursor-pointer" : ""
                        }`}
                    >
                        <img
                            src={bannerImage}
                            alt={banner.title || "Banner"}
                            className="w-full h-auto max-h-[450px] object-cover rounded-2xl"
                        />
                    </div>
                ) : (
                    <div className="relative h-[220px] overflow-hidden rounded-2xl bg-[#051023] md:h-[350px]">
                        <div className="relative z-10 flex h-full max-w-2xl flex-col items-start justify-center gap-3 p-8 md:gap-4 md:p-14">
                            {banner.badge && (
                                <span className="rounded-full bg-[#EDC98E]/20 px-3 py-1 text-xs font-bold text-[#EDC98E]">
                                    {banner.badge}
                                </span>
                            )}
                            {banner.title && (
                                <h2 className="text-2xl font-black text-white md:text-4xl">
                                    {banner.title}
                                </h2>
                            )}
                            {banner.subtitle && (
                                <p className="text-sm text-gray-300 md:text-base">
                                    {banner.subtitle}
                                </p>
                            )}
                            {button?.text && button.url && (
                                <button
                                    type="button"
                                    onClick={() => navigate(button.url!)}
                                    className="mt-2 rounded-2xl bg-[#EDC98E] px-8 py-3 text-[14px] font-bold text-[#16254F] transition hover:bg-[#DDB976]"
                                >
                                    {button.text}
                                </button>
                            )}
                        </div>
                    </div>
                )}
            </div>
        </section>
    );
}
