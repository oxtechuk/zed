export interface IBlogPaginationProps {
  currentPage: number;
  lastPage: number;
  onPageChange: (page: number) => void;
  isRTL?: boolean;
}
