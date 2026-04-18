@extends('layouts.superadmin')

@section('title', 'Laporan Data Barang')

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-archive-fill"></i></div>
            <div>
                <div class="stat-label">Total Barang</div>
                <div class="stat-value">{{ $totalBarang }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="stat-label">Kondisi Baik</div>
                <div class="stat-value">{{ $barangBaik }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="stat-label">Perlu Perbaikan</div>
                <div class="stat-value">{{ $barangRusak }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-hand-index-thumb-fill"></i></div>
            <div>
                <div class="stat-label">Bisa Dipinjam</div>
                <div class="stat-value">{{ $barangPinjam }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Ringkasan per Lokasi --}}
<div class="card mb-4">
    <div class="card-header">
        <i class="bi bi-bar-chart-fill me-2"></i>Ringkasan per Lokasi
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Lokasi</th>
                    <th class="text-center">Total Barang</th>
                    <th class="text-center">Baik</th>
                    <th class="text-center">Rusak</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ringkasanLokasi as $rl)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#0F172A;">
                            <i class="bi bi-geo-alt text-primary me-1"></i>{{ $rl->nama_lokasi }}
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="fw-bold">{{ $rl->total_barang }}</span>
                    </td>
                    <td class="text-center">
                        <span class="lb-badge lb-badge-baik">{{ $rl->barang_baik }}</span>
                    </td>
                    <td class="text-center">
                        <span class="lb-badge lb-badge-berat">{{ $rl->barang_rusak }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('superadmin.laporan-barang.index', ['lokasi' => $rl->id]) }}"
                           class="btn btn-sm btn-primary" style="font-size:11px;">
                            <i class="bi bi-eye me-1"></i>Lihat
                        </a>
                    </td>
                </tr>
                @endforeach
                @if($ringkasanLokasi->isEmpty())
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data lokasi.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- Toolbar --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('superadmin.laporan-barang.index') }}" class="row g-2 align-items-center" id="filterForm">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text" style="background:#F8FAFC;border-color:#E8EDF5;"><i class="bi bi-search" style="color:#94A3B8;"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari kode / nama…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="lokasi" class="form-select">
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasis as $lok)
                        <option value="{{ $lok->id }}" {{ request('lokasi') == $lok->id ? 'selected':'' }}>{{ $lok->nama_lokasi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected':'' }}>{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="kondisi" class="form-select">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" {{ request('kondisi')=='Baik'?'selected':'' }}>Baik</option>
                    <option value="Rusak Ringan" {{ request('kondisi')=='Rusak Ringan'?'selected':'' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ request('kondisi')=='Rusak Berat'?'selected':'' }}>Rusak Berat</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('superadmin.laporan-barang.index') }}" class="btn btn-sm" style="background:#F1F5F9;color:#64748B;border:1px solid #E8EDF5;">Reset</a>
                <button type="button" class="btn btn-danger btn-sm ms-auto" onclick="doCetak()">
                    <i class="bi bi-printer me-1"></i>Cetak
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>
            Daftar Barang
            @if(request('lokasi'))
                — {{ $lokasis->firstWhere('id', request('lokasi'))?->nama_lokasi ?? '' }}
            @else
                — Semua Lokasi
            @endif
        </span>
        <span class="text-muted" style="font-size:12px;font-weight:500;">{{ $barangs->total() }} barang ditemukan</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Kondisi</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Sumber</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $i => $barang)
                <tr>
                    <td class="text-center">{{ $barangs->firstItem() + $i }}</td>
                    <td><code style="font-size:12px;color:#2563EB;">{{ $barang->kode_barang }}</code></td>
                    <td>
                        <div style="font-weight:600;color:#0F172A;">{{ $barang->nama_barang }}</div>
                        @if($barang->lokasi_simpan)
                            <small class="text-muted">{{ $barang->lokasi_simpan }}</small>
                        @endif
                    </td>
                    <td>{{ $barang->kategoriBarang->nama_kategori ?? '-' }}</td>
                    <td>
                        @if($barang->lokasi)
                            <span class="lb-lokasi-tag">
                                <i class="bi bi-geo-alt-fill"></i>{{ $barang->lokasi->nama_lokasi }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $kondisiClass = match($barang->kondisi) {
                                'Baik'         => 'lb-badge-baik',
                                'Rusak Ringan' => 'lb-badge-ringan',
                                'Rusak Berat'  => 'lb-badge-berat',
                                default        => 'lb-badge-default',
                            };
                        @endphp
                        <span class="lb-badge {{ $kondisiClass }}">{{ $barang->kondisi }}</span>
                    </td>
                    <td>
                        <div style="font-size:13px;">
                            <span class="text-success fw-semibold">{{ $barang->jumlah_baik }}B</span>
                            @if($barang->jumlah_rusak_ringan > 0)
                                · <span class="text-warning fw-semibold">{{ $barang->jumlah_rusak_ringan }}RR</span>
                            @endif
                            @if($barang->jumlah_rusak_berat > 0)
                                · <span class="text-danger fw-semibold">{{ $barang->jumlah_rusak_berat }}RB</span>
                            @endif
                        </div>
                        <small class="text-muted">Total: {{ $barang->jumlah }}</small>
                    </td>
                    <td>{{ $barang->satuan }}</td>
                    <td>{{ $barang->sumber ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5" style="color:#94A3B8;">
                        <i class="bi bi-clipboard-x fs-2 d-block mb-2"></i>
                        Belum ada data barang.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($barangs->hasPages())
    <div class="card-footer">{{ $barangs->links() }}</div>
    @endif
</div>

<script>
function doCetak() {
    const form = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form)).toString();
    const url = '{{ route("superadmin.laporan-barang.cetak") }}?' + params;
    window.open(url, '_blank');
}
</script>

@endsection

@push('css')
<style>
.lb-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.2px;
}
.lb-badge-baik    { background: #ECFDF5; color: #065F46; }
.lb-badge-ringan  { background: #FFFBEB; color: #78350F; }
.lb-badge-berat   { background: #FEF2F2; color: #991B1B; }
.lb-badge-default { background: #F1F5F9; color: #64748B; }

.lb-lokasi-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    background: #EFF6FF;
    color: #2563EB;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
}
.lb-lokasi-tag i { font-size: 11px; }
</style>
@endpush
