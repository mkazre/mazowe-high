<section class="section cta-block">
  <div class="wrap cta-inner">
    <div>
      <?php if (! empty($data['kicker'])): ?><p class="kicker kicker-inverse"><?= esc($data['kicker']) ?></p><?php endif; ?>
      <h2><?= esc($data['title']) ?></h2>
      <p class="lead-inverse"><?= esc($data['body']) ?></p>
    </div>
    <?php if (! empty($data['buttons'])): ?>
      <div class="cta-buttons">
        <?php foreach ($data['buttons'] as $btn): ?>
          <a class="btn btn-block-inverse" href="<?= esc($btn['href']) ?>"><?= esc($btn['label']) ?></a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
