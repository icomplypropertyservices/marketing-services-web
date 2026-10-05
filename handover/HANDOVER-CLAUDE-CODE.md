# Handover to Claude Code — iComply (2026-10-06)

## Sites and hosts
| Site | Repo (org icomplypropertyservices) | Host | URL |
|---|---|---|---|
| Property | main-web | Netlify prod | https://icomplypropertyservices.co.uk |
| Professional Services (PS) | professional-services-web | Netlify SITE_ID dc86da59-3989-4b57-bac1-d214b9ca2072 | https://icomply-professional-services.netlify.app (apex icomplyprofessionalservices.co.uk pending) |
| Marketing Services | marketing-services-web | Netlify SITE_ID a888cb51-357f-4572-95a0-57e1b080b8f6 | https://icomply-marketing-services.netlify.app (apex icomplymarketingservices.co.uk pending) |

## Build / deploy (from netlify.toml; none of the repos has a package.json)
All three: PHP 8.3, publish dir `dist`.
- main-web: `php website/bin/static-export.php --keyword-towns=priority && php website/bin/check-static-export.php`. SITE_URL=https://icomplypropertyservices.co.uk. Plugin `./netlify/plugins/lock-sitemap`. www→apex redirect. Prod deploys go through the GitHub Actions "Deploy to Netlify" workflow, which is stalled on Actions minutes/billing.
- professional-services-web: `php website/bin/static-export.php && php website/bin/check-static-export.php`. SITE_URL=https://icomplyprofessionalservices.co.uk, XPLACE_TOWN_LIMIT=50. Netlify functions in `netlify/functions/` (xplace-data).
- marketing-services-web: `php website/bin/static-export.php && php website/bin/check-static-export.php`. Deploys come from the box Netlify CLI (`netlify deploy --prod`) because there's no NETLIFY_AUTH_TOKEN repo secret yet. Cutover steps are in DOMAIN-CUTOVER.md in that repo.

## Standing rules
- SEO first: every page has 800+ words, 5+ FAQs, 3 images, full meta and unique content.
- WhatsApp corner bubble + contact block on every page: WA 07517 806082 / https://wa.me/447517806082.
- Emails: Property uses info@icomplypropertyservices.co.uk. PS and Marketing use icomplypropertyservices@gmail.com.
- PS keywords target end-client demand for the professions, with iComply as the middleman. Never use web-design terms.
- No technical or scaffold text on public pages.
- AOV + barriers carry the deepest margins and come first. That includes manual barriers and width/height restrictions.
- Never put work on hold. If something is blocked, name the blocker, who clears it, and the next action.
- Branded HTML emails with vCard.
- Gas wording: "carried out by Gas Safe registered engineers" only.

