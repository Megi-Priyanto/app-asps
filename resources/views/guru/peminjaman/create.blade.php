@extends('layouts.guru')

@section('title', 'Buat Permintaan Peminjaman')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('guru.peminjaman-barang.index') }}" class="btn btn-sm" style="background:#F1F5F9;border:1px solid #E8EDF5;color:#64748B;border-radius:10px;">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <div>
        <h5 class="mb-0 fw-bold">Buat Permintaan Peminjaman</h5>
        <div style="font-size:12px;color:#94A3B8;">Isi form di bawah untuk mengajukan peminjaman barang</div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger mb-3"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Form Permintaan Peminjaman</div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('guru.peminjaman-barang.store') }}" id="formPeminjaman">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Barang <span class="text-danger">*</span></label>
                        <select name="barang_id" id="selectBarang" class="form-select @error('barang_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $b)
                            <option value="{{ $b->id }}"
                                data-stok="{{ $b->stok_tersedia }}"
                                data-satuan="{{ $b->satuan }}"
                                data-kategori="{{ $b->kategoriBarang->nama_kategori ?? '-' }}"
                                {{ old('barang_id') == $b->id ? 'selected':'' }}>
                                {{ $b->nama_barang }} — Tersedia: {{ $b->stok_tersedia }} {{ $b->satuan }}
                            </option>
                            @endforeach
                        </select>
                        @error('barang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Info stok barang --}}
                    <div id="infoBarang" class="mb-3 d-none">
                        <div style="background:#F0F9FF;border:1px solid #BAE6FD;border-radius:12px;padding:14px 16px;">
                            <div style="font-size:12px;color:#0C4A6E;font-weight:600;margin-bottom:6px;">
                                <i class="bi bi-info-circle me-1"></i>Info Stok Barang
                            </div>
                            <div style="font-size:13px;color:#0C4A6E;">
                                Stok Tersedia: <strong id="infoStok">-</strong> <span id="infoSatuan"></span>
                                &nbsp;·&nbsp; Kategori: <span id="infoKategori"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jumlah Pinjam <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_pinjam" id="jumlahPinjam"
                                class="form-control @error('jumlah_pinjam') is-invalid @enderror"
                                value="{{ old('jumlah_pinjam', 1) }}" min="1" required>
                            @error('jumlah_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pinjam"
                                class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                            @error('tanggal_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Rencana Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kembali_rencana"
                                class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror"
                                value="{{ old('tanggal_kembali_rencana') }}" required>
                            @error('tanggal_kembali_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label fw-semibold">Keperluan / Tujuan <span class="text-danger">*</span></label>
                        <textarea name="keperluan" class="form-control @error('keperluan') is-invalid @enderror"
                            rows="3" required placeholder="Jelaskan tujuan peminjaman barang ini…">{{ old('keperluan') }}</textarea>
                        @error('keperluan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-send me-2"></i>Kirim Permintaan
                        </button>
                        <a href="{{ route('guru.peminjaman-barang.index') }}" class="btn btn-sm" style="background:#F1F5F9;border:1px solid #E8EDF5;color:#64748B;padding:10px 20px;">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100" style="background:linear-gradient(135deg,#F0F9FF 0%,#EFF6FF 100%);border:1px solid #BAE6FD;">
            <div class="card-body p-4">
                <div style="font-size:14px;font-weight:700;color:#0C4A6E;margin-bottom:12px;">
                    <i class="bi bi-info-circle-fill me-2"></i>Informasi Peminjaman
                </div>
                <ul style="font-size:13px;color:#1E40AF;padding-left:18px;line-height:2;">
                    <li>Permintaan akan masuk ke status <strong>Menunggu</strong></li>
                    <li>Petugas Sarpras akan memproses permintaan Anda</li>
                    <li>Pastikan tanggal kembali sudah benar</li>
                    <li>Kembalikan barang sesuai jadwal agar tidak <span class="text-danger">Terlambat</span></li>
                    <li>Kondisi barang saat dikembalikan akan dicatat</li>
                </ul>
                <hr style="border-color:#BAE6FD;">
                <div style="font-size:12px;color:#64748B;">
                    <i class="bi bi-clock-history me-1"></i>
                    Barang yang tersedia hanyalah barang yang ditandai dapat dipinjam oleh Petugas Sarpras.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select   = document.getElementById('selectBarang');
    const infoBox  = document.getElementById('infoBarang');
    const infoStok = document.getElementById('infoStok');
    const infoSat  = document.getElementById('infoSatuan');
    const infoKat  = document.getElementById('infoKategori');
    const jmlInput = document.getElementById('jumlahPinjam');

    select.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        if (this.value) {
            const stok = opt.dataset.stok;
            infoStok.textContent = stok;
            infoSat.textContent  = opt.dataset.satuan;
            infoKat.textContent  = opt.dataset.kategori;
            infoBox.classList.remove('d-none');
            jmlInput.max = stok;
        } else {
            infoBox.classList.add('d-none');
            jmlInput.removeAttribute('max');
        }
    });
});
</script>
@endpush
