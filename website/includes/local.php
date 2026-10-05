<?php
declare(strict_types=1);

/**
 * Nationwide pages: UK places dataset, wave selection, service x town pages,
 * town / county / region hubs and keyword pages. All local facts come from
 * website/data/uk-places.csv (GeoNames, CC BY 4.0) and fixed ONS lookups.
 */

function ms_slug(string $s): string
{
    $s = strtolower(str_replace('&', 'and', $s));
    return trim((string) preg_replace('/[^a-z0-9]+/', '-', $s), '-');
}

/** Deterministic integer from a seed string. */
function ms_seed(string $seed): int
{
    // Non-linear hash (crc32 is linear, so same-length town names produced correlated picks).
    return (int) hexdec(substr(hash('xxh3', $seed), 0, 12));
}

/** Deterministic pick of $k items (order shuffled by seed). Keys preserved as list. */
function ms_pick(array $items, int $k, string $seed): array
{
    $items = array_values($items);
    $keyed = [];
    foreach ($items as $i => $it) {
        $keyed[] = [ms_seed($seed . '|' . $i), $it];
    }
    usort($keyed, fn ($a, $b) => $a[0] <=> $b[0]);
    return array_map(fn ($x) => $x[1], array_slice($keyed, 0, $k));
}

function ms_choose(array $items, string $seed)
{
    $items = array_values($items);
    return $items[ms_seed($seed) % count($items)];
}

function ms_places(): array
{
    static $places = null;
    if ($places !== null) {
        return $places;
    }
    $places = [];
    $fh = fopen(MS_ROOT . '/data/uk-places.csv', 'r');
    $head = fgetcsv($fh, null, ',', '"', '');
    while (($row = fgetcsv($fh, null, ',', '"', '')) !== false) {
        $r = array_combine($head, $row);
        $near = [];
        foreach (explode(';', $r['nearby']) as $pair) {
            if ($pair === '') {
                continue;
            }
            [$ns, $d] = explode(':', $pair);
            $near[] = [$ns, (float) $d];
        }
        $r['nearby'] = $near;
        $r['rank'] = (int) $r['rank'];
        $r['lat'] = (float) $r['lat'];
        $r['lon'] = (float) $r['lon'];
        $r['region_slug'] = ms_slug($r['region']);
        $r['county_slug'] = ms_slug($r['county']);
        $places[$r['slug']] = $r;
    }
    fclose($fh);
    return $places;
}

function ms_wave_count(): int
{
    $env = getenv('WAVE_TOWNS');
    $n = (is_string($env) && ctype_digit($env)) ? (int) $env : (int) (ms_config()['wave_towns'] ?? 0);
    return max(0, min($n, count(ms_places())));
}

/** Towns in the live wave, in rank order. */
function ms_wave_places(): array
{
    static $w = null;
    if ($w === null) {
        $w = array_slice(ms_places(), 0, ms_wave_count(), true);
    }
    return $w;
}

function ms_in_wave(string $slug): bool
{
    return isset(ms_wave_places()[$slug]);
}

function ms_km(array $a, array $b): float
{
    $p1 = deg2rad($a['lat']);
    $p2 = deg2rad($b['lat']);
    $dl = deg2rad($b['lon'] - $a['lon']);
    $c = sin($p1) * sin($p2) + cos($p1) * cos($p2) * cos($dl);
    return 6371.0 * acos(max(-1.0, min(1.0, $c)));
}

function ms_dist(float $km): string
{
    $mi = $km * 0.621371;
    if ($km < 10) {
        return number_format($km, 1) . ' km (' . number_format($mi, 1) . ' miles)';
    }
    return number_format($km, 0) . ' km (' . number_format($mi, 0) . ' miles)';
}

function ms_base_km(array $p): float
{
    static $base = null;
    $base ??= ms_places()['stockport'];
    return ms_km($base, $p);
}

function ms_regions(): array
{
    return ms_data('regions');
}

/** County => [region_slug, name, towns[]] (full dataset). */
function ms_counties(): array
{
    static $c = null;
    if ($c !== null) {
        return $c;
    }
    $c = [];
    foreach (ms_places() as $slug => $p) {
        $k = $p['county_slug'];
        $c[$k] ??= ['slug' => $k, 'name' => $p['county'], 'region' => $p['region'], 'region_slug' => $p['region_slug'], 'country' => $p['country'], 'authorities' => [], 'towns' => []];
        $c[$k]['towns'][] = $slug;
        $c[$k]['authorities'][$p['authority']] = true;
    }
    return $c;
}

function ms_county_label(string $county): string
{
    return $county === 'West Midlands (county)' ? 'the West Midlands county' : $county;
}

function ms_where(array $p): string
{
    // "Bolton, in the Bolton borough of Greater Manchester (North West England)"
    $auth = $p['authority'];
    $county = $p['county'];
    $nation = $p['country'];
    $regionTxt = $nation === 'England' ? ($p['region'] === 'London' ? 'London' : ($p['region'] === 'Yorkshire and the Humber' ? 'Yorkshire and the Humber' : 'the ' . $p['region'] . ' region') . ', England') : $nation;
    if ($county !== $auth) {
        return $p['name'] . ' is in the ' . $auth . ' local authority area of ' . ms_county_label($county) . ', in ' . $regionTxt . '.';
    }
    if ($auth === $p['name']) {
        return $p['name'] . ' is its own local authority area in ' . $regionTxt . '.';
    }
    return $p['name'] . ' is in ' . $auth . ', in ' . $regionTxt . '.';
}

function ms_place_kind(array $p): string
{
    switch ($p['feature']) {
        case 'ADM3':
            return $p['name'] . ' is one of the London boroughs, so buyers here often search by borough or by the neighbourhoods within it as well as for "London".';
        case 'PPLX':
            return $p['name'] . ' is a district within a larger built-up area, so it pays to treat it as its own micro-market rather than relying on the wider town or city name.';
        case 'PPLC':
            return 'As the UK capital, ' . $p['name'] . ' is searched both as a whole and borough by borough, so location targeting needs care.';
        case 'PPLA':
        case 'PPLA2':
            return $p['name'] . ' is an administrative centre for its area, which tends to concentrate offices, high-street businesses and local search demand.';
        default:
            return 'Buyers in and around ' . $p['name'] . ' typically search for the town name, for "near me", or for the neighbouring places they also cover.';
    }
}

function ms_working_line(array $p): string
{
    $km = ms_base_km($p);
    $d = ms_dist($km);
    if ($p['slug'] === 'stockport') {
        return 'Stockport is where iComply Marketing Services is based, so meetings, filming and photography here are straightforward to arrange.';
    }
    if ($km <= 25) {
        return $p['name'] . ' is roughly ' . $d . ' in a straight line from our Stockport base, so face-to-face meetings, filming and photography are easy to arrange alongside remote delivery.';
    }
    if ($km <= 70) {
        return $p['name'] . ' is roughly ' . $d . ' from our Stockport base. Most work is delivered remotely, and in-person planning sessions or shoots can be arranged when they add value.';
    }
    return $p['name'] . ' is roughly ' . $d . ' from our Stockport base, so we deliver remotely with video calls, shared dashboards and monthly reporting, and plan any on-site photography, video or workshops in advance.';
}

function ms_near_names(array $p, int $n = 3, float $maxKm = 999): array
{
    $places = ms_places();
    $out = [];
    foreach ($p['nearby'] as [$ns, $d]) {
        if ($d <= $maxKm && isset($places[$ns])) {
            $out[] = [$places[$ns], $d];
        }
        if (count($out) >= $n) {
            break;
        }
    }
    return $out;
}

function ms_list_names(array $items): string
{
    $names = array_map(fn ($x) => $x[0]['name'], $items);
    if (count($names) <= 1) {
        return implode('', $names);
    }
    $last = array_pop($names);
    return implode(', ', $names) . ' and ' . $last;
}

/** Service channel family, used for local planning copy. */
function ms_service_family(string $slug): string
{
    static $map = [
        'seo' => 'search', 'ai-seo' => 'search', 'franchise-seo' => 'search', 'directories-listings' => 'search', 'google-business-profile' => 'search', 'content-marketing' => 'search', 'ai-content' => 'search',
        'google-ads' => 'paid', 'meta-ads' => 'paid', 'linkedin-tiktok-ads' => 'paid', 'youtube-ads' => 'paid', 'programmatic-ads' => 'paid', 'retargeting' => 'paid', 'ad-networks' => 'paid', 'ai-ads-optimisation' => 'paid', 'podcast-ads' => 'paid', 'aso' => 'paid',
        'ai-chatbots' => 'ai', 'ai-receptionist' => 'ai', 'ai-review-replies' => 'ai', 'automation-crm' => 'ai', 'it-support' => 'ai',
        'branding' => 'creative', 'video-production' => 'creative', 'photography' => 'creative', 'drone-media' => 'creative', 'signage-wraps' => 'creative', 'websites-landing-pages' => 'creative', 'cro' => 'creative', 'funnel-builds' => 'creative',
        'email-marketing' => 'relationship', 'sms-marketing' => 'relationship', 'reputation-reviews' => 'relationship', 'social-media' => 'relationship', 'influencer-marketing' => 'relationship',
        'tender-bid-packs' => 'b2b', 'employer-branding' => 'b2b', 'marketing-strategy' => 'b2b',
    ];
    return $map[$slug] ?? 'b2b';
}

