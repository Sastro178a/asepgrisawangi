@extends('layouts.app')

@section('content')


<div class="w-full max-w-md text-center">
<!-- Logo -->
<div class="float-anim mb-8">
    <div class="w-28 h-28 mx-auto glass rounded-3xl flex items-center justify-center shadow-2xl">
        <img src="{{ asset('/icon.png') }}" alt="Logo E-Aspirasi" class="w-20 h-20 object-contain" onerror="this.onerror=null; this.parentElement.innerHTML='&lt;i class=\'fas fa-school text-5xl text-white\'&gt;&lt;/i&gt;'">
    </div>
</div>
<!-- Title -->
<h1 class="text-white font-black text-3xl mb-2">Website Sedang Diperbaiki</h1>
<p class="text-blue-200 font-bold text-sm uppercase tracking-widest mb-8">E-Aspirasi Grisawangi — SMK PGRI 1 Giri</p>
<!-- Status Card -->
<div class="glass rounded-3xl p-6 mb-6 text-left shadow-2xl">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-3 h-3 bg-yellow-400 rounded-full pulse-slow">
        </div>
        <p class="text-white font-bold text-sm">Status: Maintenance Aktif</p>
    </div>
    <p class="text-blue-100 text-sm leading-relaxed mb-4" id="maintenanceMsg">
            Website sedang dalam proses perawatan dan peningkatan sistem. 
            Kami memohon maaf atas ketidaknyamanan ini.
        </p>
    <!-- Countdown -->
    <div id="countdownBox" class="bg-blue-900/50 rounded-2xl p-4 text-center border border-blue-400/30">
        <p class="text-blue-300 text-xs uppercase tracking-widest font-bold mb-2">Perkiraan Selesai Dalam</p>
        <p class="text-yellow-400 text-4xl font-black tracking-wider" id="countdownDisplay">--:--</p>
        <p class="text-blue-300 text-xs mt-2" id="estimasiWaktu">Menghitung...</p>
    </div>
    <p id="noCountdownMsg" class="text-blue-300 text-sm text-center mt-4 hidden">
        <i class="fas fa-clock mr-1">
        </i> Silakan coba kembali beberapa saat lagi
        </p>
    </div>
    <!-- Hubungi Developer -->
    <div id="devContactBox" class="glass rounded-2xl p-4 mb-6 hidden">
        <p class="text-white font-bold text-sm mb-3 text-center">
            <i class="fas fa-headset text-blue-300 mr-2">
            </i>Butuh Bantuan Segera?
        </p>
            <a id="waDevLink" href="#" target="_blank" class="flex items-center justify-center gap-3 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-5 rounded-xl transition shadow-lg w-full">
                <i class="fab fa-whatsapp text-xl">
                </i>
                <span>Hubungi Developer via WhatsApp</span>
            </a>
        </div>
        <!-- Refresh -->
        <button onclick="window.location.reload()" class="text-blue-200 hover:text-white text-sm font-bold transition flex items-center gap-2 mx-auto">
            <i class="fas fa-redo-alt">
            </i> Periksa Ulang Status
    </button>
        </div>
        <script>
    const API = '{{ env('API') }}';

    async function callBackend(payload) {
        const res = await fetch(API, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        return res.json();
    }

    async function getSetting(key) {
        try {
            const res = await callBackend({ type: 'supabase_get', action: 'getPengaturanByKey', payload: { key } });
            return res.data?.[0]?.nilai ?? null;
        } catch { return null; }
    }

    let countdownInterval = null;

    function startCountdown(untilISO) {
        const el = document.getElementById('countdownDisplay');
        const estEl = document.getElementById('estimasiWaktu');

        const tick = () => {
            const diff = new Date(untilISO) - new Date();
            if (diff <= 0) {
                el.textContent = 'Selesai!';
                if (estEl) estEl.textContent = 'Mencoba mengalihkan...';
                clearInterval(countdownInterval);
                // Auto-check if maintenance is off after a moment
                setTimeout(() => checkAndRedirect(), 3000);
                return;
            }
            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);
            el.textContent = (h > 0 ? String(h).padStart(2,'0') + ':' : '') +
                             String(m).padStart(2,'0') + ':' +
                             String(s).padStart(2,'0');
            if (estEl) {
                const untilDate = new Date(untilISO);
                estEl.textContent = 'Selesai sekitar pukul ' + untilDate.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });
            }
        };
        tick();
        countdownInterval = setInterval(tick, 1000);
    }

    async function checkAndRedirect() {
        const mode = await getSetting('maintenance_mode');
        if (mode !== 'true') {
            window.location.href = '/';
        }
    }

    async function init() {
        const mode = await getSetting('maintenance_mode');
        // If maintenance is off, redirect to index
        if (mode !== 'true') {
            window.location.href = '/';
            return;
        }

        const until = await getSetting('maintenance_until');
        if (until) {
            document.getElementById('countdownBox').classList.remove('hidden');
            document.getElementById('noCountdownMsg').classList.add('hidden');
            startCountdown(until);
        } else {
            document.getElementById('countdownBox').classList.add('hidden');
            document.getElementById('noCountdownMsg').classList.remove('hidden');
        }

        // Load developer WA contact
        const waAdmin = await getSetting('wa_dev');
        if (waAdmin) {
            const noWa = waAdmin.replace(/\D/g, '');
            const link = document.getElementById('waDevLink');
            if (link) {
                link.href = `https://wa.me/${noWa}?text=Halo%2C%20saya%20ingin%20bertanya%20mengenai%20maintenance%20E-Aspirasi.`;
            }
            document.getElementById('devContactBox').classList.remove('hidden');
        }
    }

    // Auto re-check every 2 minutes
    init();
    setInterval(checkAndRedirect, 120000);
</script>



@endsection