import { useState, useEffect } from "react";

export interface ICountdownResult {
  days: string;
  hours: string;
  minutes: string;
  seconds: string;
  isExpired: boolean;
}

export function useCountdown(endDateString: string | null | undefined): ICountdownResult {
  const [timeLeft, setTimeLeft] = useState<ICountdownResult>({
    days: "00",
    hours: "00",
    minutes: "00",
    seconds: "00",
    isExpired: true,
  });

  useEffect(() => {
    if (!endDateString) {
      // Fallback for visual mock demo if no date is provided
      setTimeLeft({
        days: "04",
        hours: "23",
        minutes: "40",
        seconds: "03",
        isExpired: false,
      });
      return;
    }

    const calculateTimeLeft = (): ICountdownResult => {
      const difference = +new Date(endDateString) - +new Date();
      
      if (difference <= 0) {
        return {
          days: "00",
          hours: "00",
          minutes: "00",
          seconds: "00",
          isExpired: true,
        };
      }

      const d = Math.floor(difference / (1000 * 60 * 60 * 24));
      const h = Math.floor((difference / (1000 * 60 * 60)) % 24);
      const m = Math.floor((difference / 1000 / 60) % 60);
      const s = Math.floor((difference / 1000) % 60);

      return {
        days: d < 10 ? `0${d}` : `${d}`,
        hours: h < 10 ? `0${h}` : `${h}`,
        minutes: m < 10 ? `0${m}` : `${m}`,
        seconds: s < 10 ? `0${s}` : `${s}`,
        isExpired: false,
      };
    };

    setTimeLeft(calculateTimeLeft());

    const timer = setInterval(() => {
      setTimeLeft(calculateTimeLeft());
    }, 1000);

    return () => clearInterval(timer);
  }, [endDateString]);

  return timeLeft;
}
