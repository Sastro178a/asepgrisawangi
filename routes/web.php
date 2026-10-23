<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Auto-Generated)
|--------------------------------------------------------------------------
*/

Route::get('/admin', function () {
    return view('pages.admin');
});

Route::get('/bantuan', function () {
    return view('pages.bantuan');
});

Route::get('/dokumentasi', function () {
    return view('pages.dokumentasi');
});

Route::get('/elakses', function () {
    return view('pages.elakses');
});

Route::get('/elpoint', function () {
    return view('pages.elpoint');
});

Route::get('/grisva', function () {
    return view('pages.grisva');
});

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/indexold', function () {
    return view('pages.indexold');
});

Route::get('/maintenance', function () {
    return view('pages.maintenance');
});

Route::get('/manualabs', function () {
    return view('pages.manualabs');
});

Route::get('/penilaian', function () {
    return view('pages.penilaian');
});

Route::get('/perawatan', function () {
    return view('pages.perawatan');
});

Route::get('/portal-guru', function () {
    return view('pages.portal-guru');
});

Route::get('/siswa', function () {
    return view('pages.siswa');
});

Route::get('/status', function () {
    return view('pages.status');
});

Route::get('/grisaevent', function () {
    return view('pages.grisaevent');
});

Route::get('/setevent', function () {
    return view('pages.setevent');
});

