# Marketing Services: SEO keyword scale plan (updated 2026-10-06, go-live)

## Live now (wave 1, `wave_towns = 500`)
| Asset | Count | Notes |
|------|------:|-------|
| Service hubs | 38 | Core + AI + channels |
| Region / county / town hubs | 12 / 155 / 500 | GeoNames + ONS data, unique local modules |
| Service × town pages | 19,000 | 38 × 500, ≥800 unique body words, near-dup gate 0.60 |
| Keyword pages (audience + AI topic) | 111 | `/marketing/{stem}/` |
| Total HTML pages | 19,835 | |
| Keyword CORE | 3245 | 100% mapped to a live page |
| Keyword ×place P0 (17 × 500) | 8500 | 100% live |
| Handoff AI keywords | 186 | 100% live |

Synonym keywords map to the closest existing page (service hub / service×town) instead of getting duplicate pages. See `dist/keyword-map.csv` after a build.

## Wave config (documented, frozen)
Per Jack (2026-10-06): ship the current wave only; no further waves or packs for now.

| Wave | `wave_towns` | Service×town pages | Approx total pages | Approx dist |
|------|---:|---:|---:|---:|
| 1 (live) | 500 | 19,000 | 19,835 | 1.2 GB |
| 2 | 1000 | 38,000 | ~39k | ~2.3 GB |
| … | +500 per wave | +19,000 | | +1.15 GB |
| 10 (full) | 5000 | 190,000 | ~194k | ~11.5 GB |

9 waves remain to reach the full 38 × 5000. Each wave needs Jack's go and must pass `check-static-export` (word count, near-duplicate, bubble/contacts, sitemap child < 50k).

## Cross-site keyword scale (ops snapshot)
| Site | Corpus notes |
|------|----------------|
| Property main-web | `keywords.json` ~1450 (+ jobtype/nationwide packs in ops/seo) |
| Professional Services | `PS-KEYWORDS-ALL.txt` ~95k end-client; pack ship paused (review-first) |
| Marketing | This plan + CORE/ALL files; AI handoff owned here |

## Do not
- Ship thin `{service} in {town}` scaffolds (check gate enforces it)
- Start a new wave or pack without Jack's go
- Attach the apex or change DNS before the domain is bought (then follow DOMAIN-CUTOVER.md)
