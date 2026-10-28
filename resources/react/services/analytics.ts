/**
 * Unified Analytics & Pixel Tracking Service
 * Integrates Meta Pixel (Facebook), TikTok Pixel, Snapchat Pixel, Google Tag Manager (GTM) and Google Analytics 4 (gtag).
 */

declare global {
  interface Window {
    fbq?: (...args: any[]) => void;
    _fbq?: any;
    ttq?: {
      page: () => void;
      track: (eventName: string, params?: Record<string, any>, options?: Record<string, any>) => void;
      identify: (params: Record<string, any>) => void;
      [key: string]: any;
    };
    snaptr?: (action: string, eventName: string, params?: Record<string, any>) => void;
    dataLayer?: Array<Record<string, any>>;
    gtag?: (...args: any[]) => void;
  }
}

/**
 * Send an event to Google Analytics 4 (gtag.js is loaded in the page head).
 * Page views are tracked by GA4 enhanced measurement (history changes), not here.
 * Never pass personal data (name, phone, salary) to GA4.
 */
function ga4Event(name: string, params: Record<string, any> = {}): void {
  try {
    window.gtag?.('event', name, params);
  } catch {
    // analytics must never break the UI
  }
}

/**
 * Generate a unique Event ID for deduplication between client-side and server-side (CAPI).
 */
export function generateEventId(prefix: string = 'evt'): string {
  const timestamp = Date.now();
  const random = Math.random().toString(36).substring(2, 9);
  return `${prefix}_${timestamp}_${random}`;
}

/**
 * Track SPA PageView across all platforms
 */
export function trackPageView(path?: string, title?: string): void {
  try {
    const currentPath = path || window.location.pathname;
    const currentTitle = title || document.title;

    // 1. Meta Pixel
    if (typeof window.fbq === 'function') {
      window.fbq('track', 'PageView');
    }

    // 2. TikTok Pixel
    if (window.ttq && typeof window.ttq.page === 'function') {
      window.ttq.page();
    }

    // 3. Snapchat Pixel
    if (typeof window.snaptr === 'function') {
      window.snaptr('track', 'PAGE_VIEW');
    }

    // 4. Google Tag Manager / dataLayer
    if (Array.isArray(window.dataLayer)) {
      window.dataLayer.push({
        event: 'page_view',
        page_path: currentPath,
        page_title: currentTitle,
        timestamp: Date.now(),
      });
    }
  } catch (error) {
    // Fail silently so adblockers do not disrupt user UX
    console.debug('[Analytics] Error tracking PageView:', error);
  }
}

/**
 * Track Car View (ViewContent / view_item)
 */
export function trackViewContent(car: {
  id: string | number;
  name: string;
  brand?: string;
  price?: number;
  currency?: string;
}): void {
  try {
    const currency = car.currency || 'SAR';
    const price = Number(car.price) || 0;

    // 1. Meta Pixel
    if (typeof window.fbq === 'function') {
      window.fbq('track', 'ViewContent', {
        content_name: car.name,
        content_category: car.brand || 'Cars',
        content_ids: [String(car.id)],
        content_type: 'product',
        value: price,
        currency: currency,
      });
    }

    // 2. TikTok Pixel
    if (window.ttq && typeof window.ttq.track === 'function') {
      window.ttq.track('ViewContent', {
        content_id: String(car.id),
        content_name: car.name,
        content_category: car.brand || 'Cars',
        content_type: 'product',
        value: price,
        currency: currency,
      });
    }

    // 3. Snapchat Pixel
    if (typeof window.snaptr === 'function') {
      window.snaptr('track', 'VIEW_CONTENT', {
        item_ids: [String(car.id)],
        item_category: car.brand || 'Cars',
        price: price,
        currency: currency,
      });
    }

    // 4. Google Tag Manager
    if (Array.isArray(window.dataLayer)) {
      window.dataLayer.push({
        event: 'view_item',
        ecommerce: {
          currency: currency,
          value: price,
          items: [
            {
              item_id: String(car.id),
              item_name: car.name,
              item_brand: car.brand,
              price: price,
            },
          ],
        },
      });
    }

    // 5. Google Analytics 4
    ga4Event('view_item', {
      currency,
      value: price,
      items: [{ item_id: String(car.id), item_name: car.name, item_brand: car.brand, price }],
    });
  } catch (error) {
    console.debug('[Analytics] Error tracking ViewContent:', error);
  }
}

/**
 * Track Booking / Car Request Submission (Lead)
 */
export function trackLead(params: {
  eventId?: string;
  carId?: string | number;
  carName?: string;
  value?: number;
  clientName?: string;
  clientPhone?: string;
}): string {
  const eventId = params.eventId || generateEventId('lead');
  const value = Number(params.value) || 0;
  const currency = 'SAR';

  try {
    // 1. Meta Pixel with eventID for deduplication with CAPI
    if (typeof window.fbq === 'function') {
      window.fbq(
        'track',
        'Lead',
        {
          content_name: params.carName || 'Car Booking',
          content_category: 'Car Request',
          content_ids: params.carId ? [String(params.carId)] : undefined,
          value: value,
          currency: currency,
        },
        { eventID: eventId }
      );
    }

    // 2. TikTok Pixel
    if (window.ttq && typeof window.ttq.track === 'function') {
      window.ttq.track(
        'SubmitForm',
        {
          content_name: params.carName || 'Car Booking',
          content_id: params.carId ? String(params.carId) : undefined,
          value: value,
          currency: currency,
        },
        { event_id: eventId }
      );
    }

    // 3. Snapchat Pixel
    if (typeof window.snaptr === 'function') {
      window.snaptr('track', 'SIGN_UP', {
        price: value,
        currency: currency,
      });
    }

    // 4. Google Tag Manager
    if (Array.isArray(window.dataLayer)) {
      window.dataLayer.push({
        event: 'lead_request_car',
        car_id: params.carId,
      });
      window.dataLayer.push({
        event: 'generate_lead',
        event_id: eventId,
        lead_type: 'booking',
        car_name: params.carName,
        car_id: params.carId,
        value: value,
        currency: currency,
      });
    }

    // 5. Google Analytics 4
    ga4Event('generate_lead', {
      lead_type: 'request_car',
      car_id: params.carId != null ? String(params.carId) : undefined,
      car_name: params.carName,
      value,
      currency,
    });
  } catch (error) {
    console.debug('[Analytics] Error tracking Lead:', error);
  }

  return eventId;
}

