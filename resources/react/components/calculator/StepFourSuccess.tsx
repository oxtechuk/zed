import { MessageCircle, Check } from "lucide-react";
import { useNavigate } from "react-router-dom";

interface IStepFourSuccessProps {
  carName: string;
  phone: string;
  onReset: () => void;
}

export default function StepFourSuccess({ carName, phone, onReset }: IStepFourSuccessProps) {
  const navigate = useNavigate();

  return (
    <div className="w-full max-w-xl mx-auto text-center" dir="rtl">
      <div className="rounded-[24px] border border-[#E5E9F0] bg-white px-6 py-12 shadow-sm md:px-10 flex flex-col items-center">
        
        {/* Success Check Circle Icon */}
        <div className="flex h-16 w-16 items-center justify-center rounded-full bg-[#FFF4E4] text-[#D97706] mb-5">
          <Check size={32} strokeWidth={3} />
        </div>

        <span className="text-[12px] font-extrabold text-[#EDC98E] uppercase tracking-wider block mb-1">
          تم الإرسال
        </span>
        
        <h2 className="text-[26px] font-black text-[#0F172A] mb-3">
          طلبك في المراجعة!
        </h2>

        <p className="text-[14px] leading-relaxed text-gray-500 font-semibold max-w-md mb-8">
          تم استلام طلب تمويل <strong className="text-[#0F172A] font-black">{carName}</strong>. سيتواصل معك أحد متخصصينا على الرقم <strong className="text-[#0F172A] font-black" dir="ltr">{phone}</strong> خلال ساعات العمل.
        </p>

        {/* Call-to-action buttons */}
        <div className="w-full flex flex-col gap-3">
          <a
            href={`https://wa.me/966500000000?text=أرغب في متابعة طلب التمويل المرسل لسيارة ${carName}`}
            target="_blank"
            rel="noreferrer"
            className="flex h-[50px] w-full items-center justify-center gap-2 rounded-xl bg-[#25D366] text-[15px] font-extrabold text-white transition hover:bg-[#20ba59] hover:scale-[1.01] active:scale-95 shadow-sm"
          >
            <MessageCircle size={18} />
            <span>تابع طلبك عبر واتساب</span>
          </a>

          <button
            type="button"
            onClick={() => {
              onReset();
              navigate("/cars");
            }}
            className="flex h-[50px] w-full items-center justify-center gap-2 rounded-xl border border-gray-200 text-[14px] font-bold text-gray-500 transition hover:bg-gray-50 active:scale-95"
          >
            <span>العودة لتصفح السيارات</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
              <line x1="5" y1="12" x2="19" y2="12" />
              <polyline points="12 5 19 12 12 19" className="rotate-180" />
            </svg>
          </button>
        </div>

      </div>
    </div>
  );
}
