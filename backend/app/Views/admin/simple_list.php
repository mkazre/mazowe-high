<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <?php foreach ($columns as $label): ?><th><?= esc($label) ?></th><?php endforeach; ?>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $row): ?>
        <tr>
          <?php foreach (array_keys($columns) as $col): ?>
            <td>
              <?php if ($col === 'status'): ?>
                <span class="pill pill-<?= esc($row[$col]) ?>"><?= esc($row[$col]) ?></span>
              <?php elseif (str_ends_with($col, '_at') && $row[$col]): ?>
                <?= esc(date('j M Y', strtotime($row[$col]))) ?>
              <?php else: ?>
                <?= esc($row[$col] ?? '') ?>
              <?php endif; ?>
            </td>
          <?php endforeach; ?>
          <td class="row-actions">
            <a href="/admin/<?= esc($entity) ?>/<?= (int) $row['id'] ?>/edit">Edit</a>
            <form method="post" action="/admin/<?= esc($entity) ?>/<?= (int) $row['id'] ?>/delete" data-confirm="Delete this?" style="display:inline">
              <?= csrf_field() ?>
              <button type="submit" class="danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($items)): ?><tr><td colspan="<?= count($columns) + 1 ?>">Nothing here yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
