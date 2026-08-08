import { useTranslation } from "react-i18next";
import { ArrowLeft, ArrowRight, MapPin } from "lucide-react";
import type { IBranchMapCardProps } from "../../interfaces/IBranchMapCardProps";

export default function BranchMapCard({ name, address, mapLink }: IBranchMapCardProps) {
  const { i18n, t } = useTranslation();

  return (
    <div className="rounded-3xl border border-[#E5E9F0] bg-white p-6 shadow-sm flex flex-col items-center text-center">
      <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-[#EAF1FA] text-[#034EA2] mb-4">
        <MapPin size={22} />
      </div>
      <strong className="text-[17px] font-black text-[#0F172A]">{name}</strong>
      <span className="text-[13px] text-gray-400 font-bold mt-1">{address}</span>

      {mapLink && (
        <a
          href={mapLink}
          target="_blank"
          rel="noreferrer"
          className="mt-5 w-full h-11 border border-[#E5E9F0] text-[#0F172A] text-[13px] font-bold rounded-xl flex items-center justify-center gap-1.5 transition hover:bg-[#F8FAFC] active:scale-95"
        >
          <span>{t("contactPage.contactUs.labels.openInMaps")}</span>
          {i18n.dir() === "rtl" ? (
            <ArrowLeft size={14} strokeWidth={2.5} />
          ) : (
            <ArrowRight size={14} strokeWidth={2.5} />
          )}
        </a>
      )}
    </div>
  );
}
