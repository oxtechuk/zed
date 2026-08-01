import type { IInfoCard } from "../../interfaces/IInfoCard";

export default function AboutInfoCard({ title, description }: IInfoCard) {
  return (
    <div className="flex flex-col text-start">
      {/* Small Category Label */}
      <span className="text-[14px] font-bold uppercase tracking-wider text-[#2FA3DC] mb-4">
        {title}
      </span>
      {/* Content Text */}
      <p className="text-[17px] leading-[1.8] text-[#374151] font-medium">
        {description}
      </p>
    </div>
  );
}
