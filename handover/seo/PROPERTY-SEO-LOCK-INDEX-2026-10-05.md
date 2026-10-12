# PROPERTY SEO LOCK INDEX — 2026-10-05 (anti double-ship)

> **STANDING (Jack 2026-10-06):** No holds, freezes or parking ever. Anything blocked is logged as blocker + owner + next action in `/workspace/icomply-ops/MASTER-TASK-LIST.md`.

**Owner:** SEO Manager · **Shipper:** Website Team · **Rule:** one canonical pack per family; defer thin gap-fill cores into DEEP where noted.

## Ship order (current)
1. Networking/IT P0 — **PR #126 CONFIRMed** (`2ec901d`); then P0×dual269 + P1 wave-1
2. **Margin DEEP cluster:** AOV + barriers DEEP (manual + width/height) → Fencing DEEP → Perimeter detection DEEP → Electric gates → Fire dampers → AHU → BMS → AC DEEP
3. HMO Services (licence + design + build) · Shop Fitting (separate pack)
4. Facial recognition + ANPR P0
5. AI landings P0 (GO/HOLD — only `ai-property-services` GO)
6. Gap-fill P0 (defer barrier / door-entry / thin AC / thin gate cores → DEEP)
7. More services W2 (defer overlapping rows → DEEP)
8. Already-locked follow-ons: W1b, keywords P1, electrical P1, security P1, building P1, fire×TOP5000

## LOCKED packs (do not re-lock / do not thin-duplicate)

| Family | Lock path | Intents (approx) | Geo | Notes |
|--------|-----------|-----------------:|-----|-------|
| Nationwide 3-line kw (AOV/barrier/fire) | NATIONWIDE-3LINE-KEYWORDS-LOCK-* | 2000 | TOP5000 | Base; AOV+barrier superseded in priority by DEEP |
| Nationwide 3-line jobtypes | NATIONWIDE-3LINE-JOBTYPES-LOCK-* | 1954 | TOP5000 | Incl. mfr subset |
| **AOV + barriers DEEP** | AOV-BARRIERS-DEEP-LOCK-* | 6173 kw / 4298 jobs (7640 unique); P0 179; supp 3766 | TOP5000; mfr×town GM60 | **MARGIN P0** — corrected: width/height restrictions + manual; wind theme purged |
| **Fencing DEEP** | FENCING-DEEP-LOCK-* | 942 kw / 701 jobs (1440 unique); P0 108 | dual 269 FULL LOCAL; mfr×town GM60 | Margin; manual gates only (electric → ELECTRIC-GATES-DEEP); supersedes thin building-pack fencing (10 slugs) |
| **Perimeter detection DEEP** | PERIMETER-DETECTION-DEEP-LOCK-* | 548 kw / 688 jobs (1178 unique); P0 80 | dual 269 FULL LOCAL; mfr×town GM60 | Margin; security sensing (not advertising); `ai-perimeter-detection*` stays in Facial pack |
| **Electric gates DEEP** | ELECTRIC-GATES-DEEP-LOCK-* | see P0 file | dual 269 | Margin |
| **Fire dampers DEEP** | FIRE-DAMPERS-DEEP-LOCK-* | see P0 file | dual 269 + fire hubs | Margin |
| **AHU DEEP** | AHU-DEEP-LOCK-* | see P0 file | dual 269 | Margin |
| **BMS DEEP** | BMS-DEEP-LOCK-* | see P0 file | dual 269 | Margin |
| **AC DEEP** | AC-DEEP-LOCK-* | see P0 file | dual 269 | Supersedes thin AC gap-fill |
| Electrical | MANCHESTER-ELECTRICAL-* | 2000 | GM60 | |
| Building | DUAL-RING-BUILDING-SERVICES-* | 3000 | dual 269 | |
| Security (CCTV/access/ANPR base) | SECURITY-DUAL-RING-* | 200 | dual 269 | Don’t strip; facial/ANPR deep extends |
| Facial + ANPR | FACIAL-RECOGNITION-AI-SECURITY-* | 4222 | dual 269 | |
| Networking / Wi‑Fi / BT + IT | NETWORKING-IT-DUAL-RING-* | **2000** (P0=95) | dual 269 | PS 67 cores; no advertising |
| Fire-alarm installer | FIRE-ALARM-INSTALLER-* | 44 hubs | TOP5000 follow-on | |
| Gap-fill W1 | GAPFILL-SERVICES-* | 3844 | dual 269 | Defer barriers/door-entry/AC thin → DEEP |
| More services W2 | MORE-SERVICES-W2-* | 2625 | dual 269 | Defer gates/AC overlaps → DEEP |
| AI Property landings | AI-SERVICES-PROPERTY-LANDING-* | 80 | hubs | GO/HOLD file required |
| UK TOP5000 allowlist | UK-TOP5000-TOWNS-BY-POP-* | 5000 | — | |
| Dual 269 allowlist | AREA-HUB-ALLOWLIST-50MI-DUAL-* | 269 | — | |
| **HMO Services** (licence/design/build) | HMO-SERVICES-LOCK-* | **2201** (P0=185) | dual 269 | Rich copy; licence = support/compliance — never issue licences |
| **Shop Fitting** | SHOP-FITTING-LOCK-* | **861** (P0=75) | dual 269 | Separate from HMO; shutter pairing light-touch → gap-fill shutters |

