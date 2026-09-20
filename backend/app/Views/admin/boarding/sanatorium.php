<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-card">
  <h3 style="margin-top:0">Log a visit</h3>
  <form method="post" action="/admin/boarding/sanatorium" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <?= csrf_field() ?>
    <label>Pupil<select name="student_id"><?php foreach ($students as $s): ?><option value="<?= (int) $s['id'] ?>"><?= esc($s['first_name'] . ' ' . $s['last_name']) ?></option><?php endforeach; ?></select></label>
    <label>Reason<input type="text" name="reason"></label>
    <label>Notes<input type="text" name="notes"></label>
    <button class="btn btn-primary" type="submit" style="margin-bottom:14px">Log visit</button>
  </form>
</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Pupil</th><th>Reason</th><th>Visited</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= esc($r['first_name'] . ' ' . $r['last_name']) ?></td>
          <td><?= esc($r['reason']) ?> <?php if ($r['notes']): ?><br><span class="form-note"><?= esc($r['notes']) ?></span><?php endif; ?></td>
          <td><?= esc($r['visited_at']) ?></td>
          <td><span class="pill <?= $r['resolved'] ? 'pill-published' : 'pill-new' ?>"><?= $r['resolved'] ? 'Resolved' : 'Open' ?></span></td>
          <td class="row-actions">
            <?php if (! $r['resolved']): ?>
              <form method="post" action="/admin/boarding/sanatorium/<?= (int) $r['id'] ?>/resolve">
                <?= csrf_field() ?>
                <button type="submit">Mark resolved</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($rows)): ?><tr><td colspan="5">No visits logged yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
