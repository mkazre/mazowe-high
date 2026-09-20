<section class="section">
  <div class="wrap narrow">
    <?php if (! empty($data['title'])): ?><h2 class="section-title"><?= esc($data['title']) ?></h2><?php endif; ?>
    <ul class="plain-list">
      <?php foreach ($data['items'] as $it): ?>
        <li><?= esc($it) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
