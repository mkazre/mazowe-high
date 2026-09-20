<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Type</th><th>Name</th><th>Email</th><th>Subject / reference</th><th>Status</th><th>Received</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($items as $e): ?>
        <tr>
          <td><span class="pill"><?= esc($e['type']) ?></span></td>
          <td><?= esc($e['name']) ?></td>
          <td><?= esc($e['email']) ?></td>
          <td><?= esc($e['reference'] ?: $e['subject']) ?></td>
          <td><span class="pill pill-<?= $e['status'] === 'new' ? 'new' : '' ?>"><?= esc($e['status']) ?></span></td>
          <td><?= esc(date('j M Y, H:i', strtotime($e['created_at']))) ?></td>
          <td class="row-actions"><a href="/admin/enquiries/<?= (int) $e['id'] ?>">View</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($items)): ?><tr><td colspan="7">No enquiries yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
