<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Pupil</th><th>Balance</th><th>Add transaction</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= esc($r['first_name'] . ' ' . $r['last_name']) ?></td>
          <td>US$ <?= number_format($r['balance_cents'] / 100, 2) ?></td>
          <td>
            <form method="post" action="/admin/boarding/tuck/<?= (int) $r['id'] ?>/transaction" style="display:flex;gap:8px;align-items:center">
              <?= csrf_field() ?>
              <select name="type"><option value="deposit">Deposit</option><option value="purchase">Purchase</option></select>
              <input type="number" step="0.01" name="amount" placeholder="Amount" style="width:100px">
              <input type="text" name="note" placeholder="Note" style="width:140px">
              <button type="submit" class="btn btn-secondary">Add</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($rows)): ?><tr><td colspan="3">No tuck accounts yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
