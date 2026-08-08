import Skeleton from "../Skeleton";
import BlogCardSkeleton from "./BlogCardSkeleton";

export default function BlogDetailsPageSkeleton() {
    return (
        <main
            aria-busy="true"
            aria-label="Loading blog article"
            className="min-h-screen select-none bg-[#F0F2F5]"
        >
            {/* ── Page Header ── */}
            <section className="w-full py-6 md:py-8">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <Skeleton className="mb-2 h-4 w-28 rounded-lg" />
                    <Skeleton className="h-10 w-full max-w-2xl md:h-12" />
                    <Skeleton className="mt-2 h-10 w-3/4 max-w-xl md:h-12" />

                    <div className="mt-4 flex flex-wrap items-center gap-4">
                        <Skeleton className="h-7 w-24 rounded-full" />
                        <Skeleton className="h-5 w-20" />
                        <Skeleton className="h-5 w-28" />
                    </div>
                </div>
            </section>

            {/* ── Article Content ── */}
            <section className="py-8">
                <div className="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                    {Array.from({ length: 3 }).map((_, index) => (
                        <div
                            key={index}
                            className="rounded-[16px] bg-white px-6 py-7 shadow-sm md:px-10"
                        >
                            <Skeleton className="mb-5 h-6 w-56" />
                            <Skeleton className="h-4 w-full" />
                            <Skeleton className="mt-3 h-4 w-full" />
                            <Skeleton className="mt-3 h-4 w-2/3" />
                        </div>
                    ))}
                </div>
            </section>

            {/* ── Related Articles ── */}
            <section className="py-14">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <Skeleton className="mb-10 h-8 w-56" />

                    <div className="grid grid-cols-1 gap-x-10 gap-y-14 md:grid-cols-2 lg:grid-cols-3">
                        {Array.from({ length: 3 }).map((_, index) => (
                            <BlogCardSkeleton key={index} />
                        ))}
                    </div>
                </div>
            </section>
        </main>
    );
}
