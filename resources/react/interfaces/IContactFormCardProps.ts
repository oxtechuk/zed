import type { IContactFormValues } from "./IContactFormValues";

export interface IContactFormCardProps {
  isSubmitting?: boolean;
  whatsappNumber: string;
  onSubmit?: (values: IContactFormValues) => void;
}
