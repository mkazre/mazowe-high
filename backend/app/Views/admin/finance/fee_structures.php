<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-card">
  <h3 style="margin-top:0">Add fee structure</h3>
  <form method="post" action="/admin/finance/fee-structures" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <?= csrf_field() ?>
    <label>Year group
      <select name="year_group_id"><option value="">All</option><?php foreach ($yearGroups as $yg): ?><option value="<?= (int) $yg['id'] ?>"><?= esc($yg['name']) ?></option><?php endforeach; ?></select>
    </label>
    <label>Term
      <select name="term_id"><option value="">—</option><?php foreach ($terms as $t): ?><option value="<?= (int) $t['id'] ?>"><?= esc($t['name']) ?></option><?php endforeach; ?></select>
    </label>
    <label>Description<input type="text" name="description" placeholder="Term 1 boarding fee"></label>
    <label>Amount (USD)<input type="number" step="0.01" name="amount"></label>
    <button class="btn btn-primary" type="submit" style="margin-bottom:14px">Add</button>
  </form>
</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Year group</th><th>Term</th><th>Description</th><th>Amount</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= esc($r['year_group'] ?? 'All') ?></td>
          <td><?= esc($r['term'] ?? '—') ?></td>
          <td><?= esc($r['description']) ?></td>
          <td>US$ <?= number_format($r['amount_cents'] / 100, 2) ?></td>
          <td class="row-actions">
            <form method="post" action="/admin/finance/fee-structures/<?= (int) $r['id'] ?>/delete" data-confirm="Delete?">
              <?= csrf_field() ?>
              <button type="submit" class="danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($rows)): ?><tr><td colspan="5">No fee structures yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
