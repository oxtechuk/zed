import BranchMapCard from "./BranchMapCard";
import WorkingHoursCard from "./WorkingHoursCard";
import type { IContactSidebarProps } from "../../interfaces/IContactSidebarProps";

export default function ContactSidebar({ branches }: IContactSidebarProps) {
  return (
    <div className="lg:col-span-4 flex flex-col gap-6 text-start">
      {branches.map((branch, index) => (
        <div key={index} className="flex flex-col gap-6 w-full">
          <BranchMapCard name={branch.name} address={branch.address} mapLink={branch.mapLink} />
          {branch.workingHours.length > 0 && (
            <WorkingHoursCard workingHours={branch.workingHours} />
          )}
        </div>
      ))}
    </div>
  );
}
