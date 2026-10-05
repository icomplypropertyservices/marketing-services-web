#!/usr/bin/env python3
"""Build website/data/uk-places.csv from the GeoNames GB extract (CC BY 4.0).

Usage (downloads ~4 MB into a temp dir; nothing else is fetched):
  python3 website/data/sources/build_uk_places.py /tmp/geonames

Facts kept per place: GeoNames name, lat/lon, country (admin1), local authority
(admin2), English region / metropolitan county (fixed ONS lookups below), and
the 16 nearest places in the dataset by great-circle distance. Population is
used ONLY to rank and select the top N; it is not published on pages, because
GeoNames population figures for UK places mix settlement and district totals.
"""
import csv, math, os, re, sys, subprocess, zipfile, json

N = 5000
work = sys.argv[1] if len(sys.argv) > 1 else '/tmp/geonames'
os.makedirs(work, exist_ok=True)
base = 'https://download.geonames.org/export/dump/'
for f in ('GB.zip', 'admin2Codes.txt'):
    p = os.path.join(work, f)
    if not os.path.exists(p):
        subprocess.check_call(['curl', '-sSL', '-o', p, base + f])
with zipfile.ZipFile(os.path.join(work, 'GB.zip')) as z:
    z.extract('GB.txt', work)

here = os.path.dirname(os.path.abspath(__file__))
repo = os.path.abspath(os.path.join(here, '..', '..', '..'))

admin2 = {}
for line in open(os.path.join(work, 'admin2Codes.txt'), encoding='utf-8'):
    code, name = line.split('\t')[:2]
    if code.startswith('GB.'):
        admin2[code[3:]] = name

COUNTRY = {'ENG': 'England', 'SCT': 'Scotland', 'WLS': 'Wales', 'NIR': 'Northern Ireland'}

# ONS English regions (ITL1) by unitary / county authority.
REGION = {
 'North East': ['Darlington','County Durham','Gateshead','Hartlepool','Middlesbrough','Newcastle upon Tyne','North Tyneside','Northumberland','Redcar and Cleveland','South Tyneside','Stockton-on-Tees','Sunderland'],
 'North West': ['Blackburn with Darwen','Blackpool','Bolton','Bury','Cumbria','Halton','Knowsley','Lancashire','Liverpool','Manchester','Oldham','Rochdale','Salford','Sefton','St Helens','Stockport','Tameside','Trafford','Warrington','Wigan','Wirral','Cheshire East','Cheshire West and Chester'],
 'Yorkshire and the Humber': ['Barnsley','Bradford','Calderdale','Doncaster','East Riding of Yorkshire','Kingston upon Hull','Kirklees','Leeds','North East Lincolnshire','North Lincolnshire','North Yorkshire','Rotherham','Sheffield','Wakefield','York'],
 'East Midlands': ['Derby','Derbyshire','Leicester','Leicestershire','Lincolnshire','Nottingham','Nottinghamshire','Rutland','North Northamptonshire','West Northamptonshire'],
 'West Midlands': ['Birmingham','Coventry','Dudley','Herefordshire','Sandwell','Shropshire','Solihull','Staffordshire','Stoke-on-Trent','Telford and Wrekin','Walsall','Warwickshire','Wolverhampton','Worcestershire'],
 'East of England': ['Bedford','Central Bedfordshire','Cambridgeshire','Essex','Hertfordshire','Luton','Norfolk','Peterborough','Southend-on-Sea','Suffolk','Thurrock'],
 'London': ['Greater London'],
 'South East': ['Bracknell Forest','Brighton and Hove','Buckinghamshire','East Sussex','Hampshire','Isle of Wight','Kent','Medway','Milton Keynes','Oxfordshire','Portsmouth','Reading','Slough','Southampton','Surrey','West Berkshire','West Sussex','Windsor and Maidenhead','Wokingham'],
 'South West': ['Bath and North East Somerset','Bournemouth, Christchurch and Poole','Bristol','Cornwall','Devon','Dorset','Gloucestershire','Isles of Scilly','North Somerset','Plymouth','Somerset','South Gloucestershire','Swindon','Torbay','Wiltshire'],
}
REGION_OF = {la: r for r, las in REGION.items() for la in las}
# Metropolitan counties (Local Government Act 1972) used as the county hub.
METRO = {
 'Greater Manchester': ['Bolton','Bury','Manchester','Oldham','Rochdale','Salford','Stockport','Tameside','Trafford','Wigan'],
 'Merseyside': ['Knowsley','Liverpool','Sefton','St Helens','Wirral'],
 'West Yorkshire': ['Bradford','Calderdale','Kirklees','Leeds','Wakefield'],
 'South Yorkshire': ['Barnsley','Doncaster','Rotherham','Sheffield'],
 'Tyne and Wear': ['Gateshead','Newcastle upon Tyne','North Tyneside','South Tyneside','Sunderland'],
 'West Midlands (county)': ['Birmingham','Coventry','Dudley','Sandwell','Solihull','Walsall','Wolverhampton'],
}
METRO_OF = {la: m for m, las in METRO.items() for la in las}

def clean_la(n):
    n = n.replace('St. ', 'St ')
    for pre in ('City and County of ', 'City and Borough of ', 'Metropolitan Borough of ', 'Royal Borough of ', 'Borough of ', 'District of ', 'County of ', 'City of ', 'Sir ', 'The '):
        if n.startswith(pre):
            n = n[len(pre):]
    n = re.sub(r' (county borough|County Borough)$', '', n)
    n = n.replace(' Council', '')
    return {'Eilean Siar': 'Na h-Eileanan Siar', 'Aberdeen': 'Aberdeen City', 'Edinburgh': 'City of Edinburgh', 'Glasgow': 'Glasgow City', 'Dundee': 'Dundee City'}.get(n, n)

