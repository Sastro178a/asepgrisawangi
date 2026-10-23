@extends('layouts.app')

@section('content')


<!-- ═══════════════════════════════════════════════════════════
     LOGIN SCREEN
═══════════════════════════════════════════════════════════ -->
<div id="loginScreen" class="min-h-screen flex flex-col items-center justify-center p-4 bg-gradient-to-br from-slate-800 to-blue-900">
    <div class="w-full max-w-sm">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-xl">
                <img src="{{ asset('icon.png') }}" class="w-14 h-14 object-contain" onerror="this.style.display='none'; this.parentNode.innerHTML='&lt;i class=\'fas fa-chalkboard-teacher text-white text-3xl\'&gt;&lt;/i&gt;'">
            </div>
            <h1 class="text-white font-black text-2xl">Portal Guru</h1>
            <p class="text-blue-300 text-sm mt-1">E-Aspirasi Grisawangi</p>
        </div>
        <!-- Form Login -->
        <div class="bg-white rounded-3xl shadow-2xl p-6">
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-4">Masuk ke Akun Guru</p>
            <!-- Nama Guru (autocomplete) -->
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Nama Lengkap</label>
                <div class="relative">
                    <input type="text" id="loginNamaGuru" autocomplete="off" placeholder="Ketik nama guru..." class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl font-bold text-gray-800 text-sm outline-none focus:border-blue-500 transition" oninput="cariNamaGuru(this.value)" onkeydown="{{ env('ONKEYDOWN') }}">
                    <ul id="dropGuru" class="absolute w-full bg-white border border-gray-200 rounded-2xl shadow-2xl mt-1 hidden max-h-48 overflow-y-auto z-50 divide-y divide-gray-50">
                    </ul>
                </div>
            </div>
            <!-- Password -->
            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Password</label>
                <div class="relative">
                    <input type="password" id="loginPassGuru" placeholder="Password kamu" class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl font-bold text-gray-800 text-sm outline-none focus:border-blue-500 transition pr-12" onkeydown="if(event.key==='Enter') prosesLoginGuru()">
                    <button onclick="togglePass()" class="absolute right-4 top-3.5 text-gray-400 hover:text-gray-600">
                        <i id="passEye" class="fas fa-eye text-sm">
                        </i>
                    </button>
                </div>
            </div>
            <button onclick="prosesLoginGuru()" id="btnLoginGuru" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-3.5 rounded-2xl transition text-sm shadow-lg active:scale-95">
                <i class="fas fa-sign-in-alt mr-2">
                </i>Masuk Portal Guru
            </button>
            </div>
            <p class="text-center text-blue-300/60 text-xs mt-4">Hubungi admin jika belum memiliki akun</p>
        </div>
    </div>
    <!-- ═══════════════════════════════════════════════════════════
     MAIN APP (setelah login)
