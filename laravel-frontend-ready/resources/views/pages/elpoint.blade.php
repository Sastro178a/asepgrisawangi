@extends('layouts.app')

@section('content')


    <!-- TOP BAR -->
<div class="bg-gray-900 text-white text-xs py-2 px-4 flex justify-between items-center shadow-md relative z-50 flex-shrink-0">
    <div class="flex items-center gap-2 font-black tracking-widest text-blue-400">
        <i class="fas fa-qrcode">
        </i> EL POINT
        </div>
        <div id="topBarUser" class="hidden font-bold text-gray-300 flex items-center gap-2">
            <span id="realtimeClock" class="text-yellow-400 font-mono border-r border-gray-700 pr-2 mr-1">00:00</span>
            <i class="fas fa-user-shield">
            </i>
            <span id="displayTopName">Memuat...</span>
        </div>
    </div>
    <!-- LAYAR LOGIN -->
    <div id="loginScreen" class="fixed inset-0 z-40 bg-gray-100 flex flex-col justify-center items-center p-4 fade-in overflow-y-auto">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-sm p-8 text-center border-t-8 border-blue-600 relative my-auto">
            <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl shadow-inner border-4 border-white -mt-16 relative z-10">
                <i class="fas fa-user-secret">
                </i>
            </div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight mb-1">Akses Petugas</h1>
            <p class="text-xs text-gray-500 font-medium mb-6 leading-relaxed">Silakan masuk menggunakan akun yang telah diberikan.</p>
            <form id="loginForm" class="space-y-4 text-left w-full">
                <div class="w-full">
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-widest mb-1.5">Username</label>
                    <div class="relative w-full">
                        <i class="fas fa-user absolute left-4 top-3.5 text-gray-400">
                        </i>
                        <input type="text" id="loginUser" required="" class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 bg-gray-50" placeholder="Ketik username...">
                    </div>
                </div>
                <div class="w-full">
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-widest mb-1.5">Password</label>
                    <div class="relative w-full">
                        <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400">
                        </i>
                        <input type="password" id="loginPass" required="" class="w-full pl-11 pr-12 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 bg-gray-50" placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-3.5 text-gray-400 hover:text-blue-500 transition focus:outline-none">
                            <i id="eyeIcon" class="fas fa-eye">
                            </i>
                        </button>
                    </div>
                </div>
                <button type="submit" id="btnLogin" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-lg transition active:scale-95 uppercase tracking-widest text-sm mt-6 flex justify-center items-center gap-2">
                    Masuk Aplikasi <i class="fas fa-sign-in-alt">
            </i>
        </button>
    </form>
