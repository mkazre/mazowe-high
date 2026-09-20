<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Pupil</th><th>Reason</th><th>Depart</th><th>Return</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= esc($r['first_name'] . ' ' . $r['last_name']) ?></td>
          <td><?= esc($r['reason']) ?></td>
          <td><?= esc($r['depart_at']) ?></td>
          <td><?= esc($r['return_at']) ?></td>
          <td><span class="pill <?= $r['status'] === 'approved' ? 'pill-published' : ($r['status'] === 'pending' ? 'pill-new' : '') ?>"><?= esc($r['status']) ?></span></td>
          <td class="row-actions">
            <?php if ($r['status'] === 'pending'): ?>
              <form method="post" action="/admin/boarding/exeats/<?= (int) $r['id'] ?>/status" style="display:inline">
                <?= csrf_field() ?><input type="hidden" name="status" value="approved">
                <button type="submit">Approve</button>
              </form>
              <form method="post" action="/admin/boarding/exeats/<?= (int) $r['id'] ?>/status" style="display:inline">
                <?= csrf_field() ?><input type="hidden" name="status" value="declined">
                <button type="submit" class="danger">Decline</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($rows)): ?><tr><td colspan="6">No exeat requests yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
