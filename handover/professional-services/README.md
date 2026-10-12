# iComply Professional Services — ops scaffolds

**Brand:** iComply Professional Services  
**Domain:** https://icomplyprofessionalservices.co.uk  
**Status:** **PREVIEW only** — not production. No apex go-live until Jack explicitly says go.

This folder is the **ops + markdown page scaffold** tree for Professional Services SEO/Website work. It is separate from iComply Property Services.

## Do not

- Publish production DNS / Netlify promote / `publish_repository` from these drafts
- Invent fixed £ prices (always **POA**)
- Mix Property Services catalogues or SEO ops into this tree
- Ship thin shared keyword shells (hard reject)
- Generate keyword×place matrices until directed (use FULL UK allowlist when you do)

## Authoritative locks (read first)

- `PAGE-TEMPLATE-STRICT-2026-10-05.md`
- `PREVIEW-AND-TEMPLATE-RULES-2026-10-05.md`
- `seo/PAGE-QUALITY-BAR-STRICT-2026-10-05.md`
- `seo/META-TITLE-DESCRIPTION-RULES-2026-10-05.md`
- `SCOPE-EXPAND-2026-10-05.md`
- Areas: `areas/AREA-LOCK-FULL-UK-2026-10-05.md` (**34,235** places) + `UK-FULL-PLACES-2026-10-05.*`
- Keywords: `keywords/PS-KEYWORDS-P0.txt` (+ tranche locks under `keywords/`)

## Layout

| Path | Role |
|------|------|
| `pages/` | Preview markdown for home, about, contact, areas, hubs, keywords |
| `pages/hubs/` | Vertical category hubs |
| `pages/keywords/` | One unique file per keyword slug |
| `pages/TEMPLATE-KEYWORD-UNIQUE.md` | Rule doc for per-keyword templates |
| `areas/` | Place allowlists (FULL UK primary) |
| `keywords/` | SEO keyword locks/lists |
| `seo/` | Quality bar, meta rules, scaffold status, reject log |
| `sitemaps/` | Reserved for regenerated sitemaps on ship |

## Parallel PHP repo

CloudAgent **`bc-254f6f57`** builds the PHP + Netlify static-export site separately. These markdown files are content/IA scaffolds for preview and SEO, not the live export by themselves.

## Quality bar (every public page)

- ≥800 words unique **body** prose
- ≥3 images with alt text
- Full meta + OG + canonical + JSON-LD
- FAQs on hubs (and keyword pages)
- Preview frontmatter/header on drafts

## Contact for programme status

See `seo/SCAFFOLD-STATUS.md` for created paths and awaiting items.
