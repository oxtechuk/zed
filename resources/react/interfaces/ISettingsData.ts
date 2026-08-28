import type { IAboutBranch } from "./IAboutBranch";

export interface ISocialMediaItem {
  icon: string;
  link: string;
  color: string;
}

export interface IContactInfo {
  email: string;
  phone: string;
  whatsapp: string;
  address: string;
}

export interface ISettingsData {
  logo: string;
  header_logo?: string;
  footer_logo?: string;
  favicon: string;
  request_car_icon?: string;
  site_name: string;
  footer_text: string;
  footer_description?: string;
  contact: IContactInfo; 
  social_media: ISocialMediaItem[];
  about_branches?: IAboutBranch[];
}
