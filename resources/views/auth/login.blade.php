@extends('adminlte::auth.auth-master', ['authType' => 'login', 'title' => 'Masuk | POS Toko Buah'])

@section('auth_body')
    <p class="login-box-msg">Masuk untuk menggunakan POS Toko Buah</p>

    <form action="{{ route('login') }}" method="post">
        @csrf

        <div class="input-group mb-3">
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="Email" required autofocus autocomplete="email">
            <div class="input-group-text"><span class="bi bi-envelope"></span></div>
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="input-group mb-3">
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Kata sandi" required autocomplete="current-password">
            <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
        </div>

        <div class="row">
            <div class="col-8">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
            </div>
        </div>
    </form>
@endsection
