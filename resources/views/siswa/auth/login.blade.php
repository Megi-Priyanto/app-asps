@extends('layouts.auth')
@section('title', 'Masuk Akun')

@section('content')

<div class="auth-form-header">
    <div class="auth-form-icon">
        <i class="bi bi-box-arrow-in-right"></i>
    </div>
    <h1 class="auth-form-title">Selamat Datang!</h1>
    <p class="auth-form-sub">Masukkan kredensial Anda untuk mengakses dashboard layanan sekolah.</p>
</div>

{{-- Pilihan Role --}}
<div class="section-label" style="text-align: center; margin-bottom: 12px;">Masuk Sebagai:</div>
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
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>{{ $errors->first() }}</div>
    </div>
@endif

{{-- Form Login Siswa --}}
<div id="formSiswa" class="auth-form-transition">
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
                    placeholder="Contoh: 212210001"
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
                    id="passwordSiswa"
                    class="auth-input @error('password') is-invalid @enderror"
                    placeholder="Masukkan password Anda">
                <button type="button" class="pwd-toggle" onclick="togglePassword('passwordSiswa')">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="auth-error">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="auth-submit">
            <span>Masuk Siswa</span> <i class="bi bi-arrow-right-short"></i>
        </button>
    </form>
</div>

{{-- Form Login Guru --}}
<div id="formGuru" class="auth-form-transition" style="display: none;">
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
                    placeholder="Masukkan NIP Anda"
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
                    id="passwordGuru"
                    class="auth-input @error('password') is-invalid @enderror"
                    placeholder="Masukkan password Anda">
                <button type="button" class="pwd-toggle" onclick="togglePassword('passwordGuru')">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="auth-error">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="auth-submit">
            <span>Masuk Guru</span> <i class="bi bi-arrow-right-short"></i>
        </button>
    </form>
</div>

{{-- Form Login Pegawai --}}
<div id="formPegawai" class="auth-form-transition" style="display: none;">
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
                    placeholder="Username Pegawai"
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
                    id="passwordPegawai"
                    class="auth-input @error('password') is-invalid @enderror"
                    placeholder="Masukkan password Anda">
                <button type="button" class="pwd-toggle" onclick="togglePassword('passwordPegawai')">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="auth-error">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="auth-submit">
            <span>Masuk Pegawai</span> <i class="bi bi-arrow-right-short"></i>
        </button>
    </form>
</div>

<div class="auth-divider">
    <div class="auth-divider-line"></div>
    <div class="auth-divider-text">ATAU</div>
    <div class="auth-divider-line"></div>
</div>

<div class="auth-bottom-link">
    Lupa akun? Hubungi <a href="https://wa.me/6281220651433?text=Halo%20Staf%20IT%2C%20saya%20pengguna%20aplikasi%20ASPS%20ingin%20meminta%20bantuan%20terkait%20akses%20akun%20saya." target="_blank">Staf IT Sekolah</a>
</div>

{{-- Custom Styles for Login Elements --}}
<style>
    .section-label { font-size: 11px; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 1px; }

    .role-selector {
        display: flex; gap: 10px; margin-bottom: 28px;
    }

    .role-btn {
        flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px;
        padding: 16px 8px; border: 1.5px solid #E2E8F0; border-radius: 14px;
        background: #F8FAFC; color: #64748B; font-size: 13px; font-weight: 700;
        cursor: pointer; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .role-btn i { font-size: 24px; transition: transform 0.25s; }
    .role-btn span { letter-spacing: -0.2px; }

    .role-btn:hover { border-color: #CBD5E1; color: #475569; background: #F1F5F9; }
    .role-btn:hover i { transform: translateY(-2px); }

    .role-btn.active {
        border-color: #2563EB; background: #EFF6FF; color: #2563EB;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
    }

    .auth-form-transition { animation: slideUp 0.4s ease-out; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .pwd-toggle {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: #94A3B8; cursor: pointer;
        padding: 5px; font-size: 18px; display: flex; align-items: center; transition: color 0.2s;
    }
    .pwd-toggle:hover { color: #2563EB; }
</style>

{{-- Scripts --}}
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

        // Hide all with a slight fade
        Object.values(forms).forEach(f => {
            f.style.display = 'none';
        });
        Object.values(btns).forEach(b => b.classList.remove('active'));

        // Show selected
        forms[role].style.display = 'block';
        btns[role].classList.add('active');

        // Focus first input
        const firstInput = forms[role].querySelector('input');
        if (firstInput) firstInput.focus();
    }

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = event.currentTarget.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = "password";
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // Auto-switch based on old input
    @if(old('nip') || $errors->has('nip'))
        switchRole('guru');
    @elseif(old('username') || $errors->has('username'))
        switchRole('pegawai');
    @endif
</script>

@endsection
