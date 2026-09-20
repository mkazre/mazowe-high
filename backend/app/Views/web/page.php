<?= $this->extend('layouts/web') ?>
<?= $this->section('content') ?>

<?php $hasHeroBlock = ! empty($page['blocks']) && $page['blocks'][0]['type'] === 'hero'; ?>
<?php if (! $hasHeroBlock && ! empty($page['kicker'])): ?>
  <section class="page-hero">
    <div class="wrap">
      <p class="kicker"><?= esc($page['kicker']) ?></p>
      <h1><?= esc($page['title']) ?></h1>
      <?php if (! empty($page['lead'])): ?><p class="lead"><?= esc($page['lead']) ?></p><?php endif; ?>
    </div>
  </section>
<?php endif; ?>

<?php foreach ($page['blocks'] as $block): ?>
  <?= view('web/blocks/' . $block['type'], ['data' => $block['data']]) ?>
<?php endforeach; ?>

<?= $this->endSection() ?>
