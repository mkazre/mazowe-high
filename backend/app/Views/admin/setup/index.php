<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px">
  <?php foreach ($entities as $k => $conf): ?>
    <a class="btn <?= $k === $key ? 'btn-secondary' : '' ?>" href="/admin/setup/<?= esc($k) ?>"><?= esc($conf['label']) ?></a>
  <?php endforeach; ?>
</div>

<div class="admin-card">
  <h3 style="margin-top:0">Add <?= esc(rtrim($entity['label'], 's')) ?></h3>
  <form method="post" action="/admin/setup/<?= esc($key) ?>" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <?= csrf_field() ?>
    <?php foreach ($entity['fields'] as $field => $type): ?>
      <label style="min-width:160px">
        <?= esc(str_replace('_', ' ', $field)) ?>
        <?php if ($type === 'bool'): ?>
          <select name="<?= esc($field) ?>"><option value="0">No</option><option value="1">Yes</option></select>
        <?php elseif ($type === 'date'): ?>
          <input type="date" name="<?= esc($field) ?>">
        <?php else: ?>
          <input type="text" name="<?= esc($field) ?>">
        <?php endif; ?>
      </label>
    <?php endforeach; ?>
    <button class="btn btn-primary" type="submit" style="margin-bottom:14px">Add</button>
  </form>
</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <?php foreach (array_keys($entity['fields']) as $field): ?><th><?= esc(str_replace('_', ' ', $field)) ?></th><?php endforeach; ?>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $row): ?>
        <tr>
          <?php foreach (array_keys($entity['fields']) as $field): ?><td><?= esc($row[$field] ?? '') ?></td><?php endforeach; ?>
          <td class="row-actions">
            <form method="post" action="/admin/setup/<?= esc($key) ?>/<?= (int) $row['id'] ?>/delete" data-confirm="Delete this?">
              <?= csrf_field() ?>
              <button type="submit" class="danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($rows)): ?><tr><td colspan="<?= count($entity['fields']) + 1 ?>">Nothing here yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
