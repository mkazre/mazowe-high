<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php foreach ($days as $d): ?>
  <div class="admin-card">
    <h3 style="margin-top:0"><?= esc($d['day_name']) ?></h3>
    <?php foreach ($d['items'] as $item): ?>
      <form method="post" action="/admin/catering/items/<?= (int) $item['id'] ?>" style="display:flex;gap:8px;align-items:center;margin-bottom:8px">
        <?= csrf_field() ?>
        <span class="pill" style="text-transform:uppercase;min-width:80px;text-align:center"><?= esc($item['meal']) ?></span>
        <input type="text" name="description" value="<?= esc($item['description']) ?>" style="flex:1">
        <input type="text" name="tags" value="<?= esc($item['tags']) ?>" style="width:100px" placeholder="V, GF, H">
        <?php if ($item['avg_rating']): ?><span class="pill">★ <?= esc($item['avg_rating']) ?></span><?php endif; ?>
        <button type="submit" class="btn btn-secondary">Save</button>
        <button type="submit" formaction="/admin/catering/items/<?= (int) $item['id'] ?>/delete" class="danger">Remove</button>
      </form>
    <?php endforeach; ?>
    <form method="post" action="/admin/catering" style="display:flex;gap:8px;align-items:center;margin-top:12px">
      <?= csrf_field() ?>
      <input type="hidden" name="menu_day_id" value="<?= (int) $d['id'] ?>">
      <select name="meal"><option value="breakfast">Breakfast</option><option value="lunch">Lunch</option><option value="supper">Supper</option></select>
      <input type="text" name="description" placeholder="Description" style="flex:1">
      <input type="text" name="tags" placeholder="Tags" style="width:100px">
      <button type="submit" class="btn btn-primary">Add</button>
    </form>
  </div>
<?php endforeach; ?>

<?= $this->endSection() ?>
