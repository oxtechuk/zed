export interface IBrandItem {
  id: string | number;
  name: string;
  logo: string;
  url?: string;
}

export interface IBrandsCarouselProps {
  brands: IBrandItem[];
  speed?: number;
  showName?: boolean;
}
