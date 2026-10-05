<?php
declare(strict_types=1);

function ms_brand_html(): string
{
    $c = ms_config();
    return '<a class="brand" href="/" aria-label="' . ms_h($c['brand']) . ' home">'
        . '<img src="/assets/images/icomply-mark.svg" alt="" width="36" height="36">'
        . '<span class="brand-text">' . ms_h($c['brand_short']) . ' <em>' . ms_h($c['brand_tail']) . '</em></span></a>';
}

function ms_header_html(string $path): string
{
    $c = ms_config();
    $services = ms_data('services');
    $menu = '';
    foreach ($services as $slug => $s) {
        $menu .= '<a href="/services/' . $slug . '/">' . ms_icon($s['icon'], 'icon icon-sm') . '<span>' . ms_h($s['name']) . '</span></a>';
    }
    $nav = [
        ['Industries', '/industries/'],
        ['Areas', '/areas/'],
        ['How we work', '/how-we-work/'],
        ['About', '/about/'],
        ['FAQs', '/faq/'],
    ];
    $links = '';
    foreach ($nav as [$label, $href]) {
        $active = str_starts_with($path, $href) ? ' aria-current="page"' : '';
        $links .= '<a href="' . $href . '"' . $active . '>' . ms_h($label) . '</a>';
    }
    return '<header class="site-header"><div class="wrap header-inner">'
        . ms_brand_html()
        . '<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav"><span></span><span></span><span></span><span class="sr">Menu</span></button>'
        . '<nav class="nav" id="primary-nav" aria-label="Primary">'
        . '<div class="has-menu"><a href="/services/" class="menu-trigger">Services</a><div class="mega">' . $menu
        . '<a class="mega-all" href="/services/">All marketing services ' . ms_icon('arrow', 'icon icon-sm') . '</a></div></div>'
        . $links
        . '<a class="nav-phone" href="' . ms_tel() . '">' . ms_icon('phone', 'icon icon-sm') . ms_h($c['phone_display']) . '</a>'
        . '<a class="btn btn-primary btn-sm" href="/contact/#quote">Get a free quote</a>'
        . '</nav></div></header>';
}

function ms_breadcrumbs(array $trail): string
{
    if ($trail === []) {
        return '';
    }
    $items = ['<a href="/">Home</a>'];
    $ld = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => ms_url('/')]];
    $n = 2;
    foreach ($trail as [$label, $href]) {
        $items[] = $href === '' ? '<span aria-current="page">' . ms_h($label) . '</span>' : '<a href="' . ms_h($href) . '">' . ms_h($label) . '</a>';
        $entry = ['@type' => 'ListItem', 'position' => $n++, 'name' => $label];
        if ($href !== '') {
            $entry['item'] = ms_url($href);
        }
        $ld[] = $entry;
    }
    return '<nav class="crumbs wrap" aria-label="Breadcrumb">' . implode('<span class="sep">/</span>', $items) . '</nav>'
        . ms_jsonld(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $ld]);
}

function ms_cta_buttons(string $context = '', bool $onDark = true): string
{
    $c = ms_config();
    return '<div class="cta-row">'
        . '<a class="btn btn-primary" href="/contact/#quote">' . ms_icon('quote', 'icon icon-sm') . 'Get a free quote</a>'
        . '<a class="btn ' . ($onDark ? 'btn-ghost' : 'btn-outline') . '" href="' . ms_tel() . '">' . ms_icon('phone', 'icon icon-sm') . 'Call ' . ms_h($c['phone_display']) . '</a>'
        . '<a class="btn btn-whatsapp" href="' . ms_h(ms_whatsapp($context)) . '" target="_blank" rel="noopener">' . ms_icon('chat', 'icon icon-sm') . 'WhatsApp us</a>'
        . '</div>';
}

function ms_cta_band(string $heading = 'Ready for more enquiries?', string $text = '', string $context = ''): string
{
    $text = $text !== '' ? $text : 'Tell us what you sell, where, and what a good month looks like. We will come back with a clear plan and a written quote — no obligation, no jargon.';
    return '<section class="cta-band"><div class="wrap cta-band-inner"><div>'
        . '<h2>' . ms_h($heading) . '</h2><p>' . ms_h($text) . '</p>'
        . '<ul class="ticks">'
        . '<li>' . ms_icon('check', 'icon icon-sm') . 'Free initial review</li>'
        . '<li>' . ms_icon('check', 'icon icon-sm') . 'Written quote before any work</li>'
        . '<li>' . ms_icon('check', 'icon icon-sm') . 'Accounts stay in your name</li></ul>'
        . '</div>' . ms_cta_buttons($context) . '</div></section>';
}

