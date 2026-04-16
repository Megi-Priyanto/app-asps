@extends('layouts.guru')

@section('title', 'Laporan Pengaduan - Guru')

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
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 20px; flex-wrap: wrap; gap: 12px;
    }
    .page-header h1 { font-size: 20px; font-weight: 800; color: var(--text-primary); margin: 0; }
    .page-header p  { font-size: 13px; color: var(--text-secondary); margin: 2px 0 0; }
    .filter-bar {
        background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius);
        box-shadow: var(--shadow); padding: 14px 18px; margin-bottom: 20px;
        display: flex; gap: 12px; align-items: center; flex-wrap: wrap;
    }
    .filter-bar .search-wrap { flex: 1; min-width: 200px; position: relative; }
    .filter-bar .search-wrap i {
        position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
        color: var(--text-muted); font-size: 14px; pointer-events: none;
    }
    .filter-bar input[type="text"] {
        width: 100%; padding: 9px 12px 9px 34px;
        border: 1px solid var(--border); border-radius: 8px;
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px;
        background: #FAFAFA; color: var(--text-primary);
        transition: border-color .2s, box-shadow .2s;
    }
    .filter-bar input[type="text"]:focus {
        outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(5,150,105,0.12); background: #fff;
    }
    .filter-bar select {
        padding: 9px 32px 9px 12px; border: 1px solid var(--border); border-radius: 8px;
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px;
        background: #FAFAFA; color: var(--text-primary); cursor: pointer; appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 10px center;
        transition: border-color .2s;
    }
    .filter-bar select:focus { outline: none; border-color: var(--primary); }
    .btn-reset {
        padding: 9px 14px; border: 1px solid var(--border); border-radius: 8px;
        background: #F8FAFC; font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px; font-weight: 600; color: var(--text-secondary);
        cursor: pointer; text-decoration: none; white-space: nowrap; transition: background .15s;
    }
    .btn-reset:hover { background: #F1F5F9; color: var(--text-primary); }
    .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); }
    .card-header {
        background: transparent; border-bottom: 1px solid var(--border);
        padding: 14px 18px; font-weight: 700; color: var(--text-primary); font-size: 14px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .card-footer { background: transparent; border-top: 1px solid var(--border); padding: 12px 18px; }
    .table { margin: 0; font-size: 13px; }
    .table thead th {
        background: #F8FAFC; color: var(--text-secondary);
        font-weight: 700; font-size: 11px; text-transform: uppercase;
        letter-spacing: .5px; border-bottom: 1px solid var(--border);
        padding: 11px 16px; border-top: none; white-space: nowrap;
    }
    .table tbody td { padding: 13px 16px; border-bottom: 1px solid #F1F5F9; color: var(--text-primary); vertical-align: middle; }
    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover td { background: #EFF6FF; }
    .badge { font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 6px; }
    .badge-menunggu { background: #FFFBEB; color: #B45309; }
    .badge-proses   { background: #FFF7ED; color: #C2410C; }
    .badge-selesai  { background: #EFF6FF; color: #2563EB; }
    .badge-anonim   { background: #F1F5F9; color: #475569; font-size: 10.5px; padding: 2px 7px; border-radius: 4px; }
    .btn-action {
        padding: 5px 10px; border-radius: 7px; font-size: 12px; font-weight: 600;
        border: 1px solid transparent; cursor: pointer; text-decoration: none;
        transition: all .15s; display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-detail { background: #EFF6FF; color: #2563EB; border-color: #BFDBFE; }
    .btn-detail:hover { background: #D1FAE5; color: #1D4ED8; }
    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state .empty-icon { font-size: 48px; display: block; margin-bottom: 12px; color: #CBD5E1; }
    .empty-state h3 { font-size: 16px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; }
    .empty-state p  { font-size: 13px; color: var(--text-muted); margin-bottom: 20px; }
    .btn { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; font-size: 13.5px; border-radius: 8px; border: none; transition: all .15s; }
    .btn-primary { background: var(--primary); color: white; padding: 9px 18px; }
    .btn-primary:hover { background: #1D4ED8; color: white; }
    .alert { border: none; border-radius: var(--radius); font-size: 13.5px; font-weight: 500; padding: 12px 16px; }
    .alert-success { background: #ECFDF5; color: #059669; }
    .summary-pills { display: flex; gap: 8px; flex-wrap: wrap; }
    .pill {
        padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
        border: 1px solid var(--border); cursor: pointer; text-decoration: none;
        transition: all .15s; display: inline-flex; align-items: center; gap: 6px;
        color: var(--text-secondary);
    }
    .pill:hover { border-color: var(--primary); color: var(--primary); text-decoration: none; }
    .pill.active { background: var(--primary); color: white; border-color: var(--primary); }
    .pill .pill-count { font-size: 11px; background: rgba(0,0,0,0.1); border-radius: 10px; padding: 1px 6px; }
    .pill.active .pill-count { background: rgba(255,255,255,0.25); }
</style>
@endpush

@section('content')

@if (session('success'))
    <div class="alert alert-success mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:12px;"></button>
    </div>
@endif

<div class="page-header">
    <div>
        <h1><i class="bi bi-file-earmark-text me-2" style="color:#2563EB;"></i>Laporan Pengaduan</h1>
        <p>Semua laporan pengaduan yang masuk</p>
    </div>
    <a href="{{ route('guru.laporan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Buat Laporan
    </a>
</div>

{{-- ===== FILTER PILLS ===== --}}
<div class="summary-pills mb-3">
    @php $currentStatus = request('status'); @endphp
    <a href="{{ route('guru.laporan.index', array_merge(request()->query(), ['status' => ''])) }}"
       class="pill {{ !$currentStatus ? 'active' : '' }}">
        Semua <span class="pill-count">{{ $stats['total'] }}</span>
    </a>
    <a href="{{ route('guru.laporan.index', array_merge(request()->query(), ['status' => 'menunggu'])) }}"
       class="pill {{ $currentStatus === 'menunggu' ? 'active' : '' }}">
        Menunggu <span class="pill-count">{{ $stats['menunggu'] }}</span>
    </a>
    <a href="{{ route('guru.laporan.index', array_merge(request()->query(), ['status' => 'proses'])) }}"
       class="pill {{ $currentStatus === 'proses' ? 'active' : '' }}">
        Diproses <span class="pill-count">{{ $stats['proses'] }}</span>
    </a>
    <a href="{{ route('guru.laporan.index', array_merge(request()->query(), ['status' => 'selesai'])) }}"
       class="pill {{ $currentStatus === 'selesai' ? 'active' : '' }}">
        Selesai <span class="pill-count">{{ $stats['selesai'] }}</span>
    </a>
</div>

{{-- ===== FILTER BAR ===== --}}
<form method="GET" action="{{ route('guru.laporan.index') }}">
    @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
    <div class="filter-bar">
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari laporan berdasarkan keterangan...">
        </div>
        <select name="kategori">
            <option value="">Semua Kategori</option>
            @foreach($kategori as $kat)
                <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                    {{ $kat->nama_kategori }}
                </option>
            @endforeach
        </select>
        <select name="sort">
            <option value="terbaru" {{ request('sort') !== 'terlama' ? 'selected' : '' }}>Terbaru</option>
            <option value="terlama" {{ request('sort') === 'terlama' ? 'selected' : '' }}>Terlama</option>
        </select>
        <button type="submit" class="btn btn-primary" style="padding:9px 18px;">
            <i class="bi bi-funnel me-1"></i>Filter
        </button>
        @if(request()->hasAny(['search', 'kategori', 'sort', 'status']))
            <a href="{{ route('guru.laporan.index') }}" class="btn-reset">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        @endif
    </div>
</form>

{{-- ===== TABEL LAPORAN ===== --}}
<div class="card">
    <div class="card-header">
        <span>
            <i class="bi bi-table me-2" style="color:#2563EB;"></i>
            Daftar Laporan
            <span style="font-size:12px;font-weight:500;color:var(--text-secondary);margin-left:6px;">
                ({{ $laporan->total() }} laporan ditemukan)
            </span>
        </span>
    </div>

    <div class="table-responsive">
        @if ($laporan->isEmpty())
            <div class="empty-state">
                <i class="bi bi-inbox empty-icon"></i>
                <h3>Tidak ada laporan</h3>
                <p>
                    @if(request()->hasAny(['search', 'kategori', 'status']))
                        Tidak ada laporan yang cocok dengan filter ini.
                    @else
                        Belum ada laporan pengaduan yang masuk.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'kategori', 'status']))
                    <a href="{{ route('guru.laporan.index') }}" class="btn btn-primary btn-sm">Reset Filter</a>
                @endif
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Pelapor</th>
                        <th>Keterangan</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $i => $item)
                        <tr>
                            <td style="color:var(--text-muted);font-weight:600;">
                                {{ $laporan->firstItem() + $i }}
                            </td>
                            <td>
                                <div style="font-weight:600;font-size:13px;">{{ $item->reporter->nama ?? '-' }}</div>
                                <div style="font-size:11px;color:var(--text-muted);">{{ ucfirst(class_basename($item->reporter_type)) }}</div>
                            </td>
                            <td style="max-width:220px;">
                                <div style="font-weight:600;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ Str::limit($item->ket, 55) }}
                                </div>
                            </td>
                            <td>
                                <span style="color:var(--text-secondary);">{{ $item->kategoriAspirasi->nama_kategori ?? '-' }}</span>
                            </td>
                            <td style="max-width:140px;">
                                <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;color:var(--text-secondary);">
                                    {{ Str::limit($item->lokasi, 30) }}
                                </span>
                            </td>
                            <td>
                                @php $status = $item->status ?? 'menunggu'; @endphp
                                <span class="badge badge-{{ $status }}">{{ ucfirst($status) }}</span>
                            </td>
                            <td style="white-space:nowrap;">
                                <div style="font-size:13px;">{{ $item->created_at->format('d M Y') }}</div>
                                <div style="font-size:11px;color:var(--text-muted);">{{ $item->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                <a href="{{ route('guru.laporan.show', $item->id) }}" class="btn-action btn-detail">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @if ($laporan->hasPages())
        <div class="card-footer">
            {{ $laporan->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@endsection
