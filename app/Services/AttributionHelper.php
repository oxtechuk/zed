<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

final class AttributionHelper
{
    /**
     * Resolve unified Marketing Channel name.
     * Platform targets: Meta (Instagram & Facebook), Snapchat, TikTok, Google Ads/Search, Twitter/X, YouTube, Direct, Referral.
     */
    public static function resolveChannel(
        ?string $utmSource = null,
        ?string $utmMedium = null,
        ?string $referrer = null,
        ?string $clickId = null,
        ?string $source = null,
    ): string {
        $utmSourceLower = strtolower(trim((string) $utmSource));
        $utmMediumLower = strtolower(trim((string) $utmMedium));
        $referrerLower = strtolower(trim((string) $referrer));
        $clickIdLower = strtolower(trim((string) $clickId));
        $sourceLower = strtolower(trim((string) $source));

        // 1. Meta / Instagram / Facebook
        if (
            str_contains($utmSourceLower, 'meta') ||
            str_contains($utmSourceLower, 'instagram') ||
            str_contains($utmSourceLower, 'facebook') ||
            str_contains($utmSourceLower, 'fb') ||
            str_contains($utmSourceLower, 'ig') ||
            str_contains($referrerLower, 'instagram.com') ||
            str_contains($referrerLower, 'facebook.com') ||
            str_contains($referrerLower, 'fb.me') ||
            str_contains($clickIdLower, 'fbclid') ||
            str_starts_with($clickIdLower, 'fb.')
        ) {
            return 'Meta (Instagram / Facebook)';
        }

        // 2. Snapchat
        if (
            str_contains($utmSourceLower, 'snapchat') ||
            str_contains($utmSourceLower, 'snap') ||
            str_contains($referrerLower, 'snapchat.com') ||
            str_contains($clickIdLower, 'sc_clickid') ||
            str_contains($clickIdLower, 'sccid') ||
            str_contains($clickIdLower, 'snap')
        ) {
            return 'Snapchat';
        }

        // 3. TikTok
        if (
            str_contains($utmSourceLower, 'tiktok') ||
            str_contains($utmSourceLower, 'tt') ||
            str_contains($referrerLower, 'tiktok.com') ||
            str_contains($referrerLower, 'byteoversea') ||
            str_contains($clickIdLower, 'ttclid')
        ) {
            return 'TikTok';
        }

        // 4. Google (Ads & Search / Organic)
        if (
            str_contains($utmSourceLower, 'google') ||
            str_contains($utmSourceLower, 'adwords') ||
            str_contains($utmSourceLower, 'gads') ||
            str_contains($referrerLower, 'google.com') ||
            str_contains($referrerLower, 'google.com.sa') ||
            str_contains($clickIdLower, 'gclid') ||
            str_contains($clickIdLower, 'wbraid') ||
            str_contains($clickIdLower, 'gbraid')
        ) {
            if (str_contains($utmMediumLower, 'cpc') || str_contains($utmMediumLower, 'paid') || ! empty($clickIdLower)) {
                return 'Google Ads';
            }

            return 'Google Search (Organic)';
        }

        // 5. Twitter / X
        if (
            str_contains($utmSourceLower, 'twitter') ||
            str_contains($utmSourceLower, 'x') ||
            str_contains($referrerLower, 't.co') ||
            str_contains($referrerLower, 'twitter.com') ||
            str_contains($referrerLower, 'x.com')
        ) {
            return 'Twitter / X';
        }

        // 6. YouTube
        if (
            str_contains($utmSourceLower, 'youtube') ||
            str_contains($utmSourceLower, 'yt') ||
            str_contains($referrerLower, 'youtube.com') ||
            str_contains($referrerLower, 'youtu.be')
        ) {
            return 'YouTube';
        }

        // 7. Manual CRM creation
        if (str_contains($sourceLower, 'crm') || str_contains($sourceLower, 'يدوي') || str_contains($sourceLower, 'manual')) {
            return 'CRM الداخلي';
        }

        // 8. Referral from other websites
        if (! empty($referrerLower) && ! str_contains($referrerLower, 'localhost') && ! str_contains($referrerLower, 'zad-capital.sa')) {
            $parsedHost = parse_url($referrerLower, PHP_URL_HOST);

            return ! empty($parsedHost) ? ('إحالة: '.$parsedHost) : 'موقع خارجي (Referral)';
        }

        // 9. If custom utm_source provided
        if (! empty($utmSource)) {
            return ucfirst($utmSource);
        }

        // 10. Default direct traffic
        return 'مباشر (Direct Traffic)';
    }

