/* =====================================================================
 * grisVA.js — GrisVA, asisten virtual E-Aspirasi Grisawangi (TANPA AI, 100% template)
 * Pasang di setiap halaman:  <script src="/grisVA.js" defer></script>
 *
 * Cara kerja:
 *  - Mulai sebagai TAMU (info umum saja).
 *  - Tombol "Ubah mode" -> pilih portal (Siswa, Orang Tua, Guru, OSIS/Sekretaris, Admin)
 *    -> GrisVA minta verifikasi login -> menu muncul sesuai role & izin jabatan.
 *  - Setiap aksi = alur tanya-jawab (wizard) yang memanggil action /api/notify yang SUDAH ADA.
 *  - Menambah aksi baru: cukup tambah satu blok ACT({...}) di bagian KATALOG AKSI.
 *  - KEAMANAN: login diverifikasi SERVER (notify.js, type:'grisva'); semua panggilan membawa token
 *    bertanda tangan dan dicek role+izin di server. Butuh env Netlify GRISVA_SECRET.
 *  - Halaman penuh: set window.GRISVA_PAGE=true sebelum memuat file ini (lihat grisva.html).
 * ===================================================================== */
(function () {
'use strict';
if (window.GrisVA) return;

const CFG = {
  api : '{{ env('API') }}',
  supaUrl : '{{ env('SUPA_URL') }}',
  supaKey : '{{ env('SUPA_KEY') }}',
  idleMs: 10 * 60 * 1000,   // sesi habis setelah 10 menit tanpa aktivitas
  kunciMs: 5 * 60 * 1000,   // kunci login setelah terlalu banyak gagal
  batasGagal: 5
};

/* ---------------------------------------------------------------- util */
const esc = v => String(v == null ? '' : v).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
const enc = encodeURIComponent;
const $b = v => '<b>' + esc(v) + '</b>';
const arr = v => { try { const x = typeof v === 'string' ? JSON.parse(v || '[]') : (v || []); return Array.isArray(x) ? x : []; } catch (e) { return []; } };
const wib = () => { const w = new Date(Date.now() + 7 * 3600e3); return { tgl: w.toISOString().slice(0, 10), hm: w.toISOString().slice(11, 16), jam: w.getUTCHours() + w.getUTCMinutes() / 60, dow: w.getUTCDay() }; };
const tglID = s => { const m = String(s || '').match(/^(\d{4})-(\d{2})-(\d{2})/); return m ? `${m[3]}/${m[2]}/${m[1]}` : (s || '-'); };
const norm = s => String(s || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9 ]/g, ' ');
const HR = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
function ymd(t) { // normalisasi tanggal: 2008-08-17 | 17/08/2008 | 17-Aug-2008
  const s = String(t || '').trim().toLowerCase(); if (!s) return '';
  const bln = { jan: 1, feb: 2, mar: 3, apr: 4, may: 5, mei: 5, jun: 6, jul: 7, aug: 8, agu: 8, agt: 8, sep: 9, oct: 10, okt: 10, nov: 11, dec: 12, des: 12 };
  const f = (y, m, d) => `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`; let m;
  if ((m = s.match(/^(\d{4})[-\/.](\d{1,2})[-\/.](\d{1,2})/))) return f(m[1], m[2], m[3]);
  if ((m = s.match(/^(\d{1,2})[-\/.](\d{1,2})[-\/.](\d{4})/))) return f(m[3], m[2], m[1]);
  if ((m = s.match(/^(\d{1,2})[-\/. ]([a-z]{3})[a-z]*[-\/. ](\d{4})/)) && bln[m[2]]) return f(m[3], bln[m[2]], m[1]);
  return '';
}
function jarak(a, b, c, d) { const R = 6371000, r = Math.PI / 180, x = (c - a) * r, y = (d - b) * r, k = Math.sin(x / 2) ** 2 + Math.cos(a * r) * Math.cos(c * r) * Math.sin(y / 2) ** 2; return R * 2 * Math.atan2(Math.sqrt(k), Math.sqrt(1 - k)); }
function h(t, a, ...k) {
  const e = document.createElement(t);
  for (const x in (a || {})) { if (x === 'class') e.className = a[x]; else if (x === 'html') e.innerHTML = a[x]; else if (x.startsWith('on')) e.addEventListener(x.slice(2), a[x]); else e.setAttribute(x, a[x]); }
  k.flat().forEach(c => { if (c != null) e.append(c.nodeType ? c : document.createTextNode(c)); });
  return e;
}
function kompres(file, maxW = 900, q = 0.72) {
  return new Promise((res, rej) => {
    const fr = new FileReader(); fr.onerror = () => rej(new Error('File tidak bisa dibaca.'));
    fr.onload = () => { const im = new Image(); im.onerror = () => rej(new Error('File bukan gambar yang valid.'));
      im.onload = () => { let w = im.width, hh = im.height; if (w > maxW) { hh = Math.round(hh * maxW / w); w = maxW; }
        const c = document.createElement('canvas'); c.width = w; c.height = hh; const x = c.getContext('2d'); x.fillStyle = '#fff'; x.fillRect(0, 0, w, hh); x.drawImage(im, 0, 0, w, hh); res(c.toDataURL('image/jpeg', q)); };
      im.src = fr.result; };
    fr.readAsDataURL(file);
  });
}

/* ------------------------------------------------------------ API */
async function api(p) {
  const r = await fetch(CFG.api, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(p) });
  const t = await r.text(); let j = {}; try { j = JSON.parse(t); } catch (e) {}
  if (!r.ok || j.error) throw new Error(j.error || ('Server ' + r.status));
  return j;
}
// tanpa token (pencarian nama untuk login, data publik)
const auth = (action, payload) => api({ type: 'grisva', action, payload }).then(r => r.data);
const pub = what => auth('publik', { what });
// dengan token sesi: server mengecek role & izin sebelum meneruskan
async function gw(call) {
  if (!S.sess) throw new Error('Belum login.');
  try { return await api({ type: 'grisva', action: 'call', token: S.sess.token, call }); }
  catch (e) { if (/^SESI:/.test(e.message)) { sesiHabis(); throw new End(''); } throw e; }
}
const G = (action, payload) => gw({ type: 'supabase_get', action, payload });
const P = (action, payload) => gw({ type: 'supabase_post', action, payload });
const Q = async path => (await G('getData', { table: path })).data || [];
const up = async dataUrl => { const r = await gw({ type: 'upload', source: dataUrl }); if (!r.url) throw new Error('Foto gagal diunggah.'); return r.url; };
const tele = t => gw({ type: 'notification', text: t }).catch(() => {});

/* ----------------------------------------------------------- state */
const MODES = {
  tamu:    { t: 'Tamu', i: '👤' },
  siswa:   { t: 'Siswa', i: '🎓', role: 'Siswa' },
  ortu:    { t: 'Orang Tua', i: '👨‍👩‍👧', role: 'Orang Tua' },
  guru:    { t: 'Guru', i: '👩‍🏫', role: 'Guru' },
  elpoint: { t: 'OSIS / Sekretaris', i: '⭐', role: 'OSIS El Point' },
  admin:   { t: 'Admin', i: '🛡️', role: 'Admin' }
};
const S = { open: false, mode: 'tamu', sess: null, pend: null, fid: 0, t: Date.now(), greeted: false };
const SK = 'grisva_s';
let C = {};              // cache sementara (hilang saat keluar)
const el = {};
const CANCEL = { cancel: true };
class End { constructor(m) { this.m = m; } }
class Fail { constructor(m, lock) { this.m = m; this.lock = !!lock; } }

const U = () => (S.sess && S.sess.user) || {};
const NAMA = () => { const u = U(); return u.nama_panggilan || u.nama || u.nama_lengkap || u.nama_anggota || 'Kak'; };
const FULL = () => { const u = U(); return u.nama || u.nama_lengkap || u.nama_anggota || NAMA(); };
const isSuper = () => !!S.sess && S.sess.mode === 'admin' && (U().role_name === 'Super Admin' || S.sess.akses.includes('Super Admin'));
const bisaAksi = k => { if (!S.sess || S.sess.mode !== 'admin') return false; if (isSuper()) return true; const a = S.sess.akses; return !a.includes('aksi_v2') || a.includes(k); };
const punyaMenu = ids => isSuper() || [].concat(ids).some(i => S.sess.akses.includes(i));
const pinAda = s => !!(s && (s.punya_pin || (s.login_pin && String(s.login_pin).trim())));
function pageMode() { const p = location.pathname.replace(/\.html$/, '').replace(/\/+$/, ''); return { '/siswa': 'siswa', '/manualabs': 'siswa', '/penilaian': 'siswa', '/admin': 'admin', '/portal-guru': 'guru', '/elakses': 'ortu', '/elpoint': 'elpoint' }[p] || null; }

/* kunci login (pembatas percobaan salah, sisi browser) */
const kk = m => 'grisva_kunci_' + m;
function kunciSisa(m) { try { const v = JSON.parse(localStorage.getItem(kk(m)) || 'null'); if (!v || v.n < CFG.batasGagal) return 0; const s = CFG.kunciMs - (Date.now() - v.t); return s > 0 ? Math.ceil(s / 60000) : 0; } catch (e) { return 0; } }
function strike(m, penuh) { try { let v = JSON.parse(localStorage.getItem(kk(m)) || 'null'); if (!v || Date.now() - v.t > CFG.kunciMs) v = { n: 0, t: Date.now() }; v.n = penuh ? CFG.batasGagal : v.n + 1; v.t = Date.now(); localStorage.setItem(kk(m), JSON.stringify(v)); } catch (e) {} }
const strikeReset = m => { try { localStorage.removeItem(kk(m)); } catch (e) {} };

/* --------------------------------------------------------- template teks */
const T = {
  noPerm: j => `🚫 Mode/jabatan kamu tidak punya izin untuk ${$b(j)}. Hubungi Super Admin kalau perlu akses.`,
  batal: 'Oke, dibatalkan.',
  gagal: m => `😕 Gagal: ${esc(m)}`,
  bingung: 'Hmm, aku belum paham maksudnya. Coba pilih salah satu menu di bawah ya.',
  lagi: 'Ada lagi yang bisa dibantu?',
  kunci: n => `🔒 Terlalu banyak percobaan salah. Coba lagi ${n} menit lagi.`,
  masuk: n => `✅ Verifikasi berhasil. Selamat datang, ${$b(n)}! Mau ngapain hari ini?`,
  habis: '⏱️ Sesi berakhir karena tidak ada aktivitas. Login lagi kalau mau lanjut ya.'
};

