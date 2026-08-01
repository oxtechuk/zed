import { useTranslation } from "react-i18next";
import type { ICompareSummaryProps } from "../../interfaces/ICompareSummaryProps";

export default function CompareSummary({
  sections,
  car1Name,
  car2Name,
}: ICompareSummaryProps) {
  const { i18n } = useTranslation();

  // Calculate scores
  const car1Score = sections.reduce(
    (sum, s) => sum + s.rows.filter((r) => r.winner === 1).length,
    0,
  );
  const car2Score = sections.reduce(
    (sum, s) => sum + s.rows.filter((r) => r.winner === 2).length,
    0,
  );
  const totalCriteria = sections.reduce((sum, s) => sum + s.rows.length, 0);

  const hasWinner = car1Score !== car2Score;
  const isCar1Winner = car1Score > car2Score;
  const winnerName = isCar1Winner ? car1Name : car2Name;
  const winnerScore = isCar1Winner ? car1Score : car2Score;

  return (
    <section dir={i18n.dir()} className="mx-auto w-full max-w-[1200px] px-6 pb-14">
      <div className="rounded-[24px] border border-[#E5C287]/20 bg-[#FFF9F2] py-8 px-6 text-center shadow-xs">
        {hasWinner ? (
          <>
            <span className="text-[12px] font-black text-[#D97706] uppercase tracking-wider block mb-1">
              الأفضل في المقارنة
            </span>
            <h2 className="text-[24px] font-black text-[#0F172A] leading-tight mb-2">
              {winnerName}
            </h2>
            <p className="text-[13px] font-extrabold text-gray-500">
              تفوقت في {winnerScore} من {totalCriteria} معيار
            </p>
          </>
        ) : (
          <>
            <span className="text-[12px] font-black text-[#D97706] uppercase tracking-wider block mb-1">
              نتيجة المقارنة
            </span>
            <h2 className="text-[24px] font-black text-[#0F172A] leading-tight mb-2">
              تعادل السيارتين
            </h2>
            <p className="text-[13px] font-extrabold text-gray-500">
              تساوت السيارتان في نقاط المقارنة المباشرة
            </p>
          </>
        )}
      </div>
    </section>
  );
}
