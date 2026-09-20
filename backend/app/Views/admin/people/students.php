<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-card">
  <h3 style="margin-top:0">Add student</h3>
  <form method="post" action="/admin/people/students" class="form-grid">
    <?= csrf_field() ?>
    <label>First name<input type="text" name="first_name" required></label>
    <label>Last name<input type="text" name="last_name" required></label>
    <label>Date of birth<input type="date" name="dob"></label>
    <label>Gender
      <select name="gender"><option>Female</option><option>Male</option></select>
    </label>
    <label>Year group
      <select name="year_group_id">
        <?php foreach ($yearGroups as $yg): ?><option value="<?= (int) $yg['id'] ?>"><?= esc($yg['name']) ?></option><?php endforeach; ?>
      </select>
    </label>
    <label>Class
      <select name="class_id">
        <option value="">—</option>
        <?php foreach ($classes as $c): ?><option value="<?= (int) $c['id'] ?>"><?= esc($c['name']) ?></option><?php endforeach; ?>
      </select>
    </label>
    <label>House
      <select name="house_id">
        <option value="">—</option>
        <?php foreach ($houses as $h): ?><option value="<?= (int) $h['id'] ?>"><?= esc($h['name']) ?></option><?php endforeach; ?>
      </select>
    </label>
    <label>Day or boarding
      <select name="day_or_boarding"><option value="day">Day</option><option value="boarding">Boarding</option></select>
    </label>
    <div style="grid-column:1/-1"><button class="btn btn-primary" type="submit">Add student</button></div>
  </form>
</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Admission #</th><th>Name</th><th>Class</th><th>House</th><th>Boarding</th><th>Portal login</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $s): ?>
        <tr>
          <td><?= esc($s['admission_number']) ?></td>
          <td><?= esc($s['first_name'] . ' ' . $s['last_name']) ?></td>
          <td><?= esc($s['class_name'] ?? '—') ?></td>
          <td><?= esc($s['house_name'] ?? '—') ?></td>
          <td><?= esc($s['day_or_boarding']) ?></td>
          <td>
            <?php if ($s['user_id']): ?>
              <span class="pill pill-published">Has login</span>
            <?php else: ?>
              <form method="post" action="/admin/people/students/<?= (int) $s['id'] ?>/create-login" style="display:flex;gap:6px">
                <?= csrf_field() ?>
                <input type="email" name="login_email" placeholder="parent or pupil email" required style="width:170px">
                <button type="submit" class="btn btn-secondary" style="white-space:nowrap">Create login</button>
              </form>
            <?php endif; ?>
          </td>
          <td class="row-actions">
            <form method="post" action="/admin/people/students/<?= (int) $s['id'] ?>/delete" data-confirm="Delete this student?">
              <?= csrf_field() ?>
              <button type="submit" class="danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($rows)): ?><tr><td colspan="7">No students yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
