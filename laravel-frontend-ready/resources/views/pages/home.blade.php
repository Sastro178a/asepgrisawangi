@extends('layouts.app')

@section('content')


    <!-- NAVBAR -->
<nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo & Nama Web (Default icon.png, ditimpa tampilan-web.js) -->
            <div class="flex items-center gap-3">
                <img id="logoWeb" src="{{ asset('icon.png') }}" alt="Logo" class="w-10 h-10 rounded-full object-cover border border-gray-200 bg-white p-0.5 shadow-sm">
                <div>
                    <h1 id="namaWebText" class="font-black text-lg text-blue-700 leading-tight">E-Aspirasi Grisawangi</h1>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">SMK PGRI 1 Giri</p>
                </div>
            </div>
            <!-- Tombol Navigasi Bantuan, Penilaian, Dokumentasi -->
            <div class="hidden md:flex items-center gap-6">
                <a href="penilaian.html" class="text-sm font-semibold text-yellow-600 hover:text-yellow-700 transition flex items-center gap-1">
                    <i class="fas fa-star">
                    </i> Penilaian
                    </a>
                    <a href="bantuan.html" class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition flex items-center gap-1">
                        <i class="fas fa-question-circle">
                        </i> Bantuan
                    </a>
                        <a href="dokumentasi.html" class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition flex items-center gap-1">
                            <i class="fas fa-book">
                            </i> Dokumentasi
                    </a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- POPUP TERAKHIR LOGIN (Sembunyi by default) -->
            <div id="lastSessionPopup" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] bg-white rounded-2xl shadow-2xl border-2 border-blue-500 p-4 w-[90%] max-w-sm popup-animate">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-2 text-blue-600">
                        <i class="fas fa-history text-lg">
                        </i>
                        <h4 class="font-black text-sm">Aktivitas Terakhir</h4>
                    </div>
                    <button onclick="closePopup()" class="text-gray-400 hover:text-red-500 transition">
                        <i class="fas fa-times">
                        </i>
                    </button>
                </div>
                <p id="lastSessionText" class="text-xs text-gray-600 mb-3">
            Anda terakhir mengunjungi portal Siswa.
        </p>
                <a id="lastSessionBtn" href="#" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-xl text-xs font-bold transition shadow-md">
            Lanjut ke Portal <i class="fas fa-arrow-right ml-1">
            </i>
        </a>
    </div>
    <!-- HERO SECTION (DUA KOLOM + FOTO GOSOK) -->
    <section class="hero-gradient relative pt-32 pb-20 px-4 overflow-hidden">
        <div class="absolute -right-20 -top-20 w-72 h-72 bg-white/5 rounded-full blur-2xl">
        </div>
        <div class="absolute -left-20 -bottom-20 w-56 h-56 bg-white/5 rounded-full blur-2xl">
        </div>
        <div class="relative z-10 max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-10">
            <!-- Bagian Kiri: Teks Aplikasi -->
            <div class="text-white md:w-1/2 text-center md:text-left">
                <span class="bg-white/20 border border-white/30 text-white text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full mb-6 inline-block">
                    Portal Layanan Digital Resmi
                </span>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight mb-4 leading-tight">
                    E-Aspirasi Grisawangi
                </h2>
                <div class="bg-black/20 rounded-2xl p-6 backdrop-blur-sm mb-8 border border-white/10 text-left">
                    <p class="text-blue-100 text-sm md:text-base font-medium leading-relaxed">
                        <strong>Tujuan &amp; Kegunaan:</strong> Platform ini dirancang khusus untuk memfasilitasi komunikasi dua arah yang aman dan transparan di lingkungan SMK PGRI 1 Giri. Sistem ini berguna untuk menampung laporan pelanggaran, penyampaian aspirasi dan masukan, penjadwalan konseling, hingga rekap absensi harian secara <em>real-time</em> dan terintegrasi.
                    </p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start gap-4">
                        <!-- TOMBOL MASUK SISWA -->
                        <a href="siswa.html" onclick="saveSession('Portal Siswa', 'siswa.html')" class="w-full sm:w-auto bg-white text-blue-700 hover:bg-blue-50 px-8 py-3.5 rounded-2xl text-sm font-black transition shadow-xl active:scale-95 flex items-center justify-center gap-2">
                            <i class="fas fa-sign-in-alt">
                            </i> Masuk Portal Siswa
                    </a>
                            <!-- TOMBOL GRISAEVENT (DITAMBAHKAN DI SINI KEMBALI) -->
                            <a href="/event/grisaevent.html" onclick="saveSession('Grisaevent', '/event/grisaevent.html')" class="w-full sm:w-auto bg-blue-500/20 border border-blue-300 hover:bg-blue-500/40 text-white px-8 py-3.5 rounded-2xl text-sm font-black transition shadow-xl active:scale-95 flex items-center justify-center gap-2">
                                <i class="fas fa-calendar-check">
                                </i> Portal Grisaevent
                    </a>
                            </div>
                        </div>
                        <!-- Bagian Kanan: Efek Foto Interaktif (Gosok) -->
                        <div class="md:w-1/2 w-full flex justify-center mt-8 md:mt-0">
                            <div class="relative w-full max-w-sm rounded-[2rem] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.5)] border-4 border-white/30 bg-blue-900/50 aspect-[3/4]">
                                <!-- Lapisan Gambar Bawah (grisa2.jpg) -->
                                <img src="{{ asset('grisa2.jpg') }}" class="absolute inset-0 w-full h-full object-cover pointer-events-none" alt="Grisa 2">
                                <!-- Lapisan Canvas Atas (grisa1.jpg) yang diusap -->
                                <canvas id="scratchCanvas" class="absolute inset-0 w-full h-full cursor-pointer transition-opacity duration-500" style="touch-action: none;">
                                </canvas>
                                <!-- Icon ditaruh di pojok -->
                                <img id="logoKanan" src="{{ asset('icon.png') }}" class="absolute bottom-5 right-5 w-14 h-14 pointer-events-none bg-white/20 backdrop-blur-md rounded-full p-2 border border-white/30 drop-shadow-xl object-cover" alt="Icon Web">
                                <!-- Animasi Hint Usap -->
                                <div id="swipeHint" class="absolute top-5 left-5 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold tracking-wider uppercase px-3 py-1.5 rounded-full pointer-events-none border border-white/20 animate-pulse transition-opacity duration-300">
                                    <i class="fas fa-hand-pointer mr-1">
                                    </i> Usap Foto
                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- FITUR UTAMA SECTION -->
                    <section class="py-16 px-4 max-w-7xl mx-auto">
                        <div class="text-center mb-12">
                            <h3 class="text-2xl font-black text-gray-800 mb-2">Pilih Portal Akses <i class="fas fa-hand-pointer text-blue-500">
                        </i>
                    </h3>
                    <p class="text-sm text-gray-500">Masuk ke layanan sesuai dengan peran dan hak akses Anda.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Portal Siswa -->
                    <div class="bg-white border border-gray-200 rounded-3xl p-6 transition duration-300 card-hover flex flex-col">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4 text-xl">
                            <i class="fas fa-user-circle">
                            </i>
                        </div>
                        <h4 class="font-black text-lg text-gray-800 mb-2">Portal Siswa</h4>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4 flex-grow">
                    Lapor aspirasi, kirim masukan, cek status tiket, pantau poin pelanggaran, dan jadwal konseling.
                </p>
                        <a href="siswa.html" onclick="saveSession('Portal Siswa', 'siswa.html')" class="inline-block mt-auto bg-blue-50 text-blue-600 text-center py-2.5 rounded-xl text-xs font-bold hover:bg-blue-600 hover:text-white transition">Masuk Portal →</a>
                    </div>
                    <!-- Portal Admin -->
                    <div class="bg-white border border-gray-200 rounded-3xl p-6 transition duration-300 card-hover flex flex-col">
                        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-4 text-xl">
                            <i class="fas fa-chart-line">
                            </i>
                        </div>
                        <h4 class="font-black text-lg text-gray-800 mb-2">Portal Admin</h4>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4 flex-grow">
                    Dashboard operasional, manajemen data siswa, tanggapi laporan, atur absensi, dan kontrol sistem penuh.
                </p>
                        <a href="admin.html" onclick="saveSession('Portal Admin', 'admin.html')" class="inline-block mt-auto bg-green-50 text-green-600 text-center py-2.5 rounded-xl text-xs font-bold hover:bg-green-600 hover:text-white transition">Masuk Portal →</a>
                    </div>
                    <!-- Portal Guru -->
                    <div class="bg-white border border-gray-200 rounded-3xl p-6 transition duration-300 card-hover flex flex-col">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 text-xl">
                            <i class="fas fa-chalkboard-teacher">
                            </i>
                        </div>
                        <h4 class="font-black text-lg text-gray-800 mb-2">Portal Guru</h4>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4 flex-grow">
                    Input absensi mengajar, laporkan pelanggaran, serta sinkronisasi jadwal otomatis.
                </p>
                        <a href="portal-guru.html" onclick="saveSession('Portal Guru', 'portal-guru.html')" class="inline-block mt-auto bg-indigo-50 text-indigo-600 text-center py-2.5 rounded-xl text-xs font-bold hover:bg-indigo-600 hover:text-white transition">Masuk Portal →</a>
                    </div>
                    <!-- Portal Orang Tua -->
                    <div class="bg-white border border-gray-200 rounded-3xl p-6 transition duration-300 card-hover flex flex-col">
                        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 text-xl">
                            <i class="fas fa-user-friends">
                            </i>
                        </div>
                        <h4 class="font-black text-lg text-gray-800 mb-2">Portal Orang Tua</h4>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4 flex-grow">
                    Pantau presensi harian, sanksi, dan riwayat poin anak Anda secara real-time.
                </p>
                        <a href="elakses.html" onclick="saveSession('Portal Orang Tua', 'elakses.html')" class="inline-block mt-auto bg-amber-50 text-amber-600 text-center py-2.5 rounded-xl text-xs font-bold hover:bg-amber-500 hover:text-white transition">Masuk Portal →</a>
                    </div>
                    <!-- Portal El Point / OSIS -->
                    <div class="bg-white border border-gray-200 rounded-3xl p-6 transition duration-300 card-hover flex flex-col">
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-4 text-xl">
                            <i class="fas fa-star">
                            </i>
                        </div>
                        <h4 class="font-black text-lg text-gray-800 mb-2">Portal El Point</h4>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4 flex-grow">
                    Akses khusus petugas OSIS / Sekretaris untuk scan QR absensi dan pendataan pelanggaran.
                </p>
                        <a href="elpoint.html" onclick="saveSession('Portal El Point', 'elpoint.html')" class="inline-block mt-auto bg-purple-50 text-purple-600 text-center py-2.5 rounded-xl text-xs font-bold hover:bg-purple-600 hover:text-white transition">Masuk Portal →</a>
                    </div>
                    <!-- Menu Ekstra -->
                    <div class="bg-gray-800 rounded-3xl p-6 transition duration-300 card-hover text-white flex flex-col justify-center gap-4">
                        <h4 class="font-black text-lg mb-1">Layanan Ekstra</h4>
                        <a href="penilaian.html" class="flex items-center justify-between bg-gray-700 hover:bg-gray-600 p-3 rounded-xl transition">
                            <span class="text-sm font-bold flex items-center gap-2">
                                <i class="fas fa-star text-yellow-400">
                                </i> Beri Penilaian</span>
                                <i class="fas fa-chevron-right text-xs text-gray-400">
                                </i>
                            </a>
                            <a href="bantuan.html" class="flex items-center justify-between bg-gray-700 hover:bg-gray-600 p-3 rounded-xl transition">
                                <span class="text-sm font-bold flex items-center gap-2">
                                    <i class="fas fa-headset text-blue-400">
                                    </i> Pusat Bantuan</span>
                                    <i class="fas fa-chevron-right text-xs text-gray-400">
                                    </i>
                                </a>
                                <!-- MENU ASISTEN GRISVA (DITAMBAHKAN DI SINI KEMBALI) -->
                                <a href="grisva.html" class="flex items-center justify-between bg-gray-700 hover:bg-gray-600 p-3 rounded-xl transition">
                                    <span class="text-sm font-bold flex items-center gap-2">
                                        <i class="fas fa-robot text-cyan-400">
                                        </i> Asisten GrisVA</span>
                                        <i class="fas fa-chevron-right text-xs text-gray-400">
                                        </i>
                                    </a>
                                    <a href="dokumentasi.html" class="flex items-center justify-between bg-gray-700 hover:bg-gray-600 p-3 rounded-xl transition">
                                        <span class="text-sm font-bold flex items-center gap-2">
                                            <i class="fas fa-book text-green-400">
                                            </i> Dokumentasi</span>
                                            <i class="fas fa-chevron-right text-xs text-gray-400">
                                            </i>
                                        </a>
                                    </div>
                                </div>
                            </section>
                            <!-- FOOTER -->
                            <!-- SCRIPT LOGIKA BAWAAN & EFEK SCRATCH -->
                            <script>
        // --- 1. Fungsi Integrasi Tampilan Web ---
        document.addEventListener('DOMContentLoaded', () => {
            // Menerapkan nama web dari file tampilan-web.js
            if (window.NAMA_WEB) {
                const namaTeks = document.getElementById('namaWebText');
                if (namaTeks) namaTeks.innerText = window.NAMA_WEB;
                document.title = "Portal Utama | " + window.NAMA_WEB;
            }
            
            // Menerapkan logo URL dari file tampilan-web.js
            // (Akan menimpa icon.png bawaan baik yang di navbar maupun di kotak gambar usap)
            if (window.LOGO_URL) {
                const logoNav = document.getElementById('logoWeb');
                const logoKanan = document.getElementById('logoKanan');
                if (logoNav) logoNav.src = window.LOGO_URL;
                if (logoKanan) logoKanan.src = window.LOGO_URL;
            }
            
            cekSesiTerakhir();
        });

        function saveSession(portalName, portalUrl) {
            localStorage.setItem('lastPortalName', portalName);
            localStorage.setItem('lastPortalUrl', portalUrl);
        }

        function cekSesiTerakhir() {
            const lastPortalName = localStorage.getItem('lastPortalName');
            const lastPortalUrl = localStorage.getItem('lastPortalUrl');
            if (lastPortalName && lastPortalUrl) {
                const popup = document.getElementById('lastSessionPopup');
                const text = document.getElementById('lastSessionText');
                const btn = document.getElementById('lastSessionBtn');
                text.innerHTML = `Anda terakhir mengunjungi <strong>${lastPortalName}</strong>. Lanjutkan aktivitas Anda?`;
                btn.href = lastPortalUrl;
                popup.classList.remove('hidden');
            }
        }

        function closePopup() {
            document.getElementById('lastSessionPopup').classList.add('hidden');
        }

        // --- 2. SCRIPT EFEK USAP FOTO DENGAN ANIMASI PUDAR (FADE) ---
        const canvas = document.getElementById('scratchCanvas');
        const ctx = canvas.getContext('2d', { willReadFrequently: true });
        const hint = document.getElementById('swipeHint');

        // Memuat foto awalan (grisa1.jpg) ke dalam canvas
        const imgTop = new Image();
        imgTop.src = 'grisa1.jpg'; // Path relatif

        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;
        let resetTimer;           // Timer untuk hitung mundur 3 detik
        let animationFrameId;     // ID untuk animasi pudar (fade)

        function initCanvas() {
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
            
            // Simulasikan object-fit: cover untuk canvas
            const scale = Math.max(canvas.width / imgTop.width, canvas.height / imgTop.height);
            const x = (canvas.width / 2) - (imgTop.width / 2) * scale;
            const y = (canvas.height / 2) - (imgTop.height / 2) * scale;
            
            ctx.globalCompositeOperation = 'source-over';
            ctx.globalAlpha = 1.0; 
            ctx.drawImage(imgTop, x, y, imgTop.width * scale, imgTop.height * scale);
        }

        imgTop.onload = initCanvas;
        window.addEventListener('resize', initCanvas);

        function getMousePos(e) {
            const rect = canvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return {
                x: (clientX - rect.left) * (canvas.width / rect.width),
                y: (clientY - rect.top) * (canvas.height / rect.height)
            };
        }

        function scratch(x, y) {
            ctx.globalCompositeOperation = 'destination-out';
            
            ctx.beginPath();
            ctx.arc(x, y, 45, 0, Math.PI * 2);
            ctx.fill();
            
            ctx.lineWidth = 90;
            ctx.lineCap = 'round';
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(x, y);
            ctx.stroke();
            
            lastX = x;
            lastY = y;
        }

        // --- Fungsi Animasi Pudar (Fade In) saat reset ---
        function resetScratchFade() {
            let opacity = 0;
            ctx.globalCompositeOperation = 'source-over';
            
            const scale = Math.max(canvas.width / imgTop.width, canvas.height / imgTop.height);
            const x = (canvas.width / 2) - (imgTop.width / 2) * scale;
            const y = (canvas.height / 2) - (imgTop.height / 2) * scale;

            function fadeIn() {
                opacity += 0.03; // Kecepatan pudar
                
                if (opacity >= 1) {
                    initCanvas();
                    if(hint) hint.style.opacity = '1';
                    return; 
                }
                
                ctx.globalAlpha = opacity;
                ctx.drawImage(imgTop, x, y, imgTop.width * scale, imgTop.height * scale);
                ctx.globalAlpha = 1.0;
                
                animationFrameId = requestAnimationFrame(fadeIn);
            }
            
            fadeIn();
        }

        function startDraw(e) {
            isDrawing = true;
            if(hint) hint.style.opacity = '0';
            
            clearTimeout(resetTimer); 
            cancelAnimationFrame(animationFrameId); 
            ctx.globalAlpha = 1.0; 
            
            const pos = getMousePos(e);
            lastX = pos.x;
            lastY = pos.y;
            scratch(pos.x, pos.y);
        }

        function moveDraw(e) {
            if (!isDrawing) return;
            e.preventDefault();
            const pos = getMousePos(e);
            scratch(pos.x, pos.y);
        }

        function stopDraw() {
            if (!isDrawing) return;
            isDrawing = false;
            clearTimeout(resetTimer);
            resetTimer = setTimeout(resetScratchFade, 3000); 
        }

        canvas.addEventListener('mousedown', startDraw);
        canvas.addEventListener('mousemove', moveDraw);
        canvas.addEventListener('mouseup', stopDraw);
        canvas.addEventListener('mouseleave', stopDraw);

        canvas.addEventListener('touchstart', startDraw, {passive: false});
        canvas.addEventListener('touchmove', moveDraw, {passive: false});
        canvas.addEventListener('touchend', stopDraw);
        canvas.addEventListener('touchcancel', stopDraw);

    </script>
                            <!-- MENGHUBUNGKAN ASISTEN GRISVA SEBAGAI WIDGET -->
                            <script src="{{ asset('/grisVA.js') }}" defer="">
                            </script>



@endsection