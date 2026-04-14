@extends('layouts.superadmin')
@section('title', 'Tambah Pegawai')

@section('content')

<div class="card" style="max-width:600px;">
    <div class="card-header">
        <span>Tambah Data Pegawai</span>
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

        <form action="{{ route('superadmin.pengguna.pegawai.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama') }}" placeholder="Nama lengkap pegawai" required maxlength="50">
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                    value="{{ old('username') }}" placeholder="Max 20 karakter" required maxlength="20">
                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Jabatan</label>
                <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror"
                    value="{{ old('jabatan') }}" placeholder="Max 50 karakter" maxlength="50">
                @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
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
                <label class="form-label">Konfirmasi Password</label>
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
                <a href="{{ route('superadmin.pengguna.pegawai.index') }}" class="btn btn-secondary">Batal</a>
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
