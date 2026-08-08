import type { IMaskedVideoCardProps } from "../../interfaces/IMaskedVideoCardProps";

export default function MaskedVideoCard({ video }: IMaskedVideoCardProps) {
  const mask =
    "polygon(0px 0px, 72% 0px, 100% 17%, 100% 100%, 22% 100%, 0px 88%)";

  return (
    <article
      className="
        relative
        h-[360px] sm:h-[400px]
        w-full sm:w-[calc((100%-3*1.25rem)/4)]
        shrink-0
        snap-start
        overflow-hidden     
        rounded-[16px]
        bg-[#171A23]
      "
      style={{
        clipPath: mask,
        WebkitClipPath: mask,
      }}
    >
      <video
        src={video.src}
        poster={video.poster}
        autoPlay
        muted
        loop
        playsInline
        preload="metadata"
        className="h-full w-full object-cover"
      />

      {/* Small cinematic dark overlay */}
      <div
        className={[
          "pointer-events-none absolute inset-0",
          "bg-gradient-to-t",
          "from-black/15",
          "via-transparent",
          "to-black/[0.03]",
        ].join(" ")}
      />
    </article>
  );
}
