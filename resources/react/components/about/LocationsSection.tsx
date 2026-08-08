import { useState } from "react";
import { useTranslation } from "react-i18next";
import { Clock, ExternalLink, MapPin, Phone } from "lucide-react";
import LocationInfo from "./LocationInfo";
import LocationButton from "./LocationButton";
import { toEmbedUrl } from "../../helpers/maps";
import type { ILocationsSectionProps } from "../../interfaces/ILocationsSectionProps";

export default function LocationsSection({
  titleBlack,
  titleBlue,
  locations,
}: ILocationsSectionProps) {
  const { i18n, t } = useTranslation();
  const [activeLocationId, setActiveLocationId] = useState(locations[0]?.id);

  const activeLocation =
    locations.find((location) => location.id === activeLocationId) ??
    locations[0];

  if (!activeLocation) return null;

  const searchQuery = [activeLocation.address, activeLocation.city]
    .filter(Boolean)
    .join(", ");
  const canEmbedMapLink =
    activeLocation.mapLink &&
    !activeLocation.mapLink.includes("goo.gl") &&
    !activeLocation.mapLink.includes("maps.app");
  const embedUrl = canEmbedMapLink
    ? toEmbedUrl(activeLocation.mapLink!)
    : searchQuery
      ? toEmbedUrl(searchQuery)
      : "";
  const mapsHref =
    activeLocation.mapLink ||
    `https://www.google.com/maps?q=${encodeURIComponent(searchQuery)}`;
  const showMapLink = activeLocation.mapLink || searchQuery;

  return (
    <section dir={i18n.dir()} className="w-full bg-[#F0F2F5] py-16">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 className="mb-20 text-center text-[42px] font-extrabold leading-tight md:text-[52px]">
          <span className="text-[#07111F]">{titleBlack} </span>
          <span className="text-[var(--brand-primary-color)]">{titleBlue}</span>
        </h2>

        <div className="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-start">
          {/* Location list */}
          <div className="lg:col-span-4">
            <div className="flex flex-col gap-3">
              {locations.map((location) => (
                <LocationButton
                  key={location.id}
                  location={location}
                  isActive={location.id === activeLocationId}
                  onSelect={setActiveLocationId}
                />
              ))}
            </div>
          </div>

          {/* Map + info card */}
          <div className="lg:col-span-8">
            <div className="overflow-hidden rounded-[22px] bg-white shadow-sm">
              {/* Map iframe */}
              <div className="relative h-[300px] w-full overflow-hidden md:h-[360px]">
                {embedUrl ? (
                  <iframe
                    key={embedUrl}
                    src={embedUrl}
                    title={t("aboutPage.locations.mapTitle", { city: activeLocation.city })}
                    className="h-full w-full border-0"
                    allowFullScreen
                    loading="lazy"
                    referrerPolicy="no-referrer-when-downgrade"
                  />
                ) : (
                  /* Fallback when no map link is provided */
                  <div className="flex h-full w-full flex-col items-center justify-center gap-3 bg-[#0F1A2E]">
                    <MapPin
                      size={40}
                      className="text-[var(--brand-secondary-color)]"
                    />
                    <p className="text-[15px] text-white/60">
                      {t("aboutPage.locations.noMap")}
                    </p>
                  </div>
                )}

                {/* Open in maps overlay button */}
                {showMapLink && (
                  <a
                    href={mapsHref}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="absolute bottom-3 end-3 flex items-center gap-2 rounded-[8px] bg-white/90 px-4 py-2 text-[13px] font-bold text-[#164EB8] shadow transition hover:bg-white"
                  >
                    <ExternalLink size={14} />
                    {t("aboutPage.locations.openInMaps")}
                  </a>
                )}
              </div>

              {/* Address (full width, separate) */}
              <div className="px-8 pt-7">
                <LocationInfo
                  icon={<MapPin size={24} />}
                  label={t("aboutPage.locations.addressLabel")}
                  value={activeLocation.address}
                  href={showMapLink ? mapsHref : undefined}
                />
              </div>

              {/* Phone + Hours */}
              <div className="grid grid-cols-2 gap-8 px-8 py-7">
                <LocationInfo
                  icon={<Phone size={24} />}
                  label={t("aboutPage.locations.phoneLabel")}
                  value={activeLocation.phone}
                />

                <LocationInfo
                  icon={<Clock size={24} />}
                  label={t("aboutPage.locations.hoursLabel")}
                  value={activeLocation.workingHours}
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
