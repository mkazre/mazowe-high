<section class="section narrow-section">
  <div class="wrap narrow">
    <form class="tracker-form" onsubmit="return false">
      <label>Application reference
        <input type="text" placeholder="MH-2027-04918" id="trackerRef">
      </label>
      <button class="btn btn-primary" type="button" id="trackerGo">Track</button>
    </form>
    <p class="form-note">This is a preview using sample data — live tracking activates once your application is submitted and reviewed.</p>
    <div class="timeline">
      <?php foreach ($data['stages'] as $s): ?>
        <div class="timeline-item">
          <p class="timeline-when"><?= esc($s['when']) ?></p>
          <p class="timeline-t"><?= esc($s['t']) ?></p>
          <p class="timeline-b"><?= esc($s['b']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
