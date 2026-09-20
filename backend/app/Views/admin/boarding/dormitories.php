<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-card">
  <h3 style="margin-top:0">Add dormitory</h3>
  <form method="post" action="/admin/boarding/dormitories" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <?= csrf_field() ?>
    <label>House<select name="house_id"><?php foreach ($houses as $h): ?><option value="<?= (int) $h['id'] ?>"><?= esc($h['name']) ?></option><?php endforeach; ?></select></label>
    <label>Name<input type="text" name="name" placeholder="Nyanga Bay 2"></label>
    <label>Capacity<input type="number" name="capacity" value="6"></label>
    <button class="btn btn-primary" type="submit" style="margin-bottom:14px">Add</button>
  </form>
</div>

<?php foreach ($dorms as $d): ?>
  <div class="admin-card">
    <h3 style="margin-top:0"><?= esc($d['name']) ?> <span class="pill"><?= esc($d['house_name']) ?></span></h3>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead><tr><th>Bed</th><th>Occupant</th><th>Assign</th></tr></thead>
        <tbody>
          <?php foreach ($d['beds'] as $b): ?>
            <tr>
              <td><?= esc($b['label']) ?></td>
              <td><?= $b['student_id'] ? esc($b['first_name'] . ' ' . $b['last_name']) : '—' ?></td>
              <td>
                <form method="post" action="/admin/boarding/beds/<?= (int) $b['id'] ?>/assign" style="display:flex;gap:8px">
                  <?= csrf_field() ?>
                  <select name="student_id" style="min-width:180px">
                    <option value="">— vacant —</option>
                    <?php foreach ($students as $s): ?><option value="<?= (int) $s['id'] ?>" <?= (int) $b['student_id'] === (int) $s['id'] ? 'selected' : '' ?>><?= esc($s['first_name'] . ' ' . $s['last_name']) ?></option><?php endforeach; ?>
                  </select>
                  <button type="submit" class="btn btn-secondary">Save</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endforeach; ?>
<?php if (empty($dorms)): ?><p>No dormitories yet.</p><?php endif; ?>

<?= $this->endSection() ?>