/** Local planning paragraph: uses real nearby places and computed distances. */
function ms_local_plan(string $slug, array $s, array $p): string
{
    $ctx = ms_ctx($s);
    $town = $p['name'];
    $n10 = ms_near_names($p, 5, 10.0);
    $n25 = ms_near_names($p, 8, 25.0);
    $first = ms_near_names($p, 2);
    $ring = $n10 !== [] ? ms_list_names($n10) : ms_list_names($first);
    $wider = $n25 !== [] ? ms_list_names($n25) : ms_list_names($first);
    $fam = ms_service_family($slug);
    $v = ms_seed($slug . $p['slug']) % 2;
    $t = [
        'search' => [
            'For ' . $ctx . ', the job in ' . $town . ' is to be the obvious result when someone searches for what you do with the town name, with "near me", or with a neighbouring place such as ' . $ring . '. That means a page that genuinely describes your work in ' . $town . ', a Google Business Profile with the right categories and service areas, and consistent business details across the directories that rank locally.',
            'Search visibility around ' . $town . ' is won page by page. We map the services you sell against the places you cover, starting with ' . $town . ' and spreading to ' . $wider . ', and make sure each page has its own content, FAQs and internal links instead of a copy with the town name swapped.',
        ],
        'paid' => [
            'With ' . $ctx . ' we can target ' . $town . ' by radius, postcode district or named location. A tight radius around ' . $town . ' would take in ' . $ring . '; a wider one reaches ' . $wider . '. We exclude the areas you do not serve so budget is spent where you can take the work.',
            'Paid campaigns for ' . $town . ' start with geography. We agree which of the surrounding places, for example ' . $wider . ', you want enquiries from, set location targeting to match, and send clicks to a landing page that talks about ' . $town . ' rather than a generic homepage.',
        ],
        'ai' => [
            'For a business serving ' . $town . ', ' . $ctx . ' is about answering every enquiry properly, including the ones that arrive out of hours or while you are on a job. We configure it with your real service area, so it can tell callers and visitors from ' . $town . ', ' . $ring . ' whether you cover them and what happens next.',
            $ctx === 'IT support' ? 'IT support for a ' . $town . ' business is mostly remote, with clear processes for anything that needs a visit. We document your set-up so that marketing tools, email, devices and accounts keep working across every site you run, whether that is only ' . $town . ' or also ' . $wider . '.' : 'We set up ' . $ctx . ' around how enquiries actually reach a ' . $town . ' business: phone, website, Google Business Profile and messaging. Routing rules reflect the places you cover, such as ' . $wider . ', so the right person gets the right enquiry with the details already captured.',
        ],
        'creative' => [
            'Good ' . $ctx . ' for a ' . $town . ' business shows real work, real people and real places. Where a project needs on-site content, we plan shoots around your jobs and premises; where it does not, we work from your existing assets and brand guidelines.',
            'For ' . $town . ' audiences, ' . $ctx . ' has to feel local and credible. We use your own projects, team and locations rather than stock imagery wherever possible, and make sure the finished assets work on your website, Google Business Profile, ads and social channels across ' . $wider . '.',
        ],
        'relationship' => [
            'With ' . $ctx . ', most of the value comes from customers you have already served in and around ' . $town . '. We segment by location and service where it helps, so customers in ' . $ring . ' get messages that are relevant to them.',
            'For a ' . $town . ' business, ' . $ctx . ' turns one-off customers into repeat work and referrals. We plan messages around your real customer journey and the places you serve, from ' . $town . ' out to ' . $wider . ', and keep everything consistent with UK GDPR and PECR.',
        ],
        'b2b' => [
            'Businesses buying ' . $ctx . ' in ' . $town . ' usually want a partner who understands their market as well as the method. We start from your own goals, competitors and capacity, then plan for the area you actually trade in, whether that is only ' . $town . ' or also ' . $wider . '.',
            'For ' . $ctx . ', location matters less than fit, but it still shapes the plan: who your competitors are around ' . $town . ', which buyers in ' . $ring . ' you want to reach, and how far your team can realistically travel or deliver.',
        ],
    ];
    return $t[$fam][$v];
}

/** Local FAQ pool (real data only). */
function ms_local_faqs(string $slug, array $s, array $p): array
{
    $ctx = ms_ctx($s);
    $town = $p['name'];
    $near = ms_near_names($p, 3);
    $nearTxt = ms_list_names($near);
    $km = ms_base_km($p);
    $nation = $p['country'];
    $pool = [
        ['Do you provide ' . $ctx . ' for businesses in ' . $town . '?', 'Yes. ' . ms_working_line($p) . ' Every engagement starts with a free review and a written proposal.'],
        ['Can ' . $ctx . ' cover ' . $town . ' and nearby places like ' . $nearTxt . '?', 'Yes. We plan around the full area you serve. ' . ($near !== [] ? 'In our place data, ' . $near[0][0]['name'] . ' is about ' . ms_dist($near[0][1]) . ' from ' . $town . ', so ' : '') . 'campaigns, pages and listings can be set to include or exclude each place so you only pay for enquiries you can take.'],
        ['Do I need an address in ' . $town . ' to appear in local results?', 'Not necessarily. Google allows service-area businesses to hide their address and list the areas they serve instead. Map rankings still favour proximity, so location pages, reviews and citations matter for areas further from your base.'],
        ['How much does ' . $ctx . ' cost for a ' . $town . ' business?', 'It depends on scope, competition and how much of the work you want us to do, so it is quoted individually (POA) after a free review. Any ad spend is paid directly by you to the platform.'],
        ['Can you help if we have branches across ' . ($nation === 'England' ? ($p['region'] === 'London' ? 'London' : 'the ' . $p['region']) : $nation) . '?', 'Yes. Multi-location businesses get a structure with one page, listing and campaign set per branch or service area, reported separately so you can compare ' . $town . ' with your other locations.'],
        ['How will we know what ' . $ctx . ' is bringing in from ' . $town . '?', 'We set up call, form and WhatsApp tracking before work starts. Analytics and ad platforms can report by location, so we can show enquiries from ' . $town . ' separately from the wider area where the data allows.'],
        ['Will you meet us in person in ' . $town . '?', $km <= 70 ? 'Usually, yes, when it is useful. ' . $town . ' is about ' . ms_dist($km) . ' from our Stockport base. Day-to-day work is remote, with video calls and shared reporting.' : 'Most of our work is remote, with video calls, shared dashboards and monthly reports. ' . $town . ' is about ' . ms_dist($km) . ' from our Stockport base, so on-site visits are planned in advance where a project needs them.'],
        ['Is ' . $ctx . ' different in ' . $nation . '?', match ($nation) {
            'Scotland' => 'The method is the same, but the wording often is not. Scotland has its own legal system and property processes, so we adapt terminology, examples and landing pages for Scottish buyers.',
            'Wales' => 'The method is the same. In Wales we also consider bilingual Welsh and English content where your customers expect it, and use Welsh place names correctly.',
            'Northern Ireland' => 'The method is the same, but many Northern Ireland businesses also serve the Republic of Ireland, so we may separate targeting, currency and copy for each market.',
            default => 'The core method is the same across England. What changes is the local competition, the places you cover and how buyers in your area search, which is why each plan starts with a review of your market.',
        }],
        ['What do you need from us to start ' . $ctx . ' in ' . $town . '?', 'Your website address, the services and places you want to grow (for example ' . $town . ($near !== [] ? ' and ' . $near[0][0]['name'] : '') . '), access to any existing accounts, and an idea of the work you most want more of.'],
    ];
    return ms_pick($pool, 3, 'lf' . $slug . $p['slug']);
}

function ms_facts_card(array $p, string $context): string
{
    $near = ms_near_names($p, 3);
    $nearTxt = implode(', ', array_map(fn ($x) => $x[0]['name'] . ' (' . number_format($x[1], 1) . ' km)', $near));
    $c = ms_config();
    return '<aside class="side-card"><h2>' . ms_h($p['name']) . ' at a glance</h2><ul class="facts">'
        . '<li><strong>Local authority</strong>' . ms_h($p['authority']) . '</li>'
        . ($p['county'] !== $p['authority'] ? '<li><strong>County</strong>' . ms_h($p['county']) . '</li>' : '')
        . '<li><strong>' . ($p['country'] === 'England' ? 'Region' : 'Nation') . '</strong>' . ms_h($p['country'] === 'England' ? $p['region'] . ', England' : $p['country']) . '</li>'
        . '<li><strong>Nearest places</strong>' . ms_h($nearTxt) . '</li>'
        . '<li><strong>From our Stockport base</strong>' . ms_h($p['slug'] === 'stockport' ? 'Home town' : ms_dist(ms_base_km($p))) . '</li></ul>'
        . '<a class="btn btn-primary btn-block" href="/contact/#quote">Get a free quote</a>'
        . '<a class="btn btn-outline btn-block" href="' . ms_tel() . '">' . ms_icon('phone', 'icon icon-sm') . ms_h($c['phone_display']) . '</a>'
        . '<a class="btn btn-whatsapp btn-block" href="' . ms_h(ms_whatsapp($context)) . '" target="_blank" rel="noopener">' . ms_icon('chat', 'icon icon-sm') . 'WhatsApp us</a>'
        . '<p class="side-fine">Distances are straight-line from GeoNames coordinates.</p></aside>';
}

