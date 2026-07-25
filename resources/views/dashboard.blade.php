@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 fw-semibold">Dashboard</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')

    {{-- ══════════════════════════════════════════════════════════════
         ROW 1 — Small-Box Summary Widgets (AdminLTE native)
    ══════════════════════════════════════════════════════════════ --}}
    <div class="row">

        {{-- Total Jenis Buah --}}
        <div class="col-lg-3 col-sm-6">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3>{{ $totalFruits }}</h3>
                    <p>Total Jenis Buah</p>
                </div>
                <i class="small-box-icon bi bi-basket2-fill"></i>
                <a href="{{ route('data.fruits') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat Data <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
            </div>
        </div>

        {{-- Stok Menipis --}}
        <div class="col-lg-3 col-sm-6">
            <div class="small-box text-bg-danger">
                <div class="inner">
                    <h3>{{ $totalLowStock }}</h3>
                    <p>Stok Menipis (&lt; 10 unit)</p>
                </div>
                <i class="small-box-icon bi bi-exclamation-triangle-fill"></i>
                <a href="{{ route('data.stocks') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat Stok <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
            </div>
        </div>

        {{-- Transaksi Hari Ini --}}
        <div class="col-lg-3 col-sm-6">
            <div class="small-box text-bg-info">
                <div class="inner">
                    <h3>{{ $totalTransactionsToday }}</h3>
                    <p>Transaksi Hari Ini</p>
                </div>
                <i class="small-box-icon bi bi-receipt"></i>
                <a href="{{ route('data.transactions') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat Transaksi <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
            </div>
        </div>

        {{-- Pendapatan Hari Ini --}}
        <div class="col-lg-3 col-sm-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3 style="font-size: 1.6rem;">Rp {{ number_format($revenueToday, 0, ',', '.') }}</h3>
                    <p>Pendapatan Hari Ini</p>
                </div>
                <i class="small-box-icon bi bi-cash-coin"></i>
                <a href="{{ route('reports.sales') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat Laporan <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════
         ROW 2 — Stok Menipis + Transaksi Terbaru
    ══════════════════════════════════════════════════════════════ --}}
    <div class="row">

        {{-- ── Stok Menipis ────────────────────────────────────────── --}}
        <div class="col-lg-7">
            <div class="card card-outline card-danger">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Stok Menipis
                        @if($totalLowStock > 0)
                            <span class="badge text-bg-danger ms-1">{{ $totalLowStock }}</span>
                        @endif
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('data.stocks') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-box-seam me-1"></i> Kelola Stok
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($lowStockFruits->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-patch-check-fill fs-1 text-success d-block mb-2"></i>
                            <p class="mb-0">Semua stok dalam kondisi aman.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 40px;" class="text-center">No</th>
                                        <th>Nama Buah</th>
                                        <th>Kategori</th>
                                        <th class="text-end" style="width: 120px;">Sisa Stok</th>
                                        <th class="text-center" style="width: 110px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockFruits as $index => $fruit)
                                        @php
                                            $qty = $fruit->net_quantity;

                                            if ($qty <= 0) {
                                                $badgeClass = 'text-bg-danger';
                                                $status     = 'Habis';
                                            } elseif ($qty <= 3) {
                                                $badgeClass = 'text-bg-danger';
                                                $status     = 'Kritis';
                                            } else {
                                                $badgeClass = 'text-bg-warning';
                                                $status     = 'Menipis';
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="fw-semibold">
                                                {{ $fruit->name }}
                                                <small class="text-muted d-block">{{ $fruit->code }}</small>
                                            </td>
                                            <td>
                                                @if($fruit->category)
                                                    <span class="badge text-bg-info">{{ $fruit->category }}</span>
                                                @else
                                                    <span class="text-muted fst-italic">—</span>
                                                @endif
                                            </td>
                                            <td class="text-end fw-bold">
                                                @if($qty <= 0)
                                                    <span class="text-danger">0 {{ $fruit->unit }}</span>
                                                @else
                                                    <span class="{{ $qty <= 3 ? 'text-danger' : 'text-warning' }}">
                                                        {{ number_format($qty, 2, ',', '.') }} {{ $fruit->unit }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Transaksi Terbaru ───────────────────────────────────── --}}
        <div class="col-lg-5">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-clock-history me-2"></i>Transaksi Terbaru
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('data.transactions') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-receipt me-1"></i> Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentTransactions->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                            <p class="mb-0">Belum ada transaksi.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Invoice</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-center" style="width: 90px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $txn)
                                        @php
                                            $statusClass = match($txn->status ?? 'paid') {
                                                'paid'    => 'text-bg-success',
                                                'pending' => 'text-bg-warning',
                                                'cancel'  => 'text-bg-secondary',
                                                default   => 'text-bg-secondary',
                                            };
                                            $statusLabel = match($txn->status ?? 'paid') {
                                                'paid'    => 'Lunas',
                                                'pending' => 'Pending',
                                                'cancel'  => 'Batal',
                                                default   => ucfirst($txn->status ?? '-'),
                                            };
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="fw-semibold">{{ $txn->invoice_number }}</span>
                                                <small class="text-muted d-block">
                                                    {{ $txn->transaction_date?->format('d M Y, H:i') ?? '—' }}
                                                </small>
                                            </td>
                                            <td class="text-end fw-bold">
                                                Rp {{ number_format($txn->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@stop
