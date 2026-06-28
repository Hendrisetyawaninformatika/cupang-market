@extends('layouts.app')

@section('title', 'Login - Cupang Market')

@section('content')
<meta name="viewport" content="width=1024">
<style>
    body{
        background: linear-gradient(135deg,#0f172a,#1e293b,#334155);
        min-height:100vh;
        font-family: 'Poppins', sans-serif;
    }

    .auth-container{
        min-height:100vh;
        display:flex;
        justify-content:center;
        align-items:center;
        padding:30px;
    }

    .auth-card{
        width:100%;
        max-width:480px;

        padding:40px;

        color:#fff;
    }

    .brand-icon{
        width:100px;
        height:100px;
        background:linear-gradient(135deg,#06b6d4,#3b82f6);
        border-radius:50%;
        margin:auto;
        display:flex;
        justify-content:center;
        align-items:center;
        font-size:45px;
        margin-bottom:20px;
        box-shadow:0 5px 20px rgba(59,130,246,.4);
    }

    .form-pro{
        width:100%;
        padding:14px 18px;
        border:none;
        border-radius:12px;
        background:rgba(255,255,255,.15);
        color:white;
        outline:none;
        margin-top:8px;
    }

    .form-pro::placeholder{
        color:#ddd;
    }

    .form-pro:focus{
        background:rgba(255,255,255,.25);
    }

    .form-label-pro{
        font-weight:600;
        margin-bottom:5px;
    }

    .btn-main{
        width:100%;
        border:none;
        padding:15px;
        border-radius:12px;
        font-weight:bold;
        transition:.3s;
    }

    .btn-login{
        background:#10b981;
        color:white;
    }

    .btn-login:hover{
        background:#059669;
        transform:translateY(-2px);
    }

    .btn-register{
        background:#f59e0b;
        color:white;
    }

    .btn-register:hover{
        background:#d97706;
        transform:translateY(-2px);
    }

    .switch-link{
        color:#38bdf8;
        text-decoration:none;
        font-weight:bold;
    }

    .switch-link:hover{
        color:#7dd3fc;
    }

    .alert-custom{
        background:rgba(239,68,68,.2);
        padding:12px;
        border-radius:10px;
        margin-bottom:15px;
        color:#fff;
    }

    .alert-success-custom{
        background:rgba(16,185,129,.2);
        padding:12px;
        border-radius:10px;
        margin-bottom:15px;
        color:#fff;
    }

    .text-muted-custom{
        color:#cbd5e1;
    }

    /* ── Google Login ── */
    .divider-or {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 24px 0;
        color: #94a3b8;
        font-size: 14px;
    }

    .divider-or::before,
    .divider-or::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid rgba(255,255,255,0.15);
    }

    .divider-or::before { margin-right: 12px; }
    .divider-or::after  { margin-left: 12px; }

    .btn-google {
        width: 100%;
        border: none;
        padding: 14px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        transition: .3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        text-decoration: none;
        background: #ffffff;
        color: #333;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .btn-google:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
        color: #333;
    }

    .btn-google i {
        color: #ea4335;
        font-size: 18px;
    }
</style>

<div class="auth-container">

    <div class="auth-card">

        <div class="text-center">
            <div class="brand-icon">
                <i class="fas fa-fish"></i>
            </div>

            <h2 class="fw-bold">Cupang Market</h2>
            <p class="text-muted-custom">
                Marketplace Ikan Cupang Terpercaya
            </p>
        </div>

        {{-- Flash Messages --}}
        @if(session('error'))
        <div class="alert-custom">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert-success-custom">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
        @endif

        {{-- LOGIN --}}
        <div id="loginForm">

            <h4 class="text-center mb-4">
                Selamat Datang Kembali
            </h4>

            

    

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label-pro">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-pro"
                        placeholder="Masukkan email"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label-pro">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-pro"
                        placeholder="Masukkan password"
                        required>
                </div>

                @if($errors->any())
                <div class="alert-custom">
                    {{ $errors->first() }}
                </div>
                @endif

                <button class="btn-main btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk
                </button>

            </form>

            <div class="text-center mt-4">
                Belum punya akun?

                <a href="#"
                    class="switch-link"
                    onclick="toggleAuth('register')">
                    Daftar Sekarang
                </a>
            </div>

        </div>

        {{-- REGISTER --}}
        <div id="registerForm" style="display:none;">

            <h4 class="text-center mb-4">
                Buat Akun Baru
            </h4>

            

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label-pro">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-pro"
                        placeholder="Masukkan nama lengkap"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label-pro">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-pro"
                        placeholder="Masukkan email"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label-pro">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-pro"
                        placeholder="Minimal 6 karakter"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label-pro">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        name="confirm_password"
                        class="form-pro"
                        placeholder="Ulangi password"
                        required>
                </div>

                <button class="btn-main btn-register">
                    <i class="fas fa-user-plus"></i>
                    Daftar
                </button>

            </form>

            <div class="text-center mt-4">
                Sudah punya akun?

                <a href="#"
                    class="switch-link"
                    onclick="toggleAuth('login')">
                    Login Sekarang
                </a>
            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>

function toggleAuth(mode){
    if(mode === 'login'){
        document.getElementById('loginForm').style.display='block';
        document.getElementById('registerForm').style.display='none';
    }else{
        document.getElementById('loginForm').style.display='none';
        document.getElementById('registerForm').style.display='block';
    }
}

</script>

@endsection
