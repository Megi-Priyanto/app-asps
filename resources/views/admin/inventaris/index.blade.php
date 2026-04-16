@extends('layouts.admin')

@section('title', 'Inventaris Barang')

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
                <div class="stat-value">{{ $barangPinjaman }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Toolbar --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.inventaris.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text" style="background:#F8FAFC;border-color:#E8EDF5;"><i class="bi bi-search" style="color:#94A3B8;"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari kode / nama barang…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
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
                <a href="{{ route('admin.inventaris.index') }}" class="btn btn-sm" style="background:#F1F5F9;color:#64748B;border:1px solid #E8EDF5;">Reset</a>
                <a href="{{ route('admin.inventaris.create') }}" class="btn btn-primary btn-sm ms-auto"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
            </div>
        </form>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="alert alert-success mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-3"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}</div>
@endif

{{-- Table --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>Daftar Inventaris Barang</span>
        <span class="text-muted" style="font-size:12px;font-weight:500;">{{ $barangs->total() }} barang ditemukan</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Kondisi</th>
                    <th>Stok</th>
                    <th>Pinjaman</th>
                    <th width="160" class="text-end text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $barang)
                <tr>
                    <td><code style="font-size:12px;color:#2563EB;">{{ $barang->kode_barang }}</code></td>
                    <td>
                        <div style="font-weight:600;color:#0F172A;">{{ $barang->nama_barang }}</div>
                        @if($barang->gambar)
                            <small class="text-muted">Ada foto</small>
                        @endif
                    </td>
                    <td>{{ $barang->kategoriBarang->nama_kategori ?? '-' }}</td>
                    <td>{{ $barang->lokasi_simpan ?? '-' }}</td>
                    <td>
                        @php
                            $kondisiClass = match($barang->kondisi) {
                                'Baik'         => 'inv-badge-baik',
                                'Rusak Ringan' => 'inv-badge-ringan',
                                'Rusak Berat'  => 'inv-badge-berat',
                                default        => 'inv-badge-default',
                            };
                        @endphp
                        <span class="inv-badge {{ $kondisiClass }}">{{ $barang->kondisi }}</span>
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
                        @if($barang->is_pinjaman)
                            <small style="color:#2563EB;">Tersedia: {{ $barang->stok_tersedia }} {{ $barang->satuan }}</small>
                        @endif
                    </td>
                    <td>
                        @if($barang->is_pinjaman)
                            <span class="inv-badge inv-badge-baik"><i class="bi bi-check2 me-1"></i>Ya</span>
                        @else
                            <span class="inv-badge inv-badge-default">Tidak</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.inventaris.show', $barang) }}" class="btn btn-sm btn-secondary">Detail</a>
                            <a href="{{ route('admin.inventaris.edit', $barang) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.inventaris.destroy', $barang) }}" onsubmit="return confirm('Hapus barang ini?')" class="m-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5" style="color:#94A3B8;">
                        <i class="bi bi-archive fs-2 d-block mb-2"></i>
                        Belum ada data inventaris.
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

@endsection

@push('css')
<style>
.inv-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.2px;
}
.inv-badge-baik    { background: #ECFDF5; color: #065F46; }
.inv-badge-ringan  { background: #FFFBEB; color: #78350F; }
.inv-badge-berat   { background: #FEF2F2; color: #991B1B; }
.inv-badge-default { background: #F1F5F9; color: #64748B; }

.inv-btn-detail { background:#EFF6FF; color:#2563EB; border:none; border-radius:8px; width:32px; height:32px; display:flex; align-items:center; justify-content:center; }
.inv-btn-detail:hover { background:#DBEAFE; color:#1D4ED8; }
.inv-btn-edit   { background:#FFFBEB; color:#D97706; border:none; border-radius:8px; width:32px; height:32px; display:flex; align-items:center; justify-content:center; }
.inv-btn-edit:hover { background:#FEF3C7; color:#B45309; }
.inv-btn-hapus  { background:#FEF2F2; color:#DC2626; border:none; border-radius:8px; width:32px; height:32px; display:flex; align-items:center; justify-content:center; }
.inv-btn-hapus:hover { background:#FEE2E2; color:#991B1B; }
</style>
@endpush
