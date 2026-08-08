import Skeleton from "../Skeleton";

export default function BlogCardSkeleton() {
    return (
        <article className="mx-auto flex h-[400px] w-[355px] flex-col overflow-hidden rounded-[24px] border border-gray-200 bg-white shadow-xs sm:rounded-[28px]">
            <div className="h-[192px] w-[353px] overflow-hidden">
                <Skeleton className="h-full w-full rounded-none" />
            </div>

            <div className="flex flex-grow flex-col p-5 text-start">
                <div className="mb-2 flex flex-wrap items-center gap-3">
                    <Skeleton className="h-6 w-20 rounded-full" />
                    <Skeleton className="h-4 w-16" />
                    <Skeleton className="h-4 w-14" />
                </div>

                <Skeleton className="h-6 w-full" />
                <Skeleton className="mt-2 h-6 w-3/4" />

                <Skeleton className="mt-3 h-4 w-full" />
                <Skeleton className="mt-2 h-4 w-2/3" />

                <div className="mt-auto pt-5">
                    <Skeleton className="h-5 w-28" />
                </div>
            </div>
        </article>
    );
}
