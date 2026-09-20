<section class="section tour-block" data-component="tour">
  <div class="wrap">
    <div class="tour-picker" role="tablist">
      <?php foreach ($data['items'] as $i => $stop): ?>
        <button type="button" class="tour-stop <?= $i === 0 ? 'is-active' : '' ?>" data-stop="<?= $i ?>">
          <?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?> <?= esc($stop['t']) ?>
        </button>
      <?php endforeach; ?>
    </div>
    <div class="tour-detail">
      <?php foreach ($data['items'] as $i => $stop): ?>
        <div class="tour-panel <?= $i === 0 ? 'is-active' : '' ?>" data-panel="<?= $i ?>">
          <p class="tour-n"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?> / <?= count($data['items']) ?></p>
          <h2><?= esc($stop['t']) ?></h2>
          <p><?= esc($stop['b']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
