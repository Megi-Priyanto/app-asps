@extends('layouts.superadmin')

@section('title', 'Tambah Siswa')

@section('content')

<div class="card" style="max-width:600px;">
    <div class="card-header d-flex align-items-center gap-2">
        <span>Tambah Data Siswa</span>
    </div>
    <div class="card-body p-4">

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('superadmin.pengguna.siswa.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">NIS <span class="text-danger"></span></label>
                <input type="number" name="nis" class="form-control @error('nis') is-invalid @enderror"
                    value="{{ old('nis') }}" placeholder="Contoh: 2024001" required maxlength="10">
                @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger"></span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama') }}" placeholder="Nama lengkap siswa" required maxlength="50">
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Kelas <span class="text-danger"></span></label>
                <input type="text" name="kelas" class="form-control @error('kelas') is-invalid @enderror"
                    value="{{ old('kelas') }}" placeholder="Contoh: X RPL 1" required maxlength="20">
                @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password <span class="text-danger"></span></label>
                <div class="input-group">
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="8-10 karakter" required maxlength="10">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePass('password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Konfirmasi Password <span class="text-danger"></span></label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="form-control" placeholder="Ulangi password" required maxlength="10">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePass('password_confirmation', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Simpan
                </button>
                <a href="{{ route('superadmin.pengguna.siswa.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>

@endsection
