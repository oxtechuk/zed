import type { ILocationButtonProps } from "../../interfaces/ILocationButtonProps";

export default function LocationButton({
  location,
  isActive,
  onSelect,
}: ILocationButtonProps) {
  return (
    <button
      type="button"
      onClick={() => onSelect(location.id)}
      className={`flex h-[64px] items-center justify-between rounded-[14px] border px-6 text-end transition ${
        isActive
          ? "border-[#164EB8] bg-[#164EB8] text-white"
          : "border-[#164EB8] bg-white text-[#07111F] hover:bg-[#EAF2FF]"
      }`}
    >
      <div>
        <p className="text-[18px] font-extrabold">{location.city}</p>
      </div>

      <div className="flex items-center gap-3">
        <span
          className={`text-[15px] ${
            isActive ? "text-white/90" : "text-[#6B7280]"
          }`}
        >
          {location.branchType}
        </span>

        {location.label && (
          <span
            className={`rounded-full px-3 py-1 text-[13px] ${
              isActive
                ? "bg-white/15 text-white"
                : "bg-[#E7F0FF] text-[#164EB8]"
            }`}
          >
            {location.label}
          </span>
        )}
      </div>
    </button>
  );
}
