(function () {
  'use strict';

  // ---------------- Mobile hamburger + accordion nav ----------------
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('primaryNav');

  function closeNav() {
    if (nav) nav.classList.remove('is-open');
    if (toggle) toggle.setAttribute('aria-expanded', 'false');
    document.querySelectorAll('.nav-group.is-open').forEach(function (g) { g.classList.remove('is-open'); });
  }

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  document.querySelectorAll('.nav-group-toggle').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      var isMobile = window.matchMedia('(max-width: 900px)').matches;
      if (!isMobile) return; // desktop uses hover
      e.preventDefault();
      var group = btn.closest('.nav-group');
      var wasOpen = group.classList.contains('is-open');
      document.querySelectorAll('.nav-group.is-open').forEach(function (g) { g.classList.remove('is-open'); });
      if (!wasOpen) group.classList.add('is-open');
    });
  });

  document.addEventListener('click', function (e) {
    var isMobile = window.matchMedia('(max-width: 900px)').matches;
    if (isMobile) return;
    if (!e.target.closest('.nav-group')) {
      document.querySelectorAll('.nav-group.is-open').forEach(function (g) { g.classList.remove('is-open'); });
    }
  });

  window.addEventListener('resize', function () {
    if (window.matchMedia('(min-width: 901px)').matches) closeNav();
  });

  // ---------------- Ask Mazowe widget ----------------
  var askToggle = document.getElementById('askToggle');
  var askPanel = document.getElementById('askPanel');
  var askClose = document.getElementById('askClose');
  if (askToggle && askPanel) {
    askToggle.addEventListener('click', function () { askPanel.hidden = !askPanel.hidden; });
  }
  if (askClose && askPanel) {
    askClose.addEventListener('click', function () { askPanel.hidden = true; });
  }

  // ---------------- Virtual tour stop picker ----------------
  document.querySelectorAll('[data-component="tour"]').forEach(function (root) {
    root.querySelectorAll('.tour-stop').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var idx = btn.getAttribute('data-stop');
        root.querySelectorAll('.tour-stop').forEach(function (b) { b.classList.remove('is-active'); });
        root.querySelectorAll('.tour-panel').forEach(function (p) { p.classList.remove('is-active'); });
        btn.classList.add('is-active');
        var panel = root.querySelector('.tour-panel[data-panel="' + idx + '"]');
        if (panel) panel.classList.add('is-active');
      });
    });
  });

  // ---------------- Dining weekly menu day tabs ----------------
  document.querySelectorAll('[data-component="menu-week"]').forEach(function (root) {
    root.querySelectorAll('.day-tab').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var idx = btn.getAttribute('data-day');
        root.querySelectorAll('.day-tab').forEach(function (b) { b.classList.remove('is-active'); });
        root.querySelectorAll('.day-panel').forEach(function (p) { p.classList.remove('is-active'); });
        btn.classList.add('is-active');
        var panel = root.querySelector('.day-panel[data-day-panel="' + idx + '"]');
        if (panel) panel.classList.add('is-active');
      });
    });
  });

  // ---------------- Contact form (AJAX submit) ----------------
  function wireAjaxForm(form, resultId, onSuccess) {
    if (!form) return;
    var result = document.getElementById(resultId);
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (result) { result.hidden = true; result.className = 'form-result'; }
      var submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) submitBtn.disabled = true;

      fetch(form.action, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(form),
      })
        .then(function (r) { return r.json().then(function (data) { return { status: r.status, data: data }; }); })
        .then(function (res) {
          if (result) {
            result.hidden = false;
            if (res.data && res.data.ok) {
              result.className = 'form-result ok';
              result.textContent = onSuccess ? onSuccess(res.data) : 'Thank you — your message has been sent.';
              form.reset();
            } else {
              result.className = 'form-result err';
              result.textContent = 'Please check the form and try again.';
            }
          }
        })
        .catch(function () {
          if (result) {
            result.hidden = false;
            result.className = 'form-result err';
            result.textContent = 'Something went wrong sending this — please try again or email us directly.';
          }
        })
        .finally(function () {
          if (submitBtn) submitBtn.disabled = false;
        });
    });
  }

  wireAjaxForm(document.getElementById('contactForm'), 'contactResult');
  wireAjaxForm(document.getElementById('applicationForm'), 'applicationResult', function (data) {
    return 'Application received. Your reference is ' + data.reference + ' — save it to track your application.';
  });
})();
