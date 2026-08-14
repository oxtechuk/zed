import type { FormEvent } from "react";
import type { ICarRequestFormData } from "./ICarRequestFormData";
import type { ICarOption } from "./ICarOption";

export interface ICarRequestFormProps {
    formData: ICarRequestFormData;
    onChange: <K extends keyof ICarRequestFormData>(
        key: K,
        value: ICarRequestFormData[K],
    ) => void;
    onSubmit: (e: FormEvent<HTMLFormElement>) => void;
    isSubmitting: boolean;
    saudiCities: ICarOption[];
    employerTypes: ICarOption[];
    serviceDurations: ICarOption[];
    banks: { id: number; name: string }[];
    selectedBankId: number | null;
    onSelectBankId: (id: number | null) => void;
    loadingBanks: boolean;
}
