import Skeleton from "../Skeleton";
import CarCardSkeleton from "./CarCardSkeleton";

export default function BudgetCarsSkeleton() {
  return (
    <section className="relative overflow-hidden bg-white py-12 md:py-16">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8 flex flex-col items-center justify-between gap-4 md:flex-row md:items-end">
          <div>
            <Skeleton className="h-8 w-64 rounded-xl" />
            <Skeleton className="mt-2 h-4 w-96 max-w-full rounded-md" />
          </div>
          <Skeleton className="h-10 w-32 rounded-xl" />
        </div>

        {/* Salary Range Pills Skeleton */}
        <div className="mb-8 flex flex-wrap items-center justify-center gap-2 sm:gap-3">
          {[1, 2, 3, 4, 5].map((i) => (
            <Skeleton key={i} className="h-10 w-32 rounded-full" />
          ))}
        </div>

        {/* Cars Grid Skeleton */}
        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          {[1, 2, 3, 4].map((i) => (
            <CarCardSkeleton key={i} />
          ))}
        </div>
      </div>
    </section>
  );
}
