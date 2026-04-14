@extends('layouts.auth')
@section('title', 'Masuk Akun')

@section('content')

<div class="auth-form-header">
    <div class="auth-form-icon">
        <i class="bi bi-box-arrow-in-right"></i>
    </div>
    <div class="auth-form-title">Selamat Datang!</div>
    <div class="auth-form-sub">Pilih jenis akun untuk melanjutkan.</div>
</div>

{{-- Pilihan Role --}}
<div class="role-selector" id="roleSelector">
    <button type="button" class="role-btn active" id="btnSiswa" onclick="switchRole('siswa')">
        <i class="bi bi-mortarboard-fill"></i>
        <span>Siswa</span>
    </button>
    <button type="button" class="role-btn" id="btnGuru" onclick="switchRole('guru')">
        <i class="bi bi-person-video3"></i>
        <span>Guru</span>
    </button>
    <button type="button" class="role-btn" id="btnPegawai" onclick="switchRole('pegawai')">
        <i class="bi bi-person-badge-fill"></i>
        <span>Pegawai</span>
    </button>
</div>

{{-- Error global --}}
@if ($errors->any())
    <div class="auth-alert">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>{{ $errors->first() }}</div>
    </div>
@endif

{{-- Form Login Siswa --}}
<div id="formSiswa">
    <form method="POST" action="{{ route('siswa.login') }}">
        @csrf

        <div class="auth-field">
            <label class="auth-label">Nomor Induk Siswa (NIS)</label>
            <div class="auth-input-wrap">
                <i class="bi bi-person-fill auth-input-icon"></i>
                <input
                    type="text"
                    name="nis"
                    class="auth-input @error('nis') is-invalid @enderror"
                    placeholder="Masukkan NIS kamu..."
                    value="{{ old('nis') }}"
                    autocomplete="off"
                    autofocus>
            </div>
            @error('nis')
                <div class="auth-error">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="auth-field">
            <label class="auth-label">Password</label>
            <div class="auth-input-wrap">
                <i class="bi bi-lock-fill auth-input-icon"></i>
                <input
                    type="password"
                    name="password"
                    class="auth-input @error('password') is-invalid @enderror"
                    placeholder="Masukkan password kamu...">
            </div>
            @error('password')
                <div class="auth-error">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="auth-submit btn-primary-blue">
            <i class="bi bi-box-arrow-in-right"></i> Loginsebagai Siswa
        </button>
    </form>
</div>

{{-- Form Login Guru --}}
<div id="formGuru" style="display: none;">
    <form method="POST" action="{{ route('guru.login') }}">
        @csrf

        <div class="auth-field">
            <label class="auth-label">NIP Guru</label>
            <div class="auth-input-wrap">
                <i class="bi bi-person-fill auth-input-icon"></i>
                <input
                    type="text"
                    name="nip"
                    class="auth-input @error('nip') is-invalid @enderror"
                    placeholder="Masukkan NIP kamu..."
                    value="{{ old('nip') }}"
                    autocomplete="off">
            </div>
            @error('nip')
                <div class="auth-error">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="auth-field">
            <label class="auth-label">Password</label>
            <div class="auth-input-wrap">
                <i class="bi bi-lock-fill auth-input-icon"></i>
                <input
                    type="password"
                    name="password"
                    class="auth-input @error('password') is-invalid @enderror"
                    placeholder="Masukkan password kamu...">
            </div>
            @error('password')
                <div class="auth-error">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="auth-submit btn-primary-blue">
            <i class="bi bi-box-arrow-in-right"></i> Login sebagai Guru
        </button>
    </form>
</div>

{{-- Form Login Pegawai --}}
<div id="formPegawai" style="display: none;">
    <form method="POST" action="{{ route('pegawai.login') }}">
        @csrf

        <div class="auth-field">
            <label class="auth-label">Username</label>
            <div class="auth-input-wrap">
                <i class="bi bi-person-fill auth-input-icon"></i>
                <input
                    type="text"
                    name="username"
                    class="auth-input @error('username') is-invalid @enderror"
                    placeholder="Masukkan username kamu..."
                    value="{{ old('username') }}"
                    autocomplete="off">
            </div>
            @error('username')
                <div class="auth-error">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="auth-field">
            <label class="auth-label">Password</label>
            <div class="auth-input-wrap">
                <i class="bi bi-lock-fill auth-input-icon"></i>
                <input
                    type="password"
                    name="password"
                    class="auth-input @error('password') is-invalid @enderror"
                    placeholder="Masukkan password kamu...">
            </div>
            @error('password')
                <div class="auth-error">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="auth-submit btn-primary-blue">
            <i class="bi bi-box-arrow-in-right"></i> Login sebagai Pegawai
        </button>
    </form>
</div>

{{-- Style --}}
<style>
    .role-selector {
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
    }

    .role-btn {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 14px 8px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        color: #94a3b8;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .role-btn i {
        font-size: 22px;
    }

    .role-btn:hover {
        border-color: #cbd5e1;
        color: #64748b;
    }

    .role-btn.active#btnSiswa,
    .role-btn.active#btnGuru,
    .role-btn.active#btnPegawai {
        border-color: #2563eb;
        background: #eff6ff;
        color: #2563eb;
    }

    .btn-primary-blue {
        background: #2563eb !important;
    }

    .btn-primary-blue:hover {
        background: #1d4ed8 !important;
    }
</style>

{{-- Script switch role --}}
<script>
    function switchRole(role) {
        const forms = {
            siswa:   document.getElementById('formSiswa'),
            guru:    document.getElementById('formGuru'),
            pegawai: document.getElementById('formPegawai'),
        };
        const btns = {
            siswa:   document.getElementById('btnSiswa'),
            guru:    document.getElementById('btnGuru'),
            pegawai: document.getElementById('btnPegawai'),
        };

        Object.values(forms).forEach(f => f.style.display = 'none');
        Object.values(btns).forEach(b => b.classList.remove('active'));

        forms[role].style.display = 'block';
        btns[role].classList.add('active');
    }

    // Auto-switch berdasarkan old input / error
    @if(old('nip') || $errors->has('nip'))
        switchRole('guru');
    @elseif(old('username') || $errors->has('username'))
        switchRole('pegawai');
    @endif
</script>

@endsection
