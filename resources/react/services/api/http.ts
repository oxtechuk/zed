import axios from "axios";
import { API_BASE_URL, API_TIMEOUT } from "../../constants/axios.constants";
import { useLanguageStore } from "../../store/language.store";
import { getAttributionPayload } from "../attribution";

const api = axios.create({
  baseURL: API_BASE_URL,
  timeout: API_TIMEOUT,
  headers: {
    "Content-Type": "application/json",
  },
  paramsSerializer: {
    indexes: false,
  },
});

api.interceptors.request.use(
  (config) => {
    const language = useLanguageStore.getState().language;
    if (language) {
      config.headers["Accept-Language"] = language;
    }

    const token = sessionStorage.getItem("access_token");
    if (token && config.headers) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    if (config.method === "post" && config.data) {
      const attribution = getAttributionPayload();
      if (config.data instanceof FormData) {
        Object.entries(attribution).forEach(([key, val]) => {
          if (val && !config.data.has(key)) {
            config.data.append(key, val);
          }
        });
      } else if (typeof config.data === "object") {
        config.data = {
          ...attribution,
          ...config.data,
          utm_source: config.data.utm_source || attribution.utm_source,
          utm_medium: config.data.utm_medium || attribution.utm_medium,
          utm_campaign: config.data.utm_campaign || attribution.utm_campaign,
          utm_content: config.data.utm_content || attribution.utm_content,
          utm_term: config.data.utm_term || attribution.utm_term,
          referrer: config.data.referrer || attribution.referrer,
          click_id: config.data.click_id || attribution.click_id,
          marketing_channel: config.data.marketing_channel || attribution.marketing_channel,
        };
      }
    }

    return config;
  },
  (error) => Promise.reject(error)
);

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (!error.response) {
      return Promise.reject(error);
    }
    return Promise.reject(error);
  }
);

export default api;
