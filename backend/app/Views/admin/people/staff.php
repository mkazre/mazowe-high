<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-card">
  <h3 style="margin-top:0">Add staff member</h3>
  <p class="form-note">If no user account exists for this email yet, one is created automatically with the "teacher" role and a random password (reset it from Users &amp; roles).</p>
  <form method="post" action="/admin/people/staff" class="form-grid">
    <?= csrf_field() ?>
    <label>Full name<input type="text" name="name" required></label>
    <label>Email<input type="email" name="email" required></label>
    <label>Staff number<input type="text" name="staff_number"></label>
    <label>Department<input type="text" name="department"></label>
    <label>Position<input type="text" name="position"></label>
    <div style="grid-column:1/-1"><button class="btn btn-primary" type="submit">Add staff member</button></div>
  </form>
</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Name</th><th>Email</th><th>Department</th><th>Position</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $s): ?>
        <tr>
          <td><?= esc($s['name']) ?></td>
          <td><?= esc($s['email']) ?></td>
          <td><?= esc($s['department']) ?></td>
          <td><?= esc($s['position']) ?></td>
          <td class="row-actions">
            <form method="post" action="/admin/people/staff/<?= (int) $s['id'] ?>/delete" data-confirm="Remove this staff record?">
              <?= csrf_field() ?>
              <button type="submit" class="danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($rows)): ?><tr><td colspan="5">No staff yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
