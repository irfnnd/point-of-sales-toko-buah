@extends('adminlte::page')

@section('title', 'Dashboard - POS Toko Buah')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 fw-bold text-dark fs-4">Dashboard</h1>
            <small class="text-muted">Ikhtisar penjualan, stok buah, dan performa toko hari ini</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    {{-- ══════════════════════════════════════════════════════════════
         HERO WELCOME BANNER
    ══════════════════════════════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden text-white" style="background: linear-gradient(135deg, #198754 0%, #0d5c36 100%);">
        <div class="card-body p-4 position-relative">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace me-2" style="font-size: 0.75rem;">POS TOKO BUAH</span>
                        <small class="text-white text-opacity-75"><i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, d F Y') }}</small>
                    </div>
                    <h3 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name ?? 'Kasir' }}! 👋</h3>
                    <p class="mb-0 text-white text-opacity-75" style="font-size: 0.9rem;">
                        Sistem kasir siap digunakan. Hari ini terdapat <strong class="text-white">{{ $totalTransactionsToday }} transaksi</strong> dengan total pendapatan <strong class="text-warning fw-bold">Rp {{ number_format($revenueToday, 0, ',', '.') }}</strong>.
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('cashier') }}" class="btn btn-warning btn-md fw-bold rounded-pill px-4 shadow-sm py-2 text-dark me-2">
                        <i class="bi bi-cart3 me-1"></i> Buka Kasir POS
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         SUMMARY METRIC CARDS (4 COLUMNS)
    ══════════════════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        {{-- Card 1: Total Buah --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 stat-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Buah</span>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-basket2-fill fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">{{ $totalFruits }} <small class="text-muted fs-6 fw-normal">jenis</small></h3>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">Katalog Produk</small>
                        <a href="{{ route('data.fruits') }}" class="text-primary text-decoration-none fw-semibold" style="font-size: 0.78rem;">
                            Detail <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Pendapatan Hari Ini --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 stat-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Omset Hari Ini</span>
                        <div class="stat-icon bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-cash-coin fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-1" style="font-size: 1.35rem;">Rp {{ number_format($revenueToday, 0, ',', '.') }}</h3>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">{{ $totalTransactionsToday }} Transaksi</small>
                        <a href="{{ route('reports.sales') }}" class="text-success text-decoration-none fw-semibold" style="font-size: 0.78rem;">
                            Laporan <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Stok Menipis --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 stat-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Stok Menipis</span>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">{{ $totalLowStock }} <small class="text-muted fs-6 fw-normal">produk</small></h3>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">Batas &lt; 10 unit</small>
                        <a href="{{ route('data.stocks') }}" class="text-warning text-darken-2 text-decoration-none fw-semibold" style="font-size: 0.78rem;">
                            Cek Stok <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Peringatan Busuk / Kedaluwarsa --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 stat-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Warning Busuk</span>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-exclamation-octagon-fill fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold {{ $totalRottenWarningCount > 0 ? 'text-danger' : 'text-dark' }} mb-1">{{ $totalRottenWarningCount }} <small class="text-muted fs-6 fw-normal">batch</small></h3>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">{{ $totalExpiredStocksCount }} busuk, {{ $totalNearExpiryStocksCount }} hampir</small>
                        <a href="{{ route('data.stocks') }}" class="text-danger text-decoration-none fw-semibold" style="font-size: 0.78rem;">
                            Kelola <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         PERINGATAN KEDALUWARSA / FRESHNESS STOK
    ══════════════════════════════════════════════════════════════ --}}
    @if($totalRottenWarningCount > 0)
        <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
            <div class="card-header bg-danger bg-opacity-10 border-bottom border-danger border-opacity-25 py-2.5 px-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-octagon-fill text-danger fs-5 me-2"></i>
                    <h6 class="fw-bold text-danger mb-0" style="font-size: 0.92rem;">
                        Peringatan Stok Buah Busuk & Hampir Busuk
                        <span class="badge bg-danger text-white rounded-pill ms-2" style="font-size: 0.7rem;">{{ $totalRottenWarningCount }} Batch</span>
                    </h6>
                </div>
                <a href="{{ route('data.stocks') }}" class="btn btn-danger btn-sm rounded-pill fw-bold" style="font-size: 0.75rem;">
                    <i class="bi bi-box-seam me-1"></i> Kelola Stok
                </a>
            </div>
            <div class="card-body p-0" style="max-height: 270px; overflow-y: auto;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.82rem;">
                        <thead class="bg-light text-muted text-uppercase sticky-top" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <tr>
                                <th class="ps-3 py-2" style="width: 40px;">No</th>
                                <th class="py-2">Nama Buah</th>
                                <th class="py-2">Supplier</th>
                                <th class="text-end py-2" style="width: 120px;">Jumlah Batch</th>
                                <th class="py-2" style="width: 160px;">Estimasi Busuk</th>
                                <th class="text-center py-2 pe-3" style="width: 140px;">Freshness Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rottenWarningStocksList as $index => $stock)
                                <tr class="{{ $stock->expiry_status === 'expired' ? 'bg-danger bg-opacity-10' : 'bg-warning bg-opacity-10' }}">
                                    <td class="ps-3 fw-bold text-secondary">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $stock->fruit->name ?? 'Buah Dihapus' }}</span>
                                        @if($stock->fruit)
                                            <small class="text-muted d-block font-monospace" style="font-size: 0.7rem;">{{ $stock->fruit->code }}</small>
                                        @endif
                                    </td>
                                    <td class="text-secondary">
                                        {{ $stock->supplier->name ?? '—' }}
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        {{ number_format($stock->quantity, 0, ',', '.') }} {{ $stock->fruit->unit ?? '' }}
                                    </td>
                                    <td class="text-secondary">
                                        <i class="bi bi-calendar-event me-1"></i>{{ $stock->expired_at ? $stock->expired_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="text-center pe-3">
                                        @if ($stock->expiry_status === 'expired')
                                            <span class="badge bg-danger rounded-pill px-2.5 py-1" style="font-size: 0.72rem;"><i class="bi bi-exclamation-octagon-fill me-1"></i>EXPIRED / BUSUK</span>
                                        @elseif ($stock->expiry_status === 'near_expiry')
                                            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1" style="font-size: 0.72rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i>HAMPIR BUSUK</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         ROW 3 — STOK MENIPIS + TRANSAKSI TERBARU (FIXED HEIGHT 380px)
    ══════════════════════════════════════════════════════════════ --}}
    <div class="row g-3">
        {{-- ── Stok Menipis ────────────────────────────────────────── --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 d-flex flex-column bg-white" style="height: 380px;">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-2 p-1 me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                            <i class="bi bi-exclamation-triangle-fill fs-6"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.92rem;">Stok Menipis</h6>
                        @if($totalLowStock > 0)
                            <span class="badge bg-danger rounded-pill ms-2" style="font-size: 0.7rem;">{{ $totalLowStock }}</span>
                        @endif
                    </div>
                    <a href="{{ route('data.stocks') }}" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold" style="font-size: 0.75rem;">
                        <i class="bi bi-box-seam me-1"></i> Kelola Stok
                    </a>
                </div>
                <div class="card-body p-0 flex-grow-1" style="overflow-y: auto; height: 310px; max-height: 310px;">
                    @if($lowStockFruits->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-patch-check-fill fs-1 text-success d-block mb-2 opacity-75"></i>
                            <span style="font-size: 0.88rem;">Semua stok dalam kondisi aman</span>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.82rem;">
                                <thead class="bg-light text-muted text-uppercase sticky-top" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    <tr>
                                        <th class="ps-3 py-2" style="width: 40px;">No</th>
                                        <th class="py-2">Nama Buah</th>
                                        <th class="text-end py-2" style="width: 120px;">Sisa Stok</th>
                                        <th class="text-center py-2 pe-3" style="width: 100px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockFruits as $index => $fruit)
                                        @php
                                            $qty = $fruit->net_quantity;
                                            if ($qty <= 0) {
                                                $badgeClass = 'bg-danger';
                                                $status = 'Habis';
                                            } elseif ($qty <= 3) {
                                                $badgeClass = 'bg-danger';
                                                $status = 'Kritis';
                                            } else {
                                                $badgeClass = 'bg-warning text-dark';
                                                $status = 'Menipis';
                                            }
                                        @endphp
                                        <tr>
                                            <td class="ps-3 fw-bold text-secondary">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="fw-bold text-dark">{{ $fruit->name }}</span>
                                                <small class="text-muted d-block font-monospace" style="font-size: 0.7rem;">{{ $fruit->code }}</small>
                                            </td>
                                            <td class="text-end fw-bold">
                                                @if($qty <= 0)
                                                    <span class="text-danger">0 {{ $fruit->unit }}</span>
                                                @else
                                                    <span class="{{ $qty <= 3 ? 'text-danger' : 'text-warning text-darken-2' }}">
                                                        {{ number_format($qty, 2, ',', '.') }} {{ $fruit->unit }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center pe-3">
                                                <span class="badge {{ $badgeClass }} rounded-pill px-2 py-1" style="font-size: 0.7rem;">{{ $status }}</span>
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
            <div class="card border-0 shadow-sm rounded-3 d-flex flex-column bg-white" style="height: 380px;">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-2 p-1 me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                            <i class="bi bi-clock-history fs-6"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.92rem;">Transaksi Terbaru</h6>
                    </div>
                    <a href="{{ route('data.transactions') }}" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold" style="font-size: 0.75rem;">
                        <i class="bi bi-receipt me-1"></i> Semua
                    </a>
                </div>
                <div class="card-body p-0 flex-grow-1" style="overflow-y: auto; height: 310px; max-height: 310px;">
                    @if($recentTransactions->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-1 d-block mb-2 opacity-50"></i>
                            <span style="font-size: 0.88rem;">Belum ada transaksi hari ini</span>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.82rem;">
                                <thead class="bg-light text-muted text-uppercase sticky-top" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    <tr>
                                        <th class="ps-3 py-2">Invoice</th>
                                        <th class="text-end py-2">Total</th>
                                        <th class="text-center py-2 pe-3" style="width: 85px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $txn)
                                        @php
                                            $statusClass = match($txn->status ?? 'paid') {
                                                'paid' => 'bg-success',
                                                'pending' => 'bg-warning text-dark',
                                                'cancel' => 'bg-secondary',
                                                default => 'bg-secondary',
                                            };
                                            $statusLabel = match($txn->status ?? 'paid') {
                                                'paid' => 'Lunas',
                                                'pending' => 'Pending',
                                                'cancel' => 'Batal',
                                                default => ucfirst($txn->status ?? '-'),
                                            };
                                        @endphp
                                        <tr>
                                            <td class="ps-3">
                                                <span class="fw-bold text-dark font-monospace" style="font-size: 0.8rem;">{{ $txn->invoice_number }}</span>
                                                <small class="text-muted d-block" style="font-size: 0.7rem;">
                                                    {{ $txn->transaction_date?->format('d M Y, H:i') ?? '—' }}
                                                </small>
                                            </td>
                                            <td class="text-end fw-bold text-success" style="font-size: 0.82rem;">
                                                Rp {{ number_format($txn->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center pe-3">
                                                <span class="badge {{ $statusClass }} rounded-pill px-2 py-1" style="font-size: 0.7rem;">{{ $statusLabel }}</span>
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

@section('css')
<style>
    /* Styling Dashboard Modern */
    .stat-card {
        transition: all 0.25s ease-in-out;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.08) !important;
    }
    .stat-icon {
        transition: transform 0.25s ease;
    }
    .stat-card:hover .stat-icon {
        transform: scale(1.1);
    }
</style>
@stop