/** @param array<int,array{0:string,1:string}> $faqs */
function ms_faq_html(array $faqs, string $heading = 'Frequently asked questions', string $id = 'faq'): string
{
    $items = '';
    foreach ($faqs as $i => [$q, $a]) {
        $items .= '<details class="faq-item"' . ($i === 0 ? ' open' : '') . '><summary>' . ms_h($q) . '</summary>'
            . '<div class="faq-answer"><p>' . ms_h($a) . '</p></div></details>';
    }
    return '<section class="section faq" id="' . ms_h($id) . '"><div class="wrap narrow">'
        . '<h2>' . ms_h($heading) . '</h2><div class="faq-accordion">' . $items . '</div></div></section>';
}

function ms_faq_jsonld(array $faqs): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(fn ($f) => [
            '@type' => 'Question',
            'name' => $f[0],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
        ], $faqs),
    ];
}

function ms_quote_form(string $preselect = ''): string
{
    $services = ms_data('services');
    $opts = '';
    foreach ($services as $slug => $s) {
        $checked = $slug === $preselect ? ' checked' : '';
        $opts .= '<label class="check"><input type="checkbox" name="services[]" value="' . ms_h($s['name']) . '"' . $checked . '> ' . ms_h($s['name']) . '</label>';
    }
    return '<form class="quote-form" name="quote" method="POST" action="/thank-you/" data-netlify="true" netlify-honeypot="company_website">'
        . '<input type="hidden" name="form-name" value="quote">'
        . '<p class="hp"><label>Leave blank <input name="company_website" tabindex="-1" autocomplete="off"></label></p>'
        . '<div class="form-grid">'
        . '<label>Your name <span class="req">(required)</span><input name="name" required autocomplete="name"></label>'
        . '<label>Company <input name="company" autocomplete="organization"></label>'
        . '<label>Email <span class="req">(required)</span><input type="email" name="email" required autocomplete="email"></label>'
        . '<label>Phone <input type="tel" name="phone" autocomplete="tel"></label>'
        . '<label>Website <input type="url" name="website" placeholder="https://"></label>'
        . '<label>Monthly marketing budget <select name="budget"><option value="">Choose one</option><option>Not sure yet</option><option>Under £500</option><option>£500 – £1,500</option><option>£1,500 – £5,000</option><option>£5,000+</option></select></label>'
        . '</div>'
        . '<fieldset><legend>What do you need help with?</legend><div class="check-grid">' . $opts . '</div></fieldset>'
        . '<label>What would a great result look like? <textarea name="message" rows="5" placeholder="e.g. 20 more quote requests a month for boiler installs in Stockport"></textarea></label>'
        . '<button class="btn btn-primary" type="submit">Send my quote request ' . ms_icon('arrow', 'icon icon-sm') . '</button>'
        . '<p class="form-fine">We reply within one working day. Prefer to talk? Call <a href="' . ms_tel() . '">' . ms_h(ms_config()['phone_display']) . '</a> or <a href="' . ms_h(ms_whatsapp()) . '" target="_blank" rel="noopener">message us on WhatsApp</a>.</p>'
        . '</form>';
}

function ms_footer_html(): string
{
    $c = ms_config();
    $services = ms_data('services');
    $areas = ms_data('areas');
    $industries = ms_data('industries');
    $svc = '';
    foreach ($services as $slug => $s) {
        $svc .= '<li><a href="/services/' . $slug . '/">' . ms_h($s['name']) . '</a></li>';
    }
    $ar = '';
    foreach ($areas as $slug => $a) {
        $ar .= '<li><a href="/areas/' . $slug . '/">' . ms_h($a['name'] === 'UK-wide' ? 'UK-wide' : 'Marketing in ' . $a['name']) . '</a></li>';
    }
    $ind = '';
    foreach ($industries as $slug => $i) {
        $ind .= '<li><a href="/industries/' . $slug . '/">' . ms_h($i['name']) . '</a></li>';
    }
    return '<footer class="site-footer"><div class="wrap">'
        . '<div class="footer-top">' . ms_brand_html()
        . '<p>Full-service marketing for UK SMEs and B2B firms: SEO, paid ads, social, content, email, branding, websites, listings, reviews and video. Part of the iComply group.</p>'
        . ms_cta_buttons() . '</div>'
        . '<div class="footer-grid">'
        . '<div class="footer-col"><h3>Services</h3><ul>' . $svc . '<li><a href="/services/">All services</a></li></ul></div>'
        . '<div class="footer-col"><h3>Areas</h3><ul>' . $ar . '<li><a href="/areas/">All areas</a></li></ul></div>'
        . '<div class="footer-col"><h3>Company</h3><ul>'
        . '<li><a href="/about/">About us</a></li>'
        . '<li><a href="/how-we-work/">How we work &amp; pricing</a></li>'
        . '<li><a href="/industries/">Industries</a></li>' . $ind
        . '<li><a href="/faq/">FAQs</a></li>'
        . '<li><a href="/privacy/">Privacy</a></li>'
        . '</ul></div>'
        . '<div class="footer-col"><h3>Contact</h3><ul class="contact-list">'
        . '<li><a href="' . ms_tel() . '">' . ms_icon('phone', 'icon icon-sm') . ms_h($c['phone_display']) . '</a></li>'
        . '<li><a href="' . ms_h(ms_whatsapp()) . '" target="_blank" rel="noopener">' . ms_icon('chat', 'icon icon-sm') . 'WhatsApp</a></li>'
        . '<li><a href="mailto:' . ms_h($c['email']) . '">' . ms_icon('mail', 'icon icon-sm') . ms_h($c['email']) . '</a></li>'
        . '<li><a href="/contact/#quote">' . ms_icon('quote', 'icon icon-sm') . 'Request a quote</a></li>'
        . '<li class="muted">' . ms_icon('pin', 'icon icon-sm') . ms_h($c['base']) . ' · UK-wide</li>'
        . '<li class="muted">' . ms_h($c['hours']) . '</li>'
        . '</ul></div>'
        . '</div>'
        . '<div class="footer-bottom"><span>© ' . date('Y') . ' ' . ms_h($c['brand']) . '. All rights reserved.</span>'
        . '<span><a href="/privacy/">Privacy</a> · <a href="/sitemap.xml">Sitemap</a></span></div>'
        . '</div></footer>';
}

