// Netlify Scheduled Function: tiap 00:01 WIB (17:01 UTC) -> sinkron libur, siapkan hari ini, backup harian.
// Jadwal didaftarkan lewat helper schedule() (paket @netlify/functions) ATAU lewat netlify.toml:
//   [functions."cron-harian"]
//     schedule = "1 17 * * *"
const n = require('./notify');

const handler = async () => {
    const hasil = await n.cronHarian();
    console.log('[cron-harian]', JSON.stringify(hasil));
    return { statusCode: 200, body: JSON.stringify(hasil) };
};

let wrapped = handler;
try { wrapped = require('@netlify/functions').schedule('1 17 * * *', handler); } catch (e) { /* pakai netlify.toml */ }
exports.handler = wrapped;
exports.config = { schedule: '1 17 * * *' };
