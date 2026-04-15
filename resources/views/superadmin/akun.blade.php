@extends('layouts.superadmin')

@section('title', 'Akun Saya')

@section('content')

@if (session('success'))
    <div class="alert alert-success mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

<div class="row g-3">
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-person-fill me-2" style="color:#2563EB;"></i>Profil Super Admin</div>
            <div class="card-body">
                <form action="{{ route('superadmin.akun') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Profile Picture Upload --}}
                    <div class="mb-4 text-center">
                        <div class="position-relative d-inline-block">
                            <div class="profile-pic-preview">
                                @if($admin->foto)
                                    <img src="{{ asset('storage/' . $admin->foto) }}" alt="Profile" id="previewImg">
                                @else
                                    <div class="profile-pic-placeholder" id="placeholderImg">
                                        {{ strtoupper(substr($admin->nama, 0, 2)) }}
                                    </div>
                                    <img src="" alt="Profile" id="previewImg" style="display:none;">
                                @endif
                            </div>
                            <label for="foto" class="profile-pic-upload-btn shadow-sm" title="Ubah Foto">
                                <i class="bi bi-camera-fill"></i>
                            </label>
                            <input type="file" name="foto" id="foto" class="d-none" accept="image/*" onchange="previewFile()">
                        </div>
                        <div class="mt-2 text-muted small">Format: JPG, PNG (Max. 2MB)</div>
                        @error('foto')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <x-input name="nama" placeholder="Nama" :value="$admin->nama" maxlength="50" />
                    <x-input name="username" placeholder="Username" :value="$admin->username" maxlength="20" />
                    <button class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i>Update Profil</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-key-fill me-2" style="color:#F59E0B;"></i>Ganti Password</div>
            <div class="card-body">
                <form action="{{ route('superadmin.akun.password') }}" method="POST">
                    @csrf
                    <x-input type="password" name="password_lama" placeholder="Password Lama" />
                    <x-input type="password" name="password_baru" placeholder="Password Baru" maxlength="10" />
                    <x-input type="password" name="password_baru_confirmation" placeholder="Konfirmasi Password Baru" maxlength="10" />
                    <button class="btn btn-warning w-100"><i class="bi bi-lock me-1"></i>Ganti Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    .profile-pic-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .profile-pic-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .profile-pic-placeholder {
        font-size: 32px;
        font-weight: 800;
        color: #2563EB;
        background: #EFF6FF;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .profile-pic-upload-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 36px;
        height: 36px;
        background: #2563EB;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        border: 3px solid #fff;
    }
    .profile-pic-upload-btn:hover {
        background: #1D4ED8;
        transform: scale(1.1);
    }
</style>
@endpush

@push('scripts')
<script>
    function previewFile() {
        const file = document.querySelector('#foto').files[0];
        const preview = document.querySelector('#previewImg');
        const placeholder = document.querySelector('#placeholderImg');
        const reader = new FileReader();

        reader.addEventListener("load", function () {
            preview.src = reader.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        }, false);

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush

@endsection
