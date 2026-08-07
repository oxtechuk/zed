import type { IBlogFilterCategory } from "./IBlogFilterCategory";

export interface IBlogCategoryFilterProps {
  categories: IBlogFilterCategory[];
  activeCategory: string;
  onCategoryChange: (category: string) => void;
}