function ms_region_trail(array $p): array
{
    return [['Areas', '/areas/'], [$p['region'], '/areas/region/' . $p['region_slug'] . '/'], [$p['county'], '/areas/county/' . $p['county_slug'] . '/']];
}

/* ---------- Service x town ---------- */
function ms_page_service_town(string $slug, array $s, array $p): array
{
    $services = ms_data('services');
    $regions = ms_regions();
    $reg = $regions[$p['region_slug']];
    $town = $p['name'];
    $seed = $slug . '|' . $p['slug'];
    $ctx = ms_ctx($s);
    $context = $ctx . ' in ' . $town;
    $nearAll = ms_near_names($p, 8);
    $wider = array_slice(ms_near_names($p, 16), 8);

    $h1 = ms_choose([
        $s['long'] . ' in ' . $town,
        $s['name'] . ' for ' . $town . ' businesses',
        $s['name'] . ' in ' . $town . ', ' . $p['county'],
    ], 'h1' . $seed);

    $intro = ms_pick($s['intro'], count($s['intro']) > 3 ? 3 : 2, 'in' . $seed);
    $pains = ms_pick($s['pains'], 2, 'pa' . $seed);
    $inc = ms_pick($s['included'], 5, 'ic' . $seed);
    $steps = ms_pick($s['process'], 3, 'st' . $seed);
    usort($steps, fn ($a, $b) => array_search($a, $s['process'], true) <=> array_search($b, $s['process'], true));
    $measure = ms_pick($s['measure'], 3, 'me' . $seed);
    $svcFaqs = ms_pick($s['faqs'], 2, 'sf' . $seed);

    $industries = ms_data('industries');
    $indSlug = ms_choose(array_keys($industries), 'ind' . $seed);
    $ind = $industries[$indSlug];
    $indParas = ms_pick($ind['body'], 1, 'ip' . $seed);
    $indPoints = ms_pick($ind['points'], 3, 'ipt' . $seed);
    $auds = ms_data('audiences');
    $audSlug = ms_choose(array_keys($auds), 'aud' . $seed);
    $aud = $auds[$audSlug];
    $audPara = ms_choose($aud['context'], 'ap' . $seed);
    $audRules = ms_pick($aud['rules'], 2, 'ar' . $seed);
    $faqs = array_merge(ms_local_faqs($slug, $s, $p), $svcFaqs, [ms_choose($aud['faqs'], 'af' . $seed)]);
    $faqs = ms_pick($faqs, count($faqs), 'fo' . $seed);
    // Pairing: one related service and one paragraph of its own copy
    $pairSlug = ms_choose(array_values(array_filter($s['related'], fn ($r) => isset($services[$r]))), 'pr' . $seed);
    $pair = $services[$pairSlug];
    $pairPara = ms_choose($pair['intro'], 'pp' . $seed);

    $painsHtml = '';
    foreach ($pains as $x) {
        $painsHtml .= '<li>' . ms_icon('check', 'icon icon-sm') . '<span>' . ms_h($x) . '</span></li>';
    }
    $incHtml = '';
    foreach ($inc as [$t, $d]) {
        $incHtml .= '<div class="card inc-card">' . ms_icon('check', 'icon tick') . '<div><h3>' . ms_h($t) . '</h3><p>' . ms_h($d) . '</p></div></div>';
    }
    $mHtml = '';
    foreach ($measure as $m) {
        $mHtml .= '<li>' . ms_icon('target', 'icon icon-sm') . '<span>' . ms_h($m) . '</span></li>';
    }
    $nearHtml = '';
    foreach ($nearAll as [$np, $d]) {
        $label = ms_h($s['name'] . ' in ' . $np['name']) . ' <span class="muted">· ' . ms_h(ms_dist($d)) . '</span>';
        $nearHtml .= '<li>' . (ms_in_wave($np['slug']) ? '<a href="/services/' . $slug . '/' . $np['slug'] . '/">' . $label . '</a>' : $label) . '</li>';
    }
    $others = array_values(array_unique(array_merge($s['related'], ms_pick(array_keys($services), 8, 'os' . $seed))));
    $others = array_slice(array_values(array_filter($others, fn ($o) => $o !== $slug && isset($services[$o]))), 0, 8);
    $otherHtml = '';
    foreach ($others as $o) {
        $otherHtml .= '<li><a href="/services/' . $o . '/' . $p['slug'] . '/">' . ms_icon($services[$o]['icon'], 'icon icon-sm') . '<span>' . ms_h($services[$o]['name'] . ' in ' . $town) . '</span></a></li>';
    }
    $rulesHtml = '';
    foreach ($audRules as $r) {
        $rulesHtml .= '<li>' . ms_icon('shield', 'icon icon-sm') . '<span>' . ms_h($r) . '</span></li>';
    }
    $indHtml = '';
    foreach ($indPoints as $x) {
        $indHtml .= '<li>' . ms_icon('check', 'icon icon-sm') . '<span>' . ms_h($x) . '</span></li>';
    }
    $curated = ms_data('areas')[$p['slug']] ?? null;

    $localParas = [ms_where($p) . ' ' . ms_place_kind($p)];
    if ($curated) {
        $localParas[] = $curated['intro'];
    }
    $localParas[] = ms_local_plan($slug, $s, $p);
    $localParas[] = ms_choose([$reg['intro'], $reg['tip']], 'rg' . $seed) . ' ' . ms_working_line($p);

    $lede = $s['lede'] . ' Planned for businesses in ' . $town . ($nearAll !== [] ? ' and nearby ' . ms_list_names(array_slice($nearAll, 0, 2)) : '') . '.';

    $body = ms_hero($s['name'] . ' · ' . $town . ', ' . $p['county'], $h1, $lede, $context, ms_facts_card($p, $context))
        . '<section class="section"><div class="wrap content-grid"><div class="prose">'
        . '<h2>' . ms_h($s['name']) . ' for businesses in and around ' . ms_h($town) . '</h2>' . ms_paras($localParas)
        . '<h2>Sound familiar?</h2><ul class="tick-list">' . $painsHtml . '</ul>'
        . '<h2>Why ' . ms_h($ctx) . ' is worth doing properly</h2>' . ms_paras($intro)
        . '<!--b--><div class="mid-cta"><p>Want to know what ' . ms_h($ctx) . ' could do for your business in ' . ms_h($town) . '? We will review your current position for free.</p><a class="btn btn-primary btn-sm" href="/contact/?service=' . $slug . '#quote">Get my free review</a></div><!--/b-->'
        . '<h2>Who it suits</h2><p>' . ms_h($s['fit']) . '</p>'
        . '<h2>Pairs well with ' . ms_h(ms_ctx($pair)) . '</h2><p>' . ms_h($pairPara) . '</p><p><a href="/services/' . $pairSlug . '/' . $p['slug'] . '/">' . ms_h($pair['name'] . ' in ' . $town) . '</a></p>'
        . '</div>' . ms_quote_aside('Get a ' . $ctx . ' quote for ' . $town, 'Tell us what you sell, which places around ' . $town . ' you cover and what a good month looks like.', $context) . '</div></section>'
        . '<section class="section section-alt"><div class="wrap"><div class="section-head"><p class="eyebrow">What you get</p><h2>What ' . ms_h($ctx) . ' for a ' . ms_h($town) . ' business can include</h2><p>Scoped in a written proposal. Typical deliverables:</p></div><div class="grid grid-2">' . $incHtml . '</div></div></section>'
        . '<section class="section"><div class="wrap split"><div><p class="eyebrow">How we deliver</p><h2>How a ' . ms_h($ctx) . ' project runs</h2><p>Clear stages and agreed priorities, whether you are in ' . ms_h($town) . ' or anywhere else in ' . ms_h($p['country'] === 'England' ? 'England' : $p['country']) . '.</p></div>' . ms_steps($steps) . '</div></section>'
        . '<section class="section section-navy"><div class="wrap split"><div><p class="eyebrow">Accountability</p><h2>What we measure</h2><p>Tracking is set up before work starts. Where the data allows, results from ' . ms_h($town) . ' are reported separately from the rest of your area.</p></div><ul class="measure-list">' . $mHtml . '</ul></div></section>'
        . '<section class="section"><div class="wrap content-grid"><div class="prose">'
        . '<h2>' . ms_h($ind['name']) . ' firms in ' . ms_h($town) . '</h2>' . ms_paras($indParas) . '<ul class="tick-list">' . $indHtml . '</ul>'
        . '<p><a href="/industries/' . $indSlug . '/">More on marketing for ' . ms_h(strtolower($ind['name'])) . '</a></p>'
        . '<h3>Marketing for ' . ms_h($aud['label']) . ' in ' . ms_h($town) . '</h3><p>' . ms_h($audPara) . '</p><ul class="tick-list">' . $rulesHtml . '</ul>'
        . '</div><aside class="side-card"><h2>Explore</h2><ul class="svc-list">'
        . '<li><a href="/services/' . $slug . '/">' . ms_icon($s['icon'], 'icon icon-sm') . '<span>' . ms_h($s['name']) . ' (all UK)</span></a></li>'
        . '<li><a href="/areas/' . $p['slug'] . '/">' . ms_icon('pin', 'icon icon-sm') . '<span>All marketing in ' . ms_h($town) . '</span></a></li>'
        . '<li><a href="/areas/county/' . $p['county_slug'] . '/">' . ms_icon('pin', 'icon icon-sm') . '<span>' . ms_h($p['county']) . '</span></a></li>'
        . '<li><a href="/areas/region/' . $p['region_slug'] . '/">' . ms_icon('compass', 'icon icon-sm') . '<span>' . ms_h($p['region']) . '</span></a></li>'
        . '</ul></aside></div></section>'
        . ms_faq_html($faqs, $s['name'] . ' in ' . $town . ': FAQs')
        . '<section class="section section-alt"><div class="wrap split"><div><p class="eyebrow">Nearby</p><h2>' . ms_h($s['name']) . ' near ' . ms_h($town) . '</h2><ul class="near-list">' . $nearHtml . '</ul>'
        . ($wider !== [] ? '<p>Further out: ' . ms_h(implode(', ', array_map(fn ($x) => $x[0]['name'] . ' (' . number_format($x[1], 1) . ' km)', $wider))) . '.</p>' : '') . '</div>'
        . '<div><p class="eyebrow">Also in ' . ms_h($town) . '</p><h2>Other services in ' . ms_h($town) . '</h2><ul class="svc-list">' . $otherHtml . '</ul></div></div></section>'
        . ms_cta_band($s['name'] . ' for ' . $town . ' businesses', '', $context);

    $trail = ms_region_trail($p);
    array_splice($trail, 0, 0, []);
    return [
        'title' => $s['name'] . ' in ' . $town . ', ' . $p['county'] . ' | iComply Marketing',
        'description' => mb_substr($s['name'] . ' for businesses in ' . $town . ' (' . $p['county'] . '): ' . lcfirst(rtrim($s['meta_desc'], '.')) . '. POA, free review.', 0, 300),
        'path' => '/services/' . $slug . '/' . $p['slug'] . '/',
        'group' => 'svc:' . $slug,
        'body' => $body,
        'trail' => [['Services', '/services/'], [$s['name'], '/services/' . $slug . '/'], [$town, '']],
        'jsonld' => [
            ['@context' => 'https://schema.org', '@type' => 'Service', 'name' => $s['long'] . ' in ' . $town, 'serviceType' => $s['long'], 'provider' => ['@id' => ms_url('/#org')],
                'areaServed' => ['@type' => 'Place', 'name' => $town . ', ' . $p['county'], 'geo' => ['@type' => 'GeoCoordinates', 'latitude' => $p['lat'], 'longitude' => $p['lon']]],
                'url' => ms_url('/services/' . $slug . '/' . $p['slug'] . '/')],
            ms_faq_jsonld($faqs),
        ],
    ];
}

