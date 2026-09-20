<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-card">
  <h3 style="margin-top:0">Add route</h3>
  <form method="post" action="/admin/transport/routes" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <?= csrf_field() ?>
    <label>Name<input type="text" name="name" required></label>
    <label>Description<input type="text" name="description"></label>
    <button class="btn btn-primary" type="submit" style="margin-bottom:14px">Add</button>
  </form>
</div>

<?php foreach ($routes as $r): ?>
  <div class="admin-card">
    <h3 style="margin-top:0"><?= esc($r['name']) ?> <span class="pill"><?= (int) $r['subscriber_count'] ?> subscribed</span></h3>
    <p class="form-note"><?= esc($r['description']) ?></p>
    <ul>
      <?php foreach ($r['stops'] as $s): ?><li><?= esc($s['name']) ?> — ETA <?= esc($s['eta']) ?></li><?php endforeach; ?>
    </ul>
    <form method="post" action="/admin/transport/stops" style="display:flex;gap:8px;align-items:center;margin-bottom:14px">
      <?= csrf_field() ?>
      <input type="hidden" name="route_id" value="<?= (int) $r['id'] ?>">
      <input type="text" name="name" placeholder="Stop name">
      <input type="text" name="eta" placeholder="06:15" style="width:80px">
      <button type="submit" class="btn btn-secondary">Add stop</button>
    </form>
    <form method="post" action="/admin/transport/routes/<?= (int) $r['id'] ?>/ping" style="display:flex;gap:8px;align-items:center">
      <?= csrf_field() ?>
      <span class="form-note">Manual location update (simulated, no GPS hardware connected):</span>
      <input type="text" name="lat" placeholder="Lat" style="width:100px">
      <input type="text" name="lng" placeholder="Lng" style="width:100px">
      <button type="submit" class="btn btn-secondary">Update location</button>
    </form>
  </div>
<?php endforeach; ?>
<?php if (empty($routes)): ?><p>No routes yet.</p><?php endif; ?>

<?= $this->endSection() ?>
