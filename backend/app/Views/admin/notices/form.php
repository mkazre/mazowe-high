<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<form method="post" action="/admin/notices/<?= $item ? (int) $item['id'] . '/edit' : 'create' ?>" class="admin-card">
  <?= csrf_field() ?>
  <label>Title<input type="text" name="title" required value="<?= esc($item['title'] ?? '') ?>"></label>
  <label>Body<textarea name="body" rows="6" required><?= esc($item['body'] ?? '') ?></textarea></label>
  <label>Status
    <select name="status">
      <option value="published" <?= ($item['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published</option>
      <option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
    </select>
  </label>
  <button class="btn btn-primary" type="submit">Save notice</button>
</form>

<?= $this->endSection() ?>
