<?php
declare(strict_types=1);

/**
 * Post-export checks. Exits non-zero on any failure.
 *
 * Existing checks: titles (present, unique), meta description, one h1, canonical,
 * quote/call/WhatsApp CTA, four footer columns, FAQ counts + 800 words on service
 * pages, valid JSON-LD, internal links resolve, required files.
 *
 * Go-live checks added 2026-10-06:
 *  - robots meta matches config (index, follow when live); robots.txt has Sitemap when live
 *  - floating WhatsApp bubble (class wa-bubble, wa.me/<number>, aria-label) on EVERY html page
 *  - phone, WhatsApp and contact email from site.php present on every page
 *  - 800+ words of <main> body copy (boilerplate marked <!--b--> excluded) on service, service x town, town, county, region and keyword pages
 *  - FAQPage JSON-LD wherever FAQ items are shown
 *  - copy rules: no "same day", "within one working day", "Ltd", "from £" prices
 *  - near-duplicate bodies: 5-word shingle Jaccard (one-permutation MinHash, 128 bins + LSH,
 *    exact Jaccard re-checked for every flagged pair) between pages of the same group
 *    (same service across towns; town hubs; county hubs; keyword pages). FAIL above MAX_SIM.
 *  - sitemap index: every child sitemap < 50,000 URLs
 */
const MAX_SIM = 0.60;
const MIN_WORDS = 800;

$repo = dirname(__DIR__, 2);
$dist = (string) (getenv('DIST_DIR') ?: $repo . '/dist');
$cfg = require $repo . '/website/config/site.php';
$report = json_decode((string) @file_get_contents($dist . '/export-report.json'), true);
$errors = [];
if (!is_array($report)) {
    fwrite(STDERR, "export-report.json missing — run static-export.php first\n");
    exit(1);
}
$pages = $report['pages']; // path => group
$isList = array_is_list($pages);
if ($isList) {
    $pages = array_fill_keys($pages, 'core');
}
$t0 = microtime(true);
$robotsWant = $cfg['preview_only'] ? 'noindex, nofollow' : 'index, follow';
$waNum = $cfg['whatsapp'];
$phone = $cfg['phone_display'];
$email = $cfg['email'];
$wordGroups = ['service', 'svc:', 'town', 'county', 'region', 'kw:'];
$simGroups = [];      // group => [path => sig]
$wordStats = [];      // group family => list of word counts
$titles = [];
$linkOk = [];
$fileFor = fn (string $path) => $dist . ($path === '/' ? '/index.html' : (str_ends_with($path, '.html') ? $path : rtrim($path, '/') . '/index.html'));

