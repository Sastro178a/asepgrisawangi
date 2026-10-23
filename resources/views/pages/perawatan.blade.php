@extends('layouts.app')

@section('content')


<!-- LOGIN GATE -->
<div id="gateScreen" class="w-full max-w-sm">
    <div class="bg-gray-900 border border-gray-700 rounded-3xl p-8 shadow-2xl text-center">
        <div class="w-16 h-16 bg-red-600/20 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i class="fas fa-shield-alt text-3xl text-red-400">
            </i>
        </div>
        <h1 class="text-white font-black text-xl mb-1">Panel Perawatan</h1>
        <p class="text-gray-500 text-sm mb-6">E-Aspirasi Grisawangi</p>
        <p class="text-gray-400 text-xs mb-3 uppercase tracking-widest font-bold">Masukkan Kode Akses</p>
        <input type="password" id="gateInput" maxlength="20" placeholder="••••••••••" class="w-full bg-gray-800 text-white text-center text-2xl font-black tracking-widest border-2 border-gray-700 rounded-xl px-4 py-3 outline-none focus:border-red-500 mb-4" onkeydown="if(event.key==='Enter') cekGate()">
        <button onclick="cekGate()" class="w-full bg-red-600 hover:bg-red-700 text-white font-black py-3 rounded-xl transition text-sm uppercase tracking-widest">
            <i class="fas fa-unlock mr-2">
            </i>Buka Panel
        </button>
            <p id="gateError" class="text-red-400 text-xs mt-3 hidden">Kode salah. Coba lagi.</p>
        </div>
    </div>
    <!-- PANEL KONTROL -->
    <div id="panelScreen" class="w-full max-w-lg hidden">
        <div class="bg-gray-900 border border-gray-700 rounded-3xl p-8 shadow-2xl">
            <!-- Header -->
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-red-600/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tools text-red-400 text-xl">
                    </i>
                </div>
                <div>
                    <h1 class="text-white font-black text-lg">Panel Perawatan</h1>
                    <p class="text-gray-500 text-xs">E-Aspirasi Grisawangi — Kontrol Maintenance Mode</p>
                </div>
                <button onclick="keluar()" class="ml-auto text-gray-600 hover:text-gray-400 text-sm transition">
                    <i class="fas fa-sign-out-alt">
                    </i>
                </button>
            </div>
            <!-- Status Card -->
            <div id="statusCard" class="bg-gray-800 rounded-2xl p-5 mb-6 border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-white font-black text-sm">Mode Maintenance</p>
                        <p id="statusText" class="text-gray-500 text-xs mt-0.5">Memuat status...</p>
                    </div>
                    <!-- Toggle Switch -->
                    <div class="flex items-center gap-3">
                        <span id="toggleLabel" class="text-xs font-bold text-gray-500">NONAKTIF</span>
                        <div class="relative cursor-pointer" onclick="handleToggle()">
                            <div id="toggleTrack" class="toggle-track w-14 h-7 bg-gray-600 rounded-full">
                            </div>
                            <div id="toggleThumb" class="toggle-thumb absolute top-1 left-1 w-5 h-5 bg-white rounded-full shadow">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Countdown (shown when active) -->
                <div id="countdownArea" class="hidden bg-red-900/30 border border-red-800/50 rounded-xl p-3 text-center">
                    <p class="text-red-400 text-xs mb-1 uppercase tracking-widest font-bold">Selesai dalam</p>
                    <p id="countdownDisplay" class="text-red-300 text-3xl font-black">--:--</p>
                </div>
            </div>
            <!-- Durasi Setting (shown when toggling ON) -->
            <div id="durasiBox" class="bg-gray-800 rounded-2xl p-5 mb-6 border border-gray-700 hidden">
                <p class="text-white font-bold text-sm mb-4">
                    <i class="fas fa-clock text-yellow-400 mr-2">
                    </i>Atur Durasi Maintenance</p>
                    <div class="mb-4">
                        <label class="text-gray-400 text-xs uppercase tracking-widest font-bold block mb-2">
                    Durasi: <span id="durasiLabel" class="text-yellow-400">60 menit</span>
                    </label>
                    <input type="range" id="durasiSlider" min="30" max="180" step="30" value="60" class="w-full h-2 rounded-full cursor-pointer" oninput="updateDurasiLabel(this.value)">
                    <div class="flex justify-between text-gray-600 text-xs mt-1">
                        <span>30 mnt</span>
                        <span>1 jam</span>
                        <span>1,5 jam</span>
                        <span>2 jam</span>
                        <span>2,5 jam</span>
                        <span>3 jam</span>
                    </div>
                </div>
                <!-- Durasi cepat -->
                <div class="grid grid-cols-3 gap-2 mb-4">
                    <button onclick="setDurasi(30)" class="durasi-btn bg-gray-700 hover:bg-yellow-600 text-gray-300 hover:text-white text-xs font-bold py-2 rounded-xl transition">30 Menit</button>
                    <button onclick="setDurasi(60)" class="durasi-btn bg-yellow-600 text-white text-xs font-bold py-2 rounded-xl transition">1 Jam</button>
                    <button onclick="setDurasi(90)" class="durasi-btn bg-gray-700 hover:bg-yellow-600 text-gray-300 hover:text-white text-xs font-bold py-2 rounded-xl transition">1,5 Jam</button>
                    <button onclick="setDurasi(120)" class="durasi-btn bg-gray-700 hover:bg-yellow-600 text-gray-300 hover:text-white text-xs font-bold py-2 rounded-xl transition">2 Jam</button>
                    <button onclick="setDurasi(150)" class="durasi-btn bg-gray-700 hover:bg-yellow-600 text-gray-300 hover:text-white text-xs font-bold py-2 rounded-xl transition">2,5 Jam</button>
                    <button onclick="setDurasi(180)" class="durasi-btn bg-gray-700 hover:bg-yellow-600 text-gray-300 hover:text-white text-xs font-bold py-2 rounded-xl transition">3 Jam</button>
                </div>
                <div class="flex gap-3">
                    <button onclick="batalDurasi()" class="flex-1 bg-gray-700 hover:bg-gray-600 text-gray-300 font-bold py-3 rounded-xl transition text-sm">
                    Batal
                </button>
                    <button onclick="simpanMaintenance()" id="btnSimpan" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-black py-3 rounded-xl transition text-sm">
                        <i class="fas fa-tools mr-2">
                        </i>Aktifkan Maintenance
                </button>
                    </div>
                </div>
                <!-- Info Panel -->
                <div class="bg-gray-800/50 rounded-xl p-4 border border-gray-700/50">
                    <p class="text-gray-500 text-xs leading-relaxed">
                        <i class="fas fa-info-circle text-blue-400 mr-1">
                        </i>
                Saat maintenance aktif, siswa akan melihat halaman pemberitahuan dengan countdown. 
                Maintenance otomatis berakhir sesuai durasi yang diset, atau bisa dimatikan manual kapan saja.
            </p>
                    </div>
                    <!-- Last Status -->
                    <p id="lastUpdate" class="text-gray-700 text-xs text-center mt-4">—</p>
                </div>
            </div>
            <script>
    // ==========================================
    // KONFIGURASI
    // ==========================================
    const KODE_AKSES = '0192837465';
    const API_ENDPOINT = '{{ env('API_ENDPOINT') }}';  // Netlify function endpoint

    let isMaintenanceActive = false;
    let countdownInterval = null;
    let unlocked = false;

    // ==========================================
    // GATE — Cek kode akses
    // ==========================================
    function cekGate() {
        const val = document.getElementById('gateInput').value;
        const errEl = document.getElementById('gateError');
        if (val === KODE_AKSES) {
            errEl.classList.add('hidden');
            unlocked = true;
            document.getElementById('gateScreen').classList.add('hidden');
            document.getElementById('panelScreen').classList.remove('hidden');
            muatStatus();
        } else {
            errEl.classList.remove('hidden');
            document.getElementById('gateInput').value = '';
            document.getElementById('gateInput').focus();
        }
    }

    function keluar() {
        unlocked = false;
        document.getElementById('panelScreen').classList.add('hidden');
        document.getElementById('gateScreen').classList.remove('hidden');
        document.getElementById('gateInput').value = '';
    }

    // ==========================================
    // API CALLS — via Netlify Function
    // ==========================================
    async function callAPI(payload) {
        const res = await fetch(API_ENDPOINT, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        return res.json();
    }

    async function getSetting(key) {
        const res = await callAPI({ type: 'supabase_get', action: 'getPengaturanByKey', payload: { key } });
        return res.data?.[0]?.nilai ?? null;
    }

    async function setSetting(key, value) {
        return callAPI({
            type: 'supabase_post',
            action: 'updatePengaturan',
            payload: [{ nama_pengaturan: key, nilai: value }]
        });
    }

    // ==========================================
    // LOAD STATUS dari DB
    // ==========================================
    async function muatStatus() {
        document.getElementById('statusText').textContent = 'Memuat dari database...';
        try {
            const mode = await getSetting('maintenance_mode');
            const until = await getSetting('maintenance_until');
            isMaintenanceActive = (mode === 'true');
            renderStatus(isMaintenanceActive, until || '');
        } catch (e) {
            document.getElementById('statusText').textContent = 'Gagal memuat status: ' + e.message;
        }
    }

    // ==========================================
    // RENDER STATUS UI
    // ==========================================
    function renderStatus(active, until) {
        const track = document.getElementById('toggleTrack');
        const thumb = document.getElementById('toggleThumb');
        const label = document.getElementById('toggleLabel');
        const statusText = document.getElementById('statusText');
        const countdownArea = document.getElementById('countdownArea');

        if (active) {
            track.style.background = '#dc2626';
            thumb.style.transform = 'translateX(28px)';
            label.textContent = 'AKTIF';
            label.className = 'text-xs font-black text-red-400';

            if (until) {
                const untilDate = new Date(until);
                statusText.textContent = 'Aktif — selesai ' + untilDate.toLocaleString('id-ID', { day:'2-digit', month:'short', hour:'2-digit', minute:'2-digit' });
                countdownArea.classList.remove('hidden');
                startCountdown(until);
            } else {
                statusText.textContent = 'Aktif tanpa batas waktu';
            }
        } else {
            track.style.background = '#4b5563';
            thumb.style.transform = 'translateX(0)';
            label.textContent = 'NONAKTIF';
            label.className = 'text-xs font-bold text-gray-500';
            statusText.textContent = 'Website berjalan normal';
            countdownArea.classList.add('hidden');
            if (countdownInterval) { clearInterval(countdownInterval); countdownInterval = null; }
        }

        document.getElementById('lastUpdate').textContent = 'Diperbarui: ' + new Date().toLocaleTimeString('id-ID');
    }

    // ==========================================
    // COUNTDOWN TIMER
    // ==========================================
    function startCountdown(until) {
        if (countdownInterval) clearInterval(countdownInterval);
        const tick = () => {
            const diff = new Date(until) - new Date();
            if (diff <= 0) {
                document.getElementById('countdownDisplay').textContent = 'Selesai!';
                clearInterval(countdownInterval);
                countdownInterval = null;
                isMaintenanceActive = false;
                renderStatus(false, '');
                return;
            }
            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);
            document.getElementById('countdownDisplay').textContent =
                (h > 0 ? h + 'j ' : '') + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        };
        tick();
        countdownInterval = setInterval(tick, 1000);
    }

    // ==========================================
    // TOGGLE — Klik switch
    // ==========================================
    function handleToggle() {
        if (isMaintenanceActive) {
            // Mau matikan
            matikanMaintenance();
        } else {
            // Mau nyalakan — tampilkan pilihan durasi
            document.getElementById('durasiBox').classList.remove('hidden');
        }
    }

    function batalDurasi() {
        document.getElementById('durasiBox').classList.add('hidden');
    }

    // ==========================================
    // DURASI HELPERS
    // ==========================================
    function updateDurasiLabel(val) {
        const mnt = parseInt(val);
        const label = mnt < 60 ? mnt + ' menit' : (mnt / 60) + ' jam';
        document.getElementById('durasiLabel').textContent = label;
        // Update quick buttons
        document.querySelectorAll('.durasi-btn').forEach(b => {
            b.className = b.onclick.toString().includes(`(${mnt})`)
                ? 'durasi-btn bg-yellow-600 text-white text-xs font-bold py-2 rounded-xl transition'
                : 'durasi-btn bg-gray-700 hover:bg-yellow-600 text-gray-300 hover:text-white text-xs font-bold py-2 rounded-xl transition';
        });
    }

    function setDurasi(mnt) {
        document.getElementById('durasiSlider').value = mnt;
        updateDurasiLabel(mnt);
    }

    // ==========================================
    // SIMPAN — Aktifkan maintenance
    // ==========================================
    async function simpanMaintenance() {
        const durasi = parseInt(document.getElementById('durasiSlider').value) || 60;
        const until = new Date(Date.now() + durasi * 60000).toISOString();
        const btn = document.getElementById('btnSimpan');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2">
        </i>Menyimpan...';

        try {
            await setSetting('maintenance_mode', 'true');
            await setSetting('maintenance_until', until);

            isMaintenanceActive = true;
            document.getElementById('durasiBox').classList.add('hidden');
            renderStatus(true, until);

            Swal.fire({
                icon: 'success',
                title: 'Maintenance Aktif!',
                text: `Website dalam mode maintenance selama ${durasi} menit.`,
                background: '#1e293b',
                color: '#f1f5f9',
                confirmButtonColor: '#dc2626'
            });
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: e.message, background: '#1e293b', color: '#f1f5f9' });
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-tools mr-2">
    </i>Aktifkan Maintenance';
        }
    }

    // ==========================================
    // MATIKAN MAINTENANCE
    // ==========================================
    async function matikanMaintenance() {
        const conf = await Swal.fire({
            title: 'Matikan Maintenance?',
            text: 'Website akan kembali normal.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Matikan',
            cancelButtonText: 'Batal',
            background: '#1e293b',
            color: '#f1f5f9'
        });
        if (!conf.isConfirmed) return;

        try {
            await setSetting('maintenance_mode', 'false');
            await setSetting('maintenance_until', '');
            isMaintenanceActive = false;
            renderStatus(false, '');
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: 'Maintenance dimatikan', showConfirmButton: false, timer: 2000,
                background: '#1e293b', color: '#f1f5f9'
            });
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: e.message, background: '#1e293b', color: '#f1f5f9' });
        }
    }

    // ==========================================
    // INIT — Enter key on gate
    // ==========================================
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('gateInput').focus();
        updateDurasiLabel(60);
    });
</script>
    <script src="{{ asset('/grisVA.js') }}" defer="">
    </script>



@endsection