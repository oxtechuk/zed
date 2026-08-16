import api from "./http";

export interface ISubmitBookingData {
  car_id: number;
  client_name: string;
  client_phone: string;
  client_email?: string;
  down_payment?: number;
  duration_years?: number;
  interest_rate?: number;
  notes?: string;
  booking_type?: "test_drive" | "purchase" | "inquiry";
  location?: string;
  calculator_bank_id?: number | null;
  salary?: number;
  monthly_obligations?: number;
  employer_type?: string;
  years_of_service?: number;
  has_personal_loan?: boolean;
  has_mortgage_loan?: boolean;
  has_simah_default?: boolean;
  has_traffic_violations?: boolean;
  preferred_color?: string;
  monthly_installment?: number;
}

export async function submitBooking(data: ISubmitBookingData) {
  const res = await api.post("store/booking", {
    car_id: data.car_id,
    client_name: data.client_name,
    client_phone: data.client_phone,
    client_email: data.client_email || null,
    down_payment: data.down_payment ?? 0,
    duration_years: data.duration_years ?? 5,
    interest_rate: data.interest_rate ?? 0,
    notes: data.notes || null,
    booking_type: data.booking_type || "purchase",
    location: data.location || null,
    calculator_bank_id: data.calculator_bank_id || null,
    salary: data.salary,
    monthly_obligations: data.monthly_obligations,
    employer_type: data.employer_type,
    years_of_service: data.years_of_service,
    has_personal_loan: data.has_personal_loan,
    has_mortgage_loan: data.has_mortgage_loan,
    has_simah_default: data.has_simah_default,
    has_traffic_violations: data.has_traffic_violations,
    preferred_color: data.preferred_color,
    monthly_installment: data.monthly_installment,
  });
  return res.data;
}