/* ================================================================ UI */
const CSS = `
.gva-fab,.gva-panel,.gva-panel *{box-sizing:border-box}
.gva-fab{position:fixed;right:18px;bottom:88px;z-index:9990;width:56px;height:56px;border-radius:50%;border:0;cursor:pointer;color:#fff;font-size:24px;background:linear-gradient(135deg,#2563eb,#1e3a8a);box-shadow:0 10px 24px rgba(30,58,138,.45);display:flex;align-items:center;justify-content:center;transition:transform .15s}
.gva-fab:hover{transform:scale(1.07)}.gva-fab:active{transform:scale(.95)}
.gva-panel{position:fixed;right:18px;bottom:88px;z-index:9991;width:380px;max-width:calc(100vw - 24px);height:600px;max-height:calc(100vh - 108px);display:none;flex-direction:column;background:#f8fafc;color:#1f2937;border-radius:22px;overflow:hidden;box-shadow:0 24px 60px rgba(15,23,42,.35);font-family:'Poppins',system-ui,sans-serif;font-size:13px;line-height:1.5}
.gva-panel.gva-open{display:flex;animation:gvaIn .22s ease-out}
@keyframes gvaIn{from{opacity:0;transform:translateY(12px) scale(.98)}to{opacity:1;transform:none}}
.gva-head{display:flex;align-items:center;gap:10px;padding:12px 12px 12px 14px;background:linear-gradient(135deg,#1d4ed8,#1e3a8a);color:#fff;flex-shrink:0}
.gva-av{width:38px;height:38px;border-radius:12px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:17px;flex-shrink:0}
.gva-ht{font-weight:800;font-size:15px;line-height:1.1}.gva-hs{font-size:11px;opacity:.85;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px}
.gva-hb{width:32px;height:32px;border:0;border-radius:10px;background:rgba(255,255,255,.14);color:#fff;cursor:pointer;font-size:14px}.gva-hb:hover{background:rgba(255,255,255,.28)}
.gva-log{flex:1;overflow-y:auto;padding:14px 12px;display:flex;flex-direction:column;gap:8px;scroll-behavior:smooth}
.gva-m{max-width:88%;padding:9px 12px;border-radius:16px;word-wrap:break-word;overflow-wrap:anywhere}
.gva-b{background:#fff;border:1px solid #e5e7eb;border-top-left-radius:5px;align-self:flex-start}
.gva-b a{color:#2563eb;font-weight:700}.gva-b img{max-width:100%;border-radius:10px;margin-top:6px}
.gva-u{background:#2563eb;color:#fff;border-top-right-radius:5px;align-self:flex-end}
.gva-t{color:#6b7280;font-style:italic}
.gva-menu{display:flex;flex-direction:column;gap:6px;align-self:stretch}
.gva-gt{font-size:11px;font-weight:700;color:#6b7280;margin-top:4px}
.gva-ch,.gva-opts{display:flex;flex-wrap:wrap;gap:6px;align-self:stretch}
.gva-c{border:1.5px solid #bfdbfe;background:#eff6ff;color:#1d4ed8;border-radius:999px;padding:6px 12px;font:inherit;font-weight:600;font-size:12px;cursor:pointer;text-align:left}
.gva-c:hover{background:#dbeafe}.gva-alt{background:#fff;border-color:#d1d5db;color:#4b5563}
.gva-dock{flex-shrink:0;padding:10px;background:#fff;border-top:1px solid #e5e7eb;max-height:46%;overflow-y:auto;display:flex;flex-direction:column;gap:8px}
.gva-row{display:flex;gap:6px;align-items:center}.gva-row.end{justify-content:flex-end}
.gva-in{flex:1;min-width:0;border:2px solid #e5e7eb;border-radius:12px;padding:9px 12px;font:inherit;background:#fff;color:#111827;outline:none;resize:none}
.gva-in:focus{border-color:#2563eb}
.gva-send,.gva-ok{border:0;border-radius:12px;background:#2563eb;color:#fff;font:inherit;font-weight:700;padding:9px 14px;cursor:pointer}.gva-send:hover,.gva-ok:hover{background:#1d4ed8}
.gva-ok.gva-green{background:#16a34a}
.gva-bt{border:0;background:transparent;color:#9ca3af;font:inherit;font-weight:600;cursor:pointer;padding:6px 8px}.gva-bt:hover{color:#dc2626}
.gva-list{display:flex;flex-direction:column;gap:4px;max-height:190px;overflow-y:auto}
.gva-li{display:flex;flex-direction:column;align-items:flex-start;text-align:left;border:1px solid #e5e7eb;background:#fff;border-radius:10px;padding:7px 10px;font:inherit;cursor:pointer;color:#111827}
.gva-li:hover{background:#eff6ff;border-color:#93c5fd}.gva-li small{color:#6b7280;font-size:11px}
.gva-empty,.gva-load{color:#6b7280;font-size:12px;padding:6px 4px}
.gva-chips{display:flex;flex-wrap:wrap;gap:5px}
.gva-chip{background:#dbeafe;color:#1e40af;border-radius:999px;padding:3px 6px 3px 10px;font-size:12px;font-weight:600;display:flex;gap:6px;align-items:center}
.gva-chip button{border:0;background:transparent;color:#1e40af;cursor:pointer;font-weight:800}
.gva-prev{max-height:130px;border-radius:10px;align-self:center}.gva-hide{display:none}
.gva-panel.gva-page{right:auto;left:50%;transform:translateX(-50%);top:16px;bottom:16px;width:min(560px,calc(100vw - 24px));max-width:none;height:auto;max-height:none;animation:none}
@media(max-width:480px){.gva-panel.gva-page{left:0;right:0;top:0;bottom:0;width:100%;transform:none;border-radius:0}}
@media(max-width:480px){.gva-panel{right:0;left:0;bottom:0;width:100%;max-width:100%;height:88vh;max-height:88vh;border-radius:22px 22px 0 0}.gva-dock{padding-bottom:max(10px,env(safe-area-inset-bottom))}}
body.dark-mode .gva-panel{background:#0f172a;color:#e2e8f0}
body.dark-mode .gva-b{background:#1e293b;border-color:#334155;color:#e2e8f0}
body.dark-mode .gva-dock{background:#1e293b;border-color:#334155}
body.dark-mode .gva-in,body.dark-mode .gva-li{background:#0f172a;border-color:#334155;color:#f1f5f9}
body.dark-mode .gva-c{background:#1e3a8a;border-color:#1d4ed8;color:#dbeafe}body.dark-mode .gva-alt{background:#1e293b;border-color:#475569;color:#cbd5e1}
`;

function scroll() { el.log.scrollTop = el.log.scrollHeight; }
function bubble(cls, html) { const b = h('div', { class: 'gva-m ' + cls, html }); el.log.append(b); scroll(); return b; }
const bot = html => bubble('gva-b', html);
const me = txt => bubble('gva-u', esc(txt));
function chipsBox(items) {
  const w = h('div', { class: 'gva-ch' });
  items.forEach(it => w.append(h('button', { class: 'gva-c' + (it.alt ? ' gva-alt' : ''), onclick: it.fn }, it.t)));
  el.log.append(w); scroll(); return w;
}
let busyEl = null;
const busyOn = () => { if (!busyEl) busyEl = bubble('gva-b gva-t', '⏳ Sebentar ya, lagi diproses…'); };
const busyOff = () => { if (busyEl) { busyEl.remove(); busyEl = null; } };

function header() {
  const m = MODES[S.mode];
  el.sub.textContent = m.i + ' ' + m.t + (S.sess ? ' • ' + NAMA() : (S.mode === 'tamu' ? '' : ' (belum login)'));
  el.btnOut.style.display = S.sess ? '' : 'none';
}
function idleDock() {
  el.dock.innerHTML = '';
  const inp = h('input', { class: 'gva-in', placeholder: 'Ketik perintah, mis. "blokir", "poin", "absen"…', autocomplete: 'off' });
  const go = () => { const q = inp.value.trim(); if (!q) return; inp.value = ''; me(q); cari(q); };
  inp.addEventListener('keydown', e => { if (e.key === 'Enter') go(); });
  el.dock.append(h('div', { class: 'gva-row' }, inp, h('button', { class: 'gva-send', onclick: go }, '➤')));
}
function cancelPending() { if (S.pend) { const p = S.pend; S.pend = null; p.reject(CANCEL); } }

/* ---------- widget input per langkah ---------- */
async function widget(st, d, submit) {
  const dk = el.dock; dk.innerHTML = '';
  const batal = h('button', { class: 'gva-bt', onclick: () => cancelPending() }, 'Batal');
  const t = st.tipe;
  const skip = st.opsional ? h('button', { class: 'gva-bt', onclick: () => submit('', '(dilewati)') }, 'Lewati') : null;

  if (t === 'choice') {
    dk.append(h('div', { class: 'gva-load' }, 'Memuat…'));
    let o; try { o = typeof st.opsi === 'function' ? await st.opsi(d) : st.opsi; } catch (e) { if (S.pend) S.pend.reject(e); return; }
    if (!o || !o.length) { if (S.pend) S.pend.reject(new End(st.kosong || 'Tidak ada pilihan tersedia.')); return; }
    dk.innerHTML = '';
    const box = h('div', { class: 'gva-opts' });
    o.map(x => typeof x === 'string' ? { v: x, t: x } : x).forEach(x => box.append(h('button', { class: 'gva-c', onclick: () => submit(x.v, x.t) }, x.t)));
    dk.append(box, h('div', { class: 'gva-row end' }, batal)); return;
  }
  if (['text', 'textarea', 'number', 'date', 'password', 'pin', 'tel'].includes(t)) {
    const sec = t === 'password' || t === 'pin';
    const inp = t === 'textarea' ? h('textarea', { class: 'gva-in', rows: 3 }) : h('input', { class: 'gva-in', type: sec ? 'password' : t, autocomplete: 'off' });
    if (t === 'pin') { inp.setAttribute('inputmode', 'numeric'); inp.maxLength = 4; }
    if (st.ph) inp.placeholder = st.ph;
    const dv = typeof st.def === 'function' ? st.def(d) : st.def; if (dv != null) inp.value = dv;
    const go = () => {
      const v = inp.value.trim();
      if (!v && !st.opsional) { inp.focus(); return; }
      if (v && st.min && v.length < st.min) { bot(`⚠️ Minimal ${st.min} karakter.`); return; }
      if (t === 'pin' && !/^\d{4}$/.test(v)) { bot('⚠️ PIN harus 4 digit angka.'); return; }
      submit(v, sec ? '••••' : (t === 'date' ? tglID(v) : (v || '(dilewati)')));
    };
    inp.addEventListener('keydown', e => { if (e.key === 'Enter' && (t !== 'textarea' || e.ctrlKey)) { e.preventDefault(); go(); } });
    dk.append(h('div', { class: 'gva-row' }, inp, h('button', { class: 'gva-send', onclick: go }, 'Kirim')), h('div', { class: 'gva-row end' }, skip, batal));
    setTimeout(() => inp.focus(), 50); return;
  }
  if (t === 'search') {
    const picked = []; let tm, seq = 0;
    const list = h('div', { class: 'gva-list' }), chips = h('div', { class: 'gva-chips' });
    const inp = h('input', { class: 'gva-in', placeholder: st.ph || 'Ketik untuk mencari…', autocomplete: 'off' });
    const same = (a, b) => (a.v && b.v && a.v.id != null) ? a.v.id === b.v.id : a.t === b.t;
    const selesai = h('button', { class: 'gva-ok gva-green' }, 'Selesai memilih'); selesai.style.display = 'none';
    const redraw = () => {
      chips.innerHTML = ''; picked.forEach((p, i) => chips.append(h('span', { class: 'gva-chip' }, p.t, h('button', { onclick: () => { picked.splice(i, 1); redraw(); } }, '✕'))));
      selesai.style.display = picked.length ? '' : 'none'; selesai.textContent = `Selesai (${picked.length})`;
    };
    selesai.addEventListener('click', () => { if (picked.length) submit(picked.map(p => p.v), picked.map(p => p.t).join(', ')); });
    const pilih = it => { if (!st.multi) return submit(it.v, it.t); if (!picked.some(p => same(p, it))) picked.push(it); inp.value = ''; list.innerHTML = ''; redraw(); inp.focus(); };
    const draw = items => { list.innerHTML = ''; if (!items.length) { list.append(h('div', { class: 'gva-empty' }, 'Tidak ditemukan.')); return; }
      items.forEach(it => list.append(h('button', { class: 'gva-li', onclick: () => pilih(it) }, h('b', {}, it.t), it.s ? h('small', {}, it.s) : null))); };
    const load = async q => { const n = ++seq; list.innerHTML = '<div class="gva-empty">Mencari…</div>';
      try { const r = await st.cari(q, d); if (n === seq) draw(r); } catch (e) { if (n === seq) list.innerHTML = '<div class="gva-empty">' + esc(e.message) + '</div>'; } };
    const min = st.awal ? 0 : 2;
    inp.addEventListener('input', () => { clearTimeout(tm); const q = inp.value.trim(); if (q.length < min) { list.innerHTML = ''; return; } tm = setTimeout(() => load(q), 350); });
    dk.append(inp, list, chips, h('div', { class: 'gva-row end' }, selesai, batal));
    if (st.awal) load(''); setTimeout(() => inp.focus(), 50); return;
  }
  if (t === 'file') {
    const inp = h('input', { type: 'file', accept: 'image/*', class: 'gva-hide' }); if (st.kamera) inp.setAttribute('capture', st.kamera);
    const prev = h('img', { class: 'gva-prev' }); prev.style.display = 'none'; let data = null;
    const pick = h('button', { class: 'gva-c', onclick: () => inp.click() }, st.kamera ? '📷 Ambil foto' : '🖼️ Pilih foto');
    const send = h('button', { class: 'gva-ok', onclick: () => submit(data, '📎 foto terlampir') }, 'Kirim foto'); send.style.display = 'none';
    inp.addEventListener('change', async () => { const f = inp.files[0]; if (!f) return;
      try { data = await kompres(f); prev.src = data; prev.style.display = 'block'; send.style.display = ''; pick.textContent = '🔄 Ganti foto'; } catch (e) { bot('⚠️ ' + esc(e.message)); } });
    dk.append(inp, prev, h('div', { class: 'gva-row' }, pick, send), h('div', { class: 'gva-row end' }, skip, batal)); return;
  }
  if (t === 'confirm') { dk.append(h('div', { class: 'gva-row' }, h('button', { class: 'gva-ok', onclick: () => submit(true, 'Ya, lanjutkan') }, '✅ Ya, lanjutkan'), batal)); return; }
  if (t === 'danger') {
    const inp = h('input', { class: 'gva-in', placeholder: 'Ketik ' + st.kata, autocomplete: 'off' });
    const go = () => { if (inp.value.trim() !== st.kata) { bot(`⚠️ Ketik persis <b>${esc(st.kata)}</b> untuk melanjutkan.`); return; } submit(true, st.kata); };
    inp.addEventListener('keydown', e => { if (e.key === 'Enter') go(); });
    dk.append(h('div', { class: 'gva-row' }, inp, h('button', { class: 'gva-ok', onclick: go }, 'Lanjut')), h('div', { class: 'gva-row end' }, batal));
    setTimeout(() => inp.focus(), 50);
  }
}

function ask(st, d) {
  return new Promise((resolve, reject) => {
    S.pend = { reject };
    const q = typeof st.tanya === 'function' ? st.tanya(d) : st.tanya; if (q) bot(q);
    let tries = 0, sibuk = false;
    const submit = async (val, echo) => {
      if (sibuk) return; sibuk = true;
      try {
        if (st.validate) {
          let err; try { err = await st.validate(val, d); } catch (e) { err = e.message || String(e); }
          if (err) { me(echo); tries++;
            if (st.maxTry && tries >= st.maxTry) { S.pend = null; return reject(new Fail(st.kunci || 'Terlalu banyak percobaan salah.', true)); }
            bot('❌ ' + esc(err) + (st.maxTry ? ` (sisa percobaan: ${st.maxTry - tries})` : '')); return; }
        }
        me(echo); S.pend = null; el.dock.innerHTML = ''; resolve(val);
      } finally { sibuk = false; }
    };
    widget(st, d, submit);
  });
}

