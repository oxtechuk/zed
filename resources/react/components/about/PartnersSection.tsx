import type { IPartnersSectionProps } from "../../interfaces/IPartnersSectionProps";
import PartnersCarousel from "../PartnersCarousel";

export default function PartnersSection({ partners }: IPartnersSectionProps) {
  if (partners.length === 0) return null;

  return (
    <section className="w-full bg-white py-12 border-y border-[#E5E7EB]">
      <PartnersCarousel
        partners={partners.map((p) => ({
          id: p.id,
          name: p.name,
          logo: p.logo,
          link: p.link,
        }))}
      />
    </section>
  );
}
