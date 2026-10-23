@extends('layouts.app')

@section('content')


    <!-- TOP BAR -->
<div class="bg-blue-700 text-white text-xs py-2 px-4 flex justify-between items-center z-50 relative shadow-md flex-shrink-0">
    <div class="flex items-center gap-2 font-black tracking-widest text-blue-200">
        <i class="fas fa-shield-alt">
        </i> PORTAL AKSES GRISAWANGI
        </div>
        <div class="font-bold text-blue-100 flex items-center gap-2">
            <span>Pemantauan Akademik</span>
        </div>
    </div>
    <!-- ================= SCREEN LOGIN ================= -->
    <div id="loginScreen" class="flex-1 flex flex-col items-center justify-center p-4 fade-in relative z-10 py-10">
        <!-- ICON LOGIN DIKEMBALIKAN & DIRAPIKAN -->
        <div class="w-20 h-20 bg-gradient-to-tr from-blue-600 to-blue-400 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-white shadow-[0_8px_30px_rgb(37,99,235,0.3)] text-white text-3xl transform hover:scale-105 transition-transform duration-300">
            <i class="fas fa-users-cog drop-shadow-md">
            </i>
        </div>
        <div class="text-center mb-7">
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">Portal Akses</h1>
            <p class="text-sm text-gray-500 font-medium mt-1.5">Portal Pemantauan Kedisiplinan &amp; Kehadiran</p>
        </div>
        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-xl w-full max-w-md border border-gray-100 relative">
            <!-- FORM LOGIN ORANG TUA -->
            <div id="formOrtu" class="space-y-4 fade-in">
                <div class="relative">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nama Lengkap Anak</label>
                    <input type="text" id="loginNamaAnak" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 bg-gray-50" placeholder="Ketik nama anak..." autocomplete="off">
                    <ul id="autocompleteOrtu" class="absolute z-[100] w-full bg-white border border-gray-200 rounded-xl shadow-2xl mt-1 hidden max-h-48 overflow-y-auto custom-dropdown text-left divide-y divide-gray-100 top-full left-0">
                    </ul>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Tanggal Lahir Anak</label>
                    <input type="date" id="loginTglLahir" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 bg-gray-50">
                </div>
                <button id="btnSubmitOrtu" onclick="prosesLoginOrtu()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-lg transition active:scale-95 uppercase tracking-widest text-sm mt-2 flex items-center justify-center gap-2">
                    <i class="fas fa-search text-lg">
                    </i> Cek Data Anak
                </button>
                </div>
            </div>
        </div>
        <!-- ================= MAIN DASHBOARD ================= -->
        <div id="mainDashboard" class="flex-1 hidden flex-col bg-gray-50 fade-in">
            <main class="p-4 md:p-6 w-full max-w-5xl mx-auto pb-12">
                <!-- HEADER DASBOR -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-5 flex justify-between items-center mb-6">
                    <div class="flex items-center gap-4">
                        <div id="dashIcon" class="w-14 h-14 rounded-full flex items-center justify-center text-2xl font-black text-white shadow-inner">
                        </div>
                        <div>
                            <h2 id="dashTitle" class="text-2xl font-black text-gray-800 tracking-tight">Memuat...</h2>
                            <p id="dashSubtitle" class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-0.5">Portal Akses</p>
                        </div>
                    </div>
                    <button onclick="keluarSesi()" class="bg-red-50 hover:bg-red-500 hover:text-white text-red-600 px-5 py-3 rounded-xl text-xs font-bold transition shadow-sm border border-red-100 flex items-center gap-2">
                        <i class="fas fa-sign-out-alt text-base">
                        </i>
                        <span class="hidden md:inline uppercase tracking-widest">Keluar Sesi</span>
                    </button>
                </div>
                <!-- VIEW: DASHBOARD ORANG TUA -->
                <div id="viewOrangTua" class="hidden space-y-6 fade-in">
                    <!-- Info Siswa -->
                    <div class="bg-gradient-to-br from-blue-700 to-blue-900 rounded-3xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-3xl z-0">
                        </div>
                        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            <div>
                                <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest mb-1">Profil Pemantauan Siswa</p>
                                <h3 id="ortuNama" class="text-2xl md:text-3xl font-black tracking-tight mb-3">Memuat Nama Siswa...</h3>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="bg-blue-800/60 border border-blue-400/40 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5">
                                        <i class="fas fa-chalkboard-teacher text-blue-300">
                                        </i>
                                        <span id="ortuKelas">-</span>
                                    </span>
                                    <span class="bg-blue-800/60 border border-blue-400/40 px-3 py-1.5 rounded-lg text-xs font-mono font-bold shadow-sm flex items-center gap-1.5">
                                        <i class="fas fa-id-card text-blue-300">
                                        </i> NISN: <span id="ortuNisn">-</span>
                                    </span>
                                </div>
                            </div>
                            <div class="bg-white/10 p-5 rounded-2xl border border-white/20 backdrop-blur-md text-center min-w-[140px] shadow-inner">
                                <p class="text-[10px] font-black text-blue-200 uppercase tracking-widest mb-1 border-b border-white/20 pb-2">Total Poin</p>
                                <p id="ortuPoin" class="text-5xl font-black text-yellow-300 mt-2">0</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Modul Absensi -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                            <h4 class="font-black text-gray-800 mb-4 flex items-center gap-2 text-lg">
                                <i class="fas fa-calendar-check text-green-500">
                                </i> Riwayat Kehadiran</h4>
                                <div class="overflow-y-auto max-h-72 pr-2 custom-dropdown">
                                    <ul id="ortuListAbsen" class="space-y-3">
                                    </ul>
                                </div>
                            </div>
                            <!-- Modul Pelanggaran -->
                            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                                <h4 class="font-black text-gray-800 mb-4 flex items-center gap-2 text-lg">
                                    <i class="fas fa-gavel text-red-500">
                                    </i> Riwayat Poin &amp; Sanksi</h4>
                                    <div class="overflow-y-auto max-h-72 pr-2 custom-dropdown">
                                        <ul id="ortuListPelanggaran" class="space-y-3">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <!-- SCRIPT UTAMA -->
                <script>
        let currentUser = null;

        // =======================================================
        // KONEKSI API BACKEND
        // =======================================================
        async function callBackend(payload) {
            const response = await fetch('/api/notify', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            if (!response.ok) throw new Error(await response.text());
            return await response.json();
        }

        function keluarSesi() {
            currentUser = null;
            document.getElementById('mainDashboard').classList.add('hidden');
            document.getElementById('loginScreen').classList.remove('hidden');
            
            document.getElementById('loginNamaAnak').value = '';
            document.getElementById('loginTglLahir').value = '';
        }

        // =======================================================
        // AUTOCOMPLETE ORANG TUA
        // =======================================================
        function setupAutocompleteOrtu() {
            const inpNama = document.getElementById('loginNamaAnak');
            const dropdown = document.getElementById('autocompleteOrtu');
            let typingTimer;

            if(!inpNama || !dropdown) return;

            inpNama.addEventListener('input', function() {
                clearTimeout(typingTimer);
                const val = this.value.trim();
                if(val.length < 2) { dropdown.classList.add('hidden'); return; }
                
                typingTimer = setTimeout(async () => {
                    dropdown.innerHTML = '<li class="px-4 py-3 text-xs text-blue-500 font-bold">
                <i class="fas fa-spinner fa-spin mr-2">
                </i>Mencari data di server...</li>';
                    dropdown.classList.remove('hidden');
                    try {
                        const res = await callBackend({ type: 'supabase_get', action: 'cariSiswa', payload: { nama: val } });
                        if(res.data && res.data.length > 0) {
                            dropdown.innerHTML = res.data.map(s => `
                                <li class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-50 transition" onclick="pilihSiswaOrtu('${s.nama}')">
                <div class="font-bold text-gray-800 text-sm">${s.nama}</div>
                <div class="text-[10px] text-gray-500 font-black uppercase tracking-widest mt-0.5">
                    <span class="bg-gray-100 px-2 py-0.5 rounded border border-gray-200">${s.kelas}</span>
                </div>
            </li>
                            `).join('');
                        } else {
                            dropdown.innerHTML = '<li class="px-4 py-3 text-xs text-red-500 font-bold">
            <i class="fas fa-exclamation-circle mr-2">
            </i>Siswa tidak ditemukan</li>';
                        }
                    } catch(e) { dropdown.classList.add('hidden'); }
                }, 500);
            });

            document.addEventListener('click', function(e) {
                if(!e.target.closest('#loginNamaAnak') && !e.target.closest('#autocompleteOrtu')) {
                    dropdown.classList.add('hidden');
                }
            });
        }

        window.pilihSiswaOrtu = function(nama) {
            document.getElementById('loginNamaAnak').value = nama;
            document.getElementById('autocompleteOrtu').classList.add('hidden');
        };

        // =======================================================
        // PROSES LOGIN ORANG TUA (ALGORITMA CERDAS & NULL-SAFE)
        // =======================================================
        async function prosesLoginOrtu() {
            const nama = document.getElementById('loginNamaAnak').value;
            const tglRaw = document.getElementById('loginTglLahir').value; 
            const btn = document.getElementById('btnSubmitOrtu');
            
            if (!nama || !tglRaw) {
                return Swal.fire('Peringatan', 'Mohon isi nama dan tanggal lahir anak Anda.', 'warning');
            }
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin">
        </i> Memvalidasi...'; btn.disabled = true;

            // FUNGSI NORMALISASI NAMA & TANGGAL LAHIR
            const normalizeText = (text) => text ? String(text).replace(/\s+/g, ' ').trim().toLowerCase() : '';
            const toYYYYMMDD = (dateStr) => {
                if (!dateStr) return null;
                const parts = String(dateStr).replace(/[^\d\-\/]/g, '').split(/[-/]/);
                if (parts.length !== 3) return null;
                let y, m, d;
                if (parts[0].length === 4) { y = parts[0]; m = parts[1]; d = parts[2]; } 
                else if (parts[2].length === 4) { d = parts[0]; m = parts[1]; y = parts[2]; } 
                else { return null; }
                return `${y}${m.padStart(2, '0')}${d.padStart(2, '0')}`;
            };

            const targetName = normalizeText(nama);
            const targetDate = toYYYYMMDD(tglRaw);

            try {
                // Tarik HANYA Siswa yang Namanya Mirip agar Cepat & Ringan
                const res = await callBackend({ type: 'supabase_get', action: 'cariSiswa', payload: { nama: nama } });
                let matchedSiswa = null;
                
                if (res.data && res.data.length > 0) {
                    matchedSiswa = res.data.find(s => {
                        const dbName = normalizeText(s.nama);
                        const dbDate = toYYYYMMDD(s.tgl_lahir);
                        return dbName === targetName && dbDate === targetDate && targetDate !== null;
                    });
                }

                if(matchedSiswa) {
                    currentUser = { role: 'Orang Tua', dataAnak: matchedSiswa };
                    callBackend({ type: 'supabase_post', action: 'insertLog', payload: { aktor: `Orang Tua - ${matchedSiswa.nama}`, role: 'Portal Akses', aksi: 'Login Akses Orang Tua', detail: `Memantau profil ${matchedSiswa.nama} (${matchedSiswa.kelas}).` } });
                    
                    Swal.fire({toast: true, position: 'top-end', icon: 'success', title: 'Akses Diberikan', showConfirmButton: false, timer: 1500});
                    masukDasborOrtu();
                } else {
                    Swal.fire('Validasi Gagal', 'Data Nama dan Tanggal Lahir tidak cocok dengan database sekolah kami. Pastikan sudah benar.', 'error');
                }
            } catch(e) { 
                Swal.fire('Error Server', e.message, 'error'); 
            }
            finally { 
                btn.innerHTML = '<i class="fas fa-search text-lg">
    </i> Cek Data Anak'; btn.disabled = false; 
            }
        }

        // =======================================================
        // RENDER DASBOR ORANG TUA
        // =======================================================
        async function masukDasborOrtu() {
            document.getElementById('loginScreen').classList.add('hidden');
            document.getElementById('mainDashboard').classList.remove('hidden');
            document.getElementById('viewOrangTua').classList.remove('hidden');
            
            document.getElementById('dashIcon').className = "w-14 h-14 rounded-full flex items-center justify-center text-2xl font-black text-white shadow-inner bg-blue-600";
            document.getElementById('dashIcon').innerHTML = "<i class='fas fa-user-shield'>
</i>";
            document.getElementById('dashTitle').textContent = "Dasbor Orang Tua";

            const data = currentUser.dataAnak;
            document.getElementById('ortuNama').textContent = data.nama;
            document.getElementById('ortuKelas').textContent = data.kelas;
            document.getElementById('ortuNisn').textContent = data.nisn || '-';
            document.getElementById('ortuPoin').textContent = data.total_poin || 0;

            // Load Riwayat Absensi Anak
            try {
                const listAbsen = document.getElementById('ortuListAbsen');
                listAbsen.innerHTML = '<li class="text-xs text-gray-400 font-bold">
<i class="fas fa-spinner fa-spin mr-1">
</i> Memuat data absensi...</li>';
                const resAbsen = await callBackend({ type: 'supabase_get', action: 'getAllAbsensi' }); 
                if(resAbsen.data) {
                    const myAbsen = resAbsen.data.filter(a => a.id_siswa === data.id);
                    if(myAbsen.length > 0) {
                        listAbsen.innerHTML = myAbsen.map(a => {
                            let c = "text-gray-500 bg-gray-50 border-gray-200";
                            if(a.jenis === 'Hadir') c = "text-green-700 bg-green-50 border-green-200";
                            if(a.jenis === 'Sakit' || a.jenis === 'Izin') c = "text-yellow-700 bg-yellow-50 border-yellow-200";
                            if(a.jenis === 'Alpa') c = "text-red-700 bg-red-50 border-red-200";
                            const tgl = new Date(a.created_at).toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'});
                            return `<li class="p-4 rounded-2xl border ${c} flex justify-between items-center shadow-sm">
<div class="font-bold text-sm">${tgl}</div>
<div class="text-[10px] uppercase font-black tracking-widest bg-white/50 px-3 py-1.5 rounded-lg">${a.jenis} ${a.is_late ? '<span class="text-red-600">(Terlambat)</span>' : ''}</div>
</li>`;
                        }).join('');
                    } else {
                        listAbsen.innerHTML = '<li class="text-xs text-gray-500 font-medium p-4 bg-gray-50 rounded-2xl border border-gray-200 flex items-center gap-2">
<i class="fas fa-info-circle text-gray-400 text-lg">
</i> Belum ada riwayat absensi di database.</li>';
                    }
                }
            } catch(e) { console.error(e) }

            // Load Riwayat Poin/Pelanggaran Anak
            try {
                const listPoin = document.getElementById('ortuListPelanggaran');
                listPoin.innerHTML = '<li class="text-xs text-gray-400 font-bold">
<i class="fas fa-spinner fa-spin mr-1">
</i> Memuat data pelanggaran...</li>';
                const resLog = await callBackend({ type: 'supabase_get', action: 'getLogHistory' }); 
                if(resLog.data) {
                    const myLogs = resLog.data.filter(l => l.aksi === 'Lapor Poin Pelanggaran' && l.detail.includes(data.nama));
                    if(myLogs.length > 0) {
                        listPoin.innerHTML = myLogs.map(l => {
                            const tgl = new Date(l.created_at).toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'});
                            return `<li class="p-4 rounded-2xl border border-red-100 bg-red-50 flex flex-col gap-2 shadow-sm">
<div class="flex justify-between items-center">
    <span class="font-black text-xs text-red-800">
        <i class="far fa-calendar-alt mr-1">
        </i> ${tgl}</span>
        <span class="text-[10px] text-red-600 font-black uppercase tracking-widest bg-white px-2.5 py-1 rounded-md border border-red-200 shadow-sm">
            <i class="fas fa-exclamation-circle">
            </i> Poin Ditambah</span>
        </div>
        <div class="text-xs font-semibold text-red-700 mt-1 leading-relaxed">${l.detail}</div>
    </li>`;
                        }).join('');
                    } else {
                        listPoin.innerHTML = '<li class="text-xs text-green-700 font-bold p-4 bg-green-50 rounded-2xl border border-green-200 flex items-center gap-3 shadow-sm">
    <i class="fas fa-check-circle text-2xl text-green-500">
    </i> Anak Anda tidak memiliki catatan pelanggaran. Sangat baik!</li>';
                    }
                }
            } catch(e) { console.error(e) }
        }

        // Init Setup
        document.addEventListener("DOMContentLoaded", () => {
            setupAutocompleteOrtu();
        });
    </script>
    <script src="{{ asset('/grisVA.js') }}" defer="">
    </script>


@endsection