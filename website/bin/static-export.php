<?php
declare(strict_types=1);

/**
 * Static export: writes dist/ from website/. Netlify publishes dist/ only.
 * Usage: php website/bin/static-export.php
 */
require dirname(__DIR__) . '/includes/bootstrap.php';
require dirname(__DIR__) . '/includes/layout.php';
require dirname(__DIR__) . '/includes/pages.php';

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

ms_rrmdir($dist);
mkdir($dist, 0775, true);
ms_copy_tree(dirname(__DIR__) . '/assets', $dist . '/assets');

$pages = [ms_page_home(), ms_page_services()];
foreach (ms_data('services') as $slug => $s) {
    $pages[] = ms_page_service($slug, $s);
}
$pages[] = ms_page_industries();
foreach (ms_data('industries') as $slug => $i) {
    $pages[] = ms_page_industry($slug, $i);
}
$pages[] = ms_page_areas();
foreach (ms_data('areas') as $slug => $a) {
    $pages[] = ms_page_area($slug, $a);
}
array_push($pages, ms_page_about(), ms_page_how(), ms_page_faq(), ms_page_contact(), ms_page_privacy(), ms_page_thanks(), ms_page_404());

$written = [];
foreach ($pages as $p) {
    $written[] = ms_write($dist, $p);
}

// sitemap (excludes 404 + thank-you)
$cfg = ms_config();
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($written as $path) {
    if ($path === '/404.html' || $path === '/thank-you/') {
        continue;
    }
    $xml .= '  <url><loc>' . ms_h(ms_url($path)) . '</loc></url>' . "\n";
}
$xml .= '</urlset>' . "\n";
file_put_contents($dist . '/sitemap.xml', $xml);

$robots = $cfg['preview_only']
    ? "# Preview build — not indexed\nUser-agent: *\nDisallow: /\n"
    : "User-agent: *\nAllow: /\nSitemap: " . ms_url('/sitemap.xml') . "\n";
file_put_contents($dist . '/robots.txt', $robots);

file_put_contents($dist . '/export-report.json', json_encode([
    'site_url' => $cfg['site_url'],
    'preview_only' => $cfg['preview_only'],
    'pages' => $written,
    'count' => count($written),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

fwrite(STDOUT, 'Exported ' . count($written) . " pages to dist/\n");