## Key notes
- Marketing and PS apex domains get bought on Namecheap on 2026-10-06. Point their DNS at Netlify (see professional-services/DNS-READY-NAMECHEAP-2026-10-06.md and Marketing's DOMAIN-CUTOVER.md), attach each domain + SSL in Netlify, switch SITE_URL, then turn noindex off.
- PS has 700 P0 pages live that failed the uniqueness check (median 0.07 against a 0.30 target). They need rewriting.
- The PS on-demand keyword × place renderer isn't finished (branch feat/xplace-on-demand → handover/professional-services-web-2026-10-06).
- Marketing nationwide wave 1 (PR #2, go-live-nationwide) may be only partly deployed. Check the live site.
- Property packs that are locked but not shipped: fencing, perimeter, gates, fire dampers, AHU, BMS, AC, facial/ANPR, HMO, shop fitting and gap-fill. Locks are in handover/seo/.

## Open PRs and branches (2026-10-06)
### main-web
Open PRs: #127 cursor/aov-barriers-deep-p0 (AOV + Barriers DEEP P0); #126 cursor/networking-it-p0-dual-ring; #125 cursor/whatsapp-float-bubble; #120 cursor/building-services-dual-269-p0-1d17; #119 cursor/manchester-electrical-p0-640e; #116 cursor/security-dual-269-p0-2a17; #114 cursor/nationwide-3line-jobtypes-w1a-3688; plus "Handover docs for Claude Code" (handover/docs-2026-10-06).
Handover WIP branches (local uncommitted/unpushed work, snapshotted): handover/{icomply-main-web, main-web-net-p1w1, main-web-pr109, pr110, pr111, pr112, pr113, pr116, pr117, pr118, pr119, pr120, main-web-q2-hmo, main-web-wa, qm-ops-app-gate-pr117-rebase, site-404-fixer-main-web, site-404-fixer-main-web-b}-2026-10-06. Many of the prNNN snapshots hold the same local edit to netlify/edge-functions/sitemap.js. main-web-q2-hmo (HMO pack WIP) and main-web-net-p1w1 (networking P1 wave-1 scripts) are the substantive ones.
The repo has ~130 other `cursor/*` branches. Most are old or merged. Run `gh api repos/icomplypropertyservices/main-web/branches --paginate -q '.[].name'` for the full list.
### professional-services-web
Open PRs: "Handover docs for Claude Code". Branches: main, feat/p0-end-client-keywords-contact-wa, fix/slim-ci-check-xplace-0, handover/professional-services-web-2026-10-06 (xplace renderer WIP + UK places data + P1/P2 keyword lists).
### marketing-services-web
Open PRs: #2 go-live-nationwide (wave 1: 500 towns, 19,835 pages, WA bubble, domain cutover sheet); #1 feat/ai-channels-services-seo; "Handover docs for Claude Code". Branches also include handover/marketing-services-web-2026-10-06 (uncommitted static-export/layout edits on top of go-live-nationwide).

## Remaining work (from MASTER-TASK-LIST.md, 2026-10-06 01:23)
### Property
- Merge the WhatsApp bubble sitewide (#125), networking/IT P0 (#126, with P1 wave-1 stacked on it) and AOV + Barriers DEEP (#127) after SEO scoring and quality checks.
- Ship the DEEP packs: fencing (P0 108), perimeter (P0 80), electric gates, fire dampers, AHU, BMS, AC, facial recognition + ANPR, HMO (P0 185), shop fitting (P0 75), then gap-fill (EV, solar, heat pumps, smart home, nurse call, water hygiene, damp/mould, roofing/glazing, auto doors/shutters, CCTV cloud, access, pest, drainage), then W2.
- Burnley + 50mi dual ring (#112) and CLOSE-15 hub restore are blocked: the prod deploy is stalled on GitHub Actions minutes. Re-run Deploy to Netlify once minutes are restored.
- Merge the open scorecard PRs: #114, then #125, then rebase #116 → #119. #120 needs rework.
- Upgrade thin pages so each scores 100%. Refresh GSC sitemaps after each pack reaches prod.
- AI landings on Property (80, P0 15). `ai-property-services` is a full page; the other slugs are enquire/coming-soon pages with no live claims.
- Ship the locked follow-on waves (W1b, keywords P1, electrical P1, security P1, building P1, fire × TOP5000) and an SEO scorecard for every pack PR.
### Professional Services
- Rewrite the P0 pages for uniqueness (median 0.07 → ≥0.30), then re-score with professional-services/seo/p0-prod-strict-2026-10-06/.
- Get the on-demand full-UK keyword × place renderer (108,574 kw × 34,235 places) to prod. Enable the W1 sub-waves (P0 × TOP5000) and run the strict sampler on each.
- Then Wave 2 (the remaining places) and the P1/P2 keyword tiers.
- Attach the apex domain after the Namecheap purchase, then turn noindex off.
### Marketing Services
- Wave 1 (~19.8k pages) to prod. Then scale to 1k, 2.5k and 5k towns. Watch disk space: about 12 GB needed.
- Contact + WA bubble sitewide and noindex off both ship with wave 1.
- Jack needs to add the NETLIFY_AUTH_TOKEN repo secret.
- Attach the apex domain (DOMAIN-CUTOVER.md).
### Ops
Auto review request after payment; unify branded docs/review card; Taylor Telegram restore + Bloom brief; TELEGRAM_ALLOWED_USER_IDS for the Jack bot; free directories for link building; keep Contractor Finder / Quote Manager / Work Pipeline running.

## Material not copied (box paths only)
- /workspace/icomply-ops/seo/ (22 MB in total, mostly keyword .txt lists). Only the *LOCK*.md files and PROPERTY-SEO-LOCK-INDEX are copied.
- /workspace/icomply-ops/professional-services/{seo,keywords,areas,packs,pages,sitemaps} and /workspace/icomply-ops/email-templates/ (full templates).
