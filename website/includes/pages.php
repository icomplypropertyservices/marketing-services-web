<?php
declare(strict_types=1);

/** Page builders. Each returns the array consumed by ms_render(). */

function ms_hero(string $eyebrow, string $h1, string $lede, string $context = '', string $aside = ''): string
{
    return '<section class="hero"><div class="wrap hero-inner' . ($aside !== '' ? ' has-aside' : '') . '"><div class="hero-copy">'
        . ($eyebrow !== '' ? '<p class="eyebrow">' . ms_h($eyebrow) . '</p>' : '')
        . '<h1>' . ms_h($h1) . '</h1><p class="lede">' . ms_h($lede) . '</p>'
        . ms_cta_buttons($context)
        . '<ul class="hero-trust">'
        . '<li>' . ms_icon('check', 'icon icon-sm') . 'Free marketing review</li>'
        . '<li>' . ms_icon('check', 'icon icon-sm') . 'Reply within one working day</li>'
        . '<li>' . ms_icon('check', 'icon icon-sm') . 'UK team, plain-English reporting</li></ul>'
        . '</div>' . $aside . '</div></section>';
}

function ms_ctx(array $s): string
{
    // Sentence-case label that keeps platform names (Google, Meta) and acronyms intact.
    return preg_match('/^(SEO|Google|Meta)/', $s['name']) ? $s['name'] : strtolower($s['name']);
}

function ms_service_card(string $slug, array $s, bool $long = false): string
{
    return '<a class="card service-card" href="/services/' . $slug . '/">'
        . '<span class="card-icon">' . ms_icon($s['icon']) . '</span>'
        . '<h3>' . ms_h($s['name']) . '</h3>'
        . '<p>' . ms_h($long ? $s['lede'] : $s['meta_desc']) . '</p>'
        . '<span class="card-link">Explore ' . ms_h(ms_ctx($s)) . ' ' . ms_icon('arrow', 'icon icon-sm') . '</span></a>';
}

function ms_services_grid(bool $long = false, array $only = []): string
{
    $out = '';
    foreach (ms_data('services') as $slug => $s) {
        if ($only !== [] && !in_array($slug, $only, true)) {
            continue;
        }
        $out .= ms_service_card($slug, $s, $long);
    }
    return '<div class="grid grid-3">' . $out . '</div>';
}

function ms_steps(array $steps): string
{
    $out = '';
    foreach ($steps as $i => [$t, $d]) {
        $out .= '<li><span class="step-n">' . ($i + 1) . '</span><h3>' . ms_h($t) . '</h3><p>' . ms_h($d) . '</p></li>';
    }
    return '<ol class="steps">' . $out . '</ol>';
}

function ms_paras(array $ps): string
{
    return implode('', array_map(fn ($p) => '<p>' . ms_h($p) . '</p>', $ps));
}

function ms_quote_aside(string $title, string $text, string $context): string
{
    $c = ms_config();
    return '<aside class="side-card"><h2>' . ms_h($title) . '</h2><p>' . ms_h($text) . '</p>'
        . '<a class="btn btn-primary btn-block" href="/contact/#quote">Get a free quote</a>'
        . '<a class="btn btn-outline btn-block" href="' . ms_tel() . '">' . ms_icon('phone', 'icon icon-sm') . ms_h($c['phone_display']) . '</a>'
        . '<a class="btn btn-whatsapp btn-block" href="' . ms_h(ms_whatsapp($context)) . '" target="_blank" rel="noopener">' . ms_icon('chat', 'icon icon-sm') . 'WhatsApp us</a>'
        . '<p class="side-fine">' . ms_h($c['hours']) . '</p></aside>';
}