/* ------------------------------------------------------- mesin alur */
const ACTIONS = [], LOGIN = {};
const ACT = o => { o.steps = o.steps || []; ACTIONS.push(o); };
const auto = (key, fn) => ({ key, tipe: 'auto', fn });

function allowed(a) {
  if (a.modes && !a.modes.includes('*') && !a.modes.includes(S.mode)) return false;
  if (S.mode === 'elpoint' && a.sub && !a.sub.includes(S.sess.sub)) return false;
  if (S.mode === 'admin') {
    if (a.menu && !punyaMenu(a.menu)) return false;
    if (a.izin && !bisaAksi(a.izin)) return false;
  }
  return true;
}
const logIt = (aksi, detail) => P('insertLog', { aktor: FULL(), role: MODES[S.mode].role || 'Tamu', aksi: 'Asisten: ' + aksi, detail: detail || '' }).catch(() => {});

async function startAction(a) {
  cancelPending(); const my = ++S.fid;
  if (!allowed(a)) { bot(T.noPerm(a.judul)); return idle(); }
  if (a.login) { const n = kunciSisa(a.mode); if (n) { bot(T.kunci(n)); return idle(); } }
  const d = {};
  try {
    for (const st of a.steps) {
      if (st.if && !st.if(d)) continue;
      let v;
      if (st.tipe === 'auto') { busyOn(); try { v = await st.fn(d); } finally { busyOff(); } }
      else v = await ask(st, d);
      if (my !== S.fid) return;
      if (st.key) d[st.key] = v;
    }
    busyOn(); let res; try { res = await a.run(d); } finally { busyOff(); }
    if (my !== S.fid) return;
    if (a.login) { masuk(a, res); return; }
    if (!a.baca) logIt(a.judul, a.log ? a.log(d, res) : '');
    bot(a.ok ? a.ok(d, res) : (res || '✅ Selesai.'));
  } catch (e) {
    busyOff(); if (my !== S.fid) return;
    if (e === CANCEL) bot(T.batal);
    else if (e instanceof End) { if (e.m) bot(e.m); }
    else if (e instanceof Fail) { bot('🔒 ' + esc(e.m)); if (e.lock) strike(a.mode || S.mode, true); }
    else { bot(T.gagal(e.message)); if (a.login) { strike(a.mode); const n = kunciSisa(a.mode); if (n) bot(T.kunci(n)); } }
  }
  if (my === S.fid) idle(a);
}
function idle(a) {
  idleDock(); bot(T.lagi);
  const it = [{ t: '📋 Menu utama', fn: () => { me('Menu utama'); menu(); } }];
  if (a && !a.login && allowed(a)) it.push({ t: '🔁 ' + a.judul + ' lagi', fn: () => { me(a.judul); startAction(a); } });
  it.push({ t: '🔀 Ubah mode', alt: true, fn: () => { me('Ubah mode'); modeMenu(); } });
  chipsBox(it);
}
function masuk(a, r) {
  S.sess = Object.assign({ mode: a.mode }, r); S.mode = a.mode; S.t = Date.now(); C = {};
  try { sessionStorage.setItem(SK, JSON.stringify(S.sess)); } catch (e) {}
  strikeReset(a.mode); header(); bot(T.masuk(NAMA())); menu();
}
function logout(pesan) {
  cancelPending(); S.fid++; const nama = S.sess ? NAMA() : '';
  S.sess = null; S.mode = 'tamu'; C = {}; try { sessionStorage.removeItem(SK); } catch (e) {}
  header();
  if (pesan !== false) { bot(`Sampai jumpa${nama ? ', ' + esc(nama) : ''}! Sesi ditutup, kamu kembali sebagai ${$b('Tamu')}. 👋`); menu(); }
}

function sesiHabis() { logout(false); bot(T.habis); menu(); }

/* ---------- menu, mode, pencarian perintah ---------- */
const GRUP = ['Layanan', 'Kehadiran', 'Akun', 'Siswa', 'Poin', 'Guru & Akademik', 'Pengumuman', 'Sistem', 'Info'];
function menu() {
  idleDock();
  const g = {}; ACTIONS.filter(allowed).forEach(a => (g[a.grup || 'Lainnya'] = g[a.grup || 'Lainnya'] || []).push(a));
  bot(S.mode === 'tamu' ? `Ini yang bisa aku bantu sebagai ${$b('Tamu')}:` : `Menu ${$b(MODES[S.mode].t)} — pilih salah satu, atau ketik perintah (mis. <i>blokir</i>, <i>poin</i>).`);
  const wrap = h('div', { class: 'gva-menu' });
  GRUP.filter(k => g[k]).concat(Object.keys(g).filter(k => !GRUP.includes(k))).forEach(k => {
    wrap.append(h('div', { class: 'gva-gt' }, k));
    const row = h('div', { class: 'gva-ch' });
    g[k].forEach(a => row.append(h('button', { class: 'gva-c', onclick: () => { me(a.judul); startAction(a); } }, a.i + ' ' + a.judul)));
    wrap.append(row);
  });
  wrap.append(h('div', { class: 'gva-ch' }, h('button', { class: 'gva-c gva-alt', onclick: () => { me('Ubah mode'); modeMenu(); } }, '🔀 Ubah mode')));
  el.log.append(wrap); scroll();
}
function modeMenu() {
  cancelPending(); S.fid++; idleDock();
  bot('Pilih mode sesuai portalmu. Aku akan minta verifikasi dulu sebelum membuka menunya.');
  const sug = pageMode();
  chipsBox(Object.keys(MODES).map(k => ({ t: MODES[k].i + ' ' + MODES[k].t + (k === S.mode ? ' ✓' : '') + (k === sug && k !== S.mode ? ' • halaman ini' : ''), fn: () => pilihMode(k) })));
}
function pilihMode(k) {
  me(MODES[k].t);
  if (k === 'tamu') { if (S.sess) logout(); else { S.mode = 'tamu'; header(); menu(); } return; }
  if (S.sess && S.sess.mode === k) { bot('Kamu sudah masuk di mode ini. 😊'); return menu(); }
  if (S.sess) { logout(false); bot('Sesi sebelumnya ditutup dulu ya.'); }
  startAction(LOGIN[k]);
}
function cari(q) {
  const tk = norm(q).split(/\s+/).filter(x => x.length > 1);
  if (!tk.length) { bot(T.bingung); return menu(); }
  const sc = ACTIONS.map(a => { const hay = norm(a.judul + ' ' + (a.kata || '')); return { a, s: tk.reduce((n, x) => n + (hay.includes(x) ? 1 : 0), 0) }; }).filter(x => x.s > 0).sort((x, y) => y.s - x.s);
  if (!sc.length) { bot(T.bingung); return menu(); }
  const ok = sc.filter(x => allowed(x.a)).slice(0, 4);
  if (ok.length === 1) return startAction(ok[0].a);
  if (ok.length) { bot('Maksudmu yang mana?'); return chipsBox(ok.map(x => ({ t: x.a.i + ' ' + x.a.judul, fn: () => { me(x.a.judul); startAction(x.a); } }))); }
  const a = sc[0].a;
  if (a.modes && !a.modes.includes('*') && !a.modes.includes(S.mode)) {
    bot(`${$b(a.judul)} khusus mode ${$b(MODES[a.modes[0]].t)}. Mau ubah mode?`);
    return chipsBox([{ t: '🔀 Ubah mode', fn: () => { me('Ubah mode'); modeMenu(); } }]);
  }
  bot(T.noPerm(a.judul)); menu();
}

/* ---------- bangun panel ---------- */
function toggle() {
  S.open = !S.open; el.panel.classList.toggle('gva-open', S.open);
  if (S.open) { S.t = Date.now(); if (!S.greeted) { S.greeted = true; greet(); } setTimeout(() => { const i = el.dock.querySelector('input,textarea'); if (i) i.focus(); }, 80); }
}
function greet() {
  const sug = pageMode();
  if (S.sess) bot(`Selamat datang kembali, ${$b(NAMA())}! Kamu masih masuk sebagai ${$b(MODES[S.mode].t)}.`);
  else bot(`Halo! 👋 Aku ${$b('GrisVA')}, asisten ${esc(window.NAMA_WEB || 'E-Aspirasi Grisawangi')}.<br>Sekarang kamu sebagai ${$b('Tamu')}: aku bisa bantu info umum. Untuk melakukan aksi (lapor, absen, kelola data, dll) pilih ${$b('Ubah mode')} sesuai portalmu.`);
  menu();
  if (!S.sess && sug) { bot(`Sepertinya kamu sedang di halaman ${$b(MODES[sug].t)}. Mau masuk sebagai ${MODES[sug].t}?`); chipsBox([{ t: MODES[sug].i + ' Masuk sebagai ' + MODES[sug].t, fn: () => pilihMode(sug) }]); }
}
function build() {
  document.head.append(h('style', { html: CSS }));
  el.fab = h('button', { class: 'gva-fab', title: 'GrisVA — asisten virtual', 'aria-label': 'Buka GrisVA', onclick: toggle }, '💬');
  el.sub = h('div', { class: 'gva-hs' });
  const hb = (txt, title, fn) => h('button', { class: 'gva-hb', title, 'aria-label': title, onclick: fn }, txt);
  el.btnOut = hb('⏻', 'Keluar', () => logout());
  const head = h('div', { class: 'gva-head' }, h('div', { class: 'gva-av' }, 'G'), h('div', { style: 'flex:1;min-width:0' }, h('div', { class: 'gva-ht' }, 'GrisVA'), el.sub),
    hb('🔀', 'Ubah mode', () => { me('Ubah mode'); modeMenu(); }), hb('☰', 'Menu', () => { cancelPending(); S.fid++; menu(); }), el.btnOut, (el.btnX = hb('✕', 'Tutup', toggle)));
  el.log = h('div', { class: 'gva-log' }); el.dock = h('div', { class: 'gva-dock' });
  el.panel = h('div', { class: 'gva-panel' }, head, el.log, el.dock);
  document.body.append(el.fab, el.panel);
  if (window.GRISVA_PAGE) { el.panel.classList.add('gva-page'); el.fab.style.display = 'none'; el.btnX.style.display = 'none'; }
  ['pointerdown', 'keydown'].forEach(ev => el.panel.addEventListener(ev, () => { S.t = Date.now(); }, true));
  setInterval(() => { if (S.sess && Date.now() - S.t > CFG.idleMs) { logout(false); if (S.greeted) { bot(T.habis); menu(); } } }, 30000);
  try { const r = JSON.parse(sessionStorage.getItem(SK) || 'null'); if (r && r.mode && MODES[r.mode] && r.user) { S.sess = r; S.mode = r.mode; } } catch (e) {}
  header(); idleDock();
  if (window.GRISVA_PAGE) toggle();
}

/* ================================================================
 *                         LOGIN PER MODE
 * ================================================================ */
const cariSiswa = async q => ((await G('cariSiswa', { nama: q })).data || []).map(s => ({ v: s, t: s.nama, s: `${s.kelas || '-'} • NISN ${s.nisn || '-'}${s.status && s.status !== 'Aktif' ? ' • ' + s.status : ''}` }));
const ambilSiswa = async id => (S.sess && ['siswa', 'ortu'].includes(S.sess.mode)) ? (await gw({ type: 'grisva', action: 'me' })).data : (await Q(`siswa?id=eq.${id}&select=*&limit=1`))[0];

const cariLogin = mode => q => auth('cari', { mode, q }).then(r => (r || []).map(x => mode === 'guru'
  ? { v: x, t: x.nama_lengkap, s: x.nip || 'NIP belum diisi' }
  : { v: x, t: x.nama, s: `${x.kelas || '-'}${mode === 'siswa' ? (x.punya_pin ? ' • PIN aktif' : ' • belum punya PIN') : ''}${x.diblokir ? ' • diblokir' : ''}` }));
const cekServer = (payload) => async (v, d) => { try { await auth('login', payload(v, d)); return null; } catch (e) { return e.message || 'Verifikasi gagal.'; } };
const blokirAwal = auto('x', async d => { if (d.s.diblokir) throw new End('🚫 Akses akunmu sedang diblokir. Hubungi admin sekolah.'); });

LOGIN.siswa = { login: true, mode: 'siswa', judul: 'Masuk sebagai Siswa', steps: [
  { key: 's', tipe: 'search', tanya: 'Ketik nama lengkapmu, lalu pilih dari daftar.', cari: cariLogin('siswa') },
  blokirAwal,
  { key: 'pin', tipe: 'pin', if: d => d.s.punya_pin, tanya: 'Masukkan PIN 4 digit kamu.', maxTry: 3,
    validate: async (v, d) => { try { d._r = await auth('login', { mode: 'siswa', id: d.s.id, pin: v }); return null; } catch (e) { return e.message || 'PIN salah.'; } } },
  { key: 'tgl', tipe: 'date', if: d => !d.s.punya_pin, tanya: 'Kamu belum punya PIN. Untuk verifikasi, masukkan <b>tanggal lahir</b> kamu.', maxTry: 3,
    validate: cekServer((v, d) => ({ mode: 'siswa', id: d.s.id, tgl: v, cek: true })) },
  { key: 'pn', tipe: 'pin', if: d => !d.s.punya_pin, tanya: 'Buat PIN baru (4 digit angka).' },
  { key: 'pn2', tipe: 'pin', if: d => !d.s.punya_pin, tanya: 'Ulangi PIN barumu.', validate: (v, d) => v === d.pn ? null : 'PIN tidak sama.' },
  { key: 'jw', tipe: 'text', if: d => !d.s.punya_pin, min: 2, tanya: 'Pertanyaan keamanan: <b>Di mana tempat favoritmu?</b> (dipakai kalau lupa PIN. Jangan beri tahu siapa pun!)' }
], run: async d => d._r || auth('login', { mode: 'siswa', id: d.s.id, tgl: d.tgl, pinBaru: d.pn, jawaban: d.jw }) };

