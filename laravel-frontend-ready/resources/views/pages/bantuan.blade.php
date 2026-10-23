@extends('layouts.app')

@section('content')


    <!-- HEADER -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-700 pt-safe">
    <div class="max-w-md mx-auto px-5 pt-8 pb-10 text-center relative">
        <!-- back button -->
        <a href="/index.html" class="absolute left-4 top-8 w-9 h-9 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition">
            <i class="fas fa-arrow-left text-white text-sm">
            </i>
        </a>
        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
            <img src="{{ asset('icon.png') }}" class="w-11 h-11 object-contain" onerror="this.onerror=null; this.src='https://iili.io/qfChwml.png';" alt="Logo">
        </div>
        <h1 class="text-white font-black text-xl">Pusat Bantuan</h1>
        <p class="text-blue-200 text-xs mt-1">E-Aspirasi Grisawangi</p>
    </div>
</div>
<!-- CONTENT -->
<div class="max-w-md mx-auto px-5 -mt-5 pb-10 space-y-4 fade-up">
    <!-- TOMBOL 1 — KIRIM BANTUAN -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <button onclick="toggleBantuan()" id="btnToggleBantuan" class="card-btn w-full flex items-center gap-4 px-5 py-5 text-left">
            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-headset text-blue-600 text-lg">
                </i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-black text-gray-800">Ajukan Bantuan</p>
                <p class="text-xs text-gray-500 mt-0.5">Kirim pertanyaan ke tim E-Aspirasi via WhatsApp</p>
            </div>
            <i id="iconToggle" class="fas fa-chevron-down text-gray-400 text-sm transition-transform">
            </i>
        </button>
        <!-- FORM BANTUAN (collapsible) -->
        <div id="formBantuan" class="hidden border-t border-gray-100">
            <div class="px-5 py-5 space-y-4">
                <!-- Tipe Masalah -->
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">
                            Tipe Masalah <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select id="tipeMasalah" class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl text-sm font-bold text-gray-800 outline-none focus:border-blue-500 transition appearance-none bg-white">
                        <option value="">— Pilih tipe masalah —</option>
                        <option value="Masalah dengan web / aplikasi">🌐 Masalah dengan web / aplikasi</option>
                        <option value="Masalah scan QR absensi">📷 Masalah scan QR absensi</option>
                        <option value="Masalah data siswa di website">👤 Masalah data siswa di website</option>
                        <option value="Lainnya">❓ Lainnya</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 text-xs pointer-events-none">
                    </i>
                </div>
            </div>
            <!-- Nama -->
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">
                            Nama <span class="text-red-500">*</span>
            </label>
            <input type="text" id="bantuanNama" placeholder="Nama lengkap kamu" class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl text-sm font-bold text-gray-800 outline-none focus:border-blue-500 transition">
        </div>
        <!-- Kelas -->
        <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">
                            Kelas <span class="text-red-500">*</span>
        </label>
        <input type="text" id="bantuanKelas" placeholder="Contoh: XI TJKT 2" class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl text-sm font-bold text-gray-800 outline-none focus:border-blue-500 transition">
    </div>
    <!-- Deskripsi -->
    <div>
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">
                            Deskripsi Masalah <span class="text-gray-400 font-normal normal-case">(opsional)</span>
    </label>
    <textarea id="bantuanDesk" rows="3" placeholder="Ceritakan masalahmu secara singkat..." class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl text-sm font-bold text-gray-800 outline-none focus:border-blue-500 transition resize-none">
    </textarea>
</div>
<!-- Tombol Kirim -->
<button onclick="kirimBantuan()" id="btnKirim" class="card-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl flex items-center justify-center gap-2 text-sm shadow-md shadow-blue-200">
    <i class="fab fa-whatsapp text-lg">
    </i>
                        Kirim via WhatsApp
                    </button>
    <p class="text-center text-gray-400 text-[10px]">
                        Pesan akan dikirim ke nomor WhatsApp E-Aspirasi yang terdaftar
                    </p>
</div>
</div>
</div>
<!-- TOMBOL 2 — SALURAN WA -->
<a href="https://whatsapp.com/channel/0029VbCG2ymGU3BRBJ5GSt35" target="_blank" class="card-btn flex items-center gap-4 bg-white rounded-3xl shadow-sm border border-gray-100 px-5 py-5 w-full">
    <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center flex-shrink-0">
        <i class="fab fa-whatsapp text-green-600 text-xl">
        </i>
    </div>
    <div class="flex-1 min-w-0">
        <p class="font-black text-gray-800">Ikuti Saluran Kami</p>
        <p class="text-xs text-gray-500 mt-0.5">Informasi &amp; pengumuman terbaru via WhatsApp Channel</p>
    </div>
    <div class="w-8 h-8 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
        <i class="fas fa-arrow-up-right-from-square text-green-500 text-xs">
        </i>
    </div>
