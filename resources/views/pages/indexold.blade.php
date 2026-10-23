@extends('layouts.app')

@section('content')


    <!-- TOP BAR -->
<div class="bg-gray-900 text-white text-xs py-1.5 px-3 md:px-4 flex justify-between items-center z-50 relative border-b border-gray-700 flex-shrink-0">
    <div class="flex items-center gap-1 md:gap-2 font-mono font-bold tracking-widest text-yellow-400 whitespace-nowrap z-10 bg-gray-900 pr-2 border-r border-gray-700">
        <i class="far fa-clock">
        </i>
        <span id="realtimeClock">Memuat...</span>
    </div>
    <div class="marquee-container mask-edges flex-1 pl-2 w-full max-w-[50vw] md:max-w-none">
        <div class="absolute left-2 top-0 bottom-0 flex items-center bg-gray-900 pr-2 z-10">
            <i class="fas fa-bell text-blue-400 animate-pulse">
            </i>
        </div>
        <div id="worshipMarquee" class="marquee-content font-medium text-gray-300 ml-6 tracking-wide text-[10px] md:text-xs">
                Menghitung jadwal ibadah terdekat...
            </div>
    </div>
</div>
<!-- AUDIO BGM -->
<audio id="bgmAudio" loop="">
    <source id="bgmSource" src="" type="audio/mpeg">
    </audio>
    <audio id="notifAudio" preload="auto">
        <source src="/notif.mp3" type="audio/mpeg">
        </audio>
        <button id="musicToggleBtn" onclick="toggleMusic()" class="hidden w-12 h-12 bg-white/20 backdrop-blur-md text-white rounded-full shadow-2xl flex items-center justify-center border border-white/30 hover:scale-110 hover:bg-white/40 transition active:scale-95">
            <i id="musicIcon" class="fas fa-volume-up text-xl">
            </i>
        </button>
        <!-- POPUP KOMENTAR PENILAIAN (muncul sekilas di pojok kiri bawah) -->
        <div id="popupKomentar" class="fixed bottom-20 left-4 z-[200] hidden max-w-xs w-72">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-4 relative" style="animation: slideInLeft 0.4s ease-out;">
                <button onclick="tutupPopupKomentar()" class="absolute top-2 right-2 text-gray-300 hover:text-gray-500 w-6 h-6 flex items-center justify-center rounded-full hover:bg-gray-100 transition text-xs">
                    <i class="fas fa-times">
                    </i>
                </button>
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-yellow-50 rounded-xl flex items-center justify-center flex-shrink-0 text-lg border border-yellow-100">⭐</div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-black text-yellow-500 uppercase tracking-widest mb-0.5">Penilaian Terbaru</p>
                        <p class="text-xs font-black text-gray-800 truncate" id="pkNama">—</p>
                        <div class="flex gap-0.5 my-1" id="pkBintang">
                        </div>
                        <p class="text-xs font-medium text-gray-600 leading-relaxed line-clamp-2" id="pkKomentar" style="display:none">
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- INTRO SCREEN -->
        <div id="introScreen" class="fixed inset-0 bg-black z-[999] flex items-center justify-center transition-opacity duration-500">
            <video id="introVideo" autoplay="" muted="" playsinline="" class="absolute inset-0 w-full h-full object-cover opacity-80 pointer-events-none">
                <source id="introVideoSource" src="" type="video/mp4">
                </video>
                <button id="skipIntroBtn" class="absolute top-6 right-6 text-white bg-black/60 hover:bg-black/90 px-5 py-2.5 rounded-full text-xs font-bold backdrop-blur-md transition-all border border-white/20 cursor-pointer shadow-2xl z-[1000]">
            Lewati Intro <i class="fas fa-chevron-right ml-1">
            </i>
        </button>
        <div class="absolute bottom-12 flex flex-col items-center justify-center w-full px-4 text-center z-[1000]">
            <button id="unmuteHint" class="text-white/90 bg-transparent px-6 py-3 rounded-full text-sm font-bold animate-bounce cursor-pointer hover:text-white transition-all flex items-center gap-2 drop-shadow-2xl filter">
                <i class="fas fa-volume-up text-2xl drop-shadow-lg">
                </i> Klik untuk Putar Suara
            </button>
            </div>
        </div>
        <!-- LOGIN SCREEN -->
        <div id="loginScreen" class="flex-1 flex flex-col items-center justify-center p-4 md:p-6 hidden fade-in w-full relative z-10">
            <!-- TOMBOL PORTAL DROPDOWN (kiri atas) -->
            <div class="w-full max-w-md mb-3 flex justify-start relative" id="portalDropdownWrapper">
                <button onclick="togglePortalMenu()" id="btnPortalMenu" class="flex items-center gap-2.5 bg-white border border-gray-200 shadow-md hover:shadow-lg px-4 py-2.5 rounded-2xl text-sm font-black text-gray-700 transition-all hover:border-blue-300 hover:text-blue-700 active:scale-95 group">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-400 rounded-full flex items-center justify-center shadow-sm flex-shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-circle text-white text-base">
                        </i>
                    </div>
                    <span>Portal Lainnya</span>
                    <i id="portalChevron" class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-300">
                    </i>
                </button>
                <!-- DROPDOWN MENU -->
                <div id="portalDropdownMenu" class="absolute top-full left-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden hidden" style="transform-origin: top left;">
                    <div class="bg-gray-50 px-4 py-2.5 border-b border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pilih Portal Akses</p>
                    </div>
                    <ul class="py-1.5">
                        <li>
                            <a href="/admin.html" class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition group/item">
                                <div class="w-10 h-10 bg-blue-100 group-hover/item:bg-blue-600 rounded-xl flex items-center justify-center transition-colors flex-shrink-0 shadow-sm">
                                    <i class="fas fa-shield-alt text-blue-600 group-hover/item:text-white transition-colors">
                                    </i>
                                </div>
                                <div>
                                    <p class="font-black text-gray-800 text-sm group-hover/item:text-blue-700 transition-colors">Portal Admin</p>
                                    <p class="text-[10px] text-gray-400 font-medium">Manajemen &amp; laporan sekolah</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="/elakses.html" class="flex items-center gap-3 px-4 py-3 hover:bg-green-50 transition group/item">
                                <div class="w-10 h-10 bg-green-100 group-hover/item:bg-green-600 rounded-xl flex items-center justify-center transition-colors flex-shrink-0 shadow-sm">
                                    <i class="fas fa-user-friends text-green-600 group-hover/item:text-white transition-colors">
                                    </i>
                                </div>
                                <div>
                                    <p class="font-black text-gray-800 text-sm group-hover/item:text-green-700 transition-colors">Portal Orang Tua</p>
                                    <p class="text-[10px] text-gray-400 font-medium">Pantau perkembangan anak</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" onclick="bukaElPoint()" class="flex items-center gap-3 px-4 py-3 hover:bg-orange-50 transition group/item">
                                <div class="w-10 h-10 bg-orange-100 group-hover/item:bg-orange-500 rounded-xl flex items-center justify-center transition-colors flex-shrink-0 shadow-sm">
                                    <i class="fas fa-star text-orange-500 group-hover/item:text-white transition-colors">
                                    </i>
                                </div>
                                <div>
                                    <p class="font-black text-gray-800 text-sm group-hover/item:text-orange-600 transition-colors">Portal El Point</p>
                                    <p class="text-[10px] text-gray-400 font-medium">Sistem poin OSIS &amp; kedisiplinan</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="/portal-guru.html" class="flex items-center gap-3 px-4 py-3 hover:bg-green-50 transition group/item">
                                <div class="w-10 h-10 bg-green-100 group-hover/item:bg-green-600 rounded-xl flex items-center justify-center transition-colors flex-shrink-0 shadow-sm">
                                    <i class="fas fa-chalkboard-teacher text-green-600 group-hover/item:text-white transition-colors">
                                    </i>
                                </div>
                                <div>
                                    <p class="font-black text-gray-800 text-sm group-hover/item:text-green-700 transition-colors">Portal Guru</p>
                                    <p class="text-[10px] text-gray-400 font-medium">Laporan siswa &amp; data kelas</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="bg-white p-6 md:p-8 rounded-3xl shadow-xl w-full max-w-md text-center border-t-4 border-blue-600 relative">
                <img src="{{ asset('icon.png') }}" id="logoShortcut" onerror="this.onerror=null; this.src='https://iili.io/qfChwml.png';" alt="Logo Grisawangi" class="w-28 md:w-32 h-auto mx-auto mb-6 drop-shadow-md cursor-pointer select-none" title="Klik 3x untuk shortcut">
                <h1 class="text-2xl font-black mb-1 text-gray-800 tracking-tight">E-ASPIRASI GRISAWANGI</h1>
                <p class="text-xs text-gray-500 mb-8 font-medium italic">Silakan lengkapi identitas Anda untuk melanjutkan.</p>
                <form id="loginForm" class="space-y-4 text-left relative">
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1">Nama Lengkap</label>
                        <input type="text" id="loginNama" required="" autocomplete="off" class="w-full px-4 py-2.5 border-2 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition font-bold text-gray-800" placeholder="Ketik nama Anda...">
                        <ul id="autocompleteSiswa" class="absolute z-[100] w-full bg-white border border-gray-200 rounded-xl shadow-2xl mt-1 hidden max-h-48 overflow-y-auto custom-dropdown text-left divide-y divide-gray-100 top-full left-0">
                        </ul>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1">Kelas</label>
                        <input type="text" id="loginKelas" required="" class="w-full px-4 py-2.5 border-2 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition font-bold text-gray-800 bg-gray-50" placeholder="Ketik kelas Anda...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1">Jenis Kelamin</label>
                        <select id="loginGender" required="" class="w-full px-4 py-2.5 border-2 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition font-bold text-gray-800 cursor-pointer bg-white">
                            <option value="" disabled="" selected="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1">Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="loginTglLahir" required="" class="w-full px-4 py-2.5 border-2 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition font-bold text-gray-800">
                </div>
                <div class="flex items-center gap-2 mt-2 bg-blue-50 p-3 rounded-lg border border-blue-100">
                    <input type="checkbox" id="rememberMe" class="w-5 h-5 text-blue-600 border-2 border-gray-300 rounded cursor-pointer accent-blue-600" style="flex-shrink:0;">
                    <label for="rememberMe" class="text-[11px] font-bold text-blue-800 cursor-pointer select-none">Ingat sesi dan auto-login di perangkat ini</label>
                </div>
                <button type="submit" id="btnLoginSubmit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-lg hover:shadow-xl transition active:scale-95 uppercase tracking-widest text-sm mt-4">
                    Masuk Aplikasi
                </button>
            </form>
        </div>
    </div>
    <!-- MODAL DIBLOKIR -->
    <div id="blockedModal" class="fixed inset-0 bg-black bg-opacity-80 z-[99] flex items-center justify-center p-4 hidden fade-in">
        <div class="bg-white rounded-3xl max-w-sm w-full p-8 text-center border-t-8 border-red-600 shadow-2xl relative">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-lg -mt-14">
                <i class="fas fa-user-lock text-4xl text-red-600">
                </i>
            </div>
            <h3 class="text-2xl font-black text-gray-800 mb-2 tracking-tight">Akses Diblokir!</h3>
            <p class="text-sm text-gray-600 mb-6 font-medium leading-relaxed">Mohon maaf, hak akses Anda saat ini sedang dibekukan oleh Admin sekolah.</p>
            <div class="space-y-3">
                <a href="#" id="btnHubungiAdminBlokir" target="_blank" class="block w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3.5 rounded-xl shadow-lg transition uppercase tracking-widest text-xs">
                    <i class="fab fa-whatsapp text-lg mr-1 align-middle">
                    </i> Hubungi Admin</a>
                    <button onclick="document.getElementById('blockedModal').classList.add('hidden')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3.5 rounded-xl transition uppercase tracking-widest text-xs">Tutup</button>
                </div>
            </div>
        </div>
        <!-- MODAL VALIDASI GAGAL -->
        <div id="validationFailedModal" class="fixed inset-0 bg-black bg-opacity-70 z-[90] flex items-center justify-center p-4 hidden fade-in">
            <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center border-t-4 border-red-600 shadow-2xl relative">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-times-circle text-4xl text-red-600">
                    </i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Validasi Gagal</h3>
                <p class="text-sm text-gray-600 mb-6 font-medium">Data Nama, Kelas, atau Tanggal Lahir tidak cocok dengan database sekolah.</p>
                <div class="space-y-3">
                    <button onclick="closeValidationModal()" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded-xl transition">Coba Lagi</button>
                    <button onclick="openEmergencyModal()" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl shadow-md transition">Gunakan Kode Darurat</button>
                </div>
            </div>
        </div>
        <!-- MODAL KODE DARURAT -->
        <div id="emergencyCodeModal" class="fixed inset-0 bg-black bg-opacity-70 z-[95] flex items-center justify-center p-4 hidden fade-in">
            <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center border-t-4 border-orange-500 shadow-2xl relative">
                <button onclick="closeEmergencyModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                    <i class="fas fa-times text-xl">
                    </i>
                </button>
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-3xl text-orange-500">
                    </i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Akses Darurat</h3>
                <p class="text-xs text-gray-500 mb-4 font-medium">Masukkan 6 digit kode darurat untuk melanjutkan.</p>
                <div class="mb-4">
                    <input type="text" id="emergencyInput" maxlength="6" class="w-full text-center tracking-[0.5em] font-bold text-lg px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none uppercase" placeholder="------">
                </div>
                <button onclick="verifyEmergencyCode()" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl shadow-md transition mb-3">Verifikasi Kode</button>
                <div class="border-t pt-4 mt-2">
                    <button onclick="requestEmergencyCode()" class="w-full bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 font-bold py-3 rounded-xl transition flex items-center justify-center gap-2 text-sm">
                        <i class="fab fa-whatsapp text-green-600 text-lg">
                        </i> Minta Kode Darurat</button>
                    </div>
                </div>
            </div>
            <!-- POPUP SELAMAT DATANG / BROSUR -->
            <div id="welcomePopup" class="fixed inset-0 bg-black bg-opacity-60 z-[60] flex items-center justify-center p-4 hidden">
                <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl transform transition-all border-t-8 border-blue-600 overflow-hidden flex flex-col max-h-[90vh]">
                    <div class="p-6 md:p-8 flex-shrink-0 text-center relative">
                        <button onclick="closeWelcomePopup()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition">
                            <i class="fas fa-times text-xl">
                            </i>
                        </button>
                        <img src="{{ asset('icon.png') }}" onerror="this.onerror=null; this.src='https://iili.io/qfChwml.png';" alt="Logo" class="w-16 mx-auto mb-3 drop-shadow-md">
                        <h2 class="text-2xl font-black text-gray-800 mb-1">Selamat Datang!</h2>
                    </div>
                    <div class="px-6 md:px-8 pb-4 overflow-y-auto flex-1 hide-scroll">
                        <div id="brosurMode" class="hidden w-full h-auto mb-4">
                            <img id="brosurImage" src="" alt="Pengumuman Sekolah" class="w-full h-auto rounded-xl shadow-sm border border-gray-200 object-contain">
                        </div>
                        <div id="textMode" class="text-gray-600 text-sm mb-4 leading-relaxed text-left bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <p>Selamat datang di <b>E-ASPIRASI GRISAWANGI</b>. Platform ini dirancang sebagai ruang aman dan nyaman bagi Anda untuk menyuarakan aspirasi, melapor, atau berkonsultasi.</p>
                            <div class="mt-3">
                                <b class="text-blue-700 font-black uppercase tracking-widest text-[10px]">Fitur Tersedia:</b>
                                <ul class="list-none space-y-2 mt-2 font-medium">
                                    <li>
                                        <i class="fas fa-check-circle text-green-500 mr-1">
                                        </i>
                                        <b>Profil &amp; Preferensi:</b> Atur biodata &amp; agama Anda.</li>
                                        <li>
                                            <i class="fas fa-check-circle text-green-500 mr-1">
                                            </i>
                                            <b>Lapor Aspirasi:</b> Sampaikan dengan rahasia terjamin.</li>
                                            <li>
                                                <i class="fas fa-check-circle text-green-500 mr-1">
                                                </i>
                                                <b>Konseling BK:</b> Atur jadwal pertemuan pribadi.</li>
                                                <li>
                                                    <i class="fas fa-check-circle text-green-500 mr-1">
                                                    </i>
                                                    <b>Poin Kelakuan:</b> Pantau total poin kedisiplinan Anda.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-6 md:p-8 pt-0 flex-shrink-0">
                                        <button onclick="closeWelcomePopup()" class="w-full bg-blue-600 text-white font-black py-4 rounded-xl shadow-lg hover:shadow-xl hover:bg-blue-700 transition active:scale-95 uppercase tracking-widest text-sm">Lanjut ke Dashboard</button>
                                    </div>
                                </div>
                            </div>
                            <!-- POPUP BANTUAN WA -->
                            <div id="helpModal" class="fixed inset-0 bg-black bg-opacity-60 z-[70] flex items-center justify-center p-4 hidden">
                                <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl text-center border-t-4 border-green-500 relative fade-in">
                                    <button onclick="closeHelpModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-xl">
                                        <i class="fas fa-times">
                                        </i>
                                    </button>
                                    <i class="fab fa-whatsapp text-5xl text-green-500 mb-4">
                                    </i>
                                    <h2 class="text-xl font-bold text-gray-800 mb-2">Pusat Bantuan</h2>
                                    <p class="text-sm text-gray-500 mb-6 font-medium">Pilih kontak WhatsApp di bawah ini jika Anda butuh bantuan atau mengalami kendala teknis.</p>
                                    <div class="space-y-3">
                                        <a href="#" id="linkWaEa" target="_blank" class="flex items-center gap-3 bg-green-50 p-3 rounded-xl border border-green-100 hover:bg-green-100 transition shadow-sm hover:shadow-md">
                                            <div class="bg-green-500 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg">
                                                <i class="fab fa-whatsapp">
                                                </i>
                                            </div>
                                            <div class="text-left">
                                                <p class="text-sm font-bold text-green-800" id="labelWaEa">E-Aspirasi CS</p>
                                                <p class="text-xs text-green-600 font-mono" id="textWaEa">Memuat...</p>
                                            </div>
                                        </a>
                                        <a href="#" id="linkWaGuru" target="_blank" class="flex items-center gap-3 bg-green-50 p-3 rounded-xl border border-green-100 hover:bg-green-100 transition shadow-sm hover:shadow-md">
                                            <div class="bg-green-500 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg">
                                                <i class="fab fa-whatsapp">
                                                </i>
                                            </div>
                                            <div class="text-left">
                                                <p class="text-sm font-bold text-green-800" id="labelWaGuru">Admin Guru</p>
                                                <p class="text-xs text-green-600 font-mono" id="textWaGuru">Memuat...</p>
                                            </div>
                                        </a>
                                        <a href="#" id="linkWaDev" target="_blank" class="flex items-center gap-3 bg-green-50 p-3 rounded-xl border border-green-100 hover:bg-green-100 transition shadow-sm hover:shadow-md">
                                            <div class="bg-green-500 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg">
                                                <i class="fab fa-whatsapp">
                                                </i>
                                            </div>
                                            <div class="text-left">
                                                <p class="text-sm font-bold text-green-800" id="labelWaDev">IT / Developer</p>
                                                <p class="text-xs text-green-600 font-mono" id="textWaDev">Memuat...</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- POPUP LOGOUT -->
                            <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-70 z-[80] flex items-center justify-center p-4 hidden">
                                <div class="bg-white rounded-2xl max-w-sm w-full p-8 shadow-2xl text-center border-t-4 border-red-500 relative fade-in">
                                    <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-hand-sparkles text-3xl text-red-500">
                                        </i>
                                    </div>
                                    <h2 class="text-2xl font-black text-gray-800 mb-2">Terima Kasih!</h2>
                                    <p class="text-sm text-gray-600 mb-8 font-medium leading-relaxed">Terima kasih atas partisipasinya. Semoga hari Anda menyenangkan dan sampai jumpa kembali! ✨</p>
                                    <button onclick="confirmLogout()" class="w-full bg-red-500 hover:bg-red-600 text-white font-black py-3.5 rounded-xl shadow-lg transition active:scale-95 uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                        <i class="fas fa-sign-in-alt">
                                        </i> Keluar Penuh &amp; Hapus Sesi</button>
                                        <button onclick="closeLogoutModal()" class="mt-4 text-sm text-gray-500 hover:text-gray-700 font-bold underline transition">Batal Keluar</button>
                                    </div>
                                </div>
                                <!-- MODAL TAMPILKAN QR -->
                                <div id="qrModal" class="fixed inset-0 bg-black bg-opacity-80 z-[120] flex items-center justify-center p-4 hidden fade-in">
                                    <div class="bg-white rounded-3xl max-w-sm w-full p-8 text-center shadow-2xl relative border-t-8 border-blue-600">
                                        <button onclick="closeQRModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition">
                                            <i class="fas fa-times text-xl">
                                            </i>
                                        </button>
                                        <h3 class="text-xl font-black text-gray-800 mb-1">Kartu Pelajar Digital</h3>
                                        <p class="text-xs text-gray-500 mb-4 font-medium">Tunjukkan kode QR ini ke petugas saat diperlukan.</p>
                                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200 inline-block mb-4 shadow-inner">
                                            <img id="qrImage" src="" alt="QR Code NISN" class="w-48 h-48 object-contain mix-blend-multiply">
                                        </div>
                                        <div class="mb-4">
                                            <p id="qrName" class="font-black text-gray-800 text-lg uppercase tracking-tight">
                                            </p>
                                            <p id="qrNisn" class="text-blue-600 font-mono font-bold text-sm mt-1">
                                            </p>
                                            <p id="qrClass" class="text-gray-500 text-xs font-bold bg-gray-100 px-3 py-1 rounded-full inline-block mt-2 border">
                                            </p>
                                        </div>
                                        <button onclick="unduhQRSiswa()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-3 rounded-xl shadow-md transition active:scale-95 text-sm flex items-center justify-center gap-2">
                                            <i class="fas fa-download">
                                            </i> Unduh QR Card
            </button>
                                        </div>
                                    </div>
                                    <!-- MAINTENANCE MODE OVERLAY -->
                                    <div id="maintenanceOverlay" class="fixed inset-0 z-[9999] bg-blue-900 flex-col items-center justify-center text-white text-center p-8 hidden" style="display:none;">
                                        <div class="max-w-md mx-auto">
                                            <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6">
                                                <i class="fas fa-tools text-5xl text-yellow-400">
                                                </i>
                                            </div>
                                            <h1 class="text-3xl font-black mb-3">Website Sedang Diperbaiki</h1>
                                            <p class="text-blue-200 text-lg mb-6">Kami sedang melakukan upgrade dan perbaikan sistem.<br>Silakan menunggu beberapa saat.</p>
                                            <div id="maintenanceCountdown" class="bg-white/10 rounded-2xl p-4 mb-6">
                                                <p class="text-sm text-blue-200 mb-1">Estimasi selesai dalam</p>
                                                <p class="text-4xl font-black text-yellow-400" id="countdownText">--:--</p>
                                            </div>
                                            <p class="text-blue-300 text-sm">E-Aspirasi Grisawangi — SMK PGRI 1 Giri</p>
                                        </div>
                                    </div>
                                    <!-- MODAL: SET PIN PERTAMA KALI (2 langkah) -->
                                    <div id="modalSetPin" class="fixed inset-0 bg-black bg-opacity-80 z-[220] flex items-center justify-center p-4 hidden fade-in backdrop-blur-sm overflow-y-auto">
                                        <div class="bg-white rounded-3xl max-w-sm w-full shadow-2xl border-t-8 border-blue-600 my-auto">
                                            <div class="p-6">
                                                <!-- Langkah 1: Set PIN -->
                                                <div id="setPinStep1">
                                                    <div class="text-center mb-5">
                                                        <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                            <i class="fas fa-lock text-2xl text-blue-600">
                                                            </i>
                                                        </div>
                                                        <div class="flex items-center justify-center gap-2 mb-2">
                                                            <span class="bg-blue-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full">LANGKAH 1 dari 2</span>
                                                        </div>
                                                        <h3 class="text-lg font-black text-gray-800 mb-1">Buat PIN Login Kamu</h3>
                                                        <p class="text-xs text-gray-500 leading-relaxed">Login pertamamu berhasil! Sekarang buat PIN 4 digit sebagai kunci masuk berikutnya.</p>
                                                    </div>
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-xs font-black text-gray-600 uppercase tracking-widest mb-2 text-center">PIN Baru (4 digit)</label>
                                                            <div class="flex gap-3 justify-center" id="pinSetBoxes">
                                                                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" data-idx="0">
                                                                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" data-idx="1">
                                                                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" data-idx="2">
                                                                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" data-idx="3">
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-black text-gray-600 uppercase tracking-widest mb-2 text-center">Ulangi PIN</label>
                                                            <div class="flex gap-3 justify-center" id="pinConfirmBoxes">
                                                                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-confirm-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" data-idx="0">
                                                                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-confirm-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" data-idx="1">
                                                                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-confirm-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" data-idx="2">
                                                                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-confirm-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" data-idx="3">
                                                            </div>
                                                        </div>
                                                        <p class="text-[11px] text-red-500 font-bold hidden text-center" id="setPinStep1Error">
                                                        </p>
                                                        <button onclick="lanjutSetPinStep2()" class="w-full mt-2 bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-lg transition active:scale-95 text-sm uppercase tracking-widest">
                            Lanjut <i class="fas fa-arrow-right ml-1">
                                                    </i>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Langkah 2: Pertanyaan Keamanan -->
                                        <div id="setPinStep2" class="hidden">
                                            <div class="text-center mb-5">
                                                <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                    <i class="fas fa-shield-alt text-2xl text-yellow-500">
                                                    </i>
                                                </div>
                                                <div class="flex items-center justify-center gap-2 mb-2">
                                                    <span class="bg-yellow-500 text-white text-[10px] font-black px-2.5 py-1 rounded-full">LANGKAH 2 dari 2</span>
                                                </div>
                                                <h3 class="text-lg font-black text-gray-800 mb-1">Pertanyaan Keamanan</h3>
                                                <p class="text-xs text-gray-500 leading-relaxed">Jawaban ini dipakai jika kamu lupa PIN. Ingat baik-baik ya!</p>
                                            </div>
                                            <div class="space-y-4">
                                                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-4">
                                                    <p class="text-sm font-black text-gray-700 mb-3 flex items-center gap-2">
                                                        <i class="fas fa-question-circle text-yellow-500">
                                                        </i>
                                Di mana tempat favoritmu? 🌟
                            </p>
                                                        <input type="text" id="setPinSecAnswer" placeholder="Tulis jawabanmu di sini..." class="w-full px-4 py-3 border-2 border-yellow-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none bg-white" autocomplete="off">
                                                        <div class="flex items-start gap-2 mt-3 bg-red-50 p-3 rounded-lg border border-red-100">
                                                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 flex-shrink-0 text-xs">
                                                            </i>
                                                            <p class="text-[10px] text-red-600 font-bold leading-relaxed">Jangan beritahu jawabanmu kepada siapapun! Jawaban ini hanya untuk memulihkan PIN kamu.</p>
                                                        </div>
                                                    </div>
                                                    <p class="text-[11px] text-red-500 font-bold hidden text-center" id="setPinStep2Error">
                                                    </p>
                                                    <div class="flex gap-3">
                                                        <button onclick="kembaliStep1()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl transition text-sm">
                                                            <i class="fas fa-arrow-left mr-1">
                                                            </i> Kembali
                            </button>
                                                            <button onclick="simpanPinDanJawaban()" id="btnSimpanPin" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-3 rounded-xl shadow-lg transition active:scale-95 text-sm">
                                                                <i class="fas fa-save mr-1">
                                                                </i> Simpan &amp; Masuk
                            </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- MODAL: MASUKKAN PIN (setiap login setelah pertama kali) -->
                                        <div id="modalMasukPin" class="fixed inset-0 bg-black bg-opacity-80 z-[220] flex items-center justify-center p-4 hidden fade-in backdrop-blur-sm">
                                            <div class="bg-white rounded-3xl max-w-xs w-full shadow-2xl border-t-8 border-indigo-600">
                                                <div class="p-6 text-center">
                                                    <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                        <i class="fas fa-key text-2xl text-indigo-600">
                                                        </i>
                                                    </div>
                                                    <h3 class="text-lg font-black text-gray-800 mb-1">Masukkan PIN</h3>
                                                    <p class="text-xs text-gray-500 mb-5">Halo <span id="pinLoginNama" class="font-black text-indigo-600">
                                                </span>! Masukkan PIN 4 digit kamu.</p>
                                                <div class="flex gap-3 justify-center mb-2" id="pinEntryBoxes">
                                                    <input type="password" maxlength="1" inputmode="numeric" class="pin-entry-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition" data-idx="0">
                                                    <input type="password" maxlength="1" inputmode="numeric" class="pin-entry-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition" data-idx="1">
                                                    <input type="password" maxlength="1" inputmode="numeric" class="pin-entry-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition" data-idx="2">
                                                    <input type="password" maxlength="1" inputmode="numeric" class="pin-entry-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition" data-idx="3">
                                                </div>
                                                <p class="text-[11px] text-red-500 font-bold hidden mb-3" id="pinEntryError">PIN salah, coba lagi.</p>
                                                <button onclick="verifikasiPin()" id="btnVerifPin" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3.5 rounded-xl shadow-lg transition active:scale-95 text-sm uppercase tracking-widest mb-3">
                                                    <i class="fas fa-unlock mr-2">
                                                    </i> Masuk
                </button>
                                                    <button onclick="bukaLupaPin()" class="w-full text-xs font-bold py-2 px-3 rounded-xl border border-orange-200 text-orange-500 hover:bg-orange-50 transition flex items-center justify-center gap-1.5 mt-1">
                                                        <i class="fas fa-question-circle">
                                                        </i> Lupa PIN? Pulihkan sekarang
                </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- MODAL: LUPA PIN / UBAH PIN -->
                                            <div id="modalLupaPin" class="fixed inset-0 bg-black bg-opacity-80 z-[230] flex items-center justify-center p-4 hidden fade-in backdrop-blur-sm overflow-y-auto">
                                                <div class="bg-white rounded-3xl max-w-sm w-full shadow-2xl border-t-8 border-orange-500 my-auto">
                                                    <div class="p-6">
                                                        <button onclick="tutupLupaPin()" class="absolute top-4 right-4 text-gray-300 hover:text-red-500 w-8 h-8 rounded-full bg-white border flex items-center justify-center transition z-10">
                                                            <i class="fas fa-times text-sm">
                                                            </i>
                                                        </button>
                                                        <div class="text-center mb-5">
                                                            <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                                <i class="fas fa-question-circle text-2xl text-orange-500">
                                                                </i>
                                                            </div>
                                                            <h3 class="text-lg font-black text-gray-800 mb-1">Pulihkan / Ubah PIN</h3>
                                                            <p class="text-xs text-gray-500">Jawab pertanyaan keamanan untuk membuat PIN baru</p>
                                                        </div>
                                                        <div class="space-y-4">
                                                            <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                                                                <p class="text-sm font-black text-gray-700 mb-2 flex items-center gap-2">
                                                                    <i class="fas fa-question-circle text-orange-400">
                                                                    </i>
                            Di mana tempat favoritmu? 🌟
                        </p>
                                                                    <input type="text" id="lupaPinAnswer" placeholder="Jawaban kamu..." class="w-full px-3 py-2.5 border-2 border-orange-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none bg-white" autocomplete="off">
                                                                </div>
                                                                <div>
                                                                    <label class="block text-xs font-black text-gray-600 uppercase tracking-widest mb-2 text-center">PIN Baru (4 digit)</label>
                                                                    <div class="flex gap-3 justify-center" id="lupaPinNewBoxes">
                                                                        <input type="password" maxlength="1" inputmode="numeric" class="lupa-pin-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" data-idx="0">
                                                                        <input type="password" maxlength="1" inputmode="numeric" class="lupa-pin-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" data-idx="1">
                                                                        <input type="password" maxlength="1" inputmode="numeric" class="lupa-pin-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" data-idx="2">
                                                                        <input type="password" maxlength="1" inputmode="numeric" class="lupa-pin-box w-14 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" data-idx="3">
                                                                    </div>
                                                                </div>
                                                                <p class="text-[11px] text-red-500 font-bold hidden text-center" id="lupaPinError">
                                                                </p>
                                                                <button onclick="prosesLupaPin()" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-black py-4 rounded-xl shadow-md transition active:scale-95 text-sm uppercase tracking-widest">
                                                                    <i class="fas fa-key mr-2">
                                                                    </i> Ganti PIN Sekarang
                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- GLOBAL UI: MAIN DASHBOARD -->
                                                    <div id="mainApp" class="hidden flex flex-col flex-1 w-full relative">
                                                        <header class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-40 px-4 py-3 flex justify-between items-center border-b border-gray-200">
                                                            <div class="flex items-center gap-3">
                                                                <button onclick="toggleSidebar()" class="text-gray-600 hover:text-blue-600 text-2xl focus:outline-none transition">
                                                                    <i class="fas fa-bars">
                                                                    </i>
                                                                </button>
                                                                <img src="{{ asset('icon.png') }}" onerror="this.onerror=null; this.src='https://iili.io/qfChwml.png';" alt="Logo" class="w-10 h-10 object-contain drop-shadow-sm hover:scale-105 transition-transform">
                                                                <div class="hidden sm:block">
                                                                    <h1 class="text-lg font-black text-gray-800 tracking-tight leading-none">E-ASPIRASI</h1>
                                                                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest leading-none mt-1">SMK PGRI 1 Giri</p>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center gap-3">
                                                                <!-- TOMBOL LONCENG NOTIFIKASI -->
                                                                <div class="relative">
                                                                    <button onclick="toggleNotifPanel()" id="btnNotifBell" class="relative text-gray-500 hover:text-blue-600 w-10 h-10 rounded-full flex items-center justify-center text-lg transition hover:bg-blue-50 border border-gray-200 bg-white shadow-sm active:scale-95">
                                                                        <i class="fas fa-bell">
                                                                        </i>
                                                                        <span id="notifBadge" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[9px] font-black rounded-full items-center justify-center hidden shadow-md flex">0</span>
                                                                    </button>
                                                                    <!-- PANEL NOTIF DROPDOWN -->
                                                                    <div id="notifPanel" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                                                                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                                                            <p class="font-black text-gray-800 text-sm flex items-center gap-2">
                                                                                <i class="fas fa-bell text-blue-500">
                                                                                </i> Notifikasi</p>
                                                                                <button onclick="tandaiSemuaDibaca()" class="text-[10px] font-bold text-blue-500 hover:text-blue-700 uppercase tracking-widest transition">Tandai Dibaca</button>
                                                                            </div>
                                                                            <ul id="notifList" class="max-h-80 overflow-y-auto divide-y divide-gray-50 custom-dropdown">
                                                                                <li class="px-4 py-8 text-center text-gray-400 text-xs font-bold" id="notifEmpty">
                                                                                    <i class="fas fa-bell-slash text-3xl mb-2 block text-gray-200">
                                                                                    </i>
                                Belum ada notifikasi
                            </li>
                                                                                </ul>
                                                                                <div class="bg-gray-50 px-4 py-2.5 border-t border-gray-100 text-center">
                                                                                    <button onclick="navigate('progres'); toggleNotifPanel()" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition uppercase tracking-widest">Lihat Semua Tiket →</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="relative">
                                                                            <button onclick="togglePlusMenu()" class="text-white bg-blue-600 hover:bg-blue-700 w-10 h-10 rounded-full flex items-center justify-center text-xl transition shadow-lg active:scale-95 border-2 border-white">
                                                                                <i class="fas fa-plus">
                                                                                </i>
                                                                            </button>
                                                                            <div id="plusMenu" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 hidden slide-down z-50 overflow-hidden">
                                                                                <div class="bg-gray-50 px-4 py-2 border-b border-gray-100">
                                                                                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Aksi Cepat</p>
                                                                                </div>
                                                                                <ul class="py-1 text-sm text-gray-700 font-bold">
                                                                                    <li>
                                                                                        <button onclick="navigate('aspirasi'); togglePlusMenu()" class="w-full text-left px-4 py-3 hover:bg-blue-50 hover:text-blue-600 transition flex items-center">
                                                                                            <div class="w-8 flex justify-center">
                                                                                                <i class="fas fa-bullhorn text-blue-500 text-lg">
                                                                                                </i>
                                                                                            </div> Buat Laporan</button>
                                                                                        </li>
                                                                                        <li>
                                                                                            <button onclick="navigate('masukan'); togglePlusMenu()" class="w-full text-left px-4 py-3 hover:bg-green-50 hover:text-green-600 transition flex items-center">
                                                                                                <div class="w-8 flex justify-center">
                                                                                                    <i class="fas fa-comment-dots text-green-500 text-lg">
                                                                                                    </i>
                                                                                                </div> Buat Masukan</button>
                                                                                            </li>
                                                                                            <li>
                                                                                                <button onclick="navigate('konselor'); togglePlusMenu()" class="w-full text-left px-4 py-3 hover:bg-purple-50 hover:text-purple-600 transition flex items-center">
                                                                                                    <div class="w-8 flex justify-center">
                                                                                                        <i class="fas fa-user-graduate text-purple-500 text-lg">
                                                                                                        </i>
                                                                                                    </div> Jadwal Konselor</button>
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- DARK MODE TOGGLE -->
                                                                                    <button onclick="toggleDarkMode()" id="btnDarkMode" title="Mode Gelap" class="text-gray-500 hover:text-gray-800 w-10 h-10 rounded-full flex items-center justify-center text-lg transition hover:bg-gray-100 border border-gray-200 bg-white shadow-sm active:scale-95">
                                                                                        <i class="fas fa-moon" id="iconDarkMode">
                                                                                        </i>
                                                                                    </button>
                                                                                    <!-- TOMBOL BANTUAN / DOKUMENTASI -->
                                                                                    <button onclick="bukaDocModal()" title="Panduan &amp; Dokumentasi" class="text-blue-600 hover:text-white hover:bg-blue-600 w-10 h-10 rounded-full flex items-center justify-center font-black text-base transition border-2 border-blue-200 bg-blue-50 shadow-sm active:scale-95">
                    ?
                </button>
                                                                                </div>
                                                                            </header>
                                                                            <!-- MODAL DOKUMENTASI -->
                                                                            <div id="docModal" class="fixed inset-0 bg-black/60 z-[150] flex items-end sm:items-center justify-center p-0 sm:p-4 hidden backdrop-blur-sm fade-in">
                                                                                <div class="bg-white w-full sm:max-w-sm rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden border-t-4 border-blue-600 flex flex-col max-h-[90vh]">
                                                                                    <!-- Header modal -->
                                                                                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between flex-shrink-0 bg-gray-50">
                                                                                        <div class="flex items-center gap-3">
                                                                                            <div class="w-9 h-9 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center font-black text-lg">?</div>
                                                                                            <div>
                                                                                                <p class="font-black text-gray-800 text-sm leading-none">Panduan &amp; Dokumentasi</p>
                                                                                                <p class="text-[10px] text-gray-400 font-medium mt-0.5">E-Aspirasi Grisawangi</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <button onclick="tutupDocModal()" class="text-gray-400 hover:text-red-500 w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center transition shadow-sm">
                                                                                            <i class="fas fa-times text-sm">
                                                                                            </i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <!-- Menu dokumentasi -->
                                                                                    <div class="overflow-y-auto flex-1 p-4">
                                                                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Buka Halaman</p>
                                                                                        <div class="space-y-2" id="docMenuList">
                                                                                            <a href="/dokumentasi.html#overview" target="_blank" onclick="tutupDocModal()" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-blue-50 border border-gray-100 hover:border-blue-200 transition group">
                                                                                                <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition">
                                                                                                    <i class="fas fa-info-circle text-sm">
                                                                                                    </i>
                                                                                                </div>
                                                                                                <div class="flex-1 min-w-0">
                                                                                                    <p class="font-black text-gray-800 text-sm">Gambaran Umum</p>
                                                                                                    <p class="text-[10px] text-gray-400">Tentang sistem &amp; semua link akses</p>
                                                                                                </div>
                                                                                                <i class="fas fa-external-link-alt text-gray-300 text-xs">
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="/dokumentasi.html#login-siswa" target="_blank" onclick="tutupDocModal()" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-blue-50 border border-gray-100 hover:border-blue-200 transition group">
                                                                                                <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition">
                                                                                                    <i class="fas fa-sign-in-alt text-sm">
                                                                                                    </i>
                                                                                                </div>
                                                                                                <div class="flex-1 min-w-0">
                                                                                                    <p class="font-black text-gray-800 text-sm">Cara Login Siswa</p>
                                                                                                    <p class="text-[10px] text-gray-400">Langkah-langkah masuk aplikasi</p>
                                                                                                </div>
                                                                                                <i class="fas fa-external-link-alt text-gray-300 text-xs">
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="/dokumentasi.html#fitur-siswa" target="_blank" onclick="tutupDocModal()" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-green-50 border border-gray-100 hover:border-green-200 transition group">
                                                                                                <div class="w-9 h-9 bg-green-50 text-green-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-green-600 group-hover:text-white transition">
                                                                                                    <i class="fas fa-user text-sm">
                                                                                                    </i>
                                                                                                </div>
                                                                                                <div class="flex-1 min-w-0">
                                                                                                    <p class="font-black text-gray-800 text-sm">Fitur Portal Siswa</p>
                                                                                                    <p class="text-[10px] text-gray-400">Laporan, masukan, tiket, penilaian</p>
                                                                                                </div>
                                                                                                <i class="fas fa-external-link-alt text-gray-300 text-xs">
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="/dokumentasi.html#notifikasi" target="_blank" onclick="tutupDocModal()" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-red-50 border border-gray-100 hover:border-red-200 transition group">
                                                                                                <div class="w-9 h-9 bg-red-50 text-red-500 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-red-500 group-hover:text-white transition">
                                                                                                    <i class="fas fa-bell text-sm">
                                                                                                    </i>
                                                                                                </div>
                                                                                                <div class="flex-1 min-w-0">
                                                                                                    <p class="font-black text-gray-800 text-sm">Sistem Notifikasi</p>
                                                                                                    <p class="text-[10px] text-gray-400">WA, push notification, Telegram</p>
                                                                                                </div>
                                                                                                <i class="fas fa-external-link-alt text-gray-300 text-xs">
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="/dokumentasi.html#faq" target="_blank" onclick="tutupDocModal()" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-purple-50 border border-gray-100 hover:border-purple-200 transition group">
                                                                                                <div class="w-9 h-9 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-purple-600 group-hover:text-white transition">
                                                                                                    <i class="fas fa-question text-sm">
                                                                                                    </i>
                                                                                                </div>
                                                                                                <div class="flex-1 min-w-0">
                                                                                                    <p class="font-black text-gray-800 text-sm">FAQ &amp; Bantuan</p>
                                                                                                    <p class="text-[10px] text-gray-400">Pertanyaan umum &amp; kontak</p>
                                                                                                </div>
                                                                                                <i class="fas fa-external-link-alt text-gray-300 text-xs">
                                                                                                </i>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                                                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Buka Dokumentasi Lengkap</p>
                                                                                            <a href="/dokumentasi.html" target="_blank" onclick="tutupDocModal()" class="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-3 rounded-2xl text-sm transition active:scale-95 shadow-md">
                                                                                                <i class="fas fa-book-open">
                                                                                                </i> Buka Dokumentasi Penuh
                        </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-gray-900/60 z-40 hidden backdrop-blur-sm transition-opacity">
                                                                                </div>
                                                                                <div id="sidebarMenu" class="fixed inset-y-0 left-0 w-72 bg-white shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out z-50 flex flex-col border-r border-gray-100">
                                                                                    <div class="p-6 border-b bg-gradient-to-br from-blue-50 to-white flex justify-between items-center">
                                                                                        <div class="flex items-center gap-3">
                                                                                            <div class="bg-white p-2 rounded-xl shadow-sm border border-blue-100">
                                                                                                <img src="{{ asset('icon.png') }}" onerror="this.onerror=null; this.src='https://iili.io/qfChwml.png';" alt="Logo" class="w-8 h-8 object-contain">
                                                                                            </div>
                                                                                            <div>
                                                                                                <span class="font-black text-blue-800 text-lg tracking-tight block leading-none">E-ASPIRASI</span>
                                                                                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Menu Navigasi</span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <button onclick="toggleSidebar()" class="text-gray-400 hover:text-red-500 w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center transition">
                                                                                            <i class="fas fa-times">
                                                                                            </i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto hide-scroll">
                                                                                        <button onclick="navigate('dashboard'); toggleSidebar()" class="w-full text-left p-3.5 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition font-bold text-gray-600 flex items-center gap-3">
                                                                                            <div class="w-6 text-center">
                                                                                                <i class="fas fa-home text-lg">
                                                                                                </i>
                                                                                            </div> Beranda Utama</button>
                                                                                            <div class="my-3 border-t border-gray-100">
                                                                                            </div>
                                                                                            <p class="px-3 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Layanan Utama</p>
                                                                                            <button onclick="navigate('aspirasi'); toggleSidebar()" class="w-full text-left p-3.5 rounded-xl hover:bg-red-50 hover:text-red-700 transition font-bold text-gray-600 flex items-center gap-3">
                                                                                                <div class="w-6 text-center">
                                                                                                    <i class="fas fa-bullhorn text-red-500 text-lg">
                                                                                                    </i>
                                                                                                </div> Lapor Aspirasi</button>
                                                                                                <button onclick="navigate('masukan'); toggleSidebar()" class="w-full text-left p-3.5 rounded-xl hover:bg-green-50 hover:text-green-700 transition font-bold text-gray-600 flex items-center gap-3">
                                                                                                    <div class="w-6 text-center">
                                                                                                        <i class="fas fa-comment-dots text-green-500 text-lg">
                                                                                                        </i>
                                                                                                    </div> Kotak Masukan</button>
                                                                                                    <button onclick="navigate('konselor'); toggleSidebar()" class="w-full text-left p-3.5 rounded-xl hover:bg-purple-50 hover:text-purple-700 transition font-bold text-gray-600 flex items-center gap-3">
                                                                                                        <div class="w-6 text-center">
                                                                                                            <i class="fas fa-user-graduate text-purple-500 text-lg">
                                                                                                            </i>
                                                                                                        </div> Atur Konselor BK</button>
                                                                                                        <button onclick="navigate('progres'); toggleSidebar()" class="w-full text-left p-3.5 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition font-bold text-gray-600 flex justify-between items-center group">
                                                                                                            <div class="flex items-center gap-3">
                                                                                                                <div class="w-6 text-center">
                                                                                                                    <i class="fas fa-tasks text-blue-500 text-lg">
                                                                                                                    </i>
                                                                                                                </div> Cek Status Tiket</div>
                                                                                                                <i class="fas fa-chevron-right text-gray-300 group-hover:text-blue-500 transition">
                                                                                                                </i>
                                                                                                            </button>
                                                                                                            <div class="my-3 border-t border-gray-100">
                                                                                                            </div>
                                                                                                            <p class="px-3 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Informasi &amp; Profil</p>
                                                                                                            <button onclick="navigate('pelanggaran'); toggleSidebar()" class="w-full text-left p-3.5 rounded-xl hover:bg-orange-50 hover:text-orange-700 transition font-bold text-gray-600 flex items-center gap-3">
                                                                                                                <div class="w-6 text-center">
                                                                                                                    <i class="fas fa-gavel text-orange-500 text-lg">
                                                                                                                    </i>
                                                                                                                </div> Point Pelanggaran</button>
                                                                                                                <button onclick="navigate('profil'); toggleSidebar()" class="w-full text-left p-3.5 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition font-bold text-gray-600 flex items-center gap-3">
                                                                                                                    <div class="w-6 text-center">
                                                                                                                        <i class="fas fa-user-circle text-blue-500 text-lg">
                                                                                                                        </i>
                                                                                                                    </div> Profil &amp; Preferensi</button>
                                                                                                                    <button onclick="bukaMaterieBK()" class="w-full text-left p-3.5 rounded-xl hover:bg-green-50 hover:text-green-700 transition font-bold text-gray-600 flex items-center gap-3">
                                                                                                                        <div class="w-6 text-center">
                                                                                                                            <i class="fas fa-book-open text-green-500 text-lg">
                                                                                                                            </i>
                                                                                                                        </div> Materi BK</button>
                                                                                                                        <a href="https://smkpgri1giri.sch.id/berita" target="_blank" onclick="toggleSidebar()" class="block p-3.5 rounded-xl hover:bg-gray-100 hover:text-gray-800 transition font-bold text-gray-600 flex items-center gap-3">
                                                                                                                            <div class="w-6 text-center">
                                                                                                                                <i class="fas fa-newspaper text-gray-500 text-lg">
                                                                                                                                </i>
                                                                                                                            </div> Berita Sekolah</a>
                                                                                                                            <div class="my-3 border-t border-gray-100">
                                                                                                                            </div>
                                                                                                                            <p class="px-3 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pusat Bantuan</p>
                                                                                                                            <button onclick="navigate('masalahWeb'); toggleSidebar()" class="w-full text-left p-3.5 rounded-xl hover:bg-red-50 hover:text-red-600 transition font-bold text-gray-600 flex items-center gap-3">
                                                                                                                                <div class="w-6 text-center">
                                                                                                                                    <i class="fas fa-bug text-lg">
                                                                                                                                    </i>
                                                                                                                                </div> Lapor Error Web</button>
                                                                                                                                <button onclick="showHelpModal(); toggleSidebar()" class="w-full text-left p-3.5 rounded-xl hover:bg-green-50 hover:text-green-700 transition font-bold text-green-700 flex items-center gap-3">
                                                                                                                                    <div class="w-6 text-center">
                                                                                                                                        <i class="fab fa-whatsapp text-lg">
                                                                                                                                        </i>
                                                                                                                                    </div> Hubungi Admin</button>
                                                                                                                                    <button onclick="handleLogout(); toggleSidebar()" class="w-full text-left p-3.5 rounded-xl bg-red-50 hover:bg-red-500 hover:text-white text-red-600 transition font-black flex items-center justify-center gap-2 mt-4 shadow-sm">
                                                                                                                                        <i class="fas fa-sign-out-alt">
                                                                                                                                        </i> Keluar Aplikasi</button>
                                                                                                                                    </nav>
                                                                                                                                </div>
                                                                                                                                <main class="flex-1 p-4 md:p-6 lg:p-8 max-w-5xl mx-auto w-full relative pb-20">
                                                                                                                                    <!-- VIEW: DASHBOARD -->
                                                                                                                                    <div id="view-dashboard" class="page-view fade-in">
                                                                                                                                        <div class="bg-gradient-to-br from-blue-700 to-blue-500 rounded-3xl p-6 md:p-8 text-white shadow-xl mb-8 relative overflow-hidden flex flex-col md:flex-row items-center md:justify-between gap-6 border-b-4 border-blue-800">
                                                                                                                                            <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-3xl z-0">
                                                                                                                                            </div>
                                                                                                                                            <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-blue-900/20 rounded-full blur-2xl z-0">
                                                                                                                                            </div>
                                                                                                                                            <div class="relative z-10 text-center md:text-left w-full md:w-auto">
                                                                                                                                                <span class="bg-white/20 text-white border border-white/30 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest backdrop-blur-sm mb-3 inline-block shadow-sm">Dashboard Siswa</span>
                                                                                                                                                <h2 class="text-3xl md:text-4xl font-black mb-1 tracking-tight">Halo, <span id="displayNama" class="text-yellow-300">Siswa</span>!</h2>
                                                                                                                                                <p class="text-blue-100 font-medium text-sm">Selamat datang kembali di Pusat Layanan Siswa.</p>
                                                                                                                                                <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 mt-4">
                                                                                                                                                    <div class="flex items-center gap-2 bg-black/20 px-4 py-2 rounded-xl backdrop-blur-md border border-white/10">
                                                                                                                                                        <i class="fas fa-chalkboard text-blue-200">
                                                                                                                                                        </i>
                                                                                                                                                        <span class="text-xs font-bold text-white uppercase tracking-widest" id="displayKelas">-</span>
                                                                                                                                                    </div>
                                                                                                                                                    <button onclick="navigate('profil')" class="flex items-center gap-2 bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-xl font-bold text-xs transition shadow-lg active:scale-95">
                                                                                                                                                        <i class="fas fa-user-edit">
                                                                                                                                                        </i> Edit Profil</button>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <div class="relative z-10 bg-white/10 p-3 rounded-3xl shadow-inner border-2 border-white/20 backdrop-blur-md flex-shrink-0 hidden sm:block">
                                                                                                                                                    <img src="{{ asset('icon.png') }}" onerror="this.onerror=null; this.src='https://iili.io/qfChwml.png';" alt="Logo Sekolah" class="w-20 h-20 object-contain drop-shadow-xl">
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                            <!-- STAT CARDS RINGKASAN -->
                                                                                                                                            <div class="grid grid-cols-3 gap-3 mb-6" id="dashStatCards">
                                                                                                                                                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
                                                                                                                                                    <div class="w-9 h-9 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center mx-auto mb-2">
                                                                                                                                                        <i class="fas fa-paper-plane text-sm">
                                                                                                                                                        </i>
                                                                                                                                                    </div>
                                                                                                                                                    <p class="text-2xl font-black text-gray-800" id="statTotalLaporan">-</p>
                                                                                                                                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Dikirim</p>
                                                                                                                                                </div>
                                                                                                                                                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
                                                                                                                                                    <div class="w-9 h-9 bg-green-50 text-green-500 rounded-xl flex items-center justify-center mx-auto mb-2">
                                                                                                                                                        <i class="fas fa-check-circle text-sm">
                                                                                                                                                        </i>
                                                                                                                                                    </div>
                                                                                                                                                    <p class="text-2xl font-black text-gray-800" id="statSelesai">-</p>
                                                                                                                                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Selesai</p>
                                                                                                                                                </div>
                                                                                                                                                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
                                                                                                                                                    <div class="w-9 h-9 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center mx-auto mb-2">
                                                                                                                                                        <i class="fas fa-clock text-sm">
                                                                                                                                                        </i>
                                                                                                                                                    </div>
                                                                                                                                                    <p class="text-2xl font-black text-gray-800" id="statMenunggu">-</p>
                                                                                                                                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Pending</p>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                            <!-- WIDGET TIMER ABSENSI -->
                                                                                                                                            <div id="widgetAbsensi" class="mb-6 rounded-3xl overflow-hidden shadow-lg border-2 border-blue-200 bg-white transition-all duration-500">
                                                                                                                                                <div class="p-5">
                                                                                                                                                    <div class="flex items-center justify-between mb-3">
                                                                                                                                                        <div class="flex items-center gap-2">
                                                                                                                                                            <div id="absensiDot" class="w-3 h-3 rounded-full bg-blue-500 animate-pulse">
                                                                                                                                                            </div>
                                                                                                                                                            <span id="absensiLabel" class="text-xs font-black uppercase tracking-widest text-blue-600">Cek Status Absensi</span>
                                                                                                                                                        </div>
                                                                                                                                                        <span id="absensiJamSekarang" class="text-xs font-bold text-gray-400 tabular-nums">
                                                                                                                                                        </span>
                                                                                                                                                    </div>
                                                                                                                                                    <div class="flex items-center gap-4">
                                                                                                                                                        <div id="absensiIconBox" class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 bg-blue-100">
                                                                                                                                                            <i id="absensiIcon" class="fas fa-clock text-blue-500 text-2xl">
                                                                                                                                                            </i>
                                                                                                                                                        </div>
                                                                                                                                                        <div class="flex-1 min-w-0">
                                                                                                                                                            <p id="absensiStatusTeks" class="font-black text-gray-800 text-sm leading-tight">Memuat status...</p>
                                                                                                                                                            <p id="absensiSubTeks" class="text-xs text-gray-500 mt-0.5">
                                                                                                                                                            </p>
                                                                                                                                                            <div id="absensiBarWrap" class="mt-2 hidden">
                                                                                                                                                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                                                                                                                                                    <div id="absensiBar" class="h-2 rounded-full bg-blue-400 transition-all duration-1000" style="width:100%">
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                                <p id="absensiCountdown" class="text-[10px] font-black text-gray-400 mt-1 text-right tabular-nums">
                                                                                                                                                                </p>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                    <div id="absensiManualBtn" class="mt-4 hidden">
                                                                                                                                                        <!-- Tombol aktif (hijau/biru) -->
                                                                                                                                                        <a id="absensiManualBtnAktif" href="/manualabs.html" class="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-black py-3 rounded-2xl transition text-sm shadow-md">
                                                                                                                                                            <i class="fas fa-camera">
                                                                                                                                                            </i>Hadir Manual (Foto Selfie)
                            </a>
                                                                                                                                                            <!-- Tombol nonaktif (abu-abu, tidak bisa diklik) -->
                                                                                                                                                            <div id="absensiManualBtnMati" class="hidden flex items-center justify-center gap-2 w-full bg-gray-300 text-gray-500 font-black py-3 rounded-2xl text-sm cursor-not-allowed select-none">
                                                                                                                                                                <i class="fas fa-camera-slash">
                                                                                                                                                                </i>Absen Manual Dinonaktifkan
                            </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                    <div class="mb-8">
                                                                                                                                                        <div class="flex items-center justify-between mb-4 px-2">
                                                                                                                                                            <h3 class="font-black text-lg text-gray-800 tracking-tight">Layanan Tersedia</h3>
                                                                                                                                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pilih Salah Satu</span>
                                                                                                                                                        </div>
                                                                                                                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                                                                                                                            <button onclick="navigate('aspirasi')" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-red-200 transition-all flex items-center gap-4 text-left group w-full">
                                                                                                                                                                <div class="w-14 h-14 bg-red-50 text-red-500 rounded-2xl group-hover:bg-red-500 group-hover:text-white transition-colors duration-300 flex justify-center items-center flex-shrink-0">
                                                                                                                                                                    <i class="fas fa-bullhorn text-2xl">
                                                                                                                                                                    </i>
                                                                                                                                                                </div>
                                                                                                                                                                <div>
                                                                                                                                                                    <h4 class="font-black text-gray-800 text-base mb-0.5 group-hover:text-red-600 transition-colors">Lapor Aspirasi</h4>
                                                                                                                                                                    <p class="text-[11px] text-gray-500 font-medium leading-snug">Laporkan pelanggaran disiplin atau fasilitas rusak secara anonim.</p>
                                                                                                                                                                </div>
                                                                                                                                                            </button>
                                                                                                                                                            <button onclick="navigate('masukan')" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-green-200 transition-all flex items-center gap-4 text-left group w-full">
                                                                                                                                                                <div class="w-14 h-14 bg-green-50 text-green-500 rounded-2xl group-hover:bg-green-500 group-hover:text-white transition-colors duration-300 flex justify-center items-center flex-shrink-0">
                                                                                                                                                                    <i class="fas fa-comment-dots text-2xl">
                                                                                                                                                                    </i>
                                                                                                                                                                </div>
                                                                                                                                                                <div>
                                                                                                                                                                    <h4 class="font-black text-gray-800 text-base mb-0.5 group-hover:text-green-600 transition-colors">Kotak Masukan</h4>
                                                                                                                                                                    <p class="text-[11px] text-gray-500 font-medium leading-snug">Kirimkan saran, ide positif, atau kritik membangun untuk sekolah.</p>
                                                                                                                                                                </div>
                                                                                                                                                            </button>
                                                                                                                                                            <button onclick="navigate('konselor')" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-purple-200 transition-all flex items-center gap-4 text-left group w-full">
                                                                                                                                                                <div class="w-14 h-14 bg-purple-50 text-purple-500 rounded-2xl group-hover:bg-purple-500 group-hover:text-white transition-colors duration-300 flex justify-center items-center flex-shrink-0">
                                                                                                                                                                    <i class="fas fa-user-graduate text-2xl">
                                                                                                                                                                    </i>
                                                                                                                                                                </div>
                                                                                                                                                                <div>
                                                                                                                                                                    <h4 class="font-black text-gray-800 text-base mb-0.5 group-hover:text-purple-600 transition-colors">Jadwal Konseling</h4>
                                                                                                                                                                    <p class="text-[11px] text-gray-500 font-medium leading-snug">Atur janji temu dan bimbingan pribadi dengan Guru BK atau Wali Kelas.</p>
                                                                                                                                                                </div>
                                                                                                                                                            </button>
                                                                                                                                                            <button onclick="navigate('pelanggaran')" class="bg-gradient-to-br from-blue-50 to-white p-5 rounded-2xl border border-blue-200 shadow-md hover:shadow-xl transition-all flex items-center gap-4 text-left group w-full">
                                                                                                                                                                <div class="w-14 h-14 bg-blue-100 text-blue-600 border border-blue-300 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 flex justify-center items-center flex-shrink-0 shadow-inner">
                                                                                                                                                                    <i class="fas fa-clipboard-list text-2xl">
                                                                                                                                                                    </i>
                                                                                                                                                                </div>
                                                                                                                                                                <div>
                                                                                                                                                                    <h4 class="font-black text-blue-800 text-base mb-0.5 flex items-center gap-1">Lihat Point Pelanggaran <span class="bg-blue-600 text-white text-[8px] px-1.5 py-0.5 rounded uppercase tracking-wider">Baru</span>
                                                                                                                                                                </h4>
                                                                                                                                                                <p class="text-[11px] text-blue-600/70 font-medium leading-snug">Lihat total poin Anda saat ini dan daftar sanksi tata tertib sekolah.</p>
                                                                                                                                                            </div>
                                                                                                                                                        </button>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <!-- PENGUMUMAN DARI ADMIN -->
                                                                                                                                                <div id="sectionPengumuman" class="mb-6 hidden">
                                                                                                                                                    <div class="flex items-center justify-between mb-3 px-1">
                                                                                                                                                        <h3 class="font-black text-lg text-gray-800 tracking-tight flex items-center gap-2">
                                                                                                                                                            <i class="fas fa-bullhorn text-blue-500">
                                                                                                                                                            </i> Pengumuman</h3>
                                                                                                                                                        </div>
                                                                                                                                                        <div id="listPengumumanSiswa" class="space-y-3">
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                    <div class="bg-gray-800 rounded-2xl p-5 text-center flex flex-col md:flex-row justify-between items-center gap-4 border-l-4 border-yellow-400">
                                                                                                                                                        <div class="text-left">
                                                                                                                                                            <h4 class="text-white font-bold flex items-center gap-2">
                                                                                                                                                                <i class="fas fa-life-ring text-yellow-400">
                                                                                                                                                                </i> Butuh Bantuan Cepat?</h4>
                                                                                                                                                                <p class="text-xs text-gray-400 mt-1">Tim E-Aspirasi siap membantu Anda 24/7 jika terjadi kendala.</p>
                                                                                                                                                            </div>
                                                                                                                                                            <button onclick="showHelpModal()" class="w-full md:w-auto bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest border border-gray-600 transition shadow-lg whitespace-nowrap">Hubungi Sekarang</button>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                    <!-- VIEW: PROFIL -->
                                                                                                                                                    <div id="view-profil" class="page-view hidden fade-in">
                                                                                                                                                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                                                                                                                                                            <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 md:p-8 flex items-center justify-between gap-5 relative">
                                                                                                                                                                <div class="flex items-center gap-5">
                                                                                                                                                                    <div class="w-16 h-16 md:w-20 md:h-20 bg-white/20 text-white rounded-full flex items-center justify-center text-3xl shadow-inner border border-white/30 backdrop-blur-sm flex-shrink-0">
                                                                                                                                                                        <i class="fas fa-user-circle">
                                                                                                                                                                        </i>
                                                                                                                                                                    </div>
                                                                                                                                                                    <div>
                                                                                                                                                                        <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight">Profil &amp; Preferensi</h2>
                                                                                                                                                                        <p class="text-xs font-medium text-blue-100 mt-1">Lengkapi data diri untuk mempermudah pelayanan.</p>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                                <button onclick="showQRModal()" class="bg-white text-blue-600 hover:bg-blue-50 hover:scale-105 transition-transform p-3 md:px-5 md:py-3 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg flex items-center gap-2">
                                                                                                                                                                    <i class="fas fa-qrcode text-lg">
                                                                                                                                                                    </i>
                                                                                                                                                                    <span class="hidden md:inline">Tampilkan QR</span>
                                                                                                                                                                </button>
                                                                                                                                                            </div>
                                                                                                                                                            <form id="formProfil" class="p-6 md:p-8 space-y-6">
                                                                                                                                                                <div id="profilPoinStatus" class="rounded-2xl p-5 border relative shadow-sm hidden">
                                                                                                                                                                </div>
                                                                                                                                                                <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200 relative pt-6">
                                                                                                                                                                    <span class="absolute -top-3 left-4 bg-gray-800 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-sm">Identitas Pribadi</span>
                                                                                                                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                                                                                                                                        <div>
                                                                                                                                                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nama Lengkap <span class="text-red-500">*</span>
                                                                                                                                                                        </label>
                                                                                                                                                                        <input type="text" id="profNama" required="" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 bg-white shadow-sm">
                                                                                                                                                                    </div>
                                                                                                                                                                    <div>
                                                                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nama Panggilan</label>
                                                                                                                                                                        <input type="text" id="profPanggilan" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-medium bg-white shadow-sm">
                                                                                                                                                                    </div>
                                                                                                                                                                    <div>
                                                                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">NISN</label>
                                                                                                                                                                        <input type="number" id="profNisn" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-medium bg-white shadow-sm">
                                                                                                                                                                    </div>
                                                                                                                                                                    <div>
                                                                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">NIK</label>
                                                                                                                                                                        <input type="number" id="profNik" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-medium bg-white shadow-sm">
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                            <div class="bg-blue-50 p-5 rounded-2xl border border-blue-100 relative pt-6">
                                                                                                                                                                <span class="absolute -top-3 left-4 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-sm">Data Akademik &amp; Kontak</span>
                                                                                                                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                                                                                                                                    <div>
                                                                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Kelas <span class="text-red-500">*</span>
                                                                                                                                                                    </label>
                                                                                                                                                                    <input type="text" id="profKelas" list="listKelasData" required="" class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 bg-white shadow-sm">
                                                                                                                                                                    <datalist id="listKelasData">
                                                                                                                                                                    </datalist>
                                                                                                                                                                </div>
                                                                                                                                                                <div>
                                                                                                                                                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Tanggal Lahir <span class="text-red-500">*</span>
                                                                                                                                                                </label>
                                                                                                                                                                <input type="date" id="profTgl" required="" class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 bg-white shadow-sm">
                                                                                                                                                            </div>
                                                                                                                                                            <div class="md:col-span-2">
                                                                                                                                                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nomor HP / WhatsApp Aktif</label>
                                                                                                                                                                <div class="relative">
                                                                                                                                                                    <i class="fab fa-whatsapp absolute left-4 top-3.5 text-green-500 text-lg">
                                                                                                                                                                    </i>
                                                                                                                                                                    <input type="tel" id="profWa" class="w-full pl-11 pr-4 py-3 border-2 border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 bg-white shadow-sm" placeholder="Contoh: 081234567890">
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                    <div class="bg-yellow-50 p-5 rounded-2xl border border-yellow-200 relative pt-6">
                                                                                                                                                        <span class="absolute -top-3 left-4 bg-yellow-500 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-sm">Preferensi Aplikasi</span>
                                                                                                                                                        <div>
                                                                                                                                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">Agama / Kepercayaan</label>
                                                                                                                                                            <select id="profAgama" class="w-full px-4 py-3 border-2 border-yellow-200 rounded-xl focus:ring-2 focus:ring-yellow-500 outline-none font-bold text-gray-800 bg-white cursor-pointer shadow-sm">
                                                                                                                                                                <option value="Islam">Islam</option>
                                                                                                                                                                <option value="Kristen">Kristen Protestan</option>
                                                                                                                                                                <option value="Katolik">Katolik</option>
                                                                                                                                                                <option value="Hindu">Hindu</option>
                                                                                                                                                                <option value="Buddha">Buddha</option>
                                                                                                                                                                <option value="Konghucu">Konghucu</option>
                                                                                                                                                            </select>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                    <!-- SECTION: UBAH PIN & KEAMANAN -->
                                                                                                                                                    <div class="bg-indigo-50 p-5 rounded-2xl border border-indigo-200 relative pt-6">
                                                                                                                                                        <span class="absolute -top-3 left-4 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-sm">🔒 Keamanan Login</span>
                                                                                                                                                        <div class="space-y-3">
                                                                                                                                                            <div class="flex items-center justify-between bg-white p-3 rounded-xl border border-indigo-100">
                                                                                                                                                                <div>
                                                                                                                                                                    <p class="text-sm font-black text-gray-800">PIN Login</p>
                                                                                                                                                                    <p class="text-[10px] text-gray-500" id="profilPinStatus">Memuat...</p>
                                                                                                                                                                </div>
                                                                                                                                                                <button type="button" onclick="bukaUbahPin()" class="bg-indigo-100 hover:bg-indigo-600 hover:text-white text-indigo-700 px-4 py-2 rounded-xl text-xs font-black transition flex items-center gap-1.5">
                                                                                                                                                                    <i class="fas fa-key">
                                                                                                                                                                    </i> Ubah PIN
                                    </button>
                                                                                                                                                                </div>
                                                                                                                                                                <div class="flex items-center justify-between bg-white p-3 rounded-xl border border-indigo-100">
                                                                                                                                                                    <div>
                                                                                                                                                                        <p class="text-sm font-black text-gray-800">Lupa PIN?</p>
                                                                                                                                                                        <p class="text-[10px] text-gray-500">Pulihkan menggunakan pertanyaan keamanan</p>
                                                                                                                                                                    </div>
                                                                                                                                                                    <button type="button" onclick="bukaLupaPinDariProfil()" class="bg-orange-100 hover:bg-orange-500 hover:text-white text-orange-600 px-4 py-2 rounded-xl text-xs font-black transition flex items-center gap-1.5">
                                                                                                                                                                        <i class="fas fa-question-circle">
                                                                                                                                                                        </i> Lupa PIN
                                    </button>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                                                                                                                                                                <button type="submit" id="btnSubmitProfil" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-10 rounded-xl shadow-xl transition active:scale-95 uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                                                                                                                                                    <i class="fas fa-save text-lg">
                                                                                                                                                                    </i> Simpan Permanen ke Database</button>
                                                                                                                                                                </div>
                                                                                                                                                            </form>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                    <!-- VIEW: ASPIRASI -->
                                                                                                                                                    <div id="view-aspirasi" class="page-view hidden fade-in">
                                                                                                                                                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                                                                                                                                                            <div class="bg-gradient-to-r from-red-600 to-red-800 p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                                                                                                                                                <div class="flex items-center gap-5">
                                                                                                                                                                    <div class="w-16 h-16 bg-white/20 text-white rounded-full flex items-center justify-center text-3xl shadow-inner border border-white/30 flex-shrink-0">
                                                                                                                                                                        <i class="fas fa-bullhorn">
                                                                                                                                                                        </i>
                                                                                                                                                                    </div>
                                                                                                                                                                    <div>
                                                                                                                                                                        <h2 class="text-2xl font-black text-white tracking-tight">Lapor Aspirasi</h2>
                                                                                                                                                                        <p class="text-xs font-medium text-red-100 mt-1">Identitas pelapor akan dijaga kerahasiaannya.</p>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                            <form id="formAspirasi" class="p-6 md:p-8 space-y-6">
                                                                                                                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                                                                                                                                    <div>
                                                                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nomor WA Aktif <span class="text-red-500">*</span>
                                                                                                                                                                    </label>
                                                                                                                                                                    <input type="tel" id="aspWa" required="" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none font-bold text-gray-800 bg-gray-50">
                                                                                                                                                                </div>
                                                                                                                                                                <div>
                                                                                                                                                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Tanggal Kejadian <span class="text-red-500">*</span>
                                                                                                                                                                </label>
                                                                                                                                                                <input type="date" id="aspTanggal" required="" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none font-bold text-gray-800 bg-gray-50">
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                        <div>
                                                                                                                                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Kategori Laporan <span class="text-red-500">*</span>
                                                                                                                                                        </label>
                                                                                                                                                        <select id="aspJenis" required="" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none font-bold text-gray-800 bg-gray-50 cursor-pointer">
                                                                                                                                                            <option value="" disabled="" selected="">-- Pilih --</option>
                                                                                                                                                            <option value="Bullying / Perundungan">Bullying / Perundungan / Kekerasan</option>
                                                                                                                                                            <option value="Fasilitas Rusak">Fasilitas Sekolah Rusak / Hilang</option>
                                                                                                                                                            <option value="Pelanggaran Tata Tertib">Pelanggaran Tata Tertib (Rokok, Bolos, dll)</option>
                                                                                                                                                            <option value="Pungli / Pemalakan">Pungutan Liar / Pemalakan</option>
                                                                                                                                                            <option value="Kegiatan Belajar Mengajar">Masalah KBM / Jam Kosong</option>
                                                                                                                                                            <option value="Lainnya">Lainnya</option>
                                                                                                                                                        </select>
                                                                                                                                                    </div>
                                                                                                                                                    <div>
                                                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Kronologi Detail <span class="text-red-500">*</span>
                                                                                                                                                    </label>
                                                                                                                                                    <textarea id="aspDetail" required="" maxlength="1000" rows="5" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none resize-none font-medium text-gray-800 bg-gray-50">
                                                                                                                                                    </textarea>
                                                                                                                                                </div>
                                                                                                                                                <div class="bg-gray-50 border border-gray-200 p-5 rounded-2xl">
                                                                                                                                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4 border-b border-gray-200 pb-3">
                                                                                                                                                        <div>
                                                                                                                                                            <span class="text-sm font-black text-gray-800 block">
                                                                                                                                                                <i class="fas fa-paperclip mr-1">
                                                                                                                                                                </i> Lampirkan Bukti Foto</span>
                                                                                                                                                                <span id="aspUploadStatusText" class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Sangat disarankan untuk validitas laporan</span>
                                                                                                                                                            </div>
                                                                                                                                                            <label class="relative inline-flex items-center cursor-pointer">
                                                                                                                                                                <input type="checkbox" id="aspUploadToggle" class="sr-only peer" checked="" onchange="toggleAspUpload()">
                                                                                                                                                                <div class="w-12 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500 shadow-inner">
                                                                                                                                                                </div>
                                                                                                                                                                <span class="ml-2 text-xs font-bold text-gray-600 peer-checked:text-red-600 select-none">Bawa Foto</span>
                                                                                                                                                            </label>
                                                                                                                                                        </div>
                                                                                                                                                        <div id="aspUploadContainer" class="transition-all">
                                                                                                                                                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-red-50 hover:border-red-300 transition relative overflow-hidden group cursor-pointer bg-white">
                                                                                                                                                                <input type="file" id="aspFile" accept="image/*,.heic,.heif,.HEIC,.HEIF" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewMultiFoto(this)">
                                                                                                                                                                <i class="fas fa-images text-3xl text-gray-300 mb-2 group-hover:text-red-500 transition-colors">
                                                                                                                                                                </i>
                                                                                                                                                                <p class="text-sm font-bold text-gray-700">Sentuh/Klik Upload Foto</p>
                                                                                                                                                                <p class="text-[10px] text-gray-400 mt-1">Maks. 3 foto sekaligus</p>
                                                                                                                                                            </div>
                                                                                                                                                            <div id="previewFotoContainer" class="flex flex-wrap gap-2 mt-2">
                                                                                                                                                            </div>
                                                                                                                                                            <p id="previewFotoCount" class="text-[10px] text-blue-600 font-bold mt-1 hidden">
                                                                                                                                                            </p>
                                                                                                                                                        </div>
                                                                                                                                                        <div id="previewFotoGrid" class="grid grid-cols-3 gap-2 mt-3 hidden">
                                                                                                                                                        </div>
                                                                                                                                                        <p id="fileNameDisplay" class="text-xs text-red-600 font-bold mt-2 hidden">
                                                                                                                                                        </p>
                                                                                                                                                    </div>
                                                                                                                                                    <div id="aspReasonContainer" class="hidden transition-all mt-2">
                                                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Alasan Tidak Menyertakan Bukti <span class="text-red-500">*</span>
                                                                                                                                                    </label>
                                                                                                                                                    <textarea id="aspAlasan" rows="2" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none font-medium bg-white">
                                                                                                                                                    </textarea>
                                                                                                                                                </div>
                                                                                                                                            </form>
                                                                                                                                        </div>
                                                                                                                                        <div class="pt-4 border-t border-gray-100">
                                                                                                                                            <button type="submit" id="btnSubmitAsp" class="w-full bg-red-600 hover:bg-red-700 text-white font-black py-4 rounded-xl shadow-xl transition active:scale-95 uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                                                                                                                                                <i class="fas fa-paper-plane text-lg">
                                                                                                                                                </i> Kirim Laporan Sekarang</button>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </main>
                                                                                                                                </div>
                                                                                                                                <!-- VIEW: MASUKAN -->
                                                                                                                                <div id="view-masukan" class="page-view hidden fade-in">
                                                                                                                                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                                                                                                                                        <div class="bg-gradient-to-r from-green-500 to-green-700 p-6 md:p-8 flex items-center gap-5">
                                                                                                                                            <div class="w-16 h-16 bg-white/20 text-white rounded-full flex items-center justify-center text-3xl shadow-inner border border-white/30 flex-shrink-0">
                                                                                                                                                <i class="fas fa-comment-dots">
                                                                                                                                                </i>
                                                                                                                                            </div>
                                                                                                                                            <div>
                                                                                                                                                <h2 class="text-2xl font-black text-white tracking-tight">Kirim Masukan &amp; Saran</h2>
                                                                                                                                                <p class="text-xs font-medium text-green-100 mt-1">Beri ide positif untuk kemajuan sekolah tercinta.</p>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        <form id="formMasukan" class="p-6 md:p-8 space-y-6">
                                                                                                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                                                                                                                <div>
                                                                                                                                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nomor WA Aktif <span class="text-red-500">*</span>
                                                                                                                                                </label>
                                                                                                                                                <input type="tel" id="masWa" required="" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none font-bold text-gray-800 bg-gray-50">
                                                                                                                                            </div>
                                                                                                                                            <div>
                                                                                                                                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Tanggal Kirim <span class="text-red-500">*</span>
                                                                                                                                            </label>
                                                                                                                                            <input type="date" id="masTanggal" required="" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none font-bold text-gray-800 bg-gray-50">
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <div>
                                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Kategori <span class="text-red-500">*</span>
                                                                                                                                    </label>
                                                                                                                                    <select id="masJenis" required="" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none font-bold text-gray-800 bg-gray-50 cursor-pointer">
                                                                                                                                        <option value="" disabled="" selected="">-- Pilih --</option>
                                                                                                                                        <option value="Saran Fasilitas">Saran Perbaikan Fasilitas</option>
                                                                                                                                        <option value="Saran Pembelajaran">Saran Sistem Pembelajaran</option>
                                                                                                                                        <option value="Ide Event/Kegiatan">Ide Event / Kegiatan Sekolah</option>
                                                                                                                                        <option value="Kritik Pelayanan">Kritik Pelayanan Guru/Staf</option>
                                                                                                                                        <option value="Lainnya">Masukan Lainnya</option>
                                                                                                                                    </select>
                                                                                                                                </div>
                                                                                                                                <div>
                                                                                                                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Isi Pesan <span class="text-red-500">*</span>
                                                                                                                                </label>
                                                                                                                                <textarea id="masDetail" required="" maxlength="1000" rows="6" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none resize-none font-medium text-gray-800 bg-gray-50">
                                                                                                                                </textarea>
                                                                                                                            </div>
                                                                                                                            <div class="pt-4 border-t border-gray-100">
                                                                                                                                <button type="submit" id="btnSubmitMas" class="w-full bg-green-600 hover:bg-green-700 text-white font-black py-4 rounded-xl shadow-xl transition active:scale-95 uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                                                                                                                                    <i class="fas fa-paper-plane text-lg">
                                                                                                                                    </i> Kirim Pesan Sekarang</button>
                                                                                                                                </div>
                                                                                                                            </form>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <!-- VIEW: KONSELOR -->
                                                                                                                    <div id="view-konselor" class="page-view hidden fade-in">
                                                                                                                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                                                                                                                            <div class="bg-gradient-to-r from-purple-600 to-purple-800 p-6 md:p-8 flex items-center gap-5">
                                                                                                                                <div class="w-16 h-16 bg-white/20 text-white rounded-full flex items-center justify-center text-3xl shadow-inner border border-white/30 flex-shrink-0">
                                                                                                                                    <i class="fas fa-user-graduate">
                                                                                                                                    </i>
                                                                                                                                </div>
                                                                                                                                <div>
                                                                                                                                    <h2 class="text-2xl font-black text-white tracking-tight">Atur Jadwal Konseling</h2>
                                                                                                                                    <p class="text-xs font-medium text-purple-100 mt-1">Buat janji temu dengan Guru BK atau Wali Kelas secara privat.</p>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <form id="formKonselor" class="p-6 md:p-8 space-y-6">
                                                                                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                                                                                                    <div>
                                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nomor WA Aktif <span class="text-red-500">*</span>
                                                                                                                                    </label>
                                                                                                                                    <input type="tel" id="konWa" required="" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none font-bold text-gray-800 bg-gray-50">
                                                                                                                                </div>
                                                                                                                                <div>
                                                                                                                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Pilih Tujuan Konselor <span class="text-red-500">*</span>
                                                                                                                                </label>
                                                                                                                                <select id="konDropPoint" required="" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none font-bold text-gray-800 bg-gray-50 cursor-pointer">
                                                                                                                                    <option value="" disabled="" selected="">-- Pilih Tujuan --</option>
                                                                                                                                    <option value="Guru BK Utama">Ruang Guru BK</option>
                                                                                                                                    <option value="Wali Kelas">Wali Kelas Saya</option>
                                                                                                                                    <option value="Kesiswaan">Waka Kesiswaan</option>
                                                                                                                                </select>
                                                                                                                            </div>
                                                                                                                            <div class="md:col-span-2">
                                                                                                                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Rencana Tanggal Bertemu <span class="text-red-500">*</span>
                                                                                                                            </label>
                                                                                                                            <input type="date" id="konTanggal" required="" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none font-bold text-gray-800 bg-gray-50">
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div>
                                                                                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Alasan / Topik Obrolan <span class="text-red-500">*</span>
                                                                                                                    </label>
                                                                                                                    <textarea id="konAlasan" required="" maxlength="500" rows="4" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none resize-none font-medium text-gray-800 bg-gray-50">
                                                                                                                    </textarea>
                                                                                                                </div>
                                                                                                                <div class="pt-4 border-t border-gray-100">
                                                                                                                    <button type="submit" id="btnSubmitKon" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-black py-4 rounded-xl shadow-xl transition active:scale-95 uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                                                                                                                        <i class="fas fa-calendar-check text-lg">
                                                                                                                        </i> Ajukan Jadwal Pertemuan</button>
                                                                                                                    </div>
                                                                                                                </form>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <!-- VIEW: PROGRES TIKET -->
                                                                                                        <div id="view-progres" class="page-view hidden fade-in">
                                                                                                            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
                                                                                                                <div class="p-5 md:p-6 border-b border-gray-100 bg-gray-50">
                                                                                                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
                                                                                                                        <h2 class="text-xl font-black text-gray-800 flex items-center gap-2">
                                                                                                                            <i class="fas fa-tasks text-blue-600">
                                                                                                                            </i> Status Tiket Anda</h2>
                                                                                                                            <button onclick="loadRiwayatAspirasi()" id="btnRefreshProgres" class="w-full md:w-auto bg-white text-blue-600 border border-blue-200 hover:bg-blue-50 px-4 py-2 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 shadow-sm active:scale-95 uppercase tracking-widest">
                                                                                                                                <i class="fas fa-sync-alt">
                                                                                                                                </i> Segarkan</button>
                                                                                                                            </div>
                                                                                                                            <!-- FILTER TIKET -->
                                                                                                                            <div class="flex flex-col sm:flex-row gap-2">
                                                                                                                                <div class="relative flex-1">
                                                                                                                                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm">
                                                                                                                                    </i>
                                                                                                                                    <input type="text" id="searchTiket" oninput="filterTiket()" placeholder="Cari kategori atau isi tiket..." class="w-full pl-9 pr-3 py-2 border-2 border-gray-200 rounded-xl text-sm font-medium text-gray-800 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                                                                                                                                </div>
                                                                                                                                <select id="filterStatusTiket" onchange="filterTiket()" class="w-full sm:w-auto px-3 py-2 border-2 border-gray-200 rounded-xl text-sm font-bold bg-white focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                                                                                                                                    <option value="">Semua Status</option>
                                                                                                                                    <option value="Menunggu">⏳ Menunggu</option>
                                                                                                                                    <option value="Sedang Diproses">🔄 Diproses</option>
                                                                                                                                    <option value="Selesai">✅ Selesai</option>
                                                                                                                                    <option value="Ditolak">❌ Ditolak</option>
                                                                                                                                </select>
                                                                                                                                <select id="filterTipeTiket" onchange="filterTiket()" class="w-full sm:w-auto px-3 py-2 border-2 border-gray-200 rounded-xl text-sm font-bold bg-white focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                                                                                                                                    <option value="">Semua Jenis</option>
                                                                                                                                    <option value="Laporan">📣 Laporan</option>
                                                                                                                                    <option value="Masukan">💬 Masukan</option>
                                                                                                                                    <option value="Konselor">🎓 Konseling</option>
                                                                                                                                </select>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <div class="p-6">
                                                                                                                            <div class="overflow-x-auto rounded-xl border border-gray-200">
                                                                                                                                <table class="w-full text-sm text-left whitespace-nowrap">
                                                                                                                                    <thead class="text-[10px] text-gray-500 uppercase bg-gray-100 border-b font-black tracking-widest">
                                                                                                                                        <tr>
                                                                                                                                            <th class="px-5 py-4 w-1/4">Tgl Dibuat</th>
                                                                                                                                            <th class="px-5 py-4 w-1/2">Jenis / Kategori Tiket</th>
                                                                                                                                            <th class="px-5 py-4 text-right">Status Saat Ini</th>
                                                                                                                                        </tr>
                                                                                                                                    </thead>
                                                                                                                                    <tbody id="tableProgresBody" class="font-medium text-gray-700 divide-y divide-gray-100">
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </div>
                                                                                                                            <div id="emptyProgres" class="text-center py-16 hidden">
                                                                                                                                <i class="fas fa-box-open text-6xl text-gray-200 mb-4 block">
                                                                                                                                </i>
                                                                                                                                <p class="font-black text-gray-400 text-sm uppercase tracking-widest">Belum Ada Riwayat Tiket</p>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <!-- VIEW: POINT PELANGGARAN -->
                                                                                                                <div id="view-pelanggaran" class="page-view hidden fade-in">
                                                                                                                    <div class="bg-gradient-to-br from-blue-700 to-blue-900 rounded-3xl p-6 md:p-10 text-white flex justify-between items-center mb-8 shadow-xl relative overflow-hidden border-b-4 border-yellow-400">
                                                                                                                        <div class="z-10 flex-1 relative">
                                                                                                                            <h1 class="text-3xl md:text-5xl font-black mb-1 md:mb-2 tracking-tight drop-shadow-md">Point Grisawangi</h1>
                                                                                                                            <div class="inline-flex items-center bg-white/10 backdrop-blur-md border border-white/20 px-3 md:px-4 py-1.5 md:py-2 rounded-lg text-xs md:text-sm font-bold tracking-widest uppercase mb-4 shadow-sm">
                                                                                                                                <span>SMK PGRI 1 GIRI</span>
                                                                                                                            </div>
                                                                                                                            <div id="kotakPoinSiswa" class="hidden fade-in bg-gradient-to-r from-blue-900/80 to-blue-800/80 backdrop-blur-xl border border-blue-400/50 p-4 md:p-5 rounded-2xl max-w-sm mt-2 shadow-2xl relative overflow-hidden">
                                                                                                                                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-yellow-400">
                                                                                                                                </div>
                                                                                                                                <p class="text-[10px] md:text-xs font-black text-blue-200 uppercase tracking-[0.2em] mb-1">Total Point Pelanggaran Anda</p>
                                                                                                                                <div class="flex items-baseline gap-2 md:gap-3">
                                                                                                                                    <span class="text-5xl md:text-6xl font-black leading-none text-white drop-shadow-lg" id="angkaPoinSiswa">0</span>
                                                                                                                                    <span class="text-xs md:text-sm font-bold text-yellow-300 pb-1.5 md:pb-2 tracking-wide">Poin Saat Ini</span>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="mb-6">
                                                                                                                        <h2 class="text-2xl font-black text-gray-800 mb-1 tracking-tight flex items-center gap-2">
                                                                                                                            <i class="fas fa-book text-blue-600">
                                                                                                                            </i> Buku Saku Tata Tertib</h2>
                                                                                                                        </div>
                                                                                                                        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-200 mb-8 sticky top-[72px] z-20">
                                                                                                                            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                                                                                                                                <div class="relative w-full md:flex-1">
                                                                                                                                    <i class="fas fa-search text-gray-400 absolute left-4 top-4">
                                                                                                                                    </i>
                                                                                                                                    <input type="text" id="searchPelanggaran" class="block w-full pl-11 pr-4 py-3.5 border-2 border-gray-200 rounded-xl leading-5 bg-gray-50 focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all text-sm font-bold text-gray-800" placeholder="Ketik jenis pelanggaran atau angka poin...">
                                                                                                                                </div>
                                                                                                                                <div class="w-full md:w-auto flex flex-wrap gap-2 items-center hide-scroll overflow-x-auto pb-1 md:pb-0">
                                                                                                                                    <button id="btnFilterAll" onclick="setFilterPelanggaran('all')" class="filter-btn px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-blue-600 text-white shadow-md shadow-blue-500/30 whitespace-nowrap">Semua</button>
                                                                                                                                    <button id="btnFilterRingan" onclick="setFilterPelanggaran('ringan')" class="filter-btn px-4 py-3 rounded-xl text-xs font-bold transition-all bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 whitespace-nowrap">
                                                                                                                                        <i class="fas fa-leaf mr-1.5">
                                                                                                                                        </i> Ringan</button>
                                                                                                                                        <button id="btnFilterSedang" onclick="setFilterPelanggaran('sedang')" class="filter-btn px-4 py-3 rounded-xl text-xs font-bold transition-all bg-yellow-50 text-yellow-700 hover:bg-yellow-100 border border-yellow-200 whitespace-nowrap">
                                                                                                                                            <i class="fas fa-exclamation-triangle mr-1.5">
                                                                                                                                            </i> Sedang</button>
                                                                                                                                            <button id="btnFilterBerat" onclick="setFilterPelanggaran('berat')" class="filter-btn px-4 py-3 rounded-xl text-xs font-bold transition-all bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 whitespace-nowrap">
                                                                                                                                                <i class="fas fa-skull-crossbones mr-1.5">
                                                                                                                                                </i> Berat</button>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                                                                                                                                        <div class="bg-gray-50 px-6 py-5 border-b border-gray-200 flex items-center">
                                                                                                                                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3">
                                                                                                                                                <i class="fas fa-list-ol">
                                                                                                                                                </i>
                                                                                                                                            </div>
                                                                                                                                            <div>
                                                                                                                                                <h3 class="text-lg font-black text-gray-800">Daftar Poin &amp; Kelakuan</h3>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        <div class="overflow-x-auto">
                                                                                                                                            <table class="min-w-full divide-y divide-gray-100 text-left whitespace-nowrap md:whitespace-normal">
                                                                                                                                                <thead class="bg-white">
                                                                                                                                                    <tr>
                                                                                                                                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-16">No</th>
                                                                                                                                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Deskripsi Kelakuan / Pelanggaran</th>
                                                                                                                                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-24">Kategori</th>
                                                                                                                                                        <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest w-24">Bobot</th>
                                                                                                                                                    </tr>
                                                                                                                                                </thead>
                                                                                                                                                <tbody id="tablePelanggaranBody" class="bg-white divide-y divide-gray-100 font-medium">
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                                                                                                                                        <div class="bg-gray-900 px-6 py-5 border-b border-gray-800 flex items-center">
                                                                                                                                            <div class="w-10 h-10 bg-red-500/20 text-red-500 rounded-full flex items-center justify-center mr-3 border border-red-500/30">
                                                                                                                                                <i class="fas fa-gavel">
                                                                                                                                                </i>
                                                                                                                                            </div>
                                                                                                                                            <div>
                                                                                                                                                <h3 class="text-lg font-black text-white">Tindak Lanjut &amp; Sanksi</h3>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        <div class="overflow-x-auto">
                                                                                                                                            <table class="min-w-full divide-y divide-gray-100 text-left whitespace-nowrap md:whitespace-normal">
                                                                                                                                                <thead class="bg-gray-50">
                                                                                                                                                    <tr>
                                                                                                                                                        <th class="px-6 py-4 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest w-32 border-r">Rentang Poin</th>
                                                                                                                                                        <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest w-1/2 border-r">Prosedur Tindak Lanjut</th>
                                                                                                                                                        <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Sanksi / Kompensasi</th>
                                                                                                                                                    </tr>
                                                                                                                                                </thead>
                                                                                                                                                <tbody id="tableTindakLanjutBody" class="bg-white divide-y divide-gray-100 font-medium">
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <!-- VIEW: MASALAH WEB -->
                                                                                                                                <div id="view-masalahWeb" class="page-view hidden fade-in">
                                                                                                                                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-10 text-center max-w-2xl mx-auto mt-10 relative overflow-hidden">
                                                                                                                                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-500 to-orange-500">
                                                                                                                                        </div>
                                                                                                                                        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-red-100 shadow-inner">
                                                                                                                                            <i class="fas fa-bug text-3xl">
                                                                                                                                            </i>
                                                                                                                                        </div>
                                                                                                                                        <h2 class="text-2xl font-black text-gray-800 mb-2 tracking-tight">Lapor Masalah Sistem</h2>
                                                                                                                                        <form id="formMasalahWeb" class="text-left space-y-5 bg-gray-50 p-6 rounded-2xl border border-gray-200 mt-6">
                                                                                                                                            <div>
                                                                                                                                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nomor WA Anda</label>
                                                                                                                                                <input type="tel" id="webWa" required="" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-red-500 font-bold text-gray-800 bg-white shadow-sm">
                                                                                                                                            </div>
                                                                                                                                            <div>
                                                                                                                                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Jelaskan Kendala</label>
                                                                                                                                                <textarea id="webDetail" required="" rows="4" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-red-500 resize-none font-medium text-gray-800 bg-white shadow-sm">
                                                                                                                                                </textarea>
                                                                                                                                            </div>
                                                                                                                                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-black py-4 rounded-xl shadow-lg transition-all active:scale-95 uppercase tracking-widest text-sm">
                                                                                                                                                <i class="fab fa-whatsapp text-lg mr-2">
                                                                                                                                                </i> Lapor via WhatsApp IT</button>
                                                                                                                                            </form>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <!-- Alert Modal Generic -->
                                                                                                                                    <div id="alertModal" class="fixed inset-0 bg-black/60 z-[110] flex items-center justify-center p-4 hidden backdrop-blur-sm">
                                                                                                                                        <div class="bg-white rounded-3xl max-w-sm w-full p-8 text-center shadow-2xl border-t-8 border-blue-500 fade-in relative">
                                                                                                                                            <div id="alertIcon" class="text-6xl mb-4">
                                                                                                                                            </div>
                                                                                                                                            <h3 id="alertTitle" class="text-xl font-black mb-2 text-gray-800 tracking-tight">
                                                                                                                                            </h3>
                                                                                                                                            <p id="alertMessage" class="text-gray-500 text-sm mb-8 font-medium leading-relaxed">
                                                                                                                                            </p>
                                                                                                                                            <div id="alertActionContainer" class="space-y-3">
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <script>
        // =======================================================
        // FUNGSI BANTUAN FORMATTING
        // =======================================================
        function formatTglToDDMMYYYY(dateString) {
            if(!dateString) return "-";
            const parts = dateString.split('-');
            if(parts.length === 3) { return `${parts[2]}/${parts[1]}/${parts[0]}`; }
            return dateString;
        }

        // =======================================================
        // STATE GLOBAL & API
        // =======================================================
        let userData = { id: '', nama: '', nama_panggilan: '', nik: '', nisn: '', no_telp: '', kelas: '', gender: '', tglLahir: '', rawTgl: '', total_poin: 0, login_pin: '', security_answer: '' };
        let settingApp = { waEa: "6282326528169", waGuru: "6281216666182", waDev: "6283863400384", waEaLabel: "E-Aspirasi CS", waGuruLabel: "Admin Guru", waDevLabel: "IT / Developer", kelas_readonly: true, tampilkan_poin: true, audio_bgm: "", brosur_aktif: false, link_brosur: "", link_intro: "" };
        // Load knownRiwayat dari localStorage agar tidak reset saat web dibuka ulang
        let knownRiwayat = JSON.parse(localStorage.getItem('ea_known_riwayat') || '[]');
        let riwayatPollingInterval = null;
        let currentFilterPelanggaran = 'all';
        let currentSearchPelanggaran = '';

        async function callBackend(payload) {
            const response = await fetch('/api/notify', {
                method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
            });
            if (!response.ok) { const err = await response.text(); throw new Error(err); }
            return await response.json();
        }

        // =======================================================
        // TATA TERTIB & POIN
        // =======================================================
        const pelanggaranDataRaw = [
            { id: "1", kelakuan: "Membuat keributan/kegaduhan dalam kelas pada saat PBM", point: 6, kategori: "Kedisiplinan" },
            { id: "2", kelakuan: "Mengubah isi absensi tanpa sepengetahuan guru/pengurus kelas", point: 6, kategori: "Kedisiplinan" },
            { id: "3", kelakuan: "Membuat kegaduhan saat upacara", point: 6, kategori: "Kedisiplinan" },
            { id: "4", kelakuan: "Masuk/keluar lingkungan sekolah dengan loncat pagar", point: 20, kategori: "Kedisiplinan" },
            { id: "5", kelakuan: "Mengotori(mencoret-coret) benda milik sekolah, guru/karyawan", point: 6, kategori: "Kedisiplinan" },
            { id: "6", kelakuan: "Merusak/menghilangkan barang milik sekolah, siswa wajib memperbaiki/mengganti", point: 20, kategori: "Kedisiplinan" },
            { id: "7", kelakuan: "Mengambil/mencuri barang milik orang lain dan sekolah", point: 40, kategori: "Kedisiplinan" },
            { id: "8", kelakuan: "Makan dan minum didalam kelas saat PBM", point: 6, kategori: "Kedisiplinan" },
            { id: "9", kelakuan: "Membuang sampah tidak pada tempatnya", point: 6, kategori: "Kedisiplinan" },
            { id: "10", kelakuan: "Membawa benda yang tidak ada kaitannya dengan PBM", point: 6, kategori: "Kedisiplinan" },
            { id: "11", kelakuan: "Bertengkar/pertentangan dengan teman di lingkungan sekolah", point: 30, kategori: "Kedisiplinan" },
            { id: "12", kelakuan: "Melakukan perkelahian di sekolah maupun diluar sekolah", point: 50, kategori: "Kedisiplinan" },
            { id: "13", kelakuan: "Mengoperasikan HP ketika PBM", point: 30, kategori: "Kedisiplinan" },
            { id: "14", kelakuan: "Hamil/menghamili/Menikah", point: 110, kategori: "Kedisiplinan" },
            { id: "15", kelakuan: "Melakukan perjudian dan sejenisnya di sekolah maupun luar sekolah", point: 50, kategori: "Kedisiplinan" },
            { id: "16", kelakuan: "Membawa rokok di sekolah", point: 30, kategori: "Kedisiplinan" },
            { id: "17", kelakuan: "Merokok/menghisap rokok di sekolah", point: 40, kategori: "Kedisiplinan" },
            { id: "18", kelakuan: "Menyalahgunakan uang sekolah untuk kepentingan lain", point: 10, kategori: "Kedisiplinan" },
            { id: "19", kelakuan: "Memiliki, membawa atau memperlihatkan buku porno, majalah/video terlarang", point: 30, kategori: "Kedisiplinan" },
            { id: "20.1", kelakuan: "Melakukan tindakan asusila / pornografi : Tindakan asusila ringan", point: 51, kategori: "Kedisiplinan" },
            { id: "20.2", kelakuan: "Melakukan tindakan asusila / pornografi : Tindakan asusila sedang", point: 81, kategori: "Kedisiplinan" },
            { id: "20.3", kelakuan: "Melakukan tindakan asusila / pornografi : Tindakan asusila berat", point: 110, kategori: "Kedisiplinan" },
            { id: "21", kelakuan: "Membawa senjata tajam tanpa izin", point: 30, kategori: "Kedisiplinan" },
            { id: "22", kelakuan: "Memperjual belikan senjata tajam di sekolah", point: 40, kategori: "Kedisiplinan" },
            { id: "23", kelakuan: "Menggunakan senjata tajam untuk mengancam", point: 50, kategori: "Kedisiplinan" },
            { id: "24", kelakuan: "Menggunakan senjata tajam untuk melukai", point: 60, kategori: "Kedisiplinan" },
            { id: "25", kelakuan: "Melakukan tindakan kriminal, baik di sekolah maupun diluar sekolah", point: 110, kategori: "Kedisiplinan" },
            { id: "26", kelakuan: "Membawa obat/minuman terlarang", point: 80, kategori: "Kedisiplinan" },
            { id: "27", kelakuan: "Menggunakan obat/minuman terlarang didalam lingkungan sekolah", point: 110, kategori: "Kedisiplinan" },
            { id: "28", kelakuan: "Memperjual belikan obat/minuman terlarang didalam/diluar sekolah", point: 110, kategori: "Kedisiplinan" },
            { id: "29", kelakuan: "Menghina/berlaku tidak sopan terhadap guru, karyawan dengan berbagai cara", point: 30, kategori: "Kedisiplinan" },
            { id: "30", kelakuan: "Melakukan perlawanan secara fisik maupun nonfisik terhadap guru, karyawan", point: 110, kategori: "Kedisiplinan" },
            { id: "31", kelakuan: "Terlambat hadir di sekolah", point: 6, kategori: "Tanggung Jawab" },
            { id: "32", kelakuan: "Tidak masuk sekolah tanpa izin", point: 10, kategori: "Tanggung Jawab" },
            { id: "33", kelakuan: "Meninggalkan sekolah tanpa izin", point: 10, kategori: "Tanggung Jawab" },
            { id: "34", kelakuan: "Tidak mengikuti kegiatan ekstra kulikuler yang dipilih", point: 6, kategori: "Tanggung Jawab" },
            { id: "35", kelakuan: "Tidak mengikuti jam pelajaran tanpa izin", point: 10, kategori: "Tanggung Jawab" },
            { id: "36", kelakuan: "Tidak mengerjakan tugas sekolah", point: 10, kategori: "Tanggung Jawab" },
            { id: "37", kelakuan: "Tidak mengikuti upacara", point: 15, kategori: "Tanggung Jawab" },
            { id: "38", kelakuan: "Mengikuti upacara tidak tertib", point: 10, kategori: "Tanggung Jawab" },
            { id: "39", kelakuan: "Izin keluar saat proses belajar berlangsung dan tidak kembali", point: 10, kategori: "Tanggung Jawab" },
            { id: "40", kelakuan: "Siswa tidak masuk dengan membuat keterangan/surat palsu", point: 15, kategori: "Tanggung Jawab" },
            { id: "41", kelakuan: "Melanggar UU No. 22/2009 tentang tata tertib lalu lintas di lingkungan sekolah", point: 25, kategori: "Tanggung Jawab" },
            { id: "42", kelakuan: "Memakai seragam tidak rapi/tidak dimasukkan", point: 4, kategori: "Kerapian" },
            { id: "43", kelakuan: "Mengenakan seragam sekolah tanpa atribut (bed, dasi, dll)", point: 4, kategori: "Kerapian" },
            { id: "44", kelakuan: "Mengenakan pakaian terlalu ketat", point: 4, kategori: "Kerapian" },
            { id: "45", kelakuan: "Tidak memakai kaos kaki", point: 4, kategori: "Kerapian" },
            { id: "46", kelakuan: "Tidak memakai sabuk", point: 4, kategori: "Kerapian" },
            { id: "47", kelakuan: "Mengenakan atribut sekolah lain", point: 6, kategori: "Kerapian" },
            { id: "48", kelakuan: "Mengenakan seragam dengan coret-coretan", point: 6, kategori: "Kerapian" },
            { id: "49", kelakuan: "Berambut gondrong/panjang bagi siswa putra", point: 10, kategori: "Kerapian" },
            { id: "50", kelakuan: "Rambut dicat/diwarnai selain hitam bagi siswa putra putri", point: 20, kategori: "Kerapian" },
            { id: "51", kelakuan: "Mengenakan perhiasan yang berlebihan bagi siswa putri", point: 10, kategori: "Kerapian" },
            { id: "52", kelakuan: "Memakai topi yang bukan topi sekolah di lingkungan sekolah", point: 4, kategori: "Kerapian" },
            { id: "53", kelakuan: "Tidak memakai seragam ketentuan hari ini", point: 10, kategori: "Kerapian" },
            { id: "54", kelakuan: "Memakai anting-anting dan aksesoris berlebihan bagi siswa putra", point: 20, kategori: "Kerapian" },
            { id: "55", kelakuan: "Bertato", point: 20, kategori: "Kerapian" },
            { id: "56", kelakuan: "Bersolek berlebihan bagi siswa putri", point: 10, kategori: "Kerapian" },
            { id: "57", kelakuan: "Memakai sepatu tidak sesuai dengan warna ketentuan", point: 10, kategori: "Kerapian" }
        ];

        const tindakLanjutData = [
            { max: 10, tindak: "Ditangani tim ketertiban dan dikonfirmasi ke wali kelas", kompensasi: "Peringatan lisan" },
            { max: 20, tindak: "Ditangani tim ketertiban dan dikonfirmasi ke wali kelas", kompensasi: "Membersihkan lingkungan sekolah" },
            { max: 30, tindak: "Ditangani tim ketertiban dan dikonfirmasi ke wali kelas dan BK", kompensasi: "Peringatan tertulis dan melaksanakan tugas dari sekolah" },
            { max: 40, tindak: "Ditangani tim ketertiban dan dikonfirmasi ke wali kelas dan BK, serta pemanggilan orang tua", kompensasi: "Peringatan tertulis dan pemanggilan orang tua" },
            { max: 50, tindak: "Ditangani tim ketertiban dan dikonfirmasi ke wali kelas dan BK, serta pemanggilan orang tua", kompensasi: "Melaksanakan tugas dari sekolah dan membuat surat pernyataan diatas materai 10.000 serta diketahui orang tua" },
            { max: 60, tindak: "Ditangani tim ketertiban dan dikonfirmasi ke wali kelas dan BK, serta pemanggilan orang tua", kompensasi: "Membuat surat pernyataan diatas materai 10.000 diketahui orang tua serta skorsing 2 hari kalender" },
            { max: 70, tindak: "Ditangani tim ketertiban dan dikonfirmasi ke wali kelas dan BK, serta pemanggilan orang tua", kompensasi: "Membuat surat pernyataan diatas materai 10.000 diketahui orang tua serta skorsing 3 hari kalender" },
            { max: 80, tindak: "Ditangani tim ketertiban dan dikonfirmasi ke wali kelas dan BK, serta pemanggilan orang tua", kompensasi: "Membuat surat pernyataan diatas materai 10.000 diketahui orang tua serta skorsing 4 hari kalender" },
            { max: 90, tindak: "Ditangani tim ketertiban dan dikonfirmasi ke wali kelas dan BK, serta pemanggilan orang tua", kompensasi: "Membuat surat pernyataan diatas materai 10.000 diketahui orang tua serta skorsing 5 hari kalender" },
            { max: 100, tindak: "Ditangani tim ketertiban dan dikonfirmasi ke wali kelas dan BK, serta pemanggilan orang tua", kompensasi: "Membuat surat pernyataan diatas materai 10.000 diketahui orang tua serta skorsing 6 hari kalender" },
            { max: 999, tindak: "Konferensi kasus (Tim Ketertiban, Orang Tua, Wali Kelas, BK, K3, Waka Kesiswaan)", kompensasi: "Dikembalikan kepada orang tua" }
        ];

        function getSanksiText(point) {
            if (!point || point <= 0) return { t: "-", k: "-" };
            for(let i=0; i<tindakLanjutData.length; i++){ if(point <= tindakLanjutData[i].max) return { t: tindakLanjutData[i].tindak, k: tindakLanjutData[i].kompensasi }; }
            return { t: tindakLanjutData[tindakLanjutData.length-1].tindak, k: tindakLanjutData[tindakLanjutData.length-1].kompensasi };
        }

        function getPelanggaranDataWithCategory() {
            return pelanggaranDataRaw.map(item => {
                let kat = "Ringan";
                if (item.point > 50) kat = "Berat";
                else if (item.point > 20) kat = "Sedang";
                return { ...item, kategori: kat };
            });
        }

        function renderTabelPelanggaran() {
            const body = document.getElementById('tablePelanggaranBody');
            if (!body) return;

            const allData = getPelanggaranDataWithCategory();
            const filteredData = allData.filter(item => {
                const searchLower = currentSearchPelanggaran.toLowerCase();
                const matchSearch = item.kelakuan.toLowerCase().includes(searchLower) || item.point.toString().includes(searchLower);
                let matchLevel = true;
                if (currentFilterPelanggaran === 'ringan') matchLevel = item.point >= 1 && item.point <= 20;
                if (currentFilterPelanggaran === 'sedang') matchLevel = item.point >= 21 && item.point <= 50;
                if (currentFilterPelanggaran === 'berat') matchLevel = item.point > 50;
                return matchSearch && matchLevel;
            });

            body.innerHTML = '';
            if (filteredData.length === 0) { body.innerHTML = `<tr>
                                                                                                                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                                                                                                                        <i class="fas fa-exclamation-triangle mx-auto text-4xl text-gray-300 mb-3 block">
                                                                                                                                        </i>
                                                                                                                                        <p class="font-medium text-gray-600">Pencarian tidak ditemukan.</p>
                                                                                                                                    </td>
                                                                                                                                </tr>`; return; }

            filteredData.forEach(item => {
                let badgeClass = "bg-green-100 text-green-800 border-green-200";
                if (item.point > 50) badgeClass = "bg-red-100 text-red-800 border-red-200"; else if (item.point > 20) badgeClass = "bg-yellow-100 text-yellow-800 border-yellow-200";
                body.innerHTML += `<tr class="hover:bg-blue-50/30 transition-colors">
                                                                                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-medium">${item.id}</td>
                                                                                                                                <td class="px-6 py-4 text-sm text-gray-700 font-medium">${item.kelakuan}</td>
                                                                                                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                                                                                                    <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md text-xs font-semibold">${item.kategori}</span>
                                                                                                                                </td>
                                                                                                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                                                                                    <span class="px-3 py-1 inline-flex text-sm font-bold rounded-lg border ${badgeClass}">${item.point}</span>
                                                                                                                                </td>
                                                                                                                            </tr>`;
            });
        }

        function renderTabelTindakLanjut() {
            const body = document.getElementById('tableTindakLanjutBody');
            if (!body) return; body.innerHTML = '';
            const uiTindakLanjut = [
                { rentang: "1 - 10", tindak: tindakLanjutData[0].tindak, kompensasi: tindakLanjutData[0].kompensasi },
                { rentang: "11 - 20", tindak: tindakLanjutData[1].tindak, kompensasi: tindakLanjutData[1].kompensasi },
                { rentang: "21 - 30", tindak: tindakLanjutData[2].tindak, kompensasi: tindakLanjutData[2].kompensasi },
                { rentang: "31 - 40", tindak: tindakLanjutData[3].tindak, kompensasi: tindakLanjutData[3].kompensasi },
                { rentang: "41 - 50", tindak: tindakLanjutData[4].tindak, kompensasi: tindakLanjutData[4].kompensasi },
                { rentang: "51 - 60", tindak: tindakLanjutData[5].tindak, kompensasi: tindakLanjutData[5].kompensasi },
                { rentang: "61 - 70", tindak: tindakLanjutData[6].tindak, kompensasi: tindakLanjutData[6].kompensasi },
                { rentang: "71 - 80", tindak: tindakLanjutData[7].tindak, kompensasi: tindakLanjutData[7].kompensasi },
                { rentang: "81 - 90", tindak: tindakLanjutData[8].tindak, kompensasi: tindakLanjutData[8].kompensasi },
                { rentang: "91 - 100", tindak: tindakLanjutData[9].tindak, kompensasi: tindakLanjutData[9].kompensasi },
                { rentang: "101 - 110+", tindak: tindakLanjutData[10].tindak, kompensasi: tindakLanjutData[10].kompensasi }
            ];
            uiTindakLanjut.forEach(item => {
                body.innerHTML += `<tr class="hover:bg-gray-50/50 transition-colors">
                                                                                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                                                                                <span class="font-extrabold text-blue-800 bg-blue-50 px-3 py-1.5 rounded-lg">${item.rentang}</span>
                                                                                                                            </td>
                                                                                                                            <td class="px-6 py-4 text-sm text-gray-600 font-medium">${item.tindak}</td>
                                                                                                                            <td class="px-6 py-4 text-sm text-red-600 font-bold">${item.kompensasi}</td>
                                                                                                                        </tr>`;
            });
        }

        window.setFilterPelanggaran = function(level) {
            currentFilterPelanggaran = level;
            const btnAll = document.getElementById('btnFilterAll'); const btnRingan = document.getElementById('btnFilterRingan'); const btnSedang = document.getElementById('btnFilterSedang'); const btnBerat = document.getElementById('btnFilterBerat');
            if(btnAll) btnAll.className = "filter-btn px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-gray-100 text-gray-600 hover:bg-gray-200";
            if(btnRingan) btnRingan.className = "filter-btn flex items-center px-4 py-3 rounded-xl text-xs font-bold transition-all bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 whitespace-nowrap";
            if(btnSedang) btnSedang.className = "filter-btn flex items-center px-4 py-3 rounded-xl text-xs font-bold transition-all bg-yellow-50 text-yellow-700 hover:bg-yellow-100 border border-yellow-200 whitespace-nowrap";
            if(btnBerat) btnBerat.className = "filter-btn flex items-center px-4 py-3 rounded-xl text-xs font-bold transition-all bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 whitespace-nowrap";

            if (level === 'all' && btnAll) btnAll.className = "filter-btn px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-blue-600 text-white shadow-md shadow-blue-500/30 whitespace-nowrap";
            if (level === 'ringan' && btnRingan) btnRingan.className = "filter-btn flex items-center px-4 py-3 rounded-xl text-xs font-bold transition-all bg-green-500 text-white shadow-md border-transparent whitespace-nowrap";
            if (level === 'sedang' && btnSedang) btnSedang.className = "filter-btn flex items-center px-4 py-3 rounded-xl text-xs font-bold transition-all bg-yellow-500 text-white shadow-md border-transparent whitespace-nowrap";
            if (level === 'berat' && btnBerat) btnBerat.className = "filter-btn flex items-center px-4 py-3 rounded-xl text-xs font-bold transition-all bg-red-500 text-white shadow-md border-transparent whitespace-nowrap";
            renderTabelPelanggaran();
        };

        // =======================================================
        // SETUP AWAL (PENGATURAN & JAM)
        // =======================================================
        let lastWorshipUpdate = -1;
        let lastWorshipAlert = "";

        async function loadPengaturanAwal() {
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getPengaturan' });
                if (res.data && !res.error) {
                    res.data.forEach(p => {
                        if(p.nama_pengaturan === 'wa_ea') settingApp.waEa = p.nilai;
                        if(p.nama_pengaturan === 'wa_guru') settingApp.waGuru = p.nilai;
                        if(p.nama_pengaturan === 'wa_dev') settingApp.waDev = p.nilai;
                        if(p.nama_pengaturan === 'wa_ea_label') settingApp.waEaLabel = p.nilai || 'E-Aspirasi CS';
                        if(p.nama_pengaturan === 'wa_guru_label') settingApp.waGuruLabel = p.nilai || 'Admin Guru';
                        if(p.nama_pengaturan === 'wa_dev_label') settingApp.waDevLabel = p.nilai || 'IT / Developer';
                        if(p.nama_pengaturan === 'kelas_readonly') settingApp.kelas_readonly = (p.nilai === 'true');
                        if(p.nama_pengaturan === 'tampilkan_poin') settingApp.tampilkan_poin = (p.nilai === 'true');
                        if(p.nama_pengaturan === 'link_audio_bgm') settingApp.audio_bgm = p.nilai;
                        if(p.nama_pengaturan === 'brosur_aktif') settingApp.brosur_aktif = (p.nilai === 'true');
                        if(p.nama_pengaturan === 'link_brosur') settingApp.link_brosur = p.nilai;
                        if(p.nama_pengaturan === 'link_intro') settingApp.link_intro = p.nilai;
                    });
                    const elEa = document.getElementById('linkWaEa'); if(elEa) elEa.href = `https://wa.me/${settingApp.waEa}`;
                    const txtEa = document.getElementById('textWaEa'); if(txtEa) txtEa.textContent = `+${settingApp.waEa}`;
                    const lblEa = document.getElementById('labelWaEa'); if(lblEa) lblEa.textContent = settingApp.waEaLabel || 'E-Aspirasi CS';
                    const elGuru = document.getElementById('linkWaGuru'); if(elGuru) elGuru.href = `https://wa.me/${settingApp.waGuru}`;
                    const txtGuru = document.getElementById('textWaGuru'); if(txtGuru) txtGuru.textContent = `+${settingApp.waGuru}`;
                    const lblGuru = document.getElementById('labelWaGuru'); if(lblGuru) lblGuru.textContent = settingApp.waGuruLabel || 'Admin Guru';
                    const elDev = document.getElementById('linkWaDev'); if(elDev) elDev.href = `https://wa.me/${settingApp.waDev}`;
                    const txtDev = document.getElementById('textWaDev'); if(txtDev) txtDev.textContent = `+${settingApp.waDev}`;
                    const lblDev = document.getElementById('labelWaDev'); if(lblDev) lblDev.textContent = settingApp.waDevLabel || 'IT / Developer';
                    const btnBlk = document.getElementById('btnHubungiAdminBlokir'); if(btnBlk) btnBlk.href = `https://wa.me/${settingApp.waEa}`;

                    const inpKelas = document.getElementById('loginKelas');
                    if(inpKelas) {
                        if(settingApp.kelas_readonly) {
                            inpKelas.setAttribute('readonly', 'readonly');
                            inpKelas.classList.add('bg-gray-100', 'cursor-not-allowed', 'opacity-80');
                            inpKelas.placeholder = "Pilih nama untuk mengisi otomatis...";
                        } else {
                            inpKelas.removeAttribute('readonly');
                            inpKelas.classList.remove('bg-gray-100', 'cursor-not-allowed', 'opacity-80');
                            inpKelas.placeholder = "Ketik kelas Anda...";
                        }
                    }
                    const audioEl = document.getElementById('bgmAudio');
                    const sourceEl = document.getElementById('bgmSource');
                    if(settingApp.audio_bgm && sourceEl) { if(sourceEl.src !== settingApp.audio_bgm) { sourceEl.src = settingApp.audio_bgm; if(audioEl) audioEl.load(); } }
                    const videoEl = document.getElementById('introVideo');
                    const videoSrcEl = document.getElementById('introVideoSource');
                    if (videoEl && videoSrcEl) {
                        if (settingApp.link_intro) {
                            // Prioritas 1: URL dari setting admin di DB
                            videoSrcEl.src = settingApp.link_intro;
                            videoEl.load();
                        } else {
                            // Prioritas 2: Cek apakah /intro.mp4 ada di root hosting
                            fetch('/intro.mp4', { method: 'HEAD' })
                                .then(r => {
                                    if (r.ok) {
                                        videoSrcEl.src = '/intro.mp4';
                                        videoEl.load();
                                    }
                                    // Kalau tidak ada, biarkan kosong → error event akan skip intro
                                })
                                .catch(() => {}); // Tidak ada file → skip otomatis
                        }
                    }

                    const brosurMode = document.getElementById('brosurMode'); const textMode = document.getElementById('textMode'); const imgBrosur = document.getElementById('brosurImage');
                    if (settingApp.brosur_aktif && settingApp.link_brosur) {
                        if(brosurMode) brosurMode.classList.remove('hidden'); if(textMode) textMode.classList.add('hidden'); if(imgBrosur) imgBrosur.src = settingApp.link_brosur;
                    } else {
                        if(brosurMode) brosurMode.classList.add('hidden'); if(textMode) textMode.classList.remove('hidden');
                    }
                }
            } catch (err) {}
        }

        function initRealtimeClock() {
            setInterval(() => {
                const now = new Date(); let options = {};
                if(window.innerWidth < 768) { options = { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' }; } 
                else { options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' }; }
                document.getElementById('realtimeClock').textContent = now.toLocaleDateString('id-ID', options).replace(/\./g, ':');
                
                const currentMinute = now.getMinutes();
                if(currentMinute !== lastWorshipUpdate) {
                    lastWorshipUpdate = currentMinute;
                    const agama = localStorage.getItem('siswa_agama') || 'Islam'; const hours = now.getHours(); const currentTime = hours * 60 + currentMinute;
                    let message = ""; let currentIbadah = null; 

                    if (agama === 'Islam') {
                        const jadwal = [ { nama: 'Subuh', waktu: 4 * 60 + 15, str: '04:15 WIB' }, { nama: 'Dzuhur', waktu: 11 * 60 + 35, str: '11:35 WIB' }, { nama: 'Ashar', waktu: 14 * 60 + 50, str: '14:50 WIB' }, { nama: 'Maghrib', waktu: 17 * 60 + 45, str: '17:45 WIB' }, { nama: 'Isya', waktu: 18 * 60 + 55, str: '18:55 WIB' } ];
                        currentIbadah = jadwal.find(j => j.waktu === currentTime);
                        let next = jadwal.find(j => j.waktu > currentTime) || jadwal[0]; let besok = (next === jadwal[0] && currentTime > 19*60) ? " (Esok Hari)" : "";
                        message = `Jadwal Sholat Banyuwangi terdekat: ${next.nama} jam ${next.str}${besok}. Awali harimu dengan Bismillah!`;
                    } else if (agama === 'Kristen' || agama === 'Katolik') {
                        const day = now.getDay();
                        if (day === 0) { message = `Selamat Hari Minggu! Selamat melaksanakan Ibadah/Misa. Tuhan memberkati.`; if (currentTime === 7 * 60) currentIbadah = { nama: 'Ibadah Raya/Misa', str: '07:00 WIB' }; } 
                        else { message = `Ibadah Raya/Misa terdekat: Hari Minggu jam 07:00 WIB. Selamat belajar, Tuhan memberkati!`; }
                    } else if (agama === 'Hindu') {
                        const jadwal = [ { nama: 'Pagi', waktu: 6*60, str:'06:00 WIB' }, { nama: 'Siang', waktu: 12*60, str:'12:00 WIB' }, { nama: 'Sore', waktu: 18*60, str:'18:00 WIB' } ];
                        currentIbadah = jadwal.find(j => j.waktu === currentTime); let next = jadwal.find(j => j.waktu > currentTime) || jadwal[0];
                        message = `Waktu Puja Tri Sandhya terdekat: ${next.nama} jam ${next.str}. Om Shanti Shanti Shanti Om.`;
                    } else if (agama === 'Buddha') { message = `Selamat belajar. Semoga semua makhluk hidup berbahagia. Sadhu, Sadhu, Sadhu.`; } 
                    else if (agama === 'Konghucu') { message = `Huang Tian Shang Di memberkati. Selamat menjalani hari dengan semangat belajar!`; }

                    const marqueeEl = document.getElementById('worshipMarquee');
                    if(marqueeEl && marqueeEl.textContent !== message) marqueeEl.textContent = message;

                    if (currentIbadah && typeof lastWorshipAlert !== 'undefined' && lastWorshipAlert !== currentIbadah.nama) {
                        lastWorshipAlert = currentIbadah.nama;
                        Swal.fire({ title: 'Waktunya Ibadah! ✨', html: `Yuk rehat sejenak, waktunya ibadah <b>${currentIbadah.nama}</b> di pukul <b>${currentIbadah.str}</b>.`, icon: 'info', confirmButtonText: 'Baik', confirmButtonColor: '#3b82f6', backdrop: `rgba(0,0,0,0.6)` });
                    }
                }
            }, 1000);
        }

        // =======================================================
        // FUNGSI AUTOCOMPLETE SISWA UNTUK LOGIN
        // =======================================================
        function setupAutocomplete() {
            const inpNama = document.getElementById('loginNama');
            const dropdown = document.getElementById('autocompleteSiswa');
            let typingTimer;

            if(!inpNama || !dropdown) return;

            // Cache hasil pencarian agar onclick bisa akses by index (aman dari apostrophe)
            window._siswaCari = [];

            inpNama.addEventListener('input', function() {
                clearTimeout(typingTimer);
                const val = this.value.trim();
                
                if(val.length < 2) { dropdown.classList.add('hidden'); return; }
                
                typingTimer = setTimeout(async () => {
                    dropdown.innerHTML = '<li class="px-4 py-3 text-xs text-blue-500 font-bold">
                                                                                                                        <i class="fas fa-spinner fa-spin mr-2">
                                                                                                                        </i>Mencari data siswa...</li>';
                    dropdown.classList.remove('hidden');
                    
                    try {
                        const res = await callBackend({ type: 'supabase_get', action: 'cariSiswa', payload: { nama: val } });
                        if(res.data && res.data.length > 0) {
                            window._siswaCari = res.data; // simpan ke cache
                            dropdown.innerHTML = res.data.map((s, i) => `
                                <li class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-50 transition"
                                    onclick="pilihSiswaDariCache(${i})">
                                                                                                                        <div class="font-bold text-gray-800 text-sm">${s.nama}</div>
                                                                                                                        <div class="text-[10px] text-gray-500 font-black uppercase tracking-widest mt-0.5">
                                                                                                                            <span class="bg-gray-100 px-2 py-0.5 rounded border">${s.kelas}</span>
                                        ${s.nama_panggilan ? '• Panggilan: ' + s.nama_panggilan : ''}
                                        • NISN: ${s.nisn || '-'}
                                    </div>
                                                                                                                        </li>`).join('');
                        } else {
                            window._siswaCari = [];
                            dropdown.innerHTML = '<li class="px-4 py-3 text-xs text-red-500 font-bold">
                                                                                                                        <i class="fas fa-exclamation-circle mr-2">
                                                                                                                        </i>Nama tidak ditemukan. Coba ketik lebih lengkap.</li>';
                        }
                    } catch(e) { dropdown.classList.add('hidden'); }
                }, 400);
            });

            // Navigasi keyboard
            inpNama.addEventListener('keydown', function(e) {
                const items = dropdown.querySelectorAll('li[onclick]');
                const active = dropdown.querySelector('.bg-blue-100');
                let idx = Array.from(items).indexOf(active);
                if (e.key === 'ArrowDown') { e.preventDefault(); idx = Math.min(idx+1, items.length-1); }
                else if (e.key === 'ArrowUp') { e.preventDefault(); idx = Math.max(idx-1, 0); }
                else if (e.key === 'Enter' && active) { e.preventDefault(); active.click(); return; }
                else return;
                items.forEach(li => li.classList.remove('bg-blue-100'));
                if (items[idx]) { items[idx].classList.add('bg-blue-100'); items[idx].scrollIntoView({ block:'nearest' }); }
            });

            document.addEventListener('click', function(e) {
                if(!e.target.closest('#loginNama') && !e.target.closest('#autocompleteSiswa')) {
                    dropdown.classList.add('hidden');
                }
            });
        }

        window.pilihSiswaAuto = function(nama, kelas) {
            document.getElementById('loginNama').value = nama;
            document.getElementById('loginKelas').value = kelas;
            document.getElementById('autocompleteSiswa').classList.add('hidden');
        };

        // Pilih siswa dari cache (aman dari apostrophe / karakter khusus)
        window.pilihSiswaDariCache = function(i) {
            const siswa = (window._siswaCari || [])[i];
            if (!siswa) return;
            const inpNama  = document.getElementById('loginNama');
            const inpKelas = document.getElementById('loginKelas');
            const drop     = document.getElementById('autocompleteSiswa');
            if (inpNama)  inpNama.value  = siswa.nama;
            if (inpKelas) inpKelas.value = siswa.kelas;
            if (drop)     drop.classList.add('hidden');
            // Simpan sementara agar login bisa langsung cocokkan by ID
            window._selectedSiswaLogin = siswa;
        };

        // =======================================================
        // LISTENER EVENT & FORM SUBMIT
        // =======================================================
        document.addEventListener("DOMContentLoaded", async () => {
            initRealtimeClock();
            loadPengaturanAwal();
            setupAutocomplete(); 
            renderTabelPelanggaran();
            renderTabelTindakLanjut();

            const introScreen = document.getElementById('introScreen');
            const introVideo = document.getElementById('introVideo');
            const unmuteHint = document.getElementById('unmuteHint');
            const loginScreen = document.getElementById('loginScreen');

            // Cek maintenance mode DULU sebelum apapun (blocking)
                const isMaintenance = await cekMaintenanceMode().catch(()=>false);
                if (isMaintenance) return; // Stop - sudah di-redirect ke maintenance.html
                if(localStorage.getItem('ea_ingat') === 'true' && localStorage.getItem('ea_session_data')) {
                try {
                    userData = JSON.parse(localStorage.getItem('ea_session_data'));
                    if (introScreen) { introScreen.classList.add('hidden'); introScreen.style.opacity = '0'; }
                    if (introVideo) introVideo.pause();
                    if (loginScreen) loginScreen.classList.add('hidden');
                    masukKeDashboard();
                } catch(e) { localStorage.removeItem('ea_session_data'); }
            }

            function enterApp() {
                if (introScreen && !introScreen.classList.contains('hidden')) {
                    introScreen.style.opacity = '0';
                    setTimeout(() => { introScreen.classList.add('hidden'); if (loginScreen) loginScreen.classList.remove('hidden'); if (introVideo) introVideo.pause(); }, 500);
                }
            }

            const skipBtn = document.getElementById('skipIntroBtn');
            if (skipBtn) { skipBtn.addEventListener('click', (e) => { e.preventDefault(); e.stopPropagation(); enterApp(); }); }
            if (introVideo) { introVideo.addEventListener('ended', enterApp); introVideo.addEventListener('error', enterApp); }
            if (unmuteHint) {
                unmuteHint.addEventListener('click', (e) => {
                    e.preventDefault(); e.stopPropagation();
                    if (introVideo) { introVideo.muted = false; introVideo.currentTime = 0; introVideo.play().catch(err => console.log("Autoplay blocked", err)); }
                    unmuteHint.classList.add('hidden');
                });
            }

            // FORM LOGIN UTAMA SISWA
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const btn = document.getElementById('btnLoginSubmit');
                    if(btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2">
                                                                                                                    </i> Mengotentikasi...'; }

                    // Cek maintenance sebelum proses login
                    const maintActive = await cekMaintenanceMode().catch(()=>false);
                    if (maintActive) return;

                    userData.nama = document.getElementById('loginNama').value.trim();
                    userData.kelas = document.getElementById('loginKelas').value.trim();
                    userData.gender = document.getElementById('loginGender').value;
                    const inputTglLahir = document.getElementById('loginTglLahir').value;
                    let formattedTglLahir = formatTglToDDMMYYYY(inputTglLahir);
                    userData.rawTgl = inputTglLahir; userData.tglLahir = formattedTglLahir;
                    
                    try {
                        const normalizeText = (text) => text.toLowerCase().replace(/[^a-z0-9]/g, '');
                        const searchNama = normalizeText(userData.nama); const searchKelas = normalizeText(userData.kelas);
                        let matchedSiswa = null;

                        // Shortcut: jika siswa dipilih dari autocomplete, gunakan langsung
                        // (aman dari bug nama dengan karakter khusus)
                        const _cached = window._selectedSiswaLogin;
                        if (_cached && normalizeText(_cached.nama) === searchNama && normalizeText(_cached.kelas) === searchKelas) {
                            matchedSiswa = _cached;
                            window._selectedSiswaLogin = null;
                        }

                        try {
                          if (!matchedSiswa) {
                            const resultDB = await callBackend({ type: 'supabase_get', action: 'getAllSiswa' });
                            if (resultDB.data && !resultDB.error) {
                                matchedSiswa = resultDB.data.find(siswa => {
                                    if (!siswa.nama || !siswa.kelas) return false;
                                    const isNameMatch = normalizeText(siswa.nama) === searchNama;
                                    const isKelasMatch = normalizeText(siswa.kelas) === searchKelas;
                                    // Cek tgl lahir dengan berbagai format yang mungkin tersimpan di DB
                                    // Konversi nama bulan ke angka (17-Aug-2008 → 17/08/2008)
                                    const bulanMap = {jan:'01',feb:'02',mar:'03',apr:'04',may:'05',mei:'05',jun:'06',jul:'07',aug:'08',agu:'08',sep:'09',oct:'10',okt:'10',nov:'11',dec:'12',des:'12'};
                                    const normTgl = (t) => {
                                        if(!t) return '';
                                        let s = t.trim();
                                        // Ganti nama bulan jadi angka: 17-Aug-2008 → 17-08-2008
                                        s = s.replace(/([a-zA-Z]{3})/g, (m) => bulanMap[m.toLowerCase()] || m);
                                        // Normalisasi semua pemisah jadi /
                                        return s.replace(/[-]/g, '/');
                                    };
                                    const dbTgl = normTgl(siswa.tgl_lahir);
                                    const fmtSlash = normTgl(formattedTglLahir); // DD/MM/YYYY
                                    const rawSlash = normTgl(inputTglLahir);     // YYYY/MM/DD
                                    const parts = inputTglLahir.split('-');
                                    const altFmt = parts.length === 3 ? parts[2]+'/'+parts[1]+'/'+parts[0] : formattedTglLahir;
                                    const isTglMatch = dbTgl === fmtSlash || dbTgl === rawSlash || dbTgl === normTgl(altFmt) || siswa.tgl_lahir === inputTglLahir || siswa.tgl_lahir === formattedTglLahir;
                                    return isNameMatch && isKelasMatch && isTglMatch;
                                });
                            }
                          } // end if(!matchedSiswa)
                        } catch(e) {}

                        if (matchedSiswa) {
                            if (matchedSiswa.status === 'Diblokir') { if(btn) { btn.disabled = false; btn.innerHTML = 'Masuk Aplikasi'; } document.getElementById('blockedModal').classList.remove('hidden'); return; }

                            // PENTING: Fetch fresh data by ID agar login_pin & security_answer
                            // pasti ada (cariSiswa via autocomplete mungkin belum include field tsb
                            // tergantung cache). getAllSiswa sudah include semua field.
                            try {
                                const freshRes = await callBackend({ type:'supabase_get', action:'getData', payload:{ table:`siswa?id=eq.${matchedSiswa.id}&select=*&limit=1` }});
                                if (freshRes.data && freshRes.data.length > 0) {
                                    matchedSiswa = { ...matchedSiswa, ...freshRes.data[0] };
                                }
                            } catch(e) { /* pakai data matchedSiswa apa adanya */ }

                            userData.id = matchedSiswa.id || ''; userData.nama_panggilan = matchedSiswa.nama_panggilan || ''; userData.nik = matchedSiswa.nik || ''; userData.nisn = matchedSiswa.nisn || ''; userData.no_telp = matchedSiswa.no_telp || ''; userData.total_poin = matchedSiswa.total_poin || 0;
                            userData.login_pin = matchedSiswa.login_pin || ''; userData.security_answer = matchedSiswa.security_answer || '';
                            const isRemember = document.getElementById('rememberMe').checked;
                            if(btn) { btn.disabled = false; btn.innerHTML = 'Masuk Aplikasi'; }
                            // CEK PIN: jika belum ada PIN → set PIN dulu; jika sudah → minta PIN
                            cekPinSetelahLogin(matchedSiswa, isRemember);
                        } else { showValidationFailed(); }
                    } catch (error) { showValidationFailed(); }
                });
            }

            // FORM PROFIL SISWA
            const formProfil = document.getElementById('formProfil');
            if (formProfil) {
                formProfil.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    // Konfirmasi sebelum simpan profil
                    const yakinProfil = await konfirmasiTindakan('Data profil kamu akan disimpan permanen ke database.', 'Simpan Profil?');
                    if (!yakinProfil) return;
                    const btn = document.getElementById('btnSubmitProfil');
                    const namaUpdate = document.getElementById('profNama').value.trim(); const panggilanUpdate = document.getElementById('profPanggilan').value.trim(); const nisnUpdate = document.getElementById('profNisn').value.trim(); const nikUpdate = document.getElementById('profNik').value.trim(); const kelasUpdate = document.getElementById('profKelas').value.trim(); const tglRawUpdate = document.getElementById('profTgl').value; const waUpdate = document.getElementById('profWa').value.trim(); const tglFormatUpdate = formatTglToDDMMYYYY(tglRawUpdate); const agamaUpdate = document.getElementById('profAgama').value;
                    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2">
                                                                                                                </i> Menyimpan...'; }
                    try {
                        if (!userData.id) {
                            const res = await callBackend({ type: 'supabase_post', action: 'insertData', payload: { table: 'siswa', payload: { nama: namaUpdate, nama_panggilan: panggilanUpdate, nisn: nisnUpdate, nik: nikUpdate, kelas: kelasUpdate, tgl_lahir: tglFormatUpdate, no_telp: waUpdate, status: 'Aktif', total_poin: 0 } } });
                            if(res.data && res.data.length > 0) { userData.id = res.data[0].id; }
                        } else {
                            await callBackend({ type: 'supabase_post', action: 'updateData', payload: { table: 'siswa', id: userData.id, payload: { nama: namaUpdate, nama_panggilan: panggilanUpdate, nisn: nisnUpdate, nik: nikUpdate, kelas: kelasUpdate, tgl_lahir: tglFormatUpdate, no_telp: waUpdate } } });
                        }
                        userData.nama = namaUpdate; userData.nama_panggilan = panggilanUpdate; userData.nisn = nisnUpdate; userData.nik = nikUpdate; userData.kelas = kelasUpdate; userData.tglLahir = tglFormatUpdate; userData.rawTgl = tglRawUpdate; userData.no_telp = waUpdate;
                        document.getElementById('displayNama').textContent = userData.nama; document.getElementById('displayKelas').textContent = userData.kelas;
                        if (waUpdate) { localStorage.setItem('ea_wa', waUpdate); document.getElementById('aspWa').value = waUpdate; document.getElementById('masWa').value = waUpdate; document.getElementById('konWa').value = waUpdate; }
                        localStorage.setItem('siswa_agama', agamaUpdate); lastWorshipUpdate = -1;
                        if(localStorage.getItem('ea_ingat') === 'true') { localStorage.setItem('ea_session_data', JSON.stringify(userData)); }
                        sendLog('Edit Profil', 'Memperbarui biodata di menu Profil (Sinkronisasi DB Sukses).'); showAlert("Profil Diperbarui!", "Data diri dan agama Anda telah berhasil disimpan permanen ke sistem.", false);
                    } catch (err) { showAlert("Gagal Menyimpan", "Pesan Sistem: " + err.message, true); } 
                    finally { if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-save text-lg">
                                                                                                            </i> Simpan Permanen ke Database'; } }
                });
            }

            // FORM ASPIRASI
            const formAspirasi = document.getElementById('formAspirasi');
            if (formAspirasi) {
                formAspirasi.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const yakinAsp = await konfirmasiTindakan('Laporan aspirasi kamu akan dikirim ke admin sekolah.', 'Kirim Laporan?');
                    if (!yakinAsp) return;
                    const btn = document.getElementById('btnSubmitAsp'); if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2">
                                                                                                        </i> Mengirim...'; }
                    const wa = document.getElementById('aspWa').value; const detail = document.getElementById('aspDetail').value; const kat = document.getElementById('aspJenis').value; const tgl = formatTglToDDMMYYYY(document.getElementById('aspTanggal').value); const isUpload = document.getElementById('aspUploadToggle').checked; const fotoFiles = Array.from(document.getElementById('aspFile').files).slice(0,1); const alasanSkip = document.getElementById('aspAlasan').value;
                    let imgUrl = ""; let isUploadError = false; 
                    try {
                        if(isUpload && fotoFiles.length > 0) {
                            // Kompres foto sebelum upload (handles HEIC, PNG, JPG, JPEG, dll)
                            const fotoFile = fotoFiles[0];
                            try {
                                const compressed = await compressImage(fotoFile);
                                // Convert base64 to blob
                                const res = await fetch(compressed);
                                const blob = await res.blob();
                                const compressedFile = new File([blob], 'foto.jpg', { type: 'image/jpeg' });
                                const u = await uploadToFreeImage(compressedFile);
                                if(u) imgUrl = u; else isUploadError = true;
                            } catch(e) { isUploadError = true; }
                        }
                        const lampiranMsg = isUpload ? (imgUrl || 'Tanpa foto') : `Skip: ${alasanSkip}`;
                        localStorage.setItem('ea_wa', wa); userData.no_telp = wa;
                        await callBackend({ type: 'supabase_post', action: 'insertAspirasi', payload: { tipe: 'Laporan', pengirim_nama: userData.nama, pengirim_kelas: userData.kelas, pengirim_wa: wa, tanggal_kejadian: tgl, kategori: kat, detail_pesan: detail, lampiran: isUpload ? imgUrl : `{{ env('IMG_URL') }}` } });
                        sendLog('Kirim Laporan', `Kategori: ${kat}.`);
                        const msg = `🚨 <b>E-ASPIRASI BARU</b>\n\n👤 <b>Pengirim:</b> ${userData.nama}\n🏫 <b>Kelas:</b> ${userData.kelas}\n📞 <b>No. WA:</b> ${wa}\n📅 <b>Tanggal:</b> ${tgl}\n📌 <b>Kategori:</b> ${kat}\n📝 <b>Isi:</b> ${detail}\n🔗 <b>Lampiran:</b> ${lampiranMsg}`;
                        sendToTelegram(msg).catch(e=>{});
                        this.reset(); document.getElementById('aspTanggal').value = new Date().toISOString().split('T')[0]; document.getElementById('aspWa').value = wa; if(isUpload) toggleAspUpload(); 
                        if (isUploadError) { showAlert("Laporan Masuk, Foto Gagal", "Silakan kirimkan lampiran foto Anda secara manual melalui WhatsApp.", true); } else { showAlert("Terkirim!", "Laporan Anda telah berhasil masuk ke sistem.", false); }
                    } catch (err) { showAlert("Gagal Mengirim", "Terjadi kendala pada server. Silakan lapor manual via WhatsApp.", true); } finally { if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-paper-plane text-lg">
                                                                                                    </i> Kirim Laporan Sekarang'; } }
                });
            }

            // FORM MASUKAN
            const formMasukan = document.getElementById('formMasukan');
            if (formMasukan) {
                formMasukan.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const yakinMas = await konfirmasiTindakan('Masukan/saran kamu akan dikirim ke admin sekolah.', 'Kirim Masukan?');
                    if (!yakinMas) return;
                    const btn = document.getElementById('btnSubmitMas'); if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2">
                                                                                                </i> Mengirim...'; }
                    const wa = document.getElementById('masWa').value; const detail = document.getElementById('masDetail').value; const kat = document.getElementById('masJenis').value; const tgl = formatTglToDDMMYYYY(document.getElementById('masTanggal').value);
                    try {
                        localStorage.setItem('ea_wa', wa); userData.no_telp = wa;
                        await callBackend({ type: 'supabase_post', action: 'insertAspirasi', payload: { tipe: 'Masukan', pengirim_nama: userData.nama, pengirim_kelas: userData.kelas, pengirim_wa: wa, tanggal_kejadian: tgl, kategori: kat, detail_pesan: detail, lampiran: null } });
                        sendLog('Kirim Masukan', `Kategori: ${kat}.`);
                        const msg = `💡 <b>MASUKAN BARU</b>\n\n👤 <b>Pengirim:</b> ${userData.nama}\n🏫 <b>Kelas:</b> ${userData.kelas}\n📞 <b>No. WA:</b> ${wa}\n📅 <b>Tanggal:</b> ${tgl}\n📌 <b>Jenis:</b> ${kat}\n📝 <b>Isi:</b> ${detail}`;
                        sendToTelegram(msg).catch(e=>{});
                        showAlert("Terkirim!", "Masukan/saran Anda telah dikirim.", false);
                        this.reset(); document.getElementById('masTanggal').value = new Date().toISOString().split('T')[0]; document.getElementById('masWa').value = wa;
                    } catch (err) { showAlert("Gagal", "Silakan coba kirim lewat WhatsApp.", true); } finally { if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-paper-plane text-lg">
                                                                                            </i> Kirim Pesan Sekarang'; } }
                });
            }

            // FORM KONSELOR
            const formKonselor = document.getElementById('formKonselor');
            if (formKonselor) {
                formKonselor.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const yakinKon = await konfirmasiTindakan('Jadwal konseling kamu akan diajukan ke admin.', 'Ajukan Konseling?');
                    if (!yakinKon) return;
                    const btn = document.getElementById('btnSubmitKon'); if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2">
                                                                                        </i> Mengirim...'; }
                    const wa = document.getElementById('konWa').value; const drop = document.getElementById('konDropPoint').value; const alasan = document.getElementById('konAlasan').value; const tgl = formatTglToDDMMYYYY(document.getElementById('konTanggal').value);
                    try {
                        localStorage.setItem('ea_wa', wa); userData.no_telp = wa;
                        await callBackend({ type: 'supabase_post', action: 'insertAspirasi', payload: { tipe: 'Konselor', pengirim_nama: userData.nama, pengirim_kelas: userData.kelas, pengirim_wa: wa, tanggal_kejadian: tgl, kategori: drop, detail_pesan: alasan, lampiran: null } });
                        sendLog('Ajukan Konselor', `Tujuan: ${drop}.`);
                        const msg = `🤝 <b>PENGAJUAN KONSELOR</b>\n\n👤 <b>Nama:</b> ${userData.nama}\n🏫 <b>Kelas:</b> ${userData.kelas}\n📞 <b>No. WA:</b> ${wa}\n📍 <b>Tujuan:</b> ${drop}\n📅 <b>Rencana:</b> ${tgl}\n📝 <b>Alasan:</b> ${alasan}`;
                        sendToTelegram(msg).catch(e=>{});
                        showAlert("Berhasil Diajukan", "Jadwal konsultasi telah masuk.", false);
                        this.reset(); document.getElementById('konTanggal').value = new Date().toISOString().split('T')[0]; document.getElementById('konWa').value = wa;
                    } catch (err) { showAlert("Gagal", "Silakan ajukan melalui WhatsApp admin.", true); } finally { if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-calendar-check text-lg">
                                                                                    </i> Ajukan Jadwal Pertemuan'; } }
                });
            }
        });
        
        // =======================================================
        // FUNGSI NAVIGASI DASHBOARD & UI
        // =======================================================
        function masukKeDashboard() {
            setTimeout(inisialisasiTimerAbsensi, 800);
            tampilkanAppPopup();
            document.getElementById('displayNama').textContent = userData.nama; document.getElementById('displayKelas').textContent = userData.kelas;
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('aspTanggal').value = today; document.getElementById('masTanggal').value = today; document.getElementById('konTanggal').value = today;
            const savedWa = userData.no_telp || localStorage.getItem('ea_wa') || '';
            if (savedWa) { document.getElementById('aspWa').value = savedWa; document.getElementById('masWa').value = savedWa; document.getElementById('konWa').value = savedWa; document.getElementById('profWa').value = savedWa; }
            document.getElementById('loginScreen').classList.add('hidden'); document.getElementById('mainApp').classList.remove('hidden');
            muatPenilaianTerbaru();
            muatStatDashboard();
            muatPengumumanSiswa();
            document.getElementById('welcomePopup').classList.remove('hidden');
            const musicBtn = document.getElementById('musicToggleBtn'); if (musicBtn) musicBtn.classList.remove('hidden');
            const boxPoin = document.getElementById('kotakPoinSiswa'); const angkaPoin = document.getElementById('angkaPoinSiswa');
            if(settingApp.tampilkan_poin && boxPoin && angkaPoin) { boxPoin.classList.remove('hidden'); angkaPoin.textContent = userData.total_poin || 0; } else if(boxPoin) { boxPoin.classList.add('hidden'); }
            requestStudentNotificationPermission(); startStudentSmartPolling(); loadRiwayatAspirasi(true); loadDaftarKelas(); renderNotifPanel(); updateProfilPinStatus();
            // Tag user OneSignal setelah login agar notif bisa tersegmentasi per siswa
            setTimeout(() => {
                try {
                    if (window.OneSignalDeferred) {
                        OneSignalDeferred.push(async function(OneSignal) {
                            await OneSignal.User.addTags({
                                nama: userData.nama || '',
                                kelas: userData.kelas || '',
                                nisn: userData.nisn || '',
                                role: 'siswa'
                            });
                            console.log('[OneSignal] User tagged:', userData.nama);
                        });
                    }
                } catch(e) {}
            }, 2000);
        }

        function renderProfilSanksi() {
            const container = document.getElementById('profilPoinStatus'); if(!container) return;
            if(!settingApp.tampilkan_poin) { container.classList.add('hidden'); return; }
            const poin = parseInt(userData.total_poin) || 0; container.classList.remove('hidden');
            if(poin === 0) {
                container.className = "rounded-2xl p-5 border relative shadow-sm mb-6 bg-green-50 border-green-200";
                container.innerHTML = `<div class="flex items-center gap-4">
                                                                                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl flex-shrink-0">
                                                                                        <i class="fas fa-check-circle">
                                                                                        </i>
                                                                                    </div>
                                                                                    <div>
                                                                                        <h3 class="font-black text-green-800 text-lg">Poin Anda Aman!</h3>
                                                                                        <p class="text-xs text-green-700 font-medium">Selamat! Anda memiliki 0 poin pelanggaran. Terus tingkatkan kerajinan dan taati peraturan sekolah.</p>
                                                                                    </div>
                                                                                </div>`;
            } else {
                const sanksiInfo = getSanksiText(poin); container.className = "rounded-2xl p-5 border relative shadow-sm mb-6 bg-red-50 border-red-200";
                container.innerHTML = `<div class="flex items-start gap-4">
                                                                                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-2xl flex-shrink-0 mt-1">
                                                                                    <i class="fas fa-exclamation-triangle">
                                                                                    </i>
                                                                                </div>
                                                                                <div class="flex-1">
                                                                                    <h3 class="font-black text-red-800 text-lg mb-1">Peringatan Pelanggaran</h3>
                                                                                    <p class="text-xs text-red-700 font-medium mb-3">Anda saat ini memiliki total <b class="text-red-600 text-sm bg-red-100 px-2 py-0.5 rounded">${poin} Poin</b> pelanggaran tata tertib sekolah.</p>
                                                                                    <div class="bg-white rounded-xl p-3 border border-red-100 shadow-sm">
                                                                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tindak Lanjut Sekolah</p>
                                                                                        <p class="text-xs font-bold text-gray-800 mb-2">${sanksiInfo.t}</p>
                                                                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Sanksi / Kompensasi</p>
                                                                                        <p class="text-xs font-black text-red-600">${sanksiInfo.k}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>`;
            }
        }

        function showQRModal() {
            if(!userData.nisn) { return Swal.fire('NISN Kosong', 'Data NISN Anda belum terdaftar di sistem. Silakan isi dan simpan di Profil Anda terlebih dahulu.', 'warning'); }
            const modal = document.getElementById('qrModal'); const img = document.getElementById('qrImage');
            img.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(userData.nisn)}&bgcolor=f9fafb&color=1f2937`;
            document.getElementById('qrName').textContent = userData.nama; document.getElementById('qrNisn').textContent = `NISN: ${userData.nisn}`; document.getElementById('qrClass').textContent = `Kelas: ${userData.kelas}`;
            modal.classList.remove('hidden');
        }
        function closeQRModal() { document.getElementById('qrModal').classList.add('hidden'); }

        function unduhQRSiswa() {
            const img = document.getElementById('qrImage');
            const nama = document.getElementById('qrName').textContent || userData.nama || 'Siswa';
            const kelas = document.getElementById('qrClass').textContent || userData.kelas || '';
            if (!img.src || img.src === window.location.href) return;

            // Buat canvas QR card seperti admin
            const canvas = document.createElement('canvas');
            const W = 400, H = 520;
            canvas.width = W; canvas.height = H;
            const ctx = canvas.getContext('2d');

            // Background putih
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, W, H);

            // Strip biru atas
            ctx.fillStyle = '#2563eb';
            ctx.fillRect(0, 0, W, 70);
            ctx.setTransform(1,0,0,1,0,0);
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 13px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('E-ASPIRASI GRISAWANGI', W/2, 28);
            ctx.font = '11px sans-serif';
            ctx.fillText('SMK PGRI 1 Giri — Kartu Pelajar Digital', W/2, 50);

            // Nama
            ctx.fillStyle = '#1e293b';
            ctx.font = 'bold 20px sans-serif';
            const words = nama.toUpperCase().split(' ');
            let line='', y=110;
            words.forEach(w => { const t=line+w+' '; if(ctx.measureText(t).width>360&&line!==''){ctx.fillText(line.trim(),W/2,y);y+=26;line=w+' ';}else{line=t;} });
            ctx.fillText(line.trim(), W/2, y); y+=10;
            ctx.font='13px sans-serif'; ctx.fillStyle='#64748b';
            ctx.fillText(kelas, W/2, y+22);
            ctx.fillText('NISN: '+(userData.nisn||'-'), W/2, y+42);

            // QR image
            const qrImg = new Image();
            qrImg.crossOrigin = 'anonymous';
            qrImg.onload = () => {
                const qrX=(W-220)/2, qrY=y+60;
                ctx.drawImage(qrImg, qrX, qrY, 220, 220);
                // Border QR
                ctx.strokeStyle='#e2e8f0'; ctx.lineWidth=2;
                const rx=qrX-8,ry=qrY-8,rw=236,rh=236,rr=12;
                ctx.beginPath();
                ctx.moveTo(rx+rr,ry); ctx.lineTo(rx+rw-rr,ry); ctx.arcTo(rx+rw,ry,rx+rw,ry+rr,rr);
                ctx.lineTo(rx+rw,ry+rh-rr); ctx.arcTo(rx+rw,ry+rh,rx+rw-rr,ry+rh,rr);
                ctx.lineTo(rx+rr,ry+rh); ctx.arcTo(rx,ry+rh,rx,ry+rh-rr,rr);
                ctx.lineTo(rx,ry+rr); ctx.arcTo(rx,ry,rx+rr,ry,rr); ctx.closePath(); ctx.stroke();
                // Footer
                ctx.fillStyle='#94a3b8'; ctx.font='10px sans-serif';
                ctx.fillText('Scan untuk verifikasi identitas siswa', W/2, H-15);
                // Download
                const link = document.createElement('a');
                link.download = `QR_${nama.replace(/\s/g,'_')}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
            };
            qrImg.onerror = () => {
                // Fallback: langsung download img.src
                const link = document.createElement('a');
                link.download = `QR_${nama.replace(/\s/g,'_')}.png`;
                link.href = img.src;
                link.click();
            };
            qrImg.src = img.src;
        }

        function toggleSidebar() { const s = document.getElementById('sidebarMenu'); const o = document.getElementById('sidebarOverlay'); if (s) s.classList.toggle('-translate-x-full'); if (o) o.classList.toggle('hidden'); }
        function togglePlusMenu() { const menu = document.getElementById('plusMenu'); if (menu) menu.classList.toggle('hidden'); }

        function showAlert(title, msg, isError, actionHtml = '') {
            const titleEl = document.getElementById('alertTitle'); const msgEl = document.getElementById('alertMessage'); const iconDiv = document.getElementById('alertIcon'); const container = document.getElementById('alertActionContainer'); const modal = document.getElementById('alertModal');
            if (titleEl) titleEl.textContent = title; if (msgEl) msgEl.textContent = msg;
            if (iconDiv) { if(isError) { iconDiv.innerHTML = '<i class="fas fa-times-circle text-red-500">
                                                                        </i>'; } else { iconDiv.innerHTML = '<i class="fas fa-check-circle text-green-500">
                                                                    </i>'; } }
            if (container) { container.innerHTML = actionHtml || `<button onclick="closeAlert()" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl transition hover:bg-blue-700 shadow-md uppercase tracking-widest text-xs">Tutup</button>`; }
            if (modal) modal.classList.remove('hidden');
        }
        function closeAlert() { const modal = document.getElementById('alertModal'); if (modal) modal.classList.add('hidden'); }
        function showValidationFailed() { document.getElementById('validationFailedModal').classList.remove('hidden'); }
        function closeValidationModal() { document.getElementById('validationFailedModal').classList.add('hidden'); const btn = document.getElementById('btnLoginSubmit'); if(btn) { btn.disabled = false; btn.innerHTML = 'Masuk Aplikasi'; } }
        function openEmergencyModal() { closeValidationModal(); document.getElementById('emergencyCodeModal').classList.remove('hidden'); }
        function closeEmergencyModal() { document.getElementById('emergencyCodeModal').classList.add('hidden'); const btn = document.getElementById('btnLoginSubmit'); if(btn) { btn.disabled = false; btn.innerHTML = 'Masuk Aplikasi'; } document.getElementById('emergencyInput').value = ''; }
        function verifyEmergencyCode() { const input = document.getElementById('emergencyInput').value.trim(); const secretRegex = /^[a-zA-Z]{3}(984|198|326)$/; if(secretRegex.test(input)) { closeEmergencyModal(); userData.id = ''; sendLog('Login Darurat', `Masuk dengan kode pental. Nama input: ${userData.nama}, Kelas: ${userData.kelas}`); masukKeDashboard(); } else { alert("Kode Darurat Tidak Valid!"); } }
        function requestEmergencyCode() { const msg = `Nama: ${userData.nama}\nKelas: ${userData.kelas}\n\nSaya meminta kode darurat min.`; window.open(`https://wa.me/${settingApp.waEa}?text=${encodeURIComponent(msg)}`, '_blank'); }

        function closeWelcomePopup() {
            const popup = document.getElementById('welcomePopup'); if (popup) popup.classList.add('hidden');
            const audio = document.getElementById('bgmAudio'); if (audio) { audio.volume = 0.3; audio.play().catch(() => { console.log("Autoplay musik dicegah oleh browser.") }); } updateMusicIcon();
        }

        function toggleMusic() { const audio = document.getElementById('bgmAudio'); if (!audio) return; if (audio.paused) audio.play(); else audio.pause(); updateMusicIcon(); }
        function updateMusicIcon() { const audio = document.getElementById('bgmAudio'); const icon = document.getElementById('musicIcon'); const btn = document.getElementById('musicToggleBtn'); if (!audio || !icon || !btn) return; if (audio.paused) { icon.className = "fas fa-volume-mute text-xl"; btn.classList.add('opacity-50'); } else { icon.className = "fas fa-volume-up text-xl"; btn.classList.remove('opacity-50'); } }

        function showHelpModal() { const modal = document.getElementById('helpModal'); if(modal) modal.classList.remove('hidden'); }
        function closeHelpModal() { const modal = document.getElementById('helpModal'); if(modal) modal.classList.add('hidden'); }
        function handleLogout() { const modal = document.getElementById('logoutModal'); if (modal) modal.classList.remove('hidden'); }
        function closeLogoutModal() { const modal = document.getElementById('logoutModal'); if (modal) modal.classList.add('hidden'); }

        function confirmLogout() {
            if(riwayatPollingInterval) clearInterval(riwayatPollingInterval); knownRiwayat = [];
            localStorage.removeItem('ea_ingat'); localStorage.removeItem('ea_session_data'); localStorage.removeItem('ea_known_riwayat'); localStorage.removeItem('ea_notif_list');
            closeLogoutModal(); document.getElementById('mainApp').classList.add('hidden'); document.getElementById('loginScreen').classList.remove('hidden');
            const loginForm = document.getElementById('loginForm'); if(loginForm) loginForm.reset();
            const audio = document.getElementById('bgmAudio'); if (audio) { audio.pause(); audio.currentTime = 0; } updateMusicIcon();
            const plusMenu = document.getElementById('plusMenu'); if (plusMenu) plusMenu.classList.add('hidden'); window.scrollTo(0, 0);
        }

        function toggleAspUpload() {
            const isChecked = document.getElementById('aspUploadToggle').checked; const uploadContainer = document.getElementById('aspUploadContainer'); const reasonContainer = document.getElementById('aspReasonContainer'); const fileInput = document.getElementById('aspFile'); const alasanInput = document.getElementById('aspAlasan'); const statusTxt = document.getElementById('aspUploadStatusText');
            if(isChecked) { uploadContainer.classList.remove('hidden'); fileInput.required = true; reasonContainer.classList.add('hidden'); alasanInput.required = false; alasanInput.value = ""; statusTxt.textContent = "Sangat disarankan untuk validitas laporan"; } 
            else { uploadContainer.classList.add('hidden'); fileInput.required = false; fileInput.value = ""; document.getElementById('fileNameDisplay').textContent = ""; reasonContainer.classList.remove('hidden'); alasanInput.required = true; statusTxt.textContent = "Mengirim tanpa bukti visual"; }
        }

        function previewFileName(input) { const display = document.getElementById('fileNameDisplay'); if(input.files && input.files[0]) { display.innerHTML = `<i class="fas fa-file-image mr-1">
                                                                </i> ${input.files[0].name}`; } else { display.textContent = ""; } }

        function navigate(viewId) {
            document.querySelectorAll('.page-view').forEach(v => v.classList.add('hidden'));
            const targetView = document.getElementById(`view-${viewId}`);
            if (targetView) targetView.classList.remove('hidden');
            if(viewId === 'progres') { loadRiwayatAspirasi(false); } 
            else if (viewId === 'profil') {
                document.getElementById('profNama').value = userData.nama || ''; document.getElementById('profPanggilan').value = userData.nama_panggilan || ''; document.getElementById('profNik').value = userData.nik || ''; document.getElementById('profNisn').value = userData.nisn || ''; document.getElementById('profKelas').value = userData.kelas || ''; document.getElementById('profTgl').value = userData.rawTgl || ''; document.getElementById('profWa').value = userData.no_telp || localStorage.getItem('ea_wa') || ''; document.getElementById('profAgama').value = localStorage.getItem('siswa_agama') || 'Islam';
                refreshPoinDariDB().then(() => renderProfilSanksi());
            } else if (viewId === 'pelanggaran') {
                refreshPoinDariDB().then(() => {
                    const boxPoin = document.getElementById('kotakPoinSiswa'); const angkaPoin = document.getElementById('angkaPoinSiswa');
                    if(settingApp.tampilkan_poin && boxPoin && angkaPoin) { boxPoin.classList.remove('hidden'); angkaPoin.textContent = userData.total_poin || 0; }
                });
            }
            window.scrollTo(0, 0);
        }

        async function refreshPoinDariDB() {
            if (!userData.id) return;
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getAllSiswa' });
                if (res.data) {
                    const dbSiswa = res.data.find(s => s.id === userData.id);
                    if (dbSiswa) {
                        userData.total_poin = dbSiswa.total_poin || 0;
                        const angkaPoin = document.getElementById('angkaPoinSiswa');
                        if (angkaPoin) angkaPoin.textContent = userData.total_poin;
                        if (localStorage.getItem('ea_ingat') === 'true') {
                            localStorage.setItem('ea_session_data', JSON.stringify(userData));
                        }
                    }
                }
            } catch(e) {}
        }

        async function loadDaftarKelas() { try { const res = await callBackend({ type: 'supabase_get', action: 'getAllKelas' }); if (res.data) { const datalist = document.getElementById('listKelasData'); if (datalist) datalist.innerHTML = res.data.map(k => `<option value="${k.nama_kelas}">`).join(''); } } catch (e) { } }

        async function loadRiwayatAspirasi(isSilent = false) {
            const body = document.getElementById('tableProgresBody'); const empty = document.getElementById('emptyProgres'); const btnRefresh = document.getElementById('btnRefreshProgres');
            if (!isSilent && btnRefresh) btnRefresh.innerHTML = '<i class="fas fa-sync-alt fa-spin">
                                                            </i> Segarkan...'; 
            try {
                const result = await callBackend({ type: 'supabase_get', action: 'getRiwayat', payload: { nama: userData.nama } }); body.innerHTML = ''; allRiwayatData = result.data || [];
                if(result.error || !result.data || result.data.length === 0) { empty.classList.remove('hidden'); knownRiwayat = []; } 
                else {
                    empty.classList.add('hidden');
                    // Sync knownRiwayat dari localStorage — jangan overwrite yang sudah ada
                    if(knownRiwayat.length === 0) {
                        knownRiwayat = result.data.map(item => ({ id: item.id, status: item.status }));
                        localStorage.setItem('ea_known_riwayat', JSON.stringify(knownRiwayat));
                    }
                    result.data.forEach((r, i) => {
                        const isSuccess = r.status === 'Selesai'; const isProses = r.status === 'Sedang Diproses'; const isReject = r.status === 'Ditolak';
                        let statusClass = 'bg-orange-100 text-orange-700 border-orange-200'; if(isSuccess) statusClass = 'bg-green-100 text-green-700 border-green-200'; if(isProses) statusClass = 'bg-blue-100 text-blue-700 border-blue-200'; if(isReject) statusClass = 'bg-red-100 text-red-700 border-red-200';
                        const tgl = new Date(r.created_at).toLocaleDateString('id-ID', {day: '2-digit', month: 'short'});
                        body.innerHTML += `<tr class="hover:bg-gray-50 transition duration-200">
                                                            <td class="px-5 py-4 font-bold text-gray-800 text-xs">${tgl}</td>
                                                            <td class="px-5 py-4 text-[10px] md:text-xs font-bold text-gray-700 uppercase tracking-tight">${r.tipe} - ${r.kategori}</td>
                                                            <td class="px-5 py-4 text-right">
                                                                <span class="px-3 py-1.5 rounded-md text-[9px] md:text-[10px] font-bold border ${statusClass} uppercase tracking-tighter">${r.status}</span>
                                                            </td>
                                                        </tr>`;
                    });
                }
            } catch (err) { body.innerHTML = `<tr>
                                                        <td colspan="3" class="text-center py-6 text-xs text-red-500 font-bold">
                                                            <i class="fas fa-exclamation-triangle mb-2 text-2xl">
                                                            </i>
                                                            <br>Gagal terhubung ke server/database.</td>
                                                        </tr>`; empty.classList.add('hidden'); } 
            finally { if (!isSilent && btnRefresh) btnRefresh.innerHTML = '<i class="fas fa-sync-alt">
                                                    </i> Segarkan Data'; }
        }

        // ============================================================
        // ONESIGNAL: Minta izin & kirim notifikasi
        // ============================================================
        async function requestStudentNotificationPermission() {
            try {
                if (window.OneSignalDeferred) {
                    OneSignalDeferred.push(async function(OneSignal) {
                        // Cek izin saat ini
                        const permission = await OneSignal.Notifications.permission;
                        console.log('[OneSignal] Status izin:', permission);

                        if (!permission) {
                            // Tampilkan slidedown prompt custom OneSignal
                            try { await OneSignal.Slidedown.promptPush(); } catch(e) {}
                            // Fallback: minta izin native browser
                            try { await OneSignal.Notifications.requestPermission(); } catch(e) {}
                        }

                        // Tag user agar bisa kirim notif tersegmentasi per siswa
                        if (userData.nama) {
                            try {
                                await OneSignal.User.addTags({
                                    nama: userData.nama,
                                    kelas: userData.kelas || '',
                                    nisn: userData.nisn || '',
                                    role: 'siswa'
                                });
                                console.log('[OneSignal] Tag user berhasil:', userData.nama);
                            } catch(e) { console.warn('[OneSignal] Tag gagal:', e); }
                        }
                    });
                } else {
                    // Fallback kalau OneSignal tidak load
                    if ("Notification" in window && Notification.permission === "default") {
                        Notification.requestPermission();
                    }
                }
            } catch(e) { console.warn('[OneSignal] Permission error:', e); }
        }

        // Daftar notifikasi in-web
        let notifInWeb = JSON.parse(localStorage.getItem('ea_notif_list') || '[]');

        // Pesan notif sesuai status
        function getNotifByStatus(status, kategori, tipe) {
            const kat = kategori || 'laporan';
            const tp = tipe || 'Laporan';
            const map = {
                'Menunggu':       { icon: '⏳', title: 'Laporan Diterima!',       msg: `${tp} kamu tentang "${kat}" sudah masuk dan sedang menunggu antrian.` },
                'Sedang Diproses':{ icon: '🔄', title: 'Sedang Diproses!',        msg: `${tp} kamu tentang "${kat}" sedang ditangani oleh tim sekolah.` },
                'Selesai':        { icon: '✅', title: 'Laporan Selesai!',         msg: `${tp} kamu tentang "${kat}" telah diselesaikan. Terima kasih sudah melapor!` },
                'Ditolak':        { icon: '❌', title: 'Laporan Ditolak',          msg: `${tp} kamu tentang "${kat}" tidak dapat diproses. Hubungi admin untuk info lebih lanjut.` },
                'Disetujui':      { icon: '👍', title: 'Laporan Disetujui!',       msg: `${tp} kamu tentang "${kat}" telah disetujui dan akan segera ditindaklanjuti.` },
            };
            return map[status] || { icon: '🔔', title: 'Status Diperbarui', msg: `${tp} "${kat}" statusnya berubah menjadi: ${status}` };
        }

        function triggerStudentPushNotification(title, bodyText, status, kategori, tipe) {
            // Auto-generate pesan kontekstual kalau ada info status
            let notifData = { icon: '🔔', title: title, msg: bodyText };
            if (status) notifData = getNotifByStatus(status, kategori, tipe);

            // 1. Tampilkan toast SweetAlert di web
            Swal.fire({
                toast: true, position: 'top-end',
                iconHtml: `<span style="font-size:1.6rem">${notifData.icon}</span>`,
                title: `<span style="font-size:.88rem;font-weight:800">${notifData.title}</span>`,
                html: `<span style="font-size:.75rem;color:#6b7280">${notifData.msg}</span>`,
                showConfirmButton: false, timer: 5000, timerProgressBar: true,
                customClass: { popup: 'rounded-2xl shadow-2xl border border-gray-100' }
            });

            // 2. Simpan ke panel notif in-web
            tambahNotifKePanel(notifData.icon, notifData.title, notifData.msg);

            // 3. Fallback native browser notification
            if (Notification.permission === 'granted') {
                try { new Notification(notifData.title, { body: notifData.msg, icon: 'icon.png', badge: 'icon.png' }); } catch(e) {}
            }
        }

        function putarSuaraNotif() {
            try {
                const audio = document.getElementById('notifAudio');
                if (audio) { audio.currentTime = 0; audio.volume = 0.7; audio.play().catch(()=>{}); }
            } catch(e) {}
        }

        function tambahNotifKePanel(icon, title, msg) {
            const now = new Date();
            const waktu = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            const notifItem = { id: Date.now(), icon, title, msg, waktu, dibaca: false };

            // Simpan ke array & localStorage (max 20)
            notifInWeb.unshift(notifItem);
            if (notifInWeb.length > 20) notifInWeb = notifInWeb.slice(0, 20);
            localStorage.setItem('ea_notif_list', JSON.stringify(notifInWeb));

            putarSuaraNotif();
            renderNotifPanel();
        }

        function renderNotifPanel() {
            const list = document.getElementById('notifList');
            const badge = document.getElementById('notifBadge');
            const empty = document.getElementById('notifEmpty');
            if (!list) return;

            const belumDibaca = notifInWeb.filter(n => !n.dibaca).length;

            // Update badge
            if (badge) {
                if (belumDibaca > 0) {
                    badge.textContent = belumDibaca > 9 ? '9+' : belumDibaca;
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                    // Animasi ping pada lonceng
                    const bell = document.getElementById('btnNotifBell');
                    if (bell) { bell.classList.add('animate-bounce'); setTimeout(() => bell.classList.remove('animate-bounce'), 2000); }
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                }
            }

            // Render list
            if (notifInWeb.length === 0) {
                if (empty) empty.classList.remove('hidden');
                return;
            }
            if (empty) empty.classList.add('hidden');

            // Render ulang semua item
            const existingItems = list.querySelectorAll('.notif-item');
            existingItems.forEach(el => el.remove());

            notifInWeb.forEach(n => {
                const li = document.createElement('li');
                li.className = `notif-item px-4 py-3 flex items-start gap-3 transition cursor-pointer ${n.dibaca ? 'bg-white' : 'bg-blue-50/60'} hover:bg-gray-50`;
                li.onclick = () => { n.dibaca = true; localStorage.setItem('ea_notif_list', JSON.stringify(notifInWeb)); renderNotifPanel(); navigate('progres'); toggleNotifPanel(); };
                li.innerHTML = `
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xl flex-shrink-0 ${n.dibaca ? 'bg-gray-100' : 'bg-white shadow-sm border border-blue-100'}">${n.icon}</div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs font-black text-gray-800 leading-tight">${n.title}</p>
                                                        <p class="text-[11px] text-gray-500 font-medium mt-0.5 leading-snug line-clamp-2">${n.msg}</p>
                                                        <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-widest">${n.waktu}</p>
                                                    </div>
                    ${!n.dibaca ? '<div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0">
                                                </div>' : ''}
                `;
                list.appendChild(li);
            });
        }

        function toggleNotifPanel() {
            const panel = document.getElementById('notifPanel');
            if (!panel) return;
            panel.classList.toggle('hidden');
            // Tutup plusMenu jika terbuka
            const plusMenu = document.getElementById('plusMenu');
            if (plusMenu) plusMenu.classList.add('hidden');
        }

        function tandaiSemuaDibaca() {
            notifInWeb = notifInWeb.map(n => ({ ...n, dibaca: true }));
            localStorage.setItem('ea_notif_list', JSON.stringify(notifInWeb));
            renderNotifPanel();
        }

        function startStudentSmartPolling() {
            if(riwayatPollingInterval) clearInterval(riwayatPollingInterval);
            riwayatPollingInterval = setInterval(async () => {
                if(!userData.nama) return;
                try {
                    const result = await callBackend({ type: 'supabase_get', action: 'getRiwayat', payload: { nama: userData.nama } });
                    if (result.data) {
                        // Jika belum ada data referensi, simpan sebagai baseline — JANGAN kirim notif
                        if (knownRiwayat.length === 0) {
                            knownRiwayat = result.data.map(item => ({ id: item.id, status: item.status }));
                            localStorage.setItem('ea_known_riwayat', JSON.stringify(knownRiwayat));
                            return;
                        }
                        let isChanged = false;
                        result.data.forEach(newItem => {
                            const oldItem = knownRiwayat.find(k => k.id === newItem.id);
                            if (oldItem && oldItem.status !== newItem.status) {
                                // Status berubah — kirim 1 notif saja
                                isChanged = true;
                                triggerStudentPushNotification(null, null, newItem.status, newItem.kategori, newItem.tipe);
                                oldItem.status = newItem.status;
                                // Simpan perubahan ke localStorage
                                localStorage.setItem('ea_known_riwayat', JSON.stringify(knownRiwayat));
                            } else if (!oldItem) {
                                // Tiket baru — tambahkan ke referensi tanpa notif
                                knownRiwayat.push({ id: newItem.id, status: newItem.status });
                                localStorage.setItem('ea_known_riwayat', JSON.stringify(knownRiwayat));
                            }
                        });
                        if (isChanged && !document.getElementById('view-progres').classList.contains('hidden')) {
                            loadRiwayatAspirasi(true);
                        }
                    }
                } catch(e) {}
            }, 15000); 
        }

        function previewMultiFoto(input) {
            const container = document.getElementById('previewFotoContainer');
            if (!container) return;
            container.innerHTML = '';
            const countEl = document.getElementById('previewFotoCount');
            if (!input.files || !input.files.length) {
                if (countEl) countEl.classList.add('hidden');
                return;
            }
            const files = Array.from(input.files).slice(0, 1); // max 1 foto
            files.forEach((file) => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative w-20 h-20 rounded-xl overflow-hidden border-2 border-blue-200 shadow-sm flex-shrink-0';
                    wrapper.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">
                                                <div class="absolute bottom-0 left-0 right-0 bg-black/40 text-white text-[8px] text-center py-0.5 font-bold truncate px-1">' + file.name.slice(0,12) + '</div>';
                    container.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
            if (countEl) {
                countEl.textContent = files.length + ' foto dipilih';
                countEl.classList.remove('hidden');
            }
        }

                async function compressImage(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (event) => {
                    const img = new Image();
                    img.src = event.target.result;
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        let w = img.width, h = img.height;
                        // Aggressive compression: max 800px wide to keep base64 small
                        const maxW = 800;
                        if (w > maxW) { h = Math.round(h * maxW / w); w = maxW; }
                        canvas.width = w; canvas.height = h;
                        const ctx = canvas.getContext('2d');
                        ctx.fillStyle = '#ffffff';
                        ctx.fillRect(0, 0, w, h);
                        ctx.drawImage(img, 0, 0, w, h);
                        // Quality 0.70 keeps file under ~200KB base64 
                        let dataUrl = canvas.toDataURL('image/jpeg', 0.70);
                        // If still too large (>1MB base64), compress more
                        if (dataUrl.length > 1000000) {
                            dataUrl = canvas.toDataURL('image/jpeg', 0.50);
                        }
                        resolve(dataUrl);
                    };
                    img.onerror = () => reject(new Error('Gagal memuat gambar'));
                };
                reader.onerror = () => reject(new Error('Gagal membaca file'));
            });
        }

        
        async function uploadToFreeImage(file) {
            try {
                // 1. Compress first (handles HEIC, PNG, JPG, any format → JPEG ≤1MB)
                const base64Img = await compressImage(file);
                // 2. Send to backend which calls FreeImage API
                const data = await callBackend({ type: 'upload', source: base64Img });
                if (data && data.url) return data.url;
                if (data && data.error) throw new Error(data.error);
                throw new Error('Respon API tidak memiliki URL gambar');
            } catch (err) {
                console.error('Upload error:', err);
                throw new Error('Gagal upload foto: ' + (err.message || 'Unknown error'));
            }
        }
        async function sendToTelegram(text) { await callBackend({ type: 'notification', text: text }); return true; }

        // ============================================================
        // WIDGET TIMER ABSENSI v2 — BUG FIXED
        //
        // PERBAIKAN:
        // 1. Polling DB setiap 30 detik → deteksi absen via OSIS/QR
        //    yang terjadi SETELAH halaman sudah terbuka.
        // 2. Poin TIDAK dobel: terlambat = +6 total, alfa = +10 total.
        //    Jika sudah +6 (terlambat) lalu tetap alfa, hanya tambah +4.
        // 3. Re-cek DB sebelum catat poin (race condition fix).
        // 4. Semua event dicatat ke log_history (sinkron ke admin).
        // ============================================================
        let _absensiInterval     = null;
        let _absensiPollInterval = null;  // polling DB tiap 30 detik
        let _absensiPoinDicatat  = false; // flag: sudah proses poin hari ini
        let _absensiSudahHadir   = false; // flag: sudah hadir (dari DB)
        let _absensiStatusPoin   = 0;     // 0=belum, 6=terlambat, 10=alfa

        let _manualAbsenAktif = false;
        let _jamTerlambat     = 7;
        let _jamTutup         = 8;

        // ── Helper: cek record absensi hari ini dari DB ───────────────
        async function _cekAbsenHariIniDB() {
            if (!userData || !userData.id) return null;
            const hari = new Date().toISOString().split('T')[0];
            try {
                // Timeout guard 10 detik — cegah stuck selamanya jika network lambat
                const fetchPromise = callBackend({ type:'supabase_get', action:'getData',
                    payload:{ table:`absensi?id_siswa=eq.${userData.id}&created_at=gte.${hari}T00:00:00&order=created_at.desc&limit=1` }});
                const timeoutPromise = new Promise((_, reject) => setTimeout(() => reject(new Error('timeout')), 10000));
                const res = await Promise.race([fetchPromise, timeoutPromise]);
                return (res.data && res.data.length > 0) ? res.data[0] : null;
            } catch(e) { return null; }
        }

        // ── Helper: tampilkan widget "Sudah Hadir" dan hentikan SEMUA timer
        function _tampilSudahHadir(rec) {
            // Hentikan semua interval — WAJIB agar timer tidak jalan lagi
            clearInterval(_absensiInterval);
            clearInterval(_absensiPollInterval);
            _absensiSudahHadir  = true;
            _absensiPoinDicatat = true; // cegah poin dicatat lagi

            const jam  = rec && rec.created_at
                ? new Date(rec.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})
                : '';
            const late  = rec && rec.is_late ? ' (Terlambat)' : ' (Tepat Waktu)';
            const aktor = rec && rec.aktor ? ' • ' + rec.aktor : '';
            _setWidget({
                bg:'bg-green-50 border-green-300', dotCls:'bg-green-500',
                labelCls:'text-green-700', label:'✓ Sudah Hadir Hari Ini',
                iconBg:'bg-green-100', iconCls:'fas fa-check-circle text-green-500 text-2xl',
                status:'Tercatat hadir' + late + aktor,
                sub: jam ? 'Pukul ' + jam + ' WIB' : '',
                bar:false, tombol:false
            });
        }

        // ── Inisialisasi timer absensi ────────────────────────────────
        async function inisialisasiTimerAbsensi() {
            if (!userData || !userData.id) {
                // userData belum siap — tampilkan pesan jelas, jangan biarkan stuck "Memuat..."
                _setWidget({
                    bg:'bg-gray-50 border-gray-200', dotCls:'bg-gray-400',
                    labelCls:'text-gray-500', label:'Absensi',
                    iconBg:'bg-gray-100', iconCls:'fas fa-user-clock text-gray-400 text-2xl',
                    status:'Data siswa belum lengkap',
                    sub:'Lengkapi profil terlebih dahulu',
                    bar:false, tombol:false
                });
                return;
            }

            // Tampilkan status "memuat" yang jelas sambil cek DB
            _setWidget({
                bg:'bg-blue-50 border-blue-200', dotCls:'bg-blue-400 animate-pulse',
                labelCls:'text-blue-500', label:'Cek Status Absensi',
                iconBg:'bg-blue-100', iconCls:'fas fa-spinner fa-spin text-blue-500 text-2xl',
                status:'Memeriksa status kehadiran...',
                sub:'', bar:false, tombol:false
            });

            // ═══════════════════════════════════════════════════════════
            // ROOT CAUSE FIX: CEK DB DULU sebelum reset flag apapun.
            // Jika siswa sudah absen (via QR/OSIS/manual/sekretaris),
            // langsung tampil hadir & stop semua timer — TIDAK reset flag.
            // Ini mencegah status hadir hilang saat navigate ke dashboard.
            // ═══════════════════════════════════════════════════════════
            const recCepat = await _cekAbsenHariIniDB();
            if (recCepat) {
                clearInterval(_absensiInterval);
                clearInterval(_absensiPollInterval);
                _absensiSudahHadir  = true;
                _absensiPoinDicatat = true; // cegah poin dicatat lagi
                _tampilSudahHadir(recCepat);
                sendLog('Status Absensi', 'Sudah hadir via ' + (recCepat.aktor||'sistem') + '. Timer tidak dijalankan.');
                return; // STOP — tidak perlu lanjut sama sekali
            }

            // Belum hadir — baru boleh reset state dan mulai timer
            clearInterval(_absensiInterval);
            clearInterval(_absensiPollInterval);
            _absensiPoinDicatat = false;
            _absensiSudahHadir  = false;
            _absensiStatusPoin  = 0;

            // Cek hari Minggu
            const hariIni = new Date();
            if (hariIni.getDay() === 0) {
                _setWidget({
                    bg:'bg-gray-50 border-gray-200', dotCls:'bg-gray-400',
                    labelCls:'text-gray-500', label:'Hari Libur',
                    iconBg:'bg-gray-100', iconCls:'fas fa-umbrella-beach text-gray-400 text-2xl',
                    status:'Hari ini Minggu — tidak ada absensi',
                    sub:'Selamat beristirahat! 😊',
                    bar:false, tombol:false
                });
                return;
            }

            // Ambil semua setting absensi dari DB (dengan fallback aman jika gagal)
            try {
                const [rAktif, rTerlambat, rTutup, rHariLibur] = await Promise.all([
                    callBackend({ type:'supabase_get', action:'getPengaturanByKey', payload:{ key : '{{ env('KEY_9') }}' }}),
                    callBackend({ type:'supabase_get', action:'getPengaturanByKey', payload:{ key : '{{ env('KEY_10') }}' }}),
                    callBackend({ type:'supabase_get', action:'getPengaturanByKey', payload:{ key : '{{ env('KEY_11') }}' }}),
                    callBackend({ type:'supabase_get', action:'getPengaturanByKey', payload:{ key : '{{ env('KEY_12') }}' }})
                ]);

                // Cek hari libur custom
                const hariLiburStr  = rHariLibur.data?.[0]?.nilai || '';
                const hariLiburList = hariLiburStr.split(',').map(d => d.trim()).filter(Boolean);
                const todayStr      = hariIni.toISOString().split('T')[0];
                if (hariLiburList.includes(todayStr)) {
                    _setWidget({
                        bg:'bg-purple-50 border-purple-200', dotCls:'bg-purple-400',
                        labelCls:'text-purple-600', label:'Hari Libur / Nasional',
                        iconBg:'bg-purple-100', iconCls:'fas fa-flag text-purple-500 text-2xl',
                        status:'Hari ini adalah hari libur — tidak ada absensi',
                        sub:'Tanggal: ' + hariIni.toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'}),
                        bar:false, tombol:false
                    });
                    return;
                }

                _manualAbsenAktif = (rAktif.data?.[0]?.nilai === 'true');
                _jamTerlambat     = parseInt(rTerlambat.data?.[0]?.nilai || '7', 10);
                _jamTutup         = parseInt(rTutup.data?.[0]?.nilai     || '8', 10);
            } catch(e) {
                console.warn('[Absensi] Gagal load pengaturan, pakai default:', e.message);
                _manualAbsenAktif = false;
                _jamTerlambat     = 7;
                _jamTutup         = 8;
            }

            // ── FITUR BARU: Sembunyikan widget total jika admin matikan absen manual ──
            // Jika fitur absen manual dimatikan oleh admin, kolom status absensi
            // tidak perlu tampil sama sekali di dashboard siswa.
            if (!_manualAbsenAktif) {
                const w = document.getElementById('widgetAbsensi');
                if (w) w.classList.add('hidden');
                return; // Stop — tidak perlu cek apapun lagi
            } else {
                const w = document.getElementById('widgetAbsensi');
                if (w) w.classList.remove('hidden');
            }

            // ── Cek DB pertama kali sebelum mulai timer ───────────────
            // KUNCI: Jika siswa sudah absen via OSIS sebelum buka halaman,
            // langsung tampil Hadir dan TIDAK jalankan timer sama sekali.
            const recAwal = await _cekAbsenHariIniDB();
            if (recAwal) {
                _absensiSudahHadir = true;
                _tampilSudahHadir(recAwal);
                sendLog('Status Absensi', 'Terdeteksi sudah hadir saat halaman dibuka. Aktor: ' + (recAwal.aktor || 'sistem'));
                return;
            }

            // Belum hadir — mulai countdown 1 detik
            _absensiInterval = setInterval(_tickAbsensi, 1000);
            _tickAbsensi();

            // ── Polling DB setiap 15 detik ────────────────────────────
            // KUNCI: Mendeteksi absen via OSIS/QR/sekretaris yang terjadi
            // SETELAH halaman index sudah terbuka. Tanpa polling ini,
            // status tidak akan berubah meski siswa sudah di-scan OSIS.
            _absensiPollInterval = setInterval(async () => {
                if (_absensiSudahHadir) {
                    clearInterval(_absensiPollInterval);
                    return;
                }
                const rec = await _cekAbsenHariIniDB();
                if (rec) {
                    _absensiSudahHadir = true;
                    clearInterval(_absensiInterval);
                    clearInterval(_absensiPollInterval);
                    _tampilSudahHadir(rec);
                    sendLog('Status Absensi', 'Polling: siswa terdeteksi hadir via ' + (rec.aktor || 'sistem') + '. Poin otomatis tidak jadi dicatat.');
                }
            }, 15000);
        }

        // Flag: sedang menunggu hasil async cek DB — blokir aksi ganda
        let _sedangCekDB = false;

        // ── Tick setiap 1 detik ────────────────────────────────────────
        function _tickAbsensi() {
            // Sudah hadir → hentikan semua timer, jangan render apapun
            if (_absensiSudahHadir) {
                clearInterval(_absensiInterval);
                clearInterval(_absensiPollInterval);
                return;
            }

            // Sedang menunggu hasil cek DB → skip tick ini
            if (_sedangCekDB) return;

            const now   = new Date();
            const total = now.getHours() * 3600 + now.getMinutes() * 60 + now.getSeconds();

            const jamEl = document.getElementById('absensiJamSekarang');
            if (jamEl) jamEl.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

            const batasTerlambat = _jamTerlambat * 3600;
            const batasTutup     = _jamTutup * 3600;

            if (total < batasTerlambat) {
                // ── ZONA SEBELUM TERLAMBAT (00:00 - jam terlambat) ────────
                // Hanya render UI countdown. Tidak ada aksi poin di sini.
                _setWidget({
                    bg: 'bg-blue-50 border-blue-200',
                    dotCls: 'bg-blue-500 animate-pulse',
                    labelCls: 'text-blue-600',
                    label: 'Absensi Hari Ini',
                    iconBg: 'bg-blue-100',
                    iconCls: 'fas fa-camera text-blue-500 text-2xl',
                    status: 'Absensi sudah dibuka — Hadir tepat waktu!',
                    sub: 'Terlambat jika belum absen pukul ' + String(_jamTerlambat).padStart(2, '0') + ':00',
                    bar: true,
                    barColor: 'bg-blue-400',
                    barPct: Math.max(0, 100 - ((batasTerlambat - total) / batasTerlambat) * 100),
                    countdown: 'Batas tepat waktu: ' + String(_jamTerlambat).padStart(2, '0') + ':00 (' + _fmtSisa(batasTerlambat - total) + ' lagi)',
                    tombol: true
                });

            } else if (total < batasTutup) {
                // ── ZONA TERLAMBAT ─────────────────────────────────────────
                // CEK DB DULU sebelum melakukan apapun.
                // Ini adalah fix utama race condition:
                // UI negatif TIDAK boleh tampil sebelum DB dikonfirmasi.
                if (!_sedangCekDB && !_absensiSudahHadir) {
                    _sedangCekDB = true;
                    _cekAbsenHariIniDB().then(rec => {
                        _sedangCekDB = false;
                        if (rec) {
                            // ✅ Siswa sudah hadir — jangan ubah status
                            _absensiSudahHadir = true;
                            clearInterval(_absensiInterval);
                            clearInterval(_absensiPollInterval);
                            _tampilSudahHadir(rec);
                            sendLog('Status Absensi', 'Terlambat dicegah: siswa sudah hadir via ' + (rec.aktor || 'sistem'));
                        } else {
                            // ❌ Siswa belum hadir — catat poin terlambat
                            if (!_absensiPoinDicatat) {
                                _absensiPoinDicatat = true;
                                _catatTerlambat().catch(e => console.error('[Absensi] Gagal catat terlambat:', e));
                            }
                            // Render UI terlambat
                            _setWidget({
                                bg: 'bg-orange-50 border-orange-300',
                                dotCls: 'bg-orange-500',
                                labelCls: 'text-orange-600',
                                label: '⚠ Terlambat! Hadir Segera',
                                iconBg: 'bg-orange-100',
                                iconCls: 'fas fa-exclamation-triangle text-orange-500 text-2xl',
                                status: 'Sudah lewat ' + String(_jamTerlambat).padStart(2, '0') + ':00 — Kamu terlambat!',
                                sub: '+6 poin pelanggaran otomatis dicatat',
                                bar: true,
                                barColor: 'bg-orange-400',
                                barPct: Math.max(0, ((batasTutup - total) / ((_jamTutup - _jamTerlambat) * 3600)) * 100),
                                countdown: 'Absensi ditutup dalam ' + _fmtSisa(batasTutup - total) + ' (pukul ' + String(_jamTutup).padStart(2, '0') + ':00)',
                                tombol: true
                            });
                        }
                    });
                }

            } else {
                // ── ZONA ALFA / TUTUP ──────────────────────────────────────
                clearInterval(_absensiInterval);

                if (!_sedangCekDB && !_absensiSudahHadir) {
                    _sedangCekDB = true;
                    _cekAbsenHariIniDB().then(rec => {
                        _sedangCekDB = false;
                        if (rec) {
                            _absensiSudahHadir = true;
                            clearInterval(_absensiPollInterval);
                            _tampilSudahHadir(rec);
                            sendLog('Status Absensi', 'Alfa dicegah: siswa sudah hadir via ' + (rec.aktor || 'sistem'));
                        } else {
                            if (!_absensiPoinDicatat) {
                                _absensiPoinDicatat = true;
                                _catatAlfa().catch(e => console.error('[Absensi] Gagal catat alfa:', e));
                            }
                            _setWidget({
                                bg: 'bg-red-50 border-red-300',
                                dotCls: 'bg-red-500',
                                labelCls: 'text-red-600',
                                label: '✗ Absensi Ditutup (Alfa)',
                                iconBg: 'bg-red-100',
                                iconCls: 'fas fa-times-circle text-red-500 text-2xl',
                                status: 'Absensi telah ditutup pukul ' + String(_jamTutup).padStart(2, '0') + ':00',
                                sub: 'Total poin pelanggaran hari ini: 10 poin',
                                bar: false,
                                tombol: false
                            });
                        }
                    });
                }
            }
        }

        // ── Render widget absensi ─────────────────────────────────────
        function _setWidget(c) {
            const w = document.getElementById('widgetAbsensi'); if (!w) return;
            w.className = `mb-6 rounded-3xl overflow-hidden shadow-lg border-2 ${c.bg} transition-all duration-500`;
            const el = (id) => document.getElementById(id);
            const dot = el('absensiDot');
            if (dot) dot.className = `w-3 h-3 rounded-full ${c.dotCls}`;
            const lbl = el('absensiLabel');
            if (lbl) { lbl.textContent = c.label; lbl.className = `text-xs font-black uppercase tracking-widest ${c.labelCls}`; }
            const ib = el('absensiIconBox');
            if (ib) ib.className = `w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 ${c.iconBg}`;
            const ic = el('absensiIcon');
            if (ic) ic.className = c.iconCls;
            const st = el('absensiStatusTeks');
            if (st) st.textContent = c.status;
            const sb = el('absensiSubTeks');
            if (sb) sb.textContent = c.sub || '';
            const bw = el('absensiBarWrap');
            if (bw) bw.classList.toggle('hidden', !c.bar);
            if (c.bar) {
                const bar = el('absensiBar');
                if (bar) { bar.className = `h-2 rounded-full ${c.barColor||'bg-blue-400'} transition-all duration-1000`; bar.style.width = Math.max(0, Math.min(100, c.barPct||0)) + '%'; }
                const cd = el('absensiCountdown');
                if (cd) cd.textContent = c.countdown || '';
            }
            const btn      = el('absensiManualBtn');
            const btnAktif = el('absensiManualBtnAktif');
            const btnMati  = el('absensiManualBtnMati');
            if (btn) {
                btn.classList.toggle('hidden', !c.tombol);
                if (c.tombol) {
                    if (_manualAbsenAktif) {
                        if (btnAktif) btnAktif.classList.remove('hidden');
                        if (btnMati)  btnMati.classList.add('hidden');
                    } else {
                        if (btnAktif) btnAktif.classList.add('hidden');
                        if (btnMati)  btnMati.classList.remove('hidden');
                    }
                }
            }
        }

        function _fmtSisa(dtk) {
            const h = Math.floor(dtk / 3600);
            const m = Math.floor((dtk % 3600) / 60);
            const s = dtk % 60;
            return (h > 0 ? h + 'j ' : '') + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        }

        // ── Catat poin terlambat (+6) ─────────────────────────────────
        // Dipanggil HANYA setelah _tickAbsensi konfirmasi dari DB bahwa
        // siswa memang belum hadir. Tidak perlu re-cek DB lagi di sini.
        async function _catatTerlambat() {
            try {
                if (_absensiSudahHadir) return; // double-guard
                const poinBaru = (userData.total_poin || 0) + 6;
                await callBackend({ type:'supabase_post', action:'updateData', payload:{
                    table:'siswa', id: userData.id, payload:{ total_poin: poinBaru }
                }});
                await callBackend({ type:'supabase_post', action:'insertLog', payload:{
                    aktor: userData.nama || '', role:'Siswa',
                    aksi: 'Auto Poin Terlambat',
                    detail: `+6 poin otomatis — terlambat melewati jam ${String(_jamTerlambat).padStart(2,'0')}:00. Total poin siswa: ${poinBaru}`
                }});
                userData.total_poin = poinBaru;
                _absensiStatusPoin  = 6;
                if (localStorage.getItem('ea_ingat') === 'true') localStorage.setItem('ea_session_data', JSON.stringify(userData));
                else sessionStorage.setItem('ea_session_data', JSON.stringify(userData));
            } catch(e) { console.error('[Absensi] _catatTerlambat error:', e); }
        }

        // ── Catat poin alfa — total 10, bukan tambah 10 lagi jika sudah +6
        // Dipanggil HANYA setelah _tickAbsensi konfirmasi dari DB bahwa
        // siswa memang belum hadir. Tidak perlu re-cek DB lagi di sini.
        async function _catatAlfa() {
            try {
                if (_absensiSudahHadir) return; // double-guard
                // Hitung selisih agar total poin hari ini = 10 (bukan 6+10=16)
                const sudahDapat = _absensiStatusPoin; // 0 atau 6
                const selisih    = 10 - sudahDapat;    // 10 atau 4
                const poinBaru   = (userData.total_poin || 0) + selisih;

                await callBackend({ type:'supabase_post', action:'updateData', payload:{
                    table:'siswa', id: userData.id, payload:{ total_poin: poinBaru }
                }});

                const detailLog = sudahDapat === 6
                    ? `+${selisih} poin penyesuaian (dari terlambat ke alfa). Total poin hari ini: 10. Poin siswa: ${poinBaru}`
                    : `+10 poin otomatis — tidak hadir (Alfa) melewati jam ${String(_jamTutup).padStart(2,'0')}:00. Poin siswa: ${poinBaru}`;

                await callBackend({ type:'supabase_post', action:'insertLog', payload:{
                    aktor: userData.nama || '', role:'Siswa',
                    aksi: 'Auto Poin Alfa',
                    detail: detailLog
                }});

                userData.total_poin = poinBaru;
                _absensiStatusPoin  = 10;
                if (localStorage.getItem('ea_ingat') === 'true') localStorage.setItem('ea_session_data', JSON.stringify(userData));
                else sessionStorage.setItem('ea_session_data', JSON.stringify(userData));
            } catch(e) { console.error('[Absensi] _catatAlfa error:', e); }
        }

        // ── Log helper ────────────────────────────────────────────────
        async function sendLog(aksi, detail) {
            try {
                await callBackend({ type:'supabase_post', action:'insertLog', payload:{
                    aktor: userData.nama || 'Siswa Anonim', role:'Siswa', aksi, detail
                }});
            } catch(e) {}
        }
    </script>
                                                <!-- ============================================================ -->
                                                <!-- PWA: REGISTRASI SERVICE WORKER & TOMBOL INSTALL              -->
                                                <!-- ============================================================ -->
                                                <div id="pwaInstallBar" class="fixed bottom-0 left-0 right-0 bg-blue-700 text-white px-4 py-3 flex items-center justify-between gap-3 z-[999] shadow-2xl hidden">
                                                    <div class="flex items-center gap-3">
                                                        <img src="{{ asset('icon.png') }}" class="w-10 h-10 rounded-xl shadow-md flex-shrink-0" onerror="this.style.display='none'">
                                                        <div>
                                                            <p class="font-black text-sm leading-none">Install E-Aspirasi</p>
                                                            <p class="text-[11px] text-blue-200 mt-0.5 font-medium">Tambahkan ke layar utama HP kamu</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-2 flex-shrink-0">
                                                        <button id="btnInstallPWA" class="bg-white text-blue-700 font-black px-4 py-2 rounded-xl text-xs uppercase tracking-widest shadow-md hover:bg-blue-50 transition active:scale-95">
                Install
            </button>
                                                        <button id="btnDismissInstall" class="text-blue-300 hover:text-white w-8 h-8 flex items-center justify-center rounded-lg hover:bg-blue-600 transition">
                                                            <i class="fas fa-times">
                                                            </i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <script>
        // ============================================================
        // REGISTRASI SERVICE WORKER
        // ============================================================
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => {
                        console.log('[PWA] Service Worker terdaftar:', reg.scope);

                        // Cek update SW setiap 60 menit
                        setInterval(() => reg.update(), 60 * 60 * 1000);
                    })
                    .catch(err => console.warn('[PWA] SW gagal didaftarkan:', err));
            });
        }

        // ============================================================
        // TOMBOL INSTALL PWA (Install to Home Screen)
        // ============================================================
        let deferredInstallPrompt = null;
        const installBar = document.getElementById('pwaInstallBar');
        const btnInstall = document.getElementById('btnInstallPWA');
        const btnDismiss = document.getElementById('btnDismissInstall');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredInstallPrompt = e;
            // Tampilkan bar install hanya kalau belum pernah dismiss
            if (!localStorage.getItem('pwa_install_dismissed')) {
                installBar.classList.remove('hidden');
            }
        });

        if (btnInstall) {
            btnInstall.addEventListener('click', async () => {
                if (!deferredInstallPrompt) return;
                installBar.classList.add('hidden');
                deferredInstallPrompt.prompt();
                const { outcome } = await deferredInstallPrompt.userChoice;
                console.log('[PWA] User pilih:', outcome);
                deferredInstallPrompt = null;
            });
        }

        if (btnDismiss) {
            btnDismiss.addEventListener('click', () => {
                installBar.classList.add('hidden');
                localStorage.setItem('pwa_install_dismissed', '1');
            });
        }

        window.addEventListener('appinstalled', () => {
            installBar.classList.add('hidden');
            deferredInstallPrompt = null;
            console.log('[PWA] Aplikasi berhasil di-install!');
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: '🎉 E-Aspirasi berhasil di-install!',
                showConfirmButton: false, timer: 3000
            });
        });

        // ============================================================
        // NOTIFIKASI PUSH BACKGROUND — Minta izin & subscribe
        // ============================================================
        // OneSignal menggantikan native VAPID push — tidak perlu setupPushNotification manual
        function setupPushNotification() {
            // Ditangani oleh OneSignal SDK secara otomatis
        }

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = atob(base64);
            return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
        }
        // ============================================================
        // PORTAL DROPDOWN TOGGLE
        // ============================================================
        function togglePortalMenu() {
            const menu = document.getElementById('portalDropdownMenu');
            const chevron = document.getElementById('portalChevron');
            const isHidden = menu.classList.contains('hidden');

            if (isHidden) {
                menu.classList.remove('hidden', 'portal-menu-exit');
                menu.classList.add('portal-menu-enter');
                chevron.style.transform = 'rotate(180deg)';
            } else {
                menu.classList.remove('portal-menu-enter');
                menu.classList.add('portal-menu-exit');
                chevron.style.transform = 'rotate(0deg)';
                setTimeout(() => menu.classList.add('hidden'), 170);
            }
        }

        // Tutup notif panel kalau klik di luar
        document.addEventListener('click', function(eNotif) {
            const panel = document.getElementById('notifPanel');
            const bell = document.getElementById('btnNotifBell');
            if (panel && bell && !panel.contains(eNotif.target) && !bell.contains(eNotif.target)) {
                panel.classList.add('hidden');
            }
        });

        // Buka El Point — di app Median buka browser HP, di browser biasa buka langsung
        function bukaElPoint() {
            // Portal El Point dibuka di semua platform (web maupun app)
            window.location.href = window.location.origin + '/elpoint.html';
        }

        // Tutup dropdown kalau klik di luar
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('portalDropdownWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                const menu = document.getElementById('portalDropdownMenu');
                const chevron = document.getElementById('portalChevron');
                if (menu && !menu.classList.contains('hidden')) {
                    menu.classList.remove('portal-menu-enter');
                    menu.classList.add('portal-menu-exit');
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                    setTimeout(() => menu.classList.add('hidden'), 170);
                }
            }
        });

        // ============================================================
        // POPUP KOMENTAR PENILAIAN — tampil sekilas lalu hilang
        // ============================================================
        let popupTimer = null;
        let popupQueue = [];
        let popupRunning = false;

        async function muatPenilaianTerbaru() {
            try {
                // Cek dulu apakah fitur komentar aktif
                const setRes = await callBackend({ type: 'supabase_get', action: 'getPengaturan' });
                if (setRes.data) {
                    const showKomen = setRes.data.find(p => p.nama_pengaturan === 'tampilkan_komentar');
                    if (!showKomen || showKomen.nilai !== 'true') return; // Fitur mati
                }
                const res = await callBackend({ type: 'supabase_get', action: 'getData', payload: { table: 'penilaian?select=nama_penilai,bintang,komentar&order=created_at.desc&limit=10&komentar=not.is.null' } });
                if (res.data && res.data.length > 0) {
                    // Filter yang punya komentar
                    popupQueue = res.data.filter(d => d.komentar && d.komentar.trim() !== '');
                    if (popupQueue.length > 0) {
                        // Mulai tampil setelah 5 detik
                        setTimeout(() => tampilkanPopupBerikutnya(), 5000);
                    }
                }
            } catch(e) {}
        }

        function tampilkanPopupBerikutnya() {
            if (popupQueue.length === 0) return;
            const item = popupQueue[Math.floor(Math.random() * popupQueue.length)];
            tampilkanPopupKomentar(item);
            // Tampil lagi setiap 15-20 detik
            setTimeout(() => tampilkanPopupBerikutnya(), 15000 + Math.random() * 5000);
        }

        function tampilkanPopupKomentar(item) {
            const el = document.getElementById('popupKomentar');
            if (!el) return;
            // Isi data
            document.getElementById('pkNama').textContent = item.nama_penilai || 'Pengguna';
            const bintangEl = document.getElementById('pkBintang');
            bintangEl.innerHTML = '';
            for (let i = 0; i < 5; i++) {
                const s = document.createElement('i');
                s.className = `fas fa-star text-[10px] ${i < item.bintang ? 'text-yellow-400' : 'text-gray-200'}`;
                bintangEl.appendChild(s);
            }
            const komentarEl = document.getElementById('pkKomentar');
            if (item.komentar) {
                komentarEl.textContent = '"' + item.komentar + '"';
                komentarEl.style.display = 'block';
            } else {
                komentarEl.style.display = 'none';
            }
            // Tampilkan
            el.classList.remove('hidden');
            // Auto hilang setelah 6 detik
            clearTimeout(popupTimer);
            popupTimer = setTimeout(() => tutupPopupKomentar(), 6000);
        }

        function tutupPopupKomentar() {
            const el = document.getElementById('popupKomentar');
            if (el) el.classList.add('hidden');
            clearTimeout(popupTimer);
        }

        // Panggil setelah login berhasil
        // (dipanggil di bagian setelah setupDashboard)


        // ============================================================
        // FITUR 1 — Stat dashboard siswa
        // ============================================================
        async function muatStatDashboard() {
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getRiwayat', payload: { nama: userData.nama } });
                const data = res.data || [];
                const total    = data.length;
                const selesai  = data.filter(d => d.status === 'Selesai').length;
                const pending  = data.filter(d => d.status === 'Menunggu' || d.status === 'Sedang Diproses').length;
                const elTotal  = document.getElementById('statTotalLaporan');
                const elSel    = document.getElementById('statSelesai');
                const elPend   = document.getElementById('statMenunggu');
                if (elTotal) elTotal.textContent = total;
                if (elSel)   elSel.textContent   = selesai;
                if (elPend)  elPend.textContent   = pending;
            } catch(e) { console.warn('Stat dashboard gagal:', e.message); }
        }

        // ============================================================
        // FITUR 2 — Notifikasi realtime di admin (polling update)
        // Sudah ada di sistem polling — tidak perlu ditambah lagi
        // ============================================================

        // ============================================================
        // FITUR 13 — Filter & pencarian canggih di tiket
        // ============================================================
        let allRiwayatData = [];

        function filterTiket() {
            const q       = (document.getElementById('searchTiket')?.value || '').toLowerCase();
            const status  = document.getElementById('filterStatusTiket')?.value || '';
            const tipe    = document.getElementById('filterTipeTiket')?.value || '';
            const body    = document.getElementById('tableProgresBody');
            const empty   = document.getElementById('emptyProgres');
            if (!body) return;

            const filtered = allRiwayatData.filter(d => {
                const matchQ      = !q || (d.kategori||'').toLowerCase().includes(q) || (d.detail_pesan||'').toLowerCase().includes(q);
                const matchStatus = !status || d.status === status;
                const matchTipe   = !tipe || d.tipe === tipe;
                return matchQ && matchStatus && matchTipe;
            });

            renderTiketRows(filtered);
            if (empty) empty.classList.toggle('hidden', filtered.length > 0);
        }

        function renderTiketRows(data) {
            const body = document.getElementById('tableProgresBody');
            if (!body) return;
            if (data.length === 0) { body.innerHTML = ''; return; }
            body.innerHTML = data.map(item => {
                const stColor = item.status === 'Selesai'
                    ? 'text-green-600 bg-green-50 border-green-200'
                    : item.status === 'Ditolak'
                    ? 'text-red-600 bg-red-50 border-red-200'
                    : item.status === 'Sedang Diproses'
                    ? 'text-blue-600 bg-blue-50 border-blue-200'
                    : 'text-orange-600 bg-orange-50 border-orange-200';
                const tipeIcon = item.tipe === 'Laporan' ? '📣' : item.tipe === 'Masukan' ? '💬' : '🎓';
                return `<tr class="hover:bg-blue-50/30 transition-colors border-b border-gray-50 cursor-pointer" onclick="lihatDetailTiket('${item.id}')">
                                                <td class="px-5 py-3 text-xs font-bold text-gray-500 whitespace-nowrap">${new Date(item.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'})}</td>
                                                <td class="px-5 py-3">
                                                    <p class="font-bold text-gray-800 text-sm">${tipeIcon} ${item.tipe}</p>
                                                    <p class="text-[11px] text-gray-500">${item.kategori}</p>
                                                </td>
                                                <td class="px-5 py-3 text-right flex items-center justify-end gap-2">
                                                    <span class="px-2.5 py-1 rounded-full border text-[10px] font-black uppercase tracking-widest whitespace-nowrap ${stColor}">${item.status}</span>
                                                    <i class="fas fa-chevron-right text-gray-300 text-[10px]">
                                                    </i>
                                                </td>
                                            </tr>`;
            }).join('');
        }

        // Override loadRiwayatAspirasi agar simpan data ke allRiwayatData
        const _origLoadRiwayat = typeof loadRiwayatAspirasi === 'function' ? loadRiwayatAspirasi : null;

        // ============================================================
        // FITUR 15 — Dark Mode
        // ============================================================
        function toggleDarkMode() {
            const isDark = document.body.classList.toggle('dark-mode');
            localStorage.setItem('ea_dark_mode', isDark ? '1' : '0');
            const icon = document.getElementById('iconDarkMode');
            if (icon) {
                icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            }
        }

        function initDarkMode() {
            const saved = localStorage.getItem('ea_dark_mode');
            if (saved === '1') {
                document.body.classList.add('dark-mode');
                const icon = document.getElementById('iconDarkMode');
                if (icon) icon.className = 'fas fa-sun';
            }
        }

        // Init dark mode saat load
        initDarkMode();


        // ============================================================
        // FITUR 6 — Tampilkan pengumuman di beranda siswa
        // ============================================================
        async function muatPengumumanSiswa() {
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getData', payload: { table: 'pengumuman?select=*&order=created_at.desc&limit=5' } });
                const data = res.data || [];
                const section = document.getElementById('sectionPengumuman');
                const list = document.getElementById('listPengumumanSiswa');
                if (!section || !list) return;
                if (data.length === 0) { section.classList.add('hidden'); return; }
                section.classList.remove('hidden');
                const tipeStyle = {
                    info:    'border-blue-200 bg-blue-50',
                    penting: 'border-yellow-300 bg-yellow-50',
                    darurat: 'border-red-300 bg-red-50'
                };
                const tipeIcon = {
                    info: '<i class="fas fa-info-circle text-blue-500">
                                        </i>',
                    penting: '<i class="fas fa-exclamation-triangle text-yellow-500">
                                    </i>',
                    darurat: '<i class="fas fa-exclamation-circle text-red-500">
                                </i>'
                };
                list.innerHTML = data.map(p => `
                    <div class="border-l-4 ${tipeStyle[p.tipe]||'border-gray-200 bg-gray-50'} p-4 rounded-r-2xl">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-0.5">${tipeIcon[p.tipe]||'<i class="fas fa-info-circle text-gray-400">
                                </i>'}</div>
                                <div>
                                    <p class="font-black text-gray-800 text-sm">${p.judul}</p>
                                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">${p.isi}</p>
                                    <p class="text-[10px] text-gray-400 mt-1.5">${new Date(p.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'})}</p>
                                </div>
                            </div>
                        </div>`).join('');
            } catch(e) { console.warn('Pengumuman gagal dimuat:', e.message); }
        }


        // ============================================================
        // FITUR 19 — Log aktivitas tambahan sudah via sendLog (existing)
        // Tambah log saat buka halaman dashboard
        // ============================================================
        // (sendLog sudah dipanggil di 7 tempat — login, edit profil, kirim laporan, dll)


        // ============================================================
        // FITUR 7 — Lihat detail tiket + balasan admin
        // ============================================================
        async function lihatDetailTiket(id) {
            try {
                const item = allRiwayatData.find ? allRiwayatData.find(d => d.id === id) : null;
                const res = await callBackend({ type: 'supabase_post', action: 'getBalasan', payload: { id_aspirasi: id } });
                const balasan = (res && res.data) ? res.data : [];
                const stColor = !item ? '' : item.status === 'Selesai' ? 'bg-green-100 text-green-700' : item.status === 'Ditolak' ? 'bg-red-100 text-red-700' : item.status === 'Sedang Diproses' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700';

                let html = '';
                if (item) {
                    html += `<div class="bg-gray-50 rounded-xl p-4 mb-4 border text-left">
                        <div class="flex justify-between items-start mb-2 gap-2">
                            <span class="text-xs font-black text-gray-600">${item.tipe} — ${item.kategori}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black flex-shrink-0 ${stColor}">${item.status}</span>
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed">${item.detail_pesan||''}</p>
                        <p class="text-[10px] text-gray-400 mt-2">${new Date(item.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'})}</p>
                    </div>`;
                }
                if (balasan.length > 0) {
                    html += `<div class="text-left">
                    <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-2">💬 Balasan Admin</p>
                    <div class="space-y-2">${balasan.map(b => `
                            <div class="bg-blue-50 rounded-xl p-3 border border-blue-100 text-left">
                    <div class="flex justify-between items-center mb-1">
                        <p class="text-xs font-black text-blue-700">${b.nama_admin||'Admin'}</p>
                        <p class="text-[10px] text-gray-400">${new Date(b.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short'})}</p>
                    </div>
                    <p class="text-xs text-gray-700 leading-relaxed">${b.isi_balasan}</p>
                </div>`).join('')}
                        </div>
            </div>`;
                } else {
                    html += `<p class="text-center text-gray-400 text-xs py-3">
            <i class="fas fa-comment-slash text-2xl block mb-2 text-gray-200">
            </i>Belum ada balasan dari admin</p>`;
                }
                Swal.fire({ title: 'Detail Tiket', html: `<div>${html}</div>`, confirmButtonText: 'Tutup', confirmButtonColor: '#2563eb', customClass: { popup: 'rounded-2xl' } });
            } catch(e) { Swal.fire('Error', e.message, 'error'); }
        }


        // Modal dokumentasi
        function bukaDocModal() {
            document.getElementById('docModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function tutupDocModal() {
            document.getElementById('docModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
        // Klik luar modal untuk tutup
        document.getElementById('docModal')?.addEventListener('click', function(e) {
            if (e.target === this) tutupDocModal();
        });


        // Auto-jump antar kotak PIN
        // ─────────────────────────────────────────────────────────
        // HELPER PIN BOXES: auto-jump, get value, clear, shake
        // ─────────────────────────────────────────────────────────
        function setupPinBoxes(selector) {
            document.querySelectorAll(selector).forEach((box, i, boxes) => {
                box.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(-1);
                    if (this.value && i < boxes.length - 1) boxes[i + 1].focus();
                });
                box.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && i > 0) { boxes[i-1].focus(); boxes[i-1].select(); }
                    if (e.key === 'Enter') {
                        const all = Array.from(boxes).every(b => b.value);
                        if (all) {
                            if (selector === '.pin-entry-box') verifikasiPin();
                            else if (selector === '.pin-box') lanjutSetPinStep2();
                            else if (selector === '.lupa-pin-box') prosesLupaPin();
                        }
                    }
                });
            });
        }
        function getPinFromBoxes(sel) { return Array.from(document.querySelectorAll(sel)).map(b=>b.value).join(''); }
        function clearPinBoxes(sel) { document.querySelectorAll(sel).forEach(b=>b.value=''); const f=document.querySelector(sel); if(f) setTimeout(()=>f.focus(),60); }
        function showPinShake(sel) { document.querySelectorAll(sel).forEach(b=>{ b.classList.add('border-red-400','bg-red-50'); setTimeout(()=>b.classList.remove('border-red-400','bg-red-50'),700); }); }

        async function konfirmasiTindakan(pesan, judul) {
            judul = judul || 'Anda Yakin?';
            const r = await Swal.fire({ title:judul, text:pesan, icon:'question', showCancelButton:true, confirmButtonColor:'#2563eb', cancelButtonColor:'#6b7280', confirmButtonText:'Iya, Lanjutkan', cancelButtonText:'Tidak, Batal' });
            return r.isConfirmed;
        }

        let pendingLoginData = null;
        let pendingPinTemp   = '';

        function cekPinSetelahLogin(siswaData, isRemember) {
            pendingLoginData = { siswa: siswaData, isRemember };
            // Cukup cek PIN saja — security_answer opsional (data lama mungkin belum ada)
            const hasPin = siswaData.login_pin && String(siswaData.login_pin).trim() !== '';
            if (!hasPin) {
                // Belum punya PIN → minta set PIN pertama kali
                _bukaSetPin();
            } else {
                const nama = siswaData.nama_panggilan || (siswaData.nama || '').split(' ')[0];
                document.getElementById('pinLoginNama').textContent = nama;
                clearPinBoxes('.pin-entry-box');
                document.getElementById('pinEntryError').classList.add('hidden');
                document.getElementById('modalMasukPin').classList.remove('hidden');
                setTimeout(() => { const f = document.querySelector('.pin-entry-box'); if(f) f.focus(); }, 100);
            }
        }

        function _bukaSetPin() {
            pendingPinTemp = '';
            clearPinBoxes('.pin-box');
            clearPinBoxes('.pin-confirm-box');
            document.getElementById('setPinStep1Error').classList.add('hidden');
            document.getElementById('setPinStep1').classList.remove('hidden');
            document.getElementById('setPinStep2').classList.add('hidden');
            document.getElementById('modalSetPin').classList.remove('hidden');
            setupPinBoxes('.pin-box');
            setupPinBoxes('.pin-confirm-box');
            setTimeout(() => { const f=document.querySelector('.pin-box'); if(f) f.focus(); }, 100);
        }
        function bukaModalSetPin() { _bukaSetPin(); }

        function kembaliStep1() {
            pendingPinTemp = '';
            clearPinBoxes('.pin-box'); clearPinBoxes('.pin-confirm-box');
            document.getElementById('setPinStep1Error').classList.add('hidden');
            document.getElementById('setPinStep2').classList.add('hidden');
            document.getElementById('setPinStep1').classList.remove('hidden');
            setTimeout(() => { const f=document.querySelector('.pin-box'); if(f) f.focus(); }, 100);
        }

        function lanjutSetPinStep2() {
            const pin = getPinFromBoxes('.pin-box');
            const confirm = getPinFromBoxes('.pin-confirm-box');
            const errEl = document.getElementById('setPinStep1Error');
            if (pin.length < 4) { errEl.textContent='PIN harus 4 digit angka.'; errEl.classList.remove('hidden'); showPinShake('.pin-box'); return; }
            if (pin !== confirm) { errEl.textContent='PIN dan konfirmasi tidak cocok.'; errEl.classList.remove('hidden'); clearPinBoxes('.pin-confirm-box'); showPinShake('.pin-confirm-box'); return; }
            errEl.classList.add('hidden');
            pendingPinTemp = pin;
            document.getElementById('setPinStep1').classList.add('hidden');
            document.getElementById('setPinStep2').classList.remove('hidden');
            document.getElementById('setPinSecAnswer').value = '';
            document.getElementById('setPinStep2Error').classList.add('hidden');
            setTimeout(() => { const f=document.getElementById('setPinSecAnswer'); if(f) f.focus(); }, 100);
        }

        async function simpanPinDanJawaban() {
            const answer = (document.getElementById('setPinSecAnswer')?.value||'').trim();
            const errEl  = document.getElementById('setPinStep2Error');
            if (!answer) { errEl.textContent='Jawaban tidak boleh kosong.'; errEl.classList.remove('hidden'); return; }
            if (!pendingPinTemp || pendingPinTemp.length < 4) { kembaliStep1(); return; }
            const btn = document.getElementById('btnSimpanPin');
            if (btn) { btn.disabled=true; btn.innerHTML='<i class="fas fa-spinner fa-spin mr-2">
        </i> Menyimpan...'; }
            try {
                if (pendingLoginData && pendingLoginData.siswa && pendingLoginData.siswa.id) {
                    // Simpan PIN & jawaban ke DB
                    await callBackend({ type:'supabase_post', action:'updateData', payload:{
                        table:'siswa', id: pendingLoginData.siswa.id,
                        payload:{ login_pin: pendingPinTemp, security_answer: answer.toLowerCase().trim() }
                    }});
                    // Update objek siswa di pendingLoginData agar verifikasi berikutnya benar
                    pendingLoginData.siswa.login_pin       = pendingPinTemp;
                    pendingLoginData.siswa.security_answer = answer.toLowerCase().trim();
                    // Update userData juga (dipakai di profil)
                    userData.login_pin       = pendingPinTemp;
                    userData.security_answer = answer.toLowerCase().trim();
                }
                document.getElementById('modalSetPin').classList.add('hidden');
                pendingPinTemp = '';
                Swal.fire({ toast:true, position:'top-end', icon:'success', title:'PIN berhasil disimpan!', showConfirmButton:false, timer:2000 });
                if (pendingLoginData && pendingLoginData.dariProfil) {
                    updateProfilPinStatus();
                    pendingLoginData = null;
                } else {
                    // Langsung masuk dashboard — PIN sudah tersimpan
                    await selesaiLoginDenganPin();
                }
            } catch(e) {
                errEl.textContent = 'Gagal simpan ke database: ' + e.message;
                errEl.classList.remove('hidden');
            } finally {
                if (btn) { btn.disabled=false; btn.innerHTML='<i class="fas fa-save mr-1">
    </i> Simpan & Masuk'; }
            }
        }
        function simpanPinBaru() { simpanPinDanJawaban(); }

        function verifikasiPin() {
            const pin   = getPinFromBoxes('.pin-entry-box');
            const errEl = document.getElementById('pinEntryError');
            if (pin.length < 4) {
                errEl.textContent = 'Masukkan 4 digit PIN.';
                errEl.classList.remove('hidden');
                return;
            }
            // Ambil PIN dari pendingLoginData (sudah fresh dari DB)
            const savedPin = String(pendingLoginData?.siswa?.login_pin || '').trim();
            if (!savedPin) {
                // PIN tidak ada di DB padahal harusnya ada — paksa set ulang
                errEl.classList.add('hidden');
                document.getElementById('modalMasukPin').classList.add('hidden');
                _bukaSetPin();
                return;
            }
            if (pin !== savedPin) {
                errEl.textContent = 'PIN salah, coba lagi.';
                errEl.classList.remove('hidden');
                clearPinBoxes('.pin-entry-box');
                showPinShake('.pin-entry-box');
                return;
            }
            errEl.classList.add('hidden');
            document.getElementById('modalMasukPin').classList.add('hidden');
            selesaiLoginDenganPin();
        }

        async function selesaiLoginDenganPin() {
            if (!pendingLoginData) return;
            // Cek maintenance sebelum masuk dashboard
            const maintActive = await cekMaintenanceMode().catch(()=>false);
            if (maintActive) return;
            const siswa      = pendingLoginData.siswa;
            const isRemember = pendingLoginData.isRemember;

            // Isi SEMUA field userData dari data siswa — jangan sampai ada yang kosong
            userData.id              = siswa.id              || '';
            userData.nama            = siswa.nama            || userData.nama || '';
            userData.nama_panggilan  = siswa.nama_panggilan  || '';
            userData.kelas           = siswa.kelas           || userData.kelas || '';
            userData.gender          = siswa.gender          || userData.gender || '';
            userData.nik             = siswa.nik             || '';
            userData.nisn            = siswa.nisn            || '';
            userData.no_telp         = siswa.no_telp         || '';
            userData.tglLahir        = siswa.tgl_lahir       || userData.tglLahir || '';
            userData.rawTgl          = siswa.tgl_lahir       || userData.rawTgl  || '';
            userData.total_poin      = siswa.total_poin      || 0;
            userData.login_pin       = siswa.login_pin       || '';
            userData.security_answer = siswa.security_answer || '';

            // Simpan sesi jika "Ingat Saya" dicentang
            if (isRemember) {
                localStorage.setItem('ea_ingat', 'true');
                localStorage.setItem('ea_session_data', JSON.stringify(userData));
            }
            // JANGAN localStorage.clear() — itu hapus semua data termasuk knownRiwayat dll

            sendLog('Login Sukses', `Siswa berhasil login. Kelas: ${userData.kelas}`);
            const btn = document.getElementById('btnLoginSubmit');
            if (btn) { btn.disabled=false; btn.innerHTML='Masuk Aplikasi'; }
            pendingLoginData = null;
            masukKeDashboard();
        }

        // Lupa PIN dari popup masuk PIN (saat login)
        function bukaLupaPin() {
            document.getElementById('modalMasukPin').classList.add('hidden');
            if (!pendingLoginData) return;
            pendingLoginData.dariLogin = true;
            _bukaModalLupaPin();
        }

        // Lupa PIN dari halaman profil
        function bukaLupaPinDariProfil() {
            if (!userData || !userData.nama) return;
            pendingLoginData = { siswa: Object.assign({}, userData), isRemember:false, dariProfil:true, lupaPin:true };
            _bukaModalLupaPin();
        }

        // Ubah PIN dari halaman profil
        function bukaUbahPin() {
            if (!userData || !userData.nama) { Swal.fire('Info','Silakan login terlebih dahulu.','info'); return; }
            pendingLoginData = { siswa: Object.assign({}, userData), isRemember:false, dariProfil:true, ubahPin:true };
            if (!userData.login_pin) {
                // Belum ada PIN sama sekali - langsung set PIN baru
                _bukaSetPin();
            } else {
                // Sudah ada PIN - verifikasi jawaban keamanan dulu
                _bukaModalLupaPin();
            }
        }

        function _bukaModalLupaPin() {
            document.getElementById('lupaPinAnswer').value = '';
            clearPinBoxes('.lupa-pin-box');
            document.getElementById('lupaPinError').classList.add('hidden');
            document.getElementById('modalLupaPin').classList.remove('hidden');
            setupPinBoxes('.lupa-pin-box');
            setTimeout(() => { const f=document.getElementById('lupaPinAnswer'); if(f) f.focus(); }, 100);
        }
        function tutupLupaPin() { document.getElementById('modalLupaPin').classList.add('hidden'); }

        async function prosesLupaPin() {
            const answer  = (document.getElementById('lupaPinAnswer')?.value||'').trim().toLowerCase();
            const errEl   = document.getElementById('lupaPinError');
            if (!answer) { errEl.textContent='Jawaban tidak boleh kosong.'; errEl.classList.remove('hidden'); return; }

            const savedAnswer = String(
                pendingLoginData?.siswa?.security_answer || ''
            ).trim().toLowerCase();

            // Jika belum ada security_answer di DB (akun lama / data kosong)
            // → izinkan lewat dengan verifikasi tgl lahir sebagai fallback
            if (!savedAnswer) {
                // Cek apakah ada PIN baru yang diisi
                const pinBaru = getPinFromBoxes('.lupa-pin-box');
                if (pinBaru.length < 4) {
                    errEl.textContent = 'Masukkan PIN baru 4 digit.';
                    errEl.classList.remove('hidden');
                    return;
                }
                // Tidak ada security_answer → tanya konfirmasi dulu
                const konfirm = await Swal.fire({
                    title: 'Belum Ada Jawaban Keamanan',
                    text: 'Akun ini belum memiliki pertanyaan keamanan. Hubungi admin untuk reset PIN, atau klik Lanjutkan jika ini akun kamu.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Lanjutkan (Reset PIN)',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#f97316'
                });
                if (!konfirm.isConfirmed) return;
                // Lanjut proses ganti PIN tanpa verifikasi jawaban
                await _prosesGantiPin(pinBaru);
                return;
            }

            if (answer !== savedAnswer) {
                errEl.textContent = 'Jawaban salah. Coba ingat lagi!';
                errEl.classList.remove('hidden');
                const el = document.getElementById('lupaPinAnswer');
                if (el) { el.classList.add('border-red-400'); setTimeout(()=>el.classList.remove('border-red-400'),700); }
                return;
            }

            const pinBaru = getPinFromBoxes('.lupa-pin-box');
            if (pinBaru.length < 4) {
                errEl.textContent = 'Masukkan PIN baru 4 digit.';
                errEl.classList.remove('hidden');
                return;
            }

            // Jawaban benar — proses ganti PIN
            await _prosesGantiPin(pinBaru);
        }

        // Helper: simpan PIN baru ke DB dan handle routing berdasarkan context
        async function _prosesGantiPin(pinBaru) {
            document.getElementById('modalLupaPin').classList.add('hidden');

            if (pendingLoginData && pendingLoginData.dariLogin) {
                // Dari layar login — masuk dashboard langsung
                if (pendingLoginData.siswa?.id) {
                    try {
                        await callBackend({ type:'supabase_post', action:'updateData', payload:{
                            table:'siswa', id: pendingLoginData.siswa.id,
                            payload:{ login_pin: pinBaru }
                        }});
                        pendingLoginData.siswa.login_pin = pinBaru;
                        userData.login_pin = pinBaru;
                    } catch(e) {}
                }
                Swal.fire({ toast:true, position:'top-end', icon:'success', title:'PIN baru disimpan! Selamat datang.', showConfirmButton:false, timer:2000 });
                await selesaiLoginDenganPin();

            } else if (pendingLoginData && pendingLoginData.lupaPin) {
                // Dari profil — hapus PIN lama, beri tahu set ulang
                try {
                    await callBackend({ type:'supabase_post', action:'updateData', payload:{
                        table:'siswa', id: pendingLoginData.siswa.id,
                        payload:{ login_pin: null }
                    }});
                    userData.login_pin = '';
                    updateProfilPinStatus();
                } catch(e) {}
                pendingLoginData = null;
                await Swal.fire({ icon:'info', title:'PIN Dihapus', html:'PIN kamu telah dihapus.<br>Silakan klik <b>"Ubah PIN"</b> untuk membuat PIN baru.', confirmButtonColor:'#2563eb' });

            } else if (pendingLoginData && pendingLoginData.ubahPin) {
                // Dari profil ubah PIN — langsung simpan PIN baru
                try {
                    await callBackend({ type:'supabase_post', action:'updateData', payload:{
                        table:'siswa', id: pendingLoginData.siswa.id,
                        payload:{ login_pin: pinBaru }
                    }});
                    userData.login_pin = pinBaru;
                    if (localStorage.getItem('ea_ingat') === 'true') localStorage.setItem('ea_session_data', JSON.stringify(userData));
                    updateProfilPinStatus();
                    Swal.fire({ toast:true, position:'top-end', icon:'success', title:'PIN baru berhasil disimpan!', showConfirmButton:false, timer:2000 });
                } catch(e) {
                    Swal.fire('Gagal', 'Gagal simpan PIN baru: ' + e.message, 'error');
                }
                pendingLoginData = null;
            }
        }

        function updateProfilPinStatus() {
            const el = document.getElementById('profilPinStatus'); if (!el) return;
            if (userData && userData.login_pin) {
                el.textContent = 'PIN sudah diset';
                el.className = 'text-[10px] text-green-600 font-bold';
            } else {
                el.textContent = 'PIN belum diset — masih pakai tanggal lahir';
                el.className = 'text-[10px] text-orange-500 font-bold';
            }
        }

        // Materi BK - buka portal eksternal
        function bukaMaterieBK() {
            window.open('https://portal-belajarbk.netlify.app', '_blank');
        }

        // Maintenance mode check on page load
        // Returns true if maintenance is active, false if not
        async function cekMaintenanceMode() {
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getPengaturanByKey', payload: { key : '{{ env('KEY_13') }}' } });
                const isActive = (res.data?.[0]?.nilai === 'true') || false;
                if (!isActive) return false;

                // Cek apakah sudah kadaluarsa
                const untilRes = await callBackend({ type: 'supabase_get', action: 'getPengaturanByKey', payload: { key : '{{ env('KEY_14') }}' } });
                const until = untilRes.data?.[0]?.nilai || '';
                if (until && new Date(until) < new Date()) return false; // sudah lewat

                // Hitung sisa waktu
                let sisaWaktu = '';
                if (until) {
                    const diff = new Date(until) - new Date();
                    const h = Math.floor(diff / 3600000);
                    const m = Math.floor((diff % 3600000) / 60000);
                    sisaWaktu = h > 0 ? `${h} jam ${m} menit` : `${m} menit`;
                }

                // Tampilkan popup maintenance dengan countdown 3 detik lalu redirect
                let detik = 3;
                const timerInterval = setInterval(() => {
                    detik--;
                    const el = document.getElementById('maintCountdown');
                    if (el) el.textContent = detik;
                    if (detik <= 0) {
                        clearInterval(timerInterval);
                        window.location.replace('/maintenance.html');
                    }
                }, 1000);

                await Swal.fire({
                    title: '<i class="fas fa-tools text-yellow-500 mr-2">
</i>Website Sedang Perawatan',
                    html: `
                        <div class="text-left space-y-3 text-sm text-gray-700">
<p class="font-bold text-center text-base text-gray-800">🔧 Website Sedang Perbaikan / Perawatan</p>
                            ${sisaWaktu ? `<p class="text-center text-gray-500">Estimasi selesai dalam <b class="text-red-500">${sisaWaktu}</b>
</p>` : ''}
                            <hr class="border-gray-200">
<p>Silakan menunggu sesuai waktu yang tertera. Sistem kami sedang dalam proses upgrade dan perbaikan.</p>
<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-xs text-yellow-800">
    <p class="font-bold mb-1">⚠️ Informasi Penting:</p>
    <p>Kemungkinan database akan dimulai ulang. Setelah maintenance selesai, silakan <b>atur ulang PIN</b> dan data lainnya di halaman Profil. Riwayat laporan Anda mungkin tidak tampil sementara, namun <b>catatan laporan tetap tersimpan</b> di sistem kami dan akan direspon secepatnya.</p>
</div>
<p class="text-center text-gray-500">Terima kasih atas perhatian dan kesabaran Anda 🙏</p>
<p class="text-center text-xs text-gray-400 mt-2">Mengalihkan dalam <span id="maintCountdown" class="font-black text-red-500 text-base">3</span> detik...</p>
</div>`,
                    confirmButtonText: 'OK, Saya Mengerti →',
                    confirmButtonColor: '#2563eb',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: () => {
                        // Override timer from our own interval
                        const progressBar = Swal.getTimerProgressBar();
                        if (progressBar) progressBar.style.background = '#ef4444';
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                });

                // Redirect setelah popup ditutup (baik via OK maupun timer)
                window.location.replace('/maintenance.html');
                return true;

            } catch(e) {
                console.log('Maintenance check skipped:', e.message);
                return false;
            }
        }

        
        // ============================================================
        // SHORTCUT RAHASIA — klik logo 3x berturut-turut
        // ============================================================
        const SHORTCUT_PAGES = {
            'admin':       { url : '{{ env('URL_15') }}',        label: 'Portal Admin',           icon: 'fa-user-shield' },
            'perawatan':   { url : '{{ env('URL_16') }}',    label: 'Panel Perawatan',        icon: 'fa-tools' },
            'maintenance': { url : '{{ env('URL_17') }}',  label: 'Halaman Maintenance',    icon: 'fa-wrench' },
            'status':      { url : '{{ env('URL_18') }}',       label: 'Status Koneksi Layanan', icon: 'fa-signal' },
            'index':       { url: '/',             label: 'Halaman Utama Siswa',    icon: 'fa-home' },
        };

        let logoClickCount = 0;
        let logoClickTimer = null;

        document.addEventListener('DOMContentLoaded', () => {
            const logoEl = document.getElementById('logoShortcut');
            if (logoEl) {
                logoEl.addEventListener('click', () => {
                    logoClickCount++;
                    clearTimeout(logoClickTimer);
                    if (logoClickCount >= 3) {
                        logoClickCount = 0;
                        bukaShortcut();
                    } else {
                        logoClickTimer = setTimeout(() => { logoClickCount = 0; }, 600);
                    }
                });
            }
        });

        function bukaShortcut() {
            document.getElementById('shortcutModal').classList.remove('hidden');
            document.getElementById('shortcutInput').value = '';
            document.getElementById('shortcutSuggestion').classList.add('hidden');
            document.getElementById('shortcutError').classList.add('hidden');
            setTimeout(() => document.getElementById('shortcutInput').focus(), 100);
        }

        function tutupShortcut() {
            document.getElementById('shortcutModal').classList.add('hidden');
        }

        function updateShortcutSuggestion() {
            const val = document.getElementById('shortcutInput').value.trim().toLowerCase();
            const suggBox = document.getElementById('shortcutSuggestion');
            const errBox = document.getElementById('shortcutError');
            const matchEl = document.getElementById('shortcutMatch');
            const hintEl = document.getElementById('shortcutHint');

            suggBox.classList.add('hidden');
            errBox.classList.add('hidden');

            if (!val) return;

            // Exact match dulu
            if (SHORTCUT_PAGES[val]) {
                matchEl.innerHTML = `<i class="fas ${SHORTCUT_PAGES[val].icon} mr-2">
</i>${SHORTCUT_PAGES[val].label} <span class="text-gray-400 font-normal text-xs">(${SHORTCUT_PAGES[val].url})</span>`;
                suggBox.classList.remove('hidden');
                return;
            }

            // Partial match
            const partials = Object.keys(SHORTCUT_PAGES).filter(k => k.includes(val));
            if (partials.length > 0) {
                const best = partials[0];
                matchEl.innerHTML = `<i class="fas ${SHORTCUT_PAGES[best].icon} mr-2">
</i>${SHORTCUT_PAGES[best].label} <span class="text-gray-400 font-normal text-xs">(${SHORTCUT_PAGES[best].url})</span>`;
                suggBox.classList.remove('hidden');
                return;
            }

            // No match
            const allKeys = Object.keys(SHORTCUT_PAGES).join(', ');
            hintEl.textContent = allKeys;
            errBox.classList.remove('hidden');
        }

        function eksekusiShortcut() {
            const val = document.getElementById('shortcutInput').value.trim().toLowerCase();
            if (!val) return;

            // Exact match
            if (SHORTCUT_PAGES[val]) {
                tutupShortcut();
                window.location.href = SHORTCUT_PAGES[val].url;
                return;
            }

            // Partial match
            const partials = Object.keys(SHORTCUT_PAGES).filter(k => k.includes(val));
            if (partials.length > 0) {
                tutupShortcut();
                window.location.href = SHORTCUT_PAGES[partials[0]].url;
                return;
            }

            // No match - show error
            document.getElementById('shortcutSuggestion').classList.add('hidden');
            const hintEl = document.getElementById('shortcutHint');
            const errBox = document.getElementById('shortcutError');
            hintEl.textContent = Object.keys(SHORTCUT_PAGES).join(', ');
            errBox.classList.remove('hidden');
        }

        // Close shortcut modal when clicking backdrop
        document.addEventListener('click', (e) => {
            const modal = document.getElementById('shortcutModal');
            if (modal && e.target === modal) tutupShortcut();
        });


        // ── POPUP AJAKAN DOWNLOAD APP ─────────────────────────────
        function tampilkanAppPopup() {
            // Jangan tampilkan kalau sudah di dalam app Median
            const isApp = typeof window._median !== 'undefined' ||
                navigator.userAgent.toLowerCase().includes('median') ||
                navigator.userAgent.toLowerCase().includes('gonative');
            if (isApp) return;

            // Jangan tampilkan kalau sudah pernah ditutup di sesi ini
            if (sessionStorage.getItem('appPopupDismissed')) return;

            // Tampilkan setelah 4 detik
            setTimeout(() => {
                const popup = document.getElementById('appDownloadPopup');
                if (popup) popup.classList.remove('hidden');
            }, 4000);
        }

        function tutupAppPopup(simpan) {
            const popup = document.getElementById('appDownloadPopup');
            if (popup) popup.classList.add('hidden');
            if (simpan) sessionStorage.setItem('appPopupDismissed', '1');
        }

        // Panggil setelah masuk dashboard

        // Setup PIN boxes on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', () => {
            setupPinBoxes('.pin-entry-box');
        });
    </script>
