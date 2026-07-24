@extends('adminlte::page')

@section('title', 'Laporan Laba Rugi')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 fw-semibold">Laporan Laba Rugi</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Laba Rugi</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    {{-- Filter Card --}}
    <div class="card card-outline card-primary mb-4">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-funnel me-2"></i>Filter Laporan</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.profit-loss') }}" method="GET" class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="end_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>
                    <button type="button" class="btn btn-success" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Cetak Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Statement Layout --}}
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center border-bottom pb-4 mb-4">
                        <h2 class="fw-bold mb-1">Toko Buah</h2>
                        <h4 class="text-muted">Laporan Laba Rugi</h4>
                        <p class="mb-0">
                            Periode: 
                            <strong>{{ \Carbon\Carbon::parse(request('start_date', now()->startOfMonth()))->format('d M Y') }}</strong>
                            s/d 
                            <strong>{{ \Carbon\Carbon::parse(request('end_date', now()))->format('d M Y') }}</strong>
                        </p>
                    </div>

                    @php
                        // Dummy logic for template frontend display
                        $totalPendapatan = $transactions->sum('total_amount'); 
                        $totalHpp = $totalPendapatan * 0.7; // Dummy HPP 70%
                        $labaKotor = $totalPendapatan - $totalHpp;
                        $biayaOperasional = 0; // Placeholder
                        $labaBersih = $labaKotor - $biayaOperasional;
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-borderless fs-5">
                            <tbody>
                                {{-- Pendapatan --}}
                                <tr>
                                    <td colspan="2" class="fw-bold text-primary"><i class="bi bi-graph-up-arrow me-2"></i>PENDAPATAN</td>
                                </tr>
                                <tr>
                                    <td class="ps-5">Penjualan Kotor</td>
                                    <td class="text-end text-nowrap">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="border-bottom border-dark">
                                    <td class="ps-5">Diskon / Retur</td>
                                    <td class="text-end text-nowrap">( Rp 0 )</td>
                                </tr>
                                <tr>
                                    <td class="ps-4 fw-bold">Total Pendapatan Bersih</td>
                                    <td class="text-end fw-bold text-nowrap">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                                </tr>
                                
                                <tr><td colspan="2"></td></tr>

                                {{-- Harga Pokok Penjualan --}}
                                <tr>
                                    <td colspan="2" class="fw-bold text-danger"><i class="bi bi-box-arrow-right me-2"></i>HARGA POKOK PENJUALAN (HPP)</td>
                                </tr>
                                <tr>
                                    <td class="ps-5">Biaya Pembelian Buah <small class="text-muted">(Estimasi Template)</small></td>
                                    <td class="text-end text-nowrap">Rp {{ number_format($totalHpp, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="border-bottom border-dark">
                                    <td class="ps-5">Biaya Lainnya</td>
                                    <td class="text-end text-nowrap">Rp 0</td>
                                </tr>
                                <tr>
                                    <td class="ps-4 fw-bold">Total HPP</td>
                                    <td class="text-end fw-bold text-nowrap">Rp {{ number_format($totalHpp, 0, ',', '.') }}</td>
                                </tr>

                                <tr><td colspan="2"></td></tr>

                                {{-- Laba Kotor --}}
                                <tr class="bg-light">
                                    <td class="ps-4 fw-bold fs-4">LABA KOTOR</td>
                                    <td class="text-end fw-bold fs-4 text-nowrap">Rp {{ number_format($labaKotor, 0, ',', '.') }}</td>
                                </tr>

                                <tr><td colspan="2"></td></tr>

                                {{-- Biaya Operasional --}}
                                <tr>
                                    <td colspan="2" class="fw-bold text-warning"><i class="bi bi-wallet2 me-2"></i>BIAYA OPERASIONAL</td>
                                </tr>
                                <tr>
                                    <td class="ps-5 text-muted fst-italic">Belum ada rincian biaya terdata di database</td>
                                    <td class="text-end text-nowrap">Rp 0</td>
                                </tr>
                                <tr class="border-bottom border-dark">
                                    <td class="ps-4 fw-bold">Total Biaya Operasional</td>
                                    <td class="text-end fw-bold text-nowrap">Rp 0</td>
                                </tr>

                                <tr><td colspan="2"></td></tr>
                            </tbody>
                            <tfoot>
                                {{-- Laba Bersih --}}
                                <tr class="{{ $labaBersih >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                                    <td class="ps-4 fw-bold fs-3 py-3">LABA BERSIH</td>
                                    <td class="text-end fw-bold fs-3 text-nowrap py-3">Rp {{ number_format($labaBersih, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    /* Styling for print mode */
    @media print {
        .main-header, .main-sidebar, .card-header, form, .main-footer {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        body {
            background-color: white !important;
        }
    }
</style>
@stop
