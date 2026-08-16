export interface ICalculatorLeadRequest {
  name: string;
  phone: string;
  email?: string;
  city: string;
  purpose?: string;
  salary: number;
  monthly_obligations: number;
  car_ids?: number[];
  notes?: string;
  preferred_bank_id?: number;
  monthly_installment?: number;
  down_payment?: number;
  period_months?: number;
  employer_type?: string;
  employer_name?: string;
  years_of_service?: number;
  has_mortgage_loan?: boolean;
  has_personal_loan?: boolean;
  has_traffic_violations?: boolean;
  has_simah_default?: boolean;
  preferred_color?: string;
}

export interface ICalculatorLeadResponse {
  success: boolean;
  message: string;
  data: { lead_id: number } | null;
  errors: unknown;
  meta: unknown;
}
