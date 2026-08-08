import { useEffect, useRef } from "react";
import { useTranslation } from "react-i18next";
import LazyImg from "./LazyImg";
import type { IPartnersCarouselProps, IPartnerItem } from "../interfaces/IPartnersCarouselProps";

export default function PartnersCarousel({
  partners,
  speed = 85,
  showName = false,
}: IPartnersCarouselProps) {
  const { i18n } = useTranslation();
  const direction = i18n.dir();
  const trackRef = useRef<HTMLDivElement>(null);
  const offsetRef = useRef(0);
  const rafRef = useRef<number>(0);
  const pausedRef = useRef(false);

  if (!partners.length) {
    return null;
  }

  function renderPartner(partner: IPartnerItem, key: string | number) {
    const card = (
      <div
        className={[
          "flex shrink-0 items-center justify-center",
          "px-6",
          showName
            ? "h-auto w-[145px] flex-col gap-2 py-5 sm:w-[170px]"
            : "h-[150px] w-[210px] sm:h-[165px] sm:w-[240px]",
        ].join(" ")}
      >
        <LazyImg
          src={partner.logo}
          alt={partner.name}
          className={[
            "max-h-[90px] max-w-[190px] object-contain",
            "transition duration-300",
            "hover:scale-105",
            "sm:max-h-[100px] sm:max-w-[220px]",
          ].join(" ")}
        />
        {showName && (
          <span className="text-[13px] font-semibold text-[#111827] sm:text-[14px]">
            {partner.name}
          </span>
        )}
      </div>
    );

    if (!partner.link) {
      return <div key={key}>{card}</div>;
    }

    return (
      <a key={key} href={partner.link} aria-label={partner.name} className="block">
        {card}
      </a>
    );
  }

  const itemWidth = 170 + 48;
  const viewWidth = typeof window !== "undefined" ? window.innerWidth : 1440;
  const repeats = Math.max(4, Math.ceil((viewWidth * 3) / (partners.length * itemWidth)));

  const isRTL = direction === "rtl";

  const setA: React.ReactNode[] = [];
  const setB: React.ReactNode[] = [];
  for (let i = 0; i < repeats; i++) {
    for (let j = 0; j < partners.length; j++) {
      setA.push(renderPartner(partners[j], `a${i}-${j}`));
      setB.push(renderPartner(partners[j], `b${i}-${j}`));
    }
  }

  useEffect(() => {
    const track = trackRef.current;
    if (!track) return;

    const children = track.children;
    const midpoint = Math.floor(children.length / 2);
    let halfWidth = 0;
    for (let i = 0; i < midpoint; i++) {
      halfWidth += (children[i] as HTMLElement).offsetWidth;
    }
    if (halfWidth <= 0) return;

    const tick = () => {
      if (!pausedRef.current) {
        const pxPerFrame = halfWidth / (speed * 60);
        offsetRef.current += pxPerFrame;

        if (offsetRef.current >= halfWidth) {
          offsetRef.current -= halfWidth;
        }

        const sign = isRTL ? 1 : -1;
        track.style.transform = `translateX(${sign * offsetRef.current}px)`;
      }
      rafRef.current = requestAnimationFrame(tick);
    };

    rafRef.current = requestAnimationFrame(tick);

    return () => {
      cancelAnimationFrame(rafRef.current);
    };
  }, [partners, speed, isRTL]);

  return (
    <section dir={direction} className="w-full overflow-hidden">
      <div
        className="relative overflow-hidden"
        onMouseEnter={() => {
          pausedRef.current = true;
        }}
        onMouseLeave={() => {
          pausedRef.current = false;
        }}
      >
        <div
          ref={trackRef}
          className="flex w-max will-change-transform"
        >
          {setA}
          {setB}
        </div>
      </div>
    </section>
  );
}