/* ---------- Home ---------- */
function ms_page_home(): array
{
    $c = ms_config();
    $faqs = ms_data('faqs');
    $aside = '<div class="hero-card"><h2>What would you like more of?</h2><ul class="outcomes">'
        . '<li>' . ms_icon('phone') . '<span><strong>Phone calls</strong> from local buyers ready to book</span></li>'
        . '<li>' . ms_icon('quote') . '<span><strong>Quote requests</strong> for your highest-margin services</span></li>'
        . '<li>' . ms_icon('star') . '<span><strong>Five-star reviews</strong> that win the shortlist</span></li>'
        . '<li>' . ms_icon('target') . '<span><strong>Contract enquiries</strong> from agents, landlords and B2B buyers</span></li>'
        . '</ul><a class="btn btn-primary btn-block" href="/contact/#quote">Get my free marketing review</a></div>';

    $why = [
        ['target', 'Built around leads, not likes', 'Every campaign has a cost-per-lead target set from your job values. We report enquiries, not impressions.'],
        ['compass', 'One team, full marketing mix', 'SEO, ads, social, email, content, branding, web and video under one roof, so channels work together instead of competing.'],
        ['shield', 'You own everything', 'Ad accounts, analytics, listings, domain and content stay in your name. No hostage situations.'],
        ['check', 'Property and B2B know-how', 'Our group runs a property compliance business. We know landlord, agent, trade and B2B buyers first-hand.'],
    ];
    $whyHtml = '';
    foreach ($why as [$i, $t, $d]) {
        $whyHtml .= '<div class="card why-card"><span class="card-icon">' . ms_icon($i) . '</span><h3>' . ms_h($t) . '</h3><p>' . ms_h($d) . '</p></div>';
    }
    $ind = '';
    foreach (ms_data('industries') as $slug => $i) {
        $ind .= '<a class="chip" href="/industries/' . $slug . '/">' . ms_h($i['name']) . '</a>';
    }
    $areas = '';
    foreach (ms_data('areas') as $slug => $a) {
        $areas .= '<a class="chip chip-light" href="/areas/' . $slug . '/">' . ms_h($a['name']) . '</a>';
    }

    $body = ms_hero('Full-service marketing agency · UK', 'Marketing that fills your diary, not just your inbox', 'iComply Marketing Services plans and runs SEO, Google Ads, Meta ads, social, email, content, branding, websites, listings, reviews and video for UK SMEs and B2B firms. One team, one plan, measured on enquiries.', '', $aside)
        . '<section class="strip"><div class="wrap strip-inner"><span>Channels we plan and run:</span><span>Google Search</span><span>Google Maps</span><span>Meta</span><span>Instagram</span><span>LinkedIn</span><span>YouTube</span><span>TikTok</span><span>Email</span></div></section>'
        . '<section class="section"><div class="wrap"><div class="section-head"><p class="eyebrow">Services</p><h2>Every marketing service your business needs</h2><p>Use one service or the whole mix. Each is planned around the enquiries you want and reported in plain English.</p></div>'
        . ms_services_grid() . '</div></section>'
        . '<section class="section section-alt"><div class="wrap"><div class="section-head"><p class="eyebrow">Why iComply</p><h2>Marketing judged on results you can bank</h2></div><div class="grid grid-4">' . $whyHtml . '</div></div></section>'
        . '<section class="section"><div class="wrap split"><div><p class="eyebrow">How it works</p><h2>From first call to more enquiries in four steps</h2><p>No 40-page decks, no six-month discovery phases. We find the quickest wins, fix tracking, and start building the channels that will pay back.</p>'
        . '<a class="btn btn-outline" href="/how-we-work/">How we work and price ' . ms_icon('arrow', 'icon icon-sm') . '</a></div>'
        . ms_steps([
            ['Free review', 'A short call and a look at your website, Google presence, ads and reviews.'],
            ['Clear proposal', 'A written plan with deliverables, fees and expected outcomes. No obligation.'],
            ['Launch with tracking', 'Calls, forms and WhatsApp enquiries measured from day one.'],
            ['Report and grow', 'Monthly reporting on leads and cost per lead, and budget moved to what works.'],
        ]) . '</div></section>'
        . '<section class="section section-navy"><div class="wrap"><div class="section-head"><p class="eyebrow">Industries</p><h2>Specialists in property, trades and B2B services</h2><p>Deep experience where trust and local visibility decide who wins the work, and proven marketing for general SMEs.</p></div><div class="chips">' . $ind . '</div>'
        . '<div class="section-head" style="margin-top:2.5rem"><p class="eyebrow">Areas</p><h2>Based in Stockport, working UK-wide</h2></div><div class="chips">' . $areas . '</div></div></section>'
        . ms_faq_html($faqs)
        . ms_cta_band();

    return [
        'title' => 'iComply Marketing Services | Full-Service Marketing Agency for UK SMEs',
        'description' => 'SEO, Google Ads, Meta ads, social media, email, content, branding, websites, listings, reviews and video for UK SMEs and B2B firms. Get a free quote.',
        'path' => '/',
        'body' => $body,
        'jsonld' => [ms_faq_jsonld($faqs), ['@context' => 'https://schema.org', '@type' => 'WebSite', 'name' => $c['brand'], 'url' => ms_url('/')]],
    ];
}

