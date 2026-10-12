# Deploy — preview only

Site: **icomply-marketing-services** (Netlify team `icomply`, account `icomplypropertyservices`)

| Item | Value |
|---|---|
| Preview URL | https://icomply-marketing-services.netlify.app |
| `NETLIFY_SITE_ID` | `a888cb51-357f-4572-95a0-57e1b080b8f6` |
| Repo | https://github.com/icomplypropertyservices/marketing-services-web (private) |
| Brand domain (NOT attached) | `icomplymarketingservices.co.uk` |
| Publish directory | `dist/` |
| Build | `php website/bin/static-export.php && php website/bin/check-static-export.php` |
| PHP | 8.3 (8.4 also works locally) |
| First deploy | `6ac4173f1dd657e446dbaac1`, 2026-10-05 23:31 CEST, 36 pages, all HTTP 200 |
| AI+channels expand | `6ac41c39127348fb50b16147`, 2026-10-05 ~23:53 CEST, 62 pages / 38 services, all spot-check HTTP 200 |


## Hosting decisions

1. **Static export, not a PHP runtime.** PHP renders all pages at build time into `dist/`. Netlify serves plain HTML/CSS/JS, so pages are fast, cache well and have nothing server-side to patch. This mirrors `professional-services-web`.
2. **Netlify, separate site per brand.** Marketing Services has its own Netlify site and repo. It does not share a site with Property Services (`icomply-main-web`), Professional Services or the internal marketing dashboard (`icomply-marketing-dash`, which is on `marketing.icomplypropertyservices.co.uk`).
3. **Preview host = `*.netlify.app` root.** The first build was published to the site's production slot so that `https://icomply-marketing-services.netlify.app` returns 200. **No custom domain is attached**, so production here means the netlify.app hostname only. After that, CI only updates aliases (`preview--…`, `pr-N--…`) and never runs `--prod`.
4. **Not indexed while in preview.** Every page has `<meta name="robots" content="noindex, nofollow">`, Netlify sends `X-Robots-Tag: noindex, nofollow`, and `robots.txt` disallows all. Canonicals already point at `https://icomplymarketingservices.co.uk` so nothing changes at cutover except the flags below.
5. **SSO / password wall off.** `sso_login` is `false` and there is no site password, so Jack and clients can open the preview without a Netlify login.
6. **Forms = Netlify Forms.** The quote form (`name="quote"`, honeypot `company_website`) is detected by Netlify and submissions land under Site → Forms. Successful submissions go to `/thank-you/`. **Set up email notifications** (Forms → Notifications) to the right inbox before go-live.
7. **Contact routes.** Phone and WhatsApp use the iComply group line `07517 806082` / `wa.me/447517806082`. Email is `info@icomplypropertyservices.co.uk` until a `@icomplymarketingservices.co.uk` mailbox exists. Change these in `website/config/site.php`.

## Secrets (GitHub → Settings → Secrets and variables → Actions)

| Secret | Status |
|---|---|
| `NETLIFY_SITE_ID` | Set = `a888cb51-357f-4572-95a0-57e1b080b8f6` |
| `NETLIFY_AUTH_TOKEN` | **Not set yet.** Add a Netlify personal access token (User settings → Applications → Personal access tokens). The token is never committed. |

Until `NETLIFY_AUTH_TOKEN` is set, both workflows still build and run checks, then skip the deploy step with a warning instead of failing.

## What GitHub Actions does

- `.github/workflows/netlify-deploy.yml`: on push to `main`, export + checks, then a draft deploy with alias `preview`. No `--prod`.
- `.github/workflows/netlify-preview.yml`: on pull requests, export + checks, then a draft deploy with alias `pr-<number>`. No `--prod`.

Both refuse to deploy unless `NETLIFY_SITE_ID` is exactly `a888cb51-357f-4572-95a0-57e1b080b8f6`.

Alias URLs:

- `https://preview--icomply-marketing-services.netlify.app`
- `https://pr-N--icomply-marketing-services.netlify.app`

## Manual deploy (from a logged-in Netlify CLI)

```bash
php website/bin/static-export.php && php website/bin/check-static-export.php
# alias only (safe default)
netlify deploy --dir=dist --site=a888cb51-357f-4572-95a0-57e1b080b8f6 --alias preview
# refresh the netlify.app root (still no custom domain attached)
netlify deploy --prod --dir=dist --site=a888cb51-357f-4572-95a0-57e1b080b8f6
```

## Not in scope until Jack says go

- Do not attach `icomplymarketingservices.co.uk` or `www.icomplymarketingservices.co.uk` to the Netlify site.
- Do not change DNS.
- Do not remove noindex.

## Go-live checklist (Jack-go only)

1. `website/config/site.php`: set `'preview_only' => false` (removes the preview bar, switches robots meta to `index, follow`, writes a normal `robots.txt` with the sitemap).
2. `netlify.toml`: remove the `X-Robots-Tag` and `X-Preview-Site` headers.
3. Confirm the contact email and Netlify Forms notifications.
4. Netlify → Domain management: add `icomplymarketingservices.co.uk` as the primary domain and `www` as an alias (redirects to apex with a 301). Point DNS as Netlify instructs, then wait for the SSL certificate.
5. Deploy with `--prod`, submit `sitemap.xml` in Google Search Console, and create the Google Business Profile / GA4 property.
