<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Page</th><th>Nav group</th><th>Slug</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($pages as $p): ?>
        <tr>
          <td><?= esc($p['title']) ?></td>
          <td><?= esc($p['nav_group'] ?: '—') ?></td>
          <td><code>/<?= esc($p['slug']) ?></code></td>
          <td><span class="pill pill-<?= esc($p['status']) ?>"><?= esc($p['status']) ?></span></td>
          <td class="row-actions">
            <a href="/admin/pages/<?= (int) $p['id'] ?>/edit">Edit</a>
            <a href="/<?= esc($p['slug']) ?>" target="_blank">View</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
