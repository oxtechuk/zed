import { Play } from "lucide-react";
import type { ITestimonialItem } from "../../interfaces/ITestimonialItem";
import LazyImg from "../LazyImg";

interface IMediaReviewCardProps {
  item: ITestimonialItem;
  onClick: (item: ITestimonialItem) => void;
}

export default function MediaReviewCard({ item, onClick }: IMediaReviewCardProps) {
  return (
    <div
      onClick={() => onClick(item)}
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
            <Play className="ms-1 text-white fill-white" size={24} />
          </div>
        </div>
      )}

      {/* Content overlay */}
      <div className="absolute inset-x-0 bottom-0 p-6 text-start">
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
  );
}