LOGIN.ortu = { login: true, mode: 'ortu', judul: 'Masuk sebagai Orang Tua', steps: [
  { key: 's', tipe: 'search', tanya: 'Ketik nama lengkap anak Ibu/Bapak, lalu pilih dari daftar.', cari: cariLogin('ortu') },
  { key: 'tgl', tipe: 'date', tanya: 'Tanggal lahir anak?', maxTry: 3,
    validate: async (v, d) => { try { d._r = await auth('login', { mode: 'ortu', id: d.s.id, tgl: v }); return null; } catch (e) { return e.message || 'Tidak cocok.'; } } }
], run: async d => d._r };

LOGIN.guru = { login: true, mode: 'guru', judul: 'Masuk sebagai Guru', steps: [
  { key: 'g', tipe: 'search', tanya: 'Ketik nama Bapak/Ibu (min. 2 huruf), lalu pilih dari daftar.', cari: cariLogin('guru') },
  { key: 'pw', tipe: 'password', tanya: 'Masukkan password.', maxTry: 3, validate: cekServer((v, d) => ({ mode: 'guru', id: d.g.id, password: v, cek: true })) },
  { key: 'sem', tipe: 'choice', tanya: 'Pilih semester.', opsi: async () => ((await auth('semester')) || []).map(s => ({ v: s.label, t: s.label + (s.aktif ? ' ★' : '') })).concat([{ v: '', t: 'Tanpa semester' }]) }
], run: async d => auth('login', { mode: 'guru', id: d.g.id, password: d.pw, semester: d.sem }) };

LOGIN.elpoint = { login: true, mode: 'elpoint', judul: 'Masuk sebagai OSIS / Sekretaris', steps: [
  { key: 'u', tipe: 'text', tanya: 'Username akun El Point?' },
  { key: 'p', tipe: 'password', tanya: 'Password?' }
], run: async d => auth('login', { mode: 'elpoint', username: d.u, password: d.p }) };

LOGIN.admin = { login: true, mode: 'admin', judul: 'Masuk sebagai Admin', steps: [
  { key: 'e', tipe: 'text', tanya: 'Email admin terdaftar?' },
  { key: 'p', tipe: 'password', tanya: 'Kata sandi?' }
], run: async d => {
  const r = await fetch(CFG.supaUrl + '/auth/v1/token?grant_type=password', { method: 'POST', headers: { apikey: CFG.supaKey, 'Content-Type': 'application/json' }, body: JSON.stringify({ email: d.e.trim(), password: d.p }) });
  const j = await r.json().catch(() => ({})); if (!r.ok || !j.access_token) throw new Error('Email atau sandi salah.');
  const s = await auth('login', { mode: 'admin', jwt: j.access_token });   // server memverifikasi JWT, jabatan & izin
  return { user: s.user, token: s.token, akses: s.akses, jwt: j.access_token };
} };

/* ================================================================
 *                         KATALOG AKSI
 * ================================================================ */
const KAT_LAPOR = ['Bullying / Perundungan', 'Fasilitas Rusak', 'Pelanggaran Tata Tertib', 'Pungli / Pemalakan', 'Kegiatan Belajar Mengajar', 'Lainnya'];
const SANKSI = [[10, 'Ditangani tim ketertiban dan dikonfirmasi ke wali kelas', 'Peringatan lisan'], [20, 'Ditangani tim ketertiban dan dikonfirmasi ke wali kelas', 'Membersihkan lingkungan sekolah'], [30, 'Ditangani tim ketertiban, wali kelas dan BK', 'Peringatan tertulis dan melaksanakan tugas dari sekolah'], [40, 'Ditangani tim ketertiban, wali kelas dan BK, serta pemanggilan orang tua', 'Peringatan tertulis dan pemanggilan orang tua'], [50, 'Ditangani tim ketertiban, wali kelas dan BK, serta pemanggilan orang tua', 'Tugas dari sekolah dan surat pernyataan bermaterai 10.000 diketahui orang tua'], [60, 'Pemanggilan orang tua', 'Surat pernyataan bermaterai + skorsing 2 hari kalender'], [70, 'Pemanggilan orang tua', 'Surat pernyataan bermaterai + skorsing 3 hari kalender'], [80, 'Pemanggilan orang tua', 'Surat pernyataan bermaterai + skorsing 4 hari kalender'], [90, 'Pemanggilan orang tua', 'Surat pernyataan bermaterai + skorsing 5 hari kalender'], [100, 'Pemanggilan orang tua', 'Surat pernyataan bermaterai + skorsing 6 hari kalender'], [9999, 'Konferensi kasus (Tim Ketertiban, Orang Tua, Wali Kelas, BK, K3, Waka Kesiswaan)', 'Dikembalikan kepada orang tua']];
const sanksi = p => (!p || p <= 0) ? null : SANKSI.find(x => p <= x[0]) || SANKSI[SANKSI.length - 1];
const infoPoin = p => { const s = sanksi(p); return s ? `Total poin: ${$b(p)}.<br>Tindak lanjut: ${esc(s[1])}<br>Sanksi: <b>${esc(s[2])}</b>` : `Total poin: ${$b(p || 0)}. 🎉 Aman, tidak ada pelanggaran.`; };
async function pelanggaran() { if (!C.pel) C.pel = (await G('getAllPelanggaran')).data || []; return C.pel; }
const cariPel = async q => { const l = await pelanggaran(); q = String(q || '').toLowerCase(); return l.filter(p => !q || (p.kelakuan || '').toLowerCase().includes(q) || (p.kategori || '').toLowerCase().includes(q)).slice(0, 15).map(p => ({ v: p, t: p.kelakuan, s: `+${p.point} poin • ${p.kategori}` })); };
const stSiswa = (key, tanya) => ({ key: key || 'siswa', tipe: 'search', tanya: tanya || 'Ketik nama siswa, lalu pilih dari daftar.', cari: cariSiswa });
const stSiswas = (key, tanya) => ({ key: key || 'siswas', tipe: 'search', multi: true, tanya: tanya || 'Ketik nama siswa lalu pilih. Bisa banyak siswa; tekan <b>Selesai</b> kalau sudah.', cari: cariSiswa });
const stTgl = (key, tanya) => ({ key, tipe: 'date', tanya, def: () => wib().tgl });
const stKonf = teks => ({ tipe: 'confirm', tanya: teks });
const namaKelasList = async () => ((await G('getAllKelas')).data || []).map(k => k.nama_kelas);

/* ---------------- TAMU / INFO (semua mode) ---------------- */
ACT({ id: 'kontak', modes: ['*'], grup: 'Info', i: '📞', judul: 'Kontak bantuan', kata: 'kontak bantuan hubungi wa whatsapp admin developer', baca: true,
  run: async () => { const r = (await pub('kontak')) || [];
    return r.length ? 'Hubungi kami lewat WhatsApp:<br>' + r.map(x => `• ${esc(x.label)}: <a href="https://wa.me/${esc(x.nomor)}" target="_blank" rel="noopener">${esc(x.nomor)}</a>`).join('<br>') : 'Kontak belum diisi admin.'; } });
ACT({ id: 'caraLogin', modes: ['*'], grup: 'Info', i: '🔑', judul: 'Cara login', kata: 'cara login masuk panduan', baca: true,
  steps: [{ key: 'r', tipe: 'choice', tanya: 'Cara login untuk siapa?', opsi: ['Siswa', 'Orang Tua', 'Guru', 'OSIS / Sekretaris', 'Admin'] }],
  run: async d => ({ 'Siswa': '🎓 <b>Siswa:</b> pilih mode Siswa → ketik nama → masukkan PIN 4 digit. Kalau belum punya PIN, verifikasi dengan tanggal lahir lalu buat PIN + jawaban keamanan.', 'Orang Tua': '👨‍👩‍👧 <b>Orang Tua:</b> pilih nama anak → masukkan tanggal lahir anak.', 'Guru': '👩‍🏫 <b>Guru:</b> ketik nama → password → pilih semester. Belum punya akun? Hubungi admin.', 'OSIS / Sekretaris': '⭐ <b>OSIS/Sekretaris:</b> username + password akun El Point dari admin.', 'Admin': '🛡️ <b>Admin:</b> email + kata sandi akun admin. Menu yang muncul mengikuti jabatan.' })[d.r] });
ACT({ id: 'faq', modes: ['*'], grup: 'Info', i: '❓', judul: 'FAQ', kata: 'faq tanya lupa pin tidak bisa login notifikasi absen', baca: true,
  steps: [{ key: 'q', tipe: 'choice', tanya: 'Pilih pertanyaan:', opsi: ['Lupa PIN?', 'Tidak bisa login', 'Notifikasi WA tidak masuk', 'Tombol absen manual tidak muncul', 'Poin pelanggaran dari mana?'] }],
  run: async d => ({ 'Lupa PIN?': 'Mode Siswa → menu <b>Ubah PIN</b> (jawab pertanyaan keamanan), atau di portal siswa klik "Lupa PIN?". Kalau jawaban lupa, admin bisa reset PIN.', 'Tidak bisa login': 'Cek ejaan nama sesuai buku induk, kelas, dan tanggal lahir. Jika salah 3x tunggu 5 menit. Masih gagal? Hubungi admin.', 'Notifikasi WA tidak masuk': 'Pastikan nomor WA di profil benar. Sistem butuh token WA aktif dari admin.', 'Tombol absen manual tidak muncul': 'Absen manual hanya muncul kalau diaktifkan admin, bukan hari libur, dan kamu belum tercatat absen hari ini.', 'Poin pelanggaran dari mana?': 'Dari terlambat absen, laporan guru, dan catatan OSIS (El Point) sesuai Buku Saku tata tertib.' })[d.q] });
ACT({ id: 'pengumuman_lihat', modes: ['*'], grup: 'Info', i: '📢', judul: 'Lihat pengumuman', kata: 'pengumuman info berita', baca: true,
  run: async () => { const r = (await pub('pengumuman')) || []; return r.length ? r.map(p => `📢 ${$b(p.judul)} <small>(${tglID(p.created_at)})</small><br>${esc(p.isi)}`).join('<hr style="border:0;border-top:1px solid #e5e7eb;margin:8px 0">') : 'Belum ada pengumuman.'; } });

/* ---------------- SISWA ---------------- */
function aspirasi(id, tipe, judul, ikon, kata, stepsAtas, kirim) {
  ACT({ id, modes: ['siswa'], grup: 'Layanan', i: ikon, judul, kata, steps: stepsAtas.concat([
    { key: 'wa', tipe: 'tel', tanya: 'Nomor WA aktif untuk kabar tindak lanjut?', def: () => U().no_telp || '', min: 8 },
    stKonf(d => `Kirim ${tipe.toLowerCase()} ini ke admin sekolah?`)
  ]), run: async d => { const r = await kirim(d); return r; } });
}
ACT({ id: 'lapor', modes: ['siswa'], grup: 'Layanan', i: '📣', judul: 'Lapor aspirasi', kata: 'lapor laporan aspirasi bullying fasilitas pelanggaran pungli adukan', steps: [
  { key: 'kat', tipe: 'choice', tanya: 'Mau lapor soal apa?', opsi: KAT_LAPOR },
  stTgl('tgl', 'Kapan kejadiannya?'),
  { key : '{{ env('KEY_28') }}', tipe: 'textarea', min: 10, tanya: 'Ceritakan kronologinya (siapa, apa, di mana).' },
  { key: 'foto', tipe: 'file', opsional: true, tanya: 'Ada foto bukti? Pilih foto, atau lewati.' },
  { key : '{{ env('KEY_29') }}', tipe: 'textarea', min: 3, if: d => !d.foto, tanya: 'Alasan tidak melampirkan foto?' },
  { key: 'wa', tipe: 'tel', min: 8, def: () => U().no_telp || '', tanya: 'Nomor WA aktif untuk kabar tindak lanjut?' },
  stKonf(d => `Kirim laporan ${$b(d.kat)} (kejadian ${tglID(d.tgl)}) ke admin sekolah?`)
], run: async d => {
  let url = null; if (d.foto) { try { url = await up(d.foto); } catch (e) { url = null; } }
  await P('insertAspirasi', { tipe: 'Laporan', pengirim_nama: U().nama, pengirim_kelas: U().kelas, pengirim_wa: d.wa, tanggal_kejadian: tglID(d.tgl), kategori: d.kat, detail_pesan: d.detail, lampiran: d.foto ? url : '{{ env('URL_30') }}' + d.alasan });
  tele(`🚨 <b>E-ASPIRASI BARU (via GrisVA)</b>\n\n👤 ${U().nama}\n🏫 ${U().kelas}\n📞 ${d.wa}\n📌 ${d.kat}\n📝 ${d.detail}`);
  return d.foto && !url ? '✅ Laporan terkirim, tapi <b>foto gagal diunggah</b>. Kirim fotonya lewat WhatsApp admin ya.' : '✅ Laporan terkirim! Pantau di menu <b>Cek tiket</b>.'; } });
