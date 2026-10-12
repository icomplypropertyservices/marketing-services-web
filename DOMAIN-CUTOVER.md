# Domain cutover sheet — icomplymarketingservices.co.uk (Namecheap → Netlify)

Status 2026-10-06: **not attached.** The domain is not registered yet (NXDOMAIN on 5 Oct 2026). Jack is buying it on
Namecheap. Do **not** add the custom domain in Netlify or touch DNS until the domain is bought and Jack says attach.
The site is live meanwhile on https://icomply-marketing-services.netlify.app (canonical host until cutover).

| Item | Value |
|---|---|
| Domain to buy | `icomplymarketingservices.co.uk` (+ `www`) |
| Registrar / DNS | Namecheap (records stay at Namecheap: "external DNS" in Netlify terms) |
| Netlify site | `icomply-marketing-services` |
| SITE_ID | `a888cb51-357f-4572-95a0-57e1b080b8f6` |
| Netlify host | `icomply-marketing-services.netlify.app` |
| Admin | https://app.netlify.com/projects/icomply-marketing-services |
| Primary domain | apex `icomplymarketingservices.co.uk`; `www` redirects 301 to apex |

Pattern mirrors the Property main site (`icomplypropertyservices.co.uk`: apex is primary, `www.` 301s to apex, see
`icomply-main-web/DOMAINS.md`) and the Professional Services Namecheap prep sheet
(`icomply-ops/professional-services/seo/NAMECHEAP-NETLIFY-DNS-PREP-2026-10-06.md`). Property itself is hosted on
Vercel, so only the apex/www pattern is mirrored, not its records.

## 1. Namecheap Advanced DNS records

Values verified on 2026-10-06 against Netlify's docs:
https://docs.netlify.com/manage/domains/configure-domains/configure-external-dns/

Namecheap → Domain List → Manage → **Advanced DNS** → Host records. **First delete** Namecheap's default parking
records (the `CNAME www → parkingpage.namecheap.com` and the `URL Redirect @` record), otherwise they conflict.

Recommended (Netlify prefers ALIAS/ANAME/flattened CNAME for the apex; Namecheap offers an **ALIAS Record** type):

| Type | Host | Value | TTL |
|---|---|---|---|
| ALIAS Record | `@` | `apex-loadbalancer.netlify.com` | Automatic (or 5 min during cutover) |
| CNAME Record | `www` | `icomply-marketing-services.netlify.app` | Automatic |

Fallback, only if ALIAS is not available on the account:

| Type | Host | Value | TTL |
|---|---|---|---|
| A Record | `@` | `75.2.60.5` | Automatic |
| CNAME Record | `www` | `icomply-marketing-services.netlify.app` | Automatic |

Use one apex option, not both. Leave any MX/TXT records for email untouched. If Netlify's **Pending DNS verification**
panel shows different values (e.g. High-Performance Edge or a TXT verification token), use the panel's values.

## 2. Netlify: add the domain, www alias and SSL (only after Jack says attach)

UI: Site → **Domain management** → *Add a domain* → `icomplymarketingservices.co.uk` → *Verify* → *Add domain*.
Netlify adds `www` automatically and redirects it to the primary domain. Then *HTTPS* → *Verify DNS configuration* →
*Provision certificate* (Let's Encrypt; automatic once DNS resolves).

CLI equivalent (box CLI is logged in as icomplypropertyservices@gmail.com):

```bash
SITE=a888cb51-357f-4572-95a0-57e1b080b8f6
netlify api updateSite --data '{"site_id":"'$SITE'","body":{"custom_domain":"icomplymarketingservices.co.uk","domain_aliases":["www.icomplymarketingservices.co.uk"]}}'
# after DNS resolves to Netlify:
netlify api provisionSiteTLSCertificate --data '{"site_id":"'$SITE'"}'
netlify api getSite --data '{"site_id":"'$SITE'"}' | grep -E '"(custom_domain|domain_aliases|ssl|ssl_url|force_ssl)"'
```

Enable *Force HTTPS* if it is not on by default.

## 3. Switch canonicals + sitemap host, rebuild and redeploy

One line in `website/config/site.php`:

```php
'site_url' => 'https://icomplymarketingservices.co.uk',
```

(For a one-off test build without editing the file: `SITE_URL=https://icomplymarketingservices.co.uk php website/bin/static-export.php`.)

```bash
cd /workspace/marketing-services-web
php website/bin/static-export.php && php website/bin/check-static-export.php
netlify deploy --dir=dist --site=a888cb51-357f-4572-95a0-57e1b080b8f6 --alias apex-check   # spot-check first
netlify deploy --prod --dir=dist --site=a888cb51-357f-4572-95a0-57e1b080b8f6
git commit -am "chore: canonical host -> icomplymarketingservices.co.uk" && git push
```

The check script fails the build if any canonical is not on the configured `site_url`, so a half-switched build
cannot ship. Pushing to `main` also deploys `--prod` through GitHub Actions once `NETLIFY_AUTH_TOKEN` is set.

## 4. Post-cutover checks

```bash
D=https://icomplymarketingservices.co.uk
dig +short icomplymarketingservices.co.uk; dig +short www.icomplymarketingservices.co.uk
curl -sI $D/ | head -1                                   # 200
curl -sI https://www.icomplymarketingservices.co.uk/ | grep -iE '^(HTTP|location)'   # 301 -> https://icomplymarketingservices.co.uk/
curl -sI http://icomplymarketingservices.co.uk/ | grep -iE '^(HTTP|location)'        # 301 -> https
curl -s $D/services/seo/manchester/ | grep -o '<link rel="canonical"[^>]*>'          # canonical on apex
curl -s $D/robots.txt                                   # Sitemap: https://icomplymarketingservices.co.uk/sitemap.xml
curl -s $D/sitemap.xml | grep -o '<loc>[^<]*' | head -3 # child sitemaps on apex
curl -s $D/sitemap-services-towns-1.xml | grep -o '<loc>[^<]*' | head -3
curl -sI https://icomply-marketing-services.netlify.app/ | grep -iE "^(HTTP|location)"   # expect 301 to the apex once it is the primary domain
```

Then:

1. **Google Search Console:** add a *Domain* property for `icomplymarketingservices.co.uk` (DNS TXT record at
   Namecheap), submit `https://icomplymarketingservices.co.uk/sitemap.xml`, and spot-request indexing for `/`,
   `/services/`, `/areas/`.
2. Netlify Forms → notifications to `icomplypropertyservices@gmail.com` (still applies on the apex).
3. Update `DEPLOY.md` (live host line) and this file's status line.
