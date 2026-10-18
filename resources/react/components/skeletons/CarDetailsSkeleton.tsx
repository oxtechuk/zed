export default function CarDetailsSkeleton() {
  return (
    <div className="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8 animate-pulse">
      {/* Breadcrumb Skeleton */}
      <div className="mb-6 flex items-center gap-2">
        <div className="h-4 w-16 rounded bg-gray-200" />
        <div className="h-4 w-4 rounded bg-gray-200" />
        <div className="h-4 w-24 rounded bg-gray-200" />
        <div className="h-4 w-4 rounded bg-gray-200" />
        <div className="h-4 w-36 rounded bg-gray-200" />
      </div>

      {/* Hero Section Grid */}
      <div className="grid grid-cols-1 gap-8 lg:grid-cols-12 mb-12">
        {/* Left Column: Gallery Skeleton */}
        <div className="lg:col-span-8 flex flex-col gap-4">
          <div className="h-[380px] sm:h-[460px] w-full rounded-3xl bg-gray-200" />
          <div className="flex gap-3">
            {[1, 2, 3, 4].map((i) => (
              <div key={i} className="h-20 w-24 rounded-2xl bg-gray-200" />
            ))}
          </div>
        </div>

        {/* Right Column: Price & Finance Calculator Skeleton */}
        <div className="lg:col-span-4 flex flex-col gap-4">
          <div className="h-8 w-3/4 rounded-xl bg-gray-200" />
          <div className="h-4 w-1/2 rounded bg-gray-200" />
          <div className="h-28 w-full rounded-2xl bg-gray-200 mt-2" />
          <div className="h-12 w-full rounded-xl bg-gray-200 mt-4" />
          <div className="h-12 w-full rounded-xl bg-gray-200" />
        </div>
      </div>

      {/* Specifications & Features Skeleton */}
      <div className="grid grid-cols-1 gap-8 lg:grid-cols-12 mb-12">
        <div className="lg:col-span-8">
          <div className="h-64 w-full rounded-3xl bg-gray-200" />
        </div>
        <div className="lg:col-span-4">
          <div className="h-64 w-full rounded-3xl bg-gray-200" />
        </div>
      </div>
    </div>
  );
}
