import type { IPartnersSectionProps } from "../../interfaces/IPartnersSectionProps";
import LazyImg from "../LazyImg";

export default function PartnersSection({ partners }: IPartnersSectionProps) {
  if (partners.length === 0) return null;

  return (
    <section className="w-full bg-white py-12 border-y border-[#E5E7EB]">
      <div className="mx-auto max-w-7xl px-6 sm:px-8 lg:px-12">
        {/* Horizontal flex container for bank logos */}
        <div className="flex flex-wrap items-center justify-center gap-10 sm:gap-14 md:gap-16 lg:gap-20">
          {partners.map((partner) => (
            <div
              key={partner.id}
              className="flex items-center justify-center transition-all duration-300 hover:scale-105"
            >
              <LazyImg
                src={partner.logo}
                alt={partner.name}
                className="h-10 md:h-12 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300"
              />
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
