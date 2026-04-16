@extends('layouts.admin')

@section('title', 'Detail Inventaris - ' . $inventari->kode_barang)

@section('content')

<div class="row g-4">
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header">Informasi Barang</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Kode Barang</div>
                        <div style="font-weight:700;font-size:15px;color:#2563EB;">{{ $inventari->kode_barang }}</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Nama Barang</div>
                        <div style="font-weight:700;font-size:15px;">{{ $inventari->nama_barang }}</div>
                    </div>
                    <div class="col-12"><hr style="border-color:#F1F5F9;"></div>
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Kategori</div>
                        <div style="font-weight:600;">{{ $inventari->kategoriBarang->nama_kategori ?? '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Lokasi Simpan</div>
                        <div style="font-weight:600;">{{ $inventari->lokasi_simpan ?? '-' }}</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Total Stok</div>
                        <div style="font-weight:700;font-size:18px;">{{ $inventari->jumlah }} <span style="font-size:13px;font-weight:400;color:#64748B;">{{ $inventari->satuan }}</span></div>
                    </div>
                    <div class="col-8">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Rincian Kondisi</div>
                        <div style="font-size:13px;margin-top:2px;">
                            <span class="badge" style="background:#ECFDF5;color:#065F46;">{{ $inventari->jumlah_baik }} Baik</span>
                            <span class="badge" style="background:#FFFBEB;color:#78350F;">{{ $inventari->jumlah_rusak_ringan }} Rusak Ringan</span>
                            <span class="badge" style="background:#FEF2F2;color:#991B1B;">{{ $inventari->jumlah_rusak_berat }} Rusak Berat</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Status Kondisi Keseluruhan</div>
                        <div>
                            @if($inventari->kondisi == 'Baik')
                                <span class="badge" style="background:#ECFDF5;color:#065F46;">Baik</span>
                            @elseif($inventari->kondisi == 'Rusak Ringan')
                                <span class="badge" style="background:#FFFBEB;color:#78350F;">Rusak Ringan</span>
                            @else
                                <span class="badge" style="background:#FEF2F2;color:#991B1B;">Rusak Berat</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Dapat Dipinjam?</div>
                        <div>
                            @if($inventari->is_pinjaman)
                                <span class="badge" style="background:#EFF6FF;color:#1D4ED8;">Ya</span>
                            @else
                                <span class="badge" style="background:#F1F5F9;color:#64748B;">Tidak</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Tanggal Pengadaan</div>
                        <div>{{ $inventari->tanggal_pengadaan ? \Carbon\Carbon::parse($inventari->tanggal_pengadaan)->format('d F Y') : '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Sumber Dana/Asal</div>
                        <div>{{ $inventari->sumber ?? '-' }}</div>
                    </div>
                    <div class="col-12">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Keterangan</div>
                        <div>{{ $inventari->keterangan ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header">Foto Barang</div>
            <div class="card-body p-4 text-center">
                @if($inventari->gambar)
                    <img src="{{ asset('storage/' . $inventari->gambar) }}" alt="{{ $inventari->nama_barang }}" class="img-fluid rounded" style="max-height:250px;object-fit:contain;">
                @else
                    <div class="d-flex align-items-center justify-content-center" style="height:200px;background:#F8FAFC;border-radius:12px;color:#94A3B8;">
                        <div class="text-center">
                            <i class="bi bi-image fs-1 d-block mb-2"></i>
                            <div>Belum ada foto</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Aksi</div>
            <div class="card-body p-4">
                <a href="{{ route('admin.inventaris.edit', $inventari) }}" class="btn btn-secondary w-100 mb-2">
                    <i class="bi bi-pencil me-1"></i>Edit Data Barang
                </a>
                <form action="{{ route('admin.inventaris.destroy', $inventari) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash me-1"></i>Hapus Barang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
