import { useTranslation } from "react-i18next";
import type { HomepageStat } from "../types/home.types";

interface AllCarsHeroProps {
  offerImage?: string;
  badge?: string;
  titleLine1?: string;
  titleLine2Prefix?: string;
  titleLine2Highlight?: string;
  description?: string;
  stats?: HomepageStat[];
  primaryButtonText?: string;
  primaryButtonTo?: string;
}

// eslint-disable-next-line @typescript-eslint/no-unused-vars
export default function AllCarsHero(_props: AllCarsHeroProps) {
  const { i18n } = useTranslation();

  return (
    <section
      dir={i18n.dir()}
      className="relative w-full overflow-hidden bg-[#010915] text-white"
    >
      <div
        className="absolute inset-0"
        style={{ background: "var(--brand-overlay-gradient)" }}
      />
    </section>
  );
}
