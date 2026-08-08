import Skeleton from "../Skeleton";
import CarsGridSkeleton from "./CarsGridSkeleton";

export default function AllCarsPageSkeleton() {
    return (
        <main
            aria-busy="true"
            aria-label="Loading cars page"
            className="min-h-screen bg-[#F3F4F6] select-none"
        >
            {/* Page Header Banner */}
            <section className="w-full overflow-hidden py-10">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <Skeleton className="mb-2 h-4 w-32 rounded-lg" />
                    <Skeleton className="h-10 w-64 rounded-xl md:w-80" />
                </div>
            </section>

            {/* Filter & Search Bar */}
            <section className="z-30 border-b border-[#E5E9F0] bg-white py-4 shadow-xs">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-3.5 sm:flex-row sm:items-center sm:justify-between">
                        <Skeleton className="h-[46px] w-full rounded-2xl sm:max-w-[400px]" />

                        <div className="flex items-center gap-2.5">
                            <Skeleton className="h-[46px] w-36 rounded-2xl" />
                            <Skeleton className="h-[46px] w-28 rounded-2xl" />
                        </div>
                    </div>

                    <div className="mt-4 flex flex-nowrap gap-2 overflow-hidden border-t border-gray-100 pt-4">
                        <Skeleton className="h-[36px] w-20 shrink-0 rounded-full" />
                        <Skeleton className="h-[36px] w-24 shrink-0 rounded-full" />
                        <Skeleton className="h-[36px] w-28 shrink-0 rounded-full" />
                        <Skeleton className="h-[36px] w-20 shrink-0 rounded-full" />
                        <Skeleton className="h-[36px] w-24 shrink-0 rounded-full" />
                    </div>
                </div>
            </section>

            {/* Cars Grid Content */}
            <section className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <Skeleton className="mb-4 h-4 w-24" />

                <CarsGridSkeleton
                    count={9}
                    className="grid-cols-1 gap-7 justify-items-center md:grid-cols-2 xl:grid-cols-3"
                />

                <div className="mt-14 flex items-center justify-center gap-2">
                    <Skeleton className="h-11 w-11 rounded-2xl" />
                    <Skeleton className="h-11 w-11 rounded-2xl" />
                    <Skeleton className="h-11 w-11 rounded-2xl" />
                    <Skeleton className="h-11 w-11 rounded-2xl" />
                    <Skeleton className="h-11 w-11 rounded-2xl" />
                </div>
            </section>
        </main>
    );
}
