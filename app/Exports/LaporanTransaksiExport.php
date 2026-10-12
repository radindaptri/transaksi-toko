<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanTransaksiExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected array $transaksi;

    public function __construct(array $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Total Nominal',
            'Status',
            'Waktu Transaksi',
        ];
    }

    public function array(): array
    {
        return array_map(function ($item) {
            return [
                $item['kode_transaksi'] ?? '-',
                $item['total_nominal'] ?? 0,
                $item['status'] ?? '-',
                $item['waktu_transaksi'] ?? '-',
            ];
        }, $this->transaksi);
    }
}