/* ---------- Town hub ---------- */
function ms_page_town(array $p): array
{
    $services = ms_data('services');
    $reg = ms_regions()[$p['region_slug']];
    $town = $p['name'];
    $seed = 'town|' . $p['slug'];
    $curated = ms_data('areas')[$p['slug']] ?? null;
    $fams = ['search' => 'Search, SEO and listings', 'paid' => 'Paid advertising', 'ai' => 'AI, automation and IT', 'creative' => 'Websites, creative and conversion', 'relationship' => 'Email, social and reviews', 'b2b' => 'Strategy, tenders and hiring'];
    $groups = [];
    foreach ($services as $sl => $s) {
        $groups[ms_service_family($sl)][] = '<li><a href="/services/' . $sl . '/' . $p['slug'] . '/">' . ms_icon($s['icon'], 'icon icon-sm') . '<span>' . ms_h($s['name'] . ' in ' . $town) . '</span></a></li>';
    }
    $svcHtml = '';
    foreach ($fams as $k => $label) {
        $svcHtml .= '<h3>' . ms_h($label) . '</h3><ul class="svc-list">' . implode('', $groups[$k] ?? []) . '</ul>';
    }
    $industries = ms_data('industries');
    $indPick = ms_pick(array_keys($industries), 2, 'ti' . $seed);
    $indHtml = '';
    foreach ($indPick as $is) {
        $i = $industries[$is];
        $indHtml .= '<h3>' . ms_h($i['name']) . ' in ' . ms_h($town) . '</h3><p>' . ms_h(ms_choose($i['body'], 'tib' . $seed . $is)) . '</p><p><a href="/industries/' . $is . '/">Marketing for ' . ms_h(strtolower($i['name'])) . '</a></p>';
    }
    $auds = ms_data('audiences');
    $aud = $auds[ms_choose(array_keys($auds), 'ta' . $seed)];
    $near = ms_near_names($p, 8);
    $nearHtml = '';
    foreach ($near as [$np, $d]) {
        $label = ms_h($np['name']) . ' <span class="muted">· ' . ms_h(ms_dist($d)) . '</span>';
        $nearHtml .= '<li>' . (ms_in_wave($np['slug']) ? '<a href="/areas/' . $np['slug'] . '/">' . $label . '</a>' : $label) . '</li>';
    }
    $fakeSvc = ['name' => 'Marketing', 'long' => 'marketing'];
    $s0 = $services[ms_choose(array_keys($services), 'tfs' . $seed)];
    $faqs = array_merge(ms_local_faqs('marketing', ['name' => 'marketing', 'long' => 'marketing'] + $s0, $p), ms_pick(ms_data('faqs'), 3, 'tf' . $seed));
    $paras = [ms_where($p) . ' ' . ms_place_kind($p)];
    if ($curated) {
        $paras[] = $curated['intro'];
        $paras[] = $curated['angle'];
    }
    $paras[] = $reg['intro'];
    $paras[] = $reg['tip'] . ' ' . ms_working_line($p);
    $body = ms_hero('Areas · ' . $p['county'], 'Marketing services in ' . $town, 'SEO, paid ads, AI tools, websites, social, email, reviews and creative for businesses in ' . $town . ' and the surrounding area. Planned around the places you serve and measured on enquiries.', 'marketing in ' . $town, ms_facts_card($p, 'marketing in ' . $town))
        . '<section class="section"><div class="wrap content-grid"><div class="prose"><h2>Marketing in ' . ms_h($town) . '</h2>' . ms_paras($paras)
        . '<h2>Places around ' . ms_h($town) . '</h2><ul class="near-list">' . $nearHtml . '</ul></div>'
        . ms_quote_aside('Get a quote in ' . $town, 'Tell us which places around ' . $town . ' you want more work from and what you sell.', 'marketing in ' . $town) . '</div></section>'
        . '<section class="section section-alt"><div class="wrap"><div class="section-head"><p class="eyebrow">Services</p><h2>Every marketing service in ' . ms_h($town) . '</h2><p>Each page below covers what the service involves for a business in ' . ms_h($town) . ', how it is delivered and how it is measured.</p></div><div class="prose">' . $svcHtml . '</div></div></section>'
        . '<section class="section"><div class="wrap narrow prose"><h2>Sectors we know well</h2>' . $indHtml . '<h3>For ' . ms_h($aud['label']) . '</h3><p>' . ms_h(ms_choose($aud['context'], 'tac' . $seed)) . '</p>'
        . '<p>See also <a href="/areas/county/' . $p['county_slug'] . '/">marketing across ' . ms_h($p['county']) . '</a> and <a href="/areas/region/' . $p['region_slug'] . '/">' . ms_h($p['region']) . '</a>.</p></div></section>'
        . ms_faq_html($faqs, 'Marketing in ' . $town . ': FAQs')
        . ms_cta_band('Win more work in ' . $town, '', 'marketing in ' . $town);
    return [
        'title' => 'Marketing Agency ' . $town . ', ' . $p['county'] . ' | SEO, Ads & AI | iComply',
        'description' => 'Marketing services for businesses in ' . $town . ', ' . $p['county'] . ': SEO, Google Ads, Meta ads, AI chatbots, websites, social, email and reviews. POA, free review.',
        'path' => '/areas/' . $p['slug'] . '/',
        'group' => 'town',
        'body' => $body,
        'trail' => [['Areas', '/areas/'], [$p['region'], '/areas/region/' . $p['region_slug'] . '/'], [$p['county'], '/areas/county/' . $p['county_slug'] . '/'], [$town, '']],
        'jsonld' => [ms_faq_jsonld($faqs)],
    ];
}

