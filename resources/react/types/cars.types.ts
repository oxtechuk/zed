import type { ICarItem, IBrandInfo, IFilterPrice, IHomepageStat, IFilterCategory } from "./home.types";

export interface ICarType {
  id: number;
  name: string;
  slug: string;
}
export type CarType = ICarType;

export interface ICarsQueryParams {
  page?: number;
  per_page?: number;
  brands?: number[];
  model_id?: number;
  type?: number;
  category_id?: number;
  year?: string;
  min_price?: number;
  max_price?: number;
  search?: string;
  q?: string;
  offer_id?: number;
  sort?: "price_asc" | "price_desc" | "year_desc" | "year_asc";
  sort_by?: string;
  order?: "asc" | "desc";
}
export type CarsQueryParams = ICarsQueryParams;

export interface ICarsListResponse {
  data: ICarItem[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}
export type CarsListResponse = ICarsListResponse;

export interface ICarColorWithImage {
  hex: string;
  name: string;
  image: string | null;
}
export type CarColorWithImage = ICarColorWithImage;

export interface IFeatureItem {
  id: number;
  name: string;
  icon: string;
}
export type FeatureItem = IFeatureItem;

export interface ICarDetails {
  id: number;
  name: string;
  slug: string;
  main_image: string;
  thumbnail: string;
  images: string[];
  exterior_images?: string[];
  interior_images?: string[];
  cash_price: number;
  min_installment: number;
  current_price: number;
  year: string;
  is_current_year: boolean;
  model?: string;
  type: string;
  colors: ICarColorWithImage[];
  specs: Record<string, string | null>;
  description: string;
  features: string;
  is_featured: boolean;
  availability_status: string;
  views: number;
  brand: { id: number; name: string; slug: string; logo: string };
  category: { id: number; name: string; slug: string } | null;
  active_offers: any[];
  offers: any[];
  specifications: IFeatureItem[];
  features_list: IFeatureItem[];
  safety_features?: IFeatureItem[];
  related_cars: ICarItem[];
}
export type CarDetails = ICarDetails;

export interface ICarsSidebarFilterData {
  brands: IBrandInfo[];
  minPrice: number;
  maxPrice: number;
  engines: string[];
  transmissions: string[];
  fuelTypes: string[];
}
export type CarsSidebarFilterData = ICarsSidebarFilterData;

export interface IFilterValues {
  brandId: number | null;
  modelId: number | null;
  type: string;
  categoryId: number | null;
  year: string;
  priceMin: number;
  priceMax: number;
  engine: string;
  transmission: string;
  fuelType: string;
  seats: string;
  search: string;
}
export type FilterValues = IFilterValues;

export const DEFAULT_FILTER_VALUES: IFilterValues = {
  brandId: null,
  modelId: null,
  type: "all",
  categoryId: null,
  year: "",
  priceMin: 0,
  priceMax: Infinity,
  engine: "all",
  transmission: "all",
  fuelType: "all",
  seats: "all",
  search: "",
};

export interface IHeroSlide {
  link: string;
  image: string;
  button_text: string;
}
export type HeroSlide = IHeroSlide;

export interface IHeroAd {
  image: string | null;
  link: string;
}
export type HeroAd = IHeroAd;

export interface IFeaturedOfferCar {
  id: number;
  name: string;
  slug: string;
  main_image: string | null;
  thumbnail: string | null;
  cash_price: number;
  min_installment: number;
  current_price: number;
  type: string;
  year: string;
  specs: Record<string, string | null>;
  is_current_year: boolean;
}
export type FeaturedOfferCar = IFeaturedOfferCar;

export interface IFeaturedOffer {
  id: number;
  title: string;
  description: string;
  image: string | null;
  installment_starts_from: number;
  cars: IFeaturedOfferCar[];
}
export type FeaturedOffer = IFeaturedOffer;

export interface ICarsMetaData {
  featured_offer: IFeaturedOffer | null;
  total_cars: number;
  total_brands: number;
  hero_badge?: string;
  hero_title_line1?: string;
  hero_title_line2_prefix?: string;
  hero_title_line2_highlight?: string;
  hero_description?: string;
  filter_brands: IBrandInfo[];
  filter_types: IFilterCategory[];
  filter_categories: IFilterCategory[];
  filter_brand_types: any[];
  filter_years: string[];
  filter_prices: IFilterPrice[];
  filter_fuels: string[];
  homepage_stats: IHomepageStat[];
  hero_slides: IHeroSlide[];
  hero_ads: IHeroAd[];
}
export type CarsMetaData = ICarsMetaData;
