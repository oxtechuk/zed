import Skeleton from "../Skeleton";

export default function OfferCardSkeleton() {
    return (
        <article className="flex h-full w-full flex-col overflow-hidden rounded-[24px] border border-gray-200 bg-white shadow-xs sm:rounded-[28px]">
            <div className="relative h-[210px] w-full overflow-hidden bg-[#0B1736] sm:h-[230px]">
                <Skeleton className="h-full w-full rounded-none" />
                <Skeleton className="absolute top-4 start-4 h-6 w-20 rounded-full" />
            </div>

            <div className="flex flex-grow flex-col p-5 text-start sm:p-6">
                <Skeleton className="h-6 w-3/4" />

                <Skeleton className="mt-2 h-4 w-full" />
                <Skeleton className="mt-1 h-4 w-2/3" />

                <div className="mb-5 grid grid-cols-3 gap-3 pt-6" dir="ltr">
                    <Skeleton className="h-[68px] rounded-2xl" />
                    <Skeleton className="h-[68px] rounded-2xl" />
                    <Skeleton className="h-[68px] rounded-2xl" />
                </div>

                <Skeleton className="h-[48px] w-full rounded-2xl sm:h-[52px] sm:rounded-[18px]" />
            </div>
        </article>
    );
}
