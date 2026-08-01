import { useMemo, useState } from "react";
import { useTranslation } from "react-i18next";
import {
  StepOneForm,
  StepTwoCarSelector,
  StepTwoCalculator,
  StepFourSuccess,
} from "../components/calculator";
import { getImageUrl } from "../constants/app-images";
import { APP_IMAGES } from "../constants/app-images";
import type { CarItem } from "../types/home.types";
import type { ISelectedCar } from "../interfaces/ISelectedCar";
import { useSEO } from "../utils/useSEO";
import type { IPersonalInfo } from "../interfaces/IPersonalInfo";

type Step = 1 | 2 | 3 | 4;

export default function FinanceCalculatorPage() {
  const { i18n, t } = useTranslation();
  useSEO(t("pageTitles.financeCalculator"), t("financeCalculator.description"));

  const [step, setStep] = useState<Step>(1);
  const [selectedCarData, setSelectedCarData] = useState<CarItem | null>(null);
  const [selectedCarId, setSelectedCarId] = useState<number>(0);
  const [selectedColor, setSelectedColor] = useState<string>("أبيض");
  const [salary, setSalary] = useState<number>(15000);
  const [downPayment, setDownPayment] = useState<number>(0);
  const [term, setTerm] = useState<number>(60);
  const [personalInfo, setPersonalInfo] = useState<IPersonalInfo | null>(null);

  const selectedCar: ISelectedCar = useMemo(() => {
    if (!selectedCarData) {
      return { id: 0, brand: "", name: "", model: "", price: 0, tag: "", image: "" };
    }
    return {
      id: selectedCarData.id,
      brand: selectedCarData.brand?.name ?? "",
      name: selectedCarData.name,
      model: String(selectedCarData.year ?? ""),
      price: selectedCarData.current_price,
      tag: selectedCarData.is_featured ? "مميز" : "",
      image: getImageUrl(selectedCarData.main_image) || APP_IMAGES.CAR_PLACEHOLDER,
    };
  }, [selectedCarData]);

  const handleStep1Next = (info: IPersonalInfo) => {
    setPersonalInfo(info);
    setStep(2);
  };

  const handleReset = () => {
    setStep(1);
    setSelectedCarId(0);
    setSelectedCarData(null);
    setPersonalInfo(null);
    setSelectedColor("أبيض");
    setSalary(15000);
    setDownPayment(0);
    setTerm(60);
  };

  return (
    <main dir={i18n.dir()} className="min-h-screen w-full bg-[#F3F4F6]">
      {/* Top Banner (Only show for active steps 1 to 3) */}
      {step < 4 && (
        <section className="w-full bg-[#0F172A] py-14 text-white text-center relative overflow-hidden">
          <div className="absolute top-0 right-0 w-80 h-80 bg-[#EDC98E]/5 blur-3xl rounded-full pointer-events-none" />
          <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span className="text-[13px] font-extrabold text-[#EDC98E] uppercase tracking-wider block mb-2">
              حاسبة التمويل
            </span>
            <h1 className="text-[30px] font-black text-white leading-tight md:text-[38px]">
              احسب قسطك الشهري
            </h1>
          </div>
        </section>
      )}

      {/* Wizard Steps */}
      <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        {step === 1 && (
          <StepOneForm onNext={handleStep1Next} />
        )}

        {step === 2 && (
          <StepTwoCarSelector
            selectedCarId={selectedCarId}
            selectedCar={selectedCar}
            onCarSelect={(car) => {
              setSelectedCarId(car.id);
              setSelectedCarData(car);
              // Set default down payment to 30% of the selected car's price
              setDownPayment(Math.round(car.current_price * 0.3));
            }}
            selectedColor={selectedColor}
            setSelectedColor={setSelectedColor}
            onNext={() => setStep(3)}
            onBack={() => setStep(1)}
          />
        )}

        {step === 3 && personalInfo && (
          <StepTwoCalculator
            selectedCar={selectedCar}
            selectedColor={selectedColor}
            salary={salary}
            setSalary={setSalary}
            downPayment={downPayment}
            setDownPayment={setDownPayment}
            term={term}
            setTerm={setTerm}
            personalInfo={personalInfo}
            carId={selectedCarId}
            onBack={() => setStep(2)}
            onSubmitSuccess={() => setStep(4)}
          />
        )}

        {step === 4 && (
          <StepFourSuccess
            carName={selectedCar.name}
            phone={personalInfo?.phone || ""}
            onReset={handleReset}
          />
        )}
      </div>
    </main>
  );
}
