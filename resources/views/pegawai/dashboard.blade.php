@extends('layouts.pegawai')

@section('title', 'Dashboard Pegawai')

@push('css')
<style>
    :root {
        --primary: #F59E0B; --primary-light: #FFFBEB;
        --body-bg: #F8FAFC; --card-bg: #fff;
        --border: #E2E8F0; --text-primary: #0F172A;
        --text-secondary: #64748B; --text-muted: #94A3B8;
        --success: #10B981; --warning: #F59E0B; --danger: #EF4444;
        --radius: 12px; --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); }

    /* ===== WELCOME BANNER ===== */
    .welcome-banner {
        background: linear-gradient(135deg, #F59E0B 0%, #EA580C 100%);
        border-radius: var(--radius);
        padding: 22px 28px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #fff;
    }
    .welcome-banner h2 { font-size: 20px; font-weight: 800; margin: 0 0 4px; }
    .welcome-banner p  { font-size: 13px; opacity: .8; margin: 0; }
    .welcome-avatar {
        width: 52px; height: 52px;
        border-radius: 50%;
        background: rgba(255,255,255,0.22);
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; font-weight: 800;
        flex-shrink: 0;
    }

    /* ===== SECTION LABEL ===== */
    .section-label {
        font-size: 11px; font-weight: 700; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: .6px;
        margin-bottom: 12px;
    }

    /* ===== STAT CARDS ===== */
    .stat-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }
    @media (max-width: 992px) { .stat-cards { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .stat-cards { grid-template-columns: 1fr; } }

    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform .18s, box-shadow .18s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; flex-shrink: 0;
    }
    .stat-icon.orange { background: #FFF7ED; color: #EA580C; }
    .stat-icon.yellow { background: #FFFBEB; color: #D97706; }
    .stat-icon.blue   { background: #EFF6FF; color: #3B82F6; }
    .stat-icon.green  { background: #F0FDF4; color: #16A34A; }
    .stat-value { font-size: 28px; font-weight: 800; color: var(--text-primary); line-height: 1; margin-bottom: 4px; }
    .stat-label { font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; }

    /* ===== GRID 2 KOLOM ===== */
    .two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 28px;
    }
    @media (max-width: 768px) { .two-col { grid-template-columns: 1fr; } }

    /* ===== CARD ===== */
    .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); }
    .card-header {
        background: transparent; border-bottom: 1px solid var(--border);
        padding: 14px 18px; font-weight: 700; color: var(--text-primary); font-size: 14px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .card-header .header-link { font-size: 12px; font-weight: 600; color: var(--primary); text-decoration: none; }
    .card-header .header-link:hover { text-decoration: underline; }
    .card-body { padding: 16px 18px; }

    /* ===== AKTIVITAS ===== */
    .activity-item {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 12px 0; border-bottom: 1px solid #F8FAFC;
    }
    .activity-item:last-child { border-bottom: none; padding-bottom: 0; }
    .activity-dot { width: 8px; height: 8px; border-radius: 50%; margin-top: 5px; flex-shrink: 0; }
    .dot-green  { background: #10B981; }
    .dot-yellow { background: #F59E0B; }
    .dot-blue   { background: #3B82F6; }
    .dot-orange { background: #EA580C; }
    .activity-title { font-size: 13.5px; font-weight: 600; color: var(--text-primary); margin-bottom: 2px; }
    .activity-meta  { font-size: 12px; color: var(--text-secondary); }
    .activity-empty { font-size: 13px; color: var(--text-muted); text-align: center; padding: 32px 0; }

    /* ===== BADGE ===== */
    .badge { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-block; text-transform: uppercase; letter-spacing: 0.3px; }
    .badge-menunggu  { background: #FFFBEB; color: #B45309; border: 1px solid #FEF3C7; }
    .badge-proses    { background: #EFF6FF; color: #1D4ED8; border: 1px solid #DBEAFE; }
    .badge-selesai   { background: #ECFDF5; color: #059669; border: 1px solid #D1FAE5; }

    /* ===== PROGRESS ===== */
    .progress-row { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px; }
    .progress-row strong { font-weight: 700; color: var(--text-primary); }
    .progress-bar { height: 8px; background: #F1F5F9; border-radius: 4px; overflow: hidden; margin-bottom: 16px; }
    .progress-fill { height: 100%; border-radius: 4px; transition: width .6s ease; }
    .progress-fill.green  { background: var(--success); }
    .progress-fill.blue   { background: var(--primary); }
    .progress-fill.yellow { background: var(--warning); }

    /* ===== TIP CARD ===== */
    .tip-card {
        background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
        border: 1px solid #FDE68A; border-radius: 14px;
        padding: 16px 20px; display: flex; gap: 14px; align-items: center;
        margin-bottom: 20px;
    }
    .tip-icon { font-size: 24px; flex-shrink: 0; }
    .tip-title { font-size: 14px; font-weight: 700; color: #B45309; margin-bottom: 2px; }
    .tip-desc  { font-size: 12.5px; color: #D97706; line-height: 1.5; margin: 0; opacity: 0.9; }

    /* ===== QUICK ACTION ===== */
    .quick-btn {
        display: flex; align-items: center; gap: 12px; width: 100%;
        background: #fff; border: 1px solid var(--border);
        border-radius: 12px; padding: 14px 18px;
        font-size: 14px; font-weight: 700; color: var(--text-primary);
        cursor: pointer; text-decoration: none;
        transition: all 0.2s;
        margin-bottom: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .quick-btn:last-child { margin-bottom: 0; }
    .quick-btn:hover { background: #fdfdfd; border-color: #FDE68A; transform: translateX(5px); color: var(--primary); }
    .quick-btn i { font-size: 18px; opacity: 0.8; }

    .side-stack { display: flex; flex-direction: column; gap: 20px; }
    .alert { border: none; border-radius: var(--radius); font-size: 13.5px; font-weight: 500; padding: 14px 20px; }
    .alert-success { background: #ECFDF5; color: #065F46; box-shadow: 0 2px 10px rgba(16,185,129,0.1); }
</style>
@endpush

@section('content')

@if (session('success'))
    <div class="alert alert-success mb-4 d-flex align-items-center gap-3">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- ===== WELCOME BANNER ===== --}}
<div class="welcome-banner">
    <div>
        <h2>Halo, {{ Auth::guard('pegawai')->user()->nama }}</h2>
        <p>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} &nbsp;•&nbsp; Pegawai Sekolah</p>
    </div>
    <div class="welcome-avatar" style="overflow:hidden; border:2px solid rgba(255,255,255,0.3);">
        @if(Auth::guard('pegawai')->user()->foto)
            <img src="{{ asset('storage/' . Auth::guard('pegawai')->user()->foto) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">
        @else
            {{ strtoupper(substr(Auth::guard('pegawai')->user()->nama, 0, 2)) }}
        @endif
    </div>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="section-label">Ringkasan Laporan Saya</div>
<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-icon orange"><i class="bi bi-stack"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="bi bi-hourglass-top"></i></div>
        <div>
            <div class="stat-value">{{ $stats['menunggu'] }}</div>
            <div class="stat-label">Menunggu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-arrow-repeat"></i></div>
        <div>
            <div class="stat-value">{{ $stats['proses'] }}</div>
            <div class="stat-label">Diproses</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-check-lg"></i></div>
        <div>
            <div class="stat-value">{{ $stats['selesai'] }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
</div>

{{-- ===== 2 KOLOM: AKTIVITAS + PANEL KANAN ===== --}}
<div class="two-col">

    {{-- Aktivitas Terbaru --}}
    <div class="card">
        <div class="card-header">
            <span><i class="bi bi-clock-history me-2" style="color:var(--primary);"></i>Aktivitas Terbaru Anda</span>
            <a href="{{ route('pegawai.laporan.index') }}" class="header-link">Semua &rarr;</a>
        </div>
        <div class="card-body">
            @forelse ($laporanTerbaru as $item)
                @php
                    $status = $item->status ?? 'menunggu';
                    $dotClass = match($status) {
                        'selesai' => 'dot-green',
                        'proses' => 'dot-blue',
                        default => 'dot-yellow'
                    };
                    $badgeClass = 'badge-' . $status;
                @endphp
                <div class="activity-item">
                    <div class="activity-dot {{ $dotClass }}"></div>
                    <div style="flex:1; min-width:0">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                            <span class="activity-title" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ Str::limit($item->ket, 50) }}
                            </span>
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                        </div>
                        <div class="activity-meta">
                            <i class="bi bi-calendar3 me-1"></i> {{ $item->created_at->diffForHumans() }} &nbsp;•&nbsp; {{ $item->kategori->nama ?? 'Sarpras' }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="activity-empty">
                    <i class="bi bi-inbox-fill" style="font-size:32px; display:block; margin-bottom:10px; opacity:0.3;"></i>
                    Anda belum memiliki riwayat pengaduan.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Panel Kanan --}}
    <div class="side-stack">

        {{-- Progress Laporan --}}
        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-pie-chart-fill me-2" style="color:var(--success);"></i>Status Penyelesaian</span>
            </div>
            <div class="card-body">
                @php
                    $total = $stats['total'] > 0 ? $stats['total'] : 1;
                    $pctSelesai = round(($stats['selesai'] / $total) * 100);
                    $pctProses = round(($stats['proses'] / $total) * 100);
                    $pctMenunggu = round(($stats['menunggu'] / $total) * 100);
                @endphp

                <div class="progress-row">
                    <span>Selesai</span>
                    <strong>{{ $stats['selesai'] }} / {{ $stats['total'] ?: 0 }}</strong>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill green" style="width: {{ $pctSelesai }}%"></div>
                </div>

                <div class="progress-row">
                    <span>Diproses</span>
                    <strong>{{ $stats['proses'] }} / {{ $stats['total'] ?: 0 }}</strong>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill blue" style="width: {{ $pctProses }}%"></div>
                </div>

                <div class="progress-row">
                    <span>Menunggu</span>
                    <strong>{{ $stats['menunggu'] }} / {{ $stats['total'] ?: 0 }}</strong>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill yellow" style="width: {{ $pctMenunggu }}%"></div>
                </div>
            </div>
        </div>

        {{-- Aksi Cepat --}}
        <div>
            <div class="section-label">Aksi Cepat Pegawai</div>
            <a href="{{ route('pegawai.laporan.create') }}" class="quick-btn">
                <i class="bi bi-plus-circle-fill" style="color:var(--primary);"></i>
                <span>Ajukan Aspirasi Baru</span>
            </a>
            <a href="{{ route('pegawai.peminjaman-barang.index') }}" class="quick-btn">
                <i class="bi bi-box-seam-fill" style="color:var(--success);"></i>
                <span>Pinjam Sarana Prasarana</span>
            </a>
            <a href="{{ route('pegawai.laporan.index') }}" class="quick-btn">
                <i class="bi bi-clipboard2-data-fill" style="color:var(--warning);"></i>
                <span>Monitoring Laporan Saya</span>
            </a>
        </div>

        {{-- Tip Help --}}
        <div class="tip-card">
            <div class="tip-icon">🔧</div>
            <div>
                <div class="tip-title">Bantuan Teknis</div>
                <p class="tip-desc">Gunakan menu bantuan jika Anda menemui kendala dalam penggunaan sistem.</p>
            </div>
        </div>

    </div>
</div>

@endsection
