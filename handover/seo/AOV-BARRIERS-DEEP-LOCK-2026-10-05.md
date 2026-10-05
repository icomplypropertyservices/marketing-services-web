# AOV + Barriers DEEP — Property LOCK — 2026-10-05 (CORRECTED 23:55 — width/height)

**Jack (via Grok Bot):** AOV + barriers are **BEST margin** — go **DEEP**. Fullest keyword/job packs, rich page descriptions (≥800 distinct words, FAQs, 3 images), manufacturer×job depth. **Priority over thinner packs.** SEO locks; WT ships without parking.

**Jack correction (via Grok Bot, 2026-10-05 ~23:55):** deep barrier pages cover **manual barriers + WIDTH and HEIGHT restrictions** (arm/boom length, boom height clearance, vehicle height limits, opening width, lane width). The earlier wind-rating theme is **withdrawn** — those slugs are purged (see “Correction audit”) and must not be built.

## Geo / depth
| Layer | Rule |
|-------|------|
| Keyword hubs + job hubs | Nationwide |
| Keyword×town / job×town | **TOP 5000** (`UK-TOP5000-TOWNS-BY-POP-2026-10-05`) |
| Manufacturer×town | **GM-core 60 only** |
| Manufacturer×job hubs | Nationwide hubs OK |

Quality: `PAGE-QUALITY-BAR-KEYWORD-JOB-2026-10-05.md` — **rich** copy required (margin lines). POA. No fake badges. Gas Safe wording N/A.

## CURRENT COUNTS (authoritative — match file line counts)

| File | Lines |
|------|------:|
| `AOV-BARRIERS-DEEP-AOV-KEYWORDS.txt` | **1520** |
| `AOV-BARRIERS-DEEP-BARRIER-KEYWORDS.txt` | **4653** |
| `AOV-BARRIERS-DEEP-KEYWORDS-ALL.txt` (AOV ∪ barrier) | **6173** |
| `AOV-BARRIERS-DEEP-AOV-JOBTYPES.txt` | **1339** |
| `AOV-BARRIERS-DEEP-BARRIER-JOBTYPES.txt` | **2959** |
| `AOV-BARRIERS-DEEP-JOBTYPES-ALL.txt` (AOV ∪ barrier) | **4298** |
| `AOV-BARRIERS-DEEP-MANUFACTURER-JOBS.txt` (all ⊂ JOBTYPES-ALL) | **420** |
| `AOV-BARRIERS-DEEP-MANUAL-WIDTH-HEIGHT.txt` (supplement; all ⊂ barrier KW ∪ JOBS) | **3766** |
| `AOV-BARRIERS-DEEP-P0.txt` (all ⊂ KW ∪ JOBS) | **179** |
| `AOV-BARRIERS-DEEP-P1-WAVE1.txt` (unchanged) | **200** |
| Unique slugs KW ∪ JOBS | **7640** (overlap kw∩jobs 2831 → one canonical URL per slug) |

### Correction audit (high-wind → width/height)
| Removed / changed | N |
|---|--:|
| Wind-theme slugs purged from barrier keywords | 795 |
| Wind-theme slugs purged from barrier job types | 345 |
| Wind-theme manufacturer jobs purged | 39 |
| Wind-theme P0 heads purged | 10 |
| Wind-only lines dropped from old supplement | 825 |
| Malformed `-near-me-<x>` slugs purged (barrier kw / jobs / supplement) | 47 / 47 / 24 |
| Non-wind manual/spec intents migrated into new supplement | 979 |
| New width/height keyword intents | 2322 |
| New width/height job intents | 1093 |
| New manufacturer×width/height/manual jobs | 80 |
| Supplement after de-dup (components above overlap kw∩jobs) | 3766 |
| P0 heads kept / added | 128 / 51 |
| Pre-existing P0 gap fixed (`aov-for-flats` added to AOV keywords) | 1 |

- `AOV-BARRIERS-DEEP-MANUAL-HIGHWIND.txt` **removed** → replaced by `AOV-BARRIERS-DEEP-MANUAL-WIDTH-HEIGHT.txt`.
- Grep check: zero barrier/mfr/P0/supplement slugs contain wind / Beaufort / km/h / storm / coastal / exposed / hurricane terms. Only exception: `aov-wind-sensor` + `aov-wind-sensor-installation` (AOV weather-override sensor component on natural smoke/comfort vents — an AOV product, **not** a barrier restriction theme).
- Pre-correction snapshot: `_build/backup-pre-wh-2026-10-05/`. Build: `_build/build_barrier_wh_fence_perim_2026_10_05.py` (idempotent; reads snapshot).

