/* =============================================================================
   Pet Shelter: app.js (vanilla, loaded with `defer` after bootstrap.bundle.min.js)
   =============================================================================

   PUBLIC API (window.PetShelter, plus window.showToast as a shortcut)
     showToast(message, type = 'success', options)    type: 'success' | 'error' | 'info'
         options.delay (ms, default 5000 / 8000 for errors). Returns the toast element.
     fetchJson(url, { method = 'POST', body, headers })   -> Promise<{ ok, status, data }>
         Sends X-CSRF-TOKEN (from <meta name="csrf-token">), Accept: application/json,
         X-Requested-With. A plain-object body is JSON-encoded; FormData is sent as is.
         Never rejects on HTTP errors (check `ok`); rejects only on network failure.
     csrfToken()                                       -> string
     setStatus(el, status, label)                      updates a .status-dot (data-status + text)
     getTheme() / setTheme('light' | 'dark')           persists the choice in localStorage
     init(root = document)                             (re)binds reveal, count-up, password
                                                       toggles, image previews in new markup

   DECLARATIVE HOOKS (no JS needed in views)
     [data-theme-toggle]                    button that flips light/dark
     .toast[data-autoshow]                  shown on load (server flash toasts)
     [data-reveal]                          fade + rise when scrolled into view, 40 ms stagger
     [data-count-to="42"]                   counts up from 0 when visible (keep "42" as text too)
     [data-password-toggle]                 show/hide password; aria-controls="<input id>" or
                                            place inside .password-field next to the input
     form[data-confirm="Delete this pet?"]  native confirm() before submitting
                                            (also works on a submit button)
     form[data-autosubmit]                  submits when a select/radio/checkbox changes
     input[type=file][data-preview="#img"]  shows the chosen image in <img id="img">
   ============================================================================= */
