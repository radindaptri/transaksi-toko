<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class LaporanTransaksiController extends Controller
{
    public function ambilData()
    {
        $tanggal = request('tanggal', now()->toDateString());

        $response = Http::withHeaders([ 
            'X-API-KEY' => env('TOKO_ABC_API_KEY'),
        ])->get(env('TOKO_ABC_API_URL'), [
            'tanggal' => $tanggal,
        ]);

        if (!$response->successful()) {
            return back()->with(
                'error',
                'Gagal mengambil data dari API Toko ABC. HTTP ' . $response->status()
            );
        }

        $data = $response->json();

        if (!isset($data['data']) || !is_array($data['data'])) {
            return back()->with(
                'error',
                'Format respons API tidak sesuai harapan.'
            );
        }

        $jumlahDisimpan = 0;

        foreach ($data['data'] as $transaksi) {
            if (($transaksi['status'] ?? '') !== 'SUCCESS') {
                continue;
            }

            DB::table('laporan_transaksi')->updateOrInsert(
                ['kode_transaksi' => $transaksi['kode_transaksi']],
                [
                    'total_nominal' => $transaksi['total_nominal'],
                    'status' => $transaksi['status'],
                    'waktu_transaksi' => $transaksi['waktu_transaksi'] ?? null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $jumlahDisimpan++;
        }

         $transaksi = DB::table('laporan_transaksi')
        ->whereDate('waktu_transaksi', $tanggal)
        ->orderByDesc('waktu_transaksi')
        ->get()
        ->map(function ($item) {
            return (array) $item;
        })
        ->toArray();

    return redirect()->route('laporan.index')
        ->with('tanggal_laporan', $tanggal)
        ->with('hasil_laporan', $transaksi)
        ->with('pesanSukses', "Proses selesai. {$jumlahDisimpan} transaksi sukses diproses.");
    }

    public function index()
    {
        $tanggal = session('tanggal_laporan', now()->toDateString());
        $transaksi = collect(session('hasil_laporan', []));

        return view('laporan.index', [
            'transaksi' => $transaksi,
            'tanggal' => $tanggal,
            'pesanSukses' => session('pesanSukses'),
        ]);
    }
}