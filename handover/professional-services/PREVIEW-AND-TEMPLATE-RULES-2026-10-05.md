# Preview + template rules (Jack 2026-10-05)

> **SUPERSEDED 2026-10-05 23:58 (Jack override via Professional Services):** NO preview gate. Each PS wave/sub-wave ships to PRODUCTION (Netlify --prod / primary host) as soon as it passes STRICT. Attach the apex domain only if it is already configured; never hold pages for it. STRICT quality, on-demand ×place (Functions/Edge) and 50k sitemap indexes all still apply.

## Preview only
- Professional Services stays on **PREVIEW** until Jack explicitly says go live.
- No production DNS cutover, no production Netlify promote, no public apex go-live without Jack’s “go”.
- Deploy to preview / draft branches / Netlify deploy-previews only.

## Per-keyword unique templates
- **Required:** each keyword (and keyword×place where applicable) gets a **unique template** — not one generic shared body with find/replace only.
- Distinct structure/sections/FAQ angles/schema where appropriate; still meet STRICT bar (≥800w, 3 images, full meta + schema).
- One generic template for all keywords = reject.

See also: `PAGE-TEMPLATE-STRICT-2026-10-05.md`
