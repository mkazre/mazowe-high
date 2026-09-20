<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<form method="post" action="/admin/vacancies/<?= $item ? (int) $item['id'] . '/edit' : 'create' ?>" class="admin-card">
  <?= csrf_field() ?>
  <label>Title<input type="text" name="title" required value="<?= esc($item['title'] ?? '') ?>"></label>
  <div class="form-grid">
    <label>Department<input type="text" name="department" value="<?= esc($item['department'] ?? '') ?>"></label>
    <label>Closing date<input type="date" name="closing_date" value="<?= esc($item['closing_date'] ?? '') ?>"></label>
    <label>Status
      <select name="status">
        <option value="open" <?= ($item['status'] ?? 'open') === 'open' ? 'selected' : '' ?>>Open</option>
        <option value="closed" <?= ($item['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Closed</option>
      </select>
    </label>
  </div>
  <label>Description<textarea name="description" rows="5"><?= esc($item['description'] ?? '') ?></textarea></label>
  <button class="btn btn-primary" type="submit">Save vacancy</button>
</form>

<?= $this->endSection() ?>
