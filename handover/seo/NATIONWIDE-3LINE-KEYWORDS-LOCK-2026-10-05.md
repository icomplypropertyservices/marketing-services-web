# NATIONWIDE 3-line KEYWORDS LOCK — 2026-10-05

**Jack orders:** ~2000 keywords across (1) smoke control / AOV installer, (2) barrier installer / repair / service, (3) fire services. Nationwide keyword + area coverage. Town set = **TOP 5000 UK by population**. Expand beyond fire-alarm-installer-only wave.

## Counts
| Line | Slugs |
|------|------:|
| AOV / smoke control | 620 |
| Barriers | 620 |
| Fire | 760 |
| **Total unique** | **2000** |

- Existing in `keywords.json`: **449**
- Create: **1551**
- P0 installer-intent cores: **76** (see `NATIONWIDE-3LINE-KEYWORDS-P0.txt`)

## Depth
- Keyword hubs `/pages/keywords/{slug}` for all 2000
- Keyword×town / keyword×area nationwide on **`UK-TOP5000-TOWNS-BY-POP-2026-10-05`** (5000 towns) — not ≥10k-only (995)
- Dual 269 FULL LOCAL unchanged for other local services
- Fire-alarm-installer family (44 hubs + P0×995 in #113) is a **subset / first wave**; remaining fire + AOV + barrier keywords here expand the programme; TOP5000 ×town follow-on applies to #113 P0 too

## Ship waves (for Website Team)
1. **P0 hubs** — 76 installer-intent cores (`NATIONWIDE-3LINE-KEYWORDS-P0.txt`) — ship ASAP with UK-nationwide copy
2. **P0 ×town** — those 76 × TOP5000 (or interim ×995 if TOP5000 data wiring not ready in same PR; prefer TOP5000 once allowlist is in repo)
3. **P1 hubs** — remaining 1924 hubs
4. **P1 ×town** — phased by crawl budget

## Files
- `NATIONWIDE-3LINE-KEYWORDS-LOCK-2026-10-05.md` (this file)
- `NATIONWIDE-3LINE-KEYWORDS-ALL.txt` (2000)
- `NATIONWIDE-3LINE-KEYWORDS.csv` (slug,line,status,intent_tier)
- `NATIONWIDE-3LINE-KEYWORDS-P0.txt` (76)
- `NATIONWIDE-AOV-SMOKE-KEYWORDS.txt` (620)
- `NATIONWIDE-BARRIER-KEYWORDS.txt` (620)
- `NATIONWIDE-FIRE-KEYWORDS.txt` (760)
- Town set: `UK-TOP5000-TOWNS-BY-POP-2026-10-05.csv` (+ `.md`, `.slugs.txt`)

## Copy / compliance
- **POA only** — never fixed prices
- NAP if shown: `17 Woodlands Park Road, Offerton, Stockport, Cheshire SK2 5DE`
- Gas Safe: “carried out by Gas Safe registered engineers” only if gas mentioned; never claim iComply is registered
- No fake BAFE / NSI badges
- Internal links: parent service hubs (`/pages/services/fire-alarms`, AOV/smoke, barriers) + related keywords; area hubs when town is on dual-269 allowlist

## Related
- Job-types pack (~2000, incl. manufacturer brands) shipping as separate lock: `NATIONWIDE-3LINE-JOBTYPES-*` (in flight)
- Dual-ring: `DUAL-RING-DEPTH-FULL-LOCAL-2026-10-05.md`

## Per-line existing vs create (from CSV)
| Line | Total | Existing | Create | P0 hubs |
|------|------:|--------:|-------:|--------:|
| AOV / smoke | 620 | 61 | 559 | 24 |
| Barriers | 620 | 49 | 571 | 27 |
| Fire | 760 | 339 | 421 | 25 |
| **All** | **2000** | **449** | **1551** | **76** |

Existing = present in `main-web-pr110/website/data/keywords.json` and/or `barriers-keywords.json`. Create = new hub slugs to add.

## Collision / family watches (do not confuse depth)
- **Dual 50mi FULL LOCAL (269)** — area hubs + other local service matrices only; **not** the town set for these three nationwide keyword families.
- **uk-mainland-towns-10k (995)** — superseded for these three lines by **TOP 5000**; still a subset (995/995 overlap). Interim P0×995 OK only if TOP5000 wiring lags.
- **GM-core / keyword_gm_modifier (3 towns)** — old thin modifier pattern; do not limit these 2000 to GM.
- **AHU / air-handling** under service `aov-air-handling` — excluded from this AOV/smoke pack (smoke-control/AOV commercial intent only).
- **Place-locked barrier slugs** (`*-burnley`, `*-manchester`) — not nationwide hubs; omit from this pack.
- **Cavity / fire barriers** — stay on **fire** line, not vehicle barriers.
- **Fire-alarm-installer-only lock** (`FIRE-ALARM-INSTALLER-KEYWORD-LOCK-2026-10-05.md`, 44 hubs) — **subset / first wave**; this 3-line lock expands AOV + barriers + broader fire and moves ×town town-set to TOP 5000.
- **Job-types pack** (`NATIONWIDE-3LINE-JOBTYPES-*`) — separate; do not merge manufacturer job slugs into this keyword hub list without a deliberate map.
