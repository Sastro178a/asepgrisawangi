/* tampilan-web.js — nama web & logo dinamis (diatur dari Admin > Pengaturan Tampilan Web)
 * Pasang di <head> setiap halaman:  <script src="/tampilan-web.js"></script>
 * Opsi: data-observe="0" untuk mematikan pemantauan elemen dinamis (dipakai di admin yang berat).
 * Key DB (tabel pengaturan): nama_web, link_logo
 */
(function () {
    'use strict';
    var ME = document.currentScript;
    var DEFAULT_NAME = 'E-Aspirasi Grisawangi';
    var FALLBACK_LOGO = 'https://iili.io/qfChwml.png';
    var CK = 'ea_tampilan_v1';
    var cfg = { nama: '', logo: '' };

    try { var c = JSON.parse(localStorage.getItem(CK) || '{}'); cfg.nama = c.nama || ''; cfg.logo = c.logo || ''; } catch (e) {}
    window.NAMA_WEB = cfg.nama || DEFAULT_NAME;   // dipakai script halaman (PDF, pesan WA, dll)
    window.LOGO_WEB = cfg.logo || '';

    // "E-Aspirasi" / "E-Aspirasi Grisawangi" (bukan bagian domain/email: e-aspirasigrisawangi.my.id, x@e-aspirasi.id)
    var RE = /(^|[^@\/.\w-])E-Aspirasi(?:\s+Grisawangi)?(?!\w)/gi;
    var LOGO_RE = /^(\/?icon\.png(\?.*)?|https:\/\/iili\.io\/qfChwml\.png)$/;
    var SKIP = { SCRIPT: 1, STYLE: 1, NOSCRIPT: 1, TEMPLATE: 1, TEXTAREA: 1 };
    var txtMap = new WeakMap(), attrMap = new WeakMap();
    var origTitle = null;

    function sub(str) {
        if (!cfg.nama) return str;
        return str.replace(RE, function (m, p) { return p + cfg.nama; });
    }

    function doText(n) {
        var cur = n.nodeValue, rec = txtMap.get(n);
        if (!rec && !/aspirasi/i.test(cur)) return;
        var orig = (rec && rec.applied === cur) ? rec.orig : cur;
        var out = sub(orig);
        if (out !== cur) n.nodeValue = out;
        txtMap.set(n, { orig: orig, applied: out });
    }

    function doAttrs(el) {
        ['placeholder', 'alt', 'title'].forEach(function (a) {
            var v = el.getAttribute(a); if (v == null) return;
            var m = attrMap.get(el) || {}, rec = m[a];
            if (!rec && !/aspirasi/i.test(v)) return;
            var orig = (rec && rec.applied === v) ? rec.orig : v;
            var out = sub(orig);
            if (out !== v) el.setAttribute(a, out);
            m[a] = { orig: orig, applied: out }; attrMap.set(el, m);
        });
    }

    function doLogos(root) {
        var imgs = [];
        if (root.tagName === 'IMG') imgs.push(root);
        if (root.querySelectorAll) imgs = imgs.concat([].slice.call(root.querySelectorAll('img')));
        imgs.forEach(function (img) {
            var orig = img.getAttribute('data-logo-orig');
            if (orig === null) {
                var s = img.getAttribute('src') || '';
                if (!LOGO_RE.test(s)) return;
                orig = s; img.setAttribute('data-logo-orig', orig);
            }
            var target = cfg.logo || orig;
            if (img.getAttribute('src') === target) return;
            img.onerror = function () { img.onerror = null; img.src = (target !== orig) ? orig : FALLBACK_LOGO; };
            img.setAttribute('src', target);
        });
    }

    function walk(root) {
        if (root.nodeType === 3) { if (!(root.parentNode && SKIP[root.parentNode.tagName])) doText(root); return; }
        if (root.nodeType !== 1 || SKIP[root.tagName]) return;
        var tw = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
            acceptNode: function (n) { var p = n.parentNode; return (p && SKIP[p.tagName]) ? NodeFilter.FILTER_REJECT : NodeFilter.FILTER_ACCEPT; }
        });
        var list = [], n; while ((n = tw.nextNode())) list.push(n);
        list.forEach(doText);
        doAttrs(root);
        [].forEach.call(root.querySelectorAll('[placeholder],[alt],[title]'), doAttrs);
        doLogos(root);
    }

    function applyHead() {
        if (origTitle === null) origTitle = document.title;
        document.title = sub(origTitle);
        [].forEach.call(document.querySelectorAll('link[rel~="icon"], link[rel="apple-touch-icon"]'), function (l) {
            if (!l.hasAttribute('data-orig')) l.setAttribute('data-orig', l.getAttribute('href') || '');
            var t = cfg.logo || l.getAttribute('data-orig'); if (t) l.setAttribute('href', t);
        });
        var am = document.querySelector('meta[name="apple-mobile-web-app-title"]');
        if (am) { if (!am.hasAttribute('data-orig')) am.setAttribute('data-orig', am.getAttribute('content') || ''); am.setAttribute('content', cfg.nama || am.getAttribute('data-orig')); }
    }

    function applyDoc() { applyHead(); if (document.body) walk(document.body); }

    function fetchKey(k) {
        return fetch('/api/notify', { method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'supabase_get', action: 'getPengaturanByKey', payload: { key: k } }) })
            .then(function (r) { return r.json(); })
            .then(function (j) { return String((j && j.data && j.data[0] && j.data[0].nilai) || '').trim(); });
    }

    function refresh() {
        return Promise.all([fetchKey('nama_web'), fetchKey('link_logo')]).then(function (v) {
            var n = v[0], l = /^https?:\/\//i.test(v[1]) ? v[1] : '';
            if (n !== cfg.nama || l !== cfg.logo) {
                cfg.nama = n; cfg.logo = l; window.NAMA_WEB = n || DEFAULT_NAME; window.LOGO_WEB = l;
                try { localStorage.setItem(CK, JSON.stringify({ nama: n, logo: l })); } catch (e) {}
                applyDoc();
                try { window.dispatchEvent(new Event('tampilanweb:update')); } catch (e) {}
            }
        }).catch(function () { /* offline: pakai cache */ });
    }

    window.TampilanWeb = { refresh: refresh, apply: applyDoc };

    function start() {
        applyDoc();
        if (!ME || ME.getAttribute('data-observe') !== '0') {
            var q = [], sched = false;
            new MutationObserver(function (ms) {
                ms.forEach(function (m) { for (var i = 0; i < m.addedNodes.length; i++) q.push(m.addedNodes[i]); });
                if (sched) return; sched = true;
                requestAnimationFrame(function () {
                    sched = false; var x = q; q = [];
                    x.forEach(function (n) { if (n.isConnected !== false) { walk(n); } });
                });
            }).observe(document.body, { childList: true, subtree: true });
        }
        refresh();
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
})();
