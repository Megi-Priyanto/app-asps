@extends('layouts.pegawai')

@section('title', 'Dashboard Pegawai')

@push('css')
<style>
    :root {
        --primary: #2563EB; --primary-light: #EFF6FF;
        --body-bg: #F8FAFC; --card-bg: #fff;
        --border: #E2E8F0; --text-primary: #0F172A;
        --text-secondary: #64748B; --text-muted: #94A3B8;
        --success: #10B981; --warning: #F59E0B; --danger: #EF4444;
        --radius: 12px; --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); }

    .welcome-banner {
        background: linear-gradient(135deg, #2563EB 0%, #7C3AED 100%);
        border-radius: var(--radius); padding: 22px 28px; margin-bottom: 24px;
        display: flex; justify-content: space-between; align-items: center; color: #fff;
    }
    .welcome-banner h2 { font-size: 20px; font-weight: 800; margin: 0 0 4px; }
    .welcome-banner p  { font-size: 13px; opacity: .8; margin: 0; }
    .welcome-avatar {
        width: 52px; height: 52px; border-radius: 50%;
        background: rgba(255,255,255,0.22);
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; font-weight: 800; flex-shrink: 0;
    }
    .section-label {
        font-size: 11px; font-weight: 700; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: .6px; margin-bottom: 12px;
    }
    .stat-cards {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 16px; margin-bottom: 28px;
    }
    @media (max-width: 768px) { .stat-cards { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .stat-cards { grid-template-columns: 1fr; } }
    .stat-card {
        background: var(--card-bg); border: 1px solid var(--border);
        border-radius: var(--radius); box-shadow: var(--shadow);
        padding: 20px 22px; display: flex; align-items: center; gap: 16px;
        transition: transform .18s, box-shadow .18s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; flex-shrink: 0;
    }
    .stat-icon.purple { background: #F5F3FF; color: #7C3AED; }
    .stat-icon.yellow { background: #FFFBEB; color: #D97706; }
    .stat-icon.orange { background: #FFF7ED; color: #EA580C; }
    .stat-icon.green  { background: #F0FDF4; color: #16A34A; }
    .stat-value { font-size: 28px; font-weight: 800; color: var(--text-primary); line-height: 1; margin-bottom: 4px; }
    .stat-label { font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; }
    .two-col {
        display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px;
    }
    @media (max-width: 768px) { .two-col { grid-template-columns: 1fr; } }
    .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); }
    .card-header {
        background: transparent; border-bottom: 1px solid var(--border);
        padding: 14px 18px; font-weight: 700; color: var(--text-primary); font-size: 14px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .card-header .header-link { font-size: 12px; font-weight: 600; color: var(--primary); text-decoration: none; }
    .card-header .header-link:hover { text-decoration: underline; }
    .card-body { padding: 16px 18px; }
    .activity-item {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 11px 0; border-bottom: 1px solid #F8FAFC;
    }
    .activity-item:last-child { border-bottom: none; padding-bottom: 0; }
    .activity-dot { width: 8px; height: 8px; border-radius: 50%; margin-top: 5px; flex-shrink: 0; }
    .dot-green  { background: #10B981; }
    .dot-yellow { background: #F59E0B; }
    .dot-purple { background: #7C3AED; }
    .dot-gray   { background: #CBD5E1; }
    .activity-title { font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 2px; }
    .activity-meta  { font-size: 12px; color: var(--text-secondary); }
    .activity-empty { font-size: 13px; color: var(--text-muted); text-align: center; padding: 24px 0; }
    .badge { font-size: 11.5px; font-weight: 600; padding: 3px 9px; border-radius: 6px; display: inline-block; }
    .badge-menunggu { background: #FFFBEB; color: #B45309; }
    .badge-proses   { background: #FFF7ED; color: #C2410C; }
    .badge-selesai  { background: #ECFDF5; color: #059669; }
    .progress-row { display: flex; justify-content: space-between; font-size: 12.5px; margin-bottom: 5px; }
    .progress-row strong { font-weight: 700; }
    .progress-bar { height: 7px; background: #F1F5F9; border-radius: 4px; overflow: hidden; margin-bottom: 14px; }
    .progress-fill { height: 100%; border-radius: 4px; transition: width .6s ease; }
    .progress-fill.purple { background: #7C3AED; }
    .progress-fill.orange { background: #F97316; }
    .progress-fill.yellow { background: var(--warning); }
    .tip-card {
        background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px;
        padding: 14px 16px; display: flex; gap: 12px; align-items: flex-start;
    }
    .tip-card .tip-icon { font-size: 22px; flex-shrink: 0; line-height: 1; }
    .tip-card .tip-title { font-size: 13px; font-weight: 700; color: #1D4ED8; margin-bottom: 3px; }
    .tip-card .tip-desc  { font-size: 12px; color: #3B82F6; line-height: 1.5; margin: 0; }
    .quick-btn {
        display: flex; align-items: center; gap: 10px; width: 100%;
        background: var(--card-bg); border: 1px solid var(--border);
        border-radius: 8px; padding: 10px 14px;
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px;
        font-weight: 600; color: #374151; cursor: pointer; text-decoration: none;
        transition: background .15s, box-shadow .15s; margin-bottom: 8px;
    }
    .quick-btn:last-child { margin-bottom: 0; }
    .quick-btn:hover { background: #F8FAFC; box-shadow: 0 2px 8px rgba(0,0,0,0.06); color: #374151; }
    .quick-btn i { opacity: .6; font-size: 15px; }
    .side-stack { display: flex; flex-direction: column; gap: 16px; }
    .alert { border: none; border-radius: var(--radius); font-size: 13.5px; font-weight: 500; padding: 12px 16px; }
    .alert-success { background: #ECFDF5; color: #065F46; }
</style>
@endpush

@section('content')

@if (session('success'))
    <div class="alert alert-success mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:12px;"></button>
    </div>
@endif

{{-- ===== WELCOME BANNER ===== --}}
<div class="welcome-banner">
    <div>
        <h2>Halo, {{ Auth::guard('pegawai')->user()->nama }}</h2>
        <p>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} &nbsp;·&nbsp; {{ Auth::guard('pegawai')->user()->jabatan ?? 'Pegawai' }}</p>
    </div>
    <div class="welcome-avatar">
        {{ strtoupper(substr(Auth::guard('pegawai')->user()->nama, 0, 2)) }}
    </div>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="section-label">Ringkasan Laporan</div>
<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-folder2-open"></i></div>
        <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Laporan</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="bi bi-hourglass-split"></i></div>
        <div><div class="stat-value">{{ $stats['menunggu'] }}</div><div class="stat-label">Menunggu</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="bi bi-arrow-repeat"></i></div>
        <div><div class="stat-value">{{ $stats['proses'] }}</div><div class="stat-label">Diproses</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
        <div><div class="stat-value">{{ $stats['selesai'] }}</div><div class="stat-label">Selesai</div></div>
    </div>
</div>

{{-- ===== 2 KOLOM ===== --}}
<div class="two-col">
    <div class="card">
        <div class="card-header">
            <span><i class="bi bi-clock-history me-2" style="color:#2563EB;"></i>Laporan Terbaru</span>
            <a href="{{ route('pegawai.laporan.index') }}" class="header-link">Lihat semua →</a>
        </div>
        <div class="card-body">
            @forelse ($laporanTerbaru as $item)
                @php
                    $status = $item->aspirasi->status ?? 'menunggu';
                    $dotClass = $status === 'selesai' ? 'dot-green' : ($status === 'proses' ? 'dot-yellow' : 'dot-purple');
                @endphp
                <div class="activity-item">
                    <div class="activity-dot {{ $dotClass }}"></div>
                    <div style="flex:1;min-width:0">
                        <div class="activity-title d-flex align-items-center gap-2 flex-wrap">
                            <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px">
                                {{ Str::limit($item->ket, 45) }}
                            </span>
                            <span class="badge badge-{{ $status }}">{{ ucfirst($status) }}</span>
                        </div>
                        <div class="activity-meta">
                            {{ $item->kategori->nama_kategori ?? '-' }} · {{ $item->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <a href="{{ route('pegawai.laporan.show', $item->id) }}" class="btn btn-sm"
                       style="font-size:11px;color:#2563EB;font-weight:600;white-space:nowrap;padding:3px 8px;border:1px solid #BFDBFE;border-radius:6px;background:#EFF6FF;text-decoration:none">
                        Detail
                    </a>
                </div>
            @empty
                <div class="activity-empty">
                    <i class="bi bi-inbox" style="font-size:28px;display:block;margin-bottom:8px;color:#CBD5E1;"></i>
                    Belum ada laporan.
                </div>
            @endforelse
        </div>
    </div>

    <div class="side-stack">
        <div class="tip-card">
            <div class="tip-icon"></div>
            <div>
                <div class="tip-title">Selamat Datang, {{ Auth::guard('pegawai')->user()->nama }}!</div>
                <p class="tip-desc">Pantau dan tangani laporan pengaduan yang masuk dari warga sekolah dengan cepat dan tepat.</p>
            </div>
        </div>

        @if ($stats['total'] > 0)
        <div class="card">
            <div class="card-header"><i class="bi bi-bar-chart-fill me-2" style="color:#2563EB;"></i>Progres Laporan</div>
            <div class="card-body">
                <div class="progress-row">
                    <span>Selesai</span>
                    <strong>{{ $stats['selesai'] }} / {{ $stats['total'] }}</strong>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill green"
                         style="width:{{ $stats['total'] ? round($stats['selesai']/$stats['total']*100) : 0 }}%"></div>
                </div>
                <div class="progress-row">
                    <span>Diproses</span>
                    <strong>{{ $stats['proses'] }} / {{ $stats['total'] }}</strong>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill orange"
                         style="width:{{ $stats['total'] ? round($stats['proses']/$stats['total']*100) : 0 }}%"></div>
                </div>
                <div class="progress-row">
                    <span>Menunggu</span>
                    <strong>{{ $stats['menunggu'] }} / {{ $stats['total'] }}</strong>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill yellow"
                         style="width:{{ $stats['total'] ? round($stats['menunggu']/$stats['total']*100) : 0 }}%"></div>
                </div>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header"><i class="bi bi-lightning-charge-fill me-2" style="color:#F59E0B;"></i>Aksi Cepat</div>
            <div class="card-body">
                <a href="{{ route('pegawai.laporan.create') }}" class="quick-btn">
                    <i class="bi bi-plus-circle"></i> Buat Laporan Baru
                </a>
                <a href="{{ route('pegawai.laporan.index') }}" class="quick-btn">
                    <i class="bi bi-list-ul"></i> Riwayat Semua Laporan
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
