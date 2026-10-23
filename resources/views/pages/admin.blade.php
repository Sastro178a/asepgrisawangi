@extends('layouts.app')

@section('content')


    <!-- TOP BAR -->
<div class="bg-gray-900 text-white text-xs py-2 px-4 flex justify-between items-center z-[60] flex-shrink-0 border-b border-gray-700 shadow-md w-full">
    <div class="flex items-center gap-2 font-mono font-bold tracking-widest text-yellow-400 whitespace-nowrap z-10 pr-4 border-r border-gray-700">
        <i class="far fa-clock">
        </i>
        <span id="realtimeClock">Memuat...</span>
    </div>
    <div class="flex-1 pl-4 font-medium text-gray-300 tracking-wide text-[10px] md:text-xs truncate">
        <i class="fas fa-shield-alt text-blue-400 mr-1">
        </i> Sistem Pusat Kendali &amp; Manajemen Data E-Aspirasi Grisawangi
        </div>
    </div>
    <!-- LAYAR LOGIN ADMIN -->
    <div id="loginScreen" class="fixed inset-0 z-50 bg-gray-900 flex items-center justify-center p-4 fade-in overflow-y-auto">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 md:p-8 relative mt-8 md:mt-0">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-100 rounded-full blur-3xl">
            </div>
            <div class="text-center mb-8 relative z-10">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-lg">
                    <img src="{{ asset('icon.png') }}" onerror="this.onerror=null; this.src='https://iili.io/qfChwml.png';" alt="Logo" class="w-12 h-12 object-contain">
                </div>
                <h1 class="text-2xl font-black text-gray-800 tracking-tight">Portal Control</h1>
                <p class="text-xs text-gray-500 font-bold mt-1 uppercase tracking-widest">Login Admin &amp; Manajemen</p>
            </div>
            <form id="loginForm" class="space-y-5 relative z-10">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-2">Email Terdaftar</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400">
                        </i>
                        <input type="email" id="loginEmail" required="" class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition font-bold text-gray-800" placeholder="email@sekolah.com">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-2">Kata Sandi</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400">
                        </i>
                        <input type="password" id="loginPassword" required="" class="w-full pl-11 pr-12 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition font-bold text-gray-800" placeholder="••••••••">
                        <i class="fas fa-eye absolute right-4 top-3.5 text-gray-400 cursor-pointer hover:text-blue-500 transition" onclick="togglePassword('loginPassword', this)">
                        </i>
                    </div>
                </div>
                <button type="submit" id="btnLogin" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-lg transition active:scale-95 uppercase tracking-widest text-sm mt-2 flex justify-center items-center gap-2">
                    Masuk Sistem <i class="fas fa-sign-in-alt">
            </i>
        </button>
    </form>
    <div class="mt-4 pt-4 border-t border-gray-100 text-center">
        <button onclick="masukDemoMode()" class="w-full flex items-center justify-center gap-2 text-sm font-bold text-gray-500 hover:text-blue-600 hover:bg-blue-50 py-3 px-4 rounded-xl transition border border-gray-200 hover:border-blue-300 active:scale-95">
            <i class="fas fa-eye text-blue-400">
            </i> Lihat Demo Tampilan (Tanpa Login)
                </button>
            <p class="text-[10px] text-gray-400 mt-2">Demo hanya menampilkan data contoh, tidak terhubung ke database</p>
        </div>
    </div>
