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
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="small-box text-bg-primary shadow-sm h-100 mb-0">
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
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="small-box text-bg-warning shadow-sm h-100 mb-0">
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

        {{-- Warning Buah Busuk / Kedaluwarsa --}}
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="small-box text-bg-danger shadow-sm h-100 mb-0">
                <div class="inner">
                    <h3>{{ $totalRottenWarningCount }}</h3>
                    <p>Stok Busuk / Hampir Busuk</p>
                </div>
                <i class="small-box-icon bi bi-exclamation-octagon-fill"></i>
                <a href="{{ route('data.stocks') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Cek Kondisi <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
            </div>
        </div>

        {{-- Pendapatan Hari Ini --}}
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="small-box text-bg-success shadow-sm h-100 mb-0">
                <div class="inner">
                    <h3 style="font-size: 1.6rem;">Rp {{ number_format($revenueToday, 0, ',', '.') }}</h3>
                    <p>Pendapatan Hari Ini ({{ $totalTransactionsToday }} Tx)</p>
                </div>
                <i class="small-box-icon bi bi-cash-coin"></i>
                <a href="{{ route('reports.sales') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat Laporan <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════
         ROW 2 — Peringatan Buah Busuk / Kedaluwarsa (Fitur Utama)
    ══════════════════════════════════════════════════════════════ --}}
    @if($totalRottenWarningCount > 0)
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card-outline card-danger shadow-sm">
                    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-exclamation-octagon-fill me-2"></i>Peringatan Stok Buah Busuk & Hampir Busuk
                            <span class="badge bg-white text-danger ms-2">{{ $totalRottenWarningCount }} Batch</span>
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('data.stocks') }}" class="btn btn-light btn-sm fw-semibold">
                                <i class="bi bi-box-seam me-1"></i> Kelola Stok
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 40px;" class="text-center">No</th>
                                        <th>Nama Buah</th>
                                        <th>Supplier</th>
                                        <th class="text-end" style="width: 120px;">Jumlah Batch</th>
                                        <th style="width: 160px;">Estimasi Busuk</th>
                                        <th class="text-center" style="width: 140px;">Status Freshness</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rottenWarningStocksList as $index => $stock)
                                        <tr class="{{ $stock->expiry_status === 'expired' ? 'table-danger' : 'table-warning' }}">
                                            <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                            <td class="fw-semibold">
                                                {{ $stock->fruit->name ?? 'Buah Dihapus' }}
                                                @if($stock->fruit)
                                                    <small class="text-muted d-block">{{ $stock->fruit->code }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $stock->supplier->name ?? '—' }}
                                            </td>
                                            <td class="text-end fw-bold">
                                                {{ number_format($stock->quantity, 0, ',', '.') }} {{ $stock->fruit->unit ?? '' }}
                                            </td>
                                            <td>
                                                <i class="bi bi-calendar-event me-1"></i>{{ $stock->expired_at ? $stock->expired_at->format('d M Y, H:i') : '-' }}
                                            </td>
                                            <td class="text-center">
                                                @if ($stock->expiry_status === 'expired')
                                                    <span class="badge text-bg-danger fs-6 px-3 py-2"><i class="bi bi-exclamation-octagon-fill me-1"></i>BUSUK / EXPIRED</span>
                                                @elseif ($stock->expiry_status === 'near_expiry')
                                                    <span class="badge text-bg-warning text-dark fs-6 px-3 py-2"><i class="bi bi-exclamation-triangle-fill me-1"></i>HAMPIR BUSUK</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         ROW 3 — Stok Menipis + Transaksi Terbaru
    ══════════════════════════════════════════════════════════════ --}}
    <div class="row">

        {{-- ── Stok Menipis ────────────────────────────────────────── --}}
        <div class="col-lg-7 mb-3">
            <div class="card card-outline card-warning shadow-sm h-100">
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
        <div class="col-lg-5 mb-3">
            <div class="card card-outline card-primary shadow-sm h-100">
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
