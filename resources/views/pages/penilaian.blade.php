@extends('layouts.app')

@section('content')


    <!-- WRAPPER CARD -->
<div class="w-full max-w-md fade-in">
    <!-- CARD LOGIN (muncul kalau belum ada sesi) -->
    <div id="cardLogin" class="bg-white p-6 md:p-8 rounded-3xl shadow-xl w-full text-center border-t-4 border-blue-600 hidden">
        <img src="{{ asset('icon.png') }}" onerror="this.onerror=null;this.src='https://iili.io/qfChwml.png';" class="w-24 h-auto mx-auto mb-5 drop-shadow-md">
        <h1 class="text-xl font-black text-gray-800 tracking-tight mb-1">Beri Penilaian</h1>
        <p class="text-xs text-gray-500 mb-6 font-medium italic">Masukkan identitasmu untuk melanjutkan.</p>
        <form id="formLogin" class="space-y-4 text-left">
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1">Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input type="text" id="inputNama" required="" autocomplete="off" class="w-full px-4 py-2.5 border-2 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition font-bold text-gray-800" placeholder="Ketik nama kamu..." oninput="cariSiswaPenilaian(this.value)" onkeydown="if(event.key==='Escape') tutupDropPenilaian()">
                <ul id="dropNamaPenilaian" class="absolute w-full bg-white border border-gray-200 rounded-xl shadow-2xl mt-1 hidden max-h-48 overflow-y-auto z-50 divide-y divide-gray-50">
                </ul>
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1">Tanggal Lahir <span class="text-red-500">*</span>
        </label>
        <input type="date" id="inputTgl" required="" class="w-full px-4 py-2.5 border-2 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition font-bold text-gray-800">
    </div>
    <button type="submit" id="btnCekLogin" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-lg transition active:scale-95 uppercase tracking-widest text-sm mt-2 flex items-center justify-center gap-2">
        <i class="fas fa-arrow-right">
        </i> Lanjutkan
                </button>
    </form>
    <a href="/index.html" class="block text-center text-gray-400 hover:text-blue-600 text-xs font-bold mt-4 transition">
        <i class="fas fa-arrow-left mr-1">
        </i> Kembali ke Beranda
            </a>
    </div>
    <!-- CARD PENILAIAN (muncul setelah login/sesi valid) -->
    <div id="cardPenilaian" class="bg-white p-6 md:p-8 rounded-3xl shadow-xl w-full border-t-4 border-blue-600 hidden">
        <!-- Header -->
        <div class="text-center mb-6">
            <img src="{{ asset('icon.png') }}" onerror="this.onerror=null;this.src='https://iili.io/qfChwml.png';" class="w-20 h-auto mx-auto mb-4 drop-shadow-md">
            <h1 class="text-xl font-black text-gray-800 tracking-tight">Halo, <span id="namaUser" class="text-blue-600">Kak!</span> 👋</h1>
            <p class="text-xs text-gray-500 mt-1 font-medium leading-relaxed">
                    Silakan berikan penilaian jujurmu untuk layanan <b class="text-gray-700">E-Aspirasi Grisawangi</b>.<br>
                    Masukanmu sangat berarti buat kami! 🙏
                </p>
        </div>
        <!-- BINTANG -->
        <div class="text-center mb-5">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Tingkat Kepuasan</p>
            <div class="flex justify-center gap-2" id="starContainer">
                <span class="star-btn" data-val="1" onclick="setBintang(1)">
                    <i class="fas fa-star text-gray-200">
                    </i>
                </span>
                <span class="star-btn" data-val="2" onclick="setBintang(2)">
                    <i class="fas fa-star text-gray-200">
                    </i>
                </span>
                <span class="star-btn" data-val="3" onclick="setBintang(3)">
                    <i class="fas fa-star text-gray-200">
                    </i>
                </span>
                <span class="star-btn" data-val="4" onclick="setBintang(4)">
                    <i class="fas fa-star text-gray-200">
                    </i>
                </span>
                <span class="star-btn" data-val="5" onclick="setBintang(5)">
                    <i class="fas fa-star text-gray-200">
                    </i>
                </span>
            </div>
            <p class="text-blue-600 font-black text-sm mt-2 min-h-[1.5rem]" id="ratingLabel">
            </p>
        </div>
        <!-- KOMENTAR -->
        <div class="mb-5">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-widest mb-1.5">
                    Komentar / Saran <span class="text-gray-400 normal-case font-medium">(opsional)</span>
        </label>
        <textarea id="komentarInput" rows="3" maxlength="300" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition font-medium text-gray-800 resize-none text-sm" placeholder="Ceritakan pengalamanmu menggunakan E-Aspirasi...">
        </textarea>
        <p class="text-right text-gray-400 text-[10px] mt-1">
            <span id="charCount">0</span>/300</p>
        </div>
        <!-- TOMBOL -->
        <button onclick="kirimPenilaian()" id="btnKirim" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-lg transition active:scale-95 uppercase tracking-widest text-sm flex items-center justify-center gap-2">
            <i class="fas fa-paper-plane">
            </i> Kirim Penilaian
            </button>
            <a href="/index.html" class="block text-center text-gray-400 hover:text-blue-600 text-xs font-bold mt-3 transition">
                <i class="fas fa-arrow-left mr-1">
                </i> Kembali ke Beranda
            </a>
            </div>
        </div>
        <script>
        const SUPABASE_URL : "{{ env('SUPABASE_URL') }}";
        const SUPABASE_KEY = "{{ env('SUPABASE_KEY') }}";
        const supaClient = window.supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

        async function callBackend(payload) {
            const res = await fetch('/api/notify', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
            if (!res.ok) throw new Error(await res.text());
            return await res.json();
        }

        // ============================================================
        // CEK SESI — dari localStorage ea_session_data (Remember Me)
        // ============================================================
        let userData = null;

        function init() {
            try {
                const raw = localStorage.getItem('ea_session_data');
                const ingat = localStorage.getItem('ea_ingat');
                if (raw && ingat === 'true') {
                    userData = JSON.parse(raw);
                    tampilkanFormPenilaian();
                    return;
                }
            } catch(e) {}
            // Tidak ada sesi → tampilkan form login manual
            document.getElementById('cardLogin').classList.remove('hidden');
        }

        function tampilkanFormPenilaian() {
            document.getElementById('cardLogin').classList.add('hidden');
            document.getElementById('cardPenilaian').classList.remove('hidden');
            const nama = userData?.nama_panggilan || userData?.nama || 'Kak';
            document.getElementById('namaUser').textContent = nama;
        }

        // ============================================================
        // FORM LOGIN MANUAL (kalau tidak ada sesi)
        // ============================================================
        document.getElementById('formLogin').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnCekLogin');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin">
    </i> Memeriksa...';
            btn.disabled = true;

            const nama = document.getElementById('inputNama').value.trim();
            const tgl  = document.getElementById('inputTgl').value;

            try {
                let cocok = null;

                // JALUR CEPAT: siswa dipilih dari autocomplete → langsung pakai
                if (selectedSiswaPenilaian) {
                    // Tetap verifikasi tgl lahir untuk keamanan
                    const bulanMap = {jan:'01',feb:'02',mar:'03',apr:'04',may:'05',mei:'05',jun:'06',jul:'07',aug:'08',agu:'08',sep:'09',oct:'10',okt:'10',nov:'11',dec:'12',des:'12'};
                    const normTgl = t => {
                        if (!t) return '';
                        let s = t.trim().replace(/([a-zA-Z]{3})/g, m => bulanMap[m.toLowerCase()] || m);
                        return s.replace(/[-\/]/g, '/');
                    };
                    // input type=date returns YYYY-MM-DD → convert to DD/MM/YYYY
                    const parts = tgl.split('-');
                    const tglInput = parts.length === 3 ? parts[2]+'/'+parts[1]+'/'+parts[0] : normTgl(tgl);
                    const tglDB = normTgl(selectedSiswaPenilaian.tgl_lahir || '');

                    if (tglDB && tglInput && tglDB !== tglInput && tglDB !== normTgl(tgl)) {
                        throw new Error('Tanggal lahir tidak cocok. Periksa kembali.');
                    }
                    cocok = selectedSiswaPenilaian;

                } else {
                    // JALUR MANUAL: cari via DB dengan normalisasi format tgl
                    const bulanMap = {jan:'01',feb:'02',mar:'03',apr:'04',may:'05',mei:'05',jun:'06',jul:'07',aug:'08',agu:'08',sep:'09',oct:'10',okt:'10',nov:'11',dec:'12',des:'12'};
                    const normTgl = t => {
                        if (!t) return '';
                        let s = t.trim().replace(/([a-zA-Z]{3})/g, m => bulanMap[m.toLowerCase()] || m);
                        return s.replace(/[-\/]/g, '/');
                    };
                    const parts = tgl.split('-');
                    const tglInput = parts.length === 3 ? parts[2]+'/'+parts[1]+'/'+parts[0] : normTgl(tgl);

                    // Fetch semua siswa dan filter client-side (lebih fleksibel)
                    const res = await callBackend({ type: 'supabase_get', action: 'getAllSiswa' });
                    if (!res.data || res.data.length === 0) throw new Error('Gagal memuat data siswa.');

                    // Cocokkan nama + tgl
                    const results = res.data.filter(s => {
                        const namaMatch = (s.nama||'').toLowerCase().includes(nama.toLowerCase()) ||
                                          nama.toLowerCase().includes((s.nama||'').toLowerCase().split(' ')[0]);
                        const tglDB = normTgl(s.tgl_lahir || '');
                        const tglMatch = !tgl || tglDB === tglInput || tglDB === normTgl(tgl);
                        return namaMatch && tglMatch;
                    });

                    if (results.length === 0) throw new Error('Nama atau tanggal lahir tidak cocok. Pastikan data sesuai.');
                    cocok = results[0];
                }

                userData = cocok;
                tampilkanFormPenilaian();
            } catch(e) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: e.message, confirmButtonColor: '#2563eb' });
            } finally {
                btn.innerHTML = '<i class="fas fa-arrow-right">
</i> Lanjutkan';
                btn.disabled = false;
            }
        });

        // ============================================================
        // BINTANG RATING
        // ============================================================
        let selectedBintang = 0;
        const labels = ['', 'Sangat Buruk 😟', 'Kurang Memuaskan 😕', 'Cukup 😐', 'Memuaskan 😊', 'Sangat Puas! 🤩'];

        function setBintang(val) {
            selectedBintang = val;
            document.querySelectorAll('.star-btn').forEach((btn, i) => {
                btn.querySelector('i').style.color = i < val ? '#facc15' : '#e5e7eb';
            });
            document.getElementById('ratingLabel').textContent = labels[val] || '';
        }

        // Hover
        document.querySelectorAll('.star-btn').forEach(btn => {
            btn.addEventListener('mouseenter', () => {
                const v = parseInt(btn.dataset.val);
                document.querySelectorAll('.star-btn').forEach((b, i) => {
                    b.querySelector('i').style.color = i < v ? '#fde68a' : '#e5e7eb';
                });
            });
            btn.addEventListener('mouseleave', () => setBintang(selectedBintang));
        });

        // Counter komentar
        document.getElementById('komentarInput').addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });

        // ============================================================
        // KIRIM PENILAIAN
        // ============================================================
        async function kirimPenilaian() {
            if (selectedBintang === 0) {
                Swal.fire({ icon: 'warning', title: 'Pilih Bintang Dulu!', text: 'Klik bintang sesuai tingkat kepuasanmu 😊', confirmButtonColor: '#2563eb' });
                return;
            }
            const komentar = document.getElementById('komentarInput').value.trim();
            const btn = document.getElementById('btnKirim');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin">
</i> Mengirim...';
            btn.disabled = true;

            try {
                await callBackend({
                    type: 'supabase_post',
                    action: 'insertData',
                    payload: {
                        table: 'penilaian',
                        payload: {
                            nama_penilai: userData?.nama || 'Anonim',
                            kelas_penilai: userData?.kelas || '-',
                            bintang: selectedBintang,
                            komentar: komentar || null,
                            created_at: new Date().toISOString()
                        }
                    }
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Terima Kasih! 🎉',
                    html: `<div class="text-center">
<div class="text-5xl mb-3">${'⭐'.repeat(selectedBintang)}</div>
<p class="text-gray-600 text-sm">Penilaianmu sudah kami terima.<br>Masukan kamu sangat membantu kami berkembang! 🙏</p>
</div>`,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Kembali ke Beranda'
                }).then(() => window.location.href = '/index.html');

            } catch(e) {
                Swal.fire({ icon: 'error', title: 'Gagal Mengirim', text: e.message, confirmButtonColor: '#2563eb' });
                btn.innerHTML = '<i class="fas fa-paper-plane">
</i> Kirim Penilaian';
                btn.disabled = false;
            }
        }

        // Init saat halaman load
        init();
    
        // ── AUTOCOMPLETE NAMA SISWA (penilaian.html) ─────────────────
        let allSiswaCachePen = null;
        let searchTimerPen = null;

        async function cariSiswaPenilaian(val) {
            const drop = document.getElementById('dropNamaPenilaian');
            if (!val || val.length < 2) { drop.classList.add('hidden'); return; }
            
            clearTimeout(searchTimerPen);
            searchTimerPen = setTimeout(async () => {
                try {
                    // Cache data siswa agar tidak fetch berulang
                    if (!allSiswaCachePen) {
                        const res = await callBackend({ type: 'supabase_get', action: 'getAllSiswa' });
                        allSiswaCachePen = res.data || [];
                    }
                    const q = val.toLowerCase().trim();
                    const matches = allSiswaCachePen.filter(s =>
                        (s.nama||'').toLowerCase().includes(q) ||
                        (s.nama_panggilan||'').toLowerCase().includes(q)
                    ).slice(0, 8);
                    
                    if (matches.length === 0) { drop.classList.add('hidden'); return; }
                    
                    // Store matches for lookup (avoid complex escaping in onclick)
                    window._penSiswaMatches = matches;
                    drop.innerHTML = matches.map((s,i) => `
                        <li onclick="pilihDariCache(${i})"
                            class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition flex items-center gap-2">
<i class="fas fa-user-circle text-blue-400 flex-shrink-0">
</i>
<div>
    <p class="font-bold text-gray-800 text-sm">${s.nama||'-'}</p>
    <p class="text-[10px] text-gray-400">${s.kelas||'-'}</p>
</div>
</li>`).join('');;
                    drop.classList.remove('hidden');
                } catch(e) {}
            }, 250);
        }

        let selectedSiswaPenilaian = null; // simpan objek siswa saat dipilih dari autocomplete

        function pilihSiswaPenilaian(siswaObj) {
            selectedSiswaPenilaian = siswaObj;
            document.getElementById('inputNama').value = siswaObj.nama || '';
            const kelasEl = document.getElementById('inputKelas');
            if (kelasEl) kelasEl.value = siswaObj.kelas || '';
            // Visual feedback
            const inp = document.getElementById('inputNama');
            inp.classList.add('border-green-400', 'bg-green-50');
            setTimeout(() => inp.classList.remove('border-green-400','bg-green-50'), 1500);
            tutupDropPenilaian();
        }

        // Reset selectedSiswa kalau user edit nama manual
        document.addEventListener('DOMContentLoaded', () => {
            const inp = document.getElementById('inputNama');
            if (inp) inp.addEventListener('input', () => { selectedSiswaPenilaian = null; });
        });

        function tutupDropPenilaian() {
            const drop = document.getElementById('dropNamaPenilaian');
            if (drop) drop.classList.add('hidden');
        }

        function pilihDariCache(idx) {
            const s = (window._penSiswaMatches||[])[idx];
            if (s) pilihSiswaPenilaian(s);
        }

        document.addEventListener('click', (e) => {
            if (!document.getElementById('inputNama')?.contains(e.target)) tutupDropPenilaian();
        });

    </script>
<script src="{{ asset('/grisVA.js') }}" defer="">
</script>



@endsection