</div>
<!-- DASHBOARD UTAMA -->
<div id="mainDashboard" class="flex-1 flex overflow-hidden relative w-full hidden">
    <div id="sidebarOverlay" onclick="toggleSidebarMobile()" class="fixed inset-0 bg-gray-900/60 z-40 hidden md:hidden backdrop-blur-sm transition-opacity">
    </div>
    <aside id="sidebar" class="w-64 bg-gray-900 text-white flex flex-col transition-transform duration-300 z-50 fixed md:relative inset-y-0 left-0 transform -translate-x-full md:translate-x-0 shadow-2xl h-full border-r border-gray-800 flex-shrink-0">
        <div class="p-5 flex items-center justify-between border-b border-gray-800">
            <div class="flex items-center gap-3">
                <img src="{{ asset('icon.png') }}" id="sidebarLogo" onerror="this.onerror=null; this.src='https://iili.io/qfChwml.png';" alt="Logo" class="w-8 h-8 object-contain bg-white rounded-md p-1">
                <span id="sidebarNamaWeb" class="font-black tracking-tight text-lg">E-ASPIRASI</span>
            </div>
            <button id="closeSidebarBtn" class="md:hidden text-gray-400 hover:text-white">
                <i class="fas fa-times text-xl">
                </i>
            </button>
        </div>
        <div class="p-4 border-b border-gray-800 bg-gray-800/50">
            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Masuk Sebagai</p>
            <h3 id="sidebarNama" class="font-bold text-sm truncate text-blue-100">Memuat...</h3>
            <span id="sidebarRole" class="inline-block mt-1 px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded text-[10px] font-black tracking-widest uppercase border border-blue-500/30">Role</span>
        </div>
        <div class="px-3 pt-3 flex-shrink-0">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-500 text-xs">
                </i>
                <input type="text" id="menuSearch" placeholder="Cari menu..." autocomplete="off" oninput="cariMenu(this.value)" onkeydown="if(event.key==='Enter'){ const f=[...document.querySelectorAll('#menuNav a')].find(x=&gt;!x.classList.contains('hidden')); if(f) f.click(); }" class="w-full pl-8 pr-3 py-2 rounded-xl bg-gray-800 text-gray-200 text-xs font-bold border border-gray-700 focus:border-blue-500 outline-none placeholder-gray-500">
            </div>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 hide-scroll" id="menuNav">
        </nav>
        <div class="p-4 border-t border-gray-800">
            <button onclick="handleLogout()" class="w-full flex items-center justify-center gap-2 bg-red-500/10 hover:bg-red-500 hover:text-white text-red-500 font-bold py-3 rounded-xl transition uppercase tracking-widest text-xs">
                <i class="fas fa-power-off">
                </i> Keluar Sistem
                </button>
            </div>
        </aside>
        <!-- KONTEN KANAN -->
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-gray-50 relative">
            <div class="flex-1 overflow-y-auto p-4 md:p-6 pb-24 relative" id="workspace">
                <!-- VIEW: DASHBOARD -->
                <div id="view-dashboard" class="page-view fade-in w-full space-y-6">
                    <!-- STAT CARDS -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Total Laporan</p>
                                    <h3 class="text-3xl font-black text-gray-800 mt-1" id="statLaporan">0</h3>
                                    <p class="text-[10px] text-blue-500 font-bold mt-1" id="statLaporanSub">
                                    </p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-lg">
                                    <i class="fas fa-bullhorn">
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-green-500">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Total Masukan</p>
                                    <h3 class="text-3xl font-black text-gray-800 mt-1" id="statMasukan">0</h3>
                                    <p class="text-[10px] text-green-500 font-bold mt-1" id="statMasukanSub">
                                    </p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-lg">
                                    <i class="fas fa-comment-dots">
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-purple-500">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Jadwal Konselor</p>
                                    <h3 class="text-3xl font-black text-gray-800 mt-1" id="statKonselor">0</h3>
                                    <p class="text-[10px] text-purple-500 font-bold mt-1" id="statKonselorSub">
                                    </p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center text-lg">
                                    <i class="fas fa-user-graduate">
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-orange-500">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Total Siswa</p>
                                    <h3 class="text-3xl font-black text-gray-800 mt-1" id="statSiswa">0</h3>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center text-lg">
                                    <i class="fas fa-users">
                                    </i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FILTER PERIODE DASHBOARD -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-black text-gray-500 uppercase tracking-widest mr-1">Periode:</span>
                        <button onclick="setDashboardFilter(7)" id="dbf7" class="px-4 py-2 rounded-xl text-xs font-black transition bg-blue-600 text-white shadow-md">7 Hari</button>
                        <button onclick="setDashboardFilter(30)" id="dbf30" class="px-4 py-2 rounded-xl text-xs font-black transition bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">1 Bulan</button>
                        <button onclick="setDashboardFilter(365)" id="dbf365" class="px-4 py-2 rounded-xl text-xs font-black transition bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">1 Tahun</button>
                        <button onclick="setDashboardFilter(0)" id="dbf0" class="px-4 py-2 rounded-xl text-xs font-black transition bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">Semua</button>
                    </div>
                    <!-- GRAFIK -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Grafik Tren Aspirasi per Hari -->
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                            <h4 class="font-black text-gray-800 text-sm mb-4 flex items-center gap-2">
                                <i class="fas fa-chart-line text-blue-500">
                                </i> Tren Pengajuan per Hari</h4>
                                <canvas id="chartTren" height="200">
                                </canvas>
                            </div>
                            <!-- Grafik Distribusi Tipe -->
                            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                <h4 class="font-black text-gray-800 text-sm mb-4 flex items-center gap-2">
                                    <i class="fas fa-chart-donut text-purple-500">
                                    </i> Distribusi Tipe Pengajuan</h4>
                                    <canvas id="chartDistribusi" height="200">
                                    </canvas>
                                </div>
                                <!-- Grafik Status -->
                                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                    <h4 class="font-black text-gray-800 text-sm mb-4 flex items-center gap-2">
                                        <i class="fas fa-chart-bar text-green-500">
                                        </i> Status Penanganan</h4>
                                        <canvas id="chartStatus" height="200">
                                        </canvas>
                                    </div>
                                    <!-- Grafik Kategori Laporan -->
                                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                        <h4 class="font-black text-gray-800 text-sm mb-4 flex items-center gap-2">
                                            <i class="fas fa-chart-pie text-red-500">
                                            </i> Kategori Laporan Terbanyak</h4>
                                            <canvas id="chartKategori" height="200">
                                            </canvas>
                                        </div>
                                    </div>
                                </div>
                                <!-- VIEW: DATATABLE MASTER GLOBAL -->
                                <div id="view-datatable" class="page-view hidden fade-in h-full flex flex-col w-full">
                                    <div class="bg-white p-3 md:p-4 rounded-2xl shadow-sm border border-gray-100 mb-4 flex flex-col gap-3 w-full">
                                        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:flex-1">
                                            <div class="relative w-full sm:max-w-xs">
                                                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400">
                                                </i>
                                                <input type="text" id="dtSearch" onkeyup="{{ env('ONKEYUP') }}" class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-bold text-gray-800" placeholder="Ketik untuk mencari...">
                                            </div>
                                            <div id="dtDynamicFilterContainer" class="w-full flex flex-wrap gap-2">
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 flex-wrap w-full" id="dtActionsContainer">
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex-1 flex flex-col relative w-full max-h-[70vh]">
                                        <div class="overflow-x-auto overflow-y-auto flex-1 w-full hide-scroll">
                                            <table class="w-full text-sm text-left whitespace-nowrap" id="mainDataTable">
                                                <thead class="bg-gray-100 border-b font-black text-[10px] uppercase tracking-widest text-gray-500 sticky top-0 z-10" id="dtHead">
                                                </thead>
                                                <tbody id="dtBody" class="font-medium text-gray-700 divide-y divide-gray-100">
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="bg-gray-50 border-t p-3 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs font-bold text-gray-500 rounded-b-2xl">
                                            <span id="dtInfo" class="uppercase tracking-widest">Menampilkan 0 data</span>
                                            <div id="dtPaginationControls" class="flex gap-2 items-center">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- VIEW: PENGATURAN SISTEM WEB -->
                                <div id="view-settings" class="page-view hidden fade-in w-full">
                                    <div class="bg-white rounded-2xl shadow-sm border-t-4 border-blue-600 p-5 md:p-8 w-full">
                                        <h2 class="text-2xl font-black text-gray-800 mb-6 flex items-center gap-2 tracking-tight">
                                            <i class="fas fa-cogs text-blue-600">
                                            </i> Pengaturan Website &amp; API</h2>
                                            <form id="formSettings" class="space-y-6">
                                                <div class="bg-indigo-50 p-5 md:p-6 rounded-2xl border border-indigo-100 relative">
                                                    <span class="absolute -top-3 left-4 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-sm">Fitur UI Siswa</span>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                        <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-indigo-100 shadow-sm">
                                                            <div>
                                                                <h4 class="text-sm font-black text-gray-800">Kelas Read-Only (Login)</h4>
                                                            </div>
                                                            <label class="relative inline-flex items-center cursor-pointer ml-4">
                                                                <input type="checkbox" id="setKelasReadonly" class="sr-only peer">
                                                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner">
                                                                </div>
                                                            </label>
                                                        </div>
                                                        <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-indigo-100 shadow-sm">
                                                            <div>
                                                                <h4 class="text-sm font-black text-gray-800">Tampilkan Poin Pelanggaran</h4>
                                                            </div>
                                                            <label class="relative inline-flex items-center cursor-pointer ml-4">
                                                                <input type="checkbox" id="setTampilkanPoin" class="sr-only peer">
                                                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner">
                                                                </div>
                                                            </label>
                                                        </div>
                                                        <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-indigo-100 shadow-sm md:col-span-2">
                                                            <div>
                                                                <h4 class="text-sm font-black text-gray-800">Aktifkan Brosur Web</h4>
                                                            </div>
                                                            <label class="relative inline-flex items-center cursor-pointer ml-4">
                                                                <input type="checkbox" id="setBrosurAktif" class="sr-only peer">
                                                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner">
                                                                </div>
                                                            </label>
                                                        </div>
                                                        <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-indigo-100 shadow-sm md:col-span-2">
                                                            <div>
                                                                <h4 class="text-sm font-black text-gray-800">Tampilkan Popup Komentar Penilaian</h4>
                                                                <p class="text-xs text-gray-500 mt-0.5">Komentar penilaian siswa muncul sekilas di beranda</p>
                                                            </div>
                                                            <label class="relative inline-flex items-center cursor-pointer ml-4">
                                                                <input type="checkbox" id="setTampilkanKomentar" class="sr-only peer">
                                                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner">
                                                                </div>
                                                            </label>
                                                        </div>
                                                        <div class="bg-white p-4 rounded-xl border border-indigo-100 shadow-sm">
                                                            <label class="block text-xs font-black text-gray-700 uppercase tracking-widest mb-1.5">Link Foto Brosur</label>
                                                            <input type="url" id="setLinkBrosur" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm bg-gray-50 text-gray-800">
                                                        </div>
                                                        <div class="bg-white p-4 rounded-xl border border-indigo-100 shadow-sm">
                                                            <label class="block text-xs font-black text-gray-700 uppercase tracking-widest mb-1.5">Link Video Intro (MP4)</label>
                                                            <input type="url" id="setLinkIntro" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm bg-gray-50 text-gray-800">
                                                        </div>
                                                        <div class="bg-white p-4 rounded-xl border border-indigo-100 shadow-sm md:col-span-2">
                                                            <label class="block text-xs font-black text-gray-700 uppercase tracking-widest mb-1.5">Link Audio BGM (MP3)</label>
                                                            <input type="url" id="setAudioBgm" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm bg-gray-50 text-gray-800">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-teal-50 p-5 md:p-6 rounded-2xl border border-teal-100 relative mt-8">
                                                    <span class="absolute -top-3 left-4 bg-teal-600 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-sm">Portal Guru</span>
                                                    <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-teal-100 shadow-sm">
                                                        <div>
                                                            <h4 class="text-sm font-black text-gray-800">Batasi data siswa sesuai jadwal mengajar</h4>
                                                            <p class="text-xs text-gray-500 mt-0.5">ON: guru hanya melihat absensi kelas saat jam mengajarnya (butuh Manajemen Jam/Hari/Jadwal terisi). OFF: guru melihat semua kelas yang ia ajar kapan saja.</p>
                                                        </div>
                                                        <label class="relative inline-flex items-center cursor-pointer ml-4">
                                                            <input type="checkbox" id="setGuruBatasiJadwal" class="sr-only peer" checked="">
                                                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600 shadow-inner">
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-1 gap-6 mt-8">
                                                    <!-- SECTION: API KEYS EKSTERNAL -->
                                                    <div class="bg-gray-900 p-5 rounded-2xl border border-gray-700 relative">
                                                        <span class="absolute -top-3 left-4 bg-gray-800 text-yellow-400 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-sm border border-gray-600">🔑 API Keys &amp; Token Eksternal</span>
                                                        <p class="text-xs text-gray-400 mb-5 mt-1">Semua key disimpan di database — tidak perlu edit file atau Netlify ENV lagi.</p>
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                            <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                                                <label class="block text-[10px] font-black text-green-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                                    <i class="fab fa-whatsapp">
                                                                    </i> Fonnte Token #1 — Notif ke Siswa</label>
                                                                    <input type="text" id="setFonnteToken" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-green-500 font-mono text-xs bg-gray-900 text-green-300" placeholder="Token dari dashboard.fonnte.com">
                                                                    <p class="text-[10px] text-gray-500 mt-1.5">Dipakai saat status laporan berubah → notif WA ke siswa</p>
                                                                </div>
                                                                <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                                                    <label class="block text-[10px] font-black text-cyan-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                                        <i class="fab fa-whatsapp">
                                                                        </i> Fonnte Token #2 — Pesan Admin/Guru</label>
                                                                        <input type="text" id="setFonnteAdmin" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-cyan-500 font-mono text-xs bg-gray-900 text-cyan-300" placeholder="Token cadangan untuk pesan ke admin/guru">
                                                                        <p class="text-[10px] text-gray-500 mt-1.5">Dipakai untuk reminder, laporan berkala, pengumuman ke guru. Jika kosong, pakai Token #1.</p>
                                                                    </div>
                                                                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                                                        <label class="block text-[10px] font-black text-red-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                                            <i class="fas fa-bell">
                                                                            </i> OneSignal App ID</label>
                                                                            <input type="text" id="setOnesignalAppId" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-red-500 font-mono text-xs bg-gray-900 text-red-300" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx">
                                                                            <p class="text-[10px] text-gray-500 mt-1.5">OneSignal Dashboard → Settings → Keys &amp; IDs</p>
                                                                        </div>
                                                                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                                                            <label class="block text-[10px] font-black text-red-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                                                <i class="fas fa-key">
                                                                                </i> OneSignal REST API Key</label>
                                                                                <input type="password" id="setOnesignalApiKey" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-red-500 font-mono text-xs bg-gray-900 text-red-300" placeholder="os_v2_app_...">
                                                                                <p class="text-[10px] text-gray-500 mt-1.5">OneSignal Dashboard → Settings → Keys &amp; IDs → REST API Key</p>
                                                                            </div>
                                                                            <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                                                                <label class="block text-[10px] font-black text-purple-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                                                    <i class="fas fa-lock">
                                                                                    </i> VAPID Private Key</label>
                                                                                    <input type="password" id="setVapidPrivate" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-purple-500 font-mono text-xs bg-gray-900 text-purple-300" placeholder="VAPID private key...">
                                                                                    <p class="text-[10px] text-gray-500 mt-1.5">Generate di vapidkeys.com — jangan disebarkan!</p>
                                                                                </div>
                                                                                <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                                                                    <label class="block text-[10px] font-black text-purple-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                                                        <i class="fas fa-unlock">
                                                                                        </i> VAPID Public Key</label>
                                                                                        <input type="text" id="setVapidPublic" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-purple-500 font-mono text-xs bg-gray-900 text-purple-300" placeholder="VAPID public key...">
                                                                                        <p class="text-[10px] text-gray-500 mt-1.5">Pasangkan juga di index.html bagian VAPID_PUBLIC_KEY</p>
                                                                                    </div>
                                                                                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                                                                        <label class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                                                            <i class="fab fa-telegram">
                                                                                            </i> Telegram Bot Token</label>
                                                                                            <input type="password" id="setTeleToken" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 font-mono text-xs bg-gray-900 text-blue-300" placeholder="Bot token dari @BotFather">
                                                                                        </div>
                                                                                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                                                                            <label class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                                                                <i class="fas fa-at">
                                                                                                </i> Telegram Chat ID</label>
                                                                                                <input type="text" id="setTeleChat" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 font-mono text-xs bg-gray-900 text-blue-300" placeholder="-100xxxxxxxxxx">
                                                                                                <p class="text-[10px] text-gray-500 mt-1.5">Bisa lebih dari satu, pisahkan dengan koma</p>
                                                                                            </div>
                                                                                            <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                                                                                <label class="block text-[10px] font-black text-orange-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                                                                    <i class="fas fa-image">
                                                                                                    </i> FreeImage API Key <span class="text-gray-500 font-normal normal-case">(Upload foto utama)</span>
                                                                                                </label>
                                                                                                <input type="text" id="setFreeImage" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-orange-500 font-mono text-xs bg-gray-900 text-orange-300" placeholder="FreeImage API key">
                                                                                                <label class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1.5 mt-3 flex items-center gap-1.5">
                                                                                                    <i class="fas fa-image">
                                                                                                    </i> imgbb API Key <span class="text-gray-500 font-normal normal-case">(Fallback jika FreeImage gagal — daftar di api.imgbb.com)</span>
                                                                                                </label>
                                                                                                <input type="text" id="setImgbb" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 font-mono text-xs bg-gray-900 text-blue-300" placeholder="imgbb API key (opsional, direkomendasikan)">
                                                                                            </div>
                                                                                            <!-- MANUAL ABSEN SETTINGS -->
                                                                                            <div class="bg-gray-800 p-4 rounded-xl border border-blue-700/50">
                                                                                                <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                                                                                    <i class="fas fa-camera">
                                                                                                    </i> Pengaturan Absensi Manual (manualabs.html)
                                        </p>
                                                                                                    <div class="space-y-3">
                                                                                                        <!-- TOGGLE AKTIFKAN MANUAL ABSEN -->
                                                                                                        <div class="flex items-center justify-between bg-gray-900/60 rounded-xl px-4 py-3 border border-gray-700">
                                                                                                            <div>
                                                                                                                <p class="text-xs font-black text-white">Aktifkan Absen Manual</p>
                                                                                                                <p class="text-[10px] text-gray-500 mt-0.5">Jika OFF, tombol absen manual di halaman siswa disembunyikan</p>
                                                                                                            </div>
                                                                                                            <button id="toggleManualAbsen" onclick="toggleAktifManualAbsen()" class="relative w-12 h-6 rounded-full transition-colors duration-300 bg-gray-600 flex-shrink-0" data-aktif="false">
                                                                                                                <span class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform duration-300">
                                                                                                                </span>
                                                                                                            </button>
                                                                                                        </div>
                                                                                                        <!-- TOGGLE WAJIB DALAM AREA -->
                                                                                                        <div class="flex items-center justify-between bg-gray-900/60 rounded-xl px-4 py-3 border border-gray-700">
                                                                                                            <div>
                                                                                                                <p class="text-xs font-black text-white">Wajib Dalam Area Sekolah</p>
                                                                                                                <p class="text-[10px] text-gray-500 mt-0.5">Jika ON, siswa di luar radius tidak bisa absen manual</p>
                                                                                                            </div>
                                                                                                            <button id="toggleWajibGeofence" onclick="toggleWajibGeofenceFn()" class="relative w-12 h-6 rounded-full transition-colors duration-300 bg-gray-600 flex-shrink-0" data-aktif="false">
                                                                                                                <span class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform duration-300">
                                                                                                                </span>
                                                                                                            </button>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <label class="block text-[10px] font-bold text-gray-400 mb-1">Batas Jam Terlambat (WIB)</label>
                                                                                                            <div class="flex gap-2">
                                                                                                                <input type="number" id="setAbsenJamMulai" min="0" max="23" placeholder="7" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 font-mono text-xs bg-gray-900 text-blue-300">
                                                                                                                <span class="text-gray-500 text-xs self-center flex-shrink-0">:00 WIB</span>
                                                                                                            </div>
                                                                                                            <p class="text-[10px] text-gray-600 mt-1">Default: 7 (07:00 WIB). Absen manual pada/setelah jam ini tercatat Terlambat dan kena poin di bawah. Yang tidak absen tidak diproses (dikonfirmasi guru).</p>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <label class="block text-[10px] font-bold text-gray-400 mb-1">Poin Terlambat</label>
                                                                                                            <input type="number" id="setAbsenPoinTerlambat" min="0" max="100" placeholder="6" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 font-mono text-xs bg-gray-900 text-blue-300">
                                                                                                            <p class="text-[10px] text-gray-600 mt-1">Default: 6 poin. Ditambahkan otomatis saat siswa absen manual terlambat.</p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <!-- HARI LIBUR CUSTOM -->
                                                                                                <div class="bg-gray-800 p-4 rounded-xl border border-purple-700/50">
                                                                                                    <p class="text-[10px] font-black text-purple-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                                                                                        <i class="fas fa-calendar-times">
                                                                                                        </i> Hari Libur / Tanggal Merah
                                        </p>
                                                                                                        <div class="space-y-2">
                                                                                                            <label class="block text-[10px] font-bold text-gray-400 mb-1">Daftar Tanggal Libur <span class="text-gray-600 font-normal normal-case">(pisahkan dengan koma, format YYYY-MM-DD)</span>
                                                                                                        </label>
                                                                                                        <textarea id="setHariLibur" rows="3" placeholder="Contoh: 2025-08-17, 2025-12-25, 2026-01-01" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-purple-500 font-mono text-xs bg-gray-900 text-purple-300 resize-none">
                                                                                                        </textarea>
                                                                                                        <p class="text-[10px] text-gray-600">Sabtu &amp; Minggu otomatis libur (dibaca dari jam perangkat siswa). Tambahkan libur nasional atau hari khusus sekolah di sini.</p>
                                                                                                        <button type="button" onclick="tambahLiburHariIni()" class="text-[10px] font-bold text-purple-400 hover:text-purple-300 flex items-center gap-1 transition">
                                                                                                            <i class="fas fa-plus-circle">
                                                                                                            </i> Tambah Hari Ini sebagai Libur
                                            </button>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <!-- LOKASI SEKOLAH / GEOFENCE -->
                                                                                                    <div class="bg-gray-800 p-4 rounded-xl border border-green-700/50">
                                                                                                        <p class="text-[10px] font-black text-green-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                                                                                            <i class="fas fa-map-marker-alt">
                                                                                                            </i> Lokasi Sekolah (Geofence Absensi)
                                        </p>
                                                                                                            <div class="space-y-3">
                                                                                                                <div class="grid grid-cols-2 gap-2">
                                                                                                                    <div>
                                                                                                                        <label class="block text-[10px] font-bold text-gray-400 mb-1">Latitude</label>
                                                                                                                        <input type="text" id="setGeofenceLat" placeholder="-8.123456" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-green-500 font-mono text-xs bg-gray-900 text-green-300">
                                                                                                                    </div>
                                                                                                                    <div>
                                                                                                                        <label class="block text-[10px] font-bold text-gray-400 mb-1">Longitude</label>
                                                                                                                        <input type="text" id="setGeofenceLon" placeholder="114.123456" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-green-500 font-mono text-xs bg-gray-900 text-green-300">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div>
                                                                                                                    <label class="block text-[10px] font-bold text-gray-400 mb-1">Radius Geofence (meter)</label>
                                                                                                                    <input type="number" id="setGeofenceRadius" placeholder="100" min="10" max="5000" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-green-500 font-mono text-xs bg-gray-900 text-green-300">
                                                                                                                    <p class="text-[10px] text-gray-600 mt-1">Default: 100 meter. Siswa harus dalam radius ini untuk absen.</p>
                                                                                                                </div>
                                                                                                                <div>
                                                                                                                    <label class="block text-[10px] font-bold text-gray-400 mb-1 flex items-center justify-between">
                                                                                                                        <span>Maps API Key (Google)</span>
                                                                                                                        <button type="button" onclick="bukaTutorialMapsApi()" class="w-5 h-5 rounded-full border border-blue-400 bg-blue-900/40 text-blue-400 text-[10px] font-black hover:bg-blue-800/60 transition flex items-center justify-center">?</button>
                                                                                                                    </label>
                                                                                                                    <input type="text" id="setMapsApiKey" placeholder="AIzaSy..." class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-green-500 font-mono text-xs bg-gray-900 text-green-300">
                                                                                                                    <p class="text-[10px] text-gray-600 mt-1">Opsional — untuk tampilkan peta di manualabs.html. Klik (?) untuk tutorial.</p>
                                                                                                                </div>
                                                                                                                <!-- Tombol cari koordinat -->
                                                                                                                <button type="button" onclick="cariKoordinatSekolah()" class="w-full flex items-center justify-center gap-2 bg-green-700/30 hover:bg-green-700/50 border border-green-600/40 text-green-400 text-xs font-bold py-2.5 rounded-xl transition">
                                                                                                                    <i class="fas fa-crosshairs">
                                                                                                                    </i> Gunakan Lokasi Saya Sekarang
                                            </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                                                                                                <label class="block text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                                                                                    <i class="fas fa-table">
                                                                                                                    </i> Google Sheets API Key</label>
                                                                                                                    <input type="text" id="setGoogleSheetsKey" class="w-full px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 font-mono text-xs bg-gray-900 text-emerald-300" placeholder="AIzaSy...">
                                                                                                                    <p class="text-[10px] text-gray-500 mt-1.5">Google Cloud Console → APIs → Google Sheets API → Credentials</p>
                                                                                                                </div>
                                                                                                                <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                                                                                                    <label class="block text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                                                                                        <i class="fas fa-table">
                                                                                                                        </i> Google Sheet — Apps Script URL atau Sheet ID</label>
                                                                                                                        <div class="flex gap-2">
                                                                                                                            <input type="text" id="setGoogleSheetId" class="flex-1 px-3 py-2 border border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 font-mono text-xs bg-gray-900 text-emerald-300" placeholder="https://script.google.com/macros/s/.../exec" oninput="parseSheetLink(this)">
                                                                                                                            <button type="button" onclick="parseSheetLink(document.getElementById('setGoogleSheetId'))" class="px-3 py-2 bg-emerald-700 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition flex-shrink-0" title="Extract ID dari link">
                                                                                                                                <i class="fas fa-magic">
                                                                                                                                </i>
                                                                                                                            </button>
                                                                                                                        </div>
                                                                                                                        <p class="text-[10px] text-gray-500 mt-1.5" id="sheetIdPreview">Paste URL Apps Script Web App (direkomendasikan) atau Sheet ID saja</p>
                                                                                                                        <div class="mt-2 bg-yellow-900/40 border border-yellow-700/50 rounded-lg p-2">
                                                                                                                            <p class="text-[10px] text-yellow-400 font-bold">⚠️ PENTING: Google API Key TIDAK BISA menulis ke Sheets. Untuk sync bisa berjalan, wajib gunakan <b>Google Apps Script Web App URL</b>.</p>
                                                                                                                            <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="text-[10px] text-emerald-400 font-bold mt-1 hover:underline">📖 Cara setup Apps Script →</button>
                                                                                                                            <div class="hidden mt-2 text-[10px] text-gray-400 space-y-1 bg-black/30 p-2 rounded-lg font-mono">
                                                                                                                                <p>1. Buka Google Sheets → Extensions → Apps Script</p>
                                                                                                                                <p>2. Hapus kode lama, paste:</p>
                                                                                                                                <pre class="text-emerald-300 text-[9px] mt-1 whitespace-pre-wrap">function doPost(e) {
  var d = JSON.parse(e.postData.contents);
  var ss = SpreadsheetApp.openById(d.sheetId || SpreadsheetApp.getActiveSpreadsheet().getId());
  var sheet = ss.getSheets()[0];
  sheet.clearContents();
  sheet.getRange(1,1,d.values.length,d.values[0].length).setValues(d.values);
  return ContentService.createTextOutput(JSON.stringify({success:true})).setMimeType(ContentService.MimeType.JSON);
}</pre>
                                                                                                                                <p>3. Klik Deploy → New deployment → Web App</p>
                                                                                                                                <p>4. Execute as: <b>Me</b>, Who has access: <b>Anyone</b>
                                                                                                                            </p>
                                                                                                                            <p>5. Copy URL, paste di field ini</p>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                                                                                        <div class="bg-green-50 p-5 rounded-2xl border border-green-100 relative">
                                                                                                            <span class="absolute -top-3 left-4 bg-green-500 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-sm">Kontak Bantuan</span>
                                                                                                            <p class="text-[10px] text-gray-500 mb-4 mt-1">Nama label dan nomor bisa diedit sesuai kebutuhan sekolah.</p>
                                                                                                            <div class="space-y-4">
                                                                                                                <div class="bg-white p-3 rounded-xl border border-green-100 shadow-sm">
                                                                                                                    <label class="block text-[10px] font-black text-green-700 mb-1 uppercase tracking-widest">Kontak #1 — Nama Label</label>
                                                                                                                    <input type="text" id="setWaEaLabel" class="w-full px-3 py-2 border-2 rounded-xl outline-none focus:ring-2 focus:ring-green-500 font-bold bg-gray-50 text-gray-800 mb-2" placeholder="Contoh: WA E-Aspirasi">
                                                                                                                    <label class="block text-[10px] font-black text-green-700 mb-1 uppercase tracking-widest">Nomor WhatsApp</label>
                                                                                                                    <input type="text" id="setWaEa" class="w-full px-3 py-2 border-2 rounded-xl outline-none focus:ring-2 focus:ring-green-500 font-bold bg-gray-50 text-gray-800" placeholder="628xxx">
                                                                                                                </div>
                                                                                                                <div class="bg-white p-3 rounded-xl border border-green-100 shadow-sm">
                                                                                                                    <label class="block text-[10px] font-black text-green-700 mb-1 uppercase tracking-widest">Kontak #2 — Nama Label</label>
                                                                                                                    <input type="text" id="setWaGuruLabel" class="w-full px-3 py-2 border-2 rounded-xl outline-none focus:ring-2 focus:ring-green-500 font-bold bg-gray-50 text-gray-800 mb-2" placeholder="Contoh: WA Admin/Guru">
                                                                                                                    <label class="block text-[10px] font-black text-green-700 mb-1 uppercase tracking-widest">Nomor WhatsApp</label>
                                                                                                                    <input type="text" id="setWaGuru" class="w-full px-3 py-2 border-2 rounded-xl outline-none focus:ring-2 focus:ring-green-500 font-bold bg-gray-50 text-gray-800" placeholder="628xxx">
                                                                                                                </div>
                                                                                                                <div class="bg-white p-3 rounded-xl border border-green-100 shadow-sm">
                                                                                                                    <label class="block text-[10px] font-black text-green-700 mb-1 uppercase tracking-widest">Kontak #3 — Nama Label</label>
                                                                                                                    <input type="text" id="setWaDevLabel" class="w-full px-3 py-2 border-2 rounded-xl outline-none focus:ring-2 focus:ring-green-500 font-bold bg-gray-50 text-gray-800 mb-2" placeholder="Contoh: WA Sastro / IT Dev">
                                                                                                                    <label class="block text-[10px] font-black text-green-700 mb-1 uppercase tracking-widest">Nomor WhatsApp</label>
                                                                                                                    <input type="text" id="setWaDev" class="w-full px-3 py-2 border-2 rounded-xl outline-none focus:ring-2 focus:ring-green-500 font-bold bg-gray-50 text-gray-800" placeholder="628xxx">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <!-- SECTION FITUR OTOMATIS -->
                                                                                                    <div class="bg-blue-50 p-5 rounded-2xl border border-blue-100 relative mt-6">
                                                                                                        <span class="absolute -top-3 left-4 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-sm">⚡ Fitur Otomatis</span>
                                                                                                        <p class="text-xs text-gray-500 mb-4 mt-1">Jalankan secara manual atau atur jadwal otomatis.</p>
                                                                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                                                                            <div class="bg-white p-4 rounded-xl border border-blue-100">
                                                                                                                <h4 class="text-sm font-black text-gray-800 mb-1">⏰ Reminder Laporan Pending</h4>
                                                                                                                <p class="text-[11px] text-gray-500 mb-3">Kirim WA ke admin untuk laporan yang belum ditangani lebih dari 3 hari.</p>
                                                                                                                <button type="button" onclick="jalankanReminder()" id="btnReminder" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-black py-2.5 rounded-xl text-xs transition active:scale-95 flex items-center justify-center gap-2">
                                                                                                                    <i class="fas fa-bell">
                                                                                                                    </i> Kirim Reminder Sekarang
                                        </button>
                                                                                                                </div>
                                                                                                                <div class="bg-white p-4 rounded-xl border border-blue-100">
                                                                                                                    <h4 class="text-sm font-black text-gray-800 mb-1">📊 Laporan Berkala</h4>
                                                                                                                    <p class="text-[11px] text-gray-500 mb-3">Kirim ringkasan laporan bulan ini ke WA &amp; Telegram admin.</p>
                                                                                                                    <button type="button" onclick="jalankanLaporanBerkala()" id="btnBerkala" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-black py-2.5 rounded-xl text-xs transition active:scale-95 flex items-center justify-center gap-2">
                                                                                                                        <i class="fas fa-chart-bar">
                                                                                                                        </i> Kirim Ringkasan Sekarang
                                        </button>
                                                                                                                    </div>
                                                                                                                    <div class="bg-white p-4 rounded-xl border border-blue-100">
                                                                                                                        <h4 class="text-sm font-black text-gray-800 mb-1">🗄️ Laporan Backup Data</h4>
                                                                                                                        <p class="text-[11px] text-gray-500 mb-3">Kirim ringkasan jumlah data ke WA &amp; Telegram admin sebagai info backup.</p>
                                                                                                                        <button type="button" onclick="jalankanBackup()" id="btnBackup" class="w-full bg-gray-700 hover:bg-gray-800 text-white font-black py-2.5 rounded-xl text-xs transition active:scale-95 flex items-center justify-center gap-2">
                                                                                                                            <i class="fas fa-database">
                                                                                                                            </i> Kirim Info Backup Sekarang
                                        </button>
                                                                                                                        </div>
                                                                                                                        <div class="bg-white p-4 rounded-xl border border-blue-100">
                                                                                                                            <h4 class="text-sm font-black text-gray-800 mb-1">📊 Sync Google Sheets</h4>
                                                                                                                            <p class="text-[11px] text-gray-500 mb-3">Sinkronkan data laporan terbaru ke Google Sheets.</p>
                                                                                                                            <button type="button" onclick="jalankanSyncSheets()" id="btnSyncSheets" class="w-full bg-green-700 hover:bg-green-800 text-white font-black py-2.5 rounded-xl text-xs transition active:scale-95 flex items-center justify-center gap-2">
                                                                                                                                <i class="fas fa-table">
                                                                                                                                </i> Sync ke Google Sheets
                                        </button>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <!-- SECTION TEMPLATE WA -->
                                                                                                                    <div class="bg-green-50 p-5 rounded-2xl border border-green-100 relative mt-6">
                                                                                                                        <span class="absolute -top-3 left-4 bg-green-600 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-sm">📱 Template Pesan WA</span>
                                                                                                                        <p class="text-xs text-gray-500 mb-4 mt-1">Kustomisasi pesan WA yang dikirim ke siswa saat status laporan diperbarui. Gunakan: <code class="bg-white px-1 rounded text-blue-600 font-mono text-[10px]">{nama}</code>
                                                                                                                        <code class="bg-white px-1 rounded text-blue-600 font-mono text-[10px]">{tipe}</code>
                                                                                                                        <code class="bg-white px-1 rounded text-blue-600 font-mono text-[10px]">{kategori}</code>
                                                                                                                        <code class="bg-white px-1 rounded text-blue-600 font-mono text-[10px]">{status}</code>
                                                                                                                        <code class="bg-white px-1 rounded text-blue-600 font-mono text-[10px]">{deskripsi}</code>
                                                                                                                    </p>
                                                                                                                    <div class="grid grid-cols-1 gap-4">
                                                                                                                        <div class="bg-white p-4 rounded-xl border border-green-100">
                                                                                                                            <label class="block text-xs font-black text-gray-700 uppercase tracking-widest mb-1.5">Template Pesan Utama</label>
                                                                                                                            <textarea id="setWaTemplate" rows="6" class="w-full px-3 py-2 border-2 border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-green-500 font-mono text-xs bg-gray-50 text-gray-800 resize-none" placeholder="Halo Kak {nama}...">
                                                                                                                            </textarea>
                                                                                                                            <p class="text-[10px] text-gray-400 mt-1">Kosongkan untuk menggunakan template default sistem.</p>
                                                                                                                        </div>
                                                                                                                        <div class="bg-white p-4 rounded-xl border border-green-100">
                                                                                                                            <label class="block text-xs font-black text-gray-700 uppercase tracking-widest mb-1.5">Tanda Tangan / Footer Pesan</label>
                                                                                                                            <input type="text" id="setWaFooter" class="w-full px-3 py-2 border-2 border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-green-500 font-mono text-xs bg-gray-50 text-gray-800" placeholder="E-Aspirasi Grisawangi — SMK PGRI 1 Giri">
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="pt-4 flex justify-end">
                                                                                                                    <button type="button" onclick="simpanPengaturan()" id="btnSaveSetting" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-10 rounded-xl shadow-xl transition active:scale-95 uppercase tracking-widest text-xs flex justify-center items-center gap-2">
                                                                                                                        <i class="fas fa-save text-lg">
                                                                                                                        </i> Simpan Konfigurasi</button>
                                                                                                                    </div>
                                                                                                                </form>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <!-- VIEW: PENGATURAN TAMPILAN WEB -->
                                                                                                        <div id="view-tampilan_web" class="page-view hidden fade-in w-full space-y-6">
                                                                                                            <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border-t-4 border-indigo-600">
                                                                                                                <h2 class="text-xl font-black text-gray-800 flex items-center gap-2 tracking-tight">
                                                                                                                    <i class="fas fa-palette text-indigo-600">
                                                                                                                    </i> Pengaturan Tampilan Web</h2>
                                                                                                                    <p class="text-xs text-gray-500 mt-1">Ubah nama, logo, dan video intro website siswa. Setiap form disimpan sendiri-sendiri. Kosongkan isian lalu simpan untuk kembali ke bawaan.</p>
                                                                                                                </div>
                                                                                                                <!-- FORM 1: NAMA WEB -->
                                                                                                                <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-gray-100">
                                                                                                                    <h4 class="font-black text-gray-800 text-sm mb-1 flex items-center gap-2">
                                                                                                                        <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-black">1</span> Ubah Nama Web</h4>
                                                                                                                        <p class="text-xs text-gray-500 mb-4">Tampil di judul tab browser, halaman login siswa, dan popup selamat datang.</p>
                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nama Web</label>
                                                                                                                        <input type="text" id="twNamaWeb" maxlength="60" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800" placeholder="Contoh: E-ASPIRASI GRISAWANGI">
                                                                                                                        <div class="mt-4 flex justify-end">
                                                                                                                            <button type="button" onclick="simpanTampilanWeb('nama_web')" id="btnTwNama" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-3 px-8 rounded-xl shadow-md transition active:scale-95 text-xs uppercase tracking-widest flex items-center gap-2">
                                                                                                                                <i class="fas fa-save">
                                                                                                                                </i> Simpan Nama</button>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <!-- FORM 2: LOGO -->
                                                                                                                        <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-gray-100">
                                                                                                                            <h4 class="font-black text-gray-800 text-sm mb-1 flex items-center gap-2">
                                                                                                                                <span class="w-7 h-7 rounded-lg bg-green-100 text-green-600 flex items-center justify-center text-xs font-black">2</span> Ubah Logo</h4>
                                                                                                                                <p class="text-xs text-gray-500 mb-4">Pakai link <b>direct</b> gambar (berakhiran .png / .jpg / .webp). Logo dipakai di halaman siswa, favicon, dan sidebar admin.</p>
                                                                                                                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Link Direct Logo</label>
                                                                                                                                <input type="url" id="twLinkLogo" oninput="twPreviewLogo()" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none font-medium text-gray-800 text-sm" placeholder="https://contoh.com/logo.png">
                                                                                                                                <div class="mt-4 flex items-center gap-4">
                                                                                                                                    <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center overflow-hidden flex-shrink-0">
                                                                                                                                        <img id="twLogoPreview" src="{{ asset('icon.png') }}" alt="Preview logo" class="max-w-full max-h-full object-contain" onerror="this.onerror=null; this.src='https://iili.io/qfChwml.png';">
                                                                                                                                    </div>
                                                                                                                                    <p id="twLogoInfo" class="text-xs text-gray-400">Preview logo muncul di sini.</p>
                                                                                                                                </div>
                                                                                                                                <div class="mt-4 flex justify-end">
                                                                                                                                    <button type="button" onclick="simpanTampilanWeb('link_logo')" id="btnTwLogo" class="bg-green-600 hover:bg-green-700 text-white font-black py-3 px-8 rounded-xl shadow-md transition active:scale-95 text-xs uppercase tracking-widest flex items-center gap-2">
                                                                                                                                        <i class="fas fa-save">
                                                                                                                                        </i> Simpan Logo</button>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <!-- FORM 3: INTRO VIDEO -->
                                                                                                                                <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-gray-100">
                                                                                                                                    <h4 class="font-black text-gray-800 text-sm mb-1 flex items-center gap-2">
                                                                                                                                        <span class="w-7 h-7 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-xs font-black">3</span> Ubah Intro Video</h4>
                                                                                                                                        <p class="text-xs text-gray-500 mb-4">Pakai link <b>direct</b> video (berakhiran .mp4). Video diputar saat siswa pertama kali membuka web.</p>
                                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Link Direct Intro Video</label>
                                                                                                                                        <input type="url" id="twLinkIntro" oninput="twPreviewIntro()" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-medium text-gray-800 text-sm" placeholder="https://contoh.com/intro.mp4">
                                                                                                                                        <div id="twIntroWrap" class="mt-4 hidden">
                                                                                                                                            <video id="twIntroPreview" controls="" muted="" playsinline="" preload="metadata" class="w-full max-w-sm rounded-2xl border border-gray-200 bg-black">
                                                                                                                                            </video>
                                                                                                                                        </div>
                                                                                                                                        <div class="mt-4 flex justify-end">
                                                                                                                                            <button type="button" onclick="simpanTampilanWeb('link_intro')" id="btnTwIntro" class="bg-orange-500 hover:bg-orange-600 text-white font-black py-3 px-8 rounded-xl shadow-md transition active:scale-95 text-xs uppercase tracking-widest flex items-center gap-2">
                                                                                                                                                <i class="fas fa-save">
                                                                                                                                                </i> Simpan Intro</button>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <!-- VIEW: MENU SISWA TAMBAHAN -->
                                                                                                                                    <div id="view-menu_siswa" class="page-view hidden fade-in w-full space-y-6">
                                                                                                                                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between gap-4">
                                                                                                                                            <div>
                                                                                                                                                <h4 class="font-black text-gray-800 text-sm">Aktifkan grup "Menu Siswa"</h4>
                                                                                                                                                <p class="text-xs text-gray-500 mt-0.5">Jika OFF, grup menu ini tidak muncul sama sekali di sidebar siswa.</p>
                                                                                                                                            </div>
                                                                                                                                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                                                                                                                                <input type="checkbox" id="msAktif" class="sr-only peer" onchange="msToggleAktif(this)">
                                                                                                                                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner">
                                                                                                                                                </div>
                                                                                                                                            </label>
                                                                                                                                        </div>
                                                                                                                                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                                                                                                                            <h4 class="font-black text-gray-800 text-sm mb-4 flex items-center gap-2">
                                                                                                                                                <i class="fas fa-plus-circle text-indigo-500">
                                                                                                                                                </i>
                                                                                                                                                <span id="msFormJudul">Tambah Menu Baru</span>
                                                                                                                                            </h4>
                                                                                                                                            <div class="space-y-4">
                                                                                                                                                <div>
                                                                                                                                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nama Menu <span class="text-red-500">*</span>
                                                                                                                                                </label>
                                                                                                                                                <input type="text" id="msNama" maxlength="40" placeholder="Contoh: Materi BK" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold text-gray-800">
                                                                                                                                            </div>
                                                                                                                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                                                                                                                <div>
                                                                                                                                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Icon</label>
                                                                                                                                                    <select id="msIconTipe" onchange="msGantiIcon()" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold text-gray-800 cursor-pointer">
                                                                                                                                                        <option value="buku">📖 Buku</option>
                                                                                                                                                        <option value="konsol">🎮 Konsol</option>
                                                                                                                                                        <option value="kalender">📅 Kalender</option>
                                                                                                                                                        <option value="link">🔗 Link gambar sendiri</option>
                                                                                                                                                    </select>
                                                                                                                                                </div>
                                                                                                                                                <div id="msIconUrlWrap" class="hidden">
                                                                                                                                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Link Gambar Icon <span class="text-red-500">*</span>
                                                                                                                                                </label>
                                                                                                                                                <input type="url" id="msIconUrl" placeholder="https://.../icon.png" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-medium text-sm text-gray-800">
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        <div>
                                                                                                                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Link Tujuan <span class="text-red-500">*</span>
                                                                                                                                        </label>
                                                                                                                                        <input type="url" id="msLink" placeholder="https://portal-belajarbk.netlify.app" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-medium text-sm text-gray-800">
                                                                                                                                        <p class="text-[10px] text-gray-400 mt-1">Siswa yang mengklik menu akan dibuka ke link ini. Harus diawali http:// atau https://</p>
                                                                                                                                    </div>
                                                                                                                                    <div class="flex gap-2">
                                                                                                                                        <button onclick="msSubmit()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-8 rounded-xl shadow-md transition active:scale-95 text-sm flex items-center gap-2">
                                                                                                                                            <i class="fas fa-save">
                                                                                                                                            </i>
                                                                                                                                            <span id="msBtnLabel">Tambah Menu</span>
                                                                                                                                        </button>
                                                                                                                                        <button id="msBtnBatal" onclick="msResetForm()" class="hidden bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-xl transition text-sm">Batal Edit</button>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                                                                                                                <h4 class="font-black text-gray-800 text-sm mb-4 flex items-center gap-2">
                                                                                                                                    <i class="fas fa-list text-gray-500">
                                                                                                                                    </i> Daftar Menu</h4>
                                                                                                                                    <div id="msList" class="space-y-2">
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <!-- VIEW: STATISTIK PENILAIAN -->
                                                                                                                            <div id="view-penilaian_admin" class="page-view hidden fade-in w-full space-y-6">
                                                                                                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                                                                                                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-yellow-400 text-center">
                                                                                                                                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Rata-rata Bintang</p>
                                                                                                                                        <h3 class="text-3xl font-black text-yellow-500" id="statAvgBintang">-</h3>
                                                                                                                                        <p class="text-[10px] text-gray-400 mt-1">dari 5 bintang</p>
                                                                                                                                    </div>
                                                                                                                                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500 text-center">
                                                                                                                                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Penilaian</p>
                                                                                                                                        <h3 class="text-3xl font-black text-gray-800" id="statTotalPenilaian">-</h3>
                                                                                                                                    </div>
                                                                                                                                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-green-500 text-center">
                                                                                                                                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Bintang 4-5</p>
                                                                                                                                        <h3 class="text-3xl font-black text-green-600" id="statPositif">-</h3>
                                                                                                                                    </div>
                                                                                                                                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-red-500 text-center">
                                                                                                                                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Bintang 1-2</p>
                                                                                                                                        <h3 class="text-3xl font-black text-red-500" id="statNegatif">-</h3>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                                                                                                                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                                                                                                                        <h4 class="font-black text-gray-800 text-sm mb-4 flex items-center gap-2">
                                                                                                                                            <i class="fas fa-chart-bar text-yellow-500">
                                                                                                                                            </i> Distribusi Bintang</h4>
                                                                                                                                            <canvas id="chartPenilaianDist" height="200">
                                                                                                                                            </canvas>
                                                                                                                                        </div>
                                                                                                                                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                                                                                                                            <h4 class="font-black text-gray-800 text-sm mb-4 flex items-center gap-2">
                                                                                                                                                <i class="fas fa-comment-alt text-blue-500">
                                                                                                                                                </i> Komentar Terbaru</h4>
                                                                                                                                                <div id="listKomentar" class="space-y-3 max-h-64 overflow-y-auto custom-dropdown">
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                                                                                                                            <div class="flex justify-between items-center mb-4">
                                                                                                                                                <h4 class="font-black text-gray-800 text-sm flex items-center gap-2">
                                                                                                                                                    <i class="fas fa-list text-gray-500">
                                                                                                                                                    </i> Semua Penilaian</h4>
                                                                                                                                                    <button onclick="exportPenilaianExcel()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2">
                                                                                                                                                        <i class="fas fa-file-excel">
                                                                                                                                                        </i> Ekspor Excel</button>
                                                                                                                                                    </div>
                                                                                                                                                    <div class="overflow-x-auto">
                                                                                                                                                        <table class="w-full text-sm text-left">
                                                                                                                                                            <thead class="bg-gray-50 text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                                                                                                                                                <tr>
                                                                                                                                                                    <th class="px-4 py-3">Waktu</th>
                                                                                                                                                                    <th class="px-4 py-3">Nama</th>
                                                                                                                                                                    <th class="px-4 py-3">Kelas</th>
                                                                                                                                                                    <th class="px-4 py-3">Bintang</th>
                                                                                                                                                                    <th class="px-4 py-3">Komentar</th>
                                                                                                                                                                </tr>
                                                                                                                                                            </thead>
                                                                                                                                                            <tbody id="tablePenilaian" class="divide-y divide-gray-100 font-medium text-gray-700">
                                                                                                                                                            </tbody>
                                                                                                                                                        </table>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                            <div id="view-manajemen" class="page-view hidden fade-in w-full space-y-6">
                                                                                                                                            </div>
                                                                                                                                            <!-- VIEW: PENGUMUMAN -->
                                                                                                                                            <div id="view-pengumuman" class="page-view hidden fade-in w-full space-y-6">
                                                                                                                                                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                                                                                                                                    <div class="flex justify-between items-center mb-4">
                                                                                                                                                        <h4 class="font-black text-gray-800 text-sm flex items-center gap-2">
                                                                                                                                                            <i class="fas fa-plus text-blue-500">
                                                                                                                                                            </i> Buat Pengumuman Baru</h4>
                                                                                                                                                        </div>
                                                                                                                                                        <div class="space-y-4">
                                                                                                                                                            <div>
                                                                                                                                                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Judul Pengumuman <span class="text-red-500">*</span>
                                                                                                                                                            </label>
                                                                                                                                                            <input type="text" id="inputJudulPengumuman" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800" placeholder="Contoh: Libur Nasional, Jadwal Ujian...">
                                                                                                                                                        </div>
                                                                                                                                                        <div>
                                                                                                                                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Isi Pengumuman <span class="text-red-500">*</span>
                                                                                                                                                        </label>
                                                                                                                                                        <textarea id="inputIsiPengumuman" rows="4" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-medium text-gray-800 resize-none" placeholder="Tulis isi pengumuman di sini...">
                                                                                                                                                        </textarea>
                                                                                                                                                    </div>
                                                                                                                                                    <div class="grid grid-cols-2 gap-4">
                                                                                                                                                        <div>
                                                                                                                                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Tipe</label>
                                                                                                                                                            <select id="inputTipePengumuman" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold cursor-pointer">
                                                                                                                                                                <option value="info">ℹ️ Info</option>
                                                                                                                                                                <option value="penting">⚠️ Penting</option>
                                                                                                                                                                <option value="darurat">🚨 Darurat</option>
                                                                                                                                                            </select>
                                                                                                                                                        </div>
                                                                                                                                                        <div>
                                                                                                                                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Kirim Notif WA</label>
                                                                                                                                                            <select id="inputKirimWA" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold cursor-pointer">
                                                                                                                                                                <option value="tidak">Tidak</option>
                                                                                                                                                                <option value="ya">Ya — ke Admin/Guru</option>
                                                                                                                                                            </select>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                    <button onclick="simpanPengumuman()" id="btnSimpanPengumuman" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-3 px-8 rounded-xl shadow-md transition active:scale-95 text-sm flex items-center gap-2">
                                                                                                                                                        <i class="fas fa-paper-plane">
                                                                                                                                                        </i> Publikasikan Pengumuman
                            </button>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                                                                                                                                    <h4 class="font-black text-gray-800 text-sm mb-4 flex items-center gap-2">
                                                                                                                                                        <i class="fas fa-list text-gray-500">
                                                                                                                                                        </i> Riwayat Pengumuman</h4>
                                                                                                                                                        <div id="listPengumuman" class="space-y-3">
                                                                                                                                                            <p class="text-gray-400 text-sm text-center py-8">Memuat...</p>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                        </main>
                                                                                                                                    </div>
                                                                                                                                    <!-- Modal Profil -->
                                                                                                                                    <div id="modalProfil" class="fixed inset-0 bg-gray-900/80 z-[100] flex items-center justify-center p-4 hidden fade-in backdrop-blur-sm overflow-y-auto">
                                                                                                                                        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden relative border-t-8 border-blue-600 my-auto">
                                                                                                                                            <button onclick="tutupProfil()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center transition">
                                                                                                                                                <i class="fas fa-times">
                                                                                                                                                </i>
                                                                                                                                            </button>
                                                                                                                                            <div class="p-6 md:p-8">
                                                                                                                                                <div class="flex items-center gap-4 mb-6 border-b border-gray-100 pb-5">
                                                                                                                                                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-3xl shadow-inner border border-blue-100 flex-shrink-0">
                                                                                                                                                        <i class="fas fa-user-tie">
                                                                                                                                                        </i>
                                                                                                                                                    </div>
                                                                                                                                                    <div>
                                                                                                                                                        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Profil Anda</h2>
                                                                                                                                                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mt-1 bg-blue-50 px-2 py-0.5 rounded border border-blue-100 inline-block" id="profRoleBadge">Role Admin</p>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <div class="space-y-4">
                                                                                                                                                    <div>
                                                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Email Login</label>
                                                                                                                                                        <input type="email" id="profEmail" disabled="" class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl font-bold text-gray-500 cursor-not-allowed">
                                                                                                                                                    </div>
                                                                                                                                                    <div>
                                                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Nama Lengkap</label>
                                                                                                                                                        <input type="text" id="profNama" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 bg-gray-50">
                                                                                                                                                    </div>
                                                                                                                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                                                                                                                        <div>
                                                                                                                                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">No. WhatsApp</label>
                                                                                                                                                            <input type="tel" id="profTelp" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 bg-gray-50">
                                                                                                                                                        </div>
                                                                                                                                                        <div>
                                                                                                                                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Agama</label>
                                                                                                                                                            <select id="profAgama" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 cursor-pointer bg-gray-50">
                                                                                                                                                                <option value="Islam">Islam</option>
                                                                                                                                                                <option value="Kristen">Kristen</option>
                                                                                                                                                                <option value="Katolik">Katolik</option>
                                                                                                                                                                <option value="Hindu">Hindu</option>
                                                                                                                                                                <option value="Buddha">Buddha</option>
                                                                                                                                                                <option value="Konghucu">Konghucu</option>
                                                                                                                                                            </select>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                    <div class="pt-5 mt-2 border-t border-gray-100 space-y-3">
                                                                                                                                                        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4">
                                                                                                                                                            <label class="block text-xs font-bold text-indigo-700 uppercase tracking-widest mb-1">
                                                                                                                                                                <i class="fas fa-hourglass-half mr-1">
                                                                                                                                                                </i> Timeout Sesi (menit)</label>
                                                                                                                                                                <input type="number" id="profTimeout" min="1" max="1440" class="w-full px-4 py-2.5 border-2 border-indigo-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold text-gray-800 bg-white">
                                                                                                                                                                <p class="text-[10px] text-gray-500 mt-1">Keluar otomatis jika tidak ada aktivitas selama X menit (1–1440). Berlaku untuk email ini saja.</p>
                                                                                                                                                            </div>
                                                                                                                                                            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex items-center justify-between gap-3">
                                                                                                                                                                <div>
                                                                                                                                                                    <p class="text-xs font-black text-gray-700">
                                                                                                                                                                        <i class="fas fa-lock mr-1">
                                                                                                                                                                        </i> PIN Kunci Layar</p>
                                                                                                                                                                        <p class="text-[10px] text-gray-500">PIN 4 digit untuk membuka layar terkunci</p>
                                                                                                                                                                    </div>
                                                                                                                                                                    <button type="button" onclick="aturPinKunci()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-xl text-xs font-black transition whitespace-nowrap">Atur PIN</button>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                            <div class="pt-2">
                                                                                                                                                                <button id="btnTogglePasswordEdit" onclick="togglePasswordEditForm()" class="text-xs font-black text-red-600 hover:text-white bg-red-50 hover:bg-red-600 px-4 py-2 rounded-xl transition flex items-center gap-2 border border-red-200 hover:border-red-600">
                                                                                                                                                                    <i class="fas fa-key">
                                                                                                                                                                    </i> Ubah Kata Sandi</button>
                                                                                                                                                                </div>
                                                                                                                                                                <div id="passwordEditContainer" class="hidden bg-gray-50 p-5 rounded-2xl border border-gray-200 space-y-4 fade-in mt-2">
                                                                                                                                                                    <div class="relative">
                                                                                                                                                                        <input type="password" id="profPass" class="w-full pl-4 pr-10 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold bg-white text-gray-800" placeholder="Ketik Sandi Baru...">
                                                                                                                                                                        <i class="fas fa-eye absolute right-4 top-3.5 text-gray-400 cursor-pointer hover:text-blue-500 transition" onclick="togglePassword('profPass', this)">
                                                                                                                                                                        </i>
                                                                                                                                                                    </div>
                                                                                                                                                                    <div class="relative">
                                                                                                                                                                        <input type="password" id="profPassConfirm" class="w-full pl-4 pr-10 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold bg-white text-gray-800" placeholder="Ulangi Sandi Baru...">
                                                                                                                                                                        <i class="fas fa-eye absolute right-4 top-3.5 text-gray-400 cursor-pointer hover:text-blue-500 transition" onclick="togglePassword('profPassConfirm', this)">
                                                                                                                                                                        </i>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                            <div class="mt-8 flex justify-end pt-5 border-t border-gray-100">
                                                                                                                                                                <button onclick="simpanProfilAdmin()" id="btnSaveProfil" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-lg hover:shadow-xl transition active:scale-95 uppercase tracking-widest text-sm flex justify-center items-center gap-2">
                                                                                                                                                                    <i class="fas fa-save text-lg">
                                                                                                                                                                    </i> Simpan Profil</button>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                    <!-- Modal Import Excel Global -->
                                                                                                                                                    <div id="modalImport" class="fixed inset-0 bg-black/60 z-[100] flex items-center justify-center p-4 hidden fade-in backdrop-blur-sm overflow-y-auto">
                                                                                                                                                        <div class="bg-white rounded-3xl w-full max-w-md p-6 md:p-8 shadow-2xl relative border-t-8 border-green-500 my-auto">
                                                                                                                                                            <button onclick="document.getElementById('modalImport').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center transition">
                                                                                                                                                                <i class="fas fa-times">
                                                                                                                                                                </i>
                                                                                                                                                            </button>
                                                                                                                                                            <h3 class="text-2xl font-black mb-1 text-gray-800 tracking-tight" id="importTitle">Import Excel</h3>
                                                                                                                                                            <p class="text-xs text-gray-500 mb-6 font-medium leading-relaxed" id="importDesc">Pilih file .xlsx untuk upload massal.</p>
                                                                                                                                                            <input type="hidden" id="importTypeVal">
                                                                                                                                                            <div class="mb-5 bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-center justify-between">
                                                                                                                                                                <div>
                                                                                                                                                                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-0.5">Belum Punya Format?</p>
                                                                                                                                                                    <p class="text-xs font-bold text-gray-700">Unduh format baku Excel.</p>
                                                                                                                                                                </div>
                                                                                                                                                                <button type="button" onclick="downloadTemplate()" class="bg-white text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1">
                                                                                                                                                                    <i class="fas fa-download">
                                                                                                                                                                    </i> Template</button>
                                                                                                                                                                </div>
                                                                                                                                                                <div class="border-2 border-dashed border-green-300 rounded-2xl p-8 text-center hover:bg-green-50 transition cursor-pointer relative mb-4 bg-gray-50 group">
                                                                                                                                                                    <input type="file" id="fileImportExcel" accept=".xlsx, .xls" onchange="document.getElementById('importFileName').textContent = this.files[0] ? this.files[0].name : 'Belum ada file';" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                                                                                                                                                    <i class="fas fa-file-excel text-5xl text-gray-300 mb-3 group-hover:text-green-500 transition-colors">
                                                                                                                                                                    </i>
                                                                                                                                                                    <p class="text-sm font-black text-gray-700">Klik / Seret file Excel</p>
                                                                                                                                                                    <p class="text-[10px] text-gray-500 mt-2 bg-white inline-block px-3 py-1 rounded-full border shadow-sm" id="importFileName">Belum ada file</p>
                                                                                                                                                                </div>
                                                                                                                                                                <div class="mt-6 pt-4 border-t border-gray-100">
                                                                                                                                                                    <button onclick="prosesImportExcel()" id="btnProsesImport" class="w-full bg-green-600 hover:bg-green-700 text-white font-black py-4 rounded-xl shadow-lg transition active:scale-95 uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                                                                                                                                                                        <i class="fas fa-cloud-upload-alt text-lg">
                                                                                                                                                                        </i> Upload Database</button>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                            <!-- Modal Tambah/Edit Data Dinamis -->
                                                                                                                                                            <div id="modalDataForm" class="fixed inset-0 bg-black/60 z-[100] flex items-center justify-center p-4 hidden fade-in backdrop-blur-sm overflow-y-auto">
                                                                                                                                                                <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden relative border-t-8 border-indigo-600 my-auto">
                                                                                                                                                                    <div class="p-6 md:p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                                                                                                                                                        <div>
                                                                                                                                                                            <h3 class="font-black text-gray-800 text-2xl tracking-tight" id="modalDataTitle">Form Data</h3>
                                                                                                                                                                            <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest mt-1" id="modalDataSubtitle">Tambah/Edit Rekord</p>
                                                                                                                                                                        </div>
                                                                                                                                                                        <button onclick="document.getElementById('modalDataForm').classList.add('hidden')" class="text-gray-400 hover:text-red-500 bg-white rounded-full w-8 h-8 flex items-center justify-center shadow-sm border transition">
                                                                                                                                                                            <i class="fas fa-times">
                                                                                                                                                                            </i>
                                                                                                                                                                        </button>
                                                                                                                                                                    </div>
                                                                                                                                                                    <div class="p-6 md:p-8">
                                                                                                                                                                        <form id="genericDataForm" class="space-y-5">
                                                                                                                                                                            <input type="hidden" id="formActionType">
                                                                                                                                                                            <input type="hidden" id="formTableTarget">
                                                                                                                                                                            <input type="hidden" id="formRecordId">
                                                                                                                                                                            <input type="hidden" id="tambahType">
                                                                                                                                                                            <div id="dynamicFormFields" class="space-y-4">
                                                                                                                                                                            </div>
                                                                                                                                                                            <div class="pt-5 mt-4 border-t border-gray-100">
                                                                                                                                                                                <button type="submit" id="btnSubmitDataForm" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-xl shadow-lg transition active:scale-95 uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                                                                                                                                                                                    <i class="fas fa-save text-lg">
                                                                                                                                                                                    </i> Simpan Data</button>
                                                                                                                                                                                </div>
                                                                                                                                                                            </form>
                                                                                                                                                                        </div>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                                <!-- Modal Detail Absensi Harian -->
                                                                                                                                                                <div id="modalAbsensiDetail" class="fixed inset-0 bg-black/70 z-[100] flex items-center justify-center p-4 hidden fade-in backdrop-blur-md overflow-y-auto">
                                                                                                                                                                    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden relative border-t-8 border-blue-500 my-auto flex flex-col max-h-[90vh]">
                                                                                                                                                                        <div class="p-6 border-b bg-gray-50 flex justify-between items-center flex-shrink-0">
                                                                                                                                                                            <div>
                                                                                                                                                                                <h3 class="font-black text-gray-800 text-xl tracking-tight">Riwayat Absensi</h3>
                                                                                                                                                                                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-1" id="absensiDetailNama">Nama Siswa</p>
                                                                                                                                                                            </div>
                                                                                                                                                                            <button onclick="document.getElementById('modalAbsensiDetail').classList.add('hidden')" class="text-gray-400 hover:text-red-500 bg-white rounded-full w-8 h-8 flex items-center justify-center shadow-sm border transition">
                                                                                                                                                                                <i class="fas fa-times">
                                                                                                                                                                                </i>
                                                                                                                                                                            </button>
                                                                                                                                                                        </div>
                                                                                                                                                                        <div class="p-0 overflow-y-auto flex-1 bg-gray-50" id="absensiDetailContent">
                                                                                                                                                                        </div>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                                <!-- Modal Detail Laporan Guru -->
                                                                                                                                                                <div id="modalDetailLaporanGuru" class="fixed inset-0 bg-black/70 z-[200] flex items-start justify-center p-4 hidden backdrop-blur-sm overflow-y-auto">
                                                                                                                                                                    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl my-8 border-t-8 border-red-500">
                                                                                                                                                                        <div class="p-5 border-b bg-gray-50 flex justify-between items-center rounded-t-3xl">
                                                                                                                                                                            <h3 class="font-black text-gray-800 text-xl flex items-center gap-2">
                                                                                                                                                                                <i class="fas fa-flag text-red-500">
                                                                                                                                                                                </i> Detail Laporan Guru</h3>
                                                                                                                                                                                <button onclick="document.getElementById('modalDetailLaporanGuru').classList.add('hidden')" class="text-gray-400 hover:text-red-500 bg-white rounded-full w-9 h-9 flex items-center justify-center shadow-sm border transition">
                                                                                                                                                                                    <i class="fas fa-times">
                                                                                                                                                                                    </i>
                                                                                                                                                                                </button>
                                                                                                                                                                            </div>
                                                                                                                                                                            <div id="contentDetailLaporanGuru" class="p-6 space-y-4 text-sm">
                                                                                                                                                                            </div>
                                                                                                                                                                        </div>
                                                                                                                                                                    </div>
                                                                                                                                                                    <!-- Modal Detail Aspirasi -->
                                                                                                                                                                    <div id="modalAspirasi" class="fixed inset-0 bg-black/70 z-[200] flex items-start justify-center p-4 hidden fade-in backdrop-blur-md overflow-y-auto">
                                                                                                                                                                        <div class="bg-white rounded-3xl w-full max-w-2xl flex flex-col shadow-2xl relative my-8 border-t-8 border-blue-600">
                                                                                                                                                                            <!-- Header -->
                                                                                                                                                                            <div class="p-6 border-b bg-gray-50 flex justify-between items-center flex-shrink-0 rounded-t-3xl">
                                                                                                                                                                                <h3 class="font-black text-gray-800 text-2xl tracking-tight flex items-center gap-2">
                                                                                                                                                                                    <i class="fas fa-search-plus text-blue-600">
                                                                                                                                                                                    </i> Detail Tiket
                </h3>
                                                                                                                                                                                    <button onclick="document.getElementById('modalAspirasi').classList.add('hidden')" class="text-gray-400 hover:text-red-500 bg-white rounded-full w-9 h-9 flex items-center justify-center shadow-sm border transition">
                                                                                                                                                                                        <i class="fas fa-times">
                                                                                                                                                                                        </i>
                                                                                                                                                                                    </button>
                                                                                                                                                                                </div>
                                                                                                                                                                                <!-- Konten detail (diisi oleh bukaDetailAspirasi) -->
                                                                                                                                                                                <div class="p-6 space-y-5 text-sm bg-white" id="contentAspirasiDetail">
                                                                                                                                                                                </div>
                                                                                                                                                                                <!-- Footer: Balasan + Update Status -->
                                                                                                                                                                                <div class="p-5 border-t bg-gray-50 flex-shrink-0 rounded-b-3xl space-y-4">
                                                                                                                                                                                    <!-- Thread Balasan -->
                                                                                                                                                                                    <div>
                                                                                                                                                                                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 flex items-center gap-1">
                                                                                                                                                                                            <i class="fas fa-comments text-blue-400">
                                                                                                                                                                                            </i> Balasan Admin
                    </p>
                                                                                                                                                                                            <div id="threadBalasan" class="space-y-2 max-h-40 overflow-y-auto mb-3 pr-1">
                                                                                                                                                                                                <p class="text-xs text-gray-400 italic text-center py-2">Belum ada balasan...</p>
                                                                                                                                                                                            </div>
                                                                                                                                                                                            <div class="flex gap-2">
                                                                                                                                                                                                <input type="text" id="inputBalasanAdmin" placeholder="Tulis balasan ke siswa..." class="flex-1 px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none">
                                                                                                                                                                                                <button onclick="kirimBalasanAdmin()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition active:scale-95 flex items-center gap-1.5 flex-shrink-0">
                                                                                                                                                                                                    <i class="fas fa-paper-plane">
                                                                                                                                                                                                    </i> Kirim
                        </button>
                                                                                                                                                                                                </div>
                                                                                                                                                                                            </div>
                                                                                                                                                                                            <!-- Update Status -->
                                                                                                                                                                                            <div class="flex flex-col sm:flex-row items-stretch gap-3 pt-3 border-t border-gray-200">
                                                                                                                                                                                                <div class="flex-1">
                                                                                                                                                                                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Update Status</label>
                                                                                                                                                                                                    <select id="updateStatusAspirasiVal" class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 bg-white cursor-pointer text-sm">
                                                                                                                                                                                                        <option value="Menunggu">⏳ Menunggu</option>
                                                                                                                                                                                                        <option value="Sedang Diproses">🔄 Sedang Diproses</option>
                                                                                                                                                                                                        <option value="Selesai">✅ Selesai</option>
                                                                                                                                                                                                        <option value="Ditolak">❌ Ditolak</option>
                                                                                                                                                                                                    </select>
                                                                                                                                                                                                </div>
                                                                                                                                                                                                <input type="hidden" id="updateIdAspirasiVal">
                                                                                                                                                                                                <button onclick="simpanStatusAspirasi()" id="btnSimpanStatusAsp" class="sm:self-end bg-blue-600 hover:bg-blue-700 text-white font-black py-2.5 px-6 rounded-xl shadow-lg transition active:scale-95 uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                                                                                                                                                                                    <i class="fas fa-save">
                                                                                                                                                                                                    </i> Terapkan Status
                    </button>
                                                                                                                                                                                                </div>
                                                                                                                                                                                            </div>
                                                                                                                                                                                        </div>
                                                                                                                                                                                    </div>
                                                                                                                                                                                    <!-- BULK ACTION BAR (satu-satunya, fixed di bawah layar) -->
                                                                                                                                                                                    <div id="bulkActionBar" class="fixed bottom-0 left-0 right-0 z-[60] hidden">
                                                                                                                                                                                        <div class="bg-gray-900 text-white px-4 py-3 flex items-center justify-between gap-3 shadow-2xl border-t border-gray-700">
                                                                                                                                                                                            <div class="flex items-center gap-3 flex-shrink-0">
                                                                                                                                                                                                <span class="bg-blue-600 text-white font-black text-sm px-3 py-1.5 rounded-xl whitespace-nowrap">
                                                                                                                                                                                                    <span id="bulkCount">0</span> dipilih</span>
                                                                                                                                                                                                    <button onclick="piliSemua()" class="text-gray-300 hover:text-white text-xs font-bold transition hidden md:block">Pilih Semua</button>
                                                                                                                                                                                                    <button onclick="hideBulkBar()" class="text-gray-400 hover:text-white text-xs font-bold transition">✕ Batal</button>
                                                                                                                                                                                                </div>
                                                                                                                                                                                                <div class="flex items-center gap-2 flex-wrap justify-end" id="bulkActionBtns">
                                                                                                                                                                                                </div>
                                                                                                                                                                                            </div>
                                                                                                                                                                                        </div>
                                                                                                                                                                                        <!-- MODAL QR SISWA -->
                                                                                                                                                                                        <div id="modalQRSiswa" class="fixed inset-0 bg-black/70 z-[100] flex items-center justify-center p-4 hidden fade-in backdrop-blur-md overflow-y-auto">
                                                                                                                                                                                            <div class="bg-white rounded-3xl w-full max-w-sm shadow-2xl overflow-hidden relative border-t-8 border-purple-600 my-auto">
                                                                                                                                                                                                <div class="p-5 border-b bg-gray-50 flex justify-between items-center">
                                                                                                                                                                                                    <div>
                                                                                                                                                                                                        <h3 class="font-black text-gray-800 text-lg tracking-tight">QR Code Siswa</h3>
                                                                                                                                                                                                        <p class="text-[10px] font-bold text-purple-600 uppercase tracking-widest mt-0.5" id="qrSiswaSubtitle">—</p>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                    <button onclick="tutupModalQRSiswa()" class="text-gray-400 hover:text-red-500 bg-white rounded-full w-8 h-8 flex items-center justify-center shadow-sm border transition">
                                                                                                                                                                                                        <i class="fas fa-times">
                                                                                                                                                                                                        </i>
                                                                                                                                                                                                    </button>
                                                                                                                                                                                                </div>
                                                                                                                                                                                                <div class="p-6 text-center">
                                                                                                                                                                                                    <!-- Canvas untuk preview + download -->
                                                                                                                                                                                                    <canvas id="qrCardCanvas" class="rounded-2xl shadow-lg border border-gray-100 mx-auto block" style="max-width:100%;">
                                                                                                                                                                                                    </canvas>
                                                                                                                                                                                                    <div class="mt-5 flex gap-3">
                                                                                                                                                                                                        <button onclick="unduhQRSiswaCanvas()" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-black py-3 rounded-xl text-xs transition active:scale-95 flex items-center justify-center gap-2">
                                                                                                                                                                                                            <i class="fas fa-download">
                                                                                                                                                                                                            </i> Unduh PNG
                    </button>
                                                                                                                                                                                                            <button onclick="tutupModalQRSiswa()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl text-xs transition">
                        Tutup
                    </button>
                                                                                                                                                                                                        </div>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                </div>
                                                                                                                                                                                            </div>
                                                                                                                                                                                            <!-- LOCK SCREEN -->
                                                                                                                                                                                            <div id="lockScreen" class="hidden fixed inset-0 bg-gray-900 flex items-center justify-center p-4" style="z-index:1055">
                                                                                                                                                                                                <div class="w-full max-w-sm text-center">
                                                                                                                                                                                                    <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-white/10 shadow-xl">
                                                                                                                                                                                                        <img src="{{ asset('icon.png') }}" onerror="this.onerror=null; this.src='https://iili.io/qfChwml.png';" alt="Logo" class="w-14 h-14 object-contain">
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                    <h2 class="text-white font-black text-2xl tracking-tight">Portal Sedang Dikunci</h2>
                                                                                                                                                                                                    <p class="text-gray-400 text-sm mt-1 mb-6">Silakan masukkan PIN untuk membuka</p>
                                                                                                                                                                                                    <input type="password" id="lockPinInput" maxlength="4" inputmode="numeric" autocomplete="off" placeholder="••••" oninput="this.value=this.value.replace(/[^0-9]/g,'')" onkeydown="if(event.key==='Enter') bukaKunci()" class="w-40 text-center tracking-[0.6em] text-2xl font-black px-4 py-3 rounded-2xl bg-gray-800 text-white border-2 border-gray-700 focus:border-blue-500 outline-none">
                                                                                                                                                                                                    <p id="lockPinError" class="hidden text-red-400 text-xs font-bold mt-3">PIN salah, coba lagi.</p>
                                                                                                                                                                                                    <div class="mt-5">
                                                                                                                                                                                                        <button onclick="bukaKunci()" class="bg-blue-600 hover:bg-blue-700 text-white font-black px-10 py-3 rounded-xl uppercase tracking-widest text-xs transition active:scale-95">
                                                                                                                                                                                                            <i class="fas fa-unlock mr-2">
                                                                                                                                                                                                            </i>Buka</button>
                                                                                                                                                                                                        </div>
                                                                                                                                                                                                        <button onclick="keluarDariLock()" class="mt-6 text-xs text-gray-500 hover:text-gray-300 underline">Keluar (login ulang dengan email &amp; password)</button>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                </div>
                                                                                                                                                                                                <!-- LOGIKA JAVASCRIPT UTAMA -->
    

    






    





@endsection