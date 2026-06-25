@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="container">
    <div class="login-card">
        <div class="text-center">
            <div class="login-brand"><i class="fas fa-fish"></i></div>
            <h3 class="fw-bold mb-1">Cupang Market</h3>
            <p class="text-muted mb-4" style="font-size: 14px;">Marketplace Ikan Cupang Terpercaya</p>
        </div>

        <div id="loginForm">
            <h4 class="text-center mb-4 fw-bold" style="font-size: 20px;">Selamat Datang Kembali</h4>
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label-pro">Email</label>
                    <input type="email" name="email" class="form-pro" placeholder="nama@email.com" required value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label-pro">Password</label>
                    <input type="password" name="password" class="form-pro" placeholder="Minimal 6 karakter" required>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger rounded-3 mb-3" style="font-size: 14px;">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                    </div>
                @endif
                <button type="submit" class="btn-main btn-primary-main w-100 py-3 fw-bold">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>
            <div class="text-center mt-4">
                <p class="text-muted mb-0" style="font-size: 14px;">Belum punya akun? <a href="#" class="fw-bold text-decoration-none" style="color: var(--primary);" onclick="toggleAuth('register')">Daftar sekarang</a></p>
            </div>
        </div>

        <div id="registerForm" style="display: none;">
            <h4 class="text-center mb-4 fw-bold" style="font-size: 20px;">Buat Akun Baru</h4>
            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label-pro">Nama Lengkap</label>
                    <input type="text" name="name" class="form-pro" placeholder="Nama Anda" required value="{{ old('name') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label-pro">Email</label>
                    <input type="email" name="email" class="form-pro" placeholder="nama@email.com" required value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label-pro">Password</label>
                    <input type="password" name="password" class="form-pro" placeholder="Minimal 6 karakter" required>
                </div>
                <div class="mb-3">
                    <label class="form-label-pro">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" class="form-pro" placeholder="Ulangi password" required>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger rounded-3 mb-3" style="font-size: 14px;">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                    </div>
                @endif
                <button type="submit" class="btn-main btn-success-main w-100 py-3 fw-bold">
                    <i class="fas fa-user-plus"></i> Registrasi Akun
                </button>
            </form>
            <div class="text-center mt-4">
                <p class="text-muted mb-0" style="font-size: 14px;">Sudah punya akun? <a href="#" class="fw-bold text-decoration-none" style="color: var(--primary);" onclick="toggleAuth('login')">Masuk di sini</a></p>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function toggleAuth(mode) {
        document.getElementById('loginForm').style.display = mode === 'login' ? 'block' : 'none';
        document.getElementById('registerForm').style.display = mode === 'register' ? 'block' : 'none';
    }
</script>
@endsection
@endsection