/** Pool of contextual paragraphs for an area hub, selected by seed so hubs do not share one template. */
function ms_hub_paras(array $main, array $reg, string $seed, int $nFam = 3): array
{
    $services = ms_data('services');
    $byFam = [];
    foreach ($services as $sl => $s) {
        $byFam[ms_service_family($sl)][] = $sl;
    }
    $fam = [];
    foreach (ms_pick(array_keys($byFam), $nFam, 'hf' . $seed) as $f) {
        $sl = ms_choose($byFam[$f], 'hs' . $seed . $f);
        $fam[] = ms_local_plan($sl, $services[$sl], $main);
    }
    $regP = ms_pick([$reg['intro'], $reg['angle'], $reg['tip']], 2, 'hr' . $seed);
    $auds = ms_data('audiences');
    $audP = [];
    foreach (ms_pick(array_keys($auds), 2, 'ha' . $seed) as $a) {
        $audP[] = ['For ' . $auds[$a]['label'], ms_choose($auds[$a]['context'], 'hac' . $seed . $a), $a];
    }
    $inds = ms_data('industries');
    $is = ms_choose(array_keys($inds), 'hi' . $seed);
    return ['region' => $regP, 'family' => $fam, 'aud' => $audP, 'ind' => [$is, $inds[$is]['name'], ms_choose($inds[$is]['body'], 'hib' . $seed)]];
}

function ms_hub_faq_pool(string $label, array $reg, int $places, int $live, string $mainName): array
{
    return [
        ['Do you work with businesses across ' . $label . '?', 'Yes. ' . $reg['angle']],
        ['Which places in ' . $label . ' do you cover?', 'All of them. Our place data lists ' . $places . ' places in ' . $label . ', ' . $live . ' of which currently have dedicated town pages. Campaigns can target any town, postcode district or radius you serve.'],
        ['Can you run one campaign for the whole of ' . $label . '?', 'We can, but it is rarely the best use of budget. Buyers search by town, so we usually structure campaigns and pages by town or cluster of towns and report on each.'],
        ['How is pricing worked out for ' . $label . ' businesses?', 'Every engagement is quoted individually (POA) after a free review, based on scope and competition. Ad spend is paid directly by you to the platform.'],
        ['Who owns the accounts and content?', 'You do. Ad accounts, analytics, listings, website and content stay in your business name, with us added as a partner or user.'],
        ['We are based in ' . $mainName . '. Where should we start?', 'Usually with tracking, your Google Business Profile and the pages for the services you most want to grow in ' . $mainName . ', then expanding to the surrounding towns you cover.'],
        ['Do you only work with property businesses in ' . $label . '?', 'No. Our group background is property compliance, but we work with SMEs and B2B firms in most sectors, including trades, professional services and clinics.'],
        ['Can you cover several branches across ' . $label . '?', 'Yes. Each branch gets its own Google Business Profile, location page and campaign structure, with reporting by branch.'],
        ['How do we work together day to day?', 'Mostly through video calls, shared dashboards and a monthly report and call. Site visits for photography, video or workshops are planned in advance.'],
        ['What will we get before committing?', 'A free review of your current marketing and a written proposal listing deliverables, fees (POA) and how results will be measured.'],
    ];
}

function ms_hub_body_html(array $parts, string $where): string
{
    $out = ms_paras($parts['region']) . '<h2>How we would plan marketing ' . ms_h($where) . '</h2>' . ms_paras($parts['family']);
    foreach ($parts['aud'] as [$h, $para, $a]) {
        $out .= '<h3>' . ms_h($h . ' ' . $where) . '</h3><p>' . ms_h($para) . '</p>';
    }
    [$is, $iname, $ib] = $parts['ind'];
    $out .= '<h3>' . ms_h($iname) . '</h3><p>' . ms_h($ib) . '</p><p><a href="/industries/' . $is . '/">Marketing for ' . ms_h(strtolower($iname)) . '</a></p>';
    return $out;
}

/* ---------- County hub ---------- */
function ms_page_county(array $c): array
{
    $places = ms_places();
    $reg = ms_regions()[$c['region_slug']];
    $main = $places[$c['towns'][0]];
    $name = $c['name'];
    $label = ms_county_label($name);
    $seed = 'county|' . $c['slug'];
    $idx = [];
    $live = 0;
    $neigh = [];
    foreach ($c['towns'] as $ts) {
        $p = $places[$ts];
        $in = ms_in_wave($ts);
        $live += $in ? 1 : 0;
        $d = $ts === $main['slug'] ? 'main place in our data' : ms_dist(ms_km($main, $p)) . ' from ' . $main['name'];
        $txt = ms_h($p['name']) . ($p['authority'] !== $name ? ' <span class="muted">(' . ms_h($p['authority']) . ')</span>' : '') . ' <span class="muted">· ' . ms_h($d) . '</span>';
        $idx[] = '<li>' . ($in ? '<a href="/areas/' . $ts . '/">' . $txt . '</a>' : $txt) . '</li>';
        foreach ($p['nearby'] as [$ns, $_]) {
            $oc = $places[$ns]['county_slug'] ?? '';
            if ($oc !== '' && $oc !== $c['slug']) {
                $neigh[$oc] = ($neigh[$oc] ?? 0) + 1;
            }
        }
    }
    arsort($neigh);
    $counties = ms_counties();
    $neighHtml = implode(', ', array_map(fn ($k) => '<a href="/areas/county/' . $k . '/">' . ms_h($counties[$k]['name']) . '</a>', array_slice(array_keys($neigh), 0, 8)));
    $services = ms_data('services');
    $svcHtml = '';
    $inMain = ms_in_wave($main['slug']);
    foreach (ms_pick(array_keys($services), 12, 'cs' . $seed) as $sl) {
        $svcHtml .= '<li><a href="/services/' . $sl . '/' . ($inMain ? $main['slug'] . '/' : '') . '">' . ms_icon($services[$sl]['icon'], 'icon icon-sm') . '<span>' . ms_h($services[$sl]['name'] . ($inMain ? ' in ' . $main['name'] : '')) . '</span></a></li>';
    }
    $auths = array_keys($c['authorities']);
    sort($auths);
    $intro = $name . ($c['country'] === 'England' ? ' is in the ' . $c['region'] . ' of England' : ' is a council area in ' . $c['country']) . '. '
        . (count($auths) > 1 ? 'It covers the local authority areas of ' . implode(', ', $auths) . '. ' : '')
        . 'Our place data lists ' . count($c['towns']) . ' ' . (count($c['towns']) === 1 ? 'place' : 'places') . ' here, led by ' . $main['name'] . '. ' . ms_working_line($main);
    $parts = ms_hub_paras($main, $reg, $seed, 5);
    $faqs = ms_pick(ms_hub_faq_pool($label, $reg, count($c['towns']), $live, $main['name']), 6, 'cf' . $seed);
    $body = ms_hero('Areas · ' . $c['region'], 'Marketing services in ' . $name, 'SEO, paid ads, AI, websites, social, email and reviews for businesses across ' . $label . ', planned town by town and measured on enquiries.', 'marketing in ' . $name, ms_facts_card($main, 'marketing in ' . $name))
        . '<section class="section"><div class="wrap content-grid"><div class="prose"><h2>Marketing across ' . ms_h($label) . '</h2><p>' . ms_h($intro) . '</p>'
        . ms_hub_body_html($parts, 'in ' . $label)
        . '</div>' . ms_quote_aside('Get a quote in ' . $name, 'Tell us which towns in ' . $label . ' you want more work from.', 'marketing in ' . $name) . '</div></section>'
        . '<section class="section section-alt"><div class="wrap prose"><h2>Places in ' . ms_h($label) . '</h2><p>Distances are straight-line from ' . ms_h($main['name']) . ', from GeoNames coordinates. Linked places have their own town page in the current rollout wave.</p><ul class="town-index">' . implode('', $idx) . '</ul>'
        . ($neighHtml !== '' ? '<h3>Neighbouring areas</h3><p>' . $neighHtml . '</p>' : '')
        . '<h3>Popular services' . ($inMain ? ' in ' . ms_h($main['name']) : '') . '</h3><ul class="svc-list">' . $svcHtml . '</ul>'
        . '<p><a href="/areas/region/' . $c['region_slug'] . '/">Back to ' . ms_h($c['region']) . '</a> · <a href="/areas/">All areas</a> · <a href="/services/">All services</a></p></div></section>'
        . ms_faq_html($faqs, 'Marketing in ' . $name . ': FAQs')
        . ms_cta_band('Win more work across ' . $label, '', 'marketing in ' . $name);
    return [
        'title' => 'Marketing Agency ' . $name . ' | Town-by-Town SEO, Ads & AI | iComply',
        'description' => 'Marketing services across ' . $label . ': SEO, Google Ads, Meta ads, AI tools, websites, social and reviews, planned town by town. POA, free review.',
        'path' => '/areas/county/' . $c['slug'] . '/',
        'group' => 'county',
        'body' => $body,
        'trail' => [['Areas', '/areas/'], [$c['region'], '/areas/region/' . $c['region_slug'] . '/'], [$name, '']],
        'jsonld' => [ms_faq_jsonld($faqs)],
    ];
}

