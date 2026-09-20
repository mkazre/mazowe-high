<section class="section" data-component="menu-week">
  <div class="wrap">
    <div class="day-picker" role="tablist">
      <?php foreach ($data['days'] as $i => $d): ?>
        <button type="button" class="day-tab <?= $i === 0 ? 'is-active' : '' ?>" data-day="<?= $i ?>"><?= esc($d['name']) ?></button>
      <?php endforeach; ?>
    </div>
    <?php foreach ($data['days'] as $i => $d): ?>
      <div class="day-panel <?= $i === 0 ? 'is-active' : '' ?>" data-day-panel="<?= $i ?>">
        <p class="day-date"><?= esc($d['name']) ?> · <?= esc($d['date']) ?></p>
        <div class="meal-grid">
          <div class="meal"><h4>Breakfast</h4><p><?= esc($d['breakfast']) ?></p></div>
          <div class="meal"><h4>Lunch</h4><p><?= esc($d['lunch']) ?></p></div>
          <div class="meal"><h4>Supper</h4><p><?= esc($d['supper']) ?></p></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
