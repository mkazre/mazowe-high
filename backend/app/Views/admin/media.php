<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-card">
  <h3 style="margin-top:0">Upload an image</h3>
  <form method="post" action="/admin/media/upload" enctype="multipart/form-data" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
    <?= csrf_field() ?>
    <label>Image file<input type="file" name="file" accept="image/*" required></label>
    <label>Alt text (for accessibility)<input type="text" name="alt_text"></label>
    <button class="btn btn-primary" type="submit" style="margin-bottom:14px">Upload</button>
  </form>
</div>

<div class="media-grid">
  <?php foreach ($items as $m): ?>
    <div class="media-item">
      <img src="<?= esc($m['path']) ?>" alt="<?= esc($m['alt_text']) ?>">
      <p><?= esc($m['path']) ?></p>
      <form method="post" action="/admin/media/<?= (int) $m['id'] ?>/delete" data-confirm="Delete this image?">
        <?= csrf_field() ?>
        <button type="submit" class="danger" style="background:none;border:0;padding:4px 0;cursor:pointer;font-size:12px">Delete</button>
      </form>
    </div>
  <?php endforeach; ?>
  <?php if (empty($items)): ?><p>No images uploaded yet.</p><?php endif; ?>
</div>

<?= $this->endSection() ?>
