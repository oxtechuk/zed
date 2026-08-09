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
}

export async function submitBooking(data: ISubmitBookingData) {
  const res = await api.post("/store-api/booking", {
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
  });
  return res.data;
}
