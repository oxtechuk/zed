export interface IOfferListCardProps {
  id: string | number;
  image: string;
  title: string;
  description: string;
  priceLabel: string;
  price: number;
  priceUnit?: string;
  tag?: string;
  ends_at?: string;
  buttonText: string;
  buttonTo: string;
}
