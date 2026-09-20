<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<form method="get" action="/admin/attendance" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;margin-bottom:20px">
  <label>Class
    <select name="class_id" onchange="this.form.submit()">
      <?php foreach ($classes as $c): ?><option value="<?= (int) $c['id'] ?>" <?= (int) $c['id'] === $classId ? 'selected' : '' ?>><?= esc($c['name']) ?></option><?php endforeach; ?>
    </select>
  </label>
  <label>Date<input type="date" name="date" value="<?= esc($date) ?>" onchange="this.form.submit()"></label>
</form>

<form method="post" action="/admin/attendance">
  <?= csrf_field() ?>
  <input type="hidden" name="class_id" value="<?= $classId ?>">
  <input type="hidden" name="date" value="<?= esc($date) ?>">
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead><tr><th>Pupil</th><th>Present</th><th>Late</th><th>Absent</th></tr></thead>
      <tbody>
        <?php foreach ($students as $s): ?>
          <tr>
            <td><?= esc($s['first_name'] . ' ' . $s['last_name']) ?></td>
            <?php foreach (['present', 'late', 'absent'] as $st): ?>
              <td><input type="radio" name="status[<?= (int) $s['id'] ?>]" value="<?= $st ?>" <?= $s['status'] === $st || (! $s['status'] && $st === 'present') ? 'checked' : '' ?> style="width:auto"></td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($students)): ?><tr><td colspan="4">No pupils in this class.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if (! empty($students)): ?><button class="btn btn-primary" type="submit" style="margin-top:16px">Save register</button><?php endif; ?>
</form>

<?= $this->endSection() ?>
