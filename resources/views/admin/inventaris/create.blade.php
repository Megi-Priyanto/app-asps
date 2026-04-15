@extends('layouts.admin')

@section('title', 'Tambah Barang Inventaris')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.inventaris.index') }}" class="btn btn-sm" style="background:#F1F5F9;border:1px solid #E8EDF5;color:#64748B;border-radius:10px;">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <div>
        <h5 class="mb-0 fw-bold">Tambah Barang Inventaris</h5>
        <div style="font-size:12px;color:#94A3B8;">Daftarkan barang baru ke sistem inventaris</div>
    </div>
</div>

<div class="card" style="max-width:780px;">
    <div class="card-header">Form Data Barang</div>
    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.inventaris.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                {{-- Kode Barang --}}
                <div class="col-md-4">
                    <label class="form-label">Kode Barang <span class="text-danger">*</span></label>
                    <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror"
                        value="{{ old('kode_barang') }}" placeholder="Contoh: LCD-001">
                    @error('kode_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Nama Barang --}}
                <div class="col-md-8">
                    <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror"
                        value="{{ old('nama_barang') }}" placeholder="Contoh: LCD Proyektor Epson">
                    @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Kategori --}}
                <div class="col-md-6">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori_barang_id" class="form-select @error('kategori_barang_id') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_barang_id') == $k->id ? 'selected':'' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_barang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Lokasi Simpan --}}
                <div class="col-md-6">
                    <label class="form-label">Lokasi Penyimpanan</label>
                    <input type="text" name="lokasi_simpan" class="form-control" value="{{ old('lokasi_simpan') }}"
                        placeholder="Contoh: Ruang Sarpras, Lab TKJ">
                </div>

                {{-- Stok --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Jumlah Stok Awal <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label text-success" style="font-size:12px;">Kondisi Baik</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:#ECFDF5;border-color:#A7F3D0;color:#065F46;"><i class="bi bi-check-circle-fill"></i></span>
                                <input type="number" name="jumlah_baik" class="form-control @error('jumlah_baik') is-invalid @enderror"
                                    value="{{ old('jumlah_baik', 0) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-warning" style="font-size:12px;">Rusak Ringan</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:#FFFBEB;border-color:#FCD34D;color:#78350F;"><i class="bi bi-exclamation-circle-fill"></i></span>
                                <input type="number" name="jumlah_rusak_ringan" class="form-control" value="{{ old('jumlah_rusak_ringan', 0) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-danger" style="font-size:12px;">Rusak Berat</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:#FEF2F2;border-color:#FCA5A5;color:#991B1B;"><i class="bi bi-x-circle-fill"></i></span>
                                <input type="number" name="jumlah_rusak_berat" class="form-control" value="{{ old('jumlah_rusak_berat', 0) }}" min="0">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Satuan + Tanggal --}}
                <div class="col-md-4">
                    <label class="form-label">Satuan <span class="text-danger">*</span></label>
                    <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror"
                        value="{{ old('satuan') }}" placeholder="Buah / Unit / Set">
                    @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Pengadaan <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pengadaan" class="form-control @error('tanggal_pengadaan') is-invalid @enderror"
                        value="{{ old('tanggal_pengadaan') }}">
                    @error('tanggal_pengadaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sumber Pengadaan</label>
                    <input type="text" name="sumber" class="form-control" value="{{ old('sumber') }}" placeholder="APBN, Donasi, dll">
                </div>

                {{-- Bisa Dipinjam? --}}
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="isPinjaman" name="is_pinjaman" value="1"
                            {{ old('is_pinjaman') ? 'checked':'' }}>
                        <label class="form-check-label fw-semibold" for="isPinjaman">
                            Barang ini bisa dipinjam oleh Guru / Siswa / Pegawai
                        </label>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan tambahan…">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Foto --}}
                <div class="col-12">
                    <label class="form-label">Foto Barang</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG/PNG, maks 2MB</small>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Simpan Barang</button>
                <a href="{{ route('admin.inventaris.index') }}" class="btn btn-sm" style="background:#F1F5F9;border:1px solid #E8EDF5;color:#64748B;padding:10px 20px;">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
