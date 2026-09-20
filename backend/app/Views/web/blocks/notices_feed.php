<section class="section">
  <div class="wrap narrow">
    <?php if (empty($data['items'])): ?>
      <p>No notices published yet.</p>
    <?php endif; ?>
    <div class="notice-list">
      <?php foreach ($data['items'] ?? [] as $n): ?>
        <article class="notice">
          <p class="notice-date"><?= esc(date('j F Y', strtotime($n['published_at'] ?? $n['created_at']))) ?></p>
          <h3><?= esc($n['title']) ?></h3>
          <p><?= esc($n['body']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
