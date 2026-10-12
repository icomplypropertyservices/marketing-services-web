# DNS-ready pack — Namecheap tomorrow (Jack 2026-10-06)

**Do not attach apex tonight.** Prep only. Domains work is tomorrow on Namecheap.

## Target apex
- `icomplyprofessionalservices.co.uk` (and `www` if Jack wants both)

## Netlify site (already live on app subdomain)
- Site name: `icomply-professional-services`
- SITE_ID: `dc86da59-3989-4b57-bac1-d214b9ca2072`
- Current host: https://icomply-professional-services.netlify.app
- Admin: https://app.netlify.com/projects/icomply-professional-services

## Records to add at Namecheap (when Jack says go)
Netlify typically needs (confirm in Netlify → Domain management → Add custom domain → show DNS):

### Option A — Netlify DNS (nameservers)
Point Namecheap nameservers to the four Netlify DNS servers shown in the Netlify UI after adding the domain.

### Option B — records stay at Namecheap
| Type | Host | Value | Notes |
|------|------|-------|-------|
| A | `@` | `75.2.60.5` | Netlify load balancer (verify in Netlify UI if changed) |
| CNAME | `www` | `icomply-professional-services.netlify.app` | or Netlify-provided target |
| TXT | as prompted | Netlify verification token | only if Netlify asks |

After DNS propagates: Netlify provisions TLS. Leave apex **off** until Jack completes Namecheap + confirms.

## Checklist for tomorrow
- [ ] Jack adds domain in Namecheap (or confirms already owned)
- [ ] Jack (or Website) adds custom domain on Netlify site `dc86da59` — **only when Jack says attach**
- [ ] Apply A/CNAME or NS per Netlify panel
- [ ] Wait SSL issued
- [ ] Smoke: `/`, `/contact/`, one P0 keyword, one ×place URL
- [ ] Confirm contact email + WA still correct on apex

## Explicit non-goals tonight
- No Netlify domain attach
- No Namecheap changes from agents tonight
- Page ship continues on `*.netlify.app` only