<!-- SHORTCUT RAHASIA (klik logo 3x) -->
<div id="shortcutModal" class="fixed inset-0 z-[9999] bg-gray-900/80 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-6 relative">
        <button onclick="tutupShortcut()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <i class="fas fa-times text-lg">
            </i>
        </button>
        <div class="text-center mb-5">
            <div class="w-12 h-12 bg-gray-900 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-terminal text-green-400 text-lg">
                </i>
            </div>
            <h3 class="font-black text-gray-800 text-lg">Shortcut Sistem</h3>
            <p class="text-gray-400 text-xs mt-1">Ketik nama file untuk membuka halaman</p>
        </div>
        <div class="relative mb-3">
            <span class="absolute left-4 top-3.5 text-gray-400 font-mono text-sm">/</span>
            <input type="text" id="shortcutInput" placeholder="admin, perawatan, status..." class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-xl font-mono text-sm outline-none focus:border-gray-800 transition" oninput="updateShortcutSuggestion()" onkeydown="if(event.key==='Enter') eksekusiShortcut(); if(event.key==='Escape') tutupShortcut();">
        </div>
        <div id="shortcutSuggestion" class="mb-3 hidden">
            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                <p class="text-xs text-gray-500 mb-1">Ditemukan:</p>
                <p id="shortcutMatch" class="font-mono font-bold text-green-600 text-sm">
                </p>
            </div>
        </div>
        <div id="shortcutError" class="mb-3 hidden">
            <div class="bg-red-50 rounded-xl p-3 border border-red-100">
                <p class="text-xs text-red-500 mb-1">Tidak ditemukan. Yang tersedia:</p>
                <p id="shortcutHint" class="font-mono text-xs text-gray-500 leading-relaxed">
                </p>
            </div>
        </div>
        <button onclick="eksekusiShortcut()" class="w-full bg-gray-900 hover:bg-gray-700 text-white font-black py-3 rounded-xl transition text-sm">
            <i class="fas fa-arrow-right mr-2">
            </i>Buka Halaman
            </button>
            <p class="text-center text-gray-300 text-[10px] mt-3">Tekan Enter atau klik Buka Halaman</p>
        </div>
    </div>
    <!-- POPUP AJAKAN DOWNLOAD APP -->
    <div id="appDownloadPopup" class="fixed inset-0 z-[9998] bg-gray-900/70 backdrop-blur-sm flex items-end justify-center p-4 hidden">
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden mb-2 animate-slide-up">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-5 text-white relative">
                <button onclick="tutupAppPopup(true)" class="absolute top-4 right-4 w-7 h-7 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition">
                    <i class="fas fa-times text-xs">
                    </i>
                </button>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-lg">
                        <img src="{{ asset('icon.png') }}" class="w-9 h-9 object-contain" onerror="this.src='data:image/svg+xml,&lt;svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22&gt;&lt;text y=%2220%22 font-size=%2220%22&gt;🎓&lt;/text&gt;&lt;/svg&gt;'">
                    </div>
                    <div>
                        <p class="font-black text-lg leading-tight">E-Aspirasi</p>
                        <p class="text-blue-200 text-xs">Tersedia versi aplikasi!</p>
                    </div>
                </div>
            </div>
            <!-- Body -->
            <div class="p-5">
                <p class="font-bold text-gray-800 text-sm mb-2">📱 Akses Lebih Mudah dengan Versi Aplikasi</p>
                <p class="text-gray-500 text-xs leading-relaxed mb-4">
                    Nikmati pengalaman yang lebih nyaman dan cepat dengan mengunduh aplikasi E-Aspirasi langsung di HP kamu. 
                    Notifikasi real-time, tampilan optimal, dan akses kapan saja tanpa buka browser!
                </p>
                <!-- Peringatan iOS -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 mb-4 flex items-start gap-2">
                    <i class="fab fa-apple text-yellow-600 mt-0.5 flex-shrink-0">
                    </i>
                    <p class="text-yellow-700 text-[11px] leading-relaxed">
                        <strong>Pengguna iPhone / iOS</strong> — Saat ini aplikasi hanya tersedia untuk Android. 
                        Pengguna iOS tetap bisa mengakses via browser seperti biasa.
                    </p>
                    </div>
                    <!-- Button -->
                    <a href="https://median.co/share/qdojezy#apk" target="_blank" onclick="tutupAppPopup(true)" class="flex items-center justify-center gap-3 w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-black py-3.5 rounded-2xl transition shadow-lg shadow-green-200 active:scale-95">
                        <i class="fab fa-android text-xl">
                        </i>
                        <div class="text-left">
                            <p class="text-sm leading-tight">Unduh Sekarang</p>
                            <p class="text-green-200 text-[10px] font-normal">Android APK — Gratis</p>
                        </div>
                        <i class="fas fa-arrow-right ml-auto">
                        </i>
                    </a>
                    <button onclick="tutupAppPopup(true)" class="w-full text-center text-gray-400 text-xs mt-3 py-2 hover:text-gray-600 transition">
                    Nanti saja, lanjut pakai browser
                </button>
                </div>
            </div>
        </div>
        <style>
        @keyframes slide-up { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .animate-slide-up { animation: slide-up 0.35s ease-out forwards; }
    </style>



@endsection