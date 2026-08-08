export interface IPartnerItem {
  id: string | number;
  name: string;
  logo: string;
  link?: string | null;
}

export interface IPartnersCarouselProps {
  partners: IPartnerItem[];
  speed?: number;
  showName?: boolean;
}
