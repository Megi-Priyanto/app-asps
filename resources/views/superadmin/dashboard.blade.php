@extends('layouts.superadmin')

@section('title', 'Dashboard')

@push('css')
<style>
/* ── Dashboard Extra Styles ──────────────────────────────────────────── */
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
.stat-card-link { text-decoration: none; display: block; color: inherit; }
.stat-card-link:hover { text-decoration: none; color: inherit; }

.stat-card-mini {
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
.stat-card-mini:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
.stat-icon-sm {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
    flex-shrink: 0;
}
.stat-label-sm { font-size: 11.5px; color: var(--text-muted); font-weight: 500; margin-bottom: 4px; }
.stat-value-sm { font-size: 22px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; line-height: 1; }
.stat-badge {
    margin-left: auto;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    flex-shrink: 0;
}
.stat-badge-warning { background: #FFFBEB; color: #D97706; }
.stat-badge-success { background: #ECFDF5; color: #059669; }
.stat-badge-danger  { background: #FEF2F2; color: #DC2626; }
.stat-badge-blue    { background: #EFF6FF; color: #2563EB; }

/* Chart cards */
.chart-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
}
.chart-card-header {
    padding: 18px 22px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #F1F5F9;
}
.chart-card-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}
.chart-card-title i { font-size: 16px; color: var(--primary); }
.chart-card-subtitle { font-size: 11.5px; color: var(--text-muted); font-weight: 500; margin-top: 2px; }
.chart-card-body { padding: 20px 22px; }

/* Legend */
.chart-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 14px;
}
.chart-legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-secondary);
}
.chart-legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 3px;
    flex-shrink: 0;
}

