import { X } from "lucide-react";
import { useTranslation } from "react-i18next";
import type { IMediaLightboxModalProps } from "../../interfaces/IMediaLightboxModalProps";

export default function MediaLightboxModal({
  activeMedia,
  onClose,
}: IMediaLightboxModalProps) {
  const { t } = useTranslation();

  if (!activeMedia) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4 backdrop-blur-sm transition-all duration-300">
      <button
        onClick={onClose}
        className="absolute top-6 end-6 flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition-all duration-200 cursor-pointer"
        aria-label={t("aboutPage.mediaReviews.close")}
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
            alt={t("aboutPage.mediaReviews.reviewDetails")}
            className="mx-auto max-h-[80vh] object-contain w-full"
          />
        )}
      </div>
    </div>
  );
}
