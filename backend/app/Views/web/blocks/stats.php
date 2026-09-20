<section class="section stats-block">
  <div class="wrap stats-grid">
    <?php foreach ($data['items'] as $s): ?>
      <div class="stat">
        <p class="stat-n"><?= esc($s['n']) ?></p>
        <p class="stat-l"><?= esc($s['l']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>
