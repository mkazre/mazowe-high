<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= esc($title ?? 'Dashboard') ?> · School Manager</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/site.css">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">

<button class="admin-hamburger" id="adminNavToggle" aria-label="Open menu" aria-expanded="false">
  <span></span><span></span><span></span>
</button>

<div class="admin-shell">
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-brand">
      <span class="brand-mark-sm">MH</span>
      <span>
        <span class="admin-brand-name">Mazowe Heights</span>
        <span class="admin-brand-strap">School Manager</span>
      </span>
    </div>
    <nav class="admin-nav">
      <p class="admin-nav-heading">Content</p>
      <a href="/admin/dashboard" class="<?= ($active ?? '') === 'dashboard' ? 'is-active' : '' ?>">Dashboard</a>
      <a href="/admin/pages" class="<?= ($active ?? '') === 'pages' ? 'is-active' : '' ?>">Pages</a>
      <a href="/admin/media" class="<?= ($active ?? '') === 'media' ? 'is-active' : '' ?>">Media library</a>
      <a href="/admin/settings" class="<?= ($active ?? '') === 'settings' ? 'is-active' : '' ?>">Site settings</a>

      <p class="admin-nav-heading">Admissions</p>
      <a href="/admin/enquiries" class="<?= ($active ?? '') === 'enquiries' ? 'is-active' : '' ?>">Enquiries & applications</a>

      <p class="admin-nav-heading">School setup</p>
      <a href="/admin/setup/academic-years" class="<?= ($active ?? '') === 'setup' ? 'is-active' : '' ?>">Academic years & terms</a>
      <a href="/admin/people/students" class="<?= ($active ?? '') === 'people' ? 'is-active' : '' ?>">Students & guardians</a>
      <a href="/admin/people/staff" class="<?= ($active ?? '') === 'people' ? 'is-active' : '' ?>">Staff</a>
      <a href="/admin/timetable" class="<?= ($active ?? '') === 'timetable' ? 'is-active' : '' ?>">Timetable</a>

      <p class="admin-nav-heading">Daily operations</p>
      <a href="/admin/attendance" class="<?= ($active ?? '') === 'attendance' ? 'is-active' : '' ?>">Attendance</a>
      <a href="/admin/assessments" class="<?= ($active ?? '') === 'assessments' ? 'is-active' : '' ?>">Assessment & reports</a>

      <p class="admin-nav-heading">Finance</p>
      <a href="/admin/finance/fee-structures" class="<?= ($active ?? '') === 'finance' ? 'is-active' : '' ?>">Fee structures</a>
      <a href="/admin/finance/invoices" class="<?= ($active ?? '') === 'finance' ? 'is-active' : '' ?>">Invoices & payments</a>

      <p class="admin-nav-heading">Boarding & catering</p>
      <a href="/admin/boarding/dormitories" class="<?= ($active ?? '') === 'boarding' ? 'is-active' : '' ?>">Bed allocation</a>
      <a href="/admin/boarding/exeats" class="<?= ($active ?? '') === 'boarding' ? 'is-active' : '' ?>">Exeat requests</a>
      <a href="/admin/boarding/sanatorium" class="<?= ($active ?? '') === 'boarding' ? 'is-active' : '' ?>">Sanatorium</a>
      <a href="/admin/boarding/tuck" class="<?= ($active ?? '') === 'boarding' ? 'is-active' : '' ?>">Tuck accounts</a>
      <a href="/admin/catering" class="<?= ($active ?? '') === 'catering' ? 'is-active' : '' ?>">Menu cycle</a>

      <p class="admin-nav-heading">Transport & library</p>
      <a href="/admin/transport" class="<?= ($active ?? '') === 'transport' ? 'is-active' : '' ?>">Routes & stops</a>
      <a href="/admin/library/catalogue" class="<?= ($active ?? '') === 'library' ? 'is-active' : '' ?>">Catalogue & loans</a>

      <p class="admin-nav-heading">Communications</p>
      <a href="/admin/notices" class="<?= ($active ?? '') === 'notices' ? 'is-active' : '' ?>">Notices</a>
      <a href="/admin/events" class="<?= ($active ?? '') === 'events' ? 'is-active' : '' ?>">Events</a>
      <a href="/admin/posts" class="<?= ($active ?? '') === 'posts' ? 'is-active' : '' ?>">Blog posts</a>
      <a href="/admin/vacancies" class="<?= ($active ?? '') === 'vacancies' ? 'is-active' : '' ?>">Vacancies</a>
      <a href="/admin/threads" class="<?= ($active ?? '') === 'threads' ? 'is-active' : '' ?>">Parent messages</a>

      <p class="admin-nav-heading">Administration</p>
      <a href="/admin/users" class="<?= ($active ?? '') === 'users' ? 'is-active' : '' ?>">Users & roles</a>
      <a href="/" target="_blank">View website &#8599;</a>
    </nav>
    <div class="admin-user">
      <p class="admin-user-name"><?= esc(session('admin_user_name') ?? '') ?></p>
      <p class="admin-user-role"><?= esc(session('admin_user_role') ?? '') ?></p>
      <a href="/admin/logout" class="admin-logout">Log out</a>
    </div>
  </aside>
  <div class="admin-overlay" id="adminOverlay"></div>

  <main class="admin-main">
    <header class="admin-topbar">
      <div>
        <p class="admin-crumb"><?= esc($crumb ?? '') ?></p>
        <h1><?= esc($title ?? '') ?></h1>
      </div>
      <?php if (! empty($headerAction)): ?>
        <a class="btn btn-primary admin-header-action" href="<?= esc($headerAction['href']) ?>"><?= esc($headerAction['label']) ?></a>
      <?php endif; ?>
    </header>

    <div class="admin-content">
      <?php if (session()->getFlashdata('success')): ?>
        <div class="admin-flash admin-flash-ok"><?= esc(session()->getFlashdata('success')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="admin-flash admin-flash-err"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>

      <?= $this->renderSection('content') ?>
    </div>
  </main>
</div>

<script src="/assets/js/admin.js"></script>
</body>
</html>
