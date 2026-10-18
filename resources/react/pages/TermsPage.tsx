import { useTranslation } from "react-i18next";
import { useSEO } from "../utils/useSEO";
import { FileText, ShieldCheck } from "lucide-react";

export default function TermsPage() {
  const { t, i18n } = useTranslation();
  useSEO(
    "الشروط والأحكام | زاد كابيتال",
    "الشروط والأحكام الخاصة بخدمات الوساطة التمويلية وشراء وتمويل السيارات عبر شركة زاد كابيتال في المملكة العربية السعودية."
  );

  return (
    <main dir={i18n.dir()} className="min-h-screen bg-[#F8FAFC] py-12 sm:py-16">
      <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div className="rounded-3xl bg-white p-6 sm:p-12 shadow-sm border border-[#E7E9EF]">
          <div className="flex items-center gap-3 mb-6">
            <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#16254F]/5 text-[#16254F]">
              <FileText size={24} />
            </div>
            <div>
              <span className="text-[12px] font-bold text-[#EDC98E] bg-[#16254F] px-2.5 py-0.5 rounded-full">
                اتفاقية الخدمة
              </span>
              <h1 className="text-[26px] sm:text-[34px] font-black text-[#16254F] leading-tight mt-1">
                الشروط والأحكام
              </h1>
            </div>
          </div>

          <p className="text-[14px] text-[#667085] mb-8 pb-6 border-b border-gray-100">
            آخر تحديث: أكتوبر 2026 · تنظم هذه الاتفاقية استخدامك لخدمات منصة زاد كابيتال لتمويل وتقسيط السيارات.
          </p>

          <div className="space-y-8 text-start text-[15px] leading-relaxed text-[#374151]">
            <section>
              <h2 className="text-[18px] font-extrabold text-[#16254F] mb-3">
                1. مقدمة وتعريف بالخدمة
              </h2>
              <p>
                تُقدم منصة <strong>زاد كابيتال</strong> خدمات الوساطة الرقمية في مجال تمويل وتقسيط السيارات عبر الربط مع كبرى البنوك والشركات التمويلية المرخصة والمعتمدة من البنك المركزي السعودي (ساما).
              </p>
            </section>

            <section>
              <h2 className="text-[18px] font-extrabold text-[#16254F] mb-3">
                2. دقة بيانات التمويل والحاسبة
              </h2>
              <p>
                جميع المبالغ، والنسب، والأقساط الشهرية المعروضة عبر حاسبة التمويل بالمنصة هي نتائج <strong>تقديرية استرشادية</strong> بناءً على معطيات العميل الأولية. يخضع العرض التمويلي النهائي والملزم لموافقة الجهة التمويلية بناءً على السجل الائتماني (سمة) والسياسات الداخلية لكل جهة.
              </p>
            </section>

            <section>
              <h2 className="text-[18px] font-extrabold text-[#16254F] mb-3">
                3. التزامات العميل
              </h2>
              <p>
                يلتزم العميل بتقديم بيانات شخصية ومالية صحيحة ودقيقة، بما في ذلك بيانات الهوية، الراتب، الالتزامات الشهرية، وجهة العمل. يتحمل العميل المسؤولية الكاملة عن أي تأخير أو رفض ينتج عن عدم دقة المعلومات المقدمة.
              </p>
            </section>

            <section>
              <h2 className="text-[18px] font-extrabold text-[#16254F] mb-3">
                4. حجز وتوفر السيارات
              </h2>
              <p>
                أسعار السيارات ومواصفاتها وتوفرها في المعرض يخضع للتحديث المستمر مع الوكلاء والموزعين المعتمدين. يتم تثبيت سعر السيارة ومواصفاتها بشكل نهائي عند استكمال إجراءات التعميد مع جهة التمويل.
              </p>
            </section>

            <section>
              <h2 className="text-[18px] font-extrabold text-[#16254F] mb-3">
                5. التواصل وخدمة العملاء
              </h2>
              <p>
                لأي استفسارات بخصوص الشروط والأحكام، يسعدنا تواصلكم عبر الهاتف الموحد <strong>920018657</strong> أو عبر البريد الإلكتروني: <strong>info@zadcapital.sa</strong>.
              </p>
            </section>
          </div>
        </div>
      </div>
    </main>
  );
}
