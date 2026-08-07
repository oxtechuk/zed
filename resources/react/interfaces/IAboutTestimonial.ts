export interface IAboutTestimonial {
  id: number;
  name: string;
  title: string;
  content: string;
  image: string | null;
  review_image: string | null;
  review_video: string | null;
  rating: number;
  type?: string;
}
