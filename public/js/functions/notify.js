// =====================================================================
// FILE: functions/notify.js (BACKEND TERPUSAT E-ASPIRASI V5 ULTIMATE)
// FUNGSI: Mengelola Database, Autentikasi, Notifikasi Telegram, Upload 
//         Gambar, Mesin Absen, dan Sistem API Key Eksternal.
// =====================================================================

// =====================================================================
// KONFIGURASI UTAMA — Supabase (wajib hardcode agar bisa baca DB)
// Semua key lain (OneSignal, VAPID, Fonnte, dll) disimpan di tabel
// pengaturan sehingga bisa diubah langsung dari Admin Panel
// =====================================================================
// bcryptjs untuk verifikasi password guru
let bcrypt;
try { bcrypt = require('bcryptjs'); } catch(e) { bcrypt = null; }

const SUPABASE_URL : "{{ env('SUPABASE_URL') }}";
const SERVICE_TABLES = ['alumni', 'event_config']; // tabel yang diakses memakai service role key (RLS terkunci untuk anon)
const SUPABASE_KEY = "{{ env('SUPABASE_KEY') }}";

// Fonnte WhatsApp API — untuk kirim WA notif ke siswa
async function sendFonnte(target, message, forAdmin = false) {
    try {
        // Pilih token: forAdmin=true → coba fonnte_token_admin dulu, fallback ke fonnte_token
        let token = '';
        if (forAdmin) {
            const adminTok = await getSetting('fonnte_token_admin');
            token = (adminTok && adminTok.trim()) ? adminTok.trim() : '';
        }
        if (!token) {
            const mainTok = await getSetting('fonnte_token');
            token = mainTok ? mainTok.trim() : '';
        }
        if (!token) {
            console.warn('[Fonnte] Token belum diset di pengaturan DB');
            return { error: 'Fonnte token belum diset' };
        }

        // Bersihkan nomor: hapus +, spasi, dash, titik
        let cleanTarget = String(target).replace(/[^0-9]/g, '');

        // Normalisasi ke format internasional Indonesia
        // 08xx → 628xx, 628xx tetap, +628xx → 628xx
        if (cleanTarget.startsWith('0')) {
            cleanTarget = '62' + cleanTarget.substring(1);
        } else if (!cleanTarget.startsWith('62')) {
            cleanTarget = '62' + cleanTarget;
        }

        if (!cleanTarget || cleanTarget.length < 10) {
            console.warn('[Fonnte] Nomor tidak valid:', target, '->', cleanTarget);
            return { error: 'Nomor tidak valid: ' + target };
        }

        console.log('[Fonnte] Kirim ke:', cleanTarget);

        const formData = new URLSearchParams();
        formData.append('target', cleanTarget);
        formData.append('message', message);
        formData.append('countryCode', '62');
        formData.append('delay', '2');
        formData.append('typing', 'true');

        console.log('[Fonnte] Token length:', token.trim().length);
        console.log('[Fonnte] Target:', cleanTarget);
        console.log('[Fonnte] Message length:', message.length);

        const res = await fetch('https://api.fonnte.com/send', {
            method: 'POST',
            headers: {
                'Authorization': token.trim(),
            },
            body: formData
        });

        const text = await res.text();
        console.log('[Fonnte] HTTP Status:', res.status);
        console.log('[Fonnte] Response raw:', text);

        let result;
        try { result = JSON.parse(text); } catch(e) { result = { raw: text }; }

        if (result.status === false || result.status === 'false') {
            console.error('[Fonnte] GAGAL:', result.reason || result.message || text);
        } else {
            console.log('[Fonnte] BERHASIL kirim ke:', cleanTarget);
        }
        return result;
    } catch(e) {
        console.error('[Fonnte Error]', e.message);
        return { error: e.message };
    }
}

// Header Standar CORS agar tidak diblokir oleh Browser
const headers = { 
    'Access-Control-Allow-Origin': '*', 
    'Access-Control-Allow-Headers': 'Content-Type, Authorization', 
    'Access-Control-Allow-Methods': 'GET, POST, PATCH, DELETE, OPTIONS' 
};

// ---------------------------------------------------------------------
// 1. FUNGSI HELPER: KOMUNIKASI SUPABASE & TRANSLATOR NAMA TABEL
// ---------------------------------------------------------------------
async function supaFetch(path, method = 'GET', bodyData = null) {
    // [AUTO-FIX] Menerjemahkan nama tabel pendek dari web ke nama tabel asli di database
    let safePath = path;
    const pathParts = safePath.split('?');
    const tableName = pathParts[0];
    
    // Kamus Penerjemah
    const aliasMap = {
        'role': 'daftar_role',
        'kelas': 'daftar_kelas',
        'admin': 'hak_akses_admin',
        'buku_saku': 'master_pelanggaran',
        'anggota': 'anggota_elpoint'
    };

    // Jika nama tabel ada di kamus, ganti dengan nama aslinya
    if (aliasMap[tableName]) {
        pathParts[0] = aliasMap[tableName];
        safePath = pathParts.join('?');
    }

    const options = { 
        method, 
        headers: { 
            'apikey': ((SERVICE_TABLES.includes(tableName) && SERVICE_KEY) ? SERVICE_KEY : SUPABASE_KEY), 'Authorization': `Bearer ${(SERVICE_TABLES.includes(tableName) && SERVICE_KEY) ? SERVICE_KEY : SUPABASE_KEY}`, 'Content-Type': 'application/json', 'Prefer': (method === 'POST' || method === 'PATCH') ? 'return=representation' : '' 
        } 
    };
    if (bodyData) options.body = JSON.stringify(bodyData);
    
    const res = await fetch(`${SUPABASE_URL}/rest/v1/${safePath}`, options);
    if (!res.ok) { 
        const errText = await res.text(); 
        throw new Error(`DB Error (404): ${errText}`); 
    }
    if (res.status === 204) return {}; // Respon sukses tanpa body (misal Delete)
    return await res.json();
}

// ---------------------------------------------------------------------
// 2. FUNGSI HELPER: KIRIM PUSH NOTIF VIA ONESIGNAL REST API
// ---------------------------------------------------------------------
async function sendOneSignalPush({ title, message, targetNama = null, targetNisn = null, sound = 'custom_sound_1' }) {
    try {
        // Ambil App ID & API Key dari DB pengaturan
        const appId  = await getSetting('onesignal_app_id');
        const apiKey = await getSetting('onesignal_api_key');
        if (!appId || !apiKey) {
            console.warn('[OneSignal] App ID atau API Key belum diset di pengaturan');
            return null;
        }

        let filters = null;
        if (targetNisn) {
            filters = [{ field: 'tag', key: 'nisn', relation: '=', value: String(targetNisn) }];
        } else if (targetNama) {
            filters = [{ field: 'tag', key: 'nama', relation: '=', value: String(targetNama) }];
        }

        const payload = {
            app_id: appId,
            headings: { en: title, id: title },
            contents: { en: message, id: message },
            android_sound: sound,
            ios_sound: `${sound}.mp3`,
            priority: 10,
        };

        if (filters) { payload.filters = filters; }
        else { payload.included_segments = ['All']; }

        const res = await fetch('https://onesignal.com/api/v1/notifications', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Authorization': `Key ${apiKey}` },
            body: JSON.stringify(payload)
        });

        const result = await res.json();
        console.log('[OneSignal Push]', result);
        return result;
    } catch(e) {
        console.error('[OneSignal Push Error]', e.message);
        return null;
    }
}

// ---------------------------------------------------------------------
// 3. FUNGSI HELPER: PENGATURAN & AUTENTIKASI ADMIN
// ---------------------------------------------------------------------
async function getSetting(key) {
    try { 
        const data = await supaFetch(`pengaturan?nama_pengaturan=eq.${key}&select=nilai`); 
        if (data && data.length > 0) return data[0].nilai; 
    } catch(e) { console.error(`Gagal tarik setting: ${key}`); } 
    return "";
}

const VERSI_SERVER = '2026-10-12.1-grisva';
async function setSetting(k, v) {
    const ex = await supaFetch(`pengaturan?nama_pengaturan=eq.${encodeURIComponent(k)}&select=id`);
    if (ex && ex.length) await supaFetch(`pengaturan?nama_pengaturan=eq.${encodeURIComponent(k)}`, 'PATCH', { nilai: String(v) });
    else await supaFetch('pengaturan', 'POST', { nama_pengaturan: k, nilai: String(v) });
}

async function createAuthUser(email, password) {
    try {
        let authRes = await fetch(`${SUPABASE_URL}/auth/v1/admin/users`, { 
            method: 'POST', 
            headers: { 'apikey': SUPABASE_KEY, 'Authorization': `Bearer ${SUPABASE_KEY}`, 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ email: email, password: password, email_confirm: true }) 
        });
        
        if (!authRes.ok) { 
            authRes = await fetch(`${SUPABASE_URL}/auth/v1/signup`, { 
                method: 'POST', 
                headers: { 'apikey': SUPABASE_KEY, 'Content-Type': 'application/json' }, 
                body: JSON.stringify({ email: email, password: password }) 
            }); 
        }
        return true;
    } catch (e) { throw e; }
}

// ---------------------------------------------------------------------
// HELPER ABSEN MANUAL (waktu selalu dihitung server dalam WIB)
// ---------------------------------------------------------------------
function waktuWib() {
    const w = new Date(Date.now() + 7 * 3600 * 1000);
    return { tgl: w.toISOString().slice(0, 10), jam: w.getUTCHours() + w.getUTCMinutes() / 60, hari: w.getUTCDay() };
}
function jarakMeter(lat1, lon1, lat2, lon2) {
    const R = 6371000, rad = Math.PI / 180;
    const dLat = (lat2 - lat1) * rad, dLon = (lon2 - lon1) * rad;
    const a = Math.sin(dLat / 2) ** 2 + Math.cos(lat1 * rad) * Math.cos(lat2 * rad) * Math.sin(dLon / 2) ** 2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}
async function infoAbsenManual(idSiswa) {
    const wib = waktuWib();
    const angka = async (k, def) => { const v = parseInt(await getSetting(k), 10); return Number.isNaN(v) ? def : v; };
    const aktif = (await getSetting('aktifkan_manual_absen')) === 'true';
    const liburList = ((await getSetting('hari_libur_custom')) || '').split(',').map(x => x.trim()).filter(Boolean);
    let libur = null;
    if (liburList.includes(wib.tgl)) libur = 'Hari libur';
    if (aktif) { try { await pastikanLiburTerbaru(); } catch (e) {} }
    // Manajemen Hari: baris tanggal ini menentukan (Libur / Masuk). Tanpa baris -> Sabtu/Minggu otomatis libur.
    if (!libur) {
        let hr = null, ok = true;
        try { hr = (await supaFetch(`manajemen_hari?tanggal=eq.${wib.tgl}&select=status,keterangan&limit=1`))[0] || null; } catch (e) { ok = false; }
        if (hr) { if (hr.status === 'Libur') libur = hr.keterangan || 'Hari libur'; }
        else if (wib.hari === 0 || wib.hari === 6) libur = 'Akhir pekan';
        else if (!ok && (wib.hari === 0 || wib.hari === 6)) libur = 'Akhir pekan';
    }
    const jamMulai = await angka('absen_jam_mulai', 7);
    const poin = await angka('absen_poin_terlambat', 6);
    const lat = parseFloat(await getSetting('geofence_lat'));
    const lon = parseFloat(await getSetting('geofence_lon'));
    const radius = await angka('geofence_radius', 100);
    const wajib = (await getSetting('wajib_geofence')) === 'true';
    const geofence = (!Number.isNaN(lat) && !Number.isNaN(lon)) ? { lat, lon, radius, wajib } : null;
    let record = null;
    if (idSiswa) {
        const awal = encodeURIComponent(`${wib.tgl}T00:00:00+07:00`);
        const r = await supaFetch(`absensi?id_siswa=eq.${idSiswa}&created_at=gte.${awal}&select=created_at,is_late,aktor,jenis&order=created_at.desc&limit=1`);
        record = (r && r[0]) || null;
    }
    return { wib, aktif, libur, jamMulai, poin, geofence, record };
}

// ---------------------------------------------------------------------
// 3. FUNGSI UTAMA SERVER (HANDLER NETLIFY)
// ---------------------------------------------------------------------
// Kuota guru per mapel (master_mapel.max_guru; NULL = tanpa batas)
async function cekKuotaMapel(mapelJson, idGuru) {
    let baru = []; try { baru = JSON.parse(mapelJson || '[]'); } catch(e) {}
    if (!Array.isArray(baru) || !baru.length) return;
    let lama = [];
    if (idGuru) { const g = await supaFetch(`master_guru?id=eq.${idGuru}&select=mapel&limit=1`); try { lama = JSON.parse((g && g[0] && g[0].mapel) || '[]'); } catch(e) {} }
    const semua = await supaFetch('master_guru?select=id,mapel');
    for (const nm of baru) {
        if (lama.includes(nm)) continue; // mapel lama tidak dicek ulang
        const mm = await supaFetch(`master_mapel?nama_mapel=eq.${encodeURIComponent(nm)}&select=max_guru&limit=1`);
        const mx = mm && mm[0] ? mm[0].max_guru : null;
        if (mx === null || mx === undefined) continue;
        const terpakai = (semua || []).filter(g => g.id !== idGuru && (() => { try { return JSON.parse(g.mapel || '[]').includes(nm); } catch(e) { return false; } })()).length;
        if (terpakai >= mx) throw new Error(`Kuota guru untuk mapel "${nm}" penuh (maks. ${mx} guru).`);
    }
}

// ---- Jadwal guru (acuan jam WIB server) ----
const _tmS=s=>{const p=String(s||'').split(':');return (+p[0])*60+(+p[1]||0);};
const _fmS=m=>String(Math.floor(m/60)).padStart(2,'0')+':'+String(m%60).padStart(2,'0');
const _arrS=v=>{try{const x=typeof v==='string'?JSON.parse(v||'[]'):(v||[]);return Array.isArray(x)?x:[];}catch(e){return [];}};
async function _cekJadwalGuruInti(idGuru) {
    const wib = waktuWib(), hari = wib.hari || 7, now = Math.round(wib.jam * 60);
    const liburC = ((await getSetting('hari_libur_custom')) || '').split(',').map(x => x.trim()).filter(Boolean);
    let hr = (await supaFetch(`manajemen_hari?tanggal=eq.${wib.tgl}&select=*&limit=1`))[0] || null;
    if (hr && hr.status === 'Libur') return { status: 'libur', ket: hr.keterangan || '' };
    if (!hr) {
        if (hari >= 6 || liburC.includes(wib.tgl)) return { status: 'libur', ket: 'Hari libur' };
        // belum ada baris untuk hari ini -> pakai pola hari yang sama (baris terakhir yang sudah diatur admin)
        hr = (await supaFetch(`manajemen_hari?hari=eq.${hari}&status=eq.Masuk&jam_mulai=not.is.null&order=tanggal.desc&limit=1&select=*`))[0] || null;
        if (hr) { const v = (await getSetting('istirahat_default_hari_' + hari)) || (await getSetting('istirahat_default_semua')); if (v) { try { hr = { ...hr, istirahat: JSON.parse(v) }; } catch (e) {} } }
    }
    const belum = { status: 'belum_diatur', pesan: 'Jam pelajaran hari ini belum diatur admin (Manajemen Hari).' };
    if (!hr || !hr.jam_mulai) return belum;
    const dur = {}; (await supaFetch('manajemen_jam_mapel?select=jam_ke,durasi_menit')).forEach(j => { dur[j.jam_ke] = j.durasi_menit; });
    const ist = _arrS(hr.istirahat).map(x => ({ nama: x.nama, m: _tmS(x.mulai), s: _tmS(x.selesai) }));
    const ag = _arrS(hr.agenda).map(x => ({ nama: x.nama, m: _tmS(x.mulai), s: _tmS(x.selesai) })).sort((a, b) => a.m - b.m);
    let cur = _tmS(hr.jam_mulai); ag.forEach(a => { if (a.m <= cur && a.s > cur) cur = a.s; });
    const slots = [];
    for (let k = 1; k <= (parseInt(hr.jumlah_jam_mapel) || 0); k++) {
        ist.forEach(x => { if (cur >= x.m && cur < x.s) cur = x.s; });
        const d = dur[k] || 45; slots.push({ jam_ke: k, mulai: cur, selesai: cur + d }); cur += d;
    }
    if (!slots.length) return belum;
    const di = ist.find(x => now >= x.m && now < x.s); if (di) return { status: 'istirahat', sampai: _fmS(di.s) };
    const da = ag.find(x => now >= x.m && now < x.s); if (da) return { status: 'agenda', nama: da.nama, sampai: _fmS(da.s) };
    const js = await supaFetch(`jadwal_mapel?id_guru=eq.${idGuru}&select=*&order=hari.asc,jam_ke_mulai.asc`);
    const hariIni = js.filter(j => j.hari === hari);
    const sk = slots.find(s => now >= s.mulai && now < s.selesai);
    if (sk) {
        const j = hariIni.find(j => sk.jam_ke >= j.jam_ke_mulai && sk.jam_ke <= j.jam_ke_selesai);
        if (j) { const e = slots.find(s => s.jam_ke === j.jam_ke_selesai); return { status: 'mengajar', mapel: j.nama_mapel, kelas: j.nama_kelas, id_kelas: j.id_kelas, jam_ke: sk.jam_ke, sampai: _fmS((e || sk).selesai) }; }
    }
    let n = hariIni.find(j => { const s = slots.find(x => x.jam_ke === j.jam_ke_mulai); return s && s.mulai > now; }), mulai = null;
    if (n) mulai = _fmS(slots.find(x => x.jam_ke === n.jam_ke_mulai).mulai);
    for (let d = 1; !n && d <= 7; d++) n = js.find(j => j.hari === ((hari - 1 + d) % 7) + 1);
    return { status: 'tidak_mengajar', berikut: n ? { hari: n.hari, jam_ke: n.jam_ke_mulai, mapel: n.nama_mapel, kelas: n.nama_kelas, mulai } : null };
}

