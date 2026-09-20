<section class="section narrow-section">
  <div class="wrap narrow">
    <h3>Send us a message</h3>
    <form class="contact-form" id="contactForm" action="/contact/submit" method="post">
      <?= csrf_field() ?>
      <label>Your name<input type="text" name="name" required></label>
      <label>Email or WhatsApp number<input type="text" name="email"></label>
      <label>What is this about?<input type="text" name="subject" placeholder="Admissions"></label>
      <label>Message<textarea name="message" rows="5" required></textarea></label>
      <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off">
      <button class="btn btn-primary" type="submit">Send message</button>
      <div class="form-result" id="contactResult" hidden></div>
    </form>
  </div>
</section>