/* ---------- Region hub ---------- */
function ms_page_region(string $rslug, array $r): array
{
    $counties = array_filter(ms_counties(), fn ($c) => $c['region_slug'] === $rslug);
    uasort($counties, fn ($a, $b) => strcmp($a['name'], $b['name']));
    $places = ms_places();
    $seed = 'region|' . $rslug;
    $cHtml = '';
    $live = 0;
    foreach ($counties as $c) {
        $nLive = count(array_filter($c['towns'], 'ms_in_wave'));
        $live += $nLive;
        $cHtml .= '<li><a href="/areas/county/' . $c['slug'] . '/">' . ms_h($c['name']) . '</a> <span class="muted">· ' . count($c['towns']) . ' places, ' . $nLive . ' town pages</span></li>';
    }
    $topTowns = [];
    $others = [];
    $main = null;
    foreach ($places as $ts => $p) {
        if ($p['region_slug'] !== $rslug) {
            continue;
        }
        $main ??= $p;
        if (ms_in_wave($ts)) {
            $topTowns[] = '<li><a href="/areas/' . $ts . '/">' . ms_h($p['name']) . '</a></li>';
        } elseif (count($others) < 120) {
            $others[] = ms_h($p['name']);
        }
    }
    $total = array_sum(array_map(fn ($c) => count($c['towns']), $counties));
    $parts = ms_hub_paras($main, $r, $seed, 4);
    $faqs = ms_pick(ms_hub_faq_pool($r['name'], $r, $total, $live, $main['name']), 5, 'rf' . $seed);
    $body = ms_hero('Areas · ' . $r['nation'], 'Marketing services in ' . $r['name'], 'SEO, paid ads, AI, websites, social, email and reviews for businesses across ' . $r['name'] . ', planned around the towns you serve.', 'marketing in ' . $r['name'])
        . '<section class="section"><div class="wrap content-grid"><div class="prose"><h2>Marketing in ' . ms_h($r['name']) . '</h2>'
        . '<p>' . ms_h('Our place data covers ' . $total . ' places across ' . count($counties) . ' ' . ($r['nation'] === 'England' ? 'counties and unitary areas' : 'council areas') . ' in ' . $r['name'] . ', and ' . $live . ' of them currently have their own town page.') . '</p>'
        . ms_hub_body_html($parts, 'in ' . $r['name'])
        . '<h2>' . ($r['nation'] === 'England' ? 'Counties and unitary areas' : 'Council areas') . ' in ' . ms_h($r['name']) . '</h2><ul class="near-list">' . $cHtml . '</ul>'
        . '</div>' . ms_quote_aside('Get a quote in ' . $r['name'], 'Tell us where you trade and what you want more of.', 'marketing in ' . $r['name']) . '</div></section>'
        . '<section class="section section-alt"><div class="wrap prose">'
        . ($topTowns ? '<h2>Town pages in ' . ms_h($r['name']) . '</h2><ul class="town-index">' . implode('', $topTowns) . '</ul>' : '')
        . ($others ? '<h3>More places we cover in ' . ms_h($r['name']) . '</h3><p>' . implode(', ', $others) . '.</p>' : '')
        . '<p><a href="/areas/">All areas</a> · <a href="/services/">All services</a> · <a href="/marketing/">Marketing by sector</a></p></div></section>'
        . ms_faq_html($faqs, 'Marketing in ' . $r['name'] . ': FAQs')
        . ms_cta_band('Win more work across ' . $r['name'], '', 'marketing in ' . $r['name']);
    return [
        'title' => 'Marketing Agency ' . $r['name'] . ' | SEO, Ads, AI & Web | iComply',
        'description' => 'Marketing services across ' . $r['name'] . ': SEO, Google Ads, Meta ads, AI tools, websites, social and reviews, planned county by county and town by town.',
        'path' => '/areas/region/' . $rslug . '/',
        'group' => 'region',
        'body' => $body,
        'trail' => [['Areas', '/areas/'], [$r['name'], '']],
        'jsonld' => [ms_faq_jsonld($faqs)],
    ];
}

/* ---------- Areas hub (replaces 8-area list) ---------- */
function ms_page_areas_hub(): array
{
    $byNation = [];
    foreach (ms_regions() as $rs => $r) {
        $byNation[$r['nation']][] = '<a class="card service-card" href="/areas/region/' . $rs . '/"><span class="card-icon">' . ms_icon('pin') . '</span><h3>' . ms_h($r['name']) . '</h3><p>' . ms_h($r['tip']) . '</p><span class="card-link">Marketing in ' . ms_h($r['name']) . ' ' . ms_icon('arrow', 'icon icon-sm') . '</span></a>';
    }
    $html = '';
    foreach ($byNation as $n => $cards) {
        $html .= '<h2>' . ms_h($n) . '</h2><div class="grid grid-3">' . implode('', $cards) . '</div>';
    }
    $top = '';
    foreach (array_slice(ms_wave_places(), 0, 60, true) as $ts => $p) {
        $top .= '<a class="chip chip-light" href="/areas/' . $ts . '/">' . ms_h($p['name']) . '</a>';
    }
    $faqs = [
        ['Do you work across the whole UK?', 'Yes. We are based in Stockport and work with businesses in England, Scotland, Wales and Northern Ireland, remotely by default and in person where it helps.'],
        ['How are towns chosen for their own pages?', 'Town pages are rolled out in waves, ordered by our priority keyword list and then by population ranking in the GeoNames dataset. Every town in the dataset is covered by campaigns whether or not it has a page yet.'],
        ['Can you target my exact service area?', 'Yes. Ads, local SEO and listings can be set by town, postcode district or radius, with exclusions for areas you do not cover.'],
    ];
    $body = ms_hero('Areas we cover', 'Marketing services across the UK', 'Based in Stockport, working with businesses in every region and nation of the UK. Pick your region, county or town below.', 'marketing in my area')
        . '<section class="section"><div class="wrap">' . $html . '</div></section>'
        . '<section class="section section-alt"><div class="wrap"><div class="section-head"><h2>Popular towns</h2></div><div class="chips">' . $top . '</div>'
        . '<p class="data-note">Place names, local authorities and coordinates: GeoNames (geonames.org), CC BY 4.0. Regions: ONS. Also see <a href="/areas/cheshire/">Cheshire</a> and <a href="/areas/uk-wide/">UK-wide campaigns</a>.</p></div></section>'
        . ms_faq_html($faqs)
        . ms_cta_band('Wherever you trade, let us talk', 'Tell us the towns and postcodes you want more work from and we will show you how we would target them.');
    return [
        'title' => 'Areas We Cover | UK-wide Marketing Agency by Region & Town | iComply',
        'description' => 'iComply Marketing Services works across England, Scotland, Wales and Northern Ireland. Find marketing services by region, county and town.',
        'path' => '/areas/',
        'body' => $body,
        'trail' => [['Areas', '']],
        'jsonld' => [ms_faq_jsonld($faqs)],
    ];
}

/* ---------- Keyword pages ---------- */
function ms_keyword_service_prefixes(): array
{
    return ['ai-ads' => 'ai-ads-optimisation', 'ai-chatbot' => 'ai-chatbots', 'ai-content' => 'ai-content', 'ai-marketing' => 'marketing-strategy',
        'ai-receptionist' => 'ai-receptionist', 'ai-seo' => 'ai-seo', 'gbp' => 'google-business-profile', 'google-ads' => 'google-ads',
        'meta-ads' => 'meta-ads', 'seo' => 'seo', 'crm-setup' => 'automation-crm', 'it-support' => 'it-support'];
}

