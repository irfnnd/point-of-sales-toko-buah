@extends('adminlte::page')

@section('title', 'Data Pengguna')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 fw-semibold">Data Pengguna</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Pengguna</li>
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
                <i class="bi bi-people me-2"></i>Daftar Pengguna (User)
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser" id="btn-tambah-user">
                    <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle" id="table-user">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th>Nama Lengkap</th>
                            <th>Alamat Email</th>
                            <th style="width: 180px;">Terdaftar Pada</th>
                            <th style="width: 130px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-semibold">
                                    <i class="bi bi-person-circle text-muted me-2"></i>{{ $user->name }}
                                </td>
                                <td>
                                    <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                        {{ $user->email }}
                                    </a>
                                </td>
                                <td>
                                    {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                            class="btn btn-warning btn-sm btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditUser"
                                            data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}"
                                            title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @if(auth()->id() !== $user->id)
                                        <button type="button"
                                                class="btn btn-danger btn-sm btn-delete"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalHapusUser"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-secondary btn-sm" disabled title="Tidak dapat menghapus diri sendiri">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    Belum ada data pengguna.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: Tambah User ==================== --}}
    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                {{-- Form action placeholder --}}
                <form action="#" method="POST" id="form-tambah-user">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalTambahUserLabel">
                            <i class="bi bi-person-plus-fill me-2"></i>Tambah Data Pengguna
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add-name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="add-name" name="name" placeholder="Nama lengkap..." value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="add-email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="add-email" name="email" placeholder="email@contoh.com" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="add-password" class="form-label fw-semibold">Kata Sandi (Password) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="add-password" name="password" placeholder="Minimal 8 karakter" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="add-password-confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" class="form-control" id="add-password-confirmation" name="password_confirmation" placeholder="Ulangi kata sandi" required>
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

    {{-- ==================== MODAL: Edit User ==================== --}}
    <div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="#" method="POST" id="form-edit-user">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="modalEditUserLabel">
                            <i class="bi bi-pencil-square me-2"></i>Edit Data Pengguna
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="edit-name" name="name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="edit-email" name="email" required>
                            </div>
                        </div>
                        <div class="alert alert-info py-2">
                            <i class="bi bi-info-circle me-2"></i>Kosongkan form kata sandi di bawah jika tidak ingin mengubah kata sandi.
                        </div>
                        <div class="mb-3">
                            <label for="edit-password" class="form-label fw-semibold">Kata Sandi Baru</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="edit-password" name="password" placeholder="Biarkan kosong jika tidak diubah">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-password-confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi Baru</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" class="form-control" id="edit-password-confirmation" name="password_confirmation" placeholder="Ulangi kata sandi">
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

    {{-- ==================== MODAL: Hapus User ==================== --}}
    <div class="modal fade" id="modalHapusUser" tabindex="-1" aria-labelledby="modalHapusUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="#" method="POST" id="form-hapus-user">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalHapusUserLabel">
                            <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-person-x-fill text-danger" style="font-size: 3rem;"></i>
                        <p class="mt-3 mb-1">Apakah Anda yakin ingin menghapus pengguna:</p>
                        <h5 class="fw-bold text-danger" id="delete-user-name"></h5>
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
            const id    = this.dataset.id;
            const name  = this.dataset.name;
            const email = this.dataset.email;

            // Optional: document.getElementById('form-edit-user').action = '{{ url("data/user") }}/' + id;

            document.getElementById('edit-name').value  = name;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-password').value = '';
            document.getElementById('edit-password-confirmation').value = '';
        });
    });

    // ========== Populate Delete Modal ==========
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id   = this.dataset.id;
            const name = this.dataset.name;

            // Optional: document.getElementById('form-hapus-user').action = '{{ url("data/user") }}/' + id;
            document.getElementById('delete-user-name').textContent = name;
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
