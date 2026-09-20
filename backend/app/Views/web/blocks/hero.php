<section class="hero">
  <div class="wrap hero-inner">
    <div class="hero-copy">
      <p class="kicker kicker-inverse"><?= esc($data['kicker'] ?? '') ?></p>
      <h1><?= esc($data['title'] ?? '') ?></h1>
      <p class="lead lead-inverse"><?= esc($data['lead'] ?? '') ?></p>
      <?php if (! empty($data['buttons'])): ?>
        <div class="btn-row">
          <?php foreach ($data['buttons'] as $i => $btn): ?>
            <a class="btn <?= $i === 0 ? 'btn-primary' : 'btn-outline-inverse' ?>" href="<?= esc($btn['href']) ?>"><?= esc($btn['label']) ?></a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="hero-media" aria-hidden="true"></div>
  </div>
</section>
