<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-card">
  <p><strong>Type:</strong> <?= esc($item['type']) ?></p>
  <?php if ($item['reference']): ?><p><strong>Reference:</strong> <?= esc($item['reference']) ?></p><?php endif; ?>
  <p><strong>Name:</strong> <?= esc($item['name']) ?></p>
  <p><strong>Email:</strong> <?= esc($item['email']) ?></p>
  <p><strong>Phone:</strong> <?= esc($item['phone']) ?></p>
  <?php if ($item['subject']): ?><p><strong>Subject:</strong> <?= esc($item['subject']) ?></p><?php endif; ?>
  <?php if ($item['message']): ?><p><strong>Message:</strong><br><?= nl2br(esc($item['message'])) ?></p><?php endif; ?>

  <?php if ($payload): ?>
    <h3>Application details</h3>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <?php foreach ($payload as $k => $v): ?>
          <tr><th style="width:220px"><?= esc(str_replace('_', ' ', $k)) ?></th><td><?= esc(is_array($v) ? json_encode($v) : $v) ?></td></tr>
        <?php endforeach; ?>
      </table>
    </div>
  <?php endif; ?>

  <form method="post" action="/admin/enquiries/<?= (int) $item['id'] ?>/status" style="margin-top:20px;display:flex;gap:12px;align-items:flex-end">
    <?= csrf_field() ?>
    <label>Status
      <select name="status">
        <?php foreach (['new', 'in_review', 'contacted', 'closed'] as $s): ?>
          <option value="<?= $s ?>" <?= $item['status'] === $s ? 'selected' : '' ?>><?= esc(str_replace('_', ' ', $s)) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <button class="btn btn-primary" type="submit">Update status</button>
  </form>
</div>

<?= $this->endSection() ?>