ACT({ id: 'masukan', modes: ['siswa'], grup: 'Layanan', i: '💬', judul: 'Kirim masukan', kata: 'masukan saran ide kritik', steps: [
  { key: 'kat', tipe: 'choice', tanya: 'Masukan tentang apa?', opsi: ['Saran Fasilitas', 'Saran Pembelajaran', 'Ide Event/Kegiatan', 'Kritik Pelayanan', 'Lainnya'] },
  { key : '{{ env('KEY_31') }}', tipe: 'textarea', min: 10, tanya: 'Tulis masukanmu.' },
  { key: 'wa', tipe: 'tel', min: 8, def: () => U().no_telp || '', tanya: 'Nomor WA aktif?' },
  stKonf(d => `Kirim masukan ${$b(d.kat)}?`)
], run: async d => { await P('insertAspirasi', { tipe: 'Masukan', pengirim_nama: U().nama, pengirim_kelas: U().kelas, pengirim_wa: d.wa, tanggal_kejadian: tglID(wib().tgl), kategori: d.kat, detail_pesan: d.detail, lampiran: null });
  tele(`💡 <b>MASUKAN BARU (via GrisVA)</b>\n\n👤 ${U().nama} (${U().kelas})\n📌 ${d.kat}\n📝 ${d.detail}`); return '✅ Terima kasih, masukanmu sudah dikirim!'; } });
ACT({ id: 'konseling', modes: ['siswa'], grup: 'Layanan', i: '🎓', judul: 'Konseling BK', kata: 'konseling konselor bk wali kelas kesiswaan janji', steps: [
  { key : '{{ env('KEY_32') }}', tipe: 'choice', tanya: 'Mau bertemu siapa?', opsi: [{ v: 'Guru BK Utama', t: 'Guru BK' }, { v: 'Wali Kelas', t: 'Wali Kelas' }, { v: 'Kesiswaan', t: 'Waka Kesiswaan' }] },
  stTgl('tgl', 'Rencana tanggal bertemu?'),
  { key : '{{ env('KEY_33') }}', tipe: 'textarea', min: 5, tanya: 'Topik yang mau dibahas?' },
  { key: 'wa', tipe: 'tel', min: 8, def: () => U().no_telp || '', tanya: 'Nomor WA aktif?' },
  stKonf(d => `Ajukan konseling ke ${$b(d.tujuan)} tanggal ${tglID(d.tgl)}?`)
], run: async d => { await P('insertAspirasi', { tipe: 'Konselor', pengirim_nama: U().nama, pengirim_kelas: U().kelas, pengirim_wa: d.wa, tanggal_kejadian: tglID(d.tgl), kategori: d.tujuan, detail_pesan: d.alasan, lampiran: null });
  tele(`🤝 <b>PENGAJUAN KONSELOR (via GrisVA)</b>\n\n👤 ${U().nama} (${U().kelas})\n📍 ${d.tujuan}\n📅 ${tglID(d.tgl)}\n📝 ${d.alasan}`); return '✅ Jadwal diajukan. Konfirmasinya lewat WhatsApp ya.'; } });
ACT({ id: 'tiket', modes: ['siswa'], grup: 'Layanan', i: '📋', judul: 'Cek tiket', kata: 'tiket status cek progres laporan balasan', baca: true, steps: [
  auto('l', async () => { const r = (await G('getRiwayat', { nama: U().nama })).data || []; if (!r.length) throw new End('Kamu belum punya tiket.'); return r; }),
  { key : '{{ env('KEY_34') }}', tipe: 'choice', tanya: d => { const c = s => d.l.filter(x => x.status === s).length; return `Kamu punya ${$b(d.l.length)} tiket: ${c('Menunggu')} menunggu, ${c('Sedang Diproses')} diproses, ${c('Selesai')} selesai. Pilih untuk melihat detail:`; }, opsi: d => d.l.slice(0, 10).map((x, i) => ({ v: i, t: `${tglID(x.created_at)} • ${x.tipe} - ${x.kategori} (${x.status})` })) }
], run: async d => { const x = d.l[d.pilih]; const b = (await P('getBalasan', { id_aspirasi: x.id })).data || [];
  return `${$b(x.tipe + ' — ' + x.kategori)}<br>Status: ${$b(x.status)}<br>${esc(x.detail_pesan || '')}<br><br>` + (b.length ? '💬 <b>Balasan admin:</b><br>' + b.map(y => `• ${esc(y.nama_admin || 'Admin')}: ${esc(y.isi_balasan)}`).join('<br>') : 'Belum ada balasan admin.'); } });
ACT({ id: 'poin', modes: ['siswa'], grup: 'Akun', i: '⚖️', judul: 'Poin saya', kata: 'poin pelanggaran sanksi saya', baca: true,
  run: async () => { const s = (await ambilSiswa(U().id)) || U(); return infoPoin(parseInt(s.total_poin) || 0); } });
ACT({ id: 'bukuSaku', modes: ['siswa', 'ortu'], grup: 'Akun', i: '📖', judul: 'Buku saku tata tertib', kata: 'buku saku tata tertib pelanggaran daftar cari', baca: true,
  steps: [{ key: 'q', tipe: 'text', opsional: true, tanya: 'Cari pelanggaran apa? (kata kunci, atau lewati untuk daftar teratas)' }],
  run: async d => { const r = await cariPel(d.q); return r.length ? r.map(x => `• ${esc(x.v.kelakuan)} — <b>${x.v.point} poin</b> <small>(${esc(x.v.kategori)})</small>`).join('<br>') : 'Tidak ditemukan.'; } });
ACT({ id: 'absen', modes: ['siswa'], grup: 'Kehadiran', i: '📸', judul: 'Absen manual', kata: 'absen hadir sakit izin selfie kehadiran', steps: [
  auto('st', async () => { const r = (await P('statusAbsenManual', { id_siswa: U().id })).data || {};
    if (!r.aktif) throw new End('🚫 Absen manual sedang dinonaktifkan oleh admin.');
    if (r.libur) throw new End(`🏖️ Hari ini libur (${esc(r.libur)}). Tidak ada absensi.`);
    if (r.record) throw new End(`✅ Kamu sudah tercatat <b>${esc(r.record.jenis)}</b> hari ini${r.record.is_late ? ' (terlambat)' : ''}.`);
    return r; }),
  { key : '{{ env('KEY_35') }}', tipe: 'choice', tanya: d => `Mau absen apa? Batas tepat waktu: sebelum pukul ${$b(String(d.st.jam_mulai).padStart(2, '0') + ':00')} WIB.`, opsi: ['Hadir', 'Sakit', 'Izin'] },
  auto('lok', async d => {
    if (d.jenis !== 'Hadir' || !d.st.geofence) return null; const gf = d.st.geofence;
    bot('📍 Mengambil lokasimu…');
    let c; try { c = await new Promise((ok, no) => navigator.geolocation ? navigator.geolocation.getCurrentPosition(p => ok(p.coords), no, { enableHighAccuracy: true, timeout: 10000 }) : no(new Error('x'))); }
    catch (e) { if (gf.wajib) throw new End('📍 Lokasi diperlukan untuk absen hadir. Aktifkan izin lokasi di browser lalu coba lagi. (Absen Sakit/Izin tidak butuh lokasi.)'); return null; }
    const m = jarak(c.latitude, c.longitude, gf.lat, gf.lon);
    if (gf.wajib && m > gf.radius) throw new End(`📍 Kamu ${Math.round(m)} m dari sekolah. Absen hadir harus dalam radius ${gf.radius} m.`);
    bot(`📍 Lokasi terverifikasi (${Math.round(m)} m dari sekolah).`); return { lat: c.latitude, lon: c.longitude }; }),
  { key: 'foto', tipe: 'file', kamera: 'user', tanya: d => d.jenis === 'Hadir' ? 'Ambil foto selfie untuk konfirmasi kehadiran.' : `Foto surat ${d.jenis.toLowerCase()} (wajib, pastikan tulisannya terbaca).` },
  stKonf(d => `Simpan absen ${$b(d.jenis)} sekarang?`)
], run: async d => { const url = await up(d.foto);
  const r = (await P('absenManual', { id_siswa: U().id, jenis: d.jenis, lampiran: url, lat: d.lok ? d.lok.lat : null, lon: d.lok ? d.lok.lon : null })).data || {};
  return d.jenis !== 'Hadir' ? `✅ Absen ${esc(d.jenis)} tercatat. Surat tersimpan untuk guru/admin.` : (r.terlambat ? `⚠️ Absen tercatat <b>terlambat</b>${r.poin ? `, +${r.poin} poin pelanggaran` : ''}.` : '✅ Absen berhasil, kamu <b>tepat waktu</b>!'); } });
ACT({ id: 'profil', modes: ['siswa'], grup: 'Akun', i: '👤', judul: 'Edit profil', kata: 'profil ubah edit nama panggilan wa nomor nisn', steps: [
  { key: 'f', tipe: 'choice', tanya: 'Mau ubah apa?', opsi: [{ v: 'nama_panggilan', t: 'Nama panggilan' }, { v: 'no_telp', t: 'No WA' }, { v: 'nisn', t: 'NISN' }] },
  { key: 'v', tipe: 'text', min: 2, tanya: d => `Isi ${d.f === 'nama_panggilan' ? 'nama panggilan' : d.f === 'no_telp' ? 'nomor WA' : 'NISN'} baru:` }
], run: async d => { await P('updateData', { table: 'siswa', id: U().id, payload: { [d.f]: d.v } }); U()[d.f] = d.v; try { sessionStorage.setItem(SK, JSON.stringify(S.sess)); } catch (e) {} header(); return '✅ Profil diperbarui.'; } });
ACT({ id: 'ubahPin', modes: ['siswa'], grup: 'Akun', i: '🔐', judul: 'Ubah PIN', kata: 'ubah ganti lupa pin keamanan', steps: [
  auto('f', async () => { const r = (await gw({ type: 'grisva', action: 'ubahPin', payload: { op: 'status' } })).data; if (!r.ada) throw new End('Akunmu belum punya jawaban keamanan. Hubungi admin untuk reset PIN.'); return r; }),
  { key: 'jw', tipe: 'text', maxTry: 3, tanya: 'Verifikasi dulu: <b>Di mana tempat favoritmu?</b>',
    validate: async v => { try { await gw({ type: 'grisva', action: 'ubahPin', payload: { op: 'cek', jawaban: v } }); return null; } catch (e) { return e.message || 'Jawaban salah.'; } } },
  { key: 'pn', tipe: 'pin', tanya: 'PIN baru (4 digit)?' },
  { key: 'pn2', tipe: 'pin', tanya: 'Ulangi PIN baru.', validate: (v, d) => v === d.pn ? null : 'PIN tidak sama.' }
], run: async d => { await gw({ type: 'grisva', action: 'ubahPin', payload: { op: 'ubah', jawaban: d.jw, pinBaru: d.pn } }); return '✅ PIN diganti.'; } });
ACT({ id: 'qr', modes: ['siswa'], grup: 'Akun', i: '🔳', judul: 'QR kartu digital', kata: 'qr kartu digital nisn', baca: true,
  run: async () => { if (!U().nisn) throw new End('NISN kamu belum terisi. Isi dulu lewat <b>Edit profil</b>.'); return `Ini kartu digitalmu 👇<br><img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${enc(U().nisn)}" alt="QR"><br>${$b(U().nama)} • ${esc(U().kelas)} • NISN ${esc(U().nisn)}`; } });
ACT({ id: 'penilaian', modes: ['siswa'], grup: 'Akun', i: '⭐', judul: 'Beri penilaian', kata: 'penilaian nilai bintang rating', steps: [
  { key: 'b', tipe: 'choice', tanya: 'Seberapa puas kamu dengan layanan E-Aspirasi?', opsi: [{ v: 1, t: '⭐ 1' }, { v: 2, t: '⭐ 2' }, { v: 3, t: '⭐ 3' }, { v: 4, t: '⭐ 4' }, { v: 5, t: '⭐ 5' }] },
  { key: 'k', tipe: 'textarea', opsional: true, tanya: 'Mau tambah komentar? (boleh dilewati)' }
], run: async d => { await P('insertData', { table: 'penilaian', payload: { nama_penilai: U().nama, kelas_penilai: U().kelas, bintang: d.b, komentar: d.k || null, created_at: new Date().toISOString() } }); return '✅ Terima kasih atas penilaianmu! 🙏'; } });

/* ---------------- ORANG TUA ---------------- */
ACT({ id: 'absensiAnak', modes: ['ortu'], grup: 'Layanan', i: '📅', judul: 'Absensi anak', kata: 'absensi anak hadir kehadiran', baca: true, steps: [
  { key: 'p', tipe: 'choice', tanya: 'Periode?', opsi: [{ v: 7, t: '7 hari' }, { v: 30, t: '1 bulan' }, { v: 0, t: 'Semua' }] }
], run: async d => { const dari = d.p ? `&created_at=gte.${enc(new Date(Date.now() - d.p * 864e5).toISOString())}` : '';
  const r = await Q(`absensi?id_siswa=eq.${U().id}${dari}&select=jenis,is_late&limit=2000`); const c = j => r.filter(x => x.jenis === j).length;
  return `${$b(U().nama)} (${esc(U().kelas)})<br>✅ Hadir ${c('Hadir')} • ⏰ Terlambat ${r.filter(x => x.jenis === 'Hadir' && x.is_late).length}<br>🤒 Sakit ${c('Sakit')} • 📝 Izin ${c('Izin')} • ❌ Alpa ${c('Alpa')}`; } });