</a>
<!-- TOMBOL 3 — KEMBALI -->
<a href="/index.html" class="card-btn flex items-center gap-4 bg-white rounded-3xl shadow-sm border border-gray-100 px-5 py-5 w-full">
    <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center flex-shrink-0">
        <i class="fas fa-home text-indigo-600 text-lg">
        </i>
    </div>
    <div class="flex-1 min-w-0">
        <p class="font-black text-gray-800">Kembali ke Halaman Utama</p>
        <p class="text-xs text-gray-500 mt-0.5">Kembali ke portal siswa E-Aspirasi</p>
    </div>
    <div class="w-8 h-8 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0">
        <i class="fas fa-chevron-right text-indigo-400 text-xs">
        </i>
    </div>
</a>
<!-- INFO FOOTER -->
<div class="bg-blue-50 border border-blue-100 rounded-2xl px-5 py-4">
    <p class="text-xs font-bold text-blue-700 mb-1 flex items-center gap-1.5">
        <i class="fas fa-info-circle">
        </i> Jam Operasional
            </p>
        <p class="text-xs text-blue-600 leading-relaxed">
                Tim E-Aspirasi siap membantu pada hari <b>Senin–Jumat</b> pukul <b>07.00–16.00 WIB</b>.<br>
                Di luar jam tersebut, pesan akan dibalas pada hari kerja berikutnya.
            </p>
    </div>
</div>
<script>
    const API_URL = '{{ env('API_URL') }}';
    let waEa = '';

    // ── LOAD WA NUMBER ────────────────────────────────────────
    async function loadWaNumber() {
        try {
            const res = await fetch(API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ type:'supabase_get', action:'getPengaturanByKey', payload:{ key : '{{ env('KEY_7') }}' }})
            });
            const data = await res.json();
            waEa = data.data?.[0]?.nilai?.trim() || '';
        } catch(e) {}
    }

    // ── TOGGLE FORM BANTUAN ───────────────────────────────────
    let formOpen = false;
    function toggleBantuan() {
        formOpen = !formOpen;
        const form = document.getElementById('formBantuan');
        const icon = document.getElementById('iconToggle');
        if (formOpen) {
            form.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            form.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // ── KIRIM BANTUAN VIA WA ──────────────────────────────────
    async function kirimBantuan() {
        const tipe   = document.getElementById('tipeMasalah').value.trim();
        const nama   = document.getElementById('bantuanNama').value.trim();
        const kelas  = document.getElementById('bantuanKelas').value.trim();
        const desk   = document.getElementById('bantuanDesk').value.trim();

        if (!tipe)  return Swal.fire('Lengkapi Form', 'Pilih tipe masalah terlebih dahulu.', 'warning');
        if (!nama)  return Swal.fire('Lengkapi Form', 'Masukkan nama kamu.', 'warning');
        if (!kelas) return Swal.fire('Lengkapi Form', 'Masukkan kelas kamu.', 'warning');

        if (!waEa) {
            // Coba fetch ulang jika belum ada
            await loadWaNumber();
        }

        if (!waEa) {
            Swal.fire('Gagal', 'Nomor WhatsApp E-Aspirasi belum dikonfigurasi. Hubungi admin.', 'error');
            return;
        }

        // Format pesan WA
        const tgl   = new Date().toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
        const jam   = new Date().toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });

        const pesan = [
            `🆘 *BANTUAN ${String(window.NAMA_WEB||'E-Aspirasi').toUpperCase()}*`,
            '',
            `📋 *Tipe Masalah:* ${tipe}`,
            `👤 *Nama:* ${nama}`,
            `🏫 *Kelas:* ${kelas}`,
            desk ? `📝 *Deskripsi:* ${desk}` : '',
            '',
            `🕐 Dikirim: ${tgl}, ${jam} WIB`,
            '',
            `_Pesan ini dikirim melalui portal ${window.NAMA_WEB||'E-Aspirasi Grisawangi'}_`
        ].filter(l => l !== undefined && !(l === '' && !desk)).join('\n');

        // Nomor WA: pastikan format internasional
        const noWa = waEa.replace(/[^0-9]/g, '').replace(/^0/, '62');
        const url : `{{ env('URL_8') }}`;

        window.open(url, '_blank');

        // Reset form
        Swal.fire({
            toast: true, position: 'top-end', icon: 'success',
            title: 'Diarahkan ke WhatsApp!',
            showConfirmButton: false, timer: 2000
        });
        document.getElementById('tipeMasalah').value = '';
        document.getElementById('bantuanNama').value  = '';
        document.getElementById('bantuanKelas').value = '';
        document.getElementById('bantuanDesk').value  = '';
        toggleBantuan();
    }

    // ── INIT ──────────────────────────────────────────────────
    window.addEventListener('DOMContentLoaded', () => {
        loadWaNumber();
        // Auto-fill jika ada sesi siswa
        try {
            const raw = sessionStorage.getItem('ea_session_data') || localStorage.getItem('ea_session_data');
            if (raw) {
                const siswa = JSON.parse(raw);
                if (siswa.nama)  document.getElementById('bantuanNama').value  = siswa.nama;
                if (siswa.kelas) document.getElementById('bantuanKelas').value = siswa.kelas;
            }
        } catch(e) {}
    });
</script>



@endsection