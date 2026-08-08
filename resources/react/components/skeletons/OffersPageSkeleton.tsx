import Skeleton from "../Skeleton";
import OfferCardSkeleton from "./OfferCardSkeleton";

export default function OffersPageSkeleton() {
    return (
        <div
            aria-busy="true"
            aria-label="Loading offers page"
            className="w-full select-none"
        >
            {/* Offers Page Hero */}
            <section className="relative w-full overflow-hidden py-7 md:py-9">
                <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <Skeleton className="mb-1.5 h-4 w-32 rounded-lg" />
                    <Skeleton className="h-9 w-72 md:w-96" />
                    <Skeleton className="mt-2 h-4 w-full max-w-2xl" />
                </div>
            </section>

            {/* Offers Grid Section */}
            <section className="w-full bg-[#FAFAFB] py-12 md:py-16">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    {/* Featured Banner */}
                    <div className="relative mb-10 flex min-h-[240px] w-full items-center overflow-hidden rounded-[24px] bg-[#0B1736] sm:min-h-[270px] sm:rounded-[32px]">
                        <Skeleton className="absolute inset-0 h-full w-full rounded-none" />

                        <div className="relative z-10 flex w-full flex-col items-center justify-between gap-8 px-6 py-8 text-start sm:px-10 sm:py-10 md:flex-row">
                            <div className="flex max-w-xl flex-col items-start">
                                <Skeleton className="h-6 w-20 rounded-full" />
                                <Skeleton className="mt-3 h-8 w-72 sm:w-96" />
                                <Skeleton className="mt-2 h-4 w-full max-w-md" />
                                <Skeleton className="mt-6 h-[48px] w-40 rounded-2xl sm:h-[52px]" />
                            </div>

                            <div className="flex shrink-0 flex-col items-start">
                                <Skeleton className="h-4 w-32" />
                                <div className="mt-3 flex items-center gap-2.5 sm:gap-3" dir="ltr">
                                    <Skeleton className="h-14 w-14 rounded-2xl sm:h-16 sm:w-16" />
                                    <Skeleton className="h-14 w-14 rounded-2xl sm:h-16 sm:w-16" />
                                    <Skeleton className="h-14 w-14 rounded-2xl sm:h-16 sm:w-16" />
                                    <Skeleton className="h-14 w-14 rounded-2xl sm:h-16 sm:w-16" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Category Pills */}
                    <div className="mb-10 flex flex-wrap items-center justify-center gap-2">
                        {Array.from({ length: 5 }).map((_, index) => (
                            <Skeleton key={index} className="h-[36px] w-24 rounded-full" />
                        ))}
                    </div>

                    {/* Offers Grid */}
                    <div className="grid grid-cols-1 gap-x-8 gap-y-10 md:grid-cols-2 lg:grid-cols-3">
                        {Array.from({ length: 6 }).map((_, index) => (
                            <OfferCardSkeleton key={index} />
                        ))}
                    </div>

                    {/* Load More Button */}
                    <div className="mt-14 flex justify-center">
                        <Skeleton className="h-[46px] w-52 rounded-2xl" />
                    </div>
                </div>
            </section>
        </div>
    );
}
