import type { ILocationItem } from "./ILocationItem";

export interface ILocationButtonProps {
  location: ILocationItem;
  isActive: boolean;
  onSelect: (id: string) => void;
}
