<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-card">
  <h3 style="margin-top:0">Add guardian</h3>
  <form method="post" action="/admin/people/guardians" class="form-grid">
    <?= csrf_field() ?>
    <label>Full name<input type="text" name="name" required></label>
    <label>Email<input type="email" name="email"></label>
    <label>Phone<input type="text" name="phone"></label>
    <label>Link to pupil
      <select name="student_id">
        <option value="">—</option>
        <?php foreach ($students as $s): ?><option value="<?= (int) $s['id'] ?>"><?= esc($s['first_name'] . ' ' . $s['last_name']) ?></option><?php endforeach; ?>
      </select>
    </label>
    <label>Relationship<input type="text" name="relationship" placeholder="Mother"></label>
    <div style="grid-column:1/-1"><button class="btn btn-primary" type="submit">Add guardian</button></div>
  </form>
</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Children</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $g): ?>
        <tr>
          <td><?= esc($g['name']) ?></td>
          <td><?= esc($g['email']) ?></td>
          <td><?= esc($g['phone']) ?></td>
          <td><?= esc(implode(', ', array_map(fn ($c) => $c['first_name'] . ' ' . $c['last_name'] . ' (' . $c['relationship'] . ')', $g['children']))) ?></td>
          <td class="row-actions">
            <form method="post" action="/admin/people/guardians/<?= (int) $g['id'] ?>/delete" data-confirm="Delete this guardian?">
              <?= csrf_field() ?>
              <button type="submit" class="danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($rows)): ?><tr><td colspan="5">No guardians yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
