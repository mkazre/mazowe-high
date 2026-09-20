<section class="section">
  <div class="wrap">
    <div class="steps-grid">
      <?php foreach ($data['items'] as $s): ?>
        <div class="step">
          <p class="step-n"><?= esc($s['n']) ?></p>
          <h3><?= esc($s['t']) ?></h3>
          <p><?= esc($s['b']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