    /**
     * Map channel to its brand icon, Arabic label, and color styling.
     *
     * @return array{icon: string, label_ar: string, bg: string, color: string, border: string, badge_class: string}
     */
    public static function getChannelMeta(string $channel): array
    {
        if (str_contains($channel, 'Meta') || str_contains($channel, 'Instagram') || str_contains($channel, 'Facebook')) {
            return [
                'icon' => 'bi-instagram',
                'label_ar' => 'ميتا (Meta)',
                'bg' => '#FDF2F8',
                'color' => '#DB2777',
                'border' => '#FBCFE8',
                'badge_class' => 'bg-pink-100 text-pink-700',
            ];
        }

        if (str_contains($channel, 'Snapchat')) {
            return [
                'icon' => 'bi-snapchat',
                'label_ar' => 'سناب شات',
                'bg' => '#FEFCE8',
                'color' => '#CA8A04',
                'border' => '#FEF08A',
                'badge_class' => 'bg-yellow-100 text-yellow-800',
            ];
        }

        if (str_contains($channel, 'TikTok')) {
            return [
                'icon' => 'bi-tiktok',
                'label_ar' => 'تيك توك',
                'bg' => '#F8FAFC',
                'color' => '#0F172A',
                'border' => '#CBD5E1',
                'badge_class' => 'bg-slate-100 text-slate-900',
            ];
        }

        if (str_contains($channel, 'Google Ads')) {
            return [
                'icon' => 'bi-google',
                'label_ar' => 'إعلانات جوجل',
                'bg' => '#EFF6FF',
                'color' => '#2563EB',
                'border' => '#BFDBFE',
                'badge_class' => 'bg-blue-100 text-blue-700',
            ];
        }

        if (str_contains($channel, 'Google')) {
            return [
                'icon' => 'bi-search',
                'label_ar' => 'بحث جوجل',
                'bg' => '#F0FDF4',
                'color' => '#16A34A',
                'border' => '#BBF7D0',
                'badge_class' => 'bg-green-100 text-green-700',
            ];
        }

        if (str_contains($channel, 'Twitter') || str_contains($channel, 'X')) {
            return [
                'icon' => 'bi-twitter-x',
                'label_ar' => 'منصة X',
                'bg' => '#F8FAFC',
                'color' => '#000000',
                'border' => '#E2E8F0',
                'badge_class' => 'bg-neutral-100 text-neutral-900',
            ];
        }

        if (str_contains($channel, 'YouTube')) {
            return [
                'icon' => 'bi-youtube',
                'label_ar' => 'يوتيوب',
                'bg' => '#FEF2F2',
                'color' => '#DC2626',
                'border' => '#FECACA',
                'badge_class' => 'bg-red-100 text-red-700',
            ];
        }

        if (str_contains($channel, 'CRM')) {
            return [
                'icon' => 'bi-shield-check',
                'label_ar' => 'CRM الداخلي',
                'bg' => '#F3E8FF',
                'color' => '#7E22CE',
                'border' => '#E9D5FF',
                'badge_class' => 'bg-purple-100 text-purple-700',
            ];
        }

        if (str_contains($channel, 'Referral') || str_contains($channel, 'إحالة')) {
            return [
                'icon' => 'bi-box-arrow-up-right',
                'label_ar' => 'إحالة خارجية',
                'bg' => '#F1F5F9',
                'color' => '#475569',
                'border' => '#E2E8F0',
                'badge_class' => 'bg-gray-100 text-gray-700',
            ];
        }

        return [
            'icon' => 'bi-globe2',
            'label_ar' => 'مباشر',
            'bg' => '#F1F5F9',
            'color' => '#475569',
            'border' => '#E2E8F0',
            'badge_class' => 'bg-gray-100 text-gray-700',
        ];
    }

