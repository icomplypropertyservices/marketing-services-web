<?php
declare(strict_types=1);

/**
 * Non-secret site configuration.
 * NETLIFY_AUTH_TOKEN is never stored here. GitHub Actions reads it from repo secrets.
 */
return [
    'brand' => 'iComply Marketing Services',
    'brand_short' => 'iComply',
    'brand_tail' => 'Marketing Services',
    // LIVE canonical host. Apex cutover = change this ONE line to
    // 'https://icomplymarketingservices.co.uk' once that domain is registered and attached in Netlify.
    // (The SITE_URL env var still overrides it for one-off builds.)
    'site_url' => 'https://icomply-marketing-services.netlify.app',
    'apex_domain' => 'icomplymarketingservices.co.uk',
    'preview_url' => 'https://icomply-marketing-services.netlify.app',
    'preview_site_name' => 'icomply-marketing-services',
    'netlify_site_id' => 'a888cb51-357f-4572-95a0-57e1b080b8f6',
    // Contact routes. Phone + WhatsApp reuse the iComply group line.
    // Swap email to a @icomplymarketingservices.co.uk mailbox once one exists.
    'phone_display' => '07517 806082',
    'phone_tel' => '+447517806082',
    'whatsapp' => '447517806082',
    'email' => 'icomplypropertyservices@gmail.com',
    'hours' => 'Mon–Fri 9am–5:30pm',
    'base' => 'Stockport, Greater Manchester',
    'navy' => '#0B1F3A',
    'orange' => '#FF6B00',
    'preview_only' => false, // LIVE since 2026-10-06 (Jack Scott go). true = noindex preview build.
    // Rollout wave: number of towns (from website/data/uk-places.csv, in rank order) that get
    // town hub + 38 service x town pages. Full matrix = 5000. Env WAVE_TOWNS overrides for test builds.
    'wave_towns' => 500,
];
