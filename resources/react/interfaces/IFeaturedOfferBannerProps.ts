export interface IFeaturedOfferBannerProps {
  id?: string | number;
  image?: string;
  image_mobile?: string;
  background_image?: string;
  title?: string;
  description?: string;
  tag?: string;
  badge?: string;
  ends_at?: string;
  button_text?: string;
  button_url?: string;
  hero?: {
    title?: string;
    subtitle?: string;
    image?: string | null;
    image_mobile?: string | null;
    tag?: string;
    button_text?: string;
    button_url?: string;
    ends_at?: string;
  } | null;
}
