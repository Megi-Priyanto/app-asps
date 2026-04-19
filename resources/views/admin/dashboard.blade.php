@extends('layouts.admin')

@section('title', 'Dashboard')

@push('css')
<style>
/* ── Admin Dashboard Extra Styles ──────────────────────────────────── */
.dash-section-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-muted);
    letter-spacing: 0.8px;
    text-transform: uppercase;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.dash-section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

/* Welcome banner */
.welcome-banner {
    background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%);
    border-radius: var(--radius);
    padding: 22px 28px;
    color: white;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    overflow: hidden;
    position: relative;
    box-shadow: 0 8px 24px rgba(37,99,235,0.28);
}
.welcome-banner::before {
    content: '';
    position: absolute;
    right: -40px; top: -40px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
}
.welcome-banner::after {
    content: '';
    position: absolute;
    right: 80px; bottom: -60px;
    width: 160px; height: 160px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
}
.welcome-banner-title { font-size: 19px; font-weight: 800; margin-bottom: 4px; }
.welcome-banner-sub   { font-size: 12.5px; opacity: 0.8; }
.welcome-pill {
    background: rgba(255,255,255,0.15);
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.welcome-banner-icon { font-size: 52px; opacity: 0.22; position: relative; z-index: 1; }

/* Mini stat cards */
.stat-card-mini {
    background: white;
    border-radius: var(--radius);
    padding: 17px 18px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 14px;
    transition: all 0.25s;
}
.stat-card-mini:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
.stat-icon-sm {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.stat-label-sm { font-size: 11.5px; color: var(--text-muted); font-weight: 500; margin-bottom: 3px; }
.stat-value-sm { font-size: 22px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; line-height: 1; }

/* Highlight badge inline */
.inline-pill {
    font-size: 10.5px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    margin-left: 6px;
    vertical-align: middle;
}
.pill-warn  { background:#FFFBEB; color:#D97706; }
.pill-ok    { background:#ECFDF5; color:#059669; }
.pill-info  { background:#EFF6FF; color:#2563EB; }
.pill-red   { background:#FEF2F2; color:#DC2626; }

/* Chart card */
.chart-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
}
.chart-card-header {
    padding: 16px 20px 12px;
    display: flex; align-items: center; justify-content: space-between;
    border-bottom: 1px solid #F1F5F9;
}
.chart-card-title {
    font-size: 14px; font-weight: 700; color: var(--text-primary);
    display: flex; align-items: center; gap: 7px;
}
.chart-card-title i { font-size: 15px; color: var(--primary); }
.chart-card-subtitle { font-size: 11.5px; color: var(--text-muted); font-weight: 500; margin-top: 1px; }
.chart-card-body { padding: 18px 20px; }

/* Legend */
.legend-row { display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:var(--text-secondary); }
.legend-dot  { width:10px; height:10px; border-radius:3px; flex-shrink:0; }

/* Donut summary list */
.donut-row { display:flex; align-items:center; gap:9px; font-size:12.5px; }
.donut-row-dot { width:10px; height:10px; border-radius:3px; flex-shrink:0; }
.donut-row-label { flex:1; color:var(--text-secondary); font-weight:500; }
.donut-row-val   { font-weight:700; color:var(--text-primary); }

/* Activity table */
.act-table { width:100%; border-collapse:collapse; }
.act-table th {
    font-size:11px; font-weight:700; color:var(--text-muted);
    text-transform:uppercase; letter-spacing:0.5px;
    padding:10px 16px; background:#F8FAFC;
    border-bottom:1px solid var(--border);
}
.act-table td {
    padding:11px 16px; font-size:13px; color:var(--text-primary);
    border-bottom:1px solid #F8FAFC; vertical-align:middle;
}
.act-table tr:last-child td { border-bottom:none; }
.act-table tr:hover td { background:#FAFCFF; }

/* Status badges */
.bs { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:700; padding:3px 10px; border-radius:20px; }
.bs-baru        { background:#F1F5F9; color:#64748B; }
.bs-proses      { background:#FFFBEB; color:#D97706; }
.bs-selesai     { background:#ECFDF5; color:#059669; }
.bs-menunggu    { background:#FFFBEB; color:#D97706; }
.bs-disetujui   { background:#ECFDF5; color:#059669; }
.bs-dipinjam    { background:#EFF6FF; color:#2563EB; }
.bs-terlambat   { background:#FEF2F2; color:#DC2626; }
.bs-dikembalikan{ background:#F0FDF4; color:#16A34A; }
.bs-ditolak     { background:#FFF1F2; color:#BE123C; }
.bs-perbaikan   { background:#F5F3FF; color:#7C3AED; }

/* Progress ring container */
.ring-stat {
    display: flex; align-items: center; gap: 16px;
    padding: 14px 18px;
    border-radius: var(--radius-sm);
    background: #F8FAFC;
    border: 1px solid var(--border);
}
.ring-label { font-size: 12px; font-weight: 600; color: var(--text-secondary); }
.ring-val { font-size: 20px; font-weight: 800; color: var(--text-primary); line-height: 1; }
</style>
@endpush

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner mb-4">
    <div style="position:relative;z-index:1;">
        <div class="welcome-banner-title">Halo, {{ $admin->nama ?? 'Admin' }}!</div>
        <div class="welcome-banner-sub mb-2">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} &mdash; Panel Admin <strong>{{ $namaLokasi }}</strong></div>
        <div class="d-flex flex-wrap gap-2 mt-2">
            <span class="welcome-pill"><i class="bi bi-people-fill"></i> {{ $totalSiswa + $totalGuru + $totalPegawai }} Pengguna</span>
            <span class="welcome-pill"><i class="bi bi-archive-fill"></i> {{ $totalBarang }} Barang</span>
            @if($peminjamanMenunggu > 0)
            <span class="welcome-pill" style="background:rgba(251,191,36,0.3);">
                <i class="bi bi-clock-history"></i> {{ $peminjamanMenunggu }} Menunggu Persetujuan
            </span>
            @endif
            @if($laporanBaru > 0)
            <span class="welcome-pill" style="background:rgba(239,68,68,0.25);">
                <i class="bi bi-file-earmark-text-fill"></i> {{ $laporanBaru }} Laporan Baru
            </span>
            @endif
        </div>
    </div>
    <i class="bi bi-shield-check welcome-banner-icon d-none d-md-block"></i>
</div>

{{-- ── Statistik Laporan ─────────────────────────────────────────────── --}}
<p class="dash-section-title"><i class="bi bi-file-earmark-text-fill" style="color:var(--warning);"></i> Laporan & Aspirasi</p>
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div>
                <div class="stat-label">Total Laporan</div>
                <div class="stat-value">{{ number_format($totalLaporan) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-inbox-fill"></i></div>
            <div>
                <div class="stat-label">Laporan Baru</div>
                <div class="stat-value">{{ number_format($laporanBaru) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-label">Diproses</div>
                <div class="stat-value">{{ number_format($laporanProses) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="stat-label">Selesai</div>
                <div class="stat-value">{{ number_format($laporanSelesai) }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Statistik Sarpras ─────────────────────────────────────────────── --}}
<p class="dash-section-title"><i class="bi bi-box-seam-fill" style="color:var(--primary);"></i> Sarana & Prasarana &mdash; <span style="text-transform:none; font-weight:500;">{{ $namaLokasi }}</span></p>
<div class="row g-3 mb-4">
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="stat-card-mini">
            <div class="stat-icon-sm blue"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-label-sm">Siswa</div>
                <div class="stat-value-sm">{{ $totalSiswa }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="stat-card-mini">
            <div class="stat-icon-sm yellow"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <div class="stat-label-sm">Barang</div>
                <div class="stat-value-sm">{{ $totalBarang }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="stat-card-mini">
            <div class="stat-icon-sm green"><i class="bi bi-arrow-left-right"></i></div>
            <div>
                <div class="stat-label-sm">Total Pinjam</div>
                <div class="stat-value-sm">{{ $totalPeminjaman }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="stat-card-mini">
            <div class="stat-icon-sm blue"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="stat-label-sm">Aktif</div>
                <div class="stat-value-sm">
                    {{ $peminjamanAktif }}
                    @if($peminjamanMenunggu > 0)
                        <span class="inline-pill pill-warn">+{{ $peminjamanMenunggu }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="stat-card-mini">
            <div class="stat-icon-sm red"><i class="bi bi-tools"></i></div>
            <div>
                <div class="stat-label-sm">Perbaikan</div>
                <div class="stat-value-sm">
                    {{ $totalPerbaikan }}
                    @if($perbaikanBerjalan > 0)
                        <span class="inline-pill pill-warn">{{ $perbaikanBerjalan }} jalan</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="stat-card-mini">
            <div class="stat-icon-sm" style="background:#FFF7ED; color:#EA580C;"><i class="bi bi-cash-stack"></i></div>
            <div style="min-width:0;">
                <div class="stat-label-sm">Biaya Perbaikan</div>
                <div class="stat-value-sm" style="font-size:15px;">
                    {{ $totalBiayaPerbaikan > 0 ? 'Rp '.number_format($totalBiayaPerbaikan,0,',','.') : '—' }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Grafik 1: Tren + Kategori Laporan ───────────────────────────────── --}}
<p class="dash-section-title"><i class="bi bi-bar-chart-fill" style="color:var(--primary);"></i> Analitik & Grafik</p>
<div class="row g-3 mb-4">
    {{-- Tren Bulanan --}}
    <div class="col-12 col-xl-8">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title"><i class="bi bi-bar-chart-fill"></i> Tren Peminjaman & Laporan</div>
                    <div class="chart-card-subtitle">12 bulan terakhir</div>
                </div>
                <div class="d-flex gap-3">
                    <span class="legend-row"><span class="legend-dot" style="background:#3B82F6;"></span> Peminjaman</span>
                    <span class="legend-row"><span class="legend-dot" style="background:#F59E0B;"></span> Laporan</span>
                </div>
            </div>
            <div class="chart-card-body">
                <canvas id="chartTrenBulanan" style="height:260px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Donat Kategori Laporan --}}
    <div class="col-12 col-xl-4">
        <div class="chart-card h-100">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title"><i class="bi bi-pie-chart-fill"></i> Kategori Laporan</div>
                    <div class="chart-card-subtitle">Sebaran per kategori</div>
                </div>
            </div>
            <div class="chart-card-body d-flex flex-column align-items-center">
                @if(count($kategoriSebaran) > 0)
                    <canvas id="chartKategori" style="max-height:180px; max-width:180px;"></canvas>
                    <div class="w-100 mt-3" style="display:flex;flex-direction:column;gap:8px;">
                        @php $katColors = ['#3B82F6','#F59E0B','#10B981','#EF4444','#8B5CF6','#06B6D4']; @endphp
                        @foreach($kategoriSebaran as $idx => $ks)
                        <div class="donut-row">
                            <div class="donut-row-dot" style="background:{{ $katColors[$idx % count($katColors)] }};"></div>
                            <div class="donut-row-label">{{ $ks->kategori }}</div>
                            <div class="donut-row-val">{{ $ks->total }}</div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5" style="color:var(--text-muted);">
                        <i class="bi bi-pie-chart fs-1 d-block mb-2"></i>
                        <div style="font-size:13px;">Belum ada data laporan</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── Grafik 2: Status Peminjaman + Barang per Kategori ────────────────── --}}
<div class="row g-3 mb-4">
    {{-- Donat Status Peminjaman --}}
    <div class="col-12 col-md-5">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title"><i class="bi bi-pie-chart-fill"></i> Status Peminjaman</div>
                    <div class="chart-card-subtitle">Distribusi saat ini</div>
                </div>
            </div>
            <div class="chart-card-body d-flex align-items-center gap-4 flex-wrap">
                @if(count($statusPeminjaman) > 0)
                    <canvas id="chartStatusPmj" style="max-height:180px; max-width:180px; flex-shrink:0;"></canvas>
                    <div style="flex:1; min-width:130px; display:flex; flex-direction:column; gap:9px;">
                        @php
                            $spColors = ['Menunggu'=>'#F59E0B','Disetujui'=>'#10B981','Sedang Dipinjam'=>'#3B82F6','Terlambat'=>'#EF4444','Sudah Dikembalikan'=>'#6EE7B7','Ditolak'=>'#F87171'];
                        @endphp
                        @foreach($statusPeminjaman as $sp)
                        <div class="donut-row">
                            <div class="donut-row-dot" style="background:{{ $spColors[$sp->status] ?? '#94A3B8' }};"></div>
                            <div class="donut-row-label">{{ $sp->status }}</div>
                            <div class="donut-row-val">{{ $sp->total }}</div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 w-100" style="color:var(--text-muted); font-size:13px;">
                        <i class="bi bi-inbox me-2"></i>Belum ada data peminjaman
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Batang: Status Laporan --}}
    <div class="col-12 col-md-7">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title"><i class="bi bi-bar-chart-fill"></i> Status Laporan</div>
                    <div class="chart-card-subtitle">Baru / Proses / Selesai</div>
                </div>
            </div>
            <div class="chart-card-body">
                <canvas id="chartStatusLaporan" style="height:200px;"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ── Aktivitas Terbaru ──────────────────────────────────────────────── --}}
<p class="dash-section-title"><i class="bi bi-activity" style="color:var(--success);"></i> Aktivitas Terbaru</p>

{{-- Laporan Terbaru --}}
<div class="chart-card mb-3">
    <div class="chart-card-header">
        <div>
            <div class="chart-card-title"><i class="bi bi-file-earmark-text-fill"></i> Laporan Terbaru</div>
            <div class="chart-card-subtitle">5 laporan terkini di lokasi Anda</div>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-primary" style="font-size:12px;">
            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div style="overflow-x:auto;">
        <table class="act-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Peran</th>
                    <th>Nama Pelapor</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporanTerbaru as $item)
                @php
                    $lapStatus = $item->aspirasi?->status ?? 'baru';
                    $lapClass  = match($lapStatus) {
                        'selesai' => 'bs-selesai',
                        'proses'  => 'bs-proses',
                        default   => 'bs-baru',
                    };
                    $lapLabel = match($lapStatus) {
                        'selesai' => 'Selesai',
                        'proses'  => 'Proses',
                        default   => 'Baru',
                    };
                    $reporterType = class_basename($item->reporter_type ?? 'siswa');
                @endphp
                <tr>
                    <td style="color:var(--text-muted); font-size:12px;">{{ $loop->iteration }}</td>
                    <td>
                        <span class="bs bs-baru" style="font-size:10.5px;">{{ $reporterType }}</span>
                    </td>
                    <td style="font-weight:600;">{{ $item->reporter?->nama ?? $item->siswa?->nama ?? '—' }}</td>
                    <td>{{ $item->kategoriAspirasi->nama_kategori ?? '—' }}</td>
                    <td><span class="bs {{ $lapClass }}">{{ $lapLabel }}</span></td>
                    <td style="color:var(--text-muted); font-size:12px;">{{ $item->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.laporan.show', $item->id) }}" class="btn btn-sm btn-primary" style="font-size:11.5px; padding:4px 12px;">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4" style="color:var(--text-muted); font-size:13px;">
                        <i class="bi bi-inbox me-2"></i>Belum ada laporan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Peminjaman & Perbaikan Terbaru --}}
<div class="row g-3 mb-4">
    {{-- Peminjaman Terbaru --}}
    <div class="col-12 col-xl-7">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title"><i class="bi bi-arrow-left-right"></i> Peminjaman Terbaru</div>
                    <div class="chart-card-subtitle">5 transaksi terakhir</div>
                </div>
                <a href="{{ route('admin.peminjaman-barang.index') }}" class="btn btn-sm btn-primary" style="font-size:12px;">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div style="overflow-x:auto;">
                <table class="act-table">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Barang</th>
                            <th>Peminjam</th>
                            <th>Status</th>
                            <th>Tgl Pinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanTerbaru as $pmj)
                        @php
                            $pmjClass = match($pmj->status) {
                                'Menunggu'          => 'bs-menunggu',
                                'Disetujui'         => 'bs-disetujui',
                                'Sedang Dipinjam'   => 'bs-dipinjam',
                                'Terlambat'         => 'bs-terlambat',
                                'Sudah Dikembalikan'=> 'bs-dikembalikan',
                                'Ditolak'           => 'bs-ditolak',
                                default             => 'bs-baru',
                            };
                        @endphp
                        <tr>
                            <td><span style="font-family:monospace;font-size:11.5px;">{{ $pmj->nomor_transaksi }}</span></td>
                            <td>{{ $pmj->barang->nama_barang ?? '—' }}</td>
                            <td>
                                @if($pmj->borrower)
                                    {{ $pmj->borrower->nama ?? $pmj->borrower->name ?? '—' }}
                                @elseif($pmj->nama_peminjam)
                                    {{ $pmj->nama_peminjam }}
                                @else —
                                @endif
                            </td>
                            <td><span class="bs {{ $pmjClass }}">{{ $pmj->status }}</span></td>
                            <td style="color:var(--text-muted);font-size:12px;">{{ $pmj->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color:var(--text-muted);font-size:13px;">
                                <i class="bi bi-inbox me-2"></i>Belum ada peminjaman
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Perbaikan Terbaru --}}
    <div class="col-12 col-xl-5">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title"><i class="bi bi-tools"></i> Perbaikan Terbaru</div>
                    <div class="chart-card-subtitle">5 perbaikan terakhir</div>
                </div>
                <a href="{{ route('admin.perbaikan-barang.index') }}" class="btn btn-sm btn-primary" style="font-size:12px;">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div style="overflow-x:auto;">
                <table class="act-table">
                    <thead>
                        <tr>
                            <th>No. Perbaikan</th>
                            <th>Barang</th>
                            <th>Status</th>
                            <th>Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perbaikanTerbaru as $pbk)
                        @php
                            $pbkClass = match($pbk->status) {
                                'Menunggu'        => 'bs-menunggu',
                                'Dalam Perbaikan' => 'bs-perbaikan',
                                'Selesai'         => 'bs-selesai',
                                default           => 'bs-baru',
                            };
                        @endphp
                        <tr>
                            <td><span style="font-family:monospace;font-size:11.5px;">{{ $pbk->nomor_perbaikan }}</span></td>
                            <td>{{ $pbk->barang->nama_barang ?? '—' }}</td>
                            <td><span class="bs {{ $pbkClass }}">{{ $pbk->status }}</span></td>
                            <td style="font-size:12.5px;font-weight:600;color:{{ $pbk->biaya_perbaikan > 0 ? 'var(--danger)' : 'var(--text-muted)' }}">
                                {!! $pbk->biaya_perbaikan > 0 ? 'Rp '.number_format($pbk->biaya_perbaikan,0,',','.') : '&mdash;' !!}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4" style="color:var(--text-muted);font-size:13px;">
                                <i class="bi bi-inbox me-2"></i>Belum ada data perbaikan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($totalBiayaPerbaikan > 0)
            <div style="padding:12px 16px;border-top:1px solid #F1F5F9;background:#FAFCFF;border-radius:0 0 var(--radius) var(--radius);">
                <div style="font-size:11.5px;color:var(--text-muted);font-weight:500;">Total Biaya Perbaikan (Selesai)</div>
                <div style="font-size:17px;font-weight:800;color:var(--danger);margin-top:2px;">
                    Rp {{ number_format($totalBiayaPerbaikan,0,',','.') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#64748B';

    const c = {
        blue:   { s:'#3B82F6', l:'rgba(59,130,246,0.12)'   },
        yellow: { s:'#F59E0B', l:'rgba(245,158,11,0.12)'   },
        green:  { s:'#10B981', l:'rgba(16,185,129,0.12)'   },
        red:    { s:'#EF4444', l:'rgba(239,68,68,0.12)'    },
        purple: { s:'#8B5CF6', l:'rgba(139,92,246,0.12)'   },
        cyan:   { s:'#06B6D4', l:'rgba(6,182,212,0.12)'    },
    };

    const tooltipBase = {
        backgroundColor:'#1E293B', padding:11,
        cornerRadius:10, titleFont:{size:12,weight:'700'}, bodyFont:{size:12}
    };

    // ── 1. Tren Bulanan ──────────────────────────────────────────────
    new Chart(document.getElementById('chartTrenBulanan'), {
        type: 'bar',
        data: {
            labels: @json($bulanLabels),
            datasets: [
                {
                    label: 'Peminjaman',
                    data: @json($bulanDataPmj),
                    backgroundColor: c.blue.l, borderColor: c.blue.s,
                    borderWidth:2, borderRadius:8, borderSkipped:false,
                },
                {
                    label: 'Laporan',
                    data: @json($bulanDataLap),
                    backgroundColor: c.yellow.l, borderColor: c.yellow.s,
                    borderWidth:2, borderRadius:8, borderSkipped:false,
                },
            ],
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            interaction: { mode:'index', intersect:false },
            plugins: { legend:{display:false}, tooltip: tooltipBase },
            scales: {
                x: { grid:{display:false}, border:{display:false}, ticks:{font:{size:11}} },
                y: { grid:{color:'#F1F5F9'}, border:{display:false}, ticks:{font:{size:11},precision:0}, beginAtZero:true },
            },
        },
    });

    // ── 2. Donat Kategori Laporan ────────────────────────────────────
    @if(count($kategoriSebaran) > 0)
    new Chart(document.getElementById('chartKategori'), {
        type: 'doughnut',
        data: {
            labels: @json($kategoriSebaran->pluck('kategori')),
            datasets: [{
                data: @json($kategoriSebaran->pluck('total')),
                backgroundColor: [c.blue.s, c.yellow.s, c.green.s, c.red.s, c.purple.s, c.cyan.s],
                borderWidth:3, borderColor:'#fff', hoverBorderWidth:4,
            }],
        },
        options: {
            responsive:true, maintainAspectRatio:false, cutout:'68%',
            plugins: { legend:{display:false}, tooltip: tooltipBase },
        },
    });
    @endif

    // ── 3. Donat Status Peminjaman ───────────────────────────────────
    @if(count($statusPeminjaman) > 0)
    const spColors = {
        'Menunggu':'#F59E0B','Disetujui':'#10B981','Sedang Dipinjam':'#3B82F6',
        'Terlambat':'#EF4444','Sudah Dikembalikan':'#6EE7B7','Ditolak':'#F87171'
    };
    const spLabels = @json($statusPeminjaman->pluck('status'));
    new Chart(document.getElementById('chartStatusPmj'), {
        type: 'doughnut',
        data: {
            labels: spLabels,
            datasets: [{
                data: @json($statusPeminjaman->pluck('total')),
                backgroundColor: spLabels.map(l => spColors[l] ?? '#94A3B8'),
                borderWidth:3, borderColor:'#fff', hoverBorderWidth:4,
            }],
        },
        options: {
            responsive:true, maintainAspectRatio:false, cutout:'68%',
            plugins: { legend:{display:false}, tooltip: tooltipBase },
        },
    });
    @endif

    // ── 4. Batang Status Laporan ─────────────────────────────────────
    new Chart(document.getElementById('chartStatusLaporan'), {
        type: 'bar',
        data: {
            labels: ['Laporan Baru', 'Sedang Diproses', 'Selesai'],
            datasets: [{
                label: 'Laporan',
                data: [{{ $laporanBaru }}, {{ $laporanProses }}, {{ $laporanSelesai }}],
                backgroundColor: [c.red.l, c.yellow.l, c.green.l],
                borderColor:     [c.red.s, c.yellow.s, c.green.s],
                borderWidth:2, borderRadius:10, borderSkipped:false,
            }],
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins: { legend:{display:false}, tooltip: tooltipBase },
            scales: {
                x: { grid:{display:false}, border:{display:false}, ticks:{font:{size:12}} },
                y: { grid:{color:'#F1F5F9'}, border:{display:false}, ticks:{font:{size:11},precision:0}, beginAtZero:true },
            },
        },
    });

});
</script>
@endpush
