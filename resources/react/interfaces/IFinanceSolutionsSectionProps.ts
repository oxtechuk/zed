export interface IStatItem {
  value: string;
  label: string;
}

export interface IFinanceSlideItem {
  id?: string | number;
  badge?: string;
  installmentAmount?: string;
  installmentLabel?: string;
  installmentPeriod?: string;
  title?: string;
  titleHighlight?: string;
  description?: string;
  primaryBtnText?: string;
  primaryBtnUrl?: string;
  secondaryBtnText?: string;
  secondaryBtnUrl?: string;
  image?: string;
  features?: {
    icon?: string;
    text: string;
  }[];
}

export interface IFinanceSolutionsSectionProps {
  backgroundImage?: string;
  titleBlue?: string;
  titleOrange?: string;
  description?: string;
  buttonText?: string;
  buttonTo?: string;
  stats?: IStatItem[];
  features?: string[];
  className?: string;
  slides?: IFinanceSlideItem[];
  banner?: Record<string, any>;
  steps?: {
    number: string;
    title: string;
    description: string;
    icon: string;
  }[];
}
