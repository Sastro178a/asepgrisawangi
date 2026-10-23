@extends('layouts.app')

@section('content')


    <!-- ==================== MEMERIKSA SESI ==================== -->
<section id="splash" class="fixed inset-0 z-[110] bg-blue-gradient flex flex-col justify-center items-center text-white gap-3">
    <i class="fas fa-circle-notch fa-spin text-3xl">
    </i>
    <p class="text-sm font-bold">Memeriksa sesi OSIS...</p>
</section>
<!-- ==================== LOGIN OSIS ==================== -->
<section id="loginAdminSection" class="hidden fixed inset-0 z-[100] bg-blue-gradient justify-center items-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl p-8 w-full max-w-sm shadow-2xl relative my-auto">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-3xl mx-auto shadow-sm mb-3">
                <i class="fas fa-user-shield">
                </i>
            </div>
            <h2 class="text-xl font-black text-blue-800">Login OSIS</h2>
            <p class="text-xs text-gray-500 font-medium">Gunakan akun El Point dengan role OSIS</p>
        </div>
        <form id="formLoginAdmin" onsubmit="prosesLoginAdmin(event)" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Username</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                    </i>
                    <input type="text" id="adminUser" required="" autocomplete="username" placeholder="Username El Point" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                    </i>
                    <input type="password" id="adminPass" required="" autocomplete="current-password" placeholder="Password El Point" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
                </div>
            </div>
            <label class="flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-xl p-3 cursor-pointer">
                <input type="checkbox" id="adminIngat" class="w-4 h-4 accent-blue-600">
                <span class="text-[11px] font-bold text-blue-800">Ingat saya di perangkat ini (12 jam)</span>
            </label>
            <div id="loginAdminError" class="hidden bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-xl px-3 py-2">
            </div>
            <button type="submit" id="btnLoginAdmin" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-lg active:scale-95 flex items-center justify-center gap-2">
                    Masuk Panel <i class="fas fa-sign-in-alt text-sm">
        </i>
    </button>
</form>
</div>
</section>
<!-- ==================== PANEL PENGATURAN ==================== -->
<div id="mainAdminSection" class="hidden flex-1 h-screen flex-col overflow-y-auto pb-24">
    <header class="bg-blue-700 text-white p-4 shadow-md sticky top-0 z-50">
        <div class="max-w-4xl mx-auto flex justify-between items-center gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                    <i class="fas fa-cogs">
                    </i>
                </div>
                <div class="min-w-0">
                    <h1 class="font-black text-lg leading-none">Pengaturan Event</h1>
                    <p class="text-[10px] text-blue-200 font-bold uppercase tracking-wide truncate">Panel OSIS • <span id="namaAdmin">-</span>
                </p>
            </div>
        </div>
        <div class="flex gap-2 items-center shrink-0">
            <a href="#" id="btnLihatPendaftar" target="_blank" rel="noopener" class="hidden bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition items-center gap-1">
                <i class="fas fa-table">
                </i>
                <span class="hidden sm:inline">Hasil Pendaftar</span>
            </a>
            <a href="/event/grisaevent.html" class="bg-white text-blue-700 hover:bg-blue-50 px-3 py-1.5 rounded-lg text-xs font-bold transition">Lihat Web</a>
            <button onclick="logoutAdmin()" class="bg-red-500 hover:bg-red-600 text-white w-8 h-8 rounded-lg flex items-center justify-center transition" title="Keluar">
                <i class="fas fa-power-off">
                </i>
            </button>
        </div>
    </div>