async function cekJadwalGuru(idGuru) {
    try { await pastikanLiburTerbaru(); } catch (e) {}
    const r = await _cekJadwalGuruInti(idGuru);
    r.batasi = (await getSetting('guru_batasi_jadwal')) !== 'false';
    return r;
}

// ---- Filter semester (admin), verifikasi admin, backup/restore, libur ----
const rq=(p)=>{const f=p&&p.semFilter;if(!f||!f.dari||!f.sampai)return '';return `&created_at=gte.${encodeURIComponent(f.dari+'T00:00:00+07:00')}&created_at=lte.${encodeURIComponent(f.sampai+'T23:59:59.999+07:00')}`;};
const SERVICE_KEY = process.env.SUPABASE_SERVICE_ROLE_KEY || '';
const TABEL_BACKUP = ['master_semester','master_mapel','daftar_kelas','daftar_role','master_pelanggaran','hak_akses_admin','master_guru','manajemen_guru','manajemen_nama_guru','siswa','alumni','jadwal_mapel','manajemen_jam_mapel','manajemen_hari','catatan_absen_guru','aspirasi','balasan_laporan','absensi','penilaian','pengumuman','api_keys','log_history','pengaturan','event_config'];
async function verifSuperAdmin(token) {
    if (!token) throw new Error('Sesi admin tidak valid. Login ulang.');
    const r = await fetch(SUPABASE_URL + '/auth/v1/user', { headers: { apikey: SUPABASE_KEY, Authorization: 'Bearer ' + token } });
    if (!r.ok) throw new Error('Sesi admin tidak valid. Login ulang.');
    const u = await r.json();
    const a = await supaFetch(`hak_akses_admin?email_admin=eq.${encodeURIComponent(u.email)}&select=role_name&limit=1`);
    if (!a[0] || a[0].role_name !== 'Super Admin') throw new Error('Fitur ini hanya untuk Super Admin.');
    return u.email;
}
function sbSvc(path, opt = {}) {
    if (!SERVICE_KEY) throw new Error('SUPABASE_SERVICE_ROLE_KEY belum diset di Environment Variables Netlify.');
    return fetch(SUPABASE_URL + path, { ...opt, headers: { apikey: SERVICE_KEY, Authorization: 'Bearer ' + SERVICE_KEY, ...(opt.headers || {}) } });
}
function strLit(s) { return /[\\\r\n]/.test(s) ? `convert_from(decode('${Buffer.from(s, 'utf8').toString('base64')}','base64'),'UTF8')` : `'${s.replace(/'/g, "''")}'`; }
function sqlLit(v) {
    if (v === null || v === undefined) return 'NULL';
    if (typeof v === 'boolean') return v ? 'TRUE' : 'FALSE';
    if (typeof v === 'number') return String(v);
    if (typeof v === 'object') return strLit(JSON.stringify(v)) + '::jsonb';
    return strLit(String(v));
}
async function ambilSemua(t) {
    const out = [];
    for (let off = 0; off < 200000; off += 1000) {
        let r = await sbSvc(`/rest/v1/${t}?select=*&order=id.asc&limit=1000&offset=${off}`);
        if (!r.ok) r = await sbSvc(`/rest/v1/${t}?select=*&limit=1000&offset=${off}`);
        if (!r.ok) throw new Error(`${t}: HTTP ${r.status}`);
        const rows = await r.json(); out.push(...rows); if (rows.length < 1000) break;
    }
    return out;
}
async function buatBackupSql() {
    // semua tabel diambil paralel (jauh lebih cepat; aman dari timeout fungsi)
    const hasil = await Promise.all(TABEL_BACKUP.map(async t => {
        try { return { t, rows: await ambilSemua(t) }; }
        catch (e) { return { t, rows: null, err: e.message }; }
    }));
    const ring = [], dumps = [];
    hasil.forEach(h => {
        if (h.rows) { dumps.push([h.t, h.rows]); ring.push({ tabel: h.t, baris: h.rows.length }); }
        else ring.push({ tabel: h.t, baris: 0, lewati: h.err });
    });
    const L = ['-- E-ASPIRASI BACKUP v1', `-- Dibuat: ${new Date().toISOString()}`, '-- Mode: REPLACE (DELETE lalu INSERT). Skema tabel tidak ikut; gunakan setup_database.sql untuk skema.'];
    dumps.slice().reverse().forEach(([t]) => L.push(`DELETE FROM ${t} WHERE true;`));
    for (const [t, rows] of dumps) {
        L.push(`-- TABEL ${t}: ${rows.length}`);
        for (let i = 0; i < rows.length; i += 50) {
            const cols = Object.keys(rows[i]);
            const vals = rows.slice(i, i + 50).map(r => '(' + cols.map(c => sqlLit(r[c])).join(', ') + ')').join(', ');
            L.push(`INSERT INTO ${t} (${cols.join(', ')}) VALUES ${vals};`);
        }
    }
    return { sql: L.join('\n') + '\n', ringkasan: ring };
}
async function pastikanBucket() {
    const r = await sbSvc('/storage/v1/bucket/backups');
    if (r.ok) return true;
    const c = await sbSvc('/storage/v1/bucket', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: 'backups', name: 'backups', public: false }) });
    return c.ok;
}
function namaBackupAman(n) { return /^[A-Za-z0-9_.\-]{1,120}\.sql$/.test(String(n || '')); }
async function linkBackupStorage(nama, detik) {
    const s = await sbSvc('/storage/v1/object/sign/backups/' + encodeURIComponent(nama), { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ expiresIn: detik || 604800 }) });
    const j = await s.json();
    return j.signedURL ? SUPABASE_URL + '/storage/v1' + j.signedURL : null;
}
async function uploadBackup(sql, nama) {
    const kirim = () => sbSvc('/storage/v1/object/backups/' + nama, { method: 'POST', headers: { 'Content-Type': 'text/plain', 'x-upsert': 'true' }, body: sql });
    let r = await kirim();
    if (!r.ok) { await pastikanBucket(); r = await kirim(); }
    if (!r.ok) throw new Error('Upload ke Storage gagal: ' + await r.text());
    return await linkBackupStorage(nama, 604800);
}
function barisAman(l) {
    if (/\\/.test(l)) return false;
    let q = false, semi = 0;
    for (let i = 0; i < l.length; i++) {
        const c = l[i];
        if (c === "'") q = !q;
        else if (c === ';' && !q) { semi++; if (i !== l.length - 1) return false; }
    }
    return !q && semi === 1;
}
async function restoreSql(sql, opt) {
    opt = opt || {};
    const lines = String(sql || '').split(/\r?\n/).map(x => x.trim()).filter(Boolean);
    if (!lines.length || lines[0] !== '-- E-ASPIRASI BACKUP v1') throw new Error('Bukan file backup E-Aspirasi yang valid.');
    const stm = [], ringkas = [];
    for (const l of lines) {
        if (l.startsWith('--')) { const m = l.match(/^-- TABEL ([a-z_]+): (\d+)$/); if (m) ringkas.push({ tabel: m[1], baris: +m[2] }); continue; }
        const m = l.match(/^(?:DELETE FROM ([a-z_]+)(?: WHERE true)?;|INSERT INTO ([a-z_]+) \([a-z_, ]+\) VALUES \()/);
        const tb = m && (m[1] || m[2]);
        if (!tb || !TABEL_BACKUP.includes(tb) || !barisAman(l)) throw new Error('File ditolak: ada perintah yang tidak diizinkan.');
        // pg_safeupdate (Supabase) menolak DELETE tanpa WHERE
        stm.push(/^DELETE FROM [a-z_]+;$/.test(l) ? l.replace(/;$/, ' WHERE true;') : l);
    }
    if (!stm.length) throw new Error('File backup kosong.');
    const ringkasan = { tabel: ringkas.length, baris: ringkas.reduce((a, b) => a + b.baris, 0), ringkasan: ringkas, perintah: stm.length };
    if (opt.dryRun) return Object.assign({ dryRun: true }, ringkasan);
    // satu panggilan RPC = satu transaksi; gagal di tengah -> semua dibatalkan, data lama utuh
    const jalan = (arr) => sbSvc('/rest/v1/rpc/exec_sql', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ sql_query: arr.join('\n') }) });
    let r = await jalan(["SET session_replication_role = 'replica';", ...stm, "SET session_replication_role = 'origin';"]);
    if (!r.ok) { const e1 = await r.text(); if (/session_replication_role|permission denied/i.test(e1)) r = await jalan(stm); else throw new Error('Restore gagal, data lama tetap utuh: ' + e1); }
    if (!r.ok) throw new Error('Restore gagal, data lama tetap utuh: ' + await r.text());
    return ringkasan;
}
function _normTgl(t) { const m = String(t || '').match(/^(\d{4})-?(\d{1,2})-?(\d{1,2})/); return m ? `${m[1]}-${m[2].padStart(2, '0')}-${m[3].padStart(2, '0')}` : null; }
async function ambilLiburTahun(tahun) {
    const arr = j => Array.isArray(j) ? j : (j && (j.data || j.holidays)) || [];
    const sumber = [
        { nama: 'api-harilibur', url : `{{ env('URL_43') }}`, parse: j => arr(j).map(x => ({ tgl: x.holiday_date, nama: x.holiday_name })) },
        { nama: 'dayoffapi', url : `{{ env('URL_44') }}`, parse: j => arr(j).map(x => ({ tgl: x.tanggal, nama: x.keterangan })) },
        { nama: 'kemendesa', url : `{{ env('URL_45') }}`, parse: j => arr(j).map(x => ({ tgl: x.date || x.tanggal || x.holiday_date, nama: x.name || x.keterangan || x.description || x.holiday_name })) },
        { nama: 'guangrei', url : '{{ env('URL_46') }}', parse: j => Object.keys(j || {}).filter(k => j[k] && j[k].holiday).map(k => ({ tgl: k, nama: [].concat(j[k].summary || []).join(', ') })) }
    ];
    const galat = [];
    for (const sm of sumber) {
        try {
            const r = await fetch(sm.url, { signal: AbortSignal.timeout(7000) });
            if (!r.ok) throw new Error('HTTP ' + r.status);
            const peta = {};
            sm.parse(await r.json()).forEach(x => {
                const tgl = _normTgl(x.tgl);
                if (!tgl || tgl.slice(0, 4) !== String(tahun)) return;
                const nama = String(x.nama || '').trim() || 'Libur nasional';
                const hari = new Date(tgl + 'T00:00:00Z').getUTCDay();
                if ((hari === 0 || hari === 6) && /^(minggu|sabtu)$/i.test(nama)) return;
                if (peta[tgl]) { if (!peta[tgl].nama.includes(nama)) peta[tgl].nama += ', ' + nama; } else peta[tgl] = { tgl, nama };
            });
            const items = Object.values(peta);
            if (items.length) return { sumber: sm.nama, items };
            galat.push(sm.nama + ': data kosong');
        } catch (e) { galat.push(sm.nama + ': ' + e.message); }
    }
    throw new Error(galat.join(' | '));
}
async function syncLibur(tahun) {
    tahun = String(tahun);
    let hasil;
    try { hasil = await ambilLiburTahun(tahun); }
    catch (e) { return { ok: false, pesan: 'Semua sumber data libur tidak bisa diakses, data lama dipakai. Rincian: ' + e.message }; }
    const ada = await supaFetch(`manajemen_hari?tanggal=gte.${tahun}-01-01&tanggal=lte.${tahun}-12-31&select=id,tanggal,status_manual`);
    const peta = {}; (ada || []).forEach(x => { peta[x.tanggal] = x; });
    let ubah = 0, lewat = 0; const baruRows = [];
    for (const x of hasil.items) {
        const ex = peta[x.tgl]; if (ex && ex.status_manual) { lewat++; continue; }
        const cuti = /cuti bersama/i.test(x.nama);
        const body = { tanggal: x.tgl, hari: new Date(x.tgl + 'T00:00:00Z').getUTCDay() || 7, status: 'Libur', keterangan: x.nama, is_cuti_bersama: cuti, is_civic: false, is_religious: false, updated_at: new Date().toISOString() };
        if (ex) { await supaFetch(`manajemen_hari?id=eq.${ex.id}`, 'PATCH', body); ubah++; } else baruRows.push(body);
    }
    if (baruRows.length) await supaFetch('manajemen_hari', 'POST', baruRows);
    try { await setSetting('libur_sync_ok_' + tahun, waktuWib().tgl); } catch (e) {}
    return { ok: true, sumber: hasil.sumber, total: hasil.items.length, baru: baruRows.length, ubah, lewat };
}
// Sinkron libur otomatis (lazy): bila tahun ini belum pernah berhasil, coba maksimal 1x per hari
async function pastikanLiburTerbaru() {
    const w = waktuWib(), tahun = w.tgl.slice(0, 4);
    if (await getSetting('libur_sync_ok_' + tahun)) return;
    if ((await getSetting('libur_sync_coba')) === w.tgl) return;
    await setSetting('libur_sync_coba', w.tgl);
    await syncLibur(tahun);
}
async function pastikanBarisHariIni() {
    const w = waktuWib(), hari = w.hari || 7;
    const ada = await supaFetch(`manajemen_hari?tanggal=eq.${w.tgl}&select=id&limit=1`);
    if (ada && ada.length) return 'sudah ada';
    if (hari >= 6) { await supaFetch('manajemen_hari', 'POST', { tanggal: w.tgl, hari, status: 'Libur', keterangan: 'Akhir pekan' }); return 'libur akhir pekan'; }
    const tp = (await supaFetch(`manajemen_hari?hari=eq.${hari}&status=eq.Masuk&jam_mulai=not.is.null&order=tanggal.desc&limit=1&select=*`))[0];
    const b = { tanggal: w.tgl, hari, status: 'Masuk' };
    if (tp) { b.jam_mulai = tp.jam_mulai; b.jumlah_jam_mapel = tp.jumlah_jam_mapel; b.jam_pulang = tp.jam_pulang; b.istirahat = tp.istirahat; b.agenda = tp.agenda; }
    { const v = (await getSetting('istirahat_default_hari_' + hari)) || (await getSetting('istirahat_default_semua')); if (v) { try { b.istirahat = JSON.parse(v); } catch (e) {} } }
    await supaFetch('manajemen_hari', 'POST', b); return tp ? 'disalin dari pola' : 'dibuat kosong';
}
async function cronHarian() {
    const hasil = {}, w = waktuWib();
    try { hasil.libur = await syncLibur(w.tgl.slice(0, 4)); } catch (e) { hasil.libur = { ok: false, pesan: e.message }; }
    try { hasil.hariIni = await pastikanBarisHariIni(); } catch (e) { hasil.hariIni = 'gagal: ' + e.message; }
    try {
        const b = await buatBackupSql(), url = await uploadBackup(b.sql, `backup-${w.tgl}.sql`);
        hasil.backup = { ok: true, url };
        const target = (await getSetting('wa_guru')) || (await getSetting('wa_ea'));
        if (target && url) await sendFonnte(target, `🗄️ *Backup Harian E-Aspirasi*\n${w.tgl}\n\nUnduh (berlaku 7 hari):\n${url}`, true);
    } catch (e) { hasil.backup = { ok: false, pesan: e.message }; }
    return hasil;
}

// =====================================================================
// GRISAEVENT / SETEVENT
// - Siswa : cari nama (autofill) -> deteksi PIN -> login (PIN atau tanggal lahir) -> baca event
// - OSIS  : login pakai anggota_elpoint (role OSIS) -> token bertanda tangan -> simpan event
// Tabel: event_config (lihat event_setup.sql). Hanya diakses lewat service role key.
// =====================================================================
const nodeCrypto = require('crypto');
const EVENT_STATUS = ['Segera Hadir', 'Sedang Berlangsung', 'Pendaftaran Ditutup', 'Selesai'];
const EVENT_ICON = ['fas fa-link', 'fas fa-file-pdf', 'fab fa-whatsapp', 'fas fa-sitemap'];
const EVENT_BATAS_SALAH = 5, EVENT_JENDELA_MS = 10 * 60000;

