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
                <form action="{{ route('superadmin.akun') }}" method="POST">
                    @csrf
                    <x-input name="nama" placeholder="Nama" :value="$admin->nama" maxlength="50" />
                    <x-input name="username" placeholder="Username" :value="$admin->username" maxlength="20" />
                    <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Profil</button>
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
                    <button class="btn btn-warning"><i class="bi bi-lock me-1"></i>Ganti Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
