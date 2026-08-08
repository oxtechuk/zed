import type { ReactNode } from "react";

export interface IContactInfoCardProps {
  icon: ReactNode;
  label: string;
  value: string;
  href?: string;
  variant?: "whatsapp" | "default";
  valueDirection?: "ltr";
}
