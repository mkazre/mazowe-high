<section class="section">
  <div class="wrap">
    <div class="numbered-list">
      <?php foreach ($data['items'] as $it): ?>
        <div class="numbered-item">
          <p class="numbered-n"><?= esc($it['n']) ?></p>
          <div>
            <h3><?= esc($it['t']) ?></h3>
            <p><?= esc($it['b']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
