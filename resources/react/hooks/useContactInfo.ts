import { useMemo } from "react";
import { useTranslation } from "react-i18next";
import { useSettingsStore } from "../store/settings.store";
import {
  buildMailtoHref,
  buildTelHref,
  buildWhatsAppUrl,
  parseWorkingHours,
  sanitizeDigits,
} from "../utils/contact";
import {
  FALLBACK_EMAIL_HREF,
  FALLBACK_PHONE_HREF,
  FALLBACK_WHATSAPP_NUMBER,
} from "../constants/contact.constants";
import type { IBranchInfo } from "../interfaces/IBranchInfo";
import type { IUseContactInfoResult } from "../interfaces/IUseContactInfoResult";

export function useContactInfo(): IUseContactInfoResult {
  const { t } = useTranslation();
  const settings = useSettingsStore((state) => state.settings);

  return useMemo(() => {
    const apiContact = settings?.contact;

    const whatsappRaw = apiContact?.whatsapp || apiContact?.phone || FALLBACK_WHATSAPP_NUMBER;
    const whatsappNumber = sanitizeDigits(whatsappRaw);
    const whatsappDisplay =
      apiContact?.whatsapp || apiContact?.phone || t("contactPage.contactUs.labels.whatsappFallback");

    const phoneDisplay = apiContact?.phone || t("rootLayout.phone");
    const phoneHref = apiContact?.phone ? buildTelHref(apiContact.phone) : FALLBACK_PHONE_HREF;

    const emailDisplay = apiContact?.email || t("rootLayout.email");
    const emailHref = apiContact?.email ? buildMailtoHref(apiContact.email) : FALLBACK_EMAIL_HREF;

    const addressDisplay = apiContact?.address || t("rootLayout.address");

    const branches: IBranchInfo[] = settings?.about_branches?.length
      ? settings.about_branches.map((branch) => ({
          name: branch.name,
          address: branch.address,
          mapLink: branch.map_link,
          workingHours: parseWorkingHours(branch.working_hours),
        }))
      : (t("contactPage.contactUs.fallbackBranches", { returnObjects: true }) as IBranchInfo[]);

    return {
      contact: {
        whatsappNumber,
        whatsappHref: buildWhatsAppUrl(whatsappNumber),
        whatsappDisplay,
        phoneDisplay,
        phoneHref,
        emailDisplay,
        emailHref,
        addressDisplay,
      },
      branches,
    };
  }, [settings, t]);
}