function ms_main_text(string $html): string
{
    $s = strpos($html, '<main');
    $e = strrpos($html, '</main>');
    $main = ($s !== false && $e !== false) ? substr($html, $s, $e - $s) : $html;
    $main = preg_replace('#<(script|style)[^>]*>.*?</\1>#s', ' ', $main);
    // Shared boilerplate (CTA band, quote sidebar, trust ticks) is marked <!--b-->...<!--/b--> and
    // excluded, so word counts and similarity measure the page's own body copy only.
    $main = preg_replace('#<!--b-->.*?<!--/b-->#s', ' ', $main);
    $main = preg_replace('#<[^>]+>#', ' ', $main);
    return trim((string) preg_replace('/\s+/', ' ', html_entity_decode($main, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
}

function ms_tokens(string $text): array
{
    preg_match_all("/[a-z0-9£']+/", strtolower($text), $m);
    return $m[0];
}

function ms_shingles(array $tok, int $k = 5): array
{
    $out = [];
    $n = count($tok) - $k + 1;
    for ($i = 0; $i < $n; $i++) {
        $out[crc32(implode(' ', array_slice($tok, $i, $k)))] = true;
    }
    return $out;
}

/** One-permutation hashing: 128 bins, min value per bin. */
function ms_oph(array $sh): array
{
    $sig = array_fill(0, 128, PHP_INT_MAX);
    foreach ($sh as $h => $_) {
        $mix = ($h * 2654435761) & 0xFFFFFFFF;
        $b = $mix & 127;
        $v = $mix >> 7;
        if ($v < $sig[$b]) {
            $sig[$b] = $v;
        }
    }
    return $sig;
}

function ms_sig_sim(array $a, array $b): float
{
    $eq = 0;
    $n = 0;
    for ($i = 0; $i < 128; $i++) {
        if ($a[$i] === PHP_INT_MAX && $b[$i] === PHP_INT_MAX) {
            continue;
        }
        $n++;
        if ($a[$i] === $b[$i]) {
            $eq++;
        }
    }
    return $n ? $eq / $n : 0.0;
}

foreach ($pages as $path => $group) {
    $file = $fileFor($path);
    $html = (string) @file_get_contents($file);
    if ($html === '') {
        $errors[] = "$path: missing file";
        continue;
    }
    preg_match('#<title>(.*?)</title>#s', $html, $m);
    $title = $m[1] ?? '';
    if ($title === '') {
        $errors[] = "$path: no <title>";
    } elseif (isset($titles[$title]) && $path !== '/404.html') {
        $errors[] = "$path: duplicate title with {$titles[$title]}";
    }
    $titles[$title] = $path;
    if (!preg_match('#<meta name="description" content="[^"]{50,}"#', $html)) {
        $errors[] = "$path: meta description missing or short";
    }
    if (substr_count($html, '<h1') !== 1) {
        $errors[] = "$path: expected exactly one h1";
    }
    if (!str_contains($html, 'rel="canonical" href="' . htmlspecialchars($report['site_url'], ENT_QUOTES))) {
        $errors[] = "$path: canonical missing or not on {$report['site_url']}";
    }
    if (!str_contains($html, '<meta name="robots" content="' . $robotsWant . '">')) {
        $errors[] = "$path: robots meta is not '$robotsWant'";
    }
    if (!str_contains($html, 'href="tel:') || !str_contains($html, 'wa.me/') || !str_contains($html, '/contact/#quote')) {
        $errors[] = "$path: missing quote/call/WhatsApp CTA";
    }
    if (!preg_match('#<a class="wa-bubble" href="https://wa\.me/' . $waNum . '\?text=[^"]+"[^>]*aria-label="[^"]+"#', $html)) {
        $errors[] = "$path: floating WhatsApp bubble missing";
    }
    if (!str_contains($html, $phone) || !str_contains($html, 'mailto:' . $email) || !str_contains($html, 'wa.me/' . $waNum)) {
        $errors[] = "$path: contact details (phone/email/WhatsApp) missing";
    }
    foreach (['Services', 'Areas', 'Company', 'Contact'] as $col) {
        if (!str_contains($html, '<h3>' . $col . '</h3>')) {
            $errors[] = "$path: footer column $col missing";
        }
    }
    $text = ms_main_text($html);
    foreach (['/same[ -]day/i' => 'same day', '/within one working day/i' => 'timing promise', '/\bLtd\b/' => 'Ltd', '/\bfrom £\s?\d/i' => 'price'] as $re => $why) {
        if (preg_match($re, $text)) {
            $errors[] = "$path: copy rule ($why)";
        }
    }
    $faqItems = substr_count($html, 'class="faq-item"');
    if ($faqItems > 0 && !str_contains($html, '"@type":"FAQPage"')) {
        $errors[] = "$path: FAQ shown without FAQPage schema";
    }
    $checkWords = false;
    foreach ($wordGroups as $g) {
        if (str_starts_with($group, $g)) {
            $checkWords = true;
        }
    }
    if (str_starts_with($path, '/services/') && $path !== '/services/') {
        $checkWords = true;
        if ($faqItems < 5) {
            $errors[] = "$path: fewer than 5 FAQ items";
        }
    }
    if ($checkWords) {
        $tok = ms_tokens($text);
        $words = count($tok);
        $fam = str_starts_with($group, 'svc:') ? 'service-town' : (str_starts_with($group, 'kw:') ? 'keyword' : $group);
        $wordStats[$fam][] = $words;
        if ($words < MIN_WORDS) {
            $errors[] = "$path: main content only $words words (< " . MIN_WORDS . ")";
        }
        if ($group !== 'service' && $group !== 'core') {
            $simGroups[$group][$path] = ms_oph(ms_shingles($tok));
        }
    }
    if (preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $ld)) {
        foreach ($ld[1] as $block) {
            if (json_decode(str_replace('<\/', '</', $block)) === null) {
                $errors[] = "$path: invalid JSON-LD";
            }
        }
    }
    preg_match_all('#href="(/[^"\#?]*)#', $html, $links);
    foreach (array_unique($links[1]) as $href) {
        if (!isset($linkOk[$href])) {
            $linkOk[$href] = isset($pages[$href]) || file_exists($href === '/' ? $dist . '/index.html' : $dist . (str_contains(basename($href), '.') ? $href : rtrim($href, '/') . '/index.html'));
        }
        if (!$linkOk[$href]) {
            $errors[] = "$path: broken internal link $href";
        }
    }
}
// Icon sprite: every <use href="/assets/i.svg#id"> on sampled pages must resolve to a <symbol id="id">.
$sprite = (string) @file_get_contents("$dist/assets/i.svg");
foreach (array_slice(array_keys($pages), 0, 60) as $sp) {
    preg_match_all('#<use href="/assets/i\.svg\#([a-z-]+)"#', (string) @file_get_contents($fileFor($sp)), $um);
    foreach (array_unique($um[1]) as $sid) {
        str_contains($sprite, '<symbol id="' . $sid . '"') || $errors[] = "$sp: icon #$sid missing from assets/i.svg";
    }
}
foreach (['robots.txt', 'sitemap.xml', '404.html', 'assets/css/site.css', 'assets/js/site.js', 'assets/i.svg'] as $f) {
    file_exists("$dist/$f") || $errors[] = "missing $f";
}
$robotsTxt = (string) @file_get_contents("$dist/robots.txt");
if (!$cfg['preview_only'] && (!str_contains($robotsTxt, 'Sitemap: ' . $report['site_url'] . '/sitemap.xml') || str_contains($robotsTxt, 'Disallow: /'))) {
    $errors[] = 'robots.txt is not a live robots file with sitemap';
}
foreach ($report['sitemaps'] ?? [] as $name => $n) {
    if ($n >= 50000) {
        $errors[] = "$name has $n URLs (>= 50,000)";
    }
    $xml = (string) @file_get_contents("$dist/$name");
    if (substr_count($xml, '<loc>') !== $n) {
        $errors[] = "$name: URL count mismatch";
    }
}

// Near-duplicate detection
$maxSim = ['sim' => 0.0, 'a' => '', 'b' => ''];
$pairsChecked = 0;
$flagged = [];
$sampleSims = [];
$groupMax = [];
foreach ($simGroups as $group => $sigs) {
    $paths = array_keys($sigs);
    $n = count($paths);
    $cand = [];
    if ($n <= 400) {
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $cand[] = [$i, $j];
            }
        }
    } else {
        // LSH: 32 bands x 4 bins
        for ($band = 0; $band < 32; $band++) {
            $buckets = [];
            foreach ($paths as $i => $p) {
                $k = implode(',', array_slice($sigs[$p], $band * 4, 4));
                $buckets[$k][] = $i;
            }
            foreach ($buckets as $members) {
                $m = count($members);
                if ($m < 2) {
                    continue;
                }
                for ($x = 0; $x < min($m, 60); $x++) {
                    for ($y = $x + 1; $y < min($m, 60); $y++) {
                        $cand[$members[$x] . ':' . $members[$y]] = [$members[$x], $members[$y]];
                    }
                }
            }
        }
        // plus a deterministic random sample for the distribution
        mt_srand(crc32($group));
        for ($r = 0; $r < 2000; $r++) {
            $a = mt_rand(0, $n - 1);
            $b = mt_rand(0, $n - 1);
            if ($a !== $b) {
                $cand['r' . $a . ':' . $b] = [$a, $b];
            }
        }
    }
    foreach ($cand as [$i, $j]) {
        $pairsChecked++;
        $sim = ms_sig_sim($sigs[$paths[$i]], $sigs[$paths[$j]]);
        if (count($sampleSims) < 200000) {
            $sampleSims[] = $sim;
        }
        if ($sim > ($groupMax[$group] ?? 0)) {
            $groupMax[$group] = $sim;
        }
        if ($sim > $maxSim['sim']) {
            $maxSim = ['sim' => $sim, 'a' => $paths[$i], 'b' => $paths[$j], 'group' => $group];
        }
        if ($sim > MAX_SIM - 0.05) {
            $flagged[] = [$paths[$i], $paths[$j], $sim];
        }
    }
}
// exact Jaccard re-check for flagged pairs and the max pair
$exactMax = 0.0;
$exactPair = null;
$recheck = $flagged;
if ($maxSim['a'] !== '') {
    $recheck[] = [$maxSim['a'], $maxSim['b'], $maxSim['sim']];
}
foreach (array_slice($recheck, 0, 5000) as [$a, $b, $est]) {
    $sa = ms_shingles(ms_tokens(ms_main_text((string) file_get_contents($fileFor($a)))));
    $sb = ms_shingles(ms_tokens(ms_main_text((string) file_get_contents($fileFor($b)))));
    $inter = count(array_intersect_key($sa, $sb));
    $j = $inter / max(1, count($sa) + count($sb) - $inter);
    if ($j > $exactMax) {
        $exactMax = $j;
        $exactPair = [$a, $b];
    }
    if ($j > MAX_SIM) {
        $errors[] = sprintf('near-duplicate bodies (Jaccard %.3f > %.2f): %s vs %s', $j, MAX_SIM, $a, $b);
    }
}
sort($sampleSims);
$median = $sampleSims ? $sampleSims[intdiv(count($sampleSims), 2)] : 0;
$p99 = $sampleSims ? $sampleSims[(int) floor(count($sampleSims) * 0.99)] : 0;

