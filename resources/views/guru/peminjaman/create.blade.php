@extends('layouts.guru')

@section('title', 'Buat Permintaan Peminjaman')

@push('css')
<style>
    :root { --primary: #2563EB; --body-bg: #F8FAFC; --border: #E2E8F0; --text-primary: #0F172A; --text-secondary: #64748B; --radius: 12px; --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04); }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); }
    .card { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); margin-bottom: 2rem; }
    .card-header { background: transparent; border-bottom: 1px solid var(--border); padding: 16px 20px; font-weight: 600; font-size: 14.5px; }
    .card-body { padding: 24px; }
    .form-label { font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
    .form-control, .form-select { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13.5px; border: 1.5px solid var(--border); border-radius: 8px; padding: 9px 13px; color: var(--text-primary); transition: border-color 0.15s, box-shadow 0.15s; }
    .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); outline: none; }
    .btn { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; font-size: 13.5px; border-radius: 8px; border: none; padding: 10px 20px; transition: all 0.15s; }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: #1D4ED8; color: white; }
    .invalid-feedback { font-size: 12px; }
</style>
@endpush

@section('content')

@if(session('error'))
    <div class="alert alert-danger mb-3"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}</div>
@endif

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-send-fill me-2" style="color:#2563EB;"></i>Buat Permintaan Peminjaman
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0" style="font-size:13px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('guru.peminjaman-barang.store') }}" id="formPeminjaman">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Pilih Barang</label>
                        <select name="barang_id" id="selectBarang" class="form-select @error('barang_id') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
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
                        <div style="background:#F0F9FF;border:1px solid #BAE6FD;border-radius:8px;padding:12px 16px;">
                            <div style="font-size:12px;color:#0C4A6E;">
                                Stok Tersedia: <strong id="infoStok">-</strong> <span id="infoSatuan"></span>
                                &nbsp;·&nbsp; Kategori: <span id="infoKategori"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah_pinjam" id="jumlahPinjam"
                                class="form-control @error('jumlah_pinjam') is-invalid @enderror"
                                value="{{ old('jumlah_pinjam', 1) }}" min="1" required>
                            @error('jumlah_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Waktu Pinjam</label>
                            <input type="datetime-local" name="tanggal_pinjam"
                                class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                value="{{ old('tanggal_pinjam', date('Y-m-d\TH:i')) }}" required>
                            @error('tanggal_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Batas Kembali</label>
                            <input type="datetime-local" name="tanggal_kembali_rencana"
                                class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror"
                                value="{{ old('tanggal_kembali_rencana') }}" required>
                            @error('tanggal_kembali_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4 mt-3">
                        <label class="form-label">Keperluan</label>
                        <textarea name="keperluan" class="form-control @error('keperluan') is-invalid @enderror"
                            rows="3" required placeholder="Jelaskan detail tujuan peminjaman...">{{ old('keperluan') }}</textarea>
                        @error('keperluan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button class="btn btn-primary w-100">
                        <i class="bi bi-send me-2"></i>Kirim Permintaan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5 d-none d-md-block">
        <div class="card" style="background: linear-gradient(135deg, #EFF6FF, #F8FAFC);">
            <div class="card-body">
                <h6 style="font-weight:700; color:#0F172A; margin-bottom:16px;"><i class="bi bi-info-circle me-2" style="color:#2563EB;"></i>Tips Peminjaman yang Baik</h6>
                <div class="d-flex mb-3 align-items-start gap-3">
                    <div style="width:32px; height:32px; background:#2563EB; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:13px; flex-shrink:0;">1</div>
                    <div>
                        <div style="font-weight:600; font-size:13.5px; color:#0F172A;">Pilih barang yang tersedia</div>
                        <div style="font-size:12px; color:#94A3B8; margin-top:2px;">Sistem otomatis hanya memunculkan daftar barang dengan stok memadai yang dapat dipinjam.</div>
                    </div>
                </div>
                <div class="d-flex mb-3 align-items-start gap-3">
                    <div style="width:32px; height:32px; background:#2563EB; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:13px; flex-shrink:0;">2</div>
                    <div>
                        <div style="font-weight:600; font-size:13.5px; color:#0F172A;">Perhatikan jadwal pinjam</div>
                        <div style="font-size:12px; color:#94A3B8; margin-top:2px;">Tentukan jadwal peminjaman sesuaikan dengan keperluan. Mengembalikan terlambat dikenakan status <span class="text-danger fw-bold">Terlambat</span>.</div>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3">
                    <div style="width:32px; height:32px; background:#2563EB; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:13px; flex-shrink:0;">3</div>
                    <div>
                        <div style="font-weight:600; font-size:13.5px; color:#0F172A;">Menunggu Persetujuan Admin</div>
                        <div style="font-size:12px; color:#94A3B8; margin-top:2px;">Saat permintaan dikirim, status permohonan akan diperiksa oleh petugas sebelum bisa diserahkan kepadamu.</div>
                    </div>
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