/* ---------- Services hub ---------- */
function ms_page_services(): array
{
    $faqs = [
        ['Which marketing service should I start with?', 'If you need leads quickly, Google Ads with a strong landing page is usually first. If you want a lead source that compounds, start SEO and Google Business Profile work. If you are unsure, a strategy and audit tells you where the biggest gap is.'],
        ['Can I combine several services?', 'Yes, and they work better together: SEO and content, ads and landing pages, reviews and listings, social and video. We plan combined packages so the channels support each other.'],
        ['Do you offer packages?', 'We scope a package around your goals rather than forcing a fixed bundle. Your proposal lists every deliverable and the monthly fee.'],
        ['Can you work alongside our existing team or agency?', 'Yes. We often take on specific channels while your team or another supplier handles the rest, with shared reporting.'],
    ];
    $body = ms_hero('Marketing services', 'The full marketing mix, run by one accountable team', 'Pick the channel you need now or let us plan the whole mix. Every service below is delivered by the same team, tracked against enquiries and reported monthly.', 'marketing services')
        . '<section class="section"><div class="wrap">' . ms_services_grid(true) . '</div></section>'
        . '<section class="section section-alt"><div class="wrap split"><div><p class="eyebrow">Not sure where to start?</p><h2>Start with a free marketing review</h2>'
        . '<p>We look at your website, Google presence, ads accounts, listings and reviews, then tell you honestly which two or three things would make the biggest difference. If a full audit is useful, our <a href="/services/marketing-strategy/">strategy and audit service</a> goes deeper.</p></div>'
        . '<div class="card"><h3>Popular combinations</h3><ul class="tick-list">'
        . '<li>' . ms_icon('check', 'icon icon-sm') . '<span><a href="/services/google-ads/">Google Ads</a> + <a href="/services/websites-landing-pages/">landing pages</a> for fast leads</span></li>'
        . '<li>' . ms_icon('check', 'icon icon-sm') . '<span><a href="/services/seo/">SEO</a> + <a href="/services/content-marketing/">content</a> + <a href="/services/directories-listings/">listings</a> for long-term visibility</span></li>'
        . '<li>' . ms_icon('check', 'icon icon-sm') . '<span><a href="/services/reputation-reviews/">Reviews</a> + <a href="/services/email-marketing/">email</a> for repeat work and referrals</span></li>'
        . '<li>' . ms_icon('check', 'icon icon-sm') . '<span><a href="/services/meta-ads/">Meta ads</a> + <a href="/services/video-production/">video</a> + <a href="/services/social-media/">social</a> for local awareness</span></li>'
        . '</ul></div></div></section>'
        . ms_faq_html($faqs)
        . ms_cta_band('Tell us what you need', '', 'marketing services');
    return [
        'title' => 'Marketing Services | SEO, PPC, Social, Email, Web & More | iComply',
        'description' => 'All iComply marketing services: SEO, Google Ads, Meta ads, content, email, social media, branding, websites, listings, reviews, video and strategy.',
        'path' => '/services/',
        'body' => $body,
        'trail' => [['Services', '']],
        'jsonld' => [ms_faq_jsonld($faqs)],
    ];
}