/**
 * @param array{title:string,description:string,path:string,body:string,jsonld?:array,trail?:array,hero?:string} $p
 */
function ms_render(array $p): string
{
    $c = ms_config();
    $canonical = ms_url($p['path']);
    $jsonld = '';
    foreach ($p['jsonld'] ?? [] as $block) {
        $jsonld .= ms_jsonld($block) . "\n";
    }
    $preview = $c['preview_only']
        ? '<div class="preview-bar">Preview build · ' . ms_h(parse_url($c['preview_url'], PHP_URL_HOST) ?: '') . ' · not yet live on ' . ms_h(parse_url($c['site_url'], PHP_URL_HOST) ?: '') . '</div>'
        : '';
    $robots = $c['preview_only'] ? 'noindex, nofollow' : 'index, follow';
    return '<!DOCTYPE html>' . "\n" . '<html lang="en-GB">' . "\n<head>\n"
        . '<meta charset="utf-8">' . "\n"
        . '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n"
        . '<title>' . ms_h($p['title']) . '</title>' . "\n"
        . '<meta name="description" content="' . ms_h($p['description']) . '">' . "\n"
        . '<meta name="robots" content="' . $robots . '">' . "\n"
        . '<link rel="canonical" href="' . ms_h($canonical) . '">' . "\n"
        . '<meta property="og:title" content="' . ms_h($p['title']) . '">' . "\n"
        . '<meta property="og:description" content="' . ms_h($p['description']) . '">' . "\n"
        . '<meta property="og:url" content="' . ms_h($canonical) . '">' . "\n"
        . '<meta property="og:type" content="website">' . "\n"
        . '<meta property="og:image" content="' . ms_h(ms_url('/assets/images/og-default.png')) . '">' . "\n"
        . '<meta property="og:site_name" content="' . ms_h($c['brand']) . '">' . "\n"
        . '<meta name="twitter:card" content="summary_large_image">' . "\n"
        . '<meta name="theme-color" content="#0B1F3A">' . "\n"
        . '<link rel="icon" href="/assets/images/favicon.ico" sizes="any">' . "\n"
        . '<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32.png">' . "\n"
        . '<link rel="apple-touch-icon" href="/assets/images/apple-touch-icon.png">' . "\n"
        . '<link rel="manifest" href="/assets/images/site.webmanifest">' . "\n"
        . '<link rel="stylesheet" href="/assets/css/site.css">' . "\n"
        . ms_jsonld(['@context' => 'https://schema.org'] + ms_org_jsonld()) . "\n"
        . $jsonld
        . "</head>\n<body>\n"
        . '<a class="skip" href="#content">Skip to content</a>' . "\n"
        . $preview . "\n"
        . ms_header_html($p['path']) . "\n"
        . ms_breadcrumbs($p['trail'] ?? []) . "\n"
        . '<main id="content">' . "\n" . $p['body'] . "\n</main>\n"
        . ms_footer_html() . "\n"
        . '<div class="mobile-bar" aria-label="Quick contact">'
        . '<a href="' . ms_tel() . '">' . ms_icon('phone', 'icon icon-sm') . 'Call</a>'
        . '<a href="' . ms_h(ms_whatsapp()) . '" target="_blank" rel="noopener">' . ms_icon('chat', 'icon icon-sm') . 'WhatsApp</a>'
        . '<a class="mb-quote" href="/contact/#quote">' . ms_icon('quote', 'icon icon-sm') . 'Free quote</a></div>' . "\n"
        . '<script src="/assets/js/site.js" defer></script>' . "\n"
        . "</body>\n</html>\n";
}
