import Skeleton from "../Skeleton";

export default function BrandsCarouselSkeleton() {
  return (
    <section className="w-full overflow-hidden py-4 sm:py-6">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between gap-4 overflow-hidden py-2">
          {[1, 2, 3, 4, 5, 6, 7, 8].map((i) => (
            <div
              key={i}
              className="flex h-[86px] w-[145px] shrink-0 items-center justify-center rounded-2xl border border-gray-100 bg-white/60 p-4 shadow-xs sm:h-[96px] sm:w-[170px]"
            >
              <Skeleton className="h-10 w-24 rounded-lg" />
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