/* ---------- Service detail ---------- */
function ms_page_service(string $slug, array $s): array
{
    $services = ms_data('services');
    $pains = '<div class="hero-card"><h2>Sound familiar?</h2><ul class="pain-list">';
    foreach ($s['pains'] as $p) {
        $pains .= '<li>' . ms_icon('check', 'icon icon-sm') . '<span>' . ms_h($p) . '</span></li>';
    }
    $pains .= '</ul><a class="btn btn-primary btn-block" href="/contact/?service=' . $slug . '#quote">Fix it — get a quote</a></div>';

    $inc = '';
    foreach ($s['included'] as [$t, $d]) {
        $inc .= '<div class="card inc-card">' . ms_icon('check', 'icon tick') . '<div><h3>' . ms_h($t) . '</h3><p>' . ms_h($d) . '</p></div></div>';
    }
    $measure = '';
    foreach ($s['measure'] as $m) {
        $measure .= '<li>' . ms_icon('target', 'icon icon-sm') . '<span>' . ms_h($m) . '</span></li>';
    }
    $related = '';
    foreach ($s['related'] as $r) {
        if (isset($services[$r])) {
            $related .= ms_service_card($r, $services[$r]);
        }
    }
    $ctxName = ms_ctx($s);

    $body = ms_hero($s['long'], $s['h1'], $s['lede'], $ctxName, $pains)
        . '<section class="section"><div class="wrap content-grid"><div class="prose">'
        . '<h2>Why invest in ' . ms_h($ctxName) . '?</h2>' . ms_paras($s['intro'])
        . '<div class="mid-cta"><p>Want to know what ' . ms_h($ctxName) . ' could do for you? We will review your current position for free.</p><a class="btn btn-primary btn-sm" href="/contact/?service=' . $slug . '#quote">Get my free review</a></div>'
        . '<h2>Who it is for</h2><p>' . ms_h($s['fit']) . '</p>'
        . '</div>' . ms_quote_aside('Get a quote for ' . $ctxName, 'Tell us about your business and goals. We will reply within one working day with next steps.', $ctxName) . '</div></section>'
        . '<section class="section section-alt"><div class="wrap"><div class="section-head"><p class="eyebrow">What you get</p><h2>What our ' . ms_h($ctxName) . ' service includes</h2><p>Scoped to your goals in a written proposal. Typical deliverables:</p></div><div class="grid grid-2">' . $inc . '</div></div></section>'
        . '<section class="section"><div class="wrap split"><div><p class="eyebrow">How we deliver</p><h2>Our ' . ms_h($ctxName) . ' process</h2><p>Clear stages, agreed priorities and regular check-ins so you always know what is happening and why.</p></div>' . ms_steps($s['process']) . '</div></section>'
        . '<section class="section section-navy"><div class="wrap split"><div><p class="eyebrow">Accountability</p><h2>What we measure and report</h2><p>Tracking is set up before work starts, so results are measured against a baseline. Every month you get a short, plain-English report and a call to agree the next priorities.</p></div><ul class="measure-list">' . $measure . '</ul></div></section>'
        . ms_faq_html($s['faqs'], $s['name'] . ' FAQs')
        . '<section class="section section-alt"><div class="wrap"><div class="section-head"><p class="eyebrow">Works well with</p><h2>Related services</h2></div><div class="grid grid-3">' . $related . '</div></div></section>'
        . ms_cta_band('Get more from ' . $ctxName, '', $ctxName);

    return [
        'title' => $s['meta_title'],
        'description' => $s['meta_desc'],
        'path' => '/services/' . $slug . '/',
        'body' => $body,
        'trail' => [['Services', '/services/'], [$s['name'], '']],
        'jsonld' => [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Service',
                'name' => $s['long'],
                'serviceType' => $s['long'],
                'description' => $s['meta_desc'],
                'provider' => ['@id' => ms_url('/#org')],
                'areaServed' => ['@type' => 'Country', 'name' => 'United Kingdom'],
                'url' => ms_url('/services/' . $slug . '/'),
            ],
            ms_faq_jsonld($s['faqs']),
        ],
    ];
}

/* ---------- Industries ---------- */
function ms_page_industries(): array
{
    $cards = '';
    foreach (ms_data('industries') as $slug => $i) {
        $cards .= '<a class="card service-card" href="/industries/' . $slug . '/"><h3>' . ms_h($i['name']) . '</h3><p>' . ms_h($i['lede']) . '</p><span class="card-link">See how we help ' . ms_icon('arrow', 'icon icon-sm') . '</span></a>';
    }
    $body = ms_hero('Industries', 'Marketing that speaks your buyers\' language', 'We know how landlords, agents, homeowners, procurement teams and business owners choose suppliers, because our group sells to them every day.')
        . '<section class="section"><div class="wrap"><div class="grid grid-3">' . $cards . '</div></div></section>'
        . ms_cta_band('Do not see your sector?', 'Most of what we do applies to any UK business that sells a service. Tell us about yours and we will show you how we would approach it.');
    return [
        'title' => 'Industries We Market | Property, Trades, Compliance & B2B | iComply',
        'description' => 'Marketing for estate and letting agents, trades, compliance and facilities firms, professional services and B2B SMEs across the UK.',
        'path' => '/industries/',
        'body' => $body,
        'trail' => [['Industries', '']],
    ];
}

