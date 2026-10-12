# Security — KEYWORDS + JOBS LOCK (DUAL 269) — 2026-10-05

**Jack (via Grok Bot):** ~200 popular **security** intents (CCTV, access control, burglar/intruder alarms, door entry, ANPR, monitoring, install/repair/service/near-me/emergency/commercial/domestic). Lock here; Website Team ships. **Separate from** AOV / barrier / fire **nationwide** packs and from Manchester electrical.

## Town / area set = Dual 269 FULL LOCAL (LOCKED)

**Keyword×area + job×area = dual 50mi union (Manchester ∪ Burnley) = 269 towns.** Same FULL LOCAL local-services ring as `DUAL-RING-DEPTH-FULL-LOCAL-2026-10-05.md`.

| Set | Use for this pack? |
|-----|--------------------|
| **Dual 50mi union (269)** MCR∪Burnley | **YES — exclusive town matrix** |
| GM-core 60 only | NO — subset inside 269; do not shrink this pack to GM-core |
| UK TOP 5000 / mainland ≥10k | **NO** — nationwide AOV/barrier/fire only |

- Allowlist: `AREA-HUB-ALLOWLIST-50MI-DUAL-2026-10-05.csv` (**269**)
- Depth lock: `DUAL-RING-DEPTH-FULL-LOCAL-2026-10-05.md`
- NAP base: Stockport / Offerton (see copy constraints)
- Primary hubs: Manchester + Burnley ring coverage; Stockport NAP

## Counts

| Metric | Count |
|--------|------:|
| Unique commercial intents | **200** |
| P0 (highest intent) | **67** |
| P1 | **133** |
| P2 | **0** |
| P0 ship-wave file | **67** |
| Existing (in keywords/jobs sources and/or live page) | **185** |
| Create (new hubs needed) | **15** |
| kind=both (keyword + job surfaces) | **188** |
| kind=keyword only | **12** |
| kind=job only | **0** |
| Town matrix | **269** (dual ring) |

## Ranking method

1. **Commercial / local search intent first** — near-me, emergency / same-day / 24-hour, install, repair, service/maintenance, monitoring, commercial / domestic.
2. **Segment heads** — CCTV, access control, burglar/intruder alarms, door entry, intercom, ANPR, security systems.
3. **Strong follow-ons (P1)** — brand (Paxton, Hikvision, Dahua, Ajax, Texecom…), IP/wireless/HD/4K, NVR/DVR, maglock/fob/biometric, video door entry, grade 2/3, PD 6662, multi-site, landlord/office/shop/warehouse.
4. **Long-tail / cost (P2)** — cost/price (POA only) only if retained in this cut; depth otherwise deferred.
5. Sources merged then de-duplicated: `keywords.json` (cctv, access-control, door-entry, intercoms, intruder-alarm), `access-control-keywords.json`, `cctv-jobs.json`, `security-systems-jobs.json`, `job-types-access-control.json`, live `/pages/keywords/*`. High-intent gaps filled as **create**.

## Depth (what WT ships)

1. **Keyword hubs** `/pages/keywords/{slug}` — kind `keyword` or `both`
2. **Job hubs** `/pages/jobs/{slug}` — kind `job` or `both`
3. **Keyword×area** `/pages/keywords/{slug}/{town}` — **dual 269 only**
4. **Job×area** `/pages/jobs/{slug}/{town}` — **dual 269 only**
5. **Not** TOP5000 ×town for this pack
6. Service hubs remain `/pages/services/cctv`, `access-control`, and related security service pages; service×town already on dual-269 FULL LOCAL rules

## Recommended WT ship order

1. **P0 hubs** — `SECURITY-DUAL-RING-P0.txt` / `SECURITY-KEYWORDS-JOBS-P0.txt` (67 slugs): create missing hubs first, refresh existing
2. **P0 × dual 269** — keyword×area + job×area
3. **P1 hubs** — remaining create + existing refresh
4. **P1 × dual 269** — phased by crawl budget
5. **P2 hubs + ×area** — last (if any)
6. Every page must meet `PAGE-QUALITY-BAR-KEYWORD-JOB-2026-10-05.md` (≥800 words, FAQs, 3 images, full metadata; SEO Manager pre-ship score + post-ship re-score; sitemap sync after every ship)

