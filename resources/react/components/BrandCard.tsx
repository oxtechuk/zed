import { Link } from "react-router-dom";
import type { IBrandCardProps } from "../interfaces/IBrandCardProps";
import LazyImg from "./LazyImg";

export default function BrandCard({ name, logo, onClick, to }: IBrandCardProps) {
  const content = (
    <>
      <LazyImg src={logo} alt={name} className="h-[52px] w-auto object-contain" />
      <h3 className="mt-5 text-[20px] font-bold text-[#111827]">{name}</h3>
    </>
  );

  const cardClasses = `flex h-[150px] w-full flex-col items-center justify-center rounded-[16px] bg-white px-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md text-inherit no-underline ${
    to || onClick ? "cursor-pointer" : ""
  }`;

  if (to) {
    return (
      <Link to={to} className={cardClasses} aria-label={name}>
        {content}
      </Link>
    );
  }

  return (
    <div onClick={onClick} className={cardClasses}>
      {content}
    </div>
  );
}

