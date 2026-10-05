<?php
declare(strict_types=1);

/** Post-export checks. Exits non-zero on any failure. */
$dist = dirname(__DIR__, 2) . '/dist';
$report = json_decode((string) @file_get_contents($dist . '/export-report.json'), true);
$errors = [];
if (!is_array($report)) {
    fwrite(STDERR, "export-report.json missing — run static-export.php first\n");
    exit(1);
}
$titles = [];
foreach ($report['pages'] as $path) {
    $file = $dist . ($path === '/' ? '/index.html' : (str_ends_with($path, '.html') ? $path : rtrim($path, '/') . '/index.html'));
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
    if (!str_contains($html, 'rel="canonical"')) {
        $errors[] = "$path: no canonical";
    }
    if (!str_contains($html, 'href="tel:') || !str_contains($html, 'wa.me/') || !str_contains($html, '/contact/#quote')) {
        $errors[] = "$path: missing quote/call/WhatsApp CTA";
    }
    foreach (['Services', 'Areas', 'Company', 'Contact'] as $col) {
        if (!str_contains($html, '<h3>' . $col . '</h3>')) {
            $errors[] = "$path: footer column $col missing";
        }
    }
    if (str_starts_with($path, '/services/') && $path !== '/services/') {
        if (substr_count($html, 'class="faq-item"') < 5) {
            $errors[] = "$path: fewer than 5 FAQ items";
        }
        $text = trim(preg_replace('/\s+/', ' ', strip_tags(preg_replace('#<(script|header|footer|nav)[^>]*>.*?</\1>#s', ' ', $html))));
        $words = str_word_count($text);
        if ($words < 800) {
            $errors[] = "$path: body only $words words";
        }
    }
    if (preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $ld)) {
        foreach ($ld[1] as $block) {
            if (json_decode(str_replace('<\/', '</', $block)) === null) {
                $errors[] = "$path: invalid JSON-LD";
            }
        }
    }
    // internal links must resolve
    preg_match_all('#href="(/[^"\#?]*)#', $html, $links);
    foreach (array_unique($links[1]) as $href) {
        $target = $dist . (str_contains(basename($href), '.') ? $href : rtrim($href, '/') . '/index.html');
        if ($href === '/') {
            $target = $dist . '/index.html';
        }
        if (!file_exists($target)) {
            $errors[] = "$path: broken internal link $href";
        }
    }
}
foreach (['robots.txt', 'sitemap.xml', '404.html', 'assets/css/site.css', 'assets/js/site.js'] as $f) {
    file_exists("$dist/$f") || $errors[] = "missing $f";
}
if ($errors) {
    fwrite(STDERR, implode("\n", array_unique($errors)) . "\n" . count($errors) . " check(s) failed\n");
    exit(1);
}
fwrite(STDOUT, 'Checks passed for ' . count($report['pages']) . " pages\n");
