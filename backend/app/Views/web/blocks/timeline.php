<section class="section">
  <div class="wrap narrow">
    <?php if (! empty($data['title'])): ?><h2 class="section-title"><?= esc($data['title']) ?></h2><?php endif; ?>
    <div class="timeline">
      <?php foreach ($data['items'] as $it): ?>
        <div class="timeline-item">
          <p class="timeline-when"><?= esc($it['when']) ?></p>
          <p class="timeline-t"><?= esc($it['t']) ?></p>
          <?php if (! empty($it['b'])): ?><p class="timeline-b"><?= esc($it['b']) ?></p><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
