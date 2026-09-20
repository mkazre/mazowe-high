<section class="section boarding-promo">
  <div class="wrap boarding-promo-inner">
    <div>
      <p class="kicker"><?= esc($data['kicker'] ?? '') ?></p>
      <h2><?= esc($data['title'] ?? '') ?></h2>
      <p class="lead"><?= esc($data['body'] ?? '') ?></p>
      <?php if (! empty($data['buttons'])): ?>
        <div class="btn-row">
          <?php foreach ($data['buttons'] as $btn): ?>
            <a class="btn btn-secondary" href="<?= esc($btn['href']) ?>"><?= esc($btn['label']) ?></a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="boarding-media" aria-hidden="true"></div>
  </div>
</section>
