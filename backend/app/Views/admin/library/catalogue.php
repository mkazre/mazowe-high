<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-card">
  <h3 style="margin-top:0">Add a title</h3>
  <form method="post" action="/admin/library/catalogue" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <?= csrf_field() ?>
    <label>Title<input type="text" name="title" required></label>
    <label>Author<input type="text" name="author"></label>
    <label>ISBN<input type="text" name="isbn"></label>
    <label>Copies<input type="number" name="copies_total" value="1"></label>
    <button class="btn btn-primary" type="submit" style="margin-bottom:14px">Add</button>
  </form>
</div>

<div class="admin-card">
  <h3 style="margin-top:0">Issue a loan</h3>
  <form method="post" action="/admin/library/loans" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <?= csrf_field() ?>
    <label>Title<select name="catalogue_item_id"><?php foreach ($rows as $r): ?><option value="<?= (int) $r['id'] ?>"><?= esc($r['title']) ?></option><?php endforeach; ?></select></label>
    <label>Pupil<select name="student_id"><?php foreach ($students as $s): ?><option value="<?= (int) $s['id'] ?>"><?= esc($s['first_name'] . ' ' . $s['last_name']) ?></option><?php endforeach; ?></select></label>
    <button class="btn btn-primary" type="submit" style="margin-bottom:14px">Issue (14 days)</button>
  </form>
</div>

<h3>Catalogue</h3>
<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Title</th><th>Author</th><th>Copies</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?><tr><td><?= esc($r['title']) ?></td><td><?= esc($r['author']) ?></td><td><?= (int) $r['copies_total'] ?></td></tr><?php endforeach; ?>
    </tbody>
  </table>
</div>

<h3 style="margin-top:24px">Active loans</h3>
<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Title</th><th>Pupil</th><th>Due</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($loans as $l): ?>
        <tr>
          <td><?= esc($l['title']) ?></td>
          <td><?= esc($l['first_name'] . ' ' . $l['last_name']) ?></td>
          <td><?= esc($l['due_at']) ?></td>
          <td class="row-actions">
            <form method="post" action="/admin/library/loans/<?= (int) $l['id'] ?>/return">
              <?= csrf_field() ?>
              <button type="submit">Mark returned</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($loans)): ?><tr><td colspan="4">No active loans.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
