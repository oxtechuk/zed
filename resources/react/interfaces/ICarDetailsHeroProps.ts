export interface ICarColor {
  name: string;
  value: string;
  image?: string | null;
}

export interface ICarDetailsHeroProps {
  id?: number;
  slug?: string;
  title: string;
  description: string;
  images: string[];
  exteriorImages?: string[];
  interiorImages?: string[];
  mainImage?: string;
  price: number;
  oldPrice?: number;
  monthlyInstallment: number;
  savingAmount?: number;
  colors: ICarColor[];
  orderTo: string;
  financeTo: string;
  fuelType?: string;
  transmission?: string;
  seats?: string;
  horsepower?: string;
  type?: string;
  model?: string;
  year?: string;
  brandName?: string;
  featuresList?: any[];
  safetyFeatures?: any[];
  specs?: any;
  availabilityStatus?: string;
}
