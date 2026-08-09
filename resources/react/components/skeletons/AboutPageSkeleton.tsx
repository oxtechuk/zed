import Skeleton from "../Skeleton";
import type { IAboutPageSkeletonProps } from "../../interfaces/IAboutPageSkeletonProps";

export default function AboutPageSkeleton({
    className = "",
}: IAboutPageSkeletonProps) {
    return (
        <main
            aria-busy="true"
            aria-label="Loading about page"
            className={`min-h-screen select-none text-white ${className}`}
        >
            {/* ── Hero Section ── */}
            <section className="relative w-full py-12 sm:py-14 lg:py-16">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="mx-auto flex max-w-[860px] flex-col items-center text-center">
                        <Skeleton className="h-4 w-32 rounded-full bg-white/10" />
                        <Skeleton className="mt-4 h-10 w-3/4 max-w-md rounded-xl bg-white/10 sm:h-12" />
                        <Skeleton className="mt-4 h-5 w-full max-w-lg rounded-lg bg-white/10" />

                        <div className="mt-8 grid w-full max-w-[560px] grid-cols-1 gap-3 sm:grid-cols-2">
                            <Skeleton className="h-[52px] w-full rounded-[10px] bg-white/15" />
                            <Skeleton className="h-[52px] w-full rounded-[10px] bg-white/10" />
                        </div>
                    </div>

                    <div className="mt-9 grid grid-cols-2 border-t border-white/10 md:grid-cols-4">
                        {Array.from({ length: 4 }).map((_, idx) => (
                            <div
                                key={idx}
                                className="flex min-h-[92px] flex-col items-center justify-center p-5"
                            >
                                <Skeleton className="h-8 w-20 rounded-lg bg-white/10" />
                                <Skeleton className="mt-2 h-3 w-24 rounded-full bg-white/10" />
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* ── Story Section ── */}
            <section className="w-full border-b border-[#E9E9E9] bg-[#FAFAFB] py-12 sm:py-20 lg:py-24">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 gap-12 md:grid-cols-3 md:gap-10 lg:gap-16">
                        {Array.from({ length: 3 }).map((_, idx) => (
                            <div
                                key={idx}
                                className="flex flex-col items-center text-center"
                            >
                                <Skeleton className="h-3 w-20 rounded-full" />
                                <Skeleton className="mt-2 h-px w-7 bg-gray-300" />
                                <Skeleton className="mt-5 h-20 w-full max-w-[330px] rounded-xl" />
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* ── Media Reviews Video Section ── */}
            <section className="w-full  py-12 sm:py-16 lg:py-20">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between gap-6">
                        <Skeleton className="h-6 w-48 rounded-lg bg-white/10" />
                        <div className="flex gap-3">
                            <Skeleton className="h-10 w-10 rounded-[9px] bg-white/10" />
                            <Skeleton className="h-10 w-10 rounded-[9px] bg-white/10" />
                        </div>
                    </div>
                    <div className="mt-10 flex gap-5 overflow-hidden">
                        {Array.from({ length: 4 }).map((_, idx) => (
                            <Skeleton
                                key={idx}
                                className="h-[360px] w-full sm:w-[calc((100%-3*1.25rem)/4)] rounded-[16px] bg-white/10 shrink-0"
                            />
                        ))}
                    </div>
                </div>
            </section>

            {/* ── Partners Section ── */}
            <section className="w-full bg-white py-12 border-y border-[#E5E7EB]">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-around gap-6">
                        {Array.from({ length: 5 }).map((_, idx) => (
                            <Skeleton
                                key={idx}
                                className="h-16 w-32 rounded-xl bg-gray-200"
                            />
                        ))}
                    </div>
                </div>
            </section>

            {/* ── Testimonials Section ── */}
            <section className="w-full  py-24">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="mb-16 flex items-end justify-between gap-8">
                        <div>
                            <Skeleton className="h-4 w-28 rounded-full bg-white/10" />
                            <Skeleton className="mt-3 h-9 w-64 rounded-xl bg-white/10" />
                        </div>
                        <Skeleton className="h-6 w-36 rounded-lg bg-white/10" />
                    </div>
                    <div className="grid grid-cols-1 gap-4 lg:grid-cols-3">
                        {Array.from({ length: 3 }).map((_, idx) => (
                            <Skeleton
                                key={idx}
                                className="h-56 w-full rounded-2xl bg-white/10"
                            />
                        ))}
                    </div>
                </div>
            </section>
        </main>
    );
}
