import type { FeatureItem } from "../types/cars.types";

export interface ISpecItem {
  label: string;
  value: string;
}

export interface IFeatureItem {
  id: number;
  name: string;
  icon: string;
}

export interface ITab {
  label: string;
  type: "specs" | "safety" | "other";
  items: ISpecItem[] | string[] | IFeatureItem[] | (string | IFeatureItem)[];
}

export interface ICarDetailsSpecsProps {
  specifications?: FeatureItem[];
  featuresList?: FeatureItem[];
  safetyFeatures?: FeatureItem[];
  specs?: Record<string, string | null>;
  availabilityStatus?: string;
  type?: string;
  year?: string;
  showOnly?: "specs" | "features";
}
