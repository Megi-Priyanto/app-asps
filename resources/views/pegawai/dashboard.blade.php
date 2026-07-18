@extends('layouts.pegawai')

@section('title', 'Dashboard')

@push('css')
<style>
:root {
    --primary: #2563EB; --body-bg: #F8FAFC; --border: #E2E8F0;
    --text-primary: #0F172A; --text-secondary: #475569; --text-muted: #94A3B8;
    --radius: 14px; --radius-sm: 10px;
    --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
    --success: #10B981; --warning: #F59E0B; --danger: #EF4444;
}
body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); }

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

/* Section title */
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

/* Stat cards */
.stat-card {
    background: white;
    border-radius: var(--radius);
    padding: 18px 20px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 14px;
    transition: all 0.25s;
}
.stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
.stat-icon {
    width: 46px; height: 46px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.stat-icon.yellow { background: #FFFBEB; color: #D97706; }
.stat-icon.red    { background: #FEF2F2; color: #DC2626; }
.stat-icon.blue   { background: #EFF6FF; color: #2563EB; }
.stat-icon.green  { background: #ECFDF5; color: #059669; }
.stat-label { font-size: 12px; color: var(--text-muted); font-weight: 600; margin-bottom: 4px; }
.stat-value { font-size: 24px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; line-height: 1; }

/* Chart card / table card */
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
.bs-menunggu { background:#FFFBEB; color:#D97706; }
.bs-proses   { background:#EFF6FF; color:#2563EB; }
.bs-selesai  { background:#ECFDF5; color:#059669; }

/* Quick links */
.quick-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    border-radius: var(--radius-sm);
    background: #F8FAFC;
    border: 1px solid var(--border);
    text-decoration: none;
    color: var(--text-primary);
    transition: all 0.2s;
}
.quick-link:hover {
    background: #EFF6FF;
    border-color: #BFDBFE;
    transform: translateY(-1px);
    box-shadow: var(--shadow);
    color: var(--primary);
}
.quick-link-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.quick-link-label { font-size: 13px; font-weight: 600; }
.quick-link-sub { font-size: 11px; color: var(--text-muted); font-weight: 500; }

@media (max-width: 768px) {
    .welcome-banner { padding: 18px 20px; }
    .welcome-banner-title { font-size: 16px; }
    .stat-value { font-size: 20px; }
}
</style>
@endpush

@section('content')

@php
    $pegawai = Auth::guard('pegawai')->user();
@endphp

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div style="position:relative;z-index:1;">
        <div class="welcome-banner-title">Halo, {{ $pegawai->nama ?? 'Pegawai' }}! 👋</div>
        <div class="welcome-banner-sub mb-2">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} — Selamat datang di portal aspirasi</div>
        <div class="d-flex flex-wrap gap-2 mt-2">
            <span class="welcome-pill"><i class="bi bi-file-earmark-text-fill"></i> {{ $stats['total'] }} Total Laporan</span>
            @if($stats['menunggu'] > 0)
            <span class="welcome-pill" style="background:rgba(251,191,36,0.3);">
                <i class="bi bi-clock-history"></i> {{ $stats['menunggu'] }} Menunggu
            </span>
            @endif
        </div>
    </div>
    <i class="bi bi-briefcase-fill welcome-banner-icon d-none d-md-block"></i>
</div>

{{-- Statistik Laporan --}}
<p class="dash-section-title"><i class="bi bi-bar-chart-fill" style="color:var(--primary);"></i> Statistik Laporan Saya</p>
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div>
                <div class="stat-label">Total Laporan</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="stat-label">Menunggu</div>
                <div class="stat-value">{{ $stats['menunggu'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-label">Diproses</div>
                <div class="stat-value">{{ $stats['proses'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="stat-label">Selesai</div>
                <div class="stat-value">{{ $stats['selesai'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Aktivitas Terbaru --}}
<p class="dash-section-title"><i class="bi bi-activity" style="color:var(--success);"></i> Laporan Terbaru</p>
<div class="chart-card mb-4">
    <div class="chart-card-header">
        <div>
            <div class="chart-card-title"><i class="bi bi-file-earmark-text-fill"></i> Aktivitas Laporan</div>
            <div class="chart-card-subtitle">5 laporan terkini Anda</div>
        </div>
        <a href="{{ route('pegawai.laporan.index') }}" class="btn btn-sm btn-primary" style="font-size:12px;">
            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div style="overflow-x:auto;">
        <table class="act-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kategori</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporanTerbaru as $item)
                @php
                    $statusClass = match($item->status) {
                        'selesai' => 'bs-selesai',
                        'proses'  => 'bs-proses',
                        default   => 'bs-menunggu',
                    };
                    $statusLabel = match($item->status) {
                        'selesai' => 'Selesai',
                        'proses'  => 'Diproses',
                        default   => 'Menunggu',
                    };
                @endphp
                <tr>
                    <td style="color:var(--text-muted); font-size:12px;">{{ $loop->iteration }}</td>
                    <td>{{ $item->kategoriAspirasi->nama_kategori ?? '—' }}</td>
                    <td style="font-weight:600;">{{ Str::limit($item->judul ?? $item->deskripsi ?? '—', 40) }}</td>
                    <td><span class="bs {{ $statusClass }}">{{ $statusLabel }}</span></td>
                    <td style="color:var(--text-muted); font-size:12px;">{{ $item->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('pegawai.laporan.show', $item->id) }}" class="btn btn-sm btn-primary" style="font-size:11.5px; padding:4px 12px;">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4" style="color:var(--text-muted); font-size:13px;">
                        <i class="bi bi-inbox me-2"></i>Belum ada laporan. Mulai buat laporan pertama Anda!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Akses Cepat --}}
<p class="dash-section-title"><i class="bi bi-lightning-fill" style="color:var(--warning);"></i> Akses Cepat</p>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="{{ route('pegawai.laporan.create') }}" class="quick-link">
            <div class="quick-link-icon" style="background:#EFF6FF; color:#2563EB;"><i class="bi bi-plus-circle-fill"></i></div>
            <div>
                <div class="quick-link-label">Buat Laporan</div>
                <div class="quick-link-sub">Kirim aspirasi baru</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('pegawai.laporan.index') }}" class="quick-link">
            <div class="quick-link-icon" style="background:#FFFBEB; color:#D97706;"><i class="bi bi-journal-text"></i></div>
            <div>
                <div class="quick-link-label">Laporan Saya</div>
                <div class="quick-link-sub">Lihat semua laporan</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('pegawai.peminjaman-barang.index') }}" class="quick-link">
            <div class="quick-link-icon" style="background:#ECFDF5; color:#059669;"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <div class="quick-link-label">Peminjaman</div>
                <div class="quick-link-sub">Pinjam barang</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('pegawai.akun') }}" class="quick-link">
            <div class="quick-link-icon" style="background:#F5F3FF; color:#7C3AED;"><i class="bi bi-person-gear"></i></div>
            <div>
                <div class="quick-link-label">Akun Saya</div>
                <div class="quick-link-sub">Kelola profil</div>
            </div>
        </a>
    </div>
</div>

@endsection
