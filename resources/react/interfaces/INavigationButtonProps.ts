import type { ReactNode } from "react";

export interface INavigationButtonProps {
  children: ReactNode;
  onClick: () => void;
  ariaLabel: string;
}
