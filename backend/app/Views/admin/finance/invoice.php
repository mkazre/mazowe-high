<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-card">
  <p><strong>Pupil:</strong> <?= esc($invoice['first_name'] . ' ' . $invoice['last_name']) ?></p>
  <p><strong>Total:</strong> US$ <?= number_format($invoice['total_cents'] / 100, 2) ?> · <strong>Paid:</strong> US$ <?= number_format($invoice['paid_cents'] / 100, 2) ?></p>
  <p><strong>Status:</strong> <span class="pill <?= $invoice['status'] === 'paid' ? 'pill-published' : 'pill-new' ?>"><?= esc($invoice['status']) ?></span></p>

  <h3>Lines</h3>
  <?php foreach ($lines as $l): ?>
    <p><?= esc($l['description']) ?> — US$ <?= number_format($l['amount_cents'] / 100, 2) ?></p>
  <?php endforeach; ?>

  <h3>Payments</h3>
  <?php foreach ($payments as $p): ?>
    <p><?= esc(strtoupper($p['method'])) ?> — US$ <?= number_format($p['amount_cents'] / 100, 2) ?> — <?= esc($p['status']) ?> <?php if ($p['is_test']): ?><span class="pill">test</span><?php endif; ?> — <?= esc($p['created_at']) ?></p>
  <?php endforeach; ?>
  <?php if (empty($payments)): ?><p>No payments recorded yet.</p><?php endif; ?>

  <?php if ($invoice['status'] !== 'paid'): ?>
    <h3>Record a cash payment</h3>
    <form method="post" action="/admin/finance/invoices/<?= (int) $invoice['id'] ?>/cash-payment" style="display:flex;gap:12px;align-items:flex-end">
      <?= csrf_field() ?>
      <label>Amount (USD)<input type="number" step="0.01" name="amount" required></label>
      <button class="btn btn-primary" type="submit">Record</button>
    </form>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>
