(function () {
  'use strict';

  // ── Mega menu — hover with delay so mouse can travel to panel ───────────────
  var browseWrap = document.querySelector('.cph-browse-wrap');
  var closeTimer;

  if ( browseWrap ) {
    browseWrap.addEventListener('mouseenter', function () {
      clearTimeout(closeTimer);
      browseWrap.classList.add('is-open');
    });
    browseWrap.addEventListener('mouseleave', function () {
      closeTimer = setTimeout(function () {
        browseWrap.classList.remove('is-open');
      }, 120);
    });
  }

  // ── Mega menu tab switching ──────────────────────────────────────────────────
  document.querySelectorAll('.cph-mega__tab').forEach(function (tab) {
    tab.addEventListener('click', function () {
      var mega = tab.closest('.cph-mega');
      if ( ! mega ) return;

      mega.querySelectorAll('.cph-mega__tab').forEach(function (t) {
        t.classList.remove('is-active');
        t.setAttribute('aria-selected', 'false');
      });
      tab.classList.add('is-active');
      tab.setAttribute('aria-selected', 'true');

      var target = tab.dataset.tab;
      mega.querySelectorAll('.cph-mega__panel').forEach(function (panel) {
        panel.style.display = panel.dataset.panel === target ? 'grid' : 'none';
      });
    });
  });

  // ── Mobile menu toggle ───────────────────────────────────────────────────────
  var burger      = document.getElementById('cph-burger');
  var mobilePanel = document.getElementById('cph-mobile-panel');

  if ( burger && mobilePanel ) {
    burger.addEventListener('click', function () {
      var open = mobilePanel.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', String(open));
    });
  }

  // ── Mobile accordion ────────────────────────────────────────────────────────
  document.querySelectorAll('.cph-mobile-group__btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var cats = btn.nextElementSibling;
      if ( ! cats ) return;
      var isOpen = cats.classList.toggle('is-open');
      var sign   = btn.querySelector('.cph-acc-sign');
      if ( sign ) sign.textContent = isOpen ? '–' : '+';
    });
  });

  // ── Lottie ───────────────────────────────────────────────────────────────────
  function initLottie() {
    if ( ! window.lottie ) return;
    var el = document.getElementById('cph-momo-lottie');
    if ( ! el || el.dataset.loaded ) return;
    el.dataset.loaded = '1';
    var url = el.dataset.lottie;
    if ( ! url ) return;

    fetch(url)
      .then(function (r) { return r.json(); })
      .then(function (data) {
        window.lottie.loadAnimation({
          container: el, renderer: 'svg', loop: true, autoplay: true, animationData: data
        });
        var stat = document.getElementById('cph-momo-static');
        if ( stat ) stat.style.display = 'none';
      })
      .catch(function () { el.dataset.loaded = ''; });
  }

  if ( document.readyState === 'loading' ) {
    document.addEventListener('DOMContentLoaded', initLottie);
  } else {
    initLottie();
  }

})();
