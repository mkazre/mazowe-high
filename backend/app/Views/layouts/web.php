<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= esc($page['title'] ?? $site['site_name']) ?> · <?= esc($site['site_name'] ?? 'Mazowe Heights College') ?></title>
<meta name="description" content="<?= esc($page['meta_description'] ?? '') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/site.css">
</head>
<body>

<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header">
  <div class="topbar">
    <div class="wrap topbar-inner">
      <span class="topbar-loc">Mazowe Valley · Zimbabwe</span>
      <span class="topbar-banner"><?= esc($site['banner_text'] ?? '') ?></span>
      <nav class="topbar-links">
        <a href="/portals/pay-fees">Pay fees</a>
        <a href="/portals/parent-portal">Parent portal</a>
        <a href="/portals/results">Results</a>
        <a href="/community/contact">Contact</a>
      </nav>
    </div>
  </div>

  <div class="wrap header-inner">
    <a href="/" class="brand">
      <img src="<?= esc($site['logo_header'] ?? '/assets/img/logo.svg') ?>" alt="<?= esc($site['site_name'] ?? '') ?> logo" class="brand-mark" onerror="this.style.display='none'">
      <span class="brand-text">
        <span class="brand-name"><?= esc($site['site_name'] ?? 'Mazowe Heights College') ?></span>
        <span class="brand-strap"><?= esc($site['site_strapline'] ?? '') ?></span>
      </span>
    </a>

    <button class="hamburger" id="navToggle" aria-expanded="false" aria-controls="primaryNav" aria-label="Open menu">
      <span></span><span></span><span></span>
    </button>

    <nav class="primary-nav" id="primaryNav">
      <?php foreach ($nav as $key => $group): ?>
        <?php if (empty($group['items'])) continue; ?>
        <div class="nav-group" data-nav-group="<?= esc($key) ?>">
          <button class="nav-group-toggle" type="button"><?= esc($group['label']) ?></button>
          <div class="nav-dropdown">
            <?php foreach ($group['items'] as $item): ?>
              <a href="/<?= esc($key) ?>/<?= esc(explode('/', $item['slug'])[1] ?? $item['slug']) ?>"
                 class="<?= $active === $item['slug'] ? 'is-active' : '' ?>">
                <?= esc($item['title']) ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
      <a href="/portals/pay-fees" class="nav-utility">Pay fees</a>
      <a href="/portals/parent-portal" class="nav-utility">Parent portal</a>
      <a href="/portals/results" class="nav-utility">Results</a>
      <a href="/community/contact" class="nav-utility">Contact</a>
    </nav>

    <a href="/admissions/apply" class="btn btn-primary apply-cta">Apply now</a>
  </div>
</header>

<main id="main">
<?= $this->renderSection('content') ?>
</main>

<footer class="site-footer">
  <div class="wrap footer-inner">
    <div class="footer-col footer-brand">
      <div class="brand footer-brand-row">
        <img src="<?= esc($site['logo_footer'] ?? '/assets/img/logo-footer.svg') ?>" alt="<?= esc($site['site_name'] ?? '') ?> logo" class="brand-mark" onerror="this.style.display='none'">
        <span class="brand-name"><?= esc($site['site_name'] ?? '') ?></span>
      </div>
      <p><?= nl2br(esc($site['address_line'] ?? '')) ?></p>
      <p><?= esc($site['phone'] ?? '') ?><br><?= esc($site['admissions_email'] ?? '') ?></p>
    </div>
    <div class="footer-col">
      <p class="footer-heading">Admissions</p>
      <a href="/admissions/how-to-apply">How to apply</a>
      <a href="/admissions/apply">Online application</a>
      <a href="/admissions/fees">Fees & plans</a>
      <a href="/admissions/scholarships">Scholarships</a>
      <a href="/admissions/term-dates">Term dates</a>
    </div>
    <div class="footer-col">
      <p class="footer-heading">School life</p>
      <a href="/boarding/boarding-life">Boarding</a>
      <a href="/boarding/dining">Weekly menu</a>
      <a href="/school-life/sport">Sport</a>
      <a href="/school-life/events">Events</a>
      <a href="/school-life/library">Library</a>
    </div>
    <div class="footer-col">
      <p class="footer-heading">Community</p>
      <a href="/community/alumni">Alumni</a>
      <a href="/community/giving">Giving</a>
      <a href="/community/jobs">Vacancies</a>
      <a href="/news/notices">Notices</a>
      <a href="/community/contact">Contact</a>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="wrap footer-bottom-inner">
      <span><?= esc($site['footer_note'] ?? '') ?></span>
      <span class="footer-motto"><?= esc($site['motto'] ?? '') ?></span>
    </div>
  </div>
</footer>

<?php if (($site['show_assistant'] ?? '1') === '1'): ?>
<button class="ask-btn" id="askToggle">Ask Mazowe</button>
<div class="ask-panel" id="askPanel" hidden>
  <div class="ask-head">
    <span>Ask Mazowe</span>
    <button id="askClose" aria-label="Close">&times;</button>
  </div>
  <div class="ask-body">
    <div class="ask-msg">Hello — I can answer questions about fees, boarding, the Cambridge and ZIMSEC pathways, or help you start an application.</div>
  </div>
  <div class="ask-suggest">
    <a href="/admissions/fees">What are the fees?</a>
    <a href="/boarding/boarding-life">Tell me about boarding</a>
    <a href="/admissions/apply">How do I apply?</a>
  </div>
</div>
<?php endif; ?>

<script src="/assets/js/nav.js"></script>
</body>
</html>
