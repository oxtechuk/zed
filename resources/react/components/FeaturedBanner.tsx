import { useNavigate } from "react-router-dom";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { useLanguageStore } from "../store/language.store";
import { getImageUrl } from "../constants/app-images";
import type { FeaturedSection } from "../types/home.types";

interface FeaturedBannerProps {
  banner?: FeaturedSection | null;
}

export default function FeaturedBanner({ banner }: FeaturedBannerProps) {
  const navigate = useNavigate();
  const direction = useLanguageStore((s) => s.direction);

  if (!banner?.title) return null;

  const backgroundImage = getImageUrl(
    banner.background_image || banner.image || "",
  );
  const { button } = banner;

  return (
    <section className="w-full bg-[#FAFBFC] pb-6" dir={direction}>
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
            {banner.badge && (
              <span className="rounded-full bg-[#EDC98E] px-3.5 py-1 text-[12px] font-bold text-[#16254F]">
                {banner.badge}
              </span>
            )}
            <h2 className="text-[22px] font-extrabold leading-[1.3] text-white md:text-[34px]">
              {banner.title}
            </h2>
            {banner.description && (
              <p className="max-w-md text-[13px] leading-6 text-white/80 md:text-[16px]">
                {banner.description}
              </p>
            )}
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

          {button?.text && button.url && (
            <button
              type="button"
              onClick={() => navigate(button.url!)}
              className="absolute bottom-8 right-8 z-10 hidden rounded-2xl bg-[#EDC98E] px-8 py-3 text-[14px] font-bold text-[#16254F] transition hover:bg-[#DDB976] md:block"
            >
              {button.text}
            </button>
          )}

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