function ms_page_industry(string $slug, array $i): array
{
    $points = '';
    foreach ($i['points'] as $p) {
        $points .= '<li>' . ms_icon('check', 'icon icon-sm') . '<span>' . ms_h($p) . '</span></li>';
    }
    $body = ms_hero('Industry · ' . $i['name'], $i['h1'], $i['lede'], 'marketing for ' . strtolower($i['name']))
        . '<section class="section"><div class="wrap content-grid"><div class="prose">' . ms_paras($i['body'])
        . '<h2>What we typically deliver</h2><ul class="tick-list">' . $points . '</ul></div>'
        . ms_quote_aside('Talk to us about your firm', 'We will review your online presence and suggest the quickest wins for your sector.', 'marketing for ' . strtolower($i['name'])) . '</div></section>'
        . '<section class="section section-alt"><div class="wrap"><div class="section-head"><p class="eyebrow">Recommended</p><h2>Services that work best for ' . ms_h(strtolower($i['name'])) . '</h2></div>' . ms_services_grid(false, $i['services']) . '</div></section>'
        . ms_faq_html($i['faqs'])
        . ms_cta_band();
    return [
        'title' => $i['meta_title'],
        'description' => $i['meta_desc'],
        'path' => '/industries/' . $slug . '/',
        'body' => $body,
        'trail' => [['Industries', '/industries/'], [$i['name'], '']],
        'jsonld' => [ms_faq_jsonld($i['faqs'])],
    ];
}

/* ---------- Areas ---------- */
function ms_page_areas(): array
{
    $cards = '';
    foreach (ms_data('areas') as $slug => $a) {
        $cards .= '<a class="card service-card" href="/areas/' . $slug . '/"><span class="card-icon">' . ms_icon('pin') . '</span><h3>' . ms_h($a['name']) . '</h3><p>' . ms_h($a['intro']) . '</p><span class="card-link">Marketing in ' . ms_h($a['name']) . ' ' . ms_icon('arrow', 'icon icon-sm') . '</span></a>';
    }
    $body = ms_hero('Areas we cover', 'Based in Stockport. Marketing businesses across the UK.', 'We work face to face across Greater Manchester and Cheshire, and remotely with clients throughout the UK. Campaigns are always targeted to the postcodes you actually serve.')
        . '<section class="section"><div class="wrap"><div class="grid grid-3">' . $cards . '</div></div></section>'
        . ms_cta_band('Wherever you trade, let us talk', 'Tell us the towns and postcodes you want more work from and we will show you how we would target them.');
    return [
        'title' => 'Areas We Cover | Marketing Agency Manchester, Stockport & UK-wide | iComply',
        'description' => 'iComply Marketing Services works across Manchester, Stockport, Cheshire, Liverpool, Leeds, Birmingham, London and UK-wide.',
        'path' => '/areas/',
        'body' => $body,
        'trail' => [['Areas', '']],
    ];
}

function ms_page_area(string $slug, array $a): array
{
    $name = $a['name'];
    $isUk = $slug === 'uk-wide';
    $where = $isUk ? 'across the UK' : 'in ' . $name;
    $nearby = implode(', ', $a['nearby']);
    $svcList = '';
    foreach (ms_data('services') as $sslug => $s) {
        $svcList .= '<li><a href="/services/' . $sslug . '/">' . ms_icon($s['icon'], 'icon icon-sm') . '<span>' . ms_h($s['name']) . ($isUk ? '' : ' ' . ms_h($name)) . '</span></a></li>';
    }
    $faqs = [
        ['Do you work with businesses ' . $where . '?', 'Yes. ' . $a['angle']],
        ['Can you target specific towns and postcodes?', 'Yes. Google Ads, Meta ads and local SEO can all be targeted to the exact areas you serve' . ($isUk ? '' : ', including ' . $nearby) . '. We exclude areas you do not cover so budget is not wasted.'],
        ['Do I need a local office to rank ' . $where . '?', 'Map results favour businesses near the searcher, but service-area businesses can still win strong visibility through location pages, citations, reviews and paid search targeting.'],
        ['How do we work together if you are not local?', 'Most of our work is delivered remotely with video calls, shared dashboards and monthly reporting. Where filming or workshops are needed we can come to you.'],
    ];
    $title = $isUk ? 'UK-wide Marketing Agency for SMEs & B2B | iComply' : 'Marketing Agency ' . $name . ' | SEO, Google Ads & Social | iComply';
    $body = ms_hero('Areas · ' . $a['region'], $isUk ? 'Marketing services across the UK' : 'Marketing services in ' . $name, 'SEO, Google Ads, Meta ads, social, email, branding, websites and reviews for businesses ' . $where . '. Targeted to the postcodes you serve and measured on enquiries.', 'marketing ' . $where)
        . '<section class="section"><div class="wrap content-grid"><div class="prose">'
        . '<h2>Marketing ' . ms_h($where) . '</h2><p>' . ms_h($a['intro']) . '</p><p>' . ms_h($a['angle']) . '</p>'
        . '<h2>Areas we cover ' . ($isUk ? 'nationally' : 'around ' . ms_h($name)) . '</h2><p>' . ms_h($nearby) . ' and surrounding areas. Campaigns are targeted by postcode, radius or region so spend stays where your customers are.</p>'
        . '<h2>Marketing services ' . ms_h($where) . '</h2><ul class="svc-list">' . $svcList . '</ul></div>'
        . ms_quote_aside('Get a quote ' . $where, 'Tell us which towns you want more work from and what you sell.', 'marketing ' . $where) . '</div></section>'
        . ms_faq_html($faqs)
        . ms_cta_band('Win more work ' . $where, '', 'marketing ' . $where);
    return [
        'title' => $title,
        'description' => 'Marketing services ' . $where . ': SEO, Google Ads, Meta ads, social media, email, websites, listings and reviews for SMEs and B2B firms. Free quote.',
        'path' => '/areas/' . $slug . '/',
        'body' => $body,
        'trail' => [['Areas', '/areas/'], [$name, '']],
        'jsonld' => [ms_faq_jsonld($faqs)],
    ];
}

