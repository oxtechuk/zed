import Skeleton from "../Skeleton";

export default function ContactPageSkeleton() {
    return (
        <main
            aria-busy="true"
            aria-label="Loading contact page"
            className="min-h-screen select-none bg-[#F3F4F6]"
        >
            {/* ── Page Header / Hero ── */}
            <section className="w-full pb-24 pt-16">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <Skeleton className="h-4 w-32" />
                    <Skeleton className="mt-3 h-12 w-72 md:h-14 md:w-96" />
                    <Skeleton className="mt-4 h-4 w-full max-w-xl" />

                    <div className="mt-12 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        {Array.from({ length: 4 }).map((_, index) => (
                            <div key={index} className="rounded-2xl bg-white p-5">
                                <Skeleton className="h-10 w-10 rounded-xl" />
                                <Skeleton className="mt-3 h-4 w-20" />
                                <Skeleton className="mt-2 h-4 w-32" />
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* ── Form & Sidebar ── */}
            <section className="w-full py-16">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 items-start gap-10 lg:grid-cols-12">
                        <div className="lg:col-span-8">
                            <div className="overflow-hidden rounded-3xl bg-white shadow-sm">
                                <div className="px-8 py-6">
                                    <Skeleton className="h-3 w-24" />
                                    <Skeleton className="mt-2 h-6 w-56" />
                                    <Skeleton className="mt-1 h-3 w-40" />
                                </div>
                                <div className="p-8">
                                    <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                                        <Skeleton className="h-[50px] w-full rounded-xl" />
                                        <Skeleton className="h-[50px] w-full rounded-xl" />
                                    </div>
                                    <Skeleton className="mt-6 h-[50px] w-full rounded-xl" />
                                    <Skeleton className="mt-6 h-10 w-full" />
                                    <Skeleton className="mt-6 h-[140px] w-full rounded-xl" />
                                    <Skeleton className="mt-8 h-[50px] w-full rounded-xl" />
                                </div>
                            </div>
                        </div>

                        <div className="flex flex-col gap-6 lg:col-span-4">
                            {Array.from({ length: 2 }).map((_, index) => (
                                <div key={index} className="rounded-2xl bg-white p-6 shadow-sm">
                                    <Skeleton className="h-5 w-40" />
                                    <Skeleton className="mt-3 h-4 w-full" />
                                    <Skeleton className="mt-2 h-4 w-2/3" />
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            {/* ── FAQ ── */}
            <section className="w-full bg-white py-16">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <Skeleton className="h-4 w-28" />
                    <Skeleton className="mt-2 h-8 w-64" />

                    <div className="mt-8 space-y-4">
                        {Array.from({ length: 4 }).map((_, index) => (
                            <div key={index} className="rounded-2xl border border-[#E5E9F0] p-5">
                                <Skeleton className="h-5 w-3/4" />
                            </div>
                        ))}
                    </div>
                </div>
            </section>
        </main>
    );
}