    /**
     * Apply ad platform filter on an Eloquent query for bookings/leads.
     */
    public static function applyPlatformFilter(Builder $query, string $platform): Builder
    {
        return match ($platform) {
            'meta' => $query->where(function ($q) {
                $q->where('marketing_channel', 'like', '%Meta%')
                    ->orWhere('marketing_channel', 'like', '%Instagram%')
                    ->orWhere('marketing_channel', 'like', '%Facebook%')
                    ->orWhere('utm_source', 'like', '%meta%')
                    ->orWhere('utm_source', 'like', '%instagram%')
                    ->orWhere('utm_source', 'like', '%facebook%')
                    ->orWhere('utm_source', 'like', '%fb%')
                    ->orWhere('utm_source', 'like', '%ig%')
                    ->orWhere('referrer', 'like', '%instagram.com%')
                    ->orWhere('referrer', 'like', '%facebook.com%')
                    ->orWhere('referrer', 'like', '%fb.me%')
                    ->orWhere('click_id', 'like', '%fbclid%');
            }),
            'snapchat' => $query->where(function ($q) {
                $q->where('marketing_channel', 'like', '%Snapchat%')
                    ->orWhere('utm_source', 'like', '%snap%')
                    ->orWhere('referrer', 'like', '%snapchat.com%')
                    ->orWhere('click_id', 'like', '%sccid%')
                    ->orWhere('click_id', 'like', '%sc_clickid%');
            }),
            'google_ads' => $query->where(function ($q) {
                $q->where('marketing_channel', 'like', '%Google Ads%')
                    ->orWhere('click_id', 'like', '%gclid%')
                    ->orWhere('click_id', 'like', '%wbraid%')
                    ->orWhere('click_id', 'like', '%gbraid%')
                    ->orWhere(function ($sub) {
                        $sub->where('utm_source', 'like', '%google%')
                            ->where(function ($m) {
                                $m->where('utm_medium', 'like', '%cpc%')
                                    ->orWhere('utm_medium', 'like', '%paid%');
                            });
                    });
            }),
            'google' => $query->where(function ($q) {
                $q->where('marketing_channel', 'like', '%Google%')
                    ->orWhere('utm_source', 'like', '%google%')
                    ->orWhere('referrer', 'like', '%google.com%');
            }),
            'tiktok' => $query->where(function ($q) {
                $q->where('marketing_channel', 'like', '%TikTok%')
                    ->orWhere('utm_source', 'like', '%tiktok%')
                    ->orWhere('utm_source', 'like', '%tt%')
                    ->orWhere('referrer', 'like', '%tiktok.com%')
                    ->orWhere('click_id', 'like', '%ttclid%');
            }),
            'direct' => $query->where(function ($q) {
                $q->where('marketing_channel', 'like', '%Direct%')
                    ->orWhere(function ($sub) {
                        $sub->whereNull('utm_source')
                            ->whereNull('click_id')
                            ->where(function ($r) {
                                $r->whereNull('referrer')
                                    ->orWhere('referrer', 'like', '%zad-capital.sa%');
                            });
                    });
            }),
            'crm' => $query->where(function ($q) {
                $q->where('marketing_channel', 'like', '%CRM%')
                    ->orWhere('source', 'like', '%CRM%')
                    ->orWhere('source', 'like', '%يدوي%')
                    ->orWhere('source', 'like', '%manual%');
            }),
            default => $query,
        };
    }
}