## Barriers correction note (2026-10-05 ~23:55)
Barrier DEEP = manual barriers + **width/height restrictions** (arm length, boom height clearance, vehicle height limits, opening/lane width). High-wind theme withdrawn; supplement file is now `AOV-BARRIERS-DEEP-MANUAL-WIDTH-HEIGHT.txt`. WT: drop any wind-theme pages/routes if drafted.

## Gap-fill / W2 DEFER → DEEP (do not ship thin duplicates)
- Thin HMO / shopfitting rows in building pack → **HMO-SERVICES** / **SHOP-FITTING** DEEP (rich copy; ship once)
- Traffic barriers / barrier expand → **AOV-BARRIERS-DEEP**
- Door entry expand (thin) → security + **video door entry in W2** OK; electric gates → **ELECTRIC-GATES-DEEP**
- Thin AC / VRF rows in gap-fill or W2 → **AC-DEEP** (rich copy)
- Thin automatic-gate rows in W2 → **ELECTRIC-GATES-DEEP**
- Thin fencing rows in building pack (`fencing`, `fence-installation`, `closeboard-fencing`, …) → **FENCING-DEEP**
- Perimeter sensing (beams / fence detection / PIDS) → **PERIMETER-DETECTION-DEEP**; AI/facial/ANPR perimeter analytics → Facial pack

## Still unlocked / next candidates (not yet dedicated DEEP locks)
| Family | Status |
|--------|--------|
| Boilers / central heating / unvented cylinders | Partial via building pack — DEEP heating lock TBD if Jack wants |
| Plumbing / bathrooms / kitchens (domestic) | Building pack covers some — expand TBD; **retail shop fitting locked separately** |
| Lifts / stairlifts / platform lifts | **UNLOCKED** |
| Generators / UPS / three-phase distribution | Electrical adjacent — expand TBD |
| Data centres / server room cooling (beyond AC) | Partial via AC-DEEP server-room — OK |
| Solar / heat pumps / EV | In gap-fill W1 — ship after DEEP unless Jack elevates |
| Nurse call / PA-VA / lightning / water hygiene / asbestos / damp / roofing / glazing / shutters | Gap-fill W1 |
| Cloud CCTV / Paxton / Salto / fire kit / pest / drainage / scaffold / resin / safes | More services W2 |
| Supplies ecommerce SEO | Unowned / TBD |
| Professional Services | PS SEO owns — not Property |

## Canonical rule
If a slug appears in gap-fill/W2 **and** a DEEP pack, **ship once from DEEP** with rich copy. WT: skip deferred cores when building thin packs.
