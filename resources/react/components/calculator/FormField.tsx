import type { IFormFieldProps } from "../../interfaces/IFormFieldProps";

export default function FormField({ label, required, children }: IFormFieldProps) {
  return (
    <label className="block">
      <span className="mb-3 block text-[15px] font-extrabold text-[#07111F] text-start">
        {label}
        {required && (
          <span className="ms-1 text-[var(--brand-secondary-color)]">*</span>
        )}
      </span>
      {children}
    </label>
  );
}