/** Synonym stems that are the same intent as an existing service page (mapped, not duplicated). */
function ms_keyword_synonyms(): array
{
    $m = [
        'ad-networks' => ['ad-network-management', 'ad-networking', 'ad-networks'],
        'ai-ads-optimisation' => ['ai-ads-optimisation'], 'ai-chatbots' => ['ai-chatbots'], 'ai-content' => ['ai-content', 'ai-content-marketing'],
        'ai-receptionist' => ['ai-receptionist'], 'ai-review-replies' => ['ai-review-replies'], 'ai-seo' => ['ai-seo', 'ai-seo-services', 'ai-search-optimisation'],
        'aso' => ['aso', 'app-store-optimisation'], 'automation-crm' => ['automation-crm', 'automation-and-crm', 'marketing-automation'],
        'tender-bid-packs' => ['tender-bid-packs', 'tender-and-bid-packs', 'tender-pack-writing', 'bid-writing-service'],
        'branding' => ['branding'], 'photography' => ['photography', 'commercial-photography'], 'content-marketing' => ['content-marketing'],
        'cro' => ['cro', 'conversion-rate-optimisation'], 'directories-listings' => ['directories-listings', 'directories-and-listings'],
        'programmatic-ads' => ['programmatic-ads', 'programmatic-advertising', 'display-advertising'], 'drone-media' => ['drone-media', 'drone-survey-marketing'],
        'email-marketing' => ['email-marketing', 'email-campaigns'],
        'employer-branding' => ['employer-branding', 'employer-branding-and-recruitment-ads', 'employer-branding-recruitment-ads', 'recruitment-ads', 'recruitment-advertising'],
        'franchise-seo' => ['franchise-seo', 'franchise-and-multi-location-seo', 'franchise-multi-location-seo', 'multi-location-seo'],
        'funnel-builds' => ['funnel-builds', 'funnel-building'], 'google-business-profile' => ['google-business-profile', 'google-business-profile-management', 'gbp-optimisation'],
        'google-ads' => ['google-ads'], 'influencer-marketing' => ['influencer-marketing'], 'it-support' => ['it-support', 'managed-it-for-marketers'],
        'linkedin-tiktok-ads' => ['linkedin-tiktok-ads', 'linkedin-and-tiktok-ads', 'linkedin-ads', 'tiktok-ads'],
        'marketing-strategy' => ['marketing-strategy', 'strategy-audits', 'strategy-and-audits'], 'meta-ads' => ['meta-ads'],
        'podcast-ads' => ['podcast-ads', 'podcast-advertising', 'podcast-sponsorship'], 'retargeting' => ['retargeting', 'retargeting-ads', 'remarketing-ads'],
        'reputation-reviews' => ['reputation-reviews', 'reviews-and-reputation', 'reviews-reputation'], 'seo' => ['seo'],
        'signage-wraps' => ['signage-wraps', 'signage-and-wraps', 'vehicle-wraps-design'], 'sms-marketing' => ['sms-marketing'], 'social-media' => ['social-media'],
        'video-production' => ['video-production', 'video'], 'websites-landing-pages' => ['websites-landing-pages', 'websites-and-landing-pages'], 'youtube-ads' => ['youtube-ads'],
    ];
    $out = [];
    foreach ($m as $svc => $stems) {
        foreach ($stems as $st) {
            $out[$st] = $svc;
        }
    }
    return $out;
}

