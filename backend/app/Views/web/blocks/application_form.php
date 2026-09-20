<section class="section">
  <div class="wrap narrow">
    <form class="app-form" id="applicationForm" action="/admissions/apply/submit" method="post">
      <?= csrf_field() ?>
      <p class="form-note"><?= esc($data['note'] ?? '') ?></p>

      <fieldset>
        <legend>Pupil details</legend>
        <div class="form-grid">
          <label>First name<input type="text" name="pupil_first_name" required></label>
          <label>Surname<input type="text" name="pupil_surname" required></label>
          <label>Date of birth<input type="date" name="pupil_dob"></label>
          <label>Gender
            <select name="pupil_gender"><option>Female</option><option>Male</option></select>
          </label>
        </div>
      </fieldset>

      <fieldset>
        <legend>Entry & pathway</legend>
        <div class="form-grid">
          <label>Entry year group
            <select name="entry_year_group">
              <option>Form 1</option><option>Form 2</option><option>Form 3</option>
              <option>Form 4</option><option>Lower Sixth</option>
            </select>
          </label>
          <label>Day or boarding
            <select name="day_or_boarding"><option>Full boarding</option><option>Day</option></select>
          </label>
        </div>
      </fieldset>

      <fieldset>
        <legend>Parent / guardian</legend>
        <div class="form-grid">
          <label>Full name<input type="text" name="guardian_name" required></label>
          <label>Relationship<input type="text" name="guardian_relationship"></label>
          <label>Mobile number<input type="tel" name="guardian_phone"></label>
          <label>Email address<input type="email" name="guardian_email" required></label>
        </div>
      </fieldset>

      <fieldset>
        <legend>Anything else</legend>
        <label>Notes for the admissions panel (optional)
          <textarea name="notes" rows="4"></textarea>
        </label>
      </fieldset>

      <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off">
      <button class="btn btn-primary" type="submit">Submit application</button>
      <div class="form-result" id="applicationResult" hidden></div>
    </form>
  </div>
</section>
