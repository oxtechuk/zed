Zad Capital
SEO & Performance Changes
Site: https://zad-capital.sa     From: Digital Plus · SEO Team     Date: 14 October 2026
The site currently ranks for no keywords in Google. The causes are technical: the car pages cannot be discovered (no crawlable links, sitemap on another domain), and every car page has the same title. This document lists every change in one place, ordered by priority. Each item states what we observed from the live site, how to verify it, the recommended fix, and when it counts as done.
Please do not change any existing car URL while implementing these items.
Baseline (Lighthouse 12.6, 14 Oct 2026)
Page	Device	Performance	LCP	CLS	Page weight
Homepage	Mobile	58	12.4 s	0.06	5.4 MB
Homepage	Desktop	79	2.6 s	0.02	5.5 MB
/cars	Mobile	64	6.5 s	0.00	1.3 MB
Car page	Mobile	65	6.2 s	0.00	1.3 MB
Car page	Desktop	60	2.5 s	2.01	1.4 MB
Finance calculator	Mobile	58	9.5 s	0.01	1.1 MB
Targets after the changes: mobile performance ≥ 85, LCP < 2.5 s, CLS < 0.1, homepage < 1.5 MB.
Priorities
•	P0 — this week (≈ 5.5 h). Without these, nothing else has an effect.
•	P1 — within two weeks (≈ 12.5 h).
•	P2 — November (≈ 10 h + client decision on English).
Changes
1. Car cards and pagination are not crawlable links   ·   P0
Issue  On /cars, the homepage and car pages there is no <a href> pointing to any /cars/{slug} page. Car cards and pagination are buttons that navigate with JavaScript. Google only follows <a href> links, so none of the 98 car pages can be discovered by crawling.
Verify  DevTools console on /cars: document.querySelectorAll('a[href*="/cars/"]').length returns 0.
Fix
•	Render every car card as a real link: a React Router <Link to="/cars/{slug}"> wrapping the card (image + name + price).
•	Pagination as links with a URL parameter (/cars?page=2) instead of buttons. Same for brand logos on /brands and related cars on car pages.
Done when  Every car visible on /cars (all pages) is reachable through an <a href>.
2. Sitemap and robots.txt point to another domain   ·   P0
Issue  /sitemap.xml lists 9 URLs, all on https://knoz.oxtech.uk (a domain that does not resolve), and none on zad-capital.sa. /robots.txt also declares that sitemap.
Verify  curl -s https://zad-capital.sa/sitemap.xml | grep -c knoz.oxtech.uk
Fix
•	Generate /sitemap.xml dynamically from the database: static pages + every active car + every published blog post, with <lastmod> from updated_at. Remove any static sitemap file so it does not override the route.
•	Arabic slugs must be URL-encoded inside <loc>. Do not include /en/* URLs (see item 19).
•	Replace /robots.txt with:
User-agent: *
Allow: /
Disallow: /crm
Disallow: /manager-login
 
Sitemap: https://zad-capital.sa/sitemap.xml
Note  Do not disallow /api/ — Google needs the store API to render the pages.
Done when  Sitemap returns 108+ <loc> entries, all on zad-capital.sa, and 0 on knoz.oxtech.uk. robots.txt matches the block above.
3. All 98 car pages share one title and description   ·   P0
Issue  Every car page has the title زاد كابيتال | السيارات and the same meta description. Google treats them as duplicates and cannot rank them for model searches (e.g. «لاند كروزر 2026» ≈ 100K searches/month).
Verify  Open any two car pages and compare document.title.
Fix
•	Build title and description from the car data after it loads:
•	Title: {car name} {year} بالتقسيط · قسط من {min installment} ريال (if longer than 65 characters, drop the installment part).
•	Description: اشتري {car name} بالتقسيط من زاد كابيتال. السعر {price} ريال والقسط الشهري يبدأ من {installment} ريال. شوف المواصفات واحسب قسطك.
•	og:image = the car's main image. A full title list for all 98 cars is attached (Excel).
Done when  98 different titles; each starts with the car name.
4. Untranslated keys and wrong brand name in titles   ·   P0
Issue  Titles show raw translation keys: /brands → زاد كابيتال | pageTitles.brands, /compare → pageTitles.compare, the 404 page → pageTitles.notFound. /request-car and the initial HTML title show Zed Capital. The About page says «من زيد كابيتال».
Verify  Open /brands, /compare, /request-car and check the tab title. View-source of any page: <title>Zed Capital</title>.
Fix
•	Add the missing pageTitles keys (ar/en): brands → ماركات السيارات المتاحة بالتقسيط · compare → مقارنة السيارات: السعر والمواصفات والقسط · notFound → الصفحة غير موجودة.
•	Replace every «Zed» / «زيد» fallback with «زاد كابيتال». Rebuild the frontend.
Done when  No title on the site contains pageTitles, Zed or زيد.
5. No browser caching for static files   ·   P0
Issue  Every response, including hashed build files, fonts and images, is sent with Cache-Control: no-cache. Visitors re-download ~1.3–5 MB on every visit. Images (.webp, .png) are also gzip-compressed again, which wastes CPU for no gain.
Verify  curl -sI https://zad-capital.sa/build/assets/<main>.js | grep -i cache-control → no-cache.
Fix
•	In the site's Nginx server block, before the main location /:
location ^~ /build/ {
    try_files $uri =404;
    add_header Cache-Control "public, max-age=31536000, immutable";
    access_log off;
}
location ~* ^/(storage|images|fonts)/.+\.(webp|avif|jpe?g|png|gif|svg|ico|woff2?|ttf|otf)$ {
    try_files $uri @app;      # @app = your existing upstream
    add_header Cache-Control "public, max-age=2592000";
    gzip off;
    access_log off;
}
Note  Keep no-cache on HTML pages so new builds reach users immediately. Run nginx -t before a graceful reload.
Done when  Build files return max-age=31536000, immutable; images return max-age=2592000; HTML stays no-cache.
6. Same empty HTML for every URL (meta tags only after JavaScript)   ·   P1
Issue  The initial HTML is identical on every URL (same size, same hash): no description, canonical, Open Graph or structured data — only <title>Zed Capital</title>. WhatsApp, Facebook and LinkedIn previews show «Zed Capital» with no image, and Google has to wait for rendering before it sees anything.
Verify  for p in / /cars /offers /about; do curl -s https://zad-capital.sa$p | md5sum; done → four identical hashes.
Fix
•	Render per-route meta on the server into the initial HTML (e.g. the Laravel view that serves the SPA): title, description, canonical, og:*, twitter:card and JSON-LD. For /cars/{slug} and /blog/{slug} load the record and use the same formulas as item 3.
•	Set <html lang="ar" dir="rtl">. Keep the client-side updates for in-app navigation.
Done when  View-source of a car page shows its own title, description and og:image; the four hashes above differ.
7. Soft 404: any URL returns 200   ·   P1
Issue  /this-route-does-not-exist-9f3k and /cars/fake-car-xyz-2099 return HTTP 200 with a 404 message in English only. Google can index unlimited fake pages.
Verify  curl -s -o /dev/null -w "%{http_code}" https://zad-capital.sa/this-route-does-not-exist-9f3k
Fix
•	Unknown routes, unknown car slugs and unknown blog slugs: return the SPA shell with HTTP 404.
•	404 page in Arabic with links to the homepage, /cars and the calculator.
Done when  Fake URLs return 404; the 10 real pages still return 200.
8. Footer links to missing pages: /terms and /privacy   ·   P1
Issue  Every page links to /terms and /privacy; both show the 404 page. A finance site with no privacy policy loses trust signals and fails Google Ads requirements.
Verify  Open /privacy.
Fix
•	Create both pages (Arabic text will be supplied by us), indexable, with their own title and description.
Done when  Both URLs return 200 with real content.
9. Canonical includes query string   ·   P1
Issue  The canonical tag copies the full URL, so /cars?utm_source=x and /cars?page=2 each declare themselves canonical. Ad campaigns create duplicates.
Verify  Open /cars?utm_source=test and inspect link[rel=canonical].
Fix
•	Canonical = origin + path only (no query, no hash, no trailing slash).
Done when  Canonical on /cars?utm_source=test is https://zad-capital.sa/cars.
10. Homepage has 4 H1 tags   ·   P1
Issue  Each hero slide renders its own <h1> (4 in total, including hidden slides).
Verify  Console on /: document.querySelectorAll('h1').length → 4.
Fix
•	First slide <h1>, other slides <h2> (same styling). Recommended H1 text: «تمويل سيارات بالتقسيط في السعودية».
Done when  Exactly one H1 per page, site-wide.
11. Structured data   ·   P1
Issue  Only car pages have JSON-LD (Product), and its availability is always InStock. No schema on any other page.
Verify  Google Rich Results Test on the homepage and a car page.
Fix
•	Homepage: Organization (logo, phone, social profiles) + WebSite.
•	Car pages: Product with brand, model, vehicleModelDate, offer url, itemCondition: NewCondition, and availability mapped from the car's real status (available → InStock, order → PreOrder). Add BreadcrumbList (Home › Cars › Car).
•	Finance calculator: FAQPage once FAQ content is added (we will supply it).
Done when  Rich Results Test passes with no errors on homepage, a car page and the calculator.
12. Oversized images   ·   P1
Issue  The homepage transfers ~5.4 MB on mobile. Two homepage section images are original PNGs: …/storage/home-sections/LsvjzyK8…png (2.0 MB, 1774×887) and …/PP2LZsWB…png (1.9 MB). Several car thumbnails/galleries returned by the store API are also 1.5–2 MB PNGs.
Verify  DevTools › Network › Img on /, sort by size.
Fix
•	Convert every admin upload (including homepage sections) to WebP, max width 1600px, quality ≈ 80, on upload.
•	One-off command for existing files over 300 KB: convert, update the stored path, keep the originals in a backup folder.
•	Add width/height to all images; first hero image fetchpriority="high" and not loading="lazy" (today the LCP image on mobile is lazy-loaded).
Done when  No image over 300 KB on any page; homepage under 1.5 MB.
13. Font: 353 KB TTF   ·   P1
Issue  Cairo is loaded as a variable TTF (353 KB, 170 KB gzipped) — the largest file after images, and it blocks text rendering.
Verify  DevTools › Network › Font.
Fix
•	Serve WOFF2 subset (Arabic + Latin), font-display: swap, and <link rel="preload" as="font" type="font/woff2" crossorigin>.
Done when  Font under 100 KB, WOFF2.
14. Slow store API and late rendering   ·   P1
Issue  /api/store/home takes ~870 ms and the page shows no content until it returns. Mobile LCP is 6–12 s; 316 KB of JavaScript is unused on first load. Car pages on desktop have CLS 2.0 (the loading message is replaced by the full page).
Verify  Lighthouse mobile (table on page 1).
Fix
•	Cache the home/listing API responses (5–10 min) and clear on save.
•	Split rarely used code (calculator, compare, request form) into lazy routes.
•	Car page loading state: skeleton with the final layout's dimensions instead of a one-line message.
Done when  Mobile LCP < 2.5 s, CLS < 0.1 on all four tested pages.
15. Form page indexing and default share image   ·   P1
Issue  /request-car has no description or canonical and its title is «Zed Capital». No page has og:image.
Verify  View the page head in DevTools.
Fix
•	/request-car: <meta name="robots" content="noindex, follow"> and remove it from the sitemap.
•	Default og:image (1200×630, logo) on every page that has no own image.
Done when  /request-car is noindex; every page has an og:image.
16. Brand landing pages   ·   P2
Issue  Brands have no own URL (/brands lists logos only). Brand + financing searches (e.g. «عروض تويوتا», «عروض سوزوكي») have no landing page.
Fix
•	New route /brands/{brand-slug}: H1, intro text editable in the admin, crawlable links to that brand's cars, title سيارات {brand} بالتقسيط 2026 · أقساط تبدأ من {min} ريال. Add to sitemap; link brand logos to it.
Done when  One indexable page per active brand, listed in the sitemap.
17. Global SEO fields in the admin   ·   P2
Issue  The homepage title/description are not editable from the admin.
Fix
•	Use the admin's global SEO settings (meta title/description) as the homepage values and site-wide fallback.
Done when  Changing the value in the admin changes the homepage head.
18. URL changes when a car is renamed   ·   P2
Issue  Best-practice requirement: car URLs must never break.
Fix
•	When a car's slug changes, store the old slug and return 301 from the old URL to the new one.
Done when  Renaming a test car keeps the old URL working via 301.
19. English version is not indexable   ·   P2
Issue  The language switch does not change the URL, and /en shows the 404 page. Google only ever sees Arabic.
Fix
•	Pending client decision: either language in the URL (/en/...) with hreflang and an English sitemap, or keep English un-indexed. Until then, no redirects on /en.
Done when  Per decision.
20. Conversion tracking   ·   P2
Issue  After page load the dataLayer holds only gtm.js, gtm.dom and gtm.load. No lead events.
Fix
•	Push after successful submission (not on click):
dataLayer.push({ event: 'lead_finance_calculator', car_id });
dataLayer.push({ event: 'lead_request_car', car_id });
dataLayer.push({ event: 'lead_contact' });
dataLayer.push({ event: 'click_whatsapp' });  // every WhatsApp link
dataLayer.push({ event: 'click_phone' });     // every tel: link
Note  No personal data (name, phone, email) in the dataLayer.
Done when  Each event appears once in GTM Preview after a real submission.
21. Clean-up   ·   P2
Fix
•	Remove lazysizes from the CDN if native loading="lazy" is used.
•	Accessibility (Lighthouse): icon buttons without accessible names, prohibited ARIA attributes, small touch targets on mobile.
Done when  Lighthouse accessibility ≥ 90 on mobile.
Implementation checklist
All changes in one list, in the order to implement them. Please reply with the item numbers once deployed and we will run the acceptance checks.
#	Change	Priority
1	Car cards and pagination are not crawlable links	P0
2	Sitemap and robots.txt point to another domain	P0
3	All 98 car pages share one title and description	P0
4	Untranslated keys and wrong brand name in titles	P0
5	No browser caching for static files	P0
6	Same empty HTML for every URL (meta tags only after JavaScript)	P1
7	Soft 404: any URL returns 200	P1
8	Footer links to missing pages: /terms and /privacy	P1
9	Canonical includes query string	P1
10	Homepage has 4 H1 tags	P1
11	Structured data	P1
12	Oversized images	P1
13	Font: 353 KB TTF	P1
14	Slow store API and late rendering	P1
15	Form page indexing and default share image	P1
16	Brand landing pages	P2
17	Global SEO fields in the admin	P2
18	URL changes when a car is renamed	P2
19	English version is not indexable	P2
20	Conversion tracking	P2
21	Clean-up	P2
Attachment: zad-onpage-plan-2026.xlsx — proposed title, description and H1 for every page (98 cars + static pages + brand pages).
