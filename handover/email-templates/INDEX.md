# iComply email template library — master INDEX

Two roots (Jack 2026-10-05):

| Root | Path | Holds |
|---|---|---|
| **Library (transactional + landlords + cold copy)** | `/workspace/icomply-ops/email-templates/library/` | booking, welcome, reviews, subcontractors, renewals, outbound-landlords, cold |
| **Campaign templates** | `/workspace/icomply-ops/email-campaigns/templates/` | industry `*-all.html`, barrier-all, commercial-batch4 |

## Required on every send

1. Branded HTML from **BRAND-BLOCKS** (`HEADER` CSS mark + `CONTACT_CARD` + `FOOTER`) — navy `#0B1F3A`, orange `#FF6B00`
2. **Attach the real `.vcf` file** (not link-only): `/workspace/icomply-ops/email-templates/assets/icomply-property-services.vcf`
3. Hosted link also OK in HTML: `https://icomplypropertyservices.co.uk/assets/contact/icomply-property-services.vcf`
4. Canon blocks: `/workspace/icomply-ops/email-templates/BRAND-BLOCKS.html` + `brand_blocks.py`
5. Do **not** send without Jack confirm + UK business hours for cold

---

## A. Library HTML (`email-templates/library/`)

| Template | Relative path | Category | Use when | vCard block | VCF attach required | Source |
|---|---|---|---|---|---|---|
| Commercial Batch 4 | cold/commercial-batch4.html | cold | Commercial cold outbound Batch 4 | yes | yes | icomply-outbound-commercial/COMMERCIAL-BATCH4-BRANDED.html (+ campaigns/templates mirror) |
| Cold landlords WITH gas | outbound-landlords/COLD-LANDLORDS-WITH-GAS.html | cold / landlords | Landlord/HMO cold — Batch 7+ / Batch 10 (includes gas) | yes (patched) | yes | library/outbound-landlords (Batch 7/10) |
| Cold landlords NO gas | outbound-landlords/COLD-LANDLORDS-NO-GAS.html | cold / landlords | Landlord cold — Batch 6-style (no gas line) | yes (patched) | yes | library/outbound-landlords (Batch 6) |
| template-batch6 | outbound-landlords/template-batch6.html | cold / landlords | Legacy name = NO-GAS | yes (patched) | yes | same as COLD-LANDLORDS-NO-GAS |
| template-batch7 | outbound-landlords/template-batch7.html | cold / landlords | Legacy name = WITH-GAS | yes (patched) | yes | same as COLD-LANDLORDS-WITH-GAS |
| Booking confirmation | booking/BOOKING-CONFIRMATION.html | booking | Confirm a booked visit/job | yes | yes | email-templates/BOOKING-CONFIRMATION.html |
| Welcome account | welcome/WELCOME-ACCOUNT.html | welcome | New customer account welcome | yes | yes | email-templates/WELCOME-ACCOUNT.html |
| Review request | reviews/REVIEW-REQUEST.html | reviews | Ask for a review after job | yes | yes | email-templates/REVIEW-REQUEST.html |
| Subcontractor Telegram onboard | subcontractors/SUBCONTRACTOR-TELEGRAM-ONBOARD.html | subcontractors | Onboard subcontractor to Telegram ops | yes | yes | email-templates/SUBCONTRACTOR-TELEGRAM-ONBOARD.html |
| Renewal reminder | renewals/RENEWAL-REMINDER.html | renewals | Cert/service due reminder (Jack approves each) | yes (expanded) | yes | email-templates/RENEWAL-REMINDER.html + BRAND-BLOCKS expand |

### Category pointers (no duplicate HTML)

| Folder | Points to |
|---|---|
| `industry/` | Campaign root industry `*-all.html` — see section B |
| `barriers/` | `email-campaigns/templates/barrier-all.html` |
| `quotes/` | No reusable quote **email** yet — pack drafts only (see README) |

---

## B. Campaign templates (`email-campaigns/templates/`)

Folder-per-template layout (2026-10-05). Full catalogue: `/workspace/icomply-ops/email-campaigns/templates/INDEX.md`

| Template id | Absolute path | Category | Use when |
|---|---|---|---|
| barrier-all | `/workspace/icomply-ops/email-campaigns/templates/barrier-all/` | barriers | Barrier install/repair/service campaign |
| car-parks-all | `/workspace/icomply-ops/email-campaigns/templates/car-parks-all/` | industry | Car parks industry campaign |
| care-homes-all | `/workspace/icomply-ops/email-campaigns/templates/care-homes-all/` | industry | Care homes industry campaign |
| commercial-batch4 | `/workspace/icomply-ops/email-campaigns/templates/commercial-batch4/` | cold | Commercial cold Batch 4 |
| education-all | `/workspace/icomply-ops/email-campaigns/templates/education-all/` | industry | Education industry campaign |
| facilities-management-all | `/workspace/icomply-ops/email-campaigns/templates/facilities-management-all/` | industry | FM industry campaign |
| hmo-student-all | `/workspace/icomply-ops/email-campaigns/templates/hmo-student-all/` | industry | HMO / student industry campaign |
| hotels-all | `/workspace/icomply-ops/email-campaigns/templates/hotels-all/` | industry | Hotels industry campaign |
| housing-associations-all | `/workspace/icomply-ops/email-campaigns/templates/housing-associations-all/` | industry | Housing associations campaign |
| industrial-all | `/workspace/icomply-ops/email-campaigns/templates/industrial-all/` | industry | Industrial industry campaign |
| letting-agents-all | `/workspace/icomply-ops/email-campaigns/templates/letting-agents-all/` | industry | Letting agents industry campaign |
| retail-leisure-all | `/workspace/icomply-ops/email-campaigns/templates/retail-leisure-all/` | industry | Retail / leisure industry campaign |
| monthly-customer-check-in | `/workspace/icomply-ops/email-campaigns/templates/monthly-customer-check-in/` | nurture | Monthly customer compliance check-in |
| services-price-list-pdf-reply | `/workspace/icomply-ops/email-campaigns/templates/services-price-list-pdf-reply/` | reply | PDF price-list reply + call slots (Sarah pattern) |

Each folder: `body.html`, `sarah-bar.html`, `subject.txt`, `README.md`. **vCard attach required** on every send.

## Counts

- Library HTML files: **11** (see MANIFEST.txt)
- Campaign template folders: **14** (see email-campaigns/templates/INDEX.md)
- Combined reusable email HTML: library + campaigns (commercial-batch4 appears in both roots)

## Patched for vCard CONTACT_CARD (2026-10-05)

- `renewals/RENEWAL-REMINDER.html (expanded {{CONTACT_CARD}}/HEADER/FOOTER from placeholders)`
- `outbound-landlords/template-batch7.html`
- `outbound-landlords/template-batch6.html`
- `outbound-landlords/COLD-LANDLORDS-WITH-GAS.html`
- `outbound-landlords/COLD-LANDLORDS-NO-GAS.html`

Originals under `email-templates/*.html` and outbound packs were **not** deleted.