/* Activity table */
.activity-table { width: 100%; }
.activity-table th {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 10px 16px;
    background: #F8FAFC;
    border-bottom: 1px solid var(--border);
}
.activity-table td {
    padding: 12px 16px;
    font-size: 13px;
    color: var(--text-primary);
    border-bottom: 1px solid #F8FAFC;
    vertical-align: middle;
}
.activity-table tr:last-child td { border-bottom: none; }
.activity-table tr:hover td { background: #FAFCFF; }

/* Badge status */
.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
}
.badge-menunggu    { background: #FFFBEB; color: #D97706; }
.badge-disetujui   { background: #ECFDF5; color: #059669; }
.badge-dipinjam    { background: #EFF6FF; color: #2563EB; }
.badge-terlambat   { background: #FEF2F2; color: #DC2626; }
.badge-dikembalikan{ background: #F0FDF4; color: #16A34A; }
.badge-ditolak     { background: #FFF1F2; color: #BE123C; }
.badge-perbaikan   { background: #F5F3FF; color: #7C3AED; }
.badge-selesai     { background: #F0FDF4; color: #059669; }

/* Welcome banner */
.welcome-banner {
    background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 50%, #3B82F6 100%);
    border-radius: var(--radius);
    padding: 24px 28px;
    color: white;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    overflow: hidden;
    position: relative;
    box-shadow: 0 8px 24px rgba(37,99,235,0.3);
}
.welcome-banner::before {
    content: '';
    position: absolute;
    right: -40px;
    top: -40px;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
}
.welcome-banner::after {
    content: '';
    position: absolute;
    right: 80px;
    bottom: -60px;
    width: 160px;
    height: 160px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
}
.welcome-banner-title { font-size: 20px; font-weight: 800; margin-bottom: 4px; }
.welcome-banner-sub   { font-size: 13px; opacity: 0.8; font-weight: 400; }
.welcome-banner-icon  { font-size: 52px; opacity: 0.25; position: relative; z-index: 1; }

/* Progress bar */
.mini-progress {
    height: 6px;
    background: #E2E8F0;
    border-radius: 99px;
    overflow: hidden;
    margin-top: 8px;
}
.mini-progress-bar {
    height: 100%;
    border-radius: 99px;
    transition: width 1.2s ease;
}

/* Donut summary */
.donut-summary {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 8px;
}
.donut-row {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12.5px;
}
.donut-row-dot {
    width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0;
}
.donut-row-label { flex: 1; color: var(--text-secondary); font-weight: 500; }
.donut-row-val   { font-weight: 700; color: var(--text-primary); }
</style>
@endpush

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner mb-4">
    <div style="position: relative; z-index: 1;">
        <div class="welcome-banner-title">Selamat Datang, {{ auth('superadmin')->user()->nama ?? 'Super Admin' }}!</div>
        <div class="welcome-banner-sub">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} &mdash; Panel Kontrol Super Admin</div>
        <div style="margin-top: 12px; display: flex; gap: 10px; flex-wrap: wrap;">
            <span style="background: rgba(255,255,255,0.15); padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                <i class="bi bi-people-fill me-1"></i>{{ $totalSiswa + $totalGuru + $totalPegawai + $totalAdmin }} Total Pengguna
            </span>
            <span style="background: rgba(255,255,255,0.15); padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                <i class="bi bi-box-seam me-1"></i>{{ $totalBarang }} Barang
            </span>
            <span style="background: rgba(255,255,255,0.15); padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                <i class="bi bi-arrow-left-right me-1"></i>{{ $peminjamanAktif }} Peminjaman Aktif
            </span>
        </div>
    </div>
    <i class="bi bi-shield-shaded welcome-banner-icon d-none d-md-block"></i>
</div>

{{-- ── Statistik Pengguna ────────────────────────────────────────────── --}}
<p class="dash-section-title"><i class="bi bi-people-fill" style="color:var(--primary);"></i> Pengguna Sistem</p>
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-mortarboard-fill"></i></div>
            <div>
                <div class="stat-label">Total Siswa</div>
                <div class="stat-value">{{ number_format($totalSiswa) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-person-video3"></i></div>
            <div>
                <div class="stat-label">Total Guru</div>
                <div class="stat-value">{{ number_format($totalGuru) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-person-badge-fill"></i></div>
            <div>
                <div class="stat-label">Total Pegawai</div>
                <div class="stat-value">{{ number_format($totalPegawai) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-person-gear"></i></div>
            <div>
                <div class="stat-label">Total Admin</div>
                <div class="stat-value">{{ number_format($totalAdmin) }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Statistik Sarpras ─────────────────────────────────────────────── --}}
<p class="dash-section-title"><i class="bi bi-box-seam-fill" style="color:var(--warning);"></i> Sarana & Prasarana</p>
<div class="row g-3 mb-4">
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="stat-card-mini">
            <div class="stat-icon-sm blue"><i class="bi bi-geo-alt-fill"></i></div>
            <div>
                <div class="stat-label-sm">Lokasi</div>
                <div class="stat-value-sm">{{ $totalLokasi }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="stat-card-mini">
            <div class="stat-icon-sm yellow"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <div class="stat-label-sm">Total Barang</div>
                <div class="stat-value-sm">{{ $totalBarang }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="stat-card-mini">
            <div class="stat-icon-sm green"><i class="bi bi-arrow-left-right"></i></div>
            <div>
                <div class="stat-label-sm">Peminjaman</div>
                <div class="stat-value-sm">{{ $totalPeminjaman }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="stat-card-mini">
            <div class="stat-icon-sm blue"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="stat-label-sm">Aktif</div>
                <div class="stat-value-sm">{{ $peminjamanAktif }}</div>
            </div>
            @if($peminjamanMenunggu > 0)
                <span class="stat-badge stat-badge-warning">{{ $peminjamanMenunggu }} menunggu</span>
            @endif
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="stat-card-mini">
            <div class="stat-icon-sm red"><i class="bi bi-tools"></i></div>
            <div>
                <div class="stat-label-sm">Perbaikan</div>
                <div class="stat-value-sm">{{ $totalPerbaikan }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="stat-card-mini">
            <div class="stat-icon-sm green"><i class="bi bi-chat-left-quote-fill"></i></div>
            <div>
                <div class="stat-label-sm">Tanggapan</div>
                <div class="stat-value-sm">{{ $totalTanggapan }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Baris 1 Grafik: Tren Peminjaman & Status Peminjaman ─────────────── --}}
<p class="dash-section-title"><i class="bi bi-bar-chart-fill" style="color:var(--primary);"></i> Analitik & Grafik</p>
<div class="row g-3 mb-4">
    {{-- Grafik Batang: Tren Peminjaman 12 Bulan --}}
    <div class="col-12 col-xl-8">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title"><i class="bi bi-bar-chart-fill"></i> Tren Peminjaman & Laporan</div>
                    <div class="chart-card-subtitle">12 bulan terakhir</div>
                </div>
                <div style="display:flex; gap:12px;">
                    <span class="chart-legend-item">
                        <span class="chart-legend-dot" style="background:#3B82F6;"></span> Peminjaman
                    </span>
                    <span class="chart-legend-item">
                        <span class="chart-legend-dot" style="background:#10B981;"></span> Laporan
                    </span>
                </div>
            </div>
            <div class="chart-card-body">
                <canvas id="chartTrenBulanan" style="height: 280px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Grafik Donat: Status Peminjaman --}}
    <div class="col-12 col-xl-4">
        <div class="chart-card" style="height: 100%;">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title"><i class="bi bi-pie-chart-fill"></i> Status Peminjaman</div>
                    <div class="chart-card-subtitle">Distribusi saat ini</div>
                </div>
            </div>
            <div class="chart-card-body d-flex flex-column align-items-center">
                <canvas id="chartStatusPeminjaman" style="max-height: 180px; max-width: 180px;"></canvas>
                <div class="donut-summary w-100 mt-3">
                    @php
                        $statusColors = [
                            'Menunggu'         => '#F59E0B',
                            'Disetujui'        => '#10B981',
                            'Sedang Dipinjam'  => '#3B82F6',
                            'Terlambat'        => '#EF4444',
                            'Sudah Dikembalikan'=> '#6EE7B7',
                            'Ditolak'          => '#F87171',
                        ];
                    @endphp
                    @forelse($statusPeminjaman as $sp)
                    <div class="donut-row">
                        <div class="donut-row-dot" style="background:{{ $statusColors[$sp->status] ?? '#94A3B8' }};"></div>
                        <div class="donut-row-label">{{ $sp->status }}</div>
                        <div class="donut-row-val">{{ $sp->total }}</div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-2" style="font-size:13px;">Belum ada data</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Baris 2 Grafik: Distribusi Pengguna & Barang per Kategori ────────── --}}
<div class="row g-3 mb-4">
    {{-- Grafik Batang: Distribusi Pengguna --}}
    <div class="col-12 col-md-5">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title"><i class="bi bi-people-fill"></i> Distribusi Pengguna</div>
                    <div class="chart-card-subtitle">Berdasarkan peran</div>
                </div>
            </div>
            <div class="chart-card-body">
                <canvas id="chartPengguna" style="height: 220px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Grafik Donat: Barang per Kategori --}}
    <div class="col-12 col-md-7">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title"><i class="bi bi-archive-fill"></i> Barang per Kategori</div>
                    <div class="chart-card-subtitle">Top 6 kategori</div>
                </div>
            </div>
            <div class="chart-card-body d-flex align-items-center gap-4 flex-wrap">
                <canvas id="chartKategoriBarang" style="max-height: 200px; max-width: 200px; flex-shrink: 0;"></canvas>
                <div class="donut-summary flex-fill" style="min-width: 150px;">
                    @php
                        $katColors = ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6','#06B6D4'];
                    @endphp
                    @forelse($barangPerKategori as $idx => $kat)
                    <div class="donut-row">
                        <div class="donut-row-dot" style="background:{{ $katColors[$idx % count($katColors)] }};"></div>
                        <div class="donut-row-label">{{ $kat->nama_kategori }}</div>
                        <div class="donut-row-val">{{ $kat->barangs_count }}</div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-2" style="font-size:13px;">Belum ada data</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Aktivitas Terbaru ─────────────────────────────────────────────── --}}
<p class="dash-section-title"><i class="bi bi-activity" style="color:var(--success);"></i> Aktivitas Terbaru</p>
<div class="row g-3 mb-4">
    {{-- Peminjaman Terbaru --}}
    <div class="col-12 col-xl-7">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title"><i class="bi bi-arrow-left-right"></i> Peminjaman Terbaru</div>
                    <div class="chart-card-subtitle">5 transaksi terakhir</div>
                </div>
                <a href="{{ route('superadmin.laporan-barang.index') }}" class="btn btn-sm btn-primary" style="font-size:12px;">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div style="overflow-x: auto;">
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Barang</th>
                            <th>Peminjam</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanTerbaru as $pmj)
                        @php
                            $statusClass = match($pmj->status) {
                                'Menunggu'          => 'badge-menunggu',
                                'Disetujui'         => 'badge-disetujui',
                                'Sedang Dipinjam'   => 'badge-dipinjam',
                                'Terlambat'         => 'badge-terlambat',
                                'Sudah Dikembalikan'=> 'badge-dikembalikan',
                                'Ditolak'           => 'badge-ditolak',
                                default             => '',
                            };
                        @endphp
                        <tr>
                            <td><span style="font-family: monospace; font-size:12px;">{{ $pmj->nomor_transaksi }}</span></td>
                            <td>{{ $pmj->barang->nama_barang ?? '-' }}</td>
                            <td>
                                @if($pmj->borrower)
                                    {{ $pmj->borrower->nama ?? $pmj->borrower->name ?? '-' }}
                                @elseif($pmj->nama_peminjam)
                                    {{ $pmj->nama_peminjam }}
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td><span class="badge-status {{ $statusClass }}">{{ $pmj->status }}</span></td>
                            <td style="color:var(--text-muted); font-size:12px;">{{ $pmj->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4" style="font-size:13px;">
                                <i class="bi bi-inbox me-2"></i>Belum ada data peminjaman
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
                <a href="{{ route('superadmin.perbaikan-barang.index') }}" class="btn btn-sm btn-primary" style="font-size:12px;">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div style="overflow-x: auto;">
                <table class="activity-table">
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
                                'Menunggu'        => 'badge-menunggu',
                                'Dalam Perbaikan' => 'badge-perbaikan',
                                'Selesai'         => 'badge-selesai',
                                default           => '',
                            };
                        @endphp
                        <tr>
                            <td><span style="font-family: monospace; font-size:12px;">{{ $pbk->nomor_perbaikan }}</span></td>
                            <td>{{ $pbk->barang->nama_barang ?? '-' }}</td>
                            <td><span class="badge-status {{ $pbkClass }}">{{ $pbk->status }}</span></td>
                            <td style="font-size:12.5px; font-weight:600; color: {{ $pbk->biaya_perbaikan > 0 ? 'var(--danger)' : 'var(--text-muted)' }}">
                                {{ $pbk->biaya_perbaikan > 0 ? 'Rp ' . number_format($pbk->biaya_perbaikan, 0, ',', '.') : '&mdash;' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4" style="font-size:13px;">
                                <i class="bi bi-inbox me-2"></i>Belum ada data perbaikan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Total Biaya --}}
            @if($totalBiayaPerbaikan > 0)
            <div style="padding: 12px 16px; border-top: 1px solid #F1F5F9; background: #FAFCFF; border-radius: 0 0 var(--radius) var(--radius);">
                <div style="font-size: 12px; color: var(--text-muted); font-weight: 500;">Total Biaya Perbaikan (Selesai)</div>
                <div style="font-size: 18px; font-weight: 800; color: var(--danger); margin-top: 2px;">
                    Rp {{ number_format($totalBiayaPerbaikan, 0, ',', '.') }}
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

    // ── Warna & Global Config ────────────────────────────────────────
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#64748B';

    const colors = {
        blue:   { solid: '#3B82F6', light: 'rgba(59,130,246,0.12)' },
        green:  { solid: '#10B981', light: 'rgba(16,185,129,0.12)' },
        yellow: { solid: '#F59E0B', light: 'rgba(245,158,11,0.12)' },
        red:    { solid: '#EF4444', light: 'rgba(239,68,68,0.12)'  },
        purple: { solid: '#8B5CF6', light: 'rgba(139,92,246,0.12)' },
        cyan:   { solid: '#06B6D4', light: 'rgba(6,182,212,0.12)'  },
    };

    // ── 1. Tren Peminjaman & Laporan (Batang Ganda) ──────────────────
    const ctxTren   = document.getElementById('chartTrenBulanan');
    const bulanLabels = @json($bulanLabels);
    const bulanData   = @json($bulanData);
    const laporanData = @json($laporanData);

    new Chart(ctxTren, {
        type: 'bar',
        data: {
            labels: bulanLabels,
            datasets: [
                {
                    label: 'Peminjaman',
                    data: bulanData,
                    backgroundColor: colors.blue.light,
                    borderColor: colors.blue.solid,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                },
                {
                    label: 'Laporan Pengaduan',
                    data: laporanData,
                    backgroundColor: colors.green.light,
                    borderColor: colors.green.solid,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1E293B',
                    titleFont: { size: 12, weight: '700' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 10,
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { font: { size: 11 } },
                },
                y: {
                    grid: { color: '#F1F5F9' },
                    border: { display: false },
                    ticks: { font: { size: 11 }, precision: 0 },
                    beginAtZero: true,
                },
            },
        },
    });

    // ── 2. Donat: Status Peminjaman ──────────────────────────────────
    const ctxStatus = document.getElementById('chartStatusPeminjaman');
    const statusData = @json($statusPeminjaman->pluck('total'));
    const statusLabels = @json($statusPeminjaman->pluck('status'));
    const statusColors = {
        'Menunggu':          '#F59E0B',
        'Disetujui':         '#10B981',
        'Sedang Dipinjam':   '#3B82F6',
        'Terlambat':         '#EF4444',
        'Sudah Dikembalikan':'#6EE7B7',
        'Ditolak':           '#F87171',
    };
    const statusColorArr = statusLabels.map(l => statusColors[l] ?? '#94A3B8');

    if (statusData.length > 0) {
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: statusColorArr,
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverBorderWidth: 4,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        padding: 10,
                        cornerRadius: 10,
                    },
                },
            },
        });
    } else {
        ctxStatus.parentElement.innerHTML += '<p class="text-center text-muted mt-3" style="font-size:13px;">Belum ada data</p>';
    }

    // ── 3. Batang: Distribusi Pengguna ───────────────────────────────
    const ctxPengguna = document.getElementById('chartPengguna');
    const penggunaLabels = @json(array_keys($penggunaPerPeran));
    const penggunaData   = @json(array_values($penggunaPerPeran));
    const penggunaColors = [colors.blue.solid, colors.green.solid, colors.yellow.solid, colors.purple.solid];
    const penggunaLights = [colors.blue.light, colors.green.light, colors.yellow.light, colors.purple.light];

    new Chart(ctxPengguna, {
        type: 'bar',
        data: {
            labels: penggunaLabels,
            datasets: [{
                label: 'Jumlah',
                data: penggunaData,
                backgroundColor: penggunaLights,
                borderColor: penggunaColors,
                borderWidth: 2,
                borderRadius: 10,
                borderSkipped: false,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1E293B',
                    padding: 10,
                    cornerRadius: 10,
                },
            },
            scales: {
                x: {
                    grid: { color: '#F1F5F9' },
                    border: { display: false },
                    ticks: { font: { size: 11 }, precision: 0 },
                    beginAtZero: true,
                },
                y: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { font: { size: 12, weight: '600' } },
                },
            },
        },
    });

    // ── 4. Donat: Barang per Kategori ────────────────────────────────
    const ctxKat = document.getElementById('chartKategoriBarang');
    const katLabels = @json($barangPerKategori->pluck('nama_kategori'));
    const katData   = @json($barangPerKategori->pluck('barangs_count'));
    const katColors = ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6','#06B6D4'];

    if (katData.length > 0) {
        new Chart(ctxKat, {
            type: 'doughnut',
            data: {
                labels: katLabels,
                datasets: [{
                    data: katData,
                    backgroundColor: katColors,
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverBorderWidth: 4,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        padding: 10,
                        cornerRadius: 10,
                    },
                },
            },
        });
    } else {
        ctxKat.parentElement.innerHTML = '<p class="text-muted text-center py-4" style="font-size:13px;">Belum ada data barang</p>';
    }

});
</script>
@endpush
