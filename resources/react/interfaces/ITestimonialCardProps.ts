import type { ITestimonialItem } from "./ITestimonialItem";

export interface ITestimonialCardProps {
  testimonial: ITestimonialItem & { isActive?: boolean };
}
