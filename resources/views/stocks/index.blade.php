@extends('adminlte::page')

@section('title', 'Riwayat Stok')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 fw-semibold">Riwayat Stok</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Riwayat Stok</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-error">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Main Data Table Card --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-box-seam me-2"></i>Daftar Riwayat Stok
            </h3>
            <div class="card-tools">
                {{-- Button for adding new stock entry, can be wired up later --}}
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahStok" id="btn-tambah-stok">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Stok (In/Out)
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle" id="table-stok">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th style="width: 140px;">Tanggal</th>
                            <th>Buah</th>
                            <th>Supplier</th>
                            <th style="width: 100px;" class="text-center">Jenis</th>
                            <th class="text-end" style="width: 110px;">Jumlah</th>
                            <th class="text-end">Harga Satuan</th>
                            <th style="width: 140px;">Estimasi Busuk</th>
                            <th style="width: 130px;" class="text-center">Status Freshness</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $index => $stock)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    {{ $stock->recorded_at ? $stock->recorded_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="fw-semibold">
                                    {{ $stock->fruit->name ?? 'Buah Dihapus' }}
                                    @if($stock->fruit)
                                        <small class="text-muted d-block">{{ $stock->fruit->code }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($stock->supplier)
                                        {{ $stock->supplier->name }}
                                    @else
                                        <span class="text-muted fst-italic">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($stock->type === 'in')
                                        <span class="badge text-bg-success">Masuk (In)</span>
                                    @elseif ($stock->type === 'out')
                                        <span class="badge text-bg-danger">Keluar (Out)</span>
                                    @else
                                        <span class="badge text-bg-warning">Penyesuaian</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">
                                    @php
                                        $qty = (float) $stock->quantity;
                                        $formattedQty = number_format($qty, floor($qty) == $qty ? 0 : 2, ',', '.');
                                    @endphp
                                    @if ($stock->type === 'in' || $stock->type === 'adjustment' && $stock->quantity > 0)
                                        <span class="text-success">+{{ $formattedQty }}</span>
                                    @else
                                        <span class="text-danger">-{{ $formattedQty }}</span>
                                    @endif
                                    @if($stock->fruit)
                                        <small class="text-muted">{{ $stock->fruit->unit }}</small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($stock->unit_price > 0)
                                        Rp {{ number_format($stock->unit_price, 0, ',', '.') }}
                                    @else
                                        <span class="text-muted fst-italic">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($stock->expired_at)
                                        <span class="fw-semibold">{{ $stock->expired_at->format('d M Y, H:i') }}</span>
                                    @else
                                        <span class="text-muted fst-italic">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($stock->type === 'in' && $stock->expired_at)
                                        @if ($stock->expiry_status === 'expired')
                                            <span class="badge text-bg-danger"><i class="bi bi-exclamation-octagon-fill me-1"></i>Busuk</span>
                                        @elseif ($stock->expiry_status === 'near_expiry')
                                            <span class="badge text-bg-warning text-dark"><i class="bi bi-exclamation-triangle-fill me-1"></i>Hampir Busuk</span>
                                        @else
                                            <span class="badge text-bg-success"><i class="bi bi-check-circle-fill me-1"></i>Segar</span>
                                        @endif
                                    @else
                                        <span class="text-muted fst-italic">—</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $stock->note ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada data riwayat stok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: Tambah Stok ==================== --}}
    <div class="modal fade" id="modalTambahStok" tabindex="-1" aria-labelledby="modalTambahStokLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                {{-- Note: Action route below doesn't exist yet, it's just a placeholder for the frontend --}}
                <form action="{{ route('data.stocks.store') }}" method="POST" id="form-tambah-stok">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalTambahStokLabel">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Data Stok
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add-fruit-id" class="form-label fw-semibold">Buah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-basket2"></i></span>
                                    <select class="form-select" id="add-fruit-id" name="fruit_id" required>
                                        <option value="" selected disabled data-unit="-">Pilih Buah...</option>
                                        @foreach($fruits as $fruit)
                                            <option value="{{ $fruit->id }}" data-unit="{{ $fruit->unit }}">{{ $fruit->code }} - {{ $fruit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add-type" class="form-label fw-semibold">Jenis Transaksi <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-arrow-left-right"></i></span>
                                    <select class="form-select" id="add-type" name="type" required>
                                        <option value="in" selected>Stok Masuk (In)</option>
                                        <option value="out">Stok Keluar (Out)</option>
                                        <option value="adjustment">Penyesuaian (Adjustment)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add-quantity" class="form-label fw-semibold">Jumlah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-123"></i></span>
                                    <input type="number" class="form-control" id="add-quantity" name="quantity" placeholder="0" min="0" step="any" required>
                                    <span class="input-group-text fw-bold text-muted" id="add-unit-label">-</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add-unit-price" class="form-label fw-semibold">Harga Satuan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="add-unit-price" name="unit_price" placeholder="0" min="0" step="any">
                                </div>
                                <div class="form-text">Bisa dikosongkan jika tidak ada.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add-supplier-id" class="form-label fw-semibold">Supplier</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-truck"></i></span>
                                    <select class="form-select" id="add-supplier-id" name="supplier_id">
                                        <option value="" selected>Tidak ada supplier...</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add-recorded-at" class="form-label fw-semibold">Tanggal & Waktu <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="add-recorded-at" name="recorded_at" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                            </div>
                        </div>
                        <div class="row" id="wrapper-expired-at">
                            <div class="col-md-6 mb-3">
                                <label for="add-expired-at" class="form-label fw-semibold">Estimasi Busuk / Kedaluwarsa</label>
                                <input type="datetime-local" class="form-control" id="add-expired-at" name="expired_at">
                                <div class="form-text">Bisa dikosongkan untuk dihitung otomatis sesuai masa simpan buah.</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="add-note" class="form-label fw-semibold">Catatan</label>
                            <textarea class="form-control" id="add-note" name="note" rows="2" placeholder="Tambahkan catatan jika perlu..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ========== Dynamic Unit in Add Modal ==========
    const fruitSelect = document.getElementById('add-fruit-id');
    const unitLabel = document.getElementById('add-unit-label');

    if (fruitSelect && unitLabel) {
        fruitSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            unitLabel.textContent = selectedOption.getAttribute('data-unit') || '-';
        });
    }

    // ========== Dynamic Expiry Field Toggle ==========
    const typeSelect = document.getElementById('add-type');
    const wrapperExpired = document.getElementById('wrapper-expired-at');
    if (typeSelect && wrapperExpired) {
        typeSelect.addEventListener('change', function () {
            if (this.value === 'in') {
                wrapperExpired.style.display = 'flex';
            } else {
                wrapperExpired.style.display = 'none';
            }
        });
    }

    // ========== Auto-dismiss success alert after 4 seconds ==========
    const successAlert = document.getElementById('alert-success');
    if (successAlert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(successAlert);
            bsAlert.close();
        }, 4000);
    }
});
</script>
@stop