function eventSecret() { return process.env.EVENT_SECRET || SERVICE_KEY || ('ea-event::' + SUPABASE_KEY); }
function eventBuatToken(u) {
    const p = Buffer.from(JSON.stringify({ id: u.id, nama: u.nama_anggota || u.username, exp: Date.now() + 12 * 3600 * 1000 })).toString('base64url');
    return p + '.' + nodeCrypto.createHmac('sha256', eventSecret()).update(p).digest('base64url');
}
function eventBacaToken(t) {
    try {
        const [p, sig] = String(t || '').split('.'); if (!p || !sig) return null;
        const ok = nodeCrypto.createHmac('sha256', eventSecret()).update(p).digest('base64url');
        const a = Buffer.from(sig), b = Buffer.from(ok);
        if (a.length !== b.length || !nodeCrypto.timingSafeEqual(a, b)) return null;
        const d = JSON.parse(Buffer.from(p, 'base64url').toString('utf8'));
        return d.exp > Date.now() ? d : null;
    } catch (e) { return null; }
}
function eventNormTgl(t) {
    const s = String(t || '').trim().toLowerCase(); if (!s) return '';
    const bln = { jan: 1, feb: 2, mar: 3, apr: 4, may: 5, mei: 5, jun: 6, jul: 7, aug: 8, agu: 8, agt: 8, sep: 9, oct: 10, okt: 10, nov: 11, dec: 12, des: 12 };
    const f = (y, m, d) => `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
    let m;
    if ((m = s.match(/^(\d{4})[-\/.](\d{1,2})[-\/.](\d{1,2})/))) return f(m[1], m[2], m[3]);
    if ((m = s.match(/^(\d{1,2})[-\/.](\d{1,2})[-\/.](\d{4})/))) return f(m[3], m[2], m[1]);
    if ((m = s.match(/^(\d{1,2})[-\/. ]([a-z]{3})[a-z]*[-\/. ](\d{4})/)) && bln[m[2]]) return f(m[3], bln[m[2]], m[1]);
    return '';
}
// pembatas percobaan salah (PIN 4 digit rawan ditebak) — disimpan di tabel pengaturan
async function eventSisaBlokir(key) {
    const v = await getSetting(key); if (!v) return 0;
    const [n, t] = String(v).split('|').map(Number);
    if (!t || Date.now() - t > EVENT_JENDELA_MS || n < EVENT_BATAS_SALAH) return 0;
    return Math.max(1, Math.ceil((EVENT_JENDELA_MS - (Date.now() - t)) / 60000));
}
async function eventCatatSalah(key) {
    const v = await getSetting(key); let n = 0, t = Date.now();
    if (v) { const a = String(v).split('|').map(Number); if (a[1] && Date.now() - a[1] <= EVENT_JENDELA_MS) { n = a[0] || 0; t = a[1]; } }
    await setSetting(key, (n + 1) + '|' + t);
}
async function eventResetSalah(key) { if (await getSetting(key)) await setSetting(key, '0|0'); }
function eventUrl(u) { u = String(u || '').trim(); return /^https?:\/\/\S+$/i.test(u) ? u.slice(0, 1000) : ''; }
function eventTeks(s, n) { return String(s == null ? '' : s).trim().slice(0, n); }
function eventIdAman(id) { id = String(id || ''); if (!/^[\w-]{1,64}$/.test(id)) throw new Error('Data tidak valid.'); return id; }
async function eventCatatLog(aktor, role, aksi, detail) {
    try { await supaFetch('log_history', 'POST', { aktor, role, aksi, detail }); } catch (e) {}
}
async function eventAmbilSiswa(id) {
    const r = (await supaFetch(`siswa?id=eq.${encodeURIComponent(id)}&select=id,nama,kelas,status,login_pin,tgl_lahir,arsip&limit=1`))[0];
    if (!r || r.arsip) throw new Error('Data siswa tidak ditemukan.');
    if (r.status === 'Diblokir') throw new Error('Akses akun kamu sedang diblokir. Hubungi admin sekolah.');
    return r;
}
async function eventOsisValid(id) {
    const a = (await supaFetch(`anggota_elpoint?id=eq.${encodeURIComponent(eventIdAman(id))}&select=*&limit=1`))[0];
    if (!a || String(a.role || '').trim().toLowerCase() !== 'osis' || a.status === 'Diblokir') throw new Error('Sesi OSIS tidak valid. Silakan login ulang.');
    return a;
}

async function eventHandler(action, payload) {
    payload = payload || {};
    switch (action) {
        case 'eventCariSiswa': {
            const nama = String(payload.nama || '').trim();
            if (nama.length < 2) return [];
            const q = encodeURIComponent(nama);
            const kolom = 'id,nama,nama_panggilan,kelas,status,login_pin';
            let hasil = (await supaFetch(`siswa?select=${kolom}&arsip=eq.false&nama=ilike.%25${q}%25&order=nama.asc&limit=12`)) || [];
            if (hasil.length < 12) {
                const r2 = (await supaFetch(`siswa?select=${kolom}&arsip=eq.false&nama_panggilan=ilike.%25${q}%25&limit=${12 - hasil.length}`)) || [];
                const ada = new Set(hasil.map(s => s.id));
                hasil = hasil.concat(r2.filter(s => !ada.has(s.id)));
            }
            return hasil.map(s => ({ id: s.id, nama: s.nama, nama_panggilan: s.nama_panggilan || '', kelas: s.kelas || '', punya_pin: !!(s.login_pin && String(s.login_pin).trim()), diblokir: s.status === 'Diblokir' }));
        }
        case 'eventLoginSiswa': {
            const id = eventIdAman(payload.id), key = '{{ env('KEY_47') }}' + id.replace(/[^\w]/g, '');
            const tunggu = await eventSisaBlokir(key);
            if (tunggu) throw new Error(`Terlalu banyak percobaan salah. Coba lagi ${tunggu} menit lagi.`);
            const r = await eventAmbilSiswa(id);
            const punyaPin = !!(r.login_pin && String(r.login_pin).trim());
            let ok;
            if (punyaPin) ok = String(payload.pin || '').trim() === String(r.login_pin).trim();
            else { const a = eventNormTgl(payload.tgl), b = eventNormTgl(r.tgl_lahir); ok = !!a && a === b; }
            if (!ok) { await eventCatatSalah(key); throw new Error(punyaPin ? 'PIN salah.' : 'Tanggal lahir tidak cocok dengan data sekolah.'); }
            await eventResetSalah(key);
            await eventCatatLog(r.nama, 'Siswa', 'Login Grisaevent', `Masuk portal event. Kelas: ${r.kelas || '-'}`);
            return { id: r.id, nama: r.nama, kelas: r.kelas || '' };
        }
        case 'eventCekSesi': {
            const r = await eventAmbilSiswa(eventIdAman(payload.id));
            return { id: r.id, nama: r.nama, kelas: r.kelas || '' };
        }
        case 'eventLoginOsis': {
            const u = String(payload.username || '').trim().toLowerCase(), pw = String(payload.password || '');
            if (!u || !pw) throw new Error('Username dan password wajib diisi.');
            const key = '{{ env('KEY_48') }}' + u.replace(/[^\w]/g, '').slice(0, 40);
            const tunggu = await eventSisaBlokir(key);
            if (tunggu) throw new Error(`Terlalu banyak percobaan salah. Coba lagi ${tunggu} menit lagi.`);
            const rows = (await supaFetch(`anggota_elpoint?select=*&username=ilike.${encodeURIComponent(u)}&limit=10`)) || [];
            const a = rows.find(x => String(x.username || '').trim().toLowerCase() === u);
            if (!a || String(a.password || '') !== pw) { await eventCatatSalah(key); throw new Error('Username atau password salah.'); }
            if (String(a.role || '').trim().toLowerCase() !== 'osis') throw new Error('Akun ini bukan OSIS. Hanya anggota El Point dengan role OSIS yang bisa mengatur event.');
            if (a.status === 'Diblokir') throw new Error('Akun kamu sedang diblokir.');
            await eventResetSalah(key);
            await eventCatatLog(a.nama_anggota || a.username, 'OSIS El Point', 'Login Set Event', 'Masuk panel pengaturan event');
            return { token: eventBuatToken(a), nama: a.nama_anggota || a.username };
        }
        case 'eventGet': {
            const row = ((await supaFetch('event_config?select=*&order=updated_at.desc&limit=1')) || [])[0] || {};
            const out = {
                judul: row.judul || '', status: row.status || 'Segera Hadir', deskripsi: row.deskripsi || '', poster: row.poster || '', drive: row.drive || '',
                tombol_info: Array.isArray(row.tombol_info) ? row.tombol_info : [], form_lomba: Array.isArray(row.form_lomba) ? row.form_lomba : [],
                updated_at: row.updated_at || null, admin: false
            };
            const adm = eventBacaToken(payload.token);
            if (adm) { out.admin = true; out.admin_nama = adm.nama; out.spreadsheet = row.spreadsheet || ''; } // link spreadsheet hanya untuk OSIS
            return out;
        }
        case 'eventSimpan': {
            const adm = eventBacaToken(payload.token);
            if (!adm) throw new Error('Sesi OSIS berakhir. Silakan login ulang.');
            await eventOsisValid(adm.id);
            const d = payload.data || {};
            const tombol = (Array.isArray(d.tombol_info) ? d.tombol_info : []).slice(0, 20).map(t => ({
                icon: EVENT_ICON.includes(t && t.icon) ? t.icon : 'fas fa-link', label: eventTeks(t && t.label, 60), url: eventUrl(t && t.url)
            })).filter(t => t.label && t.url);
            const lomba = (Array.isArray(d.form_lomba) ? d.form_lomba : []).slice(0, 40).map(t => ({ nama: eventTeks(t && t.nama, 80), url: eventUrl(t && t.url) })).filter(t => t.nama && t.url);
            const body = {
                judul: eventTeks(d.judul, 120), status: EVENT_STATUS.includes(d.status) ? d.status : 'Segera Hadir', deskripsi: eventTeks(d.deskripsi, 3000),
                poster: eventUrl(d.poster) || null, spreadsheet: eventUrl(d.spreadsheet) || null, drive: eventUrl(d.drive) || null,
                tombol_info: tombol, form_lomba: lomba, updated_by: adm.nama, updated_at: new Date().toISOString()
            };
            if (!body.judul) throw new Error('Judul event wajib diisi.');
            const ada = ((await supaFetch('event_config?select=id&order=updated_at.desc&limit=1')) || [])[0];
            if (ada) await supaFetch(`event_config?id=eq.${ada.id}`, 'PATCH', body); else await supaFetch('event_config', 'POST', body);
            await eventCatatLog(adm.nama, 'OSIS El Point', 'Simpan Pengaturan Event', `Event "${body.judul}" (${body.status}) — ${tombol.length} tombol, ${lomba.length} formulir lomba`);
            return { spreadsheet: body.spreadsheet || '', tombol: tombol.length, lomba: lomba.length };
        }
        default: throw new Error('Aksi event tidak dikenali: ' + action);
    }
}

// =====================================================================
// GRISVA — GATEWAY ASISTEN VIRTUAL (login & izin diverifikasi di SERVER)
// Alur:  grisVA.js --(type:'grisva')--> notify.js
//   cari / semester / publik : tanpa token (data aman saja)
//   login                    : verifikasi kredensial di server -> token bertanda tangan (HMAC)
//   call                     : token + permintaan -> dicek role & izin -> diteruskan ke handler lama
// Butuh Environment Variable Netlify:  GRISVA_SECRET  (string acak panjang)
//   (jika belum ada, dipakai SUPABASE_SERVICE_ROLE_KEY yang sudah terisi untuk fitur backup)
// =====================================================================
const GV_ROLE = { siswa: 'Siswa', ortu: 'Orang Tua', guru: 'Guru', elpoint: 'OSIS El Point', admin: 'Admin' };
const GV_ID = /^[\w-]{1,64}$/;
const GV_RAHASIA = ['security_answer', 'password_hash', 'password', 'plain_password'];
const GV_TM = ['laporan', 'masukan', 'konselor'];
const GV_FIX = { alfa_mapel: 10, melanggar_peraturan: 6, makan_di_kelas: 6, mengganggu_pembelajaran: 6 };

function gvSecret() {
    const s = process.env.GRISVA_SECRET || SERVICE_KEY;
    if (!s) throw new Error('GRISVA_SECRET belum diset di Environment Variables Netlify.');
    return 'grisva::' + s;
}
function gvBuatToken(p) {
    const b = Buffer.from(JSON.stringify(Object.assign({ exp: Date.now() + 3 * 3600 * 1000 }, p))).toString('base64url');
    return b + '.' + nodeCrypto.createHmac('sha256', gvSecret()).update(b).digest('base64url');
}
function gvBacaToken(t) {
    const sec = gvSecret();
    try {
        const [b, sig] = String(t || '').split('.'); if (!b || !sig) return null;
        const ok = nodeCrypto.createHmac('sha256', sec).update(b).digest('base64url');
        const x = Buffer.from(sig), y = Buffer.from(ok);
        if (x.length !== y.length || !nodeCrypto.timingSafeEqual(x, y)) return null;
        const d = JSON.parse(Buffer.from(b, 'base64url').toString('utf8'));
        return d.exp > Date.now() ? d : null;
    } catch (e) { return null; }
}
const gvNeed = (ok, msg) => { if (!ok) throw new Error(msg || 'Aksi ini tidak diizinkan untuk mode kamu.'); };
const gvId = v => { v = String(v == null ? '' : v); gvNeed(GV_ID.test(v), 'Data tidak valid.'); return v; };
const gvPick = (o, keys) => { const r = {}; keys.forEach(k => { if (o && o[k] !== undefined) r[k] = o[k]; }); return r; };
const gvEnc = encodeURIComponent;

// hapus data rahasia dari semua respons yang keluar lewat GrisVA
function gvBersih(x, mode) {
    if (Array.isArray(x)) return x.map(i => gvBersih(i, mode));
    if (x && typeof x === 'object') {
        const o = {};
        for (const k of Object.keys(x)) {
            if (GV_RAHASIA.includes(k)) continue;
            if (k === 'login_pin') { o.punya_pin = !!(x[k] && String(x[k]).trim()); continue; }
            if (mode !== 'admin' && (k === 'tgl_lahir' || k === 'nik')) continue;
            o[k] = gvBersih(x[k], mode);
        }
        return o;
    }
    return x;
}
async function gvLimit(key, fn) {
    const w = await eventSisaBlokir(key);
    if (w) throw new Error(`Terlalu banyak percobaan salah. Coba lagi ${w} menit lagi.`);
    try { const r = await fn(); await eventResetSalah(key); return r; }
    catch (e) { await eventCatatSalah(key); throw e; }
}
async function gvHashOk(plain, hash) {
    hash = hash || '';
    if (bcrypt) return await bcrypt.compare(String(plain || ''), hash);
    return nodeCrypto.createHash('sha256').update(String(plain || '')).digest('hex') === hash;
}
async function gvHash(plain) { return bcrypt ? await bcrypt.hash(plain, 10) : nodeCrypto.createHash('sha256').update(plain).digest('hex'); }

// ---------- tanpa token ----------
async function gvPublik(p) {
    if (p.what === 'kontak') {
        const s = await supaFetch('pengaturan?select=nama_pengaturan,nilai&nama_pengaturan=in.(wa_ea,wa_guru,wa_dev,wa_ea_label,wa_guru_label,wa_dev_label)');
        const g = k => ((s || []).find(x => x.nama_pengaturan === k) || {}).nilai || '';
        return [['wa_ea', 'wa_ea_label', 'WA E-Aspirasi'], ['wa_guru', 'wa_guru_label', 'WA Admin/Guru'], ['wa_dev', 'wa_dev_label', 'WA IT / Dev']]
            .filter(r => g(r[0])).map(r => ({ label: g(r[1]) || r[2], nomor: String(g(r[0])).replace(/[^0-9]/g, '') }));
    }
    if (p.what === 'pengumuman') return (await supaFetch('pengumuman?select=judul,isi,tipe,created_at&order=created_at.desc&limit=5')) || [];
    throw new Error('Permintaan tidak dikenali.');
}
async function gvCari(p) {
    const mode = p.mode, q = String(p.q || '').trim();
    if (q.length < 2) return [];
    if (mode === 'siswa' || mode === 'ortu') return await eventHandler('eventCariSiswa', { nama: q });
    if (mode === 'guru') return (await supaFetch(`master_guru?nama_lengkap=ilike.${gvEnc('%' + q + '%')}&status=not.in.(Nonaktif,Diblokir)&select=id,nama_lengkap,nip&limit=10`)) || [];
    throw new Error('Mode tidak valid.');
}

// ---------- login: verifikasi di server, keluarkan token ----------
async function gvLogin(p) {
    const mode = p.mode;
    if (mode === 'siswa' || mode === 'ortu') {
        const id = gvId(p.id);
        return await gvLimit('gv_fail_' + id.replace(/[^\w]/g, ''), async () => {
            const r = (await supaFetch(`siswa?id=eq.${gvEnc(id)}&select=*&limit=1`))[0];
            if (!r || r.arsip) throw new Error('Data siswa tidak ditemukan.');
            if (mode === 'siswa' && r.status === 'Diblokir') throw new Error('Akses akun kamu sedang diblokir. Hubungi admin sekolah.');
            const punya = !!(r.login_pin && String(r.login_pin).trim());
            if (mode === 'siswa' && punya) {
                if (String(p.pin || '').trim() !== String(r.login_pin).trim()) throw new Error('PIN salah.');
            } else {
                const a = eventNormTgl(p.tgl), b = eventNormTgl(r.tgl_lahir);
                if (!a || a !== b) throw new Error('Tanggal lahir tidak cocok dengan data sekolah.');
            }
            if (p.cek) return { ok: true };
            if (mode === 'siswa' && !punya) {
                const pn = String(p.pinBaru || ''), jw = String(p.jawaban || '').trim().toLowerCase();
                if (!/^\d{4}$/.test(pn)) throw new Error('PIN harus 4 digit angka.');
                if (jw.length < 2) throw new Error('Jawaban keamanan wajib diisi.');
                await supaFetch(`siswa?id=eq.${gvEnc(id)}`, 'PATCH', { login_pin: pn, security_answer: jw });
            }
            await eventCatatLog(r.nama, GV_ROLE[mode], 'Login GrisVA', `Masuk asisten. Kelas: ${r.kelas || '-'}`);
            return {
                token: gvBuatToken({ m: mode, id: r.id, nm: r.nama, kl: r.kelas || '' }),
                user: { id: r.id, nama: r.nama, nama_panggilan: r.nama_panggilan || '', kelas: r.kelas || '', nisn: r.nisn || '', no_telp: r.no_telp || '', total_poin: r.total_poin || 0, status: r.status || 'Aktif', punya_pin: true }
            };
        });
    }
    if (mode === 'guru') {
        const id = gvId(p.id);
        return await gvLimit('gv_fail_g' + id.replace(/[^\w]/g, ''), async () => {
            const g = (await supaFetch(`master_guru?id=eq.${gvEnc(id)}&select=id,nama_lengkap,nama_panggilan,nip,no_wa,kelas_diajarkan,mapel,status,password_hash&limit=1`))[0];
            if (!g || ['Nonaktif', 'Diblokir'].includes(g.status)) throw new Error('Akun guru tidak ditemukan atau tidak aktif.');
            if (!(await gvHashOk(p.password, g.password_hash))) throw new Error('Password salah.');
            if (p.cek) return { ok: true };
            const sem = String(p.semester || '').slice(0, 60);
            await eventCatatLog(g.nama_lengkap, 'Guru', 'Login GrisVA', 'Masuk asisten');
            const user = Object.assign({}, g); delete user.password_hash;
            return { token: gvBuatToken({ m: 'guru', id: g.id, nm: g.nama_lengkap, sm: sem }), user, semester: sem };
        });
    }
    if (mode === 'elpoint') {
        const u = String(p.username || '').trim().toLowerCase(), pw = String(p.password || '');
        if (!u || !pw) throw new Error('Username dan password wajib diisi.');
        return await gvLimit('gv_fail_e' + u.replace(/[^\w]/g, '').slice(0, 40), async () => {
            const rows = (await supaFetch(`anggota_elpoint?select=*&username=ilike.${gvEnc(u)}&limit=10`)) || [];
            const a = rows.find(x => String(x.username || '').trim().toLowerCase() === u);
            if (!a || String(a.password || '') !== pw) throw new Error('Username atau password salah.');
            if (a.status === 'Diblokir') throw new Error('Akun kamu sedang diblokir.');
            const sub = String(a.role || '').toLowerCase().includes('sekretaris') ? 'sekretaris' : 'osis';
            await eventCatatLog(a.nama_anggota || a.username, 'OSIS El Point', 'Login GrisVA', `Masuk asisten (${sub})`);
            return {
                token: gvBuatToken({ m: 'elpoint', id: a.id, nm: a.nama_anggota || a.username, sb: sub }),
                user: { id: a.id, nama_anggota: a.nama_anggota, username: a.username, role: a.role }, sub
            };
        });
    }
    if (mode === 'admin') {
        const jwt = String(p.jwt || '');
        const r = await fetch(SUPABASE_URL + '/auth/v1/user', { headers: { apikey: SUPABASE_KEY, Authorization: 'Bearer ' + jwt } });
        if (!r.ok) throw new Error('Sesi Supabase tidak valid. Login ulang.');
        const u = await r.json();
        const ad = (await supaFetch(`hak_akses_admin?select=*&email_admin=eq.${gvEnc(u.email)}&limit=1`))[0];
        if (!ad) throw new Error('Akun terotentikasi, tapi tidak terdaftar sebagai admin.');
        if (ad.status === 'Diblokir') throw new Error('Akun admin ini sedang diblokir.');
        const roles = (await supaFetch('daftar_role?select=*')) || [];
        const my = roles.find(x => x.nama_role === ad.role_name);
        let ak = []; try { ak = JSON.parse((my && my.akses_fitur) || '[]'); } catch (e) {}
        const sa = ad.role_name === 'Super Admin'; if (sa) ak.push('Super Admin');
        await eventCatatLog(ad.nama_lengkap || ad.email_admin, 'Admin', 'Login GrisVA', `Masuk asisten (${ad.role_name})`);
        return {
            token: gvBuatToken({ m: 'admin', id: ad.id, nm: ad.nama_lengkap || ad.email_admin, ak, sa }), akses: ak,
            user: { id: ad.id, email_admin: ad.email_admin, nama_lengkap: ad.nama_lengkap, role_name: ad.role_name, no_telp: ad.no_telp, agama: ad.agama }
        };
    }
    throw new Error('Mode tidak valid.');
}

// ---------- aksi khusus akun sendiri (tanpa membocorkan PIN/jawaban/hash) ----------
async function gvSelf(ctx, action, p) {
    p = p || {};
    if (action === 'me') {
        gvNeed(['siswa', 'ortu'].includes(ctx.m));
        const r = (await supaFetch(`siswa?id=eq.${gvEnc(ctx.id)}&select=*&limit=1`))[0];
        gvNeed(r, 'Data siswa tidak ditemukan.');
        return gvBersih(r, 'siswa');
    }
    if (action === 'ubahPin') {
        gvNeed(ctx.m === 'siswa');
        const r = (await supaFetch(`siswa?id=eq.${gvEnc(ctx.id)}&select=security_answer&limit=1`))[0];
        const ans = String((r && r.security_answer) || '').trim().toLowerCase();
        if (p.op === 'status') return { ada: !!ans };
        if (!ans) throw new Error('Akunmu belum punya jawaban keamanan. Hubungi admin untuk reset PIN.');
        await gvLimit('gv_fail_pin' + String(ctx.id).replace(/[^\w]/g, ''), async () => {
            if (String(p.jawaban || '').trim().toLowerCase() !== ans) throw new Error('Jawaban salah.');
        });
        if (p.op === 'cek') return { ok: true };
        gvNeed(/^\d{4}$/.test(String(p.pinBaru || '')), 'PIN harus 4 digit angka.');
        await supaFetch(`siswa?id=eq.${gvEnc(ctx.id)}`, 'PATCH', { login_pin: String(p.pinBaru) });
        return { ok: true };
    }
    if (action === 'guruPassword') {
        gvNeed(ctx.m === 'guru');
        const g = (await supaFetch(`master_guru?id=eq.${gvEnc(ctx.id)}&select=password_hash&limit=1`))[0];
        await gvLimit('gv_fail_gp' + String(ctx.id).replace(/[^\w]/g, ''), async () => {
            if (!g || !(await gvHashOk(p.lama, g.password_hash))) throw new Error('Password lama salah.');
        });
        if (p.op === 'cek') return { ok: true };
        gvNeed(String(p.baru || '').length >= 6, 'Password baru minimal 6 karakter.');
        await supaFetch(`master_guru?id=eq.${gvEnc(ctx.id)}`, 'PATCH', { password_hash: await gvHash(String(p.baru)) });
        return { ok: true };
    }
    throw new Error('Aksi tidak dikenali.');
}

// ---------- izin per role ----------
function gvAdminCan(ctx, menu, izin) {
    if (ctx.sa) return true;
    const ak = ctx.ak || [];
    if (menu && ![].concat(menu).some(x => ak.includes(x))) return false;
    if (izin && ak.includes('aksi_v2') && !ak.includes(izin)) return false;
    return true;
}
async function gvGuruKelas(ctx) {
    const g = (await supaFetch(`master_guru?id=eq.${gvEnc(ctx.id)}&select=kelas_diajarkan&limit=1`))[0];
    return _arrS(g && g.kelas_diajarkan);
}
async function gvPoinMaster(id) {
    const mp = (await supaFetch(`master_pelanggaran?id=eq.${gvId(id)}&select=point,kelakuan&limit=1`))[0];
    gvNeed(mp, 'Pelanggaran tidak ditemukan.');
    return mp;
}

async function gvPolicy(ctx, c) {
    const m = ctx.m, t = c.type, a = c.action, key = t + ':' + a;
    const p = Object.assign({}, c.payload || {});
    const out = { type: t, action: a, payload: p };
    const tb = String(p.table || '');
    const tolak = () => { throw new Error('Aksi tidak diizinkan untuk mode ' + GV_ROLE[m] + '.'); };

    if (t === 'upload') { gvNeed(['siswa', 'guru', 'elpoint'].includes(m)); const s = String(c.source || ''); gvNeed(s.length > 0 && s.length < 4e6, 'Foto terlalu besar.'); return { type: 'upload', source: s }; }
    if (t === 'notification') { gvNeed(m === 'siswa'); return { type: 'notification', text: String(c.text || '').slice(0, 1500) }; }
    if (key === 'supabase_post:insertLog') return { type: t, action: a, payload: { aktor: ctx.nm, role: GV_ROLE[m], aksi: String(p.aksi || '').slice(0, 80), detail: String(p.detail || '').slice(0, 500) } };
    if (key === 'supabase_get:getAllPelanggaran') return out;
    if (key === 'supabase_get:getData' && /^pengumuman\?/.test(tb)) return out;
    if (key === 'supabase_get:cariSiswa' && !['siswa', 'ortu'].includes(m)) return out;

    switch (m) {
        case 'siswa':
            switch (key) {
                case 'supabase_get:getRiwayat': p.nama = ctx.nm; return out;
                case 'supabase_post:getBalasan': {
                    const r = (await supaFetch(`aspirasi?id=eq.${gvId(p.id_aspirasi)}&select=pengirim_nama&limit=1`))[0];
                    gvNeed(r && r.pengirim_nama === ctx.nm, 'Tiket tidak ditemukan.'); return out;
                }
                case 'supabase_post:statusAbsenManual': out.payload = { id_siswa: ctx.id }; return out;
                case 'supabase_post:absenManual':
                    gvNeed(['Hadir', 'Sakit', 'Izin'].includes(p.jenis));
                    p.id_siswa = ctx.id; out.payload = gvPick(p, ['id_siswa', 'jenis', 'lampiran', 'lat', 'lon']); return out;
                case 'supabase_post:insertAspirasi':
                    gvNeed(['Laporan', 'Masukan', 'Konselor'].includes(p.tipe));
                    out.payload = Object.assign(gvPick(p, ['tipe', 'pengirim_wa', 'tanggal_kejadian', 'kategori', 'detail_pesan', 'lampiran']), { pengirim_nama: ctx.nm, pengirim_kelas: ctx.kl });
                    return out;
                case 'supabase_post:updateData': {
                    gvNeed(p.table === 'siswa' && String(p.id) === String(ctx.id));
                    const pl = gvPick(p.payload, ['nama_panggilan', 'no_telp', 'nisn']); gvNeed(Object.keys(pl).length > 0);
                    out.payload = { table: 'siswa', id: ctx.id, payload: pl }; return out;
                }
                case 'supabase_post:insertData': {
                    gvNeed(p.table === 'penilaian');
                    const pl = p.payload || {}, b = parseInt(pl.bintang, 10); gvNeed(b >= 1 && b <= 5, 'Bintang tidak valid.');
                    out.payload = { table: 'penilaian', payload: { nama_penilai: ctx.nm, kelas_penilai: ctx.kl, bintang: b, komentar: String(pl.komentar || '').slice(0, 300) || null, created_at: new Date().toISOString() } };
                    return out;
                }
            }
            break;
        case 'ortu':
            if (key === 'supabase_get:getData') {
                if (tb.startsWith('absensi?id_siswa=eq.' + ctx.id + '&')) return out;
                if (tb.startsWith('log_history?aksi=eq.' + gvEnc('Lapor Poin Pelanggaran') + '&detail=ilike.') && decodeURIComponent(tb).includes(ctx.nm)) return out;
            }
            break;
        case 'guru':
            switch (key) {
                case 'supabase_post:cekJadwalGuru': p.id_guru = ctx.id; return out;
                case 'supabase_post:salinCatatanAbsen':
                    p.id_guru = ctx.id; gvNeed((await gvGuruKelas(ctx)).includes(p.kelas), 'Kelas tidak termasuk kelas yang Anda ajar.'); return out;
                case 'supabase_get:getData': {
                    const mm = tb.match(/^siswa\?kelas=eq\.([^&]+)&status=not\.in\.\(Diblokir,Alumni\)&select=id,nama(&|$)/);
                    if (mm) { gvNeed((await gvGuruKelas(ctx)).includes(decodeURIComponent(mm[1])), 'Kelas tidak termasuk kelas yang Anda ajar.'); return out; }
                    if (/^absensi\?id_siswa=in\.\([\w,-]+\)&created_at=gte\./.test(tb)) return out;
                    if (/^master_semester\?/.test(tb) || /^master_mapel\?aktif=eq\.true/.test(tb)) return out;
                    if (tb.startsWith('laporan_guru?id_guru=eq.' + ctx.id + '&')) return out;
                    break;
                }
                case 'supabase_post:insertLaporanGuru': {
                    const lp = (p.payload && typeof p.payload === 'object') ? p.payload : p;
                    let poin, label = String(lp.label_jenis || '').slice(0, 120), idm = null;
                    if (lp.id_pelanggaran_master) { idm = gvId(lp.id_pelanggaran_master); const mp = await gvPoinMaster(idm); poin = parseInt(mp.point); label = mp.kelakuan; }
                    else { gvNeed(GV_FIX[lp.jenis] != null, 'Jenis pelanggaran tidak valid.'); poin = GV_FIX[lp.jenis]; }
                    const sw = (await supaFetch(`siswa?id=eq.${gvId(lp.id_siswa)}&select=id,nama,kelas&limit=1`))[0]; gvNeed(sw, 'Siswa tidak ditemukan.');
                    out.payload = { payload: { id_guru: ctx.id, nama_guru: ctx.nm, id_siswa: sw.id, nama_siswa: sw.nama, kelas_siswa: sw.kelas || '', mata_pelajaran: String(lp.mata_pelajaran || '').slice(0, 100), tanggal: lp.tanggal, jam: lp.jam, jenis: lp.jenis, label_jenis: label, keterangan: String(lp.keterangan || '').slice(0, 500), poin_siswa: poin, id_pelanggaran_master: idm, semester: ctx.sm || '', status: 'Selesai' } };
                    return out;
                }
                case 'supabase_post:batalLaporanGuru': {
                    const id = gvId(p.id); const r = (await supaFetch(`laporan_guru?id=eq.${id}&select=id_guru,id_siswa,poin_siswa,status&limit=1`))[0];
                    gvNeed(r && String(r.id_guru) === String(ctx.id) && r.status !== 'Dibatalkan', 'Laporan tidak ditemukan.');
                    out.payload = { id, alasan_batal: String(p.alasan_batal || '').slice(0, 300), id_siswa: r.id_siswa, poin_siswa: r.poin_siswa }; return out;
                }
                case 'supabase_post:insertData':
                    gvNeed(p.table === 'aspirasi'); { const pl = p.payload || {};
                    out.payload = { table: 'aspirasi', payload: { tipe: 'Masukan', sub_tipe: 'masukan_guru', pengirim_nama: ctx.nm, pengirim_wa: String(pl.pengirim_wa || '').slice(0, 20), kategori: String(pl.kategori || '').slice(0, 60), detail_pesan: String(pl.detail_pesan || '').slice(0, 2000), status: 'Menunggu' } }; }
                    return out;
            }
            break;
        case 'elpoint':
            switch (key) {
                case 'supabase_post:statusAbsenManual': out.payload = {}; return out;
                case 'supabase_post:absenMassal': {
                    const ids = Array.isArray(p.ids) ? p.ids : []; gvNeed(ids.length > 0 && ids.length <= 100, 'Pilih 1–100 siswa.'); ids.forEach(gvId);
                    gvNeed(['Hadir', 'Sakit', 'Izin', 'Alpa'].includes(p.jenis));
                    gvNeed(!['Sakit', 'Izin'].includes(p.jenis) || String(p.lampiran || '').trim(), 'Foto surat wajib untuk Sakit/Izin.');
                    out.payload = { ids, jenis: p.jenis, isLate: !!p.isLate, lampiran: String(p.lampiran || ''), aktor: ctx.nm }; return out;
                }
                case 'supabase_post:tambahPoinSiswa': {
                    gvNeed(ctx.sb === 'osis', 'Tambah poin hanya untuk petugas OSIS.');
                    const mp = await gvPoinMaster(p.id_pelanggaran);
                    out.payload = { id_siswa: gvId(p.id_siswa), id_pelanggaran: p.id_pelanggaran, poin: parseInt(mp.point), aktor: ctx.nm }; return out;
                }
            }
            break;
        case 'admin': {
            const can = (menu, izin) => gvAdminCan(ctx, menu, izin);
            switch (key) {
                case 'supabase_get:getAllAspirasi': case 'supabase_get:getAllSiswa': case 'supabase_get:getAllKelas': return out;
                case 'supabase_get:getAllGuru': gvNeed(can('guru')); return out;
                case 'supabase_get:getLogHistory': gvNeed(can('history')); return out;
                case 'supabase_get:getData':
                    if (/^siswa\?id=eq\.[\w-]+&select=\*&limit=1$/.test(tb)) { gvNeed(can(['siswa', 'poin'])); return out; }
                    if (/^master_semester\?/.test(tb)) { gvNeed(can('semester')); return out; }
                    if (/^manajemen_hari\?tanggal=eq\.\d{4}-\d{2}-\d{2}&/.test(tb)) { gvNeed(can('hari')); return out; }
                    break;
                case 'supabase_post:getBalasan': gvNeed(can(GV_TM)); gvId(p.id_aspirasi); return out;
                case 'supabase_post:insertBalasan': {
                    gvNeed(can(GV_TM, 'aksi_balas')); const id = gvId(p.id_aspirasi);
                    const r = (await supaFetch(`aspirasi?id=eq.${id}&select=pengirim_wa&limit=1`))[0]; gvNeed(r, 'Tiket tidak ditemukan.');
                    out.payload = { id_aspirasi: id, isi_balasan: String(p.isi_balasan || '').slice(0, 2000), nama_admin: ctx.nm, pengirim_wa: r.pengirim_wa || '' }; return out;
                }
                case 'supabase_post:updateAspirasiStatus':
                    gvNeed(can(GV_TM, 'aksi_balas')); gvNeed(['Menunggu', 'Sedang Diproses', 'Selesai', 'Ditolak'].includes(p.status));
                    out.payload = { id: gvId(p.id), status: p.status }; return out;
                case 'supabase_post:updateData': {
                    const id = gvId(p.id), pl = p.payload || {}, ks = Object.keys(pl);
                    if (p.table === 'siswa') {
                        if (ks.length === 1 && ks[0] === 'status') { gvNeed(can('siswa', 'aksi_blokir') && ['Aktif', 'Diblokir'].includes(pl.status)); }
                        else if (ks.length === 2 && ks.includes('login_pin') && ks.includes('security_answer') && pl.login_pin === null && pl.security_answer === null) { gvNeed(can('siswa', 'aksi_reset_pin')); }
                        else if (ks.length === 1 && ks[0] === 'total_poin') {
                            const n = parseInt(pl.total_poin, 10); gvNeed(n >= 0 && n <= 999, 'Poin harus 0–999.');
                            gvNeed(can('poin', 'aksi_edit') || (n === 0 && can('poin', 'aksi_reset_data'))); pl.total_poin = n;
                        } else tolak();
                    } else if (p.table === 'master_guru') {
                        gvNeed(ks.length === 1 && ks[0] === 'status' && ['Aktif', 'Diblokir'].includes(pl.status) && can('guru', 'aksi_blokir'));
                    } else if (p.table === 'manajemen_hari') {
                        gvNeed(ks.every(k => ['status', 'keterangan', 'status_manual', 'updated_at'].includes(k)) && can('hari', 'aksi_sistem'));
                    } else tolak();
                    out.payload = { table: p.table, id, payload: pl }; return out;
                }
                case 'supabase_post:insertData': {
                    const pl = p.payload || {};
                    if (p.table === 'siswa') { gvNeed(can('siswa', 'aksi_tambah')); out.payload = { table: 'siswa', payload: Object.assign(gvPick(pl, ['nama', 'kelas', 'nisn', 'tgl_lahir', 'no_telp']), { status: 'Aktif', total_poin: 0 }) }; }
                    else if (p.table === 'pengumuman') { gvNeed(can('pengumuman', 'aksi_tambah')); out.payload = { table: 'pengumuman', payload: { judul: String(pl.judul || '').slice(0, 120), isi: String(pl.isi || '').slice(0, 3000), tipe: ['info', 'penting', 'darurat'].includes(pl.tipe) ? pl.tipe : 'info', pembuat: ctx.nm, created_at: new Date().toISOString() } }; }
                    else if (p.table === 'api_keys') { gvNeed(can('apikey', 'aksi_tambah')); out.payload = { table: 'api_keys', payload: { nama_aplikasi: String(pl.nama_aplikasi || '').slice(0, 60), api_key : '{{ env('API_KEY') }}' + nodeCrypto.randomBytes(6).toString('hex').toUpperCase() } }; }
                    else if (p.table === 'manajemen_hari') { gvNeed(can('hari', 'aksi_sistem')); out.payload = { table: 'manajemen_hari', payload: gvPick(pl, ['tanggal', 'hari', 'status', 'keterangan', 'status_manual', 'updated_at']) }; }
                    else tolak();
                    return out;
                }
                case 'supabase_post:tambahPoinSiswa': {
                    gvNeed(can('poin', 'aksi_edit')); const mp = await gvPoinMaster(p.id_pelanggaran);
                    out.payload = { id_siswa: gvId(p.id_siswa), id_pelanggaran: p.id_pelanggaran, poin: parseInt(mp.point), aktor: ctx.nm }; return out;
                }
                case 'supabase_post:setSemesterAktif': gvNeed(can('semester', 'aksi_sistem')); out.payload = { id: gvId(p.id), label: String(p.label || '').slice(0, 60) }; return out;
                case 'supabase_post:syncLibur': gvNeed(can('hari', 'aksi_sistem')); out.payload = gvPick(p, ['token', 'tahun']); return out;
                case 'supabase_post:backupDatabase': gvNeed(ctx.sa, 'Backup hanya untuk Super Admin.'); out.payload = { token: p.token, mode: 'storage' }; return out;
                case 'supabase_post:checkReminder': gvNeed(can('settings', 'aksi_sistem')); out.payload = { hari: 3 }; return out;
                case 'supabase_post:laporanBerkala': gvNeed(can('settings', 'aksi_sistem')); out.payload = {}; return out;
                case 'supabase_post:kirimPengumumanWA':
                    gvNeed(can('pengumuman', 'aksi_tambah'));
                    out.payload = { judul: String(p.judul || '').slice(0, 120), isi: String(p.isi || '').slice(0, 3000), tipe: ['info', 'penting', 'darurat'].includes(p.tipe) ? p.tipe : 'info' }; return out;
            }
            break;
        }
    }
    return tolak();
}

async function gvCall(token, call) {
    const ctx = gvBacaToken(token);
    if (!ctx) throw new Error('SESI: Sesi berakhir, silakan login ulang.');
    if (!call || typeof call !== 'object') throw new Error('Permintaan tidak valid.');
    if (call.type === 'grisva') {
        const d = await gvSelf(ctx, call.action, call.payload);
        return { statusCode: 200, headers, body: JSON.stringify({ success: true, data: d }) };
    }
    const body = await gvPolicy(ctx, call);
    const res = await exports.handler({ httpMethod: 'POST', body: JSON.stringify(body) });
    if (res.statusCode >= 400) return res;
    let j; try { j = JSON.parse(res.body); } catch (e) { return res; }
    return { statusCode: 200, headers, body: JSON.stringify(gvBersih(j, ctx.m)) };
}

async function grisvaHandler(action, body) {
    const ok = d => ({ statusCode: 200, headers, body: JSON.stringify({ success: true, data: d }) });
    const p = body.payload || {};
    if (action === 'publik') return ok(await gvPublik(p));
    if (action === 'cari') return ok(await gvCari(p));
    if (action === 'semester') return ok((await supaFetch('master_semester?select=label,aktif&order=urutan.desc')) || []);
    if (action === 'login') { gvSecret(); return ok(await gvLogin(p)); }
    if (action === 'call') return await gvCall(body.token, body.call);
    throw new Error('Aksi GrisVA tidak dikenali: ' + action);
}


exports.handler = async (event) => {
    // Tangani preflight browser
    if (event.httpMethod === 'OPTIONS') return { statusCode: 200, headers, body: 'OK' };

    try {
        const body = event.body ? JSON.parse(event.body) : {};
        const { type, action, payload } = body;

        // ================= GRISVA (asisten virtual) =================
        if (type === 'grisva') return await grisvaHandler(action, body);

        // ================= A. LAYANAN UPLOAD GAMBAR (FREEIMAGE) =================
        if (type === 'upload') {
            try {
                let freeImageKey = await getSetting('freeimage_key') || '6d207e02198a847aa98d0a2a901485a5';
                
                // Strip base64 prefix (data:image/jpeg;base64,XXX → XXX)
                let sourceData = (body.source || '').trim();
                if (sourceData.startsWith('data:')) {
                    sourceData = sourceData.split(',')[1] || sourceData;
                }
                if (!sourceData) throw new Error('Tidak ada data gambar');

                // ── COBA FREEIMAGE dulu ──────────────────────────────────────
                let uploadUrl = null;
                try {
                    // URLSearchParams = application/x-www-form-urlencoded (works in Node.js)
                    const params = new URLSearchParams();
                    params.append('key', freeImageKey);
                    params.append('action', 'upload');
                    params.append('source', sourceData);
                    params.append('format', 'json');

                    const fiRes = await fetch('https://freeimage.host/api/1/upload', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: params.toString(),
                        signal: AbortSignal.timeout(15000) // 15 second timeout
                    });

                    const fiText = await fiRes.text();
                    if (!fiText.trim().startsWith('<')) {
                        const fiData = JSON.parse(fiText);
                        if (fiData.status_code === 200 && fiData.status_txt === 'OK') {
                            uploadUrl = fiData.image?.url || fiData.image?.display_url || null;
                        }
                    }
                } catch (fiErr) {
                    console.log('FreeImage failed, trying imgbb:', fiErr.message);
                }

                // ── FALLBACK: IMGBB ──────────────────────────────────────────
                if (!uploadUrl) {
                    try {
                        const imgbbKey = await getSetting('imgbb_key') || '8d1e927c60294f497e62cdb5b49fa3b1';
                        const ibParams = new URLSearchParams();
                        ibParams.append('key', imgbbKey);
                        ibParams.append('image', sourceData);

                        const ibRes = await fetch('https://api.imgbb.com/1/upload', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: ibParams.toString(),
                            signal: AbortSignal.timeout(15000)
                        });

                        const ibText = await ibRes.text();
                        if (!ibText.trim().startsWith('<')) {
                            const ibData = JSON.parse(ibText);
                            if (ibData.success) {
                                uploadUrl = ibData.data?.url || ibData.data?.display_url || null;
                            }
                        }
                    } catch (ibErr) {
                        console.log('imgbb also failed:', ibErr.message);
                    }
                }

                if (!uploadUrl) throw new Error('Semua layanan upload gagal. Coba lagi atau periksa koneksi.');

                return { statusCode: 200, headers, body: JSON.stringify({ url: uploadUrl, success: true }) };

            } catch (uploadErr) {
                console.error('Upload error:', uploadErr.message);
                return { statusCode: 500, headers, body: JSON.stringify({ error: uploadErr.message, url: null }) };
            }
        }


        // ================= B. LAYANAN NOTIFIKASI (TELEGRAM) =================
        if (type === 'notification') {
            let teleToken = await getSetting('tele_bot_token'); 
            let teleIds = await getSetting('tele_chat_ids');
            
            if(!teleToken || !teleIds) return { statusCode: 200, headers, body: JSON.stringify({ error: "Token Tele Kosong" }) };
            
            const chatIdsArray = teleIds.split(',').map(id => id.trim()).filter(id => id !== "");
            for (const id of chatIdsArray) { 
                await fetch(`https://api.telegram.org/bot${teleToken}/sendMessage`, { 
                    method: 'POST', 
                    headers: { 'Content-Type': 'application/json' }, 
                    body: JSON.stringify({ chat_id: id, text: body.text, parse_mode: 'HTML' }) 
                }); 
            }
            return { statusCode: 200, headers, body: JSON.stringify({ success: true }) };
        }

        // ================= C. API EKSTERNAL (SINKRONISASI GRISAPOINT) =================
        if (action === 'syncEksternal') {
            if(!payload || !payload.nisn || !payload.api_key) {
                return { statusCode: 400, headers, body: JSON.stringify({ error: "NISN dan API Key dibutuhkan" }) };
            }
            
            const keyCheck = await supaFetch(`api_keys?select=*&api_key=eq.${encodeURIComponent(payload.api_key)}&status=eq.Aktif`);
            if(!keyCheck || keyCheck.length === 0) {
                return { statusCode: 401, headers, body: JSON.stringify({ error: "API Key tidak valid atau telah dicabut" }) };
            }
            
            const data = await supaFetch(`siswa?select=nama,nisn,kelas,total_poin&nisn=eq.${encodeURIComponent(payload.nisn)}`);
            if(data && data.length > 0) return { statusCode: 200, headers, body: JSON.stringify({ success: true, data: data[0] }) };
            
            return { statusCode: 404, headers, body: JSON.stringify({ error: "Siswa tidak ditemukan" }) };
        }

        // ================= D. AKSI KHUSUS APLIKASI OSIS (EL POINT) =================
        if (action === 'loginElPoint') {
            const data = await supaFetch(`anggota_elpoint?select=*&username=eq.${encodeURIComponent(payload.username)}&password=eq.${encodeURIComponent(payload.password)}`);
            if(data && data.length > 0) return { statusCode: 200, headers, body: JSON.stringify({ success: true, data: data[0] }) };
            throw new Error("Username atau Password Salah!");
        }

        if (action === 'scanQR') {
            const data = await supaFetch(`siswa?select=*&nisn=eq.${encodeURIComponent(payload.nisn)}`);
            if(data && data.length > 0) return { statusCode: 200, headers, body: JSON.stringify({ success: true, data: data[0] }) };
            throw new Error("NISN tidak terdaftar di sistem!");
        }

        if (action === 'absenSiswa') {
            // Ambil nisn dan kelas dari DB jika tidak dikirim dari client
            let nisn  = payload.nisn  || '';
            let kelas = payload.kelas || '';
            if ((!nisn || !kelas) && payload.id) {
                const sData = await supaFetch(`siswa?select=nisn,kelas&id=eq.${payload.id}&limit=1`);
                if (sData && sData.length > 0) {
                    nisn  = nisn  || sData[0].nisn  || '';
                    kelas = kelas || sData[0].kelas || '';
                }
            }
            const data = await supaFetch('absensi', 'POST', { 
                id_siswa:   payload.id, 
                nama_siswa: payload.nama, 
                nisn,
                kelas,
                jenis:    payload.jenis, 
                is_late: payload.isLate || false, aktor: payload.aktor || 'Sistem', semester: (await getSetting('semester_aktif')) || null });
            return { statusCode: 200, headers, body: JSON.stringify({ success: true, data }) };
        }

        if (action === 'absenMassal') {
            // Sakit/Izin WAJIB melampirkan foto surat (divalidasi juga di server)
            const perluSurat = ['Sakit', 'Izin'].includes(payload.jenis);
            const lampiranMassal = (payload.lampiran || '').toString().trim();
            if (perluSurat && !lampiranMassal) throw new Error('Absen ' + payload.jenis + ' wajib melampirkan foto surat.');
            const semAktifMassal = (await getSetting('semester_aktif')) || null;
            for (let id of payload.ids) {
                const sData = await supaFetch(`siswa?select=nama,kelas,nisn&id=eq.${id}`);
                if (sData && sData.length > 0) {
                    await supaFetch('absensi', 'POST', {
                        id_siswa: id,
                        nama_siswa: sData[0].nama,
                        nisn: sData[0].nisn,
                        kelas: sData[0].kelas,
                        jenis: payload.jenis,
                        is_late: payload.isLate || false,
                        aktor: payload.aktor || 'Sistem',
                        lampiran: perluSurat ? lampiranMassal : '',
                        semester: semAktifMassal
                    });
                }
            }
            return { statusCode: 200, headers, body: JSON.stringify({ success: true }) };
        }

        if (action === 'tambahPoinQR') {
            const currSiswa = await supaFetch(`siswa?select=nama,total_poin&id=eq.${payload.siswa_id}`);
            if(!currSiswa || currSiswa.length === 0) throw new Error("Gagal mengambil data siswa.");
            
            const newPoin = (currSiswa[0].total_poin || 0) + parseInt(payload.poin_tambahan);
            await supaFetch(`siswa?id=eq.${payload.siswa_id}`, 'PATCH', { total_poin: newPoin });
            
            await supaFetch('log_history', 'POST', { 
                aktor: payload.aktor, 
                role: 'OSIS El Point', 
                aksi: 'Lapor Poin Pelanggaran', 
                detail: `+${payload.poin_tambahan} Poin untuk ${currSiswa[0].nama}. Alasan: ${payload.nama_pelanggaran}` 
            });
            return { statusCode: 200, headers, body: JSON.stringify({ success: true, new_poin: newPoin }) };
        }

        // ================= D2. GRISAEVENT / SETEVENT =================
        if (type === 'event') {
            try {
                const hasilEvent = await eventHandler(action, payload);
                return { statusCode: 200, headers, body: JSON.stringify({ success: true, data: hasilEvent }) };
            } catch (evErr) {
                const pesan = String(evErr.message || evErr);
                const ramah = /row-level security|42501|permission denied/i.test(pesan)
                    ? 'Database menolak akses ke tabel event_config. Pastikan event_setup.sql sudah dijalankan dan SUPABASE_SERVICE_ROLE_KEY terisi di Netlify.'
                    : (/event_config/i.test(pesan) && /does not exist|schema cache|relation/i.test(pesan) ? 'Tabel event_config belum dibuat. Jalankan event_setup.sql di Supabase SQL Editor.' : pesan);
                return { statusCode: 400, headers, body: JSON.stringify({ error: ramah }) };
            }
        }

        // ================= E. RUTIN TARIK DATA (GET) =================
        if (type === 'supabase_get') {
            let data;
            switch(action) {
                case 'getPengaturan': data = await supaFetch('pengaturan?select=*'); break;
                case 'getPengaturanByKey': 
                    data = await supaFetch(`pengaturan?nama_pengaturan=eq.${encodeURIComponent(payload.key)}&select=nilai`); 
                    break;
                case 'cariSiswa': {
                    const q = encodeURIComponent(payload.nama);
                    // Sertakan login_pin dan status (semua status termasuk Diblokir)
                    // agar frontend bisa deteksi PIN & status tanpa fetch tambahan
                    const r1 = await supaFetch(`siswa?select=id,nama,nama_panggilan,kelas,total_poin,nisn,tgl_lahir,status,login_pin&arsip=eq.false&nama=ilike.%25${q}%25&limit=15`);
                    let hasil = r1 || [];
                    if (hasil.length < 15) {
                        const r2 = await supaFetch(`siswa?select=id,nama,nama_panggilan,kelas,total_poin,nisn,tgl_lahir,status,login_pin&arsip=eq.false&nama_panggilan=ilike.%25${q}%25&limit=${15 - hasil.length}`);
                        if (r2 && r2.length > 0) {
                            const existIds = new Set(hasil.map(s => s.id));
                            hasil = [...hasil, ...r2.filter(s => !existIds.has(s.id))];
                        }
                    }
                    data = hasil;
                    break;
                }
                case 'cekSiswa': data = await supaFetch(`siswa?select=*&tgl_lahir=eq.${encodeURIComponent(payload.tgl)}`); break;
                case 'getRiwayat': data = await supaFetch(`aspirasi?select=*&pengirim_nama=eq.${encodeURIComponent(payload.nama)}&order=created_at.desc`); break;
                case 'getAllAspirasi': data = await supaFetch(`aspirasi?select=*&order=created_at.desc${rq(payload)}`); break;
                case 'getAllSiswa': data = await supaFetch(`siswa?select=*&arsip=eq.false&order=nama.asc`); break;
                case 'getAllAdmins': data = await supaFetch(`hak_akses_admin?select=*&order=created_at.desc`); break;
                case 'getCurrentAdmin': data = await supaFetch(`hak_akses_admin?select=*&email_admin=eq.${encodeURIComponent(payload.email)}`); break;
                case 'getAllRoles': data = await supaFetch(`daftar_role?select=*`); break;
                case 'getAllKelas': data = await supaFetch(`daftar_kelas?select=*&order=nama_kelas.asc`); break;
                case 'getAllElpoint': data = await supaFetch(`anggota_elpoint?select=*&order=nama_anggota.asc`); break;
                case 'getLogHistory': data = await supaFetch(`log_history?select=*&order=created_at.desc${rq(payload)}`); break;
                case 'getAllAbsensi': data = await supaFetch(`absensi?select=*&order=created_at.desc${rq(payload)}`); break;
                case 'getAllApiKeys': data = await supaFetch(`api_keys?select=*&order=created_at.desc`); break;
                case 'getAllPelanggaran': data = await supaFetch(`master_pelanggaran?select=*&order=point.asc`); break;
                case 'getAllGuru': data = await supaFetch('master_guru?select=id,nama_lengkap,nama_panggilan,nip,no_wa,kelas_diajarkan,mapel,status&order=nama_lengkap'); break;
                case 'cariGuru': {
                    const qg = encodeURIComponent(payload.nama || '');
                    data = await supaFetch(`master_guru?nama_lengkap=ilike.%25${qg}%25&status=not.in.(Nonaktif,Diblokir)&select=id,nama_lengkap,nama_panggilan,nip,no_wa,kelas_diajarkan,mapel,status&limit=10`);
                    break;
                }
                case 'getGuruById': data = await supaFetch(`master_guru?id=eq.${payload.id}&select=id,nama_lengkap,nama_panggilan,nip,no_wa,kelas_diajarkan,mapel,status&limit=1`); break;
                case 'loginGuru': data = await supaFetch(`master_guru?nama_lengkap=ilike.${encodeURIComponent('%' + (payload.nama || '') + '%')}&status=not.in.(Nonaktif,Diblokir)&select=id,nama_lengkap,nama_panggilan,nip,no_wa,kelas_diajarkan,mapel,status&limit=1`); break;
                case 'getLaporanGuru': {
                    const idGuru = payload.id_guru || '';
                    data = await supaFetch(`laporan_guru?id_guru=eq.${idGuru}&order=created_at.desc&select=*${payload.semester ? '&semester=eq.' + encodeURIComponent(payload.semester) : ''}`);
                    break;
                }
                case 'getAllLaporanGuru': {
                    data = await supaFetch('laporan_guru?select=*&order=created_at.desc');
                    break;
                }
                case 'getLaporanGuruByKelas': {
                    const kl = encodeURIComponent(payload.kelas || '');
                    data = await supaFetch(`laporan_guru?kelas_siswa=eq.${kl}&order=created_at.desc&select=*`);
                    break;
                }
                case 'getLaporanGuruBySiswa': {
                    const sid = payload.id_siswa || '';
                    data = await supaFetch(`laporan_guru?id_siswa=eq.${sid}&order=created_at.desc&select=*`);
                    break;
                }
                // [PENTING] Tarik data generik dari admin panel
                // getData: jika payload.table sudah mengandung '?' (query string), gunakan langsung
                case 'getData': data = await supaFetch(payload.table.includes('?') ? payload.table : `${payload.table}?select=*`); break;
                default: throw new Error('Aksi GET tidak dikenali server: ' + action + ' (versi server ' + VERSI_SERVER + ')');
            }
            return { statusCode: 200, headers, body: JSON.stringify({ data }) };
        }

        // ================= F. RUTIN SIMPAN/HAPUS DATA (POST/PATCH/DELETE) =================
        if (type === 'supabase_post') {
            let data;
            switch(action) {
                // Operasi Dinamis Semua Tabel
                case 'insertData': data = await supaFetch(payload.table, 'POST', payload.payload); break;
                case 'updateData': data = await supaFetch(`${payload.table}?id=eq.${payload.id}`, 'PATCH', payload.payload); break;
                case 'deleteData': {
                    // Support useField for deleting by custom field (e.g. id_siswa)
                    const delField = payload.useField || 'id';
                    data = await supaFetch(`${payload.table}?${delField}=eq.${payload.id}`, 'DELETE');
                    break;
                }
                
                // Operasi Spesifik
                case 'insertAspirasi': data = await supaFetch('aspirasi', 'POST', payload); break;
                case 'updateAspirasiStatus': 
                    // Update status di DB
                    data = await supaFetch(`aspirasi?id=eq.${payload.id}`, 'PATCH', { status: payload.status });
                    // Ambil data aspirasi untuk tahu siapa pengirimnya
                    try {
                        const aspData = await supaFetch(`aspirasi?id=eq.${payload.id}&select=pengirim_nama,pengirim_wa,kategori,tipe`);
                        if (aspData && aspData.length > 0) {
                            const asp = aspData[0];
                            const nama = asp.pengirim_nama || 'Kak';
                            const tipe = asp.tipe || 'Aspirasi';
                            const kat  = asp.kategori || '-';

                            const statusInfo = {
                                'Menunggu':        { emoji: '⏳', label: 'Menunggu Antrian',   desc: 'Aspirasi kakak sudah kami terima dan sedang dalam antrian penanganan.' },
                                'Sedang Diproses': { emoji: '🔄', label: 'Sedang Diproses',    desc: 'Tim sekolah sedang menangani aspirasi kakak. Mohon tunggu ya!' },
                                'Selesai':         { emoji: '✅', label: 'Selesai Ditangani',  desc: 'Aspirasi kakak telah selesai ditangani. Terima kasih sudah berani bersuara!' },
                                'Ditolak':         { emoji: '❌', label: 'Tidak Dapat Diproses', desc: 'Maaf, aspirasi kakak tidak dapat kami proses saat ini. Silakan hubungi admin untuk info lebih lanjut.' },
                                'Disetujui':       { emoji: '👍', label: 'Disetujui',          desc: 'Aspirasi kakak telah disetujui dan akan segera ditindaklanjuti!' },
                            };
                            const si = statusInfo[payload.status] || { emoji: '🔔', label: payload.status, desc: `Status aspirasi kakak telah diperbarui menjadi: ${payload.status}` };

                            // Cek apakah ada template custom di DB
                            const customTemplate = await getSetting('wa_template');
                            const customFooter = await getSetting('wa_footer') || 'E-Aspirasi Grisawangi — SMK PGRI 1 Giri';
                            const waDev = await getSetting('wa_dev') || '083863400384';

                            let waMsg;
                            if (customTemplate && customTemplate.trim() !== '') {
                                // Pakai template custom dari admin — replace variabel
                                waMsg = customTemplate
                                    .replace(/{nama}/g, nama)
                                    .replace(/{tipe}/g, tipe)
                                    .replace(/{kategori}/g, kat)
                                    .replace(/{status}/g, si.label)
                                    .replace(/{deskripsi}/g, si.desc)
                                    .replace(/{emoji}/g, si.emoji);
                                waMsg += `

_${customFooter}_`;
                            } else {
                                // Pakai template default sistem
                                waMsg =
`Halo Kak *${nama}* 😊

${si.emoji} *Update Aspirasi Kamu!*

📋 *Jenis:* ${tipe}
🏷️ *Kategori:* ${kat}
📌 *Status Terkini:* ${si.label}

${si.desc}

Terima kasih sudah menyuarakan aspirasimu, Kak! Suaramu sangat berarti untuk kemajuan sekolah kita 🙏

——————————————
💬 *Ada masalah atau pertanyaan?*
Hubungi Developer:
📱 ${waDev}

⭐ *Beri Penilaian Aplikasi:*
🔗 e-aspirasigrisawangi.my.id/penilaian
——————————————
_${customFooter}_`;
                            }

                            // 1. Push notif OneSignal ke HP siswa
                            await sendOneSignalPush({
                                title: `${si.emoji} ${si.label}`,
                                message: `Kak ${nama}, ${tipe} kategori "${kat}" — ${si.label}`,
                                targetNama: asp.pengirim_nama || null,
                            });

                            // 2. Kirim WA via Fonnte ke nomor siswa
                            if (asp.pengirim_wa) {
                                await sendFonnte(asp.pengirim_wa, waMsg);
                            }
                        }
                    } catch(pushErr) { console.error('Push/WA error:', pushErr.message); }
                    break;
                
                case 'verifyGuruPassword': {
                    // Ambil data guru dari DB lalu verifikasi hash
                    const guru = await supaFetch(`master_guru?id=eq.${payload.id}&select=password_hash,status&limit=1`);
                    if (!guru || !guru[0] || ['Nonaktif', 'Diblokir'].includes(guru[0].status)) { data = { valid: false }; break; }
                    const hash = guru[0].password_hash || '';
                    let valid = false;
                    if (bcrypt) {
                        valid = await bcrypt.compare(payload.password, hash);
                    } else {
                        // Fallback: SHA-256 jika bcryptjs tidak tersedia
                        const crypto = require('crypto');
                        valid = crypto.createHash('sha256').update(payload.password).digest('hex') === hash;
                    }
                    data = { valid };
                    break;
                }
                case 'insertGuru': {
                    const guruRaw = (payload.payload && typeof payload.payload === 'object') ? { ...payload.payload } : { ...payload };
                    delete guruRaw.table;
                    if (!guruRaw.status) guruRaw.status = 'Aktif';
                    // Hash password sebelum simpan
                    if (guruRaw.plain_password) {
                        const rawP = guruRaw.plain_password;
                        delete guruRaw.plain_password;
                        if (bcrypt) {
                            guruRaw.password_hash = await bcrypt.hash(rawP, 10);
                        } else {
                            const crypto = require('crypto');
                            guruRaw.password_hash = crypto.createHash('sha256').update(rawP).digest('hex');
                        }
                    }
                    if (guruRaw.mapel) await cekKuotaMapel(guruRaw.mapel, null);
                    data = await supaFetch('master_guru', 'POST', guruRaw);
                    break;
                }
                case 'updateGuru': {
                    const { id: guruId, payload: guruPayload } = payload;
                    // Jika ada plain_password, hash dulu sebelum simpan
                    if (guruPayload.plain_password) {
                        const rawPass = guruPayload.plain_password;
                        delete guruPayload.plain_password;
                        if (bcrypt) {
                            guruPayload.password_hash = await bcrypt.hash(rawPass, 10);
                        } else {
                            const crypto = require('crypto');
                            guruPayload.password_hash = crypto.createHash('sha256').update(rawPass).digest('hex');
                        }
                    }
                    if (guruPayload.mapel) await cekKuotaMapel(guruPayload.mapel, guruId);
                    data = await supaFetch(`master_guru?id=eq.${guruId}`, 'PATCH', guruPayload);
                    break;
                }
                case 'insertLaporanGuru': {
                    // Insert ke tabel laporan_guru — status langsung Selesai
                    // karena poin sudah otomatis ditambah saat laporan masuk
                    const lp = payload.payload || payload;
                    delete lp.table;
                    lp.status = 'Selesai';
                    if (!lp.semester) lp.semester = (await getSetting('semester_aktif')) || null; // tidak ada "Menunggu" atau "Sedang Diproses"
                    const poinSiswa = lp.poin_siswa || 0;
                    const idSiswa   = lp.id_siswa || null;
                    data = await supaFetch('laporan_guru', 'POST', lp);
                    // Tambah poin ke siswa langsung
                    if (poinSiswa > 0 && idSiswa) {
                        const s = await supaFetch(`siswa?id=eq.${idSiswa}&select=total_poin&limit=1`);
                        if (s && s[0]) {
                            const newPoin = (s[0].total_poin || 0) + poinSiswa;
                            await supaFetch(`siswa?id=eq.${idSiswa}`, 'PATCH', { total_poin: newPoin });
                        }
                    }
                    break;
                }
                case 'batalLaporanGuru': {
                    // Batalkan laporan guru — kembalikan poin ke siswa
                    const { id: lapId, alasan_batal, id_siswa: sid, poin_siswa: poinKembali } = payload;
                    // Update status laporan
                    await supaFetch(`laporan_guru?id=eq.${lapId}`, 'PATCH', {
                        status: 'Dibatalkan',
                        alasan_batal: alasan_batal || ''
                    });
                    // Kembalikan poin siswa
                    if (poinKembali > 0 && sid) {
                        const s = await supaFetch(`siswa?id=eq.${sid}&select=total_poin&limit=1`);
                        if (s && s[0]) {
                            const newPoin = Math.max(0, (s[0].total_poin || 0) - poinKembali);
                            await supaFetch(`siswa?id=eq.${sid}`, 'PATCH', { total_poin: newPoin });
                        }
                    }
                    data = { success: true };
                    break;
                }
                case 'insertAdmin': {
                    const adminRaw = (payload.payload && typeof payload.payload === 'object') 
                        ? { ...payload.payload } 
                        : { ...payload };
                    
                    const emailAdmin = (adminRaw.email_admin || '').trim();
                    const passAdmin  = (adminRaw.password    || '').trim();
                    delete adminRaw.password;
                    delete adminRaw.table;
                    
                    // Buat Supabase Auth user (non-fatal jika gagal)
                    if (emailAdmin && passAdmin) {
                        try {
                            const ar1 = await fetch(SUPABASE_URL + '/auth/v1/admin/users', {
                                method: 'POST',
                                headers: { 'apikey': SUPABASE_KEY, 'Authorization': 'Bearer ' + SUPABASE_KEY, 'Content-Type': 'application/json' },
                                body: JSON.stringify({ email: emailAdmin, password: passAdmin, email_confirm: true })
                            });
                            if (!ar1.ok) {
                                await fetch(SUPABASE_URL + '/auth/v1/signup', {
                                    method: 'POST',
                                    headers: { 'apikey': SUPABASE_KEY, 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ email: emailAdmin, password: passAdmin })
                                });
                            }
                        } catch(authErr) {
                            console.error('Auth creation non-fatal:', authErr.message);
                        }
                    }
                    
                    if (!adminRaw.email_admin) adminRaw.email_admin = emailAdmin;
                    if (!adminRaw.status)      adminRaw.status      = 'Aktif';
                    if (!adminRaw.agama)       adminRaw.agama       = 'Islam';
                    if (!adminRaw.role_name)   adminRaw.role_name   = 'Admin Umum';
                    
                    // CRITICAL: Remove any UUID fields that are empty/null/undefined
                    // Supabase auto-generates UUID for 'id' - never send it manually for INSERT
                    const uuidFields = ['id'];
                    uuidFields.forEach(f => {
                        if (adminRaw[f] !== undefined && adminRaw[f] !== null && adminRaw[f] !== '') return;
                        delete adminRaw[f];
                    });
                    
                    // Only send known safe fields to prevent accidental UUID errors
                    const safePayload = {
                        email_admin:    adminRaw.email_admin    || '',
                        nama_lengkap:   adminRaw.nama_lengkap   || '',
                        nama_panggilan: adminRaw.nama_panggilan || '',
                        no_telp:        adminRaw.no_telp        || '',
                        agama:          adminRaw.agama          || 'Islam',
                        role_name:      adminRaw.role_name      || 'Admin Umum',
                        status:         adminRaw.status         || 'Aktif',
                        nip:            adminRaw.nip            || '',
                        spesialisasi:   adminRaw.spesialisasi   || ''
                    };
                    
                    console.log('[insertAdmin] safePayload:', JSON.stringify(safePayload));
                    // Validate: no UUID fields, no empty required fields
                    if (!safePayload.email_admin) {
                        throw new Error('Email admin tidak boleh kosong');
                    }
                    try {
                        data = await supaFetch('hak_akses_admin', 'POST', safePayload);
                    } catch(insertErr) {
                        console.error('[insertAdmin] supaFetch error:', insertErr.message);
                        // Coba lagi dengan field minimal (antisipasi field tidak dikenal)
                        const minPayload = {
                            email_admin:  safePayload.email_admin,
                            nama_lengkap: safePayload.nama_lengkap || '',
                            role_name:    safePayload.role_name    || 'Admin Umum',
                            status:       safePayload.status       || 'Aktif',
                            agama:        safePayload.agama        || 'Islam'
                        };
                        data = await supaFetch('hak_akses_admin', 'POST', minPayload);
                    }
                    break;
                }
                
                // Update massal dengan filter (mis. nonaktifkan semua semester aktif)
                case 'updateWhere': {
                    const { table: tw, where: ww, payload: pw } = payload || {};
                    if (!tw || !ww) throw new Error('table dan where wajib diisi');
                    data = await supaFetch(`${tw}?${ww}`, 'PATCH', pw);
                    break;
                }

                // Status absen manual untuk siswa (dipakai index.html & manualabs.html)
                case 'statusAbsenManual': {
                    const inf = await infoAbsenManual(payload && payload.id_siswa);
                    data = {
                        aktif: inf.aktif, libur: inf.libur, jam_mulai: inf.jamMulai,
                        poin_terlambat: inf.poin, geofence: inf.geofence, record: inf.record
                    };
                    break;
                }

                // Absen manual: server yang menentukan terlambat & poin.
                // Terlambat = absen pada/setelah jam batas (pengaturan admin) -> poin sesuai pengaturan.
                // Siswa yang tidak absen TIDAK diproses apa pun (dikonfirmasi guru via Portal Guru).
                case 'absenManual': {
                    const { id_siswa, lampiran, lat, lon } = payload || {};
                    const jenisAbsen = ['Hadir', 'Sakit', 'Izin'].includes(payload && payload.jenis) ? payload.jenis : 'Hadir';
                    if (!id_siswa) throw new Error('Data siswa tidak ditemukan.');
                    const inf = await infoAbsenManual(id_siswa);
                    if (!inf.aktif) throw new Error('Absen manual sedang dinonaktifkan oleh admin.');
                    if (inf.libur) throw new Error(`Hari ini ${inf.libur} — tidak ada absensi.`);
                    if (inf.record) throw new Error('Kamu sudah tercatat absen hari ini.');

                    if (jenisAbsen === 'Hadir') {
                        // Geofence hanya berlaku untuk Hadir (siswa sakit/izin tentu tidak di sekolah)
                        if (inf.geofence && inf.geofence.wajib) {
                            const la = parseFloat(lat), lo = parseFloat(lon);
                            if (Number.isNaN(la) || Number.isNaN(lo)) throw new Error('Lokasi tidak terdeteksi. Aktifkan izin lokasi lalu coba lagi.');
                            const d = jarakMeter(la, lo, inf.geofence.lat, inf.geofence.lon);
                            if (d > inf.geofence.radius) throw new Error(`Kamu berada ${Math.round(d)} m dari sekolah. Harus dalam radius ${inf.geofence.radius} m.`);
                        }
                    } else if (!(lampiran && String(lampiran).trim())) {
                        throw new Error('Absen ' + jenisAbsen + ' wajib melampirkan foto surat.');
                    }

                    const sw = await supaFetch(`siswa?id=eq.${id_siswa}&select=nama,nisn,kelas,total_poin,status&limit=1`);
                    if (!sw || !sw[0]) throw new Error('Siswa tidak ditemukan.');
                    if (sw[0].status === 'Diblokir') throw new Error('Akun kamu sedang diblokir.');

                    const terlambat = jenisAbsen === 'Hadir' && inf.wib.jam >= inf.jamMulai;
                    await supaFetch('absensi', 'POST', {
                        id_siswa, nama_siswa: sw[0].nama, nisn: sw[0].nisn || '', kelas: sw[0].kelas || '',
                        jenis: jenisAbsen, is_late: terlambat,
                        aktor: jenisAbsen === 'Hadir' ? 'Manual (Selfie)' : `Manual (Surat ${jenisAbsen})`,
                        lampiran: lampiran || '', semester: (await getSetting('semester_aktif')) || null
                    });

                    let total = sw[0].total_poin || 0, tambah = 0;
                    if (terlambat && inf.poin > 0) {
                        tambah = inf.poin;
                        total += tambah;
                        await supaFetch(`siswa?id=eq.${id_siswa}`, 'PATCH', { total_poin: total });
                        await supaFetch('log_history', 'POST', {
                            aktor: sw[0].nama, role: 'Siswa', aksi: 'Absen Manual Terlambat',
                            detail: `+${tambah} poin — absen manual setelah pukul ${String(inf.jamMulai).padStart(2, '0')}:00 WIB (${inf.wib.tgl}). Total poin: ${total}`
                        });
                    }
                    data = { jenis: jenisAbsen, terlambat, poin: tambah, total_poin: total, jam_mulai: inf.jamMulai };
                    break;
                }

                // Aktifkan semester + verifikasi hasilnya benar-benar tersimpan
                case 'setSemesterAktif': {
                    const { id: semId, label: semLabel } = payload || {};
                    if (!semId) throw new Error('ID semester kosong');
                    await supaFetch(`master_semester?id=neq.${semId}`, 'PATCH', { aktif: false });
                    const upd = await supaFetch(`master_semester?id=eq.${semId}`, 'PATCH', { aktif: true });
                    if (!Array.isArray(upd) || upd.length === 0) throw new Error('Database menolak perubahan (0 baris berubah). Cek RLS/policy UPDATE tabel master_semester.');
                    const cek = await supaFetch(`master_semester?id=eq.${semId}&select=aktif`);
                    if (!cek || !cek[0] || cek[0].aktif !== true) throw new Error('Status aktif tidak tersimpan. Cek trigger/policy di tabel master_semester.');
                    const ada = await supaFetch(`pengaturan?nama_pengaturan=eq.semester_aktif&select=id`);
                    if (ada && ada.length) await supaFetch(`pengaturan?nama_pengaturan=eq.semester_aktif`, 'PATCH', { nilai: semLabel });
                    else await supaFetch('pengaturan', 'POST', { nama_pengaturan: 'semester_aktif', nilai: semLabel });
                    data = { success: true };
                    break;
                }

                case 'tambahPoinSiswa': {
                    const swp = await supaFetch(`siswa?select=nama,total_poin&id=eq.${payload.id_siswa}`);
                    if (!swp || !swp.length) throw new Error('Siswa tidak ditemukan.');
                    const tambahP = parseInt(payload.poin) || 0;
                    const poinBaru = (swp[0].total_poin || 0) + tambahP;
                    await supaFetch(`siswa?id=eq.${payload.id_siswa}`, 'PATCH', { total_poin: poinBaru });
                    let ketP = '';
                    try {
                        if (payload.id_pelanggaran) {
                            const mp = await supaFetch(`master_pelanggaran?id=eq.${payload.id_pelanggaran}&select=kelakuan`);
                            ketP = (mp && mp[0]) ? mp[0].kelakuan : '';
                        }
                    } catch (e) {}
                    await supaFetch('log_history', 'POST', {
                        aktor: payload.aktor || 'OSIS El Point', role: 'OSIS El Point', aksi: 'Lapor Poin Pelanggaran',
                        detail: `+${tambahP} Poin untuk ${swp[0].nama}. Alasan: ${ketP || '-'}`
                    });
                    data = { new_poin: poinBaru };
                    break;
                }

                case 'versiServer': data = { versi: VERSI_SERVER }; break;
                case 'statusServer': {
                    await verifSuperAdmin(payload.token);
                    let bucket = false;
                    if (SERVICE_KEY) { try { bucket = (await sbSvc('/storage/v1/bucket/backups')).ok; } catch (e) {} }
                    data = { versi: VERSI_SERVER, service_key: !!SERVICE_KEY, bucket, tabel: TABEL_BACKUP.length, tanggal_wib: waktuWib().tgl };
                    break;
                }
                case 'backupDatabase': {
                    await verifSuperAdmin(payload.token);
                    const b = await buatBackupSql(), nama = `backup-${waktuWib().tgl}-${String(Math.floor(waktuWib().jam * 60)).padStart(4, '0')}.sql`;
                    let validasi;
                    try { const v = await restoreSql(b.sql, { dryRun: true }); validasi = { ok: true, tabel: v.tabel, baris: v.baris }; } catch (e) { validasi = { ok: false, pesan: e.message }; }
                    if (payload.mode === 'storage' || b.sql.length > 4500000) data = { url: await uploadBackup(b.sql, nama), nama, ringkasan: b.ringkasan, ukuran: b.sql.length, validasi };
                    else data = { sql: b.sql, nama, ringkasan: b.ringkasan, ukuran: b.sql.length, validasi };
                    break;
                }
                case 'daftarBackup': {
                    await verifSuperAdmin(payload.token);
                    const r = await sbSvc('/storage/v1/object/list/backups', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ prefix: '', limit: 100, offset: 0, sortBy: { column: 'created_at', order: 'desc' } }) });
                    if (!r.ok) { data = []; break; }
                    data = (await r.json()).filter(x => namaBackupAman(x.name)).map(x => ({ nama: x.name, ukuran: x.metadata && x.metadata.size, dibuat: x.created_at }));
                    break;
                }
                case 'linkBackup': {
                    await verifSuperAdmin(payload.token);
                    if (!namaBackupAman(payload.nama)) throw new Error('Nama file tidak valid.');
                    data = { url: await linkBackupStorage(payload.nama, 3600) };
                    break;
                }
                case 'hapusBackup': {
                    await verifSuperAdmin(payload.token);
                    if (!namaBackupAman(payload.nama)) throw new Error('Nama file tidak valid.');
                    const r = await sbSvc('/storage/v1/object/backups/' + encodeURIComponent(payload.nama), { method: 'DELETE' });
                    if (!r.ok) throw new Error('Gagal menghapus: ' + await r.text());
                    data = { success: true };
                    break;
                }
                case 'restoreDatabase': {
                    await verifSuperAdmin(payload.token);
                    let sqlTxt = payload.sql || '';
                    if (!sqlTxt && payload.path) {
                        if (!namaBackupAman(payload.path)) throw new Error('Nama file tidak valid.');
                        const rs = await sbSvc('/storage/v1/object/backups/' + encodeURIComponent(payload.path));
                        if (!rs.ok) throw new Error('File tidak ditemukan di Storage.');
                        sqlTxt = await rs.text();
                    }
                    data = await restoreSql(sqlTxt, { dryRun: !!payload.dryRun });
                    break;
                }
                case 'syncLibur': await verifSuperAdmin(payload.token); data = await syncLibur(payload.tahun || waktuWib().tgl.slice(0, 4)); break;
                case 'simpanManajemenGuru': {
                    const { id_guru: gid, email: gem, plain_password: gpw, status: gst } = payload || {};
                    if (!gid) throw new Error('Pilih guru terlebih dahulu.');
                    const body = { id_guru: gid, email: (gem || '').trim() || null, status: gst || 'Aktif' };
                    const mg = { status: gst || 'Aktif' };
                    if (gpw) {
                        const h = bcrypt ? await bcrypt.hash(gpw, 10) : require('crypto').createHash('sha256').update(gpw).digest('hex');
                        body.password_hash = h; mg.password_hash = h;   // master_guru ikut, supaya login Portal Guru (nama + password) sinkron
                    }
                    const exAk = (await supaFetch(`manajemen_guru?id_guru=eq.${gid}&select=id&limit=1`))[0];
                    data = exAk ? await supaFetch(`manajemen_guru?id=eq.${exAk.id}`, 'PATCH', body) : await supaFetch('manajemen_guru', 'POST', body);
                    await supaFetch(`master_guru?id=eq.${gid}`, 'PATCH', mg);
                    break;
                }
                case 'cekJadwalGuru': data = await cekJadwalGuru(payload.id_guru); break;

                // Salinan catatan absen pribadi guru (hanya saat guru sedang mengajar di kelas tsb)
                case 'salinCatatanAbsen': {
                    const { id_guru, kelas, dari, sampai, semester, id_siswa: idSiswaSalin } = payload || {};
                    const jd = await cekJadwalGuru(id_guru);
                    if (jd.batasi !== false && (jd.status !== 'mengajar' || jd.kelas !== kelas)) throw new Error('Belum waktunya mata pelajaran.');
                    const abs = await supaFetch(`absensi?kelas=eq.${encodeURIComponent(kelas)}${idSiswaSalin ? '&id_siswa=eq.' + idSiswaSalin : ''}&created_at=gte.${encodeURIComponent(dari + 'T00:00:00+07:00')}&created_at=lte.${encodeURIComponent(sampai + 'T23:59:59.999+07:00')}&select=id_siswa,nama_siswa,kelas,jenis,created_at&limit=5000`);
                    const ada = await supaFetch(`catatan_absen_guru?id_guru=eq.${id_guru}&kelas=eq.${encodeURIComponent(kelas)}&tanggal=gte.${dari}&tanggal=lte.${sampai}&select=id_siswa,tanggal&limit=5000`);
                    const pernah = new Set((ada || []).map(a => a.id_siswa + '|' + a.tanggal));
                    const baru = [];
                    (abs || []).forEach(a => {
                        const tgl = new Date(Date.parse(a.created_at) + 7 * 3600000).toISOString().slice(0, 10), k = a.id_siswa + '|' + tgl;
                        if (pernah.has(k)) return; pernah.add(k);
                        baru.push({ id_guru, id_siswa: a.id_siswa, nama_siswa: a.nama_siswa, kelas: a.kelas, mapel: jd.mapel || null, tanggal: tgl, jenis: a.jenis, jam_ke: String(jd.jam_ke || ''), semester: semester || null });
                    });
                    if (baru.length) await supaFetch('catatan_absen_guru', 'POST', baru);
                    data = { disalin: baru.length };
                    break;
                }
                case 'insertLog': data = await supaFetch('log_history', 'POST', payload); break;

                case 'checkReminder': {
                    // Cek laporan yang sudah > 3 hari belum diproses → kirim WA reminder ke admin
                    const batasHari = payload?.hari || 3;
                    const batasTgl = new Date(Date.now() - batasHari * 86400000).toISOString();
                    const pending = await supaFetch(
                        `aspirasi?select=id,tipe,kategori,pengirim_nama,pengirim_kelas,created_at&status=eq.Menunggu&created_at=lt.${batasTgl}&order=created_at.asc`
                    );
                    if (pending && pending.length > 0) {
                        const waGuru = await getSetting('wa_guru');
                        const waEa   = await getSetting('wa_ea');
                        const target = waGuru || waEa;
                        if (target) {
                            const listPending = pending.slice(0, 10).map((a, i) =>
                                `${i+1}. ${a.tipe} — ${a.kategori} (${a.pengirim_nama}, ${a.pengirim_kelas})`
                            ).join('\n');
                            const sisanya = pending.length > 10 ? `\n...dan ${pending.length - 10} laporan lainnya.` : '';
                            const msg =
`⚠️ *REMINDER LAPORAN PENDING*

Ada *${pending.length} laporan* yang belum ditangani lebih dari *${batasHari} hari*:

${listPending}${sisanya}

Segera tindak lanjuti ya! 🙏

_E-Aspirasi Grisawangi — SMK PGRI 1 Giri_`;
                            await sendFonnte(target, msg, true);  // forAdmin=true → pakai fonnte_token_admin jika ada
                        }
                    }
                    data = { checked: pending?.length || 0 };
                    break;
                }

                case 'laporanBerkala': {
                    // Kirim ringkasan laporan bulanan ke admin via WA & Telegram
                    const now = new Date();
                    const bulanIni = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}`;
                    const awalBulan = `${bulanIni}-01T00:00:00.000Z`;
                    const allBulan = await supaFetch(
                        `aspirasi?select=tipe,status,kategori&created_at=gte.${awalBulan}&order=created_at.desc`
                    );
                    const total    = allBulan?.length || 0;
                    const selesai  = allBulan?.filter(a => a.status === 'Selesai').length || 0;
                    const proses   = allBulan?.filter(a => a.status === 'Sedang Diproses').length || 0;
                    const pending  = allBulan?.filter(a => a.status === 'Menunggu').length || 0;
                    const ditolak  = allBulan?.filter(a => a.status === 'Ditolak').length || 0;
                    const laporan  = allBulan?.filter(a => a.tipe === 'Laporan').length || 0;
                    const masukan  = allBulan?.filter(a => a.tipe === 'Masukan').length || 0;
                    const konselor = allBulan?.filter(a => a.tipe === 'Konselor').length || 0;
                    const persen   = total > 0 ? Math.round((selesai/total)*100) : 0;

                    // Ambil rata-rata penilaian bulan ini
                    const ratings = await supaFetch(
                        `penilaian?select=bintang&created_at=gte.${awalBulan}`
                    );
                    const avgBintang = ratings && ratings.length > 0
                        ? (ratings.reduce((s,r) => s + (r.bintang||0), 0) / ratings.length).toFixed(1)
                        : 'Belum ada';

                    const namaBulan = now.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
                    const msg =
`📊 *LAPORAN BERKALA — ${namaBulan.toUpperCase()}*

