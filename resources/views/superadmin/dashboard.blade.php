@extends('layouts.superadmin')

@section('title', 'Dashboard')

@section('content')

{{-- Statistik Pengguna --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-mortarboard-fill"></i></div>
            <div>
                <div class="stat-label">Total Siswa</div>
                <div class="stat-value">{{ $totalSiswa ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-person-video3"></i></div>
            <div>
                <div class="stat-label">Total Guru</div>
                <div class="stat-value">{{ $totalGuru ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-person-badge-fill"></i></div>
            <div>
                <div class="stat-label">Total Pegawai</div>
                <div class="stat-value">{{ $totalPegawai ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-person-gear"></i></div>
            <div>
                <div class="stat-label">Total Admin</div>
                <div class="stat-value">{{ $totalAdmin ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Statistik Sistem --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-tags-fill"></i></div>
            <div>
                <div class="stat-label">Total Kategori</div>
                <div class="stat-value">{{ $totalKategori ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-chat-left-quote-fill"></i></div>
            <div>
                <div class="stat-label">Tanggapan Aplikasi</div>
                <div class="stat-value">{{ $totalTanggapan ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

@endsection