## History (superseded counts — do not use for shipping)
| | Prior 3-line slice | DEEP v1 | DEEP v2 (wind — withdrawn) | **DEEP v3 now** |
|--|--:|--:|--:|--:|
| AOV keywords | 620 | 1519 | 1519 | **1520** |
| Barrier keywords | 620 | 1414 | 3192 | **4653** |
| Keywords total | 1240 | 2933 | 4711 | **6173** |
| Job types total | ~855 | 2693 | 3544 | **4298** |
| Manufacturer×job | — | 340 | 399 | **420** |
| P0 | 76 | 120 | 138 | **179** |
| Supplement | — | — | 1828 (wind) | **3766** (manual + width/height) |

### What DEEP fills vs the thin 3-line pack
1. Manufacturer×job depth for AOV (Colt, SE Controls, D+H, GEZE, Assa Abloy, WindowMaster, Actionair, Trox, …) and barriers (Came, Nice, BFT, FAAC, Beninca, Ditec, Hörmann, Magnetic, Elka, Automatic Systems)
2. Long-tail install/repair/service/commissioning/near-me variants
3. Car-park / flats / HMO / commercial modifiers + emergency/PPM intents
4. **Manual barriers + width/height restriction depth** (this correction)
5. Rich-copy mandate for WT (not lean stubs)

Still use / do not delete: `NATIONWIDE-3LINE-KEYWORDS-*` / `NATIONWIDE-3LINE-JOBTYPES-*` — DEEP is a **superset priority wave**; canonical URL where overlap = single page (prefer DEEP slug).

## DEEPER barriers update — manual barriers + WIDTH/HEIGHT restrictions (Jack correction 2026-10-05 ~23:55)

Restriction theme for barrier DEEP = **physical width and height**, not weather.

| Cluster | Example intents (all × install / repair / service / maintenance / replacement / specification / survey / commissioning / adjustment / emergency-repair / PPM + near-me / cost / quote / sector modifiers) |
|---|---|
| Manual barriers | manual-barrier, manual-rising-arm-barrier, manual-boom-barrier, counterbalanced-/key-operated-/padlock-manual-barrier, manual-swing-arm-barrier, manual-swing-barrier, manual-height-barrier, manual-swing-height-barrier, manual-width-restriction-barrier, manual-long-/short-arm-barrier, manual-lift-up-barrier |
| Arm / boom length | barrier-arm-length, barrier-boom-length, 3m–8m barrier arm / boom barrier / rising-arm barrier / car-park barrier / manual barrier, long-arm-/long-span-/short-arm-barrier, barrier-arm-replacement, -shortening, -cut-to-length, -rebalancing, barrier-spring-rebalancing, arm support post / arm rest / arm skirt, arm-length sizing |
| Height clearance / restriction | height-restriction-barrier, height-restrictor, height-barrier, height-limit-barrier, vehicle-height-restriction(-barrier), barrier-height-clearance, boom-height-clearance, barrier-headroom, barrier-vertical-clearance, raised-boom-clearance, low-boom-/low-headroom-/low-ceiling-barrier, articulated-/folding-arm-barrier, swing-/fixed-/adjustable-/lockable-height barrier, height-restriction-gantry, height-warning-bar, 1.9m–3m height barriers (slugs `1-9m`, `2-1m`, …) |
| Car park / undercroft pairing | car-park-height-barrier, car-park-height-restrictor, multi-storey-car-park-height-barrier, undercroft-car-park-barrier, undercroft-height-barrier, basement-car-park-barrier, height-barrier-and-rising-arm-barrier, car-park-height-barrier-and-rising-arm, height-restrictor-and-barrier-system |
| Vehicle class | hgv-/van-height-restriction-barrier, car-only-height-barrier, caravan-height-restriction-barrier, anti-incursion-height-barrier |
| Opening / lane width | barrier-opening-width, barrier-clear-width, barrier-clear-opening, barrier-lane-width, single-/dual-/two-lane-barrier, narrow-lane-barrier, entry-exit-lane-barrier, wide-entrance-/wide-opening-barrier, twin-arm-barrier, opposing-barrier-pair, barrier-for-3m…10m-opening, barrier-for-wide-driveway, swing-arm-barrier(-clearance) |
| Width restriction (access) | width-restriction-barrier, width-restrictor, width-restriction-gate, width-limit-barrier, car-park-width-restrictor, vehicle-width-restriction, 2m / 2.1m / 2.2m / 2.5m width restrictors |
| Survey / spec | barrier-clearance-survey, entrance-width-survey, height-clearance-survey, swept-path-analysis-barrier, barrier-site-measure, barrier-width-and-height-specification |
| Manufacturer × width/height | {came, nice, bft, faac, beninca, ditec, hoermann, magnetic, elka, automatic-systems} × barrier-arm-replacement / long-arm-barrier-installation / articulated-arm-barrier-installation / barrier-arm-length-specification / barrier-arm-rebalancing / dual-lane-barrier-installation / manual-barrier-installation / manual-barrier-supply |

