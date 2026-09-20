<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-card">
  <h3 style="margin-top:0">Create invoice</h3>
  <form method="post" action="/admin/finance/invoices" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <?= csrf_field() ?>
    <label>Pupil
      <select name="student_id"><?php foreach ($students as $s): ?><option value="<?= (int) $s['id'] ?>"><?= esc($s['first_name'] . ' ' . $s['last_name']) ?></option><?php endforeach; ?></select>
    </label>
    <label>Description<input type="text" name="description" placeholder="Term 1 fees"></label>
    <label>Amount (USD)<input type="number" step="0.01" name="amount" required></label>
    <label>Due date<input type="date" name="due_date"></label>
    <button class="btn btn-primary" type="submit" style="margin-bottom:14px">Create</button>
  </form>
</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Invoice #</th><th>Pupil</th><th>Total</th><th>Paid</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= esc($r['invoice_number']) ?></td>
          <td><?= esc($r['first_name'] . ' ' . $r['last_name']) ?></td>
          <td>US$ <?= number_format($r['total_cents'] / 100, 2) ?></td>
          <td>US$ <?= number_format($r['paid_cents'] / 100, 2) ?></td>
          <td><span class="pill <?= $r['status'] === 'paid' ? 'pill-published' : ($r['status'] === 'unpaid' ? 'pill-new' : '') ?>"><?= esc($r['status']) ?></span></td>
          <td class="row-actions"><a href="/admin/finance/invoices/<?= (int) $r['id'] ?>">View</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($rows)): ?><tr><td colspan="6">No invoices yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
