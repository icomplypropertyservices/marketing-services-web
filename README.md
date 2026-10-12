# iComply Marketing Services

Website for **iComply Marketing Services**, brand domain `https://icomplymarketingservices.co.uk`, covering the full marketing mix for UK SMEs and B2B firms.

**LIVE (indexable)** at https://icomply-marketing-services.netlify.app (Netlify site `a888cb51-357f-4572-95a0-57e1b080b8f6`). Wave 1 = 500 towns, 19,835 pages. The apex domain is not attached yet; see [DEPLOY.md](DEPLOY.md) and [DOMAIN-CUTOVER.md](DOMAIN-CUTOVER.md).

Contact on every page: phone/WhatsApp 07517 806082, email icomplypropertyservices@gmail.com, plus a floating WhatsApp bubble.

Brand: navy `#0B1F3A`, orange `#FF6B00`, iComply tick mark (from the shared iComply brand kit).

## Local run

```bash
php website/bin/static-export.php      # writes dist/ (gitignored)
php website/bin/check-static-export.php
php -S 127.0.0.1:8080 -t dist
```

`site_url` in `website/config/site.php` (currently `https://icomply-marketing-services.netlify.app`) sets canonicals and the sitemap host; env `SITE_URL` overrides it. `wave_towns` (500) sets the number of towns built; env `WAVE_TOWNS` overrides it for test builds.

## Structure

| Path | Role |
|---|---|
| `website/config/site.php` | Brand, `site_url`, phone / WhatsApp / email, `preview_only`, `wave_towns` |
| `website/data/uk-places.csv` | 5000 ranked UK places (GeoNames CC BY 4.0 + ONS regions), 16 nearest neighbours each |
| `website/data/regions.php`, `audiences.php`, `topics.php` | Region modules, 13 audiences, 12 AI topics |
| `website/includes/local.php` | Wave selection; service×town, town, county, region, keyword page builders |
| `website/data/services.php` + `services_extra.php` | 38 services (core + AI + channels): copy, deliverables, process, KPIs, FAQs |
| `website/data/industries.php` | 5 industry pages |
| `website/data/areas.php` | 8 area pages |
| `website/data/faqs.php` | Site-wide FAQs |
| `website/includes/layout.php` | Header, mega menu, CTA bands, FAQ accordion, quote form, footer |
| `website/includes/pages.php` | Page builders |
| `website/bin/static-export.php` | Builds `dist/`, sitemap index + child sitemaps, `robots.txt`, `keyword-map.csv`, `export-report.json` |
| `website/bin/check-static-export.php` | Fails the build on missing titles/meta/h1/canonicals, CTAs, footer columns, FAQ counts, thin service copy, invalid JSON-LD broken internal links, missing WhatsApp bubble/contacts, copy-rule breaches, <800 unique body words or near-duplicates (>0.60 Jaccard) |

## Pages (wave 1: 19,835)

Nationwide: 12 region hubs, 155 county hubs, 500 town hubs, 19,000 service×town pages (`/services/{svc}/{town}/`), 111 keyword pages (`/marketing/{stem}/`). Core:

- Home, Services hub, **38 service pages**: core marketing mix + AI (chatbots, receptionist, content, SEO/GEO, ads optimisation, review replies) + LinkedIn/TikTok, YouTube, programmatic, retargeting, GBP, CRO, automation/CRM, SMS, photography, drone, signage/wraps, influencer, funnels, ASO, podcast ads, franchise SEO, tender packs, employer branding, ad networking, IT support
- Industries hub + Property & lettings, Trades & home services, Compliance & facilities, Professional services, B2B & SMEs
- Areas hub + Manchester, Stockport, Cheshire, Liverpool, Leeds, Birmingham, London, UK-wide
- About, How we work & pricing, FAQs, Contact (Netlify quote form), Privacy, Thank you, 404

Every page has get a quote / call / WhatsApp CTAs (plus a sticky mobile bar), an expandable FAQ accordion with FAQPage schema where FAQs appear, breadcrumbs, and the four-column footer (Services, Areas, Company, Contact).

## Adding a service, industry or area

Add an entry to the relevant file in `website/data/` and rebuild. Navigation, footer, sitemap and the services grid update automatically.

## SEO / keywords

See [SEO-KEYWORD-SCALE-PLAN.md](SEO-KEYWORD-SCALE-PLAN.md) and `website/data/keywords/` (CORE / ALL / ×place P0 lists). Wave 1 (500 towns) is live; further waves are documented but frozen (see DEPLOY.md).
