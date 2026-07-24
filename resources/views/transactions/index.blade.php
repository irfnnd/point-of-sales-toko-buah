@extends('adminlte::page')

@section('title', 'Data Transaksi')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 fw-semibold">Data Transaksi</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Transaksi</li>
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
                <i class="bi bi-receipt me-2"></i>Riwayat Transaksi Penjualan
            </h3>
            <div class="card-tools">
                <a href="{{ route('cashier') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-cart-plus me-1"></i> Transaksi Baru (Kasir)
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle" id="table-transaction">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th style="width: 140px;">No. Invoice</th>
                            <th style="width: 160px;">Tanggal</th>
                            <th>Kasir</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Bayar</th>
                            <th class="text-end">Kembalian</th>
                            <th style="width: 100px;" class="text-center">Status</th>
                            <th style="width: 130px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $index => $transaction)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge text-bg-secondary fs-6">{{ $transaction->invoice_number }}</span>
                                </td>
                                <td>
                                    {{ $transaction->transaction_date ? $transaction->transaction_date->format('d M Y, H:i') : '-' }}
                                </td>
                                <td>
                                    @if ($transaction->user)
                                        <i class="bi bi-person me-1 text-muted"></i>{{ $transaction->user->name }}
                                    @else
                                        <span class="text-muted fst-italic">—</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">
                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="text-end text-success">
                                    Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}
                                </td>
                                <td class="text-end">
                                    Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @if(strtolower($transaction->status) === 'success' || strtolower($transaction->status) === 'sukses')
                                        <span class="badge text-bg-success">Sukses</span>
                                    @elseif(strtolower($transaction->status) === 'pending')
                                        <span class="badge text-bg-warning">Pending</span>
                                    @elseif(strtolower($transaction->status) === 'failed' || strtolower($transaction->status) === 'gagal' || strtolower($transaction->status) === 'canceled')
                                        <span class="badge text-bg-danger">Batal</span>
                                    @else
                                        <span class="badge text-bg-info">{{ ucfirst($transaction->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-info btn-sm text-white" title="Lihat Detail" disabled>
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-danger btn-sm btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapusTransaksi"
                                            data-id="{{ $transaction->id }}"
                                            data-invoice="{{ $transaction->invoice_number }}"
                                            title="Hapus / Batalkan">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                                    Belum ada data transaksi. Silakan buka menu kasir untuk melakukan transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: Hapus Transaksi ==================== --}}
    <div class="modal fade" id="modalHapusTransaksi" tabindex="-1" aria-labelledby="modalHapusTransaksiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="#" method="POST" id="form-hapus-transaksi">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalHapusTransaksiLabel">
                            <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus Data
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-trash3 text-danger" style="font-size: 3rem;"></i>
                        <p class="mt-3 mb-1">Apakah Anda yakin ingin menghapus data transaksi dengan Invoice:</p>
                        <h5 class="fw-bold text-danger" id="delete-invoice-number"></h5>
                        <p class="text-muted small">Tindakan ini tidak dapat dibatalkan dan akan mempengaruhi laporan laba-rugi.</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-danger" disabled title="Routing belum dibuat">
                            <i class="bi bi-trash3 me-1"></i>Ya, Hapus
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
    // ========== Populate Delete Modal ==========
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id      = this.dataset.id;
            const invoice = this.dataset.invoice;

            // Optional: document.getElementById('form-hapus-transaksi').action = '{{ url("data/transaksi") }}/' + id;
            document.getElementById('delete-invoice-number').textContent = invoice;
        });
    });

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