/** All keyword stems from the locked lists that get their own page: [stem => [type, service, audience|topic]]. */
function ms_keyword_pages(): array
{
    static $pages = null;
    if ($pages !== null) {
        return $pages;
    }
    $pages = [];
    $auds = ms_data('audiences');
    $topics = ms_data('topics');
    $pref = ms_keyword_service_prefixes();
    foreach (['MARKETING-KEYWORDS-CORE.txt', 'HANDOFF-MARKETING-AI-KEYWORDS.txt'] as $f) {
        foreach (file(MS_ROOT . '/data/keywords/' . $f, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $kw) {
            $stem = ms_keyword_stem(trim($kw))[0];
            if (isset($pages[$stem])) {
                continue;
            }
            if (preg_match('/^(.+)-for-(.+)$/', $stem, $m) && isset($pref[$m[1]], $auds[$m[2]])) {
                $pages[$stem] = ['audience', $pref[$m[1]], $m[2], $m[1]];
            } elseif (isset($topics[$stem])) {
                $pages[$stem] = ['topic', $topics[$stem]['parent'], $stem, ''];
            }
        }
    }
    foreach ($topics as $t => $x) {
        $pages[$t] ??= ['topic', $x['parent'], $t, ''];
    }
    ksort($pages);
    return $pages;
}

/** Strip modifiers and an optional town: returns [stem, townSlug|null]. */
function ms_keyword_stem(string $kw): array
{
    $mods = ['agency', 'company', 'cost', 'near-me', 'quote', 'uk', 'services', 'service'];
    $kw = preg_replace('/^best-/', '', $kw);
    $town = null;
    $places = ms_places();
    $syn = ms_keyword_synonyms();
    $topics = ms_data('topics');
    for ($i = 0; $i < 4; $i++) {
        foreach ($mods as $m) {
            if (str_ends_with($kw, '-' . $m) && !isset($syn[$kw]) && !isset($topics[$kw])) {
                $kw = substr($kw, 0, -strlen($m) - 1);
            }
        }
        if ($town === null) {
            $parts = explode('-', $kw);
            for ($k = 1; $k < count($parts); $k++) {
                $cand = implode('-', array_slice($parts, $k));
                $head = implode('-', array_slice($parts, 0, $k));
                $headClean = preg_replace('/-(agency|company)$/', '', $head);
                if (isset($places[$cand]) && (isset($syn[$headClean]) || isset($syn[$head]) || preg_match('/-for-/', $head))) {
                    $town = $cand;
                    $kw = isset($syn[$head]) ? $head : $headClean;
                    break;
                }
            }
        }
    }
    return [$kw, $town];
}

/** Map any locked keyword to its target URL (or null if its page is not in this wave). */
function ms_keyword_target(string $kw): array
{
    [$stem, $town] = ms_keyword_stem($kw);
    $alias = ['ai-marketing-agency' => 'ai-marketing-services', 'ai-marketing' => 'ai-marketing-services'];
    $stem = $alias[$stem] ?? $stem;
    $syn = ms_keyword_synonyms();
    if ($town === null && str_ends_with($stem, '-cheshire')) {
        // Cheshire is split into two unitary areas in the dataset; the curated /areas/cheshire/ page covers the county.
        $base = substr($stem, 0, -9);
        $base = preg_replace('/-(agency|company)$/', '', $base);
        if (isset($syn[$base]) || isset(ms_keyword_pages()[$base])) {
            return ['/areas/cheshire/', 'county (Cheshire)'];
        }
    }
    $kp = ms_keyword_pages();
    $svc = $syn[$stem] ?? (isset($kp[$stem]) ? $kp[$stem][1] : null);
    if ($town !== null && $svc !== null) {
        if (isset($kp[$stem])) {
            return ['/marketing/' . $stem . '/', 'keyword (town variant -> keyword page)'];
        }
        return [ms_in_wave($town) ? '/services/' . $svc . '/' . $town . '/' : null, 'service x town'];
    }
    if (isset($kp[$stem])) {
        return ['/marketing/' . $stem . '/', 'keyword page'];
    }
    if ($svc !== null) {
        return ['/services/' . $svc . '/', 'service hub'];
    }
    return [null, 'unmapped'];
}

function ms_page_keyword(string $stem, array $def): array
{
    [$type, $svcSlug, $key, $prefix] = $def;
    $services = ms_data('services');
    $s = $services[$svcSlug];
    $seed = 'kw|' . $stem;
    $auds = ms_data('audiences');
    $topics = ms_data('topics');
    $ctx = ms_ctx($s);
    $prefixName = ['ai-ads' => 'AI ads', 'ai-chatbot' => 'AI chatbots', 'ai-content' => 'AI content', 'ai-marketing' => 'AI marketing', 'ai-receptionist' => 'AI receptionist', 'ai-seo' => 'AI SEO', 'gbp' => 'Google Business Profile', 'google-ads' => 'Google Ads', 'meta-ads' => 'Meta ads', 'seo' => 'SEO', 'crm-setup' => 'CRM set-up', 'it-support' => 'IT support'];
    if ($type === 'audience') {
        $a = $auds[$key];
        $name = $prefixName[$prefix] . ' for ' . $a['label'];
        $h1 = $name . ': marketing that respects how your clients choose';
        $topicIntro = $a['context'];
        $points = $a['rules'];
        $pointsHead = 'Rules and expectations we build in';
        $extraFaqs = $a['faqs'];
        $indSlug = $a['industry'];
    } else {
        $t = $topics[$key];
        $name = $t['name'];
        $h1 = $t['h1'];
        $topicIntro = $t['intro'];
        $points = $t['points'];
        $pointsHead = 'What the work involves';
        $extraFaqs = $t['faqs'];
        $indSlug = ms_choose(array_keys(ms_data('industries')), 'kwi' . $seed);
    }
    $ind = ms_data('industries')[$indSlug];
    $intro = ms_pick($s['intro'], min(3, count($s['intro'])), 'ki' . $seed);
    $inc = ms_pick($s['included'], 6, 'kc' . $seed);
    $measure = ms_pick($s['measure'], 4, 'km' . $seed);
    $faqs = ms_pick(array_merge($extraFaqs, ms_pick($s['faqs'], 3, 'kf' . $seed)), 6, 'kfo' . $seed);
    $incHtml = '';
    foreach ($inc as [$tt, $d]) {
        $incHtml .= '<div class="card inc-card">' . ms_icon('check', 'icon tick') . '<div><h3>' . ms_h($tt) . '</h3><p>' . ms_h($d) . '</p></div></div>';
    }
    $ptHtml = '';
    foreach ($points as $x) {
        $ptHtml .= '<li>' . ms_icon($type === 'audience' ? 'shield' : 'check', 'icon icon-sm') . '<span>' . ms_h($x) . '</span></li>';
    }
    $mHtml = '';
    foreach ($measure as $m) {
        $mHtml .= '<li>' . ms_icon('target', 'icon icon-sm') . '<span>' . ms_h($m) . '</span></li>';
    }
    // Related keyword pages: same audience (other services) + same service (other audiences)
    $rel = [];
    foreach (ms_keyword_pages() as $st => $d) {
        if ($st === $stem) {
            continue;
        }
        if (($type === 'audience' && $d[0] === 'audience' && ($d[2] === $key || $d[3] === $prefix)) || ($type === 'topic' && $d[0] === 'topic')) {
            $rel[$st] = $d;
        }
    }
    $rel = ms_pick(array_keys($rel), 8, 'kr' . $seed);
    $relHtml = '';
    foreach ($rel as $st) {
        $relHtml .= '<li><a href="/marketing/' . $st . '/">' . ms_icon('arrow', 'icon icon-sm') . '<span>' . ms_h(ms_keyword_title($st)) . '</span></a></li>';
    }
    $towns = array_slice(ms_wave_places(), 0, 120, true);
    $tp = ms_pick(array_keys($towns), 10, 'kt' . $seed);
    $townHtml = '';
    foreach ($tp as $ts) {
        $townHtml .= '<li><a href="/services/' . $svcSlug . '/' . $ts . '/">' . ms_h($s['name'] . ' in ' . $towns[$ts]['name']) . '</a></li>';
    }
    $body = ms_hero($s['name'] . ' · ' . ($type === 'audience' ? $auds[$key]['title'] : 'AI marketing'), $h1, ms_paras([]) . $topicIntro[0], $name, '')
        . '<section class="section"><div class="wrap content-grid"><div class="prose">'
        . '<h2>' . ms_h($name) . '</h2>' . ms_paras(array_slice($topicIntro, 1))
        . '<h2>' . ms_h($pointsHead) . '</h2><ul class="tick-list">' . $ptHtml . '</ul>'
        . '<h2>How ' . ms_h($ctx) . ' works</h2>' . ms_paras($intro)
        . '<div class="mid-cta"><p>Want an honest view of whether ' . ms_h(strtolower($name)) . ' would pay back for you? We will review your current position for free.</p><a class="btn btn-primary btn-sm" href="/contact/?service=' . $svcSlug . '#quote">Get my free review</a></div>'
        . '<h2>Choosing a provider</h2><p>Whether you are searching for an agency, a company or a consultant near you, ask the same questions: who owns the accounts, how leads are tracked, what is in the monthly fee and what happens if you leave. With us the answers are simple: you own everything, tracking is set up before work starts, every proposal is quoted individually (POA) in writing, and we are based in Stockport but work with businesses across the UK.</p>'
        . '</div>' . ms_quote_aside('Get a quote for ' . strtolower($name), 'Tell us about your business, where you work and what you want more of.', $name) . '</div></section>'
        . '<section class="section section-alt"><div class="wrap"><div class="section-head"><p class="eyebrow">What you get</p><h2>Typical deliverables</h2><p>Scoped to your goals in a written proposal.</p></div><div class="grid grid-2">' . $incHtml . '</div></div></section>'
        . '<section class="section"><div class="wrap split"><div><p class="eyebrow">How we deliver</p><h2>The process</h2><p>' . ms_h($s['fit']) . '</p></div>' . ms_steps($s['process']) . '</div></section>'
        . '<section class="section section-navy"><div class="wrap split"><div><p class="eyebrow">Accountability</p><h2>What we measure</h2><p>Reports are tied to enquiries and cost per enquiry, not vanity metrics.</p></div><ul class="measure-list">' . $mHtml . '</ul></div></section>'
        . '<section class="section"><div class="wrap narrow prose"><h2>' . ms_h($ind['name']) . '</h2>' . ms_paras(ms_pick($ind['body'], 2, 'kib' . $seed)) . '<p><a href="/industries/' . $indSlug . '/">Marketing for ' . ms_h(strtolower($ind['name'])) . '</a> · <a href="/services/' . $svcSlug . '/">' . ms_h($s['name']) . ' service</a></p></div></section>'
        . ms_faq_html($faqs, $name . ': FAQs')
        . '<section class="section section-alt"><div class="wrap split"><div><p class="eyebrow">Related</p><h2>Related topics</h2><ul class="svc-list">' . $relHtml . '</ul></div>'
        . '<div><p class="eyebrow">By town</p><h2>' . ms_h($s['name']) . ' near you</h2><ul class="near-list">' . $townHtml . '</ul><p><a href="/areas/">All areas</a> · <a href="/marketing/">All topics</a></p></div></div></section>'
        . ms_cta_band('Talk to us about ' . strtolower($name), '', $name);
    return [
        'title' => ms_keyword_title($stem) . ' | iComply Marketing Services',
        'description' => mb_substr($name . ': ' . lcfirst(rtrim($s['meta_desc'], '.')) . '. Written proposal, POA, accounts in your name.', 0, 300),
        'path' => '/marketing/' . $stem . '/',
        'group' => 'kw:' . $type,
        'body' => $body,
        'trail' => [['Topics', '/marketing/'], [$name, '']],
        'jsonld' => [
            ['@context' => 'https://schema.org', '@type' => 'Service', 'name' => $name, 'serviceType' => $s['long'], 'provider' => ['@id' => ms_url('/#org')], 'areaServed' => ['@type' => 'Country', 'name' => 'United Kingdom'], 'url' => ms_url('/marketing/' . $stem . '/')],
            ms_faq_jsonld($faqs),
        ],
    ];
}

function ms_keyword_title(string $stem): string
{
    $d = ms_keyword_pages()[$stem] ?? null;
    if ($d && $d[0] === 'topic') {
        return ms_data('topics')[$stem]['name'];
    }
    $prefixName = ['ai-ads' => 'AI Ads', 'ai-chatbot' => 'AI Chatbots', 'ai-content' => 'AI Content', 'ai-marketing' => 'AI Marketing', 'ai-receptionist' => 'AI Receptionist', 'ai-seo' => 'AI SEO', 'gbp' => 'Google Business Profile', 'google-ads' => 'Google Ads', 'meta-ads' => 'Meta Ads', 'seo' => 'SEO', 'crm-setup' => 'CRM Set-up', 'it-support' => 'IT Support'];
    return $prefixName[$d[3]] . ' for ' . ms_data('audiences')[$d[2]]['title'];
}

function ms_page_keywords_hub(): array
{
    $groups = [];
    foreach (ms_keyword_pages() as $st => $d) {
        $label = $d[0] === 'topic' ? 'AI marketing topics' : ms_data('audiences')[$d[2]]['title'];
        $groups[$label][] = '<li><a href="/marketing/' . $st . '/">' . ms_h(ms_keyword_title($st)) . '</a></li>';
    }
    ksort($groups);
    $html = '';
    foreach ($groups as $g => $items) {
        $html .= '<h2>' . ms_h($g) . '</h2><ul class="near-list">' . implode('', $items) . '</ul>';
    }
    $faqs = [
        ['Why are there pages for specific professions?', 'Because regulated and specialist sectors have different buyers and rules. A solicitor, a dentist and a gas engineer need different messages, proof and compliance checks.'],
        ['Is the service different for each sector?', 'The core method is the same; the targeting, content, proof and compliance checks change.'],
        ['How do I get a quote?', 'Call 07517 806082, message us on WhatsApp or send the quote form. Every engagement is quoted individually (POA) after a free review.'],
    ];
    $body = ms_hero('Topics', 'Marketing by sector and topic', 'Sector-specific SEO, ads, AI and Google Business Profile services, plus AI marketing topics such as ChatGPT SEO and generative engine optimisation.')
        . '<section class="section"><div class="wrap prose">' . $html . '</div></section>' . ms_faq_html($faqs) . ms_cta_band();
    return ['title' => 'Marketing by Sector & Topic | iComply Marketing Services', 'description' => 'Sector-specific marketing for accountants, solicitors, dentists, clinics, estate agents, trades and more, plus AI marketing topics.', 'path' => '/marketing/', 'body' => $body, 'trail' => [['Topics', '']], 'jsonld' => [ms_faq_jsonld($faqs)]];
}

/** "Where we deliver" block for national service hub pages: regions + town pages in the wave. */
function ms_service_where(string $slug, array $s): string
{
    $reg = '';
    foreach (ms_regions() as $rs => $r) {
        $reg .= '<a class="chip chip-light" href="/areas/region/' . $rs . '/">' . ms_h($r['name']) . '</a>';
    }
    $towns = '';
    foreach (array_slice(ms_wave_places(), 0, 48, true) as $ts => $p) {
        $towns .= '<li><a href="/services/' . $slug . '/' . $ts . '/">' . ms_h($s['name'] . ' in ' . $p['name']) . '</a></li>';
    }
    return '<section class="section"><div class="wrap prose"><p class="eyebrow">Where we deliver</p><h2>' . ms_h($s['name']) . ' across the UK</h2>'
        . '<p>We are based in Stockport and deliver ' . ms_h(ms_ctx($s)) . ' for businesses in England, Scotland, Wales and Northern Ireland. Each town page below explains how the service is planned for that area, with the nearby places it can cover, local questions answered and the same clear process and reporting.</p>'
        . '<div class="chips">' . $reg . '</div>'
        . ($towns !== '' ? '<h3>Town pages for ' . ms_h(ms_ctx($s)) . '</h3><ul class="town-index">' . $towns . '</ul><p><a href="/areas/">Find your town or county</a></p>' : '')
        . '</div></section>';
}