## P0 copy requirements — manual barriers + width/height restrictions (MANDATORY)

Every page whose primary intent is manual-barrier, arm/boom length, height clearance/restriction or opening/lane/width restriction:

| Requirement | Rule |
|-------------|------|
| Body | ≥800 **distinct** words — `PAGE-QUALITY-BAR-KEYWORD-JOB-2026-10-05.md` |
| FAQs | ≥3 Qs: suitability for the opening, measurement/clearance limits, maintenance |
| Images | 3 images (realistic service imagery OK) |
| Meta | Unique title + description + OG (title/desc/image) + absolute canonical |
| Manual barriers | Manual vs automatic; counterbalance / key / padlock operation; typical sites (car parks, estates, warehouses, depots); install/repair/service; when to upgrade to automatic |
| Arm / boom length | How clear opening width sets arm length; typical UK arm lengths (3m–8m) as **ranges, not product claims**; why longer arms need heavier operators/springs and arm supports (rest post); re-balancing after shortening or adding a skirt/lights |
| Height clearance | Two different things — (a) **height restrictors/height barriers** that stop over-height vehicles, (b) **headroom for the barrier itself** (a rising arm needs vertical clearance ≈ arm length when open → articulated/folding arms in undercrofts/low ceilings); car-park height bars paired with a rising-arm barrier; signage of the max height; vehicle classes (car/van/HGV) |
| Width / lane | Opening width vs clear width; single vs dual lane; twin/opposing arms for wide openings; width restrictors for access control on private land; swept-path / turning space for HGVs |
| Honest specs | Quote dimensions only as **site-measured** or the **manufacturer’s published range**; never invent a product rating; specification via site survey + POA |
| CTA | POA enquiry only |

Do not write any wind-rating / weather-restriction copy on barrier pages.

P0 list: `AOV-BARRIERS-DEEP-P0.txt` (179) — manual barriers, width restriction, height restriction/clearance, arm length, popular barrier install/repair hubs, AOV deep heads.  
Supplement intents: `AOV-BARRIERS-DEEP-MANUAL-WIDTH-HEIGHT.txt` (3766).

## Files
- `AOV-BARRIERS-DEEP-LOCK-2026-10-05.md`
- `AOV-BARRIERS-DEEP-KEYWORDS-ALL.txt` / `-AOV-KEYWORDS.txt` / `-BARRIER-KEYWORDS.txt`
- `AOV-BARRIERS-DEEP-JOBTYPES-ALL.txt` / `-AOV-JOBTYPES.txt` / `-BARRIER-JOBTYPES.txt`
- `AOV-BARRIERS-DEEP-MANUFACTURER-JOBS.txt`
- `AOV-BARRIERS-DEEP-MANUAL-WIDTH-HEIGHT.txt`
- `AOV-BARRIERS-DEEP-P0.txt` / `-P1-WAVE1.txt`
- `DISPATCH-AOV-BARRIERS-DEEP-WT-2026-10-05.md`

## WT ship order (Jack priority)
Networking/IT P0 → AOV + barriers DEEP (corrected: manual + width/height) → Fencing DEEP → Perimeter detection DEEP → Electric gates DEEP → Fire dampers DEEP → AHU DEEP → BMS DEEP → AC DEEP → Facial/ANPR P0 → AI landings P0 → Gap-fill P0 → More services W2

1. Finish networking/IT P0 draft (in flight)
2. **This AOV+barriers DEEP P0** (179 hubs, rich copy) → then P0 × TOP5000 phased (179×5000 = 895,000 routes max)
3. Manufacturer job hubs + GM60 ×town for mfr (420×60 = 25,200)
4. Then Fencing DEEP → Perimeter detection DEEP → electric gates → rest of margin DEEP cluster → thinner packs
