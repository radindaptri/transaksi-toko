<?php

use App\Http\Controllers\LaporanTransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/laporan-transaksi/ambil-data', [LaporanTransaksiController::class, 'ambilData'])
    ->name('laporan-transaksi.ambil-data');

Route::get('/laporan', [LaporanTransaksiController::class, 'index'])
    ->name('laporan.index');

Route::get('/laporan/export-excel', [
    \App\Http\Controllers\LaporanTransaksiController::class,
    'exportExcel'
])->name('laporan-transaksi.export-excel');