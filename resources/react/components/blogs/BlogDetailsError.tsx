import { useTranslation } from "react-i18next";

export default function BlogDetailsError() {
  const { t, i18n } = useTranslation();

  return (
    <section dir={i18n.dir()} className="flex h-[60vh] items-center justify-center bg-[#F0F2F5]">
      <p className="text-[18px] text-[#6B7280]">
        {t("blogPage.details.page.error")}
      </p>
    </section>
  );
}
