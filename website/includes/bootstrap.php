<?php
declare(strict_types=1);

const MS_ROOT = __DIR__ . '/..';

function ms_config(): array
{
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require MS_ROOT . '/config/site.php';
        $env = getenv('SITE_URL');
        if (is_string($env) && $env !== '') {
            $cfg['site_url'] = rtrim($env, '/');
        }
    }
    return $cfg;
}

function ms_data(string $name): array
{
    static $cache = [];
    if (!isset($cache[$name])) {
        $cache[$name] = require MS_ROOT . '/data/' . $name . '.php';
        if ($name === 'services') {
            $extra = MS_ROOT . '/data/services_extra.php';
            if (is_file($extra)) {
                $cache[$name] = array_merge($cache[$name], require $extra);
            }
        }
    }
    return $cache[$name];
}

function ms_h(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
}

function ms_url(string $path): string
{
    return ms_config()['site_url'] . $path;
}

function ms_tel(): string
{
    return 'tel:' . ms_config()['phone_tel'];
}

function ms_whatsapp(string $context = ''): string
{
    $msg = 'Hi iComply Marketing, I would like a quote' . ($context !== '' ? ' for ' . $context : '') . '.';
    return 'https://wa.me/' . ms_config()['whatsapp'] . '?text=' . rawurlencode($msg);
}

/** Small inline line icons (stroke = currentColor). */
function ms_icon(string $key, string $class = 'icon'): string
{
    $paths = [
        'search' => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-5-5"/>',
        'target' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.5"/>',
        'megaphone' => '<path d="M3 11v2a1 1 0 0 0 1 1h3l6 4V6L7 10H4a1 1 0 0 0-1 1z"/><path d="M17 8a5 5 0 0 1 0 8"/>',
        'pen' => '<path d="M4 20l4-1 11-11-3-3L5 16l-1 4z"/><path d="M14 6l3 3"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
        'share' => '<circle cx="18" cy="5" r="2.5"/><circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="19" r="2.5"/><path d="M8.2 10.8l7.6-4.4M8.2 13.2l7.6 4.4"/>',
        'star' => '<path d="M12 3l2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1-4.4-4.3 6.1-.9z"/>',
        'browser' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M7 6.5h.01M10 6.5h.01"/>',
        'pin' => '<path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/>',
        'shield' => '<path d="M12 3l8 3v6c0 4.5-3.4 8.3-8 9-4.6-.7-8-4.5-8-9V6z"/><path d="M8.5 12l2.5 2.5 4.5-5"/>',
        'play' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M10 9l5 3-5 3z"/>',
        'compass' => '<circle cx="12" cy="12" r="9"/><path d="M15.5 8.5l-2 5-5 2 2-5z"/>',
        'phone' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
        'chat' => '<path d="M4 20l1.5-4A8 8 0 1 1 8 18.5z"/>',
        'check' => '<path d="M5 12.5l4.5 4.5L19 7"/>',
        'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'quote' => '<path d="M4 4h16v12H8l-4 4z"/><path d="M8 9h8M8 12h5"/>',
        'bot' => '<rect x="5" y="8" width="14" height="10" rx="3"/><path d="M12 8V5M9 12h.01M15 12h.01M9 16h6M8 4l2 2M16 4l-2 2"/>',
        'headset' => '<path d="M4 14v-2a8 8 0 0 1 16 0v2"/><path d="M4 14v3a2 2 0 0 0 2 2h2v-5H6a2 2 0 0 0-2 2zM20 14v3a2 2 0 0 1-2 2h-2v-5h2a2 2 0 0 1 2 2z"/>',
        'spark' => '<path d="M12 3l1.2 4.2L17.5 8.5 13.2 9.8 12 14l-1.2-4.2L6.5 8.5l4.3-1.3zM18 14l.7 2.3L21 17l-2.3.7L18 20l-.7-2.3L15 17l2.3-.7z"/>',
        'radar' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><path d="M12 12l6-4"/>',
        'chart' => '<path d="M4 19h16M7 16V9M12 16V5M17 16v-7"/>',
        'reply' => '<path d="M9 17H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v3"/><path d="M9 14l-4 3 4 3v-2c5 0 8-1.5 9-5"/>',
        'network' => '<circle cx="6" cy="6" r="2.5"/><circle cx="18" cy="6" r="2.5"/><circle cx="12" cy="18" r="2.5"/><path d="M8 7.5l2.5 7M16 7.5l-2.5 7M8.5 6h7"/>',
        'cpu' => '<rect x="7" y="7" width="10" height="10" rx="1.5"/><path d="M10 7V4M14 7V4M10 20v-3M14 20v-3M7 10H4M7 14H4M20 10h-3M20 14h-3"/>',
    ];
    $p = $paths[$key] ?? $paths['check'];
    return '<svg class="' . ms_h($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $p . '</svg>';
}

function ms_jsonld(array $data): string
{
    $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    return '<script type="application/ld+json">' . str_replace('</', '<\/', $json) . '</script>';
}

function ms_org_jsonld(): array
{
    $c = ms_config();
    return [
        '@type' => 'ProfessionalService',
        '@id' => ms_url('/#org'),
        'name' => $c['brand'],
        'url' => ms_url('/'),
        'telephone' => $c['phone_tel'],
        'email' => $c['email'],
        'logo' => ms_url('/assets/images/favicon-512.png'),
        'areaServed' => 'GB',
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Stockport', 'addressRegion' => 'Greater Manchester', 'addressCountry' => 'GB'],
        'priceRange' => 'POA',
    ];
}
