<section class="section">
  <div class="wrap">
    <?php if (! empty($data['title'])): ?><h2 class="section-title"><?= esc($data['title']) ?></h2><?php endif; ?>
    <div class="card-grid">
      <?php foreach ($data['items'] as $c): ?>
        <div class="card">
          <?php if (! empty($c['k'])): ?><p class="card-kicker"><?= esc($c['k']) ?></p><?php endif; ?>
          <h3><?= esc($c['title']) ?></h3>
          <p><?= esc($c['body']) ?></p>
          <?php if (! empty($c['cta'])): ?><a class="card-link" href="<?= esc($c['href'] ?? '#') ?>"><?= esc($c['cta']) ?> &rarr;</a><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
