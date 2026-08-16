export const API_PROTOCOL = import.meta.env.VITE_API_PROTOCOL ?? "http";
export const API_HOST = import.meta.env.VITE_API_HOST ?? "";
export const API_PORT = import.meta.env.VITE_API_PORT ?? "";
export const API_BASE_PATH = import.meta.env.VITE_API_BASE_PATH ?? "api";

const isLocalDev = Boolean(API_HOST && API_HOST.trim() !== "");
const portPart = API_PORT ? `:${API_PORT}` : "";

export const getApiBaseUrl = (): string => {
  if (typeof window !== "undefined" && (window as any).__API_URL__) {
    return (window as any).__API_URL__;
  }
  if (isLocalDev) {
    return `${API_PROTOCOL}://${API_HOST}${portPart}/${API_BASE_PATH}/`;
  }
  return `/${API_BASE_PATH}/`;
};

export const API_BASE_URL = getApiBaseUrl();

export const API_ORIGIN = isLocalDev
  ? `${API_PROTOCOL}://${API_HOST}${portPart}`
  : (typeof window !== "undefined" && (window as any).__APP_URL__ ? (window as any).__APP_URL__ : "");

export const API_TIMEOUT = 30000;