═══════════════════════════════════════════════════════════ -->
    <div id="mainApp" class="hidden min-h-screen flex">
        <!-- Sidebar (desktop) -->
        <aside class="hidden lg:flex flex-col w-60 bg-white border-r border-gray-100 shadow-sm fixed h-full z-40">
            <div class="p-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-chalkboard-teacher text-white text-sm">
                        </i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-black text-gray-800 text-sm truncate" id="sideNama">Guru</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Portal Guru</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <button onclick="navigate('dashboard')" class="sidebar-link w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 transition text-sm text-left">
                    <i class="fas fa-home w-4 text-gray-400">
                    </i>Dashboard
            </button>
                    <button onclick="navigate('profil')" class="sidebar-link w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 transition text-sm text-left">
                        <i class="fas fa-user-edit w-4 text-gray-400">
                        </i>Edit Profil
            </button>
                        <button onclick="navigate('siswa')" class="sidebar-link w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 transition text-sm text-left">
                            <i class="fas fa-users w-4 text-gray-400">
                            </i>Daftar Siswa
            </button>
                            <button onclick="navigate('catatan')" class="sidebar-link w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 transition text-sm text-left">
                                <i class="fas fa-clipboard-list w-4 text-purple-400">
                                </i>Catatan Absen
            </button>
                                <button onclick="navigate('laporan')" class="sidebar-link w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 transition text-sm text-left">
                                    <i class="fas fa-flag w-4 text-red-400">
                                    </i>Laporkan Siswa
            </button>
                                    <button onclick="navigate('riwayat-laporan')" class="sidebar-link w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 transition text-sm text-left">
                                        <i class="fas fa-history w-4 text-red-300">
                                        </i>Riwayat Laporan
            </button>
                                        <button onclick="navigate('masukan')" class="sidebar-link w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 transition text-sm text-left">
                                            <i class="fas fa-paper-plane w-4 text-blue-400">
                                            </i>Kirim Masukan
            </button>
                                            <button onclick="navigate('riwayat-masukan')" class="sidebar-link w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 transition text-sm text-left">
                                                <i class="fas fa-inbox w-4 text-blue-300">
                                                </i>Riwayat Masukan
            </button>
                                                <button onclick="navigate('rekap-semester')" class="sidebar-link w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 transition text-sm text-left">
                                                    <i class="fas fa-chart-bar w-4 text-purple-400">
                                                    </i>Rekap Semester
            </button>
                                                    <button onclick="bukaBantuan()" class="sidebar-link w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 transition text-sm text-left">
                                                        <i class="fas fa-question-circle w-4 text-gray-400">
                                                        </i>Bantuan
            </button>
                                                    </nav>
                                                    <div class="p-4 border-t border-gray-100">
                                                        <button onclick="keluarGuru()" class="w-full flex items-center gap-2 text-red-500 hover:bg-red-50 px-4 py-2.5 rounded-xl transition text-sm font-bold">
                                                            <i class="fas fa-sign-out-alt">
                                                            </i>Keluar
            </button>
                                                        </div>
                                                    </aside>
                                                    <!-- Main Content -->
                                                    <div class="flex-1 lg:ml-60 flex flex-col min-h-screen">
                                                        <!-- Top bar mobile -->
                                                        <header class="lg:hidden bg-white border-b border-gray-100 px-4 py-3 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                                                            <div class="flex items-center gap-3">
                                                                <div class="w-8 h-8 bg-blue-600 rounded-xl flex items-center justify-center">
                                                                    <i class="fas fa-chalkboard-teacher text-white text-xs">
                                                                    </i>
                                                                </div>
                                                                <p class="font-black text-gray-800 text-sm" id="headerNama">Portal Guru</p>
                                                            </div>
                                                            <button onclick="keluarGuru()" class="text-red-500 text-sm font-bold px-3 py-1.5 rounded-xl hover:bg-red-50 transition">
                                                                <i class="fas fa-sign-out-alt mr-1">
                                                                </i>Keluar
            </button>
                                                            </header>
                                                            <!-- Pages -->
                                                            <main class="flex-1 p-4 md:p-6 pb-24 lg:pb-6">
                                                                <!-- ── DASHBOARD ── -->
                                                                <div id="view-dashboard" class="page-view fade-in">
                                                                    <!-- Header greeting -->
                                                                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-6 text-white shadow-xl mb-6 relative overflow-hidden">
                                                                        <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full blur-3xl">
                                                                        </div>
                                                                        <div class="relative z-10">
                                                                            <p class="text-blue-200 text-xs font-bold uppercase tracking-widest mb-1">Selamat Datang</p>
                                                                            <h2 class="text-2xl font-black mb-1" id="dashNama">Bapak/Ibu Guru</h2>
                                                                            <p class="text-blue-200 text-xs" id="dashMapel">—</p>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Info Cards -->
                                                                    <div class="grid grid-cols-2 gap-3 mb-6">
                                                                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                                                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Kelas Diajar</p>
                                                                            <p class="font-black text-blue-600 text-xl" id="dashJmlKelas">0</p>
                                                                            <p class="text-xs text-gray-500 mt-0.5">kelas</p>
                                                                        </div>
                                                                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                                                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Mata Pelajaran</p>
                                                                            <p class="font-black text-indigo-600 text-xl" id="dashJmlMapel">0</p>
                                                                            <p class="text-xs text-gray-500 mt-0.5">mata pelajaran</p>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Tentang Portal -->
                                                                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-4">
                                                                        <h3 class="font-black text-gray-800 mb-3 flex items-center gap-2">
                                                                            <i class="fas fa-info-circle text-blue-500">
                                                                            </i> Tentang Portal Guru</h3>
                                                                            <div class="space-y-2 text-xs text-gray-600 leading-relaxed">
                                                                                <p>🏫 Portal ini memudahkan Bapak/Ibu Guru dalam mengelola data kelas dan melaporkan aktivitas siswa secara digital.</p>
                                                                                <p>📋 <b>Fitur yang tersedia:</b>
                                                                            </p>
                                                                            <ul class="list-disc list-inside space-y-1 ml-2">
                                                                                <li>
                                                                                    <b>Edit Profil</b> — Kelola data diri, kelas yang diajarkan, dan mata pelajaran</li>
                                                                                    <li>
                                                                                        <b>Daftar Siswa</b> — Lihat rekap kehadiran siswa di kelas Anda</li>
                                                                                        <li>
                                                                                            <b>Laporkan Siswa</b> — Catat pelanggaran siswa selama PBM berlangsung</li>
                                                                                            <li>
                                                                                                <b>Kirim Masukan</b> — Sampaikan saran dan masukan untuk sekolah</li>
                                                                                            </ul>
                                                                                            <p>📌 Laporan yang dibuat akan otomatis tercatat di sistem dan siswa terkait akan mendapat poin pelanggaran.</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- Laporan Terbaru (ringkasan) -->
                                                                                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                                                                                        <h3 class="font-black text-gray-800 mb-3 flex items-center gap-2">
                                                                                            <i class="fas fa-flag text-red-400">
                                                                                            </i> Laporan Terbaru Saya</h3>
                                                                                            <div id="dashLaporanList" class="space-y-2">
                                                                                                <p class="text-xs text-gray-400 text-center py-4">Memuat laporan...</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- ── EDIT PROFIL ── -->
                                                                                    <div id="view-profil" class="page-view fade-in">
                                                                                        <h2 class="font-black text-gray-800 text-xl mb-5 flex items-center gap-2">
                                                                                            <i class="fas fa-user-edit text-blue-500">
                                                                                            </i> Edit Profil</h2>
                                                                                            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 space-y-4">
                                                                                                <!-- Nama Lengkap (read only) -->
                                                                                                <div>
                                                                                                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Nama Lengkap <span class="text-gray-300 font-normal normal-case">(tidak bisa diubah)</span>
                                                                                                </label>
                                                                                                <input type="text" id="profNama" readonly="" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 text-sm font-bold cursor-not-allowed">
                                                                                            </div>
                                                                                            <!-- Nama Panggilan -->
                                                                                            <div>
                                                                                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Nama Panggilan</label>
                                                                                                <input type="text" id="profPanggilan" placeholder="Contoh: Pak Budi" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-800 text-sm font-bold outline-none focus:border-blue-500 transition">
                                                                                            </div>
                                                                                            <!-- NIP -->
                                                                                            <div>
                                                                                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">NIP</label>
                                                                                                <input type="text" id="profNip" placeholder="Nomor Induk Pegawai" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-800 text-sm font-bold outline-none focus:border-blue-500 transition">
                                                                                            </div>
                                                                                            <!-- No WA -->
                                                                                            <div>
                                                                                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Nomor WhatsApp</label>
                                                                                                <input type="text" id="profWa" placeholder="628xxxxxxxxx" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-800 text-sm font-bold outline-none focus:border-blue-500 transition">
                                                                                            </div>
                                                                                            <!-- Mata Pelajaran (max 5) -->
                                                                                            <div>
                                                                                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Mata Pelajaran <span class="text-gray-400 font-normal normal-case">(maks. 5)</span>
                                                                                            </label>
                                                                                            <div class="relative mb-2">
                                                                                                <input type="text" id="inputTambahMapel" autocomplete="off" placeholder="Ketik / pilih mata pelajaran..." class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-gray-800 text-sm font-bold outline-none focus:border-blue-500 transition" oninput="cariMapelDrop(this.value)" onfocus="cariMapelDrop(this.value)">
                                                                                                <ul id="dropMapel" class="absolute w-full bg-white border border-gray-200 rounded-xl shadow-xl mt-1 hidden max-h-48 overflow-y-auto z-50 divide-y divide-gray-50">
                                                                                                </ul>
                                                                                            </div>
                                                                                            <div id="mapelTags" class="flex flex-wrap gap-2">
                                                                                            </div>
                                                                                        </div>
                                                                                        <!-- Kelas Diajarkan (max 15, autocomplete dari DB) -->
                                                                                        <div>
                                                                                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Kelas yang Diajarkan <span class="text-gray-400 font-normal normal-case">(maks. 15)</span>
                                                                                        </label>
                                                                                        <div class="relative mb-2">
                                                                                            <input type="text" id="inputTambahKelas" autocomplete="off" placeholder="Ketik nama kelas..." class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-gray-800 text-sm font-bold outline-none focus:border-blue-500 transition" oninput="cariKelasDrop(this.value)">
                                                                                            <ul id="dropKelas" class="absolute w-full bg-white border border-gray-200 rounded-xl shadow-xl mt-1 hidden max-h-40 overflow-y-auto z-50 divide-y divide-gray-50">
                                                                                            </ul>
                                                                                        </div>
                                                                                        <div id="kelasTags" class="flex flex-wrap gap-2">
                                                                                        </div>
                                                                                    </div>
                                                                                    <button onclick="simpanProfil()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-3.5 rounded-2xl transition text-sm shadow-md active:scale-95">
                                                                                        <i class="fas fa-save mr-2">
                                                                                        </i>Simpan Profil
                    </button>
                                                                                    </div>
                                                                                    <button onclick="navigate('catatan')" class="w-full mt-4 bg-white border-2 border-purple-200 hover:bg-purple-50 text-purple-700 font-black py-3.5 rounded-2xl transition text-sm flex items-center justify-center gap-2">
                                                                                        <i class="fas fa-clipboard-list">
                                                                                        </i> Buka Catatan Absen Pribadi</button>
                                                                                        <!-- UBAH KATA SANDI -->
                                                                                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mt-4 space-y-4">
                                                                                            <h3 class="font-black text-gray-800 flex items-center gap-2 text-base">
                                                                                                <i class="fas fa-lock text-orange-500">
                                                                                                </i> Ubah Kata Sandi
                </h3>
                                                                                                <div>
                                                                                                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Password Lama</label>
                                                                                                    <div class="relative">
                                                                                                        <input type="password" id="passLama" placeholder="Masukkan password lama" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-800 text-sm font-bold outline-none focus:border-orange-400 transition pr-12">
                                                                                                        <button onclick="togglePassField('passLama','eyeLama')" class="absolute right-4 top-3.5 text-gray-400 hover:text-gray-600">
                                                                                                            <i id="eyeLama" class="fas fa-eye text-sm">
                                                                                                            </i>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div>
                                                                                                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Password Baru</label>
                                                                                                    <div class="relative">
                                                                                                        <input type="password" id="passBaru" placeholder="Minimal 6 karakter" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-800 text-sm font-bold outline-none focus:border-orange-400 transition pr-12">
                                                                                                        <button onclick="togglePassField('passBaru','eyeBaru')" class="absolute right-4 top-3.5 text-gray-400 hover:text-gray-600">
                                                                                                            <i id="eyeBaru" class="fas fa-eye text-sm">
                                                                                                            </i>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div>
                                                                                                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Konfirmasi Password Baru</label>
                                                                                                    <input type="password" id="passKonfirm" placeholder="Ulangi password baru" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-800 text-sm font-bold outline-none focus:border-orange-400 transition">
                                                                                                </div>
                                                                                                <button onclick="ubahKataSandi()" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-black py-3.5 rounded-2xl transition text-sm shadow-md active:scale-95">
                                                                                                    <i class="fas fa-key mr-2">
                                                                                                    </i>Ubah Kata Sandi
                </button>
                                                                                                </div>
                                                                                            </div>
                                                                                            <!-- ── CATATAN ABSEN PRIBADI ── -->
                                                                                            <div id="view-catatan" class="page-view fade-in">
                                                                                                <h2 class="font-black text-gray-800 text-xl mb-1 flex items-center gap-2">
                                                                                                    <i class="fas fa-clipboard-list text-purple-500">
                                                                                                    </i> Catatan Absen Pribadi</h2>
                                                                                                    <p class="text-xs text-gray-400 mb-4">Salinan pribadi Anda. Boleh diedit atau dihapus; data absensi global tidak berubah.</p>
                                                                                                    <div class="flex gap-2 mb-3">
                                                                                                        <input type="text" id="cariCatatan" placeholder="Cari nama / kelas / tanggal..." oninput="muatCatatanAbsen()" class="flex-1 px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-purple-500 transition">
                                                                                                        <button onclick="salinHariIni()" class="flex-shrink-0 bg-purple-600 hover:bg-purple-700 text-white font-black px-3 py-2.5 rounded-xl text-xs transition active:scale-95 flex items-center gap-1.5">
                                                                                                            <i class="fas fa-copy">
                                                                                                            </i>
                                                                                                            <span class="hidden sm:inline">Salin Absensi Hari Ini</span>
                                                                                                            <span class="sm:hidden">Salin</span>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <div id="bannerCatatan" class="mb-2">
                                                                                                    </div>
                                                                                                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                                                                                                        <div id="listCatatanAbsen" class="space-y-2 max-h-[65vh] overflow-y-auto">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <!-- ── REKAP SEMESTER ── -->
                                                                                                <div id="view-rekap-semester" class="page-view fade-in">
                                                                                                    <h2 class="font-black text-gray-800 text-xl mb-5 flex items-center gap-2">
                                                                                                        <i class="fas fa-chart-bar text-purple-500">
                                                                                                        </i> Rekap Semester
                </h2>
                                                                                                        <div id="rekapSemesterContent">
                                                                                                            <div class="text-center py-10 text-gray-400 text-sm">
                                                                                                                <i class="fas fa-spinner fa-spin text-2xl mb-2 block">
                                                                                                                </i>Memuat...
                    </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <!-- ── RIWAYAT LAPORAN GURU ── -->
                                                                                                        <div id="view-riwayat-laporan" class="page-view fade-in">
                                                                                                            <h2 class="font-black text-gray-800 text-xl mb-5 flex items-center gap-2">
                                                                                                                <i class="fas fa-history text-red-500">
                                                                                                                </i> Riwayat Laporan Saya
                </h2>
                                                                                                                <div id="riwayatLaporanList" class="space-y-3">
                                                                                                                    <div class="text-center py-10 text-gray-400 text-sm">
                                                                                                                        <i class="fas fa-spinner fa-spin text-2xl mb-2 block">
                                                                                                                        </i>Memuat riwayat...
                    </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <!-- ── RIWAYAT MASUKAN GURU ── -->
                                                                                                                <div id="view-riwayat-masukan" class="page-view fade-in">
                                                                                                                    <h2 class="font-black text-gray-800 text-xl mb-5 flex items-center gap-2">
                                                                                                                        <i class="fas fa-inbox text-blue-500">
                                                                                                                        </i> Riwayat Masukan Saya
                </h2>
                                                                                                                        <div id="riwayatMasukanList" class="space-y-3">
                                                                                                                            <div class="text-center py-10 text-gray-400 text-sm">
                                                                                                                                <i class="fas fa-spinner fa-spin text-2xl mb-2 block">
                                                                                                                                </i>Memuat riwayat...
                    </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <!-- ── DAFTAR SISWA ── -->
                                                                                                                        <div id="view-siswa" class="page-view fade-in">
                                                                                                                            <h2 class="font-black text-gray-800 text-xl mb-4 flex items-center gap-2">
                                                                                                                                <i class="fas fa-users text-blue-500">
                                                                                                                                </i> Daftar Siswa</h2>
                                                                                                                                <div id="bannerJadwal" class="mb-3">
                                                                                                                                </div>
                                                                                                                                <!-- Baris 1: Semester + Kelas -->
                                                                                                                                <div class="flex gap-2 mb-3">
                                                                                                                                    <div class="relative flex-1">
                                                                                                                                        <select id="filterSemesterSiswa" onchange="loadSiswaKelas()" class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-xl text-gray-800 text-sm font-bold outline-none focus:border-blue-500 transition appearance-none bg-white">
                                                                                                                                            <option value="">— Semester —</option>
                                                                                                                                        </select>
                                                                                                                                        <i class="fas fa-chevron-down absolute right-3 top-3.5 text-gray-400 text-xs pointer-events-none">
                                                                                                                                        </i>
                                                                                                                                    </div>
                                                                                                                                    <div class="relative flex-1">
                                                                                                                                        <select id="filterKelasSiswa" onchange="loadSiswaKelas()" class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-xl text-gray-800 text-sm font-bold outline-none focus:border-blue-500 transition appearance-none bg-white">
                                                                                                                                            <option value="">— Kelas —</option>
                                                                                                                                        </select>
                                                                                                                                        <i class="fas fa-chevron-down absolute right-3 top-3.5 text-gray-400 text-xs pointer-events-none">
                                                                                                                                        </i>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <!-- Baris 2: Filter Periode -->
                                                                                                                                <div class="flex gap-1.5 mb-3 overflow-x-auto pb-1">
                                                                                                                                    <button onclick="setPeriodeSiswa('1H',this)" class="periode-btn flex-shrink-0 px-3 py-2 rounded-xl text-xs font-black border-2 border-blue-500 bg-blue-500 text-white transition">1 Hari</button>
                                                                                                                                    <button onclick="setPeriodeSiswa('7H',this)" class="periode-btn flex-shrink-0 px-3 py-2 rounded-xl text-xs font-black border-2 border-gray-200 text-gray-600 transition hover:border-blue-400">7 Hari</button>
                                                                                                                                    <button onclick="setPeriodeSiswa('1B',this)" class="periode-btn flex-shrink-0 px-3 py-2 rounded-xl text-xs font-black border-2 border-gray-200 text-gray-600 transition hover:border-blue-400">1 Bulan</button>
                                                                                                                                    <button onclick="setPeriodeSiswa('1S',this)" class="periode-btn flex-shrink-0 px-3 py-2 rounded-xl text-xs font-black border-2 border-gray-200 text-gray-600 transition hover:border-blue-400">1 Semester</button>
                                                                                                                                    <button id="btnPeriodeTgl" onclick="bukaFilterTanggal('siswa')" class="periode-btn flex-shrink-0 px-3 py-2 rounded-xl text-xs font-black border-2 border-gray-200 text-gray-600 transition hover:border-blue-400">
                                                                                                                                        <i class="fas fa-calendar-alt mr-1">
                                                                                                                                        </i>
                                                                                                                                        <span id="lblPeriodeTgl">Tanggal</span>
                                                                                                                                    </button>
                                                                                                                                </div>
                                                                                                                                <!-- Baris 3: Search + Tombol -->
                                                                                                                                <div class="flex gap-2 mb-4">
                                                                                                                                    <input type="text" id="searchSiswa" placeholder="Cari nama siswa..." oninput="filterSiswa()" class="flex-1 px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 transition">
                                                                                                                                    <button onclick="bukaAbsenHariIni()" class="flex-shrink-0 bg-green-500 hover:bg-green-600 text-white font-black px-3 py-2.5 rounded-xl text-xs transition active:scale-95 flex items-center gap-1.5">
                                                                                                                                        <i class="fas fa-calendar-day">
                                                                                                                                        </i>
                                                                                                                                        <span class="hidden sm:inline">Absen Hari Ini</span>
                                                                                                                                    </button>
                                                                                                                                </div>
                                                                                                                                <!-- Tabel Siswa — tampilan centang + predikat -->
                                                                                                                                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                                                                                                                    <div class="overflow-x-auto">
                                                                                                                                        <table class="w-full text-sm">
                                                                                                                                            <thead>
                                                                                                                                                <tr class="bg-gray-50 border-b border-gray-100">
                                                                                                                                                    <th class="text-left px-4 py-3 font-black text-xs text-gray-500 uppercase tracking-widest">Nama</th>
                                                                                                                                                    <th class="text-center px-2 py-3 font-black text-xs text-green-600 uppercase" title="Hadir">✓</th>
                                                                                                                                                    <th class="text-center px-2 py-3 font-black text-xs text-yellow-500 uppercase" title="Izin">IZ</th>
                                                                                                                                                    <th class="text-center px-2 py-3 font-black text-xs text-blue-500 uppercase" title="Sakit">SK</th>
                                                                                                                                                    <th class="text-center px-2 py-3 font-black text-xs text-red-500 uppercase" title="Alfa">AL</th>
                                                                                                                                                    <th class="text-center px-2 py-3 font-black text-xs text-gray-500 uppercase">Poin</th>
                                                                                                                                                    <th class="text-center px-3 py-3 font-black text-xs text-gray-400 uppercase">Predikat</th>
                                                                                                                                                </tr>
                                                                                                                                            </thead>
                                                                                                                                            <tbody id="siswaTableBody">
                                                                                                                                                <tr>
                                                                                                                                                    <td colspan="7" class="text-center py-10 text-gray-400 text-xs">
                                                                                                                                                        <i class="fas fa-arrow-up text-gray-300 text-xl block mb-2">
                                                                                                                                                        </i>Pilih semester &amp; kelas untuk melihat data
                                </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <!-- Info jumlah -->
                                                                                                                                    <p id="infoJumlahSiswa" class="text-[10px] text-gray-400 mt-2 text-center">
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                                <!-- ── LAPORKAN SISWA ── -->
                                                                                                                                <div id="view-laporan" class="page-view fade-in">
                                                                                                                                    <h2 class="font-black text-gray-800 text-xl mb-5 flex items-center gap-2">
                                                                                                                                        <i class="fas fa-flag text-red-500">
                                                                                                                                        </i> Laporkan Siswa</h2>
                                                                                                                                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 space-y-4">
                                                                                                                                            <!-- Tanggal -->
                                                                                                                                            <div>
                                                                                                                                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Tanggal Kejadian</label>
                                                                                                                                                <input type="date" id="laporTanggal" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 transition">
                                                                                                                                            </div>
                                                                                                                                            <!-- Mata Pelajaran -->
                                                                                                                                            <div>
                                                                                                                                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Mata Pelajaran</label>
                                                                                                                                                <select id="laporMapel" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 transition bg-white">
                                                                                                                                                    <option value="">— Pilih Mata Pelajaran —</option>
                                                                                                                                                </select>
                                                                                                                                            </div>
                                                                                                                                            <!-- Jam/Waktu -->
                                                                                                                                            <div>
                                                                                                                                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Jam / Waktu Kejadian</label>
                                                                                                                                                <input type="time" id="laporJam" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 transition">
                                                                                                                                            </div>
                                                                                                                                            <!-- Input Nama Siswa (multi, autocomplete) -->
                                                                                                                                            <div>
                                                                                                                                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Nama Siswa yang Dilaporkan</label>
                                                                                                                                                <div class="relative mb-2">
                                                                                                                                                    <input type="text" id="inputCariSiswaLapor" autocomplete="off" placeholder="Ketik nama/panggilan siswa..." class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 transition" oninput="cariSiswaLapor(this.value)">
                                                                                                                                                    <ul id="dropSiswaLapor" class="absolute w-full bg-white border border-gray-200 rounded-xl shadow-2xl mt-1 hidden max-h-48 overflow-y-auto z-50 divide-y divide-gray-50">
                                                                                                                                                    </ul>
                                                                                                                                                </div>
                                                                                                                                                <div id="tagsSiswaLapor" class="flex flex-wrap gap-2">
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                            <!-- Jenis Laporan -->
                                                                                                                                            <div>
                                                                                                                                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Jenis Pelanggaran</label>
                                                                                                                                                <div class="space-y-2" id="jenisLaporanGroup">
                                                                                                                                                    <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-400 transition peer-checked:border-red-500">
                                                                                                                                                        <input type="radio" name="jenisLaporan" value="alfa_mapel" class="text-red-500">
                                                                                                                                                        <div>
                                                                                                                                                            <p class="font-bold text-sm text-gray-800">Alfa Saat Mata Pelajaran</p>
                                                                                                                                                            <p class="text-[10px] text-gray-400">Tidak hadir tanpa keterangan — +10 poin</p>
                                                                                                                                                        </div>
                                                                                                                                                    </label>
                                                                                                                                                    <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-400 transition">
                                                                                                                                                        <input type="radio" name="jenisLaporan" value="melanggar_peraturan" class="text-orange-500">
                                                                                                                                                        <div>
                                                                                                                                                            <p class="font-bold text-sm text-gray-800">Melanggar Peraturan</p>
                                                                                                                                                            <p class="text-[10px] text-gray-400">Pelanggaran tata tertib di kelas — +6 poin</p>
                                                                                                                                                        </div>
                                                                                                                                                    </label>
                                                                                                                                                    <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-yellow-400 transition">
                                                                                                                                                        <input type="radio" name="jenisLaporan" value="makan_di_kelas" class="text-yellow-500">
                                                                                                                                                        <div>
                                                                                                                                                            <p class="font-bold text-sm text-gray-800">Makan di Kelas</p>
                                                                                                                                                            <p class="text-[10px] text-gray-400">Makan/minum saat PBM berlangsung — +6 poin</p>
                                                                                                                                                        </div>
                                                                                                                                                    </label>
                                                                                                                                                    <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-purple-400 transition">
                                                                                                                                                        <input type="radio" name="jenisLaporan" value="mengganggu_pembelajaran" class="text-purple-500">
                                                                                                                                                        <div>
                                                                                                                                                            <p class="font-bold text-sm text-gray-800">Mengganggu Pembelajaran</p>
                                                                                                                                                            <p class="text-[10px] text-gray-400">Membuat keributan/kegaduhan saat PBM — +6 poin</p>
                                                                                                                                                        </div>
                                                                                                                                                    </label>
                                                                                                                                                    <!-- Pelanggaran Lainnya dari Master -->
                                                                                                                                                    <label class="flex items-center gap-3 p-3 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-indigo-400 transition" onclick="bukaPilihMasterPelanggaran()">
                                                                                                                                                        <input type="radio" name="jenisLaporan" value="master_pelanggaran" id="radioMasterPelanggaran" class="text-indigo-500" onclick="bukaPilihMasterPelanggaran()">
                                                                                                                                                        <div class="flex-1">
                                                                                                                                                            <p class="font-bold text-sm text-gray-800">Pelanggaran Lainnya...</p>
                                                                                                                                                            <p class="text-[10px] text-gray-400">Pilih dari daftar master pelanggaran sekolah</p>
                                                                                                                                                        </div>
                                                                                                                                                        <i class="fas fa-chevron-right text-gray-400 text-xs">
                                                                                                                                                        </i>
                                                                                                                                                    </label>
                                                                                                                                                    <!-- Info pelanggaran terpilih dari master -->
                                                                                                                                                    <div id="infoMasterPelanggaran" class="hidden bg-indigo-50 border-2 border-indigo-200 rounded-xl p-3">
                                                                                                                                                        <p class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-1">Pelanggaran Dipilih:</p>
                                                                                                                                                        <p id="namaLainnya" class="font-bold text-gray-800 text-sm">
                                                                                                                                                        </p>
                                                                                                                                                        <p id="poinLainnya" class="text-xs text-indigo-500 font-bold mt-0.5">
                                                                                                                                                        </p>
                                                                                                                                                        <input type="hidden" id="idMasterPelanggaran">
                                                                                                                                                        <input type="hidden" id="poinMasterPelanggaran">
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                            <!-- Keterangan tambahan -->
                                                                                                                                            <div>
                                                                                                                                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Keterangan Tambahan <span class="text-gray-300 font-normal normal-case">(opsional)</span>
                                                                                                                                            </label>
                                                                                                                                            <textarea id="laporKeterangan" rows="3" placeholder="Detail kejadian..." class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 transition resize-none">
                                                                                                                                            </textarea>
                                                                                                                                        </div>
                                                                                                                                        <button onclick="kirimLaporanSiswa()" class="w-full bg-red-600 hover:bg-red-700 text-white font-black py-3.5 rounded-2xl transition text-sm shadow-md active:scale-95">
                                                                                                                                            <i class="fas fa-paper-plane mr-2">
                                                                                                                                            </i>Kirim Laporan
                    </button>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <!-- ── KIRIM MASUKAN ── -->
                                                                                                                                    <div id="view-masukan" class="page-view fade-in">
                                                                                                                                        <h2 class="font-black text-gray-800 text-xl mb-5 flex items-center gap-2">
                                                                                                                                            <i class="fas fa-paper-plane text-blue-500">
                                                                                                                                            </i> Kirim Masukan</h2>
                                                                                                                                            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 space-y-4">
                                                                                                                                                <div>
                                                                                                                                                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Kategori Masukan</label>
                                                                                                                                                    <select id="masukanKategori" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 transition bg-white">
                                                                                                                                                        <option value="Saran Fasilitas">Saran Fasilitas</option>
                                                                                                                                                        <option value="Saran Kurikulum">Saran Kurikulum</option>
                                                                                                                                                        <option value="Saran Tata Tertib">Saran Tata Tertib</option>
                                                                                                                                                        <option value="Keluhan">Keluhan</option>
                                                                                                                                                        <option value="Apresiasi">Apresiasi</option>
                                                                                                                                                        <option value="Lainnya">Lainnya</option>
                                                                                                                                                    </select>
                                                                                                                                                </div>
                                                                                                                                                <div>
                                                                                                                                                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Isi Masukan</label>
                                                                                                                                                    <textarea id="masukanTeks" rows="5" placeholder="Tulis masukan, saran, atau keluhan Anda..." class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 transition resize-none">
                                                                                                                                                    </textarea>
                                                                                                                                                </div>
                                                                                                                                                <button onclick="kirimMasukan()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-3.5 rounded-2xl transition text-sm shadow-md active:scale-95">
                                                                                                                                                    <i class="fas fa-paper-plane mr-2">
                                                                                                                                                    </i>Kirim Masukan
                    </button>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                        </main>
                                                                                                                                        <!-- Bottom Nav (mobile) -->
                                                                                                                                        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 shadow-2xl z-30 flex">
                                                                                                                                            <button onclick="navigate('dashboard')" class="nav-item flex-1 py-2.5 flex flex-col items-center gap-0.5 text-gray-400 transition">
                                                                                                                                                <i class="fas fa-home text-base">
                                                                                                                                                </i>
                                                                                                                                                <span class="text-[9px] font-bold">Beranda</span>
                                                                                                                                            </button>
                                                                                                                                            <button onclick="navigate('siswa')" class="nav-item flex-1 py-2.5 flex flex-col items-center gap-0.5 text-gray-400 transition">
                                                                                                                                                <i class="fas fa-users text-base">
                                                                                                                                                </i>
                                                                                                                                                <span class="text-[9px] font-bold">Siswa</span>
                                                                                                                                            </button>
                                                                                                                                            <button onclick="navigate('laporan')" class="nav-item flex-1 py-2.5 flex flex-col items-center gap-0.5 text-gray-400 transition">
                                                                                                                                                <i class="fas fa-flag text-base">
                                                                                                                                                </i>
                                                                                                                                                <span class="text-[9px] font-bold">Laporan</span>
                                                                                                                                            </button>
                                                                                                                                            <button onclick="navigate('riwayat-laporan')" class="nav-item flex-1 py-2.5 flex flex-col items-center gap-0.5 text-gray-400 transition">
                                                                                                                                                <i class="fas fa-history text-base">
                                                                                                                                                </i>
                                                                                                                                                <span class="text-[9px] font-bold">Riwayat</span>
                                                                                                                                            </button>
                                                                                                                                            <button onclick="navigate('masukan')" class="nav-item flex-1 py-2.5 flex flex-col items-center gap-0.5 text-gray-400 transition">
                                                                                                                                                <i class="fas fa-paper-plane text-base">
                                                                                                                                                </i>
                                                                                                                                                <span class="text-[9px] font-bold">Masukan</span>
                                                                                                                                            </button>
                                                                                                                                            <button onclick="navigate('profil')" class="nav-item flex-1 py-2.5 flex flex-col items-center gap-0.5 text-gray-400 transition">
                                                                                                                                                <i class="fas fa-user text-base">
                                                                                                                                                </i>
                                                                                                                                                <span class="text-[9px] font-bold">Profil</span>
                                                                                                                                            </button>
                                                                                                                                        </nav>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <script>
// ═══════════════════════════════════════════════════════════
// CONFIG & STATE
// ═══════════════════════════════════════════════════════════
const API_URL = '{{ env('API_URL') }}';
let guruData   = null;   // data guru yg login
let allKelas   = [];     // master kelas dari DB
let kelasTags  = [];     // kelas yang dipilih di profil
let mapelList  = [];     // mapel yang diisi
let siswaCacheAll = [];  // cache semua siswa untuk laporan
let siswaLaporList = []; // siswa yg dipilih untuk dilaporkan
let siswaSearchCache = [];
let allAbsensi = [];

