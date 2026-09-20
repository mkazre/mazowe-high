<section class="section">
  <div class="wrap">
    <?php if (! empty($data['title'])): ?><h2 class="section-title"><?= esc($data['title']) ?></h2><?php endif; ?>
    <div class="post-grid">
      <?php foreach ($data['items'] as $p): ?>
        <a class="post-card" href="/news/post">
          <p class="post-cat"><?= esc($p['cat']) ?> · <?= esc($p['date']) ?></p>
          <h3><?= esc($p['title']) ?></h3>
          <p><?= esc($p['dek']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
