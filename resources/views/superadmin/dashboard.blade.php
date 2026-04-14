@extends('layouts.superadmin')

@section('title', 'Dashboard')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-label">Total Siswa</div>
                <div class="stat-value">{{ $totalSiswa ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div>
                <div class="stat-label">Total Laporan</div>
                <div class="stat-value">{{ $totalLaporan ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-label">Laporan Diproses</div>
                <div class="stat-value">{{ $laporanProses ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="stat-label">Laporan Selesai</div>
                <div class="stat-value">{{ $laporanSelesai ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Sebaran Laporan per Kategori</div>
            <div class="card-body d-flex justify-content-center align-items-center">
                @if(isset($kategoriSebaran) && count($kategoriSebaran) > 0)
                    <canvas id="kategoriChart" style="max-height: 280px;"></canvas>
                @else
                    <div class="text-center py-4" style="color:#94A3B8;">
                        <i class="bi bi-pie-chart fs-1 d-block mb-2"></i>
                        Belum ada data kategori.
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Status Laporan Terkini</div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="statusChart" style="max-height: 280px; width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

@include('superadmin.list-laporan')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctxKategori = document.getElementById('kategoriChart');
    if (ctxKategori) {
        new Chart(ctxKategori, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($kategoriSebaran->pluck('kategori')) !!},
                datasets: [{ data: {!! json_encode($kategoriSebaran->pluck('total')) !!}, backgroundColor: ['#2563EB','#10B981','#F59E0B','#EF4444','#8B5CF6','#06B6D4'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right', labels: { font: { family: 'Plus Jakarta Sans', size: 12 }, padding: 16 } } }, cutout: '65%' }
        });
    }

    const ctxStatus = document.getElementById('statusChart');
    if (ctxStatus) {
        new Chart(ctxStatus, {
            type: 'bar',
            data: {
                labels: ['Belum Diproses', 'Proses', 'Selesai'],
                datasets: [{ label: 'Laporan', data: [{{ $totalLaporan - ($laporanProses + $laporanSelesai) }}, {{ $laporanProses }}, {{ $laporanSelesai }}], backgroundColor: ['#F1F5F9','#FFFBEB','#ECFDF5'], borderColor: ['#94A3B8','#F59E0B','#10B981'], borderWidth: 2, borderRadius: 8 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Plus Jakarta Sans' } }, grid: { color: '#F1F5F9' } }, x: { ticks: { font: { family: 'Plus Jakarta Sans' } }, grid: { display: false } } } }
        });
    }
});
</script>
@endpush
