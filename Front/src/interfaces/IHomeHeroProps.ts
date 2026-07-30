export interface IHomeHeroSlide {
  id: number;
  title: string;
  subtitle: string;
  description: string;
  image: string;
  image_mobile: string;
  button_url: string;
  button_text: string;
  badge?: string;
}

export interface IPromoCard {
  type: string;
  title: string;
  subtitle: string;
  image: string;
  button: {
    text: string;
    url: string;
  };
  badge?: string;
}

export interface IHomeHeroProps {
  slides?: IHomeHeroSlide[];
  promoCards?: IPromoCard[];
}
