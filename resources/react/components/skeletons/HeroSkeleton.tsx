import Skeleton from "../Skeleton";

export default function HeroSkeleton() {
  return (
    <section className="w-full pb-8 pt-0">
      <div className="relative w-full overflow-hidden bg-[#051023] h-[340px] sm:h-[420px] md:h-[480px] lg:h-[540px] flex items-center justify-between px-6 sm:px-12 md:px-20">
        <div className="z-10 max-w-xl flex flex-col gap-4">
          <Skeleton className="h-6 w-32 rounded-full bg-white/10" />
          <Skeleton className="h-10 sm:h-14 w-80 sm:w-96 max-w-full rounded-2xl bg-white/10" />
          <Skeleton className="h-4 sm:h-6 w-64 sm:w-80 rounded-lg bg-white/10" />
          <div className="mt-4 flex items-center gap-3">
            <Skeleton className="h-12 w-36 rounded-xl bg-blue-500/20" />
            <Skeleton className="h-12 w-36 rounded-xl bg-white/10" />
          </div>
        </div>
        <div className="hidden lg:block w-[450px]">
          <Skeleton className="h-[280px] w-full rounded-3xl bg-white/5" />
        </div>
      </div>
    </section>
  );
}