/**
 * Track Finance Calculator Submission (Lead / CompleteRegistration)
 */
export function trackCalculatorLead(params: {
  eventId?: string;
  carId?: string | number;
  carName?: string;
  salary?: number;
  monthlyInstallment?: number;
}): string {
  const eventId = params.eventId || generateEventId('calc');
  const value = Number(params.monthlyInstallment) || 0;
  const currency = 'SAR';

  try {
    // 1. Meta Pixel
    if (typeof window.fbq === 'function') {
      window.fbq(
        'track',
        'CompleteRegistration',
        {
          content_name: params.carName || 'Finance Calculator',
          content_category: 'Finance Calculator Lead',
          value: value,
          currency: currency,
        },
        { eventID: eventId }
      );
    }

    // 2. TikTok Pixel
    if (window.ttq && typeof window.ttq.track === 'function') {
      window.ttq.track(
        'CompleteRegistration',
        {
          content_name: params.carName || 'Finance Calculator',
          value: value,
          currency: currency,
        },
        { event_id: eventId }
      );
    }

    // 3. Snapchat Pixel
    if (typeof window.snaptr === 'function') {
      window.snaptr('track', 'CUSTOM_EVENT_1', {
        event_name: 'finance_calculator_lead',
        price: value,
        currency: currency,
      });
    }

    // 4. Google Tag Manager
    if (Array.isArray(window.dataLayer)) {
      window.dataLayer.push({
        event: 'lead_finance_calculator',
        car_id: params.carId,
      });
      window.dataLayer.push({
        event: 'calculator_lead',
        event_id: eventId,
        car_name: params.carName,
        monthly_installment: params.monthlyInstallment,
        salary: params.salary,
      });
    }

    // 5. Google Analytics 4 (no salary or installment details: lead source only)
    ga4Event('generate_lead', {
      lead_type: 'finance_calculator',
      car_id: params.carId != null ? String(params.carId) : undefined,
      car_name: params.carName,
      currency,
    });
  } catch (error) {
    console.debug('[Analytics] Error tracking Calculator Lead:', error);
  }

  return eventId;
}

/**
 * Track Direct Contact (WhatsApp / Phone call)
 */
export function trackContact(type: 'whatsapp' | 'phone', target?: string): void {
  try {
    // 1. Meta Pixel
    if (typeof window.fbq === 'function') {
      window.fbq('track', 'Contact', {
        content_name: type === 'whatsapp' ? 'WhatsApp Click' : 'Phone Call Click',
        content_category: target || 'Contact Button',
      });
    }

    // 2. TikTok Pixel
    if (window.ttq && typeof window.ttq.track === 'function') {
      window.ttq.track('Contact', {
        content_name: type === 'whatsapp' ? 'WhatsApp' : 'Phone',
      });
    }

    // 3. Snapchat Pixel
    if (typeof window.snaptr === 'function') {
      window.snaptr('track', 'CUSTOM_EVENT_2', {
        event_name: `contact_${type}`,
      });
    }

    // 4. Google Tag Manager
    if (Array.isArray(window.dataLayer)) {
      if (type === 'whatsapp') {
        window.dataLayer.push({ event: 'click_whatsapp' });
      } else if (type === 'phone') {
        window.dataLayer.push({ event: 'click_phone' });
      }
      window.dataLayer.push({
        event: 'contact_click',
        contact_type: type,
        target: target,
      });
    }

    // 5. Google Analytics 4
    ga4Event('contact_click', { contact_type: type });
  } catch (error) {
    console.debug('[Analytics] Error tracking Contact:', error);
  }
}

/**
 * Track Contact Us Form Submission (Lead)
 */
export function trackContactFormLead(): string {
  const eventId = generateEventId('contact');

  try {
    if (typeof window.fbq === 'function') {
      window.fbq(
        'track',
        'Contact',
        { content_name: 'Contact Form Submission', content_category: 'Contact Us' },
        { eventID: eventId }
      );
    }

    if (window.ttq && typeof window.ttq.track === 'function') {
      window.ttq.track('Contact', { content_name: 'Contact Form Submission' }, { event_id: eventId });
    }

    if (Array.isArray(window.dataLayer)) {
      window.dataLayer.push({
        event: 'lead_contact',
      });
    }

    // 5. Google Analytics 4
    ga4Event('generate_lead', { lead_type: 'contact_form' });
  } catch (error) {
    console.debug('[Analytics] Error tracking Contact Form Lead:', error);
  }

  return eventId;
}