## Files in this lock

| File | Role |
|------|------|
| `SECURITY-DUAL-RING-LOCK-2026-10-05.md` | This lock (dual-ring name) |
| `SECURITY-KEYWORDS-JOBS-LOCK-2026-10-05.md` | **Alias — same content** (WT preferred name) |
| `SECURITY-DUAL-RING-INTENTS-ALL.txt` | 200 slugs, popularity rank order |
| `SECURITY-KEYWORDS-JOBS-ALL.txt` | Alias — same ALL list |
| `SECURITY-DUAL-RING-INTENTS.csv` | rank,slug,kind,status,intent_tier,notes |
| `SECURITY-KEYWORDS-JOBS.csv` | Alias — same CSV |
| `SECURITY-DUAL-RING-P0.txt` | Top ship wave (67) |
| `SECURITY-KEYWORDS-JOBS-P0.txt` | Alias — same P0 |

## Page quality bar (STANDING)

**All hubs and ×area pages in this pack** (and all future keyword/job pages) must meet:

→ **`PAGE-QUALITY-BAR-KEYWORD-JOB-2026-10-05.md`** (also `CONTINUOUS-SITE-QUALITY-AUDIT-LOCK-2026-10-05.md`)

- Min **800 words** body · **FAQs** required · **3 images** · complete metadata (title/meta/OG/canonical)
- SEO Manager **scan/score before ship** and **re-score after ship**
- Sitemap sync after every ship
- Applies to **all existing pages** site-wide as continuous audit, not only this pack

## Copy / compliance constraints

- **POA only** — never fixed prices; cost/price/quote/how-much slugs stay POA
- **NAP** (when shown): `17 Woodlands Park Road, Offerton, Stockport, Cheshire SK2 5DE`
- **No fake NSI / SSAIB / Police URN / ARC badges** — do not invent scheme memberships; monitoring / police-response / certification hubs use honest “search intent / ask about monitoring & accreditation” copy only
- **Honest monitoring language** — describe remote viewing, app alerts, or third-party monitoring options factually; never claim 24/7 ARC police response unless contractually true
- **Grade / PD 6662** — describe grading practice honestly; do not invent graded-system certificates iComply does not hold
- **Separate from nationwide packs** — do not add these intents into `NATIONWIDE-3LINE-*` or fire-alarm-installer ×TOP5000
- Internal links: `/pages/services/cctv`, `/pages/services/access-control`, related security keywords/jobs, `/pages/areas/{town}` for dual-269 towns
- Anti-thin: unique intro per hub; ×area gets town-local paragraph + parent links; meet quality bar

## Collision watches

- **NATIONWIDE-3LINE** (AOV / barriers / fire) — different programme; different town set (TOP5000)
- **FIRE-ALARM-INSTALLER-KEYWORD-LOCK** — nationwide fire family; leave alone (do not pull fire-alarm slugs into this security pack)
- **MANCHESTER-ELECTRICAL** — GM-core 60 electrical; leave alone
- Place-locked `*-manchester` / `*-burnley` single-town slugs — prefer generic hub + ×area on dual 269 rather than hard-coded place hubs in this list
- Barriers/ANPR car-park barrier nationwide — barrier *gate* products stay in nationwide barrier pack; ANPR *camera/CCTV* intents stay here

## Sources

- `/workspace/main-web-pr110/website/data/keywords.json` (services: cctv, access-control, door-entry, intercoms, intruder-alarm)
- `/workspace/main-web-pr110/website/data/access-control-keywords.json`
- `/workspace/main-web-pr110/website/data/cctv-jobs.json`
- `/workspace/main-web-pr110/website/data/security-systems-jobs.json`
- `/workspace/main-web-pr110/website/data/job-types-access-control.json`
- Live `/pages/keywords/*` security-family PHP pages
- Town set: `AREA-HUB-ALLOWLIST-50MI-DUAL-2026-10-05.*` + `DUAL-RING-DEPTH-FULL-LOCAL-2026-10-05.md`
- Quality bar: `PAGE-QUALITY-BAR-KEYWORD-JOB-2026-10-05.md`
