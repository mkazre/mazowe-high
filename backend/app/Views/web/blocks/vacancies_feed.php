<section class="section">
  <div class="wrap narrow">
    <?php if (empty($data['items'])): ?>
      <p>No vacancies open right now.</p>
    <?php endif; ?>
    <div class="vacancy-list">
      <?php foreach ($data['items'] ?? [] as $v): ?>
        <article class="vacancy">
          <div>
            <h3><?= esc($v['title']) ?></h3>
            <p class="vacancy-dept"><?= esc($v['department']) ?></p>
            <p><?= esc($v['description']) ?></p>
          </div>
          <a class="btn btn-secondary" href="mailto:<?= esc($v['contact_email'] ?? 'info@mazoweheights.ac.zw') ?>?subject=<?= rawurlencode('Application: ' . $v['title']) ?>">Apply</a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
