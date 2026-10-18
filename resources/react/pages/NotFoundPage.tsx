import { useTranslation } from "react-i18next";
import { Link } from "react-router-dom";
import { Home, Car, Calculator, ArrowRight } from "lucide-react";
import { useSEO } from "../utils/useSEO";

export default function NotFoundPage() {
  const { t, i18n } = useTranslation();
  const isRTL = i18n.dir() === "rtl";
  useSEO(
    t("pageTitles.notFound", { defaultValue: "الصفحة غير موجودة | زاد كابيتال" }),
    "عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها. يمكنك تصفح أحدث سياراتنا أو حساب قسطك التمويلي."
  );

  return (
    <main
      dir={i18n.dir()}
      className="min-h-[70vh] flex items-center justify-center px-4 py-16 bg-[#F8FAFC]"
    >
      <div className="max-w-xl w-full text-center bg-white p-8 sm:p-12 rounded-3xl shadow-sm border border-[#E7E9EF]">
        <span className="inline-block text-[72px] sm:text-[96px] font-black text-[#16254F] leading-none mb-4">
          404
        </span>
        <h1 className="text-[24px] sm:text-[28px] font-black text-[#16254F] mb-3">
          {t("pageTitles.notFound", { defaultValue: "الصفحة غير موجودة" })}
        </h1>
        <p className="text-[15px] text-[#667085] leading-relaxed mb-8 max-w-md mx-auto">
          عذراً، الرابط الذي تحاول الوصول إليه غير متوفر. قد يكون تم تغيير الرابط أو حذفه.
        </p>

        <div className="flex flex-col sm:flex-row items-center justify-center gap-3">
          <Link
            to="/"
            className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-[#16254F] hover:bg-[#0F1E36] text-white text-[14px] font-black transition shadow-sm"
          >
            <Home size={16} />
            <span>الرئيسية</span>
          </Link>

          <Link
            to="/cars"
            className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-[#F0F2F5] hover:bg-[#E5E7EB] text-[#16254F] text-[14px] font-black transition"
          >
            <Car size={16} />
            <span>تصفح السيارات</span>
          </Link>

          <Link
            to="/finance-calculator"
            className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl border border-[#D1D5DB] hover:border-[#16254F] text-[#16254F] text-[14px] font-black transition"
          >
            <Calculator size={16} />
            <span>حاسبة التمويل</span>
          </Link>
        </div>
      </div>
    </main>
  );
}

