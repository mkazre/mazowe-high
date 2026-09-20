<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<form method="post" action="/admin/events/<?= $item ? (int) $item['id'] . '/edit' : 'create' ?>" class="admin-card">
  <?= csrf_field() ?>
  <label>Title<input type="text" name="title" required value="<?= esc($item['title'] ?? '') ?>"></label>
  <div class="form-grid">
    <label>Location<input type="text" name="location" value="<?= esc($item['location'] ?? '') ?>"></label>
    <label>Starts at<input type="datetime-local" name="starts_at" value="<?= esc($item['starts_at'] ? str_replace(' ', 'T', substr($item['starts_at'], 0, 16)) : '') ?>"></label>
    <label>Tag<input type="text" name="tag" placeholder="Booking open" value="<?= esc($item['tag'] ?? '') ?>"></label>
    <label>Status
      <select name="status">
        <option value="published" <?= ($item['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published</option>
        <option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
      </select>
    </label>
  </div>
  <label>Description<textarea name="description" rows="4"><?= esc($item['description'] ?? '') ?></textarea></label>
  <button class="btn btn-primary" type="submit">Save event</button>
</form>

<?= $this->endSection() ?>
