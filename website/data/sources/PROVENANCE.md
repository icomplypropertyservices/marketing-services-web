# UK places dataset — provenance

File: `website/data/uk-places.csv` (5,000 rows), built by `website/data/sources/build_uk_places.py`.

## Source

- **GeoNames** Great Britain extract `GB.zip` and `admin2Codes.txt`, downloaded with curl from
  `https://download.geonames.org/export/dump/` on 2026-10-06 (file stamp 2026-10-05).
- Licence: **Creative Commons Attribution 4.0** (https://creativecommons.org/licenses/by/4.0/).
  Attribution: "Contains data from GeoNames (geonames.org), CC BY 4.0." This is shown in the site footer of area pages.
- Why GeoNames: it is the one open, curl-downloadable gazetteer that covers all four UK nations (ONS built-up areas cover
  England and Wales only; OS Open Names excludes Northern Ireland and has no population to rank by). It is also the
  source the locked `MARKETING-KEYWORDS-XPLACE-P0.txt` town list was generated from (slugs such as `enfield-town`,
  `camden-town`, `saint-peters`, `ashford-kent` match GeoNames names), so keyword × place targets line up 1:1.

## Selection

1. Populated places (feature class `P`) with a population value > 0, plus the 32 London borough features (`ADM3` in Greater London).
2. Country from admin1. Local authority from admin2 (prefixes such as "Borough of", "City and Borough of" removed).
3. English region (ONS ITL1) and metropolitan county (Local Government Act 1972) assigned from fixed lookups in the
   script. Scotland, Wales and Northern Ireland use the nation as the region and the council area as the county.
4. Ranking: the 500 locked XPLACE-P0 towns first (in their locked order, all 500 matched), then every other place by
   GeoNames population, cut at 5,000.
5. `nearby` = the 8 closest places in the dataset by great-circle distance between GeoNames coordinates (km, 1 dp).

## What is published and what is not

- Published on pages: place name, local authority, county, region/nation, nearby places and straight-line distances
  (computed from coordinates, rounded; labelled as approximate), distance from our Stockport base.
- **Not published:** population. GeoNames figures for UK places mix settlement and district totals (e.g. Birkenhead
  carries a Wirral-scale figure), so they are used only to order the rollout, never quoted as a statistic.
- No local statistics, clients, results, reviews, accreditations or prices are invented for any place.

## Known limitations

- GeoNames ranking quirks mean a few neighbourhood features rank higher than their real size (e.g. Archway). They are
  real places, so they stay, but the order is "rollout priority", not an official population league table.
- Distances are straight-line between point coordinates, not road distances.
