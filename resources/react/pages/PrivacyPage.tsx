import { useTranslation } from "react-i18next";
import { useSEO } from "../utils/useSEO";
import { ShieldCheck, Lock } from "lucide-react";

export default function PrivacyPage() {
  const { t, i18n } = useTranslation();
  useSEO(
    "سياسة الخصوصية | زاد كابيتال",
    "سياسة الخصوصية وحماية البيانات الشخصية لعملاء زاد كابيتال لتمويل السيارات وفقاً للأنظمة واللوائح المعمول بها في المملكة العربية السعودية."
  );

  return (
    <main dir={i18n.dir()} className="min-h-screen bg-[#F8FAFC] py-12 sm:py-16">
      <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div className="rounded-3xl bg-white p-6 sm:p-12 shadow-sm border border-[#E7E9EF]">
          <div className="flex items-center gap-3 mb-6">
            <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#16254F]/5 text-[#16254F]">
              <ShieldCheck size={24} />
            </div>
            <div>
              <span className="text-[12px] font-bold text-[#EDC98E] bg-[#16254F] px-2.5 py-0.5 rounded-full">
                حماية البيانات
              </span>
              <h1 className="text-[26px] sm:text-[34px] font-black text-[#16254F] leading-tight mt-1">
                سياسة الخصوصية
              </h1>
            </div>
          </div>

          <p className="text-[14px] text-[#667085] mb-8 pb-6 border-b border-gray-100">
            تلتزم شركة <strong>زاد كابيتال</strong> بحماية خصوصية وأمان بياناتك الشخصية والمالية وفقاً لنظام حماية البيانات الشخصية في المملكة العربية السعودية.
          </p>

          <div className="space-y-8 text-start text-[15px] leading-relaxed text-[#374151]">
            <section>
              <h2 className="text-[18px] font-extrabold text-[#16254F] mb-3">
                1. البيانات التي نجمعها
              </h2>
              <p>
                لتقديم أفضل عروض التمويل، نقوم بجمع البيانات الضرورية فقط، وتشمل:
              </p>
              <ul className="mt-2 list-disc list-inside space-y-1.5 text-gray-600">
                <li>الاسم الكامل ورقم الهوية الوطنية / الإقامة.</li>
                <li>بيانات التواصل (رقم الجوال، البريد الإلكتروني، والمدينة).</li>
                <li>المعلومات المالية والوظيفية (الراتب الصافي، جهة العمل، والالتزامات الشهرية).</li>
                <li>تفضيلات السيارة المطلوبة (الماركة، الفئة، والميزانية).</li>
              </ul>
            </section>

            <section>
              <h2 className="text-[18px] font-extrabold text-[#16254F] mb-3">
                2. كيف نستخدم بياناتك؟
              </h2>
              <p>
                تُستخدم بياناتك حصراً للأغراض التالية:
              </p>
              <ul className="mt-2 list-disc list-inside space-y-1.5 text-gray-600">
                <li>دراسة طلب التمويل وتقديم أفضل العروض المتوافقة مع احتياجك.</li>
                <li>مشاركة البيانات مع جهات التمويل الشريكة (البنوك وشركات التمويل المعتمدة) بموافقتك لاستخراج عروض الأسعار.</li>
                <li>التواصل معك عبر الاتصال أو تطبيق واتساب لمتابعة طلبك وإتمام الإجراءات.</li>
              </ul>
            </section>

            <section>
              <h2 className="text-[18px] font-extrabold text-[#16254F] mb-3">
                3. سرية وأمان البيانات
              </h2>
              <p>
                نطبق معايير تقنية وأمنية متقدمة لتشفير وحماية البيانات من الوصول غير المصرح به. نؤكد أننا <strong>لا نقوم أبداً ببيع أو تأجير بياناتك لأي أطراف ثالثة لأغراض تسويقية غير متعلقة بطلبك</strong>.
              </p>
            </section>

            <section>
              <h2 className="text-[18px] font-extrabold text-[#16254F] mb-3">
                4. حقوق العميل والتواصل
              </h2>
              <p>
                يحق لك في أي وقت طلب مراجعة أو تصحيح أو حذف بياناتك المسجلة لدينا. للتواصل بخصوص سياسة الخصوصية، يرجى مراسلتنا على: <strong>privacy@zadcapital.sa</strong> أو الاتصال على الرقم الموحد <strong>920018657</strong>.
              </p>
            </section>
          </div>
        </div>
      </div>
    </main>
  );
}
