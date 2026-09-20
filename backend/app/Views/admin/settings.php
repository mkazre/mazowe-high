<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<form method="post" action="/admin/settings" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="admin-card">
    <h3 style="margin-top:0">Logo</h3>
    <div class="form-grid">
      <label>Header logo
        <?php if (! empty($settings['logo_header'])): ?>
          <img src="<?= esc($settings['logo_header']) ?>" alt="Header logo" style="width:80px;height:80px;object-fit:contain;background:var(--ground);margin:8px 0" onerror="this.style.display='none'">
        <?php endif; ?>
        <input type="file" name="logo_header_file" accept="image/*">
      </label>
      <label>Footer logo
        <?php if (! empty($settings['logo_footer'])): ?>
          <img src="<?= esc($settings['logo_footer']) ?>" alt="Footer logo" style="width:80px;height:80px;object-fit:contain;background:var(--ground);margin:8px 0" onerror="this.style.display='none'">
        <?php endif; ?>
        <input type="file" name="logo_footer_file" accept="image/*">
      </label>
    </div>
    <p class="form-note">Upload a PNG or SVG. The new logo replaces the current one on both the website header and footer immediately after saving.</p>
  </div>

  <div class="admin-card">
    <h3 style="margin-top:0">School details</h3>
    <div class="form-grid">
      <label>Site name<input type="text" name="site_name" value="<?= esc($settings['site_name'] ?? '') ?>"></label>
      <label>Strapline<input type="text" name="site_strapline" value="<?= esc($settings['site_strapline'] ?? '') ?>"></label>
      <label>Motto<input type="text" name="motto" value="<?= esc($settings['motto'] ?? '') ?>"></label>
      <label>Top banner text<input type="text" name="banner_text" value="<?= esc($settings['banner_text'] ?? '') ?>"></label>
    </div>
    <label>Address<textarea name="address_line" rows="2"><?= esc($settings['address_line'] ?? '') ?></textarea></label>
    <div class="form-grid">
      <label>Phone<input type="text" name="phone" value="<?= esc($settings['phone'] ?? '') ?>"></label>
      <label>Admissions email<input type="email" name="admissions_email" value="<?= esc($settings['admissions_email'] ?? '') ?>"></label>
      <label>General contact email<input type="email" name="contact_email" value="<?= esc($settings['contact_email'] ?? '') ?>"></label>
    </div>
    <label>Footer copyright note<textarea name="footer_note" rows="2"><?= esc($settings['footer_note'] ?? '') ?></textarea></label>
  </div>

  <div class="admin-card">
    <h3 style="margin-top:0">Social links</h3>
    <div class="form-grid">
      <label>Facebook<input type="url" name="facebook_url" value="<?= esc($settings['facebook_url'] ?? '') ?>"></label>
      <label>Instagram<input type="url" name="instagram_url" value="<?= esc($settings['instagram_url'] ?? '') ?>"></label>
      <label>Twitter / X<input type="url" name="twitter_url" value="<?= esc($settings['twitter_url'] ?? '') ?>"></label>
    </div>
  </div>

  <div class="admin-card">
    <h3 style="margin-top:0">"Ask Mazowe" widget</h3>
    <label style="display:flex;align-items:center;gap:10px">
      <input type="checkbox" name="show_assistant" value="1" style="width:auto" <?= ($settings['show_assistant'] ?? '1') === '1' ? 'checked' : '' ?>>
      Show the "Ask Mazowe" chat button on the website
    </label>
  </div>

  <button class="btn btn-primary" type="submit">Save settings</button>
</form>

<?= $this->endSection() ?>
