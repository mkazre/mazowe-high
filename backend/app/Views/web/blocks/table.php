<section class="section">
  <div class="wrap narrow">
    <?php if (! empty($data['title'])): ?><h2 class="section-title"><?= esc($data['title']) ?></h2><?php endif; ?>
    <div class="kv-table">
      <?php foreach ($data['rows'] as $row): ?>
        <div class="kv-row">
          <span class="kv-label"><?= esc($row['label']) ?></span>
          <span class="kv-value"><?= esc($row['value']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
