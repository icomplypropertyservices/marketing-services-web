# NATIONWIDE 3-LINE JOB TYPES — LOCK 2026-10-05

**Jack order (2026-10-05 via Grok Bot):** ~2000 JOB TYPES for AOV/smoke, barriers and fire, manufacturer keywords included. Covers install, repair, service and maintenance, plus brand/manufacturer job pages. Nationwide × TOP 5000 areas (same product lines). Lock the list, then WT ships.

**Status: LOCKED.** These are **job** slugs for `/pages/jobs/{slug}` and job×town `/pages/jobs/{slug}/{town}`. They are separate from the ~2000 keyword-hub lock (`NATIONWIDE-3LINE-KEYWORDS*.{csv,txt}`). No GSC metrics are used or implied. Priority comes only from intent shape and commercial fit.

## Counts (exact)

**Total unique job slugs: 1954** (target 1800–2200 ✅)

| Line | Total | Generic | Manufacturer | Existing | Create | P0 | P1 | P2 |
|---|---:|---:|---:|---:|---:|---:|---:|---:|
| `aov-smoke` | 528 | 299 | 229 | 27 | 501 | 30 | 375 | 123 |
| `barrier` | 607 | 309 | 298 | 76 | 531 | 30 | 405 | 172 |
| `fire` | 819 | 537 | 282 | 177 | 642 | 30 | 534 | 255 |
| **All** | **1954** | **1145** | **809** | **280** | **1674** | **90** | **1314** | **550** |

Scope per line:
- `aov-smoke`: AOV / smoke control
- `barrier`: Barriers (vehicle barriers, bollards, gate automation)
- `fire`: Fire (alarms, detection, emergency lighting, extinguishers, doors, stopping, sprinklers, risers, suppression)

### What `status=existing` means
- `existing` = a page already exists for this exact slug on main (`main-web-pr110`). **219** come from job-lane data and **61** from keyword pages only.
  - Job-lane sources: `fire-alarms-lane.json`, `emergency-lighting-job-types.json`, `job-types-access-control.json` (barriers family, town-suffixed rows dropped), `job-packs/core-compliance-extra.json`, `pages/jobs/*.php`.
  - Keyword-page source: `keywords.json` / `pages/keywords/*.php`.
  - Note: several existing job lanes already render at `/pages/keywords/{slug}`, not `/pages/jobs/{slug}`. WT should 301 or canonicalise to **one** URL per slug and never ship a duplicate body.
- `create` = no page on main yet.

### Overlap with the keyword-hub lock
- **428** job slugs also appear in `NATIONWIDE-3LINE-KEYWORDS.csv`. List: `NATIONWIDE-3LINE-JOBTYPES-x-KEYWORDS-OVERLAP.txt`.
- Rule: one slug, one canonical page. If the keyword hub ships first, the job URL should canonical or 301 to it. The reverse also applies. Never publish two near-identical bodies.

## Manufacturer / brand coverage

Only real brands that already exist in the site data are used: `manufacturers.json` (by_service + catalog), `manufacturer-product-lines.json`, `barrier-manufacturers.json`, `town-manufacturers.json`, `mfr-coverage.json`. **No invented brands.** Product-line tokens come from those files (e.g. Kentec Syncro AS, C-Tec XFP, Gent Vigilon, SE Controls OS2, D+H CDC/RZN, CAME GARD GT4/GT8/PX/LT/PT, FAAC 615/620/B614/B680H, Nice M-Bar/Wide M).

- **aov-smoke** (229 slugs, 19 brands): se-controls 22, geze 21, d-h-mechatronic 20, colt 19, windowmaster 19, ventlux 18, group-scs 15, simon-rwa 15, cambric 13, brooks 11, ventilux 11, trox 9, flaktgroup 7, nuaire 7, bilco 6, kingspan-air 5, systemair 5, assa-abloy 4, kac 2
- **barrier** (298 slugs, 32 brands): came 59, faac 18, nice 18, bft 16, elka 16, magnetic-autocontrol 14, automatic-systems 12, beninca 12, hormann 12, frontier-pitts 11, apt-controls 10, ditec 10, roger-technology 10, centurion 6, doorhan 5, doorking 5, genius 5, gibidi 5, liftmaster 5, sea 5, aprimatic 4, cardin 4, dea-system 4, fadini 4, key-automation 4, king-gates 4, life-home-integration 4, proteco 4, tau 4, v2 4, paxton 2, videx 2
- **fire** (282 slugs, 40 brands): c-tec 17, apollo 16, kentec 16, advanced-electronics 15, morley 15, cooper-fire 14, ems 14, fireclass 14, eaton-fire 11, fike 11, gent 11, haes-systems 11, notifier 11, ziton 11, xtralis-vesda 9, hochiki 8, honeywell 8, nittan 6, sterling-safety 5, system-sensor 5, ansell-lighting 3, beghelli 3, clevertronics 3, cooper-lighting 3, d-light 3, emergi-lite 3, jsb-electrical 3, luxonic 3, mackwell 3, menvier 3, orbik 3, p4-limited 3, thorlux 3, thorn-lighting 3, abb 2, eaton 2, fagerhult 2, legrand 2, philips-emergency 2, zumtobel 2

