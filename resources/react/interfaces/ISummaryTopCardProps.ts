import type { ReactNode } from "react";

export interface ISummaryTopCardProps {
  title: string;
  value: string | ReactNode;
  variant: "blue" | "orange";
}
