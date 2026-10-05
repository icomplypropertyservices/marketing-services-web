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
    'site_url' => 'https://icomplymarketingservices.co.uk',
    'preview_url' => 'https://icomply-marketing-services.netlify.app',
    'preview_site_name' => 'icomply-marketing-services',
    'netlify_site_id' => 'a888cb51-357f-4572-95a0-57e1b080b8f6',
    // Contact routes. Phone + WhatsApp reuse the iComply group line.
    // Swap email to a @icomplymarketingservices.co.uk mailbox once one exists.
    'phone_display' => '07517 806082',
    'phone_tel' => '+447517806082',
    'whatsapp' => '447517806082',
    'email' => 'info@icomplypropertyservices.co.uk',
    'hours' => 'Mon–Fri 9am–5:30pm',
    'base' => 'Stockport, Greater Manchester',
    'navy' => '#0B1F3A',
    'orange' => '#FF6B00',
    'preview_only' => true,
];