/* ---------- Company pages ---------- */
function ms_page_about(): array
{
    $values = [
        ['Straight talking', 'We tell you what will and will not work for your budget, even when that means recommending less.'],
        ['Measured on enquiries', 'Leads, cost per lead and revenue matter. Vanity metrics do not make the report.'],
        ['Your assets, your name', 'Accounts, data and content belong to your business from day one.'],
        ['Joined-up thinking', 'One team planning every channel means no gaps, no overlap and one point of contact.'],
    ];
    $v = '';
    foreach ($values as [$t, $d]) {
        $v .= '<div class="card why-card"><h3>' . ms_h($t) . '</h3><p>' . ms_h($d) . '</p></div>';
    }
    $body = ms_hero('About us', 'A marketing team that knows what it takes to win work', 'iComply Marketing Services is the marketing arm of the iComply group. We built it after years of marketing our own property compliance business, and now run the same playbook for other UK SMEs.')
        . '<section class="section"><div class="wrap content-grid"><div class="prose">'
        . '<h2>Why we started</h2><p>The iComply group began in property compliance, competing for landlord, letting agent and commercial work in a crowded market. To grow, we had to get good at the marketing that actually brings in jobs: map visibility, Google Ads with tight cost control, review generation, service and location pages, and email that wins repeat work.</p>'
        . '<p>Other business owners kept asking who did our marketing. iComply Marketing Services is the answer: the same practical, results-led approach, offered to SMEs and B2B companies across the UK.</p>'
        . '<h2>What makes us different</h2><p>Many agencies specialise in one channel and recommend it for every problem. We plan across the full marketing mix, then put budget where it will return the most for your business. Because we run a service business ourselves, we understand capacity, margins, seasonality and the reality of answering the phone.</p>'
        . '<p>We keep things simple: a written proposal before any work, tracking before any spend, and a short monthly report that tells you what happened, what it cost and what comes next.</p>'
        . '</div>' . ms_quote_aside('Work with us', 'Start with a free review of your current marketing.', '') . '</div></section>'
        . '<section class="section section-alt"><div class="wrap"><div class="section-head"><p class="eyebrow">How we operate</p><h2>What you can expect from us</h2></div><div class="grid grid-4">' . $v . '</div></div></section>'
        . ms_cta_band();
    return [
        'title' => 'About iComply Marketing Services | UK Marketing Agency',
        'description' => 'iComply Marketing Services is the marketing arm of the iComply group, bringing a practical, results-led approach to SMEs and B2B firms across the UK.',
        'path' => '/about/',
        'body' => $body,
        'trail' => [['About', '']],
    ];
}

