import Skeleton from "../Skeleton";
import SectionHeaderSkeleton from "./SectionHeaderSkeleton";
import CarsGridSkeleton from "./CarsGridSkeleton";

export default function HomePageSkeleton() {
    return (
        <div
            aria-busy="true"
            aria-label="Loading home page"
            className="w-full select-none"
        >
            {/* Home Hero */}
            <section className="w-full pb-10 pt-2">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <Skeleton className="h-[220px] w-full rounded-2xl sm:h-[320px] md:h-[420px] lg:h-[480px]" />

                    <div className="mt-4 grid grid-cols-2 gap-2.5 sm:mt-8 sm:gap-6 lg:grid-cols-3">
                        <Skeleton className="aspect-[403/320] w-full rounded-xl" />
                        <Skeleton className="aspect-[403/320] w-full rounded-xl" />
                        <Skeleton className="hidden aspect-[403/320] w-full rounded-xl lg:block" />
                    </div>
                </div>
            </section>

            {/* CarFinder */}
            <section className="w-full bg-[#F8F4FD] py-10">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <Skeleton className="mx-auto mb-8 h-8 w-56 md:w-72" />

                    <div className="flex flex-col gap-4 lg:flex-row lg:items-center">
                        <Skeleton className="h-[52px] flex-1 rounded-2xl" />

                        <div className="flex shrink-0 items-center gap-3">
                            <Skeleton className="h-[52px] w-28 rounded-2xl" />
                            <Skeleton className="h-[52px] w-28 rounded-2xl" />
                        </div>
                    </div>

                    <div className="mt-6 grid grid-cols-1 gap-4 rounded-2xl border border-[#E7E9EF] bg-white p-6 shadow-sm sm:grid-cols-2 lg:grid-cols-4">
                        {Array.from({ length: 4 }).map((_, index) => (
                            <div key={index}>
                                <Skeleton className="mb-2 h-3 w-16" />
                                <Skeleton className="h-[48px] rounded-[12px]" />
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Brands Carousel */}
            <section className="w-full overflow-hidden border-y border-[#E5E7EB] py-6">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-center gap-10">
                        {Array.from({ length: 6 }).map((_, index) => (
                            <Skeleton key={index} className="h-[64px] w-[120px] rounded-xl sm:h-[72px] sm:w-[140px]" />
                        ))}
                    </div>
                </div>
            </section>

            {/* Featured Cars */}
            <section className="relative w-full overflow-hidden py-14">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <SectionHeaderSkeleton />
                    <CarsGridSkeleton />
                    <div className="mt-10 flex justify-center">
                        <Skeleton className="h-[44px] w-40 rounded-2xl" />
                    </div>
                </div>
            </section>

            {/* Featured Banner */}
            <section className="w-full pb-6">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <Skeleton className="h-[220px] w-full rounded-2xl md:h-[400px]" />
                </div>
            </section>

            {/* Offers Cars */}
            <section className="relative w-full overflow-hidden py-14">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <SectionHeaderSkeleton />
                    <CarsGridSkeleton />
                    <div className="mt-10 flex justify-center">
                        <Skeleton className="h-[44px] w-40 rounded-2xl" />
                    </div>
                </div>
            </section>

            {/* Budget Cars */}
            <section className="w-full py-16">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="mb-10">
                        <Skeleton className="h-10 w-56 md:w-80" />
                        <Skeleton className="mt-4 h-4 w-full max-w-2xl" />
                    </div>

                    <div className="mb-9 flex flex-wrap items-center justify-center gap-3">
                        {Array.from({ length: 4 }).map((_, index) => (
                            <Skeleton key={index} className="h-[46px] w-28 rounded-full" />
                        ))}
                    </div>

                    <CarsGridSkeleton />
                </div>
            </section>

            {/* Finance Solutions */}
            <section className="w-full border-t border-[#E5E9F0] bg-[#FAFBFC] py-14">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <Skeleton className="mx-auto mb-14 h-8 w-64 md:w-80" />

                    <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                        {Array.from({ length: 4 }).map((_, index) => (
                            <div key={index} className="flex flex-col items-center px-4 text-center">
                                <Skeleton className="mb-5 h-16 w-16 rounded-2xl" />
                                <Skeleton className="h-5 w-32" />
                                <Skeleton className="mt-3 h-4 w-40" />
                                <Skeleton className="mt-2 h-4 w-36" />
                            </div>
                        ))}
                    </div>
                </div>
            </section>
        </div>
    );
}