// Poin per jenis laporan
const POIN_LAPORAN = {
    alfa_mapel:             10,
    melanggar_peraturan:    6,
    makan_di_kelas:         6,
    mengganggu_pembelajaran: 6
};

const LABEL_LAPORAN = {
    alfa_mapel:              'Alfa Saat Mata Pelajaran',
    melanggar_peraturan:     'Melanggar Peraturan',
    makan_di_kelas:          'Makan di Kelas',
    mengganggu_pembelajaran: 'Mengganggu Pembelajaran'
};

// ═══════════════════════════════════════════════════════════
// API
// ═══════════════════════════════════════════════════════════
async function callBackend(payload) {
    const res = await fetch(API_URL, {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });
    return res.json();
}

// Versi ketat: lempar error bila server membalas error.
// (callBackend biasa menelan error → data kosong → semua siswa tampak "belum absen")
async function callBackendStrict(payload) {
    const res = await fetch(API_URL, {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });
    const json = await res.json().catch(() => ({}));
    if (!res.ok || json.error) throw new Error(json.error || ('Server error ' + res.status));
    return json;
}
const _enc = encodeURIComponent;
function _tglWibHariIni() { return new Date().toLocaleDateString('en-CA', { timeZone: 'Asia/Jakarta' }); }
// Batas hari dalam WIB → waktu di DB (UTC) tetap cocok, termasuk absen pagi sebelum 07:00 WIB
function _rentangWib(dari, sampai) { return { awal: `${dari}T00:00:00+07:00`, akhir: `${sampai}T23:59:59.999+07:00` }; }
function _fmtTglID(t) { return new Date(t + 'T00:00:00+07:00').toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric', timeZone:'Asia/Jakarta' }); }
function _labelFilter(f) {
    if (!f.dari) return '—';
    if (f.mode === 'single' || f.dari === f.sampai) return (f.dari === _tglWibHariIni() ? 'Hari ini • ' : '') + _fmtTglID(f.dari);
    return _fmtTglID(f.dari) + ' – ' + _fmtTglID(f.sampai);
}
function _jamWib(t) { return t ? new Date(t).toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', timeZone:'Asia/Jakarta' }).replace('.', ':') : '—'; }
// Ambil semua baris (Supabase membatasi 1000 baris per request → paginasi)
async function _ambilSemua(tableBase) {
    const out = [], step = 1000;
    for (let off = 0; off < 50000; off += step) {
        const r = await callBackendStrict({ type:'supabase_get', action:'getData', payload:{ table: `${tableBase}&limit=${step}&offset=${off}` }});
        const rows = r.data || [];
        out.push(...rows);
        if (rows.length < step) break;
    }
    return out;
}

// ── Filter tanggal (dipakai modal "Absen Hari Ini" & tabel "Daftar Siswa") ──
let _absenFilter = { mode:'single', dari:'', sampai:'' };
let _siswaFilter = { mode:'single', dari:'', sampai:'' };

async function bukaFilterTanggal(target) {
    const cur = target === 'absen' ? _absenFilter : _siswaFilter;
    const today = _tglWibHariIni();
    const r = await Swal.fire({
        title: '<i class="fas fa-calendar-alt text-blue-500 mr-2">
                                                                                                                            </i>Filter Tanggal',
        html: `
        <div id="ftWrap" data-mode="${cur.mode === 'range' ? 'range' : 'single'}" class="text-left">
                                                                                                                            <div class="grid grid-cols-2 gap-2 mb-4">
                                                                                                                                <button type="button" id="ftBtnSingle" class="px-3 py-2.5 rounded-xl text-xs font-black border-2 transition">📅 Satu Hari</button>
                                                                                                                                <button type="button" id="ftBtnRange"  class="px-3 py-2.5 rounded-xl text-xs font-black border-2 transition">🗓️ Dari – Sampai</button>
                                                                                                                            </div>
                                                                                                                            <div id="ftSingle">
                                                                                                                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Tanggal</label>
                                                                                                                                <input type="date" id="ftTgl" value="${cur.dari || today}" max="${today}" class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500">
                                                                                                                            </div>
                                                                                                                            <div id="ftRange" class="hidden space-y-3">
                                                                                                                                <div>
                                                                                                                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Dari Tanggal</label>
                                                                                                                                    <input type="date" id="ftDari" value="${cur.dari || today}" max="${today}" class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500">
                                                                                                                                </div>
                                                                                                                                <div>
                                                                                                                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Sampai Tanggal</label>
                                                                                                                                    <input type="date" id="ftSampai" value="${cur.sampai || today}" max="${today}" class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500">
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>`,
        showCancelButton: true,
        confirmButtonText: 'Terapkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#2563eb',
        didOpen: () => {
            const wrap = document.getElementById('ftWrap');
            const paint = () => {
                const m = wrap.dataset.mode;
                const on = 'border-blue-500 bg-blue-50 text-blue-700', off = 'border-gray-200 bg-white text-gray-500';
                document.getElementById('ftBtnSingle').className = 'px-3 py-2.5 rounded-xl text-xs font-black border-2 transition ' + (m === 'single' ? on : off);
                document.getElementById('ftBtnRange').className  = 'px-3 py-2.5 rounded-xl text-xs font-black border-2 transition ' + (m === 'range' ? on : off);
                document.getElementById('ftSingle').classList.toggle('hidden', m !== 'single');
                document.getElementById('ftRange').classList.toggle('hidden', m !== 'range');
            };
            document.getElementById('ftBtnSingle').onclick = () => { wrap.dataset.mode = 'single'; paint(); };
            document.getElementById('ftBtnRange').onclick  = () => { wrap.dataset.mode = 'range';  paint(); };
            paint();
        },
        preConfirm: () => {
            const m = document.getElementById('ftWrap').dataset.mode;
            if (m === 'single') {
                const t = document.getElementById('ftTgl').value;
                if (!t) { Swal.showValidationMessage('Pilih tanggal'); return false; }
                return { mode:'single', dari:t, sampai:t };
            }
            const d = document.getElementById('ftDari').value, e = document.getElementById('ftSampai').value;
            if (!d || !e) { Swal.showValidationMessage('Isi tanggal awal dan tanggal akhir'); return false; }
            if (e < d) { Swal.showValidationMessage('Tanggal akhir tidak boleh sebelum tanggal awal'); return false; }
            return { mode:'range', dari:d, sampai:e };
        }
    });
    if (!r.isConfirmed) return;

    if (target === 'absen') {
        _absenFilter = r.value;
        loadAbsenHariIni();
    } else {
        _siswaFilter = r.value;
        _periodeAktif = 'TGL';
        document.querySelectorAll('.periode-btn').forEach(b => {
            b.classList.remove('border-blue-500','bg-blue-500','text-white');
            b.classList.add('border-gray-200','text-gray-600');
        });
        const btn = document.getElementById('btnPeriodeTgl');
        if (btn) { btn.classList.add('border-blue-500','bg-blue-500','text-white'); btn.classList.remove('border-gray-200','text-gray-600'); }
        const lbl = document.getElementById('lblPeriodeTgl');
        if (lbl) lbl.textContent = r.value.dari === r.value.sampai ? _fmtTglID(r.value.dari) : (_fmtTglID(r.value.dari) + ' – ' + _fmtTglID(r.value.sampai));
        loadSiswaKelas();
    }
}

// ═══════════════════════════════════════════════════════════
// LOGIN
// ═══════════════════════════════════════════════════════════
let guruDropCache = [];

async function cariNamaGuru(val) {
    const drop = document.getElementById('dropGuru');
    if (!val || val.length < 2) { drop.classList.add('hidden'); return; }
    try {
        const res = await callBackend({ type:'supabase_get', action:'cariGuru', payload:{ nama: val }});
        guruDropCache = res.data || [];
        drop.innerHTML = guruDropCache.map((g,i) => `
            <li onclick="pilihGuru(${i})" class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition">
                                                                                                                        <p class="font-bold text-gray-800 text-sm">${g.nama_lengkap}</p>
                                                                                                                        <p class="text-[10px] text-gray-400">${g.nip||'NIP belum diisi'}</p>
                                                                                                                    </li>`).join('') || '<li class="px-4 py-3 text-xs text-gray-400">Tidak ditemukan</li>';
        drop.classList.remove('hidden');
    } catch(e) {}
}

function pilihGuru(i) {
    const g = guruDropCache[i]; if (!g) return;
    document.getElementById('loginNamaGuru').value = g.nama_lengkap;
    document.getElementById('loginNamaGuru').dataset.guruId = g.id;
    document.getElementById('dropGuru').classList.add('hidden');
    window._selectedGuruLogin = g;
}

function navDropGuru(e) {
    const drop = document.getElementById('dropGuru');
    const items = drop.querySelectorAll('li');
    const active = drop.querySelector('.bg-blue-100');
    let idx = Array.from(items).indexOf(active);
    if (e.key === 'ArrowDown') { e.preventDefault(); idx = Math.min(idx+1, items.length-1); }
    else if (e.key === 'ArrowUp') { e.preventDefault(); idx = Math.max(idx-1, 0); }
    else if (e.key === 'Enter') { e.preventDefault(); if (active) active.click(); return; }
    else return;
    items.forEach(li => li.classList.remove('bg-blue-100'));
    if (items[idx]) { items[idx].classList.add('bg-blue-100'); items[idx].scrollIntoView({block:'nearest'}); }
}

function togglePass() {
    const inp = document.getElementById('loginPassGuru');
    const eye = document.getElementById('passEye');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    eye.className = inp.type === 'password' ? 'fas fa-eye text-sm' : 'fas fa-eye-slash text-sm';
}

async function prosesLoginGuru() {
    const namaVal = document.getElementById('loginNamaGuru').value.trim();
    const passVal = document.getElementById('loginPassGuru').value;
    if (!namaVal) { Swal.fire('Nama Kosong', 'Ketik nama guru terlebih dahulu.', 'warning'); return; }
    if (!passVal) { Swal.fire('Password Kosong', 'Masukkan password.', 'warning'); return; }

    const btn = document.getElementById('btnLoginGuru');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2">
                                                                                                                </i>Memeriksa...';
    btn.disabled = true;

    try {
        // Ambil data guru dari DB
        let guru = window._selectedGuruLogin;
        if (!guru || guru.nama_lengkap.toLowerCase() !== namaVal.toLowerCase()) {
            const res = await callBackend({ type:'supabase_get', action:'loginGuru', payload:{ nama: namaVal }});
            guru = res.data?.[0] || null;
        }
        if (!guru) throw new Error('Nama guru tidak ditemukan di sistem. Hubungi admin.');

        // Verifikasi password via bcrypt (server side)
        const verRes = await callBackend({ type:'supabase_post', action:'verifyGuruPassword', payload:{ id: guru.id, password: passVal }});
        const isValid = verRes.data?.valid ?? verRes.valid ?? false;
        if (!isValid) throw new Error('Password salah. Coba lagi atau hubungi admin.');

        // Login sukses
        guruData = guru;
        sessionStorage.setItem('ea_guru_session', JSON.stringify(guru));
        masukDashboardGuru();

    } catch(e) {
        Swal.fire({ icon:'error', title:'Login Gagal', text: e.message, confirmButtonColor:'#2563eb' });
    } finally {
        btn.innerHTML = '<i class="fas fa-sign-in-alt mr-2">
                                                                                                            </i>Masuk Portal Guru';
        btn.disabled = false;
    }
}

// ═══════════════════════════════════════════════════════════
// DASHBOARD
// ═══════════════════════════════════════════════════════════
async function masukDashboardGuru() {
    document.getElementById('loginScreen').classList.add('hidden');
    document.getElementById('mainApp').classList.remove('hidden');

    const nama = guruData.nama_panggilan || guruData.nama_lengkap;
    const mapel = (guruData.mapel ? (typeof guruData.mapel === 'string' ? JSON.parse(guruData.mapel) : guruData.mapel) : []).join(', ');
    const kelas = guruData.kelas_diajarkan ? (typeof guruData.kelas_diajarkan === 'string' ? JSON.parse(guruData.kelas_diajarkan) : guruData.kelas_diajarkan) : [];

    document.getElementById('sideNama').textContent    = nama;
    document.getElementById('headerNama').textContent  = nama;
    document.getElementById('dashNama').textContent    = 'Bpk/Ibu ' + nama;
    document.getElementById('dashMapel').textContent   = mapel || '—';
    document.getElementById('dashJmlKelas').textContent = kelas.length;
    document.getElementById('dashJmlMapel').textContent = (guruData.mapel ? (typeof guruData.mapel === 'string' ? JSON.parse(guruData.mapel) : guruData.mapel) : []).length;

    navigate('dashboard');
    await loadLaporanTerbaru();
    await loadMasterKelas();
}

async function loadLaporanTerbaru() {
    const el = document.getElementById('dashLaporanList');
    try {
        const res = await callBackend({ type:'supabase_get', action:'getLaporanGuru', payload:{ id_guru: guruData.id }});
        const list = (res.data||[]).slice(0,5);
        if (!list.length) { el.innerHTML = '<p class="text-xs text-gray-400 text-center py-4">Belum ada laporan</p>'; return; }
        el.innerHTML = list.map(l => `
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                                                                                                            <div class="w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                                                                                                                <i class="fas fa-flag text-red-500 text-xs">
                                                                                                                </i>
                                                                                                            </div>
                                                                                                            <div class="min-w-0 flex-1">
                                                                                                                <p class="font-bold text-gray-800 text-xs truncate">${l.label_jenis||l.jenis||'Laporan Siswa'}</p>
                                                                                                                <p class="text-[10px] text-gray-500 mt-0.5">${l.tanggal||''} ${l.jam||''} — ${l.nama_siswa||''} (${l.kelas_siswa||''})</p>
                                                                                                                <span class="text-[10px] font-bold text-red-500">+${l.poin_siswa||0} poin</span>
                                                                                                            </div>
                                                                                                        </div>`).join('');
    } catch(e) { el.innerHTML = '<p class="text-xs text-red-400 text-center py-4">Gagal memuat laporan</p>'; }
}

// ═══════════════════════════════════════════════════════════
// NAVIGASI
// ═══════════════════════════════════════════════════════════
function navigate(page) {
    document.querySelectorAll('.page-view').forEach(v => v.classList.remove('active'));
    document.querySelectorAll('.nav-item, .sidebar-link').forEach(b => b.classList.remove('active'));
    const view = document.getElementById(`view-${page}`);
    if (view) { view.classList.add('active'); view.classList.remove('fade-in'); void view.offsetWidth; view.classList.add('fade-in'); }
    document.querySelectorAll(`[onclick="navigate('${page}')"]`).forEach(b => b.classList.add('active'));

    if (page === 'profil')           muatDataProfil();
    if (page === 'siswa')            muatDropKelasFilter();
    if (page === 'laporan')          muatFormLaporan();
    if (page === 'riwayat-laporan')  muatRiwayatLaporan();
    if (page === 'riwayat-masukan')  muatRiwayatMasukan();
    if (page === 'rekap-semester')   muatMenuRekapSemester();
}

// ═══════════════════════════════════════════════════════════
// PROFIL
// ═══════════════════════════════════════════════════════════
let mapelTags = [];

async function ensureMapelCache() {
    if (_cacheMapel.length) return;
    try {
        const res = await callBackend({ type:'supabase_get', action:'getData', payload:{ table:'master_mapel?aktif=eq.true&order=nama_mapel.asc' }});
        _cacheMapel = res.data || [];
    } catch(e) {}
}

async function muatDataProfil() {
    if (!guruData) return;
    document.getElementById('profNama').value      = guruData.nama_lengkap || '';
    document.getElementById('profPanggilan').value = guruData.nama_panggilan || '';
    document.getElementById('profNip').value       = guruData.nip || '';
    document.getElementById('profWa').value        = guruData.no_wa || '';
    const parse = v => { try { const x = typeof v === 'string' ? JSON.parse(v || '[]') : (v || []); return Array.isArray(x) ? x : []; } catch(e) { return []; } };
    mapelTags = parse(guruData.mapel);
    renderMapelTags();
    kelasTags = parse(guruData.kelas_diajarkan);
    renderKelasTags();
    await ensureMapelCache();
}

async function cariMapelDrop(val) {
    const drop = document.getElementById('dropMapel');
    await ensureMapelCache();
    const q = (val || '').toLowerCase();
    const list = _cacheMapel.map(m => m.nama_mapel).filter(n => !mapelTags.includes(n) && n.toLowerCase().includes(q)).slice(0, 20);
    if (!list.length) {
        drop.innerHTML = `<li class="px-4 py-2.5 text-xs text-gray-400">${_cacheMapel.length ? 'Tidak ditemukan di Master Mapel' : 'Master Mapel kosong / gagal dimuat'}</li>`;
    } else {
        drop.innerHTML = list.map(n => `<li onclick="tambahMapelTag(decodeURIComponent('${encodeURIComponent(n)}'))" class="px-4 py-2.5 hover:bg-blue-50 cursor-pointer text-sm font-bold text-gray-700 transition">${n}</li>`).join('');
    }
    drop.classList.remove('hidden');
}

function tambahMapelTag(m) {
    if (mapelTags.includes(m)) return;
    if (mapelTags.length >= 5) { Swal.fire({toast:true, position:'top-end', icon:'warning', title:'Maks. 5 mata pelajaran', showConfirmButton:false, timer:1500}); return; }
    mapelTags.push(m);
    renderMapelTags();
    document.getElementById('inputTambahMapel').value = '';
    document.getElementById('dropMapel').classList.add('hidden');
}

function hapusMapelTag(i) { mapelTags.splice(i, 1); renderMapelTags(); }

function renderMapelTags() {
    const el = document.getElementById('mapelTags');
    if (!el) return;
    el.innerHTML = mapelTags.map((m, i) => `
        <span class="flex items-center gap-1.5 bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-full">
            ${m}
            <button onclick="hapusMapelTag(${i})" class="text-indigo-400 hover:text-red-500 transition">
                                                                                                        <i class="fas fa-times text-[10px]">
                                                                                                        </i>
                                                                                                    </button>
                                                                                                </span>`).join('');
}

async function loadMasterKelas() {
    try {
        const res = await callBackend({ type:'supabase_get', action:'getAllKelas' });
        allKelas = (res.data || []).map(k => k.nama_kelas || k.kelas || '').filter(Boolean);
    } catch(e) {}
}

async function cariKelasDrop(val) {
    const drop = document.getElementById('dropKelas');
    if (!val || val.length < 1) { drop.classList.add('hidden'); return; }
    const filtered = allKelas.filter(k => k.toLowerCase().includes(val.toLowerCase())).slice(0, 10);
    if (!filtered.length) { drop.classList.add('hidden'); return; }
    drop.innerHTML = filtered.map(k => `
        <li onclick="tambahKelasTag('${k}')" class="px-4 py-2.5 hover:bg-blue-50 cursor-pointer text-sm font-bold text-gray-700 transition">${k}</li>
    `).join('');
    drop.classList.remove('hidden');
}

function tambahKelasTag(kelas) {
    if (kelasTags.includes(kelas)) { Swal.fire({toast:true, position:'top-end', icon:'info', title:'Kelas sudah ditambahkan', showConfirmButton:false, timer:1200}); return; }
    if (kelasTags.length >= 15) { Swal.fire({toast:true, position:'top-end', icon:'warning', title:'Maks. 15 kelas', showConfirmButton:false, timer:1500}); return; }
    kelasTags.push(kelas);
    renderKelasTags();
    document.getElementById('inputTambahKelas').value = '';
    document.getElementById('dropKelas').classList.add('hidden');
}

function hapusKelasTag(kelas) {
    kelasTags = kelasTags.filter(k => k !== kelas);
    renderKelasTags();
}

function renderKelasTags() {
    const el = document.getElementById('kelasTags');
    el.innerHTML = kelasTags.map(k => `
        <span class="flex items-center gap-1.5 bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full">
            ${k}
            <button onclick="hapusKelasTag('${k}')" class="text-blue-400 hover:text-red-500 transition">
                                                                                                <i class="fas fa-times text-[10px]">
                                                                                                </i>
                                                                                            </button>
                                                                                        </span>`).join('');
}

async function simpanProfil() {
    const mapelArr = [];
    mapelTags.forEach(m => mapelArr.push(m));

    const payload = {
        nama_panggilan:   document.getElementById('profPanggilan').value.trim(),
        nip:              document.getElementById('profNip').value.trim(),
        no_wa:            document.getElementById('profWa').value.trim(),
        mapel:            JSON.stringify(mapelArr),
        kelas_diajarkan:  JSON.stringify(kelasTags)
    };

    try {
        await callBackendStrict({ type:'supabase_post', action:'updateGuru', payload:{ id: guruData.id, payload }});
        guruData = { ...guruData, ...payload };
        sessionStorage.setItem('ea_guru_session', JSON.stringify(guruData));
        Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Profil berhasil disimpan!', showConfirmButton:false, timer:2000 });
    } catch(e) {
        Swal.fire('Gagal', e.message, 'error');
    }
}

// ═══════════════════════════════════════════════════════════
// DAFTAR SISWA
// ═══════════════════════════════════════════════════════════
let siswaDataAll = [];

// ── State filter periode ──────────────────────────────────────
let _periodeAktif = '1H'; // default 1 Hari
let _absenWaktuInterval = null;

function _getKelasList() {
    const kd = guruData?.kelas_diajarkan;
    if (!kd) return [];
    return typeof kd === 'string' ? (JSON.parse(kd) || []) : kd;
}

async function muatDropKelasFilter() {
    // Isi dropdown semester
    const selSmt = document.getElementById('filterSemesterSiswa');
    if (selSmt && selSmt.options.length <= 1) {
        if (!_cacheSemester.length) {
            try {
                const r = await callBackend({ type:'supabase_get', action:'getData', payload:{ table:'master_semester?order=urutan.desc' }});
                _cacheSemester = r.data || [];
            } catch(e) {}
        }
        const aktif = _cacheSemester.find(s => s.aktif);
        selSmt.innerHTML = '<option value="">— Semua Semester —</option>' +
            _cacheSemester.map(s => `<option value="${s.label}" ${s.label === (aktif?.label||'') ? 'selected' : ''}>${s.label}${s.aktif?' ★':''}</option>`).join('');
    }

    // Isi dropdown kelas
    const sel = document.getElementById('filterKelasSiswa');
    const kelas = _getKelasList();
    sel.innerHTML = '<option value="">— Pilih Kelas —</option>' + kelas.map(k => `<option value="${k}">${k}</option>`).join('');
    if (kelas.length === 1) { sel.value = kelas[0]; loadSiswaKelas(); }
}

function setPeriodeSiswa(kode, btn) {
    _periodeAktif = kode;
    // Update tampilan tombol
    document.querySelectorAll('.periode-btn').forEach(b => {
        b.classList.remove('border-blue-500','bg-blue-500','text-white');
        b.classList.add('border-gray-200','text-gray-600');
    });
    btn.classList.add('border-blue-500','bg-blue-500','text-white');
    btn.classList.remove('border-gray-200','text-gray-600');
    loadSiswaKelas();
}

function _getCutoffDate() {
    const now = new Date();
    if (_periodeAktif === '1H') {
        const d = new Date(now); d.setHours(0,0,0,0); return d;
    }
    if (_periodeAktif === '7H') {
        const d = new Date(now); d.setDate(d.getDate()-7); return d;
    }
    if (_periodeAktif === '1B') {
        const d = new Date(now); d.setDate(d.getDate()-30); return d;
    }
    return null; // 1S = filter semester
}

async function loadSiswaKelas() {
    const kelas   = document.getElementById('filterKelasSiswa').value;
    const semVal  = document.getElementById('filterSemesterSiswa')?.value || '';
    if (!kelas) return;

    const tbody = document.getElementById('siswaTableBody');
    tbody.innerHTML = `<tr>
                                                                                        <td colspan="7" class="text-center py-10">
                                                                                            <i class="fas fa-spinner fa-spin text-blue-400 text-2xl">
                                                                                            </i>
                                                                                        </td>
                                                                                    </tr>`;
    document.getElementById('infoJumlahSiswa').textContent = '';

    try {
        // Query siswa di kelas ini
        const resSiswa = await callBackend({ type:'supabase_get', action:'getData', payload:{ table:`siswa?kelas=eq.${encodeURIComponent(kelas)}&status=not.in.(Diblokir,Alumni)&order=nama.asc` }});
        const siswaList = resSiswa.data || [];

        // Bangun filter absensi berdasarkan periode
        let absenTable = `absensi?kelas=eq.${encodeURIComponent(kelas)}`;
        if (_periodeAktif === 'TGL') {
            const f = _siswaFilter.dari ? _siswaFilter : { dari:_tglWibHariIni(), sampai:_tglWibHariIni() };
            const { awal, akhir } = _rentangWib(f.dari, f.sampai);
            absenTable += `&created_at=gte.${_enc(awal)}&created_at=lte.${_enc(akhir)}`;
        } else if (_periodeAktif === '1S' && semVal) {
            absenTable += `&semester=eq.${encodeURIComponent(semVal)}`;
        } else {
            const cutoff = _getCutoffDate();
            if (cutoff) absenTable += `&created_at=gte.${_enc(cutoff.toISOString())}`;
        }
        absenTable += '&select=id_siswa,jenis,is_late,created_at,nama_siswa&order=created_at.asc';

        const absenAll = await _ambilSemua(absenTable);

        _hariEfektif = await hitungHariEfektif(semVal);
        // Aggregate per siswa
        siswaDataAll = siswaList.map(s => {
            const abs = absenAll.filter(a => a.id_siswa === s.id);
            return {
                ...s, abs,
                h:    abs.filter(a => a.jenis==='Hadir').length,
                i:    abs.filter(a => a.jenis==='Izin').length,
                sakit:abs.filter(a => a.jenis==='Sakit').length,
                a:    abs.filter(a => a.jenis==='Alpa').length,
            };
        });

        renderSiswaTabel(siswaDataAll);
    } catch(e) {
        tbody.innerHTML = `<tr>
                                                                                    <td colspan="7" class="text-center py-8 text-red-400 text-xs">
                                                                                        <i class="fas fa-exclamation-circle mr-1">
                                                                                        </i>${e.message}</td>
                                                                                    </tr>`;
    }
}

function _badgeCentang(jml, warna, kode) {
    if (!jml) return `<span class="text-gray-300 font-black text-base">—</span>`;
    const cls = {
        'h':'text-green-600', 'i':'text-yellow-500', 's':'text-blue-500', 'a':'text-red-500'
    }[warna] || 'text-gray-500';
    const label = {
        'h':`✓ (${jml}x)`, 'i':`IZ (${jml}x)`, 's':`SK (${jml}x)`, 'a':`AL (${jml}x)`
    }[warna] || jml;
    return `<span class="${cls} font-black text-xs" title="${kode} ${jml}x">${jml > 1 ? label : (warna==='h'?'✓':'●')}</span>`;
}

function renderSiswaTabel(data) {
    const tbody = document.getElementById('siswaTableBody');
    const info  = document.getElementById('infoJumlahSiswa');
    if (!data.length) {
        tbody.innerHTML = `<tr>
                                                                                    <td colspan="7" class="text-center py-10 text-gray-400 text-xs">Tidak ada siswa di kelas ini</td>
                                                                                </tr>`;
        if (info) info.textContent = '';
        return;
    }
    if (info) info.textContent = `Menampilkan ${data.length} siswa`;

    tbody.innerHTML = data.map(s => {
        const pr = _predikat(s.total_poin||0);
        return `
        <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                                                                <td class="px-4 py-3">
                                                                                    <p class="font-bold text-gray-800 text-sm">${s.nama}</p>
                ${s.nama_panggilan ? `<p class="text-[10px] text-gray-400">${s.nama_panggilan}</p>` : ''}
            </td>
                                                                                    <td class="px-2 py-3 text-center whitespace-nowrap">${_hariEfektif !== null ? `<span class="font-black text-xs text-green-600">${s.h}/${_hariEfektif}</span>` : _badgeCentang(s.h,'h','Hadir')}<button onclick="detailAbsenSiswa('${s.id}')" title="Detail" class="ml-1.5 text-blue-500 hover:text-blue-700">
                                                                                    <i class="fas fa-list text-[10px]">
                                                                                    </i>
                                                                                </button>
                                                                            </td>
                                                                            <td class="px-2 py-3 text-center">${_badgeCentang(s.i,'i','Izin')}</td>
                                                                            <td class="px-2 py-3 text-center">${_badgeCentang(s.sakit,'s','Sakit')}</td>
                                                                            <td class="px-2 py-3 text-center">${_badgeCentang(s.a,'a','Alpa')}</td>
                                                                            <td class="px-2 py-3 text-center">
                                                                                <span class="font-black text-sm ${(s.total_poin||0)>50?'text-red-600':(s.total_poin||0)>30?'text-orange-600':'text-gray-700'}">${s.total_poin||0}</span>
                                                                            </td>
                                                                            <td class="px-3 py-3 text-center">
                                                                                <span class="text-[10px] font-black px-2 py-1 rounded-full ${pr.bg} ${pr.text}">${pr.label}</span>
                                                                            </td>
                                                                        </tr>`;
    }).join('');
}

function filterSiswa() {
    const q = document.getElementById('searchSiswa').value.toLowerCase();
    const filtered = siswaDataAll.filter(s =>
        (s.nama||'').toLowerCase().includes(q) ||
        (s.nama_panggilan||'').toLowerCase().includes(q) ||
        (s.nisn||'').includes(q)
    );
    renderSiswaTabel(filtered);
}

// ── POPUP ABSEN (hari ini / tanggal tertentu / rentang tanggal) ──
let _absenPerSiswa = {};   // id_siswa -> daftar record absensi pada filter aktif
let _absenNamaSiswa = {};  // id_siswa -> nama

function bukaAbsenHariIni() {
    const kelas = document.getElementById('filterKelasSiswa').value;
    if (!kelas) { Swal.fire({toast:true, position:'top-end', icon:'warning', title:'Pilih kelas dulu', showConfirmButton:false, timer:1500}); return; }

    const modal = document.getElementById('modalAbsenHariIni');
    if (modal) modal.classList.remove('hidden');

    const today = _tglWibHariIni();
    _absenFilter = { mode:'single', dari:today, sampai:today };
    document.getElementById('absenKelasLabel').textContent = kelas;

    // Jam real-time
    if (_absenWaktuInterval) clearInterval(_absenWaktuInterval);
    const elWaktu = document.getElementById('absenHariIniWaktu');
    const tick = () => { if(elWaktu) elWaktu.textContent = new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit',timeZone:'Asia/Jakarta'}) + ' WIB'; };
    tick(); _absenWaktuInterval = setInterval(tick, 1000);

    loadAbsenHariIni();
}

function tutupAbsenHariIni() {
    const modal = document.getElementById('modalAbsenHariIni');
    if (modal) modal.classList.add('hidden');
    if (_absenWaktuInterval) { clearInterval(_absenWaktuInterval); _absenWaktuInterval = null; }
}

async function loadAbsenHariIni() {
    const kelas  = document.getElementById('absenKelasLabel').textContent;
    const f      = _absenFilter;
    const elList = document.getElementById('absenHariIniList');
    const elRing = document.getElementById('absenRingkasan');
    const isRange = f.mode === 'range' && f.dari !== f.sampai;

    document.getElementById('absenFilterLabel').textContent = _labelFilter(f);
    elList.innerHTML = '<div class="text-center py-8 text-gray-400 text-sm">
                                                                        <i class="fas fa-spinner fa-spin text-xl block mb-2">
                                                                        </i>Memuat...</div>';
    elRing.classList.add('hidden');

    try {
        // Siswa di kelas ini
        const resSiswa = await callBackendStrict({ type:'supabase_get', action:'getData', payload:{ table:`siswa?kelas=eq.${encodeURIComponent(kelas)}&status=not.in.(Diblokir,Alumni)&order=nama.asc` }});
        const siswaList = resSiswa.data || [];
        if (!siswaList.length) {
            elList.innerHTML = '<div class="text-center py-6 text-gray-400 text-xs">Tidak ada siswa di kelas ini</div>';
            return;
        }

        // Absensi pada rentang tanggal (WIB). Dicocokkan lewat id_siswa — bukan kolom kelas —
        // supaya record lama yang kolom kelasnya kosong tetap terbaca.
        const { awal, akhir } = _rentangWib(f.dari, f.sampai);
        const ids = siswaList.map(s => s.id).join(',');
        const absenAll = await _ambilSemua(
            `absensi?id_siswa=in.(${ids})&created_at=gte.${_enc(awal)}&created_at=lte.${_enc(akhir)}` +
            `&select=id_siswa,jenis,is_late,created_at,lampiran&order=created_at.asc`
        );

        _absenPerSiswa = {}; _absenNamaSiswa = {};
        absenAll.forEach(a => { (_absenPerSiswa[a.id_siswa] = _absenPerSiswa[a.id_siswa] || []).push(a); });
        siswaList.forEach(s => { _absenNamaSiswa[s.id] = s.nama; });

        const adaLampiran = rows => (rows || []).some(a => a.lampiran && String(a.lampiran).trim());
        const btnLampiran = id => `<button onclick="lihatLampiranSiswa('${id}')" title="Lihat foto / surat" class="flex-shrink-0 w-8 h-8 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 flex items-center justify-center transition">
                                                                        <i class="fas fa-image text-xs">
                                                                        </i>
                                                                    </button>`;

        let h=0, i=0, s=0, a=0, belum=0;

        if (!isRange) {
            // ── MODE SATU HARI: status terakhir per siswa ──
            elList.innerHTML = siswaList.map(sw => {
                const rows = _absenPerSiswa[sw.id] || [];
                const ab = rows[rows.length - 1];
                let badge, badgeCls, waktu;
                if (!ab) { belum++; badge = '--'; badgeCls = 'bg-gray-100 text-gray-400'; waktu = '—'; }
                else if (ab.jenis === 'Hadir') { h++; badge = ab.is_late ? 'TL' : 'OK'; badgeCls = ab.is_late ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700'; waktu = _jamWib(ab.created_at); }
                else if (ab.jenis === 'Izin')  { i++; badge='IZ'; badgeCls='bg-yellow-100 text-yellow-700'; waktu = _jamWib(ab.created_at); }
                else if (ab.jenis === 'Sakit') { s++; badge='SK'; badgeCls='bg-blue-100 text-blue-700';     waktu = _jamWib(ab.created_at); }
                else if (ab.jenis === 'Alpa')  { a++; badge='AL'; badgeCls='bg-red-100 text-red-700';       waktu = _jamWib(ab.created_at); }
                else { badge = String(ab.jenis||'?').substring(0,2); badgeCls='bg-gray-100 text-gray-500'; waktu = _jamWib(ab.created_at); }
                const statusLabel = !ab ? 'Belum absen' : ab.jenis === 'Hadir' ? (ab.is_late ? 'Terlambat' : 'Tepat Waktu') : ab.jenis;
                return `
                <div class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 rounded-xl transition">
                                                                    <span class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center font-black text-xs ${badgeCls}">${badge}</span>
                                                                    <div class="flex-1 min-w-0">
                                                                        <p class="font-bold text-gray-800 text-sm truncate">${sw.nama}</p>
                                                                        <p class="text-[10px] text-gray-400">${statusLabel}${rows.length > 1 ? ' • ' + rows.length + ' catatan' : ''}</p>
                                                                    </div>
                    ${adaLampiran(rows) ? btnLampiran(sw.id) : ''}
                    <span class="text-xs font-bold text-gray-500 flex-shrink-0 w-12 text-right">${waktu}</span>
                                                                </div>`;
            }).join('');
        } else {
            // ── MODE RENTANG TANGGAL: rekap jumlah per siswa ──
            elList.innerHTML = siswaList.map(sw => {
                const rows = _absenPerSiswa[sw.id] || [];
                if (!rows.length) belum++;
                const c = { Hadir:0, Izin:0, Sakit:0, Alpa:0, TL:0 };
                rows.forEach(r => { if (c[r.jenis] !== undefined) c[r.jenis]++; if (r.jenis === 'Hadir' && r.is_late) c.TL++; });
                h += c.Hadir; i += c.Izin; s += c.Sakit; a += c.Alpa;
                const kiri = rows.length ? `<span class="flex-shrink-0 w-10 h-10 rounded-xl flex flex-col items-center justify-center bg-green-100 text-green-700 font-black text-sm leading-none">${c.Hadir}<span class="text-[8px] font-bold mt-0.5">hadir</span>
                                                            </span>`
                                         : `<span class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center bg-gray-100 text-gray-400 font-black text-xs">--</span>`;
                return `
                <div class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 rounded-xl transition">
                    ${kiri}
                    <div class="flex-1 min-w-0">
                                                            <p class="font-bold text-gray-800 text-sm truncate">${sw.nama}</p>
                                                            <p class="text-[10px] text-gray-400">${rows.length ? `Izin ${c.Izin} · Sakit ${c.Sakit} · Alfa ${c.Alpa}${c.TL ? ' · Terlambat ' + c.TL : ''}` : 'Tidak ada catatan'}</p>
                                                        </div>
                    ${adaLampiran(rows) ? btnLampiran(sw.id) : ''}
                </div>`;
            }).join('');
        }

        elRing.classList.remove('hidden');
        document.getElementById('sumH').textContent = h;
        document.getElementById('sumI').textContent = i;
        document.getElementById('sumS').textContent = s;
        document.getElementById('sumA').textContent = a;
        document.getElementById('sumBelum').textContent = belum;

    } catch(e) {
        elList.innerHTML = `<div class="text-center py-6 text-red-400 text-xs">
                                                        <i class="fas fa-exclamation-circle mr-1">
                                                        </i>Gagal memuat absensi: ${e.message}</div>`;
    }
}

// Foto selfie / surat sakit-izin milik satu siswa pada filter aktif
function lihatLampiranSiswa(id) {
    const rows = (_absenPerSiswa[id] || []).filter(r => r.lampiran && String(r.lampiran).trim());
    if (!rows.length) return;
    const nama = _absenNamaSiswa[id] || 'Siswa';
    const kartu = rows.map(r => {
        const surat = (r.jenis === 'Sakit' || r.jenis === 'Izin');
        const tgl = new Date(r.created_at).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric', timeZone:'Asia/Jakarta' });
        return `<a href="${r.lampiran}" target="_blank" rel="noopener" class="block text-left">
                                                        <img src="${r.lampiran}" class="w-full h-40 object-cover rounded-xl border-2 ${surat ? 'border-amber-300' : 'border-purple-200'}" onerror="this.style.display='none'">
                                                        <p class="text-[11px] font-black mt-1 ${surat ? 'text-amber-600' : 'text-purple-600'}">${surat ? 'Surat ' + r.jenis : 'Selfie Hadir'}</p>
                                                        <p class="text-[10px] text-gray-400">${tgl} • ${_jamWib(r.created_at)} WIB</p>
                                                    </a>`;
    }).join('');
    Swal.fire({
        title: `<span class="text-base font-black">${nama}</span>`,
        html: `<div class="grid grid-cols-2 gap-3 max-h-[60vh] overflow-y-auto">${kartu}</div>
                                                    <p class="text-[10px] text-gray-400 mt-3">Ketuk foto untuk membuka ukuran penuh</p>`,
        showConfirmButton: false, showCloseButton: true, width: 520
    });
}

// ═══════════════════════════════════════════════════════════
// LAPORKAN SISWA
// ═══════════════════════════════════════════════════════════
let siswaCacheDropLapor = [];
siswaLaporList = [];

function muatFormLaporan() {
    // Set tanggal hari ini
    document.getElementById('laporTanggal').value = new Date().toISOString().split('T')[0];
    // Set waktu sekarang
    const now = new Date();
    document.getElementById('laporJam').value = `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;
    // Isi dropdown mapel dari master DB (bukan dari profil teks bebas)
    muatDropdownMapel();
    // Reset siswa list dan form pelanggaran
    siswaLaporList = [];
    renderTagsSiswaLapor();
    document.querySelectorAll('input[name="jenisLaporan"]').forEach(r => r.checked = false);
    document.getElementById('infoMasterPelanggaran')?.classList.add('hidden');
    document.getElementById('idMasterPelanggaran').value = '';
    document.getElementById('poinMasterPelanggaran').value = '';
}

async function cariSiswaLapor(val) {
    const drop = document.getElementById('dropSiswaLapor');
    if (!val || val.length < 2) { drop.classList.add('hidden'); return; }
    try {
        const res = await callBackend({ type:'supabase_get', action:'cariSiswa', payload:{ nama: val }});
        siswaCacheDropLapor = res.data || [];
        drop.innerHTML = siswaCacheDropLapor.map((s,i) => `
            <li onclick="pilihSiswaLapor(${i})" class="px-4 py-2.5 hover:bg-blue-50 cursor-pointer transition">
                                                    <p class="font-bold text-gray-800 text-sm">${s.nama}</p>
                                                    <p class="text-[10px] text-gray-400">${s.kelas||''} ${s.nama_panggilan ? '· '+s.nama_panggilan : ''}</p>
                                                </li>`).join('') || '<li class="px-4 py-3 text-xs text-gray-400">Tidak ditemukan</li>';
        drop.classList.remove('hidden');
    } catch(e) {}
}

function pilihSiswaLapor(i) {
    const s = siswaCacheDropLapor[i]; if (!s) return;
    if (siswaLaporList.find(x => x.id === s.id)) {
        Swal.fire({toast:true, position:'top-end', icon:'info', title:'Siswa sudah dipilih', showConfirmButton:false, timer:1200});
        return;
    }
    siswaLaporList.push(s);
    renderTagsSiswaLapor();
    document.getElementById('inputCariSiswaLapor').value = '';
    document.getElementById('dropSiswaLapor').classList.add('hidden');
}

function hapusSiswaLapor(id) {
    siswaLaporList = siswaLaporList.filter(s => s.id !== id);
    renderTagsSiswaLapor();
}

function renderTagsSiswaLapor() {
    const el = document.getElementById('tagsSiswaLapor');
    el.innerHTML = siswaLaporList.map(s => `
        <span class="flex items-center gap-1.5 bg-red-100 text-red-700 text-xs font-bold px-3 py-1.5 rounded-full">
            ${s.nama}
            <button onclick="hapusSiswaLapor('${s.id}')" class="text-red-400 hover:text-red-600">
                                                <i class="fas fa-times text-[10px]">
                                                </i>
                                            </button>
                                        </span>`).join('');
}

async function kirimLaporanSiswa() {
    const tgl   = document.getElementById('laporTanggal').value;
    const mapel = document.getElementById('laporMapel').value;
    const jam   = document.getElementById('laporJam').value;
    const jenis = document.querySelector('input[name="jenisLaporan"]:checked')?.value;
    const ket   = document.getElementById('laporKeterangan').value.trim();

    if (!tgl)   return Swal.fire('Lengkapi Form', 'Pilih tanggal kejadian.', 'warning');
    if (!mapel) return Swal.fire('Lengkapi Form', 'Pilih mata pelajaran.', 'warning');
    if (!jenis) return Swal.fire('Lengkapi Form', 'Pilih jenis pelanggaran.', 'warning');
    if (!siswaLaporList.length) return Swal.fire('Pilih Siswa', 'Tambahkan minimal 1 siswa yang dilaporkan.', 'warning');

    // Tentukan poin dan label — bisa dari hardcode ATAU dari master pelanggaran
    let poin, label, idMaster = null;
    if (jenis === 'master_pelanggaran') {
        idMaster = document.getElementById('idMasterPelanggaran').value;
        poin     = parseInt(document.getElementById('poinMasterPelanggaran').value) || 0;
        label    = document.getElementById('namaLainnya').textContent;
        if (!idMaster) return Swal.fire('Pilih Pelanggaran', 'Klik "Pelanggaran Lainnya" dan pilih dari daftar.', 'warning');
    } else {
        poin  = POIN_LAPORAN[jenis]  || 0;
        label = LABEL_LAPORAN[jenis] || jenis;
    }

    // Ambil semester aktif
    const semAktif = _cacheSemester.find(s => s.aktif)?.label || _semesterDipilih || '';

    const namaSiswa = siswaLaporList.map(s => s.nama).join(', ');
    const konfirm = await Swal.fire({
        title: 'Konfirmasi Laporan',
        html: `<div class="text-left text-sm space-y-1.5">
                                        <p>📅 <b>Tanggal:</b> ${tgl}</p>
                                        <p>📚 <b>Mapel:</b> ${mapel}</p>
                                        <p>⏰ <b>Jam:</b> ${jam||'—'}</p>
                                        <p>⚠️ <b>Pelanggaran:</b> ${label}</p>
                                        <p>👥 <b>Siswa:</b> ${namaSiswa}</p>
                                        <p>🔴 <b>Poin per siswa:</b> +${poin} poin</p>
            ${semAktif ? `<p>📆 <b>Semester:</b> ${semAktif}</p>` : ''}
        </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Ya, Kirim Laporan',
        cancelButtonText: 'Batal'
    });
    if (!konfirm.isConfirmed) return;

    Swal.fire({ title: 'Mengirim laporan...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });

    try {
        for (const siswa of siswaLaporList) {
            await callBackend({ type:'supabase_post', action:'insertLaporanGuru', payload:{
                payload: {
                    id_guru:                guruData.id,
                    nama_guru:              guruData.nama_lengkap,
                    id_siswa:               siswa.id,
                    nama_siswa:             siswa.nama,
                    kelas_siswa:            siswa.kelas || '',
                    mata_pelajaran:         mapel,
                    tanggal:                tgl,
                    jam,
                    jenis,
                    label_jenis:            label,
                    keterangan:             ket,
                    poin_siswa:             poin,
                    id_pelanggaran_master:  idMaster,
                    semester:               semAktif,
                    status:                 'Selesai'   // langsung selesai, poin otomatis masuk
                }
            }});
            await callBackend({ type:'supabase_post', action:'insertLog', payload:{
                aktor: guruData.nama_lengkap, role:'Guru',
                aksi: 'Laporan Siswa',
                detail: `${label} — ${siswa.nama} — +${poin} poin${semAktif?' — '+semAktif:''}`
            }});
        }

        Swal.fire({
            icon: 'success', title: 'Laporan Terkirim!',
            html: `<p>Laporan untuk <b>${siswaLaporList.length} siswa</b> berhasil dikirim.<br>Masing-masing mendapat <b>+${poin} poin</b> pelanggaran.</p>`,
            confirmButtonColor: '#2563eb'
        });

        // Reset form
        siswaLaporList = [];
        renderTagsSiswaLapor();
        document.querySelectorAll('input[name="jenisLaporan"]').forEach(r => r.checked = false);
        document.getElementById('laporKeterangan').value = '';
        document.getElementById('infoMasterPelanggaran')?.classList.add('hidden');
        document.getElementById('idMasterPelanggaran').value = '';
        document.getElementById('poinMasterPelanggaran').value = '';
        loadLaporanTerbaru();

    } catch(e) {
        Swal.fire('Gagal', e.message, 'error');
    }
}

// ═══════════════════════════════════════════════════════════
// KIRIM MASUKAN
// ═══════════════════════════════════════════════════════════
async function kirimMasukan() {
    const kategori = document.getElementById('masukanKategori').value;
    const teks     = document.getElementById('masukanTeks').value.trim();
    if (!teks) { Swal.fire('Kosong', 'Tulis isi masukan terlebih dahulu.', 'warning'); return; }

    const konfirm = await Swal.fire({ title:'Kirim Masukan?', text:'Masukan akan diteruskan ke admin.', icon:'question', showCancelButton:true, confirmButtonColor:'#2563eb', confirmButtonText:'Ya, Kirim', cancelButtonText:'Batal' });
    if (!konfirm.isConfirmed) return;

    try {
        await callBackend({ type:'supabase_post', action:'insertData', payload:{
            table: 'aspirasi',
            payload: { tipe:'masukan', sub_tipe:'masukan_guru', pengirim_nama: guruData.nama_lengkap, pengirim_wa: guruData.no_wa||'', kategori, detail_pesan: teks, status:'Menunggu' }
        }});
        Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Masukan terkirim!', showConfirmButton:false, timer:2000 });
        document.getElementById('masukanTeks').value = '';
    } catch(e) { Swal.fire('Gagal', e.message, 'error'); }
}

// ═══════════════════════════════════════════════════════════
// RIWAYAT LAPORAN GURU
// ═══════════════════════════════════════════════════════════
let _cacheRiwayatLaporan = []; // cache untuk akses data saat batalkan

async function muatRiwayatLaporan() {
    const el = document.getElementById('riwayatLaporanList');
    if (!el) return;
    el.innerHTML = '<div class="text-center py-10 text-gray-400 text-sm">
                                        <i class="fas fa-spinner fa-spin text-2xl mb-2 block">
                                        </i>Memuat riwayat...</div>';
    try {
        const res = await callBackend({ type:'supabase_get', action:'getData', payload:{ table:`laporan_guru?id_guru=eq.${guruData.id}&order=created_at.desc` }});
        const data = res.data || [];
        _cacheRiwayatLaporan = data; // simpan ke cache
        if (!data.length) {
            el.innerHTML = '<div class="text-center py-12 text-gray-400">
                                        <i class="fas fa-folder-open text-4xl mb-3 block">
                                        </i>
                                        <p class="text-sm">Belum ada laporan yang dikirim.</p>
                                    </div>';
            return;
        }
        el.innerHTML = data.map(d => {
            // Hanya 2 status: Selesai (poin sudah masuk) atau Dibatalkan (poin dikembalikan)
            const stColor = d.status === 'Dibatalkan' ? 'bg-gray-100 text-gray-500' : 'bg-green-100 text-green-700';
            const stLabel = d.status === 'Dibatalkan' ? 'Dibatalkan' : 'Selesai';
            const bisa_batal = d.status !== 'Dibatalkan'; // Selesai masih bisa dibatalkan
            const tgl = d.created_at ? new Date(d.created_at).toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric'}) : '';
            return `
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 space-y-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1">
                                            <p class="font-black text-gray-800 text-sm">${d.nama_siswa||'-'} <span class="text-gray-400 font-normal text-xs">• ${d.kelas_siswa||''}</span>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">${d.label_jenis||d.jenis||'-'} — ${d.mata_pelajaran||''}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">📅 ${d.tanggal||tgl} ${d.jam ? '⏰ '+d.jam : ''}</p>
                                        <p class="text-xs font-black text-red-500 mt-0.5">+${d.poin_siswa||0} poin</p>
                        ${d.keterangan ? `<p class="text-xs text-gray-500 mt-1 italic">"${d.keterangan}"</p>` : ''}
                        ${d.alasan_batal ? `<p class="text-xs text-red-400 mt-1">
                                        <i class="fas fa-undo mr-1">
                                        </i>Dibatalkan: ${d.alasan_batal}</p>` : ''}
                    </div>
                                        <span class="text-[10px] font-black px-2.5 py-1 rounded-full flex-shrink-0 ${stColor}">${stLabel}</span>
                                    </div>
                ${bisa_batal ? `
                <button onclick="batalkanLaporan('${d.id}')"
                    class="w-full text-xs font-black text-red-500 border-2 border-red-200 hover:bg-red-50 py-2 rounded-xl transition active:scale-95">
                                    <i class="fas fa-times-circle mr-1">
                                    </i>Batalkan Laporan
                </button>` : ''}
            </div>`;
        }).join('');
    } catch(e) {
        el.innerHTML = `<div class="text-center py-8 text-red-400 text-sm">
                                    <i class="fas fa-exclamation-circle text-2xl mb-2 block">
                                    </i>${e.message}</div>`;
    }
}

async function batalkanLaporan(idLaporan) {
    const ALASAN_OPSI = [
        { val: 'Siswa masuk kembali di kelas', label: '👨‍🎓 Siswa masuk kembali di kelas' },
        { val: 'Salah mengirim laporan', label: '✏️ Salah mengirim laporan' },
        { val: 'Ingin membuat laporan baru', label: '📝 Ingin membuat laporan baru' },
        { val: 'Lainnya', label: '💬 Lainnya (tulis manual)' }
    ];

    const { value: pilihan, isConfirmed } = await Swal.fire({
        title: 'Batalkan Laporan?',
        html: `
            <p class="text-sm text-gray-500 mb-4">Pilih alasan pembatalan laporan ini:</p>
                                    <div class="space-y-2 text-left">
                ${ALASAN_OPSI.map((o,i) => `
                <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-400 transition">
                                    <input type="radio" name="alasanBatal" value="${o.val}" class="text-red-500" ${i===0?'checked':''}>
                                    <span class="text-sm font-bold text-gray-700">${o.label}</span>
                                </label>`).join('')}
            </div>
                                <div id="alasanManualWrap" class="hidden mt-3">
                                    <textarea id="alasanManual" rows="2" placeholder="Tulis alasan pembatalan..."
                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-red-400 transition resize-none">
                                    </textarea>
                                </div>`,
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tidak Jadi',
        didOpen: () => {
            document.querySelectorAll('input[name="alasanBatal"]').forEach(r => {
                r.addEventListener('change', () => {
                    const wrap = document.getElementById('alasanManualWrap');
                    if (wrap) wrap.classList.toggle('hidden', r.value !== 'Lainnya');
                });
            });
        },
        preConfirm: () => {
            const radio = document.querySelector('input[name="alasanBatal"]:checked');
            if (!radio) { Swal.showValidationMessage('Pilih alasan pembatalan'); return false; }
            if (radio.value === 'Lainnya') {
                const manual = document.getElementById('alasanManual')?.value.trim();
                if (!manual) { Swal.showValidationMessage('Tulis alasan pembatalan'); return false; }
                return manual;
            }
            return radio.value;
        }
    });

    if (!isConfirmed || !pilihan) return;

    // Ambil data laporan dari cache untuk tahu poin yang harus dikembalikan
    const dataLaporan = _cacheRiwayatLaporan.find(d => d.id === idLaporan);

    try {
        Swal.fire({ title:'Membatalkan & mengembalikan poin...', didOpen:()=>Swal.showLoading(), allowOutsideClick:false });
        await callBackend({ type:'supabase_post', action:'batalLaporanGuru', payload:{
            id:          idLaporan,
            alasan_batal: pilihan,
            id_siswa:    dataLaporan?.id_siswa || null,
            poin_siswa:  dataLaporan?.poin_siswa || 0
        }});
        await callBackend({ type:'supabase_post', action:'insertLog', payload:{
            aktor: guruData.nama_lengkap, role:'Guru',
            aksi: 'Batalkan Laporan',
            detail: `Laporan ${dataLaporan?.nama_siswa||idLaporan} dibatalkan. Alasan: ${pilihan}. Poin -${dataLaporan?.poin_siswa||0} dikembalikan.`
        }});
        Swal.fire({ icon:'success', title:'Laporan Dibatalkan', text:`Poin +${dataLaporan?.poin_siswa||0} telah dikembalikan ke siswa.`, confirmButtonColor:'#2563eb' });
        muatRiwayatLaporan();
    } catch(e) {
        Swal.fire('Gagal', e.message, 'error');
    }
}

// ═══════════════════════════════════════════════════════════
// RIWAYAT MASUKAN GURU
// ═══════════════════════════════════════════════════════════
async function muatRiwayatMasukan() {
    const el = document.getElementById('riwayatMasukanList');
    if (!el) return;
    el.innerHTML = '<div class="text-center py-10 text-gray-400 text-sm">
                                <i class="fas fa-spinner fa-spin text-2xl mb-2 block">
                                </i>Memuat riwayat...</div>';
    try {
        const res = await callBackend({ type:'supabase_get', action:'getData', payload:{
            table:`aspirasi?pengirim_nama=eq.${encodeURIComponent(guruData.nama_lengkap)}&sub_tipe=eq.masukan_guru&order=created_at.desc`
        }});
        const data = res.data || [];
        if (!data.length) {
            el.innerHTML = '<div class="text-center py-12 text-gray-400">
                                <i class="fas fa-folder-open text-4xl mb-3 block">
                                </i>
                                <p class="text-sm">Belum ada masukan yang dikirim.</p>
                            </div>';
            return;
        }
        el.innerHTML = data.map(d => {
            const stColor = d.status === 'Selesai' ? 'bg-green-100 text-green-700' :
                            d.status === 'Dibatalkan' ? 'bg-gray-100 text-gray-500' :
                            d.status === 'Sedang Diproses' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700';
            const bisa_batal = !['Selesai','Dibatalkan'].includes(d.status);
            const tgl = d.created_at ? new Date(d.created_at).toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'}) : '-';
            return `
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] font-black bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">${d.kategori||'Masukan'}</span>
                                        <span class="text-[10px] text-gray-400">${tgl}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">${d.detail_pesan||''}</p>
                        ${d.alasan_batal ? `<p class="text-xs text-red-400 mt-1">Alasan batal: ${d.alasan_batal}</p>` : ''}
                    </div>
                                    <span class="text-[10px] font-black px-2.5 py-1 rounded-full flex-shrink-0 ${stColor}">${d.status||'Menunggu'}</span>
                                </div>
                ${bisa_batal ? `
                <button onclick="batalkanMasukan('${d.id}')"
                    class="w-full text-xs font-black text-red-500 border-2 border-red-200 hover:bg-red-50 py-2 rounded-xl transition active:scale-95 mt-1">
                                <i class="fas fa-times-circle mr-1">
                                </i>Batalkan Masukan
                </button>` : ''}
            </div>`;
        }).join('');
    } catch(e) {
        el.innerHTML = `<div class="text-center py-8 text-red-400 text-sm">
                                <i class="fas fa-exclamation-circle text-2xl mb-2 block">
                                </i>${e.message}</div>`;
    }
}

async function batalkanMasukan(idMasukan) {
    const ALASAN_OPSI = [
        { val: 'Salah mengirim masukan', label: '✏️ Salah mengirim masukan' },
        { val: 'Ingin membuat masukan baru', label: '📝 Ingin membuat masukan baru' },
        { val: 'Masukan sudah tidak relevan', label: '🔄 Masukan sudah tidak relevan' },
        { val: 'Lainnya', label: '💬 Lainnya (tulis manual)' }
    ];

    const { value: pilihan, isConfirmed } = await Swal.fire({
        title: 'Batalkan Masukan?',
        html: `
            <p class="text-sm text-gray-500 mb-4">Pilih alasan pembatalan masukan ini:</p>
                                <div class="space-y-2 text-left">
                ${ALASAN_OPSI.map((o,i) => `
                <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-400 transition">
                                <input type="radio" name="alasanBatalMasukan" value="${o.val}" class="text-red-500" ${i===0?'checked':''}>
                                <span class="text-sm font-bold text-gray-700">${o.label}</span>
                            </label>`).join('')}
            </div>
                            <div id="alasanManualMasukanWrap" class="hidden mt-3">
                                <textarea id="alasanManualMasukan" rows="2" placeholder="Tulis alasan pembatalan..."
                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-red-400 transition resize-none">
                                </textarea>
                            </div>`,
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tidak Jadi',
        didOpen: () => {
            document.querySelectorAll('input[name="alasanBatalMasukan"]').forEach(r => {
                r.addEventListener('change', () => {
                    const wrap = document.getElementById('alasanManualMasukanWrap');
                    if (wrap) wrap.classList.toggle('hidden', r.value !== 'Lainnya');
                });
            });
        },
        preConfirm: () => {
            const radio = document.querySelector('input[name="alasanBatalMasukan"]:checked');
            if (!radio) { Swal.showValidationMessage('Pilih alasan pembatalan'); return false; }
            if (radio.value === 'Lainnya') {
                const manual = document.getElementById('alasanManualMasukan')?.value.trim();
                if (!manual) { Swal.showValidationMessage('Tulis alasan pembatalan'); return false; }
                return manual;
            }
            return radio.value;
        }
    });

    if (!isConfirmed || !pilihan) return;

    try {
        Swal.fire({ title:'Membatalkan...', didOpen:()=>Swal.showLoading(), allowOutsideClick:false });
        await callBackend({ type:'supabase_post', action:'updateData', payload:{
            table:'aspirasi', id: idMasukan,
            payload: { status:'Dibatalkan', alasan_batal: pilihan }
        }});
        await callBackend({ type:'supabase_post', action:'insertLog', payload:{
            aktor: guruData.nama_lengkap, role:'Guru',
            aksi: 'Batalkan Masukan',
            detail: `Masukan ID ${idMasukan} dibatalkan. Alasan: ${pilihan}`
        }});
        Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Masukan dibatalkan', showConfirmButton:false, timer:2000 });
        muatRiwayatMasukan();
    } catch(e) {
        Swal.fire('Gagal', e.message, 'error');
    }
}

// ═══════════════════════════════════════════════════════════
// MASTER MAPEL — Isi dropdown dari DB
// ═══════════════════════════════════════════════════════════
let _cacheMapel = [];

async function muatDropdownMapel() {
    const sel = document.getElementById('laporMapel');
    if (!sel) return;
    try {
        if (!_cacheMapel.length) {
            const res = await callBackend({ type:'supabase_get', action:'getData', payload:{ table:'master_mapel?aktif=eq.true&order=nama_mapel.asc' }});
            _cacheMapel = res.data || [];
        }
        // Isi dari mapel yang diajar guru (jika ada), sisanya tambahkan setelah
        const mapelGuru = (() => { try { const m = guruData?.mapel; return typeof m === 'string' ? JSON.parse(m || '[]') : (m || []); } catch(e) { return []; } })();
        const opsiGuru = _cacheMapel.filter(m => {
            if (Array.isArray(mapelGuru)) {
                return mapelGuru.includes(m.id) || mapelGuru.includes(m.nama_mapel);
            }
            return false;
        });
        const opsiLain = _cacheMapel.filter(m => !opsiGuru.find(g => g.id === m.id));

        sel.innerHTML = '<option value="">— Pilih Mata Pelajaran —</option>';
        if (opsiGuru.length) {
            const grpGuru = document.createElement('optgroup');
            grpGuru.label = '📚 Mapel Saya';
            opsiGuru.forEach(m => { const o = document.createElement('option'); o.value = m.nama_mapel; o.textContent = m.nama_mapel; grpGuru.appendChild(o); });
            sel.appendChild(grpGuru);
        }
        if (opsiLain.length) {
            const grpLain = document.createElement('optgroup');
            grpLain.label = '— Mapel Lainnya —';
            opsiLain.forEach(m => { const o = document.createElement('option'); o.value = m.nama_mapel; o.textContent = m.nama_mapel; grpLain.appendChild(o); });
            sel.appendChild(grpLain);
        }
    } catch(e) {
        sel.innerHTML = '<option value="">— Gagal load, ketik manual —</option>';
    }
}

// ═══════════════════════════════════════════════════════════
// MASTER PELANGGARAN — Popup pilih dari DB
// ═══════════════════════════════════════════════════════════
let _cachePelanggaran = [];

async function bukaPilihMasterPelanggaran() {
    const modal = document.getElementById('modalMasterPelanggaran');
    if (modal) modal.classList.remove('hidden');
    document.getElementById('radioMasterPelanggaran').checked = true;
    if (!_cachePelanggaran.length) {
        try {
            const res = await callBackend({ type:'supabase_get', action:'getData', payload:{ table:'master_pelanggaran?order=kategori.asc,kelakuan.asc' }});
            _cachePelanggaran = res.data || [];
        } catch(e) { _cachePelanggaran = []; }
    }
    renderListPelanggaran(_cachePelanggaran);
}

function tutupPilihMasterPelanggaran() {
    const modal = document.getElementById('modalMasterPelanggaran');
    if (modal) modal.classList.add('hidden');
}

function filterMasterPelanggaran(q) {
    const filtered = q.trim()
        ? _cachePelanggaran.filter(p => (p.kelakuan||'').toLowerCase().includes(q.toLowerCase()) || (p.kategori||'').toLowerCase().includes(q.toLowerCase()))
        : _cachePelanggaran;
    renderListPelanggaran(filtered);
}

function renderListPelanggaran(list) {
    const el = document.getElementById('listMasterPelanggaran');
    if (!list.length) { el.innerHTML = '<p class="text-center text-gray-400 text-sm py-4">Tidak ditemukan</p>'; return; }
    // Group by kategori
    const grouped = {};
    list.forEach(p => { const k = p.kategori||'Lainnya'; if (!grouped[k]) grouped[k] = []; grouped[k].push(p); });
    el.innerHTML = Object.entries(grouped).map(([kat, items]) => `
        <div class="mb-3">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">${kat}</p>
                            <div class="space-y-1.5">
                ${items.map(p => `
                <button onclick="pilihMasterPelanggaran('${p.id}','${String(p.kelakuan||'').replace(/'/g,"\\'")}',${p.point||0})"
                    class="w-full text-left flex items-center justify-between px-3 py-2.5 bg-gray-50 hover:bg-indigo-50 hover:border-indigo-300 border-2 border-gray-100 rounded-xl transition">
                            <span class="text-sm font-bold text-gray-800">${p.kelakuan||'-'}</span>
                            <span class="text-xs font-black text-red-500 flex-shrink-0 ml-2">+${p.point||0} poin</span>
                        </button>`).join('')}
            </div>
                    </div>`).join('');
}

function pilihMasterPelanggaran(id, nama, poin) {
    document.getElementById('idMasterPelanggaran').value  = id;
    document.getElementById('poinMasterPelanggaran').value = poin;
    document.getElementById('namaLainnya').textContent = nama;
    document.getElementById('poinLainnya').textContent = `+${poin} poin pelanggaran`;
    document.getElementById('infoMasterPelanggaran').classList.remove('hidden');
    document.getElementById('radioMasterPelanggaran').checked = true;
    tutupPilihMasterPelanggaran();
}

// ═══════════════════════════════════════════════════════════
// REKAP SEMESTER
// ═══════════════════════════════════════════════════════════
let _cacheSemester = [];
let _semesterDipilih = '';

async function muatMenuRekapSemester() {
    // Load semester list
    if (!_cacheSemester.length) {
        try {
            const res = await callBackend({ type:'supabase_get', action:'getData', payload:{ table:'master_semester?order=urutan.desc' }});
            _cacheSemester = res.data || [];
        } catch(e) { _cacheSemester = []; }
    }
    const aktif = _cacheSemester.find(s => s.aktif);
    _semesterDipilih = aktif?.label || _cacheSemester[0]?.label || '';

    const el = document.getElementById('rekapSemesterContent');
    if (!el) return;

    const opsiSmt = _cacheSemester.map(s =>
        `<option value="${s.label}" ${s.label === _semesterDipilih ? 'selected' : ''}>${s.label}${s.aktif ? ' ★' : ''}</option>`
    ).join('');

    el.innerHTML = `
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 space-y-4 mb-4">
                    <div class="flex gap-3 items-end">
                        <div class="flex-1">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Semester</label>
                            <select id="pilihanSemester" onchange="_semesterDipilih=this.value"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 bg-white">
                        ${opsiSmt}
                    </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Tampilan</label>
                            <select id="pilihanOpsiRekap"
                        class="px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 bg-white">
                                <option value="kelas">Per Kelas</option>
                                <option value="orang">Per Orang</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="tampilRekap()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-3 rounded-xl transition text-sm active:scale-95">
                            <i class="fas fa-chart-bar mr-2">
                            </i>Tampilkan Rekap
                </button>
                            <button onclick="exportRekapExcel()" class="bg-green-600 hover:bg-green-700 text-white font-black px-4 py-3 rounded-xl transition text-sm active:scale-95">
                                <i class="fas fa-file-excel">
                                </i>
                            </button>
                        </div>
                    </div>
                    <div id="hasilRekap" class="space-y-3">
                    </div>`;
}

function _predikat(poin) {
    if (poin <= 10) return { label:'Baik', bg:'bg-green-100', text:'text-green-700' };
    if (poin <= 30) return { label:'Cukup', bg:'bg-yellow-100', text:'text-yellow-700' };
    if (poin <= 50) return { label:'Perlu Perhatian', bg:'bg-orange-100', text:'text-orange-700' };
    return { label:'Bermasalah', bg:'bg-red-100', text:'text-red-700' };
}

async function tampilRekap() {
    const opsi = document.getElementById('pilihanOpsiRekap').value;
    const sem  = _semesterDipilih;
    const el   = document.getElementById('hasilRekap');
    if (!el) return;
    el.innerHTML = '<div class="text-center py-8 text-gray-400">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2 block">
                    </i>Memuat rekap...</div>';

    try {
        if (opsi === 'kelas') {
            // Pilih kelas dari kelas yang diajar guru
            const kelasList = guruData?.kelas_diajarkan
                ? (typeof guruData.kelas_diajarkan === 'string' ? JSON.parse(guruData.kelas_diajarkan) : guruData.kelas_diajarkan)
                : [];
            if (!kelasList.length) { el.innerHTML = '<p class="text-center text-gray-400 text-sm py-4">Tidak ada kelas yang diajarkan.</p>'; return; }

            // Pilih kelas dulu
            const { value: kelasDipilih } = await Swal.fire({
                title: 'Pilih Kelas',
                input: 'select',
                inputOptions: Object.fromEntries(kelasList.map(k => [k, k])),
                inputPlaceholder: '-- Pilih Kelas --',
                showCancelButton: true
            });
            if (!kelasDipilih) { el.innerHTML = ''; return; }

            const [resAbsen, resPelanggaran] = await Promise.all([
                callBackend({ type:'supabase_get', action:'getData', payload:{ table:`absensi?kelas=eq.${encodeURIComponent(kelasDipilih)}&semester=eq.${encodeURIComponent(sem)}&select=id_siswa,nama_siswa,jenis,is_late` }}),
                callBackend({ type:'supabase_get', action:'getData', payload:{ table:`laporan_guru?kelas_siswa=eq.${encodeURIComponent(kelasDipilih)}&semester=eq.${encodeURIComponent(sem)}&status=eq.Selesai&select=id_siswa,nama_siswa,poin_siswa` }})
            ]);

            const absen = resAbsen.data || [];
            const pelanggaran = resPelanggaran.data || [];

            // Aggregate per siswa
            const agg = {};
            absen.forEach(r => {
                if (!agg[r.id_siswa]) agg[r.id_siswa] = { nama: r.nama_siswa, h:0, i:0, s:0, a:0, poin:0 };
                if (r.jenis==='Hadir') agg[r.id_siswa].h++;
                else if (r.jenis==='Izin') agg[r.id_siswa].i++;
                else if (r.jenis==='Sakit') agg[r.id_siswa].s++;
                else if (r.jenis==='Alpa') agg[r.id_siswa].a++;
            });
            pelanggaran.forEach(r => {
                if (!agg[r.id_siswa]) agg[r.id_siswa] = { nama: r.nama_siswa, h:0, i:0, s:0, a:0, poin:0 };
                agg[r.id_siswa].poin += r.poin_siswa||0;
            });

            const rows = Object.values(agg).sort((a,b) => a.nama.localeCompare(b.nama));
            const totalH = rows.reduce((s,r)=>s+r.h,0);
            const totalA = rows.reduce((s,r)=>s+r.a,0);
            const bermasalah = rows.filter(r => r.poin > 50).length;

            el.innerHTML = `
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-3 grid grid-cols-3 gap-3 text-center">
                    <div>
                        <p class="text-2xl font-black text-blue-700">${rows.length}</p>
                        <p class="text-[10px] text-gray-500 uppercase font-bold">Total Siswa</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-green-600">${totalH}</p>
                        <p class="text-[10px] text-gray-500 uppercase font-bold">Total Hadir</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-red-600">${bermasalah}</p>
                        <p class="text-[10px] text-gray-500 uppercase font-bold">Bermasalah</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left font-black text-xs text-gray-500 uppercase">Nama</th>
                                    <th class="px-3 py-3 text-center font-black text-xs text-green-500">✓</th>
                                    <th class="px-3 py-3 text-center font-black text-xs text-yellow-500">IZ</th>
                                    <th class="px-3 py-3 text-center font-black text-xs text-blue-500">SK</th>
                                    <th class="px-3 py-3 text-center font-black text-xs text-red-500">AL</th>
                                    <th class="px-3 py-3 text-center font-black text-xs text-gray-500">Poin</th>
                                    <th class="px-4 py-3 text-center font-black text-xs text-gray-500">Predikat</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rows.map(r => {
                                    const pr = _predikat(r.poin);
                                    return `<tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold text-gray-800 text-xs">${r.nama}</td>
                            <td class="px-3 py-3 text-center">${r.h ? `<span class="text-green-600 font-black text-xs" title="Hadir ${r.h}x">✓</span>` : '<span class="text-gray-300">—</span>'}</td>
                            <td class="px-3 py-3 text-center">${r.i ? `<span class="text-yellow-500 font-black text-xs" title="Izin ${r.i}x">IZ</span>` : '<span class="text-gray-300">—</span>'}</td>
                            <td class="px-3 py-3 text-center">${r.s ? `<span class="text-blue-500 font-black text-xs" title="Sakit ${r.s}x">SK</span>` : '<span class="text-gray-300">—</span>'}</td>
                            <td class="px-3 py-3 text-center">${r.a ? `<span class="text-red-500 font-black text-xs" title="Alpa ${r.a}x">AL</span>` : '<span class="text-gray-300">—</span>'}</td>
                            <td class="px-3 py-3 text-center">
                                <span class="font-black text-sm ${r.poin > 50 ? 'text-red-600' : r.poin > 30 ? 'text-orange-600' : 'text-gray-700'}">${r.poin}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-[10px] font-black px-2 py-1 rounded-full ${pr.bg} ${pr.text}">${pr.label}</span>
                            </td>
                        </tr>`;
                                }).join('')}
                            </tbody>
                    </table>
                </div>
            </div>`;

        } else {
            // Per orang — cari siswa dulu
            const { value: namaSiswa } = await Swal.fire({
                title: 'Cari Siswa',
                input: 'text',
                inputPlaceholder: 'Ketik nama siswa...',
                showCancelButton: true
            });
            if (!namaSiswa) { el.innerHTML = ''; return; }

            const [resAbsen, resPelanggaran, resRiwayatKelas] = await Promise.all([
                callBackend({ type:'supabase_get', action:'getData', payload:{ table:`absensi?nama_siswa=ilike.*${encodeURIComponent(namaSiswa)}*&semester=eq.${encodeURIComponent(sem)}&order=created_at.asc` }}),
                callBackend({ type:'supabase_get', action:'getData', payload:{ table:`laporan_guru?nama_siswa=ilike.*${encodeURIComponent(namaSiswa)}*&semester=eq.${encodeURIComponent(sem)}&status=eq.Selesai&order=created_at.asc` }}),
                callBackend({ type:'supabase_get', action:'getData', payload:{ table:`riwayat_kelas_siswa?semester=eq.${encodeURIComponent(sem)}&order=tanggal_pindah.asc` }})
            ]);

            const absen = resAbsen.data || [];
            const pelanggaran = resPelanggaran.data || [];
            const riwayatKelas = resRiwayatKelas.data?.filter(r => absen[0] && r.id_siswa === absen[0].id_siswa) || [];

            if (!absen.length && !pelanggaran.length) {
                el.innerHTML = `<div class="text-center py-8 text-gray-400">
            <i class="fas fa-user-times text-3xl mb-2 block">
            </i>
            <p class="text-sm">Tidak ada data untuk "${namaSiswa}" di semester ${sem}</p>
        </div>`;
                return;
            }

            const h = absen.filter(r=>r.jenis==='Hadir').length;
            const i = absen.filter(r=>r.jenis==='Izin').length;
            const s = absen.filter(r=>r.jenis==='Sakit').length;
            const a = absen.filter(r=>r.jenis==='Alpa').length;
            const totalPoin = pelanggaran.reduce((sum,r)=>sum+(r.poin_siswa||0), 0);
            const pr = _predikat(totalPoin);
            const namaTampil = absen[0]?.nama_siswa || pelanggaran[0]?.nama_siswa || namaSiswa;
            const kelasTampil = absen[0]?.kelas || pelanggaran[0]?.kelas_siswa || '-';

            el.innerHTML = `
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="font-black text-gray-800 text-lg">${namaTampil}</p>
                <p class="text-xs text-gray-500">${kelasTampil} • ${sem}</p>
            </div>
            <span class="text-sm font-black px-3 py-1.5 rounded-full ${pr.bg} ${pr.text}">${pr.label}</span>
        </div>
        <!-- Ringkasan -->
        <div class="grid grid-cols-4 gap-2 text-center">
            <div class="bg-green-50 rounded-xl p-2">
                <p class="text-xl font-black text-green-600">${h}</p>
                <p class="text-[9px] text-gray-500 font-bold uppercase">Hadir</p>
            </div>
            <div class="bg-yellow-50 rounded-xl p-2">
                <p class="text-xl font-black text-yellow-600">${i}</p>
                <p class="text-[9px] text-gray-500 font-bold uppercase">Izin</p>
            </div>
            <div class="bg-blue-50 rounded-xl p-2">
                <p class="text-xl font-black text-blue-600">${s}</p>
                <p class="text-[9px] text-gray-500 font-bold uppercase">Sakit</p>
            </div>
            <div class="bg-red-50 rounded-xl p-2">
                <p class="text-xl font-black text-red-600">${a}</p>
                <p class="text-[9px] text-gray-500 font-bold uppercase">Alpa</p>
            </div>
        </div>
        <div class="text-center py-2 bg-gray-50 rounded-xl">
            <p class="text-2xl font-black ${pr.text}">${totalPoin}</p>
            <p class="text-[10px] text-gray-500 font-bold uppercase">Total Poin Pelanggaran</p>
        </div>
        <!-- Detail Absensi -->
                    ${absen.length ? `
                    <div>
        <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Detail Absensi</p>
        <div class="space-y-1 max-h-48 overflow-y-auto">
                            ${absen.map(r => {
                                const stCls = r.jenis==='Hadir'?'text-green-600':r.jenis==='Izin'?'text-yellow-600':r.jenis==='Sakit'?'text-blue-600':'text-red-600';
                                return `<div class="flex justify-between items-center text-xs py-1.5 border-b border-gray-50">
        <span class="text-gray-500">${r.created_at ? new Date(r.created_at).toLocaleDateString('id-ID',{day:'numeric',month:'short'}) : '-'}</span>
        <span class="font-bold ${stCls}">${r.jenis}</span>
    </div>`;
                            }).join('')}
                        </div>
</div>` : ''}

                    <!-- Riwayat Pelanggaran -->
                    ${pelanggaran.length ? `
                    <div>
<p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Riwayat Pelanggaran</p>
<div class="space-y-1.5">
                            ${pelanggaran.map(r => `
                            <div class="flex justify-between items-center p-2 bg-red-50 rounded-lg text-xs">
<span class="font-bold text-gray-700">${r.label_jenis||r.jenis||'-'}</span>
<span class="font-black text-red-500">+${r.poin_siswa||0}</span>
</div>`).join('')}
                        </div>
</div>` : ''}

                    <!-- Riwayat Kelas -->
                    ${riwayatKelas.length ? `
                    <div>
<p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Riwayat Pindah Kelas</p>
                        ${riwayatKelas.map(r => `
                        <div class="text-xs text-gray-600 py-1 border-b border-gray-50">
<span class="line-through text-gray-400">${r.kelas_lama}</span>
<i class="fas fa-arrow-right mx-1 text-gray-400">
</i>
<span class="font-bold">${r.kelas_baru}</span>
<span class="text-gray-400 ml-1">(${r.tanggal_pindah})</span>
</div>`).join('')}
                    </div>` : ''}
                </div>`;
        }
    } catch(e) {
        el.innerHTML = `<div class="text-center py-8 text-red-400 text-sm">${e.message}</div>`;
    }
}

async function exportRekapExcel() {
    Swal.fire({ title:'Fitur Export Excel', text:'Fitur export Excel rekap semester memerlukan library xlsx-js-style. Pastikan library sudah dipasang di server.', icon:'info', confirmButtonColor:'#2563eb' });
}

// ═══════════════════════════════════════════════════════════
// BANTUAN
// ═══════════════════════════════════════════════════════════
function bukaBantuan() {
    Swal.fire({
        title: '<i class="fas fa-question-circle text-blue-500 mr-2">
</i>Bantuan Portal Guru',
        html: `<div class="text-left text-sm space-y-3">
<div class="bg-blue-50 border border-blue-200 rounded-xl p-3">
    <p class="font-bold text-blue-800 text-xs mb-2">📌 Panduan Singkat</p>
    <ul class="text-blue-700 text-xs space-y-1.5">
        <li>🏠 <b>Dashboard</b> — Ringkasan aktivitas dan laporan terbaru Anda</li>
        <li>👤 <b>Edit Profil</b> — Atur kelas & mapel yang diajarkan agar data siswa tampil sesuai</li>
        <li>👥 <b>Daftar Siswa</b> — Lihat rekap kehadiran siswa per kelas</li>
        <li>🚩 <b>Laporkan Siswa</b> — Catat pelanggaran siswa, poin otomatis ditambahkan</li>
        <li>📨 <b>Kirim Masukan</b> — Sampaikan saran atau keluhan ke admin sekolah</li>
    </ul>
</div>
<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3">
    <p class="font-bold text-yellow-800 text-xs mb-1">⚠️ Poin Pelanggaran</p>
    <ul class="text-yellow-700 text-xs space-y-1">
        <li>Alfa Saat Mapel → +10 poin</li>
        <li>Melanggar Peraturan → +6 poin</li>
        <li>Makan di Kelas → +6 poin</li>
        <li>Mengganggu Pembelajaran → +6 poin</li>
    </ul>
</div>
<p class="text-xs text-gray-500 text-center">Butuh bantuan lebih lanjut? Hubungi admin sekolah.</p>
</div>`,
        confirmButtonText: 'Mengerti',
        confirmButtonColor: '#2563eb'
    });
}

// ═══════════════════════════════════════════════════════════
// KELUAR
// ═══════════════════════════════════════════════════════════
async function keluarGuru() {
    const c = await Swal.fire({ title:'Keluar?', text:'Sesi akan diakhiri.', icon:'question', showCancelButton:true, confirmButtonColor:'#dc2626', confirmButtonText:'Ya, Keluar', cancelButtonText:'Batal' });
    if (!c.isConfirmed) return;
    guruData = null;
    sessionStorage.removeItem('ea_guru_session');
    window._selectedGuruLogin = null;
    document.getElementById('mainApp').classList.add('hidden');
    document.getElementById('loginScreen').classList.remove('hidden');
    document.getElementById('loginNamaGuru').value = '';
    document.getElementById('loginPassGuru').value = '';
}


// ═══════════════════════════════════════════════════════════
// UBAH KATA SANDI
// ═══════════════════════════════════════════════════════════
function togglePassField(inputId, eyeId) {
    const inp = document.getElementById(inputId);
    const eye = document.getElementById(eyeId);
    if (!inp) return;
    inp.type = inp.type === 'password' ? 'text' : 'password';
    if (eye) eye.className = inp.type === 'password' ? 'fas fa-eye text-sm' : 'fas fa-eye-slash text-sm';
}

async function ubahKataSandi() {
    const passLama   = document.getElementById('passLama')?.value || '';
    const passBaru   = document.getElementById('passBaru')?.value || '';
    const passKonfirm = document.getElementById('passKonfirm')?.value || '';

    if (!passLama) return Swal.fire('Kosong', 'Masukkan password lama terlebih dahulu.', 'warning');
    if (!passBaru) return Swal.fire('Kosong', 'Masukkan password baru.', 'warning');
    if (passBaru.length < 6) return Swal.fire('Terlalu Pendek', 'Password baru minimal 6 karakter.', 'warning');
    if (passBaru !== passKonfirm) return Swal.fire('Tidak Cocok', 'Konfirmasi password baru tidak cocok.', 'warning');

    Swal.fire({ title: 'Memverifikasi...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });

    try {
        // 1. Verifikasi password lama
        const verRes = await callBackend({ type:'supabase_post', action:'verifyGuruPassword', payload:{ id: guruData.id, password: passLama }});
        const isValidLama = verRes.data?.valid ?? verRes.valid ?? false;
        if (!isValidLama) {
            Swal.fire('Password Salah', 'Password lama yang kamu masukkan tidak sesuai.', 'error');
            return;
        }

        // 2. Simpan password baru (server akan hash dengan bcrypt)
        await callBackend({ type:'supabase_post', action:'updateGuru', payload:{ id: guruData.id, payload:{ plain_password: passBaru }}});

        // 3. Reset field
        document.getElementById('passLama').value    = '';
        document.getElementById('passBaru').value    = '';
        document.getElementById('passKonfirm').value = '';

        Swal.fire({ icon:'success', title:'Kata Sandi Berhasil Diubah!', text:'Password baru sudah aktif. Gunakan saat login berikutnya.', confirmButtonColor:'#f97316' });

        // Catat log
        await callBackend({ type:'supabase_post', action:'insertLog', payload:{ aktor: guruData.nama_lengkap, role:'Guru', aksi:'Ubah Password', detail:'Guru mengubah kata sandi sendiri via portal' }});

    } catch(e) {
        Swal.fire('Gagal', e.message || 'Terjadi kesalahan.', 'error');
    }
}

// ═══════════════════════════════════════════════════════════
// INIT
// ═══════════════════════════════════════════════════════════
window.addEventListener('DOMContentLoaded', () => {
    // Cek sesi tersimpan
    const saved = sessionStorage.getItem('ea_guru_session');
    if (saved) {
        try { guruData = JSON.parse(saved); masukDashboardGuru(); } catch(e) {}
    }
    // Close dropdown on outside click
    document.addEventListener('click', e => {
        if (!e.target.closest('#loginNamaGuru') && !e.target.closest('#dropGuru')) document.getElementById('dropGuru')?.classList.add('hidden');
        if (!e.target.closest('#inputCariSiswaLapor') && !e.target.closest('#dropSiswaLapor')) document.getElementById('dropSiswaLapor')?.classList.add('hidden');
        if (!e.target.closest('#inputTambahKelas') && !e.target.closest('#dropKelas')) document.getElementById('dropKelas')?.classList.add('hidden');
        if (!e.target.closest('#inputTambahMapel') && !e.target.closest('#dropMapel')) document.getElementById('dropMapel')?.classList.add('hidden');
    });
});
</script>
<!-- MODAL ABSEN HARI INI -->
<div id="modalAbsenHariIni" class="fixed inset-0 bg-black/60 z-[300] flex items-end justify-center hidden">
    <div class="bg-white rounded-t-3xl w-full max-w-lg shadow-2xl max-h-[90vh] flex flex-col">
        <div class="p-5 border-b flex justify-between items-center flex-shrink-0">
            <div>
                <h3 class="font-black text-gray-800 text-lg flex items-center gap-2">
                    <i class="fas fa-calendar-day text-green-500">
                    </i> Absen Hari Ini
                    </h3>
                    <p id="absenHariIniWaktu" class="text-xs text-gray-400 mt-0.5">
                    </p>
                </div>
                <button onclick="tutupAbsenHariIni()" class="text-gray-400 hover:text-red-500 w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 transition">
                    <i class="fas fa-times">
                    </i>
                </button>
            </div>
            <div class="p-4 flex-shrink-0 border-b bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Tanggal</label>
                        <button type="button" onclick="bukaFilterTanggal('absen')" class="w-full flex items-center justify-between gap-2 px-3 py-2 border-2 border-gray-200 hover:border-green-500 rounded-xl text-xs font-bold text-gray-800 bg-white transition text-left">
                            <span id="absenFilterLabel" class="truncate">—</span>
                            <i class="fas fa-calendar-alt text-green-500 flex-shrink-0">
                            </i>
                        </button>
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Kelas</label>
                        <p class="text-sm font-black text-gray-800 py-2" id="absenKelasLabel">—</p>
                    </div>
                </div>
            </div>
            <!-- Ringkasan -->
            <div id="absenRingkasan" class="px-4 py-3 flex-shrink-0 border-b bg-white hidden">
                <div class="flex gap-3 text-center">
                    <div class="flex-1 bg-green-50 rounded-xl py-2">
                        <p id="sumH" class="font-black text-green-600 text-lg">0</p>
                        <p class="text-[9px] text-gray-400 font-bold">HADIR</p>
                    </div>
                    <div class="flex-1 bg-yellow-50 rounded-xl py-2">
                        <p id="sumI" class="font-black text-yellow-600 text-lg">0</p>
                        <p class="text-[9px] text-gray-400 font-bold">IZIN</p>
                    </div>
                    <div class="flex-1 bg-blue-50 rounded-xl py-2">
                        <p id="sumS" class="font-black text-blue-600 text-lg">0</p>
                        <p class="text-[9px] text-gray-400 font-bold">SAKIT</p>
                    </div>
                    <div class="flex-1 bg-red-50 rounded-xl py-2">
                        <p id="sumA" class="font-black text-red-600 text-lg">0</p>
                        <p class="text-[9px] text-gray-400 font-bold">ALFA</p>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-xl py-2">
                        <p id="sumBelum" class="font-black text-gray-400 text-lg">0</p>
                        <p class="text-[9px] text-gray-400 font-bold">BELUM</p>
                    </div>
                </div>
            </div>
            <!-- List siswa -->
            <div id="absenHariIniList" class="overflow-y-auto flex-1 divide-y divide-gray-50 px-2 py-2">
                <div class="text-center py-10 text-gray-400 text-sm">
                    <i class="fas fa-spinner fa-spin text-xl block mb-2">
                    </i>Memuat...</div>
                </div>
            </div>
        </div>
        <!-- MODAL PILIH MASTER PELANGGARAN -->
        <div id="modalMasterPelanggaran" class="fixed inset-0 bg-black/60 z-[300] flex items-end justify-center hidden">
            <div class="bg-white rounded-t-3xl w-full max-w-lg shadow-2xl">
                <div class="p-5 border-b flex justify-between items-center">
                    <h3 class="font-black text-gray-800 text-lg flex items-center gap-2">
                        <i class="fas fa-list-ul text-indigo-500">
                        </i> Pilih Pelanggaran
                </h3>
                        <button onclick="tutupPilihMasterPelanggaran()" class="text-gray-400 hover:text-red-500 w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 transition">
                            <i class="fas fa-times">
                            </i>
                        </button>
                    </div>
                    <div class="p-4">
                        <input type="text" id="cariMasterPelanggaran" placeholder="Cari pelanggaran..." oninput="filterMasterPelanggaran(this.value)" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-indigo-500 mb-3">
                        <div id="listMasterPelanggaran" class="space-y-2 max-h-72 overflow-y-auto pr-1">
                            <p class="text-center text-gray-400 text-sm py-4">
                                <i class="fas fa-spinner fa-spin mr-2">
                                </i>Memuat...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
// Scroll lock modal: body terkunci saat modal terbuka, klik backdrop menutup, scroll list tidak merembet
(function(){
  var tutup={modalAbsenHariIni:function(){tutupAbsenHariIni();},modalMasterPelanggaran:function(){tutupPilihMasterPelanggaran();}};
  Object.keys(tutup).forEach(function(id){
    var m=document.getElementById(id); if(!m) return;
    new MutationObserver(function(){
      document.body.style.overflow=document.querySelector('#modalAbsenHariIni:not(.hidden), #modalMasterPelanggaran:not(.hidden)')?'hidden':'';
    }).observe(m,{attributes:true,attributeFilter:['class']});
    m.addEventListener('click',function(e){ if(e.target===m) tutup[id](); });
  });
  ['absenHariIniList','listMasterPelanggaran'].forEach(function(id){var l=document.getElementById(id); if(l) l.style.overscrollBehavior='contain';});
})();
</script>
                <script>
// 2.3 Format hadir x/x (periode > 1 hari) + detail per tanggal
var _hariEfektif=null;
function _geserTgl(tgl,n){var d=new Date(tgl+'T00:00:00Z');d.setUTCDate(d.getUTCDate()+n);return d.toISOString().slice(0,10);}
async function hitungHariEfektif(semVal){
  var today=_tglWibHariIni(),d,e;
  if(_periodeAktif==='TGL'){var f=_siswaFilter;if(!f.dari||f.dari===f.sampai)return null;d=f.dari;e=f.sampai;}
  else if(_periodeAktif==='7H'){d=_geserTgl(today,-7);e=today;}
  else if(_periodeAktif==='1B'){d=_geserTgl(today,-30);e=today;}
  else if(_periodeAktif==='1S'){var sm=_cacheSemester.find(function(x){return x.label===semVal;});if(!sm||!sm.tanggal_mulai||!sm.tanggal_akhir)return null;d=sm.tanggal_mulai;e=sm.tanggal_akhir<today?sm.tanggal_akhir:today;}
  else return null;
  var libur=[],peta={};
  try{var r=await callBackend({type:'supabase_get',action:'getPengaturanByKey',payload:{key : '{{ env('KEY_19') }}'}});libur=((r.data&&r.data[0]&&r.data[0].nilai)||'').split(',').map(function(x){return x.trim();}).filter(Boolean);}catch(x){}
  // Manajemen Hari menentukan: baris ada -> Masuk dihitung, Libur tidak; tanpa baris -> Senin-Jumat dihitung
  try{var rr=await callBackend({type:'supabase_get',action:'getData',payload:{table:'manajemen_hari?tanggal=gte.'+d+'&tanggal=lte.'+e+'&select=tanggal,status&limit=1000'}});(rr.data||[]).forEach(function(x){peta[x.tanggal]=x.status;});}catch(x){}
  var n=0;
  for(var c=d;c<=e;c=_geserTgl(c,1)){
    if(peta[c]){if(peta[c]==='Masuk')n++;continue;}
    var h=new Date(c+'T00:00:00Z').getUTCDay();if(h!==0&&h!==6&&libur.indexOf(c)<0)n++;
  }
  return n;
}
function detailAbsenSiswa(id){
  var s=siswaDataAll.find(function(x){return x.id===id;});if(!s)return;
  var g={Hadir:[],Izin:[],Sakit:[],Alpa:[],Terlambat:[]};
  (s.abs||[]).forEach(function(a){var t=new Date(a.created_at).toLocaleDateString('id-ID',{day:'numeric',month:'short',timeZone:'Asia/Jakarta'});if(g[a.jenis])g[a.jenis].push(t);if(a.jenis==='Hadir'&&a.is_late)g.Terlambat.push(t);});
  var warna={Hadir:'green',Izin:'yellow',Sakit:'blue',Alpa:'red',Terlambat:'orange'};
  var html='<div class="text-left space-y-2">'+Object.keys(g).map(function(k){
    return '<div class="bg-'+warna[k]+'-50 rounded-xl p-3">
                <p class="text-xs font-black text-'+warna[k]+'-700 mb-1">'+k+' ('+g[k].length+')</p>
                <p class="text-xs text-gray-600">'+(g[k].length?g[k].join(', '):'—')+'</p>
            </div>';}).join('')+'</div>';
  Swal.fire({titleText:s.nama,html:html,showDenyButton:true,denyButtonText:'Salin ke Catatan Pribadi',denyButtonColor:'#7c3aed',confirmButtonColor:'#2563eb',confirmButtonText:'Tutup'}).then(function(r){if(r.isDenied)ambilSalinanCatatan(id);});
}
</script>
            <script>
// Jadwal mengajar (acuan jam WIB server) + Catatan Absen pribadi
var _jadwalNow=null;
function _escG(v){return String(v==null?'':v).replace(/[&<>"']/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}
var _HR=['','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
function bolehLihat(j){return !!j&&(j.status==='mengajar'||j.batasi===false);}
async function cekJadwalSekarang(){
  try{
    var r=await callBackendStrict({type:'supabase_post',action:'cekJadwalGuru',payload:{id_guru:guruData.id}});
    if(!r.data)throw new Error('Server tidak mengembalikan jadwal — notify.js di Netlify kemungkinan masih versi lama. Hubungi admin untuk deploy ulang.');
    _jadwalNow=r.data;
  }catch(e){_jadwalNow={status:'error',pesan:e.message};}
  renderBannerJadwal();return _jadwalNow;
}
function _pesanJadwal(j){
  if(j.status==='mengajar')return ['green','Anda mengajar <b>'+_escG(j.mapel)+'</b> di <b>'+_escG(j.kelas)+'</b> sampai jam <b>'+j.sampai+'</b>.'];
  if(j.status==='istirahat')return ['yellow','Sedang istirahat sampai jam <b>'+j.sampai+'</b>.'];
  if(j.status==='agenda')return ['yellow','Sedang ada agenda'+(j.nama?' ('+_escG(j.nama)+')':'')+' sampai jam <b>'+j.sampai+'</b>.'];
  if(j.status==='libur')return ['gray','Hari ini libur'+(j.ket?' — '+_escG(j.ket):'')+'.'];
  if(j.status==='tidak_mengajar'){var b=j.berikut;return ['blue','Anda tidak sedang mengajar. '+(b?'Jadwal berikutnya: <b>'+_HR[b.hari]+'</b>, jam ke-'+b.jam_ke+(b.mulai?' ('+b.mulai+')':'')+', '+_escG(b.mapel)+', '+_escG(b.kelas)+'.':'Belum ada jadwal berikutnya.')];}
  if(j.status==='belum_diatur')return ['yellow',_escG(j.pesan||'Jam pelajaran hari ini belum diatur admin.')];
  return ['red',_escG(j.pesan||'Jadwal tidak dapat dimuat.')];
}
function renderBannerJadwal(){
  var el=document.getElementById('bannerJadwal');if(!el||!_jadwalNow)return;
  var p=_pesanJadwal(_jadwalNow),on=bolehLihat(_jadwalNow);
  el.innerHTML='<div class="bg-'+p[0]+'-50 border border-'+p[0]+'-200 text-'+p[0]+'-800 rounded-xl p-3 text-xs font-bold">'+p[1]+(_jadwalNow.batasi===false?'<br>
            <span class="font-normal">Pembatasan jadwal dimatikan admin — semua kelas Anda bisa dilihat.</span>':'')+'</div>'
   +'<div class="flex gap-2 mt-2">
            <button onclick="ambilSalinanCatatan()" '+(on?'':'disabled ')+'class="flex-1 text-xs font-black py-2.5 rounded-xl transition '+(on?'bg-purple-600 hover:bg-purple-700 text-white':'bg-gray-100 text-gray-400 cursor-not-allowed')+'">
                <i class="fas fa-copy mr-1">
                </i>Ambil Salinan Catatan Pribadi</button>'
   +'<button onclick="navigate(\'catatan\')" class="flex-shrink-0 text-xs font-black px-3 py-2.5 rounded-xl bg-white border-2 border-purple-200 text-purple-700 hover:bg-purple-50 transition">
                <i class="fas fa-clipboard-list mr-1">
                </i>Catatan Absen</button>
            </div>';
}
function _kunciSiswa(j){
  var t=j.status==='error'?_escG(j.pesan):'Belum waktunya mata pelajaran. Silakan cek jadwal Anda.';
  document.getElementById('siswaTableBody').innerHTML='<tr>
            <td colspan="7" class="text-center py-10 text-gray-400 text-xs px-4">
                <i class="fas fa-lock text-2xl block mb-2 text-gray-300">
                </i>'+t+'</td>
            </tr>';
  document.getElementById('infoJumlahSiswa').textContent='';
}
var _mdkfAsli=muatDropKelasFilter;
muatDropKelasFilter=async function(){await cekJadwalSekarang();return _mdkfAsli();};
var _loadSiswaAsli=loadSiswaKelas;
loadSiswaKelas=async function(){
  var j=await cekJadwalSekarang();
  if(!bolehLihat(j)){siswaDataAll=[];_kunciSiswa(j);return;}
  var sel=document.getElementById('filterKelasSiswa');
  if(j.batasi!==false&&j.status==='mengajar'&&sel&&sel.value!==j.kelas){
    var ada=Array.prototype.some.call(sel.options,function(o){return o.value===j.kelas;});
    if(ada)sel.value=j.kelas;
    else{document.getElementById('siswaTableBody').innerHTML='<tr>
            <td colspan="7" class="text-center py-10 text-gray-400 text-xs px-4">Saat ini Anda mengajar di kelas '+_escG(j.kelas)+'. Tambahkan kelas itu di Edit Profil.</td>
        </tr>';return;}
  }
  if(sel&&!sel.value&&!_getKelasList().length){document.getElementById('siswaTableBody').innerHTML='<tr>
        <td colspan="7" class="text-center py-10 text-gray-400 text-xs px-4">Belum ada kelas yang diajarkan. Tambahkan di Edit Profil atau minta admin mengisinya.</td>
    </tr>';return;}
  return _loadSiswaAsli();
};
function _rentangFilterSiswa(sem){
  var t=_tglWibHariIni();
  if(_periodeAktif==='1H')return [t,t];
  if(_periodeAktif==='7H')return [_geserTgl(t,-7),t];
  if(_periodeAktif==='1B')return [_geserTgl(t,-30),t];
  if(_periodeAktif==='TGL')return [_siswaFilter.dari||t,_siswaFilter.sampai||t];
  var s=_cacheSemester.find(function(x){return x.label===sem;});return s&&s.tanggal_mulai?[s.tanggal_mulai,s.tanggal_akhir||t]:[t,t];
}
async function _salinKe(kelas,dari,sampai,idSiswa){
  var smAktif=(_cacheSemester.find(function(x){return x.aktif;})||{}).label||'';
  Swal.fire({title:'Menyalin...',didOpen:function(){Swal.showLoading();},allowOutsideClick:false});
  var res=await callBackendStrict({type:'supabase_post',action:'salinCatatanAbsen',payload:{id_guru:guruData.id,kelas:kelas,dari:dari,sampai:sampai,semester:smAktif,id_siswa:idSiswa||null}});
  Swal.fire({icon:'success',title:'Tersalin',text:res.data.disalin+' catatan baru disimpan ke Catatan Absen Pribadi.',confirmButtonColor:'#2563eb'});
  if(document.getElementById('view-catatan').classList.contains('active'))muatCatatanAbsen();
}
async function ambilSalinanCatatan(idSiswa){
  try{
    var j=await cekJadwalSekarang();
    if(!bolehLihat(j)){Swal.fire({icon:'info',title:'Belum waktunya mata pelajaran',text:'Salinan hanya bisa diambil saat Anda mengajar.',confirmButtonColor:'#2563eb'});return;}
    var kelas=document.getElementById('filterKelasSiswa').value||j.kelas,sem=document.getElementById('filterSemesterSiswa').value,r=_rentangFilterSiswa(sem);
    if(!kelas){Swal.fire('Pilih kelas','Pilih kelas dulu di Daftar Siswa.','info');return;}
    await _salinKe(kelas,r[0],r[1],idSiswa);
  }catch(e){Swal.fire('Gagal',e.message,'error');}
}
// dari halaman Catatan Absen: salin absensi hari ini untuk kelas yang sedang diajar
async function salinHariIni(){
  try{
    var j=await cekJadwalSekarang();
    if(!bolehLihat(j)){Swal.fire({icon:'info',title:'Belum waktunya mata pelajaran',text:'Salinan hanya bisa diambil saat Anda mengajar.',confirmButtonColor:'#2563eb'});return;}
    var kelas=j.status==='mengajar'?j.kelas:'';
    if(!kelas){
      var ks=_getKelasList();if(!ks.length){Swal.fire('Belum ada kelas','Tambahkan kelas di Edit Profil.','info');return;}
      var o={};ks.forEach(function(k){o[k]=k;});
      var r=await Swal.fire({title:'Pilih kelas',input:'select',inputOptions:o,showCancelButton:true,confirmButtonText:'Salin'});if(!r.isConfirmed||!r.value)return;kelas=r.value;
    }
    var t=_tglWibHariIni();await _salinKe(kelas,t,t,null);
  }catch(e){Swal.fire('Gagal',e.message,'error');}
}
async function muatCatatanAbsen(){
  var el=document.getElementById('listCatatanAbsen');if(!el)return;
  var q=((document.getElementById('cariCatatan')||{}).value||'').toLowerCase();
  try{
    var r=await callBackendStrict({type:'supabase_get',action:'getData',payload:{table:'catatan_absen_guru?id_guru=eq.'+guruData.id+'&order=tanggal.desc,nama_siswa.asc&limit=300'}});
    var d=(r.data||[]).filter(function(c){return !q||(c.nama_siswa||'').toLowerCase().indexOf(q)>=0||(c.kelas||'').toLowerCase().indexOf(q)>=0||(c.tanggal||'').indexOf(q)>=0;});
    el.innerHTML=d.map(function(c){
      return '<div class="border border-gray-100 rounded-xl p-2.5 bg-gray-50 flex flex-wrap items-center gap-2">
    <div class="flex-1 min-w-[140px]">
        <p class="text-xs font-black text-gray-800">'+_escG(c.nama_siswa)+'</p>
        <p class="text-[10px] text-gray-400">'+_escG(c.tanggal)+' • '+_escG(c.kelas)+' • '+_escG(c.mapel||'')+'</p>
    </div>'
      +'<select onchange="simpanCatatan(\''+c.id+'\',{jenis:this.value})" class="text-xs font-bold border-2 border-gray-200 rounded-lg px-2 py-1">'+['Hadir','Izin','Sakit','Alpa'].map(function(j){return '<option'+(c.jenis===j?' selected':'')+'>'+j+'</option>';}).join('')+'</select>'
      +'<input value="'+_escG(c.catatan||'')+'" placeholder="Catatan" onchange="simpanCatatan(\''+c.id+'\',{catatan:this.value})" class="text-xs border-2 border-gray-200 rounded-lg px-2 py-1 flex-1 min-w-[100px]">'
      +'<button onclick="hapusCatatan(\''+c.id+'\')" class="text-red-500 hover:bg-red-100 p-1.5 rounded-lg">
    <i class="fas fa-trash-alt text-xs">
    </i>
</button>
</div>';
    }).join('')||'<p class="text-xs text-gray-400 text-center py-8">
<i class="fas fa-clipboard text-3xl block mb-2 text-gray-200">
</i>Belum ada catatan. Tekan <b>Salin Absensi Hari Ini</b> saat Anda sedang mengajar, atau ambil dari menu Daftar Siswa.</p>';
  }catch(e){el.innerHTML='<p class="text-xs text-red-400 text-center py-3">'+_escG(e.message)+'</p>';}
}
async function simpanCatatan(id,p){
  p.updated_at=new Date().toISOString();
  try{await callBackendStrict({type:'supabase_post',action:'updateData',payload:{table:'catatan_absen_guru',id:id,payload:p}});Swal.fire({toast:true,position:'top-end',icon:'success',title:'Tersimpan',showConfirmButton:false,timer:1200});}
  catch(e){Swal.fire('Gagal',e.message,'error');}
}
async function hapusCatatan(id){
  var c=await Swal.fire({title:'Hapus catatan ini?',text:'Hanya salinan pribadi; absensi global tidak terpengaruh.',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc2626',confirmButtonText:'Hapus'});
  if(!c.isConfirmed)return;
  try{await callBackendStrict({type:'supabase_post',action:'deleteData',payload:{table:'catatan_absen_guru',id:id}});muatCatatanAbsen();}catch(e){Swal.fire('Gagal',e.message,'error');}
}
var _navAsli=navigate;
navigate=function(p){_navAsli(p);if(p==='catatan')muatCatatanAbsen();};
</script>
<script>
// ============ LOGIN PAKAI SEMESTER (guru) + gating Absen Hari Ini ============
async function pilihSemesterGuru(){
  if(!_cacheSemester.length){try{var r=await callBackend({type:'supabase_get',action:'getData',payload:{table:'master_semester?order=urutan.desc'}});_cacheSemester=r.data||[];}catch(e){}}
  var ak=_cacheSemester.find(function(s){return s.aktif;});
  var h=await Swal.fire({title:'Pilih Semester',html:'<select id="semGuruSel" class="swal2-select" style="display:flex;width:80%">'+_cacheSemester.map(function(s){return '<option value="'+_escG(s.label)+'"'+(ak&&s.label===ak.label?' selected':'')+'>'+_escG(s.label)+(s.aktif?' ★':'')+'</option>';}).join('')+'</select>',allowOutsideClick:false,confirmButtonText:'Pilih',confirmButtonColor:'#2563eb',preConfirm:function(){return document.getElementById('semGuruSel').value;}});
  sessionStorage.setItem('ea_semester_guru',h.value||'');_terapkanSemesterGuru();
}
function _terapkanSemesterGuru(){
  var v=sessionStorage.getItem('ea_semester_guru');if(!v)return;
  var s=document.getElementById('filterSemesterSiswa');if(s&&Array.prototype.some.call(s.options,function(o){return o.value===v;}))s.value=v;
  _semesterDipilih=v;var p=document.getElementById('pilihanSemester');if(p)p.value=v;
}
var _mdgAsli=masukDashboardGuru;
masukDashboardGuru=async function(){await _mdgAsli();if(!sessionStorage.getItem('ea_semester_guru'))await pilihSemesterGuru();};
var _mdkf2=muatDropKelasFilter;
muatDropKelasFilter=async function(){await _mdkf2();_terapkanSemesterGuru();};
var _mmrs=muatMenuRekapSemester;
muatMenuRekapSemester=async function(){await _mmrs();_terapkanSemesterGuru();};
var _kgAsli=keluarGuru;
keluarGuru=async function(){await _kgAsli();if(!sessionStorage.getItem('ea_guru_session'))sessionStorage.removeItem('ea_semester_guru');};
var _bahAsli=bukaAbsenHariIni;
bukaAbsenHariIni=async function(){
  var j=await cekJadwalSekarang();
  if(j.status!=='mengajar'){Swal.fire({icon:'info',title:'Belum waktunya mata pelajaran',text:'Absen kelas hanya bisa dilihat saat Anda sedang mengajar.',confirmButtonColor:'#2563eb'});return;}
  var sel=document.getElementById('filterKelasSiswa');if(sel)sel.value=j.kelas;
  _bahAsli();
};
</script>
<script>
// Dashboard guru ikut semester pilihan (laporan guru)
var _cbG=callBackend;
callBackend=function(p){
  try{var sm=sessionStorage.getItem('ea_semester_guru');
    if(sm&&p&&p.type==='supabase_get'){
      if(p.action==='getLaporanGuru')p=Object.assign({},p,{payload:Object.assign({},p.payload,{semester:sm})});
      else if(p.action==='getData'&&p.payload&&typeof p.payload.table==='string'&&p.payload.table.indexOf('laporan_guru?id_guru=eq.')===0)p=Object.assign({},p,{payload:Object.assign({},p.payload,{table:p.payload.table+'&semester=eq.'+encodeURIComponent(sm)})});
    }}catch(e){}
  return _cbG(p);
};
</script>
<script src="{{ asset('/grisVA.js') }}" defer="">
</script>



@endsection