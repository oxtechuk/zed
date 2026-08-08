import { useTranslation } from "react-i18next";
import { Clock } from "lucide-react";
import type { IWorkingHoursCardProps } from "../../interfaces/IWorkingHoursCardProps";

export default function WorkingHoursCard({ workingHours }: IWorkingHoursCardProps) {
  const { t } = useTranslation();

  return (
    <div className="rounded-3xl bg-[#0F172A] p-6 text-white shadow-md flex flex-col relative overflow-hidden">
      <div className="absolute top-0 end-0 w-24 h-24 bg-[#EDC98E]/5 blur-2xl rounded-full" />

      <div className="flex items-center gap-3 border-b border-white/5 pb-4 mb-4">
        <Clock size={20} className="text-[#EDC98E]" />
        <strong className="text-[16px] font-black text-[#EDC98E]">
          {t("contactPage.contactUs.labels.workingHours")}
        </strong>
      </div>

      <div className="flex flex-col gap-3.5">
        {workingHours.map((line, index) =>
          line.hours ? (
            <div
              key={index}
              className="flex items-center justify-between text-[13px] font-semibold text-white/80"
            >
              <span>{line.day}</span>
              <strong className="text-white">{line.hours}</strong>
            </div>
          ) : (
            <div key={index} className="text-[13px] font-semibold text-white/80">
              {line.day}
            </div>
          )
        )}
      </div>

      <div className="mt-6 pt-4 border-t border-white/5 flex items-center justify-start gap-2.5">
        <span className="relative flex h-2 w-2">
          <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
          <span className="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
        </span>
        <span className="text-[12px] text-emerald-400 font-bold">
          {t("contactPage.contactUs.labels.whatsappAvailable")}
        </span>
      </div>
    </div>
  );
}
