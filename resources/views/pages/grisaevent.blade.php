@extends('layouts.app')

@section('content')


    <!-- ==================== MEMERIKSA SESI ==================== -->
<section id="splash" class="fixed inset-0 z-[60] bg-blue-gradient flex flex-col justify-center items-center text-white gap-3">
    <i class="fas fa-circle-notch fa-spin text-3xl">
    </i>
    <p class="text-sm font-bold">Memeriksa sesi login...</p>
</section>
<!-- ==================== HALAMAN LOGIN ==================== -->
<section id="loginSection" class="hidden fixed inset-0 z-50 bg-blue-gradient justify-center items-center p-4 overflow-y-auto">
    <div class="glass-card rounded-3xl p-8 w-full max-w-sm shadow-2xl relative my-auto">
        <div class="text-center mb-6">
            <img id="logoLogin" src="{{ asset('/icon.png') }}" onerror="this.onerror=null; this.src='https://iili.io/qfChwml.png';" alt="Logo" class="w-20 h-20 mx-auto rounded-full border-4 border-white shadow-md mb-3 object-cover bg-white">
            <h2 class="text-2xl font-black text-blue-800">Grisaevent</h2>
            <p class="text-xs text-gray-500 font-medium">Portal Publikasi Event Sekolah</p>
        </div>
        <form id="formLogin" onsubmit="prosesLogin(event)" class="space-y-4" autocomplete="off">
            <div class="relative">
                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                    </i>
                    <input type="text" id="loginNama" required="" placeholder="Ketik nama kamu..." autocomplete="off" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm font-bold focus:outline-none focus:border-blue-500 transition">
                </div>
                <ul id="autoList" class="hidden absolute z-20 left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-xl shadow-2xl max-h-52 overflow-y-auto divide-y divide-gray-100">
                </ul>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Kelas</label>
                <div class="relative">
                    <i class="fas fa-chalkboard absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                    </i>
                    <input type="text" id="loginKelas" readonly="" placeholder="Terisi otomatis setelah memilih nama" class="w-full bg-gray-100 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm font-bold text-gray-600 cursor-not-allowed focus:outline-none">
                </div>
            </div>
            <!-- Muncul jika siswa SUDAH punya PIN -->
            <div id="wrapPin" class="hidden">
                <label class="block text-xs font-bold text-gray-700 mb-2">PIN (4 digit)</label>
                <div class="flex gap-3 justify-center" id="pinBoxes">
                    <input type="password" maxlength="1" inputmode="numeric" autocomplete="off" class="pin-box w-12 h-12 text-center text-xl font-black border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none transition">
                    <input type="password" maxlength="1" inputmode="numeric" autocomplete="off" class="pin-box w-12 h-12 text-center text-xl font-black border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none transition">
                    <input type="password" maxlength="1" inputmode="numeric" autocomplete="off" class="pin-box w-12 h-12 text-center text-xl font-black border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none transition">
                    <input type="password" maxlength="1" inputmode="numeric" autocomplete="off" class="pin-box w-12 h-12 text-center text-xl font-black border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none transition">
                </div>
            </div>
            <!-- Muncul jika siswa BELUM punya PIN -->
            <div id="wrapTgl">
                <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Lahir</label>
                <div class="relative">
                    <i class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                    </i>
                    <input type="date" id="loginTgl" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
                </div>
            </div>
            <p id="loginHint" class="text-[11px] text-gray-500 leading-relaxed">
                <i class="fas fa-info-circle text-blue-400 mr-1">
                </i>Pilih namamu dari daftar saran. Jika sudah punya PIN, kamu cukup memasukkan PIN.</p>
                <label class="flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-xl p-3 cursor-pointer">
                    <input type="checkbox" id="loginIngat" class="w-4 h-4 accent-blue-600">
                    <span class="text-[11px] font-bold text-blue-800">Ingat saya di perangkat ini</span>
                </label>
                <div id="loginError" class="hidden bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-xl px-3 py-2">
                </div>
                <button type="submit" id="btnLogin" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-lg active:scale-95 flex items-center justify-center gap-2">
                    Masuk Portal <i class="fas fa-arrow-right text-sm">
            </i>
        </button>
    </form>
