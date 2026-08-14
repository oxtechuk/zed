export interface IBankDropdownSelectorProps {
    banks: { id: number; name: string }[];
    selectedBankId: number | null;
    onSelectBankId: (id: number | null) => void;
    loadingBanks: boolean;
}
