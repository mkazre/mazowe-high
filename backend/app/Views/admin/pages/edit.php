<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="admin-card">
  <h3 style="margin-top:0">Page details</h3>
  <form method="post" action="/admin/pages/<?= (int) $page['id'] ?>/edit">
    <?= csrf_field() ?>
    <label>Title<input type="text" name="title" value="<?= esc($page['title']) ?>"></label>
    <div class="form-grid">
      <label>Kicker (small label above the heading)<input type="text" name="kicker" value="<?= esc($page['kicker']) ?>"></label>
      <label>Meta description (for search engines)<input type="text" name="meta_description" value="<?= esc($page['meta_description']) ?>"></label>
    </div>
    <label>Lead paragraph<textarea name="lead" rows="2"><?= esc($page['lead']) ?></textarea></label>
    <button class="btn btn-primary" type="submit">Save page details</button>
  </form>
</div>

<h3>Content blocks</h3>
<p class="form-note">These are the sections that make up the page, top to bottom. Simple text fields edit directly; fields marked "JSON" hold lists (cards, stats, steps, etc.) — edit carefully, keeping the same structure.</p>

<div class="block-editor">
  <?php foreach ($blocks as $block): ?>
    <div class="block-card">
      <div class="block-card-head">
        <strong><?= esc(str_replace('_', ' ', $block['type'])) ?></strong>
        <div style="margin-left:auto;display:flex;gap:8px">
          <form method="post" action="/admin/pages/<?= (int) $page['id'] ?>/blocks/reorder">
            <?= csrf_field() ?>
            <input type="hidden" name="block_id" value="<?= (int) $block['id'] ?>">
            <input type="hidden" name="direction" value="up">
            <button type="submit" title="Move up">&uarr;</button>
          </form>
          <form method="post" action="/admin/pages/<?= (int) $page['id'] ?>/blocks/reorder">
            <?= csrf_field() ?>
            <input type="hidden" name="block_id" value="<?= (int) $block['id'] ?>">
            <input type="hidden" name="direction" value="down">
            <button type="submit" title="Move down">&darr;</button>
          </form>
          <form method="post" action="/admin/pages/<?= (int) $page['id'] ?>/blocks/<?= (int) $block['id'] ?>/delete" data-confirm="Remove this block?">
            <?= csrf_field() ?>
            <button type="submit" class="danger">Remove</button>
          </form>
        </div>
      </div>
      <div class="block-card-body">
        <?php $hasImageField = in_array($block['type'], ['hero', 'boarding_promo', 'image'], true) || array_key_exists('image', $block['data']); ?>
        <?php if ($hasImageField): ?>
        <div class="image-field">
          <?php if (! empty($block['data']['image'])): ?>
            <img src="<?= esc($block['data']['image']) ?>" alt="" class="image-field-preview">
          <?php endif; ?>
          <form method="post" action="/admin/pages/<?= (int) $page['id'] ?>/blocks/<?= (int) $block['id'] ?>/image" enctype="multipart/form-data" class="image-field-form">
            <?= csrf_field() ?>
            <label>Photo<input type="file" name="image_file" accept="image/*"></label>
            <label>Alt text<input type="text" name="image_alt" value="<?= esc($block['data']['image_alt'] ?? '') ?>" placeholder="Describe the photo for accessibility"></label>
            <?php if (array_key_exists('caption', $block['data'])): ?>
              <label>Caption<input type="text" name="caption" value="<?= esc($block['data']['caption'] ?? '') ?>"></label>
            <?php endif; ?>
            <button class="btn btn-secondary" type="submit">Upload photo</button>
          </form>
        </div>
        <?php endif; ?>
        <form method="post" action="/admin/pages/<?= (int) $page['id'] ?>/blocks/<?= (int) $block['id'] ?>">
          <?= csrf_field() ?>
          <?php foreach ($block['data'] as $key => $value): ?>
            <?php if (in_array($key, ['image', 'image_alt', 'caption'], true)) continue; ?>
            <?php if (is_array($value)): ?>
              <label><?= esc($key) ?> <em>(JSON)</em>
                <textarea name="json_field[<?= esc($key) ?>]"><?= esc(json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) ?></textarea>
              </label>
            <?php elseif (is_string($value) && (strlen($value) > 100 || str_contains($value, "\n") || str_contains($value, '<'))): ?>
              <label><?= esc($key) ?>
                <textarea name="field[<?= esc($key) ?>]" rows="5"><?= esc($value) ?></textarea>
              </label>
            <?php else: ?>
              <label><?= esc($key) ?>
                <input type="text" name="field[<?= esc($key) ?>]" value="<?= esc((string) $value) ?>">
              </label>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php if (empty($block['data'])): ?>
            <p class="form-note">This block has no editable fields.</p>
          <?php endif; ?>
          <button class="btn btn-secondary" type="submit">Save block</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="admin-card" style="margin-top:20px">
  <h3 style="margin-top:0">Add a block</h3>
  <form method="post" action="/admin/pages/<?= (int) $page['id'] ?>/blocks/add" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
    <?= csrf_field() ?>
    <label style="min-width:220px">Block type
      <select name="type">
        <option value="richtext">Rich text</option>
        <option value="image">Image</option>
        <option value="cards">Cards</option>
        <option value="stats">Stats</option>
        <option value="steps">Steps</option>
        <option value="timeline">Timeline</option>
        <option value="numbered_list">Numbered list</option>
        <option value="list">Simple list</option>
        <option value="table">Table (key/value)</option>
        <option value="events">Events</option>
        <option value="posts">Posts</option>
        <option value="cta">Call to action</option>
      </select>
    </label>
    <button class="btn btn-primary" type="submit" style="margin-bottom:14px">Add block</button>
  </form>
</div>

<?= $this->endSection() ?>
