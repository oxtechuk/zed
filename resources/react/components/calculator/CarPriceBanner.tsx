import { useTranslation } from "react-i18next";
import { formatPrice } from "../../utils/format";
import type { ICarPriceBannerProps } from "../../interfaces/ICarPriceBannerProps";

export default function CarPriceBanner({ price }: ICarPriceBannerProps) {
    const { t } = useTranslation();

    return (
        <div className="mb-8 rounded-2xl bg-[#16254F] p-5 text-white flex items-center justify-between text-start relative overflow-hidden">
            <div className="absolute top-0 end-0 w-32 h-32 bg-[#EDC98E]/5 blur-xl rounded-full" />
            <div>
                <span className="text-[12px]  font-bold block mb-1">
                    {t("financeCalculator.step2Car.carPrice", "سعر السيارة")}
                </span>
                <strong className="text-[24px] text-[#EDC98E] font-black leading-none text-white tracking-tight">
                    {formatPrice(price, "#EDC98E")}
                </strong>
            </div>
        </div>
    );
}
