# Manchester electrical — KEYWORDS + JOBS LOCK — 2026-10-05

**Jack order (via Grok Bot):** Manchester electrical pack — ~2000 job/keyword types, ranked most popular down. **Jack scale correction:** expanded from 200 → ~2000 unique intents. **Separate from** AOV / barrier / fire **nationwide** packs. Lock here; Website Team ships. Do **not** merge into `NATIONWIDE-3LINE-*`.

## Town / area set = GM-core 60 (LOCKED)

**Manchester areas for keyword×area + job×area = GM-core 60 only.**

| Set | Use for this pack? |
|-----|--------------------|
| **GM-core 60** (dual allowlist GM-core section) | **YES — exclusive town matrix** |
| Dual 50mi union (269) | **NO** — not the Manchester-area matrix |
| UK TOP 5000 / mainland ≥10k | **NO** — nationwide AOV/barrier/fire only |

- Source: `AREA-HUB-ALLOWLIST-50MI-DUAL-2026-10-05.md` § GM-core 60
- Primary hub city: **Manchester** (`manchester`)
- NAP base: Stockport / Offerton (see copy constraints)
- If the live site still has a smaller “Manchester areas” subset, **prefer GM-core 60** and treat this list as the Manchester-area matrix for electrical keyword×area + job×area.

### GM-core 60 slugs
```
altrincham
ashton-under-lyne
atherton
bolton
bramhall
bury
cadishead
chadderton
cheadle
cheadle-hulme
chorlton
denton
didsbury
droylsden
dukinfield
eccles
failsworth
farnworth
hazel-grove
heywood
horwich
hyde
irlam
kearsley
lees
leigh
little-lever
littleborough
manchester
marple
middleton
milnrow
mossley
oldham
pendlebury
prestwich
radcliffe
rochdale
romiley
royton
saddleworth
sale
salford
shaw
stalybridge
stockport
stretford
swinton
tameside
trafford
tyldesley
uppermill
urmston
walkden
westhoughton
whitefield
wigan
withington
worsley
wythenshawe
```

## Counts

| Metric | Count |
|--------|------:|
| Unique commercial intents | **2000** |
| P0 (highest intent) | **89** |
| P1 | **1902** |
| P2 | **9** |
| P0 ship-wave file | **89** |
| Existing (in `keywords.json` and/or `electrical-jobs.json` and/or live keyword/job page) | **357** |
| Create (new hubs needed) | **1643** |
| kind=both (keyword + job surfaces) | **794** |
| kind=keyword only | **1202** |
| kind=job only | **4** |

## Ranking method

1. **Commercial / local search intent first** — emergency, near-me, same-day / 24-hour / next-day, EICR, rewire, 3-phase, qualified / Part P / certified, consumer-unit / fuse-board, landlord certificates.
2. **Segment heads** — domestic / commercial / industrial electrician, electrical contractor / services / installation.
3. **Locked head (ranks 1–200)** — original 200 intents preserved as top of ranked list (original P0 1–58 kept; P0 wave expanded to ~89).
4. **Strong follow-ons (P1)** — cost/price (POA only), PAT, EV charger install, board upgrades, HMO/void/tenancy EICR, remediation, lighting/socket installs, GM city hub modifiers (Stockport, Salford, Bolton, …), urgency×intent×town.
5. **Long-tail depth (P2)** — testing codes, specialty sockets/lighting, brand CU upgrades, solar/battery/generator, EL *electrical* works (electrical lane — **not** the nationwide fire pack), commercial phrase matrix.
6. Sources merged then de-duplicated: `keywords.json` (service=electrical + electrician/EICR/rewire/Part P family), `electrical-jobs.json`, live `/pages/keywords/*` electrical pages, `seo-matrix-electrical.md`. High-intent gaps filled as **create**.

## Depth (what WT ships)

