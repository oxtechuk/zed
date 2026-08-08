import Skeleton from "../Skeleton";

interface ISectionHeaderSkeletonProps {
    withArrows?: boolean;
    titleClassName?: string;
    className?: string;
}

export default function SectionHeaderSkeleton({
    withArrows = true,
    titleClassName = "h-9 w-48 md:w-72",
    className = "",
}: ISectionHeaderSkeletonProps) {
    return (
        <div className={`mb-10 flex flex-col gap-5 md:flex-row md:items-start md:justify-between ${className}`}>
            <Skeleton className={titleClassName} />
            {withArrows && (
                <div dir="ltr" className="flex items-center gap-6">
                    <Skeleton className="h-[38px] w-[38px] rounded-[12px]" />
                    <Skeleton className="h-[38px] w-[38px] rounded-[12px]" />
                </div>
            )}
        </div>
    );
}
