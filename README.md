# iComply Marketing Services

Website for **iComply Marketing Services**, brand domain `https://icomplymarketingservices.co.uk`, covering the full marketing mix for UK SMEs and B2B firms.

**Preview only.** Live host: https://icomply-marketing-services.netlify.app (Netlify site `a888cb51-357f-4572-95a0-57e1b080b8f6`). The apex domain is not attached. See [DEPLOY.md](DEPLOY.md).

Brand: navy `#0B1F3A`, orange `#FF6B00`, iComply tick mark (from the shared iComply brand kit).

## Local run

```bash
php website/bin/static-export.php      # writes dist/ (gitignored)
php website/bin/check-static-export.php
php -S 127.0.0.1:8080 -t dist
```

`SITE_URL` (default `https://icomplymarketingservices.co.uk`) sets canonicals and the sitemap host.

## Structure

| Path | Role |
|---|---|
| `website/config/site.php` | Brand, URLs, phone / WhatsApp / email, `preview_only` flag |
| `website/data/services.php` | 12 services: copy, deliverables, process, KPIs, FAQs |
| `website/data/industries.php` | 5 industry pages |
| `website/data/areas.php` | 8 area pages |
| `website/data/faqs.php` | Site-wide FAQs |
| `website/includes/layout.php` | Header, mega menu, CTA bands, FAQ accordion, quote form, footer |
| `website/includes/pages.php` | Page builders |
| `website/bin/static-export.php` | Builds `dist/`, `sitemap.xml`, `robots.txt` |
| `website/bin/check-static-export.php` | Fails the build on missing titles/meta/h1/canonicals, CTAs, footer columns, FAQ counts, thin service copy, invalid JSON-LD or broken internal links |

## Pages (36)

- Home, Services hub, 12 service pages: SEO, Google Ads, Meta Ads, Content marketing, Email campaigns, Social media, Branding, Websites & landing pages, Directories & listings, Reviews & reputation, Video, Strategy & audits
- Industries hub + Property & lettings, Trades & home services, Compliance & facilities, Professional services, B2B & SMEs
- Areas hub + Manchester, Stockport, Cheshire, Liverpool, Leeds, Birmingham, London, UK-wide
- About, How we work & pricing, FAQs, Contact (Netlify quote form), Privacy, Thank you, 404

Every page has get a quote / call / WhatsApp CTAs (plus a sticky mobile bar), an expandable FAQ accordion with FAQPage schema where FAQs appear, breadcrumbs, and the four-column footer (Services, Areas, Company, Contact).

## Adding a service, industry or area

Add an entry to the relevant file in `website/data/` and rebuild. Navigation, footer, sitemap and the services grid update automatically.
