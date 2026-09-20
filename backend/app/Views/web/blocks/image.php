<?php if (! empty($data['image'])): ?>
<section class="section">
  <div class="wrap narrow">
    <figure class="content-image">
      <img src="<?= esc($data['image']) ?>" alt="<?= esc($data['image_alt'] ?? '') ?>">
      <?php if (! empty($data['caption'])): ?><figcaption><?= esc($data['caption']) ?></figcaption><?php endif; ?>
    </figure>
  </div>
</section>
<?php endif; ?>