ACT({ id: 'poinAnak', modes: ['ortu'], grup: 'Layanan', i: '⚖️', judul: 'Poin & sanksi anak', kata: 'poin sanksi pelanggaran anak', baca: true,
  run: async () => { const s = (await ambilSiswa(U().id)) || U(); const lg = await Q(`log_history?aksi=eq.${enc('Lapor Poin Pelanggaran')}&detail=ilike.${enc('*' + U().nama + '*')}&order=created_at.desc&limit=8&select=detail,created_at`);
    return infoPoin(parseInt(s.total_poin) || 0) + (lg.length ? '<br><br><b>Riwayat terbaru:</b><br>' + lg.map(x => `• ${tglID(x.created_at)} — ${esc(x.detail)}`).join('<br>') : '<br>Tidak ada catatan pelanggaran. Sangat baik!'); } });
ACT({ id: 'profilAnak', modes: ['ortu'], grup: 'Layanan', i: '🧒', judul: 'Profil anak', kata: 'profil anak data', baca: true,
  run: async () => `${$b(U().nama)}<br>Kelas: ${esc(U().kelas || '-')}<br>NISN: ${esc(U().nisn || '-')}<br>Poin: ${$b(U().total_poin || 0)}` });

/* ---------------- OSIS / SEKRETARIS ---------------- */
const telat = async () => { const r = (await P('statusAbsenManual', {})).data || {}; return wib().jam >= (r.jam_mulai != null ? r.jam_mulai : 7); };
const stJenis = (opsi) => ({ key : '{{ env('KEY_36') }}', tipe: 'choice', tanya: 'Status kehadiran?', opsi });
const stSurat = { key: 'foto', tipe: 'file', if: d => d.jenis === 'Sakit' || d.jenis === 'Izin', tanya: 'Foto surat (wajib untuk Sakit/Izin).' };
ACT({ id: 'absenCari', modes: ['elpoint'], sub: ['osis'], grup: 'Kehadiran', i: '🔍', judul: 'Absen siswa (cari nama)', kata: 'absen cari siswa manual scan hadir', steps: [
  stSiswa(), stJenis(['Hadir', 'Sakit', 'Izin', 'Alpa']), stSurat, stKonf(d => `Catat ${$b(d.siswa.nama)} (${esc(d.siswa.kelas)}) sebagai ${$b(d.jenis)}?`)
], run: async d => { const late = d.jenis === 'Hadir' && await telat(); const lamp = d.foto ? await up(d.foto) : '';
  await P('absenMassal', { ids: [d.siswa.id], jenis: d.jenis, isLate: late, lampiran: lamp, aktor: U().nama_anggota });
  return `✅ ${$b(d.siswa.nama)} tercatat ${$b(d.jenis)}${late ? ' (terlambat)' : ''} pukul ${wib().hm} WIB.`; } });
ACT({ id: 'absenMassal', modes: ['elpoint'], grup: 'Kehadiran', i: '👥', judul: 'Absen massal kelas', kata: 'absen massal banyak siswa kelas sekretaris', steps: [
  stSiswas(), stJenis(['Hadir', 'Sakit', 'Izin', 'Alpa']), stSurat, stKonf(d => `Catat ${$b(d.siswas.length)} siswa sebagai ${$b(d.jenis)}?`)
], run: async d => { const late = d.jenis === 'Hadir' && await telat(); const lamp = d.foto ? await up(d.foto) : '';
  await P('absenMassal', { ids: d.siswas.map(s => s.id), jenis: d.jenis, isLate: late, lampiran: lamp, aktor: U().nama_anggota });
  return `✅ ${d.siswas.length} siswa tercatat ${$b(d.jenis)}${late ? ' (terlambat)' : ''}.`; } });
ACT({ id: 'poinOsis', modes: ['elpoint'], sub: ['osis'], grup: 'Poin', i: '⚖️', judul: 'Tambah poin pelanggaran', kata: 'tambah poin pelanggaran hukuman osis', steps: [
  stSiswa(), { key: 'pel', tipe: 'search', awal: true, tanya: 'Pelanggaran apa? (ketik untuk mencari di buku saku)', cari: cariPel },
  stKonf(d => `Tambah ${$b('+' + d.pel.point + ' poin')} untuk ${$b(d.siswa.nama)}?<br><small>${esc(d.pel.kelakuan)}</small>`)
], run: async d => { await P('tambahPoinSiswa', { id_siswa: d.siswa.id, id_pelanggaran: d.pel.id, poin: parseInt(d.pel.point), aktor: U().nama_anggota }); return `✅ +${d.pel.point} poin untuk ${$b(d.siswa.nama)}.`; } });

/* ---------------- GURU ---------------- */
function fmtJadwal(j) {
  switch (j.status) {
    case 'mengajar': return `Anda mengajar ${$b(j.mapel)} di ${$b(j.kelas)} sampai jam ${$b(j.sampai)}.`;
    case 'istirahat': return `Sedang istirahat sampai jam ${$b(j.sampai)}.`;
    case 'agenda': return `Sedang ada agenda${j.nama ? ' (' + esc(j.nama) + ')' : ''} sampai jam ${$b(j.sampai)}.`;
    case 'libur': return `Hari ini libur${j.ket ? ' — ' + esc(j.ket) : ''}.`;
    case 'tidak_mengajar': { const b = j.berikut; return 'Anda tidak sedang mengajar. ' + (b ? `Jadwal berikutnya: ${$b(HR[b.hari])}, jam ke-${b.jam_ke}${b.mulai ? ' (' + b.mulai + ')' : ''}, ${esc(b.mapel)}, ${esc(b.kelas)}.` : 'Belum ada jadwal berikutnya.'); }
    default: return esc(j.pesan || 'Jadwal belum diatur admin.');
  }
}
const bolehLihat = j => j.status === 'mengajar' || j.batasi === false;
const jadwalSkrg = async () => (await P('cekJadwalGuru', { id_guru: U().id })).data || { status: 'error' };
const JENIS = { alfa_mapel: ['Alfa Saat Mata Pelajaran', 10], melanggar_peraturan: ['Melanggar Peraturan', 6], makan_di_kelas: ['Makan di Kelas', 6], mengganggu_pembelajaran: ['Mengganggu Pembelajaran', 6] };
ACT({ id: 'jadwal', modes: ['guru'], grup: 'Layanan', i: '🕒', judul: 'Jadwal sekarang', kata: 'jadwal mengajar sekarang kelas mapel', baca: true, run: async () => fmtJadwal(await jadwalSkrg()) });
ACT({ id: 'absensiKelas', modes: ['guru'], grup: 'Kehadiran', i: '📅', judul: 'Absensi kelas', kata: 'absensi kelas hadir siswa hari ini', baca: true, steps: [
  auto('j', async () => { const j = await jadwalSkrg(); if (!bolehLihat(j)) throw new End('⛔ Belum waktunya mata pelajaran. Absensi kelas hanya bisa dilihat saat Anda mengajar.<br>' + fmtJadwal(j)); return j; }),
  { key : '{{ env('KEY_37') }}', tipe: 'choice', if: d => d.j.status !== 'mengajar', tanya: 'Kelas mana?', opsi: () => arr(U().kelas_diajarkan), kosong: 'Belum ada kelas yang diajarkan. Atur di profil.' }
], run: async d => { const kelas = d.kelas || d.j.kelas; const sw = await Q(`siswa?kelas=eq.${enc(kelas)}&status=not.in.(Diblokir,Alumni)&select=id,nama&order=nama.asc`);
  if (!sw.length) return 'Tidak ada siswa di kelas ' + esc(kelas) + '.';
  const ab = await Q(`absensi?id_siswa=in.(${sw.map(s => s.id).join(',')})&created_at=gte.${enc(wib().tgl + 'T00:00:00+07:00')}&select=id_siswa,jenis,is_late&order=created_at.asc&limit=2000`);
  const last = {}; ab.forEach(a => { last[a.id_siswa] = a; }); const v = Object.values(last); const c = j => v.filter(x => x.jenis === j).length;
  const belum = sw.filter(s => !last[s.id]).map(s => s.nama);
  return `Kelas ${$b(kelas)} hari ini:<br>✅ Hadir ${c('Hadir')} (terlambat ${v.filter(x => x.jenis === 'Hadir' && x.is_late).length}) • 📝 Izin ${c('Izin')} • 🤒 Sakit ${c('Sakit')} • ❌ Alpa ${c('Alpa')}<br>⏳ Belum absen ${belum.length}` + (belum.length ? ':<br><small>' + belum.slice(0, 15).map(esc).join(', ') + (belum.length > 15 ? ' …' : '') + '</small>' : ''); } });
ACT({ id: 'laporSiswa', modes: ['guru'], grup: 'Layanan', i: '🚩', judul: 'Lapor siswa', kata: 'lapor laporkan siswa pelanggaran alfa makan keributan', steps: [
  stSiswas('siswas', 'Siswa mana yang dilaporkan? Ketik nama, pilih, bisa lebih dari satu. Tekan <b>Selesai</b> kalau sudah.'),
  { key : '{{ env('KEY_38') }}', tipe: 'choice', tanya: 'Mata pelajaran?', opsi: async () => { const m = arr(U().mapel); return m.length ? m : (await Q('master_mapel?aktif=eq.true&select=nama_mapel&order=nama_mapel.asc')).map(x => x.nama_mapel); } },
  { key : '{{ env('KEY_39') }}', tipe: 'choice', tanya: 'Jenis pelanggaran?', opsi: () => Object.keys(JENIS).map(k => ({ v: k, t: `${JENIS[k][0]} (+${JENIS[k][1]})` })).concat([{ v: '_lain', t: 'Lainnya (buku saku)…' }]) },
  { key: 'pel', tipe: 'search', awal: true, if: d => d.jenis === '_lain', tanya: 'Pilih dari buku saku:', cari: cariPel },
  { key: 'ket', tipe: 'textarea', opsional: true, tanya: 'Keterangan tambahan? (boleh dilewati)' },
  stKonf(d => { const l = d.jenis === '_lain' ? d.pel.kelakuan : JENIS[d.jenis][0], p = d.jenis === '_lain' ? d.pel.point : JENIS[d.jenis][1]; return `Laporkan ${$b(d.siswas.map(s => s.nama).join(', '))}<br>Mapel: ${esc(d.mapel)}<br>${$b(l)} — <b>+${p} poin</b> per siswa?`; })
], log: d => d.siswas.map(s => s.nama).join(', '),
run: async d => { const l = d.jenis === '_lain' ? d.pel.kelakuan : JENIS[d.jenis][0], p = parseInt(d.jenis === '_lain' ? d.pel.point : JENIS[d.jenis][1]);
  for (const s of d.siswas) await P('insertLaporanGuru', { payload: { id_guru: U().id, nama_guru: U().nama_lengkap, id_siswa: s.id, nama_siswa: s.nama, kelas_siswa: s.kelas || '', mata_pelajaran: d.mapel, tanggal: wib().tgl, jam: wib().hm, jenis: d.jenis === '_lain' ? 'master_pelanggaran' : d.jenis, label_jenis: l, keterangan: d.ket || '', poin_siswa: p, id_pelanggaran_master: d.jenis === '_lain' ? d.pel.id : null, semester: S.sess.semester || '', status: 'Selesai' } });
  return `✅ Laporan terkirim untuk ${d.siswas.length} siswa. Masing-masing +${p} poin.`; } });
