export interface IApiResponse<T> {
  success: boolean;
  message: string;
  data: T;
  errors: null;
  meta: null;
}
export type ApiResponse<T> = IApiResponse<T>;

export interface IFilterCategory {
  id: number;
  name: string | Record<string, string>;
  slug: string;
  sort_order?: number;
  is_active?: boolean;
}
export type FilterCategory = IFilterCategory;

export interface IBrandInfo {
  id: number;
  name: string;
  slug: string;
  logo: string | null;
  cars_count?: number;
}
export type BrandInfo = IBrandInfo;

export interface ICarColor {
  hex: string;
  name: string;
}
export type CarColor = ICarColor;

export interface ICarSpec {
  label: string;
  value: string;
}
export type CarSpec = ICarSpec;

export interface ICarItem {
  id: number;
  name: string;
  slug: string;
  main_image: string | null;
  thumbnail: string | null;
  badge_color?: string | null;
  images: string[];
  cash_price: number;
  min_installment: number;
  current_price: number;
  year: number | string;
  type: string;
  transmission?: string;
  fuel_type?: string;
  seats?: string;
  colors: ICarColor[];
  specs: ICarSpec[] | Record<string, string | null>;
  description: string;
  features: string;
  is_featured: boolean;
  is_current_year?: boolean;
  availability_status: string;
  views: number;
  brand: IBrandInfo;
  active_offers: any[];
}
export type CarItem = ICarItem;

export interface IOfferItem {
  id: number;
  title: string;
  image: string | null;
  [key: string]: unknown;
}
export type OfferItem = IOfferItem;

export interface IHeroData {
  title1: string | null;
  title2: string | null;
  subtitle: string | null;
  image: string | null;
}
export type HeroData = IHeroData;

export interface IHomeStats {
  cars: number;
  brands: number;
}
export type HomeStats = IHomeStats;

export interface IFeaturedSectionButton {
  text: string | null;
  url: string | null;
}
export type FeaturedSectionButton = IFeaturedSectionButton;

export interface IFeaturedSection {
  title: string | null;
  subtitle: string | null;
  description: string | null;
  badge: string | null;
  button: IFeaturedSectionButton | null;
  image: string | null;
  background_image: string | null;
}
export type FeaturedSection = IFeaturedSection;

export interface IBudgetRange {
  label: string;
  min: number;
  max: number | null;
  cars: ICarItem[];
}
export type BudgetRange = IBudgetRange;

export interface IHomepageStat {
  label: string;
  value: string;
}
export type HomepageStat = IHomepageStat;

export interface IFilterPrice {
  min: number;
  max: number | null;
  count: number;
}
export type FilterPrice = IFilterPrice;

export interface IPageSectionContent {
  badge?: string;
  title?: string;
  subtitle?: string;
  button_text?: string;
  button_url?: string;
  description?: string;
  features?: string[];
}
export type PageSectionContent = IPageSectionContent;

export interface IPageSections {
  filter?: IPageSectionContent;
  featured_cars?: IPageSectionContent;
  offers?: IPageSectionContent;
  highlighted_cars?: IPageSectionContent;
  finance?: IPageSectionContent;
  brands?: IPageSectionContent;
  budget?: IPageSectionContent;
}
export type PageSections = IPageSections;

export interface IFinanceSettingsData {
  finance: IPageSectionContent;
  stats: IHomepageStat[];
}
export type FinanceSettingsData = IFinanceSettingsData;

export interface IHomePageData {
  featured_cars: ICarItem[];
  active_offers: any[];
  offer_cars?: ICarItem[];
  brands: IBrandInfo[];
  latest_posts: any[];
  stats: IHomeStats;
  testimonials: any[];
  partners: any[];
  hero: IHeroData;
  featured_design: null;
  social_designs: any[];
  filter_brands: IBrandInfo[];
  filter_categories: IFilterCategory[];
  filter_types: IFilterCategory[];
  filter_years: string[];
  filter_prices?: IFilterPrice[];
  bento_cars: ICarItem[];
  featured_offers: any[];
  highlighted_cars: ICarItem[];
  hero_slides: any[];
  promo_cards?: any[];
  finance_steps?: any[];
  featured_section?: IFeaturedSection;
  budget_ranges?: IBudgetRange[];
  homepage_stats?: IHomepageStat[];
  page_sections?: IPageSections;
}
export type HomePageData = IHomePageData;
