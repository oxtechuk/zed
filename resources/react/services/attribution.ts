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
const ATTRIBUTION_COOKIE_PREFIX = 'zad_attr_';

function setCookie(name: string, value: string, days = 30): void {
  if (typeof document === 'undefined') return;
  try {
    const d = new Date();
    d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = `${name}=${encodeURIComponent(value)};expires=${d.toUTCString()};path=/;SameSite=Lax`;
  } catch {}
}

function getCookie(name: string): string | null {
  if (typeof document === 'undefined') return null;
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  return match ? decodeURIComponent(match[2]) : null;
}

/**
 * Capture and store attribution parameters from current URL or referrer.
 */
export function captureAttribution(): IAttributionData {
  if (typeof window === 'undefined') return {};

  try {
    const urlParams = new URLSearchParams(window.location.search);
    const referrer = document.referrer || '';

    const getParam = (...keys: string[]): string | undefined => {
      for (const k of keys) {
        const val = urlParams.get(k);
        if (val && val.trim()) return val.trim();
      }
      return undefined;
    };

    // Direct UTMs and common campaign parameter aliases
    let utmSource = getParam('utm_source', 'source', 'src', 'utm-source', 'utm_src');
    let utmMedium = getParam('utm_medium', 'medium', 'utm-medium', 'utm_med');
    let utmCampaign = getParam('utm_campaign', 'campaign', 'camp', 'utm-campaign', 'utm_camp', 'campaign_name');
    const utmContent = getParam('utm_content', 'content', 'utm-content', 'ad_id', 'adname', 'ad_name');
    const utmTerm = getParam('utm_term', 'term', 'keyword', 'kw', 'utm-term');

    // Platform Click IDs
    const fbclid = getParam('fbclid') || getCookie('_fbc') || undefined;
    const ttclid = getParam('ttclid') || getCookie('_ttclid') || undefined;
    const scClickId = getParam('sc_clickid', 'sccid') || getCookie('_scid') || undefined;
    const gclid = getParam('gclid', 'wbraid', 'gbraid') || getCookie('_gcl_aw') || undefined;
    const twclid = getParam('twclid') || undefined;
    const msclkid = getParam('msclkid') || undefined;
    const clickId = fbclid || ttclid || scClickId || gclid || twclid || msclkid;

    // Fallback campaign from ID if name not provided
    if (!utmCampaign) {
      utmCampaign = getParam('campaign_id', 'campaignid', 'adset_id', 'adsetid');
    }

    // Auto-derive utm_source / utm_medium from click IDs or referrer if not explicitly set
    if (!utmSource) {
      if (fbclid || referrer.includes('instagram.com') || referrer.includes('facebook.com') || referrer.includes('fb.me')) {
        utmSource = 'meta';
        utmMedium = utmMedium || (fbclid ? 'cpc' : 'social');
      } else if (scClickId || referrer.includes('snapchat.com')) {
        utmSource = 'snapchat';
        utmMedium = utmMedium || (scClickId ? 'cpc' : 'social');
      } else if (ttclid || referrer.includes('tiktok.com') || referrer.includes('byteoversea')) {
        utmSource = 'tiktok';
        utmMedium = utmMedium || (ttclid ? 'cpc' : 'social');
      } else if (gclid || referrer.includes('google.com') || referrer.includes('google.com.sa')) {
        utmSource = 'google';
        utmMedium = utmMedium || (gclid ? 'cpc' : 'organic');
      } else if (twclid || referrer.includes('twitter.com') || referrer.includes('x.com') || referrer.includes('t.co')) {
        utmSource = 'twitter';
        utmMedium = utmMedium || (twclid ? 'cpc' : 'social');
      } else if (msclkid || referrer.includes('bing.com')) {
        utmSource = 'bing';
        utmMedium = utmMedium || (msclkid ? 'cpc' : 'organic');
      } else if (referrer && !referrer.includes(window.location.hostname)) {
        try {
          utmSource = new URL(referrer).hostname;
          utmMedium = utmMedium || 'referral';
        } catch {}
      }
    }

    // Check if incoming tracking data exists on this visit
    const hasIncomingTracking = Boolean(utmSource || utmCampaign || clickId || (referrer && !referrer.includes(window.location.hostname)));

    if (hasIncomingTracking) {
      let channel = 'مباشر (Direct Traffic)';
      const srcLower = utmSource?.toLowerCase() || '';
      const medLower = utmMedium?.toLowerCase() || '';

      if (
        srcLower.includes('meta') ||
        srcLower.includes('instagram') ||
        srcLower.includes('facebook') ||
        srcLower.includes('fb') ||
        srcLower.includes('ig') ||
        fbclid ||
        referrer.includes('instagram.com') ||
        referrer.includes('facebook.com')
      ) {
        channel = 'Meta (Instagram / Facebook)';
      } else if (
        srcLower.includes('snap') ||
        scClickId ||
        referrer.includes('snapchat.com')
      ) {
        channel = 'Snapchat';
      } else if (
        srcLower.includes('tiktok') ||
        srcLower.includes('tt') ||
        ttclid ||
        referrer.includes('tiktok.com')
      ) {
        channel = 'TikTok';
      } else if (
        srcLower.includes('google') ||
        gclid ||
        referrer.includes('google.com')
      ) {
        channel = medLower.includes('cpc') || gclid ? 'Google Ads' : 'Google Search (Organic)';
      } else if (srcLower.includes('twitter') || srcLower.includes('x') || referrer.includes('t.co') || referrer.includes('x.com')) {
        channel = 'Twitter / X';
      } else if (srcLower.includes('youtube') || srcLower.includes('yt') || referrer.includes('youtube.com')) {
        channel = 'YouTube';
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

      // Persist across sessions (sessionStorage + localStorage + cookies)
      const serialized = JSON.stringify(attribution);
      sessionStorage.setItem(ATTRIBUTION_STORAGE_KEY, serialized);
      localStorage.setItem(ATTRIBUTION_STORAGE_KEY, serialized);
      if (utmSource) setCookie(`${ATTRIBUTION_COOKIE_PREFIX}source`, utmSource);
      if (utmCampaign) setCookie(`${ATTRIBUTION_COOKIE_PREFIX}campaign`, utmCampaign);
      if (channel) setCookie(`${ATTRIBUTION_COOKIE_PREFIX}channel`, channel);

      return attribution;
    }

    // Fallback: Read existing stored attribution from storage or cookies
    const stored = sessionStorage.getItem(ATTRIBUTION_STORAGE_KEY) || localStorage.getItem(ATTRIBUTION_STORAGE_KEY);
    if (stored) {
      return JSON.parse(stored);
    }

    const cookieSource = getCookie(`${ATTRIBUTION_COOKIE_PREFIX}source`);
    const cookieCampaign = getCookie(`${ATTRIBUTION_COOKIE_PREFIX}campaign`);
    const cookieChannel = getCookie(`${ATTRIBUTION_COOKIE_PREFIX}channel`);

    if (cookieSource || cookieCampaign || cookieChannel) {
      return {
        utm_source: cookieSource || undefined,
        utm_campaign: cookieCampaign || undefined,
        marketing_channel: cookieChannel || 'مباشر (Direct Traffic)',
      };
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
