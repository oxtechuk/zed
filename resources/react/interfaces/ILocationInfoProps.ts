import type { ReactNode } from "react";

export interface ILocationInfoProps {
  icon: ReactNode;
  label: string;
  value: string;
  href?: string;
}
