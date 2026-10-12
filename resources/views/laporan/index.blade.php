
@extends('layouts.app')

@section('title', 'Dashboard Laporan | ABC Report')
@section('page', 'Dashboard Laporan')

@section('content')
    @php
        $jumlahTransaksi = $transaksi->count();

        $totalNominal = $transaksi->sum(function ($item) {
            return (float) ($item['total_nominal'] ?? 0);
        });

        $jumlahBerhasil = $transaksi->filter(function ($item) {
            return strtoupper($item['status'] ?? '') === 'SUCCESS';
        })->count();
    @endphp

    <section class="page-heading">
        <div>
            <h1>Dashboard Laporan</h1>
            <p>Kelola dan pantau transaksi dari Toko ABC.</p>
        </div>

        <div class="date-label">
            Tanggal: {{ $tanggal }}
        </div>
    </section>

    @if (isset($pesanSukses) && $pesanSukses)
        <div class="alert alert-success" role="status">
            ✓ {{ $pesanSukses }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error" role="alert">
            ⚠ {{ session('error') }}
        </div>
    @endif

    <section class="stats">
        <div class="stat-card">
            <div class="stat-top">
                Total Nominal
                <span class="stat-icon">↗</span>
            </div>
            <div class="stat-value">
                Rp {{ number_format($totalNominal, 0, ',', '.') }}
            </div>
            <div class="stat-foot">Total data yang ditampilkan</div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                Jumlah Transaksi
                <span class="stat-icon">▤</span>
            </div>
            <div class="stat-value">
                {{ number_format($jumlahTransaksi, 0, ',', '.') }}
            </div>
            <div class="stat-foot">Transaksi dalam laporan ini</div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                Transaksi Berhasil
                <span class="stat-icon">✓</span>
            </div>
            <div class="stat-value">
                {{ number_format($jumlahBerhasil, 0, ',', '.') }}
            </div>
            <div class="stat-foot">Status SUCCESS</div>
        </div>
    </section>

    <section class="panel" id="filter">
        <div class="panel-heading">
            <div>
                <h2>Ambil Data Transaksi</h2>
                <p>Pilih tanggal untuk meminta data dari API Toko ABC.</p>
            </div>
        </div>

        <form
            class="filter-form"
            method="POST"
            action="{{ route('laporan-transaksi.ambil-data') }}"
            id="ambilDataForm"
        >
            @csrf

            <div class="field">
                <label for="tanggal">Tanggal transaksi</label>
                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    value="{{ old('tanggal', $tanggal) }}"
                    required
                >
            </div>

            <button class="btn btn-primary" type="submit" id="submitBtn">
                <span id="submitText">↻ Ambil Data Transaksi</span>
            </button>
        </form>
    </section>

    <section class="panel" id="data-transaksi">
        <div class="panel-heading">
            <div>
                <h2>Data Transaksi</h2>
                <p>Daftar transaksi yang ditampilkan pada laporan.</p>
            </div>
            <span class="status">Laporan</span>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Transaksi</th>
                        <th>Total Nominal</th>
                        <th>Status</th>
                        <th>Waktu Transaksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transaksi as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="trx-code">
                                {{ $item['kode_transaksi'] ?? '-' }}
                            </td>
                            <td class="amount">
                                Rp {{ number_format((float) ($item['total_nominal'] ?? 0), 0, ',', '.') }}
                            </td>
                            <td>
                                @if (strtoupper($item['status'] ?? '') === 'SUCCESS')
                                    <span class="status">SUCCESS</span>
                                @else
                                    {{ $item['status'] ?? '-' }}
                                @endif
                            </td>
                            <td>{{ $item['waktu_transaksi'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty">
                                <strong>Belum ada data transaksi</strong>
                                <span>
                                    Pilih tanggal, lalu klik Ambil Data Transaksi.
                                </span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <span>
                Menampilkan <strong>{{ $jumlahTransaksi }}</strong> transaksi
            </span>
            <span>
                Total: <strong>Rp {{ number_format($totalNominal, 0, ',', '.') }}</strong>
            </span>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.getElementById('ambilDataForm').addEventListener('submit', function () {
        const button = document.getElementById('submitBtn');
        const text = document.getElementById('submitText');

        button.disabled = true;
        text.textContent = 'Mengambil data...';
    });
</script>
@endpush