ACT({ id: 'riwayatLaporan', modes: ['guru'], grup: 'Layanan', i: '🗂️', judul: 'Riwayat & batalkan laporan', kata: 'riwayat batalkan batal laporan', steps: [
  auto('l', async () => { const r = await Q(`laporan_guru?id_guru=eq.${U().id}&status=neq.Dibatalkan&order=created_at.desc&limit=10&select=*`); if (!r.length) throw new End('Belum ada laporan aktif.'); return r; }),
  { key: 'i', tipe: 'choice', tanya: 'Pilih laporan yang mau dibatalkan:', opsi: d => d.l.map((x, i) => ({ v: i, t: `${x.tanggal || ''} ${x.nama_siswa} — ${x.label_jenis || x.jenis} (+${x.poin_siswa})` })) },
  { key : '{{ env('KEY_40') }}', tipe: 'choice', tanya: 'Alasan pembatalan?', opsi: ['Siswa masuk kembali di kelas', 'Salah mengirim laporan', 'Ingin membuat laporan baru'] },
  stKonf(d => `Batalkan laporan ${$b(d.l[d.i].nama_siswa)}? Poin <b>${d.l[d.i].poin_siswa}</b> akan dikembalikan.`)
], run: async d => { const x = d.l[d.i]; await P('batalLaporanGuru', { id: x.id, alasan_batal: d.alasan, id_siswa: x.id_siswa, poin_siswa: x.poin_siswa }); return `✅ Dibatalkan, ${x.poin_siswa} poin dikembalikan ke ${esc(x.nama_siswa)}.`; } });
ACT({ id: 'salinCatatan', modes: ['guru'], grup: 'Kehadiran', i: '📑', judul: 'Salin catatan absen', kata: 'salin catatan absen pribadi', steps: [
  auto('j', async () => { const j = await jadwalSkrg(); if (j.status !== 'mengajar') throw new End('⛔ Belum waktunya mata pelajaran. Salinan hanya bisa diambil saat Anda mengajar.<br>' + fmtJadwal(j)); return j; }),
  stKonf(d => `Salin absensi hari ini kelas ${$b(d.j.kelas)} ke catatan pribadi?`)
], run: async d => { const t = wib().tgl; const r = (await P('salinCatatanAbsen', { id_guru: U().id, kelas: d.j.kelas, dari: t, sampai: t, semester: S.sess.semester || '', id_siswa: null })).data || {}; return `✅ ${r.disalin || 0} catatan baru disalin.`; } });
ACT({ id: 'masukanGuru', modes: ['guru'], grup: 'Layanan', i: '📨', judul: 'Kirim masukan', kata: 'masukan saran keluhan apresiasi', steps: [
  { key: 'kat', tipe: 'choice', tanya: 'Kategori?', opsi: ['Saran Fasilitas', 'Saran Kurikulum', 'Saran Tata Tertib', 'Keluhan', 'Apresiasi', 'Lainnya'] },
  { key: 'isi', tipe: 'textarea', min: 5, tanya: 'Tulis masukan Anda.' }, stKonf(d => `Kirim masukan ${$b(d.kat)} ke admin?`)
], run: async d => { await P('insertData', { table: 'aspirasi', payload: { tipe: 'Masukan', sub_tipe: 'masukan_guru', pengirim_nama: U().nama_lengkap, pengirim_wa: U().no_wa || '', kategori: d.kat, detail_pesan: d.isi, status: 'Menunggu' } }); return '✅ Masukan terkirim ke admin.'; } });
ACT({ id: 'passGuru', modes: ['guru'], grup: 'Akun', i: '🔐', judul: 'Ubah password', kata: 'ubah ganti password sandi', steps: [
  { key: 'lama', tipe: 'password', maxTry: 3, tanya: 'Password lama?',
    validate: async v => { try { await gw({ type: 'grisva', action: 'guruPassword', payload: { op: 'cek', lama: v } }); return null; } catch (e) { return e.message || 'Password salah.'; } } },
  { key: 'baru', tipe: 'password', min: 6, tanya: 'Password baru (min. 6 karakter)?' },
  { key: 'b2', tipe: 'password', tanya: 'Ulangi password baru.', validate: (v, d) => v === d.baru ? null : 'Password tidak sama.' }
], run: async d => { await gw({ type: 'grisva', action: 'guruPassword', payload: { op: 'ubah', lama: d.lama, baru: d.baru } }); return '✅ Password diganti. Pakai yang baru saat login berikutnya.'; } });

/* ---------------- ADMIN ---------------- */
const TIPE_MENU = { Laporan: 'laporan', Masukan: 'masukan', Konselor: 'konselor' };
const TMENU = ['laporan', 'masukan', 'konselor'];
const TEMPL = [
  ['Diterima', 'Yth. Siswa/i,\n\nLaporan/aspirasi Anda telah kami terima dan akan segera kami tindaklanjuti. Terima kasih telah mempercayakan permasalahan Anda kepada kami.\n\nHormat kami,\nTim E-Aspirasi'],
  ['Sedang diproses', 'Yth. Siswa/i,\n\nLaporan Anda sedang dalam proses penanganan oleh pihak terkait. Mohon bersabar dan kami akan segera memberikan kabar lebih lanjut.\n\nHormat kami,\nTim E-Aspirasi'],
  ['Jadwal konseling', 'Yth. Siswa/i,\n\nTerima kasih telah menghubungi layanan konseling. Tim BK akan menghubungi Anda mengenai waktu dan tempat sesi konseling.\n\nSalam,\nGuru BK'],
  ['Perlu konfirmasi', 'Yth. Siswa/i,\n\nKami telah menerima laporan Anda. Untuk memproses lebih lanjut, kami memerlukan konfirmasi dan informasi tambahan. Mohon hubungi pihak sekolah atau balas melalui layanan ini.\n\nHormat kami,\nTim E-Aspirasi'],
  ['Terima kasih', 'Yth. Siswa/i,\n\nTerima kasih telah menyampaikan masukan dan aspirasi Anda. Masukan Anda akan menjadi bahan evaluasi kami ke depannya.\n\nHormat kami,\nTim E-Aspirasi'],
  ['Tidak dapat diproses', 'Yth. Siswa/i,\n\nMohon maaf, laporan/aspirasi yang Anda sampaikan tidak dapat kami proses saat ini. Jika ada pertanyaan, silakan hubungi pihak sekolah secara langsung.\n\nHormat kami,\nTim E-Aspirasi']
];
const STATUS = ['Menunggu', 'Sedang Diproses', 'Selesai', 'Ditolak'];
const stTiket = (saring) => [
  auto('asp', async () => { const a = ((await G('getAllAspirasi')).data || []).filter(x => punyaMenu(TIPE_MENU[x.tipe] || '-')); if (!a.length) throw new End('Belum ada tiket yang bisa kamu akses.'); return a; }),
  { key: 'tk', tipe: 'search', awal: true, tanya: 'Cari tiket (ketik nama pengirim atau kategori).', cari: (q, d) => { q = q.toLowerCase();
    return d.asp.filter(x => (!saring || saring(x)) && (!q || (x.pengirim_nama || '').toLowerCase().includes(q) || (x.kategori || '').toLowerCase().includes(q) || (x.detail_pesan || '').toLowerCase().includes(q))).slice(0, 12)
      .map(x => ({ v: x, t: `${x.pengirim_nama} • ${x.tipe}`, s: `${x.kategori} • ${x.status} • ${tglID(x.created_at)}` })); } }
];
ACT({ id: 'ringkasan', modes: ['admin'], grup: 'Layanan', i: '📊', judul: 'Ringkasan hari ini', kata: 'ringkasan dashboard statistik jumlah', baca: true,
  run: async () => { const a = (await G('getAllAspirasi')).data || [], sw = (await G('getAllSiswa')).data || []; const m = a.filter(x => x.status === 'Menunggu'); const lama = m.filter(x => Date.now() - new Date(x.created_at) > 3 * 864e5).length;
    const n = t => a.filter(x => x.tipe === t).length;
    return `📊 <b>Ringkasan</b><br>Laporan ${n('Laporan')} • Masukan ${n('Masukan')} • Konselor ${n('Konselor')}<br>⏳ Menunggu: ${$b(m.length)} (${lama} lebih dari 3 hari)<br>🔄 Diproses: ${a.filter(x => x.status === 'Sedang Diproses').length} • ✅ Selesai: ${a.filter(x => x.status === 'Selesai').length}<br>🎓 Total siswa: ${sw.length}`; } });
ACT({ id: 'tiketLihat', modes: ['admin'], menu: TMENU, grup: 'Layanan', i: '📥', judul: 'Lihat tiket menunggu', kata: 'lihat tiket daftar laporan menunggu pending', baca: true, steps: [
  { key: 's', tipe: 'choice', tanya: 'Status tiket?', opsi: STATUS }
], run: async d => { const a = ((await G('getAllAspirasi')).data || []).filter(x => x.status === d.s && punyaMenu(TIPE_MENU[x.tipe] || '-')).slice(0, 10);
  return a.length ? `${$b(d.s)} (10 terbaru):<br>` + a.map(x => `• ${tglID(x.created_at)} ${esc(x.pengirim_nama)} (${esc(x.pengirim_kelas)}) — ${esc(x.tipe)}/${esc(x.kategori)}`).join('<br>') : `Tidak ada tiket berstatus ${esc(d.s)}.`; } });
ACT({ id: 'tiketBalas', modes: ['admin'], menu: TMENU, izin: 'aksi_balas', grup: 'Layanan', i: '💬', judul: 'Balas tiket', kata: 'balas tiket laporan jawab reply', steps: stTiket().concat([
  { key: 'tpl', tipe: 'choice', tanya: d => `Tiket ${$b(d.tk.pengirim_nama)} — ${esc(d.tk.kategori)}:<br><small>${esc(d.tk.detail_pesan || '')}</small><br>Pilih template balasan atau tulis sendiri:`, opsi: TEMPL.map((x, i) => ({ v: i, t: x[0] })).concat([{ v: -1, t: '✍️ Tulis sendiri' }]) },
  { key: 'isi', tipe: 'textarea', if: d => d.tpl === -1, min: 3, tanya: 'Tulis balasannya.' },
  stKonf(d => `Kirim balasan ke ${$b(d.tk.pengirim_nama)}?<br><small>${esc(d.tpl === -1 ? d.isi : TEMPL[d.tpl][1]).replace(/\n/g, '<br>')}</small>`)
]), log: d => d.tk.pengirim_nama,
run: async d => { await P('insertBalasan', { id_aspirasi: d.tk.id, isi_balasan: d.tpl === -1 ? d.isi : TEMPL[d.tpl][1], nama_admin: FULL(), pengirim_wa: d.tk.pengirim_wa || '' }); return `✅ Balasan terkirim ke ${esc(d.tk.pengirim_nama)} (WA jika nomor tersedia).`; } });
ACT({ id: 'tiketStatus', modes: ['admin'], menu: TMENU, izin: 'aksi_balas', grup: 'Layanan', i: '🔄', judul: 'Ubah status tiket', kata: 'ubah status tiket proses selesai tolak', steps: stTiket().concat([
  { key: 'st', tipe: 'choice', tanya: d => `Status ${$b(d.tk.pengirim_nama)} sekarang ${$b(d.tk.status)}. Ubah ke?`, opsi: STATUS },
  stKonf(d => `Ubah status ke ${$b(d.st)}? Siswa akan dinotifikasi (WA + push).`)
]), log: d => `${d.tk.pengirim_nama} → ${d.st}`, run: async d => { await P('updateAspirasiStatus', { id: d.tk.id, status: d.st }); return `✅ Status diubah ke ${$b(d.st)}, siswa dinotifikasi.`; } });
ACT({ id: 'siswaInfo', modes: ['admin'], menu: 'siswa', grup: 'Siswa', i: '🔎', judul: 'Cari data siswa', kata: 'cari data siswa info detail', baca: true, steps: [stSiswa()],
  run: async d => { const s = (await ambilSiswa(d.siswa.id)) || d.siswa; return `${$b(s.nama)}<br>Kelas: ${esc(s.kelas || '-')} • NISN: ${esc(s.nisn || '-')}<br>Tgl lahir: ${esc(s.tgl_lahir || '-')} • WA: ${esc(s.no_telp || '-')}<br>Status: ${$b(s.status || 'Aktif')} • Poin: ${$b(s.total_poin || 0)}<br>PIN: ${pinAda(s) ? 'sudah diset' : 'belum diset'}`; } });
