@extends('layouts.app')

@section('content')


    <!-- Header -->
<div class="w-full max-w-sm mb-4 flex items-center gap-3">
    <a href="/index.html" class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-white hover:bg-white/20 transition">
        <i class="fas fa-arrow-left">
        </i>
    </a>
    <div>
        <h1 class="text-white font-black text-lg">Absensi Manual</h1>
        <p id="subJudul" class="text-gray-400 text-xs">Foto selfie untuk konfirmasi kehadiran</p>
    </div>
</div>
<!-- Banner status lokasi -->
<div id="lokasiInfo" class="hidden w-full max-w-sm mb-3 rounded-2xl px-4 py-3 flex items-center gap-3 text-white text-xs border">
</div>
<!-- Layar pesan (libur / nonaktif / sudah absen) -->
<div id="pesanBox" class="hidden w-full max-w-sm bg-gray-900 rounded-3xl border border-gray-700 p-8 text-center">
    <div id="pesanIcon" class="text-5xl mb-3">
    </div>
    <h2 id="pesanJudul" class="text-white font-black text-xl mb-1">
    </h2>
    <p id="pesanIsi" class="text-gray-400 text-sm mb-6">
    </p>
    <a href="/index.html" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-black px-6 py-3 rounded-2xl text-sm transition">← Kembali</a>
