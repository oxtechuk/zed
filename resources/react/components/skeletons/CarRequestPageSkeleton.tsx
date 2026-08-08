import Skeleton from "../Skeleton";
import type { ICarRequestPageSkeletonProps } from "../../interfaces/ICarRequestPageSkeletonProps";

export default function CarRequestPageSkeleton({
    className = "",
}: ICarRequestPageSkeletonProps) {
    return (
        <main
            aria-busy="true"
            aria-label="Loading car request page"
            className={`min-h-screen w-full select-none bg-[#F8FAFC] ${className}`}
        >
            {/* Header Banner Skeleton */}
            <section className="w-full bg-[#080E1E] py-12 md:py-16 border-b border-white/5">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col items-start text-start">
                    <Skeleton className="h-4 w-24 bg-white/20 rounded-md mb-2" />
                    <Skeleton className="h-8 md:h-10 w-64 bg-white/20 rounded-lg mb-3" />
                    <Skeleton className="h-4 w-48 bg-white/10 rounded-md" />
                </div>
            </section>

            {/* Content Container Skeleton */}
            <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    {/* Form Skeleton (8 cols) */}
                    <div className="lg:col-span-8">
                        <div className="bg-white border border-[#E5E9F0] rounded-[24px] p-6 md:p-8 shadow-sm flex flex-col gap-6">
                            <div className="border-b border-gray-100 pb-4">
                                <Skeleton className="h-7 w-48 rounded-md mb-2" />
                                <Skeleton className="h-4 w-80 rounded-md" />
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {Array.from({ length: 6 }).map((_, i) => (
                                    <div key={i} className="flex flex-col gap-2">
                                        <Skeleton className="h-4 w-28 rounded-md" />
                                        <Skeleton className="h-[50px] w-full rounded-xl" />
                                    </div>
                                ))}
                                <div className="flex flex-col gap-2 md:col-span-2">
                                    <Skeleton className="h-4 w-36 rounded-md" />
                                    <Skeleton className="h-[50px] w-full rounded-xl" />
                                </div>
                            </div>

                            {/* Toggles Skeleton */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                                {Array.from({ length: 4 }).map((_, i) => (
                                    <div key={i} className="flex flex-col gap-2">
                                        <Skeleton className="h-4 w-32 rounded-md" />
                                        <div className="grid grid-cols-2 gap-3">
                                            <Skeleton className="h-11 rounded-xl" />
                                            <Skeleton className="h-11 rounded-xl" />
                                        </div>
                                    </div>
                                ))}
                            </div>

                            {/* Submit Button Skeleton */}
                            <Skeleton className="mt-6 h-[54px] w-full rounded-xl" />
                        </div>
                    </div>

                    {/* Summary Sidebar Skeleton (4 cols) */}
                    <div className="lg:col-span-4 flex flex-col gap-6">
                        <div className="bg-white border border-[#E5E9F0] rounded-[24px] p-6 shadow-sm flex flex-col gap-6">
                            {/* Dropdown Selector Skeleton */}
                            <div className="flex flex-col gap-2">
                                <Skeleton className="h-4 w-24 rounded-md" />
                                <Skeleton className="h-[50px] w-full rounded-xl" />
                            </div>

                            {/* Active Car Preview Skeleton */}
                            <div className="border border-gray-100 rounded-2xl p-4 bg-gray-50 flex flex-col items-center">
                                <Skeleton className="h-40 w-full rounded-xl mb-4" />
                                <Skeleton className="h-6 w-40 rounded-md mb-2" />
                                <Skeleton className="h-4 w-28 rounded-md mb-4" />
                                <div className="w-full border-t border-gray-200/60 pt-4 flex flex-col gap-3">
                                    <Skeleton className="h-4 w-24 rounded-md" />
                                    <div className="flex gap-2.5">
                                        {Array.from({ length: 6 }).map((_, i) => (
                                            <Skeleton key={i} className="h-9 w-9 rounded-full" />
                                        ))}
                                    </div>
                                </div>
                            </div>

                            {/* Finance Terms Skeleton */}
                            <div className="flex flex-col gap-3">
                                <Skeleton className="h-4 w-32 rounded-md" />
                                <div className="grid grid-cols-4 gap-2">
                                    {Array.from({ length: 4 }).map((_, i) => (
                                        <Skeleton key={i} className="h-11 rounded-xl" />
                                    ))}
                                </div>
                            </div>

                            {/* Installment Box Skeleton */}
                            <Skeleton className="h-32 w-full rounded-2xl" />
                        </div>
                    </div>
                </div>
            </div>
        </main>
    );
}
