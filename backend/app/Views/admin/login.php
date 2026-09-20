<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sign in · School Manager</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/site.css">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
<div class="login-shell">
  <div class="login-card">
    <h1>Mazowe Heights — School Manager</h1>
    <p class="form-note">Sign in with your staff account.</p>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="admin-flash admin-flash-err"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <form method="post" action="/admin/login">
      <?= csrf_field() ?>
      <label>Email<input type="email" name="email" required value="<?= esc(old('email')) ?>"></label>
      <label>Password<input type="password" name="password" required></label>
      <button class="btn btn-primary" type="submit" style="width:100%">Sign in</button>
    </form>
    <p class="form-note" style="margin-top:16px">Default admin: admin@mazoweheights.ac.zw</p>
  </div>
</div>
</body>
</html>
