# Dual-ring building services — KEYWORDS + JOBS LOCK — 2026-10-05

**Jack order (2026-10-05 via Grok Bot):** ~**3000** building / property-services commercial intents × **all Manchester + Burnley dual-ring areas**. Examples called out: plasterers, dryliners, garden wall building company, fire door installer, boiler replacement, boiler repair, gas certs / CP12. Expand to popular building/property keywords iComply can credibly offer or subcontract with correct wording. Lock here; Website Team ships. **Do not** merge into `NATIONWIDE-3LINE-*`, `MANCHESTER-ELECTRICAL-*`, or `UK-TOP5000-*`.

**Alias filenames (WT preference):** same content also written as `BUILDING-MCR-BURNLEY-*` (see files table).

## Town / area set = dual 269 (LOCKED)

**Keyword×area + job×area = FULL LOCAL on the dual 50mi union (Manchester ∪ Burnley) = 269 towns.**

| Set | Use for this pack? |
|-----|--------------------|
| **Dual 50mi union (269)** from `AREA-HUB-ALLOWLIST-50MI-DUAL-2026-10-05.csv` | **YES — exclusive town matrix** |
| GM-core 60 | **NO** — that is Manchester-electrical only |
| UK TOP 5000 / mainland ≥10k | **NO** — nationwide AOV/barrier/fire only |

- Source: `AREA-HUB-ALLOWLIST-50MI-DUAL-2026-10-05.csv` (**269** towns)
- Primary hubs: Manchester + Burnley rings; NAP base Stockport / Offerton
- Depth rule (from `DUAL-RING-DEPTH-FULL-LOCAL-2026-10-05.md`): keyword×town + job×town **ON** for every town in the 269

## Counts

| Metric | Count |
|--------|------:|
| Unique commercial intents | **3000** |
| P0 (highest intent / ship wave) | **100** |
| P1 | **1904** |
| P2 | **996** |
| Existing (in `keywords.json` and/or building/gas/plumbing/fabric job JSON) | **922** |
| Create (new hubs needed) | **2078** |
| kind=both (keyword + job surfaces) | **3000** |
| kind=keyword only | **0** |
| kind=job only | **0** |
| Town set (dual ring) | **269** |

## Ranking method

1. **P0 — commercial / local search heads first** — plasterers, dryliners, bricklayers, garden-wall building company, fire-door installer, boiler replacement/repair/install/service, gas engineer / Gas Safe search, CP12 / landlord gas safety, builders, joiners, damp proofing, rendering, plumbers, kitchens/bathrooms, roofing-adjacent property maintenance.
2. **P1 — strong follow-ons** — existing website building/gas/plumbing/fabric intents, urgency (emergency / 24h / same-day), core certificates, symptom searches (boiler/damp/leak/mould/fire-door).
3. **P2 — long-tail depth** — trade×modifier, room/property matrices, brand/product, cost/quote (POA), hub place phrases, maintenance tasks, how-much forms.
4. Sources merged then de-duplicated: `keywords.json` (building/gas/plumbing/fire-doors/heating/joinery/… families), `job-types-building.json`, `job-types-gas.json`, `job-types-plumbing.json`, `job-packs/building-fabric.json`. High-intent gaps filled as **create**.

## Depth (what WT ships)

1. **Keyword hubs** `/pages/keywords/{slug}` — kind `keyword` or `both`
2. **Job hubs** `/pages/jobs/{slug}` — kind `job` or `both`
3. **Keyword×area** `/pages/keywords/{slug}/{town}` — **dual 269 only**
4. **Job×area** `/pages/jobs/{slug}/{town}` — **dual 269 only**
5. **Not** TOP5000 ×town, **not** GM-core-60-only (that pack is electrical)
6. Theoretical surface ≈ 3000 × 269 = **807,000** keyword/job ×town URLs — ship in waves; do not dump all into sitemap at once

## Recommended WT ship order

1. **P0 hubs** — `DUAL-RING-BUILDING-SERVICES-P0.txt` / `BUILDING-MCR-BURNLEY-P0.txt` (100 slugs): create missing first, refresh existing
2. **P0 × dual 269** — keyword×area + job×area (Manchester + Burnley rings; Stockport NAP)
3. **P1 hubs** — remaining create + existing refresh
4. **P1 × dual 269** — phased by crawl budget
5. **P2 hubs + ×area** — last

## Files in this lock

| File | Role |
|------|------|
| `DUAL-RING-BUILDING-SERVICES-LOCK-2026-10-05.md` | This lock (primary) |
| `BUILDING-MCR-BURNLEY-LOCK-2026-10-05.md` | Alias lock (same content) |
| `DUAL-RING-BUILDING-SERVICES-ALL.txt` | 3000 slugs, popularity rank order |
| `BUILDING-MCR-BURNLEY-ALL.txt` | Alias of ALL |
| `DUAL-RING-BUILDING-SERVICES.csv` | rank,slug,kind,status,intent_tier,notes |
| `BUILDING-MCR-BURNLEY.csv` | Alias of CSV |
| `DUAL-RING-BUILDING-SERVICES-P0.txt` | Top ship wave (100) |
| `BUILDING-MCR-BURNLEY-P0.txt` | Alias of P0 |

## Copy / compliance constraints

- **POA only** — never fixed prices; cost/price/quote/how-much slugs stay POA
- **NAP** (when shown): `17 Woodlands Park Road, Offerton, Stockport, Cheshire SK2 5DE`
- **Gas Safe wording** — gas / boiler / CP12 / landlord gas pages: work is **“carried out by Gas Safe registered engineers”**. **Never claim iComply is Gas Safe registered.**
- **Fire doors** — honest compliance / inspection / installation language; no invented scheme badges
- **Subcontract framing** — where iComply subcontracts a trade, use accurate “arranged / carried out by qualified …” wording
- **Separate from** `NATIONWIDE-3LINE-*` (AOV/barrier/fire × TOP5000), `MANCHESTER-ELECTRICAL-*` (GM-core 60), `FIRE-ALARM-INSTALLER-*`, `UK-TOP5000-*`
- Internal links: related building/gas/plumbing/fire-door keywords/jobs, `/pages/areas/{town}` for dual 269; Manchester + Burnley as primary hub cross-links
- Anti-thin: unique intro per hub; ×area gets town-local paragraph + parent links

## Collision watches

- **NATIONWIDE-3LINE** (AOV / barriers / fire) — different programme; different town set (TOP5000)
- **FIRE-ALARM-INSTALLER-KEYWORD-LOCK** — nationwide fire-alarm family; leave alone (fire-**door** intents in this pack are building/compliance, not fire-alarm-installer)
- **MANCHESTER-ELECTRICAL-*** — GM-core 60 only; do not merge
- Pure electrical / CCTV / access / AOV / barrier / EV intents — out of scope for this pack

## Sources

- `/workspace/main-web-pr110/website/data/keywords.json`
- `/workspace/main-web-pr110/website/data/job-types-building.json`
- `/workspace/main-web-pr110/website/data/job-types-gas.json`
- `/workspace/main-web-pr110/website/data/job-types-plumbing.json`
- `/workspace/main-web-pr110/website/data/job-packs/building-fabric.json`
- Area set: `AREA-HUB-ALLOWLIST-50MI-DUAL-2026-10-05.csv` (**269**)
- Depth: `DUAL-RING-DEPTH-FULL-LOCAL-2026-10-05.md`
