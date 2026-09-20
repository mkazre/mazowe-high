<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="stat-cards">
  <div class="stat-card"><p class="stat-n"><?= (int) $counts['pages'] ?></p><p class="stat-l">Published pages</p></div>
  <div class="stat-card"><p class="stat-n"><?= (int) $counts['enquiries'] ?></p><p class="stat-l">New enquiries & applications</p></div>
  <div class="stat-card"><p class="stat-n"><?= (int) $counts['notices'] ?></p><p class="stat-l">Notices to parents</p></div>
  <div class="stat-card"><p class="stat-n"><?= (int) $counts['vacancies'] ?></p><p class="stat-l">Open vacancies</p></div>
</div>

<div class="admin-card">
  <h3 style="margin-top:0">Recent enquiries</h3>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead><tr><th>Type</th><th>Name</th><th>Subject</th><th>Received</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($recentEnquiries as $e): ?>
          <tr>
            <td><span class="pill"><?= esc($e['type']) ?></span></td>
            <td><?= esc($e['name']) ?></td>
            <td><?= esc($e['subject'] ?: '—') ?></td>
            <td><?= esc(date('j M Y, H:i', strtotime($e['created_at']))) ?></td>
            <td class="row-actions"><a href="/admin/enquiries/<?= (int) $e['id'] ?>">View</a></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($recentEnquiries)): ?>
          <tr><td colspan="5">No enquiries yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="admin-card">
  <h3 style="margin-top:0">Quick links</h3>
  <p><a href="/admin/pages">Edit page content &rarr;</a></p>
  <p><a href="/admin/settings">Update logo & contact details &rarr;</a></p>
  <p><a href="/admin/media">Upload images &rarr;</a></p>
</div>

<?= $this->endSection() ?>
