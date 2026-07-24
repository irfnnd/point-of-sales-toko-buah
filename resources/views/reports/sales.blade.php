@extends('adminlte::page')

@section('title', 'Laporan Penjualan')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 fw-semibold">Laporan Penjualan</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Penjualan</li>
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
            <form action="{{ route('reports.sales') }}" method="GET" class="row align-items-end">
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

    {{-- Summary Boxes --}}
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="bi bi-receipt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Transaksi</span>
                    <span class="info-box-number">{{ $transactions->count() }} Trx</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="bi bi-cash-stack"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Penjualan (Omset)</span>
                    <span class="info-box-number">Rp {{ number_format($transactions->where('status', 'success')->sum('total_amount'), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 col-12">
            <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="bi bi-basket"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Item Terjual</span>
                    <span class="info-box-number">- <small class="fw-normal">(Menunggu Relasi Detail Transaksi)</small></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Data Table Card --}}
    <div class="card">
        <div class="card-header border-0">
            <h3 class="card-title fw-bold">Detail Transaksi</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover m-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Tanggal</th>
                            <th>No. Invoice</th>
                            <th>Kasir</th>
                            <th class="text-end">Total Belanja</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $index => $transaction)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y H:i') : '-' }}</td>
                                <td class="fw-semibold">{{ $transaction->invoice_number }}</td>
                                <td>{{ $transaction->user ? $transaction->user->name : 'N/A' }}</td>
                                <td class="text-end fw-bold text-success">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if(strtolower($transaction->status) === 'success')
                                        <span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i>Sukses</span>
                                    @else
                                        <span class="badge text-bg-danger"><i class="bi bi-x-circle me-1"></i>Batal</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox d-block fs-2 mb-2"></i>
                                    Tidak ada data transaksi pada rentang tanggal tersebut.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($transactions->count() > 0)
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="4" class="text-end text-uppercase">Total Keseluruhan (Sukses):</td>
                            <td class="text-end fs-5 text-success">Rp {{ number_format($transactions->where('status', 'success')->sum('total_amount'), 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    /* Styling for print mode */
    @media print {
        .main-header, .main-sidebar, .card-header .btn-tool, form, .main-footer {
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