1. **Keyword hubs** `/pages/keywords/{slug}` — for every intent with kind `keyword` or `both`
2. **Job hubs** `/pages/jobs/{slug}` — for every intent with kind `job` or `both`
3. **Keyword×area** `/pages/keywords/{slug}/{town}` — **GM-core 60 only** (Manchester matrix)
4. **Job×area** `/pages/jobs/{slug}/{town}` — **GM-core 60 only**
5. **Not** TOP5000 ×town, **not** dual-269 ×town for this pack
6. Service hub remains `/pages/services/electrical`; service×town already on GM / dual rules separately

## Recommended WT ship order

1. **P0 hubs** — `MANCHESTER-ELECTRICAL-P0.txt` (89 slugs): create missing hubs first, refresh existing
2. **P0 × GM-core 60** — keyword×area + job×area for those P0 intents (Manchester primary; Stockport NAP)
3. **P1 hubs** — remaining create + existing refresh
4. **P1 × GM-core 60** — phased by crawl budget
5. **P2 hubs + ×area** — last

## Files in this lock

| File | Role |
|------|------|
| `MANCHESTER-ELECTRICAL-KEYWORDS-JOBS-LOCK-2026-10-05.md` | This lock |
| `MANCHESTER-ELECTRICAL-INTENTS-ALL.txt` | 2000 slugs, popularity rank order (best first) |
| `MANCHESTER-ELECTRICAL-INTENTS.csv` | rank,slug,kind,status,intent_tier,notes |
| `MANCHESTER-ELECTRICAL-P0.txt` | Top ship wave (89) |
| `MANCHESTER-ELECTRICAL-KEYWORDS-ONLY.txt` | Keyword surface slugs (kind keyword\|both) |
| `MANCHESTER-ELECTRICAL-JOBS-ONLY.txt` | Job surface slugs (kind job\|both) |

## Copy / compliance constraints

- **POA only** — never fixed prices; cost/price/quote/how-much slugs stay POA
- **NAP** (when shown): `17 Woodlands Park Road, Offerton, Stockport, Cheshire SK2 5DE`
- **Part P / qualified electricians** — electrical work uses Part P / Building Regulations / BS 7671 / qualified electrician language where accurate
- **Never claim iComply is Gas Safe registered** — Gas Safe wording only if gas work is explicitly mentioned (“carried out by Gas Safe registered engineers”); **do not** put Gas Safe on pure electrical pages
- **Never invent scheme badges** — NICEIC / NIC / NAPIT / Elecsa membership only if factually true for iComply; `niceic-certified` / `nic-electrician` hubs must use honest “search intent / ask about certification” copy, not fake registration claims
- **Separate from nationwide packs** — do not add these intents into `NATIONWIDE-3LINE-KEYWORDS*` or fire-alarm-installer nationwide ×TOP5000
- Emergency-lighting *electrical* slugs in this pack are **electrical-lane** wiring/supply work, not the nationwide fire-alarm-installer family
- Internal links: `/pages/services/electrical`, related electrical keywords/jobs, `/pages/areas/{town}` for GM-core 60 towns; Manchester as primary hub cross-links
- Anti-thin: unique intro per hub; ×area gets town-local paragraph + parent links

## Collision watches

- **NATIONWIDE-3LINE** (AOV / barriers / fire) — different programme; different town set (TOP5000)
- **FIRE-ALARM-INSTALLER-KEYWORD-LOCK** — nationwide fire family; leave alone
- **Dual-269 FULL LOCAL** — other local services may use 269; **this electrical Manchester pack uses GM-core 60 only** for keyword×area + job×area
- Place-locked nationwide barrier/AOV/fire slugs — out of scope

## Sources

- `/workspace/main-web-pr110/website/data/keywords.json`
- `/workspace/main-web-pr110/website/data/electrical-jobs.json`
- `/workspace/main-web-pr110/website/data/seo-matrix-electrical.md`
- Live `/pages/keywords/*` electrical PHP pages
- Area set: `AREA-HUB-ALLOWLIST-50MI-DUAL-2026-10-05.md` GM-core 60
- Scale correction: expanded 200 → 2000 unique intents (2026-10-05)
