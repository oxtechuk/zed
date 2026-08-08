import Skeleton from "../Skeleton";
import BlogCardSkeleton from "./BlogCardSkeleton";

export default function BlogsPageSkeleton() {
    return (
        <main
            aria-busy="true"
            aria-label="Loading blog page"
            className="min-h-screen select-none bg-[#FAFAFB]"
        >
            {/* ── Page Header ── */}
            <section className="relative w-full overflow-hidden py-7 md:py-9">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <Skeleton className="mb-1.5 h-4 w-32 rounded-lg" />
                    <Skeleton className="h-9 w-72 md:w-96" />
                    <Skeleton className="mt-2 h-4 w-full max-w-2xl" />
                </div>
            </section>

            {/* ── Filter Category Tabs ── */}
            <section className="pt-10 pb-4">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-wrap items-start justify-start gap-2">
                        {Array.from({ length: 5 }).map((_, index) => (
                            <Skeleton
                                key={index}
                                className="h-[36px] w-24 rounded-full"
                            />
                        ))}
                    </div>
                </div>
            </section>

            {/* ── Blog Grid & Pagination ── */}
            <section className="mx-auto max-w-7xl px-4 py-8 pb-20 sm:px-6 lg:px-8">
                <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    {Array.from({ length: 6 }).map((_, index) => (
                        <BlogCardSkeleton key={index} />
                    ))}
                </div>

                <div className="mt-14 flex items-center justify-center gap-2.5">
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
