export interface ICarColor {
  name: string;
  value: string;
  image?: string | null;
}

export interface ICarDetailsHeroProps {
  title: string;
  description: string;
  images: string[];
  exteriorImages?: string[];
  interiorImages?: string[];
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
  year?: string;
  brandName?: string;
}