Brand rules baked into the list:
- **CAME is the barrier partner.** Only CAME gets new-install / replacement / upgrade barrier jobs. Other barrier brands get repair, service, maintenance and parts jobs for existing equipment only, per `town-manufacturers.json` ("new installs default to CAME unless the incumbent should stay").
- AOV brands with a generic-word name are **excluded** to avoid collisions with generic slugs: `Smoke Control` (brand) is skipped. Tunstall is excluded per `mfr-coverage.json` / `barrier-manufacturers.json`.
- Brooks / Ventilux / Cambric: service and repair framing only, for equipment "already on site" (per `mfr-coverage.json`).
- Emergency-lighting brands sit in the **fire** line at P2. General electrical brands (ABB, Legrand, Eaton, Fagerhult, Zumtobel, Philips) get only install and repair.
- Brand pages must not claim manufacturer approval, accreditation numbers or "authorised dealer" status. The only exception is the CAME partnership, stated in plain words with no number.

## Town set

- **TOP 5000 UK mainland towns/areas by population**: `/workspace/icomply-ops/seo/UK-TOP5000-TOWNS-BY-POP-2026-10-05.csv` (slugs: `UK-TOP5000-TOWNS-BY-POP-2026-10-05.slugs.txt`, **5000** rows, rank 1 = london).
- These three lines are **nationwide**. Do **not** limit them to the dual-269 FULL LOCAL ring; dual-269 stays for other services.
- Full theoretical job×town surface = 1954 × 5000 = **9,770,000** URLs. Ship it in waves (below). Do not drop it all into the sitemap in one go.

## P0 waves (suggested ship order for WT)

**Tier definitions** (intent shape only, no search-volume data):
- **P0 (90 = 30 per line):** head service-intent jobs (install / repair / service / maintenance / call-out / replacement) on the core product of each line, plus the top brands (CAME partner, the main fire panel brands, the main AOV system brands).
- **P1:** remaining generic install / repair / service / maintenance / commissioning / upgrade / testing jobs, component jobs, and brand-level manufacturer jobs.
- **P2:** long tail. Product-line (model) manufacturer jobs, sector-qualified jobs, fault-symptom jobs, certificate / standard / category variants, near-me and installer forms, emergency-lighting brands.

| Wave | What | Slugs | ×Towns | URLs |
|---|---|---:|---|---:|
| **W1a** | P0 job hubs `/pages/jobs/{slug}` | 90 | — | 90 |
| **W1b** | P0 × TOP5000 ranks 1–1000 | 90 | 1000 | 90,000 |
| **W1c** | P0 × TOP5000 ranks 1001–5000 | 90 | 4000 | 360,000 |
| **W2** | P1 job hubs (all) | 1314 | — | 1314 |
| **W3** | P1 × TOP5000 (stage by rank as above) | 1314 | 5000 | 6,570,000 |
| **W4** | P2 hubs; P2×town only after W1–W3 are indexed | 550 | 5000 | 2,750,000 |