(function () {
    'use strict';

    var doc = document;
    var root = doc.documentElement;
    var STORAGE_KEY = 'ps-theme';
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- Theme ---------------------------------------------------- */

    function storedTheme() {
        try { return localStorage.getItem(STORAGE_KEY); } catch (e) { return null; }
    }

    function getTheme() {
        return root.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
    }

    function syncThemeButtons() {
        var next = getTheme() === 'dark' ? 'light' : 'dark';
        doc.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
            btn.setAttribute('aria-label', 'Switch to ' + next + ' theme');
            btn.setAttribute('title', 'Switch to ' + next + ' theme');
        });
    }

    function applyTheme(theme, animate) {
        if (animate && !reduceMotion) {
            root.classList.add('theme-anim');
            window.setTimeout(function () { root.classList.remove('theme-anim'); }, 350);
        }
        root.setAttribute('data-bs-theme', theme === 'dark' ? 'dark' : 'light');
        syncThemeButtons();
    }

    function setTheme(theme) {
        applyTheme(theme, true);
        try { localStorage.setItem(STORAGE_KEY, getTheme()); } catch (e) { /* storage blocked */ }
    }

    function initTheme() {
        syncThemeButtons();
        doc.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-theme-toggle]');
            if (btn) setTheme(getTheme() === 'dark' ? 'light' : 'dark');
        });
        // Follow the OS setting until the user picks a theme explicitly.
        if (window.matchMedia) {
            var mq = window.matchMedia('(prefers-color-scheme: dark)');
            var onChange = function (ev) {
                var saved = storedTheme();
                if (saved !== 'light' && saved !== 'dark') applyTheme(ev.matches ? 'dark' : 'light', true);
            };
            if (mq.addEventListener) mq.addEventListener('change', onChange);
            else if (mq.addListener) mq.addListener(onChange);
        }
    }

    /* ---------- Toasts --------------------------------------------------- */

    var TOAST_ICONS = { success: 'bi-check-circle', error: 'bi-exclamation-circle', info: 'bi-info-circle' };

    function toastStack() {
        var stack = doc.getElementById('toast-stack');
        if (!stack) {
            stack = doc.createElement('div');
            stack.id = 'toast-stack';
            stack.className = 'toast-stack';
            doc.body.appendChild(stack);
        }
        return stack;
    }

    function showToastEl(el, delay) {
        if (!window.bootstrap || !window.bootstrap.Toast) { el.classList.add('show'); return; }
        var t = window.bootstrap.Toast.getOrCreateInstance(el, delay ? { delay: delay } : undefined);
        t.show();
    }

    function showToast(message, type, options) {
        type = TOAST_ICONS[type] ? type : 'success';
        options = options || {};
        var el = doc.createElement('div');
        el.className = 'toast app-toast fade';
        el.setAttribute('data-type', type);
        el.setAttribute('role', type === 'error' ? 'alert' : 'status');
        el.setAttribute('aria-live', type === 'error' ? 'assertive' : 'polite');
        el.setAttribute('aria-atomic', 'true');

        var inner = doc.createElement('div');
        inner.className = 'app-toast__inner';
        var icon = doc.createElement('i');
        icon.className = 'bi ' + TOAST_ICONS[type] + ' app-toast__icon';
        icon.setAttribute('aria-hidden', 'true');
        var text = doc.createElement('div');
        text.className = 'app-toast__text';
        text.textContent = String(message);
        var close = doc.createElement('button');
        close.type = 'button';
        close.className = 'btn-close';
        close.setAttribute('data-bs-dismiss', 'toast');
        close.setAttribute('aria-label', 'Close');
        inner.appendChild(icon);
        inner.appendChild(text);
        inner.appendChild(close);
        el.appendChild(inner);

        toastStack().appendChild(el);
        el.addEventListener('hidden.bs.toast', function () { el.remove(); });
        showToastEl(el, options.delay || (type === 'error' ? 8000 : 5000));
        return el;
    }

    function initToasts(scope) {
        scope.querySelectorAll('.toast[data-autoshow]').forEach(function (el) {
            el.removeAttribute('data-autoshow');
            showToastEl(el);
        });
    }

    /* ---------- Fetch helper -------------------------------------------- */

    function csrfToken() {
        var meta = doc.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function fetchJson(url, opts) {
        opts = opts || {};
        var headers = Object.assign({
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken()
        }, opts.headers || {});
        var body = opts.body;
        if (body && !(body instanceof FormData) && typeof body !== 'string') {
            headers['Content-Type'] = 'application/json';
            body = JSON.stringify(body);
        }
        return fetch(url, {
            method: (opts.method || 'POST').toUpperCase(),
            headers: headers,
            body: body,
            credentials: 'same-origin'
        }).then(function (res) {
            return res.text().then(function (txt) {
                var data = null;
                try { data = txt ? JSON.parse(txt) : null; } catch (e) { data = { message: txt }; }
                return { ok: res.ok, status: res.status, data: data };
            });
        });
    }

    /* ---------- Status dot ---------------------------------------------- */

    function setStatus(el, status, label) {
        if (!el) return;
        el.setAttribute('data-status', status);
        if (label != null) el.textContent = label;
    }

    /* ---------- Reveal on scroll + count-up ----------------------------- */

    var revealObserver = null;
    var countObserver = null;

    function revealNow(el) { el.classList.add('is-revealed'); }

    function initReveal(scope) {
        var items = Array.prototype.slice.call(scope.querySelectorAll('[data-reveal]:not(.is-revealed)'));
        if (!items.length) return;
        if (reduceMotion || !('IntersectionObserver' in window)) { items.forEach(revealNow); return; }
        if (!revealObserver) {
            revealObserver = new IntersectionObserver(function (entries) {
                var visible = entries.filter(function (en) { return en.isIntersecting; })
                    .map(function (en) { return en.target; })
                    .sort(function (a, b) {
                        return a.compareDocumentPosition(b) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1;
                    });
                visible.forEach(function (el, i) {
                    el.style.setProperty('--reveal-delay', Math.min(i, 10) * 40 + 'ms');
                    revealNow(el);
                    revealObserver.unobserve(el);
                });
            }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
        }
        items.forEach(function (el) { revealObserver.observe(el); });
    }

    function formatNumber(n) {
        try { return n.toLocaleString('en-US'); } catch (e) { return String(n); }
    }

    function runCount(el) {
        var target = parseFloat(el.getAttribute('data-count-to'));
        if (!isFinite(target)) return;
        var decimals = (String(el.getAttribute('data-count-to')).split('.')[1] || '').length;
        var duration = Math.min(1600, 700 + target * 15);
        var start = null;
        function frame(ts) {
            if (start === null) start = ts;
            var p = Math.min(1, (ts - start) / duration);
            var eased = 1 - Math.pow(1 - p, 3);
            var val = target * eased;
            el.textContent = formatNumber(decimals ? Number(val.toFixed(decimals)) : Math.round(val));
            if (p < 1) window.requestAnimationFrame(frame);
        }
        window.requestAnimationFrame(frame);
    }

    function initCountUp(scope) {
        var items = Array.prototype.slice.call(scope.querySelectorAll('[data-count-to]:not([data-counted])'));
        if (!items.length || reduceMotion || !('IntersectionObserver' in window)) return;
        if (!countObserver) {
            countObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (en) {
                    if (!en.isIntersecting) return;
                    countObserver.unobserve(en.target);
                    runCount(en.target);
                });
            }, { threshold: 0.5 });
        }
        items.forEach(function (el) {
            el.setAttribute('data-counted', '');
            el.textContent = '0';
            countObserver.observe(el);
        });
    }

    /* ---------- Forms ---------------------------------------------------- */

    function initPasswordToggles(scope) {
        scope.querySelectorAll('[data-password-toggle]:not([data-bound])').forEach(function (btn) {
            btn.setAttribute('data-bound', '');
            var id = btn.getAttribute('aria-controls');
            var input = id ? doc.getElementById(id) : null;
            if (!input && btn.parentElement) input = btn.parentElement.querySelector('input');
            if (!input) return;
            btn.setAttribute('aria-pressed', 'false');
            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.setAttribute('aria-pressed', show ? 'true' : 'false');
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                var icon = btn.querySelector('.bi');
                if (icon) {
                    icon.classList.toggle('bi-eye', !show);
                    icon.classList.toggle('bi-eye-slash', show);
                }
            });
        });
    }

    function initPreviews(scope) {
        scope.querySelectorAll('input[type="file"][data-preview]:not([data-bound])').forEach(function (input) {
            input.setAttribute('data-bound', '');
            input.addEventListener('change', function () {
                var img = doc.querySelector(input.getAttribute('data-preview'));
                var file = input.files && input.files[0];
                if (!img || !file || !/^image\//.test(file.type)) return;
                if (img.dataset.objectUrl) URL.revokeObjectURL(img.dataset.objectUrl);
                img.dataset.objectUrl = URL.createObjectURL(file);
                img.src = img.dataset.objectUrl;
                img.hidden = false;
                img.classList.remove('d-none');
            });
        });
    }

    function initGlobalFormHooks() {
        doc.addEventListener('submit', function (e) {
            var form = e.target;
            var submitter = e.submitter;
            var msg = (submitter && submitter.getAttribute('data-confirm')) || form.getAttribute('data-confirm');
            if (msg && !window.confirm(msg)) e.preventDefault();
        });
        doc.addEventListener('change', function (e) {
            var el = e.target;
            var form = el.form;
            if (!form || !form.hasAttribute('data-autosubmit')) return;
            if (el.matches('select, input[type="radio"], input[type="checkbox"]')) {
                if (form.requestSubmit) form.requestSubmit(); else form.submit();
            }
        });
    }

    /* ---------- Boot ----------------------------------------------------- */

    function init(scope) {
        scope = scope || doc;
        initToasts(scope);
        initReveal(scope);
        initCountUp(scope);
        initPasswordToggles(scope);
        initPreviews(scope);
    }

    window.PetShelter = {
        showToast: showToast,
        fetchJson: fetchJson,
        csrfToken: csrfToken,
        setStatus: setStatus,
        getTheme: getTheme,
        setTheme: setTheme,
        init: init
    };
    window.showToast = showToast;

    function boot() {
        initTheme();
        initGlobalFormHooks();
        init(doc);
    }

    if (doc.readyState === 'loading') doc.addEventListener('DOMContentLoaded', boot);
    else boot();
})();