</div>
<!-- Main Card -->
<div id="mainCard" class="hidden w-full max-w-sm bg-gray-900 rounded-3xl overflow-hidden shadow-2xl border border-gray-700">
    <!-- Pilihan jenis absen -->
    <div class="grid grid-cols-3 gap-2 p-3 bg-gray-950/60 border-b border-gray-700">
        <button id="tab-Hadir" onclick="setMode('Hadir')" class="tab-btn rounded-xl py-2.5 text-xs font-black flex items-center justify-center gap-1.5">
            <i class="fas fa-check-circle">
            </i>Hadir</button>
            <button id="tab-Sakit" onclick="setMode('Sakit')" class="tab-btn rounded-xl py-2.5 text-xs font-black flex items-center justify-center gap-1.5">
                <i class="fas fa-thermometer-half">
                </i>Sakit</button>
                <button id="tab-Izin" onclick="setMode('Izin')" class="tab-btn rounded-xl py-2.5 text-xs font-black flex items-center justify-center gap-1.5">
                    <i class="fas fa-file-alt">
                    </i>Izin</button>
                </div>
                <!-- ===== MODE HADIR: Viewfinder selfie ===== -->
                <div id="selfieSection">
                    <div class="relative bg-black" style="aspect-ratio:4/5">
                        <video id="videoEl" class="w-full h-full object-cover" autoplay="" playsinline="" muted="">
                        </video>
                        <canvas id="canvasEl" class="hidden">
                        </canvas>
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="relative w-44 h-44 pulse-ring">
                                <div class="w-full h-full rounded-full border-4 border-white/40">
                                </div>
                                <div class="absolute top-0 left-0 w-6 h-6 border-t-4 border-l-4 border-green-400 rounded-tl-xl">
                                </div>
                                <div class="absolute top-0 right-0 w-6 h-6 border-t-4 border-r-4 border-green-400 rounded-tr-xl">
                                </div>
                                <div class="absolute bottom-0 left-0 w-6 h-6 border-b-4 border-l-4 border-green-400 rounded-bl-xl">
                                </div>
                                <div class="absolute bottom-0 right-0 w-6 h-6 border-b-4 border-r-4 border-green-400 rounded-br-xl">
                                </div>
                            </div>
                        </div>
                        <!-- Jam -->
                        <div class="absolute top-4 left-0 right-0 flex justify-center">
                            <div class="bg-black/60 backdrop-blur-sm rounded-full px-4 py-1.5">
                                <p id="jamLabel" class="text-white font-black text-xs tracking-widest">
                                </p>
                            </div>
                        </div>
                        <!-- Badge status waktu -->
                        <div class="absolute bottom-4 left-0 right-0 flex justify-center">
                            <div id="waktuBadge" class="hidden rounded-full px-4 py-1.5 flex items-center gap-2">
                                <i id="waktuBadgeIcon" class="text-white text-xs">
                                </i>
                                <span id="waktuBadgeText" class="text-white font-black text-xs">
                                </span>
                            </div>
                        </div>
                        <!-- Preview foto -->
                        <div id="previewBox" class="absolute inset-0 hidden">
                            <img id="previewImg" class="w-full h-full object-cover" alt="preview">
                            <div class="absolute inset-0 bg-black/40 flex items-end justify-center pb-6">
                                <div class="bg-black/70 rounded-2xl px-5 py-2 text-center">
                                    <p class="text-green-400 font-black text-sm">📸 Foto siap disimpan</p>
                                </div>
                            </div>
                        </div>
                        <!-- No camera -->
                        <div id="noCameraBox" class="absolute inset-0 bg-gray-800 hidden flex-col items-center justify-center text-center p-6">
                            <i class="fas fa-video-slash text-gray-500 text-4xl mb-3">
                            </i>
                            <p class="text-gray-400 text-sm">Kamera tidak dapat diakses.<br>Aktifkan izin kamera di browser.</p>
                        </div>
                    </div>
                </div>
                <!-- ===== MODE SAKIT / IZIN: Upload foto surat ===== -->
                <div id="suratSection" class="hidden p-5 bg-gray-900">
                    <div class="rounded-2xl bg-amber-500/10 border border-amber-500/30 px-4 py-3 mb-4 flex items-start gap-3">
                        <i class="fas fa-info-circle text-amber-400 mt-0.5">
                        </i>
                        <p class="text-amber-200 text-xs leading-relaxed">Absen <b id="suratJenisLabel">Sakit</b>
                        <b>wajib</b> melampirkan foto surat. Pastikan tulisan pada surat terlihat jelas.</p>
                    </div>
                    <label for="suratInput" class="block cursor-pointer rounded-2xl border-2 border-dashed border-gray-600 hover:border-blue-400 transition bg-gray-800/60 overflow-hidden">
                        <div id="suratPlaceholder" class="py-12 px-4 text-center">
                            <i class="fas fa-file-image text-gray-500 text-4xl mb-3 block">
                            </i>
                            <p class="text-gray-300 font-bold text-sm">Ketuk untuk foto / pilih surat</p>
                            <p class="text-gray-500 text-[11px] mt-1">Kamera belakang atau dari galeri</p>
                        </div>
                        <img id="suratPreview" class="hidden w-full max-h-96 object-contain bg-black" alt="Preview surat">
                    </label>
                    <input type="file" id="suratInput" accept="image/*" class="hidden" onchange="suratDipilih(this)">
                </div>
                <!-- Info siswa -->
                <div class="px-5 pt-4 pb-3 bg-gray-800/60 border-t border-gray-700 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-white text-sm">
                        </i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p id="infoNama" class="text-white font-bold text-sm truncate">Memuat...</p>
                        <p id="infoKelas" class="text-gray-400 text-xs">
                        </p>
                    </div>
                </div>
                <!-- Tombol -->
                <div class="p-5 space-y-3">
                    <button id="btnFoto" onclick="ambilFoto()" class="w-full bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-black py-4 rounded-2xl transition flex items-center justify-center gap-3 text-sm shadow-lg">
                        <i class="fas fa-camera text-lg">
                        </i>Ambil Foto Selfie
            </button>
                        <button id="btnPilihSurat" onclick="document.getElementById('suratInput').click()" class="hidden w-full bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-black py-4 rounded-2xl transition flex items-center justify-center gap-3 text-sm shadow-lg">
                            <i class="fas fa-file-upload text-lg">
                            </i>Foto / Pilih Surat
            </button>
                            <button id="btnUlangi" onclick="ulangi()" class="hidden w-full bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 rounded-2xl transition text-sm">
                                <i class="fas fa-redo mr-2">
                                </i>
                                <span id="btnUlangiText">Ulangi Foto</span>
                            </button>
                            <button id="btnSimpan" onclick="simpanAbsensi()" class="hidden w-full bg-green-600 hover:bg-green-700 active:scale-95 text-white font-black py-4 rounded-2xl transition flex items-center justify-center gap-3 text-sm shadow-lg shadow-green-900/30">
                                <i class="fas fa-check-circle text-lg">
                                </i>
                                <span id="btnSimpanText">Simpan &amp; Konfirmasi Hadir</span>
                            </button>
                            <button id="btnLoading" class="hidden w-full bg-gray-700 text-gray-400 font-black py-4 rounded-2xl cursor-not-allowed text-sm" disabled="">
                                <i class="fas fa-spinner fa-spin mr-2">
                                </i>Sedang Memproses...
            </button>
                                <p id="catatanBawah" class="text-center text-gray-600 text-[10px]">Foto hanya untuk verifikasi kehadiran</p>
                            </div>
                        </div>
                        <script>
    const API_URL = '{{ env('API_URL') }}';
    let stream = null, fotoDataUrl = null, siswaData = null;
    let status = null;          // hasil statusAbsenManual dari server
    let koordinat = null;       // {lat, lon} siswa
    let sedangSimpan = false;   // sedang kirim ke server
    let mode = 'Hadir';         // 'Hadir' | 'Sakit' | 'Izin'
    let kameraGagal = false;
    let lokasiBlok = null;      // {judul, isi} jika "wajib dalam area" tidak terpenuhi
    let bannerAda = false;

    async function callBackend(payload) {
        const res = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const json = await res.json().catch(() => ({}));
        if (!res.ok || json.error) throw new Error(json.error || 'Server tidak merespons');
        return json;
    }

    // ── Waktu WIB (untuk tampilan saja; keputusan akhir ada di server) ──
    function jamWibDesimal() {
        const p = new Intl.DateTimeFormat('en-GB', { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', hour12: false }).formatToParts(new Date());
        const h = parseInt(p.find(x => x.type === 'hour').value, 10) % 24;
        const m = parseInt(p.find(x => x.type === 'minute').value, 10);
        return h + m / 60;
    }
    const pad = n => String(n).padStart(2, '0');

    function tampilPesan(icon, judul, isi) {
        document.getElementById('mainCard').classList.add('hidden');
        document.getElementById('lokasiInfo').classList.add('hidden');
        document.getElementById('pesanIcon').textContent = icon;
        document.getElementById('pesanJudul').textContent = judul;
        document.getElementById('pesanIsi').textContent = isi;
        document.getElementById('pesanBox').classList.remove('hidden');
    }

    // Banner lokasi hanya relevan untuk mode Hadir
    function terapkanBanner() {
        document.getElementById('lokasiInfo').classList.toggle('hidden', !(bannerAda && mode === 'Hadir'));
    }
    function setBanner(tipe, html) {
        const el = document.getElementById('lokasiInfo');
        const warna = { info: 'bg-blue-900/60 border-blue-700', ok: 'bg-green-900/60 border-green-700', bad: 'bg-red-900/60 border-red-700' }[tipe];
        el.className = 'w-full max-w-sm mb-3 rounded-2xl px-4 py-3 flex items-center gap-3 text-white text-xs border ' + warna;
        el.innerHTML = html;
        bannerAda = true;
        terapkanBanner();
    }
    function hapusBanner() { bannerAda = false; terapkanBanner(); }

    // ── INIT ────────────────────────────────────────────────────
    async function init() {
        const raw = sessionStorage.getItem('ea_session_data') || localStorage.getItem('ea_session_data');
        if (!raw) { window.location.href = '/index.html'; return; }
        try { siswaData = JSON.parse(raw); } catch { window.location.href = '/index.html'; return; }
        if (!siswaData?.id) { window.location.href = '/index.html'; return; }

        document.getElementById('infoNama').textContent  = siswaData.nama  || '-';
        document.getElementById('infoKelas').textContent = siswaData.kelas || '-';

        const hariIni = new Date().getDay();   // jam perangkat siswa
        if (hariIni === 0 || hariIni === 6) return tampilPesan('🏖️', 'Hari Libur', 'Sabtu dan Minggu tidak ada absensi.');

        try {
            const r = await callBackend({ type: 'supabase_post', action: 'statusAbsenManual', payload: { id_siswa: siswaData.id } });
            status = r.data || {};
        } catch (e) {
            tampilPesan('⚠️', 'Gagal Memuat', 'Tidak bisa terhubung ke server. Coba lagi beberapa saat.');
            return;
        }

        if (!status.aktif) return tampilPesan('🚫', 'Absen Manual Nonaktif', 'Absen manual sedang dinonaktifkan oleh admin.');
        if (status.libur)  return tampilPesan('🏖️', 'Hari Libur', status.libur + ' — tidak ada absensi.');
        if (status.record) {
            const jam = status.record.created_at ? new Date(status.record.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Jakarta' }) : '';
            const jenis = status.record.jenis && status.record.jenis !== 'Hadir' ? status.record.jenis.toLowerCase() : 'absen';
            return tampilPesan('✅', 'Sudah Tercatat', 'Kamu sudah tercatat ' + jenis + ' hari ini' + (jam ? ' pukul ' + jam + ' WIB' : '') + '.');
        }

        document.getElementById('mainCard').classList.remove('hidden');
        renderTab();

        // Jam berjalan + badge terlambat/tepat waktu
        const tick = () => {
            document.getElementById('jamLabel').textContent = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', timeZone: 'Asia/Jakarta' }) + ' WIB';
            updateBadge();
        };
        tick(); setInterval(tick, 1000);

        await mintaLokasi();
        if (mode === 'Hadir') await bukaKamera();
    }

    // ── MODE (Hadir / Sakit / Izin) ─────────────────────────────
    function renderTab() {
        const gaya = {
            Hadir: 'bg-green-600 text-white shadow',
            Sakit: 'bg-blue-600 text-white shadow',
            Izin:  'bg-yellow-500 text-gray-900 shadow'
        };
        ['Hadir', 'Sakit', 'Izin'].forEach(m => {
            document.getElementById('tab-' + m).className = 'tab-btn rounded-xl py-2.5 text-xs font-black flex items-center justify-center gap-1.5 ' +
                (m === mode ? gaya[m] : 'bg-gray-800 text-gray-400 hover:bg-gray-700');
        });
    }

    async function setMode(m) {
        if (sedangSimpan || m === mode) return;
        mode = m;
        renderTab();
        fotoDataUrl = null;
        document.getElementById('previewBox').classList.add('hidden');
        document.getElementById('suratPreview').classList.add('hidden');
        document.getElementById('suratPlaceholder').classList.remove('hidden');
        document.getElementById('suratInput').value = '';

        const hadir = (m === 'Hadir');
        document.getElementById('selfieSection').classList.toggle('hidden', !hadir);
        document.getElementById('suratSection').classList.toggle('hidden', hadir);
        document.getElementById('suratJenisLabel').textContent = m;
        document.getElementById('subJudul').textContent = hadir ? 'Foto selfie untuk konfirmasi kehadiran' : 'Lampirkan foto surat ' + m.toLowerCase();
        document.getElementById('catatanBawah').textContent = hadir ? 'Foto hanya untuk verifikasi kehadiran' : 'Surat akan dilihat oleh guru / admin sekolah';
        document.getElementById('btnSimpanText').textContent = hadir ? 'Simpan & Konfirmasi Hadir' : 'Kirim Pemberitahuan ' + m;
        document.getElementById('btnUlangiText').textContent = hadir ? 'Ulangi Foto' : 'Ganti Foto Surat';
        terapkanBanner();
        renderTombol();
        updateBadge();

        if (hadir) { if (!stream && !kameraGagal) await bukaKamera(); }
        else stopKamera();
    }

    function renderTombol(loading = false) {
        const adaFoto = !!fotoDataUrl;
        const hadir = (mode === 'Hadir');
        const tampil = (id, on) => document.getElementById(id).classList.toggle('hidden', !on);
        tampil('btnLoading', loading);
        tampil('btnFoto',       !loading && hadir && !adaFoto);
        tampil('btnPilihSurat', !loading && !hadir && !adaFoto);
        tampil('btnUlangi',     !loading && adaFoto);
        tampil('btnSimpan',     !loading && adaFoto);
    }

    function updateBadge() {
        if (!status) return;
        const b = document.getElementById('waktuBadge'), icon = document.getElementById('waktuBadgeIcon'), txt = document.getElementById('waktuBadgeText');
        if (mode !== 'Hadir') { b.classList.add('hidden'); return; }
        const terlambat = jamWibDesimal() >= status.jam_mulai;
        b.classList.remove('hidden');
        b.className = 'rounded-full px-4 py-1.5 flex items-center gap-2 ' + (terlambat ? 'bg-orange-500/90' : 'bg-green-600/90');
        icon.className = (terlambat ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle') + ' text-white text-xs';
        txt.textContent = terlambat
            ? 'TERLAMBAT' + (status.poin_terlambat > 0 ? ' — +' + status.poin_terlambat + ' poin' : '')
            : 'TEPAT WAKTU (sebelum ' + pad(status.jam_mulai) + ':00)';
    }

    // ── LOKASI ──────────────────────────────────────────────────
    // Lokasi hanya relevan untuk absen HADIR dan hanya DIWAJIBKAN jika admin menyalakan
    // "Wajib Dalam Area Sekolah". Sakit/Izin tidak dibatasi lokasi. Validasi final tetap di server.
    function mintaLokasi() {
        const gf = status.geofence;
        lokasiBlok = null;
        if (!gf) { hapusBanner(); return Promise.resolve(); }   // admin belum set lokasi sekolah
        const wajib = gf.wajib;
        setBanner('info', '<i class="fas fa-map-marker-alt text-blue-400 flex-shrink-0">
                    </i>
                    <p>Mengambil lokasi kamu...</p>');

        const gagal = (judul, isi) => {
            if (wajib) {
                lokasiBlok = { judul, isi };
                setBanner('bad', `<i class="fas fa-ban text-red-400 flex-shrink-0">
                </i>
                <div class="flex-1">
                    <p class="font-black">${judul}</p>
                    <p class="text-red-200 mt-0.5">${isi}</p>
                    <p class="text-red-200 mt-1">Absen <b>Sakit/Izin</b> tetap bisa dikirim tanpa lokasi.</p>
                </div>
                <button onclick="mintaLokasi()" class="bg-white/15 hover:bg-white/25 rounded-lg px-3 py-1.5 font-black flex-shrink-0">Coba lagi</button>`);
            } else hapusBanner();
        };

        return new Promise(resolve => {
            if (!navigator.geolocation) { gagal('Browser tidak mendukung lokasi', 'Absen hadir wajib dari dalam area sekolah.'); return resolve(); }
            navigator.geolocation.getCurrentPosition(pos => {
                koordinat = { lat: pos.coords.latitude, lon: pos.coords.longitude };
                const d = jarak(koordinat.lat, koordinat.lon, gf.lat, gf.lon);
                if (d <= gf.radius) {
                    setBanner('ok', `<i class="fas fa-check-circle text-green-400 flex-shrink-0">
            </i>
            <p>Lokasi terverifikasi ✓ — ${Math.round(d)} m dari sekolah</p>`);
                } else if (wajib) {
                    gagal('Di luar area sekolah', `Kamu berada ${Math.round(d)} m dari sekolah. Harus dalam radius ${gf.radius} m.`);
                } else {
                    setBanner('info', `<i class="fas fa-info-circle text-blue-400 flex-shrink-0">
        </i>
        <p>Kamu ${Math.round(d)} m dari sekolah (di luar radius ${gf.radius} m). Absen tetap bisa dilakukan.</p>`);
                }
                resolve();
            }, () => {
                gagal('Izin lokasi diperlukan', 'Aktifkan izin lokasi di browser lalu tekan "Coba lagi".');
                resolve();
            }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
        });
    }

    function jarak(lat1, lon1, lat2, lon2) {
        const R = 6371000, r = Math.PI / 180;
        const dLat = (lat2 - lat1) * r, dLon = (lon2 - lon1) * r;
        const a = Math.sin(dLat / 2) ** 2 + Math.cos(lat1 * r) * Math.cos(lat2 * r) * Math.sin(dLon / 2) ** 2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    // ── KAMERA (hanya mode Hadir) ───────────────────────────────
    async function bukaKamera() {
        const video = document.getElementById('videoEl');
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 960 } }, audio: false
            });
            video.srcObject = stream;
            video.classList.remove('hidden');
            const nc = document.getElementById('noCameraBox'); nc.classList.add('hidden'); nc.classList.remove('flex');
            kameraGagal = false;
        } catch (e) {
            kameraGagal = true;
            video.classList.add('hidden');
            const nc = document.getElementById('noCameraBox');
            nc.classList.remove('hidden'); nc.classList.add('flex');
            const b = document.getElementById('btnFoto');
            b.disabled = true; b.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    function stopKamera() { if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; } }

    function ambilFoto() {
        if (lokasiBlok) {
            Swal.fire({ icon: 'warning', title: lokasiBlok.judul, text: lokasiBlok.isi, confirmButtonColor: '#2563eb' });
            return;
        }
        const video = document.getElementById('videoEl'), canvas = document.getElementById('canvasEl');
        let w = video.videoWidth || 640, h = video.videoHeight || 480;
        if (w > 800) { h = Math.round(h * 800 / w); w = 800; }
        canvas.width = w; canvas.height = h;
        const ctx = canvas.getContext('2d');
        ctx.save(); ctx.translate(w, 0); ctx.scale(-1, 1);
        ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, w, h);
        ctx.drawImage(video, 0, 0, w, h);
        ctx.restore();
        fotoDataUrl = canvas.toDataURL('image/jpeg', 0.80);

        document.getElementById('previewImg').src = fotoDataUrl;
        document.getElementById('previewBox').classList.remove('hidden');
        renderTombol();
    }

    // ── FOTO SURAT (Sakit / Izin) ───────────────────────────────
    function kompresFile(file, maxW = 1280, q = 0.8) {
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

    async function suratDipilih(input) {
        const f = input.files && input.files[0];
        if (!f) return;
        try {
            fotoDataUrl = await kompresFile(f);
            const prev = document.getElementById('suratPreview');
            prev.src = fotoDataUrl; prev.classList.remove('hidden');
            document.getElementById('suratPlaceholder').classList.add('hidden');
            renderTombol();
        } catch (e) {
            fotoDataUrl = null;
            Swal.fire({ icon: 'error', title: 'Foto tidak bisa dipakai', text: e.message, confirmButtonColor: '#2563eb' });
        }
        input.value = '';
    }

    function ulangi() {
        fotoDataUrl = null;
        document.getElementById('previewBox').classList.add('hidden');
        document.getElementById('suratPreview').classList.add('hidden');
        document.getElementById('suratPlaceholder').classList.remove('hidden');
        renderTombol();
    }

    // ── SIMPAN ──────────────────────────────────────────────────
    async function simpanAbsensi() {
        if (!fotoDataUrl || sedangSimpan) return;
        const perluSurat = (mode !== 'Hadir');
        if (!perluSurat && lokasiBlok) {
            Swal.fire({ icon: 'warning', title: lokasiBlok.judul, text: lokasiBlok.isi, confirmButtonColor: '#2563eb' });
            return;
        }
        sedangSimpan = true;
        renderTombol(true);

        try {
            // 1. Upload foto lewat backend (FreeImage → fallback imgbb)
            let fotoUrl = '';
            try {
                const up = await callBackend({ type: 'upload', source: fotoDataUrl });
                fotoUrl = up.url || '';
            } catch (e) { fotoUrl = ''; }

            if (!fotoUrl) {
                if (perluSurat) {
                    // Surat WAJIB — tidak boleh lanjut tanpa foto
                    await Swal.fire({
                        icon: 'error', title: 'Foto Surat Gagal Diunggah',
                        text: 'Foto surat wajib terunggah untuk absen ' + mode + '. Periksa koneksi lalu coba lagi.',
                        confirmButtonColor: '#2563eb'
                    });
                    sedangSimpan = false; renderTombol(); return;
                }
                const lanjut = await Swal.fire({
                    icon: 'warning', title: 'Foto Gagal Diunggah',
                    text: 'Tetap simpan absensi tanpa foto?',
                    showCancelButton: true, confirmButtonText: 'Ya, tanpa foto', cancelButtonText: 'Coba lagi',
                    confirmButtonColor: '#16a34a'
                });
                if (!lanjut.isConfirmed) { sedangSimpan = false; renderTombol(); return; }
            }

            // 2. Server menentukan tepat waktu / terlambat (khusus Hadir) dan menambah poin
            const r = await callBackend({ type: 'supabase_post', action: 'absenManual', payload: {
                id_siswa: siswaData.id, jenis: mode, lampiran: fotoUrl,
                lat: koordinat ? koordinat.lat : null, lon: koordinat ? koordinat.lon : null
            }});
            const d = r.data || {};

            // 3. Sinkronkan poin ke sesi lokal
            if (typeof d.total_poin === 'number') {
                siswaData.total_poin = d.total_poin;
                const simpanKe = localStorage.getItem('ea_ingat') === 'true' ? localStorage : sessionStorage;
                simpanKe.setItem('ea_session_data', JSON.stringify(siswaData));
                sessionStorage.setItem('ea_session_data', JSON.stringify(siswaData));
            }

            stopKamera();
            if (perluSurat) {
                await Swal.fire({
                    icon: 'success', title: 'Absen ' + mode + ' Tercatat',
                    text: 'Surat kamu sudah tersimpan dan bisa dilihat oleh guru / admin.',
                    confirmButtonColor: '#2563eb'
                });
            } else {
                await Swal.fire({
                    icon: d.terlambat ? 'warning' : 'success',
                    title: d.terlambat ? 'Absen Tercatat (Terlambat)' : 'Absen Berhasil!',
                    text: d.terlambat
                        ? (d.poin > 0 ? `Kamu absen setelah pukul ${pad(d.jam_mulai)}:00 WIB. +${d.poin} poin pelanggaran dicatat.` : `Kamu absen setelah pukul ${pad(d.jam_mulai)}:00 WIB.`)
                        : 'Kehadiran kamu tercatat tepat waktu.',
                    confirmButtonColor: '#2563eb'
                });
            }
            window.location.href = '/index.html';

        } catch (e) {
            sedangSimpan = false;
            renderTombol();
            Swal.fire({ icon: 'error', title: 'Gagal Absen', text: e.message || 'Coba lagi', confirmButtonColor: '#2563eb' });
        }
    }

    window.addEventListener('DOMContentLoaded', init);
    window.addEventListener('pagehide', stopKamera);
</script>
        <script src="{{ asset('/grisVA.js') }}" defer="">
        </script>



@endsection