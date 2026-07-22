@extends('adminlte::page')

@section('title', 'Data Buah')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 fw-semibold">Data Buah</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Buah</li>
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
                <i class="bi bi-basket2 me-2"></i>Daftar Data Buah
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahBuah" id="btn-tambah-buah">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Buah
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle" id="table-buah">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th style="width: 100px;">Kode</th>
                            <th>Nama Buah</th>
                            <th>Kategori</th>
                            <th style="width: 80px;" class="text-center">Satuan</th>
                            <th class="text-end">Harga Beli</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-end">Margin</th>
                            <th style="width: 130px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fruits as $index => $fruit)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge text-bg-secondary">{{ $fruit->code }}</span>
                                </td>
                                <td class="fw-semibold">{{ $fruit->name }}</td>
                                <td>
                                    @if ($fruit->category)
                                        <span class="badge text-bg-info">{{ $fruit->category }}</span>
                                    @else
                                        <span class="text-muted fst-italic">—</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $fruit->unit }}</td>
                                <td class="text-end">Rp {{ number_format($fruit->purchase_price, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($fruit->selling_price, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    @php $margin = $fruit->selling_price - $fruit->purchase_price; @endphp
                                    <span class="{{ $margin >= 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                                        Rp {{ number_format($margin, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                            class="btn btn-warning btn-sm btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditBuah"
                                            data-id="{{ $fruit->id }}"
                                            data-code="{{ $fruit->code }}"
                                            data-name="{{ $fruit->name }}"
                                            data-category="{{ $fruit->category }}"
                                            data-unit="{{ $fruit->unit }}"
                                            data-purchase-price="{{ $fruit->purchase_price }}"
                                            data-selling-price="{{ $fruit->selling_price }}"
                                            title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-danger btn-sm btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapusBuah"
                                            data-id="{{ $fruit->id }}"
                                            data-name="{{ $fruit->name }}"
                                            title="Hapus">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada data buah. Silakan tambah data buah baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: Tambah Buah ==================== --}}
    <div class="modal fade" id="modalTambahBuah" tabindex="-1" aria-labelledby="modalTambahBuahLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('data.fruits.store') }}" method="POST" id="form-tambah-buah">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalTambahBuahLabel">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Data Buah
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add-code" class="form-label fw-semibold">Kode Buah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-upc"></i></span>
                                    <input type="text" class="form-control" id="add-code" name="code" placeholder="Contoh: BH001" value="{{ old('code') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add-name" class="form-label fw-semibold">Nama Buah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-basket2"></i></span>
                                    <input type="text" class="form-control" id="add-name" name="name" placeholder="Contoh: Apel Fuji" value="{{ old('name') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add-category" class="form-label fw-semibold">Kategori</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tags"></i></span>
                                    <input type="text" class="form-control" id="add-category" name="category" placeholder="Contoh: Buah Impor" value="{{ old('category') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add-unit" class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-rulers"></i></span>
                                    <select class="form-select" id="add-unit" name="unit" required>
                                        <option value="kg" {{ old('unit', 'kg') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                        <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram (gram)</option>
                                        <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pcs / Buah</option>
                                        <option value="ikat" {{ old('unit') == 'ikat' ? 'selected' : '' }}>Ikat</option>
                                        <option value="sisir" {{ old('unit') == 'sisir' ? 'selected' : '' }}>Sisir</option>
                                        <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add-purchase-price" class="form-label fw-semibold">Harga Beli <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="add-purchase-price" name="purchase_price" placeholder="0" value="{{ old('purchase_price') }}" min="0" step="any" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add-selling-price" class="form-label fw-semibold">Harga Jual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="add-selling-price" name="selling_price" placeholder="0" value="{{ old('selling_price') }}" min="0" step="any" required>
                                </div>
                            </div>
                        </div>
                        {{-- Margin preview --}}
                        <div class="alert alert-light border d-flex align-items-center" id="add-margin-preview">
                            <i class="bi bi-info-circle-fill text-primary me-2 fs-5"></i>
                            <span>Margin: <strong id="add-margin-value">Rp 0</strong></span>
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

    {{-- ==================== MODAL: Edit Buah ==================== --}}
    <div class="modal fade" id="modalEditBuah" tabindex="-1" aria-labelledby="modalEditBuahLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" id="form-edit-buah">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="modalEditBuahLabel">
                            <i class="bi bi-pencil-square me-2"></i>Edit Data Buah
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit-code" class="form-label fw-semibold">Kode Buah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-upc"></i></span>
                                    <input type="text" class="form-control" id="edit-code" name="code" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit-name" class="form-label fw-semibold">Nama Buah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-basket2"></i></span>
                                    <input type="text" class="form-control" id="edit-name" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit-category" class="form-label fw-semibold">Kategori</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tags"></i></span>
                                    <input type="text" class="form-control" id="edit-category" name="category">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit-unit" class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-rulers"></i></span>
                                    <select class="form-select" id="edit-unit" name="unit" required>
                                        <option value="kg">Kilogram (kg)</option>
                                        <option value="gram">Gram (gram)</option>
                                        <option value="pcs">Pcs / Buah</option>
                                        <option value="ikat">Ikat</option>
                                        <option value="sisir">Sisir</option>
                                        <option value="pack">Pack</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit-purchase-price" class="form-label fw-semibold">Harga Beli <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="edit-purchase-price" name="purchase_price" min="0" step="any" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit-selling-price" class="form-label fw-semibold">Harga Jual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="edit-selling-price" name="selling_price" min="0" step="any" required>
                                </div>
                            </div>
                        </div>
                        {{-- Margin preview --}}
                        <div class="alert alert-light border d-flex align-items-center" id="edit-margin-preview">
                            <i class="bi bi-info-circle-fill text-primary me-2 fs-5"></i>
                            <span>Margin: <strong id="edit-margin-value">Rp 0</strong></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-lg me-1"></i>Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: Hapus Buah ==================== --}}
    <div class="modal fade" id="modalHapusBuah" tabindex="-1" aria-labelledby="modalHapusBuahLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" id="form-hapus-buah">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalHapusBuahLabel">
                            <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-trash3 text-danger" style="font-size: 3rem;"></i>
                        <p class="mt-3 mb-1">Apakah Anda yakin ingin menghapus data buah:</p>
                        <h5 class="fw-bold text-danger" id="delete-fruit-name"></h5>
                        <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
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

    // ========== Margin calculator helper ==========
    function formatRupiah(num) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
    }

    function calcMargin(purchaseEl, sellingEl, displayEl) {
        const purchase = parseFloat(purchaseEl.value) || 0;
        const selling  = parseFloat(sellingEl.value) || 0;
        const margin   = selling - purchase;
        displayEl.textContent = formatRupiah(margin);
        displayEl.closest('.alert').classList.toggle('alert-success', margin > 0);
        displayEl.closest('.alert').classList.toggle('alert-danger', margin < 0);
        displayEl.closest('.alert').classList.toggle('alert-light', margin === 0);
    }

    // ========== Add modal: live margin preview ==========
    const addPurchase = document.getElementById('add-purchase-price');
    const addSelling  = document.getElementById('add-selling-price');
    const addMarginEl = document.getElementById('add-margin-value');

    if (addPurchase && addSelling) {
        addPurchase.addEventListener('input', () => calcMargin(addPurchase, addSelling, addMarginEl));
        addSelling.addEventListener('input',  () => calcMargin(addPurchase, addSelling, addMarginEl));
    }

    // ========== Edit modal: live margin preview ==========
    const editPurchase = document.getElementById('edit-purchase-price');
    const editSelling  = document.getElementById('edit-selling-price');
    const editMarginEl = document.getElementById('edit-margin-value');

    if (editPurchase && editSelling) {
        editPurchase.addEventListener('input', () => calcMargin(editPurchase, editSelling, editMarginEl));
        editSelling.addEventListener('input',  () => calcMargin(editPurchase, editSelling, editMarginEl));
    }

    // ========== Populate Edit Modal ==========
    document.querySelectorAll('.btn-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id    = this.dataset.id;
            const code  = this.dataset.code;
            const name  = this.dataset.name;
            const cat   = this.dataset.category;
            const unit  = this.dataset.unit;
            const pp    = this.dataset.purchasePrice;
            const sp    = this.dataset.sellingPrice;

            document.getElementById('form-edit-buah').action =
                '{{ url("data/buah") }}/' + id;

            document.getElementById('edit-code').value  = code;
            document.getElementById('edit-name').value  = name;
            document.getElementById('edit-category').value = cat;
            document.getElementById('edit-unit').value  = unit;
            editPurchase.value = pp;
            editSelling.value  = sp;

            calcMargin(editPurchase, editSelling, editMarginEl);
        });
    });

    // ========== Populate Delete Modal ==========
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id   = this.dataset.id;
            const name = this.dataset.name;

            document.getElementById('form-hapus-buah').action =
                '{{ url("data/buah") }}/' + id;
            document.getElementById('delete-fruit-name').textContent = name;
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
