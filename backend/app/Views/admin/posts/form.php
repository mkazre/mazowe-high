<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<form method="post" action="/admin/posts/<?= $item ? (int) $item['id'] . '/edit' : 'create' ?>" class="admin-card">
  <?= csrf_field() ?>
  <label>Title<input type="text" name="title" required value="<?= esc($item['title'] ?? '') ?>"></label>
  <div class="form-grid">
    <label>Category<input type="text" name="category" value="<?= esc($item['category'] ?? '') ?>"></label>
    <label>Status
      <select name="status">
        <option value="published" <?= ($item['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published</option>
        <option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
      </select>
    </label>
  </div>
  <label>Excerpt<input type="text" name="excerpt" value="<?= esc($item['excerpt'] ?? '') ?>"></label>
  <label>Body (HTML)<textarea name="body" rows="8"><?= esc($item['body'] ?? '') ?></textarea></label>
  <button class="btn btn-primary" type="submit">Save post</button>
</form>

<?= $this->endSection() ?>
