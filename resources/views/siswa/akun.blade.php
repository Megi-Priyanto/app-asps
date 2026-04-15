@extends('layouts.siswa')

@section('title', 'Akun Saya')

@push('css')
<style>
    :root {
        --primary: #2563EB; --body-bg: #F8FAFC; --border: #E2E8F0;
        --text-primary: #0F172A; --text-secondary: #64748B;
        --radius: 12px; --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); }
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 20px; font-weight: 800; color: var(--text-primary); margin: 0 0 4px; }
    .page-header p  { font-size: 13px; color: var(--text-secondary); margin: 0; }
    .card { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); }
    .card-header {
        background: transparent; border-bottom: 1px solid var(--border);
        padding: 15px 20px; font-weight: 700; font-size: 14px; color: var(--text-primary);
        display: flex; align-items: center; gap: 8px;
    }
    .card-body { padding: 24px; }
    .profile-banner {
        display: flex; align-items: center; gap: 20px;
        padding: 20px; border-radius: 10px; margin-bottom: 24px; color: white;
        background: linear-gradient(135deg, #2563EB 0%, #0EA5E9 100%);
    }
    .profile-avatar {
        width: 64px; height: 64px; border-radius: 16px;
        background: rgba(255,255,255,0.22);
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; font-weight: 800; flex-shrink: 0;
    }
    .profile-name { font-size: 18px; font-weight: 800; margin-bottom: 2px; }
    .profile-role { font-size: 13px; opacity: .8; }
    .form-label { font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; display: block; }
    .form-control {
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13.5px;
        border: 1.5px solid var(--border); border-radius: 8px; padding: 9px 13px;
        width: 100%; transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); outline: none; }
    .form-control[readonly] { background: #F8FAFC; cursor: not-allowed; color: var(--text-secondary); }
    .form-control.is-invalid { border-color: #EF4444; }
    .invalid-feedback { display: block; color: #EF4444; font-size: 12px; margin-top: 4px; }
    .btn { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; font-size: 13.5px; border-radius: 8px; border: none; padding: 10px 20px; transition: all 0.15s; cursor: pointer; }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: #1D4ED8; color: white; }
    .alert { border: none; border-radius: 8px; font-size: 13.5px; font-weight: 500; padding: 12px 16px; }
    .alert-success { background: #ECFDF5; color: #065F46; }
    .alert-danger  { background: #FEF2F2; color: #991B1B; }
    .divider { height: 1px; background: var(--border); margin: 24px 0; }
    .info-note {
        background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px;
        padding: 12px 14px; font-size: 12.5px; color: #1E3A8A;
        display: flex; gap: 8px; align-items: flex-start;
    }
    .info-note i { color: #2563EB; flex-shrink: 0; margin-top: 1px; }
</style>
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:12px;"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="page-header">
    <h1><i class="bi bi-person-circle me-2" style="color:#2563EB;"></i>Akun Saya</h1>
    <p>Kelola informasi profil dan keamanan akun Anda</p>
</div>

<div class="row g-4">

    {{-- ===== Informasi Profil ===== --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-fill" style="color:#2563EB;"></i> Informasi Profil
            </div>
            <div class="card-body">
                <div class="profile-banner">
                    <div class="profile-avatar" id="avatarPreviewContainer">
                        @if($siswa->foto)
                            <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Avatar" id="avatarPreview" style="width:100%; height:100%; object-fit:cover; border-radius:16px;">
                        @else
                            <span id="initialsFallback">{{ strtoupper(substr($siswa->nama, 0, 2)) }}</span>
                        @endif
                    </div>
                    <div>
                        <div class="profile-name">{{ $siswa->nama }}</div>
                        <div class="profile-role">Siswa · Kelas {{ $siswa->kelas ?? '-' }}</div>
                        <label for="foto" class="btn btn-sm" style="background:rgba(255,255,255,0.2); color:white; font-size:11px; padding:4px 10px; margin-top:8px; border:1px solid rgba(255,255,255,0.4);">
                            <i class="bi bi-camera-fill me-1"></i> Ubah Foto
                        </label>
                    </div>
                </div>

                <form action="{{ route('siswa.akun.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <input type="file" name="foto" id="foto" class="d-none" accept="image/*" onchange="previewImage(this)">

                    <div class="mb-3">
                        <label class="form-label">NIS</label>
                        <input type="number" name="nis" class="form-control" value="{{ $siswa->nis }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama"
                               class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $siswa->nama) }}" required maxlength="50">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Kelas</label>
                        <input type="text" name="kelas"
                               class="form-control @error('kelas') is-invalid @enderror"
                               value="{{ old('kelas', $siswa->kelas) }}"
                               placeholder="Contoh: XII RPL 1" maxlength="20">
                        @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="info-note mb-4">
                        <i class="bi bi-info-circle-fill"></i>
                        NIS tidak dapat diubah secara langsung. Hubungi tata usaha atau admin jika terdapat kesalahan pendaftaran.
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== Ubah Password ===== --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-shield-lock-fill" style="color:#2563EB;"></i> Ubah Password
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.akun.password') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="password_lama"
                               class="form-control @error('password_lama') is-invalid @enderror"
                               required placeholder="Masukkan password saat ini">
                        @error('password_lama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required placeholder="8-10 karakter" maxlength="10">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                               class="form-control"
                               required placeholder="Ulangi password baru" maxlength="10">
                    </div>

                    <div class="divider"></div>

                    <div class="info-note mb-4">
                        <i class="bi bi-shield-check-fill"></i>
                        Gunakan kombinasi huruf, angka, dan simbol untuk password yang lebih aman.
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-lock-fill me-2"></i>Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const container = document.getElementById('avatarPreviewContainer');
                let img = document.getElementById('avatarPreview');
                const fallback = document.getElementById('initialsFallback');
                
                if (!img) {
                    img = document.createElement('img');
                    img.id = 'avatarPreview';
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '16px';
                    container.appendChild(img);
                }
                
                if (fallback) fallback.classList.add('d-none');
                img.src = e.target.result;
                img.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
