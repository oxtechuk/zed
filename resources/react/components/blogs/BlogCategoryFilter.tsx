import type { IBlogCategoryFilterProps } from "../../interfaces/IBlogCategoryFilterProps";

export default function BlogCategoryFilter({
  categories,
  activeCategory,
  onCategoryChange,
}: IBlogCategoryFilterProps) {
  return (
    <section className="pt-10 pb-4">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div className="flex flex-wrap items-center justify-center gap-2">
          {categories.map((category) => {
            const isActive = category.value === activeCategory;
            return (
              <button
                key={category.value}
                type="button"
                onClick={() => onCategoryChange(category.value)}
                className={`h-[36px] rounded-full px-4 text-[14px] font-bold transition duration-200 cursor-pointer ${
                  isActive
                    ? "bg-[#16254F] text-white"
                    : "border border-[#E7E9EF] bg-white text-[#667085] hover:bg-[#16254F] hover:text-white hover:border-[#16254F]"
                }`}
              >
                {category.label}
              </button>
            );
          })}
        </div>
      </div>
    </section>
  );
}
