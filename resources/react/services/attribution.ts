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

function clearCookie(name: string): void {
  if (typeof document === 'undefined') return;
  try {
    document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;SameSite=Lax`;
  } catch {}
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

    // Real URL Platform Click IDs (Query parameters only, NEVER generic pixel cookies)
    const gclid = getParam('gclid', 'wbraid', 'gbraid');
    const fbclid = getParam('fbclid');
    const ttclid = getParam('ttclid');
    const scClickId = getParam('sc_clickid', 'sccid');
    const twclid = getParam('twclid');
    const msclkid = getParam('msclkid');
    const clickId = gclid || fbclid || ttclid || scClickId || twclid || msclkid;

    // Fallback campaign from ID if name not provided
    if (!utmCampaign) {
      utmCampaign = getParam('campaign_id', 'campaignid', 'adset_id', 'adsetid');
    }

    // Auto-derive utm_source / utm_medium from click IDs or referrer if not explicitly set
    if (!utmSource) {
      if (gclid) {
        utmSource = 'google';
        utmMedium = utmMedium || 'cpc';
      } else if (fbclid) {
        utmSource = 'meta';
        utmMedium = utmMedium || 'cpc';
      } else if (scClickId) {
        utmSource = 'snapchat';
        utmMedium = utmMedium || 'cpc';
      } else if (ttclid) {
        utmSource = 'tiktok';
        utmMedium = utmMedium || 'cpc';
      } else if (twclid) {
        utmSource = 'twitter';
        utmMedium = utmMedium || 'cpc';
      } else if (msclkid) {
        utmSource = 'bing';
        utmMedium = utmMedium || 'cpc';
      } else if (referrer && !referrer.includes(window.location.hostname)) {
        if (referrer.includes('google.com') || referrer.includes('google.com.sa')) {
          utmSource = 'google';
          utmMedium = utmMedium || 'organic';
        } else if (referrer.includes('instagram.com') || referrer.includes('facebook.com') || referrer.includes('fb.me')) {
          utmSource = 'meta';
          utmMedium = utmMedium || 'social';
        } else if (referrer.includes('snapchat.com')) {
          utmSource = 'snapchat';
          utmMedium = utmMedium || 'social';
        } else if (referrer.includes('tiktok.com') || referrer.includes('byteoversea')) {
          utmSource = 'tiktok';
          utmMedium = utmMedium || 'social';
        } else if (referrer.includes('twitter.com') || referrer.includes('x.com') || referrer.includes('t.co')) {
          utmSource = 'twitter';
          utmMedium = utmMedium || 'social';
        } else if (referrer.includes('youtube.com') || referrer.includes('youtu.be')) {
          utmSource = 'youtube';
          utmMedium = utmMedium || 'social';
        } else {
          try {
            utmSource = new URL(referrer).hostname;
            utmMedium = utmMedium || 'referral';
          } catch {}
        }
      }
    }

    // Check if incoming tracking data exists on this visit
    const hasIncomingTracking = Boolean(
      utmSource || utmCampaign || clickId || (referrer && !referrer.includes(window.location.hostname))
    );

    if (hasIncomingTracking) {
      let channel = 'مباشر (Direct Traffic)';
      const srcLower = utmSource?.toLowerCase() || '';
      const medLower = utmMedium?.toLowerCase() || '';
      const isPaidMedium = medLower.includes('cpc') || medLower.includes('paid') || medLower.includes('ad');

      // 1. Google Ads & Search
      if (
        srcLower.includes('google') ||
        srcLower.includes('adwords') ||
        srcLower.includes('gads') ||
        gclid ||
        (!utmSource && (referrer.includes('google.com') || referrer.includes('google.com.sa')))
      ) {
        channel = isPaidMedium || gclid ? 'Google Ads' : 'Google Search (Organic)';
      }
      // 2. Meta (Instagram / Facebook)
      else if (
        srcLower.includes('meta') ||
        srcLower.includes('instagram') ||
        srcLower.includes('facebook') ||
        srcLower === 'fb' ||
        srcLower === 'ig' ||
        srcLower.startsWith('fb_') ||
        srcLower.startsWith('ig_') ||
        fbclid ||
        (!utmSource &&
          (referrer.includes('instagram.com') || referrer.includes('facebook.com') || referrer.includes('fb.me')))
      ) {
        channel = 'Meta (Instagram / Facebook)';
      }
      // 3. Snapchat
      else if (
        srcLower.includes('snapchat') ||
        srcLower === 'snap' ||
        srcLower.startsWith('snap_') ||
        srcLower.startsWith('snapchat_') ||
        scClickId ||
        (!utmSource && referrer.includes('snapchat.com'))
      ) {
        channel = 'Snapchat';
      }
      // 4. TikTok
      else if (
        srcLower.includes('tiktok') ||
        srcLower === 'tt' ||
        srcLower.startsWith('tt_') ||
        srcLower.startsWith('tiktok_') ||
        ttclid ||
        (!utmSource && (referrer.includes('tiktok.com') || referrer.includes('byteoversea')))
      ) {
        channel = 'TikTok';
      }
      // 5. Twitter / X
      else if (
        srcLower.includes('twitter') ||
        srcLower === 'x' ||
        srcLower.startsWith('twitter_') ||
        srcLower.startsWith('x_') ||
        twclid ||
        (!utmSource && (referrer.includes('t.co') || referrer.includes('twitter.com') || referrer.includes('x.com')))
      ) {
        channel = 'Twitter / X';
      }
      // 6. YouTube
      else if (
        srcLower.includes('youtube') ||
        srcLower === 'yt' ||
        srcLower.startsWith('yt_') ||
        srcLower.startsWith('youtube_') ||
        (!utmSource && (referrer.includes('youtube.com') || referrer.includes('youtu.be')))
      ) {
        channel = 'YouTube';
      }
      // 7. Referral from other external websites
      else if (referrer && !referrer.includes(window.location.hostname)) {
        try {
          const host = new URL(referrer).hostname;
          channel = `إحالة: ${host}`;
        } catch {
          channel = 'موقع خارجي (Referral)';
        }
      }
      // 8. Custom UTM source
      else if (utmSource) {
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
      try {
        const parsed = JSON.parse(stored) as IAttributionData;
        // Sanitize legacy bug where Snapchat was falsely attributed via _scid cookie
        const isLegacySnapchatBug =
          parsed.marketing_channel === 'Snapchat' &&
          (!parsed.utm_source || parsed.utm_source === 'snapchat') &&
          !parsed.utm_campaign &&
          (!parsed.click_id || parsed.click_id.length > 30 || parsed.click_id.includes('-'));

        if (isLegacySnapchatBug) {
          sessionStorage.removeItem(ATTRIBUTION_STORAGE_KEY);
          localStorage.removeItem(ATTRIBUTION_STORAGE_KEY);
          clearCookie(`${ATTRIBUTION_COOKIE_PREFIX}source`);
          clearCookie(`${ATTRIBUTION_COOKIE_PREFIX}campaign`);
          clearCookie(`${ATTRIBUTION_COOKIE_PREFIX}channel`);
          return { marketing_channel: 'مباشر (Direct Traffic)' };
        }

        return parsed;
      } catch {}
    }

    const cookieSource = getCookie(`${ATTRIBUTION_COOKIE_PREFIX}source`);
    const cookieCampaign = getCookie(`${ATTRIBUTION_COOKIE_PREFIX}campaign`);
    const cookieChannel = getCookie(`${ATTRIBUTION_COOKIE_PREFIX}channel`);

    if (cookieSource || cookieCampaign || cookieChannel) {
      if (cookieChannel === 'Snapchat' && !cookieCampaign && (!cookieSource || cookieSource === 'snapchat')) {
        // Clear corrupted legacy cookie
        clearCookie(`${ATTRIBUTION_COOKIE_PREFIX}source`);
        clearCookie(`${ATTRIBUTION_COOKIE_PREFIX}campaign`);
        clearCookie(`${ATTRIBUTION_COOKIE_PREFIX}channel`);
        return { marketing_channel: 'مباشر (Direct Traffic)' };
      }

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