### P0 — aov-smoke (30)
```
aov-actuator-repair  [generic, create]
aov-actuator-replacement  [generic, existing]
aov-annual-service  [generic, existing]
aov-call-out  [generic, create]
aov-commissioning  [generic, existing]
aov-installation  [generic, existing]
aov-maintenance  [generic, existing]
aov-panel-repair  [generic, create]
aov-panel-replacement  [generic, existing]
aov-repair  [generic, existing]
aov-replacement  [generic, create]
aov-service  [generic, create]
aov-servicing  [generic, existing]
aov-testing  [generic, existing]
colt-aov-service  [manufacturer/colt, create]
d-h-aov-repair  [manufacturer/d-h-mechatronic, create]
emergency-aov-repair  [generic, create]
roof-smoke-vent-installation  [generic, existing]
se-controls-aov-repair  [manufacturer/se-controls, create]
se-controls-aov-service  [manufacturer/se-controls, create]
smoke-control-system-installation  [generic, create]
smoke-control-system-maintenance  [generic, existing]
smoke-control-system-repair  [generic, create]
smoke-damper-testing  [generic, create]
smoke-shaft-maintenance  [generic, existing]
smoke-vent-installation  [generic, existing]
smoke-vent-maintenance  [generic, create]
smoke-vent-repair  [generic, create]
smoke-vent-service  [generic, create]
smoke-ventilation-installation  [generic, create]
```
### P0 — barrier (30)
```
automatic-barrier-installation  [generic, existing]
automatic-barrier-maintenance  [generic, existing]
automatic-barrier-repair  [generic, existing]
barrier-arm-replacement  [generic, existing]
barrier-call-out  [generic, create]
barrier-installation  [generic, existing]
barrier-loop-detector  [generic, existing]
barrier-maintenance  [generic, create]
barrier-motor-replacement  [generic, existing]
barrier-repair  [generic, existing]
barrier-replacement  [generic, create]
barrier-service  [generic, create]
barrier-servicing  [generic, existing]
barrier-stuck-down  [generic, existing]
bft-barrier-repair  [manufacturer/bft, create]
came-barrier-installation  [manufacturer/came, existing]
came-barrier-repair  [manufacturer/came, existing]
came-barrier-service  [manufacturer/came, create]
came-gard-gt4-installation  [manufacturer/came, create]
car-park-barrier-installation  [generic, existing]
car-park-barrier-maintenance  [generic, existing]
car-park-barrier-repair  [generic, existing]
car-park-barrier-servicing  [generic, existing]
emergency-barrier-repair  [generic, existing]
faac-barrier-repair  [manufacturer/faac, create]
nice-barrier-repair  [manufacturer/nice, create]
rising-arm-barrier-installation  [generic, existing]
rising-arm-barrier-repair  [generic, existing]
vehicle-barrier-installation  [generic, existing]
vehicle-barrier-repair  [generic, existing]
```
### P0 — fire (30)
```
addressable-fire-alarm-installation  [generic, create]
advanced-fire-alarm-repair  [manufacturer/advanced-electronics, create]
c-tec-fire-alarm-repair  [manufacturer/c-tec, create]
conventional-fire-alarm-installation  [generic, create]
emergency-fire-alarm-repair  [generic, create]
emergency-lighting-installation  [generic, existing]
emergency-lighting-repair  [generic, existing]
emergency-lighting-testing  [generic, existing]
false-alarm-investigation  [generic, existing]
fire-alarm-call-out  [generic, existing]
fire-alarm-certificate  [generic, existing]
fire-alarm-commissioning  [generic, existing]
fire-alarm-fault-finding  [generic, existing]
fire-alarm-installation  [generic, existing]
fire-alarm-maintenance  [generic, existing]
fire-alarm-panel-repair  [generic, create]
fire-alarm-panel-replacement  [generic, create]
fire-alarm-ppm  [generic, existing]
fire-alarm-repair  [generic, existing]
fire-alarm-replacement  [generic, existing]
fire-alarm-service  [generic, existing]
fire-alarm-servicing  [generic, existing]
fire-alarm-upgrade  [generic, existing]
fire-door-inspection  [generic, existing]
fire-door-installation  [generic, existing]
fire-extinguisher-servicing  [generic, create]
gent-fire-alarm-service  [manufacturer/gent, create]
kentec-fire-alarm-repair  [manufacturer/kentec, create]
morley-fire-alarm-repair  [manufacturer/morley, create]
wireless-fire-alarm-installation  [generic, create]
```

## Copy constraints (every job hub and job×town page)

- **Pricing: POA only.** No £ figures, bands or "from £X". Use "price on application after survey". This also covers CAME 5m packs on job pages.
- **NAP (locked, Stockport):** iComply Property Services, 17 Woodlands Park Road, Offerton, Stockport, Cheshire SK2 5DE · 07517806082 · info@icomplypropertyservices.co.uk. Footer and NAP blocks must match exactly, including "Cheshire".
- **Nationwide framing:** we serve UK-wide from a Stockport base. Do not claim a local office in each town. Town pages need real town context (from the TOP5000 CSV: county / region / nation), not just a token swap.
- **Gas:** these three lines are **not** gas work. Do not use Gas Safe wording on them. If a page ever mentions gas (gas suppression is *not* gas work), the only allowed form is "carried out by Gas Safe registered engineers". Never "iComply is Gas Safe registered".
- **No fake accreditations:** do **not** claim BAFE, NSI, FIRAS, LPCB, IFC or manufacturer-approved-installer status, and no certificate numbers. Standards can be named as the work standard (BS 5839-1/-6, BS 5266-1, BS 9991/9999, BS EN 12101, BS 7346-8, BS 5306-3, BS 9990, BS EN 12453, BS 8629) but never as an accreditation. Certificate-type jobs mean *the paperwork we issue for the work done*.
- **Brands:** the partner claim is CAME only. Other brands: "we service / repair existing {brand} equipment; model confirmed on site".
- **UK English**, kebab-case slugs, no 5-modifier stacks (the longest slug in the lock is 7 tokens).
- **No invented metrics:** no GSC, ranking or search-volume figures on pages or in briefs.

## Files

| File | Rows |
|---|---:|
| `NATIONWIDE-3LINE-JOBTYPES-LOCK-2026-10-05.md` | this file |
| `NATIONWIDE-AOV-SMOKE-JOBTYPES.txt` | 528 |
| `NATIONWIDE-BARRIER-JOBTYPES.txt` | 607 |
| `NATIONWIDE-FIRE-JOBTYPES.txt` | 819 |
| `NATIONWIDE-3LINE-JOBTYPES-ALL.txt` | 1954 |
| `NATIONWIDE-3LINE-JOBTYPES.csv` | 1954 (+header) — slug,line,kind,brand,status,intent_tier |
| `NATIONWIDE-3LINE-JOBTYPES-x-KEYWORDS-OVERLAP.txt` | 428 |
| `_build/build_jobtypes.py` | reproducible generator |

_Built by Grok Bot (SEO executor) 2026-10-05. Website Team not messaged, no PRs opened._
