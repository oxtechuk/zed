import { useEffect, useState, useMemo } from "react";
import { useSearchParams, useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { toast } from "react-toastify";
import { MapPin, Clock, Send, MessageCircle, ChevronDown, Check, Info, Calendar, Sparkles } from "lucide-react";
import { getCars } from "../services/api/cars.service";
import { submitCalculatorLead } from "../services/api/calculator.service";
import { getImageUrl, APP_IMAGES } from "../constants/app-images";
import { formatPrice } from "../utils/format";
import type { CarItem } from "../types/home.types";

const saudiCities = [
  { value: "الرياض", label: "الرياض" },
  { value: "جدة", label: "جدة" },
  { value: "الدمام", label: "الدمام" },
  { value: "مكة المكرمة", label: "مكة المكرمة" },
  { value: "المدينة المنورة", label: "المدينة المنورة" },
  { value: "الخبر", label: "الخبر" },
  { value: "الجبيل", label: "الجبيل" },
  { value: "الهفوف", label: "الهفوف" },
  { value: "الطائف", label: "الطائف" },
  { value: "تبوك", label: "تبوك" },
  { value: "بريدة", label: "بريدة" },
  { value: "خميس مشيط", label: "خميس مشيط" },
  { value: "حائل", label: "حائل" },
  { value: "نجران", label: "نجران" },
  { value: "أبها", label: "أبها" },
  { value: "جيزان", label: "جيزان" }
];

const employerTypes = [
  { value: "government", label: "القطاع الحكومي" },
  { value: "military", label: "القطاع العسكري" },
  { value: "company", label: "القطاع الخاص (شركة)" },
  { value: "institution", label: "القطاع الخاص (مؤسسة)" },
  { value: "retired", label: "متقاعد" }
];

const serviceDurations = [
  { value: 0.5, label: "أقل من سنة" },
  { value: 1, label: "سنة" },
  { value: 2, label: "سنتين" },
  { value: 3, label: "3 سنوات فأكثر" }
];

const defaultColorOptions = [
  { name: "كحلي", hex: "#1E293B" },
  { name: "رمادي", hex: "#6B7280" },
  { name: "فضي", hex: "#D1D5DB" },
  { name: "أسود", hex: "#111827" },
  { name: "أبيض", hex: "#FFFFFF" },
  { name: "أخضر داكن", hex: "#064E3B" },
  { name: "ذهبي", hex: "#EDC98E" },
  { name: "أزرق", hex: "#3B82F6" },
  { name: "أحمر", hex: "#EF4444" }
];

export default function CarRequestPage() {
  const { t, i18n } = useTranslation();
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();

  // Cars list
  const [cars, setCars] = useState<CarItem[]>([]);
  const [loadingCars, setLoadingCars] = useState(true);

  // Selected Car
  const [selectedCarId, setSelectedCarId] = useState<number>(0);
  const [selectedColor, setSelectedColor] = useState<string>("أبيض");
  const [term, setTerm] = useState<number>(60);

  // Form Fields
  const [fullName, setFullName] = useState("");
  const [phone, setPhone] = useState("");
  const [city, setCity] = useState("");
  const [employerType, setEmployerType] = useState("");
  const [yearsOfService, setYearsOfService] = useState<number>(1);
  const [salary, setSalary] = useState("");
  const [obligations, setObligations] = useState("");

  // Toggles
  const [hasPersonalLoan, setHasPersonalLoan] = useState(false);
  const [hasMortgageLoan, setHasMortgageLoan] = useState(false);
  const [hasSimahDefault, setHasSimahDefault] = useState(false);
  const [hasTrafficViolations, setHasTrafficViolations] = useState(false);

  const [isSubmitting, setIsSubmitting] = useState(false);
  const [isSuccess, setIsSuccess] = useState(false);

  // Load cars on mount
  useEffect(() => {
    getCars()
      .then((res) => {
        setCars(res.data);
        setLoadingCars(false);

        // Preselect car from query param if present
        const carIdParam = searchParams.get("car_id");
        if (carIdParam) {
          const parsedId = parseInt(carIdParam, 10);
          if (res.data.some((c) => c.id === parsedId)) {
            setSelectedCarId(parsedId);
          }
        } else if (res.data.length > 0) {
          setSelectedCarId(res.data[0].id);
        }
      })
      .catch(() => {
        setLoadingCars(false);
      });
  }, [searchParams]);

  // Find active car object
  const activeCar = useMemo(() => {
    return cars.find((c) => c.id === selectedCarId) || null;
  }, [cars, selectedCarId]);

  // Dynamic installment calculation
  const calculatedInstallment = useMemo(() => {
    if (!activeCar) return 0;
    const baseInstallment = activeCar.min_installment || Math.round((activeCar.current_price * 0.02));
    // Assume base min_installment is calculated for 60 months
    return Math.round((baseInstallment * 60) / term);
  }, [activeCar, term]);

  const carColors = useMemo(() => {
    if (!activeCar || !activeCar.colors || activeCar.colors.length === 0) {
      return defaultColorOptions;
    }
    return activeCar.colors.map((c) => ({
      name: c.name,
      hex: c.hex || "#CCCCCC"
    }));
  }, [activeCar]);

  // Auto-set first color when car changes
  useEffect(() => {
    if (carColors.length > 0) {
      setSelectedColor(carColors[0].name);
    }
  }, [carColors]);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    if (!selectedCarId) {
      toast.error("الرجاء اختيار سيارة أولاً");
      return;
    }
    if (!fullName || !phone || !city || !employerType || !salary || !obligations) {
      toast.error("الرجاء تعبئة جميع الحقول المطلوبة");
      return;
    }

    setIsSubmitting(true);
    try {
      await submitCalculatorLead({
        name: fullName,
        phone: phone,
        email: `${phone}@zed.com`,
        city: city,
        salary: Number(salary),
        monthly_obligations: Number(obligations),
        car_ids: [selectedCarId],
        preferred_color: selectedColor,
        employer_type: employerType,
        years_of_service: yearsOfService,
        has_personal_loan: hasPersonalLoan,
        has_mortgage_loan: hasMortgageLoan,
        has_simah_default: hasSimahDefault,
        has_traffic_violations: hasTrafficViolations,
        notes: `طلب سيارة مباشر | مدة التمويل: ${term} شهر | القسط المقدر: ${calculatedInstallment} ريال`,
      });

      setIsSuccess(true);
      toast.success("تم إرسال طلبك بنجاح! سيتواصل معك أحد مستشارينا قريباً.");
    } catch (error: any) {
      console.error("Submission error:", error);
      const apiMessage = error.response?.data?.message;
      toast.error(apiMessage || "حدث خطأ أثناء إرسال الطلب، يرجى المحاولة لاحقاً");
    } finally {
      setIsSubmitting(false);
    }
  };

  const inputClasses =
    "h-[50px] w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 text-[14px] font-medium text-[#0F172A] outline-none transition placeholder:text-gray-400 focus:border-[#0F172A] focus:bg-white focus:ring-2 focus:ring-[#0F172A]/10";

  if (isSuccess) {
    return (
      <main dir="rtl" className="min-h-screen w-full bg-[#F3F4F6] py-16 flex items-center justify-center">
        <div className="max-w-md w-full mx-auto px-4">
          <div className="bg-white border border-[#E5E9F0] rounded-[24px] p-8 shadow-sm text-center flex flex-col items-center">
            <div className="w-16 h-16 bg-[#ECFDF5] border border-[#A7F3D0] text-[#059669] rounded-full flex items-center justify-center mb-6">
              <Check size={32} />
            </div>
            <h1 className="text-[24px] font-black text-[#0F172A] mb-3">تم إرسال طلبك بنجاح!</h1>
            <p className="text-[14px] text-gray-500 font-bold mb-8 leading-relaxed">
              شكراً لاهتمامك بـ زاد كابيتال. لقد تم تسجيل طلبك لسيارة <strong className="text-[#0F172A]">{activeCar?.name}</strong> بنجاح. سيتواصل معك أحد ممثلي المبيعات لدينا عبر الرقم <span className="text-[#0F172A] font-bold">{phone}</span> لتأكيد التفاصيل وإتمام الإجراءات.
            </p>
            <button
              type="button"
              onClick={() => navigate("/")}
              className="h-[50px] w-full bg-[#0F172A] text-white text-[15px] font-extrabold rounded-xl transition hover:opacity-90 active:scale-95 shadow-sm"
            >
              العودة للرئيسية
            </button>
          </div>
        </div>
      </main>
    );
  }

  return (
    <main dir="rtl" className="min-h-screen w-full bg-[#F3F4F6]">
      {/* Header Banner */}
      <section className="w-full bg-[#0F172A] py-14 text-white text-center relative overflow-hidden">
        <div className="absolute top-0 right-0 w-80 h-80 bg-[#EDC98E]/5 blur-3xl rounded-full pointer-events-none" />
        <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <span className="text-[13px] font-extrabold text-[#EDC98E] uppercase tracking-wider block mb-2">
            زاد كابيتال
          </span>
          <h1 className="text-[30px] font-black text-white leading-tight md:text-[38px]">
            طلب سيارة جديدة
          </h1>
          <p className="text-[14px] text-white/70 font-semibold mt-2">قم بملء النموذج أدناه وسيتكفل خبراؤنا بالباقي</p>
        </div>
      </section>

      <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <form onSubmit={handleSubmit} className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          
          {/* LEFT COLUMN: Car Details & Term Selection (lg:col-span-5) */}
          <div className="lg:col-span-5 flex flex-col gap-6 lg:sticky lg:top-8">
            <div className="bg-white border border-[#E5E9F0] rounded-[24px] p-6 shadow-sm flex flex-col gap-6">
              
              {/* Car Selector Dropdown */}
              <div className="flex flex-col text-start">
                <label className="text-[14px] font-extrabold text-[#374151] mb-2">اختر السيارة</label>
                <div className="relative">
                  <select
                    value={selectedCarId}
                    onChange={(e) => setSelectedCarId(Number(e.target.value))}
                    className={`${inputClasses} appearance-none pe-12`}
                    disabled={loadingCars}
                  >
                    {loadingCars ? (
                      <option>جاري تحميل السيارات...</option>
                    ) : (
                      cars.map((car) => (
                        <option key={car.id} value={car.id}>
                          {car.brand?.name} {car.name} ({car.year})
                        </option>
                      ))
                    )}
                  </select>
                  <ChevronDown
                    size={18}
                    className="pointer-events-none absolute end-4 top-1/2 -translate-y-1/2 text-[#8A8F99]"
                  />
                </div>
              </div>

              {/* Active Car Details Card */}
              {activeCar && (
                <div className="border border-gray-100 rounded-2xl p-4 bg-gray-50 flex flex-col items-center">
                  <div className="h-40 w-full overflow-hidden rounded-xl bg-white mb-4 border border-gray-100 flex items-center justify-center">
                    <img
                      src={getImageUrl(activeCar.main_image) || APP_IMAGES.CAR_PLACEHOLDER}
                      alt={activeCar.name}
                      className="h-full max-w-full object-contain"
                    />
                  </div>
                  
                  <h3 className="text-[18px] font-black text-[#0F172A] text-center mb-1">
                    {activeCar.brand?.name} {activeCar.name}
                  </h3>
                  <p className="text-[12px] text-gray-400 font-bold text-center mb-4">
                    {activeCar.year} . {activeCar.fuel_type || (!Array.isArray(activeCar.specs) && activeCar.specs ? (activeCar.specs as any).fuel : "") || "بنزين"} . {activeCar.transmission || (!Array.isArray(activeCar.specs) && activeCar.specs ? (activeCar.specs as any).gearbox : "") || "أوتوماتيك"}
                  </p>

                  {/* Colors Selector */}
                  <div className="w-full text-start border-t border-gray-200/60 pt-4">
                    <span className="text-[13px] font-extrabold text-[#374151] block mb-3">اللون المطلوب</span>
                    <div className="flex flex-wrap gap-2.5">
                      {carColors.map((color, idx) => (
                        <button
                          key={idx}
                          type="button"
                          onClick={() => setSelectedColor(color.name)}
                          className={`w-9 h-9 rounded-full border-2 transition-all relative ${
                            selectedColor === color.name
                              ? "border-[#EDC98E] scale-110 shadow-sm"
                              : "border-gray-200 hover:border-gray-300"
                          }`}
                          style={{ backgroundColor: color.hex }}
                          title={color.name}
                        >
                          {selectedColor === color.name && (
                            <span
                              className="absolute inset-0 flex items-center justify-center text-[10px]"
                              style={{ color: color.hex === "#FFFFFF" ? "#0F172A" : "#FFFFFF" }}
                            >
                              ✓
                            </span>
                          )}
                        </button>
                      ))}
                    </div>
                  </div>
                </div>
              )}

              {/* Term selection */}
              <div className="text-start">
                <span className="text-[13px] font-extrabold text-[#374151] block mb-3">
                  مدة التمويل: <span className="text-[#EDC98E] font-black">{term} شهر</span>
                </span>
                <div className="grid grid-cols-4 gap-2">
                  {[24, 36, 48, 60].map((month) => (
                    <button
                      key={month}
                      type="button"
                      onClick={() => setTerm(month)}
                      className={`flex h-11 items-center justify-center rounded-xl text-[13px] font-extrabold transition-all ${
                        term === month
                          ? "bg-[#0F172A] text-white scale-105 shadow-md"
                          : "bg-[#F8FAFC] border border-[#E2E8F0] text-gray-500 hover:border-gray-400"
                      }`}
                    >
                      {month}
                    </button>
                  ))}
                </div>
              </div>

              {/* Price and Installment box */}
              {activeCar && (
                <div className="rounded-2xl bg-[#0F172A] p-5 text-white relative overflow-hidden text-start">
                  <div className="absolute top-0 right-0 w-24 h-24 bg-[#EDC98E]/5 blur-2xl rounded-full" />
                  
                  <div className="flex justify-between items-center border-b border-white/5 pb-3 mb-3">
                    <div>
                      <span className="text-[11px] text-white/50 font-bold block">سعر السيارة</span>
                      <strong className="text-[18px] font-black text-white">
                        {formatPrice(activeCar.current_price, "white")}
                      </strong>
                    </div>
                    <div className="text-end">
                      <span className="text-[11px] text-[#EDC98E] font-bold block">القسط التقديري</span>
                      <strong className="text-[20px] font-black text-[#EDC98E]">
                        {formatPrice(calculatedInstallment, "#EDC98E")}
                      </strong>
                    </div>
                  </div>
                  
                  <p className="text-[10px] text-white/40 font-semibold leading-relaxed">
                    * الأرقام تقديرية بناءً على نسبة فائدة تمويلية 4.5%. التمويل النهائي يخضع للتقييم الائتماني من الجهة الممولة.
                  </p>
                </div>
              )}

            </div>
          </div>

          {/* RIGHT COLUMN: Personal & Financial Details Form (lg:col-span-7) */}
          <div className="lg:col-span-7">
            <div className="bg-white border border-[#E5E9F0] rounded-[24px] p-6 md:p-8 shadow-sm flex flex-col gap-6">
              
              <div className="text-start border-b border-gray-100 pb-4">
                <h2 className="text-[22px] font-black text-[#0F172A] flex items-center gap-2">
                  <Sparkles className="text-[#EDC98E]" size={22} />
                  <span>بياناتك الشخصية</span>
                </h2>
                <p className="text-[13px] text-gray-400 font-bold mt-1">يرجى إدخال معلوماتك الشخصية بدقة لتسجيل طلبك</p>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {/* الاسم الكامل */}
                <div className="flex flex-col text-start">
                  <label className="text-[14px] font-extrabold text-[#374151] mb-2">الاسم الكامل <span className="text-red-500">*</span></label>
                  <input
                    type="text"
                    required
                    className={inputClasses}
                    placeholder="محمد عبدالله..."
                    value={fullName}
                    onChange={(e) => setFullName(e.target.value)}
                  />
                </div>

                {/* رقم الجوال */}
                <div className="flex flex-col text-start">
                  <label className="text-[14px] font-extrabold text-[#374151] mb-2">رقم الجوال <span className="text-red-500">*</span></label>
                  <input
                    type="tel"
                    required
                    dir="ltr"
                    className={`${inputClasses} text-start`}
                    placeholder="05xxxxxxxx"
                    value={phone}
                    onChange={(e) => setPhone(e.target.value)}
                  />
                </div>

                {/* المدينة */}
                <div className="flex flex-col text-start">
                  <label className="text-[14px] font-extrabold text-[#374151] mb-2">المدينة <span className="text-red-500">*</span></label>
                  <div className="relative">
                    <select
                      value={city}
                      onChange={(e) => setCity(e.target.value)}
                      className={`${inputClasses} appearance-none pe-12`}
                      required
                    >
                      <option value="">اختر مدينتك</option>
                      {saudiCities.map((cityOpt) => (
                        <option key={cityOpt.value} value={cityOpt.value}>
                          {cityOpt.label}
                        </option>
                      ))}
                    </select>
                    <ChevronDown
                      size={18}
                      className="pointer-events-none absolute end-4 top-1/2 -translate-y-1/2 text-[#8A8F99]"
                    />
                  </div>
                </div>

                {/* جهة العمل */}
                <div className="flex flex-col text-start">
                  <label className="text-[14px] font-extrabold text-[#374151] mb-2">جهة العمل <span className="text-red-500">*</span></label>
                  <div className="relative">
                    <select
                      value={employerType}
                      onChange={(e) => setEmployerType(e.target.value)}
                      className={`${inputClasses} appearance-none pe-12`}
                      required
                    >
                      <option value="">اختر قطاع عملك</option>
                      {employerTypes.map((emp) => (
                        <option key={emp.value} value={emp.value}>
                          {emp.label}
                        </option>
                      ))}
                    </select>
                    <ChevronDown
                      size={18}
                      className="pointer-events-none absolute end-4 top-1/2 -translate-y-1/2 text-[#8A8F99]"
                    />
                  </div>
                </div>

                {/* مدة الخدمة بالوظيفة */}
                <div className="flex flex-col text-start">
                  <label className="text-[14px] font-extrabold text-[#374151] mb-2">مدة الخدمة بالوظيفة <span className="text-red-500">*</span></label>
                  <div className="relative">
                    <select
                      value={yearsOfService}
                      onChange={(e) => setYearsOfService(Number(e.target.value))}
                      className={`${inputClasses} appearance-none pe-12`}
                      required
                    >
                      {serviceDurations.map((duration) => (
                        <option key={duration.value} value={duration.value}>
                          {duration.label}
                        </option>
                      ))}
                    </select>
                    <ChevronDown
                      size={18}
                      className="pointer-events-none absolute end-4 top-1/2 -translate-y-1/2 text-[#8A8F99]"
                    />
                  </div>
                </div>

                {/* صافي الراتب */}
                <div className="flex flex-col text-start">
                  <label className="text-[14px] font-extrabold text-[#374151] mb-2">صافي الراتب <span className="text-red-500">*</span></label>
                  <input
                    type="number"
                    required
                    className={inputClasses}
                    placeholder="مثال: 8500"
                    value={salary}
                    onChange={(e) => setSalary(e.target.value)}
                  />
                </div>

                {/* الالتزامات الشهرية */}
                <div className="flex flex-col text-start md:col-span-2">
                  <label className="text-[14px] font-extrabold text-[#374151] mb-2">الالتزامات الشهرية <span className="text-red-500">*</span></label>
                  <input
                    type="number"
                    required
                    className={inputClasses}
                    placeholder="أدخل إجمالي التزاماتك أو أقساطك الشهرية الأخرى..."
                    value={obligations}
                    onChange={(e) => setObligations(e.target.value)}
                  />
                </div>

              </div>

              {/* Toggles Panel */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                
                {/* قرض شخصي */}
                <div className="flex flex-col text-start">
                  <label className="text-[14px] font-extrabold text-[#374151] mb-2">قرض شخصي <span className="text-red-500">*</span></label>
                  <div className="grid grid-cols-2 gap-3">
                    <button
                      type="button"
                      onClick={() => setHasPersonalLoan(true)}
                      className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                        hasPersonalLoan
                          ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                          : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                      }`}
                    >
                      نعم
                    </button>
                    <button
                      type="button"
                      onClick={() => setHasPersonalLoan(false)}
                      className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                        !hasPersonalLoan
                          ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                          : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                      }`}
                    >
                      لا
                    </button>
                  </div>
                </div>

                {/* قرض عقاري */}
                <div className="flex flex-col text-start">
                  <label className="text-[14px] font-extrabold text-[#374151] mb-2">قرض عقاري <span className="text-red-500">*</span></label>
                  <div className="grid grid-cols-2 gap-3">
                    <button
                      type="button"
                      onClick={() => setHasMortgageLoan(true)}
                      className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                        hasMortgageLoan
                          ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                          : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                      }`}
                    >
                      نعم
                    </button>
                    <button
                      type="button"
                      onClick={() => setHasMortgageLoan(false)}
                      className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                        !hasMortgageLoan
                          ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                          : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                      }`}
                    >
                      لا
                    </button>
                  </div>
                </div>

                {/* تعثر سمة */}
                <div className="flex flex-col text-start">
                  <label className="text-[14px] font-extrabold text-[#374151] mb-2">هل لديك تعثر في سمة؟ <span className="text-red-500">*</span></label>
                  <div className="grid grid-cols-2 gap-3">
                    <button
                      type="button"
                      onClick={() => setHasSimahDefault(true)}
                      className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                        hasSimahDefault
                          ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                          : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                      }`}
                    >
                      نعم
                    </button>
                    <button
                      type="button"
                      onClick={() => setHasSimahDefault(false)}
                      className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                        !hasSimahDefault
                          ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                          : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                      }`}
                    >
                      لا
                    </button>
                  </div>
                </div>

                {/* مخالفات مرورية */}
                <div className="flex flex-col text-start">
                  <label className="text-[14px] font-extrabold text-[#374151] mb-2">هل لديك مخالفات مرورية؟ <span className="text-red-500">*</span></label>
                  <div className="grid grid-cols-2 gap-3">
                    <button
                      type="button"
                      onClick={() => setHasTrafficViolations(true)}
                      className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                        hasTrafficViolations
                          ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                          : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                      }`}
                    >
                      نعم
                    </button>
                    <button
                      type="button"
                      onClick={() => setHasTrafficViolations(false)}
                      className={`h-11 rounded-xl text-[14px] font-extrabold transition-all border ${
                        !hasTrafficViolations
                          ? "bg-[#0F172A] text-white border-[#0F172A] shadow-sm"
                          : "bg-white text-gray-700 border-[#E2E8F0] hover:bg-gray-50"
                      }`}
                    >
                      لا
                    </button>
                  </div>
                </div>

              </div>

              {/* Submit Button */}
              <button
                type="submit"
                disabled={isSubmitting}
                className="mt-6 flex h-[54px] w-full items-center justify-center gap-2 rounded-xl bg-[#0F172A] text-[16px] font-extrabold text-white transition hover:opacity-95 disabled:opacity-50 hover:scale-[1.01] active:scale-95 shadow-sm cursor-pointer"
              >
                <Send size={18} />
                <span>{isSubmitting ? "جاري إرسال طلبك..." : "إرسال طلب السيارة"}</span>
              </button>

            </div>
          </div>

        </form>
      </div>
    </main>
  );
}