function ms_page_how(): array
{
    $faqs = [
        ['Why do you not publish fixed prices?', 'Marketing costs depend on competition, area, goals and how much of the work you want us to do. A fixed price list would either overcharge small businesses or underdeliver for ambitious ones. Your quote is specific and written.'],
        ['What is included in the monthly fee?', 'Exactly what your proposal lists: deliverables, reporting and meetings. Ad spend, third-party software and printing are separate and always agreed in advance.'],
        ['Is there a set-up fee?', 'Some services, such as website builds, branding or rebuilding an ad account, involve one-off set-up work. Where that applies it is shown separately in the proposal.'],
        ['How do I pay ad spend?', 'Directly to Google, Meta or LinkedIn from your own account, using your own payment card. We never mark up your ad spend.'],
        ['What happens if we stop working together?', 'You keep everything: accounts, data, content, website and assets. We hand over access cleanly.'],
    ];
    $body = ms_hero('How we work', 'Simple process. Clear pricing. No surprises.', 'Every engagement starts with a free review and a written proposal. You know exactly what you are getting, what it costs and how it will be measured before you commit.')
        . '<section class="section"><div class="wrap split"><div><p class="eyebrow">Process</p><h2>How an engagement runs</h2><p>Whether you need one channel or full outsourced marketing, the process is the same.</p></div>'
        . ms_steps([
            ['Free discovery call', 'Twenty to thirty minutes on your business, goals, capacity and what you have tried before.'],
            ['Review and proposal', 'We look at your current marketing and send a written proposal with deliverables, fees and targets.'],
            ['Onboarding', 'Access to accounts, tracking set-up and a kick-off call to agree first priorities.'],
            ['Delivery', 'Work goes live in agreed stages. You approve copy, creative and major changes.'],
            ['Monthly report and call', 'Leads, cost per lead, work completed and next month\'s priorities.'],
        ]) . '</div></section>'
        . '<section class="section section-alt"><div class="wrap"><div class="section-head"><p class="eyebrow">Pricing</p><h2>How our pricing works</h2></div><div class="grid grid-3">'
        . '<div class="card"><h3>Monthly retainers</h3><p>For ongoing work such as SEO, ads management, social media, email and content. A fixed monthly fee for a defined scope.</p></div>'
        . '<div class="card"><h3>Projects</h3><p>For websites, landing pages, branding, video shoots and audits. A fixed project price with clear milestones.</p></div>'
        . '<div class="card"><h3>Outsourced marketing team</h3><p>Strategy, delivery and supplier coordination across channels, scoped around your growth targets.</p></div>'
        . '</div><p class="note">All prices are quoted individually (POA) after the free review. Advertising spend is always paid directly by you to the platform.</p></div></section>'
        . ms_faq_html($faqs)
        . ms_cta_band('Get your free review and quote');
    return [
        'title' => 'How We Work & Pricing | iComply Marketing Services',
        'description' => 'How iComply Marketing Services works: free review, written proposal, tracked delivery and monthly reporting. Retainers, projects and outsourced marketing.',
        'path' => '/how-we-work/',
        'body' => $body,
        'trail' => [['How we work', '']],
        'jsonld' => [ms_faq_jsonld($faqs)],
    ];
}

function ms_page_faq(): array
{
    $all = ms_data('faqs');
    $sections = ms_faq_html($all, 'General questions', 'general');
    $jsonFaqs = $all;
    foreach (ms_data('services') as $slug => $s) {
        $sections .= ms_faq_html(array_slice($s['faqs'], 0, 3), $s['name'] . ' questions', $slug);
        $jsonFaqs = array_merge($jsonFaqs, array_slice($s['faqs'], 0, 3));
    }
    $body = ms_hero('FAQs', 'Marketing questions, answered honestly', 'Straight answers about how we work, what things cost, how long results take and what you own. Can\'t find your question? Call or WhatsApp us.')
        . $sections
        . ms_cta_band('Still have a question?', 'Ask us directly. We are happy to talk through your situation with no obligation.');
    return [
        'title' => 'Marketing FAQs | iComply Marketing Services',
        'description' => 'Answers to common questions about SEO, Google Ads, Meta ads, social media, email, websites, reviews, pricing and contracts.',
        'path' => '/faq/',
        'body' => $body,
        'trail' => [['FAQs', '']],
        'jsonld' => [ms_faq_jsonld($jsonFaqs)],
    ];
}

