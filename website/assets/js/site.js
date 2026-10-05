(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    // Mobile nav
    var toggle = document.querySelector('.nav-toggle');
    var nav = document.getElementById('primary-nav');
    if (toggle && nav) {
      toggle.addEventListener('click', function () {
        var open = nav.classList.toggle('open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
      var trigger = nav.querySelector('.menu-trigger');
      if (trigger) {
        trigger.addEventListener('click', function (e) {
          if (window.matchMedia('(max-width: 960px)').matches) {
            e.preventDefault();
            trigger.parentElement.classList.toggle('open');
          }
        });
      }
    }

    // FAQ accordion: one open per group, aria-expanded kept in sync
    document.querySelectorAll('.faq-accordion').forEach(function (group) {
      group.querySelectorAll('details.faq-item').forEach(function (item) {
        var summary = item.querySelector('summary');
        summary.setAttribute('aria-expanded', item.open ? 'true' : 'false');
        item.addEventListener('toggle', function () {
          summary.setAttribute('aria-expanded', item.open ? 'true' : 'false');
          if (item.open) {
            group.querySelectorAll('details.faq-item[open]').forEach(function (other) {
              if (other !== item) other.open = false;
            });
          }
        });
      });
    });

    // Preselect service on the quote form from ?service=slug
    var params = new URLSearchParams(window.location.search);
    var svc = params.get('service');
    if (svc) {
      var labels = {
        'seo': 'SEO', 'google-ads': 'Google Ads', 'meta-ads': 'Meta Ads', 'content-marketing': 'Content marketing',
        'email-marketing': 'Email campaigns', 'social-media': 'Social media', 'branding': 'Branding',
        'websites-landing-pages': 'Websites & landing pages', 'directories-listings': 'Directories & listings',
        'reputation-reviews': 'Reviews & reputation', 'video-production': 'Video', 'marketing-strategy': 'Strategy & audits'
      };
      document.querySelectorAll('.quote-form input[name="services[]"]').forEach(function (box) {
        if (box.value === labels[svc]) box.checked = true;
      });
    }
  });
})();
