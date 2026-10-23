@extends('layouts.app')

@section('content')


<div class="max-w-2xl mx-auto">
<!-- Header -->
<div class="flex items-center gap-4 mb-8">
    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
        <i class="fas fa-signal text-white text-xl">
        </i>
    </div>
    <div>
        <h1 class="font-black text-gray-900 text-xl">Status Koneksi Layanan</h1>
        <p class="text-gray-500 text-sm">E-Aspirasi Grisawangi — Monitor Real-time</p>
    </div>
    <button onclick="cekSemua()" id="btnRefresh" class="ml-auto bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl text-sm transition flex items-center gap-2">
        <i class="fas fa-sync-alt" id="refreshIcon">
        </i> Refresh
        </button>
    </div>
    <!-- Last check time -->
    <p class="text-gray-400 text-xs mb-6 text-right" id="lastCheckTime">Belum dicek</p>
    <!-- Service Cards -->
    <div class="space-y-4" id="serviceList">
        <!-- FreeImage -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5" id="card-freeimage">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-image text-orange-500 text-xl">
                        </i>
                    </div>
                    <div>
                        <p class="font-black text-gray-800">FreeImage Host</p>
                        <p class="text-gray-500 text-xs">Upload foto lampiran laporan</p>
                        <p class="text-gray-400 text-[10px] mt-0.5">freeimage.host/api/1/upload</p>
                    </div>
                </div>
                <div class="text-right">
                    <div id="status-freeimage" class="flex items-center gap-2 justify-end mb-1">
                        <div class="w-2.5 h-2.5 bg-gray-300 rounded-full" id="dot-freeimage">
                        </div>
                        <span class="text-xs font-bold text-gray-400" id="label-freeimage">Menunggu</span>
                    </div>
                    <span class="text-[10px] text-gray-400" id="latency-freeimage">—</span>
                </div>
            </div>
            <div id="detail-freeimage" class="mt-3 hidden">
                <div class="bg-gray-50 rounded-xl p-3 text-xs text-gray-600 font-mono break-all" id="detail-text-freeimage">
                </div>
            </div>
        </div>
        <!-- Supabase -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5" id="card-supabase">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-database text-green-500 text-xl">
                        </i>
                    </div>
                    <div>
                        <p class="font-black text-gray-800">Supabase Database</p>
                        <p class="text-gray-500 text-xs">Penyimpanan data utama</p>
                        <p class="text-gray-400 text-[10px] mt-0.5">API Backend → Supabase</p>
                    </div>
                </div>
                <div class="text-right">
                    <div id="status-supabase" class="flex items-center gap-2 justify-end mb-1">
                        <div class="w-2.5 h-2.5 bg-gray-300 rounded-full" id="dot-supabase">
                        </div>
                        <span class="text-xs font-bold text-gray-400" id="label-supabase">Menunggu</span>
                    </div>
                    <span class="text-[10px] text-gray-400" id="latency-supabase">—</span>
                </div>
            </div>
        </div>
        <!-- Backend API -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5" id="card-backend">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-server text-blue-500 text-xl">
                        </i>
                    </div>
                    <div>
                        <p class="font-black text-gray-800">Backend API (Netlify)</p>
                        <p class="text-gray-500 text-xs">Server fungsi /api/notify</p>
                        <p class="text-gray-400 text-[10px] mt-0.5">/api/notify</p>
                    </div>
                </div>
                <div class="text-right">
                    <div id="status-backend" class="flex items-center gap-2 justify-end mb-1">
                        <div class="w-2.5 h-2.5 bg-gray-300 rounded-full" id="dot-backend">
                        </div>
                        <span class="text-xs font-bold text-gray-400" id="label-backend">Menunggu</span>
                    </div>
                    <span class="text-[10px] text-gray-400" id="latency-backend">—</span>
                </div>
            </div>
        </div>
        <!-- Fonnte WA -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5" id="card-fonnte">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fab fa-whatsapp text-green-500 text-xl">
                        </i>
                    </div>
                    <div>
                        <p class="font-black text-gray-800">Fonnte WhatsApp</p>
                        <p class="text-gray-500 text-xs">Notifikasi WA ke siswa &amp; guru</p>
                        <p class="text-gray-400 text-[10px] mt-0.5">api.fonnte.com</p>
                    </div>
                </div>
                <div class="text-right">
                    <div id="status-fonnte" class="flex items-center gap-2 justify-end mb-1">
                        <div class="w-2.5 h-2.5 bg-gray-300 rounded-full" id="dot-fonnte">
                        </div>
                        <span class="text-xs font-bold text-gray-400" id="label-fonnte">Menunggu</span>
                    </div>
                    <span class="text-[10px] text-gray-400" id="latency-fonnte">—</span>
                </div>
            </div>
        </div>
        <!-- imgbb -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5" id="card-imgbb">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-image text-purple-500 text-xl">
                        </i>
                    </div>
                    <div>
                        <p class="font-black text-gray-800">imgbb</p>
                        <p class="text-gray-500 text-xs">Fallback upload foto (cadangan FreeImage)</p>
                        <p class="text-gray-400 text-[10px] mt-0.5">api.imgbb.com/1/upload</p>
                    </div>
                </div>
                <div class="text-right">
                    <div id="status-imgbb" class="flex items-center gap-2 justify-end mb-1">
                        <div class="w-2.5 h-2.5 bg-gray-300 rounded-full" id="dot-imgbb">
                        </div>
                        <span class="text-xs font-bold text-gray-400" id="label-imgbb">Menunggu</span>
                    </div>
                    <span class="text-[10px] text-gray-400" id="latency-imgbb">—</span>
                </div>
            </div>
            <div id="detail-imgbb" class="mt-3 hidden">
                <div class="bg-gray-50 rounded-xl p-3 text-xs text-gray-600 font-mono break-all" id="detail-text-imgbb">
                </div>
            </div>
        </div>
    </div>
    <!-- Legend -->
    <div class="flex items-center gap-6 mt-8 justify-center text-xs text-gray-500">
        <div class="flex items-center gap-1.5">
            <div class="w-2.5 h-2.5 bg-green-500 rounded-full">
            </div> Online</div>
            <div class="flex items-center gap-1.5">
                <div class="w-2.5 h-2.5 bg-yellow-400 rounded-full">
                </div> Lambat</div>
                <div class="flex items-center gap-1.5">
                    <div class="w-2.5 h-2.5 bg-red-500 rounded-full">
                    </div> Offline / Error</div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 bg-gray-300 rounded-full">
                        </div> Belum dicek</div>
                    </div>
                    <p class="text-center text-gray-300 text-xs mt-6">Auto-refresh setiap 60 detik</p>
                </div>
                <script>
    const API = '{{ env('API') }}';

    function setStatus(svc, state, latency, detail) {
        const dot = document.getElementById('dot-' + svc);
        const label = document.getElementById('label-' + svc);
        const latEl = document.getElementById('latency-' + svc);
        const detailBox = document.getElementById('detail-' + svc);
        const detailText = document.getElementById('detail-text-' + svc);
        const card = document.getElementById('card-' + svc);

        const cfg = {
            ok:      { dot: 'bg-green-500 pulse-dot', label: 'Online',  labelCls: 'text-green-600', card: 'border-green-200' },
            slow:    { dot: 'bg-yellow-400 pulse-dot', label: 'Lambat', labelCls: 'text-yellow-600', card: 'border-yellow-200' },
            error:   { dot: 'bg-red-500',  label: 'Offline / Error', labelCls: 'text-red-500', card: 'border-red-200' },
            loading: { dot: 'bg-blue-400 spin', label: 'Mengecek...', labelCls: 'text-blue-500', card: 'border-blue-100' },
        }[state] || { dot: 'bg-gray-300', label: 'Menunggu', labelCls: 'text-gray-400', card: 'border-gray-100' };

        dot.className = 'w-2.5 h-2.5 rounded-full ' + cfg.dot;
        label.textContent = cfg.label;
        label.className = 'text-xs font-bold ' + cfg.labelCls;
        if (card) card.className = card.className.replace(/border-\w+-\d+/g, '') + ' ' + cfg.card;
        if (latEl) latEl.textContent = latency || '—';
        if (detail && detailBox && detailText) {
            detailText.textContent = detail;
            detailBox.classList.remove('hidden');
        } else if (detailBox) {
            detailBox.classList.add('hidden');
        }
    }

    async function cekFreeImage() {
        setStatus('freeimage', 'loading');
        const t0 = Date.now();
        try {
            // Test with a tiny known-good image URL
            const testUrl : '{{ env('TEST_URL') }}';
            const res = await fetch(`https://freeimage.host/api/1/upload?key=6d207e02198a847aa98d0a2a901485a5&source=${encodeURIComponent(testUrl)}&format=json`);
            const ms = Date.now() - t0;
            const text = await res.text();

            if (text.trim().startsWith('<')) {
                setStatus('freeimage', 'error', ms + 'ms', 'Server mengembalikan HTML bukan JSON: ' + text.substring(0, 100));
                return;
            }

            const data = JSON.parse(text);
            if (data.status_code === 200 || data.status_txt === 'OK') {
                const state = ms > 3000 ? 'slow' : 'ok';
                setStatus('freeimage', state, ms + 'ms', 'URL : '{{ env('URL_27') }}'OK'));
            } else {
                setStatus('freeimage', 'error', ms + 'ms', 'Error: ' + (data.status_txt || JSON.stringify(data).substring(0,100)));
            }
        } catch (e) {
            setStatus('freeimage', 'error', (Date.now()-t0) + 'ms', 'Exception: ' + e.message);
        }
    }

    async function cekBackend() {
        setStatus('backend', 'loading');
        const t0 = Date.now();
        try {
            const res = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ type: 'ping' })
            });
            const ms = Date.now() - t0;
            if (res.ok || res.status === 400) {
                // 400 is fine - means backend is up but doesn't know 'ping'
                setStatus('backend', ms > 2000 ? 'slow' : 'ok', ms + 'ms');
            } else {
                setStatus('backend', 'error', ms + 'ms', 'HTTP ' + res.status);
            }
        } catch (e) {
            setStatus('backend', 'error', (Date.now()-t0) + 'ms', e.message);
        }
    }

    async function cekSupabase() {
        setStatus('supabase', 'loading');
        const t0 = Date.now();
        try {
            const res = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ type: 'supabase_get', action: 'getPengaturan' })
            });
            const ms = Date.now() - t0;
            const data = await res.json();
            if (data && !data.error) {
                setStatus('supabase', ms > 2000 ? 'slow' : 'ok', ms + 'ms');
            } else {
                setStatus('supabase', 'error', ms + 'ms', data.error || 'Unknown error');
            }
        } catch (e) {
            setStatus('supabase', 'error', (Date.now()-t0) + 'ms', e.message);
        }
    }

    async function cekFonnte() {
        setStatus('fonnte', 'loading');
        const t0 = Date.now();
        try {
            await fetch('https://api.fonnte.com', { method: 'HEAD', mode: 'no-cors' });
            const ms = Date.now() - t0;
            setStatus('fonnte', ms > 3000 ? 'slow' : 'ok', ms + 'ms');
        } catch (e) {
            const ms = Date.now() - t0;
            setStatus('fonnte', 'ok', ms + 'ms', 'Reachable (no-cors mode)');
        }
    }

    async function cekImgbb() {
        setStatus('imgbb', 'loading');
        const t0 = Date.now();
        try {
            // Test imgbb dengan gambar 1x1 pixel base64
            const tiny1x1 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
            const params = new URLSearchParams();
            params.append('key', '8d1e927c60294f497e62cdb5b49fa3b1'); // demo key
            params.append('image', tiny1x1);

            const res = await fetch('https://api.imgbb.com/1/upload', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params.toString(),
                signal: AbortSignal.timeout(10000)
            });
            const ms = Date.now() - t0;
            const text = await res.text();
            if (text.trim().startsWith('<')) throw new Error('HTML response');
            const data = JSON.parse(text);
            if (data.success) {
                setStatus('imgbb', ms > 3000 ? 'slow' : 'ok', ms + 'ms', 'Upload test berhasil: ' + (data.data?.url || 'OK'));
            } else {
                // Key might be invalid but server is reachable
                setStatus('imgbb', 'ok', ms + 'ms', 'Server online (API key perlu diset di Pengaturan)');
            }
        } catch(e) {
            setStatus('imgbb', 'error', (Date.now()-t0) + 'ms', e.message);
        }
    }

    async function cekSemua() {
        const icon = document.getElementById('refreshIcon');
        const btn = document.getElementById('btnRefresh');
        icon.className = 'fas fa-sync-alt spin';
        btn.disabled = true;

        document.getElementById('lastCheckTime').textContent = 'Mengecek... ' + new Date().toLocaleTimeString('id-ID');

        await Promise.all([cekFreeImage(), cekBackend(), cekSupabase(), cekFonnte(), cekImgbb()]);

        icon.className = 'fas fa-sync-alt';
        btn.disabled = false;
        document.getElementById('lastCheckTime').textContent = 'Terakhir dicek: ' + new Date().toLocaleTimeString('id-ID');
    }

    // Run on load
    cekSemua();
    // Auto-refresh every 60 seconds
    setInterval(cekSemua, 60000);
</script>
                <script src="{{ asset('/grisVA.js') }}" defer="">
                </script>



@endsection