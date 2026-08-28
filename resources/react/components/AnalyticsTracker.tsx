import { useEffect } from "react";
import { useLocation } from "react-router-dom";
import { trackPageView } from "../services/analytics";
import { captureAttribution } from "../services/attribution";

export default function AnalyticsTracker() {
  const location = useLocation();

  useEffect(() => {
    // Capture marketing attribution and UTMs
    captureAttribution();
    // Fire PageView on every route transition in the SPA
    trackPageView(location.pathname + location.search);
  }, [location.pathname, location.search]);

  return null;
}
