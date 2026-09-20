<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<p style="margin-bottom:16px"><a class="btn btn-primary" href="/admin/users/create">+ New user</a></p>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($items as $u): ?>
        <tr>
          <td><?= esc($u['name']) ?></td>
          <td><?= esc($u['email']) ?></td>
          <td><?= esc($u['role_name'] ?? '—') ?></td>
          <td><span class="pill pill-active"><?= esc($u['status']) ?></span></td>
          <td class="row-actions">
            <a href="/admin/users/<?= (int) $u['id'] ?>/edit">Edit</a>
            <form method="post" action="/admin/users/<?= (int) $u['id'] ?>/delete" data-confirm="Delete this user?" style="display:inline">
              <?= csrf_field() ?>
              <button type="submit" class="danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