$wordSummary = [];
foreach ($wordStats as $fam => $list) {
    sort($list);
    $wordSummary[$fam] = ['pages' => count($list), 'min' => $list[0], 'median' => $list[intdiv(count($list), 2)], 'max' => end($list)];
}
$summary = [
    'pages' => count($pages),
    'words' => $wordSummary,
    'similarity' => ['pairs_checked' => $pairsChecked, 'median_est' => round($median, 3), 'p99_est' => round($p99, 3), 'max_est' => round($maxSim['sim'], 3), 'max_est_pair' => [$maxSim['a'], $maxSim['b']], 'max_exact_jaccard' => round($exactMax, 3), 'max_exact_pair' => $exactPair, 'threshold' => MAX_SIM],
    'max_est_by_group' => array_map(fn ($v) => round($v, 3), $groupMax),
    'seconds' => round(microtime(true) - $t0, 1),
];
file_put_contents($dist . '/check-report.json', json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
if ($errors) {
    $errors = array_unique($errors);
    fwrite(STDERR, implode("\n", array_slice($errors, 0, 200)) . "\n" . count($errors) . " check(s) failed\n" . json_encode($summary, JSON_UNESCAPED_SLASHES) . "\n");
    exit(1);
}
fwrite(STDOUT, 'Checks passed for ' . count($pages) . " pages\n" . json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