function ms_page_contact(): array
{
    $c = ms_config();
    $faqs = [
        ['What happens after I send the form?', 'We review your website and online presence, then contact you within one working day to arrange a short call. After the call you get a written proposal.'],
        ['Is the review really free?', 'Yes. There is no charge and no obligation for the initial review and proposal.'],
        ['What information should I have ready?', 'Your website address, the services and areas you want to grow, a rough idea of your average job value, and any marketing you are already running.'],
    ];
    $body = '<section class="hero hero-compact"><div class="wrap"><p class="eyebrow">Contact</p><h1>Get a free marketing review and quote</h1>'
        . '<p class="lede">Tell us about your business and what you want more of. We reply within one working day.</p></div></section>'
        . '<section class="section"><div class="wrap contact-grid">'
        . '<div class="card form-card" id="quote"><h2>Request your quote</h2>' . ms_quote_form() . '</div>'
        . '<aside class="contact-side">'
        . '<a class="contact-tile" href="' . ms_tel() . '">' . ms_icon('phone') . '<span><strong>Call us</strong>' . ms_h($c['phone_display']) . '<small>' . ms_h($c['hours']) . '</small></span></a>'
        . '<a class="contact-tile tile-wa" href="' . ms_h(ms_whatsapp()) . '" target="_blank" rel="noopener">' . ms_icon('chat') . '<span><strong>WhatsApp</strong>Message us any time<small>Usually answered same day</small></span></a>'
        . '<a class="contact-tile" href="mailto:' . ms_h($c['email']) . '">' . ms_icon('mail') . '<span><strong>Email</strong>' . ms_h($c['email']) . '</span></a>'
        . '<div class="contact-tile static">' . ms_icon('pin') . '<span><strong>Where we are</strong>' . ms_h($c['base']) . '<small>Working with businesses UK-wide</small></span></div>'
        . '</aside></div></section>'
        . ms_faq_html($faqs);
    return [
        'title' => 'Contact iComply Marketing Services | Free Quote',
        'description' => 'Get a free marketing review and quote from iComply Marketing Services. Call, WhatsApp or send the form — we reply within one working day.',
        'path' => '/contact/',
        'body' => $body,
        'trail' => [['Contact', '']],
        'jsonld' => [ms_faq_jsonld($faqs)],
    ];
}

function ms_page_thanks(): array
{
    $body = '<section class="hero hero-compact"><div class="wrap"><p class="eyebrow">Thank you</p><h1>We have your request</h1>'
        . '<p class="lede">Thanks for getting in touch. We will review your details and come back to you within one working day. Need us sooner?</p>'
        . ms_cta_buttons() . '</div></section>'
        . '<section class="section"><div class="wrap"><div class="section-head"><h2>While you wait, explore our services</h2></div>' . ms_services_grid() . '</div></section>';
    return ['title' => 'Thank you | iComply Marketing Services', 'description' => 'Thanks for your enquiry. We will be in touch within one working day.', 'path' => '/thank-you/', 'body' => $body, 'trail' => [['Thank you', '']]];
}

function ms_page_privacy(): array
{
    $c = ms_config();
    $body = '<section class="hero hero-compact"><div class="wrap"><p class="eyebrow">Legal</p><h1>Privacy notice</h1><p class="lede">How we handle the information you send us.</p></div></section>'
        . '<section class="section"><div class="wrap narrow prose">'
        . '<h2>Who we are</h2><p>' . ms_h($c['brand']) . ' is part of the iComply group, based in ' . ms_h($c['base']) . '. Contact us at <a href="mailto:' . ms_h($c['email']) . '">' . ms_h($c['email']) . '</a> about anything in this notice.</p>'
        . '<h2>What we collect</h2><p>When you submit our quote form, call, email or message us on WhatsApp, we collect the details you provide: typically your name, company, contact details, website and information about your marketing needs.</p>'
        . '<h2>How we use it</h2><p>We use your details to respond to your enquiry, prepare a proposal and, if you become a client, deliver our services. Our lawful basis is legitimate interests in responding to business enquiries, or the performance of a contract.</p>'
        . '<h2>Who we share it with</h2><p>We use trusted providers to host this website and process form submissions. We do not sell your data. We do not send marketing emails without an appropriate lawful basis, and you can opt out at any time.</p>'
        . '<h2>How long we keep it</h2><p>Enquiries that do not become clients are kept for up to two years and then deleted. Client records are kept for as long as needed for contractual and legal purposes.</p>'
        . '<h2>Your rights</h2><p>You can ask for a copy of your data, ask us to correct or delete it, or object to how we use it. You can also complain to the Information Commissioner\'s Office (ico.org.uk).</p>'
        . '<p class="note">This notice is a working draft for the preview site and should be reviewed before go-live.</p>'
        . '</div></section>';
    return ['title' => 'Privacy Notice | iComply Marketing Services', 'description' => 'How iComply Marketing Services collects, uses and protects information submitted through this website.', 'path' => '/privacy/', 'body' => $body, 'trail' => [['Privacy', '']]];
}

function ms_page_404(): array
{
    $body = '<section class="hero hero-compact"><div class="wrap"><p class="eyebrow">404</p><h1>That page could not be found</h1>'
        . '<p class="lede">The link may be old or mistyped. Try one of our services below, or get in touch.</p>' . ms_cta_buttons() . '</div></section>'
        . '<section class="section"><div class="wrap">' . ms_services_grid() . '</div></section>';
    return ['title' => 'Page not found | iComply Marketing Services', 'description' => 'The page you were looking for could not be found. Browse iComply marketing services or contact us for a free quote.', 'path' => '/404.html', 'body' => $body];
}
