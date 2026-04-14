@extends('layouts.auth')
@section('title', 'Login Super Admin')

@push('css')
<style>
    .auth-super-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: #0F172A;
        font-size: 11.5px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        margin-bottom: 16px;
        letter-spacing: 0.2px;
    }

    /* Primary color for Super Admin is Slate/Slate-900 */
    .auth-input:focus {
        border-color: #0F172A;
        box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1);
    }
    .auth-submit {
        background: #0F172A;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.3);
    }
    .auth-submit:hover {
        background: #1e293b;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.4);
    }

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
    .auth-input-toggle:hover { color: #0F172A; }

    .auth-input-wrap .auth-input {
        padding-right: 42px;
    }
</style>
@endpush

@section('content')

<div class="auth-form-header">
    <div class="auth-super-badge">
        <i class="bi bi-shield-lock-fill"></i> Super Admin Panel
    </div>
    <div class="auth-form-title">Portal Super Admin</div>
    <div class="auth-form-sub">Masuk untuk manajemen sistem</div>
</div>

@if ($errors->any())
    <div class="auth-alert">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>{{ $errors->first() }}</div>
    </div>
@endif

<form method="POST" action="{{ route('superadmin.login') }}">
    @csrf

    <div class="auth-field">
        <label class="auth-label">Username</label>
        <div class="auth-input-wrap">
            <i class="bi bi-person-fill auth-input-icon"></i>
            <input type="text" name="username"
                class="auth-input @error('username') is-invalid @enderror"
                placeholder="Masukkan username"
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
            <input type="password" id="superPassword" name="password"
                class="auth-input @error('password') is-invalid @enderror"
                placeholder="Masukkan password"
                autocomplete="current-password">
            <button type="button" class="auth-input-toggle" onclick="toggleSuperPass()">
                <i id="superPassIcon" class="bi bi-eye"></i>
            </button>
        </div>
        @error('password')
            <div class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="auth-submit">
        <i class="bi bi-box-arrow-in-right"></i> Masuk ke Dashboard
    </button>
</form>

<div class="mt-4 text-center">
    <a href="{{ route('admin.login') }}" class="text-muted text-decoration-none" style="font-size: 13px;">
        <i class="bi bi-shield-check"></i> Login sebagai Admin?
    </a>
</div>

@endsection

@push('js')
<script>
function toggleSuperPass() {
    const p = document.getElementById('superPassword');
    const i = document.getElementById('superPassIcon');
    p.type = p.type === 'password' ? 'text' : 'password';
    i.className = p.type === 'text' ? 'bi bi-eye-slash' : 'bi bi-eye';
}
</script>
@endpush