def slugify(s):
    s = s.lower().replace('&', 'and')
    s = re.sub(r'[^a-z0-9]+', '-', s)
    return s.strip('-')

rows = []
for line in open(os.path.join(work, 'GB.txt'), encoding='utf-8'):
    f = line.rstrip('\n').split('\t')
    borough = f[6] == 'A' and f[7] == 'ADM3' and f[11] == 'GLA'  # London boroughs
    if (f[6] != 'P' and not borough) or not f[14].isdigit() or int(f[14]) <= 0:
        continue
    if borough:
        f[1] = re.sub(r'^(London Borough of |Royal Borough of |Royal )', '', f[1])
    a1, a2 = f[10], f[11]
    if a1 not in COUNTRY or not a2:
        continue
    la = clean_la(admin2.get(a1 + '.' + a2, ''))
    if not la:
        continue
    country = COUNTRY[a1]
    region = REGION_OF.get(la) if country == 'England' else country
    if region is None:
        sys.exit('No region mapping for ' + la)
    county = METRO_OF.get(la, la) if country == 'England' else la
    rows.append(dict(geonameid=f[0], name=f[1], lat=float(f[4]), lon=float(f[5]), feature=f[7], pop=int(f[14]),
                     country=country, region=region, county=county, authority=la))

rows.sort(key=lambda r: -r['pop'])
# de-duplicate same name in the same authority (GeoNames sometimes lists a town twice)
seen, uniq = set(), []
for r in rows:
    k = (r['name'].lower(), r['authority'])
    if k in seen:
        continue
    seen.add(k)
    uniq.append(r)
rows = uniq

# Slugs: plain name for the most populous, otherwise name-authority. Then align
# with the locked XPLACE-P0 keyword list, which was generated from the same source.
used = {}
for r in rows:
    s = slugify(r['name'])
    if s in used:
        s2 = slugify(r['name'] + ' ' + r['county'])
        s = s2 if s2 not in used else slugify(r['name'] + ' ' + r['authority'] + ' ' + r['geonameid'])
    used[s] = r
    r['slug'] = s
ALIAS = {'ashford-kent': ('Ashford', 'Kent'), 'newport-wales': ('Newport', 'Newport')}
for alias, (nm, la) in ALIAS.items():
    for r in rows:
        if r['name'] == nm and r['authority'] == la:
            old = r['slug']
            if old in used and used[old] is r:
                del used[old]
            r['slug'] = alias
            used[alias] = r
            break
# Re-point any plain slug freed by aliasing (e.g. "ashford") to the next place of that name.
for alias, (nm, la) in ALIAS.items():
    plain = slugify(nm)
    if plain not in used:
        for r in rows:
            if r['name'] == nm and r['slug'] != alias:
                del used[r['slug']]
                r['slug'] = plain
                used[plain] = r
                break

xplace = []
for line in open(os.path.join(repo, 'website/data/keywords/MARKETING-KEYWORDS-XPLACE-P0.txt')):
    line = line.strip()
    if line.startswith('seo-'):
        xplace.append(line[4:])
    else:
        break
xset = set(xplace)
by_slug = {r['slug']: r for r in rows}
missing = [s for s in xplace if s not in by_slug]

# Rank: XPLACE-P0 towns first (locked order), then everything else by population.
ordered = [by_slug[s] for s in xplace if s in by_slug]
ordered += [r for r in rows if r['slug'] not in xset]
top = ordered[:N]

def km(a, b):
    p1, p2 = math.radians(a['lat']), math.radians(b['lat'])
    dl = math.radians(b['lon'] - a['lon'])
    c = math.sin(p1) * math.sin(p2) + math.cos(p1) * math.cos(p2) * math.cos(dl)
    return 6371.0 * math.acos(max(-1.0, min(1.0, c)))

grid = {}
for i, r in enumerate(top):
    grid.setdefault((int(r['lat'] * 4), int(r['lon'] * 2.5)), []).append(i)
for i, r in enumerate(top):
    gy, gx = int(r['lat'] * 4), int(r['lon'] * 2.5)
    cand = []
    rad = 1
    while len(cand) < 17 and rad < 40:
        cand = [j for dy in range(-rad, rad + 1) for dx in range(-rad, rad + 1) for j in grid.get((gy + dy, gx + dx), []) if j != i]
        rad += 1
    rad += 1
    cand = [j for dy in range(-rad, rad + 1) for dx in range(-rad, rad + 1) for j in grid.get((gy + dy, gx + dx), []) if j != i]
    near = sorted(((km(r, top[j]), j) for j in cand))[:16]
    r['nearby'] = ';'.join(f"{top[j]['slug']}:{d:.1f}" for d, j in near)

out = os.path.join(repo, 'website/data/uk-places.csv')
with open(out, 'w', newline='', encoding='utf-8') as fh:
    w = csv.writer(fh)
    w.writerow(['rank', 'slug', 'name', 'country', 'region', 'county', 'authority', 'lat', 'lon', 'geonameid', 'feature', 'xplace_p0', 'nearby'])
    for i, r in enumerate(top, 1):
        w.writerow([i, r['slug'], r['name'], r['country'], r['region'], r['county'], r['authority'], f"{r['lat']:.5f}", f"{r['lon']:.5f}", r['geonameid'], r['feature'], 1 if r['slug'] in xset else 0, r['nearby']])
print(json.dumps({'written': len(top), 'xplace_towns': len(xplace), 'xplace_missing': missing,
                  'countries': {c: sum(1 for r in top if r['country'] == c) for c in COUNTRY.values()}}, indent=1))
