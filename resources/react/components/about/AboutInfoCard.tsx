import type { IInfoCard } from "../../interfaces/IInfoCard";

export default function AboutInfoCard({ title, description }: IInfoCard) {
    return (
        <article className="flex flex-col items-start text-start">
            {/* Small category label */}
            <div className="flex flex-col items-start">
                <span className="text-[14px] font-medium text-[#8B8F98]">
                    {title}
                </span>

                <span className="mt-2 h-px w-[28px] bg-[var(--brand-secondary-color)]" />
            </div>

            {/* Description */}
            <p
                className={[
                    "mt-5 max-w-[330px]",
                    "text-[16px] font-medium leading-[1.9]",
                    "text-[var(--brand-primary-color)]",
                    "sm:text-[17px]",
                ].join(" ")}
            >
                {description}
            </p>
        </article>
    );
}
