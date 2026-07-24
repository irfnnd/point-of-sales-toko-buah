@extends('adminlte::page')

@section('title', 'Data Supplier')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 fw-semibold">Data Supplier</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Supplier</li>
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
                <i class="bi bi-truck me-2"></i>Daftar Data Supplier
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahSupplier" id="btn-tambah-supplier">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Supplier
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle" id="table-supplier">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th style="width: 120px;">Kode</th>
                            <th>Nama Supplier</th>
                            <th style="width: 180px;">Telepon</th>
                            <th>Alamat</th>
                            <th style="width: 130px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $index => $supplier)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge text-bg-secondary">{{ $supplier->code }}</span>
                                </td>
                                <td class="fw-semibold">{{ $supplier->name }}</td>
                                <td>
                                    @if ($supplier->phone)
                                        <a href="tel:{{ $supplier->phone }}" class="text-decoration-none">
                                            <i class="bi bi-telephone-fill me-1 text-muted"></i>{{ $supplier->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted fst-italic">—</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $supplier->address ?: '-' }}
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                            class="btn btn-warning btn-sm btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditSupplier"
                                            data-id="{{ $supplier->id }}"
                                            data-code="{{ $supplier->code }}"
                                            data-name="{{ $supplier->name }}"
                                            data-phone="{{ $supplier->phone }}"
                                            data-address="{{ $supplier->address }}"
                                            title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-danger btn-sm btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapusSupplier"
                                            data-id="{{ $supplier->id }}"
                                            data-name="{{ $supplier->name }}"
                                            title="Hapus">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada data supplier. Silakan tambah data supplier baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: Tambah Supplier ==================== --}}
    <div class="modal fade" id="modalTambahSupplier" tabindex="-1" aria-labelledby="modalTambahSupplierLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                {{-- Form action placeholder --}}
                <form action="#" method="POST" id="form-tambah-supplier">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalTambahSupplierLabel">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Data Supplier
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add-code" class="form-label fw-semibold">Kode Supplier <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-upc"></i></span>
                                    <input type="text" class="form-control" id="add-code" name="code" placeholder="Contoh: SUP-001" value="{{ old('code') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add-name" class="form-label fw-semibold">Nama Supplier <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text" class="form-control" id="add-name" name="name" placeholder="Nama Perusahaan/Individu" value="{{ old('name') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="add-phone" class="form-label fw-semibold">Nomor Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" class="form-control" id="add-phone" name="phone" placeholder="Contoh: 081234567890" value="{{ old('phone') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="add-address" class="form-label fw-semibold">Alamat</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <textarea class="form-control" id="add-address" name="address" rows="2" placeholder="Alamat lengkap...">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary" disabled title="Routing belum dibuat">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: Edit Supplier ==================== --}}
    <div class="modal fade" id="modalEditSupplier" tabindex="-1" aria-labelledby="modalEditSupplierLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="#" method="POST" id="form-edit-supplier">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="modalEditSupplierLabel">
                            <i class="bi bi-pencil-square me-2"></i>Edit Data Supplier
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit-code" class="form-label fw-semibold">Kode Supplier <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-upc"></i></span>
                                    <input type="text" class="form-control" id="edit-code" name="code" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit-name" class="form-label fw-semibold">Nama Supplier <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text" class="form-control" id="edit-name" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-phone" class="form-label fw-semibold">Nomor Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" class="form-control" id="edit-phone" name="phone">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-address" class="form-label fw-semibold">Alamat</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <textarea class="form-control" id="edit-address" name="address" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-warning" disabled title="Routing belum dibuat">
                            <i class="bi bi-check-lg me-1"></i>Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: Hapus Supplier ==================== --}}
    <div class="modal fade" id="modalHapusSupplier" tabindex="-1" aria-labelledby="modalHapusSupplierLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="#" method="POST" id="form-hapus-supplier">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalHapusSupplierLabel">
                            <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-trash3 text-danger" style="font-size: 3rem;"></i>
                        <p class="mt-3 mb-1">Apakah Anda yakin ingin menghapus data supplier:</p>
                        <h5 class="fw-bold text-danger" id="delete-supplier-name"></h5>
                        <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
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

    // ========== Populate Edit Modal ==========
    document.querySelectorAll('.btn-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id      = this.dataset.id;
            const code    = this.dataset.code;
            const name    = this.dataset.name;
            const phone   = this.dataset.phone;
            const address = this.dataset.address;

            // Optional: document.getElementById('form-edit-supplier').action = '{{ url("data/supplier") }}/' + id;

            document.getElementById('edit-code').value    = code;
            document.getElementById('edit-name').value    = name;
            document.getElementById('edit-phone').value   = phone;
            document.getElementById('edit-address').value = address;
        });
    });

    // ========== Populate Delete Modal ==========
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id   = this.dataset.id;
            const name = this.dataset.name;

            // Optional: document.getElementById('form-hapus-supplier').action = '{{ url("data/supplier") }}/' + id;
            document.getElementById('delete-supplier-name').textContent = name;
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
