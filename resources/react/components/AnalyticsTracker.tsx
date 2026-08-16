import { useEffect } from "react";
import { useLocation } from "react-router-dom";
import { trackPageView } from "../services/analytics";

export default function AnalyticsTracker() {
  const location = useLocation();

  useEffect(() => {
    // Fire PageView on every route transition in the SPA
    trackPageView(location.pathname + location.search);
  }, [location.pathname, location.search]);

  return null;
}
