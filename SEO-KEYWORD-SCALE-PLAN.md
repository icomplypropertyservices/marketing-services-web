# Marketing Services — SEO keyword scale plan — 2026-10-05

## Current ship (this preview)
| Asset | Count | Notes |
|------|------:|-------|
| Service pages (full quality) | 38 | Core + AI + channels expansion |
| Area pages | 8 | Manchester…UK-wide |
| Industries | 5 | Existing |
| Keyword CORE list | 3245 | `website/data/keywords/MARKETING-KEYWORDS-CORE.txt` |
| Keyword ×place P0 stems (17 services × 500 towns) | 8500 | Listed; pages NOT mass-generated yet |
| Keyword ALL (core ∪ sample ×place) | 5715 | `MARKETING-KEYWORDS-ALL.txt` |
| Handoff AI keywords ingested | 186 | From PS HANDOFF file |

## Why pages are not all live yet
Quality bar (unique body, FAQs, CTAs, no doorway city-swap) matches Property main-web / PS locks. Mass-shipping 17×5000 town pages would be thin without SEO lock + enrichment wave.

## Scale path to thousands of **pages**
1. **Lock** CORE + AI handoff + channel stems (done in data/).
2. **P0 pages**: service hubs already live; next wave = priority service × top 50–100 towns with unique local modules (angle, nearby, proof) — target ~1–2k quality pages.
3. **P1**: remaining services × top 500 towns after scorecard.
4. **Full UK TOP5000**: only after enrichment + noindex-off go-live decision (Jack).
5. Service×area matrix routes: `/services/{slug}/{area}/` when template uniqueness passes check-static-export (≥800 words on service paths).

## Planned page math (quality-gated)
| Wave | Formula | Approx pages | Gate |
|------|---------|-------------:|------|
| Now | 38 services + hubs/areas/industries/company | ~70 | Live in this deploy |
| P0 ×place | 17 priority × 75 towns | ~1,275 | Local module + FAQ uniqueness |
| P1 ×place | 17 × 500 towns | ~8,500 | Scorecard + internal links |
| Full | 38 × up to 5000 | tens of thousands | Jack go + enrichment |

## Cross-site keyword scale (ops snapshot)
| Site | Corpus notes |
|------|----------------|
| Property main-web | `keywords.json` ~1450 (+ jobtype/nationwide packs in ops/seo) |
| Professional Services | `PS-KEYWORDS-ALL.txt` ~95k end-client; pack ship paused (review-first) |
| Marketing | This plan + CORE/ALL files; AI handoff owned here |

## Do not
- Ship thin `{service} in {town}` scaffolds
- Attach apex or remove noindex until Jack says go