📥 *Total Pengajuan:* ${total}
├ 📣 Laporan: ${laporan}
├ 💬 Masukan: ${masukan}
└ 🎓 Konseling: ${konselor}

📌 *Status Penanganan:*
├ ✅ Selesai: ${selesai} (${persen}%)
├ 🔄 Diproses: ${proses}
├ ⏳ Menunggu: ${pending}
└ ❌ Ditolak: ${ditolak}

⭐ *Rata-rata Penilaian:* ${avgBintang}/5

${pending > 0 ? `⚠️ Masih ada *${pending} laporan* yang belum ditangani!` : '✅ Semua laporan sudah ditangani!'}

_E-Aspirasi Grisawangi — SMK PGRI 1 Giri_`;

                    // Kirim ke WA admin (pakai fonnte_token_admin jika tersedia)
                    const waTarget = await getSetting('wa_guru') || await getSetting('wa_ea');
                    if (waTarget) await sendFonnte(waTarget, msg, true);

                    // Kirim ke Telegram
                    const teleToken = await getSetting('tele_bot_token');
                    const teleIds   = await getSetting('tele_chat_ids');
                    if (teleToken && teleIds) {
                        for (const id of teleIds.split(',').map(s=>s.trim()).filter(Boolean)) {
                            await fetch(`https://api.telegram.org/bot${teleToken}/sendMessage`, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ chat_id: id, text: msg, parse_mode: 'Markdown' })
                            });
                        }
                    }
                    data = { bulan: namaBulan, total, selesai, pending };
                    break;
                }
                case 'sendWA':
                    // Kirim WA manual via Fonnte
                    const waResult = await sendFonnte(payload.target, payload.message);
                    data = waResult;
                    break;

                // Cek Fonnte dari server (hindari CORS di browser)
                case 'cekFonnte': {
                    const fToken = await getSetting('fonnte_token');
                    if (!fToken || fToken.trim() === '') { data = { status: 'unconfigured', msg: 'fonnte_token belum diset' }; break; }
                    const fRes = await fetch('https://api.fonnte.com/validate', {
                        method: 'POST',
                        headers: { 'Authorization': fToken.trim() }
                    });
                    data = { status: fRes.status >= 500 ? 'error' : 'online', httpStatus: fRes.status };
                    break;
                }

                // Cek status OneSignal dari server (hindari CORS di browser)
                case 'cekOneSignal': {
                    const appId  = await getSetting('onesignal_app_id');
                    const apiKey = await getSetting('onesignal_api_key');
                    if (!appId) { data = { status: 'unconfigured', msg: 'onesignal_app_id belum diset' }; break; }
                    const osRes = await fetch(`https://api.onesignal.com/apps/${appId}`, {
                        headers: {
                            'Authorization': `Basic ${apiKey}`,
                            'Content-Type': 'application/json'
                        }
                    });
                    if (!osRes.ok && osRes.status !== 403) throw new Error(`OneSignal HTTP ${osRes.status}`);
                    data = { status: 'online', httpStatus: osRes.status };
                    break;
                }
                
                case 'updatePengaturan': 
                    // UPSERT: coba PATCH dulu (update jika ada), jika 0 rows maka POST (insert baru)
                    for (const item of payload) {
                        // Cek apakah row sudah ada
                        const existing = await supaFetch(`pengaturan?nama_pengaturan=eq.${encodeURIComponent(item.nama_pengaturan)}&select=id`);
                        if (existing && existing.length > 0) {
                            // Row sudah ada → update
                            await supaFetch(`pengaturan?nama_pengaturan=eq.${encodeURIComponent(item.nama_pengaturan)}`, 'PATCH', { nilai: item.nilai });
                        } else {
                            // Row belum ada → insert
                            await supaFetch('pengaturan', 'POST', { nama_pengaturan: item.nama_pengaturan, nilai: item.nilai });
                        }
                    }
                    data = { success: true }; 
                    break;

                // FITUR 6 — Kirim pengumuman via WA ke admin/guru
                case 'kirimPengumumanWA': {
                    const waGuru = await getSetting('wa_guru');
                    const waEa   = await getSetting('wa_ea');
                    const target = waGuru || waEa;
                    if (target) {
                        const tipeLabel = payload.tipe === 'darurat' ? '🚨 DARURAT' : payload.tipe === 'penting' ? '⚠️ PENTING' : 'ℹ️ INFO';
                        const msg = `*${tipeLabel} — PENGUMUMAN*

*${payload.judul}*

${payload.isi}

_E-Aspirasi Grisawangi — SMK PGRI 1 Giri_`;
                        await sendFonnte(target, msg, true);  // forAdmin=true
                        // Juga ke Telegram
                        const teleToken = await getSetting('tele_bot_token');
                        const teleIds   = await getSetting('tele_chat_ids');
                        if (teleToken && teleIds) {
                            for (const id of teleIds.split(',').map(s=>s.trim()).filter(Boolean)) {
                                await fetch(`https://api.telegram.org/bot${teleToken}/sendMessage`, {
                                    method:'POST', headers:{'Content-Type':'application/json'},
                                    body: JSON.stringify({ chat_id: id, text: msg, parse_mode: 'Markdown' })
                                });
                            }
                        }
                    }
                    data = { success: true };
                    break;
                }

                // FITUR 17 — Backup data ke Telegram/WA
                case 'backupData': {
                    const tables = ['siswa', 'aspirasi', 'absensi', 'penilaian', 'pengumuman'];
                    let totalRows = 0;
                    const backupInfo = [];
                    for (const tbl of tables) {
                        try {
                            const rows = await supaFetch(`${tbl}?select=*&limit=1`);
                            // Hanya ambil count — backup besar via Supabase dashboard
                            const countRes = await supaFetch(`${tbl}?select=id`);
                            const count = Array.isArray(countRes) ? countRes.length : 0;
                            totalRows += count;
                            backupInfo.push(`📊 ${tbl}: ${count} baris`);
                        } catch(e) { backupInfo.push(`❌ ${tbl}: gagal`); }
                    }
                    const msg = `🗄️ *LAPORAN BACKUP DATA*

${backupInfo.join('\n')}

Total: *${totalRows} baris* data tersimpan

💡 Untuk backup penuh, gunakan Supabase Dashboard → Settings → Backups

_E-Aspirasi Grisawangi — ${new Date().toLocaleDateString('id-ID')}_`;
                    const waTarget = await getSetting('wa_guru') || await getSetting('wa_ea');
                    if (waTarget) await sendFonnte(waTarget, msg, true);  // forAdmin=true
                    const teleToken = await getSetting('tele_bot_token');
                    const teleIds   = await getSetting('tele_chat_ids');
                    if (teleToken && teleIds) {
                        for (const id of teleIds.split(',').map(s=>s.trim()).filter(Boolean)) {
                            await fetch(`https://api.telegram.org/bot${teleToken}/sendMessage`, {
                                method:'POST', headers:{'Content-Type':'application/json'},
                                body: JSON.stringify({ chat_id: id, text: msg, parse_mode: 'Markdown' })
                            });
                        }
                    }
                    data = { success: true, totalRows, tables: backupInfo };
                    break;
                }

                // FITUR 19 — Log aktivitas siswa
                case 'logAktivitasSiswa': {
                    const logData = {
                        aktor: payload.nama || 'Siswa',
                        role: payload.kelas || '-',
                        aksi: payload.aksi || 'Aktivitas',
                        detail: payload.detail || '',
                        created_at: new Date().toISOString()
                    };
                    data = await supaFetch('log_history', 'POST', logData);
                    break;
                }

                // FITUR 23 — Sync ke Google Sheets
                // CATATAN: Google Sheets API dengan API Key (read-only) tidak bisa menulis data.
                // Solusi: Gunakan Google Apps Script Web App sebagai proxy penulisan.
                // Cara setup: Di Google Sheets → Extensions → Apps Script → tempel kode di bawah,
                // deploy sebagai Web App (akses: Anyone), lalu paste URL-nya di pengaturan 'google_sheet_id'.
                //
                // Kode Apps Script (tempel di editor):
                // function doPost(e) {
                //   var data = JSON.parse(e.postData.contents);
                //   var ss = SpreadsheetApp.openById(data.sheetId);
                //   var sheet = ss.getSheets()[0];
                //   sheet.clearContents();
                //   sheet.getRange(1,1,data.values.length,data.values[0].length).setValues(data.values);
                //   return ContentService.createTextOutput(JSON.stringify({success:true,rows:data.values.length-1})).setMimeType(ContentService.MimeType.JSON);
                // }
                case 'syncGoogleSheets': {
                    const appsScriptUrl = await getSetting('google_sheet_id');
                    const sheetActualId = await getSetting('google_sheet_id');
                    const sheetKey = await getSetting('google_sheets_key');

                    if (!sheetActualId) {
                        data = { error: 'Google Sheet ID / Apps Script URL belum diset di pengaturan.' };
                        break;
                    }

                    // Ambil data aspirasi terbaru
                    const aspirasi = await supaFetch('aspirasi?select=*&order=created_at.desc&limit=500');
                    if (!aspirasi || aspirasi.length === 0) { data = { success: true, synced: 0 }; break; }

                    const rows = aspirasi.map(a => [
                        new Date(a.created_at).toLocaleString('id-ID'),
                        a.tipe || '', a.pengirim_nama || '', a.pengirim_kelas || '',
                        a.pengirim_wa || '', a.kategori || '',
                        (a.detail_pesan || '').substring(0, 500), a.status || ''
                    ]);
                    const values = [['Tanggal', 'Tipe', 'Nama', 'Kelas', 'WA', 'Kategori', 'Isi Pesan', 'Status'], ...rows];

                    // Cek apakah yang disimpan adalah URL Apps Script atau Sheet ID
                    const isAppsScriptUrl = sheetActualId.startsWith('https://script.google.com');

                    if (isAppsScriptUrl) {
                        // Mode 1: Kirim via Apps Script Web App (bisa write)
                        const appsRes = await fetch(sheetActualId, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ values })
                        });
                        let appsData;
                        try { appsData = await appsRes.json(); } catch(e) { appsData = { success: true }; }
                        if (appsData.error) throw new Error(appsData.error);
                        data = { success: true, synced: rows.length, mode: 'apps_script' };
                    } else if (sheetKey && !isAppsScriptUrl) {
                        // Mode 2: Coba Google Sheets API v4 dengan OAuth2 Service Account
                        // Catatan: API Key saja TIDAK BISA write. Perlu service account atau Apps Script.
                        // Jika sheetKey diisi tapi bukan Apps Script URL, beri petunjuk
                        const sheetId = sheetActualId.match(/spreadsheets\/d\/([a-zA-Z0-9_-]+)/)?.[1] || sheetActualId;
                        // Coba baca dulu (read OK dengan API Key)
                        const readRes = await fetch(
                            `https://sheets.googleapis.com/v4/spreadsheets/${sheetId}?key=${sheetKey}&fields=properties.title`,
                            { method: 'GET' }
                        );
                        const readData = await readRes.json();
                        if (readData.error) throw new Error(`Sheet tidak bisa diakses: ${readData.error.message}. Pastikan Sheet bersifat publik atau gunakan Apps Script URL.`);
                        // Sheet bisa dibaca tapi tidak bisa ditulis dengan API Key saja
                        data = {
                            success: false,
                            synced: 0,
                            mode: 'api_key_readonly',
                            message: `Sheet "${readData.properties?.title}" terdeteksi (${rows.length} baris siap sync), namun API Key TIDAK BISA menulis ke Google Sheets. Gunakan Google Apps Script Web App URL sebagai Sheet ID untuk bisa menulis data. Lihat panduan di komentar kode.`
                        };
                        throw new Error(data.message);
                    } else {
                        throw new Error('Konfigurasi Google Sheets tidak lengkap. Isi Apps Script URL di field "Google Sheet ID atau Link".');
                    }
                    break;
                }

                // FITUR 3 — Balas/catatan admin ke laporan
                case 'insertBalasan': {
                    // FIX: simpan pengirim_wa ke DB agar bisa di-query nanti
                    data = await supaFetch('balasan_laporan', 'POST', {
                        id_aspirasi: payload.id_aspirasi,
                        isi_balasan: payload.isi_balasan,
                        nama_admin: payload.nama_admin || 'Admin',
                        pengirim_wa: payload.pengirim_wa || '',
                        created_at: new Date().toISOString()
                    });
                    // Kirim notif WA ke siswa jika ada nomor
                    if (payload.pengirim_wa && payload.pengirim_wa.trim() !== '') {
                        const waMsg = `💬 *Admin membalas laporanmu!*

_${payload.isi_balasan}_

— ${payload.nama_admin}

_E-Aspirasi Grisawangi — SMK PGRI 1 Giri_`;
                        await sendFonnte(payload.pengirim_wa, waMsg);
                    }
                    break;
                }

                case 'getBalasan': {
                    data = await supaFetch(`balasan_laporan?id_aspirasi=eq.${payload.id_aspirasi}&order=created_at.asc`);
                    break;
                }
                default: throw new Error('Aksi POST tidak dikenali server: ' + action + ' (versi server ' + VERSI_SERVER + ')');
            }
            return { statusCode: 200, headers, body: JSON.stringify({ success: true, data }) };
        }

        return { statusCode: 400, headers, body: JSON.stringify({ error: 'Aksi API tidak dikenali' }) };

    } catch (error) { 
        return { statusCode: 500, headers, body: JSON.stringify({ error: error.message }) }; 
    }
};
exports.cronHarian = cronHarian;