</div>
</section>
<!-- ==================== HALAMAN UTAMA (DASHBOARD) ==================== -->
<div id="mainSection" class="hidden flex-1 h-screen flex flex-col md:flex-row bg-gray-50">
    <!-- HEADER MOBILE -->
    <header class="md:hidden bg-blue-700 text-white p-4 flex justify-between items-center shadow-md z-20">
        <div class="flex items-center gap-2">
            <img src="{{ asset('/icon.png') }}" data-logo="" onerror="this.onerror=null; this.src='https://iili.io/qfChwml.png';" class="w-8 h-8 rounded-full border border-white/20 bg-white object-cover">
            <h1 class="font-black text-lg leading-none">Grisaevent</h1>
        </div>
        <button onclick="logout()" class="text-blue-100 hover:text-white" title="Keluar">
            <i class="fas fa-sign-out-alt">
            </i>
        </button>
    </header>
    <!-- SIDEBAR DESKTOP -->
    <aside class="hidden md:flex flex-col w-64 bg-white border-r border-gray-200 h-full shadow-sm z-20">
        <div class="p-6 border-b border-gray-100 flex items-center gap-3">
            <img src="{{ asset('/icon.png') }}" data-logo="" onerror="this.onerror=null; this.src='https://iili.io/qfChwml.png';" class="w-10 h-10 rounded-full shadow-sm object-cover bg-white">
            <div>
                <h1 class="font-black text-blue-700 text-lg leading-tight">Grisaevent</h1>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wide">Portal Event</p>
            </div>
        </div>
        <nav class="flex-1 p-4 space-y-1" id="nav-desktop">
            <a href="#" onclick="pindahMenu('dashboard'); return false;" data-menu="dashboard" class="menu-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold bg-blue-50 text-blue-700 transition">
                <i class="fas fa-home w-5 text-center">
                </i> Dashboard</a>
                <a href="#" onclick="pindahMenu('informasi'); return false;" data-menu="informasi" class="menu-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-50 transition">
                    <i class="fas fa-bullhorn w-5 text-center">
                    </i> Info Event</a>
                    <a href="#" onclick="pindahMenu('pendaftaran'); return false;" data-menu="pendaftaran" class="menu-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-50 transition">
                        <i class="fas fa-clipboard-list w-5 text-center">
                        </i> Pendaftaran</a>
                        <a href="#" onclick="pindahMenu('dokumentasi'); return false;" data-menu="dokumentasi" class="menu-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-50 transition">
                            <i class="fas fa-camera-retro w-5 text-center">
                            </i> Dokumentasi</a>
                            <hr class="my-4 border-gray-100">
                            <a href="/index.html" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-50 transition">
                                <i class="fas fa-arrow-left w-5 text-center">
                                </i> Kembali Utama</a>
                            </nav>
                            <div class="p-4 border-t border-gray-100">
                                <button onclick="logout()" class="w-full flex items-center justify-center gap-2 bg-red-50 text-red-600 hover:bg-red-100 py-2.5 rounded-xl text-xs font-bold transition">
                                    <i class="fas fa-power-off">
                                    </i> Keluar
                </button>
                                </div>
                            </aside>
                            <!-- KONTEN UTAMA -->
                            <main class="flex-1 overflow-y-auto p-4 md:p-8 pb-24 md:pb-8">
                                <div id="eventError" class="hidden mb-4 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-2xl px-4 py-3">
                                </div>
                                <!-- 1. Dashboard -->
                                <div id="content-dashboard" class="menu-content block">
                                    <div class="bg-blue-600 rounded-3xl p-6 md:p-8 text-white relative overflow-hidden shadow-lg mb-6">
                                        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl">
                                        </div>
                                        <div class="relative z-10">
                                            <span id="badgeStatus" class="bg-white/20 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-4 inline-block">Memuat...</span>
                                            <h2 class="text-2xl md:text-3xl font-black mb-2" id="txtJudulEvent">Selamat Datang di Grisaevent</h2>
                                            <p id="txtDeskripsiEvent" class="text-blue-100 text-sm md:text-base max-w-2xl leading-relaxed whitespace-pre-line">Pusat informasi dan pendaftaran kegiatan SMK PGRI 1 Giri.</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 text-center">
                                            <i class="fas fa-user-circle text-blue-500 text-2xl mb-2">
                                            </i>
                                            <h4 class="font-bold text-sm text-gray-800 truncate" id="namaSiswaCard">Siswa</h4>
                                            <p class="text-xs text-gray-500" id="kelasSiswaCard">Login Aktif</p>
                                        </div>
                                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 text-center">
                                            <i class="fas fa-trophy text-green-500 text-2xl mb-2">
                                            </i>
                                            <h4 class="font-bold text-sm text-gray-800" id="countLomba">0</h4>
                                            <p class="text-xs text-gray-500">Cabang Lomba</p>
                                        </div>
                                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 text-center cursor-pointer hover:bg-gray-50 transition" onclick="pindahMenu('pendaftaran')">
                                            <i class="fas fa-clipboard-list text-yellow-500 text-2xl mb-2">
                                            </i>
                                            <h4 class="font-bold text-sm text-gray-800">Daftar Lomba</h4>
                                            <p class="text-[10px] text-blue-600 font-bold mt-1">Isi Formulir</p>
                                        </div>
                                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 text-center cursor-pointer hover:bg-gray-50 transition" onclick="bukaPoster()">
                                            <i class="fas fa-image text-purple-500 text-2xl mb-2">
                                            </i>
                                            <h4 class="font-bold text-sm text-gray-800">Lihat Poster</h4>
                                            <p class="text-[10px] text-blue-600 font-bold mt-1">Klik di sini</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- 2. Informasi Event -->
                                <div id="content-informasi" class="menu-content hidden">
                                    <h3 class="text-xl font-black text-gray-800 mb-1 flex items-center gap-2">
                                        <i class="fas fa-bullhorn text-blue-600">
                                        </i> Informasi &amp; Unduhan</h3>
                                        <div class="w-12 h-1 bg-blue-600 rounded-full mb-6">
                                        </div>
                                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                                            <p class="text-sm text-gray-600 leading-relaxed mb-6">Berikut adalah tautan penting terkait event ini. Klik tombol di bawah untuk mengunduh Juknis, melihat skema, atau bergabung ke grup WhatsApp panitia.</p>
                                            <div id="renderTombolInfo" class="flex flex-wrap gap-3">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 3. Pendaftaran -->
                                    <div id="content-pendaftaran" class="menu-content hidden">
                                        <h3 class="text-xl font-black text-gray-800 mb-1 flex items-center gap-2">
                                            <i class="fas fa-clipboard-list text-blue-600">
                                            </i> Pendaftaran Lomba</h3>
                                            <div class="w-12 h-1 bg-blue-600 rounded-full mb-6">
                                            </div>
                                            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mb-4">
                                                <p class="text-sm text-gray-600">Pilih cabang lomba di bawah ini untuk membuka formulir pendaftaran. Pengisian form dilakukan langsung tanpa berpindah halaman.</p>
                                            </div>
                                            <div id="renderDaftarLomba" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                            </div>
                                        </div>
                                        <!-- 4. Dokumentasi -->
                                        <div id="content-dokumentasi" class="menu-content hidden">
                                            <h3 class="text-xl font-black text-gray-800 mb-1 flex items-center gap-2">
                                                <i class="fas fa-camera-retro text-blue-600">
                                                </i> Dokumentasi Event</h3>
                                                <div class="w-12 h-1 bg-blue-600 rounded-full mb-6">
                                                </div>
                                                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col sm:flex-row items-center gap-6">
                                                    <div class="w-24 h-24 bg-gray-50 rounded-2xl flex items-center justify-center flex-shrink-0 border border-gray-100">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/1/12/Google_Drive_icon_%282020%29.svg" alt="Google Drive" class="w-12">
                                                    </div>
                                                    <div class="flex-1 text-center sm:text-left">
                                                        <h4 class="text-lg font-black text-gray-800 mb-1">Galeri Foto &amp; Video</h4>
                                                        <p class="text-sm text-gray-500 mb-4">Semua momen keseruan event sekolah disimpan dengan aman di Google Drive. Klik tombol di bawah untuk melihat atau mengunduh dokumentasi.</p>
                                                        <div id="renderLinkDokumentasi" class="flex flex-wrap justify-center sm:justify-start gap-3">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </main>
                                        <!-- BOTTOM NAV MOBILE -->
                                        <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex justify-around py-2 z-20 pb-safe">
                                            <a href="#" onclick="pindahMenu('dashboard'); return false;" data-nav="dashboard" class="nav-mobile flex flex-col items-center p-2 text-blue-600">
                                                <i class="fas fa-home text-lg mb-1">
                                                </i>
                                                <span class="text-[9px] font-bold">Beranda</span>
                                            </a>
                                            <a href="#" onclick="pindahMenu('informasi'); return false;" data-nav="informasi" class="nav-mobile flex flex-col items-center p-2 text-gray-400 hover:text-blue-600 transition">
                                                <i class="fas fa-bullhorn text-lg mb-1">
                                                </i>
                                                <span class="text-[9px] font-bold">Info</span>
                                            </a>
                                            <a href="#" onclick="pindahMenu('pendaftaran'); return false;" data-nav="pendaftaran" class="nav-mobile flex flex-col items-center p-2 text-gray-400 hover:text-blue-600 transition">
                                                <i class="fas fa-clipboard-list text-lg mb-1">
                                                </i>
                                                <span class="text-[9px] font-bold">Daftar</span>
                                            </a>
                                            <a href="#" onclick="pindahMenu('dokumentasi'); return false;" data-nav="dokumentasi" class="nav-mobile flex flex-col items-center p-2 text-gray-400 hover:text-blue-600 transition">
                                                <i class="fas fa-camera-retro text-lg mb-1">
                                                </i>
                                                <span class="text-[9px] font-bold">Galeri</span>
                                            </a>
                                        </nav>
                                    </div>
                                    <!-- Toast -->
                                    <div id="toast" class="hidden fixed top-4 left-1/2 -translate-x-1/2 z-[200] bg-gray-900 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-2xl max-w-[90vw] text-center">
                                    </div>
                                    <!-- ==================== MODALS ==================== -->
                                    <div id="posterModal" class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm hidden justify-center items-center p-4">
                                        <div class="relative w-full max-w-md modal-animate">
                                            <button onclick="tutupPoster()" class="absolute -top-4 -right-4 w-10 h-10 bg-white text-gray-800 rounded-full flex items-center justify-center font-bold shadow-xl hover:bg-gray-200 z-10">
                                                <i class="fas fa-times">
                                                </i>
                                            </button>
                                            <img id="imgPoster" src="" alt="Poster Event" class="w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain bg-white">
                                            <p id="posterKosong" class="hidden bg-white rounded-2xl p-6 text-center text-sm text-gray-500 font-bold">Poster belum diunggah panitia.</p>
                                        </div>
                                    </div>
                                    <div id="formModal" class="fixed inset-0 z-[100] bg-black/50 backdrop-blur-sm hidden justify-center items-end sm:items-center p-0 sm:p-4 transition-all">
                                        <div class="bg-white w-full max-w-3xl sm:rounded-2xl rounded-t-2xl h-[90vh] sm:h-[85vh] flex flex-col modal-animate shadow-2xl overflow-hidden">
                                            <div class="bg-blue-600 p-4 flex justify-between items-center text-white shrink-0 gap-3">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <i class="fas fa-clipboard-list">
                                                    </i>
                                                    <span id="judulModalForm" class="font-bold text-sm truncate">Formulir Pendaftaran</span>
                                                </div>
                                                <div class="flex items-center gap-2 shrink-0">
                                                    <a id="linkFormTab" href="#" target="_blank" rel="noopener" class="text-[11px] font-bold bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg transition">
                                                        <i class="fas fa-external-link-alt mr-1">
                                                        </i>Tab baru</a>
                                                        <button onclick="tutupFormModal()" class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition">
                                                            <i class="fas fa-times">
                                                            </i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="flex-1 w-full bg-gray-50 relative">
                                                    <iframe id="iframeForm" src="" width="100%" height="100%" frameborder="0" marginheight="0" marginwidth="0">Memuat…</iframe>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
    // =====================================================================
    // GRISAEVENT — semua data lewat /api/notify (Supabase di sisi server)
    // =====================================================================
    const $ = id => document.getElementById(id);
    const KEY_SESI = '{{ env('KEY_SESI') }}';       // sesi login Grisaevent sendiri
    const KEY_KELUAR = '{{ env('KEY_KELUAR') }}';      // tanda: user sengaja keluar dari Grisaevent di tab ini

    async function api(action, payload) {
        const r = await fetch('/api/notify', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ type: 'event', action, payload: payload || {} }) });
        const j = await r.json().catch(() => ({}));
        if (!r.ok || j.error) throw new Error(j.error || ('Gagal terhubung ke server (HTTP ' + r.status + ')'));
        return j.data;
    }
    function h(tag, cls, text) { const e = document.createElement(tag); if (cls) e.className = cls; if (text != null) e.textContent = text; return e; }
    function safeUrl(u) { return /^https?:\/\//i.test(u || '') ? u : '#'; }
    function toast(msg) { const t = $('toast'); t.textContent = msg; t.classList.remove('hidden'); clearTimeout(toast._t); toast._t = setTimeout(() => t.classList.add('hidden'), 3500); }

    let sesi = null, dataEvent = null, terpilih = null;

    // ---------- Sesi ----------
    function bacaSesiSiswa() { // sesi dari siswa.html (E-Aspirasi)
        try {
            const raw = (localStorage.getItem('ea_ingat') === 'true' ? localStorage.getItem('ea_session_data') : null) || sessionStorage.getItem('ea_session_data');
            if (!raw) return null;
            const d = JSON.parse(raw); return d && d.id ? { id: d.id, sumber: 'siswa' } : null;
        } catch (e) { return null; }
    }
    function bacaSesiGrisa() {
        try {
            const raw = localStorage.getItem(KEY_SESI) || sessionStorage.getItem(KEY_SESI);
            if (!raw) return null;
            const d = JSON.parse(raw); return d && d.id ? { id: d.id, sumber: 'grisa' } : null;
        } catch (e) { return null; }
    }
    function hapusSesiGrisa() { localStorage.removeItem(KEY_SESI); sessionStorage.removeItem(KEY_SESI); }

    async function mulai() {
        if (window.LOGO_URL) setLogo(window.LOGO_URL);
        if (window.NAMA_WEB) document.title = 'Grisaevent | ' + window.NAMA_WEB;
        let pesanBlokir = '';
        if (sessionStorage.getItem(KEY_KELUAR) !== '1') {
            for (const k of [bacaSesiGrisa(), bacaSesiSiswa()].filter(Boolean)) {
                try {
                    const u = await api('eventCekSesi', { id: k.id });
                    masuk(u, k.sumber === 'siswa' ? 'Masuk otomatis dari akun E-Aspirasi' : null);
                    return;
                } catch (e) {
                    if (k.sumber === 'grisa') hapusSesiGrisa();
                    if (/blokir/i.test(e.message)) pesanBlokir = e.message;
                }
            }
        }
        tampilLogin();
        if (pesanBlokir) tampilError(pesanBlokir);
    }
    function setLogo(url) { document.querySelectorAll('#logoLogin,[data-logo]').forEach(i => { i.src = url; }); }
    function tampilLogin() {
        $('splash').classList.add('hidden');
        $('mainSection').classList.add('hidden');
        $('loginSection').classList.remove('hidden'); $('loginSection').classList.add('flex');
    }
    function masuk(user, info) {
        sesi = user;
        sessionStorage.removeItem(KEY_KELUAR);
        $('splash').classList.add('hidden');
        $('loginSection').classList.add('hidden'); $('loginSection').classList.remove('flex');
        $('mainSection').classList.remove('hidden');
        $('namaSiswaCard').textContent = user.nama;
        $('kelasSiswaCard').textContent = user.kelas ? 'Kelas ' + user.kelas : 'Login Aktif';
        if (info) toast(info + ': ' + user.nama);
        muatEvent(true);
    }
    function logout() {
        hapusSesiGrisa();
        sessionStorage.setItem(KEY_KELUAR, '1'); // cegah auto-login dari sesi siswa.html di tab ini
        location.reload();
    }

    // ---------- Login: autofill + deteksi PIN ----------
    function tampilError(msg) { const e = $('loginError'); e.textContent = msg; e.classList.toggle('hidden', !msg); }
    function pinBoxes() { return Array.from(document.querySelectorAll('#pinBoxes .pin-box')); }
    function ambilPin() { return pinBoxes().map(b => b.value).join(''); }
    function kosongkanPin() { pinBoxes().forEach(b => { b.value = ''; }); }
    function aturModeLogin() {
        const punyaPin = terpilih && terpilih.punya_pin;
        $('wrapPin').classList.toggle('hidden', !punyaPin);
        $('wrapTgl').classList.toggle('hidden', !!punyaPin);
        $('loginTgl').required = false;
        const hint = $('loginHint');
        if (!terpilih) hint.innerHTML = '<i class="fas fa-info-circle text-blue-400 mr-1">
                                    </i>Pilih namamu dari daftar saran. Jika sudah punya PIN, kamu cukup memasukkan PIN.';
        else if (punyaPin) hint.innerHTML = '<i class="fas fa-lock text-green-500 mr-1">
                                </i>Kamu sudah punya PIN — masukkan PIN, tanggal lahir tidak diperlukan.';
        else hint.innerHTML = '<i class="fas fa-calendar-check text-orange-400 mr-1">
                            </i>Kamu belum punya PIN, jadi verifikasi pakai tanggal lahir. Buat PIN di E-Aspirasi supaya login berikutnya lebih cepat.';
    }

    let cariTimer = null, cariId = 0;
    $('loginNama').addEventListener('input', function () {
        terpilih = null; $('loginKelas').value = ''; tampilError(''); aturModeLogin();
        clearTimeout(cariTimer);
        const q = this.value.trim(); const list = $('autoList');
        if (q.length < 2) { list.classList.add('hidden'); return; }
        cariTimer = setTimeout(async () => {
            const myId = ++cariId;
            list.replaceChildren(h('li', 'px-4 py-3 text-xs text-blue-500 font-bold', 'Mencari data siswa...')); list.classList.remove('hidden');
            try {
                const rows = await api('eventCariSiswa', { nama: q });
                if (myId !== cariId) return;
                list.replaceChildren();
                if (!rows.length) { list.appendChild(h('li', 'px-4 py-3 text-xs text-red-500 font-bold', 'Nama tidak ditemukan. Coba ketik lebih lengkap.')); return; }
                rows.forEach(s => {
                    const li = h('li', 'px-4 py-3 hover:bg-blue-50 cursor-pointer transition');
                    li.appendChild(h('div', 'font-bold text-gray-800 text-sm', s.nama));
                    const sub = h('div', 'text-[10px] text-gray-500 font-bold mt-0.5 flex items-center gap-2 flex-wrap');
                    sub.appendChild(h('span', 'bg-gray-100 border px-2 py-0.5 rounded', s.kelas || '-'));
                    if (s.nama_panggilan) sub.appendChild(h('span', '', 'Panggilan: ' + s.nama_panggilan));
                    if (s.punya_pin) sub.appendChild(h('span', 'text-green-600', 'PIN aktif'));
                    if (s.diblokir) sub.appendChild(h('span', 'text-red-500', 'Diblokir'));
                    li.appendChild(sub);
                    li.addEventListener('mousedown', ev => { ev.preventDefault(); pilihSiswa(s); });
                    list.appendChild(li);
                });
            } catch (e) { if (myId === cariId) { list.replaceChildren(h('li', 'px-4 py-3 text-xs text-red-500 font-bold', e.message)); } }
        }, 350);
    });
    document.addEventListener('click', e => { if (!e.target.closest('#loginNama') && !e.target.closest('#autoList')) $('autoList').classList.add('hidden'); });

    function pilihSiswa(s) {
        terpilih = s;
        $('loginNama').value = s.nama; $('loginKelas').value = s.kelas || '';
        $('autoList').classList.add('hidden');
        kosongkanPin(); $('loginTgl').value = '';
        aturModeLogin();
        tampilError(s.diblokir ? 'Akses akun ini sedang diblokir. Hubungi admin sekolah.' : '');
        setTimeout(() => { (s.punya_pin ? pinBoxes()[0] : $('loginTgl')).focus(); }, 60);
    }

    // kotak PIN: loncat otomatis, backspace, tempel 4 digit
    pinBoxes().forEach((b, i, all) => {
        b.addEventListener('input', () => {
            b.value = b.value.replace(/\D/g, '').slice(-1);
            if (b.value && i < all.length - 1) all[i + 1].focus();
            if (ambilPin().length === 4 && b.value) $('formLogin').requestSubmit();
        });
        b.addEventListener('keydown', e => { if (e.key === 'Backspace' && !b.value && i > 0) { all[i - 1].focus(); all[i - 1].value = ''; } });
        b.addEventListener('paste', e => {
            const t = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 4);
            if (!t) return; e.preventDefault();
            t.split('').forEach((c, j) => { if (all[j]) all[j].value = c; });
            (all[Math.min(t.length, 3)]).focus();
        });
    });

    async function prosesLogin(e) {
        e.preventDefault(); tampilError('');
        if (!terpilih) return tampilError('Pilih namamu dari daftar saran yang muncul saat mengetik.');
        if (terpilih.diblokir) return tampilError('Akses akun ini sedang diblokir. Hubungi admin sekolah.');
        const payload = { id: terpilih.id };
        if (terpilih.punya_pin) {
            const pin = ambilPin(); if (pin.length < 4) return tampilError('Masukkan 4 digit PIN.');
            payload.pin = pin;
        } else {
            const t = $('loginTgl').value; if (!t) return tampilError('Isi tanggal lahir kamu.');
            payload.tgl = t;
        }
        const btn = $('btnLogin'); const awal = btn.innerHTML; btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin">
                        </i> Memverifikasi...';
        try {
            const u = await api('eventLoginSiswa', payload);
            const simpan = JSON.stringify({ id: u.id, nama: u.nama, kelas: u.kelas });
            hapusSesiGrisa();
            ($('loginIngat').checked ? localStorage : sessionStorage).setItem(KEY_SESI, simpan);
            masuk(u);
        } catch (ex) { tampilError(ex.message); kosongkanPin(); if (terpilih && terpilih.punya_pin) pinBoxes()[0].focus(); }
        finally { btn.disabled = false; btn.innerHTML = awal; }
    }

    // ---------- Data event ----------
    let posterSudahTampil = false;
    async function muatEvent(pertama) {
        try {
            dataEvent = await api('eventGet', {});
            $('eventError').classList.add('hidden');
            renderEvent(pertama);
        } catch (e) {
            $('eventError').textContent = 'Data event gagal dimuat: ' + e.message;
            $('eventError').classList.remove('hidden');
            $('badgeStatus').textContent = 'Tidak tersedia';
        }
    }
    function ikanAman(c) { return /^fa[sbr] fa-[\w-]+$/.test(c || '') ? c : 'fas fa-link'; }

    function renderEvent(pertama) {
        const d = dataEvent || {};
        $('txtJudulEvent').textContent = d.judul || 'Event Sedang Berlangsung';
        $('txtDeskripsiEvent').textContent = d.deskripsi || 'Belum ada penjelasan event dari panitia.';
        $('badgeStatus').textContent = d.status || 'Tersedia';
        if (d.poster) { $('imgPoster').src = d.poster; $('imgPoster').classList.remove('hidden'); $('posterKosong').classList.add('hidden'); }
        else { $('imgPoster').removeAttribute('src'); $('imgPoster').classList.add('hidden'); $('posterKosong').classList.remove('hidden'); }

        // Info + dokumentasi
        const info = $('renderTombolInfo'), dok = $('renderLinkDokumentasi'); info.replaceChildren(); dok.replaceChildren();
        const tombolDok = (label, url) => {
            const a = h('a', 'bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-md inline-flex items-center gap-2');
            a.href = safeUrl(url); a.target = '_blank'; a.rel = 'noopener noreferrer';
            a.appendChild(h('i', 'fas fa-cloud-download-alt')); a.appendChild(document.createTextNode(' ' + label)); dok.appendChild(a);
        };
        let adaDok = false;
        if (d.drive) { tombolDok('Buka Dokumentasi', d.drive); adaDok = true; }
        (d.tombol_info || []).forEach(t => {
            if ((t.icon || '').includes('camera-retro')) { tombolDok(t.label, t.url); adaDok = true; return; }
            const a = h('a', 'bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm');
            a.href = safeUrl(t.url); a.target = '_blank'; a.rel = 'noopener noreferrer';
            a.appendChild(h('i', ikanAman(t.icon) + ' text-blue-500 text-sm')); a.appendChild(document.createTextNode(' ' + t.label)); info.appendChild(a);
        });
        if (!info.childElementCount) info.appendChild(h('p', 'text-xs text-gray-400 italic w-full', 'Belum ada tautan informasi tambahan.'));
        if (!adaDok) dok.appendChild(h('p', 'text-xs text-red-400 italic', 'Panitia belum menyematkan link dokumentasi.'));

        // Lomba
        const box = $('renderDaftarLomba'); box.replaceChildren();
        const lomba = d.form_lomba || [];
        $('countLomba').textContent = lomba.length;
        if (!lomba.length) box.appendChild(h('p', 'text-xs text-gray-400 italic', 'Belum ada formulir lomba yang dibuka.'));
        lomba.forEach(f => {
            const card = h('div', 'bg-white border border-gray-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition');
            const top = h('div', 'flex items-center gap-3 mb-3');
            const ic = h('div', 'w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center'); ic.appendChild(h('i', 'fas fa-trophy text-lg'));
            top.appendChild(ic); top.appendChild(h('h4', 'font-bold text-sm text-gray-800 flex-1 leading-tight', f.nama));
            const btn = h('button', 'w-full bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white py-2 rounded-xl text-xs font-bold transition', 'Daftar Sekarang');
            btn.addEventListener('click', () => bukaFormModal(f.nama, f.url));
            card.appendChild(top); card.appendChild(btn); box.appendChild(card);
        });

        if (pertama && !posterSudahTampil && d.poster && sessionStorage.getItem('ge_poster') !== '1') {
            posterSudahTampil = true; sessionStorage.setItem('ge_poster', '1');
            setTimeout(bukaPoster, 500);
        }
    }
    document.addEventListener('visibilitychange', () => { if (!document.hidden && sesi) muatEvent(false); });

    // ---------- Navigasi & modal ----------
    function pindahMenu(id) {
        document.querySelectorAll('.menu-content').forEach(el => { el.classList.add('hidden'); el.classList.remove('block'); });
        const t = $('content-' + id); if (t) { t.classList.remove('hidden'); t.classList.add('block'); }
        document.querySelectorAll('#nav-desktop .menu-link').forEach(el => {
            el.className = el.getAttribute('data-menu') === id
                ? 'menu-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold bg-blue-50 text-blue-700 transition'
                : 'menu-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-50 transition';
        });
        document.querySelectorAll('.nav-mobile').forEach(el => {
            el.className = el.getAttribute('data-nav') === id
                ? 'nav-mobile flex flex-col items-center p-2 text-blue-600'
                : 'nav-mobile flex flex-col items-center p-2 text-gray-400 hover:text-blue-600 transition';
        });
    }
    function bukaPoster() { $('posterModal').classList.remove('hidden'); $('posterModal').classList.add('flex'); }
    function tutupPoster() { $('posterModal').classList.add('hidden'); $('posterModal').classList.remove('flex'); }
    function bukaFormModal(nama, url) {
        $('judulModalForm').textContent = 'Form: ' + nama;
        $('linkFormTab').href = safeUrl(url);
        let u = safeUrl(url);
        if (u.includes('docs.google.com/forms') && !u.includes('embedded=true')) u += (u.includes('?') ? '&' : '?') + 'embedded=true';
        $('iframeForm').src = u;
        $('formModal').classList.remove('hidden'); $('formModal').classList.add('flex');
    }
    function tutupFormModal() { $('formModal').classList.add('hidden'); $('formModal').classList.remove('flex'); $('iframeForm').src = ''; }

    document.addEventListener('DOMContentLoaded', mulai);
    </script>



@endsection