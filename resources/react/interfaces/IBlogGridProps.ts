import type { IBlogCardProps } from "./IBlogCardProps";

export interface IBlogGridProps {
  articles: Partial<IBlogCardProps>[];
  isLoading: boolean;
}
