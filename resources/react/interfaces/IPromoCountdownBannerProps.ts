export interface IPromoCountdownBannerData {
    is_active?: boolean;
    title?: string;
    subtitle?: string;
    description?: string;
    badge?: string;
    extra_tag?: string;
    disclaimer?: string;
    countdown_end?: string;
    button?: { text?: string; url?: string };
    button_text?: string;
    button_url?: string;
    image?: string;
    background_image?: string;
}

export interface IPromoCountdownBannerProps {
    banner?: IPromoCountdownBannerData | null;
}
