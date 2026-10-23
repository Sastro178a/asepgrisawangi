<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>Portal Control - E-Aspirasi Grisawangi</title>
        <link rel="icon" type="image/png" href="icon.png">
        <script src="https://cdn.tailwindcss.com">
        </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js">
        </script>
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        /* ===== BASE ===== */
        *, *::before, *::after { box-sizing: border-box; }
        html { -webkit-text-size-adjust: 100%; text-size-adjust: 100%; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        /* ===== SAFE AREA (notch/punch-hole HP) ===== */
        .safe-top    { padding-top: env(safe-area-inset-top, 0px); }
        .safe-bottom { padding-bottom: max(env(safe-area-inset-bottom, 0px), 16px); }
        .safe-x      { padding-left: env(safe-area-inset-left, 0px); padding-right: env(safe-area-inset-right, 0px); }

        /* ===== ANIMASI ===== */
        .fade-in       { animation: fadeIn 0.45s ease-out forwards; }
        .slide-up      { animation: slideUp 0.35s ease-out forwards; }
        .slide-down    { animation: slideDown 0.3s ease-out forwards; transform-origin: top; }
        .slide-in-left { animation: slideInLeft 0.3s ease-out forwards; }

        @keyframes fadeIn      { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
        @keyframes slideUp     { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        @keyframes slideDown   { from { opacity:0; transform:scaleY(0); } to { opacity:1; transform:scaleY(1); } }
        @keyframes slideInLeft { from { opacity:0; transform:translateX(-16px); } to { opacity:1; transform:translateX(0); } }

        /* ===== SCROLLBAR CUSTOM ===== */
        .hide-scroll::-webkit-scrollbar { display:none; }
        .hide-scroll { -ms-overflow-style:none; scrollbar-width:none; }
        .custom-dropdown::-webkit-scrollbar { width:5px; }
        .custom-dropdown::-webkit-scrollbar-track { background:#f1f5f9; border-radius:8px; }
        .custom-dropdown::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:8px; }
        .custom-dropdown::-webkit-scrollbar-thumb:hover { background:#94a3b8; }

        /* ===== TYPOGRAPHY RESPONSIVE ===== */
        @media (max-width: 360px) {
            html { font-size: 14px; }
        }

        /* ===== TABEL RESPONSIVE ===== */
        @media (max-width: 640px) {
            table { font-size: 0.72rem; }
            th, td { padding-left: 0.6rem !important; padding-right: 0.6rem !important; }
        }

        /* ===== FORM ===== */
        input:not([type="checkbox"]):not([type="radio"]), textarea, select {
            -webkit-appearance: none;
            appearance: none;
            outline: none;
        }
        /* Checkbox tampil normal dan bisa diklik */
        input[type="checkbox"], input[type="radio"] {
            -webkit-appearance: auto;
            appearance: auto;
            cursor: pointer;
        }
        input[type="date"]::-webkit-calendar-picker-indicator { opacity: 0.5; }

        /* ===== BUTTON ===== */
        button:active, a:active { transform: scale(0.97); }
        button:disabled { opacity: 0.6; cursor: not-allowed; transform: none !important; }

        /* ===== DARK MODE (semua halaman) ===== */
        body.dark-mode { background-color: #0f172a !important; color: #f1f5f9; }
        body.dark-mode .bg-white   { background-color: #1e293b !important; }
        body.dark-mode .bg-gray-50 { background-color: #0f172a !important; }
        body.dark-mode .bg-gray-100 { background-color: #1e293b !important; }
        body.dark-mode .bg-gray-200 { background-color: #334155 !important; }
        body.dark-mode .text-gray-800 { color: #f1f5f9 !important; }
        body.dark-mode .text-gray-700 { color: #e2e8f0 !important; }
        body.dark-mode .text-gray-600 { color: #cbd5e1 !important; }
        body.dark-mode .text-gray-500 { color: #94a3b8 !important; }
        body.dark-mode .border-gray-100,
        body.dark-mode .border-gray-200 { border-color: #334155 !important; }
        body.dark-mode header { background-color: rgba(15,23,42,.95) !important; border-color: #334155 !important; }
        body.dark-mode #sidebar, body.dark-mode #sidebarMenu { background-color: #0f172a !important; }
        body.dark-mode input:not([type="checkbox"]):not([type="radio"]), body.dark-mode textarea, body.dark-mode select {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
            border-color: #334155 !important;
        }
        body.dark-mode .shadow-sm { box-shadow: 0 1px 4px rgba(0,0,0,.4) !important; }
        body.dark-mode .shadow-xl { box-shadow: 0 8px 24px rgba(0,0,0,.5) !important; }
.fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .custom-dropdown::-webkit-scrollbar { width: 6px; }
        .custom-dropdown::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 8px; }
        .custom-dropdown::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }

        /* ===== CHECKBOX STYLING ===== */
        input[type="checkbox"].bulk-check,
        input[type="checkbox"].kelas-check,
        input[type="checkbox"].role-check,
        input[type="checkbox"].buku-check,
        input[type="checkbox"]#checkAllMain,
        input[type="checkbox"]#checkAllBuku,
        input[type="checkbox"]#checkAllKelas,
        input[type="checkbox"]#checkAllRole {
            width: 18px !important;
            height: 18px !important;
            border-radius: 5px !important;
            border: 2px solid #d1d5db !important;
            cursor: pointer !important;
            accent-color: #2563eb;
            transition: border-color 0.15s, transform 0.1s;
            flex-shrink: 0;
        }
        input[type="checkbox"].bulk-check:hover,
        input[type="checkbox"].kelas-check:hover,
        input[type="checkbox"].role-check:hover,
        input[type="checkbox"].buku-check:hover,
        input[type="checkbox"]#checkAllMain:hover {
            border-color: #2563eb !important;
            transform: scale(1.1);
        }
        input[type="checkbox"]:checked {
            border-color: #2563eb !important;
            background-color: #2563eb;
        }
        /* Kolom checkbox di tabel - lebar tetap dan centered */
        #mainDataTable th:first-child,
        #mainDataTable td:first-child {
            width: 52px !important;
            min-width: 52px !important;
            text-align: center !important;
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
    </style>
        <script src="{{ asset('/tampilan-web.js') }}" data-observe="0">
        </script>
    </head>
    <body class="antialiased">

    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

<script>
        const SUPABASE_URL : "{{ env('SUPABASE_URL') }}"; 
        const SUPABASE_KEY = "{{ env('SUPABASE_KEY') }}"; 
        // Initialize Supabase client with error guard
        let supaClient;
        try {
            supaClient = window.supabase.createClient(SUPABASE_URL, SUPABASE_KEY);
        } catch(e) {
            console.error('Supabase init failed:', e);
        }

        async function callBackend(payload) {
            const response = await fetch('/api/notify', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
            if (!response.ok) throw new Error(await response.text()); return await response.json();
        }

        function formatTgl(isoString) { if(!isoString) return "-"; return new Date(isoString).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric', hour:'2-digit', minute:'2-digit'}); }
        function togglePassword(inputId, iconEl) { const input = document.getElementById(inputId); if (input.type === "password") { input.type = "text"; iconEl.classList.replace('fa-eye', 'fa-eye-slash'); } else { input.type = "password"; iconEl.classList.replace('fa-eye-slash', 'fa-eye'); } }

        const tindakLanjutDataAdmin = [
            { max: 10, tindak: "Ditangani tim ketertiban dan wali kelas", kompensasi: "Peringatan lisan" },
            { max: 20, tindak: "Ditangani tim ketertiban dan wali kelas", kompensasi: "Membersihkan lingkungan sekolah" },
            { max: 30, tindak: "Pemanggilan orang tua (SP 1)", kompensasi: "Peringatan tertulis dan tugas khusus" },
            { max: 50, tindak: "Pemanggilan orang tua (SP 2)", kompensasi: "Surat pernyataan bermaterai" },
            { max: 100, tindak: "Pemanggilan orang tua & Skorsing", kompensasi: "Skorsing sesuai hari kalender" },
            { max: 999, tindak: "Konferensi Kasus (Drop Out)", kompensasi: "Dikembalikan kepada orang tua" }
        ];

        function getSanksiTextAdmin(point) {
            if (!point || point <= 0) return { t: "-", k: "-" };
            for(let i=0; i<tindakLanjutDataAdmin.length; i++){ if(point <= tindakLanjutDataAdmin[i].max) return { t: tindakLanjutDataAdmin[i].tindak, k: tindakLanjutDataAdmin[i].kompensasi }; }
            return { t: tindakLanjutDataAdmin[tindakLanjutDataAdmin.length-1].tindak, k: tindakLanjutDataAdmin[tindakLanjutDataAdmin.length-1].kompensasi };
        }

        let userData = null; let activeView = 'dashboard'; let currentTableId = ''; let globalDataStore = {}; let isSuperAdminUnlocked = false; let currentPage = 1; const rowsPerPage = 15; let currentFilteredData = [];

        const MENU_CONFIG = [
            { id: 'dashboard',       icon: 'fa-home',            label: 'Beranda Admin',        roles: ['ALL'] },
            // === LAYANAN UTAMA ===
            { id: 'laporan',         grp: 'Layanan Utama', icon: 'fa-bullhorn',        label: 'Tiket Laporan',       roles: ['laporan'],              table: 'aspirasi' },
            { id: 'masukan',         grp: 'Layanan Utama', icon: 'fa-comment-dots',    label: 'Kotak Masukan',       roles: ['masukan'],              table: 'aspirasi' },
            { id: 'konselor',        grp: 'Layanan Utama', icon: 'fa-user-graduate',   label: 'Jadwal Konselor',     roles: ['konselor'],             table: 'aspirasi' },
            { id: 'absensi',         grp: 'Layanan Utama', icon: 'fa-calendar-check',  label: 'Rekap Absensi',       roles: ['absensi', 'Super Admin'], table: 'absensi' },
            { id: 'poin',            grp: 'Layanan Utama', icon: 'fa-gavel',           label: 'Poin Pelanggaran',    roles: ['poin'],                 table: 'siswa' },
            { id: 'buku_saku',       grp: 'Layanan Utama', icon: 'fa-book',            label: 'Master Pelanggaran',  roles: ['poin'],                 table: 'master_pelanggaran' },
            // === DATA SISWA ===
            { id: 'siswa',           grp: 'Data Siswa', icon: 'fa-users',          label: 'Data Master Siswa',   roles: ['siswa_view', 'siswa_edit'], table: 'siswa' },
            { id: 'alumni',          grp: 'Data Siswa', icon: 'fa-user-graduate',  label: 'Data Alumni',         roles: ['siswa_edit', 'Super Admin'], table: 'alumni' },
            { id: 'kelas',           grp: 'Data Siswa', icon: 'fa-layer-group',    label: 'Data Master Kelas',   roles: ['kelas'],                table: 'daftar_kelas' },
            { id: 'anggota_elpoint', grp: 'Data Siswa', icon: 'fa-user-secret',    label: 'Anggota El Point',    roles: ['anggota_elpoint'],      table: 'anggota_elpoint' },
            // === GURU & AKADEMIK ===
            { id: 'guru',            grp: 'Guru & Akademik', icon: 'fa-chalkboard-teacher', label: 'Master Akun & Guru',   roles: ['Super Admin','Kepala Sekolah'] },
            { id: 'guru_nama',       grp: 'Guru & Akademik', icon: 'fa-id-card',            label: 'Manajemen Nama Guru',  roles: ['Super Admin', 'settings'] },
            { id: 'lapguru',         grp: 'Guru & Akademik', icon: 'fa-flag',               label: 'Laporan Guru',         roles: ['Super Admin','Kepala Sekolah','Guru BK'], table: 'laporan_guru' },
            { id: 'mapel',           grp: 'Guru & Akademik', icon: 'fa-book-open',          label: 'Master Mapel',         roles: ['Super Admin','settings'] },
            { id: 'semester',        grp: 'Guru & Akademik', icon: 'fa-calendar-alt',       label: 'Master Semester',      roles: ['Super Admin','settings'], table: 'master_semester' },
            { id: 'jam_mapel',       grp: 'Guru & Akademik', icon: 'fa-clock',              label: 'Manajemen Jam Mapel',  roles: ['Super Admin', 'settings'] },
            { id: 'hari',            grp: 'Guru & Akademik', icon: 'fa-calendar-day',       label: 'Manajemen Hari',       roles: ['Super Admin', 'settings'] },
            { id: 'jadwal',          grp: 'Guru & Akademik', icon: 'fa-table',              label: 'Manajemen Jadwal',     roles: ['Super Admin', 'settings'] },
            // === ADMIN & HAK AKSES ===
            { id: 'admin',           grp: 'Admin & Hak Akses', icon: 'fa-user-shield', label: 'Manajemen Admin',   roles: ['Super Admin'], table: 'hak_akses_admin' },
            { id: 'role',            grp: 'Admin & Hak Akses', icon: 'fa-key',         label: 'Jabatan & Rules',   roles: ['role'],        table: 'daftar_role' },
            // === FITUR TAMBAHAN ===
            { id: 'pengumuman',      grp: 'Fitur Tambahan', icon: 'fa-bullhorn',    label: 'Pengumuman',          roles: ['laporan', 'Super Admin'], table: 'pengumuman' },
            { id: 'penilaian_admin', grp: 'Fitur Tambahan', icon: 'fa-star',        label: 'Statistik Penilaian', roles: ['Super Admin', 'settings'] },
            { id: 'menu_siswa',      grp: 'Fitur Tambahan', icon: 'fa-th-large',    label: 'Menu Siswa Tambahan', roles: ['Super Admin', 'settings'] },
            { id: 'tampilan_web',    grp: 'Fitur Tambahan', icon: 'fa-palette',     label: 'Pengaturan Tampilan Web', roles: ['Super Admin', 'settings'] },
            // === SISTEM (paling bawah) ===
            { id: 'history',         grp: 'Sistem', icon: 'fa-history',       label: 'Log Sistem',          roles: ['history'],        table: 'log_history' },
            { id: 'apikey',          grp: 'Sistem', icon: 'fa-network-wired', label: 'Manajemen API Key',   roles: ['Super Admin'],    table: 'api_keys' },
            { id: 'settings',        grp: 'Sistem', icon: 'fa-cogs',          label: 'Pengaturan Web',      roles: ['settings'] },
            { id: 'db',              grp: 'Sistem', icon: 'fa-database',      label: 'Manajemen Database',  roles: ['Super Admin'] }
        ];

        function initRealtimeClock() { 
            const updateClock = () => { const now = new Date(); const options = window.innerWidth < 768 ? { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' } : { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' }; document.getElementById('realtimeClock').textContent = now.toLocaleDateString('id-ID', options).replace(/\./g, ':'); };
            updateClock(); setInterval(updateClock, 1000);
        }
        initRealtimeClock();

        async function loadSettings() {
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getPengaturan' });
                if(res.data) {
                    res.data.forEach(p => {
                        if(p.nama_pengaturan==='guru_batasi_jadwal') { const el=document.getElementById('setGuruBatasiJadwal'); if(el) el.checked = (p.nilai !== 'false'); }
                        if(p.nama_pengaturan==='wa_ea') document.getElementById('setWaEa').value = p.nilai;
                        if(p.nama_pengaturan==='wa_guru') document.getElementById('setWaGuru').value = p.nilai;
                        if(p.nama_pengaturan==='wa_dev') document.getElementById('setWaDev').value = p.nilai;
                        if(p.nama_pengaturan==='wa_ea_label') { const el=document.getElementById('setWaEaLabel'); if(el) el.value=p.nilai||'WA E-Aspirasi'; }
                        if(p.nama_pengaturan==='wa_guru_label') { const el=document.getElementById('setWaGuruLabel'); if(el) el.value=p.nilai||'WA Admin/Guru'; }
                        if(p.nama_pengaturan==='wa_dev_label') { const el=document.getElementById('setWaDevLabel'); if(el) el.value=p.nilai||'WA IT / Dev'; }
                        if(p.nama_pengaturan==='tele_bot_token') document.getElementById('setTeleToken').value = p.nilai;
                        if(p.nama_pengaturan==='tele_chat_ids') document.getElementById('setTeleChat').value = p.nilai;
                        if(p.nama_pengaturan==='freeimage_key') document.getElementById('setFreeImage').value = p.nilai;
                    if(p.nama_pengaturan==='imgbb_key') { const el=document.getElementById('setImgbb'); if(el) el.value=p.nilai; }
                    if(p.nama_pengaturan==='absen_jam_mulai') { const el=document.getElementById('setAbsenJamMulai'); if(el) el.value=p.nilai||'7'; }
                    if(p.nama_pengaturan==='wajib_geofence') {
                        const btn=document.getElementById('toggleWajibGeofence');
                        if(btn) {
                            const on = p.nilai==='true';
                            btn.dataset.aktif = on?'true':'false';
                            btn.className = btn.className.replace(/bg-[a-z]+-\d+/g, on?'bg-orange-600':'bg-gray-600');
                            btn.querySelector('span').style.transform = on?'translateX(24px)':'translateX(0)';
                        }
                    }
                    if(p.nama_pengaturan==='aktifkan_manual_absen') {
                        const btn=document.getElementById('toggleManualAbsen');
                        if(btn) {
                            const on = p.nilai==='true';
                            btn.dataset.aktif = on?'true':'false';
                            btn.className = btn.className.replace(/bg-\w+-\d+/g, on?'bg-blue-600':'bg-gray-600');
                            btn.querySelector('span').style.transform = on?'translateX(24px)':'translateX(0)';
                        }
                    }
                    if(p.nama_pengaturan==='absen_poin_terlambat') { const el=document.getElementById('setAbsenPoinTerlambat'); if(el) el.value=p.nilai||'6'; }
                    if(p.nama_pengaturan==='geofence_lat')  { const el=document.getElementById('setGeofenceLat'); if(el) el.value=p.nilai||''; }
                    if(p.nama_pengaturan==='geofence_lon')  { const el=document.getElementById('setGeofenceLon'); if(el) el.value=p.nilai||''; }
                    if(p.nama_pengaturan==='geofence_radius') { const el=document.getElementById('setGeofenceRadius'); if(el) el.value=p.nilai||'100'; }
                    if(p.nama_pengaturan==='maps_api_key')  { const el=document.getElementById('setMapsApiKey'); if(el) el.value=p.nilai||''; }
                    if(p.nama_pengaturan==='hari_libur_custom') { const el=document.getElementById('setHariLibur'); if(el) el.value=p.nilai||''; }
                        if(p.nama_pengaturan==='kelas_readonly') document.getElementById('setKelasReadonly').checked = (p.nilai === 'true');
                        if(p.nama_pengaturan==='tampilkan_poin') document.getElementById('setTampilkanPoin').checked = (p.nilai === 'true');
                        if(p.nama_pengaturan==='link_audio_bgm') document.getElementById('setAudioBgm').value = p.nilai;
                        if(p.nama_pengaturan==='brosur_aktif') document.getElementById('setBrosurAktif').checked = (p.nilai === 'true');
                        if(p.nama_pengaturan==='link_brosur') document.getElementById('setLinkBrosur').value = p.nilai;
                        if(p.nama_pengaturan==='link_intro') document.getElementById('setLinkIntro').value = p.nilai;
                        if(p.nama_pengaturan==='wa_template') { const el = document.getElementById('setWaTemplate'); if(el) el.value = p.nilai; }
                        if(p.nama_pengaturan==='wa_footer') { const el = document.getElementById('setWaFooter'); if(el) el.value = p.nilai; }
                        if(p.nama_pengaturan==='tampilkan_komentar') { const el = document.getElementById('setTampilkanKomentar'); if(el) el.checked = (p.nilai === 'true'); }
                        if(p.nama_pengaturan==='fonnte_token') { const el = document.getElementById('setFonnteToken'); if(el) el.value = p.nilai; }
                        if(p.nama_pengaturan==='fonnte_token_admin') { const el = document.getElementById('setFonnteAdmin'); if(el) el.value = p.nilai; }
                        if(p.nama_pengaturan==='onesignal_app_id') { const el = document.getElementById('setOnesignalAppId'); if(el) el.value = p.nilai; }
                        if(p.nama_pengaturan==='onesignal_api_key') { const el = document.getElementById('setOnesignalApiKey'); if(el) el.value = p.nilai; }
                        if(p.nama_pengaturan==='vapid_private_key') { const el = document.getElementById('setVapidPrivate'); if(el) el.value = p.nilai; }
                        if(p.nama_pengaturan==='vapid_public_key') { const el = document.getElementById('setVapidPublic'); if(el) el.value = p.nilai; }
                        if(p.nama_pengaturan==='google_sheets_key') { const el = document.getElementById('setGoogleSheetsKey'); if(el) el.value = p.nilai; }
                        if(p.nama_pengaturan==='google_sheet_id') { const el = document.getElementById('setGoogleSheetId'); if(el) el.value = p.nilai; }
                    });
                }
            } catch(e) {}
        }

        async function simpanPengaturan() {
            const konfirm = await Swal.fire({ title: 'Simpan Pengaturan?', text: 'Semua konfigurasi akan diperbarui di database.', icon: 'question', showCancelButton: true, confirmButtonColor: '#2563eb', confirmButtonText: 'Iya, Simpan', cancelButtonText: 'Batal' });
            if (!konfirm.isConfirmed) return;
            const btn = document.getElementById('btnSaveSetting'); const oH = btn.innerHTML; btn.innerHTML = '<i class="fas fa-spinner fa-spin">
</i> Menyimpan...'; btn.disabled = true;
            const arr = [ {nama_pengaturan:'guru_batasi_jadwal', nilai:document.getElementById('setGuruBatasiJadwal')?.checked?'true':'false'}, {nama_pengaturan:'wa_ea', nilai:document.getElementById('setWaEa').value}, {nama_pengaturan:'wa_guru', nilai:document.getElementById('setWaGuru').value}, {nama_pengaturan:'wa_dev', nilai:document.getElementById('setWaDev').value}, {nama_pengaturan:'wa_ea_label', nilai:document.getElementById('setWaEaLabel')?.value||'WA E-Aspirasi'}, {nama_pengaturan:'wa_guru_label', nilai:document.getElementById('setWaGuruLabel')?.value||'WA Admin/Guru'}, {nama_pengaturan:'wa_dev_label', nilai:document.getElementById('setWaDevLabel')?.value||'WA IT / Dev'}, {nama_pengaturan:'tele_bot_token', nilai:document.getElementById('setTeleToken').value}, {nama_pengaturan:'tele_chat_ids', nilai:document.getElementById('setTeleChat').value}, {nama_pengaturan:'freeimage_key', nilai:document.getElementById('setFreeImage').value}, {nama_pengaturan:'imgbb_key', nilai:document.getElementById('setImgbb')?.value||''},
                    {nama_pengaturan:'absen_jam_mulai', nilai:document.getElementById('setAbsenJamMulai')?.value||'7'},
                    {nama_pengaturan:'aktifkan_manual_absen', nilai:document.getElementById('toggleManualAbsen')?.dataset?.aktif||'false'},
                    {nama_pengaturan:'wajib_geofence', nilai:document.getElementById('toggleWajibGeofence')?.dataset?.aktif||'false'},
                    {nama_pengaturan:'absen_poin_terlambat', nilai:document.getElementById('setAbsenPoinTerlambat')?.value||'6'},
                    {nama_pengaturan:'geofence_lat',    nilai:document.getElementById('setGeofenceLat')?.value||''},
                    {nama_pengaturan:'geofence_lon',    nilai:document.getElementById('setGeofenceLon')?.value||''},
                    {nama_pengaturan:'geofence_radius', nilai:document.getElementById('setGeofenceRadius')?.value||'100'},
                    {nama_pengaturan:'maps_api_key',    nilai:document.getElementById('setMapsApiKey')?.value||''},
                    {nama_pengaturan:'hari_libur_custom', nilai:document.getElementById('setHariLibur')?.value||''}, {nama_pengaturan:'kelas_readonly', nilai:document.getElementById('setKelasReadonly').checked?'true':'false'}, {nama_pengaturan:'tampilkan_poin', nilai:document.getElementById('setTampilkanPoin').checked?'true':'false'}, {nama_pengaturan:'link_audio_bgm', nilai:document.getElementById('setAudioBgm').value}, {nama_pengaturan:'brosur_aktif', nilai:document.getElementById('setBrosurAktif').checked?'true':'false'}, {nama_pengaturan:'link_brosur', nilai:document.getElementById('setLinkBrosur').value}, {nama_pengaturan:'link_intro', nilai:document.getElementById('setLinkIntro').value}, {nama_pengaturan:'tampilkan_komentar', nilai:document.getElementById('setTampilkanKomentar')?.checked?'true':'false'},
                {nama_pengaturan:'wa_template', nilai:document.getElementById('setWaTemplate')?.value||''},
                {nama_pengaturan:'wa_footer', nilai:document.getElementById('setWaFooter')?.value||''},
                {nama_pengaturan:'tampilkan_komentar', nilai:document.getElementById('setTampilkanKomentar')?.checked?'true':'false'},
                {nama_pengaturan:'wa_template', nilai:document.getElementById('setWaTemplate')?.value||''},
                {nama_pengaturan:'wa_footer', nilai:document.getElementById('setWaFooter')?.value||''},
                {nama_pengaturan:'fonnte_token',       nilai:document.getElementById('setFonnteToken')?.value||''},
                {nama_pengaturan:'fonnte_token_admin', nilai:document.getElementById('setFonnteAdmin')?.value||''},
                {nama_pengaturan:'onesignal_app_id',   nilai:document.getElementById('setOnesignalAppId')?.value||''},
                {nama_pengaturan:'onesignal_api_key',  nilai:document.getElementById('setOnesignalApiKey')?.value||''},
                {nama_pengaturan:'vapid_private_key',  nilai:document.getElementById('setVapidPrivate')?.value||''},
                {nama_pengaturan:'vapid_public_key',   nilai:document.getElementById('setVapidPublic')?.value||''},
                {nama_pengaturan:'google_sheets_key',  nilai:document.getElementById('setGoogleSheetsKey')?.value||''},
                {nama_pengaturan:'google_sheet_id',    nilai:document.getElementById('setGoogleSheetId')?.value||''} ];
            try { await callBackend({ type: 'supabase_post', action: 'updatePengaturan', payload: arr }); Swal.fire({toast: true, position: 'top-end', icon: 'success', title: 'Tersimpan', showConfirmButton: false, timer: 2000}); } catch(e) { Swal.fire('Kesalahan', e.message, 'error'); } finally { btn.innerHTML = oH; btn.disabled = false; }
        }
        

        // =======================================================
        // PENGATURAN TAMPILAN WEB (nama web, logo, intro video)
        // Key DB (tabel pengaturan): nama_web, link_logo, link_intro
        // =======================================================
        const TW_FIELD = { nama_web: 'twNamaWeb', link_logo: 'twLinkLogo', link_intro: 'twLinkIntro' };
        const TW_BTN   = { nama_web: 'btnTwNama', link_logo: 'btnTwLogo', link_intro: 'btnTwIntro' };

        function twUrlValid(u) { try { const x = new URL(u); return x.protocol === 'https:' || x.protocol === 'http:'; } catch(e) { return false; } }

        function twPreviewLogo() {
            const v = (document.getElementById('twLinkLogo').value || '').trim();
            const img = document.getElementById('twLogoPreview'); const info = document.getElementById('twLogoInfo');
            if (!v) { img.src = 'icon.png'; info.textContent = 'Kosong = pakai logo bawaan (icon.png).'; return; }
            if (!twUrlValid(v)) { info.textContent = 'Link belum valid (harus diawali https://).'; return; }
            img.onerror = function() { img.onerror = null; img.src = 'icon.png'; info.textContent = 'Gambar tidak bisa dimuat. Pastikan link direct ke file gambar.'; };
            img.onload = function() { info.textContent = 'Preview logo berhasil dimuat.'; };
            img.src = v;
        }

        function twPreviewIntro() {
            const v = (document.getElementById('twLinkIntro').value || '').trim();
            const wrap = document.getElementById('twIntroWrap'); const vid = document.getElementById('twIntroPreview');
            if (!v || !twUrlValid(v)) { wrap.classList.add('hidden'); vid.removeAttribute('src'); vid.load(); return; }
            wrap.classList.remove('hidden'); vid.src = v; vid.load();
        }

        async function muatTampilanWeb() {
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getPengaturan' });
                const map = {};
                (res.data || []).forEach(p => { map[p.nama_pengaturan] = p.nilai || ''; });
                Object.keys(TW_FIELD).forEach(k => { const el = document.getElementById(TW_FIELD[k]); if (el) el.value = map[k] || ''; });
                twPreviewLogo(); twPreviewIntro();
            } catch (e) { Swal.fire('Kesalahan', 'Gagal memuat pengaturan tampilan: ' + e.message, 'error'); }
        }

        async function simpanTampilanWeb(key) {
            const el = document.getElementById(TW_FIELD[key]); const btn = document.getElementById(TW_BTN[key]);
            if (!el || !btn) return;
            const nilai = (el.value || '').trim();
            if (key !== 'nama_web' && nilai && !twUrlValid(nilai)) return Swal.fire('Link Tidak Valid', 'Gunakan link direct yang diawali https://', 'warning');
            if (typeof isDemoMode !== 'undefined' && isDemoMode) return Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: 'Mode demo: tidak disimpan', showConfirmButton: false, timer: 2000 });
            const oH = btn.innerHTML; btn.innerHTML = '<i class="fas fa-spinner fa-spin">
</i> Menyimpan...'; btn.disabled = true;
            try {
                await callBackend({ type: 'supabase_post', action: 'updatePengaturan', payload: [{ nama_pengaturan: key, nilai }] });
                // samakan dengan input lama di menu "Pengaturan Web" supaya tidak menimpa nilai lama saat disimpan
                if (key === 'link_intro') { const old = document.getElementById('setLinkIntro'); if (old) old.value = nilai; }
                terapkanTampilanWebAdmin();
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Tersimpan', showConfirmButton: false, timer: 2000 });
            } catch (e) { Swal.fire('Kesalahan', e.message, 'error'); }
            finally { btn.innerHTML = oH; btn.disabled = false; }
        }

        // Terapkan nama & logo ke tampilan admin sendiri (sidebar + favicon)
        async function terapkanTampilanWebAdmin() {
            if (window.TampilanWeb) { window.TampilanWeb.refresh(); return; }
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getPengaturan' });
                const map = {}; (res.data || []).forEach(p => { map[p.nama_pengaturan] = (p.nilai || '').trim(); });
                if (map.nama_web) { const s = document.getElementById('sidebarNamaWeb'); if (s) s.textContent = map.nama_web; }
                if (map.link_logo) {
                    const l = document.getElementById('sidebarLogo'); if (l) l.src = map.link_logo;
                    document.querySelectorAll('link[rel="icon"], link[rel="apple-touch-icon"]').forEach(x => x.href = map.link_logo);
                }
            } catch (e) {}
        }

        async function handleLogout() {
            const res = await Swal.fire({ title: 'Keluar dari Admin?', text: 'Sesi admin akan diakhiri.', icon: 'question', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Keluar', cancelButtonText: 'Batal' });
            if (!res.isConfirmed) return;
            stopIdleWatcher();
            _bersihkanLock();
            localStorage.removeItem('admin_last_active');
            localStorage.removeItem('admin_ea_session');
            userData = null;
            document.getElementById('mainDashboard').classList.add('hidden');
            document.getElementById('loginScreen').classList.remove('hidden');
            document.getElementById('loginForm').reset();
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Berhasil keluar', showConfirmButton: false, timer: 1500 });
        }

                async function checkLoginState() {
            const _la = parseInt(localStorage.getItem('admin_last_active') || '0', 10);
            if (_la && localStorage.getItem('admin_locked') !== '1' && (Date.now() - _la) > (parseInt(localStorage.getItem('admin_timeout_menit') || '2', 10) * 60000)) { localStorage.removeItem('admin_ea_session'); localStorage.removeItem('admin_last_active'); }
            const storedSession = localStorage.getItem('admin_ea_session');
            if (storedSession) {
                try {
                    userData = JSON.parse(storedSession);
                    const res = await callBackend({ type: 'supabase_get', action: 'getCurrentAdmin', payload: { email: userData.email_admin } });
                    if (res.data && res.data.length > 0) setupDashboard(); else throw new Error('Sesi admin tidak valid');
                } catch(e) { localStorage.removeItem('admin_ea_session'); userData = null; }
            }
        }

        function setupDashboard() {
            document.getElementById('loginScreen').classList.add('hidden'); document.getElementById('mainDashboard').classList.remove('hidden');
            document.getElementById('sidebarNama').textContent = userData.nama_admin || userData.nama_lengkap || userData.email_admin; document.getElementById('sidebarRole').textContent = userData.role_name;
            isSuperAdminUnlocked = (userData.role_name === 'Super Admin');
            renderSidebarMenu(); loadSettings(); switchView('dashboard'); startAdminPolling(); loadTimeoutAdmin().then(mulaiIdleWatcher); if (localStorage.getItem('admin_locked') === '1') tampilkanLock();
        }
        
        document.addEventListener("DOMContentLoaded", () => {
            checkLoginState();

            // Fix sidebar mobile — pasang event listener
            const mobileBtn = document.getElementById('mobileMenuBtn');
            const closeBtn = document.getElementById('closeSidebarBtn');
            if (mobileBtn) mobileBtn.addEventListener('click', () => toggleSidebarMobile());
            if (closeBtn) closeBtn.addEventListener('click', () => toggleSidebarMobile());

            document.getElementById('loginForm').addEventListener('submit', async (e) => {
                e.preventDefault(); const btn = document.getElementById('btnLogin'); btn.innerHTML = '<i class="fas fa-circle-notch fa-spin">
</i> Memeriksa...';
                try {
                    if (!supaClient) throw new Error('Koneksi ke Supabase gagal. Periksa konfigurasi URL/Key.');
                    const email = document.getElementById('loginEmail').value.trim(); const pass = document.getElementById('loginPassword').value;
                    const { error } = await supaClient.auth.signInWithPassword({ email, password: pass }); if (error) throw new Error("Email atau Sandi salah! (" + (error.message||'') + ")");
                    const res = await callBackend({ type: 'supabase_get', action: 'getCurrentAdmin', payload: { email } });
                    if (res.error || !res.data || res.data.length === 0) throw new Error("Akun terotentikasi, namun tidak terdaftar di database pegawai.");
                    const adminDetail = res.data[0]; const roleRes = await callBackend({ type: 'supabase_get', action: 'getAllRoles' }); let aksesArr = [];
                    if (roleRes.data) { const myRole = roleRes.data.find(r => r.nama_role === adminDetail.role_name); if (myRole) { try { aksesArr = JSON.parse(myRole.akses_fitur || '[]'); } catch(e){} } }
                    if (adminDetail.role_name === 'Super Admin') aksesArr.push('Super Admin');
                    userData = { ...adminDetail, akses: aksesArr, agama: adminDetail.agama || 'Islam' };
                    localStorage.setItem('admin_ea_session', JSON.stringify(userData)); setupDashboard();
                } catch (err) { Swal.fire('Login Gagal', err.message, 'error'); } finally { btn.innerHTML = 'Masuk Sistem <i class="fas fa-sign-in-alt">
</i>'; }
            });
        });
        
        function renderSidebarMenu() {
            const menuContainer = document.getElementById('menuNav');
            menuContainer.innerHTML = '';
            { const _ms = document.getElementById('menuSearch'); if (_ms) _ms.value = ''; }
            let currentGroup = '';
            MENU_CONFIG.forEach(item => {
                const hasAccess = item.roles.includes('ALL') ||
                    (userData && userData.akses && userData.akses.includes(item.id)) ||
                    isSuperAdminUnlocked;
                if (!hasAccess) return;
                if (item.grp && item.grp !== currentGroup) {
                    menuContainer.innerHTML += `<div class="mt-4 mb-2 px-3">
<p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">${item.grp}</p>
</div>`;
                    currentGroup = item.grp;
                }
                menuContainer.innerHTML += `<a href="javascript:void(0)" onclick="switchView('${item.id}')" id="menu-${item.id}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-gray-400 hover:text-white hover:bg-gray-800 transition group relative">
<div class="w-8 flex justify-center">
    <i class="fas ${item.icon} text-lg group-hover:scale-110 transition-transform">
    </i>
</div>
<span>${item.label}</span>
</a>`;
            });
        }

        function switchView(viewId) {
            try { hideBulkBar(); } catch(e) {}
            // Demo mode intercept - tampilkan data contoh tanpa DB
            if (typeof isDemoMode !== 'undefined' && isDemoMode) { switchViewDemo(viewId); return; }
            document.querySelectorAll('.page-view').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('#menuNav a').forEach(el => { el.classList.remove('bg-blue-600', 'text-white', 'shadow-md'); el.classList.add('text-gray-400', 'hover:bg-gray-800'); });
            const targetMenu = document.getElementById(`menu-${viewId}`);
            if(targetMenu) { targetMenu.classList.remove('text-gray-400', 'hover:bg-gray-800'); targetMenu.classList.add('bg-blue-600', 'text-white', 'shadow-md'); }

            if (viewId === 'dashboard') { document.getElementById('view-dashboard').classList.remove('hidden'); document.getElementById('pageTitle').textContent = "Dashboard Utama"; refreshDashboardStats(); } 
            else if (viewId === 'settings') { document.getElementById('view-settings').classList.remove('hidden'); document.getElementById('pageTitle').textContent = "Pengaturan Web"; loadSettings(); } 
            else if (viewId === 'role') { document.getElementById('view-datatable').classList.remove('hidden'); muatDataRole(); } 
            else if (viewId === 'kelas') { document.getElementById('view-datatable').classList.remove('hidden'); muatDataKelas(); }
            else if (viewId === 'penilaian_admin') { document.getElementById('view-penilaian_admin').classList.remove('hidden'); document.getElementById('pageTitle').textContent = 'Statistik Penilaian'; muatStatistikPenilaian(); }
            else if (viewId === 'menu_siswa') { document.getElementById('view-menu_siswa').classList.remove('hidden'); document.getElementById('pageTitle').textContent = 'Menu Siswa Tambahan'; muatMenuSiswaAdmin(); }
            else if (viewId === 'pengumuman') { document.getElementById('view-pengumuman').classList.remove('hidden'); document.getElementById('pageTitle').textContent = 'Pengumuman'; muatListPengumuman(); }
            else if (viewId === 'tampilan_web') { document.getElementById('view-tampilan_web').classList.remove('hidden'); document.getElementById('pageTitle').textContent = 'Pengaturan Tampilan Web'; muatTampilanWeb(); }
            else if (typeof GT !== 'undefined' && GT[viewId]) { document.getElementById('view-datatable').classList.remove('hidden'); gtMuat(viewId); }
            else if (viewId === 'db') { document.getElementById('view-manajemen').classList.remove('hidden'); renderManajemen('db'); }
            else if (viewId === 'admin') {
                document.getElementById('view-datatable').classList.remove('hidden');
                loadDataTable('hak_akses_admin', 'admin');
            }
            else { 
                const menuItem = MENU_CONFIG.find(m => m.id === viewId);
                if (menuItem && menuItem.table) {
                    document.getElementById('view-datatable').classList.remove('hidden');
                    loadDataTable(menuItem.table, viewId);
                }
            }
            if(window.innerWidth < 768) toggleSidebarMobile();
        }
        
        function toggleSidebarMobile() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); document.getElementById('sidebarOverlay').classList.toggle('hidden'); }

        let chartTrenInst = null, chartDistribusiInst = null, chartStatusInst = null, chartKategoriInst = null, chartPenilaianInst = null;
        let allAspirasData = [];
        let dashFilterDays = 7;

        function setDashboardFilter(days) {
            dashFilterDays = days;
            ['7','30','365','0'].forEach(d => {
                const btn = document.getElementById('dbf' + d);
                if(btn) btn.className = 'px-4 py-2 rounded-xl text-xs font-black transition bg-white border border-gray-200 text-gray-600 hover:bg-gray-50';
            });
            const activeBtn = document.getElementById('dbf' + days);
            if(activeBtn) activeBtn.className = 'px-4 py-2 rounded-xl text-xs font-black transition bg-blue-600 text-white shadow-md';
            renderDashboardCharts(allAspirasData, days);
        }

        async function refreshDashboardStats() {
            try {
                const asRes = await callBackend({ type: 'supabase_get', action: 'getAllAspirasi' });
                const swRes = await callBackend({ type: 'supabase_get', action: 'getAllSiswa' });
                let lap = 0, mas = 0, kon = 0, sis = 0;
                if(asRes.data) {
                    allAspirasData = asRes.data;
                    lap = asRes.data.filter(a => a.tipe === 'Laporan').length;
                    mas = asRes.data.filter(a => a.tipe === 'Masukan').length;
                    kon = asRes.data.filter(a => a.tipe === 'Konselor').length;
                    // Sub label: berapa menunggu
                    const lapMenunggu = asRes.data.filter(a => a.tipe === 'Laporan' && a.status === 'Menunggu').length;
                    const masMenunggu = asRes.data.filter(a => a.tipe === 'Masukan' && a.status === 'Menunggu').length;
                    const konMenunggu = asRes.data.filter(a => a.tipe === 'Konselor' && a.status === 'Menunggu').length;
                    const subLap = document.getElementById('statLaporanSub'); if(subLap) subLap.textContent = lapMenunggu > 0 ? lapMenunggu + ' menunggu' : '';
                    const subMas = document.getElementById('statMasukanSub'); if(subMas) subMas.textContent = masMenunggu > 0 ? masMenunggu + ' menunggu' : '';
                    const subKon = document.getElementById('statKonselorSub'); if(subKon) subKon.textContent = konMenunggu > 0 ? konMenunggu + ' menunggu' : '';
                }
                if(swRes.data) sis = swRes.data.length;
                document.getElementById('statLaporan').textContent = lap;
                document.getElementById('statMasukan').textContent = mas;
                document.getElementById('statKonselor').textContent = kon;
                document.getElementById('statSiswa').textContent = sis;
                renderDashboardCharts(allAspirasData, dashFilterDays);
            } catch(e) { console.error('Dashboard stats error:', e); }
        }

        function renderDashboardCharts(data, days) {
            const now = new Date();
            const cutoff = days > 0 ? new Date(now - days * 86400000) : null;
            const filtered = cutoff ? data.filter(d => new Date(d.created_at) >= cutoff) : data;

            // --- CHART 1: Tren per hari ---
            const trenMap = {};
            filtered.forEach(d => {
                const tgl = new Date(d.created_at).toLocaleDateString('id-ID', {day:'2-digit', month:'short'});
                trenMap[tgl] = (trenMap[tgl] || 0) + 1;
            });
            const trenLabels = Object.keys(trenMap).slice(-14);
            const trenValues = trenLabels.map(k => trenMap[k]);
            if(chartTrenInst) chartTrenInst.destroy();
            const ctxTren = document.getElementById('chartTren');
            if(ctxTren) chartTrenInst = new Chart(ctxTren, {
                type: 'line',
                data: { labels: trenLabels, datasets: [{ label: 'Pengajuan', data: trenValues, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)', fill: true, tension: 0.4, pointBackgroundColor: '#3b82f6', pointRadius: 4 }] },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
            });

            // --- CHART 2: Distribusi Tipe (Donut) ---
            const lap = filtered.filter(d => d.tipe === 'Laporan').length;
            const mas = filtered.filter(d => d.tipe === 'Masukan').length;
            const kon = filtered.filter(d => d.tipe === 'Konselor').length;
            if(chartDistribusiInst) chartDistribusiInst.destroy();
            const ctxDist = document.getElementById('chartDistribusi');
            if(ctxDist) chartDistribusiInst = new Chart(ctxDist, {
                type: 'doughnut',
                data: { labels: ['Laporan', 'Masukan', 'Konselor'], datasets: [{ data: [lap, mas, kon], backgroundColor: ['#3b82f6', '#22c55e', '#a855f7'], borderWidth: 0 }] },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '65%' }
            });

            // --- CHART 3: Status (Bar) ---
            const statusList = ['Menunggu', 'Sedang Diproses', 'Selesai', 'Ditolak'];
            const statusCount = statusList.map(s => filtered.filter(d => d.status === s).length);
            if(chartStatusInst) chartStatusInst.destroy();
            const ctxStatus = document.getElementById('chartStatus');
            if(ctxStatus) chartStatusInst = new Chart(ctxStatus, {
                type: 'bar',
                data: { labels: statusList, datasets: [{ label: 'Jumlah', data: statusCount, backgroundColor: ['#f59e0b','#3b82f6','#22c55e','#ef4444'], borderRadius: 8, borderWidth: 0 }] },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
            });

            // --- CHART 4: Kategori Laporan (Bar horizontal) ---
            const katMap = {};
            filtered.filter(d => d.tipe === 'Laporan').forEach(d => { katMap[d.kategori] = (katMap[d.kategori] || 0) + 1; });
            const katSorted = Object.entries(katMap).sort((a,b) => b[1]-a[1]).slice(0,6);
            if(chartKategoriInst) chartKategoriInst.destroy();
            const ctxKat = document.getElementById('chartKategori');
            if(ctxKat) chartKategoriInst = new Chart(ctxKat, {
                type: 'bar',
                data: { labels: katSorted.map(k => k[0]), datasets: [{ data: katSorted.map(k => k[1]), backgroundColor: '#f87171', borderRadius: 8, borderWidth: 0 }] },
                options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } }
            });
        }

        async function loadDataTable(tableName, viewId) {
            currentTableId = viewId;
            const tbody = document.getElementById('dtBody'); const thead = document.getElementById('dtHead'); const titleEl = document.getElementById('pageTitle'); const actionEl = document.getElementById('dtActionsContainer');
            titleEl.textContent = MENU_CONFIG.find(m => m.id === viewId)?.label || "Data Tabel";
            tbody.innerHTML = '<tr>
<td colspan="10" class="text-center py-10">
    <i class="fas fa-circle-notch fa-spin text-3xl text-blue-500 mb-2">
    </i>
</td>
</tr>';
            
            let btnHtml = '';
            // 1. Ekspor (semua tabel yang punya data tabular)
            if (!['penilaian_admin','settings'].includes(viewId)) {
                btnHtml += `<button onclick="exportToExcel('${viewId}')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
<i class="fas fa-file-excel">
</i> Ekspor</button>`;
            }
            // 2. Manual (tambah data)
            if (['siswa','buku_saku','poin','anggota_elpoint','admin','apikey','kelas','role','guru','semester','mapel'].includes(viewId)) {
                btnHtml += `<button onclick="bukaModalData('${viewId}', 'add')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
<i class="fas fa-plus">
</i> Manual</button>`;
            }
            // 3. Impor Excel
            if (['siswa','poin','buku_saku','anggota_elpoint','guru'].includes(viewId)) {
                btnHtml += `<button onclick="bukaModalImport('${viewId}')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
<i class="fas fa-file-import">
</i> Impor</button>`;
            }
            // 4. Pilih Semua + placeholder for dropdown tindakan
            btnHtml += `<button onclick="piliSemua()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
<i class="fas fa-check-square">
</i> Pilih Semua</button>`;
            btnHtml += `<div id="tindakanDropdownWrapper" class="hidden relative">
</div>`;
            // Tombol Galeri Foto Selfie (hanya di menu absensi)
            if (viewId === 'absensi') {
                btnHtml += `<button onclick="bukaGaleriFotoAbsensi()" title="Galeri foto selfie siswa" class="flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition shadow-sm">
<i class="fas fa-images">
</i> Galeri Foto</button>`;
            }
            // 5. Tombol Tutorial API Key (hanya di menu apikey)
            if (viewId === 'apikey') {
                btnHtml += `<button onclick="bukaTutorialApiKey()" title="Cara menggunakan API Key" class="w-9 h-9 rounded-xl border-2 border-indigo-300 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-black text-sm transition flex items-center justify-center flex-shrink-0 shadow-sm">?</button>`;
            }
            actionEl.innerHTML = btnHtml;

            try {
                let action = 'getData';
                if(tableName === 'aspirasi') action = 'getAllAspirasi'; else if(tableName === 'siswa') action = 'getAllSiswa'; else if(tableName === 'hak_akses_admin') action = 'getAllAdmins'; else if(tableName === 'log_history') action = 'getLogHistory'; else if(tableName === 'absensi') action = 'getAllAbsensi'; else if(tableName === 'api_keys') action = 'getAllApiKeys'; else if(tableName === 'master_pelanggaran') action = 'getAllPelanggaran'; else if(tableName === 'anggota_elpoint') action = 'getAllElpoint'; else if(tableName === 'master_semester') action = 'getData'; else if(tableName === 'master_mapel') action = 'getData';
                const res = await callBackend({ type: 'supabase_get', action: action, payload: { table: tableName } });
                // Filter data aspirasi berdasarkan tipe menu
                let rawData = res.data || [];
                if (viewId === 'laporan') rawData = rawData.filter(d => d.tipe === 'Laporan');
                else if (viewId === 'masukan') rawData = rawData.filter(d => d.tipe === 'Masukan');
                else if (viewId === 'konselor') rawData = rawData.filter(d => d.tipe === 'Konselor');

                // Saat load absensi, pastikan master siswa tersedia sebagai fallback nisn/kelas
                if (viewId === 'absensi' && !globalDataStore['siswa']?.length) {
                    try {
                        const resSiswa = await callBackend({ type: 'supabase_get', action: 'getAllSiswa', payload: {} });
                        globalDataStore['siswa'] = resSiswa.data || [];
                    } catch(e2) { /* silent - fallback tidak kritis */ }
                }

                if (viewId === 'absensi') {
                    try { const rs = await callBackend({ type: 'supabase_get', action: 'getData', payload: { table: 'master_semester?order=urutan.desc' } }); globalDataStore['_semester'] = rs.data || []; } catch(e2) { globalDataStore['_semester'] = []; }
                }
                globalDataStore[viewId] = rawData; currentFilteredData = [...rawData]; currentPage = 1;
                renderDataTableHeader(viewId); if (viewId === 'absensi') filterTableLokal(); else renderDataTableBody();
            } catch(e) { tbody.innerHTML = `<tr>
<td colspan="10" class="text-center py-6 text-red-500 font-bold">${e.message}</td>
</tr>`; }
        }

        // --- TAMBAHAN KHUSUS: MENYATUKAN TOMBOL KELAS AGAR SAMA PERSIS DENGAN SISWA ---
        async function muatDataKelas() {
            currentTableId = 'kelas'; const tbody = document.getElementById('dtBody'); const thead = document.getElementById('dtHead');
            document.getElementById('pageTitle').textContent = "Data Master Kelas";
            document.getElementById('dtActionsContainer').innerHTML = `
                <button onclick="exportToExcel('kelas')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
<i class="fas fa-file-excel">
</i> Ekspor</button>
<button onclick="bukaModalKelas()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
    <i class="fas fa-plus">
    </i> Manual</button>
    <button onclick="bukaModalImport('kelas')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
        <i class="fas fa-file-import">
        </i> Impor</button>
        <button onclick="piliSemuaKelas()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
            <i class="fas fa-check-square">
            </i> Pilih Semua</button>
            `;
            thead.innerHTML = `<tr>
            <th class="px-5 py-4 w-8">
                <input type="checkbox" id="checkAllKelas" class="w-5 h-5 rounded border-2 border-gray-300 accent-blue-600 cursor-pointer" onclick="piliSemuaKelas()">
            </th>
            <th class="px-5 py-4 w-1/3">Nama Kelas</th>
            <th class="px-5 py-4 text-right">Aksi</th>
        </tr>`;
            tbody.innerHTML = '<tr>
        <td colspan="2" class="text-center py-10">
            <i class="fas fa-circle-notch fa-spin text-3xl text-blue-500 mb-2">
            </i>
        </td>
    </tr>';
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getData', payload: { table: 'daftar_kelas' } });
                globalDataStore['kelas'] = res.data || []; renderTabelKelas();
            } catch(e) { tbody.innerHTML = `<tr>
    <td colspan="2" class="text-center py-6 text-red-500 font-bold">${e.message}</td>
</tr>`; }
        }

        function renderTabelKelas() {
            const tbody = document.getElementById('dtBody'); tbody.innerHTML = '';
            if(!globalDataStore['kelas'] || globalDataStore['kelas'].length === 0) {
                tbody.innerHTML = '<tr>
<td colspan="3" class="text-center py-10 text-gray-500">
    <i class="fas fa-folder-open text-4xl mb-3 block text-gray-300">
    </i>Belum ada data kelas.</td>
</tr>';
                document.getElementById('dtInfo').textContent = 'Menampilkan 0 data'; document.getElementById('dtPaginationControls').innerHTML = ''; return;
            }
            globalDataStore['kelas'].forEach(item => {
                tbody.innerHTML += `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
<td class="px-4 py-4">
    <input type="checkbox" class="kelas-check w-5 h-5 rounded border-2 border-gray-300 text-blue-600 cursor-pointer accent-blue-600" data-id="${item.id}" onchange="updateKelasCheckCount(); updateBulkBar()">
</td>
<td class="px-5 py-4 font-bold text-gray-800 text-base">
    <i class="fas fa-chalkboard text-blue-300 mr-2">
    </i> ${item.nama_kelas}</td>
    <td class="px-5 py-4 text-right">
        <button onclick="bukaModalKelas('${item.id}')" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition mr-1">
            <i class="fas fa-edit">
            </i>
        </button>
        <button onclick="hapusSatuData('daftar_kelas', '${item.id}')" class="text-red-500 hover:bg-red-100 p-2 rounded-lg transition">
            <i class="fas fa-trash-alt">
            </i>
        </button>
    </td>
</tr>`;
            });
            document.getElementById('dtInfo').textContent = `Total ${globalDataStore['kelas'].length} Kelas`; document.getElementById('dtPaginationControls').innerHTML = '';
        }

        function bukaModalKelas(id = null) {
            const isEdit = id !== null; let targetData = {}; if(isEdit) targetData = globalDataStore['kelas'].find(x => x.id === id) || {};
            document.getElementById('modalDataTitle').innerHTML = `<i class="fas fa-chalkboard text-blue-600 mr-2">
</i> ${isEdit ? 'Edit' : 'Tambah'} Kelas`;
            document.getElementById('genericDataForm').innerHTML = `<input type="hidden" id="formActionType" value="${isEdit ? 'edit' : 'add'}">
<input type="hidden" id="tambahType" value="kelas">
<input type="hidden" id="formRecordId" value="${id || ''}">
<div>
    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nama Kelas <span class="text-red-500">*</span>
</label>
<input type="text" id="formKelasNama" required value="${targetData.nama_kelas || ''}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 uppercase" placeholder="Contoh: X RPL 1">
</div>
<div class="mt-6 pt-4 border-t flex justify-end gap-3">
    <button type="button" onclick="document.getElementById('modalDataForm').classList.add('hidden')" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-200 transition">Batal</button>
    <button type="button" onclick="simpanKelasLokal()" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-md flex items-center gap-2">
        <i class="fas fa-save">
        </i> Simpan</button>
    </div>`;
            document.getElementById('modalDataForm').classList.remove('hidden');
        }

        async function simpanKelasLokal() {
            const id = document.getElementById('formRecordId').value; const nama = document.getElementById('formKelasNama').value.trim().toUpperCase();
            if(!nama) return Swal.fire('Error', 'Nama Kelas wajib diisi!', 'warning');
            Swal.fire({ title: 'Menyimpan...', didOpen: () => { Swal.showLoading() } });
            try {
                let aksi = id ? 'updateData' : 'insertData'; let payload = { table: 'daftar_kelas', payload: { nama_kelas: nama } }; if(id) payload.id = id;
                await callBackend({ type: 'supabase_post', action: aksi, payload: payload });
                document.getElementById('modalDataForm').classList.add('hidden'); Swal.fire({toast: true, position: 'top-end', icon: 'success', title: 'Tersimpan', showConfirmButton: false, timer: 1500}); muatDataKelas();
            } catch(e) { Swal.fire('Gagal', e.message, 'error'); }
        }

        // --- TAMBAHAN KHUSUS: MENYATUKAN TOMBOL ROLE AGAR SAMA PERSIS DENGAN SISWA ---
        async function muatDataRole() {
            currentTableId = 'role'; const tbody = document.getElementById('dtBody'); const thead = document.getElementById('dtHead');
            document.getElementById('pageTitle').textContent = "Jabatan & Hak Akses";
            document.getElementById('dtActionsContainer').innerHTML = `
                <button onclick="exportToExcel('role')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
    <i class="fas fa-file-excel">
    </i> Ekspor</button>
    <button onclick="bukaModalRole()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
        <i class="fas fa-plus">
        </i> Manual</button>
        <button onclick="bukaModalImport('role')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
            <i class="fas fa-file-import">
            </i> Impor</button>
            <button onclick="piliSemuaRole()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
                <i class="fas fa-check-square">
                </i> Pilih Semua</button>
            `;
            thead.innerHTML = `<tr>
                <th class="px-5 py-4 w-8">
                    <input type="checkbox" id="checkAllRole" class="w-5 h-5 rounded border-2 border-gray-300 accent-blue-600 cursor-pointer" onclick="piliSemuaRole()">
                </th>
                <th class="px-5 py-4 w-1/4">Nama Jabatan</th>
                <th class="px-5 py-4">Akses Tersedia</th>
                <th class="px-5 py-4 w-24 text-right">Aksi</th>
            </tr>`;
            tbody.innerHTML = '<tr>
            <td colspan="3" class="text-center py-10">
                <i class="fas fa-circle-notch fa-spin text-3xl text-blue-500 mb-2">
                </i>
            </td>
        </tr>';
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getData', payload: { table: 'daftar_role' } });
                globalDataStore['role'] = res.data || []; renderTabelRole();
            } catch(e) { tbody.innerHTML = `<tr>
        <td colspan="3" class="text-center py-6 text-red-500 font-bold">${e.message}</td>
    </tr>`; }
        }

        function renderTabelRole() {
            const tbody = document.getElementById('dtBody'); tbody.innerHTML = '';
            if(!globalDataStore['role'] || globalDataStore['role'].length === 0) {
                tbody.innerHTML = '<tr>
    <td colspan="4" class="text-center py-10 text-gray-500">
        <i class="fas fa-folder-open text-4xl mb-3 block text-gray-300">
        </i>Belum ada data.</td>
    </tr>';
                document.getElementById('dtInfo').textContent = 'Menampilkan 0 data'; document.getElementById('dtPaginationControls').innerHTML = ''; return;
            }
            globalDataStore['role'].forEach(item => {
                let akses = []; try { akses = JSON.parse(item.akses_fitur || '[]'); } catch(e){}
                const _bd = 'px-2 py-1 rounded text-[10px] font-black uppercase mr-1 mb-1 inline-block border';
                const _menuB = akses.filter(a => !String(a).startsWith('aksi_')).map(a => `<span class="bg-blue-50 text-blue-700 border-blue-200 ${_bd}">${labelAkses(a)}</span>`).join('');
                const _legacy = !akses.includes('aksi_v2');
                const _aksiB = _legacy ? (_menuB ? `<span class="bg-gray-100 text-gray-600 border-gray-200 ${_bd}" title="Jabatan versi lama: semua tombol boleh. Edit & simpan ulang untuk membatasi.">Semua tombol (versi lama)</span>` : '') : akses.filter(a => String(a).startsWith('aksi_') && a !== 'aksi_v2').map(a => `<span class="bg-green-50 text-green-700 border-green-200 ${_bd}">${labelAkses(a)}</span>`).join('');
                const badges = _menuB + (_aksiB ? `<div class="mt-1 pt-1 border-t border-dashed border-gray-200">${_aksiB}</div>` : (_legacy ? '' : '<div class="mt-1 text-[10px] font-bold text-gray-400 italic">Tanpa tombol aksi (hanya lihat)</div>'));
                tbody.innerHTML += `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
    <td class="px-4 py-4">
        <input type="checkbox" class="role-check w-5 h-5 rounded border-2 border-gray-300 text-blue-600 cursor-pointer accent-blue-600" data-id="${item.id}" onchange="updateRoleCheckCount(); updateBulkBar()">
    </td>
    <td class="px-5 py-4 font-bold text-gray-800">${item.nama_role}</td>
    <td class="px-5 py-4 whitespace-normal leading-relaxed">${badges || '<span class="text-gray-400 italic text-xs">Tidak ada akses</span>'}</td>${_aksiRole(item)}</tr>`;
            });
            document.getElementById('dtInfo').textContent = `Total ${globalDataStore['role'].length} Jabatan`; document.getElementById('dtPaginationControls').innerHTML = '';
        }

        // =======================================================
        // KATALOG HAK AKSES JABATAN (menu + tombol/aksi)
        // - Kode menu  = id di MENU_CONFIG (otomatis ikut kalau ada menu baru)
        // - Kode aksi  = diawali "aksi_" (membatasi tombol di dalam halaman)
        // - 'aksi_v2'  = penanda jabatan sudah memakai pembatasan tombol.
        //   Jabatan LAMA (tanpa penanda) tetap boleh semua tombol, jadi tidak ada yang tiba-tiba terkunci.
        // =======================================================
        const AKSES_BAHAYA_MENU = ['siswa','alumni','kelas','role','admin','anggota_elpoint','history','settings','guru','apikey','db','tampilan_web'];
        const AKSI_CATALOG = [
            { grp: 'Data: Tambah / Ubah / Hapus', items: [
                { id: 'aksi_tambah', label: 'Tambah Data', desc: 'Tombol Manual/Tambah, publikasi pengumuman' },
                { id: 'aksi_edit',   label: 'Edit Data', desc: 'Tombol edit di baris tabel dan tindakan massal ubah/aktifkan' },
                { id: 'aksi_hapus',  label: 'Hapus Data', desc: 'Hapus satuan maupun massal', bahaya: true },
                { id: 'aksi_impor',  label: 'Impor Data', desc: 'Impor dari Excel / Google Sheet', bahaya: true },
                { id: 'aksi_ekspor', label: 'Ekspor & Unduh', desc: 'Ekspor Excel, unduh template dan rekap' }
            ]},
            { grp: 'Layanan & Siswa', items: [
                { id: 'aksi_balas',      label: 'Balas & Ubah Status Tiket', desc: 'Balas laporan, ubah status aspirasi' },
                { id: 'aksi_blokir',     label: 'Blokir / Buka Blokir Akun', desc: 'Siswa dan guru, satuan maupun massal', bahaya: true },
                { id: 'aksi_reset_pin',  label: 'Reset PIN Siswa', bahaya: true },
                { id: 'aksi_reset_data', label: 'Reset Poin & Absensi', desc: 'Reset massal poin dan absensi', bahaya: true },
                { id: 'aksi_naik_kelas', label: 'Naik Kelas & Kembalikan Alumni', bahaya: true },
                { id: 'aksi_qr',         label: 'Lihat & Unduh QR Siswa' },
                { id: 'aksi_foto',       label: 'Lihat Foto Absensi', desc: 'Galeri foto selfie absensi' }
            ]},
            { grp: 'Sistem', items: [
                { id: 'aksi_sistem', label: 'Jalankan Tugas Sistem', desc: 'Sinkron Sheets, backup, reminder, laporan berkala, semester aktif, atur hari libur', bahaya: true }
            ]}
        ];
        const AKSI_LABEL = {}; AKSI_CATALOG.forEach(g => g.items.forEach(it => { AKSI_LABEL[it.id] = it.label; }));

        function labelAkses(kode) {
            if (AKSI_LABEL[kode]) return AKSI_LABEL[kode];
            const m = MENU_CONFIG.find(x => x.id === kode);
            return m ? m.label : String(kode).replace(/_/g, ' ');
        }

        // Dipakai guard tombol: Super Admin & jabatan lama = boleh semua
        function bisaAksi(kode) {
            if (typeof isDemoMode !== 'undefined' && isDemoMode) return true;
            if (isSuperAdminUnlocked) return true;
            const a = (userData && userData.akses) || [];
            if (a.includes('Super Admin')) return true;
            if (!a.includes('aksi_v2')) return true;
            return a.includes(kode);
        }

        function roleCentang(jenis, nilai) {
            document.querySelectorAll('#genericDataForm input[type="checkbox"][data-grup]').forEach(cb => {
                if (jenis === 'semua' || cb.dataset.grup === jenis) cb.checked = nilai;
            });
        }
        function rolePresetLihat() {
            document.querySelectorAll('#genericDataForm input[type="checkbox"][data-grup]').forEach(cb => {
                cb.checked = (cb.dataset.grup === 'menu' && !AKSES_BAHAYA_MENU.includes(cb.value));
            });
        }

        function bukaModalRole(id = null) {
            const isEdit = id !== null; let targetData = {}; if(isEdit) targetData = globalDataStore['role'].find(x => x.id === id) || {};
            let currentAkses = []; try { currentAkses = JSON.parse(targetData.akses_fitur || '[]'); } catch(e){}
            const legacy = isEdit && !currentAkses.includes('aksi_v2');
            const cek = (v, aksi) => ((aksi && legacy) || currentAkses.includes(v)) ? 'checked' : '';

            const grpMap = {};
            MENU_CONFIG.filter(m => m.id !== 'dashboard').forEach(m => { const g = m.grp || 'Umum'; (grpMap[g] = grpMap[g] || []).push(m); });
            const menuHtml = Object.keys(grpMap).map(g =>
                `<p class="col-span-1 sm:col-span-2 text-[10px] font-black text-gray-500 uppercase tracking-widest border-b border-gray-200 pb-1 mt-3 first:mt-0">${g}</p>` +
                grpMap[g].map(m => { const b = AKSES_BAHAYA_MENU.includes(m.id);
                    return `<label class="flex items-center gap-3 cursor-pointer p-2 hover:bg-white rounded-lg transition">
    <input type="checkbox" data-grup="menu" value="${m.id}" ${cek(m.id,false)} class="w-4 h-4 rounded ${b ? 'text-red-600' : 'text-blue-600'}">
    <span class="text-sm font-bold ${b ? 'text-red-700' : 'text-gray-700'}">
        <i class="fas ${m.icon} w-4 text-center mr-1 opacity-60">
        </i>${m.label}${b ? ' <i class="fas fa-exclamation-triangle text-[10px] text-red-400" title="Akses sensitif">
    </i>' : ''}</span>
</label>`; }).join('')
            ).join('');

            const aksiHtml = AKSI_CATALOG.map(g =>
                `<p class="col-span-1 sm:col-span-2 text-[10px] font-black text-gray-500 uppercase tracking-widest border-b border-gray-200 pb-1 mt-3 first:mt-0">${g.grp}</p>` +
                g.items.map(it => `<label class="flex items-start gap-3 cursor-pointer p-2 hover:bg-white rounded-lg transition sm:col-span-2">
<input type="checkbox" data-grup="aksi" value="${it.id}" ${cek(it.id,true)} class="w-4 h-4 rounded mt-0.5 ${it.bahaya ? 'text-red-600' : 'text-green-600'}">
<span class="text-sm font-bold ${it.bahaya ? 'text-red-700' : 'text-gray-700'}">${it.label}${it.desc ? `<span class="block text-[11px] font-medium text-gray-400 leading-snug">${it.desc}</span>` : ''}</span>
</label>`).join('')
            ).join('');

            const btnP = 'px-3 py-1.5 rounded-lg text-[11px] font-bold border transition';
            document.getElementById('modalDataTitle').innerHTML = `<i class="fas fa-user-shield text-blue-600 mr-2">
</i> ${isEdit ? 'Edit' : 'Tambah'} Jabatan`;
            document.getElementById('genericDataForm').innerHTML = `
                <input type="hidden" id="formActionType" value="${isEdit ? 'edit' : 'add'}">
<input type="hidden" id="tambahType" value="role">
<input type="hidden" id="formRecordId" value="${id || ''}">
<div>
    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nama Jabatan</label>
    <input type="text" id="formRoleNama" required value="${targetData.nama_role || ''}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800">
</div>
                ${legacy ? '<div class="mt-3 bg-yellow-50 border border-yellow-200 text-yellow-800 text-[11px] font-medium rounded-xl px-3 py-2">
<i class="fas fa-info-circle mr-1">
</i> Jabatan ini masih versi lama: semua tombol masih boleh dipakai. Semua tombol sudah dicentang di bawah, hilangkan centang yang tidak diperlukan lalu simpan.</div>' : ''}
                <div class="mt-4 flex flex-wrap gap-2">
<button type="button" onclick="roleCentang('menu', true)" class="${btnP} bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100">Semua Menu</button>
<button type="button" onclick="roleCentang('aksi', true)" class="${btnP} bg-green-50 text-green-700 border-green-200 hover:bg-green-100">Semua Tombol</button>
<button type="button" onclick="rolePresetLihat()" class="${btnP} bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100" title="Menu non-sensitif saja, tanpa tombol aksi">Hanya Lihat</button>
<button type="button" onclick="roleCentang('semua', false)" class="${btnP} bg-red-50 text-red-600 border-red-200 hover:bg-red-100">Kosongkan</button>
</div>
<div class="mt-3">
    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">
        <i class="fas fa-bars mr-1">
        </i> Hak Akses Menu <span class="normal-case font-medium text-gray-400">(halaman yang tampil di sidebar)</span>
    </label>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 bg-gray-50 p-4 rounded-xl border border-gray-200 max-h-64 overflow-y-auto custom-dropdown">${menuHtml}</div>
</div>
<div class="mt-4">
    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">
        <i class="fas fa-hand-pointer mr-1">
        </i> Hak Akses Tombol <span class="normal-case font-medium text-gray-400">(aksi di dalam halaman)</span>
    </label>
    <div class="grid grid-cols-1 gap-1 bg-gray-50 p-4 rounded-xl border border-gray-200 max-h-64 overflow-y-auto custom-dropdown">${aksiHtml}</div>
    <p class="text-[10px] text-gray-400 mt-2 leading-relaxed">Tombol hanya muncul kalau menunya juga dicentang. Perubahan jabatan berlaku setelah admin yang bersangkutan login ulang.</p>
</div>
<div class="mt-6 pt-4 border-t flex justify-end gap-3">
    <button type="button" onclick="document.getElementById('modalDataForm').classList.add('hidden')" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-200 transition">Batal</button>
    <button type="button" onclick="simpanRoleLokal()" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-md flex items-center gap-2">
        <i class="fas fa-save">
        </i> Simpan</button>
    </div>
            `;
            document.getElementById('modalDataForm').classList.remove('hidden');
        }

        async function simpanRoleLokal() {
            const id = document.getElementById('formRecordId').value; const nama = document.getElementById('formRoleNama').value.trim();
            if(!nama) return Swal.fire('Error', 'Nama Jabatan wajib diisi!', 'warning');
            const checkboxes = document.querySelectorAll('#genericDataForm input[type="checkbox"]:checked'); const aksesArray = Array.from(checkboxes).map(cb => cb.value); aksesArray.push('aksi_v2');
            Swal.fire({ title: 'Menyimpan...', didOpen: () => { Swal.showLoading() } });
            try {
                let aksi = id ? 'updateData' : 'insertData'; let payload = { table: 'daftar_role', payload: { nama_role: nama, akses_fitur: JSON.stringify(aksesArray) } }; if(id) payload.id = id;
                await callBackend({ type: 'supabase_post', action: aksi, payload: payload });
                document.getElementById('modalDataForm').classList.add('hidden'); Swal.fire({toast: true, position: 'top-end', icon: 'success', title: 'Tersimpan', showConfirmButton: false, timer: 1500}); muatDataRole();
            } catch(e) { Swal.fire('Gagal', e.message, 'error'); }
        }

        // --- LANJUTAN KODE UMUM DATATABLE SEPERTI SEBELUMNYA ---
        function renderDataTableHeader(viewId) {
            const thead = document.getElementById('dtHead'); let cols = [];
            const chk = '<input type="checkbox" class="w-5 h-5 rounded border-2 border-gray-300 text-blue-600 cursor-pointer accent-blue-600" id="checkAllMain" onclick="toggleCheckAll(this)">';
            if(viewId === 'laporan' || viewId === 'masukan' || viewId === 'konselor') cols = [chk, 'TANGGAL', 'PENGIRIM', 'KATEGORI', 'ISI PESAN', 'STATUS', 'AKSI'];
            else if(viewId === 'absensi') cols = [chk, 'NAMA SISWA', 'KELAS', 'TOTAL HADIR', 'TEPAT WAKTU', 'TERLAMBAT (×)', 'SAKIT', 'IZIN', 'ALPA', 'AKSI'];
            else if(viewId === 'guru')    cols = [chk, 'NAMA LENGKAP', 'NIP', 'MATA PELAJARAN', 'KELAS', 'NO WA', 'STATUS', 'AKSI'];
            else if(viewId === 'lapguru') cols = [chk, 'GURU', 'SISWA', 'MAPEL', 'TGL & JAM', 'JENIS PELANGGARAN', 'POIN', 'KETERANGAN', 'AKSI'];
            else if(viewId === 'poin') cols = [chk, 'SISWA', 'KELAS', 'TOTAL POIN', 'SANKSI TERAKHIR', 'AKSI'];
            else if(viewId === 'buku_saku') cols = ['<input type="checkbox" id="checkAllMain" class="w-5 h-5 rounded border-2 border-gray-300 accent-blue-600 cursor-pointer" onclick="toggleCheckAll(this)">', 'DESKRIPSI KELAKUAN', 'KATEGORI', 'BOBOT POIN', 'AKSI'];
            else if(viewId === 'semester') cols = [chk, 'TAHUN AJARAN', 'SEMESTER', 'LABEL', 'PERIODE', 'STATUS', 'AKSI'];
            else if(viewId === 'mapel')    cols = [chk, 'NAMA MAPEL', 'KODE', 'STATUS', 'AKSI'];
            else if(viewId === 'siswa') cols = [chk, 'SISWA', 'TGL LAHIR', 'KELAS', 'STATUS', 'AKSI'];
            else if(viewId === 'anggota_elpoint') cols = [chk, 'NAMA ANGGOTA', 'USERNAME', 'ROLE', 'AKSI'];
            else if(viewId === 'history') cols = [chk, 'WAKTU', 'AKTOR', 'ROLE / MODUL', 'AKSI / KEGIATAN', 'DETAIL'];
            else if(viewId === 'apikey') cols = [chk, 'NAMA APLIKASI', 'API KEY (RAHASIA)', 'DIBUAT PADA', 'STATUS', 'AKSI'];
            else if(viewId === 'admin') cols = [chk, 'NAMA ADMIN', 'EMAIL', 'JABATAN', 'AKSI'];
            else if(viewId === 'alumni') cols = [chk, 'NAMA', 'NISN', 'KELAS TERAKHIR', 'TGL LULUS', 'AKSI'];
            else cols = ['DATA', 'AKSI'];

            thead.innerHTML = `<tr>${cols.map(c => {
                const isHtml = c.startsWith('<');
                const isAksi = c === 'AKSI';
                // Kolom checkbox: lebar tetap 48px, centered, background biru muda
                return `<th class="py-3 ${isAksi?'text-right px-5':''} ${isHtml?'w-12 text-center px-3 bg-blue-50/50':'px-5 py-4'}">${c}</th>`;
            }).join('')}</tr>`;
            const filterContainer = document.getElementById('dtDynamicFilterContainer'); filterContainer.innerHTML = '';
            // Reset bulk bar
            hideBulkBar();
            let filterHtml = '';
            // Filter kelas (untuk data yang punya kelas)
            if (['laporan', 'masukan', 'konselor', 'siswa'].includes(viewId)) {
                const uniqueClasses = [...new Set(globalDataStore[viewId].map(d => d.kelas || d.pengirim_kelas).filter(Boolean))].sort();
                filterHtml += `<select id="filterKelas" onchange="filterTableLokal()" class="px-4 py-2 border-2 border-gray-200 rounded-xl text-sm font-bold bg-white focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
    <option value="">Semua Kelas</option>${uniqueClasses.map(c => `<option value="${c}">${c}</option>`).join('')}</select>`;
            }
            // Filter periode tanggal (laporan, masukan, konselor, history, absensi saja — BUKAN siswa)
            if (['laporan', 'masukan', 'konselor', 'history'].includes(viewId)) {
                filterHtml += `
                <div class="flex items-center gap-1.5 flex-wrap">
    <button onclick="setFilterPeriode(7, this)" class="filter-periode-btn px-3 py-2 rounded-xl text-[11px] font-black transition bg-blue-600 text-white shadow-sm" data-days="7">7 Hari</button>
    <button onclick="setFilterPeriode(30, this)" class="filter-periode-btn px-3 py-2 rounded-xl text-[11px] font-black transition bg-white border border-gray-200 text-gray-600 hover:bg-gray-50" data-days="30">1 Bulan</button>
    <button onclick="setFilterPeriode(365, this)" class="filter-periode-btn px-3 py-2 rounded-xl text-[11px] font-black transition bg-white border border-gray-200 text-gray-600 hover:bg-gray-50" data-days="365">1 Tahun</button>
    <button onclick="setFilterPeriode(0, this)" class="filter-periode-btn px-3 py-2 rounded-xl text-[11px] font-black transition bg-white border border-gray-200 text-gray-600 hover:bg-gray-50" data-days="0">Semua</button>
</div>`;
            }
            // Filter role khusus log history
            if (viewId === 'history') {
                const uniqueRoles = [...new Set((globalDataStore['history']||[]).map(d => d.role).filter(Boolean))].sort();
                filterHtml += `<select id="filterHistoryRole" onchange="filterTableLokal()" class="px-4 py-2 border-2 border-gray-200 rounded-xl text-sm font-bold bg-white focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
<option value="">Semua Role</option>${uniqueRoles.map(r => '<option value="'+r+'">'+r+'</option>').join('')}</select>`;
            }
            if (viewId === 'absensi') filterHtml += _filterAbsensiHtml();
            filterContainer.innerHTML = filterHtml;
        }

        let currentFilterDays = 0; // 0 = semua

        function setFilterPeriode(days, btn) {
            currentFilterDays = days;
            // Update tombol aktif
            document.querySelectorAll('.filter-periode-btn').forEach(b => {
                b.className = 'filter-periode-btn px-3 py-2 rounded-xl text-[11px] font-black transition bg-white border border-gray-200 text-gray-600 hover:bg-gray-50';
            });
            if (btn) btn.className = 'filter-periode-btn px-3 py-2 rounded-xl text-[11px] font-black transition bg-blue-600 text-white shadow-sm';
            filterTableLokal();
        }

        function filterTableLokal() {
            const q = document.getElementById('dtSearch').value.toLowerCase();
            const filterKelasEl = document.getElementById('filterKelas');
            const classVal = filterKelasEl ? filterKelasEl.value : (document.getElementById('filterKelasAbs')?.value || '');
            const filterRoleEl = document.getElementById('filterHistoryRole');
            const roleVal = filterRoleEl ? filterRoleEl.value : '';

            const now = new Date();
            const cutoff = currentFilterDays > 0 ? new Date(now - currentFilterDays * 86400000) : null;

            currentFilteredData = globalDataStore[currentTableId].filter(d => {
                // Filter role (history only)
                if (roleVal && (d.role||'') !== roleVal) return false;

                let matchQ = false;
                if (currentTableId === 'history') {
                    matchQ = (d.aktor||'').toLowerCase().includes(q) || (d.aksi||'').toLowerCase().includes(q);
                } else if (currentTableId === 'buku_saku') {
                    matchQ = (d.kelakuan||'').toLowerCase().includes(q);
                } else if (currentTableId === 'absensi') {
                    // Raw absensi: field nama ada di nama_siswa
                    matchQ = (d.nama_siswa||d.nama||'').toLowerCase().includes(q)
                          || (d.nisn||'').toLowerCase().includes(q)
                          || (d.kelas||'').toLowerCase().includes(q);
                } else if (currentTableId === 'poin') {
                    // Data siswa: field nama = d.nama, juga bisa cari nisn/kelas
                    matchQ = (d.nama||'').toLowerCase().includes(q)
                          || (d.nisn||'').toLowerCase().includes(q)
                          || (d.kelas||'').toLowerCase().includes(q);
                } else {
                    matchQ = (d.nama||d.nama_lengkap||d.pengirim_nama||d.nama_anggota||d.nama_aplikasi||'').toLowerCase().includes(q);
                }

                let matchClass = true;
                if (currentTableId === 'absensi') { matchClass = !classVal || ((d.kelas || (window._kelasBySiswa || {})[d.id_siswa] || '') === classVal); }
                else if (classVal && (d.kelas !== undefined || d.pengirim_kelas !== undefined)) {
                    matchClass = (d.kelas === classVal || d.pengirim_kelas === classVal);
                }

                let matchDate = true;
                if (currentTableId === 'absensi') matchDate = _cocokPeriodeAbs(d);
                // Filter tanggal hanya untuk laporan/masukan/konselor/history
                if (cutoff && d.created_at && !['siswa','poin','absensi'].includes(currentTableId)) {
                    matchDate = new Date(d.created_at) >= cutoff;
                }

                return matchQ && matchClass && matchDate;
            });
            hideBulkBar(); currentPage = 1; renderDataTableBody();
        }

        function renderDataTableBody() {
            const tbody = document.getElementById('dtBody'); tbody.innerHTML = '';
            if(currentFilteredData.length === 0 && currentTableId !== 'absensi') {
                tbody.innerHTML = '<tr>
<td colspan="10" class="text-center py-10 text-gray-500">
    <i class="fas fa-folder-open text-4xl mb-3 block text-gray-300">
    </i>Data tidak ditemukan.</td>
</tr>';
                document.getElementById('dtInfo').textContent = 'Menampilkan 0 data'; document.getElementById('dtPaginationControls').innerHTML = ''; return;
            }

            let displayData = currentFilteredData;
            if (currentTableId === 'absensi') {
                // Aggregate raw absensi → per siswa
                // JANGAN overwrite globalDataStore['absensi'] — rawData harus tetap ada untuk search ulang
                const siswaMaster = globalDataStore['siswa'] || [];
                const agg = {};
                currentFilteredData.forEach(r => {
                    const key = r.id_siswa || r.nama_siswa;
                    if (!agg[key]) {
                        // Fallback nisn/kelas dari master siswa jika kosong di record absensi
                        const masterSiswa = siswaMaster.find(s => s.id === r.id_siswa);
                        agg[key] = {
                            id:    r.id_siswa,
                            nama:  r.nama_siswa,
                            nisn:  r.nisn  || masterSiswa?.nisn  || '-',
                            kelas: r.kelas || masterSiswa?.kelas || '-',
                            h: 0, s: 0, i: 0, a: 0, terlambat: 0, history: []
                        };
                    }
                    // Update nisn/kelas jika record ini punya data lebih lengkap
                    if (r.nisn  && agg[key].nisn  === '-') agg[key].nisn  = r.nisn;
                    if (r.kelas && agg[key].kelas === '-') agg[key].kelas = r.kelas;

                    const j = r.jenis;
                    if (j === 'Hadir') { agg[key].h++; if (r.is_late) agg[key].terlambat++; }
                    else if (j === 'Sakit') agg[key].s++;
                    else if (j === 'Izin')  agg[key].i++;
                    else if (j === 'Alpa')  agg[key].a++;
                    agg[key].history.push(r);
                });
                {   // tampilkan juga siswa tanpa catatan pada periode ini (nilai 0), sesuai filter kelas & pencarian
                    const kelasF = document.getElementById('filterKelasAbs')?.value || '';
                    const qF = (document.getElementById('dtSearch')?.value || '').toLowerCase();
                    siswaMaster.forEach(s => {
                        if ((s.status || 'Aktif') !== 'Aktif' || agg[s.id]) return;
                        if (kelasF && s.kelas !== kelasF) return;
                        if (qF && !((s.nama || '').toLowerCase().includes(qF) || (s.nisn || '').includes(qF) || (s.kelas || '').toLowerCase().includes(qF))) return;
                        agg[s.id] = { id: s.id, nama: s.nama, nisn: s.nisn || '-', kelas: s.kelas || '-', h: 0, s: 0, i: 0, a: 0, terlambat: 0, history: [] };
                    });
                }
                window._absensiAggMap = agg; Object.keys(agg).forEach(k => { agg[k].key = k; });
                displayData = Object.values(agg).map(d => ({
                    ...d,
                    tepat_waktu:    d.h - d.terlambat,
                    poin_terlambat: d.terlambat * 6
                }));
                // TIDAK overwrite globalDataStore['absensi'] — rawData tetap terjaga untuk filter ulang
            }

            window._displayData = displayData;
            if (!displayData.length) { tbody.innerHTML = '<tr>
<td colspan="10" class="text-center py-10 text-gray-500">
    <i class="fas fa-folder-open text-4xl mb-3 block text-gray-300">
    </i>Data tidak ditemukan.</td>
</tr>'; document.getElementById('dtInfo').textContent = 'Menampilkan 0 data'; document.getElementById('dtPaginationControls').innerHTML = ''; return; }
            const totalRows = displayData.length; const totalPages = Math.ceil(totalRows / rowsPerPage);
            const startIdx = (currentPage - 1) * rowsPerPage; const endIdx = startIdx + rowsPerPage;
            const paginatedData = displayData.slice(startIdx, endIdx);

            paginatedData.forEach(item => {
                let tr = `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">`;
                if (currentTableId === 'alumni') {
                    tr += `<td class="px-3 py-4 text-center w-12">
<input type="checkbox" class="bulk-check w-5 h-5 rounded border-2 border-gray-300 accent-blue-600 cursor-pointer" value="${item.id}" onchange="updateBulkBar()">
</td>
<td class="px-5 py-4 font-bold text-gray-800">${item.nama}</td>
<td class="px-5 py-4 text-xs text-gray-600">${item.nisn||'-'}</td>
<td class="px-5 py-4 text-sm font-bold text-gray-600">${item.kelas_terakhir||'-'}</td>
<td class="px-5 py-4 text-xs text-gray-600">${item.tanggal_lulus||'-'}</td>
<td class="px-5 py-4 text-right">
    <button onclick="kembalikanAlumni('${item.id}')" class="bg-green-100 text-green-700 hover:bg-green-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-black transition">
        <i class="fas fa-undo mr-1">
        </i>Kembalikan ke Siswa</button>
    </td>`;
                } else if (['laporan', 'masukan', 'konselor'].includes(currentTableId)) {
                    let stColor = item.status === 'Selesai' ? 'text-green-600 bg-green-50 border-green-200' : (item.status === 'Ditolak' ? 'text-red-600 bg-red-50 border-red-200' : (item.status === 'Sedang Diproses' ? 'text-blue-600 bg-blue-50 border-blue-200' : 'text-orange-600 bg-orange-50 border-orange-200'));
                    tr = `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50" data-id="${item.id}">`;
                    tr += `<td class="px-3 py-4 text-center w-12">
    <input type="checkbox" class="bulk-check w-5 h-5 rounded border-2 border-gray-300 checked:border-blue-600 text-blue-600 cursor-pointer accent-blue-600" value="${item.id}" onchange="updateBulkBar()">
</td>`;
                    const _esc = s => String(s||'').replace(/\\/g,'\\\\').replace(/'/g,"\\'");
                    tr += `<td class="px-5 py-4 font-bold text-xs">${formatTgl(item.created_at)}</td>
<td class="px-5 py-4">
    <p class="font-bold text-gray-800">${item.pengirim_nama}</p>
    <p class="text-[10px] text-gray-500 uppercase tracking-widest">${item.pengirim_kelas} • ${item.pengirim_wa}</p>
</td>
<td class="px-5 py-4">
    <span class="bg-gray-100 border px-2 py-1 rounded text-xs font-bold">${item.kategori}</span>
</td>
<td class="px-5 py-4 max-w-xs truncate">${item.detail_pesan}</td>
<td class="px-5 py-4">
    <span class="px-2.5 py-1 rounded border text-[10px] font-black uppercase tracking-widest ${stColor}">${item.status}</span>
</td>
<td class="px-5 py-4 text-right flex items-center justify-end gap-1">
    <button onclick="bukaDetailAspirasi('${_esc(item.id)}')" class="bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white p-2 rounded-lg transition" title="Detail">
        <i class="fas fa-search-plus">
        </i>
    </button>
    <button onclick="bukaModalBalasan('${_esc(item.id)}','${_esc(item.pengirim_nama)}','${_esc(item.pengirim_wa||'')}')" class="bg-green-100 text-green-600 hover:bg-green-600 hover:text-white p-2 rounded-lg transition" title="Balas">
        <i class="fas fa-reply">
        </i>
    </button>
    <button onclick="hapusSatuData('aspirasi','${_esc(item.id)}')" class="text-red-500 hover:bg-red-100 p-2 rounded-lg transition" title="Hapus">
        <i class="fas fa-trash-alt">
        </i>
    </button>
</td>`;
                }
                else if (currentTableId === 'absensi') {
                    const terlambat = item.terlambat || 0;
                    const tepatWaktu = item.tepat_waktu !== undefined ? item.tepat_waktu : (item.h||0);
                    tr = `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
<td class="px-3 py-4 text-center w-12">
    <input type="checkbox" class="bulk-check w-5 h-5 rounded border-2 border-gray-300 text-blue-600 cursor-pointer accent-blue-600" value="${String(item.key).replace(/&/g,'&amp;').replace(/"/g,'&quot;')}" onchange="updateBulkBar()">
</td>
<td class="px-5 py-4">
    <p class="font-bold text-gray-800">${item.nama}</p>
    <p class="text-[10px] text-gray-500 uppercase tracking-widest">NISN: ${item.nisn||'-'}</p>
</td>
<td class="px-5 py-4 font-bold text-gray-600 text-sm">${item.kelas||'-'}</td>
<td class="px-5 py-4 text-center">
    <span class="bg-blue-100 text-blue-700 font-black text-sm px-2.5 py-1 rounded-xl">${item.h||0}</span>
</td>
<td class="px-5 py-4 text-center">
    <span class="bg-green-100 text-green-700 font-black text-sm px-2.5 py-1 rounded-xl">${tepatWaktu}</span>
</td>
<td class="px-5 py-4 text-center">${terlambat > 0 ? `<span class="bg-orange-100 text-orange-700 font-black text-sm px-2.5 py-1 rounded-xl">${terlambat}</span>` : '<span class="text-gray-300 text-xs">—</span>'}</td>
<td class="px-5 py-4 text-center">
    <span class="bg-yellow-100 text-yellow-700 font-black text-sm px-2.5 py-1 rounded-xl">${item.s||0}</span>
</td>
<td class="px-5 py-4 text-center">
    <span class="bg-indigo-100 text-indigo-700 font-black text-sm px-2.5 py-1 rounded-xl">${item.i||0}</span>
</td>
<td class="px-5 py-4 text-center">
    <span class="bg-red-100 text-red-700 font-black text-sm px-2.5 py-1 rounded-xl">${item.a||0}</span>
</td>
<td class="px-5 py-4 text-right">
    <button data-key="${String(item.key).replace(/&/g,'&amp;').replace(/"/g,'&quot;')}" onclick="lihatDetailAbsensi(this.dataset.key)" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition text-xs font-bold">
        <i class="fas fa-history">
        </i>
    </button>
</td>
</tr>`;
                }
                else if (currentTableId === 'poin') {
                    let point = parseInt(item.total_poin) || 0; let st = getSanksiTextAdmin(point);
                    tr = `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
<td class="px-3 py-4 text-center w-12">
    <input type="checkbox" class="bulk-check w-4 h-4 rounded text-blue-600 cursor-pointer" value="${item.id}" onchange="updateBulkBar()">
</td>`;
                    tr += `<td class="px-5 py-4">
<p class="font-bold text-gray-800">${item.nama}</p>
<p class="text-[10px] text-gray-500 uppercase tracking-widest">NISN: ${item.nisn||'-'}</p>
</td>
<td class="px-5 py-4 font-bold text-sm text-gray-600">${item.kelas}</td>
<td class="px-5 py-4">
    <span class="px-3 py-1 rounded border font-black text-lg ${point > 50 ? 'bg-red-50 text-red-600 border-red-200' : (point > 0 ? 'bg-orange-50 text-orange-600 border-orange-200' : 'bg-green-50 text-green-600 border-green-200')}">${point}</span>
</td>
<td class="px-5 py-4 text-xs font-bold text-gray-600 max-w-xs truncate">${point > 0 ? st.t : '-'}</td>
<td class="px-5 py-4 text-right">
    <button onclick="bukaModalData('poin', 'edit', '${item.id}')" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition mr-1">
        <i class="fas fa-edit">
        </i>
    </button>
    <button onclick="hapusSatuData('siswa', '${item.id}', true)" class="text-green-500 hover:bg-green-100 p-2 rounded-lg transition" title="Reset Poin ke 0">
        <i class="fas fa-redo-alt">
        </i>
    </button>
</td>`;
                }
                else if (currentTableId === 'buku_saku') {
                    const katColor = item.kategori === 'Kedisiplinan' ? 'bg-blue-50 text-blue-700 border-blue-200' : item.kategori === 'Kerapian' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-green-50 text-green-700 border-green-200';
                    tr += `<td class="px-5 py-3 w-8">
<input type="checkbox" class="w-5 h-5 rounded border-2 border-gray-300 text-blue-600 cursor-pointer accent-blue-600 bulk-check" value="${item.id}" onchange="updateBulkBar()">
</td>`;
                    tr += `<td class="px-5 py-4 font-bold text-sm text-gray-800 whitespace-normal leading-relaxed">${item.kelakuan}</td>`;
                    tr += `<td class="px-5 py-4">
<span class="border px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-widest ${katColor}">${item.kategori}</span>
</td>`;
                    tr += `<td class="px-5 py-4">
<span class="px-3 py-1 rounded border font-black text-sm ${item.point > 50 ? 'bg-red-50 text-red-600 border-red-200' : item.point > 10 ? 'bg-orange-50 text-orange-600 border-orange-200' : 'bg-yellow-50 text-yellow-600 border-yellow-200'}">${item.point} pts</span>
</td>`;
                    tr += `<td class="px-5 py-4 text-right whitespace-nowrap">
<button onclick="editBukuSakuInline('${item.id}')" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition mr-1" title="Edit">
    <i class="fas fa-edit">
    </i>
</button>
<button onclick="hapusSatuData('master_pelanggaran', '${item.id}')" class="text-red-500 hover:bg-red-100 p-2 rounded-lg transition" title="Hapus">
    <i class="fas fa-trash-alt">
    </i>
</button>
</td>`;
                }
                else if (currentTableId === 'siswa') {
                    const isBlok = (item.status||'Aktif') === 'Diblokir';
                    tr = `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50 ${isBlok?'opacity-60':''}">`;
                    tr += `<td class="px-3 py-4 text-center w-12">
<input type="checkbox" class="bulk-check w-5 h-5 rounded border-2 border-gray-300 checked:border-blue-600 text-blue-600 cursor-pointer accent-blue-600" value="${item.id}" onchange="updateBulkBar()">
</td>`;
                    tr += `<td class="px-5 py-4">
<p class="font-bold text-gray-800">${item.nama}</p>
<p class="text-[10px] text-gray-500 uppercase tracking-widest">NISN: ${item.nisn||'-'} • ${item.no_telp||'-'}</p>
</td>`;
                    tr += `<td class="px-5 py-4 font-bold text-xs text-gray-600">${item.tgl_lahir||'-'}</td>`;
                    tr += `<td class="px-5 py-4 font-bold text-sm text-gray-600">
<span class="bg-gray-100 border px-2 py-1 rounded">${item.kelas}</span>
</td>`;
                    tr += `<td class="px-5 py-4">
<span class="text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded ${(item.status||'Aktif')==='Aktif'?'bg-green-100 text-green-700':(item.status==='Diblokir'?'bg-red-100 text-red-700':'bg-gray-100 text-gray-600')}">${item.status||'Aktif'}</span>
</td>`;
                    tr += `<td class="px-5 py-4">
<div class="flex items-center justify-end gap-1">
    <button onclick="bukaEditSiswa('${item.id}')" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition" title="Edit">
        <i class="fas fa-edit">
        </i>
    </button>
    <button onclick="lihatQRSiswa('${item.id}')" class="text-purple-500 hover:bg-purple-100 p-2 rounded-lg transition" title="Lihat QR">
        <i class="fas fa-qrcode">
        </i>
    </button>
    <button onclick="toggleBlokir('${item.id}','${item.status||'Aktif'}')" class="${isBlok?'text-green-500 hover:bg-green-100':'text-yellow-500 hover:bg-yellow-100'} p-2 rounded-lg transition" title="${isBlok?'Aktifkan':'Blokir'}">
        <i class="fas fa-${isBlok?'unlock-alt':'ban'}">
        </i>
    </button>
    <button onclick="hapusSatuData('siswa','${item.id}')" class="text-red-500 hover:bg-red-100 p-2 rounded-lg transition" title="Hapus">
        <i class="fas fa-trash-alt">
        </i>
    </button>
</div>
</td>`;
                }
                else if (currentTableId === 'anggota_elpoint') {
                    tr = `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
<td class="px-3 py-4 text-center w-12">
    <input type="checkbox" class="bulk-check w-4 h-4 rounded text-blue-600 cursor-pointer" value="${item.id}" onchange="updateBulkBar()">
</td>`;
                    tr += `<td class="px-5 py-4 font-bold text-gray-800">${item.nama_anggota}</td>
<td class="px-5 py-4 font-mono font-bold text-blue-600 text-xs">${item.username}</td>
<td class="px-5 py-4">
    <span class="bg-purple-50 text-purple-700 border border-purple-200 px-2 py-1 rounded text-[10px] font-black uppercase">${item.role}</span>
</td>
<td class="px-5 py-4 text-right">
    <button onclick="bukaModalData('anggota_elpoint', 'edit', '${item.id}')" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition mr-1">
        <i class="fas fa-edit">
        </i>
    </button>
    <button onclick="hapusSatuData('anggota_elpoint', '${item.id}')" class="text-red-500 hover:bg-red-100 p-2 rounded-lg transition">
        <i class="fas fa-trash-alt">
        </i>
    </button>
</td>`;
                }
                else if (currentTableId === 'apikey') {
                    const apiBtnAksi = item.status === 'Aktif'
                        ? `<button onclick="cabutSatuApiKey('${item.id}')" title="Hentikan Sementara" class="text-yellow-500 hover:bg-yellow-100 p-2 rounded-lg transition">
<i class="fas fa-pause-circle">
</i>
</button>`
                        : `<button onclick="aktifkanSatuApiKey('${item.id}')" title="Aktifkan Kembali" class="text-green-500 hover:bg-green-100 p-2 rounded-lg transition">
<i class="fas fa-play-circle">
</i>
</button>`;
                    const apiStatusColor = item.status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600';
                    tr = `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
<td class="px-3 py-4 text-center w-12">
    <input type="checkbox" class="bulk-check w-5 h-5 rounded border-2 border-gray-300 text-blue-600 cursor-pointer accent-blue-600" value="${item.id}" onchange="updateBulkBar()">
</td>
<td class="px-5 py-4 font-bold text-gray-800 uppercase">${item.nama_aplikasi}</td>
<td class="px-5 py-4 font-mono font-bold text-blue-600 text-xs tracking-wider bg-gray-50 border-x">${item.api_key}</td>
<td class="px-5 py-4 text-xs font-bold text-gray-500">${formatTgl(item.created_at)}</td>
<td class="px-5 py-4">
    <span class="text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded ${apiStatusColor}">${item.status}</span>
</td>
<td class="px-5 py-4 text-right">
    <div class="flex items-center gap-1 justify-end">
                                ${apiBtnAksi}
                                <button onclick="hapusSatuData('api_keys', '${item.id}')" title="Hapus" class="text-red-500 hover:bg-red-100 p-2 rounded-lg transition">
    <i class="fas fa-trash-alt">
    </i>
</button>
</div>
</td>
</tr>`;
                }
                else if (currentTableId === 'guru') {
                    const kelasArr = (() => { try { return typeof item.kelas_diajarkan === 'string' ? JSON.parse(item.kelas_diajarkan) : (item.kelas_diajarkan||[]); } catch(e) { return []; }})();
                    const mapelArr = (() => { try { return typeof item.mapel === 'string' ? JSON.parse(item.mapel) : (item.mapel||[]); } catch(e) { return []; }})();
                    const statusCls = item.status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600';
                    tr = `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
<td class="px-3 py-4 text-center w-12">
    <input type="checkbox" class="bulk-check w-5 h-5 rounded border-2 border-gray-300 text-blue-600 cursor-pointer accent-blue-600" value="${item.id}" onchange="updateBulkBar()">
</td>
<td class="px-5 py-4">
    <p class="font-bold text-gray-800">${item.nama_lengkap||'-'}</p>
    <p class="text-[10px] text-gray-400">${item.nama_panggilan||''}</p>
</td>
<td class="px-5 py-4 font-mono text-xs text-gray-500">${item.nip||'—'}</td>
<td class="px-5 py-4 text-xs text-gray-600">${mapelArr.slice(0,2).join(', ')}${mapelArr.length>2?'...':''}</td>
<td class="px-5 py-4 text-xs text-gray-600">${kelasArr.length} kelas</td>
<td class="px-5 py-4 text-xs text-gray-500">${item.no_wa||'—'}</td>
<td class="px-5 py-4">
    <span class="text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded ${statusCls}">${item.status||'Aktif'}</span>
</td>
<td class="px-5 py-4 text-right flex items-center gap-1 justify-end">
    <button onclick="bukaModalData('guru','edit','${item.id}')" title="Edit" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition">
        <i class="fas fa-edit">
        </i>
    </button>
    <button onclick="toggleBlokirGuru('${item.id}','${item.status||'Aktif'}')" title="${item.status==='Diblokir'?'Buka Blokir':'Blokir'}" class="${item.status==='Diblokir'?'text-green-500 hover:bg-green-100':'text-yellow-500 hover:bg-yellow-100'} p-2 rounded-lg transition">
        <i class="fas fa-${item.status==='Diblokir'?'unlock-alt':'ban'}">
        </i>
    </button>
    <button onclick="hapusSatuData('master_guru','${item.id}')" title="Hapus" class="text-red-500 hover:bg-red-100 p-2 rounded-lg transition">
        <i class="fas fa-trash-alt">
        </i>
    </button>
</td>
</tr>`;
                }
                else if (currentTableId === 'lapguru') {
                    const poinCls = (item.poin_siswa||0) >= 10 ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600';
                    const stLapCls = item.status === 'Dibatalkan' ? 'bg-gray-100 text-gray-500' :
                                     item.status === 'Selesai' ? 'bg-green-100 text-green-700' :
                                     item.status === 'Sedang Diproses' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-600';
                    const _e = s => String(s||'').replace(/\\/g,'\\\\').replace(/'/g,"\\'");
                    tr = `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
<td class="px-3 py-4 text-center w-12">
    <input type="checkbox" class="bulk-check w-5 h-5 rounded border-2 border-gray-300 text-blue-600 cursor-pointer accent-blue-600" value="${item.id}" onchange="updateBulkBar()">
</td>
<td class="px-5 py-4">
    <p class="font-bold text-gray-800 text-sm">${item.nama_guru||'-'}</p>
    <p class="text-[10px] text-gray-400">Guru</p>
</td>
<td class="px-5 py-4">
    <p class="font-bold text-gray-800 text-sm">${item.nama_siswa||'-'}</p>
    <p class="text-[10px] text-gray-400">${item.kelas_siswa||''}</p>
</td>
<td class="px-5 py-4 text-xs text-gray-600">${item.mata_pelajaran||'—'}</td>
<td class="px-5 py-4 text-xs text-gray-600">${item.tanggal||''} ${item.jam||''}</td>
<td class="px-5 py-4 text-xs text-gray-700 font-bold">${item.label_jenis||item.jenis||'—'}</td>
<td class="px-5 py-4 text-center">
    <span class="text-xs font-black px-2 py-1 rounded ${poinCls}">+${item.poin_siswa||0}</span>
</td>
<td class="px-5 py-4 text-center">
    <span class="text-[10px] font-black px-2 py-1 rounded ${stLapCls}">${item.status||'Terkirim'}</span>
</td>
<td class="px-5 py-4 text-right flex items-center gap-1 justify-end">
    <button onclick="bukaDetailLaporanGuru('${_e(item.id)}')" title="Detail" class="bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white p-2 rounded-lg transition">
        <i class="fas fa-search-plus">
        </i>
    </button>
    <button onclick="hapusSatuData('laporan_guru','${_e(item.id)}')" title="Hapus" class="text-red-500 hover:bg-red-100 p-2 rounded-lg transition">
        <i class="fas fa-trash-alt">
        </i>
    </button>
</td>
</tr>`;
                }
                else if (currentTableId === 'admin') {
                    tr = `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
<td class="px-3 py-4 text-center w-12">
    <input type="checkbox" class="bulk-check w-5 h-5 rounded border-2 border-gray-300 text-blue-600 cursor-pointer accent-blue-600" value="${item.id}" onchange="updateBulkBar()">
</td>`;
                    tr += `<td class="px-5 py-4">
<p class="font-bold text-gray-800">${item.nama_lengkap||'-'}</p>
<p class="text-[10px] text-gray-500 uppercase tracking-widest">${item.no_telp||'-'}</p>
</td>`;
                    tr += `<td class="px-5 py-4 text-sm text-gray-600">${item.email_admin}</td>`;
                    tr += `<td class="px-5 py-4">
<span class="bg-blue-100 text-blue-700 text-[10px] font-black px-2 py-1 rounded-full">${item.role_name||'Admin'}</span>
</td>`;
                    tr += _aksiAdmin(item) + '</tr>';
                }
                else if (currentTableId === 'semester') {
                    const aktifBadge = item.aktif
                        ? '<span class="bg-green-100 text-green-700 text-[10px] font-black px-2.5 py-1 rounded-full">✓ AKTIF</span>'
                        : '<span class="bg-gray-100 text-gray-400 text-[10px] font-black px-2.5 py-1 rounded-full">Non-aktif</span>';
                    const _e = s => String(s||'').replace(/'/g,"\\'");
                    tr = `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
<td class="px-3 py-4 text-center w-12">
    <input type="checkbox" class="bulk-check w-5 h-5 rounded border-2 border-gray-300 accent-blue-600 cursor-pointer" value="${item.id}" onchange="updateBulkBar()">
</td>
<td class="px-5 py-4 font-bold text-gray-800">${item.tahun_ajaran||'-'}</td>
<td class="px-5 py-4 text-sm text-gray-600 font-bold">${item.semester||'-'}</td>
<td class="px-5 py-4 text-sm text-gray-700">${item.label||'-'}</td>
<td class="px-5 py-4 text-xs font-bold text-gray-600">${_fmtTglPendek(item.tanggal_mulai)} <span class="text-gray-400 font-normal">s/d</span> ${_fmtTglPendek(item.tanggal_akhir)}</td>
<td class="px-5 py-4">${aktifBadge}</td>
<td class="px-5 py-4 text-right flex items-center justify-end gap-1">
                            ${!item.aktif ? `<button onclick="setSemesterAktif('${_e(item.id)}','${_e(item.label)}')" class="bg-green-100 text-green-700 hover:bg-green-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-black transition" title="Jadikan Aktif">
<i class="fas fa-check-circle mr-1">
</i>Aktifkan</button>` : ''}
                            <button onclick="bukaModalData('semester','edit','${_e(item.id)}')" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition">
<i class="fas fa-edit">
</i>
</button>
<button onclick="hapusSatuData('master_semester','${_e(item.id)}')" class="text-red-500 hover:bg-red-100 p-2 rounded-lg transition">
    <i class="fas fa-trash-alt">
    </i>
</button>
</td>
</tr>`;
                }
                else if (currentTableId === 'mapel') {
                    const aktifBadge = item.aktif !== false
                        ? '<span class="bg-green-100 text-green-700 text-[10px] font-black px-2 py-0.5 rounded-full">Aktif</span>'
                        : '<span class="bg-red-100 text-red-500 text-[10px] font-black px-2 py-0.5 rounded-full">Non-aktif</span>';
                    const _e = s => String(s||'').replace(/'/g,"\\'");
                    tr = `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
<td class="px-3 py-4 text-center w-12">
    <input type="checkbox" class="bulk-check w-5 h-5 rounded border-2 border-gray-300 accent-blue-600 cursor-pointer" value="${item.id}" onchange="updateBulkBar()">
</td>
<td class="px-5 py-4 font-bold text-gray-800">${item.nama_mapel||'-'}</td>
<td class="px-5 py-4">
    <span class="bg-blue-100 text-blue-700 text-[10px] font-black px-2 py-0.5 rounded">${item.kode_mapel||'-'}</span>
</td>
<td class="px-5 py-4">${aktifBadge}</td>
<td class="px-5 py-4 text-right flex items-center justify-end gap-1">
    <button onclick="bukaModalData('mapel','edit','${_e(item.id)}')" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition">
        <i class="fas fa-edit">
        </i>
    </button>
    <button onclick="hapusSatuData('master_mapel','${_e(item.id)}')" class="text-red-500 hover:bg-red-100 p-2 rounded-lg transition">
        <i class="fas fa-trash-alt">
        </i>
    </button>
</td>
</tr>`;
                }
                else if (currentTableId === 'history') {
                    tr = `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
<td class="px-3 py-4 text-center w-12">
    <input type="checkbox" class="bulk-check w-5 h-5 rounded border-2 border-gray-300 text-blue-600 cursor-pointer accent-blue-600" value="${item.id||item.aktor}" onchange="updateBulkBar()">
</td>`;
                    tr += `<td class="px-5 py-4 text-xs font-bold text-gray-500">${formatTgl(item.created_at)}</td>
<td class="px-5 py-4 font-bold text-gray-800">${item.aktor}</td>
<td class="px-5 py-4">
    <span class="bg-gray-100 border px-2 py-1 rounded text-[10px] font-black uppercase">${item.role}</span>
</td>
<td class="px-5 py-4 font-bold text-sm text-blue-600">${item.aksi}</td>
<td class="px-5 py-4 text-xs font-medium text-gray-600 max-w-sm truncate" title="${item.detail}">${item.detail}</td>`;
                }
                tr += `</tr>`; tbody.innerHTML += tr;
            });

            document.getElementById('dtInfo').textContent = `Baris ${startIdx + 1} - ${Math.min(endIdx, totalRows)} dari ${totalRows}`;
            let pagHtml = `<button onclick="changePage(-1)" class="px-3 py-1.5 rounded-lg border bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-50" ${currentPage === 1 ? 'disabled' : ''}>
<i class="fas fa-chevron-left">
</i>
</button>
<span class="text-xs font-bold px-2">Hal ${currentPage}/${totalPages || 1}</span>
<button onclick="changePage(1)" class="px-3 py-1.5 rounded-lg border bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-50" ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}>
    <i class="fas fa-chevron-right">
    </i>
</button>`;
            document.getElementById('dtPaginationControls').innerHTML = pagHtml;
            _terapkanSelKeDOM(); updateBulkBar();
        }

        function changePage(dir) { currentPage += dir; renderDataTableBody(); }

        // ============================================================
        // BULK SELECT & NAIK KELAS
        // ============================================================
        async function hapusSatuData(tableName, id, isReset = false) {
            // Demo mode: hapus dari memory saja
            if (typeof isDemoMode !== 'undefined' && isDemoMode) {
                const conf = await Swal.fire({ title:'Hapus Data Demo?', text:'Hanya dari tampilan demo, tidak tersimpan ke DB.', icon:'warning', showCancelButton:true, confirmButtonColor:'#d33', confirmButtonText:'Hapus', cancelButtonText:'Batal' });
                if (!conf.isConfirmed) return;
                for (const key of Object.keys(DEMO_DATA)) {
                    DEMO_DATA[key] = (DEMO_DATA[key]||[]).filter(d => d.id !== id);
                    if (globalDataStore[key]) globalDataStore[key] = globalDataStore[key].filter(d => d.id !== id);
                }
                currentFilteredData = currentFilteredData.filter(d => d.id !== id);
                renderDataTableBody(); hideBulkBar();
                Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Data demo dihapus', showConfirmButton:false, timer:1500 });
                return;
            }
            let actionType = 'deleteData'; let titleText = `Hapus Data?`; let confirmText = 'Hapus';
            if (isReset) { actionType = 'updateData'; titleText = `Reset poin menjadi 0?`; confirmText = 'Ya, Reset'; }
            
            const res = await Swal.fire({ title: titleText, icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: confirmText });
            if (res.isConfirmed) {
                Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading() });
                try { 
                    if (actionType === 'deleteData') { await callBackend({ type: 'supabase_post', action: 'deleteData', payload: { table: tableName, id: id } }); } 
                    else { await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: tableName, id: id, payload: { total_poin: 0 } } }); }
                    Swal.fire('Berhasil', '', 'success'); 
                    if(tableName === 'daftar_kelas') muatDataKelas();
                    else if(tableName === 'daftar_role') muatDataRole();
                    else {
                        // Reload tabel yang sedang aktif, bukan tabel berdasarkan nama DB
                        const menuItem = MENU_CONFIG.find(m => m.id === currentTableId);
                        loadDataTable(menuItem ? menuItem.table : tableName, currentTableId);
                    }
                } catch(e) { Swal.fire('Kesalahan', e.message, 'error'); }
            }
        }

        async function bukaModalData(tipe, actionType, recordId = null) {
            _pastikanFormGeneric();
            document.getElementById('tambahType').value = tipe; document.getElementById('formActionType').value = actionType; document.getElementById('formRecordId').value = recordId || '';
            const container = document.getElementById('dynamicFormFields'); container.innerHTML = ''; document.getElementById('modalDataTitle').textContent = actionType === 'add' ? 'Tambah Data' : 'Edit Data';
            let ex = {}; if(actionType === 'edit' && recordId) ex = globalDataStore[tipe].find(d => d.id === recordId) || {};

            if(tipe === 'poin') { container.innerHTML = `<div>
<label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Set Poin Manual</label>
<input type="number" id="fmPoinBaru" required value="${ex.total_poin||0}" class="w-full p-3 border-2 border-gray-200 rounded-xl font-black bg-white outline-none focus:border-indigo-500">
</div>`; }
            else if(tipe === 'buku_saku') { container.innerHTML = `<div>
<label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Deskripsi Pelanggaran</label>
<textarea id="fmBukuKelakuan" required class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-indigo-500" rows="3">${ex.kelakuan||''}</textarea>
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Kategori</label>
        <select id="fmBukuKategori" required class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-indigo-500">
            <option value="Kedisiplinan" ${ex.kategori==='Kedisiplinan'?'selected':''}>Kedisiplinan</option>
            <option value="Kerapian" ${ex.kategori==='Kerapian'?'selected':''}>Kerapian</option>
            <option value="Tanggung Jawab" ${ex.kategori==='Tanggung Jawab'?'selected':''}>Tanggung Jawab</option>
        </select>
    </div>
    <div>
        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Bobot Poin</label>
        <input type="number" id="fmBukuPoin" required value="${ex.point||0}" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-indigo-500">
    </div>
</div>`; }
            else if(tipe === 'semester') {
                const taVal = ex.tahun_ajaran || '';
                const smVal = ex.semester || 'Ganjil';
                const aktifVal = ex.aktif || false;
                container.innerHTML = `
                    <div>
<label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 block">Tahun Ajaran</label>
<input type="text" id="fmSemTA" required value="${taVal}" placeholder="contoh: 2025/2026"
                        class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500"
                        oninput="document.getElementById('fmSemLabel').value=(this.value||'')+' '+(document.getElementById('fmSemSmt').value||'')">
</div>
<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 block">Semester</label>
    <select id="fmSemSmt" required class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500"
                        onchange="document.getElementById('fmSemLabel').value=(document.getElementById('fmSemTA').value||'')+' '+this.value">
        <option value="Ganjil" ${smVal==='Ganjil'?'selected':''}>Ganjil</option>
        <option value="Genap"  ${smVal==='Genap'?'selected':''}>Genap</option>
    </select>
</div>
<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 block">Label (auto)</label>
    <input type="text" id="fmSemLabel" readonly value="${ex.label||taVal+' '+smVal}"
                        class="w-full p-3 border-2 border-gray-100 rounded-xl font-bold bg-gray-50 outline-none text-gray-400">
</div>
<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 block">Tanggal Awal</label>
        <input type="date" id="fmSemMulai" value="${ex.tanggal_mulai||''}" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500">
    </div>
    <div>
        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 block">Tanggal Akhir</label>
        <input type="date" id="fmSemAkhir" value="${ex.tanggal_akhir||''}" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500">
    </div>
</div>
<label class="flex items-center gap-3 cursor-pointer p-3 bg-green-50 border-2 border-green-200 rounded-xl">
    <input type="checkbox" id="fmSemAktif" ${aktifVal?'checked':''} class="w-5 h-5 accent-green-600">
    <span class="text-sm font-black text-green-700">Jadikan Semester Aktif</span>
</label>`;
            }
            else if(tipe === 'mapel') {
                container.innerHTML = `
                    <div>
<label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 block">Nama Mata Pelajaran</label>
<input type="text" id="fmMapelNama" required value="${ex.nama_mapel||''}" placeholder="contoh: Matematika"
                        class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500">
</div>
<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 block">Kode Mapel (opsional)</label>
    <input type="text" id="fmMapelKode" value="${ex.kode_mapel||''}" placeholder="contoh: MTK"
                        class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500">
</div>
<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 block">Maks. Guru Pengajar</label>
    <input type="number" id="fmMapelMax" min="1" value="${ex.max_guru||''}" placeholder="Kosongkan = tanpa batas" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500">
</div>
<label class="flex items-center gap-3 cursor-pointer p-3 bg-blue-50 border-2 border-blue-200 rounded-xl">
    <input type="checkbox" id="fmMapelAktif" ${ex.aktif!==false?'checked':''} class="w-5 h-5 accent-blue-600">
    <span class="text-sm font-black text-blue-700">Mapel Aktif (tampil di dropdown guru)</span>
</label>`;
            }
            else if(tipe === 'anggota_elpoint') { let pF = `<div>
<label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">${actionType==='add'?'Password':'Password Baru (kosongkan jika tidak diubah)'}</label>
<input type="text" id="fmElpointPass" ${actionType==='add'?'required':''} placeholder="${actionType==='add'?'':'Isi hanya jika ingin mengganti password'}" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-indigo-500">
</div>`; container.innerHTML = `<div>
<label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Nama Anggota</label>
<input type="text" id="fmElpointNama" required value="${ex.nama_anggota||''}" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-indigo-500">
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Username Login</label>
        <input type="text" id="fmElpointUser" required value="${ex.username||''}" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-indigo-500">
    </div>
    <div>
        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Hak Akses</label>
        <select id="fmElpointRole" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none cursor-pointer">
            <option value="OSIS" ${ex.role==='OSIS'?'selected':''}>Petugas OSIS</option>
            <option value="Sekretaris" ${ex.role==='Sekretaris'?'selected':''}>Sekretaris Kelas</option>
        </select>
    </div>
</div>${pF}`; }
            else if(tipe === 'guru') {
                await _siapkanFormGuru(ex);
                container.innerHTML = `
                    <label class="text-xs font-black text-gray-500 uppercase tracking-widest">Nama Lengkap *</label>
<input id="fmGuruNama" value="${ex.nama_lengkap||''}" placeholder="Nama lengkap guru" class="w-full mt-1 mb-3 px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 transition">
<label class="text-xs font-black text-gray-500 uppercase tracking-widest">Nama Panggilan</label>
<input id="fmGuruPanggilan" value="${ex.nama_panggilan||''}" placeholder="Pak/Bu ..." class="w-full mt-1 mb-3 px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 transition">
<label class="text-xs font-black text-gray-500 uppercase tracking-widest">NIP</label>
<input id="fmGuruNip" value="${ex.nip||''}" placeholder="Nomor Induk Pegawai" class="w-full mt-1 mb-3 px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 transition">
<label class="text-xs font-black text-gray-500 uppercase tracking-widest">No WhatsApp</label>
<input id="fmGuruWa" value="${ex.no_wa||''}" placeholder="628xxxxxxxxx" class="w-full mt-1 mb-3 px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 transition">
<label class="text-xs font-black text-gray-500 uppercase tracking-widest">Password ${actionType==='add'?'*':'(kosongkan jika tidak diubah)'}</label>
<input id="fmGuruPass" type="password" placeholder="${actionType==='add'?'Buat password':'Biarkan kosong jika tidak diubah'}" class="w-full mt-1 mb-3 px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 transition">
<label class="text-xs font-black text-gray-500 uppercase tracking-widest">Mata Pelajaran <span class="normal-case font-normal text-gray-400">(maks. 5, dari Master Mapel)</span>
</label>
<div class="flex gap-2 mt-1 mb-2">
    <select id="fmGuruMapelPick" class="flex-1 px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 bg-white">${_guruOpsi('mapel')}</select>
    <button type="button" onclick="guruTambahPilihan('mapel')" class="px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black">Tambah</button>
</div>
<div id="fmGuruMapelChips" class="flex flex-wrap gap-2 mb-3">${_guruChips('mapel')}</div>
<label class="text-xs font-black text-gray-500 uppercase tracking-widest">Kelas Diajarkan <span class="normal-case font-normal text-gray-400">(maks. 15, dari Master Kelas)</span>
</label>
<div class="flex gap-2 mt-1 mb-2">
    <select id="fmGuruKelasPick" class="flex-1 px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 bg-white">${_guruOpsi('kelas')}</select>
    <button type="button" onclick="guruTambahPilihan('kelas')" class="px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black">Tambah</button>
</div>
<div id="fmGuruKelasChips" class="flex flex-wrap gap-2 mb-3">${_guruChips('kelas')}</div>
<label class="text-xs font-black text-gray-500 uppercase tracking-widest">Status</label>
<select id="fmGuruStatus" class="w-full mt-1 px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 bg-white transition">
    <option value="Aktif" ${(ex.status||'Aktif')==='Aktif'?'selected':''}>Aktif</option>
    <option value="Nonaktif" ${ex.status==='Nonaktif'?'selected':''}>Nonaktif</option>
    <option value="Diblokir" ${ex.status==='Diblokir'?'selected':''}>Diblokir (tidak bisa login)</option>
</select>`;
            }
            else if(tipe === 'siswa') {
                // Load daftar kelas dari DB jika belum ada di store
                if (!globalDataStore['kelas'] || globalDataStore['kelas'].length === 0) {
                    try {
                        const kelasRes = await callBackend({ type: 'supabase_get', action: 'getAllKelas' });
                        if (kelasRes.data) globalDataStore['kelas'] = kelasRes.data;
                    } catch(e) {}
                }
                const kelasList = globalDataStore['kelas'] || [];
                const kelasOptions = kelasList.map(k => `<option value="${k.nama_kelas}" ${ex.kelas===k.nama_kelas?'selected':''}>${k.nama_kelas}</option>`).join('');
                container.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="md:col-span-2">
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">Nama Lengkap <span class="text-red-500">*</span>
</label>
<input type="text" id="fmSiswaNama" required value="${ex.nama||''}" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500">
</div>
<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">NISN</label>
    <input type="text" id="fmSiswaNisn" value="${ex.nisn||''}" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500">
</div>
<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">NIK</label>
    <input type="text" id="fmSiswaNik" value="${ex.nik||''}" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500">
</div>
<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">Kelas <span class="text-red-500">*</span>
</label>
<select id="fmSiswaKelas" required class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500 cursor-pointer">
    <option value="">-- Pilih Kelas --</option>${kelasOptions}</select>
</div>
<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">Tanggal Lahir</label>
    <input type="text" id="fmSiswaTgl" value="${ex.tgl_lahir||''}" placeholder="DD/MM/YYYY" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500">
</div>
<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">No. WA</label>
    <input type="text" id="fmSiswaWa" value="${ex.no_telp||''}" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500">
</div>
<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">Status</label>
    <select id="fmSiswaStatus" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500 cursor-pointer">
        <option value="Aktif" ${ex.status==='Aktif'||!ex.status?'selected':''}>Aktif</option>
        <option value="Diblokir" ${ex.status==='Diblokir'?'selected':''}>Diblokir</option>
        <option value="Alumni" ${ex.status==='Alumni'?'selected':''}>Alumni</option>
    </select>
</div>
<div class="md:col-span-2">
    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
        <p class="text-[10px] font-black text-indigo-700 uppercase tracking-widest mb-3 flex items-center gap-1.5">
            <i class="fas fa-lock text-indigo-500">
            </i> Keamanan Akun Siswa (PIN & Verifikasi)</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">PIN Login (4 digit)</label>
                    <div class="flex gap-2">
                        <input type="text" id="fmSiswaPin" value="${ex.login_pin||''}" maxlength="4" pattern="[0-9]{4}" placeholder="${ex.login_pin ? '****' : 'Belum diset'}" class="flex-1 p-2.5 border-2 border-gray-200 rounded-xl font-mono font-bold bg-white outline-none focus:border-indigo-500 text-center tracking-widest text-lg" readonly>
                        <button type="button" onclick="resetPinSiswa('${ex.id||''}')" class="px-3 py-2 bg-red-100 hover:bg-red-500 hover:text-white text-red-600 rounded-xl text-xs font-bold transition" title="Reset PIN siswa">
                            <i class="fas fa-redo-alt">
                            </i> Reset
                                            </button>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">${ex.login_pin ? '✅ PIN sudah diset' : '⚠️ Belum ada PIN — siswa akan diminta set PIN saat login pertama'}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Jawaban Keamanan</label>
                        <input type="text" id="fmSiswaSecAnswer" value="${ex.security_answer||''}" placeholder="${ex.security_answer ? '(tersimpan)' : 'Belum diset'}" class="w-full p-2.5 border-2 border-gray-200 rounded-xl font-medium bg-white outline-none focus:border-indigo-500 text-sm" readonly>
                        <p class="text-[10px] text-gray-400 mt-1">Jawaban: "Di mana tempat favoritmu?"</p>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
            }
            else if(tipe === 'apikey') { container.innerHTML = `<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Nama Aplikasi / Pengguna</label>
    <input type="text" id="fmApiNama" required placeholder="Contoh: Grisapoint Web" class="w-full p-3 border-2 border-gray-200 rounded-xl font-black bg-white outline-none focus:border-indigo-500 uppercase tracking-widest">
</div>
<p class="text-[10px] text-gray-500 mt-2">
    <i class="fas fa-info-circle text-blue-500">
    </i> Sistem akan secara otomatis mengacak dan membuat kunci (API Key) rahasia yang unik saat Anda menekan simpan.</p>`; }
            else if(tipe === 'admin') {
                const roleRes = await callBackend({ type: 'supabase_get', action: 'getAllRoles' });
                const roles = roleRes.data || [];
                const roleOpts = roles.map(r => `<option value="${r.nama_role}" ${ex.role_name===r.nama_role?'selected':''}>${r.nama_role}</option>`).join('');
                container.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">Email Login <span class="text-red-500">*</span>
    </label>
    <input type="email" id="fmAdminEmail" required value="${ex.email_admin||''}" ${actionType==='edit'?'disabled':''} class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500 ${actionType==='edit'?'bg-gray-100 cursor-not-allowed':''}">
</div>
                    ${actionType==='add'?`<div class="md:col-span-2">
<label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">Password Sementara <span class="text-red-500">*</span>
</label>
<input type="password" id="fmAdminPass" required placeholder="Min. 8 karakter" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500">
</div>`:''}
                    <div>
<label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">Nama Lengkap</label>
<input type="text" id="fmAdminNama" value="${ex.nama_lengkap||''}" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500">
</div>
<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">No. WhatsApp</label>
    <input type="text" id="fmAdminTelp" value="${ex.no_telp||''}" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500">
</div>
<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">Jabatan / Role</label>
    <select id="fmAdminRole" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none focus:border-blue-500 cursor-pointer">
                            ${roleOpts}
                        </select>
</div>
<div>
    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">Agama</label>
    <select id="fmAdminAgama" class="w-full p-3 border-2 border-gray-200 rounded-xl font-bold bg-white outline-none cursor-pointer">
                            ${['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'].map(a=>`<option value="${a}" ${(ex.agama||'Islam')===a?'selected':''}>${a}</option>`).join('')}
                        </select>
</div>
</div>`;
            }
            document.getElementById('modalDataForm').classList.remove('hidden');
        }

        document.getElementById('genericDataForm').addEventListener('submit', async function(e) {
            e.preventDefault(); 
            const aT = document.getElementById('formActionType').value; const tp = document.getElementById('tambahType').value; const rI = document.getElementById('formRecordId').value;
            const aksiLabel = aT === 'add' ? 'Tambah data baru' : 'Simpan perubahan data';
            const konfirmGeneric = await Swal.fire({ title: aksiLabel + '?', text: 'Data akan disimpan ke database.', icon: 'question', showCancelButton: true, confirmButtonColor: '#4f46e5', confirmButtonText: 'Iya, Simpan', cancelButtonText: 'Batal' });
            if (!konfirmGeneric.isConfirmed) return;
            const btn = document.getElementById('btnSubmitDataForm'); btn.innerHTML = '<i class="fas fa-spinner fa-spin">
</i> Menyimpan...'; btn.disabled = true;
            try {
                let p = {}; let cA = aT === 'add' ? 'insertData' : 'updateData'; let tT = tp;
                if(tp === 'siswa') { 
                    p = { 
                        nama: document.getElementById('fmSiswaNama').value.trim(),
                        nisn: document.getElementById('fmSiswaNisn').value.trim(),
                        nik: document.getElementById('fmSiswaNik').value.trim(),
                        kelas: document.getElementById('fmSiswaKelas').value,
                        tgl_lahir: document.getElementById('fmSiswaTgl').value.trim(),
                        no_telp: document.getElementById('fmSiswaWa').value.trim(),
                        status: document.getElementById('fmSiswaStatus').value,
                    };
                    tT = 'siswa';
                }
                else if(tp === 'poin') { p = { total_poin: parseInt(document.getElementById('fmPoinBaru').value)||0 }; tT = 'siswa'; }
                else if(tp === 'buku_saku') { p = { kelakuan: document.getElementById('fmBukuKelakuan').value, kategori: document.getElementById('fmBukuKategori').value, point: parseInt(document.getElementById('fmBukuPoin').value)||0 }; tT = 'master_pelanggaran'; }
                else if(tp === 'semester') {
                    const ta    = document.getElementById('fmSemTA').value.trim();
                    const smt   = document.getElementById('fmSemSmt').value;
                    const aktif = document.getElementById('fmSemAktif').checked;
                    if (!ta) { Swal.fire('Perhatian','Tahun ajaran wajib diisi','warning'); btn.innerHTML='<i class="fas fa-save">
</i> Simpan Data'; btn.disabled=false; return; }
                    const tMulai = document.getElementById('fmSemMulai')?.value || null, tAkhir = document.getElementById('fmSemAkhir')?.value || null;
                    if (tMulai && tAkhir && tAkhir < tMulai) { Swal.fire('Perhatian','Tanggal akhir tidak boleh sebelum tanggal awal','warning'); btn.innerHTML='<i class="fas fa-save">
</i> Simpan Data'; btn.disabled=false; return; }
                    p = { tahun_ajaran: ta, semester: smt, label: ta+' '+smt, aktif, tanggal_mulai: tMulai, tanggal_akhir: tAkhir };
                    tT = 'master_semester';
                    if (aktif) {
                        await callBackend({ type:'supabase_post', action:'updateWhere', payload:{ table:'master_semester', where:'aktif=eq.true', payload:{ aktif:false } }});
                        await callBackend({ type:'supabase_post', action:'updatePengaturan', payload:[{ nama_pengaturan:'semester_aktif', nilai: ta+' '+smt }] });
                    }
                }
                else if(tp === 'mapel') {
                    p = {
                        nama_mapel: document.getElementById('fmMapelNama').value.trim(),
                        kode_mapel: document.getElementById('fmMapelKode').value.trim().toUpperCase(),
                        aktif:      document.getElementById('fmMapelAktif').checked,
                        max_guru: (document.getElementById('fmMapelMax').value.trim() ? parseInt(document.getElementById('fmMapelMax').value,10) : null)
                    };
                    if (!p.nama_mapel) { Swal.fire('Perhatian','Nama mapel wajib diisi','warning'); btn.innerHTML='<i class="fas fa-save">
</i> Simpan Data'; btn.disabled=false; return; }
                    tT = 'master_mapel';
                }
                else if(tp === 'anggota_elpoint') { p = { nama_anggota: document.getElementById('fmElpointNama').value, username: document.getElementById('fmElpointUser').value, role: document.getElementById('fmElpointRole').value }; { const _pw = (document.getElementById('fmElpointPass')?.value || '').trim(); if (_pw) p.password = _pw; } }
                else if(tp === 'apikey') { p = { nama_aplikasi: document.getElementById('fmApiNama').value, api_key : '{{ env('API_KEY') }}' + Math.random().toString(36).substr(2, 12).toUpperCase() }; tT = 'api_keys'; }
                else if(tp === 'guru') {
                    const pass = document.getElementById('fmGuruPass')?.value?.trim();
                    p = {
                        nama_lengkap:   document.getElementById('fmGuruNama').value.trim(),
                        nama_panggilan: document.getElementById('fmGuruPanggilan').value.trim(),
                        nip:            document.getElementById('fmGuruNip').value.trim(),
                        no_wa:          document.getElementById('fmGuruWa').value.trim(),
                        status:         document.getElementById('fmGuruStatus').value,
                        mapel:           JSON.stringify(window._guruMapelSel || []),
                        kelas_diajarkan: JSON.stringify(window._guruKelasSel || [])
                    };
                    if (pass) p.plain_password = pass;
                    tT = 'master_guru';
                    // Gunakan aT (add/edit) bukan cA yang sudah berubah
                    const isAddGuru = (aT === 'add');
                    const guruReq = isAddGuru
                        ? { type:'supabase_post', action:'insertGuru', payload:{ payload: p } }
                        : { type:'supabase_post', action:'updateGuru', payload:{ id: rI, payload: p } };
                    await callBackend(guruReq);
                    Swal.fire({ toast:true, position:'top-end', icon:'success', title: isAddGuru ? 'Data guru ditambahkan!' : 'Data guru diperbarui!', showConfirmButton:false, timer:2000 });
                    document.getElementById('modalDataForm').classList.add('hidden');
                    loadDataTable('master_guru','guru');
                    return;
                }
                else if(tp === 'admin') {
                    p = {
                        email_admin:  document.getElementById('fmAdminEmail').value.trim(),
                        nama_lengkap: document.getElementById('fmAdminNama').value.trim(),
                        no_telp:      document.getElementById('fmAdminTelp').value.trim(),
                        role_name:    document.getElementById('fmAdminRole').value,
                        agama:        document.getElementById('fmAdminAgama').value
                    };
                    // Gunakan aT (add/edit) bukan cA yang mungkin sudah berubah
                    const isAddAdmin = (aT === 'add');
                    if (isAddAdmin) {
                        const pass = document.getElementById('fmAdminPass')?.value?.trim();
                        if (!pass || pass.length < 8) {
                            Swal.fire('Perhatian', 'Password minimal 8 karakter!', 'warning');
                            btn.innerHTML = '<i class="fas fa-save">
</i> Simpan Data'; btn.disabled = false;
                            return;
                        }
                        p.password = pass;
                    }
                    tT = 'hak_akses_admin';
                    const adminReqObj = isAddAdmin
                        ? { type: 'supabase_post', action: 'insertAdmin', payload: { payload: p } }
                        : { type: 'supabase_post', action: 'updateData', payload: { table: 'hak_akses_admin', id: rI, payload: p } };
                    await callBackend(adminReqObj);
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: isAddAdmin ? 'Admin baru berhasil ditambahkan!' : 'Data admin diperbarui!', showConfirmButton: false, timer: 2000 });
                    document.getElementById('modalDataForm').classList.add('hidden');
                    loadDataTable('hak_akses_admin', 'admin');
                    return;
                }
                const reqObj = { type: 'supabase_post', action: cA, payload: { table: tT, payload: p } }; if(cA === 'updateData') reqObj.payload.id = rI;
                await callBackend(reqObj); Swal.fire({toast: true, position: 'top-end', icon: 'success', title: 'Berhasil', showConfirmButton: false, timer: 1500}); 
                document.getElementById('modalDataForm').classList.add('hidden'); loadDataTable(tT, tp);
            } catch(err) { Swal.fire('Error', err.message, 'error'); } finally { btn.innerHTML = '<i class="fas fa-save">
</i> Simpan Data'; btn.disabled = false; }
        });

        function lihatDetailAbsensi(idSiswa) {
            const siswaData = (window._absensiAggMap || {})[idSiswa]; if(!siswaData) return;
            document.getElementById('absensiDetailNama').textContent = `${siswaData.nama} - ${siswaData.kelas}`;
            const cont = document.getElementById('absensiDetailContent'); cont.innerHTML = '';
            const sortedHist = siswaData.history.sort((a,b) => new Date(b.created_at) - new Date(a.created_at));
            sortedHist.forEach(h => {
                let badge = '';
                if(h.jenis === 'Hadir' && h.is_late) badge = '<span class="text-red-600 bg-red-50 border border-red-200 px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest">
<i class="fas fa-clock mr-1">
</i> Terlambat</span>';
                else if(h.jenis === 'Hadir') badge = '<span class="text-green-600 bg-green-50 border border-green-200 px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest">
<i class="fas fa-check mr-1">
</i> Hadir Tepat</span>';
                else if(h.jenis === 'Sakit') badge = '<span class="text-blue-600 bg-blue-50 border border-blue-200 px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest">Sakit (S)</span>';
                else if(h.jenis === 'Izin') badge = '<span class="text-yellow-600 bg-yellow-50 border border-yellow-200 px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest">Izin (I)</span>';
                else badge = '<span class="text-red-700 bg-red-100 border border-red-300 px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest">Alpa (A)</span>';
                                const _adaLamp = !!(h.lampiran && h.lampiran.trim());
                const _isSurat = (h.jenis === 'Sakit' || h.jenis === 'Izin');
                const _capFoto = (_isSurat ? 'Surat ' + h.jenis : 'Selfie Hadir') + ' — ' + formatTgl(h.created_at);
                const fotoHTML = _adaLamp
                    ? `<button onclick="bukaFotoLightbox('${h.lampiran}', '${_capFoto}')" class="ml-2 flex-shrink-0 group relative" title="${_isSurat ? 'Lihat surat' : 'Lihat foto selfie'}">
<img src="${h.lampiran}" class="w-12 h-12 rounded-xl object-cover border-2 ${_isSurat ? 'border-amber-300 group-hover:border-amber-500' : 'border-blue-100 group-hover:border-blue-500'} transition shadow-sm" onerror="this.parentNode.remove()">
<span class="absolute -bottom-1 -right-1 text-[8px] font-black px-1.5 py-0.5 rounded-full text-white ${_isSurat ? 'bg-amber-500' : 'bg-purple-500'}">${_isSurat ? 'SURAT' : 'SELFIE'}</span>
</button>`
                    : (_isSurat ? '<span class="ml-2 text-[9px] font-black text-red-400 uppercase">Tanpa surat</span>' : '');
                cont.innerHTML += `<div class="flex justify-between items-center p-4 border-b bg-white hover:bg-blue-50/30 transition">
<div class="flex-1 min-w-0">
    <p class="text-xs font-black text-gray-800">${formatTgl(h.created_at)}</p>
    <p class="text-[10px] text-gray-500 font-bold mt-0.5">
        <i class="fas fa-user-check text-blue-400 mr-1">
        </i> Petugas: ${h.aktor || 'Sistem'}</p>
    </div>
    <div class="flex items-center gap-2">${badge}${fotoHTML}</div>
</div>`;
            });
            document.getElementById('modalAbsensiDetail').classList.remove('hidden');
        }


        // Lightbox foto selfie absensi
        function bukaFotoLightbox(url, caption = '') {
            return Swal.fire({
                html: `<div class="text-center">
<img src="${url}" class="max-w-full max-h-[70vh] rounded-2xl shadow-2xl object-contain mx-auto" onerror="this.src=''; this.parentNode.innerHTML='<p class=\'text-red-400\'>Foto tidak bisa dimuat</p>'">
                    ${caption ? `<p class="text-white text-xs font-bold mt-3">${caption}</p>` : ''}
                    <div class="mt-3 flex items-center justify-center gap-2">
<a href="${url}" target="_blank" class="text-xs font-bold text-blue-500 hover:text-blue-700 flex items-center gap-1">
    <i class="fas fa-external-link-alt">
    </i> Buka di Tab Baru</a>
</div>
</div>`,
                showConfirmButton: false,
                showCloseButton: true,
                background: '#0f172a',
                width: 'auto',
                padding: '1rem'
            });
        }

        // Galeri foto selfie absensi (semua foto dari DB absensi)
        let _galeriData = [], _galeriTampil = [], _galeriFilter = 'semua';
        const _escH = v => String(v == null ? '' : v).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
        const _jenisFoto = d => (d.jenis === 'Sakit' || d.jenis === 'Izin') ? 'surat' : 'selfie';

        async function bukaGaleriFotoAbsensi() {
            Swal.fire({ title: 'Memuat galeri...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });
            try {
                const res = await callBackend({ type:'supabase_get', action:'getData', payload:{ table:'absensi?select=nama_siswa,kelas,jenis,is_late,lampiran,created_at&lampiran=neq.&order=created_at.desc&limit=300' }});
                _galeriData = (res.data || []).filter(d => d.lampiran && d.lampiran.trim());
                if (!_galeriData.length) {
                    Swal.fire({ icon:'info', title:'Belum Ada Foto', text:'Belum ada foto selfie / surat yang diunggah.', confirmButtonColor:'#2563eb' });
                    return;
                }
                _galeriFilter = 'semua';
                tampilGaleri();
            } catch(e) {
                Swal.fire('Gagal', 'Tidak dapat memuat galeri foto: ' + e.message, 'error');
            }
        }

        function _htmlGaleri() {
            const n = { semua: _galeriData.length, selfie: _galeriData.filter(d => _jenisFoto(d) === 'selfie').length, surat: _galeriData.filter(d => _jenisFoto(d) === 'surat').length };
            _galeriTampil = _galeriData.filter(d => _galeriFilter === 'semua' || _jenisFoto(d) === _galeriFilter);
            const tab = (k, label) => `<button onclick="gantiFilterGaleri('${k}')" class="px-3 py-1.5 rounded-xl text-xs font-black border-2 transition ${_galeriFilter === k ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-200 text-gray-600 hover:border-blue-400'}">${label} (${n[k]})</button>`;
            const grid = _galeriTampil.map((d, i) => {
                const surat = _jenisFoto(d) === 'surat';
                const jam = d.created_at ? new Date(d.created_at).toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' }) : '';
                const tgl = d.created_at ? new Date(d.created_at).toLocaleDateString('id-ID') : '';
                const badge = surat
                    ? `<span class="absolute top-1.5 left-1.5 bg-amber-500 text-white text-[8px] font-black px-1.5 py-0.5 rounded-full">SURAT ${_escH(String(d.jenis).toUpperCase())}</span>`
                    : (d.is_late ? '<span class="absolute top-1.5 right-1.5 bg-orange-500 text-white text-[8px] font-black px-1.5 py-0.5 rounded-full">TERLAMBAT</span>' : '');
                return `<div class="relative cursor-pointer group" onclick="bukaFotoGaleri(${i})">
<div class="overflow-hidden rounded-xl border-2 ${surat ? 'border-amber-200 group-hover:border-amber-400' : 'border-gray-200 group-hover:border-blue-400'} transition">
    <img src="${_escH(d.lampiran)}" class="w-full h-28 object-cover group-hover:scale-105 transition duration-300" onerror="this.parentNode.parentNode.remove()">
</div>
                    ${badge}
                    <p class="text-[10px] font-bold text-gray-700 mt-1 truncate">${_escH(d.nama_siswa || '-')}</p>
<p class="text-[9px] text-gray-400">${_escH(d.kelas || '')} · ${tgl} ${jam}</p>
</div>`;
            }).join('');
            return `<div class="text-left">
<div class="flex gap-2 mb-3 flex-wrap">${tab('semua','Semua')}${tab('selfie','Selfie Hadir')}${tab('surat','Surat Sakit/Izin')}</div>
<div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-[60vh] overflow-y-auto pr-1">${grid || '<p class="col-span-full text-center text-gray-400 text-sm py-8">Tidak ada foto di kategori ini.</p>'}</div>
</div>`;
        }

        function tampilGaleri() {
            Swal.fire({ title: '<i class="fas fa-images text-blue-500 mr-2">
</i>Galeri Foto Absensi', html: _htmlGaleri(), width: '700px', showConfirmButton: false, showCloseButton: true });
        }
        function gantiFilterGaleri(f) {
            _galeriFilter = f;
            const c = Swal.getHtmlContainer(); if (c) c.innerHTML = _htmlGaleri();
        }
        function bukaFotoGaleri(i) {
            const d = _galeriTampil[i]; if (!d) return;
            const cap = _escH(d.nama_siswa || '') + ' — ' + (_jenisFoto(d) === 'surat' ? 'Surat ' + _escH(d.jenis) : 'Selfie Hadir');
            bukaFotoLightbox(d.lampiran, cap).then(() => tampilGaleri()); // setelah ditutup, kembali ke galeri
        }

        async function bulkResetAbsensi() {
            const ids = getCheckedIds();
            if (!ids.length) return;

            // ids di absensi menggunakan nama atau id siswa
            const targetSiswa = ids.map(k => (window._absensiAggMap || {})[k]).filter(Boolean);

            if (!targetSiswa.length) return Swal.fire('Kosong', 'Data siswa tidak ditemukan.', 'warning');

            const namaSiswa = targetSiswa.map(s => s.nama).join(', ');
            const konfirm = await Swal.fire({
                title: `Reset Absensi ${targetSiswa.length} Siswa?`,
                html: `<div class="text-left text-sm text-gray-600 space-y-2">
<p>Seluruh data absensi siswa berikut akan <b>dihapus</b>:</p>
<div class="bg-orange-50 border border-orange-200 rounded-xl p-3 text-xs">
                        ${targetSiswa.map(s => `<p>• ${s.nama} <span class="text-gray-400">(${s.kelas})</span>
</p>`).join('')}
                    </div>
<p class="text-orange-600 text-xs font-bold">⚠️ Rekap kehadiran akan kembali ke 0. Tindakan ini tidak bisa dibatalkan.</p>
</div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                confirmButtonText: `Reset ${targetSiswa.length} Siswa`,
                cancelButtonText: 'Batal'
            });
            if (!konfirm.isConfirmed) return;

            Swal.fire({ title: 'Mereset absensi...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });

            let berhasil = 0;
            let gagal = 0;
            try {
                for (const siswa of targetSiswa) {
                    // Hapus semua record absensi milik siswa ini
                    const res = await callBackend({
                        type: 'supabase_post',
                        action: 'deleteData',
                        payload: { table: 'absensi', id: siswa.id, useField: 'id_siswa' }
                    });
                    if (res && !res.error) berhasil++;
                    else gagal++;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Reset Berhasil!',
                    html: `<p>${berhasil} siswa berhasil direset.</p>${gagal > 0 ? `<p class="text-red-500 text-xs mt-1">${gagal} gagal direset.</p>` : ''}`,
                    confirmButtonColor: '#2563eb'
                });

                // Reload tabel absensi
                const currentFilter = document.getElementById('absensiFilterKelas')?.value || '';
                loadDataTable('absensi', 'absensi');

            } catch(e) {
                Swal.fire('Gagal', e.message, 'error');
            }
        }

                async function exportToExcel(viewId) {
            const data = globalDataStore[viewId]; if(!data || data.length === 0) return Swal.fire('Kosong', 'Tidak ada data.', 'info');
            if (viewId === 'guru') {
                const exportData = data.map(d => {
                    const k = (() => { try { return JSON.parse(d.kelas_diajarkan||'[]'); } catch(e){return[];} })();
                    const m2 = (() => { try { return JSON.parse(d.mapel||'[]'); } catch(e){return[];} })();
                    return { 'Nama Lengkap':d.nama_lengkap||'', 'Nama Panggilan':d.nama_panggilan||'', 'NIP':d.nip||'', 'No WA':d.no_wa||'', 'Mata Pelajaran':m2.join(', '), 'Kelas Diajarkan':k.join(', '), 'Status':d.status||'Aktif' };
                });
                const ws = XLSX.utils.json_to_sheet(exportData); ws['!cols']=[{wch:25},{wch:20},{wch:20},{wch:15},{wch:30},{wch:40},{wch:10}];
                const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb,ws,'Master Guru'); XLSX.writeFile(wb,'Master_Guru.xlsx'); return;
            }
            if (viewId === 'lapguru') {
                const exportData = data.map(d => ({ 'Nama Guru':d.nama_guru||'', 'Nama Siswa':d.nama_siswa||'', 'Kelas':d.kelas_siswa||'', 'Mata Pelajaran':d.mata_pelajaran||'', 'Tanggal':d.tanggal||'', 'Jam':d.jam||'', 'Jenis':d.label_jenis||d.jenis||'', 'Poin':d.poin_siswa||0, 'Keterangan':d.keterangan||'' }));
                const ws = XLSX.utils.json_to_sheet(exportData); ws['!cols']=[{wch:25},{wch:25},{wch:15},{wch:20},{wch:12},{wch:8},{wch:30},{wch:6},{wch:40}];
                const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb,ws,'Laporan Guru'); XLSX.writeFile(wb,'Laporan_Guru.xlsx'); return;
            }
            if (viewId === 'poin') {
                const res = await Swal.fire({ title: 'Format Ekspor Poin', icon: 'question', showDenyButton: true, showCancelButton: true, confirmButtonText: 'Lengkap (Sanksi)', denyButtonText: 'Data Dasar' });
                if (res.isDismissed) return; Swal.fire({title: 'Menyiapkan...', didOpen: () => Swal.showLoading()});
                let exportData = [];
                if (res.isConfirmed) {
                    exportData = data.map(d => { let point = parseInt(d.total_poin) || 0; let sanksiInfo = getSanksiTextAdmin(point); let kat = point > 50 ? "Berat" : (point > 20 ? "Sedang" : (point > 0 ? "Ringan" : "-")); return { "Nama Lengkap": d.nama, "NISN": d.nisn, "Kelas": d.kelas, "Total Poin": point, "Kategori Pelanggaran": kat, "Tindak Lanjut": point > 0 ? sanksiInfo.t : "-", "Sanksi / Kompensasi": point > 0 ? sanksiInfo.k : "-" }; });
                } else if (res.isDenied) { exportData = data.map(d => ({ "Nama Siswa": d.nama, "Kelas": d.kelas, "Total Poin": parseInt(d.total_poin) || 0 })); }
                const ws = XLSX.utils.json_to_sheet(exportData); const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, "Data"); XLSX.writeFile(wb, `Laporan_Poin.xlsx`); Swal.close(); return;
            }
            if (viewId === 'absensi') {
                Swal.fire({toast: true, position: 'top-end', icon: 'info', title: 'Export Absensi...', showConfirmButton: false, timer: 1500});
                let exportData = Object.values(window._absensiAggMap || {}).map(d => ({
                    "Nama Siswa"           : d.nama,
                    "NISN"                 : d.nisn,
                    "Kelas"                : d.kelas,
                    "Hadir (Total)"         : d.h || 0,
                    "Tepat Waktu"           : d.tepat_waktu !== undefined ? d.tepat_waktu : (d.h || 0),
                    "Terlambat"             : d.terlambat || 0,
                    "Sakit"                 : d.s || 0,
                    "Izin"                  : d.i || 0,
                    "Alpa"                  : d.a || 0,
                    "Status Hadir"          : (d.terlambat || 0) > 0 ? `${d.tepat_waktu !== undefined ? d.tepat_waktu : d.h} Tepat Waktu, ${d.terlambat} Terlambat` : 'Semua Tepat Waktu',
                    "Jumlah Terlambat"     : d.terlambat || 0
                }));
                const ws = XLSX.utils.json_to_sheet(exportData);
                // Style header row - bold
                const range = XLSX.utils.decode_range(ws['!ref']);
                for (let C = range.s.c; C <= range.e.c; C++) {
                    const cell = ws[XLSX.utils.encode_cell({r:0, c:C})];
                    if (cell) cell.s = { font: { bold: true }, fill: { fgColor: { rgb: 'DBEAFE' } } };
                }
                // Set column widths
                ws['!cols'] = [
                    {wch:25},{wch:15},{wch:15},{wch:12},{wch:14},{wch:12},
                    {wch:10},{wch:10},{wch:10},{wch:30},{wch:18}
                ];
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Rekap Absensi');
                const tgl = new Date().toLocaleDateString('id-ID').replace(/\//g,'-');
                XLSX.writeFile(wb, `Rekap_Absensi_${tgl}.xlsx`);
                return;
            }
            Swal.fire({toast: true, position: 'top-end', icon: 'info', title: 'Membuat Excel...', showConfirmButton: false, timer: 1500});
            const ws = XLSX.utils.json_to_sheet(data.map(d => { let c={...d}; delete c.id; delete c.lampiran; return c; })); const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, "Data"); XLSX.writeFile(wb, `Export_${viewId}.xlsx`);
        }

        function downloadTemplate() {
            const t = document.getElementById('importTypeVal').value; let h = []; let f = "Template.xlsx";
            if(t==='siswa'){ h=['Nama','NISN','NIK','Kelas','Tgl Lahir','WA','Status','Poin']; f="Template_Siswa.xlsx"; }
            else if(t==='poin'){ h=['Nama Siswa','Kelas','Poin Tambahan']; f="Template_Poin.xlsx"; }
            else if(t==='buku_saku'){ h=['Deskripsi','Kategori','Poin']; f="Template_BukuSaku.xlsx"; }
            else if(t==='anggota_elpoint'){ h=['Nama','Username','Password','Role']; f="Template_ElPoint.xlsx"; }
            else if(t==='kelas'){ h=['Nama Kelas']; f="Template_Kelas.xlsx"; }
            else if(t==='role'){ h=['Nama Jabatan']; f="Template_Jabatan.xlsx"; }
            else if(t==='guru'){ h=['Nama Lengkap','Nama Panggilan','NIP','No WA','Password','Mata Pelajaran 1','Mata Pelajaran 2','Mata Pelajaran 3','Kelas 1','Kelas 2','Kelas 3','Status']; f="Template_Guru.xlsx"; }
            const ws = XLSX.utils.aoa_to_sheet([h]); const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, "Format"); XLSX.writeFile(wb, f);
        }

        function bukaModalImport(type) { 
            document.getElementById('importTypeVal').value = type; let ti="Impor Excel"; let de="";
            if(type==='buku_saku'){ti="Impor Buku Saku";de="Kolom: Deskripsi | Kategori | Poin";}
            if(type==='poin'){ti="Impor Poin Pelanggaran";de="Poin yang diupload akan DITAMBAHKAN (+) ke poin siswa yang sudah ada.";}
            if(type==='kelas'){ti="Impor Data Kelas";de="Kolom: Nama Kelas";}
            if(type==='role'){ti="Impor Jabatan (Role)";de="Kolom: Nama Jabatan";}
            document.getElementById('importTitle').textContent = ti; document.getElementById('importDesc').textContent = de;
            document.getElementById('modalImport').classList.remove('hidden'); 
        }
        
        // Helper: cek duplikat dan tanya replace/batal
        async function cekDanImport({ type, newData, existing, keyFn, labelFn, doInsert, doReplace, doFinish }) {
            const duplikat = newData.filter(d => existing.find(e => keyFn(e) === keyFn(d)));
            const baru     = newData.filter(d => !existing.find(e => keyFn(e) === keyFn(d)));

            if (duplikat.length === 0) {
                // Tidak ada duplikat — langsung insert
                if (baru.length > 0) await doInsert(baru);
                await doFinish(baru.length, 0, 0);
                return;
            }

            // Ada duplikat — tanya dulu
            const listDup = duplikat.slice(0,5).map(d => `<li class="text-left text-xs">• ${labelFn(d)}</li>`).join('');
            const more    = duplikat.length > 5 ? `<li class="text-gray-400 text-xs">...dan ${duplikat.length-5} lainnya</li>` : '';
            const result  = await Swal.fire({
                title: `${duplikat.length} Data Duplikat Ditemukan`,
                html: `<p class="text-sm text-gray-600 mb-3">Data berikut sudah ada di database:</p>
<ul class="bg-gray-50 rounded-xl p-3 mb-3 text-gray-700 space-y-1">${listDup}${more}</ul>
<p class="text-xs text-gray-500">Pilih tindakan untuk data duplikat:</p>`,
                icon: 'warning',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '<i class="fas fa-sync-alt mr-1">
</i> Timpa (Replace)',
                denyButtonText: '<i class="fas fa-plus mr-1">
</i> Lewati Duplikat',
                cancelButtonText: 'Batal Semua',
                confirmButtonColor: '#d97706',
                denyButtonColor: '#2563eb',
            });

            if (result.isDismissed) { Swal.fire({toast:true,position:'top-end',icon:'info',title:'Import dibatalkan',showConfirmButton:false,timer:2000}); return; }

            let inserted = 0, replaced = 0;
            if (baru.length > 0) { await doInsert(baru); inserted = baru.length; }
            if (result.isConfirmed && duplikat.length > 0) {
                // Timpa semua duplikat
                await doReplace(duplikat);
                replaced = duplikat.length;
            } else if (result.isDenied) {
                // Skip duplikat — hanya insert yang baru
                replaced = 0;
            }
            await doFinish(inserted, replaced, duplikat.length - replaced);
        }

        function prosesImportExcel() {
            const fI = document.getElementById('fileImportExcel'); const type = document.getElementById('importTypeVal').value; if(!fI.files[0]) return;
            const reader = new FileReader();
            reader.onload = async function(e) {
                try {
                    Swal.fire({title: 'Membaca file Excel...', didOpen: () => Swal.showLoading()});
                    const wb = XLSX.read(new Uint8Array(e.target.result), {type: 'array'}); const js = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]]);
                    Swal.close();

                    if (type === 'poin') {
                        // Poin: tidak ada duplikat konsep (tambah poin, bukan replace)
                        Swal.fire({title: 'Memasukkan Poin...', didOpen: () => Swal.showLoading()});
                        let cu = 0;
                        for(let r of js) {
                            const n = String(r.Nama||r['Nama Siswa']||'').toLowerCase().trim(); const k = String(r.Kelas||'').toUpperCase().trim(); const pA = parseInt(r.Poin||r['Poin Tambahan']||0);
                            if(!n || !k || isNaN(pA) || pA===0) continue;
                            const s = globalDataStore['siswa']?.find(x => x.nama.toLowerCase().includes(n) && x.kelas === k);
                            if(s) { await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'siswa', id: s.id, payload: { total_poin: (parseInt(s.total_poin)||0) + pA } } }); cu++; }
                        }
                        Swal.fire('Sukses', `${cu} data poin diperbarui (+).`, 'success'); loadDataTable('siswa', 'poin');
                    }
                    else if (type === 'buku_saku') {
                        const newData = js.map(i => ({ kelakuan: String(i.Deskripsi||i.Pelanggaran||i.Kelakuan||'').trim(), kategori: String(i.Kategori||'Kedisiplinan').trim(), point: parseInt(i.Poin||i.Bobot||i.Point||0) })).filter(i => i.kelakuan && i.point > 0);
                        if (!newData.length) return Swal.fire('Kosong', 'Tidak ada data valid di file Excel.', 'warning');
                        const existing = globalDataStore['buku_saku'] || [];
                        await cekDanImport({
                            type, newData, existing,
                            keyFn: d => d.kelakuan.toLowerCase(),
                            labelFn: d => `${d.kelakuan} (${d.point} pts)`,
                            doInsert: async (data) => {
                                await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'master_pelanggaran', payload: data } });
                            },
                            doReplace: async (data) => {
                                for (const d of data) {
                                    const ex = existing.find(e => e.kelakuan.toLowerCase() === d.kelakuan.toLowerCase());
                                    if (ex) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'master_pelanggaran', id: ex.id, payload: { kelakuan: d.kelakuan, kategori: d.kategori, point: d.point } } });
                                }
                            },
                            doFinish: async (ins, rep, skip) => {
                                Swal.fire('Selesai', `✅ ${ins} ditambahkan · 🔄 ${rep} ditimpa · ⏭️ ${skip} dilewati`, 'success');
                                loadDataTable('master_pelanggaran', 'buku_saku');
                            }
                        });
                    }
                    else if (type === 'siswa') {
                        const newData = js.map(i => ({ nama: String(i.Nama||i['Nama Lengkap']||i['Nama Siswa']||'').trim(), nisn: String(i.NISN||'').trim(), nik: String(i.NIK||'').trim(), kelas: String(i.Kelas||'').trim(), tgl_lahir: String(i['Tgl Lahir']||i.TglLahir||'').trim(), no_telp: String(i.WA||i.Telepon||'').trim(), status: String(i.Status||'Aktif'), total_poin: parseInt(i.Poin||0) })).filter(i => i.nama && i.kelas);
                        if (!newData.length) return Swal.fire('Kosong', 'Tidak ada data valid di file Excel.', 'warning');
                        // Muat data siswa terbaru dulu
                        Swal.fire({title:'Memeriksa duplikat...', didOpen: () => Swal.showLoading()});
                        const resS = await callBackend({ type: 'supabase_get', action: 'getAllSiswa' });
                        const existing = resS.data || [];
                        Swal.close();
                        await cekDanImport({
                            type, newData, existing,
                            keyFn: d => (d.nama||'').toLowerCase().trim(),
                            labelFn: d => `${d.nama} — ${d.kelas}`,
                            doInsert: async (data) => {
                                await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'siswa', payload: data } });
                            },
                            doReplace: async (data) => {
                                for (const d of data) {
                                    const ex = existing.find(e => e.nama.toLowerCase().trim() === d.nama.toLowerCase().trim());
                                    if (ex) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'siswa', id: ex.id, payload: d } });
                                }
                            },
                            doFinish: async (ins, rep, skip) => {
                                Swal.fire('Selesai', `✅ ${ins} ditambahkan · 🔄 ${rep} ditimpa · ⏭️ ${skip} dilewati`, 'success');
                                loadDataTable('siswa', 'siswa');
                            }
                        });
                    }
                    else if (type === 'anggota_elpoint') {
                        const newData = js.map(i => ({ nama_anggota: String(i.Nama||i['Nama Anggota']||'').trim(), username: String(i.Username||'').trim(), password: String(i.Password||'').trim(), role: String(i.Role||'OSIS').trim() })).filter(i => i.nama_anggota && i.username);
                        if (!newData.length) return Swal.fire('Kosong', 'Tidak ada data valid.', 'warning');
                        Swal.fire({title:'Memeriksa duplikat...', didOpen: () => Swal.showLoading()});
                        const resE = await callBackend({ type: 'supabase_get', action: 'getAllElpoint' });
                        const existing = resE.data || [];
                        Swal.close();
                        await cekDanImport({
                            type, newData, existing,
                            keyFn: d => (d.username||'').toLowerCase(),
                            labelFn: d => `${d.nama_anggota} (${d.username})`,
                            doInsert: async (data) => {
                                await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'anggota_elpoint', payload: data } });
                            },
                            doReplace: async (data) => {
                                for (const d of data) {
                                    const ex = existing.find(e => e.username.toLowerCase() === d.username.toLowerCase());
                                    if (ex) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'anggota_elpoint', id: ex.id, payload: d } });
                                }
                            },
                            doFinish: async (ins, rep, skip) => {
                                Swal.fire('Selesai', `✅ ${ins} ditambahkan · 🔄 ${rep} ditimpa · ⏭️ ${skip} dilewati`, 'success');
                                loadDataTable('anggota_elpoint', 'anggota_elpoint');
                            }
                        });
                    }
                    else if (type === 'guru') {
                        const newData = js.map(i => {
                            const mapel = [i['Mata Pelajaran 1']||'', i['Mata Pelajaran 2']||'', i['Mata Pelajaran 3']||''].filter(Boolean);
                            const kelas = [i['Kelas 1']||'', i['Kelas 2']||'', i['Kelas 3']||''].filter(Boolean);
                            return {
                                nama_lengkap:    String(i['Nama Lengkap']||'').trim(),
                                nama_panggilan:  String(i['Nama Panggilan']||'').trim(),
                                nip:             String(i.NIP||'').trim(),
                                no_wa:           String(i['No WA']||'').trim(),
                                plain_password:  String(i.Password||'').trim(),
                                mapel:           JSON.stringify(mapel),
                                kelas_diajarkan: JSON.stringify(kelas),
                                status:          String(i.Status||'Aktif').trim()
                            };
                        }).filter(i => i.nama_lengkap);
                        if (!newData.length) return Swal.fire('Kosong', 'Tidak ada data valid di file Excel.', 'warning');
                        Swal.fire({title:'Mengimpor data guru...', html:'<p id="importProg">Menyiapkan...</p>', didOpen: () => Swal.showLoading()});
                        let berhasil = 0;
                        for (const g of newData) {
                            const progEl = document.getElementById('importProg');
                            if (progEl) progEl.textContent = `${berhasil}/${newData.length} — ${g.nama_lengkap}`;
                            await callBackend({ type:'supabase_post', action:'insertGuru', payload:{ payload: g }});
                            berhasil++;
                        }
                        Swal.fire('Selesai', `✅ ${berhasil} data guru berhasil diimpor.`, 'success');
                        loadDataTable('master_guru', 'guru');
                    }
                    else if (type === 'kelas') {
                        const newData = js.map(i => ({ nama_kelas: String(i['Nama Kelas']||i.Kelas||'').toUpperCase().trim() })).filter(i => i.nama_kelas);
                        if (!newData.length) return Swal.fire('Kosong', 'Tidak ada data valid.', 'warning');
                        Swal.fire({title:'Memeriksa duplikat...', didOpen: () => Swal.showLoading()});
                        const resK = await callBackend({ type: 'supabase_get', action: 'getData', payload: { table: 'daftar_kelas?select=*' } });
                        const existing = resK.data || [];
                        Swal.close();
                        await cekDanImport({
                            type, newData, existing,
                            keyFn: d => (d.nama_kelas||'').toUpperCase(),
                            labelFn: d => d.nama_kelas,
                            doInsert: async (data) => {
                                await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'daftar_kelas', payload: data } });
                            },
                            doReplace: async (data) => {
                                // Kelas tidak perlu di-replace (nama saja), skip
                            },
                            doFinish: async (ins, rep, skip) => {
                                Swal.fire('Selesai', `✅ ${ins} kelas ditambahkan · ⏭️ ${skip} sudah ada`, 'success');
                                muatDataKelas();
                            }
                        });
                    }
                    else if (type === 'role') {
                        const newData = js.map(i => ({ nama_role: String(i['Nama Jabatan']||i.Role||'').trim(), akses_fitur: '[]' })).filter(i => i.nama_role);
                        if (!newData.length) return Swal.fire('Kosong', 'Tidak ada data valid.', 'warning');
                        Swal.fire({title:'Memeriksa duplikat...', didOpen: () => Swal.showLoading()});
                        const resR = await callBackend({ type: 'supabase_get', action: 'getData', payload: { table: 'daftar_role?select=*' } });
                        const existing = resR.data || [];
                        Swal.close();
                        await cekDanImport({
                            type, newData, existing,
                            keyFn: d => (d.nama_role||'').toLowerCase(),
                            labelFn: d => d.nama_role,
                            doInsert: async (data) => {
                                await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'daftar_role', payload: data } });
                            },
                            doReplace: async () => {},
                            doFinish: async (ins, rep, skip) => {
                                Swal.fire('Selesai', `✅ ${ins} jabatan ditambahkan · ⏭️ ${skip} sudah ada`, 'success');
                                muatDataRole();
                            }
                        });
                    }
                    else { Swal.close(); }
                    document.getElementById('modalImport').classList.add('hidden');
                } catch(e) { Swal.fire('Error', 'Gagal memproses data: ' + e.message, 'error'); }
            };
            reader.readAsArrayBuffer(fI.files[0]);
        }

        // ── SET SEMESTER AKTIF ────────────────────────────────────────
        async function setSemesterAktif(id, label) {
            const konfirm = await Swal.fire({
                title: 'Aktifkan Semester?',
                html: `Semester <b>${label}</b> akan dijadikan aktif.<br>Semester lain otomatis non-aktif.`,
                icon: 'question', showCancelButton: true,
                confirmButtonText: 'Ya, Aktifkan', cancelButtonText: 'Batal', confirmButtonColor: '#2563eb'
            });
            if (!konfirm.isConfirmed) return;
            try {
                Swal.fire({ title:'Memproses...', didOpen:()=>Swal.showLoading(), allowOutsideClick:false });
                await callBackend({ type:'supabase_post', action:'setSemesterAktif', payload:{ id, label } });
                try {
                    await callBackend({ type:'supabase_post', action:'insertLog', payload:{
                        aktor: (userData && (userData.nama_lengkap || userData.nama_admin)) || 'Admin', role:'Admin',
                        aksi:'Set Semester Aktif', detail:`Semester aktif diubah ke: ${label}`
                    }});
                } catch(e2) {}
                Swal.fire({ toast:true, position:'top-end', icon:'success', title:`Semester ${label} sekarang aktif`, showConfirmButton:false, timer:2500 });
                loadDataTable('master_semester','semester');
            } catch(e) { Swal.fire('Gagal', e.message, 'error'); }
        }

        function bukaDetailLaporanGuru(id) {
            const data = (globalDataStore['lapguru']||[]).find(d => d.id === id);
            if (!data) { console.warn('[Admin] Data laporan guru tidak ditemukan:', id); return; }
            const stCls = data.status === 'Dibatalkan' ? 'bg-gray-100 text-gray-600' :
                          data.status === 'Selesai'    ? 'bg-green-100 text-green-700' :
                          data.status === 'Sedang Diproses' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-600';
            const poinCls = (data.poin_siswa||0) >= 10 ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600';
            document.getElementById('contentDetailLaporanGuru').innerHTML = `
                <div class="grid grid-cols-2 gap-3">
<div class="bg-gray-50 rounded-2xl p-3">
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Guru Pelapor</p>
    <p class="font-black text-gray-800">${data.nama_guru||'-'}</p>
</div>
<div class="bg-gray-50 rounded-2xl p-3">
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Siswa Dilaporkan</p>
    <p class="font-black text-gray-800">${data.nama_siswa||'-'}</p>
    <p class="text-xs text-gray-500">${data.kelas_siswa||''}</p>
</div>
<div class="bg-gray-50 rounded-2xl p-3">
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Mata Pelajaran</p>
    <p class="font-bold text-gray-800">${data.mata_pelajaran||'—'}</p>
</div>
<div class="bg-gray-50 rounded-2xl p-3">
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal & Jam</p>
    <p class="font-bold text-gray-800">${data.tanggal||''} ${data.jam ? '• '+data.jam : ''}</p>
</div>
<div class="bg-gray-50 rounded-2xl p-3">
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Jenis Pelanggaran</p>
    <p class="font-bold text-gray-800">${data.label_jenis||data.jenis||'—'}</p>
</div>
<div class="bg-gray-50 rounded-2xl p-3">
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Poin</p>
    <span class="text-sm font-black px-2 py-1 rounded ${poinCls}">+${data.poin_siswa||0} poin</span>
</div>
</div>
                ${data.keterangan ? `
                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-3">
<p class="text-[10px] font-black text-yellow-600 uppercase tracking-widest mb-1">Keterangan Tambahan</p>
<p class="text-gray-700">${data.keterangan}</p>
</div>` : ''}
                <div class="flex items-center justify-between bg-gray-50 rounded-2xl p-3">
<div>
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status Laporan</p>
    <span class="text-xs font-black px-3 py-1 rounded-full ${stCls}">${data.status||'Terkirim'}</span>
</div>
                    ${data.status === 'Dibatalkan' && data.alasan_batal ? `
                    <div class="text-right">
<p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Alasan Batal</p>
<p class="text-xs text-red-500 font-bold">${data.alasan_batal}</p>
</div>` : ''}
                </div>`;
            document.getElementById('modalDetailLaporanGuru').classList.remove('hidden');
        }

        function bukaDetailAspirasi(id) {
            // Cari dari currentFilteredData dulu, fallback ke globalDataStore semua view aspirasi
            let data = currentFilteredData.find(d => d.id === id);
            if (!data) data = (globalDataStore['laporan']||[]).concat(globalDataStore['masukan']||[], globalDataStore['konselor']||[]).find(d => d.id === id);
            if (!data) { console.warn('[Admin] Data tidak ditemukan untuk id:', id); return; }
            const c = document.getElementById('contentAspirasiDetail'); document.getElementById('updateIdAspirasiVal').value = id; document.getElementById('updateStatusAspirasiVal').value = data.status;
            let lampiranHtml = '';
            if(data.lampiran && data.lampiran.startsWith('http')) { lampiranHtml = `<div class="mt-4">
<p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Lampiran Bukti</p>
<a href="${data.lampiran}" target="_blank">
    <img src="${data.lampiran}" class="w-full max-w-sm rounded-xl border-2 border-gray-200 shadow-sm hover:opacity-90 transition">
</a>
</div>`; }
            else if (data.lampiran) { lampiranHtml = `<div class="mt-4 bg-gray-50 p-3 rounded-xl border border-gray-200">
<p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Alasan Tidak Melampirkan Foto</p>
<p class="text-sm font-bold text-gray-700 italic">"${data.lampiran.replace('Skip: ', '')}"</p>
</div>`; }

            c.innerHTML = `
                <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
<div>
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal Masuk</p>
    <p class="font-bold text-gray-800">${formatTgl(data.created_at)}</p>
</div>
<div>
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status Terkini</p>
    <span class="bg-white border px-2 py-1 rounded text-xs font-bold shadow-sm">${data.status}</span>
</div>
<div>
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Pengirim</p>
    <p class="font-bold text-gray-800">${data.pengirim_nama}</p>
</div>
<div>
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kelas</p>
    <p class="font-bold text-gray-800">${data.pengirim_kelas}</p>
</div>
<div>
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">No. WhatsApp</p>
    <a href="https://wa.me/${data.pengirim_wa}" target="_blank" class="font-bold text-green-600 hover:underline">
        <i class="fab fa-whatsapp">
        </i> ${data.pengirim_wa}</a>
    </div>
    <div>
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kategori / Topik</p>
        <p class="font-bold text-gray-800">${data.kategori}</p>
    </div>
</div>
<div>
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Isi Laporan / Pesan Detail</p>
    <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 text-gray-800 font-medium leading-relaxed whitespace-pre-wrap">${data.detail_pesan}</div>
</div>
                ${lampiranHtml}
            `;
            document.getElementById('modalAspirasi').classList.remove('hidden');
        }

        async function simpanStatusAspirasi() {
            const id = document.getElementById('updateIdAspirasiVal').value;
            const status = document.getElementById('updateStatusAspirasiVal').value;
            const konfirmStatus = await Swal.fire({ title: 'Ubah Status?', text: `Status laporan akan diubah menjadi "${status}" dan notifikasi akan dikirim ke siswa.`, icon: 'question', showCancelButton: true, confirmButtonColor: '#2563eb', confirmButtonText: 'Iya, Terapkan', cancelButtonText: 'Batal' });
            if (!konfirmStatus.isConfirmed) return;
            const btn = document.getElementById('btnSimpanStatusAsp');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin">
</i> Menyimpan...'; btn.disabled = true;
            try {
                // updateAspirasiStatus di backend sudah otomatis:
                // 1. Update status di DB
                // 2. Kirim push notif OneSignal ke HP siswa
                // 3. Kirim WA via Fonnte ke nomor siswa
                await callBackend({ type: 'supabase_post', action: 'updateAspirasiStatus', payload: { id, status } });
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Status diperbarui & notifikasi terkirim', showConfirmButton: false, timer: 2000 });
                document.getElementById('modalAspirasi').classList.add('hidden');
                loadDataTable('aspirasi', currentTableId);
            } catch(e) { Swal.fire('Error', e.message, 'error'); }
            finally { btn.innerHTML = '<i class="fas fa-save">
</i> Terapkan Status'; btn.disabled = false; }
        }

        function bukaProfil() {
            { const _pt = document.getElementById('profTimeout'); if (_pt) _pt.value = Math.round(IDLE_LIMIT_DETIK / 60) || 2; }
            document.getElementById('profEmail').value = userData.email_admin; document.getElementById('profNama').value = userData.nama_admin || userData.nama_lengkap || ''; document.getElementById('profTelp').value = userData.no_telp || ''; document.getElementById('profAgama').value = userData.agama || 'Islam'; document.getElementById('profRoleBadge').textContent = userData.role_name;
            document.getElementById('passwordEditContainer').classList.add('hidden'); document.getElementById('btnTogglePasswordEdit').classList.remove('hidden'); document.getElementById('profPass').value = ''; document.getElementById('profPassConfirm').value = '';
            document.getElementById('modalProfil').classList.remove('hidden');
        }
        function tutupProfil() { document.getElementById('modalProfil').classList.add('hidden'); }
        function togglePasswordEditForm() { document.getElementById('passwordEditContainer').classList.remove('hidden'); document.getElementById('btnTogglePasswordEdit').classList.add('hidden'); }

        async function simpanProfilAdmin() {
            const konfirm = await Swal.fire({ title: 'Simpan Profil?', text: 'Data profil admin akan diperbarui.', icon: 'question', showCancelButton: true, confirmButtonColor: '#2563eb', confirmButtonText: 'Iya, Simpan', cancelButtonText: 'Batal' });
            if (!konfirm.isConfirmed) return;
            const nama = document.getElementById('profNama').value; const telp = document.getElementById('profTelp').value; const agama = document.getElementById('profAgama').value;
            const pass = document.getElementById('profPass').value; const passConf = document.getElementById('profPassConfirm').value;
            if(!document.getElementById('passwordEditContainer').classList.contains('hidden')) { if(!pass || pass.length < 6) return Swal.fire('Error', 'Password baru minimal 6 karakter', 'warning'); if(pass !== passConf) return Swal.fire('Error', 'Konfirmasi password tidak cocok', 'warning'); }
            const btn = document.getElementById('btnSaveProfil'); btn.innerHTML = '<i class="fas fa-spinner fa-spin">
</i> Menyimpan...'; btn.disabled = true;
            try {
                const _tm = parseInt(document.getElementById('profTimeout')?.value, 10);
                if (isNaN(_tm) || _tm < 1 || _tm > 1440) throw new Error('Timeout sesi harus 1 sampai 1440 menit.');
                let payloadUpdate = { nama_lengkap: nama, no_telp: telp, agama: agama };
                await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'hak_akses_admin', id: userData.id, payload: payloadUpdate } });
                await callBackend({ type: 'supabase_post', action: 'updatePengaturan', payload: [{ nama_pengaturan: 'admin_timeout_' + String(userData.email_admin || '').toLowerCase(), nilai: String(_tm) }] });
                IDLE_LIMIT_DETIK = _tm * 60; localStorage.setItem('admin_timeout_menit', String(_tm)); mulaiIdleWatcher();
                if(pass) { await supaClient.auth.updateUser({ password: pass }); }
                userData.nama_admin = nama; userData.nama_lengkap = nama; userData.no_telp = telp; userData.agama = agama; localStorage.setItem('admin_ea_session', JSON.stringify(userData)); document.getElementById('sidebarNama').textContent = nama;
                Swal.fire({toast: true, position: 'top-end', icon: 'success', title: 'Profil Tersimpan', showConfirmButton: false, timer: 1500}); tutupProfil();
            } catch(e) { Swal.fire('Gagal', e.message, 'error'); } finally { btn.innerHTML = '<i class="fas fa-save text-lg">
</i> Simpan Profil'; btn.disabled = false; }
        }

        // ============================================================
        // DEMO MODE — Tampilan + Interaktif Tanpa DB (data sementara)
        // ============================================================
        let isDemoMode = false;

        function masukDemoMode() {
            isDemoMode = true;
            userData = {
                id: 'demo-id', email_admin: 'demo@e-aspirasi.id',
                nama_lengkap: 'Demo Admin', nama_admin: 'Demo Admin',
                role_name: 'Super Admin',
                akses: ['laporan','masukan','konselor','siswa','siswa_view','siswa_edit','kelas','role',
                        'admin','anggota_elpoint','poin','buku_saku','absensi','history','settings','apikey','Super Admin'],
                agama: 'Islam', no_telp: ''
            };
            isSuperAdminUnlocked = true;
            document.getElementById('loginScreen').classList.add('hidden');
            document.getElementById('mainDashboard').classList.remove('hidden');
            document.getElementById('sidebarNama').textContent = 'Demo Admin';
            document.getElementById('sidebarRole').textContent = 'DEMO MODE';

            if (!document.getElementById('demoBanner')) {
                const banner = document.createElement('div');
                banner.id = 'demoBanner';
                banner.className = 'bg-yellow-400 text-yellow-900 text-xs font-black text-center py-2 px-4 flex items-center justify-center gap-2 z-[200] relative flex-shrink-0';
                banner.innerHTML = '<i class="fas fa-eye">
</i>
<span>MODE DEMO — Interaktif: boleh tambah/edit/hapus. Data hilang saat reload, tidak tersimpan ke database.</span>
<button onclick="keluarDemoMode()" class="ml-3 bg-yellow-700 text-white px-3 py-1 rounded-lg hover:bg-yellow-800 transition text-[10px]">
    <i class="fas fa-sign-out-alt mr-1">
    </i>Keluar Demo</button>';
                const topBar = document.querySelector('.bg-gray-900.text-white.text-xs.py-2');
                if (topBar) topBar.after(banner); else document.body.insertBefore(banner, document.body.firstChild);
            }
            renderSidebarMenu();
            switchView('dashboard');
        }

        function keluarDemoMode() {
            isDemoMode = false; userData = null; isSuperAdminUnlocked = false;
            const banner = document.getElementById('demoBanner');
            if (banner) banner.remove();
            document.getElementById('mainDashboard').classList.add('hidden');
            document.getElementById('loginScreen').classList.remove('hidden');
            document.getElementById('loginForm').reset();
        }

        // Data contoh demo — in-memory (reset saat reload halaman)
        const DEMO_DATA = {
            laporan: [
                { id:'d1', tipe:'Laporan', pengirim_nama:'Budi Santoso', pengirim_kelas:'X RPL 1', pengirim_wa:'6281234567890', kategori:'Fasilitas Rusak', detail_pesan:'Kursi di kelas X RPL 1 banyak yang rusak dan perlu diganti segera.', status:'Menunggu', created_at:new Date().toISOString() },
                { id:'d2', tipe:'Laporan', pengirim_nama:'Siti Rahayu', pengirim_kelas:'XI TKJ 2', pengirim_wa:'6281234567891', kategori:'Kebersihan', detail_pesan:'Toilet lantai 2 sangat kotor dan tidak terawat.', status:'Sedang Diproses', created_at:new Date(Date.now()-86400000).toISOString() },
                { id:'d3', tipe:'Laporan', pengirim_nama:'Ahmad Fauzi', pengirim_kelas:'XII RPL 1', pengirim_wa:'6281234567892', kategori:'Keamanan', detail_pesan:'Ada orang tidak dikenal yang sering masuk ke area sekolah.', status:'Selesai', created_at:new Date(Date.now()-172800000).toISOString() }
            ],
            masukan: [
                { id:'d4', tipe:'Masukan', pengirim_nama:'Dewi Lestari', pengirim_kelas:'X AKL 1', pengirim_wa:'6281234567893', kategori:'Saran Fasilitas', detail_pesan:'Mohon ditambahkan AC di ruang lab komputer.', status:'Menunggu', created_at:new Date().toISOString() },
                { id:'d5', tipe:'Masukan', pengirim_nama:'Rizki Maulana', pengirim_kelas:'XI RPL 1', pengirim_wa:'6281234567894', kategori:'Saran Pembelajaran', detail_pesan:'Sebaiknya jadwal praktikum ditambah menjadi 2x seminggu.', status:'Ditolak', created_at:new Date(Date.now()-86400000).toISOString() },
                { id:'d6', tipe:'Masukan', pengirim_nama:'Ani Kartika', pengirim_kelas:'XII AKL 1', pengirim_wa:'6281234567895', kategori:'Saran Kegiatan', detail_pesan:'Usulkan kegiatan study tour ke perusahaan IT terkemuka.', status:'Selesai', created_at:new Date(Date.now()-172800000).toISOString() }
            ],
            konselor: [
                { id:'d7', tipe:'Konselor', pengirim_nama:'Fajar Nugroho', pengirim_kelas:'X TKJ 1', pengirim_wa:'6281234567896', kategori:'Guru BK', detail_pesan:'Saya ingin konsultasi mengenai masalah belajar.', status:'Menunggu', created_at:new Date().toISOString() },
                { id:'d8', tipe:'Konselor', pengirim_nama:'Maya Sari', pengirim_kelas:'XI AKL 1', pengirim_wa:'6281234567897', kategori:'Wali Kelas', detail_pesan:'Perlu bimbingan mengenai pilihan jurusan kuliah.', status:'Sedang Diproses', created_at:new Date(Date.now()-86400000).toISOString() },
                { id:'d9', tipe:'Konselor', pengirim_nama:'Hendra Wijaya', pengirim_kelas:'XII TKJ 1', pengirim_wa:'6281234567898', kategori:'Guru BK', detail_pesan:'Konsultasi masalah pribadi yang mengganggu konsentrasi.', status:'Selesai', created_at:new Date(Date.now()-172800000).toISOString() }
            ],
            siswa: [
                { id:'s1', nama:'Budi Santoso', nama_panggilan:'Budi', nisn:'0012345678', nik:'3509012345678901', kelas:'X RPL 1', tgl_lahir:'15/03/2008', no_telp:'6281234567890', status:'Aktif', total_poin:0 },
                { id:'s2', nama:'Siti Rahayu', nama_panggilan:'Siti', nisn:'0012345679', nik:'3509012345678902', kelas:'XI TKJ 2', tgl_lahir:'22/07/2007', no_telp:'6281234567891', status:'Aktif', total_poin:6 },
                { id:'s3', nama:'Ahmad Fauzi', nama_panggilan:'Fauzi', nisn:'0012345680', nik:'3509012345678903', kelas:'XII RPL 1', tgl_lahir:'10/11/2006', no_telp:'6281234567892', status:'Diblokir', total_poin:30 }
            ],
            poin: [
                { id:'s1', nama:'Budi Santoso', nisn:'0012345678', kelas:'X RPL 1', total_poin:0, status:'Aktif' },
                { id:'s2', nama:'Siti Rahayu', nisn:'0012345679', kelas:'XI TKJ 2', total_poin:6, status:'Aktif' },
                { id:'s3', nama:'Ahmad Fauzi', nisn:'0012345680', kelas:'XII RPL 1', total_poin:30, status:'Diblokir' }
            ],
            absensi: [
                { id:'a1', nama:'Budi Santoso', nisn:'0012345678', kelas:'X RPL 1', h:18, s:1, i:1, a:0, history:[] },
                { id:'a2', nama:'Siti Rahayu', nisn:'0012345679', kelas:'XI TKJ 2', h:15, s:2, i:1, a:2, history:[] },
                { id:'a3', nama:'Ahmad Fauzi', nisn:'0012345680', kelas:'XII RPL 1', h:20, s:0, i:0, a:0, history:[] }
            ],
            buku_saku: [
                { id:'b1', kelakuan:'Memakai seragam tidak rapi / tidak dimasukkan', kategori:'Kerapian', point:4 },
                { id:'b2', kelakuan:'Tidak memakai kaos kaki sesuai ketentuan', kategori:'Kerapian', point:4 },
                { id:'b3', kelakuan:'Terlambat hadir di sekolah', kategori:'Tanggung Jawab', point:6 }
            ],
            anggota_elpoint: [
                { id:'e1', nama_anggota:'Sastro Negoro', username:'Sastro', role:'OSIS' },
                { id:'e2', nama_anggota:'Dewi Sekretaris', username:'dewi.sek', role:'Sekretaris' },
                { id:'e3', nama_anggota:'Rizki OSIS', username:'rizki.osis', role:'OSIS' }
            ],
            kelas: [
                { id:'k1', nama_kelas:'X RPL 1' }, { id:'k2', nama_kelas:'XI TKJ 2' }, { id:'k3', nama_kelas:'XII AKL 1' }
            ],
            role: [
                { id:'r1', nama_role:'Super Admin', akses_fitur:'["laporan","masukan","konselor","siswa","settings","Super Admin"]' },
                { id:'r2', nama_role:'Guru BK', akses_fitur:'["konselor","siswa"]' },
                { id:'r3', nama_role:'Admin Umum', akses_fitur:'["laporan","masukan"]' }
            ],
            history: [
                { id:'h1', aktor:'Super Admin', role:'Super Admin', aksi:'Login Sukses', detail:'Admin berhasil masuk ke sistem.', created_at:new Date().toISOString() },
                { id:'h2', aktor:'Budi Santoso', role:'Siswa', aksi:'Kirim Laporan', detail:'Mengirim laporan fasilitas rusak.', created_at:new Date(Date.now()-3600000).toISOString() },
                { id:'h3', aktor:'Super Admin', role:'Super Admin', aksi:'Update Status', detail:'Mengubah status laporan ke Selesai.', created_at:new Date(Date.now()-7200000).toISOString() }
            ],
            apikey: [
                { id:'ak1', nama_aplikasi:'GRISAPOINT WEB', api_key : '{{ env('API_KEY_1') }}', status:'Aktif', created_at:new Date().toISOString() },
                { id:'ak2', nama_aplikasi:'MOBILE APP', api_key : '{{ env('API_KEY_2') }}', status:'Aktif', created_at:new Date(Date.now()-86400000).toISOString() },
                { id:'ak3', nama_aplikasi:'EXTERNAL APP', api_key : '{{ env('API_KEY_3') }}', status:'Dicabut', created_at:new Date(Date.now()-172800000).toISOString() }
            ]
        };

        function switchViewDemo(viewId) {
            document.querySelectorAll('.page-view').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('#menuNav a').forEach(el => { el.classList.remove('bg-blue-600','text-white','shadow-md'); el.classList.add('text-gray-400','hover:bg-gray-800'); });
            const targetMenu = document.getElementById('menu-' + viewId);
            if (targetMenu) { targetMenu.classList.remove('text-gray-400','hover:bg-gray-800'); targetMenu.classList.add('bg-blue-600','text-white','shadow-md'); }
            activeView = viewId;

            if (viewId === 'dashboard') {
                document.getElementById('view-dashboard').classList.remove('hidden');
                document.getElementById('pageTitle').textContent = 'Dashboard Utama ✦ DEMO';
                setTimeout(() => {
                    ['statLaporan','statMasukan','statKonselor','statSiswa'].forEach((id,i) => {
                        const e = document.getElementById(id); if (e) e.textContent = '3';
                    });
                }, 100);
            } else if (viewId === 'settings') {
                document.getElementById('view-settings').classList.remove('hidden');
                document.getElementById('pageTitle').textContent = 'Pengaturan Web ✦ DEMO';
            } else if (viewId === 'penilaian_admin') {
                document.getElementById('view-penilaian_admin').classList.remove('hidden');
                document.getElementById('pageTitle').textContent = 'Statistik Penilaian ✦ DEMO';
            } else if (viewId === 'menu_siswa') {
                document.getElementById('view-menu_siswa').classList.remove('hidden');
                document.getElementById('pageTitle').textContent = 'Menu Siswa Tambahan ✦ DEMO';
                muatMenuSiswaAdmin();
            } else if (viewId === 'pengumuman') {
                document.getElementById('view-pengumuman').classList.remove('hidden');
                document.getElementById('pageTitle').textContent = 'Pengumuman ✦ DEMO';
            } else if (viewId === 'tampilan_web') {
                document.getElementById('view-tampilan_web').classList.remove('hidden');
                document.getElementById('pageTitle').textContent = 'Pengaturan Tampilan Web ✦ DEMO';
            } else if (viewId === 'kelas') {
                loadDemoTable('kelas', 'Data Master Kelas');
            } else if (viewId === 'role') {
                loadDemoTable('role', 'Jabatan & Rules');
            } else {
                const menuItem = MENU_CONFIG.find(m => m.id === viewId);
                loadDemoTable(viewId, menuItem?.label || viewId);
            }
            if (window.innerWidth < 768) toggleSidebarMobile();
        }

        function loadDemoTable(viewId, title) {
            document.getElementById('view-datatable').classList.remove('hidden');
            currentTableId = viewId;
            // Deep copy agar perubahan demo tidak merusak original DEMO_DATA
            const data = JSON.parse(JSON.stringify(DEMO_DATA[viewId] || []));
            globalDataStore[viewId] = data;
            currentFilteredData = [...data];
            currentPage = 1;
            document.getElementById('pageTitle').textContent = title + ' ✦ DEMO';

            const actionEl = document.getElementById('dtActionsContainer');
            if (actionEl) {
                let btnHtml = '<span class="bg-yellow-100 text-yellow-700 border border-yellow-300 px-3 py-2 rounded-xl text-[10px] font-black flex items-center gap-1.5 flex-shrink-0">
    <i class="fas fa-database">
    </i> DEMO</span>';
                if (!['laporan','masukan','konselor','absensi','history'].includes(viewId)) {
                    btnHtml += `<button onclick="demoBukaFormTambah('${viewId}')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
    <i class="fas fa-plus">
    </i> Tambah</button>`;
                }
                btnHtml += `<button onclick="piliSemua()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
    <i class="fas fa-check-square">
    </i> Pilih Semua</button>`;
                actionEl.innerHTML = btnHtml;
            }

            const filterEl = document.getElementById('dtDynamicFilterContainer');
            if (filterEl) filterEl.innerHTML = '';

            hideBulkBar();
            renderDataTableHeader(viewId);
            renderDataTableBody();
        }

        function demoBukaFormTambah(viewId) {
            // Isi globalDataStore['kelas'] untuk dropdown kelas di form siswa
            if (viewId === 'siswa' && !globalDataStore['kelas']) {
                globalDataStore['kelas'] = JSON.parse(JSON.stringify(DEMO_DATA['kelas']));
            }
            bukaModalData(viewId, 'add');
            // Ganti tombol submit agar tidak ke DB
            setTimeout(() => {
                const btn = document.getElementById('btnSubmitDataForm');
                if (btn) btn.innerHTML = '<i class="fas fa-plus text-lg">
</i> Tambah Data (Demo)';
                const form = document.getElementById('genericDataForm');
                if (form) {
                    const demoHandler = (e) => {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        demoSimpanDataBaru(viewId);
                    };
                    form.addEventListener('submit', demoHandler, { once: true });
                }
            }, 200);
        }

        function demoSimpanDataBaru(viewId) {
            let newItem = { id: 'demo-' + Date.now() };
            if (viewId === 'siswa') {
                newItem = { ...newItem, nama: document.getElementById('fmSiswaNama')?.value||'Siswa Baru', nisn: document.getElementById('fmSiswaNisn')?.value||'', nik: document.getElementById('fmSiswaNik')?.value||'', kelas: document.getElementById('fmSiswaKelas')?.value||'X RPL 1', tgl_lahir: document.getElementById('fmSiswaTgl')?.value||'', no_telp: document.getElementById('fmSiswaWa')?.value||'', status: document.getElementById('fmSiswaStatus')?.value||'Aktif', total_poin:0 };
                // Sync ke poin demo juga
                DEMO_DATA['poin'].unshift({ ...newItem });
                if (globalDataStore['poin']) globalDataStore['poin'].unshift({ ...newItem });
            } else if (viewId === 'buku_saku') {
                newItem = { ...newItem, kelakuan: document.getElementById('fmBukuKelakuan')?.value||'Pelanggaran Baru', kategori: document.getElementById('fmBukuKategori')?.value||'Kedisiplinan', point: parseInt(document.getElementById('fmBukuPoin')?.value)||0 };
            } else if (viewId === 'anggota_elpoint') {
                newItem = { ...newItem, nama_anggota: document.getElementById('fmElpointNama')?.value||'Anggota Baru', username: document.getElementById('fmElpointUser')?.value||'user.baru', role: document.getElementById('fmElpointRole')?.value||'OSIS' };
            } else if (viewId === 'kelas') {
                newItem = { ...newItem, nama_kelas: (document.getElementById('formKelasNama')?.value||'Kelas Baru').toUpperCase() };
            } else if (viewId === 'role') {
                const checkboxes = document.querySelectorAll('#genericDataForm input[type="checkbox"]:checked');
                newItem = { ...newItem, nama_role: document.getElementById('formRoleNama')?.value||'Jabatan Baru', akses_fitur: JSON.stringify(Array.from(checkboxes).map(cb => cb.value)) };
            } else if (viewId === 'apikey') {
                newItem = { ...newItem, nama_aplikasi: document.getElementById('fmApiNama')?.value||'Aplikasi Baru', api_key : '{{ env('API_KEY_4') }}' + Math.random().toString(36).substr(2,8).toUpperCase(), status:'Aktif', created_at: new Date().toISOString() };
            }
            DEMO_DATA[viewId] = DEMO_DATA[viewId] || [];
            DEMO_DATA[viewId].unshift(newItem);
            globalDataStore[viewId] = globalDataStore[viewId] || [];
            globalDataStore[viewId].unshift(newItem);
            currentFilteredData = [...globalDataStore[viewId]];
            document.getElementById('modalDataForm').classList.add('hidden');
            renderDataTableBody();
            Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Data demo ditambahkan! (tidak tersimpan ke DB)', showConfirmButton:false, timer:2500 });
        }

        // ============================================================
        // FUNGSI BULK ACTION & NAIK KELAS
        // ============================================================
        const _selectedSet = new Set();
        function _rowId(item) {
            if (currentTableId === 'absensi') return String(item.key);
            if (currentTableId === 'history') return String(item.id || item.aktor);
            return String(item.id);
        }
        function _syncSelDariDOM() {
            document.querySelectorAll('.bulk-check').forEach(c => { if (c.checked) _selectedSet.add(String(c.value)); else _selectedSet.delete(String(c.value)); });
        }
        function _terapkanSelKeDOM() {
            document.querySelectorAll('.bulk-check').forEach(c => { c.checked = _selectedSet.has(String(c.value)); });
        }
        async function piliSemua() {
            const pageIds = [...document.querySelectorAll('.bulk-check')].map(c => String(c.value));
            if (!pageIds.length) return false;
            const allIds = (window._displayData || []).map(_rowId);
            const r = await Swal.fire({
                title: 'Pilih Semua', html: '<p class="text-sm text-gray-500">Pilih cakupan data yang ingin dipilih:</p>',
                icon: 'question', showDenyButton: true, showCancelButton: true,
                confirmButtonText: `Halaman ini (${pageIds.length} data)`,
                denyButtonText: `Semua data (${allIds.length} data)`,
                cancelButtonText: 'Batal', confirmButtonColor: '#2563eb', denyButtonColor: '#4f46e5'
            });
            if (r.isDismissed) return false;
            _selectedSet.clear();
            (r.isConfirmed ? pageIds : allIds).forEach(id => _selectedSet.add(id));
            _terapkanSelKeDOM(); updateBulkBar();
            return true;
        }
        function hideBulkBar() {
            _selectedSet.clear();
            const bar = document.getElementById('bulkActionBar');
            if (bar) bar.classList.add('hidden');
            document.querySelectorAll('.bulk-check').forEach(c => c.checked = false);
            const cm = document.getElementById('checkAllMain'); if (cm) cm.checked = false;
            const w = document.getElementById('tindakanDropdownWrapper'); if (w) w.classList.add('hidden');
            const btns = document.getElementById('bulkActionBtns'); if (btns) btns.innerHTML = '';
        }
        function getCheckedIds() {
            if (currentTableId === 'kelas') return [...document.querySelectorAll('.kelas-check:checked')].map(cb => cb.dataset.id);
            if (currentTableId === 'role') return _bukanTerkunci([...document.querySelectorAll('.role-check:checked')].map(cb => cb.dataset.id));
            _syncSelDariDOM();
            return _bukanTerkunci([..._selectedSet]);
        }
        async function toggleCheckAll(el) {
            if (!el.checked) { hideBulkBar(); return; }
            const ok = await piliSemua();
            if (!ok) el.checked = false;
        }
        function batalPilihan() {
            hideBulkBar();
            const ca = document.getElementById('checkAllSiswa'); if (ca) ca.checked = false;
        }

        async function bulkHapus() {
            const ids = getCheckedIds();
            if (ids.length === 0) return;
            // Demo mode
            if (typeof isDemoMode !== 'undefined' && isDemoMode) {
                const conf = await Swal.fire({ title:`Hapus ${ids.length} data demo?`, text:'Hanya dari tampilan demo.', icon:'warning', showCancelButton:true, confirmButtonColor:'#d33', confirmButtonText:'Hapus', cancelButtonText:'Batal' });
                if (!conf.isConfirmed) return;
                ids.forEach(id => {
                    for (const key of Object.keys(DEMO_DATA)) {
                        DEMO_DATA[key] = (DEMO_DATA[key]||[]).filter(d => d.id !== id);
                        if (globalDataStore[key]) globalDataStore[key] = globalDataStore[key].filter(d => d.id !== id);
                    }
                    currentFilteredData = currentFilteredData.filter(d => d.id !== id);
                });
                renderDataTableBody(); hideBulkBar();
                Swal.fire({ toast:true, position:'top-end', icon:'success', title:`${ids.length} data demo dihapus`, showConfirmButton:false, timer:2000 });
                return;
            }
            // Tentukan tabel yang benar berdasarkan menu aktif
            const tabelMap = { siswa: 'siswa', laporan: 'aspirasi', masukan: 'aspirasi', konselor: 'aspirasi', anggota_elpoint: 'anggota_elpoint', buku_saku: 'master_pelanggaran', apikey : '{{ env('APIKEY') }}', guru: 'master_guru', lapguru: 'laporan_guru', admin: 'hak_akses_admin', history: 'log_history', semester: 'master_semester', mapel: 'master_mapel' };
            const tableName = tabelMap[currentTableId] || currentTableId;
            const label = currentTableId === 'laporan' ? 'laporan' : currentTableId === 'masukan' ? 'masukan' : currentTableId === 'konselor' ? 'jadwal konselor' : 'data';

            const conf = await Swal.fire({
                title: `Hapus ${ids.length} ${label}?`,
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus Semua',
                cancelButtonText: 'Batal'
            });
            if (!conf.isConfirmed) return;
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            let berhasil = 0;
            for (const id of ids) {
                try { await callBackend({ type: 'supabase_post', action: 'deleteData', payload: { table: tableName, id } }); berhasil++; } catch(e) {}
            }
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${berhasil} data berhasil dihapus`, showConfirmButton: false, timer: 2000 });
            batalPilihan();
            // Reload tabel yang benar
            const menuItem = MENU_CONFIG.find(m => m.id === currentTableId);
            loadDataTable(menuItem ? menuItem.table : tableName, currentTableId);
        }

        async function naikKelas() { await bulkNaikKelas(); }

        async function bulkNaikKelas() {
            const ids = getCheckedIds();
            if (ids.length === 0) { Swal.fire('Pilih Siswa Dulu', 'Centang siswa yang ingin dinaikkan kelasnya.', 'info'); return; }
            const pilih = await Swal.fire({
                title: `Naik Kelas ${ids.length} Siswa`,
                html: '<p class="text-sm text-gray-600 mb-2">Pilih cara menaikkan kelas:</p>
<p class="text-xs text-gray-500 text-left">
    <b>Naik Cepat</b>: otomatis X → XI, XI → XII, XII → Alumni (ada konfirmasi dulu).<br>
    <b>Pilih Manual</b>: tentukan sendiri kelas tujuan.</p>',
                icon: 'question', showDenyButton: true, showCancelButton: true,
                confirmButtonText: '<i class="fas fa-bolt mr-1">
</i> Naik Cepat', denyButtonText: 'Pilih Manual', cancelButtonText: 'Batal',
                confirmButtonColor: '#4f46e5', denyButtonColor: '#2563eb'
            });
            if (pilih.isConfirmed) return naikKelasCepat(ids);
            if (pilih.isDenied) return naikKelasManual(ids);
        }

        async function naikKelasCepat(ids) {
            let kelasList = [];
            try { const r = await callBackend({ type: 'supabase_get', action: 'getAllKelas' }); kelasList = (r.data || []).map(k => k.nama_kelas); } catch(e) {}
            const peta = {}; kelasList.forEach(k => { peta[String(k).toUpperCase().trim()] = k; });
            const siswaData = ids.map(id => (globalDataStore['siswa'] || []).find(s => s.id === id)).filter(Boolean);
            const rencana = [], dilewati = [];
            siswaData.forEach(s => {
                const m = String(s.kelas || '').trim().match(/^(XII|XI|X)(\s.*)?$/i);
                if (!m) { dilewati.push({ s, alasan: 'format kelas tidak dikenali' }); return; }
                const t = m[1].toUpperCase(), sisa = m[2] || '';
                if (t === 'XII') { rencana.push({ s, alumni: true, dari: s.kelas, ke: 'ALUMNI' }); return; }
                const tujuan = (t === 'X' ? 'XI' : 'XII') + sisa;
                const nama = peta[tujuan.toUpperCase().trim()];
                if (!nama) { dilewati.push({ s, alasan: `kelas "${tujuan}" belum ada di Master Kelas` }); return; }
                rencana.push({ s, alumni: false, dari: s.kelas, ke: nama });
            });
            const htmlLewat = dilewati.length
                ? `<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 mt-3 text-xs text-yellow-800 text-left">
<p class="font-bold mb-1">⚠️ ${dilewati.length} siswa dilewati:</p>${[...new Set(dilewati.map(d => d.s.kelas + ' — ' + d.alasan))].map(t => `<p>• ${t}</p>`).join('')}</div>` : '';
            if (!rencana.length) { return Swal.fire({ icon: 'warning', title: 'Tidak Ada yang Bisa Diproses', html: htmlLewat || 'Tidak ada siswa valid.' }); }
            const grup = {};
            rencana.forEach(r => { const k = r.dari + ' → ' + r.ke; grup[k] = (grup[k] || 0) + 1; });
            const htmlRencana = Object.entries(grup).map(([k, n]) => `<li class="flex justify-between gap-3">
<span>${k}</span>
<b class="whitespace-nowrap">${n} siswa</b>
</li>`).join('');
            const nAlumni = rencana.filter(r => r.alumni).length;
            const konf = await Swal.fire({
                title: 'Konfirmasi Naik Cepat',
                html: `<div class="text-left text-sm">
<ul class="bg-gray-50 rounded-xl p-3 space-y-1 text-gray-700 max-h-48 overflow-y-auto">${htmlRencana}</ul>${nAlumni ? `<div class="bg-red-50 border border-red-200 rounded-xl p-3 mt-3 text-xs text-red-700">🎓 <b>${nAlumni} siswa kelas XII</b> akan diubah statusnya menjadi <b>Alumni</b>.</div>` : ''}${htmlLewat}<p class="text-xs text-gray-500 mt-3">Perubahan kelas tercatat otomatis di riwayat kelas siswa. Lanjutkan?</p>${nAlumni ? `<div class="mt-3">
<label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Tanggal Lulus (default hari ini)</label>
<input type="date" id="tglLulusIn" value="${new Date().toISOString().slice(0,10)}" class="w-full px-3 py-2 border-2 border-gray-200 rounded-xl text-sm font-bold">
</div>` : ''}</div>`,
                icon: 'warning', showCancelButton: true, preConfirm: () => (document.getElementById('tglLulusIn')?.value || new Date().toISOString().slice(0,10)), confirmButtonText: `Ya, Proses ${rencana.length} Siswa`, cancelButtonText: 'Batal', confirmButtonColor: '#4f46e5'
            });
            if (!konf.isConfirmed) return;
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            let ok = 0;
            const tglLulus = konf.value || new Date().toISOString().slice(0,10);
            for (const r of rencana) {
                try {
                    if (r.alumni) { await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'alumni', payload: { id_siswa_lama: r.s.id, nama: r.s.nama, nama_panggilan: r.s.nama_panggilan || null, nisn: r.s.nisn || null, nik: r.s.nik || null, kelas_terakhir: r.s.kelas, tgl_lahir: r.s.tgl_lahir || null, no_telp: r.s.no_telp || null, total_poin: r.s.total_poin || 0, login_pin: r.s.login_pin || null, security_answer: r.s.security_answer || null, tanggal_lulus: tglLulus } } }); }
                    await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'siswa', id: r.s.id, payload: r.alumni ? { status: 'Alumni', arsip: true } : { kelas: r.ke } } });
                    ok++;
                } catch(e) {}
            }
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${ok} siswa berhasil diproses`, showConfirmButton: false, timer: 2500 });
            batalPilihan();
            loadDataTable('siswa', 'siswa');
        }

        async function naikKelasManual(ids) {

            // Ambil daftar kelas dari DB
            let kelasList = [];
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getAllKelas' });
                if (res.data) kelasList = res.data.map(k => k.nama_kelas).sort();
            } catch(e) {}

            // Buat opsi pemetaan kelas otomatis
            const siswaData = ids.map(id => globalDataStore['siswa'].find(s => s.id === id)).filter(Boolean);
            const kelasSummary = [...new Set(siswaData.map(s => s.kelas))].join(', ');

            const { value: targetKelasMap, isConfirmed } = await Swal.fire({
                title: `Naik Kelas ${ids.length} Siswa`,
                html: `
                    <p class="text-sm text-gray-600 mb-4">Kelas saat ini: <b>${kelasSummary}</b>
</p>
<div class="text-left space-y-3 max-h-64 overflow-y-auto pr-2">
                        ${[...new Set(siswaData.map(s => s.kelas))].map(kelas => `
                            <div class="bg-gray-50 p-3 rounded-xl border">
<p class="text-xs font-black text-gray-700 mb-2 uppercase tracking-widest">${kelas} → Naik ke:</p>
<select id="naik_${kelas.split(' ').join('_')}" class="w-full p-2 border-2 border-gray-200 rounded-lg font-bold text-sm bg-white">
    <option value="">-- Pilih Kelas Tujuan --</option>
                                    ${kelasList.filter(k => k !== kelas).map(k => `<option value="${k}">${k}</option>`).join('')}
                                </select>
</div>
                        `).join('')}
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Proses Naik Kelas',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#4f46e5',
                preConfirm: () => {
                    const map = {};
                    [...new Set(siswaData.map(s => s.kelas))].forEach(kelas => {
                        const el = document.getElementById('naik_' + kelas.split(' ').join('_'));
                        if (el && el.value) map[kelas] = el.value;
                    });
                    if (Object.keys(map).length === 0) { Swal.showValidationMessage('Pilih kelas tujuan!'); return false; }
                    return map;
                }
            });

            if (!isConfirmed || !targetKelasMap) return;

            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            let berhasil = 0;
            for (const siswa of siswaData) {
                const kelasTarget = targetKelasMap[siswa.kelas];
                if (!kelasTarget) continue;
                try {
                    await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'siswa', id: siswa.id, payload: { kelas: kelasTarget } } });
                    berhasil++;
                } catch(e) {}
            }
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${berhasil} siswa berhasil naik kelas!`, showConfirmButton: false, timer: 2500 });
            batalPilihan();
            loadDataTable('siswa', 'siswa');
        }


        // ============================================================
        // FUNGSI EDIT SISWA, BLOKIR, BULK STATUS ASPIRASI
        // ============================================================

        // Buka edit siswa (alias ke bukaModalData)
        function bukaEditSiswa(id) {
            bukaModalData('siswa', 'edit', id);
        }

        // Toggle blokir / aktifkan siswa
        async function toggleBlokir(id, statusSaatIni) {
            const isBlokir = statusSaatIni === 'Diblokir';
            const aksi = isBlokir ? 'Aktifkan' : 'Blokir';
            const warna = isBlokir ? '#22c55e' : '#f59e0b';
            const conf = await Swal.fire({
                title: `${aksi} Siswa ini?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: warna,
                confirmButtonText: `Ya, ${aksi}`,
                cancelButtonText: 'Batal'
            });
            if (!conf.isConfirmed) return;
            try {
                const statusBaru = isBlokir ? 'Aktif' : 'Diblokir';
                await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'siswa', id, payload: { status: statusBaru } } });
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `Siswa berhasil di${aksi.toLowerCase()}kan`, showConfirmButton: false, timer: 1500 });
                loadDataTable('siswa', 'siswa');
            } catch(e) { Swal.fire('Gagal', e.message, 'error'); }
        }

        function _tabelStatus() {
            return ({ siswa: 'siswa', guru: 'master_guru', admin: 'hak_akses_admin', anggota_elpoint: 'anggota_elpoint' })[currentTableId] || null;
        }
        function _reloadTabelAktif() {
            const m = MENU_CONFIG.find(x => x.id === currentTableId);
            if (m && m.table) loadDataTable(m.table, currentTableId);
        }
        async function bulkBlokir() { await _bulkStatusAkun('Diblokir'); }
        async function bulkBukaBlokir() { await _bulkStatusAkun('Aktif'); }
        async function _bulkStatusAkun(statusBaru) {
            const ids = getCheckedIds(); if (!ids.length) return;
            const blok = statusBaru === 'Diblokir';
            if (typeof isDemoMode !== 'undefined' && isDemoMode) {
                ids.forEach(id => { const it = currentFilteredData.find(d => d.id === id); if (it) it.status = statusBaru; });
                hideBulkBar(); renderDataTableBody();
                Swal.fire({ toast:true, position:'top-end', icon:'success', title:`${ids.length} akun diubah (demo)`, showConfirmButton:false, timer:2000 });
                return;
            }
            const tbl = _tabelStatus();
            if (!tbl) { Swal.fire('Info', 'Aksi ini tidak tersedia untuk menu ini.', 'info'); return; }
            const conf = await Swal.fire({
                title: blok ? `Blokir ${ids.length} akun?` : `Buka blokir ${ids.length} akun?`,
                text: blok ? 'Akun yang diblokir tidak bisa login.' : 'Akun akan aktif kembali.',
                icon: 'warning', showCancelButton: true, confirmButtonColor: blok ? '#f59e0b' : '#22c55e',
                confirmButtonText: blok ? 'Ya, Blokir' : 'Ya, Buka Blokir', cancelButtonText: 'Batal'
            });
            if (!conf.isConfirmed) return;
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            let ok = 0;
            for (const id of ids) {
                try { await callBackend({ type:'supabase_post', action:'updateData', payload:{ table: tbl, id, payload:{ status: statusBaru } } }); ok++; } catch(e) {}
            }
            Swal.fire({ toast:true, position:'top-end', icon:'success', title:`${ok} akun berhasil ${blok ? 'diblokir' : 'dibuka blokirnya'}`, showConfirmButton:false, timer:2000 });
            hideBulkBar(); _reloadTabelAktif();
        }

        async function bulkHapusKomentar() {
            const ids = getCheckedIds();
            if (!ids.length) return;
            const conf = await Swal.fire({ title:`Hapus ${ids.length} komentar?`, text:'Tidak bisa dikembalikan.', icon:'warning', showCancelButton:true, confirmButtonColor:'#d33', confirmButtonText:'Hapus' });
            if (!conf.isConfirmed) return;
            Swal.fire({ title:'Menghapus...', didOpen:()=>Swal.showLoading() });
            try {
                for (const id of ids) await callBackend({ type:'supabase_post', action:'deleteData', payload:{ table:'penilaian', id }});
                Swal.fire({ toast:true, position:'top-end', icon:'success', title:`${ids.length} komentar dihapus`, showConfirmButton:false, timer:2000 });
                muatStatistikPenilaian();
            } catch(e) { Swal.fire('Gagal', e.message, 'error'); }
        }

        async function bulkHapusPengumuman() {
            const ids = getCheckedIds();
            if (!ids.length) return;
            const conf = await Swal.fire({ title:`Hapus ${ids.length} pengumuman?`, text:'Tidak bisa dikembalikan.', icon:'warning', showCancelButton:true, confirmButtonColor:'#d33', confirmButtonText:'Hapus' });
            if (!conf.isConfirmed) return;
            Swal.fire({ title:'Menghapus...', didOpen:()=>Swal.showLoading() });
            try {
                for (const id of ids) await callBackend({ type:'supabase_post', action:'deleteData', payload:{ table:'pengumuman', id }});
                Swal.fire({ toast:true, position:'top-end', icon:'success', title:`${ids.length} pengumuman dihapus`, showConfirmButton:false, timer:2000 });
                muatListPengumuman();
            } catch(e) { Swal.fire('Gagal', e.message, 'error'); }
        }


        async function bulkUpdateStatus(statusBaru) {
            const ids = getCheckedIds();
            if (ids.length === 0) return;
            // Demo mode
            if (typeof isDemoMode !== 'undefined' && isDemoMode) {
                ids.forEach(id => {
                    currentFilteredData.forEach(d => { if(d.id===id) d.status = statusBaru; });
                    ['laporan','masukan','konselor'].forEach(k => {
                        const item = (DEMO_DATA[k]||[]).find(d => d.id===id);
                        if(item) item.status = statusBaru;
                    });
                });
                renderDataTableBody(); hideBulkBar();
                Swal.fire({ toast:true, position:'top-end', icon:'success', title:`${ids.length} status demo diubah ke "${statusBaru}"`, showConfirmButton:false, timer:2000 });
                return;
            }
            const warna = statusBaru === 'Selesai' ? '#22c55e' : statusBaru === 'Ditolak' ? '#ef4444' : '#3b82f6';
            const conf = await Swal.fire({
                title: `Ubah ${ids.length} tiket ke "${statusBaru}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: warna,
                confirmButtonText: 'Ya, Proses Semua',
                cancelButtonText: 'Batal'
            });
            if (!conf.isConfirmed) return;
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            let berhasil = 0;
            for (const id of ids) {
                try {
                    await callBackend({ type: 'supabase_post', action: 'updateAspirasiStatus', payload: { id, status: statusBaru } });
                    berhasil++;
                } catch(e) {}
            }
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${berhasil} tiket diubah ke "${statusBaru}"`, showConfirmButton: false, timer: 2000 });
            batalPilihan();
            loadDataTable('aspirasi', currentTableId);
        }

        // Override updateBulkBar agar set tombol aksi sesuai menu aktif (semua tabel)
        // updateBulkBar — dropdown tindakan muncul di sebelah Pilih Semua
        function updateBulkBar() {
            _syncSelDariDOM();
            { const _cs = [...document.querySelectorAll('.bulk-check')]; const _cm = document.getElementById('checkAllMain'); if (_cm) _cm.checked = _cs.length > 0 && _cs.every(c => c.checked); }
            const ids = getCheckedIds();
            const bar = document.getElementById('bulkActionBar');
            const countEl = document.getElementById('bulkCount');
            const wrapper = document.getElementById('tindakanDropdownWrapper');
            
            if (!bar) return;
            
            if (ids.length === 0) {
                bar.classList.add('hidden');
                if (wrapper) wrapper.classList.add('hidden');
                return;
            }
            
            // Show bottom bar (count + batal)
            bar.classList.remove('hidden');
            if (countEl) countEl.textContent = ids.length;
            
            if (!wrapper) return;
            wrapper.classList.remove('hidden');
            
            const n = ids.length;
            const dCls = 'w-full text-left px-4 py-2.5 text-xs font-bold hover:bg-gray-100 flex items-center gap-2 transition whitespace-nowrap';
            
            let items = '';
            
            if (['laporan','masukan','konselor'].includes(currentTableId)) {
                items += `<button onclick="bulkUpdateStatus('Sedang Diproses');closeTindakanDrop()" class="${dCls} text-blue-600">
<i class="fas fa-sync-alt w-4">
</i> Proses (${n})</button>`;
                items += `<button onclick="bulkUpdateStatus('Selesai');closeTindakanDrop()" class="${dCls} text-green-600">
<i class="fas fa-check w-4">
</i> Selesai (${n})</button>`;
                items += `<button onclick="bulkUpdateStatus('Ditolak');closeTindakanDrop()" class="${dCls} text-orange-600">
<i class="fas fa-times w-4">
</i> Tolak (${n})</button>`;
                items += `<hr class="my-1 border-gray-100">`;
                items += `<button onclick="bulkHapus();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus (${n})</button>`;
            } else if (currentTableId === 'siswa') {
                items += `<button onclick="bulkNaikKelas();closeTindakanDrop()" class="${dCls} text-indigo-600">
<i class="fas fa-level-up-alt w-4">
</i> Naik Kelas (${n})</button>`;
                items += `<button onclick="bulkBlokir();closeTindakanDrop()" class="${dCls} text-yellow-600">
<i class="fas fa-ban w-4">
</i> Blokir Akses (${n})</button>`;
                items += `<button onclick="bulkBukaBlokir();closeTindakanDrop()" class="${dCls} text-green-600">
<i class="fas fa-unlock w-4">
</i> Buka Blokir (${n})</button>`;
                items += `<button onclick="downloadSemuaQR();closeTindakanDrop()" class="${dCls} text-purple-600">
<i class="fas fa-qrcode w-4">
</i> Unduh QR (${n})</button>`;
                items += `<hr class="my-1 border-gray-100">`;
                items += `<button onclick="bulkHapus();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus Data (${n})</button>`;
            } else if (currentTableId === 'anggota_elpoint') {
                items += `<button onclick="bulkBlokir();closeTindakanDrop()" class="${dCls} text-yellow-600">
<i class="fas fa-ban w-4">
</i> Blokir Akses (${n})</button>`;
                items += `<button onclick="bulkBukaBlokir();closeTindakanDrop()" class="${dCls} text-green-600">
<i class="fas fa-unlock w-4">
</i> Buka Blokir (${n})</button>`;
                items += `<hr class="my-1 border-gray-100">`;
                items += `<button onclick="bulkHapus();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus Data (${n})</button>`;
            } else if (currentTableId === 'kelas') {
                items += `<button onclick="bulkHapusKelas();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus Kelas (${n})</button>`;
            } else if (currentTableId === 'role') {
                items += `<button onclick="bulkBlokir();closeTindakanDrop()" class="${dCls} text-yellow-600">
<i class="fas fa-ban w-4">
</i> Blokir Akses (${n})</button>`;
                items += `<button onclick="bulkBukaBlokir();closeTindakanDrop()" class="${dCls} text-green-600">
<i class="fas fa-unlock w-4">
</i> Buka Blokir (${n})</button>`;
                items += `<hr class="my-1 border-gray-100">`;
                items += `<button onclick="bulkHapusRole();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus (${n})</button>`;
            } else if (currentTableId === 'guru') {
                items += `<button onclick="bulkBlokir();closeTindakanDrop()" class="${dCls} text-yellow-600">
<i class="fas fa-ban w-4">
</i> Blokir Akses (${n})</button>`;
                items += `<button onclick="bulkBukaBlokir();closeTindakanDrop()" class="${dCls} text-green-600">
<i class="fas fa-unlock w-4">
</i> Buka Blokir (${n})</button>`;
                items += `<hr class="my-1 border-gray-100">`;
                items += `<button onclick="bulkHapus();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus Data Guru (${n})</button>`;
            } else if (currentTableId === 'lapguru') {
                items += `<button onclick="bulkHapus();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus Laporan (${n})</button>`;
            } else if (currentTableId === 'apikey') {
                items += `<button onclick="bulkCabutApiKey();closeTindakanDrop()" class="${dCls} text-yellow-600">
<i class="fas fa-pause-circle w-4">
</i> Hentikan Sementara (${n})</button>`;
                items += `<button onclick="bulkAktifkanApiKey();closeTindakanDrop()" class="${dCls} text-green-600">
<i class="fas fa-play-circle w-4">
</i> Aktifkan Kembali (${n})</button>`;
                items += `<hr class="my-1 border-gray-100">`;
                items += `<button onclick="bulkHapus();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus API (${n})</button>`;
            } else if (currentTableId === 'history') {
                items += `<button onclick="bulkHapus();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus Log (${n})</button>`;
            } else if (currentTableId === 'buku_saku') {
                items += `<button onclick="bulkHapus();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus Terpilih (${n})</button>`;
            } else if (currentTableId === 'semester' || currentTableId === 'mapel') {
                items += `<button onclick="bulkHapus();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus Terpilih (${n})</button>`;
            } else if (currentTableId === 'penilaian_admin') {
                items += `<button onclick="bulkHapusKomentar();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus Komentar (${n})</button>`;
            } else if (currentTableId === 'pengumuman') {
                items += `<button onclick="bulkHapusPengumuman();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus Pengumuman (${n})</button>`;
            } else if (currentTableId === 'admin') {
                items += `<button onclick="bulkBlokir();closeTindakanDrop()" class="${dCls} text-yellow-600">
<i class="fas fa-ban w-4">
</i> Blokir Akses (${n})</button>`;
                items += `<button onclick="bulkBukaBlokir();closeTindakanDrop()" class="${dCls} text-green-600">
<i class="fas fa-unlock w-4">
</i> Buka Blokir (${n})</button>`;
                items += `<hr class="my-1 border-gray-100">`;
                items += `<button onclick="bulkHapus();closeTindakanDrop()" class="${dCls} text-red-600">
<i class="fas fa-trash-alt w-4">
</i> Hapus Admin (${n})</button>`;
            } else if (currentTableId === 'absensi') {
                items += `<button onclick="bulkResetAbsensi();closeTindakanDrop()" class="${dCls} text-orange-600">
<i class="fas fa-undo w-4">
</i> Reset Absensi (${n} siswa)</button>`;
                
            } else if (currentTableId === 'poin') {
                items += `<button onclick="bulkResetPoin();closeTindakanDrop()" class="${dCls} text-orange-600">
<i class="fas fa-redo w-4">
</i> Reset Poin (${n})</button>`;
            }
            
            if (items) {
                wrapper.innerHTML = `
                    <button onclick="toggleTindakanDrop(event)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm select-none">
<i class="fas fa-bolt">
</i> Tindakan <i class="fas fa-chevron-down text-[9px] ml-0.5" id="tindakanChevron">
</i>
</button>
<div id="tindakanDropMenu" class="hidden absolute top-full left-0 mt-1 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 min-w-[220px] z-[80]">
                        ${items}
                    </div>`;
            } else {
                wrapper.innerHTML = '';
                wrapper.classList.add('hidden');
            }
        }
        
        function toggleTindakanDrop(e) {
            e.stopPropagation();
            const menu = document.getElementById('tindakanDropMenu');
            const chev = document.getElementById('tindakanChevron');
            if (!menu) return;
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden');
            if (chev) chev.className = isHidden ? 'fas fa-chevron-up text-[9px] ml-0.5' : 'fas fa-chevron-down text-[9px] ml-0.5';
        }
        function closeTindakanDrop() {
            const menu = document.getElementById('tindakanDropMenu');
            const chev = document.getElementById('tindakanChevron');
            if (menu) menu.classList.add('hidden');
            if (chev) chev.className = 'fas fa-chevron-down text-[9px] ml-0.5';
        }

        
        function toggleBulkDrop() {
            const menu = document.getElementById('bulkDropMenu');
            if (menu) menu.classList.toggle('hidden');
        }
        function closeBulkDrop() {
            const menu = document.getElementById('bulkDropMenu');
            if (menu) menu.classList.add('hidden');
        }

        
        // Bulk reset poin ke 0
        async function bulkResetPoin() {
            const ids = getCheckedIds(); if (ids.length === 0) return;
            const conf = await Swal.fire({ title: `Reset poin ${ids.length} siswa ke 0?`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#22c55e', confirmButtonText: 'Ya, Reset Semua', cancelButtonText: 'Batal' });
            if (!conf.isConfirmed) return;
            Swal.fire({ title: 'Mereset...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            let ok = 0;
            for (const id of ids) {
                try { await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'siswa', id, payload: { total_poin: 0 } } }); ok++; } catch(e) {}
            }
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${ok} poin berhasil direset`, showConfirmButton: false, timer: 2000 });
            batalPilihan(); loadDataTable('siswa', 'poin');
        }

        // Bulk set poin manual
        async function bulkSetPoin() {
            const ids = getCheckedIds(); if (ids.length === 0) return;
            const { value: poinBaru, isConfirmed } = await Swal.fire({
                title: `Set Poin ${ids.length} Siswa`, input: 'number', inputLabel: 'Masukkan nilai poin baru',
                inputAttributes: { min: 0, max: 999, step: 1 }, showCancelButton: true,
                confirmButtonText: 'Terapkan', cancelButtonText: 'Batal', confirmButtonColor: '#3b82f6',
                inputValidator: v => { if (!v && v !== '0') return 'Masukkan angka!'; }
            });
            if (!isConfirmed) return;
            Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            let ok = 0;
            for (const id of ids) {
                try { await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'siswa', id, payload: { total_poin: parseInt(poinBaru) } } }); ok++; } catch(e) {}
            }
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${ok} poin berhasil diset ke ${poinBaru}`, showConfirmButton: false, timer: 2000 });
            batalPilihan(); loadDataTable('siswa', 'poin');
        }

        // Bulk ekspor absensi terpilih ke Excel
        async function bulkEksporAbsensi() {
            const ids = getCheckedIds(); if (ids.length === 0) return;
            const data = ids.map(k => (window._absensiAggMap || {})[k]).filter(Boolean);
            if (!data || data.length === 0) return Swal.fire('Kosong', 'Data tidak ditemukan.', 'info');
            const POIN_TL = 6;
            const exportData = data.map(d => ({
                'Nama Siswa'     : d.nama,
                'NISN'           : d.nisn || '-',
                'Kelas'          : d.kelas || '-',
                'Hadir (Total)'  : d.h || 0,
                'Tepat Waktu'    : d.tepat_waktu !== undefined ? d.tepat_waktu : (d.h || 0),
                'Terlambat'      : d.terlambat || 0,
                'Sakit'          : d.s || 0,
                'Izin'           : d.i || 0,
                'Alpa'           : d.a || 0,
                'Status Hadir'   : (d.terlambat||0) > 0 ? `${d.tepat_waktu !== undefined ? d.tepat_waktu : (d.h||0)} Tepat Waktu, ${d.terlambat} Terlambat` : 'Semua Tepat Waktu',
                'Jumlah Terlambat': d.terlambat || 0
            }));
            const ws = XLSX.utils.json_to_sheet(exportData);
            ws['!cols'] = [{wch:25},{wch:15},{wch:15},{wch:12},{wch:14},{wch:12},{wch:10},{wch:10},{wch:10},{wch:30},{wch:18}];
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Absensi Terpilih');
            const tgl = new Date().toLocaleDateString('id-ID').replace(/\//g,'-');
            XLSX.writeFile(wb, `Absensi_Terpilih_${ids.length}siswa_${tgl}.xlsx`);
        }

        // Bulk cabut akses API Key
        async function bulkCabutApiKey() {
            const ids = getCheckedIds(); if (ids.length === 0) return;
            const conf = await Swal.fire({ title: `Cabut akses ${ids.length} API Key?`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#d97706', confirmButtonText: 'Ya, Cabut', cancelButtonText: 'Batal' });
            if (!conf.isConfirmed) return;
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            let ok = 0;
            for (const id of ids) {
                try { await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'api_keys', id, payload: { status: 'Dicabut' } } }); ok++; } catch(e) {}
            }
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${ok} API Key dicabut`, showConfirmButton: false, timer: 2000 });
            batalPilihan(); loadDataTable('api_keys', 'apikey');
        }


        // ============================================================
        // FITUR 14 — Reminder laporan pending
        // ============================================================
        async function cabutSatuApiKey(id) {
            const conf = await Swal.fire({ title: 'Hentikan API Key?', text: 'API Key tidak bisa digunakan sementara. Bisa diaktifkan kembali kapan saja.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d97706', confirmButtonText: 'Hentikan', cancelButtonText: 'Batal' });
            if (!conf.isConfirmed) return;
            try {
                await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'api_keys', id, payload: { status: 'Dicabut' } }});
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'API Key dihentikan', showConfirmButton: false, timer: 2000 });
                loadDataTable('api_keys', 'apikey');
            } catch(e) { Swal.fire('Gagal', e.message, 'error'); }
        }

        async function aktifkanSatuApiKey(id) {
            const conf = await Swal.fire({ title: 'Aktifkan Kembali API Key?', text: 'API Key akan bisa digunakan kembali.', icon: 'question', showCancelButton: true, confirmButtonColor: '#22c55e', confirmButtonText: 'Aktifkan', cancelButtonText: 'Batal' });
            if (!conf.isConfirmed) return;
            try {
                await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'api_keys', id, payload: { status: 'Aktif' } }});
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'API Key diaktifkan kembali!', showConfirmButton: false, timer: 2000 });
                loadDataTable('api_keys', 'apikey');
            } catch(e) { Swal.fire('Gagal', e.message, 'error'); }
        }

        async function bulkAktifkanApiKey() {
            const ids = getCheckedIds(); if (!ids.length) return;
            const conf = await Swal.fire({ title: `Aktifkan ${ids.length} API Key?`, icon: 'question', showCancelButton: true, confirmButtonColor: '#22c55e', confirmButtonText: 'Aktifkan', cancelButtonText: 'Batal' });
            if (!conf.isConfirmed) return;
            try {
                for (const id of ids) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'api_keys', id, payload: { status: 'Aktif' } }});
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${ids.length} API Key diaktifkan`, showConfirmButton: false, timer: 2000 });
                loadDataTable('api_keys', 'apikey');
            } catch(e) { Swal.fire('Gagal', e.message, 'error'); }
        }


        async function jalankanReminder() {
            const btn = document.getElementById('btnReminder');
            const oH = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin">
</i> Mengirim...';
            btn.disabled = true;
            try {
                const res = await callBackend({ type: 'supabase_post', action: 'checkReminder', payload: { hari: 3 } });
                const checked = res.data?.checked || 0;
                if (checked === 0) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Tidak ada laporan pending > 3 hari', showConfirmButton: false, timer: 2500 });
                } else {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `Reminder terkirim! ${checked} laporan pending`, showConfirmButton: false, timer: 2500 });
                }
            } catch(e) {
                Swal.fire('Gagal', e.message, 'error');
            } finally {
                btn.innerHTML = oH;
                btn.disabled = false;
            }
        }

        // ============================================================
        // FITUR 11 — Laporan berkala
        // ============================================================
        async function jalankanLaporanBerkala() {
            const btn = document.getElementById('btnBerkala');
            const oH = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin">
</i> Mengirim...';
            btn.disabled = true;
            try {
                const res = await callBackend({ type: 'supabase_post', action: 'laporanBerkala', payload: {} });
                const d = res.data || {};
                Swal.fire({
                    icon: 'success',
                    title: 'Laporan Berkala Terkirim! 📊',
                    html: `<p class="text-sm text-gray-600">Bulan: <b>${d.bulan || '-'}</b>
<br>Total: <b>${d.total || 0}</b> pengajuan<br>Selesai: <b>${d.selesai || 0}</b> | Pending: <b>${d.pending || 0}</b>
</p>`,
                    confirmButtonColor: '#7c3aed'
                });
            } catch(e) {
                Swal.fire('Gagal', e.message, 'error');
            } finally {
                btn.innerHTML = oH;
                btn.disabled = false;
            }
        }


        // ============================================================
        // FITUR 2 — Notifikasi realtime di admin (polling aspirasi baru)
        // ============================================================
        let adminLastCheck = null;
        let adminNotifCount = 0;

        async function startAdminPolling() {
            // Cek setiap 30 detik apakah ada laporan baru
            setInterval(async () => {
                try {
                    const res = await callBackend({ type: 'supabase_get', action: 'getAllAspirasi' });
                    const data = res.data || [];
                    const pending = data.filter(d => d.status === 'Menunggu');
                    const now = new Date();

                    // Cek yang baru masuk dalam 30 detik terakhir
                    if (adminLastCheck) {
                        const baru = pending.filter(d => new Date(d.created_at) > adminLastCheck);
                        if (baru.length > 0) {
                            baru.forEach(d => {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'info',
                                    title: `📨 Laporan Baru!`,
                                    text: `${d.tipe} dari ${d.pengirim_nama} (${d.pengirim_kelas})`,
                                    showConfirmButton: false,
                                    timer: 5000,
                                    timerProgressBar: true
                                });
                            });
                            // Update stat cards jika di dashboard
                            if (activeView === 'dashboard') refreshDashboardStats();
                        }
                    }
                    adminLastCheck = now;
                } catch(e) {}
            }, 30000);

            // Set waktu awal
            adminLastCheck = new Date();
        }

        // ============================================================
        // FITUR 15 — Dark Mode admin
        // ============================================================
        function toggleDarkModeAdmin() {
            const isDark = document.body.classList.toggle('dark-mode');
            localStorage.setItem('admin_dark_mode', isDark ? '1' : '0');
            const icon = document.getElementById('iconDarkAdmin');
            if (icon) icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
        }

        function initDarkModeAdmin() {
            if (localStorage.getItem('admin_dark_mode') === '1') {
                document.body.classList.add('dark-mode');
                const icon = document.getElementById('iconDarkAdmin');
                if (icon) icon.className = 'fas fa-sun';
            }
        }

        initDarkModeAdmin();


        // ============================================================
        // FITUR QR SISWA — Lihat, Unduh 1, Unduh Semua (ZIP)
        // ============================================================

        let currentQRSiswa = null; // Simpan data siswa yang sedang ditampilkan QR-nya

        // Helper: generate QR card di canvas (nama di atas, QR di bawah)
        function buatQRCard(canvas, siswa) {
            return new Promise((resolve, reject) => {
                const nama   = siswa.nama || 'TANPA NAMA';
                const nisn   = siswa.nisn || '-';
                const kelas  = siswa.kelas || '-';
                const qrData = siswa.nisn || siswa.id;

                // Ukuran kartu: 400 x 500 px
                const W = 400, H = 520;
                canvas.width  = W;
                canvas.height = H;
                const ctx = canvas.getContext('2d');

                // Background putih
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, W, H);

                // Strip biru atas
                ctx.fillStyle = '#2563eb';
                ctx.fillRect(0, 0, W, 70);

                // Teks header
                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 13px sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText(String(window.NAMA_WEB||'E-ASPIRASI GRISAWANGI').toUpperCase(), W/2, 28);
                ctx.font = '11px sans-serif';
                ctx.fillText('SMK PGRI 1 Giri — Kartu Pelajar Digital', W/2, 50);

                // Nama siswa
                ctx.fillStyle = '#1e293b';
                ctx.font = 'bold 20px sans-serif';
                ctx.textAlign = 'center';
                // Wrap nama panjang
                const maxW = W - 40;
                const words = nama.toUpperCase().split(' ');
                let line = '', y = 110;
                words.forEach(word => {
                    const test = line + word + ' ';
                    if (ctx.measureText(test).width > maxW && line !== '') {
                        ctx.fillText(line.trim(), W/2, y); y += 26; line = word + ' ';
                    } else { line = test; }
                });
                ctx.fillText(line.trim(), W/2, y); y += 10;

                // Kelas & NISN
                ctx.font = '13px sans-serif';
                ctx.fillStyle = '#64748b';
                ctx.fillText(`Kelas ${kelas}`, W/2, y + 22);
                ctx.fillText(`NISN: ${nisn}`, W/2, y + 42);

                // Load QR dari API
                const qrUrl : `{{ env('QR_URL') }}`;
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.onload = () => {
                    // QR di tengah bawah
                    const qrX = (W - 220) / 2;
                    const qrY = y + 60;
                    ctx.drawImage(img, qrX, qrY, 220, 220);

                    // Border tipis sekitar QR (kompatibel semua browser)
                    ctx.strokeStyle = '#e2e8f0';
                    ctx.lineWidth = 2;
                    const rx = qrX - 8, ry = qrY - 8, rw = 236, rh2 = 236, rr = 12;
                    ctx.beginPath();
                    ctx.moveTo(rx + rr, ry);
                    ctx.lineTo(rx + rw - rr, ry);
                    ctx.arcTo(rx + rw, ry, rx + rw, ry + rr, rr);
                    ctx.lineTo(rx + rw, ry + rh2 - rr);
                    ctx.arcTo(rx + rw, ry + rh2, rx + rw - rr, ry + rh2, rr);
                    ctx.lineTo(rx + rr, ry + rh2);
                    ctx.arcTo(rx, ry + rh2, rx, ry + rh2 - rr, rr);
                    ctx.lineTo(rx, ry + rr);
                    ctx.arcTo(rx, ry, rx + rr, ry, rr);
                    ctx.closePath();
                    ctx.stroke();

                    // Footer
                    ctx.fillStyle = '#94a3b8';
                    ctx.font = '10px sans-serif';
                    ctx.fillText('Scan untuk verifikasi identitas siswa', W/2, H - 15);

                    resolve(canvas);
                };
                img.onerror = () => reject(new Error('Gagal load QR image'));
                img.src = qrUrl;
            });
        }

        // Lihat QR satu siswa
        async function lihatQRSiswa(id) {
            const siswa = globalDataStore['siswa']?.find(s => s.id === id);
            if (!siswa) return Swal.fire('Error', 'Data siswa tidak ditemukan', 'error');
            if (!siswa.nisn) return Swal.fire('NISN Kosong', `${siswa.nama} belum punya NISN di database.`, 'warning');

            currentQRSiswa = siswa;
            document.getElementById('qrSiswaSubtitle').textContent = `${siswa.nama} — ${siswa.kelas}`;
            document.getElementById('modalQRSiswa').classList.remove('hidden');

            const canvas = document.getElementById('qrCardCanvas');
            try {
                await buatQRCard(canvas, siswa);
            } catch(e) {
                Swal.fire('Error', 'Gagal generate QR: ' + e.message, 'error');
            }
        }

        function tutupModalQRSiswa() {
            document.getElementById('modalQRSiswa').classList.add('hidden');
            currentQRSiswa = null;
        }

        // Unduh QR satu siswa sebagai PNG
        function unduhQRSiswaCanvas() {
            if (!currentQRSiswa) return;
            const canvas = document.getElementById('qrCardCanvas');
            const link = document.createElement('a');
            link.download = `QR_${(currentQRSiswa.nama||'siswa').replace(/\s+/g,'_')}_${currentQRSiswa.kelas||''}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        // Unduh QR semua siswa sekaligus → ZIP berisi PNG per siswa
        async function downloadSemuaQR() {
            const checkedIds = getCheckedIds();
            const allSiswa = globalDataStore['siswa'] || currentFilteredData || [];
            const checked = allSiswa.filter(s => checkedIds.includes(s.id));

            // Selalu tampilkan 2 opsi pilihan
            const { value: pilihan } = await Swal.fire({
                title: '<i class="fas fa-qrcode text-purple-600 mr-2">
</i>Unduh QR Code',
                html: `
                    <p class="text-gray-500 text-sm mb-4">Pilih data yang ingin diunduh QR-nya:</p>
<div class="space-y-3">
    <button id="pil1" onclick="Swal.clickConfirm()" data-val="pilihan"
                            class="w-full flex items-center gap-3 bg-purple-600 hover:bg-purple-700 text-white font-bold px-5 py-3.5 rounded-xl transition text-sm">
        <i class="fas fa-check-square text-lg">
        </i>
        <div class="text-left">
            <p>Yang Dicentang Saja</p>
            <p class="text-purple-200 text-xs font-normal">${checked.length > 0 ? checked.length + ' siswa dipilih' : 'Belum ada yang dicentang'}</p>
        </div>
    </button>
    <button id="pil2" onclick="document.getElementById('_qrPilSemua').value='semua'; Swal.clickConfirm()"
                            class="w-full flex items-center gap-3 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold px-5 py-3.5 rounded-xl transition text-sm border-2 border-gray-200">
        <i class="fas fa-users text-lg text-gray-600">
        </i>
        <div class="text-left">
            <p>Semua Siswa</p>
            <p class="text-gray-500 text-xs font-normal">${allSiswa.length} total siswa</p>
        </div>
    </button>
    <input type="hidden" id="_qrPilSemua" value="pilihan">
</div>`,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Batal',
                cancelButtonColor: '#6b7280',
                didOpen: () => {
                    document.getElementById('pil1').addEventListener('click', () => {
                        document.getElementById('_qrPilSemua').value = 'pilihan';
                        Swal.clickConfirm();
                    });
                },
                preConfirm: () => document.getElementById('_qrPilSemua')?.value || 'pilihan'
            });

            if (!pilihan) return; // Batal

            const isSemua = (document.getElementById('_qrPilSemua')?.value === 'semua') || pilihan === 'semua';
            let targetSiswa = isSemua ? allSiswa : checked;

            if (!targetSiswa || targetSiswa.length === 0) {
                return Swal.fire('Tidak Ada Data',
                    isSemua ? 'Belum ada data siswa.' : 'Tidak ada siswa yang dicentang. Centang dulu dari tabel.',
                    'info');
            }

            const punya = targetSiswa.filter(s => s.nisn && s.nisn.trim() !== '');
            if (punya.length === 0) {
                return Swal.fire('NISN Kosong', 'Siswa yang dipilih tidak memiliki NISN. QR tidak bisa digenerate.', 'warning');
            }

            const konfirm = await Swal.fire({
                title: `Unduh ${punya.length} QR Code`,
                html: `<p class="text-gray-600 text-sm">Akan mengunduh <b>${punya.length} QR</b> dalam format ZIP.<br>Proses ini mungkin memakan waktu beberapa saat.</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#7c3aed',
                confirmButtonText: `<i class="fas fa-download mr-1">
</i> Mulai Unduh`,
                cancelButtonText: 'Batal'
            });
            if (!konfirm.isConfirmed) return;

            Swal.fire({
                title: 'Membuat QR Code...',
                html: `<p id="qrProgress">Menyiapkan... 0/${punya.length}</p>`,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const zip = new JSZip();
                for (let i = 0; i < punya.length; i++) {
                    const s = punya[i];
                    const el = document.getElementById('qrProgress');
                    if (el) el.textContent = `${i+1} / ${punya.length} — ${s.nama || s.nisn}`;
                    const qrUrl : `{{ env('QR_URL_5') }}`;
                    try {
                        const res = await fetch(qrUrl);
                        const blob = await res.blob();
                        const safeName = (s.nama || s.nisn).replace(/[^a-zA-Z0-9 ]/g, '').trim().replace(/ +/g, '_');
                        const kelas = (s.kelas || '').replace(/[^a-zA-Z0-9]/g, '');
                        zip.file(`${kelas}_${safeName}_${s.nisn}.png`, blob);
                    } catch(e) { console.warn('QR skip:', s.nama, e.message); }
                }
                const zipBlob = await zip.generateAsync({ type: 'blob' });
                const a = document.createElement('a');
                a.href = URL.createObjectURL(zipBlob);
                const tgl = new Date().toLocaleDateString('id-ID').replace(/\//g, '-');
                a.download = `QR_Siswa_${isSemua ? 'Semua' : 'Pilihan'}_${punya.length}_${tgl}.zip`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                Swal.fire({
                    icon: 'success',
                    title: 'QR Berhasil Diunduh!',
                    html: `<p>${punya.length} QR Code berhasil diunduh dalam file ZIP.</p>`,
                    confirmButtonColor: '#7c3aed'
                });
            } catch(e) {
                Swal.fire('Gagal', 'Gagal membuat ZIP: ' + e.message, 'error');
            }
        }

        async function muatStatistikPenilaian() {
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getData', payload: { table: 'penilaian?select=*&order=created_at.desc' } });
                const data = res.data || [];
                const total = data.length;
                const avg = total > 0 ? (data.reduce((s,d) => s + (d.bintang||0), 0) / total).toFixed(1) : '-';
                const positif = data.filter(d => d.bintang >= 4).length;
                const negatif = data.filter(d => d.bintang <= 2).length;

                document.getElementById('statAvgBintang').textContent = avg;
                document.getElementById('statTotalPenilaian').textContent = total;
                document.getElementById('statPositif').textContent = positif;
                document.getElementById('statNegatif').textContent = negatif;

                try { // Chart distribusi bintang
                const dist = [1,2,3,4,5].map(b => data.filter(d => d.bintang === b).length);
                if (chartPenilaianInst) chartPenilaianInst.destroy();
                const ctx = document.getElementById('chartPenilaianDist');
                if (ctx) chartPenilaianInst = new Chart(ctx, {
                    type: 'bar',
                    data: { labels: ['⭐ 1','⭐⭐ 2','⭐⭐⭐ 3','⭐⭐⭐⭐ 4','⭐⭐⭐⭐⭐ 5'], datasets: [{ data: dist, backgroundColor: ['#ef4444','#f97316','#eab308','#84cc16','#22c55e'], borderRadius: 8, borderWidth: 0 }] },
                    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                });

                } catch(e1) { console.error(e1); }
                try { // Komentar terbaru
                const komen = data.filter(d => d.komentar && d.komentar.trim() !== '').slice(0, 10);
                const listEl = document.getElementById('listKomentar');
                if (listEl) {
                    listEl.innerHTML = komen.length === 0
                        ? '<p class="text-gray-400 text-sm text-center py-4">Belum ada komentar</p>'
                        : komen.map(d => `
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
<div class="flex justify-between items-start mb-1">
    <p class="font-bold text-gray-800 text-xs">${d.nama_penilai||'Anonim'} <span class="text-gray-400 font-normal">— ${d.kelas_penilai||'-'}</span>
</p>
<span class="text-yellow-400 text-[10px]">${'⭐'.repeat(d.bintang||0)}</span>
</div>
<p class="text-xs text-gray-600 italic">"${d.komentar}"</p>
</div>`).join('');
                }

                } catch(e2) { console.error(e2); }
                try { // Tabel semua penilaian
                const tbody = document.getElementById('tablePenilaian');
                if (tbody) {
                    tbody.innerHTML = data.map(d => `<tr class="hover:bg-gray-50 transition">
<td class="px-4 py-3 text-xs text-gray-500">${new Date(d.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'})}</td>
<td class="px-4 py-3 font-bold text-gray-800 text-sm">${d.nama_penilai||'Anonim'}</td>
<td class="px-4 py-3 text-sm text-gray-600">${d.kelas_penilai||'-'}</td>
<td class="px-4 py-3">
    <span class="text-yellow-400">${'⭐'.repeat(d.bintang||0)}</span>
    <span class="text-gray-400 text-xs ml-1">(${d.bintang})</span>
</td>
<td class="px-4 py-3 text-xs text-gray-600 max-w-xs truncate">${d.komentar||'—'}</td>
</tr>`).join('');
                }
            } catch(e3) { console.error(e3); }
            } catch(e) { console.error('Statistik penilaian error:', e); }
        }

        async function exportPenilaianExcel() {
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getData', payload: { table: 'penilaian?select=*&order=created_at.desc' } });
                const data = (res.data || []).map(d => ({
                    'Waktu': new Date(d.created_at).toLocaleString('id-ID'),
                    'Nama': d.nama_penilai||'Anonim',
                    'Kelas': d.kelas_penilai||'-',
                    'Bintang': d.bintang,
                    'Komentar': d.komentar||''
                }));
                const ws = XLSX.utils.json_to_sheet(data);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Penilaian');
                XLSX.writeFile(wb, 'Statistik_Penilaian.xlsx');
            } catch(e) { Swal.fire('Error', e.message, 'error'); }
        }

        // ============================================================
        // FITUR 6 — Pengumuman
        // ============================================================
        async function muatListPengumuman() {
            const listEl = document.getElementById('listPengumuman');
            if (!listEl) return;
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getData', payload: { table: 'pengumuman?select=*&order=created_at.desc&limit=20' } });
                const data = res.data || [];
                if (data.length === 0) { listEl.innerHTML = '<p class="text-gray-400 text-sm text-center py-8">
<i class="fas fa-bullhorn text-4xl block mb-3 text-gray-200">
</i>Belum ada pengumuman</p>'; return; }
                const tipeColor = { info: 'bg-blue-50 border-blue-200 text-blue-700', penting: 'bg-yellow-50 border-yellow-200 text-yellow-700', darurat: 'bg-red-50 border-red-200 text-red-700' };
                const tipeIcon  = { info: 'fa-info-circle', penting: 'fa-exclamation-triangle', darurat: 'fa-exclamation-circle' };
                listEl.innerHTML = data.map(p => `
                    <div class="bg-white border border-gray-100 rounded-2xl p-4 flex items-start gap-4 shadow-sm">
<div class="w-10 h-10 ${tipeColor[p.tipe]||'bg-gray-50 border-gray-200 text-gray-600'} rounded-xl flex items-center justify-center flex-shrink-0 border">
    <i class="fas ${tipeIcon[p.tipe]||'fa-info-circle'}">
    </i>
</div>
<div class="flex-1 min-w-0">
    <div class="flex items-start justify-between gap-2">
        <p class="font-black text-gray-800 text-sm">${p.judul}</p>
        <button onclick="hapusPengumuman('${p.id}')" class="text-red-400 hover:text-red-600 flex-shrink-0 p-1 hover:bg-red-50 rounded-lg transition">
            <i class="fas fa-trash text-xs">
            </i>
        </button>
    </div>
    <p class="text-xs text-gray-600 mt-1 leading-relaxed">${p.isi}</p>
    <p class="text-[10px] text-gray-400 mt-2 font-bold">Oleh: ${p.pembuat||'Admin'} · ${new Date(p.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'})}</p>
</div>
</div>`).join('');
            } catch(e) { listEl.innerHTML = '<p class="text-red-500 text-sm text-center py-4">Gagal memuat: ' + e.message + '</p>'; }
        }

        async function simpanPengumuman() {
            const judul = document.getElementById('inputJudulPengumuman').value.trim();
            const isi   = document.getElementById('inputIsiPengumuman').value.trim();
            const tipe  = document.getElementById('inputTipePengumuman').value;
            const kirimWA = document.getElementById('inputKirimWA').value;
            if (!judul || !isi) return Swal.fire('Error', 'Judul dan isi wajib diisi!', 'warning');
            const konfirmPengumuman = await Swal.fire({ title: 'Publikasikan Pengumuman?', text: 'Pengumuman akan tampil di beranda semua siswa.', icon: 'question', showCancelButton: true, confirmButtonColor: '#2563eb', confirmButtonText: 'Iya, Publikasikan', cancelButtonText: 'Batal' });
            if (!konfirmPengumuman.isConfirmed) return;
            const btn = document.getElementById('btnSimpanPengumuman');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin">
</i> Menyimpan...'; btn.disabled = true;
            try {
                await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'pengumuman', payload: { judul, isi, tipe, pembuat: userData.nama_admin || userData.nama_lengkap || 'Admin', created_at: new Date().toISOString() } } });
                if (kirimWA === 'ya') {
                    await callBackend({ type: 'supabase_post', action: 'kirimPengumumanWA', payload: { judul, isi, tipe } });
                }
                document.getElementById('inputJudulPengumuman').value = '';
                document.getElementById('inputIsiPengumuman').value = '';
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Pengumuman dipublikasikan!', showConfirmButton: false, timer: 2000 });
                muatListPengumuman();
            } catch(e) { Swal.fire('Error', e.message, 'error'); }
            finally { btn.innerHTML = '<i class="fas fa-paper-plane">
</i> Publikasikan Pengumuman'; btn.disabled = false; }
        }

        async function hapusPengumuman(id) {
            const conf = await Swal.fire({ title: 'Hapus pengumuman?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Hapus' });
            if (!conf.isConfirmed) return;
            try {
                await callBackend({ type: 'supabase_post', action: 'deleteData', payload: { table: 'pengumuman', id } });
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Pengumuman dihapus', showConfirmButton: false, timer: 1500 });
                muatListPengumuman();
            } catch(e) { Swal.fire('Error', e.message, 'error'); }
        }


        // FITUR 17 — Backup
        async function jalankanBackup() {
            const btn = document.getElementById('btnBackup');
            const oH = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin">
</i> Memproses...'; btn.disabled = true;
            try {
                const res = await callBackend({ type: 'supabase_post', action: 'backupData', payload: {} });
                Swal.fire({ icon: 'success', title: 'Info Backup Terkirim! 🗄️', html: `<p class="text-sm text-gray-600">${(res.data?.tables||[]).join('<br>')}<br>
<br>Total: <b>${res.data?.totalRows||0} baris</b>
</p>`, confirmButtonColor: '#374151' });
            } catch(e) { Swal.fire('Error', e.message, 'error'); }
            finally { btn.innerHTML = oH; btn.disabled = false; }
        }

        // FITUR 23 — Sync Google Sheets
        async function jalankanSyncSheets() {
            const btn = document.getElementById('btnSyncSheets');
            const oH = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin">
</i> Mensinkronkan...'; btn.disabled = true;
            try {
                const res = await callBackend({ type: 'supabase_post', action: 'syncGoogleSheets', payload: {} });
                if (res.data?.error) throw new Error(res.data.error);
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${res.data?.synced||0} baris tersinkronkan ke Google Sheets`, showConfirmButton: false, timer: 2500 });
            } catch(e) { Swal.fire('Error', e.message, 'error'); }
            finally { btn.innerHTML = oH; btn.disabled = false; }
        }


        // ============================================================
        // FITUR 3 & 7 — Balas & Thread per laporan
        // ============================================================
        async function muatBalasan(idAspirasi) {
            const el = document.getElementById('threadBalasan');
            if (!el) return;
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getData', payload: { table: `balasan_laporan?id_aspirasi=eq.${idAspirasi}&order=created_at.asc` } });
                const data = res.data || [];
                if (data.length === 0) { el.innerHTML = '<p class="text-xs text-gray-400 italic text-center py-2">Belum ada balasan...</p>'; return; }
                el.innerHTML = data.map(b => `
                    <div class="bg-blue-50 border border-blue-100 rounded-xl px-3 py-2">
<div class="flex items-center justify-between mb-1">
    <p class="text-[10px] font-black text-blue-700 uppercase tracking-widest">${b.nama_admin||'Admin'}</p>
    <p class="text-[9px] text-gray-400">${new Date(b.created_at).toLocaleString('id-ID',{day:'2-digit',month:'short',hour:'2-digit',minute:'2-digit'})}</p>
</div>
<p class="text-xs text-gray-700 font-medium">${b.isi_balasan}</p>
</div>`).join('');
                el.scrollTop = el.scrollHeight;
            } catch(e) { el.innerHTML = '<p class="text-xs text-red-400 text-center">Gagal memuat balasan</p>'; }
        }

        async function kirimBalasanAdmin() {
            const id = document.getElementById('updateIdAspirasiVal').value;
            const isi = document.getElementById('inputBalasanAdmin').value.trim();
            if (!isi) return;
            // Cari data aspirasi untuk dapat nomor WA siswa (fallback ke globalDataStore)
            const asp = currentFilteredData.find(d => d.id === id)
                || (globalDataStore['laporan']||[]).concat(globalDataStore['masukan']||[], globalDataStore['konselor']||[]).find(d => d.id === id);
            try {
                await callBackend({ type: 'supabase_post', action: 'insertBalasan', payload: {
                    id_aspirasi: id,
                    isi_balasan: isi,
                    nama_admin: userData.nama_admin || userData.nama_lengkap || 'Admin',
                    pengirim_wa: asp?.pengirim_wa || ''
                }});
                document.getElementById('inputBalasanAdmin').value = '';
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Balasan terkirim ke siswa!', showConfirmButton: false, timer: 1500 });
                await muatBalasan(id);
            } catch(e) { Swal.fire('Error', e.message, 'error'); }
        }

        // Override bukaDetailAspirasi untuk load balasan sekalian
        const _origBukaDetail = bukaDetailAspirasi;
        bukaDetailAspirasi = async function(id) {
            _origBukaDetail(id);
            // Reset input balasan
            const inputEl = document.getElementById('inputBalasanAdmin');
            if (inputEl) inputEl.value = '';
            // Load balasan
            setTimeout(() => muatBalasan(id), 100);
        };

        // ============================================================
        // FITUR 4 — Export Laporan ke PDF
        // ============================================================
        async function exportLaporanPDF(viewId) {
            const data = globalDataStore[viewId] || currentFilteredData;
            if (!data || data.length === 0) return Swal.fire('Kosong', 'Tidak ada data', 'info');

            const judulMap = { laporan: 'Laporan Aspirasi', masukan: 'Kotak Masukan', konselor: 'Jadwal Konselor' };
            const judul = judulMap[viewId] || 'Data';
            const tgl = new Date().toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });

            // Build HTML untuk dicetak
            const rows = data.map((d,i) => `
                <tr>
<td>${i+1}</td>
<td>${new Date(d.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'})}</td>
<td>${d.pengirim_nama||'-'}</td>
<td>${d.pengirim_kelas||'-'}</td>
<td>${d.kategori||'-'}</td>
<td style="max-width:200px;word-break:break-word">${(d.detail_pesan||'').substring(0,100)}${d.detail_pesan?.length>100?'...':''}</td>
<td>
    <span style="padding:2px 8px;border-radius:10px;font-size:10px;background:${d.status==='Selesai'?'#dcfce7':d.status==='Ditolak'?'#fee2e2':d.status==='Sedang Diproses'?'#dbeafe':'#ffedd5'};color:${d.status==='Selesai'?'#166534':d.status==='Ditolak'?'#991b1b':d.status==='Sedang Diproses'?'#1e40af':'#9a3412'}">${d.status}</span>
</td>
</tr>`).join('');

            const html = `<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>${judul} - ${window.NAMA_WEB||'E-Aspirasi Grisawangi'}</title>
        <style>
                body{font-family:Arial,sans-serif;font-size:11px;color:#1f2937;margin:20px}
                h1{font-size:16px;margin:0}h2{font-size:12px;color:#6b7280;margin:4px 0 16px}
                table{width:100%;border-collapse:collapse}
                th{background:#1e40af;color:white;padding:8px 6px;text-align:left;font-size:10px;text-transform:uppercase;letter-spacing:0.05em}
                td{padding:6px;border-bottom:1px solid #f3f4f6;vertical-align:top}
                tr:nth-child(even){background:#f9fafb}
                .footer{margin-top:16px;font-size:10px;color:#9ca3af;text-align:center}
                @media print{@page{margin:1cm}}
            </style>
    </head>
    <body>
        <h1>📋 ${judul}</h1>
        <h2>${window.NAMA_WEB||'E-Aspirasi Grisawangi'} — SMK PGRI 1 Giri · Dicetak: ${tgl} · Total: ${data.length} data</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Kategori</th>
                    <th>Isi Pesan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>${rows}</tbody>
        </table>
        <div class="footer">${window.NAMA_WEB||'E-Aspirasi Grisawangi'} — SMK PGRI 1 Giri · Dicetak oleh: ${userData.nama_admin||userData.nama_lengkap||'Admin'}</div>
    </body>
</html>`;

            // Buka jendela print
            const win = window.open('', '_blank');
            win.document.write(html);
            win.document.close();
            win.focus();
            setTimeout(() => { win.print(); }, 500);
        }


        // ============================================================
        // FITUR 4 — Export Laporan ke PDF
        // ============================================================
        


        // bukaModalBalasan — alias shortcut untuk buka detail + fokus ke input balasan
        


        // ============================================================
        // FITUR 3 & 7 — Balas laporan dari admin
        // ============================================================
        let currentBalasanData = {};

        function bukaModalBalasan(idAspirasi, namaPengirim, waPengirim) {
            currentBalasanData = { id_aspirasi: idAspirasi, pengirim_nama: namaPengirim, pengirim_wa: waPengirim };
            Swal.fire({
                title: `Balas ke ${namaPengirim}`,
                html: `
                    <div class="text-left space-y-3">
<div>
    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Isi Balasan / Catatan Admin</label>
    <!-- Template Balasan -->
    <div class="mb-2">
        <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1.5">⚡ Template Cepat</p>
        <div class="flex flex-wrap gap-1.5">
            <button type="button" onclick="pakaiTemplate(1)" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-[10px] font-bold border border-blue-200 transition">✅ Diterima</button>
            <button type="button" onclick="pakaiTemplate(2)" class="px-2.5 py-1 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg text-[10px] font-bold border border-green-200 transition">🔄 Sedang Diproses</button>
            <button type="button" onclick="pakaiTemplate(3)" class="px-2.5 py-1 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 rounded-lg text-[10px] font-bold border border-yellow-200 transition">📅 Jadwal Konseling</button>
            <button type="button" onclick="pakaiTemplate(4)" class="px-2.5 py-1 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg text-[10px] font-bold border border-purple-200 transition">📩 Perlu Konfirmasi</button>
            <button type="button" onclick="pakaiTemplate(5)" class="px-2.5 py-1 bg-orange-50 hover:bg-orange-100 text-orange-700 rounded-lg text-[10px] font-bold border border-orange-200 transition">🙏 Terimakasih</button>
            <button type="button" onclick="pakaiTemplate(6)" class="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-[10px] font-bold border border-red-200 transition">❌ Tidak Dapat Diproses</button>
        </div>
    </div>
    <textarea id="inputBalasan" rows="4" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-medium text-gray-800 text-sm resize-none" placeholder="Tulis balasan atau catatan untuk siswa... atau pilih template di atas">
    </textarea>
</div>
<p class="text-[11px] text-gray-400">
    <i class="fas fa-info-circle mr-1 text-blue-400">
    </i>Balasan akan dikirim via WA ke ${waPengirim||'nomor siswa'} jika nomor tersedia.</p>
</div>`,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-paper-plane mr-1">
</i> Kirim Balasan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb',
                preConfirm: () => {
                    const isi = document.getElementById('inputBalasan')?.value?.trim();
                    if (!isi) { Swal.showValidationMessage('Isi balasan tidak boleh kosong!'); return false; }
                    return isi;
                }
            }).then(async result => {
                if (!result.isConfirmed) return;
                Swal.fire({ title: 'Mengirim...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                try {
                    await callBackend({
                        type: 'supabase_post',
                        action: 'insertBalasan',
                        payload: {
                            id_aspirasi: currentBalasanData.id_aspirasi,
                            isi_balasan: result.value,
                            nama_admin: userData.nama_admin || userData.nama_lengkap || 'Admin',
                            pengirim_wa: currentBalasanData.pengirim_wa || ''
                        }
                    });
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Balasan terkirim!', showConfirmButton: false, timer: 2000 });
                } catch(e) { Swal.fire('Error', e.message, 'error'); }
            });
        }

        // ============================================================
        // FITUR 4 — Export PDF laporan
        // ============================================================
        async function exportToPDF(viewId) {
            const data = globalDataStore[viewId];
            if (!data || data.length === 0) return Swal.fire('Kosong', 'Tidak ada data untuk diekspor.', 'info');

            Swal.fire({ title: 'Membuat PDF...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
                const label = MENU_CONFIG.find(m => m.id === viewId)?.label || viewId;
                const tgl = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });

                // Header
                doc.setFillColor(37, 99, 235);
                doc.rect(0, 0, 210, 28, 'F');
                doc.setTextColor(255, 255, 255);
                doc.setFontSize(14); doc.setFont('helvetica', 'bold');
                doc.text(String(window.NAMA_WEB||'E-ASPIRASI GRISAWANGI').toUpperCase(), 14, 11);
                doc.setFontSize(9); doc.setFont('helvetica', 'normal');
                doc.text('SMK PGRI 1 Giri — Banyuwangi', 14, 18);
                doc.text(`Dicetak: ${tgl}`, 14, 24);

                // Judul
                doc.setTextColor(30, 41, 59);
                doc.setFontSize(12); doc.setFont('helvetica', 'bold');
                doc.text(`Laporan: ${label}`, 14, 38);
                doc.setFontSize(9); doc.setFont('helvetica', 'normal');
                doc.setTextColor(100, 116, 139);
                doc.text(`Total data: ${data.length} tiket`, 14, 45);

                // Garis
                doc.setDrawColor(226, 232, 240);
                doc.line(14, 48, 196, 48);

                // Data rows
                let y = 56;
                doc.setTextColor(30, 41, 59);
                data.forEach((item, i) => {
                    if (y > 270) { doc.addPage(); y = 20; }
                    const tglItem = new Date(item.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                    // Nomor
                    doc.setFont('helvetica', 'bold'); doc.setFontSize(8);
                    doc.setTextColor(37, 99, 235);
                    doc.text(`#${String(i+1).padStart(3,'0')}`, 14, y);
                    // Nama & kelas
                    doc.setTextColor(30, 41, 59);
                    doc.text(`${item.pengirim_nama} — ${item.pengirim_kelas}`, 26, y);
                    // Tanggal & status
                    doc.setFont('helvetica', 'normal'); doc.setTextColor(100, 116, 139);
                    doc.text(`${tglItem} | ${item.tipe} — ${item.kategori}`, 26, y + 4);
                    // Status badge
                    const stColor = item.status === 'Selesai' ? [34, 197, 94] : item.status === 'Ditolak' ? [239, 68, 68] : item.status === 'Sedang Diproses' ? [59, 130, 246] : [249, 115, 22];
                    doc.setFillColor(...stColor);
                    doc.roundedRect(170, y - 4, 26, 6, 1.5, 1.5, 'F');
                    doc.setTextColor(255, 255, 255); doc.setFontSize(6.5);
                    doc.text(item.status, 183, y - 0.5, { align: 'center' });
                    // Isi pesan (truncate)
                    doc.setTextColor(71, 85, 105); doc.setFontSize(7.5); doc.setFont('helvetica', 'normal');
                    const msg = (item.detail_pesan || '').substring(0, 90) + ((item.detail_pesan||'').length > 90 ? '...' : '');
                    doc.text(msg, 26, y + 9, { maxWidth: 160 });
                    // Garis pemisah
                    doc.setDrawColor(241, 245, 249);
                    doc.line(14, y + 13, 196, y + 13);
                    y += 18;
                });

                // Footer
                const pageCount = doc.internal.getNumberOfPages();
                for (let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(7); doc.setTextColor(148, 163, 184);
                    doc.text(`${window.NAMA_WEB||'E-Aspirasi Grisawangi'} — Halaman ${i} dari ${pageCount}`, 105, 290, { align: 'center' });
                }

                doc.save(`${label.replace(/\s/g,'_')}_${new Date().toLocaleDateString('id-ID').replace(/\//g,'-')}.pdf`);
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'PDF berhasil dibuat!', showConfirmButton: false, timer: 2000 });
            } catch(e) { Swal.fire('Error', 'Gagal buat PDF: ' + e.message, 'error'); }
        }


        // ============================================================
        // FITUR EDIT BUKU SAKU INLINE
        // ============================================================
        function editBukuSakuInline(id) {
            const item = globalDataStore['buku_saku']?.find(d => d.id === id);
            if (!item) return;
            Swal.fire({
                title: 'Edit Pelanggaran',
                html: `<div class="space-y-3 text-left">
<div>
    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Deskripsi Pelanggaran</label>
    <textarea id="editKelakuan" rows="3" class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm font-medium text-gray-800 resize-none">${item.kelakuan}</textarea>
</div>
<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Kategori</label>
        <select id="editKategori" class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm cursor-pointer">
            <option value="Kedisiplinan" ${item.kategori==='Kedisiplinan'?'selected':''}>Kedisiplinan</option>
            <option value="Kerapian" ${item.kategori==='Kerapian'?'selected':''}>Kerapian</option>
            <option value="Tanggung Jawab" ${item.kategori==='Tanggung Jawab'?'selected':''}>Tanggung Jawab</option>
            <option value="Ketertiban" ${item.kategori==='Ketertiban'?'selected':''}>Ketertiban</option>
            <option value="Lainnya" ${!['Kedisiplinan','Kerapian','Tanggung Jawab','Ketertiban'].includes(item.kategori)?'selected':''}>Lainnya</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Bobot Poin</label>
        <input type="number" id="editPoin" value="${item.point}" min="1" max="200"
                                class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm">
    </div>
</div>
</div>`,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-save mr-1">
</i> Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb',
                preConfirm: () => {
                    const kelakuan = document.getElementById('editKelakuan')?.value?.trim();
                    const kategori = document.getElementById('editKategori')?.value;
                    const point    = parseInt(document.getElementById('editPoin')?.value || 0);
                    if (!kelakuan) { Swal.showValidationMessage('Deskripsi tidak boleh kosong!'); return false; }
                    if (!point || point < 1) { Swal.showValidationMessage('Bobot poin harus minimal 1!'); return false; }
                    return { kelakuan, kategori, point };
                }
            }).then(async result => {
                if (!result.isConfirmed) return;
                Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                try {
                    await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'master_pelanggaran', id, payload: result.value } });
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Pelanggaran diperbarui!', showConfirmButton: false, timer: 2000 });
                    loadDataTable('master_pelanggaran', 'buku_saku');
                } catch(e) { Swal.fire('Error', e.message, 'error'); }
            });
        }

        // Toggle pilih semua checkbox buku saku
        function toggleCheckAllBuku(cb) {
            document.querySelectorAll('.buku-check').forEach(c => c.checked = cb.checked);
            updateBukuCheckCount();
        }

        // Update info jumlah yang dipilih
        function updateBukuCheckCount() {
            const checked = document.querySelectorAll('.buku-check:checked').length;
            const total   = document.querySelectorAll('.buku-check').length;
            // Update header checkbox state
            const cbAll = document.getElementById('checkAllBuku');
            if (cbAll) { cbAll.indeterminate = checked > 0 && checked < total; cbAll.checked = checked === total && total > 0; }
            // Tampilkan/sembunyikan tombol hapus massal
            let bulkBtn = document.getElementById('btnBulkHapusBuku');
            if (!bulkBtn && checked > 0) {
                bulkBtn = document.createElement('button');
                bulkBtn.id = 'btnBulkHapusBuku';
                bulkBtn.className = 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm';
                bulkBtn.innerHTML = '<i class="fas fa-trash-alt">
</i>
<span id="bulkBukuLabel">Hapus 0 Dipilih</span>';
                bulkBtn.onclick = bulkHapusBuku;
                const actionsEl = document.getElementById('dtActionsContainer');
                if (actionsEl) actionsEl.appendChild(bulkBtn);
            }
            if (bulkBtn) {
                const label = document.getElementById('bulkBukuLabel');
                if (label) label.textContent = `Hapus ${checked} Dipilih`;
                bulkBtn.style.display = checked > 0 ? 'flex' : 'none';
            }
        }

        // Hapus massal buku saku
        async function bulkHapusBuku() {
            const ids = Array.from(document.querySelectorAll('.buku-check:checked')).map(c => c.dataset.id);
            if (ids.length === 0) return;
            const conf = await Swal.fire({
                title: `Hapus ${ids.length} pelanggaran?`,
                text: 'Data yang dihapus tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: `Hapus ${ids.length} Data`
            });
            if (!conf.isConfirmed) return;
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            let ok = 0;
            for (const id of ids) {
                try {
                    await callBackend({ type: 'supabase_post', action: 'deleteData', payload: { table: 'master_pelanggaran', id } });
                    ok++;
                } catch(e) { console.warn('Gagal hapus', id); }
            }
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${ok} pelanggaran dihapus`, showConfirmButton: false, timer: 2000 });
            loadDataTable('master_pelanggaran', 'buku_saku');
        }


                // Template balasan cepat
        // ============================================================
        // TUTORIAL API KEY E-ASPIRASI
        // ============================================================
        async function bukaTutorialApiKey() {
            await Swal.fire({
                title: '<i class="fas fa-plug text-indigo-500 mr-2">
</i>Tutorial API Key E-Aspirasi',
                width: '680px',
                html: `
                <div class="text-left text-sm space-y-4 max-h-[70vh] overflow-y-auto pr-2">
<!-- Intro -->
<div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
    <p class="font-bold text-indigo-800 mb-1">📌 Apa itu API Key E-Aspirasi?</p>
    <p class="text-indigo-700 text-xs leading-relaxed">API Key memungkinkan website atau aplikasi lain (misalnya web Grisapoint, website kelas, atau aplikasi absensi) mengakses data E-Aspirasi secara aman dan terkontrol.</p>
</div>
<!-- Step 1 -->
<div>
    <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
        <span class="w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">1</span>
                            Buat API Key
                        </p>
        <p class="text-gray-600 text-xs ml-8 leading-relaxed">Klik tombol <strong>Manual</strong> di atas → isi nama aplikasi → klik Simpan. Sistem akan otomatis generate kode unik seperti:</p>
        <div class="bg-gray-900 rounded-xl p-3 ml-8 mt-2">
            <code class="text-green-400 text-xs font-mono">EA-KEY-ABC123XYZ456</code>
        </div>
    </div>
    <!-- Step 2 -->
    <div>
        <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
            <span class="w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">2</span>
                            Cara Memanggil API dari Web Lain
                        </p>
            <p class="text-gray-600 text-xs ml-8 mb-2 leading-relaxed">Kirim HTTP <strong>POST</strong> ke endpoint berikut:</p>
            <div class="bg-gray-900 rounded-xl p-3 ml-8">
                <code class="text-yellow-400 text-xs font-mono">POST https://e-aspirasigrisawangi.my.id/api/notify</code>
            </div>
        </div>
        <!-- Step 3 - Request Format -->
        <div>
            <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
                <span class="w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">3</span>
                            Format Request (JSON)
                        </p>
                <div class="bg-gray-900 rounded-xl p-3 ml-8">
                    <pre class="text-green-400 text-xs font-mono leading-relaxed">{
  "type": "supabase_get",
  "action": "cariSiswa",
  "payload": {
    "nama": "Budi",
    "api_key": "EA-KEY-ABC123XYZ456"
  }
}</pre>
                </div>
            </div>
            <!-- Step 4 - Response Format -->
            <div>
                <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
                    <span class="w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">4</span>
                            Contoh Response
                        </p>
                    <div class="bg-gray-900 rounded-xl p-3 ml-8">
                        <pre class="text-green-400 text-xs font-mono leading-relaxed">{
  "data": [
    {
      "nama": "Budi Santoso",
      "kelas": "XII RPL 1",
      "nisn": "1234567890",
      "total_poin": 12,
      "status": "Aktif"
    }
  ]
}</pre>
                    </div>
                </div>
                <!-- Step 5 - Contoh Kode JS -->
                <div>
                    <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
                        <span class="w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">5</span>
                            Contoh Kode JavaScript (Fetch)
                        </p>
                        <div class="bg-gray-900 rounded-xl p-3 ml-8">
                            <pre class="text-green-400 text-xs font-mono leading-relaxed">const res = await fetch(
  'https://e-aspirasigrisawangi.my.id/api/notify',
  {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      type: 'supabase_get',
      action: 'cariSiswa',
      payload: {
        nama: 'Budi',
        api_key : '{{ env('API_KEY_6') }}'
      }
    })
  }
);
const json = await res.json();
console.log(json.data); // Array siswa</pre>
                        </div>
                    </div>
                    <!-- Step 6 - PHP -->
                    <div>
                        <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">6</span>
                            Contoh Kode PHP
                        </p>
                            <div class="bg-gray-900 rounded-xl p-3 ml-8">
                                <pre class="text-green-400 text-xs font-mono leading-relaxed">&lt;?php
$url : '{{ env('URL') }}';
$data = [
  'type'    => 'supabase_get',
  'action'  => 'cariSiswa',
  'payload' => [
    'nama'    => 'Budi',
    'api_key' => 'EA-KEY-ABC123XYZ456'
  ]
];
$ch = curl_init($url);
curl_setopt_array($ch, [
  CURLOPT_POST           => true,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
  CURLOPT_POSTFIELDS     => json_encode($data),
]);
$result = json_decode(curl_exec($ch), true);
print_r($result['data']);</pre>
                            </div>
                        </div>
                        <!-- Action List -->
                        <div>
                            <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
                                <span class="w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">7</span>
                            Daftar Action yang Tersedia
                        </p>
                                <div class="ml-8 bg-white border border-gray-200 rounded-xl overflow-hidden">
                                    <table class="w-full text-xs">
                                        <thead>
                                            <tr class="bg-gray-50 border-b border-gray-100">
                                                <th class="text-left px-3 py-2 font-black text-gray-500 uppercase text-[10px]">action</th>
                                                <th class="text-left px-3 py-2 font-black text-gray-500 uppercase text-[10px]">Fungsi</th>
                                                <th class="text-left px-3 py-2 font-black text-gray-500 uppercase text-[10px]">payload</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            <tr>
                                                <td class="px-3 py-2 font-mono text-indigo-600">cariSiswa</td>
                                                <td class="px-3 py-2 text-gray-600">Cari data siswa</td>
                                                <td class="px-3 py-2 font-mono text-gray-400 text-[10px]">{ nama, api_key }</td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 py-2 font-mono text-indigo-600">getAllSiswa</td>
                                                <td class="px-3 py-2 text-gray-600">Semua data siswa</td>
                                                <td class="px-3 py-2 font-mono text-gray-400 text-[10px]">{ api_key }</td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 py-2 font-mono text-indigo-600">getAllKelas</td>
                                                <td class="px-3 py-2 text-gray-600">Daftar kelas</td>
                                                <td class="px-3 py-2 font-mono text-gray-400 text-[10px]">{ api_key }</td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 py-2 font-mono text-indigo-600">getRiwayat</td>
                                                <td class="px-3 py-2 text-gray-600">Riwayat laporan siswa</td>
                                                <td class="px-3 py-2 font-mono text-gray-400 text-[10px]">{ nama, api_key }</td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 py-2 font-mono text-indigo-600">getPengumuman</td>
                                                <td class="px-3 py-2 text-gray-600">Pengumuman aktif</td>
                                                <td class="px-3 py-2 font-mono text-gray-400 text-[10px]">{ api_key }</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Security Note -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                <p class="font-bold text-yellow-800 text-xs mb-1">⚠️ Keamanan API Key</p>
                                <ul class="text-yellow-700 text-xs space-y-1 list-disc list-inside">
                                    <li>Jangan pernah tampilkan API Key di source code frontend yang publik</li>
                                    <li>Simpan API Key di environment variable (ENV) atau backend server</li>
                                    <li>Jika API Key bocor, segera cabut di menu Manajemen API Key</li>
                                    <li>Setiap aplikasi sebaiknya punya API Key sendiri untuk kemudahan revoke</li>
                                </ul>
                            </div>
                            <!-- ENV Variable -->
                            <div>
                                <p class="font-black text-gray-800 mb-2">🔒 Simpan di Environment Variable (Direkomendasikan)</p>
                                <div class="bg-gray-900 rounded-xl p-3">
                                    <pre class="text-green-400 text-xs font-mono"># .env file (jangan di-commit ke GitHub!)
EA_API_KEY=EA-KEY-ABC123XYZ456
EA_BASE_URL=https://e-aspirasigrisawangi.my.id

# Cara pakai di Node.js
const apiKey = process.env.EA_API_KEY;</pre>
                                </div>
                            </div>
                        </div>`,
                confirmButtonText: '<i class="fas fa-check mr-1">
                    </i>Mengerti',
                confirmButtonColor: '#4f46e5',
                showCloseButton: true,
            });
        }

        

        // Tutorial Maps API Key
        async function bukaTutorialMapsApi() {
            await Swal.fire({
                title: '<i class="fas fa-map-marker-alt text-green-500 mr-2">
                </i>Tutorial Google Maps API Key',
                width: '680px',
                html: `
                <div class="text-left text-sm space-y-4 max-h-[72vh] overflow-y-auto pr-1">
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <p class="font-bold text-green-800 text-sm mb-1">📌 Untuk apa Maps API Key?</p>
                    <p class="text-green-700 text-xs leading-relaxed">Google Maps API Key digunakan untuk menampilkan peta lokasi sekolah di halaman absensi manual (manualabs.html) dan untuk validasi geofence — memastikan siswa absen dari dalam area sekolah.</p>
                </div>
                <!-- STEP 1 -->
                <div>
                    <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
                        <span class="w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0">1</span>
                            Buka Google Cloud Console
                        </p>
                        <div class="ml-8 bg-gray-900 rounded-xl p-3">
                            <code class="text-yellow-400 text-xs font-mono">https://console.cloud.google.com</code>
                        </div>
                        <p class="text-gray-500 text-xs ml-8 mt-1">Login dengan akun Google sekolah atau akun pribadi.</p>
                    </div>
                    <!-- STEP 2 -->
                    <div>
                        <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0">2</span>
                            Buat Project Baru
                        </p>
                            <ol class="ml-8 space-y-1 text-xs text-gray-600 list-decimal list-inside">
                                <li>Klik dropdown project di pojok kiri atas</li>
                                <li>Klik <strong>New Project</strong>
                            </li>
                            <li>Isi nama project: <code class="bg-gray-100 px-1 rounded">E-Aspirasi Grisawangi</code>
                        </li>
                        <li>Klik <strong>Create</strong> → tunggu selesai</li>
                    </ol>
                </div>
                <!-- STEP 3 -->
                <div>
                    <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
                        <span class="w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0">3</span>
                            Aktifkan Maps JavaScript API
                        </p>
                        <ol class="ml-8 space-y-1 text-xs text-gray-600 list-decimal list-inside">
                            <li>Di sidebar kiri → klik <strong>APIs & Services → Library</strong>
                        </li>
                        <li>Cari: <code class="bg-gray-100 px-1 rounded">Maps JavaScript API</code>
                    </li>
                    <li>Klik hasil pencarian → klik <strong>Enable</strong>
                </li>
                <li>Ulangi untuk: <code class="bg-gray-100 px-1 rounded">Geolocation API</code> (opsional)</li>
            </ol>
        </div>
        <!-- STEP 4 -->
        <div>
            <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
                <span class="w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0">4</span>
                            Buat API Key
                        </p>
                <ol class="ml-8 space-y-1 text-xs text-gray-600 list-decimal list-inside">
                    <li>Di sidebar → <strong>APIs & Services → Credentials</strong>
                </li>
                <li>Klik <strong>+ Create Credentials → API Key</strong>
            </li>
            <li>API Key langsung terbuat — salin kodenya</li>
        </ol>
        <div class="ml-8 mt-2 bg-gray-900 rounded-xl p-3">
            <p class="text-gray-500 text-[10px] mb-1">Contoh API Key:</p>
            <code class="text-green-400 text-xs font-mono">AIzaSyD4x8K2mN9pQrT7vL1cF6hJ3wE5uB0zA</code>
        </div>
    </div>
    <!-- STEP 5 -->
    <div>
        <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
            <span class="w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0">5</span>
                            Batasi API Key (Penting untuk Keamanan)
                        </p>
            <ol class="ml-8 space-y-1 text-xs text-gray-600 list-decimal list-inside">
                <li>Klik API Key yang baru dibuat</li>
                <li>Di <strong>Application restrictions</strong> → pilih <strong>HTTP referrers (websites)</strong>
            </li>
            <li>Tambahkan domain: <code class="bg-gray-100 px-1 rounded">e-aspirasigrisawangi.my.id/*</code>
        </li>
        <li>Di <strong>API restrictions</strong> → pilih <strong>Restrict key</strong> → centang <strong>Maps JavaScript API</strong>
    </li>
    <li>Klik <strong>Save</strong>
</li>
</ol>
</div>
<!-- STEP 6 -->
<div>
    <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
        <span class="w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0">6</span>
                            Pasang di E-Aspirasi
                        </p>
        <ol class="ml-8 space-y-1 text-xs text-gray-600 list-decimal list-inside">
            <li>Kembali ke halaman Pengaturan Web ini</li>
            <li>Paste API Key ke kolom <strong>Maps API Key</strong>
        </li>
        <li>Isi <strong>Latitude & Longitude</strong> sekolah — gunakan tombol <em>"Gunakan Lokasi Saya"</em> saat di sekolah</li>
        <li>Atur <strong>Radius Geofence</strong> (rekomendasi: 100–200 meter)</li>
        <li>Klik <strong>Simpan Pengaturan</strong>
    </li>
</ol>
</div>
<!-- STEP 7 - Cara dapat koordinat manual -->
<div>
    <p class="font-black text-gray-800 mb-2 flex items-center gap-2">
        <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0">💡</span>
                            Cara Dapat Koordinat Sekolah (Alternatif)
                        </p>
        <ol class="ml-8 space-y-1 text-xs text-gray-600 list-decimal list-inside">
            <li>Buka <strong>Google Maps</strong> di browser</li>
            <li>Cari lokasi sekolah</li>
            <li>Klik kanan tepat di titik sekolah</li>
            <li>Koordinat muncul di atas menu — format: <code class="bg-gray-100 px-1 rounded">-8.123456, 114.123456</code>
        </li>
        <li>Angka pertama = Latitude, angka kedua = Longitude</li>
    </ol>
</div>
<!-- Info biaya -->
<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
    <p class="font-bold text-yellow-800 text-xs mb-1">💰 Biaya Google Maps API</p>
    <p class="text-yellow-700 text-xs leading-relaxed">Google memberikan kredit gratis <strong>$200/bulan</strong>. Untuk penggunaan sekolah (ratusan siswa per hari) biasanya masih gratis. Aktivasi billing tetap diperlukan tapi tidak akan dicharge selama di bawah limit gratis. Pantau penggunaan di Google Cloud Console.</p>
</div>
</div>`,
                confirmButtonText: '<i class="fas fa-check mr-1">
</i>Mengerti',
                confirmButtonColor: '#16a34a',
                showCloseButton: true,
            });
        }

                // Ambil koordinat lokasi admin untuk isi geofence sekolah


        function toggleWajibGeofenceFn() {
            const btn = document.getElementById('toggleWajibGeofence');
            if (!btn) return;
            const isOn = btn.dataset.aktif === 'true';
            const nowOn = !isOn;
            btn.dataset.aktif = nowOn ? 'true' : 'false';
            btn.className = btn.className.replace(/bg-[a-z]+-\d+/g, nowOn ? 'bg-orange-600' : 'bg-gray-600');
            btn.querySelector('span').style.transform = nowOn ? 'translateX(24px)' : 'translateX(0)';
            callBackend({ type:'supabase_post', action:'updatePengaturan',
                payload:[{ nama_pengaturan:'wajib_geofence', nilai: nowOn?'true':'false' }]
            }).then(() => {
                Swal.fire({ toast:true, position:'top-end', icon: nowOn ? 'warning' : 'success',
                    title: nowOn ? 'Wajib Dalam Area: AKTIF' : 'Wajib Dalam Area: NONAKTIF',
                    showConfirmButton:false, timer:1500 });
            }).catch(() => {});
        }

                function toggleAktifManualAbsen() {
            const btn = document.getElementById('toggleManualAbsen');
            if (!btn) return;
            const isOn = btn.dataset.aktif === 'true';
            const nowOn = !isOn;
            btn.dataset.aktif = nowOn ? 'true' : 'false';
            btn.className = btn.className.replace(/bg-\w+-\d+/g, nowOn ? 'bg-blue-600' : 'bg-gray-600');
            btn.querySelector('span').style.transform = nowOn ? 'translateX(24px)' : 'translateX(0)';
            // Auto-save langsung
            callBackend({ type:'supabase_post', action:'updatePengaturan',
                payload:[{ nama_pengaturan:'aktifkan_manual_absen', nilai: nowOn?'true':'false' }]
            }).then(() => {
                Swal.fire({ toast:true, position:'top-end', icon:'success',
                    title: nowOn ? 'Absen Manual AKTIF' : 'Absen Manual NONAKTIF',
                    showConfirmButton:false, timer:1500 });
            }).catch(() => {});
        }

        
        function tambahLiburHariIni() {
            const today = new Date().toISOString().split('T')[0];
            const el = document.getElementById('setHariLibur');
            if (!el) return;
            const current = el.value.trim();
            const list = current ? current.split(',').map(d => d.trim()).filter(Boolean) : [];
            if (list.includes(today)) {
                Swal.fire({ toast:true, position:'top-end', icon:'info', title:'Tanggal sudah ada', showConfirmButton:false, timer:1500 });
                return;
            }
            list.push(today);
            el.value = list.join(', ');
            Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Tanggal ' + today + ' ditambahkan', showConfirmButton:false, timer:1500 });
        }

                function cariKoordinatSekolah() {
            if (!navigator.geolocation) {
                Swal.fire('Tidak Didukung', 'Browser tidak mendukung geolocation.', 'warning');
                return;
            }
            Swal.fire({ title: 'Mengambil Lokasi...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const lat = pos.coords.latitude.toFixed(6);
                    const lon = pos.coords.longitude.toFixed(6);
                    const el_lat = document.getElementById('setGeofenceLat');
                    const el_lon = document.getElementById('setGeofenceLon');
                    if (el_lat) el_lat.value = lat;
                    if (el_lon) el_lon.value = lon;
                    Swal.fire({
                        icon: 'success',
                        title: 'Lokasi Ditemukan!',
                        html: `<p class="font-mono text-sm">Lat: <b>${lat}</b>
<br>Lon: <b>${lon}</b>
</p>
<p class="text-xs text-gray-500 mt-2">Pastikan kamu sedang berada di area sekolah saat mengambil koordinat ini.</p>`,
                        confirmButtonColor: '#16a34a'
                    });
                },
                (err) => {
                    Swal.fire('Gagal', 'Tidak bisa mendapatkan lokasi: ' + err.message, 'error');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }

                const TEMPLATE_BALASAN = {
            1: `Yth. Siswa/i,

Laporan/aspirasi Anda telah kami terima dan akan segera kami tindaklanjuti. Terima kasih telah mempercayakan permasalahan Anda kepada kami.

Hormat kami,
Tim E-Aspirasi`,
            2: `Yth. Siswa/i,

Laporan Anda sedang dalam proses penanganan oleh pihak terkait. Mohon bersabar dan kami akan segera memberikan kabar lebih lanjut.

Hormat kami,
Tim E-Aspirasi`,
            3: `Yth. Siswa/i,

Terima kasih telah menghubungi layanan konseling. Kami mengundang Anda untuk hadir pada sesi konseling yang akan dijadwalkan. Tim BK akan menghubungi Anda lebih lanjut mengenai waktu dan tempat.

Salam,
Guru BK`,
            4: `Yth. Siswa/i,

Kami telah menerima laporan Anda. Untuk memproses lebih lanjut, kami memerlukan konfirmasi dan informasi tambahan. Mohon hubungi pihak sekolah atau balas melalui layanan ini.

Hormat kami,
Tim E-Aspirasi`,
            5: `Yth. Siswa/i,

Terima kasih telah menyampaikan masukan dan aspirasi Anda. Setiap masukan sangat berarti bagi perkembangan sekolah kita. Masukan Anda akan menjadi bahan evaluasi kami ke depannya.

Hormat kami,
Tim E-Aspirasi`,
            6: `Yth. Siswa/i,

Mohon maaf, laporan/aspirasi yang Anda sampaikan tidak dapat kami proses saat ini karena beberapa pertimbangan. Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi pihak sekolah secara langsung.

Hormat kami,
Tim E-Aspirasi`
        };

        function pakaiTemplate(num) {
            const ta = document.getElementById('inputBalasan');
            if (ta) {
                ta.value = TEMPLATE_BALASAN[num] || '';
                ta.focus();
                // Highlight efek
                ta.classList.add('border-blue-400', 'bg-blue-50');
                setTimeout(() => ta.classList.remove('border-blue-400','bg-blue-50'), 800);
            }
        }

        // ============================================================
        // FIX: parseSheetLink — extract Sheet ID dari URL lengkap
        // ============================================================
        // ============================================================
        // parseSheetLink — extract/validate Google Apps Script URL
        // ============================================================
        function parseSheetLink(input) {
            const val = (input?.value || input || '').trim();
            const target = document.getElementById('setGoogleSheetId');
            if (!target) return;
            // Cek apakah URL adalah Apps Script Web App (exec URL)
            if (val.includes('script.google.com') && val.includes('/exec')) {
                target.value = val; // simpan URL langsung sebagai key
                input.style.borderColor = '#22c55e';
                return;
            }
            // Coba extract Sheet ID dari URL spreadsheet biasa
            const match = val.match(/\/spreadsheets\/d\/([a-zA-Z0-9_-]{10,})/);
            if (match) {
                target.value = match[1];
                input.style.borderColor = '#22c55e';
            } else if (val.length > 10) {
                // Anggap input langsung sebagai ID/URL
                target.value = val;
                input.style.borderColor = '#f59e0b';
            } else {
                input.style.borderColor = '#ef4444';
            }
        }

        // ============================================================
        // resetPinSiswa — reset PIN login siswa dari panel admin
        // ============================================================
        async function resetPinSiswa(siswaId) {
            if (!siswaId) return Swal.fire('Error', 'ID siswa tidak ditemukan', 'error');
            const conf = await Swal.fire({
                title: 'Reset PIN Siswa?',
                text: 'PIN dan jawaban keamanan siswa akan dihapus. Siswa harus membuat PIN baru saat login berikutnya.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Reset PIN',
                cancelButtonText: 'Batal'
            });
            if (!conf.isConfirmed) return;
            try {
                Swal.fire({ title: 'Mereset...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                await callBackend({ type: 'supabase_post', action: 'updateData', payload: {
                    table: 'siswa', id: siswaId,
                    payload: { login_pin: null, security_answer: null }
                }});
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'PIN siswa berhasil direset', showConfirmButton: false, timer: 2000 });
                // Reload tabel siswa
                loadDataTable('siswa', 'siswa');
            } catch(e) {
                Swal.fire('Gagal', 'Gagal reset PIN: ' + e.message, 'error');
            }
        }

                // Kelas bulk select helpers
        function piliSemuaKelas() {
            const checks = document.querySelectorAll('.kelas-check');
            const allChecked = Array.from(checks).every(c => c.checked);
            checks.forEach(c => c.checked = !allChecked);
            const cb = document.getElementById('checkAllKelas');
            if (cb) cb.checked = !allChecked;
            updateKelasCheckCount();
        }
        async function bulkHapusKelas() {
            const ids = getCheckedIds();
            if (!ids.length) return;
            const conf = await Swal.fire({ title: `Hapus ${ids.length} kelas?`, text: 'Kelas yang dihapus tidak bisa dikembalikan.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal' });
            if (!conf.isConfirmed) return;
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            let ok = 0;
            for (const id of ids) {
                try { await callBackend({ type: 'supabase_post', action: 'deleteData', payload: { table: 'daftar_kelas', id } }); ok++; } catch(e) {}
            }
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${ok} kelas dihapus`, showConfirmButton: false, timer: 2000 });
            hideBulkBar(); muatDataKelas();
        }

        async function bulkHapusRole() {
            const ids = getCheckedIds();
            if (!ids.length) return;
            const conf = await Swal.fire({ title: `Hapus ${ids.length} jabatan?`, text: 'Jabatan yang dihapus tidak bisa dikembalikan.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal' });
            if (!conf.isConfirmed) return;
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            let ok = 0;
            for (const id of ids) {
                try { await callBackend({ type: 'supabase_post', action: 'deleteData', payload: { table: 'daftar_role', id } }); ok++; } catch(e) {}
            }
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${ok} jabatan dihapus`, showConfirmButton: false, timer: 2000 });
            hideBulkBar(); muatDataRole();
        }

                function updateKelasCheckCount() {
            const checks = document.querySelectorAll('.kelas-check');
            const checked = document.querySelectorAll('.kelas-check:checked');
            const cb = document.getElementById('checkAllKelas');
            if (cb) { cb.indeterminate = checked.length > 0 && checked.length < checks.length; cb.checked = checked.length === checks.length && checks.length > 0; }
            let btn = document.getElementById('btnBulkHapusKelas');
            if (!btn && checked.length > 0) {
                btn = document.createElement('button'); btn.id = 'btnBulkHapusKelas';
                btn.className = 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm';
                btn.onclick = bulkHapusKelas;
                const ac = document.getElementById('dtActionsContainer'); if (ac) ac.appendChild(btn);
            }
            if (btn) { btn.innerHTML = `<i class="fas fa-trash-alt">
</i> Hapus ${checked.length} Kelas`; btn.style.display = checked.length > 0 ? 'flex' : 'none'; }
        }

        // Role bulk select helpers
        function piliSemuaRole() {
            const checks = document.querySelectorAll('.role-check');
            const allChecked = Array.from(checks).every(c => c.checked);
            checks.forEach(c => c.checked = !allChecked);
            const cb = document.getElementById('checkAllRole');
            if (cb) cb.checked = !allChecked;
            updateRoleCheckCount();
        }
        function updateRoleCheckCount() {
            const checks = document.querySelectorAll('.role-check');
            const checked = document.querySelectorAll('.role-check:checked');
            const cb = document.getElementById('checkAllRole');
            if (cb) { cb.indeterminate = checked.length > 0 && checked.length < checks.length; cb.checked = checked.length === checks.length && checks.length > 0; }
            let btn = document.getElementById('btnBulkHapusRole');
            if (!btn && checked.length > 0) {
                btn = document.createElement('button'); btn.id = 'btnBulkHapusRole';
                btn.className = 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm';
                btn.onclick = bulkHapusRole;
                const ac = document.getElementById('dtActionsContainer'); if (ac) ac.appendChild(btn);
            }
            if (btn) { btn.innerHTML = `<i class="fas fa-trash-alt">
</i> Hapus ${checked.length} Jabatan`; btn.style.display = checked.length > 0 ? 'flex' : 'none'; }
        }
    
        // ============================================================
        // CARI MENU SIDEBAR
        // ============================================================
        function cariMenu(q) {
            q = (q || '').toLowerCase().trim();
            const nav = document.getElementById('menuNav'); if (!nav) return;
            let header = null, headerAda = false, adaHasil = false;
            Array.from(nav.children).forEach(el => {
                if (el.id === 'menuNoResult') return;
                if (el.tagName === 'DIV') {
                    if (header) header.classList.toggle('hidden', q !== '' && !headerAda);
                    header = el; headerAda = false;
                } else {
                    const cocok = !q || el.textContent.toLowerCase().includes(q);
                    el.classList.toggle('hidden', !cocok);
                    if (cocok) { headerAda = true; adaHasil = true; }
                }
            });
            if (header) header.classList.toggle('hidden', q !== '' && !headerAda);
            let info = document.getElementById('menuNoResult');
            if (!info) { info = document.createElement('p'); info.id = 'menuNoResult'; info.className = 'hidden text-center text-xs text-gray-500 py-6'; info.textContent = 'Menu tidak ditemukan'; nav.appendChild(info); }
            info.classList.toggle('hidden', adaHasil || !q);
        }

        // ============================================================
        // AUTO LOGOUT KARENA TIDAK ADA AKTIVITAS
        // ============================================================
        let IDLE_LIMIT_DETIK = 120;   // default 2 menit; diatur per email lewat Profil (ikon gerigi)
        const IDLE_PERINGATAN_DETIK = 15;
        let _lastActivity = Date.now(), _lastSave = 0, _idleTimer = null;
        const _idleEvents = ['mousemove', 'mousedown', 'keydown', 'click', 'scroll', 'touchstart', 'wheel'];

        function _catatAktivitas() {
            _lastActivity = Date.now();
            if (_lastActivity - _lastSave > 1000) { _lastSave = _lastActivity; try { localStorage.setItem('admin_last_active', String(_lastActivity)); } catch(e) {} }
            const c = document.getElementById('idleChip'); if (c) c.classList.add('hidden');
        }
        function mulaiIdleWatcher() {
            stopIdleWatcher();
            _catatAktivitas();
            _idleEvents.forEach(ev => window.addEventListener(ev, _catatAktivitas, { passive: true, capture: true }));
            if (!document.getElementById('idleChip')) {
                const c = document.createElement('div');
                c.id = 'idleChip';
                c.className = 'hidden fixed bottom-4 left-4 z-[9999] bg-red-600 text-white text-xs font-black px-4 py-2.5 rounded-xl shadow-2xl';
                document.body.appendChild(c);
            }
            _idleTimer = setInterval(() => {
                if (!userData || (typeof isDemoMode !== 'undefined' && isDemoMode) || layarTerkunci()) return;
                const sisa = Math.ceil(IDLE_LIMIT_DETIK - (Date.now() - _lastActivity) / 1000);
                const chip = document.getElementById('idleChip');
                if (sisa <= 0) { logoutOtomatis(); }
                else if (sisa <= IDLE_PERINGATAN_DETIK && chip) { chip.textContent = `⏱ Sesi berakhir dalam ${sisa} dtk — gerakkan mouse / sentuh layar untuk lanjut`; chip.classList.remove('hidden'); }
            }, 1000);
        }
        function stopIdleWatcher() {
            if (_idleTimer) { clearInterval(_idleTimer); _idleTimer = null; }
            _idleEvents.forEach(ev => window.removeEventListener(ev, _catatAktivitas, { capture: true }));
            const c = document.getElementById('idleChip'); if (c) c.classList.add('hidden');
        }
        function logoutOtomatis() {
            stopIdleWatcher();
            _bersihkanLock();
            Swal.close();
            localStorage.removeItem('admin_ea_session');
            localStorage.removeItem('admin_last_active');
            try { if (supaClient) supaClient.auth.signOut(); } catch(e) {}
            userData = null; isSuperAdminUnlocked = false;
            document.querySelectorAll('[id^="modal"]').forEach(m => m.classList.add('hidden'));
            hideBulkBar();
            document.getElementById('mainDashboard').classList.add('hidden');
            document.getElementById('loginScreen').classList.remove('hidden');
            document.getElementById('loginForm').reset();
            Swal.fire({ icon: 'info', title: 'Sesi Berakhir', text: `Anda keluar otomatis karena tidak ada aktivitas selama ${IDLE_LIMIT_DETIK} detik.`, confirmButtonColor: '#2563eb' });
        }

        // ============================================================
        // GURU: blokir + pilih mapel/kelas dari master
        // ============================================================
        const _GURU_MAX = { mapel: 5, kelas: 15 };
        window._guruMapelSel = []; window._guruKelasSel = []; window._masterMapelNama = []; window._masterKelasNama = [];
        async function _siapkanFormGuru(ex) {
            const parse = v => { try { const x = typeof v === 'string' ? JSON.parse(v || '[]') : (v || []); return Array.isArray(x) ? x : []; } catch(e) { return []; } };
            window._guruMapelSel = parse(ex.mapel); window._guruKelasSel = parse(ex.kelas_diajarkan);
            try { const r = await callBackend({ type:'supabase_get', action:'getData', payload:{ table:'master_mapel?aktif=eq.true&order=nama_mapel.asc' } }); window._masterMapelNama = (r.data || []).map(m => m.nama_mapel); } catch(e) { window._masterMapelNama = []; }
            try { const r = await callBackend({ type:'supabase_get', action:'getAllKelas' }); window._masterKelasNama = (r.data || []).map(k => k.nama_kelas); } catch(e) { window._masterKelasNama = []; }
        }
        function _guruSel(t) { return t === 'mapel' ? window._guruMapelSel : window._guruKelasSel; }
        function _guruMaster(t) { return t === 'mapel' ? window._masterMapelNama : window._masterKelasNama; }
        function _guruOpsi(t) {
            const sel = _guruSel(t);
            return '<option value="">— Pilih ' + (t === 'mapel' ? 'mata pelajaran' : 'kelas') + ' —</option>' +
                _guruMaster(t).filter(n => !sel.includes(n)).map(n => `<option value="${String(n).replace(/"/g, '&quot;')}">${n}</option>`).join('');
        }
        function _guruChips(t) {
            const warna = t === 'mapel' ? 'bg-indigo-100 text-indigo-700' : 'bg-blue-100 text-blue-700';
            return _guruSel(t).map((n, i) => `<span class="flex items-center gap-1.5 ${warna} text-xs font-bold px-3 py-1.5 rounded-full">${n}<button type="button" onclick="guruHapusPilihan('${t}',${i})" class="hover:text-red-500">
<i class="fas fa-times text-[10px]">
</i>
</button>
</span>`).join('') || '<span class="text-xs text-gray-400">Belum ada</span>';
        }
        function _guruRender(t) {
            const c = document.getElementById(t === 'mapel' ? 'fmGuruMapelChips' : 'fmGuruKelasChips'); if (c) c.innerHTML = _guruChips(t);
            const s = document.getElementById(t === 'mapel' ? 'fmGuruMapelPick' : 'fmGuruKelasPick'); if (s) s.innerHTML = _guruOpsi(t);
        }
        function guruTambahPilihan(t) {
            const s = document.getElementById(t === 'mapel' ? 'fmGuruMapelPick' : 'fmGuruKelasPick');
            const v = s && s.value; if (!v) return;
            const arr = _guruSel(t);
            if (arr.length >= _GURU_MAX[t]) { Swal.fire({ toast:true, position:'top-end', icon:'warning', title:`Maks. ${_GURU_MAX[t]} ${t}`, showConfirmButton:false, timer:1500 }); return; }
            if (!arr.includes(v)) arr.push(v);
            _guruRender(t);
        }
        function guruHapusPilihan(t, i) { _guruSel(t).splice(i, 1); _guruRender(t); }
        async function toggleBlokirGuru(id, status) {
            const blok = status !== 'Diblokir';
            const c = await Swal.fire({ title: blok ? 'Blokir guru ini?' : 'Buka blokir guru ini?', text: blok ? 'Guru tidak bisa login ke Portal Guru.' : 'Guru bisa login kembali.', icon: 'warning', showCancelButton: true, confirmButtonColor: blok ? '#f59e0b' : '#22c55e', confirmButtonText: blok ? 'Ya, Blokir' : 'Ya, Buka Blokir', cancelButtonText: 'Batal' });
            if (!c.isConfirmed) return;
            try {
                await callBackend({ type:'supabase_post', action:'updateData', payload:{ table:'master_guru', id, payload:{ status: blok ? 'Diblokir' : 'Aktif' } } });
                Swal.fire({ toast:true, position:'top-end', icon:'success', title: blok ? 'Guru diblokir' : 'Blokir dibuka', showConfirmButton:false, timer:1800 });
                loadDataTable('master_guru', 'guru');
            } catch(e) { Swal.fire('Gagal', e.message, 'error'); }
        }


        // ============================================================
        // PERIODE SEMESTER
        // ============================================================
        function _fmtTglPendek(d) {
            if (!d) return '<span class="text-gray-300">—</span>';
            try { return new Date(d + 'T00:00:00').toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' }); } catch(e) { return d; }
        }

        // ============================================================
        // TIMEOUT SESI PER EMAIL
        // ============================================================
        async function loadTimeoutAdmin() {
            try {
                const r = await callBackend({ type:'supabase_get', action:'getPengaturanByKey', payload:{ key : '{{ env('KEY') }}' + String(userData.email_admin || '').toLowerCase() } });
                const v = parseInt(r.data?.[0]?.nilai, 10);
                const menit = (v >= 1 && v <= 1440) ? v : 2;
                IDLE_LIMIT_DETIK = menit * 60;
                localStorage.setItem('admin_timeout_menit', String(menit));
            } catch(e) {}
        }

        // ============================================================
        // GEMBOK DATA PENTING (role Super Admin & akun superadmin)
        // ============================================================
        const PIN_BUKA_GEMBOK = '2410';
        const _unlockedRows = new Set();
        let _gemTimer = null, _gemCount = 0, _gemKey = '';
        function _adminTerkunci(it) { return /superadmin/i.test(it.email_admin || '') && !_unlockedRows.has('admin:' + it.id); }
        function _roleTerkunci(it) { return /^\s*super\s*admin\s*$/i.test(it.nama_role || '') && !_unlockedRows.has('role:' + it.id); }
        function _tombolGembok(tipe, id) {
            return `<td class="px-5 py-4 text-right">
<button onclick="klikGembok('${tipe}','${id}')" title="Terkunci — klik gembok 3x untuk membuka" class="text-gray-500 hover:bg-gray-100 p-2 rounded-lg transition">
    <i class="fas fa-lock">
    </i>
</button>
</td>`;
        }
        function _aksiAdmin(item) {
            if (_adminTerkunci(item)) return _tombolGembok('admin', item.id);
            return `<td class="px-5 py-4 text-right">
<button onclick="bukaModalData('admin','edit','${item.id}')" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition mr-1">
    <i class="fas fa-edit">
    </i>
</button>
<button onclick="hapusSatuData('hak_akses_admin','${item.id}')" class="text-red-500 hover:bg-red-100 p-2 rounded-lg transition">
    <i class="fas fa-trash-alt">
    </i>
</button>
</td>`;
        }
        function _aksiRole(item) {
            if (_roleTerkunci(item)) return _tombolGembok('role', item.id);
            return `<td class="px-5 py-4 text-right">
<button onclick="bukaModalRole('${item.id}')" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition mr-1">
    <i class="fas fa-edit">
    </i>
</button>
<button onclick="hapusSatuData('daftar_role', '${item.id}')" class="text-red-500 hover:bg-red-100 p-2 rounded-lg transition">
    <i class="fas fa-trash-alt">
    </i>
</button>
</td>`;
        }
        function _bukanTerkunci(ids) {
            if (currentTableId === 'admin') return ids.filter(id => { const it = (globalDataStore['admin'] || []).find(x => x.id === id); return !(it && _adminTerkunci(it)); });
            if (currentTableId === 'role') return ids.filter(id => { const it = (globalDataStore['role'] || []).find(x => x.id === id); return !(it && _roleTerkunci(it)); });
            return ids;
        }
        function klikGembok(tipe, id) {
            const k = tipe + ':' + id;
            if (_gemKey !== k) { _gemKey = k; _gemCount = 0; }
            _gemCount++;
            clearTimeout(_gemTimer); _gemTimer = setTimeout(() => { _gemCount = 0; }, 1500);
            if (_gemCount === 1) Swal.fire({ toast:true, position:'top-end', icon:'info', title:'Data terkunci. Klik gembok 3x untuk membuka.', showConfirmButton:false, timer:1500 });
            if (_gemCount >= 3) { _gemCount = 0; mintaPinBuka(tipe, id); }
        }
        async function mintaPinBuka(tipe, id) {
            const r = await Swal.fire({ title: 'Buka Gembok', text: 'Masukkan PIN untuk mengedit data ini', input: 'password', inputAttributes: { maxlength: 4, inputmode: 'numeric', autocomplete: 'off' }, showCancelButton: true, confirmButtonText: 'Buka', cancelButtonText: 'Batal', confirmButtonColor: '#2563eb' });
            if (!r.isConfirmed) return;
            if (String(r.value || '').trim() !== PIN_BUKA_GEMBOK) { Swal.fire('PIN Salah', 'Gembok tetap terkunci.', 'error'); return; }
            _unlockedRows.add(tipe + ':' + id);
            Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Gembok terbuka', showConfirmButton:false, timer:1500 });
            if (tipe === 'role') renderTabelRole(); else renderDataTableBody();
        }

        // ============================================================
        // LOCK SCREEN (kunci layar dengan PIN 4 digit)
        // ============================================================
        function layarTerkunci() { const l = document.getElementById('lockScreen'); return !!l && !l.classList.contains('hidden'); }
        function _kunciPinKey() { return 'admin_lockpin_' + String(userData?.email_admin || '').toLowerCase(); }
        async function _hashPin(pin) {
            const d = new TextEncoder().encode('ea-lock:' + String(userData?.email_admin || '').toLowerCase() + ':' + pin);
            const h = await crypto.subtle.digest('SHA-256', d);
            return [...new Uint8Array(h)].map(b => b.toString(16).padStart(2, '0')).join('');
        }
        async function _ambilHashPin() {
            try { const r = await callBackend({ type:'supabase_get', action:'getPengaturanByKey', payload:{ key: _kunciPinKey() } }); return r.data?.[0]?.nilai || ''; } catch(e) { return ''; }
        }
        async function _setPinBaru() {
            const r = await Swal.fire({
                title: 'Buat PIN Kunci Layar',
                html: '<p class="text-xs text-gray-500 mb-3">PIN 4 digit untuk membuka layar yang terkunci.</p>
<input id="pinA" type="password" maxlength="4" inputmode="numeric" placeholder="PIN baru" class="swal2-input" style="text-align:center;letter-spacing:.5em">
<input id="pinB" type="password" maxlength="4" inputmode="numeric" placeholder="Ulangi PIN" class="swal2-input" style="text-align:center;letter-spacing:.5em">',
                showCancelButton: true, confirmButtonText: 'Simpan PIN', cancelButtonText: 'Batal', confirmButtonColor: '#2563eb',
                preConfirm: () => {
                    const a = document.getElementById('pinA').value, b = document.getElementById('pinB').value;
                    if (!/^\d{4}$/.test(a)) { Swal.showValidationMessage('PIN harus 4 digit angka'); return false; }
                    if (a !== b) { Swal.showValidationMessage('Konfirmasi PIN tidak cocok'); return false; }
                    return a;
                }
            });
            if (!r.isConfirmed) return false;
            try {
                await callBackend({ type:'supabase_post', action:'updatePengaturan', payload:[{ nama_pengaturan: _kunciPinKey(), nilai: await _hashPin(r.value) }] });
                Swal.fire({ toast:true, position:'top-end', icon:'success', title:'PIN kunci layar tersimpan', showConfirmButton:false, timer:1800 });
                return true;
            } catch(e) { Swal.fire('Gagal', e.message, 'error'); return false; }
        }
        async function aturPinKunci() {
            if (typeof isDemoMode !== 'undefined' && isDemoMode) { Swal.fire('Info', 'Tidak tersedia di mode demo.', 'info'); return; }
            const cur = await _ambilHashPin();
            if (cur) {
                const o = await Swal.fire({ title: 'PIN Saat Ini', input: 'password', inputAttributes: { maxlength: 4, inputmode: 'numeric' }, showCancelButton: true, confirmButtonText: 'Lanjut', cancelButtonText: 'Batal', confirmButtonColor: '#2563eb' });
                if (!o.isConfirmed) return;
                if (await _hashPin(String(o.value || '')) !== cur) { Swal.fire('PIN Salah', 'PIN saat ini tidak cocok.', 'error'); return; }
            }
            await _setPinBaru();
        }
        async function kunciLayar() {
            if (typeof isDemoMode !== 'undefined' && isDemoMode) { Swal.fire('Info', 'Kunci layar tidak tersedia di mode demo.', 'info'); return; }
            const cur = await _ambilHashPin();
            if (!cur) { const ok = await _setPinBaru(); if (!ok) return; }
            tampilkanLock();
        }
        function tampilkanLock() {
            localStorage.setItem('admin_locked', '1');
            document.querySelectorAll('[id^="modal"]').forEach(m => m.classList.add('hidden'));
            Swal.close();
            const l = document.getElementById('lockScreen'); l.classList.remove('hidden');
            const i = document.getElementById('lockPinInput'); i.value = '';
            document.getElementById('lockPinError').classList.add('hidden');
            setTimeout(() => i.focus(), 100);
        }
        async function bukaKunci() {
            const i = document.getElementById('lockPinInput'), err = document.getElementById('lockPinError');
            const pin = i.value;
            if (!/^\d{4}$/.test(pin)) { err.textContent = 'Masukkan 4 digit PIN.'; err.classList.remove('hidden'); return; }
            const cur = await _ambilHashPin();
            if (!cur) { err.textContent = 'PIN belum diatur. Gunakan "Keluar" lalu login ulang.'; err.classList.remove('hidden'); return; }
            if (await _hashPin(pin) !== cur) { err.textContent = 'PIN salah, coba lagi.'; err.classList.remove('hidden'); i.value = ''; i.focus(); return; }
            localStorage.removeItem('admin_locked');
            document.getElementById('lockScreen').classList.add('hidden');
            _catatAktivitas();
        }
        function _bersihkanLock() {
            localStorage.removeItem('admin_locked');
            const l = document.getElementById('lockScreen'); if (l) l.classList.add('hidden');
            _unlockedRows.clear();
        }
        function keluarDariLock() {
            stopIdleWatcher(); _bersihkanLock();
            localStorage.removeItem('admin_ea_session'); localStorage.removeItem('admin_last_active');
            try { if (supaClient) supaClient.auth.signOut(); } catch(e) {}
            userData = null; isSuperAdminUnlocked = false;
            hideBulkBar();
            document.getElementById('mainDashboard').classList.add('hidden');
            document.getElementById('loginScreen').classList.remove('hidden');
            document.getElementById('loginForm').reset();
        }


        // ============================================================
        // FIX: modal Kelas/Role menimpa isi form generik → pulihkan kerangka
        // ============================================================
        function _pastikanFormGeneric() {
            const f = document.getElementById('genericDataForm');
            if (!f) return;
            if (document.getElementById('dynamicFormFields') && document.getElementById('btnSubmitDataForm') && document.getElementById('tambahType') && document.getElementById('formActionType') && document.getElementById('formRecordId')) return;
            f.innerHTML = '<input type="hidden" id="formActionType">
<input type="hidden" id="formTableTarget">
<input type="hidden" id="formRecordId">
<input type="hidden" id="tambahType">
<div id="dynamicFormFields" class="space-y-4">
</div>
<div class="pt-5 mt-4 border-t border-gray-100">
    <button type="submit" id="btnSubmitDataForm" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-xl shadow-lg transition active:scale-95 uppercase tracking-widest text-sm flex items-center justify-center gap-2">
        <i class="fas fa-save text-lg">
        </i> Simpan Data</button>
    </div>';
        }

        // ============================================================
        // FILTER REKAP ABSENSI (Semester + Kelas + Periode)
        // ============================================================
        let _absPeriode = '1H';
        const _ABS_BTN_ON = 'abs-periode-btn flex-shrink-0 px-3 py-2 rounded-xl text-xs font-black transition bg-blue-600 text-white border-2 border-blue-600 shadow-sm';
        const _ABS_BTN_OFF = 'abs-periode-btn flex-shrink-0 px-3 py-2 rounded-xl text-xs font-black transition bg-white border-2 border-gray-200 text-gray-600 hover:border-blue-400';
        function _filterAbsensiHtml() {
            _absPeriode = '1H';
            const siswa = globalDataStore['siswa'] || [];
            window._kelasBySiswa = {};
            siswa.forEach(s => { window._kelasBySiswa[s.id] = s.kelas; });
            const kelas = [...new Set([...siswa.map(s => s.kelas), ...(globalDataStore['absensi'] || []).map(r => r.kelas)].filter(Boolean))].sort();
            const sem = globalDataStore['_semester'] || [];
            const semAktif = sem.find(s => s.aktif);
            const cls = 'px-3 py-2 border-2 border-gray-200 rounded-xl text-sm font-bold bg-white focus:ring-2 focus:ring-blue-500 flex-1 sm:flex-none min-w-[140px]';
            const btn = (p, t) => `<button onclick="setPeriodeAbs('${p}',this)" data-p="${p}" class="${p === _absPeriode ? _ABS_BTN_ON : _ABS_BTN_OFF}">${t}</button>`;
            return `<div class="w-full flex flex-col gap-2">
    <div class="flex gap-2 flex-wrap">
        <select id="filterSemAbs" onchange="gantiSemesterAbs()" class="${cls}">
            <option value="">— Semester —</option>${sem.map(s => `<option value="${s.label}" ${semAktif && semAktif.id === s.id ? 'data-aktif="1"' : ''}>${s.label}${s.aktif ? ' ★' : ''}</option>`).join('')}</select>
            <select id="filterKelasAbs" onchange="filterTableLokal()" class="${cls}">
                <option value="">— Semua Kelas —</option>${kelas.map(k => `<option value="${k}">${k}</option>`).join('')}</select>
            </div>
            <div class="flex gap-1.5 overflow-x-auto pb-1">${btn('1H', '1 Hari')}${btn('7H', '7 Hari')}${btn('1B', '1 Bulan')}${btn('1S', '1 Semester')}${btn('ALL', 'Semua')}</div>
        </div>`;
        }
        function setPeriodeAbs(p, btn) {
            _absPeriode = p;
            document.querySelectorAll('.abs-periode-btn').forEach(b => { b.className = b.dataset.p === p ? _ABS_BTN_ON : _ABS_BTN_OFF; });
            if (p === '1S') {
                const s = document.getElementById('filterSemAbs');
                if (s && !s.value) { const o = [...s.options].find(x => x.dataset.aktif === '1'); if (o) s.value = o.value; }
            }
            filterTableLokal();
        }
        function gantiSemesterAbs() {
            const v = document.getElementById('filterSemAbs')?.value;
            if (v) { setPeriodeAbs('1S'); } else { filterTableLokal(); }
        }
        function _cocokPeriodeAbs(d) {
            if (!d.created_at) return true;
            const t = new Date(d.created_at), now = new Date();
            if (_absPeriode === '1H') { const s = new Date(now); s.setHours(0, 0, 0, 0); return t >= s; }
            if (_absPeriode === '7H') return t >= new Date(now - 7 * 86400000);
            if (_absPeriode === '1B') return t >= new Date(now - 30 * 86400000);
            if (_absPeriode === '1S') {
                const lab = document.getElementById('filterSemAbs')?.value;
                if (!lab) return true;
                const sem = (globalDataStore['_semester'] || []).find(s => s.label === lab);
                if (sem && sem.tanggal_mulai && sem.tanggal_akhir) return t >= new Date(sem.tanggal_mulai + 'T00:00:00') && t <= new Date(sem.tanggal_akhir + 'T23:59:59');
                return d.semester === lab;
            }
            return true;
        }

</script>
        <script>
        // ============================================================
        // MENU SISWA TAMBAHAN — disimpan di tabel pengaturan:
        //   menu_tambahan_aktif ('true'/'false') & menu_tambahan_list (JSON)
        // ============================================================
        let _msList = [], _msEditIdx = -1;
        const _MS_ICON = { buku: ['fa-book', 'text-green-500'], konsol: ['fa-gamepad', 'text-purple-500'], kalender: ['fa-calendar-alt', 'text-orange-500'] };
        const _msAman = u => /^https?:\/\//i.test(String(u || '').trim());
        const _msToast = (t, icon) => Swal.fire({ toast: true, position: 'top-end', icon: icon || 'success', title: t, showConfirmButton: false, timer: 1800 });

        async function muatMenuSiswaAdmin() {
            msResetForm();
            if (!(typeof isDemoMode !== 'undefined' && isDemoMode)) {
                try {
                    const res = await callBackend({ type: 'supabase_get', action: 'getPengaturan' });
                    const get = k => ((res.data || []).find(p => p.nama_pengaturan === k) || {}).nilai;
                    document.getElementById('msAktif').checked = get('menu_tambahan_aktif') === 'true';
                    try { _msList = JSON.parse(get('menu_tambahan_list') || '[]'); } catch(e) { _msList = []; }
                    if (!Array.isArray(_msList)) _msList = [];
                } catch(e) { Swal.fire('Gagal memuat', e.message, 'error'); }
            }
            msRender();
        }

        async function _msSimpanKeDB(kunci, nilai) {
            if (typeof isDemoMode !== 'undefined' && isDemoMode) return true;
            try { await callBackend({ type: 'supabase_post', action: 'updatePengaturan', payload: [{ nama_pengaturan: kunci, nilai }] }); return true; }
            catch(e) { Swal.fire('Gagal menyimpan', e.message, 'error'); return false; }
        }

        async function msToggleAktif(el) {
            const on = el.checked;
            const ok = await _msSimpanKeDB('menu_tambahan_aktif', on ? 'true' : 'false');
            if (!ok) { el.checked = !on; return; }
            _msToast(on ? 'Menu Siswa AKTIF' : 'Menu Siswa NONAKTIF');
        }

        function msGantiIcon() {
            document.getElementById('msIconUrlWrap').classList.toggle('hidden', document.getElementById('msIconTipe').value !== 'link');
        }

        function msResetForm() {
            _msEditIdx = -1;
            ['msNama', 'msIconUrl', 'msLink'].forEach(id => { const e = document.getElementById(id); if (e) e.value = ''; });
            const t = document.getElementById('msIconTipe'); if (t) t.value = 'buku';
            msGantiIcon();
            document.getElementById('msFormJudul').textContent = 'Tambah Menu Baru';
            document.getElementById('msBtnLabel').textContent = 'Tambah Menu';
            document.getElementById('msBtnBatal').classList.add('hidden');
        }

        async function msSubmit() {
            const nama = document.getElementById('msNama').value.trim();
            const icon = document.getElementById('msIconTipe').value;
            const iconUrl = document.getElementById('msIconUrl').value.trim();
            const link = document.getElementById('msLink').value.trim();
            if (!nama) return Swal.fire('Perhatian', 'Nama menu wajib diisi.', 'warning');
            if (icon === 'link' && !_msAman(iconUrl)) return Swal.fire('Perhatian', 'Link gambar icon harus diawali http:// atau https://', 'warning');
            if (!_msAman(link)) return Swal.fire('Perhatian', 'Link tujuan harus diawali http:// atau https://', 'warning');
            const item = { nama, icon, icon_url: icon === 'link' ? iconUrl : '', link };
            const backup = JSON.parse(JSON.stringify(_msList));
            if (_msEditIdx >= 0) _msList[_msEditIdx] = item; else _msList.push(item);
            const ok = await _msSimpanKeDB('menu_tambahan_list', JSON.stringify(_msList));
            if (!ok) { _msList = backup; return; }
            _msToast(_msEditIdx >= 0 ? 'Menu diperbarui' : 'Menu ditambahkan');
            msResetForm(); msRender();
        }

        function msEdit(i) {
            const m = _msList[i]; if (!m) return;
            _msEditIdx = i;
            document.getElementById('msNama').value = m.nama || '';
            document.getElementById('msIconTipe').value = m.icon || 'buku';
            document.getElementById('msIconUrl').value = m.icon_url || '';
            document.getElementById('msLink').value = m.link || '';
            msGantiIcon();
            document.getElementById('msFormJudul').textContent = 'Edit Menu';
            document.getElementById('msBtnLabel').textContent = 'Simpan Perubahan';
            document.getElementById('msBtnBatal').classList.remove('hidden');
            document.getElementById('msNama').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        async function msHapus(i) {
            const m = _msList[i]; if (!m) return;
            const c = await Swal.fire({ title: 'Hapus menu "' + m.nama + '"?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Hapus', cancelButtonText: 'Batal' });
            if (!c.isConfirmed) return;
            const backup = JSON.parse(JSON.stringify(_msList));
            _msList.splice(i, 1);
            const ok = await _msSimpanKeDB('menu_tambahan_list', JSON.stringify(_msList));
            if (!ok) { _msList = backup; return; }
            _msToast('Menu dihapus'); msResetForm(); msRender();
        }

        function msRender() {
            const box = document.getElementById('msList'); if (!box) return;
            box.innerHTML = '';
            if (!_msList.length) { box.innerHTML = '<p class="text-gray-400 text-sm text-center py-8">Belum ada menu. Tambahkan lewat formulir di atas.</p>'; return; }
            _msList.forEach((m, i) => {
                const row = document.createElement('div');
                row.className = 'flex items-center gap-3 border border-gray-100 rounded-xl p-3 bg-gray-50';
                const ic = document.createElement('div');
                ic.className = 'w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center flex-shrink-0';
                if (m.icon === 'link' && _msAman(m.icon_url)) { const im = document.createElement('img'); im.src = m.icon_url; im.className = 'w-6 h-6 object-contain'; im.onerror = () => { ic.innerHTML = '<i class="fas fa-link text-gray-400">
    </i>'; }; ic.appendChild(im); }
                else { const p = _MS_ICON[m.icon] || ['fa-link', 'text-gray-400']; ic.innerHTML = '<i class="fas ' + p[0] + ' ' + p[1] + '">
</i>'; }
                const info = document.createElement('div'); info.className = 'flex-1 min-w-0';
                const n = document.createElement('p'); n.className = 'font-black text-gray-800 text-sm truncate'; n.textContent = m.nama;
                const l = document.createElement('p'); l.className = 'text-[11px] text-blue-600 truncate'; l.textContent = m.link;
                info.appendChild(n); info.appendChild(l);
                const be = document.createElement('button'); be.className = 'text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition'; be.title = 'Edit'; be.innerHTML = '<i class="fas fa-edit">
</i>'; be.onclick = () => msEdit(i);
                const bh = document.createElement('button'); bh.className = 'text-red-500 hover:bg-red-100 p-2 rounded-lg transition'; bh.title = 'Hapus'; bh.innerHTML = '<i class="fas fa-trash-alt">
</i>'; bh.onclick = () => msHapus(i);
                row.appendChild(ic); row.appendChild(info); row.appendChild(be); row.appendChild(bh);
                box.appendChild(row);
            });
        }
    </script>
<script>
async function kembalikanAlumni(id) {
    const a = (globalDataStore['alumni'] || []).find(x => x.id === id); if (!a) return;
    const c = await Swal.fire({ title: 'Kembalikan ke siswa aktif?', text: a.nama + ' akan muncul kembali di Data Master Siswa.', icon: 'question', showCancelButton: true, confirmButtonColor: '#16a34a', confirmButtonText: 'Ya, Kembalikan', cancelButtonText: 'Batal' });
    if (!c.isConfirmed) return;
    try {
        Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        let ada = false;
        if (a.id_siswa_lama) {
            const r = await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'siswa', id: a.id_siswa_lama, payload: { arsip: false, status: 'Aktif' } } });
            ada = Array.isArray(r.data) && r.data.length > 0;
        }
        if (!ada) await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'siswa', payload: { nama: a.nama, nama_panggilan: a.nama_panggilan, nisn: a.nisn, nik: a.nik, kelas: a.kelas_terakhir, tgl_lahir: a.tgl_lahir, no_telp: a.no_telp, total_poin: a.total_poin || 0, login_pin: a.login_pin, security_answer: a.security_answer, status: 'Aktif', arsip: false } } });
        await callBackend({ type: 'supabase_post', action: 'deleteData', payload: { table: 'alumni', id } });
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Dikembalikan ke siswa aktif', showConfirmButton: false, timer: 2000 });
        loadDataTable('alumni', 'alumni');
    } catch (e) { Swal.fire('Gagal', e.message, 'error'); }
}
</script>
<script>
// ============ HELPER MANAJEMEN (dipakai menu Guru & Akademik + Manajemen Database) ============
const _HARI=['','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
const _gid=id=>document.getElementById(id);
const _okT=m=>Swal.fire({toast:true,position:'top-end',icon:'success',title:m,showConfirmButton:false,timer:1800});
const _bt='px-4 py-2.5 rounded-xl text-xs font-bold text-white transition shadow-sm flex items-center gap-2 ';
const _in='px-3 py-2 border-2 border-gray-200 rounded-xl font-bold text-sm bg-white outline-none focus:border-blue-500 ';
async function _ambil(t){const r=await callBackend({type:'supabase_get',action:'getData',payload:{table:t}});return r.data||[];}
const _tm=s=>{const p=String(s||'').split(':');return (+p[0])*60+(+p[1]||0);};
const _fm=m=>String(Math.floor(m/60)).padStart(2,'0')+':'+String(m%60).padStart(2,'0');
const _arr=v=>{try{const x=typeof v==='string'?JSON.parse(v||'[]'):(v||[]);return Array.isArray(x)?x:[];}catch(e){return [];}};
const _tglWib=()=>new Date().toLocaleDateString('en-CA',{timeZone:'Asia/Jakarta'});

// Mesin jam: agenda menggeser jam mulai, istirahat mem-pause hitungan jam mapel
function hitungSlotJam(h,dur){
  if(!h.jam_mulai) return [];
  const ist=_arr(h.istirahat).map(x=>[_tm(x.mulai),_tm(x.selesai)]);
  const ag=_arr(h.agenda).map(x=>[_tm(x.mulai),_tm(x.selesai)]).sort((a,b)=>a[0]-b[0]);
  let cur=_tm(h.jam_mulai); ag.forEach(a=>{if(a[0]<=cur&&a[1]>cur)cur=a[1];});
  const out=[];
  for(let k=1;k<=(parseInt(h.jumlah_jam_mapel)||0);k++){
    ist.forEach(x=>{if(cur>=x[0]&&cur<x[1])cur=x[1];});
    const d=dur[k]||45; out.push({jam_ke:k,mulai:_fm(cur),selesai:_fm(cur+d)}); cur+=d;
  }
  return out;
}

// Halaman non-tabel (hanya Manajemen Database)
function renderManajemen(id){
  _gid('pageTitle').textContent='Manajemen Database';
  _gid('view-manajemen').innerHTML='<p class="text-center text-gray-400 py-10">
<i class="fas fa-spinner fa-spin text-2xl">
</i>
</p>';
  mjDb().catch(e=>{_gid('view-manajemen').innerHTML='<p class="text-red-500 text-center py-6 font-bold">Gagal memuat: '+_escH(e.message)+'</p>';});
}

</script>
<script>
// ============ FRAMEWORK TABEL "GT" — dipakai menu Guru & Akademik ============
// Memakai kerangka #view-datatable yang sama dengan Master Siswa: Ekspor / Manual / Impor / Pilih Semua / Tindakan / checkbox / paging.
var GT = {};
const _x = v => _escH(v);
const _P2 = n => String(n).padStart(2, '0');
const _gtB = (fn, icon, cls, title) => `<button onclick="${fn}" title="${title}" class="${cls} p-2 rounded-lg transition">
<i class="fas ${icon}">
</i>
</button>`;
const _gtLbl = 'block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5';
const _gtIn = 'w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-bold text-gray-800 bg-white outline-none focus:border-blue-500';
const _gtFld = (label, inner, cls) => `<div class="${cls || ''}">
<label class="${_gtLbl}">${label}</label>${inner}</div>`;
const _gtInp = (id, val, ph, type, extra) => `<input type="${type || 'text'}" id="${id}" value="${_x(val == null ? '' : val)}" placeholder="${_x(ph || '')}" ${extra || ''} class="${_gtIn}">`;
const _gtSel = (id, opts, val, extra) => `<select id="${id}" ${extra || ''} class="${_gtIn} cursor-pointer">${opts.map(o => `<option value="${_x(o.v)}" ${String(o.v) === String(val) ? 'selected' : ''}>${_x(o.t)}</option>`).join('')}</select>`;
const _gtChip = (t, cls) => `<span class="inline-block text-[10px] font-black px-2 py-1 rounded ${cls}">${_x(t)}</span>`;
const _gtMin = s => String(s || '').slice(0, 5);
function gtPesan(e) {
    const m = String((e && e.message) || e);
    if (/does not exist|schema cache|Could not find the/i.test(m)) return 'Tabel/kolom database belum lengkap. Jalankan setup_database.sql terbaru di Supabase SQL Editor. Detail: ' + m.slice(0, 180);
    if (/null value in column "id_guru"/i.test(m)) return 'Jalankan setup_database.sql terbaru (migrasi Manajemen Nama Guru: id_guru boleh kosong).';
    if (/duplicate key/i.test(m)) return 'Data dengan nilai unik yang sama sudah ada.';
    if (/tidak dikenali server/i.test(m)) return m + ' — fungsi Netlify notify.js yang aktif belum versi terbaru. Deploy ulang.';
    return m;
}
function gtErr(e, judul) { Swal.fire(judul || 'Gagal', gtPesan(e), 'error'); }
const gtIdsRows = ids => (globalDataStore[currentTableId] || []).filter(r => ids.includes(String(r.id)));
function gtPick(r, ...ks) { for (const k of ks) for (const rk of Object.keys(r)) if (rk.trim().toLowerCase() === k.toLowerCase() && String(r[rk]).trim() !== '') return String(r[rk]).trim(); return ''; }
function gtTglExcel(v) {
    if (v === '' || v == null) return '';
    if (typeof v === 'number') return new Date(Math.round((v - 25569) * 86400000)).toISOString().slice(0, 10);
    const s = String(v).trim(); let m;
    if ((m = s.match(/^(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})/))) return `${m[1]}-${_P2(m[2])}-${_P2(m[3])}`;
    if ((m = s.match(/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/))) return `${m[3]}-${_P2(m[2])}-${_P2(m[1])}`;
    return '';
}
function gtJamExcel(v) {
    if (v === '' || v == null) return null;
    if (typeof v === 'number') { const mnt = Math.round(v * 1440); return _fm(mnt % 1440); }
    const m = String(v).trim().match(/^(\d{1,2})[:.](\d{2})/);
    return m ? `${_P2(m[1])}:${m[2]}` : null;
}

// ---------- autocomplete (saran saat mengetik) ----------
function gtAutocomplete(inpId, getItems, onPick) {
    const inp = _gid(inpId), wrap = inp.parentNode;
    wrap.style.position = 'relative';
    const ul = document.createElement('ul');
    ul.className = 'absolute z-[130] left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-xl shadow-2xl max-h-44 overflow-y-auto hidden divide-y divide-gray-50 text-left';
    wrap.appendChild(ul);
    const show = () => {
        const q = inp.value.trim().toLowerCase(), items = getItems(q).slice(0, 30);
        ul._items = items;
        ul.innerHTML = items.length
            ? items.map((it, i) => `<li data-i="${i}" class="px-4 py-2.5 text-sm ${it.disabled ? 'opacity-50' : 'hover:bg-blue-50 cursor-pointer'}">
<p class="font-bold text-gray-800">${_x(it.label)}</p>${it.sub ? `<p class="text-[10px] text-gray-400">${_x(it.sub)}</p>` : ''}</li>`).join('')
            : `<li class="px-4 py-3 text-xs text-gray-400">${q ? 'Tidak ada saran yang cocok.' : 'Belum ada data.'}</li>`;
        ul.classList.remove('hidden');
    };
    inp.addEventListener('input', () => { onPick(null); show(); });
    inp.addEventListener('focus', show);
    ul.addEventListener('mousedown', e => {
        const li = e.target.closest('li[data-i]'); if (!li) return; e.preventDefault();
        const it = ul._items[+li.dataset.i]; if (!it || it.disabled) return;
        ul.classList.add('hidden'); onPick(it);
    });
    document.addEventListener('mousedown', e => { if (document.body.contains(wrap) && !wrap.contains(e.target)) ul.classList.add('hidden'); });
}

// ---------- modal form ----------
function gtModal(title, sub, html, saveFn, btnLabel) {
    _gid('modalDataTitle').innerHTML = title;
    _gid('modalDataSubtitle').textContent = sub || '';
    const f = _gid('genericDataForm');
    f.innerHTML = html + `<div class="pt-5 mt-4 border-t border-gray-100 flex gap-3 justify-end">
<button type="button" onclick="_gid('modalDataForm').classList.add('hidden')" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-200 transition">Batal</button>
<button type="button" id="gtBtnSimpan" onclick="${saveFn}" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-md flex items-center gap-2">
    <i class="fas fa-save">
    </i> ${btnLabel || 'Simpan'}</button>
</div>`;
    f.onkeydown = e => { if (e.key === 'Enter' && e.target.tagName === 'INPUT') e.preventDefault(); };
    _gid('modalDataForm').classList.remove('hidden');
}
function gtBusy(on) { const b = _gid('gtBtnSimpan'); if (!b) return; b.disabled = on; b.innerHTML = on ? '<i class="fas fa-spinner fa-spin">
</i> Menyimpan...' : '<i class="fas fa-save">
</i> Simpan'; }
function gtTutupModal() { _gid('modalDataForm').classList.add('hidden'); }

// ---------- muat / filter / render ----------
async function gtMuat(id) {
    const cfg = GT[id]; if (!cfg) return;
    currentTableId = id;
    _gid('pageTitle').textContent = cfg.title;
    hideBulkBar();
    let b = `<button onclick="gtEkspor()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
<i class="fas fa-file-excel">
</i> Ekspor</button>`;
    if (cfg.form) b += `<button onclick="gtForm()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
<i class="fas fa-plus">
</i> Manual</button>`;
    if (cfg.impor) b += `<button onclick="gtBukaImpor()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
<i class="fas fa-file-import">
</i> Impor</button>`;
    if (cfg.extraBtn) b += cfg.extraBtn();
    b += `<button onclick="piliSemua()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
<i class="fas fa-check-square">
</i> Pilih Semua</button>
<div id="tindakanDropdownWrapper" class="hidden relative">
</div>`;
    _gid('dtActionsContainer').innerHTML = b;
    _gid('dtSearch').value = '';
    _gid('dtDynamicFilterContainer').innerHTML = cfg.filterHtml ? cfg.filterHtml() : '';
    _gid('dtHead').innerHTML = '';
    _gid('dtBody').innerHTML = '<tr>
<td colspan="12" class="text-center py-10">
    <i class="fas fa-circle-notch fa-spin text-3xl text-blue-500">
    </i>
</td>
</tr>';
    _gid('dtInfo').textContent = 'Memuat...'; _gid('dtPaginationControls').innerHTML = '';
    try {
        const rows = await cfg.load();
        if (currentTableId !== id) return; // pengguna sudah pindah menu
        globalDataStore[id] = rows; currentPage = 1;
        if (cfg.afterLoad) cfg.afterLoad();
        gtFilter();
        if (cfg.setelahTampil) cfg.setelahTampil();
    } catch (e) {
        if (currentTableId !== id) return;
        _gid('dtBody').innerHTML = `<tr>
<td colspan="12" class="text-center py-8 text-red-500 font-bold text-sm px-6">${_x(gtPesan(e))}</td>
</tr>`;
    }
}
function gtFilter() {
    const cfg = GT[currentTableId]; if (!cfg) return;
    const q = (_gid('dtSearch').value || '').toLowerCase().trim();
    currentFilteredData = (globalDataStore[currentTableId] || []).filter(r => (!q || cfg.searchText(r).toLowerCase().includes(q)) && (!cfg.filter || cfg.filter(r)));
    hideBulkBar(); currentPage = 1; gtRender();
}
function gtRender() {
    const cfg = GT[currentTableId]; if (!cfg) return;
    const rows = currentFilteredData; window._displayData = rows;
    const per = cfg.pageSize || 15, total = rows.length, pages = Math.ceil(total / per) || 1;
    if (currentPage > pages) currentPage = pages;
    const st = (currentPage - 1) * per, en = st + per;
    _gid('dtHead').innerHTML = '<tr>
<th class="py-3 w-12 text-center px-3 bg-blue-50/50">
    <input type="checkbox" class="w-5 h-5 rounded border-2 border-gray-300 text-blue-600 cursor-pointer accent-blue-600" id="checkAllMain" onclick="toggleCheckAll(this)">
</th>'
        + cfg.cols.map(c => `<th class="px-5 py-4 ${c.cls || ''}">${c.label}</th>`).join('') + '<th class="px-5 py-4 text-right">AKSI</th>
</tr>';
    const body = _gid('dtBody');
    if (!total) {
        body.innerHTML = `<tr>
<td colspan="${cfg.cols.length + 2}" class="text-center py-10 text-gray-500">
    <i class="fas fa-folder-open text-4xl mb-3 block text-gray-300">
    </i>${cfg.kosong || 'Data belum ada. Klik <b>Manual</b> atau <b>Impor</b> untuk menambah.'}</td>
</tr>`;
    } else {
        body.innerHTML = rows.slice(st, en).map(r => `<tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-50 ${cfg.rowCls ? cfg.rowCls(r) : ''}">
<td class="px-3 py-4 text-center w-12">
    <input type="checkbox" class="bulk-check w-5 h-5 rounded border-2 border-gray-300 text-blue-600 cursor-pointer accent-blue-600" value="${_x(r.id)}" onchange="updateBulkBar()">
</td>`
            + cfg.cols.map(c => `<td class="px-5 py-4 ${c.cls || ''}">${c.r(r)}</td>`).join('')
            + `<td class="px-5 py-4 text-right whitespace-nowrap">${cfg.aksi(r)}</td>
</tr>`).join('');
    }
    _gid('dtInfo').textContent = cfg.info ? cfg.info(rows) : (total ? `Baris ${st + 1} - ${Math.min(en, total)} dari ${total}` : 'Menampilkan 0 data');
    _gid('dtPaginationControls').innerHTML = total > per
        ? `<button onclick="changePage(-1)" class="px-3 py-1.5 rounded-lg border bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-50" ${currentPage === 1 ? 'disabled' : ''}>
<i class="fas fa-chevron-left">
</i>
</button>
<span class="text-xs font-bold px-2">Hal ${currentPage}/${pages}</span>
<button onclick="changePage(1)" class="px-3 py-1.5 rounded-lg border bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-50" ${currentPage === pages ? 'disabled' : ''}>
    <i class="fas fa-chevron-right">
    </i>
</button>` : '';
    _terapkanSelKeDOM(); updateBulkBar();
}
function gtUpdateBulkBar() {
    const cfg = GT[currentTableId];
    const cs = [...document.querySelectorAll('.bulk-check')], cm = _gid('checkAllMain');
    if (cm) cm.checked = cs.length > 0 && cs.every(c => c.checked);
    const ids = getCheckedIds(), bar = _gid('bulkActionBar'), wrap = _gid('tindakanDropdownWrapper');
    if (!bar) return;
    if (!ids.length) { bar.classList.add('hidden'); if (wrap) wrap.classList.add('hidden'); return; }
    bar.classList.remove('hidden'); _gid('bulkCount').textContent = ids.length;
    if (!wrap) return;
    wrap.classList.remove('hidden');
    const dCls = 'w-full text-left px-4 py-2.5 text-xs font-bold hover:bg-gray-100 flex items-center gap-2 transition whitespace-nowrap';
    const items = cfg.tindakan.map(t => t === '-' ? '<hr class="my-1 border-gray-100">' : `<button onclick="gtTindakan('${t.fn}');closeTindakanDrop()" class="${dCls} ${t.cls}">
<i class="fas ${t.icon} w-4">
</i> ${t.label} (${ids.length})</button>`).join('');
    wrap.innerHTML = `<button onclick="toggleTindakanDrop(event)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm select-none">
<i class="fas fa-bolt">
</i> Tindakan <i class="fas fa-chevron-down text-[9px] ml-0.5" id="tindakanChevron">
</i>
</button>
<div id="tindakanDropMenu" class="hidden absolute top-full left-0 mt-1 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 min-w-[240px] z-[80]">${items}</div>`;
}
async function gtTindakan(fn) {
    const cfg = GT[currentTableId], ids = getCheckedIds(); if (!ids.length) return;
    try { await cfg.massal[fn](ids, gtIdsRows(ids)); } catch (e) { gtErr(e); }
}
function gtForm(id) { const cfg = GT[currentTableId]; if (cfg && cfg.form) Promise.resolve(cfg.form(id)).catch(e => gtErr(e)); }
async function gtHapus(id) {
    const cfg = GT[currentTableId]; const r = gtIdsRows([String(id)]);
    if (!r.length) return;
    await gtHapusMassal([String(id)], r);
}
async function gtHapusMassal(ids, rows) {
    const cfg = GT[currentTableId];
    rows = (rows || gtIdsRows(ids)).filter(r => !r._virtual);
    if (!rows.length) return Swal.fire('Tidak ada data', 'Tidak ada baris tersimpan yang dipilih.', 'info');
    const c = await Swal.fire({ title: `Hapus ${rows.length} data?`, text: cfg.hapusCatatan || 'Data yang dihapus tidak bisa dikembalikan.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal' });
    if (!c.isConfirmed) return;
    Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    let ok = 0, gagal = [];
    for (const r of rows) {
        try { if (cfg.sebelumHapus) await cfg.sebelumHapus(r); await callBackend({ type: 'supabase_post', action: 'deleteData', payload: { table: cfg.table, id: r.id } }); ok++; }
        catch (e) { gagal.push(gtPesan(e)); }
    }
    Swal.close();
    if (gagal.length) Swal.fire('Sebagian gagal', `${ok} terhapus, ${gagal.length} gagal.<br>
<span class="text-xs">${_x(gagal[0])}</span>`, 'warning'); else _okT(`${ok} data dihapus`);
    gtMuat(currentTableId);
}

// ---------- ekspor / impor ----------
function gtEkspor() {
    const cfg = GT[currentTableId], rows = currentFilteredData;
    if (!rows || !rows.length) return Swal.fire('Kosong', 'Tidak ada data untuk diekspor.', 'info');
    const ws = XLSX.utils.json_to_sheet(rows.map(cfg.exportRow));
    const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, 'Data');
    XLSX.writeFile(wb, `${cfg.title.replace(/\s+/g, '_')}_${_tglWib()}.xlsx`);
}
function gtBukaImpor() {
    const cfg = GT[currentTableId];
    bukaModalImport(currentTableId);
    _gid('importTitle').textContent = 'Impor ' + cfg.title;
    _gid('importDesc').textContent = cfg.imporInfo || '';
    _gid('fileImportExcel').value = ''; _gid('importFileName').textContent = 'Belum ada file';
}
var _gtDownloadTemplateAsli = downloadTemplate;
downloadTemplate = function () {
    const t = _gid('importTypeVal').value, cfg = GT[t];
    if (!cfg || !cfg.template) return _gtDownloadTemplateAsli();
    const ws = XLSX.utils.aoa_to_sheet([cfg.template.head].concat(cfg.template.contoh || []));
    const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, 'Format');
    XLSX.writeFile(wb, 'Template_' + cfg.title.replace(/\s+/g, '_') + '.xlsx');
};
var _gtProsesImporAsli = prosesImportExcel;
prosesImportExcel = function () {
    const t = _gid('importTypeVal').value;
    if (!GT[t] || !GT[t].impor) return _gtProsesImporAsli();
    const f = _gid('fileImportExcel').files[0];
    if (!f) { Swal.fire('Pilih file', 'Pilih file Excel (.xlsx) terlebih dahulu.', 'info'); return; }
    const rd = new FileReader();
    rd.onload = async e => {
        try {
            const wb = XLSX.read(new Uint8Array(e.target.result), { type: 'array' });
            const js = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]], { defval: '' });
            if (!js.length) return Swal.fire('Kosong', 'File tidak berisi data.', 'warning');
            Swal.fire({ title: 'Mengimpor...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            const r = await GT[t].impor(js);
            _gid('modalImport').classList.add('hidden');
            const err = r.err || [];
            Swal.fire({ icon: r.ok + r.rep === 0 ? 'warning' : 'success', title: 'Impor selesai', html: `<p class="text-sm">✅ ${r.ok} ditambahkan · 🔄 ${r.rep} diperbarui · ⏭️ ${r.skip || 0} dilewati</p>${err.length ? `<div class="text-left text-xs bg-red-50 border border-red-200 rounded-xl p-3 mt-3 max-h-40 overflow-y-auto">
<p class="font-bold text-red-700 mb-1">${err.length} catatan:</p>${err.slice(0, 12).map(x => `<p>• ${_x(x)}</p>`).join('')}${err.length > 12 ? `<p>...dan ${err.length - 12} lainnya</p>` : ''}</div>` : ''}`, confirmButtonColor: '#2563eb' });
            gtMuat(t);
        } catch (err) { gtErr(err, 'Impor gagal'); }
    };
    rd.readAsArrayBuffer(f);
};

// ---------- sambungan ke fungsi lama (menu lama tetap jalan) ----------
var _gtUbbAsli = updateBulkBar;
updateBulkBar = function () { if (GT[currentTableId]) return gtUpdateBulkBar(); return _gtUbbAsli(); };
var _gtRdtbAsli = renderDataTableBody;
renderDataTableBody = function () { if (GT[currentTableId]) return gtRender(); return _gtRdtbAsli(); };
var _gtFtlAsli = filterTableLokal;
filterTableLokal = function () { if (GT[currentTableId]) return gtFilter(); return _gtFtlAsli(); };
var _gtSvdAsli = switchViewDemo;
switchViewDemo = function (id) { if (GT[id]) { Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: 'Menu ini tidak tersedia di mode demo', showConfirmButton: false, timer: 2000 }); return; } return _gtSvdAsli(id); };

// ============ MANAJEMEN NAMA GURU ============
GT.guru_nama = {
    title: 'Manajemen Nama Guru', table: 'manajemen_nama_guru',
    kosong: 'Belum ada nama guru. Klik <b>Manual</b> / <b>Impor</b>. Nama di sini nanti muncul sebagai saran saat membuat akun di Master Akun &amp; Guru.',
    async load() {
        const [n, g] = await Promise.all([_ambil('manajemen_nama_guru?select=*&order=nama_lengkap.asc'), _ambil('master_guru?select=id,status')]);
        const gm = {}; g.forEach(x => { gm[x.id] = x; });
        n.forEach(r => { r._akun = r.id_guru && gm[r.id_guru] ? (gm[r.id_guru].status || 'Aktif') : null; });
        return n;
    },
    searchText: r => [r.nama_lengkap, r.nama_panggilan, r.nip, r.no_wa].join(' '),
    filterHtml: () => `<select id="gnFilter" onchange="filterTableLokal()" class="px-4 py-2 border-2 border-gray-200 rounded-xl text-sm font-bold bg-white focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
<option value="">Semua</option>
<option value="ya">Sudah punya akun</option>
<option value="tidak">Belum punya akun</option>
</select>`,
    filter: r => { const v = (_gid('gnFilter') || {}).value; return !v || (v === 'ya' ? !!r._akun : !r._akun); },
    cols: [
        { label: 'NAMA GURU', r: r => `<p class="font-bold text-gray-800">${_x(r.nama_lengkap)}</p>
<p class="text-[10px] text-gray-400">${_x(r.nama_panggilan || '')}</p>` },
        { label: 'NIP', r: r => `<span class="font-mono text-xs text-gray-600">${_x(r.nip || '—')}</span>` },
        { label: 'WHATSAPP', r: r => `<span class="text-xs text-gray-600">${_x(r.no_wa || '—')}</span>` },
        { label: 'AKUN PORTAL GURU', r: r => r._akun ? _gtChip('Sudah punya akun', 'bg-green-100 text-green-700') : _gtChip('Belum punya akun', 'bg-gray-100 text-gray-500') }
    ],
    aksi: r => _gtB(`gtForm('${_x(r.id)}')`, 'fa-edit', 'text-blue-500 hover:bg-blue-100', 'Edit') + _gtB(`gtHapus('${_x(r.id)}')`, 'fa-trash-alt', 'text-red-500 hover:bg-red-100', 'Hapus'),
    tindakan: [{ fn: 'hapus', label: 'Hapus', icon: 'fa-trash-alt', cls: 'text-red-600' }],
    massal: { hapus: (ids, rows) => gtHapusMassal(ids, rows) },
    hapusCatatan: 'Hanya hilang dari daftar nama. Akun di Master Akun & Guru (jika ada) tidak ikut terhapus.',
    exportRow: r => ({ 'Nama Lengkap': r.nama_lengkap, 'Nama Panggilan': r.nama_panggilan || '', 'NIP': r.nip || '', 'No WA': r.no_wa || '', 'Punya Akun': r._akun ? 'Ya' : 'Tidak' }),
    form(id) {
        const r = id ? globalDataStore.guru_nama.find(x => x.id === id) : {};
        window._gnEditId = id || '';
        gtModal('<i class="fas fa-id-card text-indigo-600 mr-2">
</i>' + (id ? 'Edit' : 'Tambah') + ' Nama Guru', 'Manajemen Nama Guru',
            `<div class="space-y-4">${_gtFld('Nama Lengkap <span class="text-red-500">*</span>', _gtInp('gnNama', r.nama_lengkap, 'Nama lengkap + gelar'))}
            ${_gtFld('Nama Panggilan', _gtInp('gnPang', r.nama_panggilan, 'Contoh: Pak Budi'))}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">${_gtFld('NIP', _gtInp('gnNip', r.nip, 'Nomor Induk Pegawai'))}${_gtFld('No WhatsApp', _gtInp('gnWa', r.no_wa, '628xxxxxxxxxx'))}</div>
</div>`, 'gnSimpan()');
    },
    template: { head: ['Nama Lengkap', 'Nama Panggilan', 'NIP', 'No WA'], contoh: [['Budi Santoso, S.Pd', 'Pak Budi', '198001012005011001', '628123456789']] },
    imporInfo: 'Kolom: Nama Lengkap | Nama Panggilan | NIP | No WA. Nama yang sudah ada akan diperbarui.',
    async impor(js) {
        const ada = await _ambil('manajemen_nama_guru?select=*'); let ok = 0, rep = 0, skip = 0; const err = [];
        for (const [i, row] of js.entries()) {
            const nama = gtPick(row, 'Nama Lengkap', 'Nama'); if (!nama) { skip++; err.push(`Baris ${i + 2}: nama kosong`); continue; }
            const b = { nama_lengkap: nama, nama_panggilan: gtPick(row, 'Nama Panggilan', 'Panggilan') || null, nip: gtPick(row, 'NIP') || null, no_wa: gtPick(row, 'No WA', 'WA', 'WhatsApp') || null };
            const ex = ada.find(x => x.nama_lengkap.trim().toLowerCase() === nama.toLowerCase());
            try {
                if (ex) { await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'manajemen_nama_guru', id: ex.id, payload: b } }); if (ex.id_guru) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'master_guru', id: ex.id_guru, payload: b } }); rep++; }
                else { const r = await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'manajemen_nama_guru', payload: b } }); ada.push((r.data && r.data[0]) || b); ok++; }
            } catch (e) { err.push(`Baris ${i + 2} (${nama}): ${gtPesan(e)}`); }
        }
        return { ok, rep, skip, err };
    }
};
async function gnSimpan() {
    const nama = _gid('gnNama').value.trim(); if (!nama) return Swal.fire('Lengkapi', 'Nama lengkap wajib diisi.', 'warning');
    const id = window._gnEditId, rows = globalDataStore.guru_nama || [];
    if (rows.some(x => x.id !== id && x.nama_lengkap.trim().toLowerCase() === nama.toLowerCase())) return Swal.fire('Nama sudah ada', 'Nama guru tersebut sudah ada di daftar.', 'warning');
    const b = { nama_lengkap: nama, nama_panggilan: _gid('gnPang').value.trim() || null, nip: _gid('gnNip').value.trim() || null, no_wa: _gid('gnWa').value.trim() || null };
    gtBusy(true);
    try {
        if (id) {
            await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'manajemen_nama_guru', id, payload: b } });
            const ex = rows.find(x => x.id === id);
            if (ex && ex.id_guru) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'master_guru', id: ex.id_guru, payload: b } });
        } else await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'manajemen_nama_guru', payload: b } });
        gtTutupModal(); _okT('Tersimpan'); gtMuat('guru_nama');
    } catch (e) { gtErr(e); } finally { gtBusy(false); }
}

// ============ MASTER AKUN & GURU (login Portal Guru memakai NAMA + password) ============
async function gtSinkronNama(guruId, d, picked) {
    const names = await _ambil('manajemen_nama_guru?select=*');
    let row = names.find(n => n.id_guru === guruId);
    if (!row && picked) row = names.find(n => n.id === picked.id);
    if (!row) row = names.find(n => !n.id_guru && n.nama_lengkap.trim().toLowerCase() === d.nama_lengkap.trim().toLowerCase());
    const b = { nama_lengkap: d.nama_lengkap, nama_panggilan: d.nama_panggilan || null, nip: d.nip || null, no_wa: d.no_wa || null, id_guru: guruId };
    if (row) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'manajemen_nama_guru', id: row.id, payload: b } });
    else await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'manajemen_nama_guru', payload: b } });
}
async function gtSetStatusGuru(rows, status) {
    for (const r of rows) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'master_guru', id: r.id, payload: { status } } });
}
GT.guru = {
    title: 'Master Akun & Guru', table: 'master_guru',
    kosong: 'Belum ada akun guru. Klik <b>Manual</b> (ketik sebagian nama → pilih saran dari Manajemen Nama Guru) atau <b>Impor</b>.',
    async load() {
        const [g, pw, names] = await Promise.all([
            _ambil('master_guru?select=id,nama_lengkap,nama_panggilan,nip,no_wa,kelas_diajarkan,mapel,status&order=nama_lengkap.asc'),
            _ambil('master_guru?select=id&password_hash=not.is.null'), _ambil('manajemen_nama_guru?select=id,id_guru')]);
        const pwSet = new Set(pw.map(x => x.id)), nSet = new Set(names.map(x => x.id_guru));
        g.forEach(r => { r._pw = pwSet.has(r.id); r._mapel = _arr(r.mapel); r._kelas = _arr(r.kelas_diajarkan); r.status = r.status || 'Aktif'; r._nama = nSet.has(r.id); });
        return g;
    },
    searchText: r => [r.nama_lengkap, r.nama_panggilan, r.nip, r.no_wa, r._mapel.join(' '), r._kelas.join(' ')].join(' '),
    filterHtml: () => `<select id="ggStatus" onchange="filterTableLokal()" class="px-4 py-2 border-2 border-gray-200 rounded-xl text-sm font-bold bg-white focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
<option value="">Semua status</option>
<option>Aktif</option>
<option>Nonaktif</option>
<option>Diblokir</option>
</select>`,
    filter: r => { const v = (_gid('ggStatus') || {}).value; return !v || r.status === v; },
    cols: [
        { label: 'NAMA GURU', r: r => `<p class="font-bold text-gray-800">${_x(r.nama_lengkap)}</p>
<p class="text-[10px] text-gray-400">${_x(r.nama_panggilan || '')}${r._nama ? '' : ' • <span class="text-orange-500 font-bold">belum ada di Manajemen Nama</span>'}</p>` },
        { label: 'NIP / WA', r: r => `<p class="font-mono text-xs text-gray-600">${_x(r.nip || '—')}</p>
<p class="text-[10px] text-gray-400">${_x(r.no_wa || '—')}</p>` },
        { label: 'MATA PELAJARAN', cls: 'whitespace-normal min-w-[160px]', r: r => r._mapel.length ? r._mapel.map(m => `<span class="inline-block bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded-full mr-1 mb-1">${_x(m)}</span>`).join('') : '<span class="text-gray-300 text-xs">—</span>' },
        { label: 'KELAS', cls: 'whitespace-normal min-w-[140px]', r: r => r._kelas.length ? `<span class="text-xs text-gray-600" title="${_x(r._kelas.join(', '))}">${_x(r._kelas.slice(0, 3).join(', '))}${r._kelas.length > 3 ? ` <b class="text-blue-600">+${r._kelas.length - 3}</b>` : ''}</span>` : '<span class="text-gray-300 text-xs">—</span>' },
        { label: 'PASSWORD', r: r => r._pw ? _gtChip('Terpasang', 'bg-green-100 text-green-700') : _gtChip('Belum ada', 'bg-red-100 text-red-600') },
        { label: 'STATUS', r: r => _gtChip(r.status, r.status === 'Aktif' ? 'bg-green-100 text-green-700' : (r.status === 'Diblokir' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-500')) }
    ],
    aksi: r => _gtB(`gtForm('${_x(r.id)}')`, 'fa-edit', 'text-blue-500 hover:bg-blue-100', 'Edit')
        + _gtB(`ggToggle('${_x(r.id)}')`, r.status === 'Diblokir' ? 'fa-unlock-alt' : 'fa-ban', r.status === 'Diblokir' ? 'text-green-500 hover:bg-green-100' : 'text-yellow-500 hover:bg-yellow-100', r.status === 'Diblokir' ? 'Buka blokir' : 'Blokir')
        + _gtB(`gtHapus('${_x(r.id)}')`, 'fa-trash-alt', 'text-red-500 hover:bg-red-100', 'Hapus'),
    tindakan: [
        { fn: 'blokir', label: 'Blokir Akses', icon: 'fa-ban', cls: 'text-yellow-600' },
        { fn: 'buka', label: 'Buka Blokir / Aktifkan', icon: 'fa-unlock', cls: 'text-green-600' },
        { fn: 'nonaktif', label: 'Nonaktifkan', icon: 'fa-user-slash', cls: 'text-gray-600' }, '-',
        { fn: 'hapus', label: 'Hapus Akun Guru', icon: 'fa-trash-alt', cls: 'text-red-600' }],
    massal: {
        blokir: async (ids, rows) => { await gtSetStatusGuru(rows, 'Diblokir'); _okT(rows.length + ' guru diblokir'); gtMuat('guru'); },
        buka: async (ids, rows) => { await gtSetStatusGuru(rows, 'Aktif'); _okT(rows.length + ' guru diaktifkan'); gtMuat('guru'); },
        nonaktif: async (ids, rows) => { await gtSetStatusGuru(rows, 'Nonaktif'); _okT(rows.length + ' guru dinonaktifkan'); gtMuat('guru'); },
        hapus: (ids, rows) => gtHapusMassal(ids, rows)
    },
    hapusCatatan: 'Akun guru + jadwal mengajarnya ikut dihapus. Nama di Manajemen Nama Guru tetap ada (hanya putus tautan akun).',
    async sebelumHapus(r) {
        try { await callBackend({ type: 'supabase_post', action: 'deleteData', payload: { table: 'jadwal_mapel', id: r.id, useField: 'id_guru' } }); } catch (e) {}
        try { await callBackend({ type: 'supabase_post', action: 'updateWhere', payload: { table: 'manajemen_nama_guru', where: 'id_guru=eq.' + r.id, payload: { id_guru: null } } }); } catch (e) {}
        try { await callBackend({ type: 'supabase_post', action: 'deleteData', payload: { table: 'manajemen_guru', id: r.id, useField: 'id_guru' } }); } catch (e) {}
    },
    exportRow: r => ({ 'Nama Lengkap': r.nama_lengkap, 'Nama Panggilan': r.nama_panggilan || '', 'NIP': r.nip || '', 'No WA': r.no_wa || '', 'Mata Pelajaran': r._mapel.join(', '), 'Kelas': r._kelas.join(', '), 'Password': r._pw ? 'Terpasang' : 'Belum ada', 'Status': r.status }),
    async form(id) {
        const ex = id ? globalDataStore.guru.find(x => x.id === id) : {};
        await _siapkanFormGuru(ex || {});
        window._gtNamaList = await _ambil('manajemen_nama_guru?select=*&order=nama_lengkap.asc');
        window._ggEditId = id || ''; window._ggPicked = null;
        gtModal('<i class="fas fa-chalkboard-teacher text-indigo-600 mr-2">
</i>' + (id ? 'Edit' : 'Tambah') + ' Akun Guru', 'Login Portal Guru memakai nama + password',
            `<div class="space-y-4">
            ${_gtFld('Nama Lengkap <span class="text-red-500">*</span>', _gtInp('ggNama', ex.nama_lengkap, 'Ketik sebagian nama, lalu pilih saran...', 'text', 'autocomplete="off"') + '<p class="text-[10px] text-gray-400 mt-1">Saran berasal dari <b>Manajemen Nama Guru</b>. Nama yang belum ada otomatis ditambahkan ke sana.</p>')}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">${_gtFld('Nama Panggilan', _gtInp('ggPang', ex.nama_panggilan, 'Pak/Bu ...'))}${_gtFld('NIP', _gtInp('ggNip', ex.nip, 'Nomor Induk Pegawai'))}</div>
            ${_gtFld('No WhatsApp', _gtInp('ggWa', ex.no_wa, '628xxxxxxxxxx'))}
            ${_gtFld('Password ' + (id ? '<span class="normal-case font-normal text-gray-400">(kosongkan jika tidak diubah)</span>' : '<span class="text-red-500">*</span>'), _gtInp('ggPass', '', id ? 'Biarkan kosong jika tidak diubah' : 'Minimal 6 karakter', 'password', 'autocomplete="new-password"'))}
            ${_gtFld('Mata Pelajaran <span class="normal-case font-normal text-gray-400">(maks. 5, dari Master Mapel)</span>', `<div class="flex gap-2 mb-2">
<select id="fmGuruMapelPick" class="${_gtIn} cursor-pointer flex-1">${_guruOpsi('mapel')}</select>
<button type="button" onclick="guruTambahPilihan('mapel')" class="px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black">Tambah</button>
</div>
<div id="fmGuruMapelChips" class="flex flex-wrap gap-2">${_guruChips('mapel')}</div>`)}
            ${_gtFld('Kelas Diajarkan <span class="normal-case font-normal text-gray-400">(maks. 15, dari Master Kelas)</span>', `<div class="flex gap-2 mb-2">
<select id="fmGuruKelasPick" class="${_gtIn} cursor-pointer flex-1">${_guruOpsi('kelas')}</select>
<button type="button" onclick="guruTambahPilihan('kelas')" class="px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black">Tambah</button>
</div>
<div id="fmGuruKelasChips" class="flex flex-wrap gap-2">${_guruChips('kelas')}</div>`)}
            ${_gtFld('Status', _gtSel('ggStatus2', [{ v: 'Aktif', t: 'Aktif' }, { v: 'Nonaktif', t: 'Nonaktif' }, { v: 'Diblokir', t: 'Diblokir (tidak bisa login)' }], ex.status || 'Aktif'))}</div>`, 'ggSimpan()');
        gtAutocomplete('ggNama', q => (window._gtNamaList || []).filter(n => !q || n.nama_lengkap.toLowerCase().includes(q) || (n.nama_panggilan || '').toLowerCase().includes(q)).map(n => ({
            label: n.nama_lengkap, sub: [n.nip ? 'NIP ' + n.nip : '', n.no_wa || '', n.id_guru && n.id_guru !== window._ggEditId ? '— sudah punya akun' : ''].filter(Boolean).join(' • '),
            disabled: !!(n.id_guru && n.id_guru !== window._ggEditId), raw: n
        })), it => {
            window._ggPicked = it ? it.raw : null;
            if (it) { _gid('ggNama').value = it.raw.nama_lengkap; _gid('ggPang').value = it.raw.nama_panggilan || ''; _gid('ggNip').value = it.raw.nip || ''; _gid('ggWa').value = it.raw.no_wa || ''; }
        });
    },
    template: { head: ['Nama Lengkap', 'Nama Panggilan', 'NIP', 'No WA', 'Password', 'Mata Pelajaran', 'Kelas', 'Status'], contoh: [['Budi Santoso, S.Pd', 'Pak Budi', '198001012005011001', '628123456789', 'rahasia123', 'Matematika, Fisika', 'X RPL 1, X RPL 2', 'Aktif']] },
    imporInfo: 'Kolom: Nama Lengkap | Nama Panggilan | NIP | No WA | Password | Mata Pelajaran (pisah koma) | Kelas (pisah koma) | Status. Nama yang sudah ada diperbarui (password hanya diganti jika diisi). Mapel/kelas harus ada di master.',
    async impor(js) {
        const [mp, kl, ada] = await Promise.all([_ambil('master_mapel?select=nama_mapel'), _ambil('daftar_kelas?select=nama_kelas'), _ambil('master_guru?select=id,nama_lengkap')]);
        const mapelM = mp.map(x => x.nama_mapel), kelasM = kl.map(x => x.nama_kelas);
        const cocok = (daftar, v) => daftar.find(d => String(d).trim().toLowerCase() === v.trim().toLowerCase());
        let ok = 0, rep = 0, skip = 0; const err = [];
        for (const [i, row] of js.entries()) {
            const nama = gtPick(row, 'Nama Lengkap', 'Nama'); if (!nama) { skip++; err.push(`Baris ${i + 2}: nama kosong`); continue; }
            const pilih = (kolom, master, maks, label) => {
                const hasil = [];
                gtPick(row, ...kolom).split(/[,;]/).map(s => s.trim()).filter(Boolean).forEach(v => { const c = cocok(master, v); if (!c) err.push(`Baris ${i + 2} (${nama}): ${label} "${v}" tidak ada di master — dilewati`); else if (!hasil.includes(c)) hasil.push(c); });
                if (hasil.length > maks) { err.push(`Baris ${i + 2} (${nama}): ${label} dibatasi ${maks}`); hasil.length = maks; }
                return hasil;
            };
            const p = { nama_lengkap: nama, nama_panggilan: gtPick(row, 'Nama Panggilan', 'Panggilan'), nip: gtPick(row, 'NIP'), no_wa: gtPick(row, 'No WA', 'WA', 'WhatsApp'), status: ['Aktif', 'Nonaktif', 'Diblokir'].includes(gtPick(row, 'Status')) ? gtPick(row, 'Status') : 'Aktif',
                mapel: JSON.stringify(pilih(['Mata Pelajaran', 'Mapel'], mapelM, 5, 'Mapel')), kelas_diajarkan: JSON.stringify(pilih(['Kelas'], kelasM, 15, 'Kelas')) };
            const pass = gtPick(row, 'Password'); if (pass) { if (pass.length < 6) { err.push(`Baris ${i + 2} (${nama}): password < 6 karakter, tidak dipasang`); } else p.plain_password = pass; }
            try {
                const ex = ada.find(x => x.nama_lengkap.trim().toLowerCase() === nama.toLowerCase());
                let gid;
                if (ex) { await callBackend({ type: 'supabase_post', action: 'updateGuru', payload: { id: ex.id, payload: p } }); gid = ex.id; rep++; }
                else { const r = await callBackend({ type: 'supabase_post', action: 'insertGuru', payload: { payload: p } }); gid = r.data && r.data[0] && r.data[0].id; ada.push({ id: gid, nama_lengkap: nama }); ok++; if (!pass) err.push(`Baris ${i + 2} (${nama}): akun dibuat tanpa password — isi lewat Edit`); }
                if (gid) await gtSinkronNama(gid, p);
            } catch (e) { err.push(`Baris ${i + 2} (${nama}): ${gtPesan(e)}`); }
        }
        return { ok, rep, skip, err };
    }
};
async function ggToggle(id) {
    const r = globalDataStore.guru.find(x => x.id === id); if (!r) return;
    const blok = r.status !== 'Diblokir';
    const c = await Swal.fire({ title: blok ? 'Blokir guru ini?' : 'Buka blokir guru ini?', text: blok ? 'Guru tidak bisa login ke Portal Guru.' : 'Guru bisa login kembali.', icon: 'warning', showCancelButton: true, confirmButtonColor: blok ? '#f59e0b' : '#22c55e', confirmButtonText: blok ? 'Ya, Blokir' : 'Ya, Buka Blokir', cancelButtonText: 'Batal' });
    if (!c.isConfirmed) return;
    try { await gtSetStatusGuru([r], blok ? 'Diblokir' : 'Aktif'); _okT(blok ? 'Guru diblokir' : 'Blokir dibuka'); gtMuat('guru'); } catch (e) { gtErr(e); }
}
async function ggSimpan() {
    const nama = _gid('ggNama').value.trim(), id = window._ggEditId, pass = _gid('ggPass').value;
    if (!nama) return Swal.fire('Lengkapi', 'Nama lengkap wajib diisi.', 'warning');
    if (!id && pass.length < 6) return Swal.fire('Password', 'Password minimal 6 karakter.', 'warning');
    if (id && pass && pass.length < 6) return Swal.fire('Password', 'Password baru minimal 6 karakter.', 'warning');
    if ((globalDataStore.guru || []).some(x => x.id !== id && x.nama_lengkap.trim().toLowerCase() === nama.toLowerCase())) return Swal.fire('Nama sudah dipakai', 'Sudah ada akun guru dengan nama ini.', 'warning');
    const d = { nama_lengkap: nama, nama_panggilan: _gid('ggPang').value.trim(), nip: _gid('ggNip').value.trim(), no_wa: _gid('ggWa').value.trim() };
    const p = Object.assign({}, d, { status: _gid('ggStatus2').value, mapel: JSON.stringify(window._guruMapelSel || []), kelas_diajarkan: JSON.stringify(window._guruKelasSel || []) });
    if (pass) p.plain_password = pass;
    gtBusy(true);
    try {
        let gid = id;
        if (!id) { const r = await callBackend({ type: 'supabase_post', action: 'insertGuru', payload: { payload: p } }); gid = r.data && r.data[0] && r.data[0].id; if (!gid) throw new Error('Server tidak mengembalikan ID guru baru.'); }
        else await callBackend({ type: 'supabase_post', action: 'updateGuru', payload: { id, payload: p } });
        await gtSinkronNama(gid, d, window._ggPicked);
        gtTutupModal(); _okT(id ? 'Akun guru diperbarui' : 'Akun guru dibuat'); gtMuat('guru');
    } catch (e) { gtErr(e); } finally { gtBusy(false); }
}

// ============ MASTER MAPEL ============
GT.mapel = {
    title: 'Master Mapel', table: 'master_mapel',
    async load() {
        const [m, g, j] = await Promise.all([_ambil('master_mapel?select=*&order=nama_mapel.asc'), _ambil('master_guru?select=id,mapel'), _ambil('jadwal_mapel?select=id_mapel')]);
        m.forEach(r => {
            r._guru = g.filter(x => _arr(x.mapel).includes(r.nama_mapel)).length;
            r._jadwal = j.filter(x => x.id_mapel === r.id).length;
            r._aktif = r.aktif !== false;
        });
        return m;
    },
    searchText: r => [r.nama_mapel, r.kode_mapel].join(' '),
    filterHtml: () => `<select id="gmStatus" onchange="filterTableLokal()" class="px-4 py-2 border-2 border-gray-200 rounded-xl text-sm font-bold bg-white focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
<option value="">Semua status</option>
<option value="1">Aktif</option>
<option value="0">Nonaktif</option>
</select>`,
    filter: r => { const v = (_gid('gmStatus') || {}).value; return !v || (v === '1') === r._aktif; },
    cols: [
        { label: 'NAMA MAPEL', r: r => `<p class="font-bold text-gray-800">${_x(r.nama_mapel)}</p>` },
        { label: 'KODE', r: r => r.kode_mapel ? _gtChip(r.kode_mapel, 'bg-blue-100 text-blue-700') : '<span class="text-gray-300">—</span>' },
        { label: 'GURU PENGAMPU', r: r => { const over = r.max_guru != null && r._guru > r.max_guru; return `<span class="text-xs font-bold ${over ? 'text-red-600' : 'text-gray-700'}">${r._guru} / ${r.max_guru == null ? '∞' : r.max_guru}</span>`; } },
        { label: 'DIPAKAI JADWAL', r: r => `<span class="text-xs text-gray-500">${r._jadwal} jadwal</span>` },
        { label: 'STATUS', r: r => r._aktif ? _gtChip('Aktif', 'bg-green-100 text-green-700') : _gtChip('Nonaktif', 'bg-red-100 text-red-500') }
    ],
    aksi: r => _gtB(`gtForm('${_x(r.id)}')`, 'fa-edit', 'text-blue-500 hover:bg-blue-100', 'Edit') + _gtB(`gtHapus('${_x(r.id)}')`, 'fa-trash-alt', 'text-red-500 hover:bg-red-100', 'Hapus'),
    tindakan: [{ fn: 'aktif', label: 'Aktifkan', icon: 'fa-check-circle', cls: 'text-green-600' }, { fn: 'nonaktif', label: 'Nonaktifkan', icon: 'fa-ban', cls: 'text-yellow-600' }, '-', { fn: 'hapus', label: 'Hapus', icon: 'fa-trash-alt', cls: 'text-red-600' }],
    massal: {
        aktif: async (ids, rows) => { for (const r of rows) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'master_mapel', id: r.id, payload: { aktif: true } } }); _okT(rows.length + ' mapel diaktifkan'); gtMuat('mapel'); },
        nonaktif: async (ids, rows) => { for (const r of rows) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'master_mapel', id: r.id, payload: { aktif: false } } }); _okT(rows.length + ' mapel dinonaktifkan'); gtMuat('mapel'); },
        hapus: (ids, rows) => gtHapusMassal(ids, rows)
    },
    hapusCatatan: 'Mapel yang masih dipakai jadwal sebaiknya dinonaktifkan, bukan dihapus.',
    exportRow: r => ({ 'Nama Mapel': r.nama_mapel, 'Kode': r.kode_mapel || '', 'Maks Guru': r.max_guru == null ? '' : r.max_guru, 'Guru Pengampu': r._guru, 'Status': r._aktif ? 'Aktif' : 'Nonaktif' }),
    form(id) {
        const r = id ? globalDataStore.mapel.find(x => x.id === id) : {}; window._gmEditId = id || '';
        gtModal('<i class="fas fa-book-open text-indigo-600 mr-2">
</i>' + (id ? 'Edit' : 'Tambah') + ' Mata Pelajaran', 'Master Mapel',
            `<div class="space-y-4">${_gtFld('Nama Mata Pelajaran <span class="text-red-500">*</span>', _gtInp('gmNama', r.nama_mapel, 'Contoh: Matematika'))}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">${_gtFld('Kode Mapel', _gtInp('gmKode', r.kode_mapel, 'MTK'))}${_gtFld('Maks. Guru Pengajar', _gtInp('gmMax', r.max_guru, 'Kosong = tanpa batas', 'number', 'min="1"'))}</div>
<label class="flex items-center gap-3 cursor-pointer p-3 bg-blue-50 border-2 border-blue-200 rounded-xl">
    <input type="checkbox" id="gmAktif" ${r.aktif !== false ? 'checked' : ''} class="w-5 h-5 accent-blue-600">
    <span class="text-sm font-black text-blue-700">Mapel aktif (tampil di pilihan guru &amp; jadwal)</span>
</label>
</div>`, 'gmSimpan()');
    },
    template: { head: ['Nama Mapel', 'Kode', 'Maks Guru', 'Status'], contoh: [['Matematika', 'MTK', '3', 'Aktif']] },
    imporInfo: 'Kolom: Nama Mapel | Kode | Maks Guru (opsional) | Status (Aktif/Nonaktif). Mapel yang sudah ada diperbarui.',
    async impor(js) {
        const ada = await _ambil('master_mapel?select=id,nama_mapel'); let ok = 0, rep = 0, skip = 0; const err = [];
        for (const [i, row] of js.entries()) {
            const nama = gtPick(row, 'Nama Mapel', 'Mapel', 'Nama'); if (!nama) { skip++; err.push(`Baris ${i + 2}: nama kosong`); continue; }
            const mx = parseInt(gtPick(row, 'Maks Guru', 'Max Guru'), 10);
            const b = { nama_mapel: nama, kode_mapel: gtPick(row, 'Kode').toUpperCase(), aktif: !/^non/i.test(gtPick(row, 'Status')), max_guru: mx > 0 ? mx : null };
            const ex = ada.find(x => x.nama_mapel.trim().toLowerCase() === nama.toLowerCase());
            try {
                if (ex) { await gmTulis('updateData', { table: 'master_mapel', id: ex.id, payload: b }); rep++; }
                else { const r = await gmTulis('insertData', { table: 'master_mapel', payload: b }); ada.push((r.data && r.data[0]) || { nama_mapel: nama }); ok++; }
            } catch (e) { err.push(`Baris ${i + 2} (${nama}): ${gtPesan(e)}`); }
        }
        return { ok, rep, skip, err };
    }
};
async function gmTulis(action, payload) {
    try { return await callBackend({ type: 'supabase_post', action, payload }); }
    catch (e) { // kolom max_guru belum ada -> simpan tanpa kolom itu
        if (/max_guru/i.test(String(e.message)) && payload.payload && 'max_guru' in payload.payload) { const p2 = Object.assign({}, payload.payload); delete p2.max_guru; return await callBackend({ type: 'supabase_post', action, payload: Object.assign({}, payload, { payload: p2 }) }); }
        throw e;
    }
}
async function gmSimpan() {
    const nama = _gid('gmNama').value.trim(), id = window._gmEditId; if (!nama) return Swal.fire('Lengkapi', 'Nama mapel wajib diisi.', 'warning');
    if ((globalDataStore.mapel || []).some(x => x.id !== id && x.nama_mapel.trim().toLowerCase() === nama.toLowerCase())) return Swal.fire('Sudah ada', 'Mapel dengan nama itu sudah ada.', 'warning');
    const mx = _gid('gmMax').value.trim();
    const b = { nama_mapel: nama, kode_mapel: _gid('gmKode').value.trim().toUpperCase(), aktif: _gid('gmAktif').checked, max_guru: mx ? parseInt(mx, 10) : null };
    gtBusy(true);
    try {
        if (id) await gmTulis('updateData', { table: 'master_mapel', id, payload: b }); else await gmTulis('insertData', { table: 'master_mapel', payload: b });
        gtTutupModal(); _okT('Mapel tersimpan'); gtMuat('mapel');
    } catch (e) { gtErr(e); } finally { gtBusy(false); }
}

// ============ MANAJEMEN JAM MAPEL ============
GT.jam_mapel = {
    title: 'Manajemen Jam Mapel', table: 'manajemen_jam_mapel',
    kosong: 'Belum ada jam mapel. Klik <b>Isi Otomatis</b> untuk membuat jam ke-1 s/d ke-N sekaligus.',
    async load() { const r = await _ambil('manajemen_jam_mapel?select=*&order=jam_ke.asc'); let cum = 0; r.forEach(x => { x._mulai = _fm(420 + cum); cum += x.durasi_menit; x._selesai = _fm(420 + cum); }); return r; },
    searchText: r => 'jam ' + r.jam_ke + ' ' + r.durasi_menit,
    cols: [
        { label: 'JAM KE', r: r => `<span class="font-black text-gray-800">Jam ke-${r.jam_ke}</span>` },
        { label: 'DURASI', r: r => `<span class="bg-blue-100 text-blue-700 font-black text-sm px-2.5 py-1 rounded-xl">${r.durasi_menit} menit</span>` },
        { label: 'CONTOH WAKTU <span class="normal-case text-gray-400">(mulai 07:00, tanpa istirahat)</span>', r: r => `<span class="text-xs text-gray-500">${r._mulai} – ${r._selesai}</span>` }
    ],
    extraBtn: () => `<button onclick="gjIsiOtomatis()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
<i class="fas fa-magic">
</i> Isi Otomatis</button>`,
    aksi: r => _gtB(`gtForm('${_x(r.id)}')`, 'fa-edit', 'text-blue-500 hover:bg-blue-100', 'Edit') + _gtB(`gtHapus('${_x(r.id)}')`, 'fa-trash-alt', 'text-red-500 hover:bg-red-100', 'Hapus'),
    tindakan: [{ fn: 'samakan', label: 'Samakan Durasi', icon: 'fa-equals', cls: 'text-blue-600' }, '-', { fn: 'hapus', label: 'Hapus', icon: 'fa-trash-alt', cls: 'text-red-600' }],
    massal: {
        samakan: async (ids, rows) => {
            const r = await Swal.fire({ title: 'Samakan durasi ' + rows.length + ' jam', input: 'number', inputValue: 45, inputAttributes: { min: 1 }, showCancelButton: true, confirmButtonText: 'Terapkan' });
            if (!r.isConfirmed) return; const d = parseInt(r.value, 10); if (!d || d < 1) return;
            for (const x of rows) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'manajemen_jam_mapel', id: x.id, payload: { durasi_menit: d } } });
            _okT('Durasi diperbarui'); gtMuat('jam_mapel');
        },
        hapus: (ids, rows) => gtHapusMassal(ids, rows)
    },
    exportRow: r => ({ 'Jam Ke': r.jam_ke, 'Durasi (menit)': r.durasi_menit }),
    form(id) {
        const r = id ? globalDataStore.jam_mapel.find(x => x.id === id) : {}; window._gjEditId = id || '';
        const next = (globalDataStore.jam_mapel || []).length ? Math.max(...globalDataStore.jam_mapel.map(x => x.jam_ke)) + 1 : 1;
        const last = (globalDataStore.jam_mapel || []).slice(-1)[0];
        gtModal('<i class="fas fa-clock text-indigo-600 mr-2">
</i>' + (id ? 'Edit' : 'Tambah') + ' Jam Mapel', 'Manajemen Jam Mapel',
            `<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">${_gtFld('Jam Ke <span class="text-red-500">*</span>', _gtInp('gjKe', id ? r.jam_ke : next, '', 'number', 'min="1"'))}${_gtFld('Durasi (menit) <span class="text-red-500">*</span>', _gtInp('gjDur', id ? r.durasi_menit : (last ? last.durasi_menit : 45), '', 'number', 'min="1"'))}</div>`, 'gjSimpan()');
    },
    template: { head: ['Jam Ke', 'Durasi (menit)'], contoh: [[1, 45], [2, 45], [3, 45]] },
    imporInfo: 'Kolom: Jam Ke | Durasi (menit). Jam yang sudah ada diperbarui.',
    async impor(js) {
        const ada = await _ambil('manajemen_jam_mapel?select=id,jam_ke'); let ok = 0, rep = 0, skip = 0; const err = [];
        for (const [i, row] of js.entries()) {
            const k = parseInt(gtPick(row, 'Jam Ke', 'Jam'), 10), d = parseInt(gtPick(row, 'Durasi (menit)', 'Durasi', 'Menit'), 10);
            if (!k || k < 1 || !d || d < 1) { skip++; err.push(`Baris ${i + 2}: jam ke / durasi tidak valid`); continue; }
            try {
                const ex = ada.find(x => x.jam_ke === k);
                if (ex) { await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'manajemen_jam_mapel', id: ex.id, payload: { durasi_menit: d } } }); rep++; }
                else { const r = await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'manajemen_jam_mapel', payload: { jam_ke: k, durasi_menit: d } } }); ada.push((r.data && r.data[0]) || { jam_ke: k }); ok++; }
            } catch (e) { err.push(`Baris ${i + 2}: ${gtPesan(e)}`); }
        }
        return { ok, rep, skip, err };
    }
};
async function gjSimpan() {
    const k = parseInt(_gid('gjKe').value, 10), d = parseInt(_gid('gjDur').value, 10), id = window._gjEditId;
    if (!k || k < 1 || !d || d < 1) return Swal.fire('Periksa isian', 'Jam ke dan durasi harus angka ≥ 1.', 'warning');
    if ((globalDataStore.jam_mapel || []).some(x => x.id !== id && x.jam_ke === k)) return Swal.fire('Sudah ada', `Jam ke-${k} sudah ada.`, 'warning');
    gtBusy(true);
    try {
        if (id) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'manajemen_jam_mapel', id, payload: { jam_ke: k, durasi_menit: d } } });
        else await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'manajemen_jam_mapel', payload: { jam_ke: k, durasi_menit: d } } });
        gtTutupModal(); _okT('Jam tersimpan'); gtMuat('jam_mapel');
    } catch (e) { gtErr(e); } finally { gtBusy(false); }
}
async function gjIsiOtomatis() {
    const r = await Swal.fire({ title: 'Isi Otomatis Jam Mapel', html: '<input id="gjN" type="number" min="1" max="20" value="10" class="swal2-input" placeholder="Jumlah jam">
<input id="gjD" type="number" min="1" value="45" class="swal2-input" placeholder="Durasi (menit)">
<p class="text-xs text-gray-500 mt-2">Hanya menambah jam yang belum ada; jam yang sudah ada tidak diubah.</p>', showCancelButton: true, confirmButtonText: 'Buat', preConfirm: () => { const n = parseInt(_gid('gjN').value, 10), d = parseInt(_gid('gjD').value, 10); if (!(n >= 1 && n <= 20) || !(d >= 1)) { Swal.showValidationMessage('Jumlah 1–20 dan durasi ≥ 1'); return false; } return { n, d }; } });
    if (!r.isConfirmed) return;
    try {
        const ada = new Set((globalDataStore.jam_mapel || []).map(x => x.jam_ke)); let n = 0;
        for (let k = 1; k <= r.value.n; k++) if (!ada.has(k)) { await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'manajemen_jam_mapel', payload: { jam_ke: k, durasi_menit: r.value.d } } }); n++; }
        _okT(n + ' jam ditambahkan'); gtMuat('jam_mapel');
    } catch (e) { gtErr(e); }
}

// ============ MANAJEMEN HARI — tabel tanggal otomatis per bulan ============
var _hariBulan = _tglWib().slice(0, 7);
var _hr = null, _hrDur = {};
const _gtTglFmt = t => new Date(t + 'T00:00:00Z').toLocaleDateString('id-ID', { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric', timeZone: 'UTC' });
function gtHariSumber(r) {
    if (r._virtual) return _gtChip('AUTO', 'bg-gray-100 text-gray-500');
    if (r.status_manual) return _gtChip('MANUAL', 'bg-yellow-100 text-yellow-700');
    if (r.status === 'Libur') return _gtChip(r.is_cuti_bersama ? 'CUTI BERSAMA' : 'LIBUR NASIONAL', 'bg-purple-100 text-purple-700');
    return _gtChip('TERCATAT', 'bg-blue-100 text-blue-700');
}
async function gtHariUpsert(r, patch) {
    const base = Object.assign({}, patch, { status_manual: true, updated_at: new Date().toISOString() });
    if (r._virtual) await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'manajemen_hari', payload: Object.assign({ tanggal: r.tanggal, hari: r.hari, status: r.status, keterangan: r.keterangan || null }, base) } });
    else await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'manajemen_hari', id: r.id, payload: base } });
}
GT.hari = {
    title: 'Manajemen Hari', table: 'manajemen_hari', pageSize: 31,
    filterHtml: () => `<div class="flex flex-wrap items-center gap-2">
<input type="month" id="hrBulan" value="${_hariBulan}" onchange="gtHariGanti()" class="px-3 py-2 border-2 border-gray-200 rounded-xl text-sm font-bold bg-white">
<button onclick="gtHariBulanIni()" class="px-3 py-2 rounded-xl text-xs font-black bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">Bulan Ini</button>
<select id="hrStatus" onchange="filterTableLokal()" class="px-3 py-2 border-2 border-gray-200 rounded-xl text-sm font-bold bg-white">
    <option value="">Semua status</option>
    <option>Libur</option>
    <option>Masuk</option>
</select>
</div>`,
    extraBtn: () => `<button onclick="gtHariSinkron()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
<i class="fas fa-sync-alt">
</i> Sinkron Libur Nasional</button>`,
    async load() {
        const b = (_gid('hrBulan') && _gid('hrBulan').value) || _hariBulan; _hariBulan = b;
        const [y, m] = b.split('-').map(Number), last = new Date(Date.UTC(y, m, 0)).getUTCDate();
        const rows = await _ambil(`manajemen_hari?tanggal=gte.${b}-01&tanggal=lte.${b}-${_P2(last)}&select=*&order=tanggal.asc&limit=100`);
        const map = {}; rows.forEach(r => { map[r.tanggal] = r; });
        const out = [];
        for (let d = 1; d <= last; d++) {
            const tgl = `${b}-${_P2(d)}`, hari = new Date(tgl + 'T00:00:00Z').getUTCDay() || 7, r = map[tgl];
            if (r) { r._virtual = false; r.hari = hari; r.istirahat = _arr(r.istirahat); r.agenda = _arr(r.agenda); out.push(r); }
            else { const wk = hari >= 6; out.push({ id: 'v-' + tgl, tanggal: tgl, hari, status: wk ? 'Libur' : 'Masuk', keterangan: wk ? (hari === 6 ? 'Sabtu' : 'Minggu') : '', status_manual: false, istirahat: [], agenda: [], _virtual: true }); }
        }
        return out;
    },
    setelahTampil() { gtHariAutoSync(_hariBulan.slice(0, 4)); },
    searchText: r => [r.tanggal, _HARI[r.hari], r.status, r.keterangan].join(' '),
    filter: r => { const v = (_gid('hrStatus') || {}).value; return !v || r.status === v; },
    rowCls: r => r.status === 'Libur' ? 'bg-red-50/40' : '',
    info: rows => `${rows.length} hari • ${rows.filter(r => r.status === 'Libur').length} libur • ${rows.filter(r => r.status === 'Masuk').length} masuk`,
    cols: [
        { label: 'TANGGAL', r: r => `<p class="font-bold text-gray-800">${_gtTglFmt(r.tanggal)}</p>${r.tanggal === _tglWib() ? '<span class="text-[9px] font-black bg-blue-600 text-white px-1.5 py-0.5 rounded">HARI INI</span>' : ''}` },
        { label: 'HARI', r: r => `<span class="font-bold ${r.hari >= 6 ? 'text-red-500' : 'text-gray-700'}">${_HARI[r.hari]}</span>` },
        { label: 'LIBUR?', r: r => r.status === 'Libur' ? _gtChip('LIBUR', 'bg-red-100 text-red-600') : _gtChip('MASUK', 'bg-green-100 text-green-700') },
        { label: 'KETERANGAN', cls: 'whitespace-normal min-w-[160px]', r: r => `<span class="text-xs text-gray-600">${_x(r.keterangan || '—')}</span>` },
        { label: 'JAM PELAJARAN', r: r => r.status === 'Masuk' && r.jam_mulai ? `<p class="text-xs font-bold text-gray-700">${_gtMin(r.jam_mulai)}${r.jam_pulang ? ' – ' + _gtMin(r.jam_pulang) : ''}</p>
<p class="text-[10px] text-gray-400">${r.jumlah_jam_mapel || 0} jam mapel${r.istirahat.length ? ' • ' + r.istirahat.length + ' istirahat' : ''}</p>` : '<span class="text-gray-300 text-xs">—</span>' },
        { label: 'SUMBER', r: r => gtHariSumber(r) }
    ],
    aksi: r => _gtB(`gtForm('${r.tanggal}')`, 'fa-edit', 'text-blue-500 hover:bg-blue-100', 'Atur hari ini') + (r._virtual ? '' : _gtB(`ghReset('${_x(r.id)}')`, 'fa-undo', 'text-orange-500 hover:bg-orange-100', 'Reset ke otomatis')),
    tindakan: [
        { fn: 'libur', label: 'Jadikan Libur', icon: 'fa-calendar-times', cls: 'text-red-600' },
        { fn: 'masuk', label: 'Jadikan Masuk', icon: 'fa-calendar-check', cls: 'text-green-600' },
        { fn: 'jam', label: 'Atur Jam Pelajaran', icon: 'fa-clock', cls: 'text-blue-600' }, '-',
        { fn: 'reset', label: 'Reset ke Otomatis', icon: 'fa-undo', cls: 'text-orange-600' }],
    massal: {
        libur: async (ids, rows) => {
            const r = await Swal.fire({ title: `Jadikan ${rows.length} tanggal libur`, input: 'text', inputValue: 'Libur sekolah', inputLabel: 'Keterangan', showCancelButton: true, confirmButtonText: 'Terapkan' });
            if (!r.isConfirmed) return;
            for (const x of rows) await gtHariUpsert(x, { status: 'Libur', keterangan: r.value || 'Libur' });
            _okT(rows.length + ' tanggal dijadikan libur'); gtMuat('hari');
        },
        masuk: async (ids, rows) => {
            for (const x of rows) await gtHariUpsert(x, { status: 'Masuk', keterangan: null });
            _okT(rows.length + ' tanggal dijadikan masuk'); gtMuat('hari');
        },
        jam: async (ids, rows) => {
            const r = await Swal.fire({ title: 'Atur Jam Pelajaran', html: '<label class="text-xs font-bold">Jam mulai pertama</label>
<input id="gjhM" type="time" value="07:00" class="swal2-input">
<label class="text-xs font-bold">Jumlah jam mapel</label>
<input id="gjhJ" type="number" min="1" value="10" class="swal2-input">
<label class="text-xs font-bold">Jam pulang (opsional)</label>
<input id="gjhP" type="time" class="swal2-input">
<p class="text-xs text-gray-500 mt-2">Hanya untuk tanggal berstatus <b>Masuk</b>; tanggal libur dilewati.</p>', showCancelButton: true, confirmButtonText: 'Terapkan', preConfirm: () => { const m = _gid('gjhM').value, j = parseInt(_gid('gjhJ').value, 10); if (!m || !(j >= 1)) { Swal.showValidationMessage('Isi jam mulai dan jumlah jam'); return false; } return { jam_mulai: m, jumlah_jam_mapel: j, jam_pulang: _gid('gjhP').value || null }; } });
            if (!r.isConfirmed) return;
            const masuk = rows.filter(x => x.status === 'Masuk'); for (const x of masuk) await gtHariUpsert(x, r.value);
            _okT(`${masuk.length} tanggal diatur` + (rows.length - masuk.length ? `, ${rows.length - masuk.length} libur dilewati` : '')); gtMuat('hari');
        },
        reset: async (ids, rows) => {
            const real = rows.filter(x => !x._virtual); if (!real.length) return Swal.fire('Sudah otomatis', 'Tanggal terpilih belum punya pengaturan khusus.', 'info');
            const c = await Swal.fire({ title: `Reset ${real.length} tanggal ke otomatis?`, text: 'Pengaturan khusus (termasuk libur nasional hasil sinkron) dihapus. Klik Sinkron Libur Nasional untuk mengisinya lagi.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Reset', cancelButtonText: 'Batal' });
            if (!c.isConfirmed) return;
            for (const x of real) await callBackend({ type: 'supabase_post', action: 'deleteData', payload: { table: 'manajemen_hari', id: x.id } });
            _okT(real.length + ' tanggal direset'); gtMuat('hari');
        }
    },
    exportRow: r => ({ 'Tanggal': r.tanggal, 'Hari': _HARI[r.hari], 'Status': r.status, 'Keterangan': r.keterangan || '', 'Jam Mulai': _gtMin(r.jam_mulai), 'Jumlah Jam Mapel': r.jumlah_jam_mapel || '', 'Jam Pulang': _gtMin(r.jam_pulang), 'Sumber': r._virtual ? 'Auto' : (r.status_manual ? 'Manual' : 'Sinkron') }),
    form(tgl) { return ghMuat(typeof tgl === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(tgl) ? tgl : _tglWib()); },
    template: { head: ['Tanggal', 'Status', 'Keterangan', 'Jam Mulai', 'Jumlah Jam Mapel', 'Jam Pulang'], contoh: [['2026-10-12', 'Masuk', '', '07:00', 10, '15:30'], ['2026-10-17', 'Libur', 'Libur sekolah', '', '', '']] },
    imporInfo: 'Kolom: Tanggal (YYYY-MM-DD / DD/MM/YYYY) | Status (Masuk/Libur) | Keterangan | Jam Mulai | Jumlah Jam Mapel | Jam Pulang. Tanggal yang sama diperbarui dan ditandai MANUAL.',
    async impor(js) {
        let ok = 0, rep = 0, skip = 0; const err = [];
        for (const [i, row] of js.entries()) {
            const tgl = gtTglExcel(row['Tanggal'] !== undefined ? row['Tanggal'] : gtPick(row, 'Tanggal')); if (!tgl) { skip++; err.push(`Baris ${i + 2}: tanggal tidak valid`); continue; }
            const st = /^lib/i.test(gtPick(row, 'Status')) ? 'Libur' : 'Masuk', hari = new Date(tgl + 'T00:00:00Z').getUTCDay() || 7;
            const jm = gtJamExcel(row['Jam Mulai']), jp = gtJamExcel(row['Jam Pulang']), jml = parseInt(gtPick(row, 'Jumlah Jam Mapel', 'Jumlah Jam'), 10) || null;
            const b = { status: st, keterangan: gtPick(row, 'Keterangan') || null, jam_mulai: st === 'Masuk' ? jm : null, jumlah_jam_mapel: st === 'Masuk' ? jml : null, jam_pulang: st === 'Masuk' ? jp : null, status_manual: true, updated_at: new Date().toISOString() };
            try {
                const ex = (await _ambil('manajemen_hari?tanggal=eq.' + tgl + '&select=id&limit=1'))[0];
                if (ex) { await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'manajemen_hari', id: ex.id, payload: b } }); rep++; }
                else { await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'manajemen_hari', payload: Object.assign({ tanggal: tgl, hari }, b) } }); ok++; }
            } catch (e) { err.push(`Baris ${i + 2} (${tgl}): ${gtPesan(e)}`); }
        }
        return { ok, rep, skip, err };
    }
};
function gtHariGanti() { _hariBulan = _gid('hrBulan').value || _hariBulan; gtMuat('hari'); }
function gtHariBulanIni() { _hariBulan = _tglWib().slice(0, 7); gtMuat('hari'); }
async function ghReset(id) {
    const c = await Swal.fire({ title: 'Reset ke otomatis?', text: 'Pengaturan khusus tanggal ini dihapus (Sabtu/Minggu otomatis libur, lainnya masuk).', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Reset', cancelButtonText: 'Batal' });
    if (!c.isConfirmed) return;
    try { await callBackend({ type: 'supabase_post', action: 'deleteData', payload: { table: 'manajemen_hari', id } }); _okT('Dikembalikan ke otomatis'); gtMuat('hari'); } catch (e) { gtErr(e); }
}
async function gtHariSinkron(senyap, tahun) {
    const thn = tahun || _hariBulan.slice(0, 4);
    try {
        if (!senyap) Swal.fire({ title: 'Menyinkronkan libur ' + thn + '...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        const d = await dbPanggil('syncLibur', { tahun: thn });
        if (senyap) return d;
        if (d.ok) Swal.fire({ icon: 'success', title: 'Sinkron selesai', html: `Sumber: <b>${_x(d.sumber)}</b>
<br>${d.baru} libur baru, ${d.ubah} diperbarui, ${d.lewat} dilewati (diatur manual).`, confirmButtonColor: '#2563eb' });
        else Swal.fire({ icon: 'warning', title: 'Tidak bisa sinkron', text: d.pesan, confirmButtonColor: '#2563eb' });
        gtMuat('hari');
    } catch (e) { if (!senyap) gtErr(e, 'Sinkron gagal'); }
}
async function gtHariAutoSync(thn) {
    const k = 'ea_libur_sync_' + thn; if (sessionStorage.getItem(k)) return;
    sessionStorage.setItem(k, '1');
    const d = await gtHariSinkron(true, thn);
    if (d && d.ok && (d.baru + d.ubah) > 0 && currentTableId === 'hari') gtMuat('hari');
}

// ---- editor satu tanggal (modal) ----
async function ghMuat(tgl) {
    const dow = new Date(tgl + 'T00:00:00Z').getUTCDay() || 7;
    const [rows, jam, pola, istDef] = await Promise.all([
        _ambil('manajemen_hari?tanggal=eq.' + tgl + '&select=*&limit=1'), _ambil('manajemen_jam_mapel?select=*'),
        _ambil('manajemen_hari?hari=eq.' + dow + '&status=eq.Masuk&jam_mulai=not.is.null&order=tanggal.desc&limit=1&select=*'),
        _ambil('pengaturan?nama_pengaturan=in.(istirahat_default_hari_' + dow + ',istirahat_default_semua)&select=nama_pengaturan,nilai')]);
    _hrDur = {}; jam.forEach(j => { _hrDur[j.jam_ke] = j.durasi_menit; });
    if (rows[0]) _hr = rows[0];
    else {
        _hr = { tanggal: tgl, status: dow >= 6 ? 'Libur' : 'Masuk', status_manual: false, keterangan: dow >= 6 ? (dow === 6 ? 'Sabtu' : 'Minggu') : '', istirahat: [], agenda: [] };
        if (dow < 6 && pola[0]) { const p = pola[0]; _hr.jam_mulai = p.jam_mulai; _hr.jumlah_jam_mapel = p.jumlah_jam_mapel; _hr.jam_pulang = p.jam_pulang; _hr.istirahat = _arr(p.istirahat); _hr.agenda = _arr(p.agenda); }
        const d = istDef.find(x => x.nama_pengaturan === 'istirahat_default_hari_' + dow) || istDef.find(x => x.nama_pengaturan === 'istirahat_default_semua');
        if (d && dow < 6) _hr.istirahat = _arr(d.nilai);
    }
    _hr.istirahat = _arr(_hr.istirahat); _hr.agenda = _arr(_hr.agenda); _hr.hari = dow;
    ghRender();
}
function _ghRows(k) {
    return _hr[k].map((x, i) => `<div class="flex flex-wrap items-center gap-2">
<input value="${_x(x.nama || '')}" placeholder="Nama" oninput="_hr.${k}[${i}].nama=this.value" class="${_in}flex-1 min-w-[120px]">
<input type="time" value="${x.mulai || ''}" oninput="_hr.${k}[${i}].mulai=this.value" class="${_in}">
<span class="text-xs">s/d</span>
<input type="time" value="${x.selesai || ''}" oninput="_hr.${k}[${i}].selesai=this.value" class="${_in}">
<button type="button" onclick="_hr.${k}.splice(${i},1);ghRender()" class="text-red-500 hover:bg-red-100 p-2 rounded-lg">
    <i class="fas fa-trash-alt">
    </i>
</button>
</div>`).join('') || '<p class="text-xs text-gray-400">Belum ada.</p>';
}
function ghBaca() { const h = _hr; h.status = _gid('ghStatus').value; h.keterangan = _gid('ghKet').value.trim(); h.jam_mulai = _gid('ghMulai').value || null; h.jumlah_jam_mapel = parseInt(_gid('ghJml').value, 10) || null; h.jam_pulang = _gid('ghPulang').value || null; }
function ghRender() {
    const h = _hr;
    gtModal('<i class="fas fa-calendar-day text-indigo-600 mr-2">
</i>Atur Hari', _HARI[h.hari] + ' • ' + (h.status_manual ? 'MANUAL' : (h.id ? 'TERCATAT' : 'AUTO (belum disimpan)')),
        `<div class="space-y-4">
        ${_gtFld('Tanggal', `<input type="date" id="ghTgl" value="${h.tanggal}" onchange="ghMuat(this.value)" class="${_gtIn}">`)}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          ${_gtFld('Status', _gtSel('ghStatus', [{ v: 'Masuk', t: 'Masuk' }, { v: 'Libur', t: 'Libur' }], h.status))}${_gtFld('Keterangan', _gtInp('ghKet', h.keterangan || '', 'Mis. Libur nasional / Class meeting'))}
          ${_gtFld('Jam mulai pertama', _gtInp('ghMulai', _gtMin(h.jam_mulai), '', 'time'))}${_gtFld('Jumlah jam mapel', _gtInp('ghJml', h.jumlah_jam_mapel == null ? '' : h.jumlah_jam_mapel, '', 'number', 'min="0"'))}
          ${_gtFld('Jam pulang', _gtInp('ghPulang', _gtMin(h.jam_pulang), '', 'time'))}</div>
<div>
    <div class="flex justify-between items-center mb-2">
        <p class="text-xs font-black text-gray-600 uppercase">Istirahat (pause jam mapel)</p>
        <button type="button" onclick="ghBaca();_hr.istirahat.push({nama:'Istirahat '+(_hr.istirahat.length+1),mulai:'',selesai:''});ghRender()" class="text-xs font-bold text-blue-600">+ Tambah</button>
    </div>
    <div class="space-y-2">${_ghRows('istirahat')}</div>
</div>
<div>
    <div class="flex justify-between items-center mb-2">
        <p class="text-xs font-black text-gray-600 uppercase">Agenda (menggeser jam mulai)</p>
        <button type="button" onclick="ghBaca();_hr.agenda.push({nama:'',mulai:'',selesai:''});ghRender()" class="text-xs font-bold text-blue-600">+ Tambah</button>
    </div>
    <div class="space-y-2">${_ghRows('agenda')}</div>
</div>
<button type="button" onclick="ghPreview()" class="${_bt}bg-gray-700 hover:bg-gray-800">
    <i class="fas fa-eye">
    </i> Lihat Pembagian Jam</button>
    <div id="ghPreview">
    </div>
</div>`, 'ghSimpan()');
}
function ghPreview() { ghBaca(); const s = hitungSlotJam(_hr, _hrDur); _gid('ghPreview').innerHTML = s.length ? '<div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-2">' + s.map(x => `<div class="bg-blue-50 border border-blue-100 rounded-xl p-2 text-xs">
<b>Jam ke-${x.jam_ke}</b>
<br>${x.mulai}–${x.selesai}</div>`).join('') + '</div>' : '<p class="text-xs text-gray-400 mt-2">Isi jam mulai pertama dan jumlah jam mapel dulu.</p>'; }
async function ghSimpan() {
    ghBaca(); const h = _hr; if (!h.tanggal) return;
    const body = { tanggal: h.tanggal, hari: h.hari, status: h.status, status_manual: true, keterangan: h.keterangan || null, jam_mulai: h.status === 'Masuk' ? h.jam_mulai : null, jumlah_jam_mapel: h.status === 'Masuk' ? h.jumlah_jam_mapel : null, jam_pulang: h.status === 'Masuk' ? h.jam_pulang : null, istirahat: h.istirahat, agenda: h.agenda, updated_at: new Date().toISOString() };
    let scope = null;
    if (h.status === 'Masuk' && h.istirahat.length) {
        const r = await Swal.fire({ title: 'Terapkan istirahat', text: 'Istirahat ini diterapkan ke mana?', icon: 'question', showDenyButton: true, showCancelButton: true, allowOutsideClick: false, confirmButtonText: 'Semua Hari', denyButtonText: 'Hanya Hari ' + _HARI[h.hari], cancelButtonText: 'Tanggal ini saja' });
        scope = r.isConfirmed ? 'semua' : (r.isDenied ? 'hari' : null);
    }
    gtBusy(true);
    try {
        if (h.id) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'manajemen_hari', id: h.id, payload: body } });
        else await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'manajemen_hari', payload: body } });
        if (scope) {
            await callBackend({ type: 'supabase_post', action: 'updatePengaturan', payload: [{ nama_pengaturan: scope === 'semua' ? 'istirahat_default_semua' : 'istirahat_default_hari_' + h.hari, nilai: JSON.stringify(h.istirahat) }] });
            await callBackend({ type: 'supabase_post', action: 'updateWhere', payload: { table: 'manajemen_hari', where: 'tanggal=gte.' + h.tanggal + (scope === 'hari' ? '&hari=eq.' + h.hari : ''), payload: { istirahat: h.istirahat } } });
        }
        gtTutupModal(); _okT('Tersimpan' + (scope ? ' & diterapkan ke tanggal ini dan seterusnya' : '')); _hariBulan = h.tanggal.slice(0, 7); gtMuat('hari');
    } catch (e) { gtErr(e); } finally { gtBusy(false); }
}

// ============ MANAJEMEN JADWAL ============
GT.jadwal = {
    title: 'Manajemen Jadwal', table: 'jadwal_mapel', ref: {},
    kosong: 'Belum ada jadwal. Klik <b>Manual</b> atau <b>Impor</b>. Pastikan Master Kelas, Master Mapel, Akun Guru, dan Jam Mapel sudah terisi.',
    async load() {
        const [rows, kelas, mapel, guru, jam] = await Promise.all([_ambil('jadwal_mapel?select=*&order=hari.asc,jam_ke_mulai.asc'), _ambil('daftar_kelas?select=*&order=nama_kelas.asc'), _ambil('master_mapel?select=id,nama_mapel,aktif&order=nama_mapel.asc'), _ambil('master_guru?select=id,nama_lengkap,status&order=nama_lengkap.asc'), _ambil('manajemen_jam_mapel?select=*&order=jam_ke.asc')]);
        GT.jadwal.ref = { kelas, mapel: mapel.filter(m => m.aktif !== false), guru: guru.filter(g => (g.status || 'Aktif') !== 'Nonaktif'), jam };
        return rows;
    },
    afterLoad() {
        const R = GT.jadwal.ref, o = (a, l) => a.map(x => `<option value="${_x(x.id)}">${_x(x[l])}</option>`).join('');
        _gid('jfKelas').innerHTML = '<option value="">Semua kelas</option>' + o(R.kelas, 'nama_kelas');
        _gid('jfGuru').innerHTML = '<option value="">Semua guru</option>' + o(R.guru, 'nama_lengkap');
    },
    searchText: r => [_HARI[r.hari], r.nama_kelas, r.nama_mapel, r.nama_guru, r.drop_point].join(' '),
    filterHtml: () => { const c = 'px-3 py-2 border-2 border-gray-200 rounded-xl text-sm font-bold bg-white focus:ring-2 focus:ring-blue-500'; return `<div class="flex flex-wrap gap-2">
<select id="jfHari" onchange="filterTableLokal()" class="${c}">
    <option value="">Semua hari</option>${_HARI.slice(1).map((h, i) => `<option value="${i + 1}">${h}</option>`).join('')}</select>
    <select id="jfKelas" onchange="filterTableLokal()" class="${c}">
        <option value="">Semua kelas</option>
    </select>
    <select id="jfGuru" onchange="filterTableLokal()" class="${c}">
        <option value="">Semua guru</option>
    </select>
</div>`; },
    filter: r => { const h = _gid('jfHari').value, k = _gid('jfKelas').value, g = _gid('jfGuru').value; return (!h || String(r.hari) === h) && (!k || r.id_kelas === k) && (!g || r.id_guru === g); },
    cols: [
        { label: 'HARI', r: r => `<span class="font-bold text-gray-800">${_HARI[r.hari]}</span>` },
        { label: 'JAM', r: r => `<span class="bg-blue-100 text-blue-700 font-black text-xs px-2.5 py-1 rounded-xl">${r.jam_ke_mulai === r.jam_ke_selesai ? 'Jam ' + r.jam_ke_mulai : 'Jam ' + r.jam_ke_mulai + '–' + r.jam_ke_selesai}</span>` },
        { label: 'KELAS', r: r => `<span class="bg-gray-100 border px-2 py-1 rounded text-xs font-bold">${_x(r.nama_kelas || '')}</span>` },
        { label: 'MATA PELAJARAN', r: r => `<span class="font-bold text-gray-800 text-sm">${_x(r.nama_mapel || '')}</span>` },
        { label: 'GURU', r: r => `<span class="text-sm text-gray-700">${_x(r.nama_guru || '')}</span>` },
        { label: 'DROP POINT', r: r => `<span class="text-xs text-gray-500">${_x(r.drop_point || '—')}</span>` }
    ],
    aksi: r => _gtB(`gtForm('${_x(r.id)}')`, 'fa-edit', 'text-blue-500 hover:bg-blue-100', 'Edit') + _gtB(`gtHapus('${_x(r.id)}')`, 'fa-trash-alt', 'text-red-500 hover:bg-red-100', 'Hapus'),
    tindakan: [{ fn: 'hapus', label: 'Hapus Jadwal', icon: 'fa-trash-alt', cls: 'text-red-600' }],
    massal: { hapus: (ids, rows) => gtHapusMassal(ids, rows) },
    exportRow: r => ({ 'Hari': _HARI[r.hari], 'Kelas': r.nama_kelas || '', 'Mata Pelajaran': r.nama_mapel || '', 'Guru': r.nama_guru || '', 'Jam Mulai': r.jam_ke_mulai, 'Jam Selesai': r.jam_ke_selesai, 'Drop Point': r.drop_point || '' }),
    form(id) {
        const R = GT.jadwal.ref, r = id ? globalDataStore.jadwal.find(x => x.id === id) : {}; window._jdEditId = id || ''; window._jdGuruId = r.id_guru || '';
        const jamOpt = R.jam.length ? R.jam.map(j => ({ v: j.jam_ke, t: 'Jam ke-' + j.jam_ke })) : [1, 2, 3, 4, 5, 6, 7, 8, 9, 10].map(n => ({ v: n, t: 'Jam ke-' + n }));
        const drops = [...new Set((globalDataStore.jadwal || []).map(x => x.drop_point).filter(Boolean))];
        gtModal('<i class="fas fa-table text-indigo-600 mr-2">
</i>' + (id ? 'Edit' : 'Tambah') + ' Jadwal', 'Manajemen Jadwal',
            `<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            ${_gtFld('Hari', _gtSel('jdHari', _HARI.slice(1).map((h, i) => ({ v: i + 1, t: h })), r.hari || 1))}
            ${_gtFld('Kelas <span class="text-red-500">*</span>', _gtSel('jdKelas', [{ v: '', t: '— Pilih kelas —' }].concat(R.kelas.map(k => ({ v: k.id, t: k.nama_kelas }))), r.id_kelas || ''))}
            ${_gtFld('Mata Pelajaran <span class="text-red-500">*</span>', _gtSel('jdMapel', [{ v: '', t: '— Pilih mapel —' }].concat(R.mapel.map(m => ({ v: m.id, t: m.nama_mapel }))), r.id_mapel || ''))}
            ${_gtFld('Guru <span class="text-red-500">*</span>', _gtInp('jdGuruNama', r.nama_guru, 'Ketik sebagian nama guru...', 'text', 'autocomplete="off"'))}
            ${_gtFld('Jam mulai', _gtSel('jdMulai', jamOpt, r.jam_ke_mulai || jamOpt[0].v))}${_gtFld('Jam selesai', _gtSel('jdSelesai', jamOpt, r.jam_ke_selesai || r.jam_ke_mulai || jamOpt[0].v))}
            <div class="sm:col-span-2">${_gtFld('Drop Point (opsional)', _gtInp('jdDrop', r.drop_point, 'Pilih / ketik lokasi (Lab 1, Aula...)', 'text', 'list="jdDropList" autocomplete="off"') + `<datalist id="jdDropList">${drops.map(d => `<option value="${_x(d)}">`).join('')}</datalist>`)}</div>
</div>`, 'jdSimpan()');
        gtAutocomplete('jdGuruNama', q => R.guru.filter(g => !q || g.nama_lengkap.toLowerCase().includes(q)).map(g => ({ label: g.nama_lengkap, raw: g })), it => { window._jdGuruId = it ? it.raw.id : ''; if (it) _gid('jdGuruNama').value = it.raw.nama_lengkap; });
    },
    template: { head: ['Hari', 'Kelas', 'Mata Pelajaran', 'Guru', 'Jam Mulai', 'Jam Selesai', 'Drop Point'], contoh: [['Senin', 'X RPL 1', 'Matematika', 'Budi Santoso, S.Pd', 1, 2, 'Ruang Kelas']] },
    imporInfo: 'Kolom: Hari (Senin..Minggu / 1-7) | Kelas | Mata Pelajaran | Guru | Jam Mulai | Jam Selesai | Drop Point. Nama kelas/mapel/guru harus sesuai master. Jadwal identik dilewati.',
    async impor(js) {
        const R = GT.jadwal.ref; if (!R.kelas) await GT.jadwal.load();
        const ada = await _ambil('jadwal_mapel?select=*'); let ok = 0, skip = 0; const err = [];
        const cari = (a, l, v) => a.find(x => String(x[l]).trim().toLowerCase() === String(v).trim().toLowerCase());
        for (const [i, row] of js.entries()) {
            const hs = gtPick(row, 'Hari'); let hari = parseInt(hs, 10); if (!(hari >= 1 && hari <= 7)) hari = _HARI.findIndex(h => h && h.toLowerCase() === hs.toLowerCase());
            const k = cari(R.kelas, 'nama_kelas', gtPick(row, 'Kelas')), m = cari(R.mapel, 'nama_mapel', gtPick(row, 'Mata Pelajaran', 'Mapel')), g = cari(R.guru, 'nama_lengkap', gtPick(row, 'Guru', 'Nama Guru'));
            const a = parseInt(gtPick(row, 'Jam Mulai'), 10), z = parseInt(gtPick(row, 'Jam Selesai'), 10) || a;
            const salah = []; if (!(hari >= 1)) salah.push('hari'); if (!k) salah.push('kelas'); if (!m) salah.push('mapel'); if (!g) salah.push('guru'); if (!(a >= 1) || z < a) salah.push('jam');
            if (salah.length) { skip++; err.push(`Baris ${i + 2}: ${salah.join(', ')} tidak valid/tidak ada di master`); continue; }
            if (ada.some(x => x.hari === hari && x.id_kelas === k.id && x.id_mapel === m.id && x.id_guru === g.id && x.jam_ke_mulai === a && x.jam_ke_selesai === z)) { skip++; continue; }
            try {
                const p = { hari, id_kelas: k.id, nama_kelas: k.nama_kelas, id_mapel: m.id, nama_mapel: m.nama_mapel, id_guru: g.id, nama_guru: g.nama_lengkap, jam_ke_mulai: a, jam_ke_selesai: z, drop_point: gtPick(row, 'Drop Point') || null };
                const r = await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'jadwal_mapel', payload: p } }); ada.push((r.data && r.data[0]) || p); ok++;
            } catch (e) { err.push(`Baris ${i + 2}: ${gtPesan(e)}`); }
        }
        return { ok, rep: 0, skip, err };
    }
};
async function jdSimpan() {
    const R = GT.jadwal.ref, v = i => _gid(i).value, id = window._jdEditId, gid = window._jdGuruId;
    const kelas = R.kelas.find(x => x.id === v('jdKelas')), mapel = R.mapel.find(x => x.id === v('jdMapel')), guru = R.guru.find(x => x.id === gid);
    if (!kelas || !mapel) return Swal.fire('Lengkapi', 'Pilih kelas dan mata pelajaran.', 'warning');
    if (!guru) return Swal.fire('Guru belum dipilih', 'Ketik sebagian nama guru lalu pilih dari saran yang muncul.', 'warning');
    const a = +v('jdMulai'), z = +v('jdSelesai'), hari = +v('jdHari');
    if (z < a) return Swal.fire('Periksa jam', 'Jam selesai tidak boleh sebelum jam mulai.', 'warning');
    const bentrok = (globalDataStore.jadwal || []).filter(x => x.id !== id && x.hari === hari && !(z < x.jam_ke_mulai || a > x.jam_ke_selesai) && (x.id_guru === guru.id || x.id_kelas === kelas.id));
    if (bentrok.length) {
        const c = await Swal.fire({ title: 'Jadwal bentrok', html: '<div class="text-left text-xs space-y-1">' + bentrok.map(x => `<p>• ${_HARI[x.hari]} jam ${x.jam_ke_mulai}–${x.jam_ke_selesai}: ${_x(x.nama_kelas)} — ${_x(x.nama_mapel)} (${_x(x.nama_guru)})</p>`).join('') + '</div>
<p class="text-sm mt-2">Guru atau kelas yang sama sudah terisi di jam itu. Tetap simpan?</p>', icon: 'warning', showCancelButton: true, confirmButtonText: 'Tetap Simpan', cancelButtonText: 'Batal' });
        if (!c.isConfirmed) return;
    }
    const p = { hari, id_kelas: kelas.id, nama_kelas: kelas.nama_kelas, id_mapel: mapel.id, nama_mapel: mapel.nama_mapel, id_guru: guru.id, nama_guru: guru.nama_lengkap, jam_ke_mulai: a, jam_ke_selesai: z, drop_point: v('jdDrop').trim() || null };
    gtBusy(true);
    try {
        if (id) await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'jadwal_mapel', id, payload: p } });
        else await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'jadwal_mapel', payload: p } });
        gtTutupModal(); _okT('Jadwal tersimpan'); gtMuat('jadwal');
    } catch (e) { gtErr(e); } finally { gtBusy(false); }
}

</script>
<script>
// ============ MANAJEMEN DATABASE (backup / restore) ============
async function _tokenAdmin(){const r=await supaClient.auth.getSession();const t=r&&r.data&&r.data.session&&r.data.session.access_token;if(!t)throw new Error('Sesi login tidak ditemukan. Keluar lalu login ulang.');return t;}
async function dbPanggil(action,payload){
  const token=await _tokenAdmin();
  const r=await callBackend({type:'supabase_post',action,payload:Object.assign({token},payload||{})});
  if(r.data===undefined)throw new Error('Server membalas kosong untuk aksi "'+action+'". Fungsi Netlify notify.js yang aktif kemungkinan masih versi lama — deploy ulang notify.js terbaru.');
  return r.data;
}
const _kb=n=>n==null?'—':(n>1048576?(n/1048576).toFixed(1)+' MB':Math.max(1,Math.round(n/1024))+' KB');
async function mjDb(){
  _gid('view-manajemen').innerHTML=`<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
<div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-3">
    <h4 class="font-black text-gray-800 text-sm flex items-center gap-2">
        <i class="fas fa-heartbeat text-green-500">
        </i> Status Server</h4>
        <div id="dbStatus" class="text-xs text-gray-500">
            <i class="fas fa-spinner fa-spin">
            </i> Memeriksa...</div>
            <button onclick="dbCekStatus()" class="${_bt}bg-gray-700 hover:bg-gray-800">
                <i class="fas fa-sync-alt">
                </i> Periksa Ulang</button>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-3">
                <h4 class="font-black text-gray-800 text-sm flex items-center gap-2">
                    <i class="fas fa-database text-blue-500">
                    </i> Backup Sekarang</h4>
                    <p class="text-xs text-gray-500">Memuat 23 tabel data (skema memakai setup_database.sql). Setiap backup otomatis diperiksa agar pasti bisa di-restore.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <button onclick="dbBackup('download')" class="${_bt}bg-blue-600 hover:bg-blue-700 justify-center">
                            <i class="fas fa-download">
                            </i> Unduh Langsung (.sql)</button>
                            <button onclick="dbBackup('storage')" class="${_bt}bg-indigo-600 hover:bg-indigo-700 justify-center">
                                <i class="fas fa-link">
                                </i> Simpan &amp; Buat Link</button>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2" id="dbHasilBox" style="display:none">
                            <div id="dbHasil">
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2 space-y-3">
                            <div class="flex flex-wrap justify-between items-center gap-2">
                                <h4 class="font-black text-gray-800 text-sm flex items-center gap-2">
                                    <i class="fas fa-cloud text-indigo-500">
                                    </i> Backup di Storage</h4>
                                    <div class="flex gap-2">
                                        <button onclick="dbDaftar()" class="${_bt}bg-gray-700 hover:bg-gray-800">
                                            <i class="fas fa-sync-alt">
                                            </i> Muat Ulang</button>
                                        </div>
                                    </div>
                                    <div id="dbDaftar" class="text-xs text-gray-400">Memuat...</div>
                                </div>
                                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2 space-y-3">
                                    <h4 class="font-black text-gray-800 text-sm flex items-center gap-2">
                                        <i class="fas fa-upload text-red-500">
                                        </i> Restore dari File .sql</h4>
                                        <p class="text-xs text-gray-500">Mode REPLACE: data saat ini dihapus lalu diganti isi file, dalam satu transaksi (gagal di tengah = data lama utuh). <b>Cek File</b> hanya memeriksa tanpa mengubah data. Sebelum restore, sistem membuat backup pengaman ke Storage.</p>
                                        <div class="flex flex-wrap gap-2">
                                            <button onclick="_gid('dbFileCek').click()" class="${_bt}bg-green-600 hover:bg-green-700">
                                                <i class="fas fa-check-double">
                                                </i> Cek File (aman)</button>
                                                <button onclick="_gid('dbFile').click()" class="${_bt}bg-red-600 hover:bg-red-700">
                                                    <i class="fas fa-upload">
                                                    </i> Restore dari File</button>
                                                </div>
                                                <input type="file" id="dbFileCek" accept=".sql" class="hidden" onchange="dbCekFile(this)">
                                                <input type="file" id="dbFile" accept=".sql" class="hidden" onchange="dbRestoreFile(this)">
                                            </div>
                                        </div>`;
  dbCekStatus(); dbDaftar();
}
async function dbCekStatus(){
  const el=_gid('dbStatus'); if(!el)return; el.innerHTML='<i class="fas fa-spinner fa-spin">
                                    </i> Memeriksa...';
  const chip=(ok,t)=>`<span class="inline-block text-[11px] font-black px-2.5 py-1 rounded-lg mr-1 mb-1 ${ok?'bg-green-100 text-green-700':'bg-red-100 text-red-600'}">${ok?'✓':'✗'} ${t}</span>`;
  try{
    const d=await dbPanggil('statusServer');
    el.innerHTML=chip(true,'Server terhubung (v'+_escH(d.versi)+')')+chip(d.service_key,d.service_key?'SUPABASE_SERVICE_ROLE_KEY terisi':'SUPABASE_SERVICE_ROLE_KEY belum diisi di Netlify')+chip(d.bucket,d.bucket?'Bucket "backups" ada':'Bucket "backups" dibuat otomatis saat backup pertama')+chip(true,d.tabel+' tabel dibackup');
  }catch(e){el.innerHTML=chip(false,'Server bermasalah')+`<p class="text-red-500 font-bold mt-1">${_escH(e.message)}</p>`;}
}
async function dbBackup(mode){
  try{
    Swal.fire({title:'Membuat backup...',text:'Mohon tunggu, jangan tutup halaman.',didOpen:()=>Swal.showLoading(),allowOutsideClick:false});
    const d=await dbPanggil('backupDatabase',{mode});
    const tot=d.ringkasan.reduce((a,b)=>a+b.baris,0),lw=d.ringkasan.filter(x=>x.lewati);
    if(d.sql){const a=document.createElement('a');a.href=URL.createObjectURL(new Blob([d.sql],{type:'text/plain'}));a.download=d.nama;document.body.appendChild(a);a.click();a.remove();}
    const v=d.validasi||{};
    _gid('dbHasilBox').style.display='block';
    _gid('dbHasil').innerHTML=`<div class="flex flex-wrap items-center gap-2 mb-3">
                                    <h4 class="font-black text-gray-800 text-sm">Hasil Backup</h4>
                                    <span class="text-xs font-bold text-gray-500">${_escH(d.nama)} • ${_kb(d.ukuran)} • ${tot} baris</span>${v.ok?'<span class="text-[11px] font-black px-2.5 py-1 rounded-lg bg-green-100 text-green-700">✓ Lolos pemeriksaan restore</span>':'<span class="text-[11px] font-black px-2.5 py-1 rounded-lg bg-red-100 text-red-600">✗ '+_escH(v.pesan||'Pemeriksaan gagal')+'</span>'}</div>
      ${d.url?`<a href="${_escH(d.url)}" target="_blank" class="text-blue-600 underline text-sm font-bold">
                                    <i class="fas fa-link mr-1">
                                    </i>Buka link unduhan (berlaku 7 hari)</a>`:''}
      ${lw.length?`<p class="text-xs text-orange-600 mt-2">Tabel dilewati: ${_escH(lw.map(x=>x.tabel+' ('+x.lewati+')').join('; '))}</p>`:''}
      <div class="overflow-x-auto mt-3">
                                    <table class="w-full text-xs">
                                        <thead class="bg-gray-50 text-[10px] uppercase text-gray-500">
                                            <tr>
                                                <th class="px-3 py-2 text-left">Tabel</th>
                                                <th class="px-3 py-2 text-right">Baris</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">${d.ringkasan.map(x=>`<tr>
                                        <td class="px-3 py-1.5 font-mono">${_escH(x.tabel)}</td>
                                        <td class="px-3 py-1.5 text-right font-bold ${x.lewati?'text-red-500':''}">${x.lewati?'gagal':x.baris}</td>
                                    </tr>`).join('')}</tbody>
                                </table>
                            </div>`;
    Swal.fire({icon:v.ok?'success':'warning',title:'Backup selesai',text:d.sql?'File .sql sedang diunduh.':'Backup tersimpan di Storage.',confirmButtonColor:'#2563eb'});
    dbDaftar();
  }catch(e){Swal.fire('Backup gagal',e.message,'error');}
}
async function dbDaftar(){
  const el=_gid('dbDaftar'); if(!el)return;
  try{
    const l=await dbPanggil('daftarBackup');
    el.innerHTML=l.length?`<div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead class="bg-gray-50 text-[10px] uppercase text-gray-500">
                                    <tr>
                                        <th class="px-3 py-2 text-left">File</th>
                                        <th class="px-3 py-2 text-left">Ukuran</th>
                                        <th class="px-3 py-2 text-left">Dibuat</th>
                                        <th class="px-3 py-2 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">${l.map(x=>`<tr>
                                <td class="px-3 py-2 font-mono font-bold text-gray-700">${_escH(x.nama)}</td>
                                <td class="px-3 py-2">${_kb(x.ukuran)}</td>
                                <td class="px-3 py-2">${x.dibuat?new Date(x.dibuat).toLocaleString('id-ID'):'—'}</td>
                                <td class="px-3 py-2 text-right whitespace-nowrap">
                                    <button data-n="${_escH(x.nama)}" onclick="dbUnduh(this.dataset.n)" title="Unduh" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg">
                                        <i class="fas fa-download">
                                        </i>
                                    </button>
                                    <button data-n="${_escH(x.nama)}" onclick="dbCekStorage(this.dataset.n)" title="Cek file (aman)" class="text-green-600 hover:bg-green-100 p-2 rounded-lg">
                                        <i class="fas fa-check-double">
                                        </i>
                                    </button>
                                    <button data-n="${_escH(x.nama)}" onclick="dbRestoreStorage(this.dataset.n)" title="Restore" class="text-red-500 hover:bg-red-100 p-2 rounded-lg">
                                        <i class="fas fa-undo">
                                        </i>
                                    </button>
                                    <button data-n="${_escH(x.nama)}" onclick="dbHapusStorage(this.dataset.n)" title="Hapus" class="text-gray-400 hover:bg-gray-100 p-2 rounded-lg">
                                        <i class="fas fa-trash-alt">
                                        </i>
                                    </button>
                                </td>
                            </tr>`).join('')}</tbody>
                        </table>
                    </div>`:'<p class="text-center py-4">Belum ada backup di Storage. Klik "Simpan & Buat Link".</p>';
  }catch(e){el.innerHTML='<p class="text-red-500 font-bold">'+_escH(e.message)+'</p>';}
}
async function dbUnduh(n){try{const d=await dbPanggil('linkBackup',{nama:n});window.open(d.url,'_blank');}catch(e){Swal.fire('Gagal',e.message,'error');}}
async function dbHapusStorage(n){const c=await Swal.fire({title:'Hapus backup ini?',text:n,icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Hapus'});if(!c.isConfirmed)return;try{await dbPanggil('hapusBackup',{nama:n});_okT('Backup dihapus');dbDaftar();}catch(e){Swal.fire('Gagal',e.message,'error');}}
function _htmlCek(d){return `<p class="text-sm">Format valid ✓</p>
                    <p class="text-xs text-gray-500 mt-1">${d.tabel} tabel • ${d.baris} baris • ${d.perintah} perintah SQL</p>
                    <div class="text-left text-[11px] bg-gray-50 border rounded-xl p-2 mt-2 max-h-40 overflow-y-auto font-mono">${d.ringkasan.map(x=>_escH(x.tabel)+': '+x.baris).join('<br>')}</div>`;}
async function dbCekStorage(n){try{Swal.fire({title:'Memeriksa...',didOpen:()=>Swal.showLoading(),allowOutsideClick:false});const d=await dbPanggil('restoreDatabase',{path:n,dryRun:true});Swal.fire({icon:'success',title:'File backup sehat',html:_htmlCek(d),confirmButtonColor:'#2563eb'});}catch(e){Swal.fire('File bermasalah',e.message,'error');}}
async function dbCekFile(inp){
  const f=inp.files[0];inp.value='';if(!f)return;
  if(f.size>4500000)return Swal.fire('File terlalu besar','Unggah ke bucket "backups" lalu pakai tombol cek di daftar Storage.','info');
  try{Swal.fire({title:'Memeriksa...',didOpen:()=>Swal.showLoading(),allowOutsideClick:false});const d=await dbPanggil('restoreDatabase',{sql:await f.text(),dryRun:true});Swal.fire({icon:'success',title:'File backup sehat',html:_htmlCek(d),confirmButtonColor:'#2563eb'});}catch(e){Swal.fire('File bermasalah',e.message,'error');}
}
async function _konfirmRestore(sumber){
  const a=await Swal.fire({title:'Semua data akan DITIMPA',html:'Data saat ini dihapus dan diganti isi <b>'+_escH(sumber)+'</b>. Lanjutkan?',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc2626',confirmButtonText:'Lanjutkan',cancelButtonText:'Batal'});
  if(!a.isConfirmed)return false;
  const b=await Swal.fire({title:'Konfirmasi final',text:'Ketik RESTORE untuk melanjutkan.',input:'text',showCancelButton:true,confirmButtonColor:'#dc2626',confirmButtonText:'Jalankan Restore',preConfirm:v=>v==='RESTORE'||Swal.showValidationMessage('Ketik persis: RESTORE')});
  return b.isConfirmed;
}
async function _jalankanRestore(extra){
  try{
    Swal.fire({title:'Membuat backup pengaman...',text:'Jangan tutup halaman.',didOpen:()=>Swal.showLoading(),allowOutsideClick:false});
    try{await dbPanggil('backupDatabase',{mode:'storage'});}
    catch(e){const l=await Swal.fire({icon:'warning',title:'Backup pengaman gagal',text:e.message+' — tetap lanjutkan restore?',showCancelButton:true,confirmButtonText:'Tetap Restore',confirmButtonColor:'#dc2626'});if(!l.isConfirmed)return;}
    Swal.fire({title:'Merestore database...',text:'Jangan tutup halaman.',didOpen:()=>Swal.showLoading(),allowOutsideClick:false});
    const r=await dbPanggil('restoreDatabase',extra);
    Swal.fire({icon:'success',title:'Restore selesai',text:r.tabel+' tabel, '+r.baris+' baris dipulihkan. Muat ulang halaman.',confirmButtonColor:'#2563eb'});
    dbDaftar();
  }catch(e){Swal.fire('Restore gagal',e.message,'error');}
}
async function dbRestoreFile(inp){
  const f=inp.files[0];inp.value='';if(!f)return;
  if(f.size>4500000)return Swal.fire('File terlalu besar','Unggah file ke bucket "backups" di Supabase Storage lalu pakai tombol restore di daftar Storage.','info');
  if(!(await _konfirmRestore(f.name)))return;
  _jalankanRestore({sql:await f.text()});
}
async function dbRestoreStorage(n){if(!(await _konfirmRestore(n)))return;_jalankanRestore({path:n});}


// ============ LOGIN PAKAI SEMESTER (admin) ============
let _semAdminList=[];
function _rentangSemAdmin(){
  const lab=localStorage.getItem('ea_semester_admin')||'';if(!lab)return null;
  const s=_semAdminList.find(x=>x.label===lab);return s&&s.tanggal_mulai&&s.tanggal_akhir?{dari:s.tanggal_mulai,sampai:s.tanggal_akhir}:null;
}
const _cbAsli=callBackend;
callBackend=function(p){
  try{const r=_rentangSemAdmin();if(r&&p&&p.type==='supabase_get'&&p.action==='getData'&&p.payload&&typeof p.payload.table==='string'&&/^(penilaian|laporan_guru|pengumuman|catatan_absen_guru)(\?|$)/.test(p.payload.table)){const tb=p.payload.table;p=Object.assign({},p,{payload:Object.assign({},p.payload,{table:tb+(tb.includes('?')?'&':'?')+'created_at=gte.'+encodeURIComponent(r.dari+'T00:00:00+07:00')+'&created_at=lte.'+encodeURIComponent(r.sampai+'T23:59:59.999+07:00')})});}
    if(r&&p&&p.type==='supabase_get'&&['getAllAspirasi','getAllAbsensi','getLogHistory'].includes(p.action))p=Object.assign({},p,{payload:Object.assign({},p.payload||{},{semFilter:r})});}catch(e){}
  return _cbAsli(p);
};
function _labelSemAdmin(){
  let el=document.getElementById('semAdminLabel');
  if(!el){const bar=document.querySelector('.bg-gray-900.text-white.text-xs.py-2');if(!bar)return;el=document.createElement('button');el.id='semAdminLabel';el.className='ml-3 text-yellow-300 font-bold whitespace-nowrap hover:underline';el.onclick=()=>pilihSemesterAdmin();bar.appendChild(el);}
  el.innerHTML='<i class="fas fa-calendar-alt mr-1">
                </i>'+_escH(localStorage.getItem('ea_semester_admin')||'Non Semester');
}
async function pilihSemesterAdmin(){
  try{_semAdminList=await _cbAsli({type:'supabase_get',action:'getData',payload:{table:'master_semester?order=urutan.desc'}}).then(r=>r.data||[]);}catch(e){_semAdminList=[];}
  const cur=localStorage.getItem('ea_semester_admin')||'';
  const r=await Swal.fire({title:'Pilih Semester',html:'<select id="semAdminSel" class="swal2-select" style="display:flex;width:80%">
                <option value="">Non Semester (Semua Data)</option>'+_semAdminList.map(s=>'<option value="'+_escH(s.label)+'"'+(s.label===cur?' selected':'')+'>'+_escH(s.label)+(s.aktif?' ★':'')+'</option>').join('')+'</select>
                <p class="text-xs text-gray-500 mt-3">Laporan, absensi, dan log difilter sesuai tanggal semester.</p>',allowOutsideClick:false,confirmButtonText:'Pilih',confirmButtonColor:'#2563eb',preConfirm:()=>document.getElementById('semAdminSel').value});
  localStorage.setItem('ea_semester_admin',r.value||'');sessionStorage.setItem('ea_sem_asked','1');_labelSemAdmin();
  if(userData)switchView('dashboard');
}
const _sdAsli=setupDashboard;
setupDashboard=function(){_sdAsli();if(!sessionStorage.getItem('ea_sem_asked'))pilihSemesterAdmin();else{_cbAsli({type:'supabase_get',action:'getData',payload:{table:'master_semester'}}).then(r=>{_semAdminList=r.data||[];_labelSemAdmin();}).catch(()=>{});}};
document.getElementById('loginForm').addEventListener('submit',()=>sessionStorage.removeItem('ea_sem_asked'),true);
</script>
                <script>
    // =========================================================
    // GUARD HAK AKSES TOMBOL (aksi_*) — dipasang setelah semua script lain dimuat
    // 1) Fungsi aksi dibungkus: kalau jabatan tidak punya izin, aksi dibatalkan.
    // 2) Tombol onclick yang tidak diizinkan disembunyikan otomatis (juga yang dirender belakangan).
    // Catatan: ini pembatasan di tampilan admin (sama seperti pembatasan menu), bukan pengganti validasi server.
    // =========================================================
    (function () {
        const GT_FN = { hapus: 'aksi_hapus', blokir: 'aksi_blokir', buka: 'aksi_blokir', nonaktif: 'aksi_blokir', aktif: 'aksi_blokir' };
        const tambahEdit = (i) => (a) => (a[i] !== undefined && a[i] !== null && a[i] !== '') ? 'aksi_edit' : 'aksi_tambah';
        const RULE = {
            bukaModalData: (a) => a[1] === 'edit' ? 'aksi_edit' : 'aksi_tambah',
            gtForm: tambahEdit(0), bukaModalRole: tambahEdit(0), bukaModalKelas: tambahEdit(0),
            bukaEditSiswa: 'aksi_edit', editBukuSakuInline: 'aksi_edit', ghReset: 'aksi_edit',
            simpanPengumuman: 'aksi_tambah',
            hapusSatuData: (a) => a[2] === true ? 'aksi_reset_data' : 'aksi_hapus',
            gtHapus: 'aksi_hapus', gtHapusMassal: 'aksi_hapus', bulkHapus: 'aksi_hapus', bulkHapusRole: 'aksi_hapus', bulkHapusKelas: 'aksi_hapus',
            bulkHapusPengumuman: 'aksi_hapus', bulkHapusKomentar: 'aksi_hapus', bulkHapusBuku: 'aksi_hapus', hapusPengumuman: 'aksi_hapus',
            bukaModalImport: 'aksi_impor', gtBukaImpor: 'aksi_impor', prosesImportExcel: 'aksi_impor',
            exportToExcel: 'aksi_ekspor', gtEkspor: 'aksi_ekspor', exportPenilaianExcel: 'aksi_ekspor', downloadTemplate: 'aksi_ekspor',
            bukaModalBalasan: 'aksi_balas', kirimBalasanAdmin: 'aksi_balas', simpanStatusAspirasi: 'aksi_balas', bulkUpdateStatus: 'aksi_balas',
            toggleBlokir: 'aksi_blokir', toggleBlokirGuru: 'aksi_blokir', bulkBlokir: 'aksi_blokir', bulkBukaBlokir: 'aksi_blokir',
            resetPinSiswa: 'aksi_reset_pin',
            bulkResetPoin: 'aksi_reset_data', bulkResetAbsensi: 'aksi_reset_data',
            bulkNaikKelas: 'aksi_naik_kelas', kembalikanAlumni: 'aksi_naik_kelas',
            lihatQRSiswa: 'aksi_qr', unduhQRSiswaCanvas: 'aksi_qr', downloadSemuaQR: 'aksi_qr',
            bukaGaleriFotoAbsensi: 'aksi_foto',
            jalankanReminder: 'aksi_sistem', jalankanLaporanBerkala: 'aksi_sistem', jalankanBackup: 'aksi_sistem', jalankanSyncSheets: 'aksi_sistem',
            setSemesterAktif: 'aksi_sistem', tambahLiburHariIni: 'aksi_sistem', gtHariSinkron: 'aksi_sistem', gtHariBulanIni: 'aksi_sistem',
            gtTindakan: (a) => GT_FN[a[0]] || 'aksi_edit'
        };
        const kodeUntuk = (nama, args) => { const r = RULE[nama]; return typeof r === 'function' ? r(args) : r; };

        function toast(kode) {
            try { Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Jabatanmu tidak punya izin: ' + labelAkses(kode), showConfirmButton: false, timer: 2500 }); } catch (e) {}
        }

        // 1) bungkus fungsi
        Object.keys(RULE).forEach(nama => {
            const asli = window[nama];
            if (typeof asli !== 'function' || asli.__rbac) return;
            const bungkus = function () {
                const kode = kodeUntuk(nama, Array.prototype.slice.call(arguments));
                if (kode && !bisaAksi(kode)) { toast(kode); return; }
                return asli.apply(this, arguments);
            };
            bungkus.__rbac = true;
            window[nama] = bungkus;
        });

        // 2) sembunyikan tombol
        const parseArgs = (str) => {
            if (!str.trim()) return [];
            return str.split(',').map(x => { x = x.trim().replace(/^['"`]|['"`]$/g, ''); return x === 'true' ? true : x === 'false' ? false : x === 'null' ? null : x === 'undefined' ? undefined : x; });
        };
        const kodeDariOnclick = (code) => {
            const m = /^\s*([A-Za-z_$][\w$]*)\s*\(([^)]*)\)/.exec(code || '');
            return m && RULE[m[1]] ? kodeUntuk(m[1], parseArgs(m[2])) : null;
        };
        function pindai(root) {
            if (!root || root.nodeType !== 1) return;
            const els = []; if (root.hasAttribute('onclick')) els.push(root);
            root.querySelectorAll('[onclick]').forEach(e => els.push(e));
            els.forEach(el => {
                const kode = kodeDariOnclick(el.getAttribute('onclick'));
                if (!kode) return;
                if (bisaAksi(kode)) { if (el.hasAttribute('data-rbac')) { el.style.display = ''; el.removeAttribute('data-rbac'); } }
                else { el.style.display = 'none'; el.setAttribute('data-rbac', '1'); }
            });
        }
        window.pindaiHakAksesTombol = () => pindai(document.body);

        // setelah login/sidebar dibangun userData sudah ada -> pindai ulang seluruh halaman
        const sidebarAsli = window.renderSidebarMenu;
        if (typeof sidebarAsli === 'function') {
            window.renderSidebarMenu = function () { const r = sidebarAsli.apply(this, arguments); pindai(document.body); return r; };
        }
        // elemen yang dirender belakangan (tabel, modal, dropdown tindakan)
        let antri = [], jadwal = false;
        new MutationObserver(ms => {
            ms.forEach(m => m.addedNodes.forEach(n => { if (n.nodeType === 1) antri.push(n); }));
            if (jadwal) return; jadwal = true;
            requestAnimationFrame(() => { jadwal = false; const x = antri; antri = []; x.forEach(pindai); });
        }).observe(document.body, { childList: true, subtree: true });
        pindai(document.body);
    })();
    </script>
                <script src="{{ asset('/grisVA.js') }}" defer="">
                </script>
            </body>
        </html>