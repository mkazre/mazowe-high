<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px">
  <?php foreach ($classes as $c): ?>
    <a class="btn <?= (int) $c['id'] === $classId ? 'btn-secondary' : '' ?>" href="/admin/timetable?class_id=<?= (int) $c['id'] ?>"><?= esc($c['name']) ?></a>
  <?php endforeach; ?>
</div>

<?php if ($classId): ?>
<div class="admin-card">
  <h3 style="margin-top:0">Add lesson</h3>
  <form method="post" action="/admin/timetable" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <?= csrf_field() ?>
    <input type="hidden" name="class_id" value="<?= $classId ?>">
    <label>Day
      <select name="day_of_week">
        <option value="1">Monday</option><option value="2">Tuesday</option><option value="3">Wednesday</option>
        <option value="4">Thursday</option><option value="5">Friday</option>
      </select>
    </label>
    <label>Period
      <select name="period_id"><?php foreach ($periods as $p): ?><option value="<?= (int) $p['id'] ?>"><?= esc($p['name']) ?></option><?php endforeach; ?></select>
    </label>
    <label>Subject
      <select name="subject_id"><?php foreach ($subjects as $s): ?><option value="<?= (int) $s['id'] ?>"><?= esc($s['name']) ?></option><?php endforeach; ?></select>
    </label>
    <label>Teacher
      <select name="staff_id"><option value="">—</option><?php foreach ($staff as $st): ?><option value="<?= (int) $st['id'] ?>"><?= esc($st['name']) ?></option><?php endforeach; ?></select>
    </label>
    <label>Room<input type="text" name="room"></label>
    <button class="btn btn-primary" type="submit" style="margin-bottom:14px">Add</button>
  </form>
</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Day</th><th>Period</th><th>Subject</th><th>Teacher</th><th>Room</th><th></th></tr></thead>
    <tbody>
      <?php $days = [1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday']; ?>
      <?php foreach ($entries as $e): ?>
        <tr>
          <td><?= esc($days[(int) $e['day_of_week']] ?? '') ?></td>
          <td><?= esc($e['period']) ?> (<?= esc($e['start_time']) ?>–<?= esc($e['end_time']) ?>)</td>
          <td><?= esc($e['subject']) ?></td>
          <td><?= esc($e['teacher'] ?? '—') ?></td>
          <td><?= esc($e['room']) ?></td>
          <td class="row-actions">
            <form method="post" action="/admin/timetable/<?= (int) $e['id'] ?>/delete" data-confirm="Remove this lesson?">
              <?= csrf_field() ?>
              <button type="submit" class="danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($entries)): ?><tr><td colspan="6">No lessons scheduled for this class yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
<?php else: ?>
  <p>Create a class in Students &amp; guardians first.</p>
<?php endif; ?>

<?= $this->endSection() ?>
