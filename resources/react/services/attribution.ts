/**
 * Unified Marketing Attribution & UTM Tracker
 * Automatically captures, stores, and attaches UTM parameters, Click IDs, and Referrers to lead submissions.
 */

export interface IAttributionData {
  utm_source?: string;
  utm_medium?: string;
  utm_campaign?: string;
  utm_content?: string;
  utm_term?: string;
  referrer?: string;
  click_id?: string;
  marketing_channel?: string;
}

const ATTRIBUTION_STORAGE_KEY = 'zad_attribution_data';

/**
 * Capture and store attribution parameters from current URL or referrer.
 */
export function captureAttribution(): IAttributionData {
  if (typeof window === 'undefined') return {};

  try {
    const urlParams = new URLSearchParams(window.location.search);
    const referrer = document.referrer || '';

    // Direct UTMs
    const utmSource = urlParams.get('utm_source') || undefined;
    const utmMedium = urlParams.get('utm_medium') || undefined;
    const utmCampaign = urlParams.get('utm_campaign') || undefined;
    const utmContent = urlParams.get('utm_content') || undefined;
    const utmTerm = urlParams.get('utm_term') || undefined;

    // Platform Click IDs
    const fbclid = urlParams.get('fbclid') || getCookie('_fbc') || undefined;
    const ttclid = urlParams.get('ttclid') || undefined;
    const scClickId = urlParams.get('sc_clickid') || urlParams.get('sccid') || undefined;
    const gclid = urlParams.get('gclid') || urlParams.get('wbraid') || urlParams.get('gbraid') || undefined;
    const clickId = fbclid || ttclid || scClickId || gclid;

    // Check if new tracking data exists on this visit
    const hasIncomingTracking = Boolean(utmSource || utmCampaign || clickId || (referrer && !referrer.includes(window.location.hostname)));

    if (hasIncomingTracking) {
      let channel = 'مباشر (Direct Traffic)';

      if (
        utmSource?.toLowerCase().includes('meta') ||
        utmSource?.toLowerCase().includes('instagram') ||
        utmSource?.toLowerCase().includes('facebook') ||
        fbclid ||
        referrer.includes('instagram.com') ||
        referrer.includes('facebook.com')
      ) {
        channel = 'Meta (Instagram / Facebook)';
      } else if (
        utmSource?.toLowerCase().includes('snap') ||
        scClickId ||
        referrer.includes('snapchat.com')
      ) {
        channel = 'Snapchat';
      } else if (
        utmSource?.toLowerCase().includes('tiktok') ||
        ttclid ||
        referrer.includes('tiktok.com')
      ) {
        channel = 'TikTok';
      } else if (
        utmSource?.toLowerCase().includes('google') ||
        gclid ||
        referrer.includes('google.com')
      ) {
        channel = utmMedium?.toLowerCase().includes('cpc') || gclid ? 'Google Ads' : 'Google Search (Organic)';
      } else if (utmSource?.toLowerCase().includes('twitter') || referrer.includes('t.co') || referrer.includes('x.com')) {
        channel = 'Twitter / X';
      } else if (referrer && !referrer.includes(window.location.hostname)) {
        try {
          const host = new URL(referrer).hostname;
          channel = `إحالة: ${host}`;
        } catch {
          channel = 'موقع خارجي (Referral)';
        }
      } else if (utmSource) {
        channel = utmSource.toUpperCase();
      }

      const attribution: IAttributionData = {
        utm_source: utmSource,
        utm_medium: utmMedium,
        utm_campaign: utmCampaign,
        utm_content: utmContent,
        utm_term: utmTerm,
        referrer: referrer || undefined,
        click_id: clickId,
        marketing_channel: channel,
      };

      sessionStorage.setItem(ATTRIBUTION_STORAGE_KEY, JSON.stringify(attribution));
      localStorage.setItem(ATTRIBUTION_STORAGE_KEY, JSON.stringify(attribution));

      return attribution;
    }

    // Fallback: Read existing stored attribution
    const stored = sessionStorage.getItem(ATTRIBUTION_STORAGE_KEY) || localStorage.getItem(ATTRIBUTION_STORAGE_KEY);
    if (stored) {
      return JSON.parse(stored);
    }
  } catch (err) {
    console.debug('[Attribution] Error capturing attribution:', err);
  }

  return {
    marketing_channel: 'مباشر (Direct Traffic)',
  };
}

/**
 * Get current attribution payload for API requests.
 */
export function getAttributionPayload(): Record<string, string | undefined> {
  const data = captureAttribution();
  return {
    utm_source: data.utm_source,
    utm_medium: data.utm_medium,
    utm_campaign: data.utm_campaign,
    utm_content: data.utm_content,
    utm_term: data.utm_term,
    referrer: data.referrer,
    click_id: data.click_id,
    marketing_channel: data.marketing_channel,
  };
}

function getCookie(name: string): string | null {
  if (typeof document === 'undefined') return null;
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  return match ? decodeURIComponent(match[2]) : null;
}
