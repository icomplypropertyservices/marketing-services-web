# Deploy: LIVE on netlify.app (apex not attached yet)

Site: **icomply-marketing-services** (Netlify team `icomply`, account `icomplypropertyservices`)

| Item | Value |
|---|---|
| Live URL | https://icomply-marketing-services.netlify.app |
| `NETLIFY_SITE_ID` | `a888cb51-357f-4572-95a0-57e1b080b8f6` |
| Repo | https://github.com/icomplypropertyservices/marketing-services-web (private) |
| Brand domain | `icomplymarketingservices.co.uk`: **not registered or attached yet.** Namecheap purchase is planned, then follow [DOMAIN-CUTOVER.md](DOMAIN-CUTOVER.md) |
| Publish directory | `dist/` |
| Build | `php -d memory_limit=3G website/bin/static-export.php && php -d memory_limit=3G website/bin/check-static-export.php` |
| PHP | 8.3 (8.4 also works locally) |
| First deploy | `6ac4173f1dd657e446dbaac1`, 2026-10-05 23:31 CEST, 36 pages (preview) |
| AI+channels expand | `6ac41c39127348fb50b16147`, 2026-10-05 ~23:53 CEST, 62 pages (preview) |
| **Go-live wave 1** | see "Deploy log" below |

## Hosting decisions

1. **Static export, not a PHP runtime.** PHP renders every page into `dist/` at build time, and Netlify serves plain HTML/CSS/JS. This mirrors `professional-services-web`.
2. **Netlify, one site per brand.** Marketing Services does not share a site with Property Services, Professional Services or the internal marketing dashboard. **Never Vercel.**
3. **Production = the `*.netlify.app` root** until the apex is attached. Canonicals, sitemap and robots use `site_url` in `website/config/site.php` (currently the netlify.app host). Apex switch = one line (see DOMAIN-CUTOVER.md).
4. **Indexable.** `preview_only => false`: pages emit `<meta name="robots" content="index, follow">`, `robots.txt` allows all and lists `sitemap.xml`, and there is no `X-Robots-Tag` header.
5. **SSO / password wall off.** Public site.
6. **Forms = Netlify Forms.** Quote form `name="quote"` (honeypot `company_website`), success → `/thank-you/`. **Still to do:** Site → Forms → Notifications → email `icomplypropertyservices@gmail.com`.
7. **Contact routes** (`website/config/site.php`): phone + WhatsApp `07517 806082` / `wa.me/447517806082`, email `icomplypropertyservices@gmail.com`. Every page shows all three and has a floating WhatsApp bubble (bottom-right, above the sticky mobile CTA bar). `check-static-export` fails if any page is missing them.

## Nationwide rollout (waves)

- Data: `website/data/uk-places.csv`, 5000 UK places ranked (the XPLACE-P0 500 towns first). Built by `website/data/sources/build_uk_places.py` from GeoNames (CC BY 4.0) + ONS region lookups; see `website/data/sources/PROVENANCE.md`.
- `wave_towns` in `website/config/site.php` = how many places (in rank order) get a town hub + 38 service×town pages. Env `WAVE_TOWNS` overrides it for test builds.
- **Current wave: `wave_towns = 500` (wave 1). Shipped and frozen per Jack (2026-10-06): no further waves or packs for now.**
- Future waves (documented only, NOT scheduled): 1000, 1500 … 5000 = 9 more steps of +500 towns (+500 town hubs + 19,000 service×town pages each, ≈ +1.15 GB dist per step; full 5000 ≈ 11.5 GB / ~194k pages). To run one later: bump `wave_towns`, rebuild, run checks (word count, near-duplicate, bubble, sitemap < 50k per child), deploy. Note that wave 1 has no Northern Ireland towns (Belfast ranks after 500); they arrive in later waves.

### Wave 1 page counts (19,835 HTML pages)

| Type | Pages |
|---|---:|
| Core (home, hubs, company, legal, 404, thank-you) | 10 |
| Service hubs | 38 |
| Industries | 5 |
| Areas hub | 1 |
| Region / nation hubs | 12 |
| County hubs | 155 |
| Legacy areas (Cheshire, UK-wide) | 2 |
| Town hubs | 500 |
| Keyword hub + keyword pages (audience/topic) | 1 + 111 |
| Service × town | 19,000 |

Keyword coverage (`dist/keyword-map.csv`): CORE 3245/3245, XPLACE-P0 8500/8500, HANDOFF-AI 186/186 live.

## Quality gates (`website/bin/check-static-export.php`)

Titles/meta/h1/canonical on `site_url`, `index, follow`, CTAs, footer, FAQ + FAQPage schema, valid JSON-LD, internal links, WhatsApp bubble + phone/email/WA on every page, copy lint (no "same day", "within one working day", "Ltd", "from £N"), ≥800 words of unique main body text (boilerplate excluded), near-duplicate MinHash/LSH with exact Jaccard re-check (fail > 0.60), sitemap children < 50k URLs. Report: `dist/check-report.json`.

## Secrets (GitHub → Settings → Secrets and variables → Actions)

| Secret | Status |
|---|---|
| `NETLIFY_SITE_ID` | Set = `a888cb51-357f-4572-95a0-57e1b080b8f6` |
| `NETLIFY_AUTH_TOKEN` | **Not set yet.** Add a Netlify personal access token. Never commit it. |

If `NETLIFY_AUTH_TOKEN` is missing, workflows still build and run checks, then skip deploy with a warning.

## What GitHub Actions does

- `.github/workflows/netlify-deploy.yml`: on push to `main` → export + checks → **`netlify deploy --prod`** (site-id guard).
- `.github/workflows/netlify-preview.yml`: on pull requests → export + checks → draft deploy alias `pr-<number>` (no `--prod`).

## Manual deploy (logged-in Netlify CLI)

```bash
rm -rf dist
php -d memory_limit=3G website/bin/static-export.php && php -d memory_limit=3G website/bin/check-static-export.php
netlify deploy --dir=dist --site=a888cb51-357f-4572-95a0-57e1b080b8f6 --alias golive   # draft check
netlify deploy --prod --dir=dist --site=a888cb51-357f-4572-95a0-57e1b080b8f6           # live
```

## Still gated

- Do not attach `icomplymarketingservices.co.uk` / `www` or change DNS until the domain is bought; then follow [DOMAIN-CUTOVER.md](DOMAIN-CUTOVER.md).
- No new waves or packs until Jack says go.
- No invented stats, prices, testimonials, "Ltd" or timing promises.

## Deploy log
