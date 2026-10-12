# iComply MASTER TASK LIST (Jack, 2026-10-06 01:23 Berlin)
RULE: No agent may put any item on hold/lock/freeze/park. Blocked = name the blocker + who clears it + next action. Checked every 30 min.
Status values: DOING | BLOCKED(blocker, owner) | DONE(evidence)

## Property (main-web) — owner Website Team 48e150a6 + SEO Manager 87afdb0d
- [ ] WhatsApp corner bubble + contact details sitewide — DOING (WT: PR #125 head 6fd9fc1; Ellie Quality+merge next after #114)
- [ ] Networking / Wi-Fi / Bluetooth + IT support pack — DOING (P0: PR #126 head 2ec901d, SEO CONFIRMed; next = Ellie draft→ready + Quality smoke. P1 wave-1 being built, stacked on #126)
- [ ] AOV / smoke control DEEP — DOING (draft PR #127 head 0c30d7f, P0 179 1:1, checkers PASS; next = SEO score → Ellie ready+Quality)
- [ ] Barriers DEEP incl manual + width/height restrictions — DOING (in PR #127 head 0c30d7f; manual + width/height, zero wind; next = SEO score → Ellie)
- [ ] Fencing DEEP (P0 108) — DOING (WT margin worker, pack 1 of 7)
- [ ] Perimeter detection DEEP (P0 80) — DOING (WT margin worker, next after fencing)
- [ ] Electric gates DEEP — DOING (WT margin worker sequence)
- [ ] Fire dampers DEEP — DOING (WT margin worker sequence)
- [ ] AHU DEEP — DOING (WT margin worker sequence)
- [ ] BMS DEEP — DOING (WT margin worker sequence)
- [ ] Air conditioning DEEP — DOING (WT margin worker sequence)
- [ ] Facial recognition + ANPR — DOING (WT second pack worker, after HMO/shop fitting)
- [ ] HMO services (licence/design/build, P0 185) — DOING (WT second pack worker, pack 1)
- [ ] Shop fitting (separate, P0 75) — DOING (WT second pack worker, pack 2)
- [ ] Gap-fill packs (EV, solar, heat pumps, smart home, nurse call, water hygiene, damp/mould, roofing/glazing, auto doors/shutters, CCTV cloud, access, pest, drainage) — DOING (WT second pack worker, after facial/ANPR + AI landings)
- [ ] W2 wave — DOING (WT second pack worker, last in its sequence)
- [ ] Burnley + 50mi dual ring full local (#112) — BLOCKED(#112 on main but prod deploy stalled: GitHub Actions runners/minutes; owner Ellie/Jack billing; next: re-run Deploy to Netlify once minutes restored)
- [ ] CLOSE-15 hubs restore — BLOCKED(same #112 prod deploy stall; owner Ellie/Jack; next: deploy re-run)
- [ ] Open PR scorecards (#112/#120 etc) merged to prod — DOING (#114 head a96c411 at Ellie gate; #125 next; then WT rebases #116 → #119; #120 back in WT queue for rework)
- [ ] Page scores to 100% (thin page upgrades) — DOING (every new pack ≥800 distinct words/3 FAQs/3 images/full meta)
- [ ] GSC sitemap refresh after new packs — BLOCKED(packs not on prod yet; owner Ellie merge+deploy; next: SEO resubmits sitemaps after each pack hits prod)
- [ ] AI landings on Property (80, P0 15) — DOING (WT second pack worker after facial/ANPR; `ai-property-services` ships as a full page, other feature slugs ship as enquire/coming-soon pages per AI Services Manager copy ruling, no live claims)
- [ ] Building P0 #120 rework — DOING (WT reworking; next: WT posts new head → SEO re-scores + relays SHA to PE)
- [ ] Already-locked follow-on waves (W1b, keywords P1, electrical P1, security P1, building P1, fire×TOP5000) — DOING (WT ships from locks; SEO scores each draft PR as it opens)
- [ ] SEO scorecards for every new pack PR — DOING (SEO Manager scores each WT draft SHA within the hour; #126 CONFIRMed)

## Professional Services — owner Professional Services be226e67 + PS Website e8142c8f + PS SEO ff9c7eda
- [x] P0 700 + contact/WA live (PR #7)
- [ ] P0 uniqueness rewrite (median 0.07 -> >=0.30) — DOING
- [ ] On-demand FULL UK x place renderer (108,574 kw x 34,235 places) to prod — DOING
- [ ] W1 sub-waves (P0 x TOP5000 by population) enabled on prod as soon as renderer is up, without waiting on the P0 re-score. PS SEO samples each one live, and a FAIL gets fixed in place, never un-enabled — DOING (PS Website + PS SEO)
- [ ] Wave 2 (rest of the 34,235 places), then P1/P2 keyword tiers, rolling on right after W1 — DOING (PS Website)
- [ ] noindex off — BLOCKED(canonicals point to apex, which isn't attached yet; owner Jack Namecheap DNS; next: PS Website flips noindex the same day apex goes live)
- [ ] Apex icomplyprofessionalservices.co.uk — BLOCKED(Jack buys on Namecheap 2026-10-06; DNS sheet ready)
- [ ] PS SEO: re-score P0 when the uniqueness fix lands (scripts in professional-services/seo/p0-prod-strict-2026-10-06/) — DOING (PS SEO, runs on each Website ping)
- [ ] PS SEO: x place live STRICT sampler (seo/xplace-strict/run.py) to fix-list each sub-wave — DOING (PS SEO; the x place URL pattern comes from PS Website's renderer)
- [x] PS SEO: P1/P2 x place locks released (seo/P1-P2-XPLACE-RELEASE-2026-10-06.md)

## Marketing Services — owner Marketing Manager f7c82bc5
- [ ] Wave 1 (500 towns x 38 services + keyword pages, ~19.8k pages, all gates passing) to prod — DOING (the first golive upload stalled at 00:54; re-deploy running, then --prod)
- [ ] Scale to the full ~5,000 towns x 38 services in steps (1k, 2.5k, 5k), each step to --prod after gates — DOING (risk: disk, ~12 GB needed vs ~20 GB free; HTML weight being cut. If blocked = BLOCKED(disk, Disk Saver/Marketing))
- [ ] Contact (WA 07517806082, icomplypropertyservices@gmail.com) + corner WhatsApp bubble sitewide — DOING (built into wave 1, build check enforces it; goes live with --prod)
- [ ] noindex off — DOING (in wave 1 build; live with --prod)
- [ ] NETLIFY_AUTH_TOKEN GitHub secret for CI deploys — BLOCKED(Jack: create a Netlify personal access token and add it to the marketing-services-web repo secrets; deploys from the box CLI meanwhile)
- [ ] Apex icomplymarketingservices.co.uk — BLOCKED(Jack buys on Namecheap 2026-10-06; next = follow /workspace/marketing-services-web/DOMAIN-CUTOVER.md: DNS records, Netlify domain + SSL, SITE_URL switch, redeploy, GSC submit)

## Ops
- [ ] Review request after payment (auto)
- [ ] Branded docs/review card unified
- [ ] Taylor Telegram restore + Bloom brief
- [ ] TELEGRAM_ALLOWED_USER_IDS for Jack bot
- [ ] Link Building Manager free directories
- [ ] Contractor Finder / Quote Manager / Work Pipeline running
