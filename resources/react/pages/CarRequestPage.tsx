import { useEffect } from "react";
import { useCarRequest } from "../hooks/useCarRequest";
import { CarRequestHeader } from "../components/car-request/CarRequestHeader";
import { CarRequestSuccess } from "../components/car-request/CarRequestSuccess";
import { CarRequestForm } from "../components/car-request/CarRequestForm";
import { CarRequestSummaryCard } from "../components/car-request/CarRequestSummaryCard";
import CarRequestPageSkeleton from "../components/skeletons/CarRequestPageSkeleton";

export default function CarRequestPage() {
    const {
        direction,
        cars,
        loadingCars,
        selectedCarId,
        setSelectedCarId,
        activeCar,
        selectedColor,
        setSelectedColor,
        term,
        setTerm,
        formData,
        handleFormChange,
        isSubmitting,
        isSuccess,
        calculatedInstallment,
        carColors,
        saudiCities,
        employerTypes,
        serviceDurations,
        handleSubmit,
        whatsappHref,
        onBackToCars,
    } = useCarRequest();

    useEffect(() => {
        if (isSuccess) {
            window.scrollTo({ top: 0, behavior: "auto" });
        }
    }, [isSuccess]);

    if (isSuccess) {
        return (
            <CarRequestSuccess
                activeCar={activeCar}
                phone={formData.phone}
                whatsappHref={whatsappHref}
                onBackToCars={onBackToCars}
                direction={direction}
            />
        );
    }

    return (
        <main dir={direction} className="min-h-screen w-full bg-[#F8FAFC]">
            {/* Header Banner */}
            <CarRequestHeader />

            <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <form
                    onSubmit={handleSubmit}
                    className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start"
                >
                    {/* Personal & Financial Details Form */}
                    <CarRequestForm
                        formData={formData}
                        onChange={handleFormChange}
                        onSubmit={handleSubmit}
                        isSubmitting={isSubmitting}
                        saudiCities={saudiCities}
                        employerTypes={employerTypes}
                        serviceDurations={serviceDurations}
                    />

                    {/* Car Details & Term Selection Summary */}
                    <CarRequestSummaryCard
                        cars={cars}
                        activeCar={activeCar}
                        loadingCars={loadingCars}
                        selectedCarId={selectedCarId}
                        onSelectCarId={setSelectedCarId}
                        selectedColor={selectedColor}
                        onSelectColor={setSelectedColor}
                        term={term}
                        onChangeTerm={setTerm}
                        calculatedInstallment={calculatedInstallment}
                        carColors={carColors}
                    />
                </form>
            </div>
        </main>
    );
}
