@extends('layouts.pegawai')

@section('title', 'Buat Permintaan Peminjaman')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('pegawai.peminjaman-barang.index') }}" class="btn btn-sm" style="background:#F1F5F9;border:1px solid #E8EDF5;color:#64748B;border-radius:10px;">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h5 class="mb-0 fw-bold">Buat Permintaan Peminjaman</h5>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Form Permintaan Peminjaman</div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger mb-4"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                @endif

                <form method="POST" action="{{ route('pegawai.peminjaman-barang.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Barang <span class="text-danger">*</span></label>
                        <select name="barang_id" id="selectBarang" class="form-select" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $b)
                            <option value="{{ $b->id }}" data-stok="{{ $b->stok_tersedia }}" data-satuan="{{ $b->satuan }}" data-kategori="{{ $b->kategoriBarang->nama_kategori ?? '-' }}" {{ old('barang_id') == $b->id ? 'selected':'' }}>
                                {{ $b->nama_barang }} — Tersedia: {{ $b->stok_tersedia }} {{ $b->satuan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="infoBarang" class="mb-3 d-none">
                        <div style="background:#F0F9FF;border:1px solid #BAE6FD;border-radius:12px;padding:12px 16px;font-size:13px;color:#0C4A6E;">
                            Stok Tersedia: <strong id="infoStok"></strong> <span id="infoSatuan"></span> · Kategori: <span id="infoKategori"></span>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_pinjam" id="jumlahPinjam" class="form-control" value="{{ old('jumlah_pinjam', 1) }}" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tgl Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Rencana Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kembali_rencana" class="form-control" value="{{ old('tanggal_kembali_rencana') }}" required>
                        </div>
                    </div>
                    <div class="mt-3 mb-3">
                        <label class="form-label fw-semibold">Keperluan <span class="text-danger">*</span></label>
                        <textarea name="keperluan" class="form-control" rows="3" required placeholder="Jelaskan tujuan peminjaman…">{{ old('keperluan') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-send me-2"></i>Kirim Permintaan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card" style="background:#F0F9FF;border:1px solid #BAE6FD;">
            <div class="card-body p-4" style="font-size:13px;color:#0C4A6E;">
                <div style="font-weight:700;margin-bottom:10px;"><i class="bi bi-info-circle-fill me-2"></i>Informasi</div>
                <ul style="padding-left:18px;line-height:2;">
                    <li>Permintaan status <strong>Menunggu</strong> hingga di-ACC Petugas</li>
                    <li>Kembalikan barang tepat waktu</li>
                    <li>Kondisi dikembalikan akan dicatat oleh petugas</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('selectBarang');
    const infoBox = document.getElementById('infoBarang');
    select.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('infoStok').textContent     = opt.dataset.stok;
            document.getElementById('infoSatuan').textContent   = opt.dataset.satuan;
            document.getElementById('infoKategori').textContent = opt.dataset.kategori;
            document.getElementById('jumlahPinjam').max         = opt.dataset.stok;
            infoBox.classList.remove('d-none');
        } else {
            infoBox.classList.add('d-none');
        }
    });
});
</script>
@endpush
