<section class="section section-tinted">
  <div class="wrap">
    <div class="section-head">
      <h2 class="section-title"><?= esc($data['title'] ?? "What's on") ?></h2>
      <a class="section-more" href="/school-life/events">Full calendar &rarr;</a>
    </div>
    <div class="event-grid">
      <?php foreach ($data['items'] as $e): ?>
        <div class="event-card">
          <p class="event-date"><?= esc($e['date']) ?></p>
          <h4><?= esc($e['title']) ?></h4>
          <p class="event-where"><?= esc($e['where']) ?></p>
          <p class="event-tag"><?= esc($e['tag']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