</header>
<main class="max-w-4xl mx-auto p-4 mt-4 w-full">
    <div id="panelError" class="hidden mb-4 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-2xl px-4 py-3">
    </div>
    <form id="formSetEvent" onsubmit="simpanDataEvent(event)" class="space-y-6">
        <!-- 1. INFO UTAMA & POSTER -->
        <div class="glass-card rounded-2xl p-5 shadow-sm">
            <h2 class="font-black text-gray-800 text-sm mb-4 flex items-center gap-2 border-b pb-2">
                <i class="fas fa-edit text-blue-500">
                </i> Informasi Utama &amp; Poster</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Judul Event</label>
                            <input type="text" id="evJudul" required="" maxlength="120" placeholder="Contoh: Porseni &amp; Festival Pelajar" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Status Event</label>
                            <select id="evStatus" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-blue-500 transition">
                                <option value="Segera Hadir">Segera Hadir</option>
                                <option value="Sedang Berlangsung">Sedang Berlangsung</option>
                                <option value="Pendaftaran Ditutup">Pendaftaran Ditutup</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Link Poster (URL Gambar)</label>
                            <input type="url" id="evPoster" oninput="previewPoster()" placeholder="https://... (akhiran .jpg/.png)" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-blue-500 transition">
                            <img id="previewPosterImg" alt="Pratinjau poster" class="hidden mt-2 h-28 rounded-xl border border-gray-200 object-cover">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Singkat</label>
                        <textarea id="evDeskripsi" rows="8" required="" maxlength="3000" placeholder="Tuliskan penjelasan event di sini..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-blue-500 transition">
                        </textarea>
                    </div>
                </div>
            </div>
            <!-- 2. DATABASE PENDAFTAR (Rahasia) -->
            <div class="bg-gray-800 rounded-2xl p-5 shadow-sm text-white">
                <h2 class="font-black text-sm mb-2 flex items-center gap-2">
                    <i class="fas fa-database text-green-400">
                    </i> Database Pendaftar (Spreadsheet)</h2>
                    <p class="text-[10px] text-gray-300 mb-4">Link ini <strong>tidak dikirim ke web siswa</strong>. Server hanya mengirimkannya ke akun OSIS yang sedang login, dan tampil lewat tombol <em>Hasil Pendaftar</em> di pojok kanan atas.</p>
                    <input type="url" id="evSpreadsheet" placeholder="Masukkan Link Google Sheets rekap pendaftaran..." class="w-full bg-gray-700 border border-gray-600 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-green-500 transition">
                </div>
                <!-- 3. TOMBOL INFORMASI -->
                <div class="glass-card rounded-2xl p-5 shadow-sm">
                    <div class="flex justify-between items-center border-b pb-2 mb-4">
                        <h2 class="font-black text-gray-800 text-sm flex items-center gap-2">
                            <i class="fas fa-bullhorn text-yellow-500">
                            </i> Tombol Informasi Tambahan</h2>
                            <button type="button" onclick="tambahTombolInfo()" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded-lg text-xs font-bold transition flex items-center gap-1">
                                <i class="fas fa-plus">
                                </i> Tambah</button>
                            </div>
                            <p class="text-[10px] text-gray-500 mb-4">Tambahkan tombol pendukung seperti Juknis, Skema Lomba, atau Grup WA (maks. 20).</p>
                            <div id="containerTombolInfo" class="space-y-3">
                            </div>
                        </div>
                        <!-- 4. DOKUMENTASI -->
                        <div class="glass-card rounded-2xl p-5 shadow-sm">
                            <h2 class="font-black text-gray-800 text-sm mb-2 flex items-center gap-2 border-b pb-2">
                                <i class="fas fa-camera-retro text-blue-500">
                                </i> Tautan Galeri / Dokumentasi</h2>
                                <p class="text-[10px] text-gray-500 mb-3">Link Google Drive atau penyimpanan cloud untuk dokumentasi kegiatan. Muncul khusus di menu Dokumentasi.</p>
                                <input type="url" id="evDrive" placeholder="Masukkan Link Google Drive (https://drive.google...)" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-blue-500 transition">
                            </div>
                            <!-- 5. FORM LOMBA -->
                            <div class="glass-card rounded-2xl p-5 shadow-sm">
                                <div class="flex justify-between items-center border-b pb-2 mb-4">
                                    <h2 class="font-black text-gray-800 text-sm flex items-center gap-2">
                                        <i class="fas fa-clipboard-list text-purple-500">
                                        </i> Daftar Formulir Lomba (Multi-Event)</h2>
                                        <button type="button" onclick="tambahFormLomba()" class="bg-purple-100 text-purple-700 hover:bg-purple-200 px-3 py-1 rounded-lg text-xs font-bold transition flex items-center gap-1">
                                            <i class="fas fa-plus">
                                            </i> Tambah</button>
                                        </div>
                                        <p class="text-[10px] text-gray-500 mb-4">Tambahkan formulir Google Form untuk tiap cabang lomba (maks. 40).</p>
                                        <div id="containerFormLomba" class="space-y-3">
                                        </div>
                                    </div>
                                    <div class="fixed bottom-0 left-0 right-0 z-40 bg-white p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] border-t border-gray-200 flex justify-center">
                                        <button type="submit" id="btnSimpan" class="w-full max-w-4xl bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl text-sm font-black transition active:scale-95 shadow-lg flex items-center justify-center gap-2">
                                            <i class="fas fa-save">
                                            </i> Simpan Semua Pengaturan
                    </button>
                                        </div>
                                    </form>
                                </main>
                            </div>
                            <div id="toast" class="hidden fixed top-4 left-1/2 -translate-x-1/2 z-[200] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-2xl max-w-[90vw] text-center">
                            </div>
                            <script>
    // =====================================================================
    // SETEVENT — login = anggota_elpoint role OSIS; data disimpan ke Supabase via /api/notify
    // =====================================================================
    const $ = id => document.getElementById(id);
    const KEY_TOKEN = '{{ env('KEY_TOKEN') }}';
    let token = null;

    async function api(action, payload) {
        const r = await fetch('/api/notify', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ type: 'event', action, payload: payload || {} }) });
        const j = await r.json().catch(() => ({}));
        if (!r.ok || j.error) throw new Error(j.error || ('Gagal terhubung ke server (HTTP ' + r.status + ')'));
        return j.data;
    }
    function h(tag, cls, text) { const e = document.createElement(tag); if (cls) e.className = cls; if (text != null) e.textContent = text; return e; }
    function toast(msg, ok) {
        const t = $('toast'); t.textContent = msg; t.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-[200] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-2xl max-w-[90vw] text-center ' + (ok === false ? 'bg-red-600' : 'bg-green-600');
        clearTimeout(toast._t); toast._t = setTimeout(() => t.classList.add('hidden'), 4000);
    }

    // ---------- Sesi OSIS ----------
    function bacaToken() { return localStorage.getItem(KEY_TOKEN) || sessionStorage.getItem(KEY_TOKEN); }
    function hapusToken() { localStorage.removeItem(KEY_TOKEN); sessionStorage.removeItem(KEY_TOKEN); token = null; }

    async function mulai() {
        const t = bacaToken();
        if (t) {
            try {
                const d = await api('eventGet', { token: t });
                if (d.admin) { token = t; bukaPanel(d); return; }
            } catch (e) { /* token kedaluwarsa / server error → login ulang */ }
            hapusToken();
        }
        tampilLogin();
    }
    function tampilLogin() {
        $('splash').classList.add('hidden'); $('mainAdminSection').classList.add('hidden'); $('mainAdminSection').classList.remove('flex');
        $('loginAdminSection').classList.remove('hidden'); $('loginAdminSection').classList.add('flex');
    }
    async function prosesLoginAdmin(e) {
        e.preventDefault();
        const err = $('loginAdminError'); err.classList.add('hidden');
        const btn = $('btnLoginAdmin'), awal = btn.innerHTML; btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin">
                        </i> Memeriksa...';
        try {
            const r = await api('eventLoginOsis', { username: $('adminUser').value.trim(), password: $('adminPass').value });
            hapusToken();
            ($('adminIngat').checked ? localStorage : sessionStorage).setItem(KEY_TOKEN, r.token);
            token = r.token;
            const d = await api('eventGet', { token });
            $('adminPass').value = '';
            bukaPanel(d);
        } catch (ex) { err.textContent = ex.message; err.classList.remove('hidden'); }
        finally { btn.disabled = false; btn.innerHTML = awal; }
    }
    function logoutAdmin() { hapusToken(); location.reload(); }
    function sesiBerakhir() { hapusToken(); toast('Sesi OSIS berakhir. Silakan login ulang.', false); setTimeout(() => location.reload(), 1200); }

    function bukaPanel(d) {
        $('splash').classList.add('hidden'); $('loginAdminSection').classList.add('hidden'); $('loginAdminSection').classList.remove('flex');
        $('mainAdminSection').classList.remove('hidden'); $('mainAdminSection').classList.add('flex');
        $('bodyContainer').classList.remove('overflow-hidden');
        $('namaAdmin').textContent = d.admin_nama || 'OSIS';
        isiForm(d);
    }

    // ---------- Baris dinamis (dibangun lewat DOM, aman dari karakter kutip) ----------
    function inp(type, cls, ph, val) { const i = h('input', cls); i.type = type; i.placeholder = ph; i.value = val || ''; return i; }
    const ICONS = [['fas fa-link', '🔗 Link'], ['fas fa-file-pdf', '📄 PDF / Juknis'], ['fab fa-whatsapp', '💬 WhatsApp'], ['fas fa-sitemap', '📊 Skema']];

    function hapusBaris(btn) { btn.parentElement.remove(); }
    function tombolHapus() {
        const b = h('button', 'bg-red-50 text-red-500 hover:bg-red-100 w-full sm:w-10 h-8 rounded-lg flex items-center justify-center transition shrink-0'); b.type = 'button';
        b.appendChild(h('i', 'fas fa-trash text-xs')); b.addEventListener('click', () => hapusBaris(b)); return b;
    }
    function tambahTombolInfo(icon, label, url) {
        const row = h('div', 'row-info flex flex-col sm:flex-row gap-2 items-center bg-gray-50 p-3 rounded-xl border border-gray-100');
        const sel = h('select', 'input-icon bg-white border border-gray-200 rounded-lg px-2 py-2 text-xs focus:outline-none w-full sm:w-1/4');
        ICONS.forEach(([v, t]) => { const o = h('option', '', t); o.value = v; sel.appendChild(o); });
        sel.value = icon || 'fas fa-link';
        row.appendChild(sel);
        row.appendChild(inp('text', 'input-label w-full sm:w-1/3 bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none', 'Nama Tombol (cth: Juknis Futsal)', label));
        row.appendChild(inp('url', 'input-url w-full sm:flex-1 bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none', 'URL Link Direct (https://...)', url));
        row.appendChild(tombolHapus());
        $('containerTombolInfo').appendChild(row);
    }
    function tambahFormLomba(nama, url) {
        const row = h('div', 'row-lomba flex flex-col sm:flex-row gap-2 items-center bg-purple-50/30 p-3 rounded-xl border border-purple-100');
        const ic = h('div', 'w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center shrink-0'); ic.appendChild(h('i', 'fas fa-trophy text-xs'));
        row.appendChild(ic);
        row.appendChild(inp('text', 'input-nama w-full sm:w-1/3 bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none', 'Nama Lomba (cth: Futsal Putra)', nama));
        row.appendChild(inp('url', 'input-url w-full sm:flex-1 bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none', 'Link Google Form (https://docs...)', url));
        row.appendChild(tombolHapus());
        $('containerFormLomba').appendChild(row);
    }
    function previewPoster() {
        const v = $('evPoster').value.trim(), img = $('previewPosterImg');
        if (/^https?:\/\//i.test(v)) { img.src = v; img.classList.remove('hidden'); img.onerror = () => img.classList.add('hidden'); } else img.classList.add('hidden');
    }
    function tampilkanTombolPendaftar(url) {
        const b = $('btnLihatPendaftar');
        if (url) { b.href = url; b.classList.remove('hidden'); b.classList.add('flex'); } else { b.classList.add('hidden'); b.classList.remove('flex'); }
    }

    // ---------- Isi form dari database ----------
    function isiForm(d) {
        $('evJudul').value = d.judul || '';
        $('evStatus').value = d.status || 'Segera Hadir';
        $('evDeskripsi').value = d.deskripsi || '';
        $('evPoster').value = d.poster || '';
        $('evSpreadsheet').value = d.spreadsheet || '';
        $('evDrive').value = d.drive || '';
        previewPoster(); tampilkanTombolPendaftar(d.spreadsheet);
        $('containerTombolInfo').replaceChildren(); $('containerFormLomba').replaceChildren();
        (d.tombol_info || []).filter(t => !(t.icon || '').includes('camera-retro')).forEach(t => tambahTombolInfo(t.icon, t.label, t.url));
        if (!$('containerTombolInfo').childElementCount) tambahTombolInfo();
        (d.form_lomba || []).forEach(f => tambahFormLomba(f.nama, f.url));
        if (!$('containerFormLomba').childElementCount) tambahFormLomba();
    }

    // ---------- Simpan ke database ----------
    async function simpanDataEvent(e) {
        e.preventDefault();
        const tombol_info = [], form_lomba = [];
        document.querySelectorAll('.row-info').forEach(r => {
            const label = r.querySelector('.input-label').value.trim(), url = r.querySelector('.input-url').value.trim();
            if (label && url) tombol_info.push({ icon: r.querySelector('.input-icon').value, label, url });
        });
        document.querySelectorAll('.row-lomba').forEach(r => {
            const nama = r.querySelector('.input-nama').value.trim(), url = r.querySelector('.input-url').value.trim();
            if (nama && url) form_lomba.push({ nama, url });
        });
        const data = {
            judul: $('evJudul').value.trim(), status: $('evStatus').value, deskripsi: $('evDeskripsi').value.trim(),
            poster: $('evPoster').value.trim(), spreadsheet: $('evSpreadsheet').value.trim(), drive: $('evDrive').value.trim(),
            tombol_info, form_lomba
        };
        const btn = $('btnSimpan'), awal = btn.innerHTML; btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin">
                    </i> Menyimpan...';
        try {
            const r = await api('eventSimpan', { token, data });
            tampilkanTombolPendaftar(r.spreadsheet);
            toast('Tersimpan: ' + r.tombol + ' tombol info, ' + r.lomba + ' formulir lomba. Langsung tampil di Grisaevent.');
        } catch (ex) {
            if (/sesi osis/i.test(ex.message)) return sesiBerakhir();
            toast(ex.message, false);
        } finally { btn.disabled = false; btn.innerHTML = awal; }
    }

    document.addEventListener('DOMContentLoaded', mulai);
    </script>



@endsection