</div>
</div>
<!-- AREA KERJA UTAMA (DASHBOARD) -->
<div id="mainApp" class="hidden flex-1 flex flex-col p-4 md:p-6 bg-gray-100 max-w-lg mx-auto w-full relative pb-20">
    <!-- Header Info -->
    <div class="bg-blue-600 text-white rounded-2xl p-5 shadow-lg mb-6 flex justify-between items-center relative overflow-hidden flex-shrink-0">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-xl">
        </div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-widest text-blue-200 mb-0.5" id="displayRoleLevel">Petugas</p>
            <h2 id="displayName" class="text-xl font-bold tracking-tight">Nama</h2>
        </div>
        <button onclick="logout()" class="relative z-10 bg-white/20 hover:bg-red-500 hover:text-white px-3 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 backdrop-blur-sm border border-white/30">
            <i class="fas fa-power-off">
            </i> Keluar
            </button>
        </div>
        <!-- MENU ABSENSI MASSAL (Semua Role Bisa Lihat) -->
        <div class="mb-6">
            <h3 class="text-sm font-black text-gray-800 mb-3 border-b-2 border-blue-500 inline-block pb-1">Tugas Kehadiran</h3>
            <button onclick="bukaModalAbsenMassal()" class="w-full bg-white border-2 border-blue-200 hover:border-blue-500 p-5 rounded-2xl transition flex items-center justify-between group shadow-sm">
                <div class="text-left">
                    <h4 class="font-black text-blue-700 text-base mb-1 group-hover:text-blue-800">Lapor Absensi Kelas</h4>
                    <p class="text-[10px] text-gray-500 font-medium">Bisa pilih banyak siswa sekaligus (Hadir/Sakit/Izin/Alpa)</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-full flex justify-center items-center text-xl group-hover:bg-blue-500 group-hover:text-white transition">
                    <i class="fas fa-users">
                    </i>
                </div>
            </button>
        </div>
        <!-- AREA KAMERA SCANNER (Hanya Muncul Jika OSIS) -->
        <div id="scannerSection" class="fade-in flex-1 flex flex-col pt-2 border-t border-gray-200 hidden">
            <div class="mb-4 text-center">
                <h3 class="text-lg font-black text-gray-800 mb-1">Pindai Kartu Digital (QR)</h3>
                <p class="text-xs text-gray-500 font-medium">Otomatis mencatat Absen Masuk &amp; Deteksi Telat.</p>
            </div>
            <div id="reader" class="bg-white shadow-sm mb-4">
            </div>
            <div class="my-4 flex items-center justify-center gap-2">
                <div class="h-px bg-gray-300 w-12">
                </div>
                <span class="text-[10px] font-black text-gray-400 uppercase">ATAU PENCARIAN MANUAL</span>
                <div class="h-px bg-gray-300 w-12">
                </div>
            </div>
            <!-- Form Pencarian Manual (Nama / NISN) -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 text-left relative mb-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-3.5 text-blue-400">
                    </i>
                    <input type="text" id="inputCariManual" autocomplete="off" placeholder="Ketik nama siswa..." class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm bg-gray-50">
                    <ul id="dropdownCariManual" class="absolute w-full bg-white border border-gray-200 rounded-xl shadow-2xl mt-1 hidden max-h-48 overflow-y-auto custom-scrollbar text-left divide-y divide-gray-100 z-50 bottom-full mb-1">
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================== -->
    <!-- MODAL HASIL SCAN (AUTO ABSEN & POIN TAMBAHAN)  -->
    <!-- ============================================== -->
    <div id="scanResultModal" class="fixed inset-0 bg-black/80 z-[100] flex items-center justify-center p-4 hidden fade-in backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl relative border-t-8 my-auto transition-colors duration-300 border-green-500" id="scanResultCard">
            <!-- Icon Indikator -->
            <div id="scanResultIcon" class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl shadow-inner border-4 border-white -mt-10 relative z-10 transition-colors duration-300">
                <i class="fas fa-check" id="iconInnerResult">
                </i>
            </div>
            <div class="p-6 md:p-8 pt-2">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight" id="scanResultTitle">Tercatat Hadir</h2>
                    <p class="text-xs text-gray-500 font-bold mt-1" id="scanResultTime">Memuat waktu...</p>
                </div>
                <!-- Info Siswa -->
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-4 text-center shadow-inner relative overflow-hidden">
                    <div id="scanResultAccent" class="absolute top-0 left-0 w-1.5 h-full bg-green-500 transition-colors duration-300">
                    </div>
                    <p id="targetNama" class="font-black text-lg text-gray-800 mb-1 leading-tight">Nama Siswa</p>
                    <p class="text-xs font-bold text-gray-500 font-mono mb-2">NISN: <span id="targetNisn" class="text-blue-600">123</span>
                </p>
                <span id="targetKelas" class="bg-white border border-gray-300 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-gray-600 shadow-sm">Kelas</span>
                <input type="hidden" id="targetId">
            </div>
            <!-- Notifikasi Telat Otomatis -->
            <div id="lateWarning" class="hidden bg-red-50 border border-red-200 rounded-xl p-3 mb-5 flex items-start gap-3 animate-pulse">
                <div class="text-red-500 mt-0.5">
                    <i class="fas fa-exclamation-triangle text-lg">
                    </i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-black text-red-800 uppercase tracking-widest mb-0.5">Terlambat!</p>
                    <p class="text-[10px] text-red-600 font-medium">Sistem telah otomatis menambahkan poin sanksi keterlambatan ke profil siswa ini.</p>
                </div>
            </div>
            <div class="space-y-3">
                <!-- Tombol Lapor Poin Tambahan -->
                <button onclick="toggleExtraPoin()" id="btnToggleExtraPoin" class="w-full bg-orange-50 text-orange-600 border border-orange-200 hover:bg-orange-100 p-3.5 rounded-xl transition flex items-center justify-center gap-2 font-bold text-xs">
                    <i class="fas fa-gavel">
                    </i> Lapor Pelanggaran Lain (Atribut, dll)
                    </button>
                    <!-- Form Poin Tambahan (Hidden) -->
                    <div id="extraPoinForm" class="hidden bg-orange-50 p-5 rounded-xl border border-orange-200 fade-in">
                        <label class="block text-[10px] font-black text-orange-800 uppercase tracking-widest mb-2">Pilih Pelanggaran Tambahan:</label>
                        <select id="jenisPelanggaran" class="w-full px-3 py-3 border border-orange-300 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-bold text-gray-800 bg-white cursor-pointer text-xs mb-3">
                            <option value="" disabled="" selected="">-- Memuat Daftar Pelanggaran --</option>
                        </select>
                        <button onclick="prosesPoinTambahan()" id="btnKirimPoin" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-black py-3 rounded-lg text-xs uppercase tracking-widest transition shadow-md active:scale-95">
                            Kirim Poin Tambahan
                        </button>
                    </div>
                    <!-- Tombol Selesai -->
                    <button onclick="tutupScanResult()" class="w-full bg-gray-800 hover:bg-gray-900 text-white p-4 rounded-xl shadow-md transition font-black text-sm uppercase tracking-widest flex items-center justify-center gap-2 active:scale-95">
                        <i class="fas fa-qrcode">
                        </i> Selesai (Scan Lanjut)
                    </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================== -->
        <!-- MODAL ABSENSI MASSAL (SEKRETARIS & OSIS)       -->
        <!-- ============================================== -->
        <div id="massalModal" class="fixed inset-0 bg-gray-900/90 z-[110] flex items-center justify-center p-4 hidden fade-in backdrop-blur-sm overflow-y-auto">
            <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl relative border-t-8 border-blue-500 my-auto flex flex-col max-h-[90vh]">
                <div class="p-6 border-b flex justify-between items-center flex-shrink-0">
                    <div>
                        <h2 class="text-xl font-black text-gray-800 tracking-tight">
                            <i class="fas fa-clipboard-list text-blue-500 mr-1">
                            </i> Lapor Absensi</h2>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">Multi-Siswa / Sekaligus</p>
                        </div>
                        <button onclick="tutupModalAbsenMassal()" class="text-gray-400 hover:text-red-500 bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center transition">
                            <i class="fas fa-times">
                            </i>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
                        <!-- Input Pencarian Massal -->
                        <div class="relative mb-4">
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">1. Cari &amp; Tambah Siswa</label>
                            <div class="relative">
                                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400">
                                </i>
                                <input type="text" id="inputCariMassal" autocomplete="off" placeholder="Ketik nama siswa lalu pilih..." class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm bg-gray-50">
                            </div>
                            <ul id="dropdownCariMassal" class="absolute w-full bg-white border border-gray-200 rounded-xl shadow-2xl mt-1 hidden max-h-40 overflow-y-auto custom-scrollbar text-left divide-y divide-gray-100 z-50">
                            </ul>
                        </div>
                        <!-- Daftar Siswa Terpilih -->
                        <div class="mb-6">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Daftar Terpilih (<span id="countMassal">0</span>)</p>
                            <div id="daftarTerpilihMassal" class="flex flex-wrap gap-2 p-3 bg-gray-50 border border-gray-200 rounded-xl min-h-[60px]">
                                <p class="text-xs text-gray-400 italic w-full text-center my-auto" id="placeholderMassal">Belum ada siswa yang dipilih.</p>
                            </div>
                        </div>
                        <!-- Pilihan Status Absen -->
                        <div class="mb-2">
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-2">2. Tetapkan Status Serentak</label>
                            <div class="grid grid-cols-4 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="alasanAbsen" value="Hadir" class="peer sr-only" checked="">
                                    <div class="text-center p-2 rounded-xl border-2 border-gray-200 font-bold text-gray-500 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 transition text-[10px] sm:text-xs">
                                        <i class="fas fa-check-circle block mb-1 text-base sm:text-lg">
                                        </i> Hadir</div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="alasanAbsen" value="Sakit" class="peer sr-only">
                                        <div class="text-center p-2 rounded-xl border-2 border-gray-200 font-bold text-gray-500 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition text-[10px] sm:text-xs">
                                            <i class="fas fa-briefcase-medical block mb-1 text-base sm:text-lg">
                                            </i> Sakit</div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="alasanAbsen" value="Izin" class="peer sr-only">
                                            <div class="text-center p-2 rounded-xl border-2 border-gray-200 font-bold text-gray-500 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 peer-checked:text-yellow-700 transition text-[10px] sm:text-xs">
                                                <i class="fas fa-envelope-open-text block mb-1 text-base sm:text-lg">
                                                </i> Izin</div>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="alasanAbsen" value="Alpa" class="peer sr-only">
                                                <div class="text-center p-2 rounded-xl border-2 border-gray-200 font-bold text-gray-500 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 transition text-[10px] sm:text-xs">
                                                    <i class="fas fa-user-alt-slash block mb-1 text-base sm:text-lg">
                                                    </i> Alpa</div>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Upload surat (WAJIB untuk Sakit / Izin) -->
                                        <div id="suratMassalWrap" class="hidden mt-4">
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">3. Foto Surat <span class="text-red-500">* wajib</span>
                                        </label>
                                        <label for="suratFileMassal" class="block cursor-pointer border-2 border-dashed border-gray-300 hover:border-blue-400 rounded-xl p-4 text-center transition bg-gray-50">
                                            <img id="suratPrevMassal" class="hidden mx-auto max-h-44 rounded-lg mb-2" alt="Preview surat">
                                            <span id="suratLabelMassal" class="text-xs font-bold text-gray-500">
                                                <i class="fas fa-camera mr-1">
                                                </i>Ketuk untuk foto / pilih surat</span>
                                            </label>
                                            <input type="file" id="suratFileMassal" accept="image/*" class="hidden" onchange="pilihSuratMassal(this)">
                                            <p class="text-[10px] text-gray-400 mt-1.5">Satu foto surat dilampirkan ke semua siswa yang dipilih. Jika suratnya berbeda-beda, kirim terpisah per siswa.</p>
                                        </div>
                                    </div>
                                    <div class="p-6 border-t bg-gray-50 flex-shrink-0">
                                        <button onclick="kirimAbsenMassal()" id="btnKirimMassal" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-lg transition active:scale-95 uppercase tracking-widest text-sm flex justify-center items-center gap-2">
                                            <i class="fas fa-paper-plane">
                                            </i> Kirim Data Absen
                </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- LOGIKA JAVASCRIPT -->
                                <script>
        // ==============================================
        // STATE GLOBAL
        // ==============================================
        let currentUser = null;
        let html5QrcodeScanner = null;
        let isScanning = false;
        let arraySiswaMassal = []; 
        let daftarPelanggaranServer = []; // Diambil otomatis dari database admin

        // ==============================================
        // SETUP AWAL & JAM OTOMATIS
        // ==============================================
        function togglePassword() {
            const input = document.getElementById('loginPass');
            const icon = document.getElementById('eyeIcon');
            if (input.type === "password") { input.type = "text"; icon.classList.replace('fa-eye', 'fa-eye-slash'); } 
            else { input.type = "password"; icon.classList.replace('fa-eye-slash', 'fa-eye'); }
        }

        function initClock() {
            setInterval(() => {
                const now = new Date();
                const hh = String(now.getHours()).padStart(2, '0');
                const mm = String(now.getMinutes()).padStart(2, '0');
                document.getElementById('realtimeClock').textContent = `${hh}:${mm}`;
            }, 1000);
        }

        // ==============================================
        // KONEKSI API BACKEND
        // ==============================================
        async function callBackend(payload) {
            const response = await fetch('/api/notify', {
                method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
            });
            if (!response.ok) { const t = await response.text(); let m = t; try { m = JSON.parse(t).error || t; } catch(e) {} throw new Error(m); }
            return await response.json();
        }

        // ==============================================
        // HELPER FOTO SURAT (Sakit / Izin wajib lampirkan surat)
        // ==============================================
        function kompresFoto(file, maxW = 1280, q = 0.8) {
            return new Promise((resolve, reject) => {
                const fr = new FileReader();
                fr.onerror = () => reject(new Error('File tidak bisa dibaca'));
                fr.onload = () => {
                    const img = new Image();
                    img.onerror = () => reject(new Error('File bukan gambar yang valid'));
                    img.onload = () => {
                        let w = img.width, h = img.height;
                        if (w > maxW) { h = Math.round(h * maxW / w); w = maxW; }
                        const c = document.createElement('canvas'); c.width = w; c.height = h;
                        const ctx = c.getContext('2d'); ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, w, h); ctx.drawImage(img, 0, 0, w, h);
                        resolve(c.toDataURL('image/jpeg', q));
                    };
                    img.src = fr.result;
                };
                fr.readAsDataURL(file);
            });
        }
        async function uploadSurat(dataUrl) {
            const r = await callBackend({ type: 'upload', source: dataUrl });
            if (!r || !r.url) throw new Error('Upload foto surat gagal. Coba lagi.');
            return r.url;
        }

        // --- Surat untuk modal absen massal ---
        let suratMassalDataUrl = null;
        function resetSuratMassal() {
            suratMassalDataUrl = null;
            const inp = document.getElementById('suratFileMassal'); if (inp) inp.value = '';
            const prev = document.getElementById('suratPrevMassal'); if (prev) { prev.src = ''; prev.classList.add('hidden'); }
            const lab = document.getElementById('suratLabelMassal'); if (lab) lab.innerHTML = '<i class="fas fa-camera mr-1">
                            </i>Ketuk untuk foto / pilih surat';
            toggleSuratMassal();
        }
        function toggleSuratMassal() {
            const v = document.querySelector('input[name="alasanAbsen"]:checked')?.value;
            const wrap = document.getElementById('suratMassalWrap');
            if (wrap) wrap.classList.toggle('hidden', !(v === 'Sakit' || v === 'Izin'));
        }
        async function pilihSuratMassal(input) {
            const f = input.files && input.files[0]; if (!f) return;
            try {
                suratMassalDataUrl = await kompresFoto(f);
                const prev = document.getElementById('suratPrevMassal'); prev.src = suratMassalDataUrl; prev.classList.remove('hidden');
                document.getElementById('suratLabelMassal').innerHTML = '<i class="fas fa-sync-alt mr-1">
                        </i>Ganti foto surat';
            } catch (e) { suratMassalDataUrl = null; Swal.fire('Gagal', e.message, 'error'); }
        }

        document.addEventListener("DOMContentLoaded", () => {
            initClock();
            cekSesi();

            document.getElementById('loginForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn = document.getElementById('btnLogin');
                const user = document.getElementById('loginUser').value.trim();
                const pass = document.getElementById('loginPass').value;
                
                btn.innerHTML = '<i class="fas fa-circle-notch fa-spin">
                    </i> Memeriksa...'; btn.disabled = true;

                try {
                    const res = await callBackend({ type: 'supabase_get', action: 'loginElPoint', payload: { username: user, password: pass } });
                    if (res.data) {
                        currentUser = res.data;
                        const roleDariDB = currentUser.role ? currentUser.role.toLowerCase() : 'osis';
                        currentUser.ui_role = roleDariDB.includes('sekretaris') ? 'sekretaris' : 'osis'; 
                        
                        localStorage.setItem('elpoint_session', JSON.stringify(currentUser));
                        Swal.fire({toast: true, position: 'top-end', icon: 'success', title: 'Login Berhasil', showConfirmButton: false, timer: 1500});
                        masukAplikasi();
                    }
                } catch (err) { Swal.fire('Login Gagal', err.message, 'error'); } 
                finally { btn.innerHTML = 'Masuk Aplikasi <i class="fas fa-sign-in-alt">
                </i>'; btn.disabled = false; }
            });

            setupAutocomplete('inputCariManual', 'dropdownCariManual', async (siswa) => {
                document.getElementById('inputCariManual').value = "";
                // Input manual: tampilkan popup konfirmasi status + poin
                await popupKonfirmasiManual(siswa);
            });

            setupAutocomplete('inputCariMassal', 'dropdownCariMassal', async (siswa) => {
                // Pencarian di dalam modal massal: tambahkan ke daftar terpilih
                document.getElementById('inputCariMassal').value = '';
                tambahSiswaMassal(siswa);
            });
            document.querySelectorAll('input[name="alasanAbsen"]').forEach(r => r.addEventListener('change', toggleSuratMassal));
        });

        function cekSesi() {
            const saved = localStorage.getItem('elpoint_session');
            if (saved) { currentUser = JSON.parse(saved); masukAplikasi(); }
        }

        function logout() {
            Swal.fire({ title: 'Keluar?', text: "Anda akan mengakhiri sesi tugas.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya' }).then((r) => {
                if (r.isConfirmed) {
                    hentikanKamera(); localStorage.removeItem('elpoint_session'); currentUser = null;
                    document.getElementById('mainApp').classList.add('hidden'); document.getElementById('topBarUser').classList.add('hidden'); document.getElementById('loginScreen').classList.remove('hidden'); document.getElementById('loginForm').reset();
                }
            });
        }

        function masukAplikasi() {
            document.getElementById('loginScreen').classList.add('hidden');
            document.getElementById('mainApp').classList.remove('hidden');
            document.getElementById('topBarUser').classList.remove('hidden');
            document.getElementById('displayName').textContent = currentUser.nama_anggota;
            
            const isOsis = currentUser.ui_role === 'osis';
            
            if(isOsis) {
                document.getElementById('displayRoleLevel').textContent = "Petugas OSIS";
                document.getElementById('scannerSection').classList.remove('hidden');
                loadDaftarPelanggaranDariServer(); // Tarik data Buku Saku
                mulaiKamera();
            } else {
                document.getElementById('displayRoleLevel').textContent = "Sekretaris Kelas";
                document.getElementById('scannerSection').classList.add('hidden');
                hentikanKamera();
            }
        }

        // AMBIL BUKU SAKU DARI SERVER
        async function loadDaftarPelanggaranDariServer() {
            try {
                const res = await callBackend({ type: 'supabase_get', action: 'getAllPelanggaran' });
                if(res.data && res.data.length > 0) {
                    daftarPelanggaranServer = res.data;
                    const sel = document.getElementById('jenisPelanggaran');
                    sel.innerHTML = '<option value="" disabled selected>-- Pilih Pelanggaran Lain --</option>';
                    daftarPelanggaranServer.forEach(p => {
                        sel.innerHTML += `<option value="${p.point}|${p.kelakuan}">${p.kelakuan} (+${p.point} Poin)</option>`;
                    });
                }
            } catch(e) { console.log("Gagal memuat buku saku"); }
        }

        // ==============================================
        // LOGIKA KAMERA SCANNER QR (Hanya OSIS)
        // ==============================================
        async function mulaiKamera() {
            if(isScanning) return;

            // Minta izin kamera eksplisit dulu — wajib agar Median/Android munculkan popup izin
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                // Langsung stop stream dummy — kita cuma butuh trigger izin
                stream.getTracks().forEach(track => track.stop());
            } catch(err) {
                console.warn('[Kamera] Izin ditolak atau tidak tersedia:', err);
                Swal.fire({
                    icon: 'warning',
                    title: 'Izin Kamera Diperlukan',
                    html: `Aplikasi membutuhkan akses kamera untuk scan QR.<br>
                <br>
                <b>Cara mengizinkan:</b>
                <br>
                           Buka <b>Pengaturan HP → Aplikasi → E-Aspirasi → Izin → Kamera → Izinkan</b>`,
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            // Izin diberikan, mulai scanner
            html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0,
                showTorchButtonIfSupported: true,
                showZoomSliderIfSupported: true,
                defaultZoomValueIfSupported: 1.5
            });
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            isScanning = true;
        }

        function hentikanKamera() {
            if (html5QrcodeScanner && isScanning) {
                try { html5QrcodeScanner.pause(true); } catch(e) { console.warn("Kamera sedang transisi", e); }
            }
        }
        function resumeKamera() {
            if (html5QrcodeScanner && isScanning) {
                try { html5QrcodeScanner.resume(); } catch(e) { mulaiKamera(); }
            } else {
                mulaiKamera();
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            hentikanKamera(); // Pause kamera saat memproses
            try { const aCtx = new (window.AudioContext || window.webkitAudioContext)(); const osc = aCtx.createOscillator(); osc.type = 'sine'; osc.frequency.setValueAtTime(800, aCtx.currentTime); osc.connect(aCtx.destination); osc.start(); osc.stop(aCtx.currentTime + 0.1); } catch(e){}
            
            Swal.fire({ title: 'Membaca Data...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            callBackend({ type: 'supabase_get', action: 'scanQR', payload: { nisn: decodedText.trim() } })
            .then(res => {
                Swal.close();
                if(res.data) {
                    prosesAbsenOtomatis(res.data);
                }
            }).catch(err => {
                Swal.fire({ title: 'Tidak Ditemukan!', text: "QR Code tidak terdaftar di sistem E-Aspirasi.", icon: 'error', confirmButtonText: 'Scan Ulang' }).then(() => resumeKamera());
            });
        }
        function onScanFailure(error) {}

        // ==============================================
        // AUTOCOMPLETE PENCARIAN
        // ==============================================
        function setupAutocomplete(inputId, dropdownId, onSelectCallback) {
            const inp = document.getElementById(inputId); const dp = document.getElementById(dropdownId); let t;
            if(!inp || !dp) return;
            window._autoHasil = window._autoHasil || {};

            inp.addEventListener('input', function() {
                clearTimeout(t); const v = this.value.trim(); if(v.length < 2) { dp.classList.add('hidden'); return; }
                t = setTimeout(async () => {
                    dp.innerHTML = '<li class="p-4 text-xs font-bold text-blue-500 text-center">
                <i class="fas fa-spinner fa-spin mr-2">
                </i>Mencari...</li>'; dp.classList.remove('hidden');
                    try {
                        const res = await callBackend({ type: 'supabase_get', action: 'cariSiswa', payload: { nama: v } });
                        if(res.data && res.data.length>0) {
                            window._autoHasil[dropdownId] = { data: res.data, cb: onSelectCallback };
                            dp.innerHTML = res.data.map((s, i) => `<li class="p-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 transition" onclick="pilihSiswaIdx('${dropdownId}', ${i})">
                <div class="font-black text-sm text-gray-800">${s.nama}</div>
                <div class="text-[10px] text-gray-500 font-bold mt-1">
                    <span class="bg-gray-100 border px-1 rounded mr-1">${s.kelas}</span> NISN: ${s.nisn||'-'}</div>
                </li>`).join('');
                        } else { dp.innerHTML = '<li class="p-4 text-xs font-bold text-red-500 text-center">Siswa tidak ditemukan</li>'; }
                    } catch(e){ dp.classList.add('hidden'); }
                }, 500);
            });
        }

        // Dipanggil saat salah satu hasil pencarian diklik (berdasarkan index, bukan nama fungsi)
        window.pilihSiswaIdx = function(dropdownId, idx) {
            const h = (window._autoHasil || {})[dropdownId];
            document.querySelectorAll('[id^="dropdownCari"]').forEach(el => el.classList.add('hidden'));
            if (!h || !h.data[idx]) return;
            if (typeof h.cb === 'function') h.cb(h.data[idx]);
        };

        document.addEventListener('click', function(e) { 
            const d1 = document.getElementById('dropdownCariManual'); const d2 = document.getElementById('dropdownCariMassal');
            if(d1 && !e.target.closest('#inputCariManual')) d1.classList.add('hidden'); 
            if(d2 && !e.target.closest('#inputCariMassal')) d2.classList.add('hidden'); 
        });

        // ==============================================
        // LOGIKA MESIN OTOMATIS (OSIS)
        // ==============================================
        async function prosesAbsenOtomatis(siswaData) {
            hentikanKamera();
            Swal.fire({ title: 'Mencatat Kehadiran...', didOpen: () => Swal.showLoading() });

            const now = new Date();
            const h = now.getHours(); const m = now.getMinutes();
            const timeStr = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')} WIB`;
            
            // Cek Keterlambatan (> 07:00)
            let isLate = false; let latePoint = 6; let lateDesc = "Terlambat hadir di sekolah";
            if(h > 7 || (h === 7 && m > 0)) { isLate = true; }

            try {
                // 1. Tembak data absen ke database
                await callBackend({ type: 'supabase_post', action: 'absenSiswa', payload: { id: siswaData.id, nama: siswaData.nama, jenis: 'Hadir', isLate: isLate, aktor: currentUser.nama_anggota } });
                
                // 2. Jika terlambat, tembak poin otomatis
                if(isLate) {
                    await callBackend({ type: 'supabase_post', action: 'tambahPoinQR', payload: { siswa_id: siswaData.id, poin_tambahan: latePoint, nisn: (siswaData.nisn||'-'), nama_pelanggaran: lateDesc, aktor: currentUser.nama_anggota } });
                }
                
                Swal.close();
                tampilkanScanResult(siswaData, isLate, timeStr);

            } catch(e) {
                Swal.fire('Sistem Offline', 'Database absen sedang disiapkan. Fungsi ini belum bisa menyimpan.', 'info').then(() => resumeKamera());
            }
        }

        // TAMPILKAN POPUP KESUKSESAN
        function tampilkanScanResult(siswa, isLate, timeStr) {
            document.getElementById('targetId').value = siswa.id;
            document.getElementById('targetNama').textContent = siswa.nama;
            document.getElementById('targetNisn').textContent = siswa.nisn || '-';
            document.getElementById('targetKelas').textContent = siswa.kelas;
            document.getElementById('scanResultTime').textContent = `Tercatat: ${timeStr}`;

            const card = document.getElementById('scanResultCard');
            const iconWrap = document.getElementById('scanResultIcon');
            const iconInner = document.getElementById('iconInnerResult');
            const title = document.getElementById('scanResultTitle');
            const accent = document.getElementById('scanResultAccent');
            const lateWarn = document.getElementById('lateWarning');
            
            // Reset state tambahan
            document.getElementById('extraPoinForm').classList.add('hidden');
            document.getElementById('jenisPelanggaran').value = "";
            document.getElementById('btnToggleExtraPoin').classList.remove('hidden');

            if(isLate) {
                card.className = "bg-white rounded-3xl w-full max-w-md shadow-2xl relative border-t-8 my-auto transition-colors duration-300 border-red-500";
                iconWrap.className = "w-20 h-20 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl shadow-inner border-4 border-white -mt-10 relative z-10 transition-colors duration-300";
                iconInner.className = "fas fa-clock";
                title.textContent = "Hadir Terlambat";
                accent.className = "absolute top-0 left-0 w-1.5 h-full bg-red-500 transition-colors duration-300";
                lateWarn.classList.remove('hidden');
            } else {
                card.className = "bg-white rounded-3xl w-full max-w-md shadow-2xl relative border-t-8 my-auto transition-colors duration-300 border-green-500";
                iconWrap.className = "w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl shadow-inner border-4 border-white -mt-10 relative z-10 transition-colors duration-300";
                iconInner.className = "fas fa-check";
                title.textContent = "Absen Berhasil";
                accent.className = "absolute top-0 left-0 w-1.5 h-full bg-green-500 transition-colors duration-300";
                lateWarn.classList.add('hidden');
            }

            document.getElementById('scanResultModal').classList.remove('hidden');
        }

        function tutupScanResult() {
            document.getElementById('scanResultModal').classList.add('hidden');
            resumeKamera(); // Lanjut Scan
        }

        // FITUR TAMBAHAN DI DALAM POPUP SUKSES
        function toggleExtraPoin() {
            document.getElementById('btnToggleExtraPoin').classList.add('hidden');
            document.getElementById('extraPoinForm').classList.remove('hidden');
        }

        async function prosesPoinTambahan() {
            const val = document.getElementById('jenisPelanggaran').value;
            if(!val) return Swal.fire('Pilih', 'Silakan pilih jenis pelanggaran', 'warning');
            
            const point = val.split('|')[0]; const desc = val.split('|')[1];
            const btn = document.getElementById('btnKirimPoin'); btn.innerHTML = '<i class="fas fa-spinner fa-spin">
            </i>'; btn.disabled = true;

            try {
                await callBackend({ type: 'supabase_post', action: 'tambahPoinQR', payload: { 
                    siswa_id: document.getElementById('targetId').value, 
                    poin_tambahan: point, nisn: document.getElementById('targetNisn').textContent,
                    nama_pelanggaran: desc, aktor: currentUser.nama_anggota 
                }});
                
                Swal.fire({ title: 'Poin Masuk!', text: `+${point} Poin tambahan ditambahkan`, icon: 'success', timer: 1500, showConfirmButton: false }).then(() => {
                    tutupScanResult(); // Otomatis tutup dan lanjut scan
                });
            } catch(e) { Swal.fire('Error', e.message, 'error'); } 
            finally { btn.innerHTML = 'Kirim Poin Tambahan'; btn.disabled = false; }
        }

        // ==============================================
        // MODAL ABSENSI MASSAL (SEKRETARIS & OSIS)
        // ==============================================
        function bukaModalAbsenMassal() {
            hentikanKamera(); arraySiswaMassal = []; renderDaftarMassal(); resetSuratMassal();
            document.getElementById('massalModal').classList.remove('hidden');
        }
        function tutupModalAbsenMassal() { 
            document.getElementById('massalModal').classList.add('hidden'); 
            if(currentUser.ui_role === 'osis') resumeKamera(); 
        }

        function tambahSiswaMassal(siswaObj) {
            if(!arraySiswaMassal.find(s => s.id === siswaObj.id)) {
                arraySiswaMassal.push({id: siswaObj.id, nama: siswaObj.nama, kelas: siswaObj.kelas});
                renderDaftarMassal();
            }
        }
        function hapusSiswaMassal(id) {
            arraySiswaMassal = arraySiswaMassal.filter(s => s.id !== id); renderDaftarMassal();
        }
        function renderDaftarMassal() {
            const cont = document.getElementById('daftarTerpilihMassal'); const count = document.getElementById('countMassal'); const place = document.getElementById('placeholderMassal');
            count.textContent = arraySiswaMassal.length;
            if(arraySiswaMassal.length === 0) { place.style.display = 'block'; cont.innerHTML = ''; cont.appendChild(place); return; }
            place.style.display = 'none'; cont.innerHTML = '';
            arraySiswaMassal.forEach(s => {
                cont.innerHTML += `<div class="bg-white border border-gray-300 shadow-sm px-3 py-1.5 rounded-lg flex items-center gap-2 fade-in">
            <span class="font-bold text-xs text-gray-700">${s.nama}</span>
            <span class="text-[9px] bg-gray-100 px-1 rounded text-gray-500">${s.kelas}</span>
            <button onclick="hapusSiswaMassal('${s.id}')" class="text-red-400 hover:text-red-600 ml-1">
                <i class="fas fa-times-circle">
                </i>
            </button>
        </div>`;
            });
        }

        // ── POPUP KONFIRMASI INPUT MANUAL (status + surat + poin) ──
        async function popupKonfirmasiManual(siswa) {
            hentikanKamera(); // jeda scanner selama popup terbuka
            try { await _popupKonfirmasiManual(siswa); }
            finally { if (currentUser && currentUser.ui_role === 'osis') resumeKamera(); }
        }

        async function _popupKonfirmasiManual(siswa) {
            let masterPelanggaran = [];
            try {
                const res = await callBackend({ type:'supabase_get', action:'getData', payload:{ table:'master_pelanggaran?select=id,kelakuan,point,kategori&order=kategori' }});
                masterPelanggaran = res.data || [];
            } catch(e) {}

            const opts = masterPelanggaran.map(p =>
                `<option value="${p.id}|${p.point}">[${p.kategori}] ${p.kelakuan} (+${p.point} poin)</option>`
            ).join('');

            let suratPop = null;

            const { value: formVals } = await Swal.fire({
                title: `<span class="text-base font-black">📋 ${siswa.nama}</span>`,
                html: `
                    <p class="text-xs text-gray-500 mb-4">${siswa.kelas} — Pencatatan Manual</p>
        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Status Kehadiran</p>
        <div class="grid grid-cols-4 gap-2 mb-4">
                        ${[['Hadir','fa-check-circle','blue'],['Sakit','fa-thermometer-half','yellow'],['Izin','fa-file-alt','indigo'],['Alpa','fa-times-circle','red']].map(([s,icon,c],i) => `
                        <label class="cursor-pointer">
        <input type="radio" name="jenisManualPop" value="${s}" class="peer sr-only" ${i===0?'checked':''}>
        <div class="text-center p-2.5 rounded-xl border-2 border-gray-200 font-bold text-gray-400 peer-checked:border-${c}-500 peer-checked:bg-${c}-50 peer-checked:text-${c}-700 transition text-[10px] leading-tight">
            <i class="fas ${icon} block mb-1 text-sm">
            </i>${s}
                            </div>
        </label>`).join('')}
                    </div>
        <div id="suratWrapPop" class="hidden mb-4 text-left">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Foto Surat <span class="text-red-500">* wajib</span>
        </p>
        <label for="suratFilePop" class="block cursor-pointer border-2 border-dashed border-gray-300 hover:border-blue-400 rounded-xl p-3 text-center transition">
            <img id="suratPrevPop" class="hidden mx-auto max-h-40 rounded-lg mb-2" alt="Preview surat">
            <span id="suratLabelPop" class="text-xs font-bold text-gray-500">
                <i class="fas fa-camera mr-1">
                </i>Ketuk untuk foto / pilih surat</span>
            </label>
            <input type="file" id="suratFilePop" accept="image/*" class="hidden">
        </div>
        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Poin Pelanggaran</p>
        <select id="poinManualPop" class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 bg-white">
            <option value="">— Tidak Ada Pelanggaran —</option>
                        ${opts}
                    </select>
            <p class="text-[10px] text-gray-400 mt-1.5">Opsional — kosongkan jika tidak ada pelanggaran</p>`,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-check mr-1">
        </i>Catat Sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb',
                customClass: { popup: 'text-left' },
                didOpen: () => {
                    const wrap = document.getElementById('suratWrapPop');
                    document.querySelectorAll('input[name="jenisManualPop"]').forEach(r => r.addEventListener('change', () => {
                        const v = document.querySelector('input[name="jenisManualPop"]:checked')?.value;
                        wrap.classList.toggle('hidden', !(v === 'Sakit' || v === 'Izin'));
                    }));
                    document.getElementById('suratFilePop').addEventListener('change', async (e) => {
                        const f = e.target.files && e.target.files[0]; if (!f) return;
                        try {
                            suratPop = await kompresFoto(f);
                            const prev = document.getElementById('suratPrevPop'); prev.src = suratPop; prev.classList.remove('hidden');
                            document.getElementById('suratLabelPop').innerHTML = '<i class="fas fa-sync-alt mr-1">
    </i>Ganti foto surat';
                        } catch (err) { suratPop = null; Swal.showValidationMessage(err.message); }
                    });
                },
                preConfirm: () => {
                    const jenis = document.querySelector('input[name="jenisManualPop"]:checked')?.value || 'Hadir';
                    if ((jenis === 'Sakit' || jenis === 'Izin') && !suratPop) {
                        Swal.showValidationMessage('Foto surat wajib diunggah untuk absen ' + jenis);
                        return false;
                    }
                    return { jenis, poin: document.getElementById('poinManualPop')?.value || '', surat: suratPop };
                }
            });

            if (!formVals) return; // Batal diklik

            Swal.fire({ title: 'Mencatat...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            const now = new Date();
            const isLate = formVals.jenis === 'Hadir' && (now.getHours() > 7 || (now.getHours() === 7 && now.getMinutes() > 0));
            const perluSurat = (formVals.jenis === 'Sakit' || formVals.jenis === 'Izin');

            try {
                let lampiran = '';
                if (perluSurat) lampiran = await uploadSurat(formVals.surat);

                await callBackend({ type:'supabase_post', action:'absenMassal', payload:{
                    ids: [siswa.id], jenis: formVals.jenis, isLate, lampiran, aktor: currentUser.nama_anggota
                }});

                if (formVals.poin) {
                    const [pelId, pelPoin] = formVals.poin.split('|');
                    if (pelId && pelPoin) {
                        await callBackend({ type:'supabase_post', action:'tambahPoinSiswa', payload:{
                            id_siswa: siswa.id, id_pelanggaran: pelId,
                            poin: parseInt(pelPoin), aktor: currentUser.nama_anggota
                        }});
                    }
                }

                const label = isLate ? 'Hadir (Terlambat)' : formVals.jenis;
                Swal.fire({
                    icon:'success', title:'Berhasil Dicatat!',
                    html:`<b>${siswa.nama}</b> dicatat sebagai <b>${label}</b>${perluSurat ? '<br>📎 Foto surat tersimpan' : ''}${formVals.poin ? '<br>+ poin pelanggaran ditambahkan' : ''}`,
                    confirmButtonColor:'#2563eb', timer:3000, timerProgressBar:true
                });
            } catch(e) {
                Swal.fire('Gagal', e.message, 'error');
            }
        }

        async function kirimAbsenMassal() {
            if(arraySiswaMassal.length === 0) return Swal.fire('Kosong', 'Pilih minimal 1 siswa.', 'warning');
            const jenis = document.querySelector('input[name="alasanAbsen"]:checked').value;
            const perluSurat = (jenis === 'Sakit' || jenis === 'Izin');
            if (perluSurat && !suratMassalDataUrl) return Swal.fire('Foto Surat Wajib', `Absen ${jenis} wajib melampirkan foto surat.`, 'warning');

            const btn = document.getElementById('btnKirimMassal'); btn.innerHTML = '<i class="fas fa-spinner fa-spin">
</i> Menyimpan...'; btn.disabled = true;
            const now = new Date();

            try {
                let lampiran = '';
                if (perluSurat) lampiran = await uploadSurat(suratMassalDataUrl);
                const isLate = jenis === 'Hadir' && (now.getHours() > 7 || (now.getHours() === 7 && now.getMinutes() > 0));
                await callBackend({ type: 'supabase_post', action: 'absenMassal', payload: {
                    ids: arraySiswaMassal.map(s => s.id), jenis, isLate, lampiran, aktor: currentUser.nama_anggota
                }});

                const jml = arraySiswaMassal.length;
                tutupModalAbsenMassal();
                Swal.fire({ title: 'Berhasil!', text: `${jml} siswa berhasil dicatat${perluSurat ? ' beserta foto surat.' : '.'}`, icon: 'success' });
            } catch(e) {
                Swal.fire('Gagal Menyimpan', e.message, 'error');
            } finally { btn.innerHTML = '<i class="fas fa-paper-plane">
</i> Kirim Data Absen'; btn.disabled = false; }
        }
    </script>
<script src="{{ asset('/grisVA.js') }}" defer="">
</script>


@endsection