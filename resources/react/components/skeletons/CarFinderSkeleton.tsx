import Skeleton from "../Skeleton";

export default function CarFinderSkeleton() {
  return (
    <section className="relative -mt-6 z-20 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div className="rounded-2xl sm:rounded-3xl border border-gray-100 bg-white p-5 sm:p-6 shadow-xl shadow-slate-200/50">
        <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
          <Skeleton className="h-12 rounded-xl" />
          <Skeleton className="h-12 rounded-xl" />
          <Skeleton className="h-12 rounded-xl" />
          <Skeleton className="h-12 rounded-xl" />
          <Skeleton className="h-12 rounded-xl bg-blue-100" />
        </div>
      </div>
    </section>
  );
}
