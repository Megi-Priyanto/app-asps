@extends('layouts.auth')
@section('title', 'Login Admin')

@push('css')
<style>
    .auth-admin-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
        color: #1E40AF;
        font-size: 11.5px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        margin-bottom: 16px;
        letter-spacing: 0.2px;
    }

    /* Tombol toggle password */
    .auth-input-toggle {
        position: absolute;
        right: 13px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94A3B8;
        cursor: pointer;
        font-size: 15px;
        padding: 2px;
        line-height: 1;
        transition: color 0.2s;
        z-index: 2;
    }
    .auth-input-toggle:hover { color: #1E40AF; }

    /* Beri ruang kanan agar teks tidak ketutup tombol */
    .auth-input-wrap .auth-input {
        padding-right: 42px;
    }

    .auth-input:focus {
        border-color: #1E40AF;
        box-shadow: 0 0 0 3px rgba(30,64,175,0.1);
    }
    .auth-submit {
        background: #1E40AF;
        box-shadow: 0 4px 14px rgba(30,64,175,0.3);
    }
    .auth-submit:hover {
        background: #1e3a8a;
        box-shadow: 0 6px 20px rgba(30,64,175,0.4);
    }
</style>
@endpush

@section('content')

<div class="auth-form-header">
    <div class="auth-admin-badge">
        <i class="bi bi-shield-lock-fill"></i> Admin Panel
    </div>
    <div class="auth-form-title">Selamat Datang</div>
    <div class="auth-form-sub">Masuk ke dashboard admin</div>
</div>

@if ($errors->any())
    <div class="auth-alert">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>{{ $errors->first() }}</div>
    </div>
@endif

<form method="POST" action="{{ route('admin.login') }}">
    @csrf

    <div class="auth-field">
        <label class="auth-label">Username</label>
        <div class="auth-input-wrap">
            <i class="bi bi-person-fill auth-input-icon"></i>
            <input type="text" name="username"
                class="auth-input @error('username') is-invalid @enderror"
                placeholder="Masukkan username admin"
                value="{{ old('username') }}"
                autocomplete="username" autofocus>
        </div>
        @error('username')
            <div class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
        @enderror
    </div>

    <div class="auth-field">
        <label class="auth-label">Password</label>
        <div class="auth-input-wrap">
            <i class="bi bi-lock-fill auth-input-icon"></i>
            <input type="password" id="adminPassword" name="password"
                class="auth-input @error('password') is-invalid @enderror"
                placeholder="Masukkan password"
                autocomplete="current-password">
            <button type="button" class="auth-input-toggle" onclick="toggleAdminPass()">
                <i id="adminPassIcon" class="bi bi-eye"></i>
            </button>
        </div>
        @error('password')
            <div class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="auth-submit">
        <i class="bi bi-shield-check"></i> Masuk ke Dashboard
    </button>
</form>

@endsection

@push('js')
<script>
function toggleAdminPass() {
    const p = document.getElementById('adminPassword');
    const i = document.getElementById('adminPassIcon');
    p.type = p.type === 'password' ? 'text' : 'password';
    i.className = p.type === 'text' ? 'bi bi-eye-slash' : 'bi bi-eye';
}
</script>
@endpush