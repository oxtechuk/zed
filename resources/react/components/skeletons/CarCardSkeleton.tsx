import Skeleton from "../Skeleton";

export default function CarCardSkeleton() {
    return (
        <article className="mx-auto w-full max-w-[355px] overflow-hidden rounded-2xl border border-[#E7E9EF] bg-white text-start">
            <div className="relative h-[240px] overflow-hidden">
                <Skeleton className="h-full w-full rounded-none" />
            </div>

            <div className="p-5">
                <Skeleton className="h-3 w-24" />

                <Skeleton className="mt-2 h-4 w-40" />

                <div className="mt-4 flex flex-wrap items-center gap-1.5">
                    <Skeleton className="h-6 w-16 rounded-xl" />
                    <Skeleton className="h-6 w-14 rounded-xl" />
                    <Skeleton className="h-6 w-16 rounded-xl" />
                </div>

                <div className="mt-4 flex items-start justify-between border-t border-[#E7E9EF] pt-3">
                    <Skeleton className="h-5 w-20" />
                    <Skeleton className="h-5 w-28" />
                </div>

                <div className="mt-4 flex items-center gap-2">
                    <Skeleton className="h-10 flex-1 rounded-2xl" />
                    <Skeleton className="h-10 w-10 rounded-2xl" />
                    <Skeleton className="h-10 w-10 rounded-2xl" />
                </div>
            </div>
        </article>
    );
}
