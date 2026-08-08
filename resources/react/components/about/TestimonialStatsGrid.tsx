import type { ITestimonialStat } from "../../interfaces/ITestimonialStat";

interface ITestimonialStatsGridProps {
  stats: ITestimonialStat[];
}

export default function TestimonialStatsGrid({ stats }: ITestimonialStatsGridProps) {
  if (!stats || stats.length === 0) return null;

  return (
    <div className="mt-12 grid grid-cols-2 gap-4 md:grid-cols-4">
      {stats.map((stat, idx) => (
        <div
          key={idx}
          className="flex flex-col items-center justify-center rounded-2xl border border-white/5 bg-[#0D1826] py-4 px-4 text-center"
        >
          <strong className="text-[30px] font-black text-[#EDC98E] md:text-[36px] leading-none">
            {stat.value}
          </strong>
          <span className="mt-2 text-[13px] font-semibold text-white/50">
            {stat.label}
          </span>
        </div>
      ))}
    </div>
  );
}