ACT({ id: 'siswaBlokir', modes: ['admin'], menu: 'siswa', izin: 'aksi_blokir', grup: 'Siswa', i: '🚫', judul: 'Blokir / buka blokir siswa', kata: 'blokir buka blokir akses siswa ban aktifkan', steps: [
  stSiswa('siswa', 'Mau blokir/aktifkan siapa? Ketik nama siswa lalu pilih.'),
  stKonf(d => d.siswa.status === 'Diblokir' ? `${$b(d.siswa.nama)} (${esc(d.siswa.kelas)}) sedang <b>diblokir</b>. Aktifkan kembali?` : `Yakin <b>blokir</b> ${$b(d.siswa.nama)} (${esc(d.siswa.kelas)})? Siswa tidak akan bisa login.`)
], log: d => d.siswa.nama, run: async d => { const blok = d.siswa.status !== 'Diblokir'; await P('updateData', { table: 'siswa', id: d.siswa.id, payload: { status: blok ? 'Diblokir' : 'Aktif' } }); return blok ? `✅ ${$b(d.siswa.nama)} diblokir.` : `✅ Blokir ${$b(d.siswa.nama)} dibuka.`; } });
ACT({ id: 'siswaResetPin', modes: ['admin'], menu: 'siswa', izin: 'aksi_reset_pin', grup: 'Siswa', i: '🔓', judul: 'Reset PIN siswa', kata: 'reset pin siswa lupa', steps: [
  stSiswa(), stKonf(d => `PIN dan jawaban keamanan ${$b(d.siswa.nama)} akan dihapus. Siswa harus membuat PIN baru saat login. Lanjut?`)
], log: d => d.siswa.nama, run: async d => { await P('updateData', { table: 'siswa', id: d.siswa.id, payload: { login_pin: null, security_answer: null } }); return `✅ PIN ${$b(d.siswa.nama)} direset.`; } });
ACT({ id: 'siswaTambah', modes: ['admin'], menu: 'siswa', izin: 'aksi_tambah', grup: 'Siswa', i: '➕', judul: 'Tambah siswa', kata: 'tambah siswa baru daftar', steps: [
  { key: 'nama', tipe: 'text', min: 3, tanya: 'Nama lengkap siswa?' },
  { key : '{{ env('KEY_41') }}', tipe: 'choice', tanya: 'Kelas?', opsi: namaKelasList, kosong: 'Master kelas masih kosong.' },
  { key: 'nisn', tipe: 'text', opsional: true, tanya: 'NISN? (boleh dilewati)' },
  { key: 'tgl', tipe: 'date', opsional: true, tanya: 'Tanggal lahir? (boleh dilewati)', def: '' },
  { key: 'wa', tipe: 'tel', opsional: true, tanya: 'Nomor WA? (boleh dilewati)' },
  stKonf(d => `Tambah siswa ${$b(d.nama)} kelas ${$b(d.kelas)}?`)
], log: d => d.nama, run: async d => { await P('insertData', { table: 'siswa', payload: { nama: d.nama, kelas: d.kelas, nisn: d.nisn || '', tgl_lahir: d.tgl ? tglID(d.tgl) : '', no_telp: d.wa || '', status: 'Aktif', total_poin: 0 } }); return `✅ Siswa ${$b(d.nama)} ditambahkan.`; } });
ACT({ id: 'poinSet', modes: ['admin'], menu: 'poin', izin: 'aksi_edit', grup: 'Poin', i: '✏️', judul: 'Set poin manual', kata: 'set ubah poin siswa manual', steps: [
  stSiswa(), { key: 'n', tipe: 'number', tanya: d => `Poin ${$b(d.siswa.nama)} sekarang ${$b(d.siswa.total_poin || 0)}. Set ke berapa?`, validate: v => (/^\d{1,3}$/.test(v) ? null : 'Isi angka 0–999.') },
  stKonf(d => `Set poin ${$b(d.siswa.nama)} menjadi ${$b(d.n)}?`)
], log: d => `${d.siswa.nama} → ${d.n}`, run: async d => { await P('updateData', { table: 'siswa', id: d.siswa.id, payload: { total_poin: parseInt(d.n) } }); return `✅ Poin ${$b(d.siswa.nama)} jadi ${$b(d.n)}.`; } });
ACT({ id: 'poinTambah', modes: ['admin'], menu: 'poin', izin: 'aksi_edit', grup: 'Poin', i: '➕', judul: 'Tambah poin dari buku saku', kata: 'tambah poin pelanggaran buku saku', steps: [
  stSiswa(), { key: 'pel', tipe: 'search', awal: true, tanya: 'Pelanggaran apa?', cari: cariPel },
  stKonf(d => `Tambah ${$b('+' + d.pel.point)} poin untuk ${$b(d.siswa.nama)}?<br><small>${esc(d.pel.kelakuan)}</small>`)
], log: d => `${d.siswa.nama} +${d.pel.point}`, run: async d => { await P('tambahPoinSiswa', { id_siswa: d.siswa.id, id_pelanggaran: d.pel.id, poin: parseInt(d.pel.point), aktor: FULL() }); return `✅ +${d.pel.point} poin untuk ${$b(d.siswa.nama)}.`; } });
ACT({ id: 'poinReset', modes: ['admin'], menu: 'poin', izin: 'aksi_reset_data', grup: 'Poin', i: '♻️', judul: 'Reset poin ke 0', kata: 'reset poin nol siswa', steps: [
  stSiswa(), { key: 'k', tipe: 'danger', kata: 'RESET', tanya: d => `⚠️ Poin ${$b(d.siswa.nama)} (${$b(d.siswa.total_poin || 0)}) akan dikembalikan ke 0 dan tidak bisa dibatalkan. Ketik <b>RESET</b> untuk melanjutkan.` }
], log: d => d.siswa.nama, run: async d => { await P('updateData', { table: 'siswa', id: d.siswa.id, payload: { total_poin: 0 } }); return `✅ Poin ${$b(d.siswa.nama)} direset ke 0.`; } });
ACT({ id: 'guruBlokir', modes: ['admin'], menu: 'guru', izin: 'aksi_blokir', grup: 'Guru & Akademik', i: '🚫', judul: 'Blokir / buka blokir guru', kata: 'blokir buka blokir guru akun', steps: [
  auto('gl', async () => (await G('getAllGuru')).data || []),
  { key: 'g', tipe: 'search', awal: true, tanya: 'Guru mana? Ketik nama.', cari: (q, d) => d.gl.filter(g => !q || (g.nama_lengkap || '').toLowerCase().includes(q.toLowerCase())).slice(0, 12).map(g => ({ v: g, t: g.nama_lengkap, s: g.status || 'Aktif' })) },
  stKonf(d => d.g.status === 'Diblokir' ? `Aktifkan kembali ${$b(d.g.nama_lengkap)}?` : `Blokir ${$b(d.g.nama_lengkap)}? Guru tidak bisa login Portal Guru.`)
], log: d => d.g.nama_lengkap, run: async d => { const blok = d.g.status !== 'Diblokir'; await P('updateData', { table: 'master_guru', id: d.g.id, payload: { status: blok ? 'Diblokir' : 'Aktif' } }); return blok ? `✅ ${$b(d.g.nama_lengkap)} diblokir.` : `✅ Blokir ${$b(d.g.nama_lengkap)} dibuka.`; } });
ACT({ id: 'semesterAktif', modes: ['admin'], menu: 'semester', izin: 'aksi_sistem', grup: 'Guru & Akademik', i: '🗓️', judul: 'Aktifkan semester', kata: 'semester aktif ganjil genap tahun ajaran', steps: [
  { key: 's', tipe: 'choice', tanya: 'Semester mana yang diaktifkan?', opsi: async () => (await Q('master_semester?select=*&order=urutan.desc')).map(s => ({ v: s, t: s.label + (s.aktif ? ' ★ (aktif)' : '') })), kosong: 'Master semester masih kosong.' },
  stKonf(d => `Jadikan ${$b(d.s.label)} semester aktif? Semester lain otomatis non-aktif.`)
], log: d => d.s.label, run: async d => { await P('setSemesterAktif', { id: d.s.id, label: d.s.label }); return `✅ Semester aktif: ${$b(d.s.label)}.`; } });
ACT({ id: 'hariAtur', modes: ['admin'], menu: 'hari', izin: 'aksi_sistem', grup: 'Guru & Akademik', i: '📆', judul: 'Atur libur / hari masuk', kata: 'libur hari masuk tanggal merah kalender cuti', steps: [
  stTgl('tgl', 'Tanggal berapa?'), { key: 'st', tipe: 'choice', tanya: 'Statusnya?', opsi: ['Libur', 'Masuk'] },
  { key: 'ket', tipe: 'text', opsional: true, if: d => d.st === 'Libur', tanya: 'Keterangan libur? (mis. Libur nasional)' },
  stKonf(d => `Tandai ${$b(tglID(d.tgl))} sebagai ${$b(d.st)}?`)
], log: d => `${d.tgl} ${d.st}`, run: async d => {
  const ex = (await Q(`manajemen_hari?tanggal=eq.${d.tgl}&select=id&limit=1`))[0]; const dow = new Date(d.tgl + 'T00:00:00Z').getUTCDay() || 7;
  const b = { status: d.st, keterangan: d.st === 'Libur' ? (d.ket || 'Libur') : null, status_manual: true, updated_at: new Date().toISOString() };
  if (ex) await P('updateData', { table: 'manajemen_hari', id: ex.id, payload: b }); else await P('insertData', { table: 'manajemen_hari', payload: Object.assign({ tanggal: d.tgl, hari: dow }, b) });
  return `✅ ${$b(tglID(d.tgl))} ditandai ${$b(d.st)}.`; } });
ACT({ id: 'liburSync', modes: ['admin'], menu: 'hari', izin: 'aksi_sistem', grup: 'Guru & Akademik', i: '🔄', judul: 'Sinkron libur nasional', kata: 'sinkron sync libur nasional tahun', steps: [
  { key: 'th', tipe: 'number', def: () => wib().tgl.slice(0, 4), tanya: 'Tahun berapa?', validate: v => (/^20\d\d$/.test(v) ? null : 'Isi tahun 4 digit.') }
], run: async d => { const r = (await P('syncLibur', { token: S.sess.jwt, tahun: d.th })).data || {}; return r.ok ? `✅ Sumber ${esc(r.sumber)}: ${r.baru} libur baru, ${r.ubah} diperbarui, ${r.lewat} dilewati (diatur manual).` : `⚠️ ${esc(r.pesan || 'Tidak bisa sinkron.')}`; } });
ACT({ id: 'pengumumanBuat', modes: ['admin'], menu: 'pengumuman', izin: 'aksi_tambah', grup: 'Pengumuman', i: '📢', judul: 'Buat pengumuman', kata: 'pengumuman buat publikasi info darurat', steps: [
  { key : '{{ env('KEY_42') }}', tipe: 'text', min: 3, tanya: 'Judul pengumuman?' }, { key: 'isi', tipe: 'textarea', min: 5, tanya: 'Isi pengumuman?' },
  { key: 'tipe', tipe: 'choice', tanya: 'Tipe?', opsi: [{ v: 'info', t: 'ℹ️ Info' }, { v: 'penting', t: '⚠️ Penting' }, { v: 'darurat', t: '🚨 Darurat' }] },
  { key: 'wa', tipe: 'choice', tanya: 'Kirim juga lewat WA ke admin/guru?', opsi: [{ v: false, t: 'Tidak' }, { v: true, t: 'Ya' }] },
  stKonf(d => `Publikasikan ${$b(d.judul)}? Akan tampil di beranda semua siswa.`)
], log: d => d.judul, run: async d => { await P('insertData', { table: 'pengumuman', payload: { judul: d.judul, isi: d.isi, tipe: d.tipe, pembuat: FULL(), created_at: new Date().toISOString() } }); if (d.wa) await P('kirimPengumumanWA', { judul: d.judul, isi: d.isi, tipe: d.tipe }); return '✅ Pengumuman dipublikasikan.'; } });
ACT({ id: 'logLihat', modes: ['admin'], menu: 'history', grup: 'Sistem', i: '📜', judul: 'Log sistem terbaru', kata: 'log riwayat aktivitas sistem history', baca: true,
  run: async () => { const r = ((await G('getLogHistory')).data || []).slice(0, 10); return r.length ? r.map(x => `• ${tglID(x.created_at)} <b>${esc(x.aktor)}</b> (${esc(x.role)}): ${esc(x.aksi)}`).join('<br>') : 'Log kosong.'; } });
ACT({ id: 'reminder', modes: ['admin'], menu: 'settings', izin: 'aksi_sistem', grup: 'Sistem', i: '⏰', judul: 'Kirim reminder laporan pending', kata: 'reminder pending laporan wa', steps: [stKonf('Kirim WA reminder untuk laporan yang belum ditangani lebih dari 3 hari?')],
  run: async () => { const r = (await P('checkReminder', { hari: 3 })).data || {}; return r.checked ? `✅ Reminder terkirim: ${r.checked} laporan pending.` : '✅ Tidak ada laporan pending lebih dari 3 hari.'; } });
ACT({ id: 'berkala', modes: ['admin'], menu: 'settings', izin: 'aksi_sistem', grup: 'Sistem', i: '📈', judul: 'Kirim laporan berkala', kata: 'laporan berkala bulanan ringkasan wa telegram', steps: [stKonf('Kirim ringkasan laporan bulan ini ke WA & Telegram admin?')],
  run: async () => { const r = (await P('laporanBerkala', {})).data || {}; return `✅ Terkirim. ${esc(r.bulan || '')}: total ${r.total || 0}, selesai ${r.selesai || 0}, pending ${r.pending || 0}.`; } });
ACT({ id: 'backup', modes: ['admin'], menu: 'db', grup: 'Sistem', i: '🗄️', judul: 'Backup database', kata: 'backup database cadangan simpan sql', steps: [
  stKonf('Buat backup semua tabel dan simpan di Storage (link berlaku 7 hari)? Khusus Super Admin.')
], run: async () => { const r = (await P('backupDatabase', { token: S.sess.jwt, mode: 'storage' })).data || {}; const tot = (r.ringkasan || []).reduce((a, b) => a + b.baris, 0);
  return `✅ Backup ${esc(r.nama)} selesai: ${tot} baris. ${r.validasi && r.validasi.ok ? 'Lolos pemeriksaan restore ✓' : '⚠️ Pemeriksaan restore gagal'}.<br>` + (r.url ? `<a href="${esc(r.url)}" target="_blank" rel="noopener">Unduh backup (7 hari)</a>` : ''); } });
ACT({ id: 'apiKeyBuat', modes: ['admin'], menu: 'apikey', izin: 'aksi_tambah', grup: 'Sistem', i: '🔑', judul: 'Buat API key', kata: 'api key buat kunci aplikasi', steps: [
  { key: 'n', tipe: 'text', min: 3, tanya: 'Nama aplikasi/pengguna API key?' }, stKonf(d => `Buat API key untuk ${$b(d.n)}?`)
], log: d => d.n, run: async d => { const r = await P('insertData', { table: 'api_keys', payload: { nama_aplikasi: d.n.toUpperCase() } }); const k = r.data && r.data[0] && r.data[0].api_key; return k ? `✅ Key dibuat untuk ${$b(d.n)}:<br><code>${esc(k)}</code>` : '✅ Key dibuat. Lihat di menu API Key panel admin.'; } });
ACT({ id: 'panelAdmin', modes: ['admin'], grup: 'Sistem', i: '🖥️', judul: 'Buka panel admin lengkap', kata: 'panel admin lengkap naik kelas jadwal jabatan impor excel galeri grafik', baca: true,
  run: async () => 'Fitur yang butuh tabel besar atau unggah file (naik kelas, jadwal, jabatan, impor Excel, galeri foto, grafik) ada di <a href="/admin" target="_blank" rel="noopener">panel admin</a>.' });

/* ---------------------------------------------------------------- init */
window.GrisVA = { buka: () => { if (!S.open) toggle(); }, tutup: () => { if (S.open) toggle(); }, mode: () => S.mode };
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', build); else build();
})();
