<?php
declare(strict_types=1);

/**
 * Audience modules for keyword pages such as /marketing/seo-for-dentists/.
 * Regulatory references are to real, public UK rules; we describe how marketing
 * should respect them, not legal advice. No client claims, no statistics.
 */
return [
'accountants' => ['label' => 'accountants', 'title' => 'Accountants', 'industry' => 'professional-services',
    'context' => [
        'Accountancy buyers rarely pick a firm from one advert. Sole traders, landlords and company directors compare websites, reviews and fees pages, then shortlist the firms that explain clearly what they do for a business like theirs. Specialist pages (contractors, landlords, e-commerce, construction industry scheme) usually convert far better than a generic "accountancy services" page.',
        'Demand is seasonal. Searches and enquiries tend to build ahead of the 31 January Self Assessment deadline, and Making Tax Digital for Income Tax, which began applying from April 2026 to sole traders and landlords above the first income threshold, has created a new group of people looking for help with digital record keeping and quarterly updates.',
    ],
    'rules' => ['Professional bodies such as ICAEW, ACCA and AAT expect publicity to be accurate and not to make unfair comparisons with other firms.', 'Fee messages need to be clear about what is and is not included, in line with the CAP Code on misleading advertising.', 'Testimonials and reviews must be genuine; fake or incentivised-without-disclosure reviews are banned under the Digital Markets, Competition and Consumers Act 2024.'],
    'faqs' => [
        ['Can marketing help an accountancy practice outside tax season?', 'Yes. Year-end, bookkeeping, payroll, advisory and Making Tax Digital support give you reasons to market all year. We plan campaigns around the services with the best lifetime value rather than the January rush alone.'],
        ['Should accountants publish fees online?', 'It is your choice. Many firms publish "from" prices or fixed-fee packages so buyers can self-qualify; others prefer a call first. We test which approach brings better-fit enquiries for your practice, and we never invent prices on your behalf.'],
        ['Can you target landlords and sole traders affected by Making Tax Digital?', 'Yes. We can build landing pages and campaigns that explain how you help with digital records and quarterly updates, targeted to the areas and audiences you serve.'],
    ]],
'builders' => ['label' => 'builders', 'title' => 'Builders', 'industry' => 'trades-home-services',
    'context' => [
        'Homeowners choosing a builder are making a large, nervous purchase. They look for photos of finished projects, reviews that mention communication and tidiness, and evidence that the firm is established and insured before they request a quote. Your marketing has to answer those worries before the first call.',
        'Project types behave differently online: extensions, loft conversions, kitchens, new builds and commercial fit-outs each attract different searches, budgets and lead times. Separate pages and campaigns for the work you most want stop small-job enquiries crowding out profitable projects.',
    ],
    'rules' => ['Only display trade body or scheme logos (for example FMB or TrustMark) that your business genuinely holds, and keep them current.', 'Project photos and case studies should be your own work, with the client\'s permission where they are identifiable.', 'Claims about guarantees or insurance-backed warranties must match the actual cover you provide.'],
    'faqs' => [
        ['How do builders get better quality leads?', 'By being specific. Pages and ads focused on the projects you want, with typical scope and process explained, filter out mismatched enquiries. Pre-qualifying questions on the form help too.'],
        ['Is Google Ads or SEO better for a building firm?', 'Ads bring enquiries while SEO builds. Many builders run search ads for high-value project terms and invest in SEO and Google Business Profile for steady local visibility.'],
        ['Do photos of past projects really matter?', 'Yes. For building work, real photos of completed projects are often what convinces a homeowner to ask for a quote. We help you capture and organise them.'],
    ]],
'clinics' => ['label' => 'clinics', 'title' => 'Clinics', 'industry' => 'b2b-smes',
    'context' => [
        'Private clinics, from physiotherapy and chiropractic to aesthetics, hearing and eye care, compete on trust. Patients read reviews closely, look at practitioner profiles and want to know what a first appointment involves before they book. Clear service pages and easy online booking or call routes do a lot of the selling.',
        'Healthcare advertising carries extra rules. Claims about treatment outcomes need evidence, and some treatments cannot be promoted directly to the public at all, so campaigns are written to promote consultations and expertise rather than prescription products.',
    ],
    'rules' => ['Prescription-only medicines (including botulinum toxin products) must not be advertised to the public; the ASA and MHRA enforce this.', 'Health and efficacy claims must be backed by evidence under the CAP Code.', 'Where services are regulated activities, keep your CQC (or the relevant nation\'s regulator) registration details accurate and visible.'],
    'faqs' => [
        ['Can you advertise aesthetic treatments?', 'We can promote consultations, practitioner expertise and your clinic, while keeping prescription-only medicines out of public-facing ads, in line with ASA and MHRA rules.'],
        ['How do clinics get more bookings from their website?', 'Clear treatment pages, honest practitioner profiles, visible reviews and a short booking or call route. Tracking shows which pages produce bookings.'],
        ['Do you write medical claims?', 'We avoid unsupported claims and work from information you can evidence. Clinical content should be checked by your practitioners before it goes live.'],
    ]],
'dentists' => ['label' => 'dentists', 'title' => 'Dentists', 'industry' => 'b2b-smes',
    'context' => [
        'Dental patients usually choose a practice close to home or work, so map results, reviews and clear information about NHS and private availability carry a lot of weight. High-value treatments such as implants, orthodontics and cosmetic work need their own pages, because patients research them carefully before booking a consultation.',
        'The General Dental Council publishes guidance on ethical advertising, and practice websites are expected to include accurate information about the dental professionals and how patients can raise concerns. Good marketing builds that in rather than treating it as an afterthought.',
    ],
    'rules' => ['Follow GDC guidance on advertising, including accurate details of the registered professionals named on your website.', 'Prices and finance offers must be clear about what is included under the CAP Code.', 'Before-and-after images must be genuine and representative of typical results.'],
    'faqs' => [
        ['How can a dental practice attract more private patients?', 'Dedicated treatment pages, strong local reviews and campaigns aimed at consultation bookings for the treatments you want to grow, tracked to booked appointments.'],
        ['Should we mention NHS availability?', 'Clear, current information about whether you are accepting NHS or private patients reduces wasted calls and builds trust. We keep it easy to update.'],
        ['Can you help with GDC advertising guidance?', 'We build pages with the information the guidance expects in mind. Responsibility for professional compliance stays with your practice, so we flag anything you should check.'],
    ]],
'estate-agents' => ['label' => 'estate agents', 'title' => 'Estate agents', 'industry' => 'property-lettings',
    'context' => [
        'For estate and letting agents, the valuable lead is the instruction: a vendor valuation request or a landlord looking for a new agent. Buyers and tenants come through the portals, but instructions come from local reputation, Google visibility, reviews and staying in front of homeowners until they are ready to move.',
        'Property marketing in the UK carries specific obligations. Agents must belong to a government-approved redress scheme, and National Trading Standards guidance sets out the material information that property listings should include. Marketing that respects those rules also tends to look more professional to vendors and landlords.',
    ],
    'rules' => ['Membership of an approved redress scheme (The Property Ombudsman or the Property Redress Scheme) is a legal requirement for estate and letting agents.', 'Listings should include material information in line with National Trading Standards Estate and Letting Agency Team guidance.', 'Reviews must be genuine; fake reviews are banned under the Digital Markets, Competition and Consumers Act 2024.'],
    'faqs' => [
        ['How do estate agents win more valuations?', 'Branch-level Google Business Profiles, strong reviews, valuation landing pages and targeted ads for the postcodes you want, plus email nurturing for homeowners not yet ready to sell.'],
        ['Do you advertise properties on Rightmove or Zoopla?', 'Portal listings stay with your team. We focus on generating vendor and landlord instructions that feed your pipeline.'],
        ['Can you target landlords for lettings and management?', 'Yes. Landlord acquisition campaigns and content on compliance changes are one of the areas our group knows best.'],
    ]],
'ifas' => ['label' => 'IFAs', 'title' => 'IFAs and financial advisers', 'industry' => 'professional-services',
    'context' => [
        'Independent financial advisers sell trust and expertise over a long relationship. Prospects usually research advisers carefully, look for qualifications and specialisms such as pensions, retirement or protection, and want to understand how fees work before they book an initial meeting.',
        'Financial promotions are regulated by the FCA. Marketing must be fair, clear and not misleading, the Consumer Duty applies to communications with retail customers, and the FCA has published specific guidance on financial promotions on social media. Every campaign we plan for advisers is built with that sign-off process in mind.',
    ],
    'rules' => ['Financial promotions must be fair, clear and not misleading (FCA COBS 4), and approved under your firm\'s sign-off process.', 'The FCA Consumer Duty applies to how you communicate with retail customers.', 'FCA guidance FG24/1 covers financial promotions on social media, including finfluencer activity.'],
    'faqs' => [
        ['Can you run social media ads for financial advisers?', 'Yes, with copy written for your compliance sign-off and in line with FCA guidance on social media promotions. Nothing goes live without your approval.'],
        ['What content works for IFAs?', 'Plain-English explainers on the topics your ideal clients worry about, such as retirement planning or pension consolidation, with clear routes to book an initial conversation.'],
        ['Do you guarantee a number of new clients?', 'No. We commit to a clear plan, tracked enquiries and honest reporting. Conversion into clients depends on your advice process.'],
    ]],
'landlords' => ['label' => 'landlords', 'title' => 'Landlords', 'industry' => 'property-lettings',
    'context' => [
        'Landlords who market their own properties, and portfolio landlords building a brand, need to reach good tenants quickly and keep voids low. That means clear listings, fast responses to enquiries and a professional presence that reassures tenants and letting partners alike.',
        'The rules on renting are changing. The Renters\' Rights Act 2025 has reformed how private tenancies in England work, including how rental properties may be advertised, and Making Tax Digital for Income Tax now applies to landlords above the first income threshold. Clear, accurate marketing matters more than ever.',
    ],
    'rules' => ['Advertise rents and terms accurately and in line with current rules for your nation; England\'s rules changed under the Renters\' Rights Act 2025.', 'Do not use discriminatory wording in adverts (for example blanket bans on tenants receiving benefits).', 'Keep safety certificates and EPC information accurate where you reference them.'],
    'faqs' => [
        ['Can marketing reduce void periods?', 'Better listings, faster enquiry handling (for example with an AI assistant or chatbot) and targeted local ads can help fill properties with suitable tenants. We track enquiries per property.'],
        ['Do you market to landlords as well as for them?', 'Both. We help landlords find tenants and help agents and service firms reach landlords, which is a core strength of our group.'],
        ['Is my advertising affected by the Renters\' Rights Act?', 'Rules on how rental properties are advertised in England have changed. We write adverts with the current rules in mind, but you should confirm your obligations with your agent or adviser.'],
    ]],
'law-firms' => ['label' => 'law firms', 'title' => 'Law firms', 'industry' => 'professional-services',
    'context' => [
        'Law firms compete on expertise, reputation and clarity. Prospective clients want to know you handle their type of matter, roughly what it will cost and who they will deal with. Practice-area pages, lawyer profiles and clear contact routes convert better than a single "services" page.',
        'Legal marketing in England and Wales sits under the Solicitors Regulation Authority. The SRA Transparency Rules require firms to publish price and service information for certain services, such as residential conveyancing and probate, and to display the SRA clear digital badge. The Code of Conduct also restricts unsolicited approaches to members of the public.',
    ],
    'rules' => ['Publish price and service information where the SRA Transparency Rules require it, and display the SRA digital badge.', 'Do not make unsolicited approaches to members of the public to advertise legal services (SRA Code of Conduct).', 'In Scotland and Northern Ireland, follow the Law Society of Scotland or Law Society of Northern Ireland rules instead.'],
    'faqs' => [
        ['Can law firms use Google Ads?', 'Yes. Search ads for specific matter types, such as conveyancing or family law, work well when they lead to practice-area pages with clear pricing information where required.'],
        ['Do you help with SRA transparency pages?', 'We can structure and design price and service pages so they are clear and easy to find. The content and figures come from your firm, and compliance sign-off stays with you.'],
        ['Is cold outreach allowed?', 'Unsolicited approaches to members of the public are restricted under the SRA Code. We focus on inbound channels, referrals and B2B relationships where outreach is appropriate.'],
    ]],
'solicitors' => ['label' => 'solicitors', 'title' => 'Solicitors', 'industry' => 'professional-services',
    'context' => [
        'People looking for a solicitor are often dealing with something important or stressful: a house move, a divorce, a will or a dispute. They respond to clear explanations, visible prices where they exist, reviews and the reassurance of speaking to a real person quickly.',
        'Solicitors in England and Wales must follow the SRA Standards and Regulations. The Transparency Rules require published price and service information for certain services, and the SRA digital badge confirms that a firm is regulated. Good marketing makes that information easy to find instead of burying it.',
    ],
    'rules' => ['Follow the SRA Transparency Rules for the services they cover and display the SRA digital badge.', 'Avoid unsolicited approaches to members of the public (SRA Code of Conduct).', 'Reviews must be genuine; review platforms and the Digital Markets, Competition and Consumers Act 2024 both prohibit fake reviews.'],
    'faqs' => [
        ['Which marketing channels work best for solicitors?', 'Search (paid and organic) for specific matter types, a strong Google Business Profile for each office, reviews and referral nurturing usually do the heavy lifting.'],
        ['Can you write legal content?', 'We write plain-English explainers based on information from your fee earners, who review them for accuracy before publishing.'],
        ['Do you need to see our price information?', 'Yes, where it is published, so pages and ads are consistent with it. We never invent prices or fees.'],
    ]],
'recruiters' => ['label' => 'recruiters', 'title' => 'Recruitment agencies', 'industry' => 'b2b-smes',
    'context' => [
        'Recruitment agencies market to two audiences at once: clients with vacancies and candidates with skills. Each needs different messaging, channels and landing pages, and mixing them in one campaign usually means neither performs well.',
        'Job advertising has its own rules. The Conduct of Employment Agencies and Employment Businesses Regulations 2003 set requirements for agency job adverts, the Equality Act 2010 applies to how roles are described, and platforms such as Meta require employment ads to run in a special category with restricted targeting.',
    ],
    'rules' => ['Job adverts must meet the Conduct of Employment Agencies and Employment Businesses Regulations 2003, including stating whether a role is permanent or temporary and the nature of your involvement.', 'Avoid discriminatory wording under the Equality Act 2010.', 'Run employment ads on Meta under the special ad category, which limits age, gender and postcode targeting.'],
    'faqs' => [
        ['Can you help us win more clients as well as candidates?', 'Yes. We run separate client acquisition (often LinkedIn and search) and candidate attraction campaigns, each with its own pages and tracking.'],
        ['Do you handle Meta employment ad rules?', 'Yes. Employment campaigns run in the special ad category with compliant targeting and copy.'],
        ['Can AI help screen candidate enquiries?', 'AI chat and receptionist tools can capture key details and route enquiries, while hiring decisions stay with your consultants.'],
    ]],
'trades' => ['label' => 'trades', 'title' => 'Trades', 'industry' => 'trades-home-services',
    'context' => [
        'Gas engineers, electricians, plumbers, roofers and other trades win work locally, often from people who need help soon and pick from the first few businesses they see. Map visibility, reviews, a fast-loading mobile site and a clear phone number matter more than clever slogans.',
        'Trust signals carry real weight. Gas work must be carried out by Gas Safe registered engineers, electricians often show NICEIC or NAPIT membership, and homeowners look for those signs before they call. Your marketing should show the registrations you genuinely hold and the areas you really cover.',
    ],
    'rules' => ['Only show registrations and scheme logos you hold, such as Gas Safe, NICEIC or NAPIT, and keep them current.', 'Describe your service area accurately; do not imply a local office you do not have.', 'Reviews must be genuine and not selectively solicited in a misleading way.'],
    'faqs' => [
        ['What is the quickest way for a trade business to get more calls?', 'Usually a well-structured Google Ads campaign plus a complete Google Business Profile, both pointing to a mobile-friendly page with a clear call button. We set up call tracking so you can see what works.'],
        ['How do I stop getting jobs outside my area?', 'Location targeting, exclusions and clear service-area information on your profile and pages keep enquiries inside the postcodes you cover.'],
        ['Do reviews really make a difference for trades?', 'Yes. Many homeowners compare review counts and recent comments before calling. A simple, consistent review request process helps a lot.'],
    ]],
'business' => ['label' => 'businesses', 'title' => 'Businesses', 'industry' => 'b2b-smes',
    'context' => [
        'Most UK SMEs do not need a large marketing department, they need the right few channels working properly. For a growing business that usually means being easy to find on Google, answering enquiries quickly and following up consistently.',
        'Rules that apply to almost every business include UK GDPR and the Privacy and Electronic Communications Regulations (PECR) for email and SMS marketing, and the CAP Code for advertising claims. Building them in from the start avoids awkward fixes later.',
    ],
    'rules' => ['Email and SMS marketing to individuals needs consent or the soft opt-in under PECR.', 'Advertising claims must be truthful and capable of substantiation under the CAP Code.', 'Personal data collected by forms, chatbots and CRMs must be handled in line with UK GDPR.'],
    'faqs' => [
        ['Where should a small business start with marketing?', 'Usually with tracking, a solid Google Business Profile and website, and one paid or organic channel that fits your budget. We help you choose based on your margins and capacity.'],
        ['Do we need a big budget?', 'No. We scope work to what your business can sustain and focus on the channels most likely to pay back first. Every proposal is quoted individually (POA).'],
        ['Will you work with our existing team?', 'Yes. We often handle specific channels while your team or other suppliers cover the rest, with shared reporting.'],
    ]],
'small-business' => ['label' => 'small businesses', 'title' => 'Small businesses', 'industry' => 'b2b-smes',
    'context' => [
        'Small businesses often run on a handful of people wearing several hats. Systems such as a CRM, automated follow-ups and reliable IT only help if they are simple enough to use on a busy day, so we set them up around how your team already works.',
        'Data protection still applies at small scale. UK GDPR covers customer data in your CRM and inboxes, and PECR covers marketing emails and texts. Sensible defaults, such as role-based access and consent records, protect you without adding admin.',
    ],
    'rules' => ['Keep consent records for marketing emails and texts (PECR).', 'Limit access to customer data to the people who need it (UK GDPR).', 'Keep software and devices updated and use multi-factor authentication on business accounts.'],
    'faqs' => [
        ['Is a CRM worth it for a small business?', 'If enquiries come from more than one place, or follow-ups get missed, a simple CRM usually pays for itself in recovered leads. We set up only what you will use.'],
        ['Can you support our IT as well as marketing?', 'Yes. We can look after the IT that your marketing and sales depend on, such as email, devices, accounts and website hosting.'],
        ['How much does it cost?', 'It depends on scope, so every engagement is quoted individually (POA) after a free review.'],
    ]],
];
