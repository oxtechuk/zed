export interface ICalculatorResultCardProps {
  monthlyPayment: number;
  loanAmount: number;
  totalPayment: number;
  totalInterest: number;
  whatsappHref: string;
  isSubmitting: boolean;
  onSubmitLead: () => void;
}
