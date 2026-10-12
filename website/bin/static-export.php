<?php
declare(strict_types=1);

/**
 * Static export: writes dist/ from website/. Netlify publishes dist/ only.
 * Usage: php website/bin/static-export.php
 */
require dirname(__DIR__) . '/includes/bootstrap.php';
require dirname(__DIR__) . '/includes/layout.php';
require dirname(__DIR__) . '/includes/pages.php';
require dirname(__DIR__) . '/includes/local.php';

$repo = dirname(__DIR__, 2);
$dist = $repo . '/dist';

function ms_rrmdir(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($it as $f) {
        $f->isDir() ? rmdir($f->getPathname()) : unlink($f->getPathname());
    }
    rmdir($dir);
}

function ms_copy_tree(string $src, string $dest): void
{
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($it as $f) {
        $target = $dest . substr($f->getPathname(), strlen($src));
        if ($f->isDir()) {
            is_dir($target) || mkdir($target, 0775, true);
        } else {
            is_dir(dirname($target)) || mkdir(dirname($target), 0775, true);
            copy($f->getPathname(), $target);
        }
    }
}

function ms_write(string $dist, array $page): string
{
    $path = $page['path'];
    $rel = $path === '/' ? '/index.html' : (str_ends_with($path, '.html') ? $path : rtrim($path, '/') . '/index.html');
    $target = $dist . $rel;
    is_dir(dirname($target)) || mkdir(dirname($target), 0775, true);
    file_put_contents($target, ms_render($page)) !== false || throw new RuntimeException('write failed: ' . $target);
    return $path;
}

$t0 = microtime(true);
ms_rrmdir($dist);
mkdir($dist, 0775, true);
ms_copy_tree(dirname(__DIR__) . '/assets', $dist . '/assets');

$cfg = ms_config();
$written = [];   // path => group
$counts = [];
$emit = function (array $p, string $type) use ($dist, &$written, &$counts): void {
    $path = ms_write($dist, $p);
    $written[$path] = $p['group'] ?? $type;
    $counts[$type] = ($counts[$type] ?? 0) + 1;
};

// Core pages
$emit(ms_page_home(), 'core');
$emit(ms_page_services(), 'core');
foreach (ms_data('services') as $slug => $s) {
    $emit(ms_page_service($slug, $s), 'service');
}
$emit(ms_page_industries(), 'core');
foreach (ms_data('industries') as $slug => $i) {
    $emit(ms_page_industry($slug, $i), 'industry');
}
foreach ([ms_page_about(), ms_page_how(), ms_page_faq(), ms_page_contact(), ms_page_privacy(), ms_page_thanks(), ms_page_404()] as $p) {
    $emit($p, 'core');
}

// Areas: hub, regions, counties, legacy curated (cheshire, uk-wide), towns in wave
$emit(ms_page_areas_hub(), 'area-hub');
foreach (ms_regions() as $rs => $r) {
    $emit(ms_page_region($rs, $r), 'region');
}
foreach (ms_counties() as $c) {
    $emit(ms_page_county($c), 'county');
}
$places = ms_places();
foreach (ms_data('areas') as $slug => $a) {
    if (!isset($places[$slug])) {
        $emit(ms_page_area($slug, $a), 'area-legacy');
    }
}
$wave = ms_wave_places();
foreach ($wave as $ts => $p) {
    $emit(ms_page_town($p), 'town');
}

// Keyword pages
$emit(ms_page_keywords_hub(), 'keyword-hub');
foreach (ms_keyword_pages() as $stem => $def) {
    $emit(ms_page_keyword($stem, $def), 'keyword');
}

// Service x town matrix (the wave)
$services = ms_data('services');
foreach ($wave as $ts => $p) {
    foreach ($services as $slug => $s) {
        $emit(ms_page_service_town($slug, $s, $p), 'service-town');
    }
}

// Sitemaps: index + child files, each < 50,000 URLs
$sitemapFiles = [];
$chunks = [];
foreach ($written as $path => $group) {
    if ($path === '/404.html' || $path === '/thank-you/') {
        continue;
    }
    $bucket = str_starts_with($group, 'svc:') ? 'services-towns' : (in_array($group, ['town', 'county', 'region'], true) ? 'areas' : (str_starts_with($group, 'kw:') ? 'keywords' : 'core'));
    $chunks[$bucket][] = $path;
}
$today = date('Y-m-d');
foreach ($chunks as $bucket => $paths) {
    foreach (array_chunk($paths, 45000) as $i => $part) {
        $name = 'sitemap-' . $bucket . '-' . ($i + 1) . '.xml';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($part as $path) {
            $xml .= '  <url><loc>' . ms_h(ms_url($path)) . '</loc><lastmod>' . $today . '</lastmod></url>' . "\n";
        }
        file_put_contents($dist . '/' . $name, $xml . '</urlset>' . "\n");
        $sitemapFiles[$name] = count($part);
    }
}
$idx = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($sitemapFiles as $name => $n) {
    $idx .= '  <sitemap><loc>' . ms_h(ms_url('/' . $name)) . '</loc><lastmod>' . $today . '</lastmod></sitemap>' . "\n";
}
file_put_contents($dist . '/sitemap.xml', $idx . '</sitemapindex>' . "\n");

$robots = $cfg['preview_only']
    ? "# Preview build — not indexed\nUser-agent: *\nDisallow: /\n"
    : "User-agent: *\nAllow: /\n\nSitemap: " . ms_url('/sitemap.xml') . "\n";
file_put_contents($dist . '/robots.txt', $robots);

// Keyword coverage map for the locked lists
$kwStats = [];
$kwMap = "keyword,list,target,type\n";
foreach (['CORE' => 'MARKETING-KEYWORDS-CORE.txt', 'XPLACE-P0' => 'MARKETING-KEYWORDS-XPLACE-P0.txt', 'HANDOFF-AI' => 'HANDOFF-MARKETING-AI-KEYWORDS.txt'] as $list => $f) {
    $kwStats[$list] = ['total' => 0, 'live' => 0, 'pending_wave' => 0, 'unmapped' => 0];
    foreach (file(MS_ROOT . '/data/keywords/' . $f, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $kw) {
        $kw = trim($kw);
        [$target, $type] = ms_keyword_target($kw);
        $kwStats[$list]['total']++;
        if ($target !== null && isset($written[$target])) {
            $kwStats[$list]['live']++;
        } elseif ($type === 'unmapped') {
            $kwStats[$list]['unmapped']++;
        } else {
            $kwStats[$list]['pending_wave']++;
        }
        $kwMap .= $kw . ',' . $list . ',' . ($target ?? '') . ',' . $type . "\n";
    }
}
file_put_contents($dist . '/keyword-map.csv', $kwMap);

$secs = round(microtime(true) - $t0, 1);
file_put_contents($dist . '/export-report.json', json_encode([
    'site_url' => $cfg['site_url'],
    'preview_only' => $cfg['preview_only'],
    'wave_towns' => ms_wave_count(),
    'dataset_towns' => count($places),
    'counts' => $counts,
    'count' => count($written),
    'sitemaps' => $sitemapFiles,
    'keywords' => $kwStats,
    'build_seconds' => $secs,
    'pages' => $written,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

fwrite(STDOUT, 'Exported ' . count($written) . ' pages to dist/ in ' . $secs . "s (wave_towns=" . ms_wave_count() . ")\n" . json_encode($counts) . "\n" . json_encode($kwStats) . "\n");
