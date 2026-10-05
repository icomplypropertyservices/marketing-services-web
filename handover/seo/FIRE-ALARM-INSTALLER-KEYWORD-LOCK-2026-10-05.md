# FIRE-ALARM-INSTALLER keyword family LOCK — 2026-10-05

**Jack:** Barriers/AOV/fire town pages stay nationwide. **PRIORITY:** fire-alarm-installer keyword family nationwide (all variants). Area hubs still dual 50mi MCR∪Burnley for other areas.

## Depth
1. **Keyword hubs** `/pages/keywords/{slug}` — ALL locked variants below (create missing). Copy is **UK/nationwide**, not GM-only.
2. **Keyword×town** `/pages/keywords/{slug}/{town}` — **nationwide** on Jack’s **TOP 5000** UK mainland towns/areas by population (`UK-TOP5000-TOWNS-BY-POP-2026-10-05.*`; was `uk-mainland-towns-10k.json` 995 ≥10k — that set is a subset of the top of TOP 5000). **Not** limited to GM or 50mi rings. **Note:** PR #113 may need a follow-on expand from 995→5000 for fire/AOV/barrier ×town generation.
3. Ship **P0 ×town wave first** (installer-intent core), then remaining hubs’ ×town in follow-on if needed for crawl budget.

## P0 keyword×town wave (ship first)
- `fire-alarm-installer`
- `fire-alarm-installers`
- `fire-alarm-installation`
- `fire-alarm-installation-near-me`
- `fire-alarm-installer-near-me`
- `fire-alarm-installers-near-me`
- `fire-alarm-engineer`
- `fire-alarm-engineer-near-me`
- `fire-alarm-engineers-near-me`
- `emergency-fire-alarm-installer`
- `emergency-fire-alarm-installation`
- `emergency-fire-alarm`
- `commercial-fire-alarm-installer`
- `commercial-fire-alarm-installation`
- `domestic-fire-alarm-installer`
- `domestic-fire-alarm-installation`

## Full hub list — already in keywords.json
- `domestic-fire-alarm-installation`
- `emergency-fire-alarm`
- `factory-fire-alarm-system`
- `fire-alarm-call-point-installation`
- `fire-alarm-engineer`
- `fire-alarm-engineer-near-me`
- `fire-alarm-engineers-near-me`
- `fire-alarm-installation`
- `fire-alarm-installation-certificate`
- `fire-alarm-strobe-installation`
- `office-fire-alarm-installation`

## Full hub list — CREATE
- `fire-alarm-installer`
- `fire-alarm-installers`
- `fire-alarm-installations`
- `fire-alarm-installer-near-me`
- `fire-alarm-installers-near-me`
- `fire-alarm-installation-near-me`
- `fire-alarm-engineers`
- `emergency-fire-alarm-installer`
- `emergency-fire-alarm-installation`
- `fire-alarm-company`
- `fire-alarm-companies`
- `fire-alarm-contractors`
- `fire-alarm-contractor`
- `fire-alarm-specialist`
- `fire-alarm-specialists`
- `fire-alarm-fitter`
- `fire-alarm-fitters`
- `commercial-fire-alarm-installer`
- `commercial-fire-alarm-installation`
- `commercial-fire-alarm-installers`
- `domestic-fire-alarm-installer`
- `warehouse-fire-alarm-installation`
- `same-day-fire-alarm-installer`
- `24-hour-fire-alarm-engineer`
- `fire-alarm-repair-near-me`
- `fire-alarm-service-near-me`
- `fire-alarm-maintenance-near-me`
- `bs-5839-fire-alarm-installer`
- `addressable-fire-alarm-installation`
- `conventional-fire-alarm-installation`
- `landlord-fire-alarm-installer`
- `hmo-fire-alarm-installation`
- `care-home-fire-alarm-installation`

## All hubs (union, 44)
```
24-hour-fire-alarm-engineer
addressable-fire-alarm-installation
bs-5839-fire-alarm-installer
care-home-fire-alarm-installation
commercial-fire-alarm-installation
commercial-fire-alarm-installer
commercial-fire-alarm-installers
conventional-fire-alarm-installation
domestic-fire-alarm-installation
domestic-fire-alarm-installer
emergency-fire-alarm
emergency-fire-alarm-installation
emergency-fire-alarm-installer
factory-fire-alarm-system
fire-alarm-call-point-installation
fire-alarm-companies
fire-alarm-company
fire-alarm-contractor
fire-alarm-contractors
fire-alarm-engineer
fire-alarm-engineer-near-me
fire-alarm-engineers
fire-alarm-engineers-near-me
fire-alarm-fitter
fire-alarm-fitters
fire-alarm-installation
fire-alarm-installation-certificate
fire-alarm-installation-near-me
fire-alarm-installations
fire-alarm-installer
fire-alarm-installer-near-me
fire-alarm-installers
fire-alarm-installers-near-me
fire-alarm-maintenance-near-me
fire-alarm-repair-near-me
fire-alarm-service-near-me
fire-alarm-specialist
fire-alarm-specialists
fire-alarm-strobe-installation
hmo-fire-alarm-installation
landlord-fire-alarm-installer
office-fire-alarm-installation
same-day-fire-alarm-installer
warehouse-fire-alarm-installation
```

## Copy / compliance constraints
- **POA only** — never fixed prices
- NAP if shown: `17 Woodlands Park Road, Offerton, Stockport, Cheshire SK2 5DE`
- Gas Safe: only “carried out by Gas Safe registered engineers” where gas work is mentioned; **never** claim iComply itself is Gas Safe registered
- Fire alarms: no invented BAFE/NSI/regulatory badges; honest BS 5839 / design-install-commission language only
- Internal links: parent `/pages/services/fire-alarms`, related fire keywords, `/pages/areas/{town}` when town is on area-hub allowlist; else link service hub + nearest radius/GM hub sparingly
- Do not thin-doorway: unique intro per hub; ×town gets town-local paragraph + parent links (≥80 outs pattern if using matrix chrome)

## Related ops
- Nationwide town set: `UK-TOP5000-TOWNS-BY-POP-2026-10-05.csv` / `.slugs.txt` / `.md` (TOP 5000 by pop; replaces ≥10k 995 for keyword×town scope)
- Dual area allowlist CSV: `AREA-HUB-ALLOWLIST-50MI-DUAL-2026-10-05.csv` (also mirrored as `AREA-HUB-ALLOWLIST-50MI-2026-10-05.csv`)
- Area restore: dual circle; local service×town ON inside either circle per Jack dual-circle order; keyword/job/manufacturer×town stay GM-core only (except this fire-alarm-installer family which is nationwide)
