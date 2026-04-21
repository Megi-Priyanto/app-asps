@extends('layouts.siswa')

@section('title', 'Buat Laporan')

@push('css')
<style>
    :root { --primary: #2563EB; --body-bg: #F8FAFC; --border: #E2E8F0; --text-primary: #0F172A; --text-secondary: #64748B; --radius: 12px; --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04); }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); }
    .card { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); }
    .card-header { background: transparent; border-bottom: 1px solid var(--border); padding: 16px 20px; font-weight: 600; font-size: 14.5px; }
    .card-body { padding: 24px; }
    .form-label { font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
    .form-control, .form-select { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13.5px; border: 1.5px solid var(--border); border-radius: 8px; padding: 9px 13px; color: var(--text-primary); transition: border-color 0.15s, box-shadow 0.15s; }
    .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); outline: none; }
    .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }
    .form-check-label { font-size: 13.5px; color: var(--text-secondary); }
    .btn { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; font-size: 13.5px; border-radius: 8px; border: none; padding: 10px 20px; transition: all 0.15s; }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: #1D4ED8; color: white; }
    .invalid-feedback { font-size: 12px; }
    .file-drop { border: 2px dashed var(--border); border-radius: 10px; padding: 24px; text-align: center; color: var(--text-secondary); background: #F8FAFC; cursor: pointer; transition: border-color 0.15s; }
    .file-drop:hover { border-color: var(--primary); }
    .file-drop i { font-size: 28px; color: #CBD5E1; display: block; margin-bottom: 6px; }
    .form-select:disabled { background: #F1F5F9; color: #94A3B8; cursor: not-allowed; }
    .kategori-loading { display: none; font-size: 12px; color: var(--primary); margin-top: 4px; }
    .kategori-loading.show { display: block; }
</style>
@endpush

@section('content')

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-send-fill me-2" style="color:#2563EB;"></i>Buat Laporan Pengaduan
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- 1. Pilih Lokasi --}}
                    <div class="mb-3">
                        <label class="form-label">Lokasi Kejadian</label>
                        <select name="lokasi" id="lokasi-select" class="form-select @error('lokasi') is-invalid @enderror" required>
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($lokasi as $lok)
                                <option value="{{ $lok->nama_lokasi }}" data-id="{{ $lok->id }}" {{ old('lokasi') == $lok->nama_lokasi ? 'selected' : '' }}>
                                    {{ $lok->nama_lokasi }}
                                </option>
                            @endforeach
                        </select>
                        @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- 2. Pilih Kategori (dinamis berdasarkan lokasi) --}}
                    <div class="mb-3">
                        <label class="form-label">Kategori Aspirasi</label>
                        <select name="kategori_aspirasi_id" id="kategori-select" class="form-select @error('kategori_aspirasi_id') is-invalid @enderror" required disabled>
                            <option value="">-- Pilih Lokasi Terlebih Dahulu --</option>
                        </select>
                        <div class="kategori-loading" id="kategori-loading">
                            <i class="bi bi-arrow-repeat"></i> Memuat kategori...
                        </div>
                        @error('kategori_aspirasi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- 3. Foto --}}
                    <div class="mb-3">
                        <label class="form-label">Foto Bukti / Lampiran <span style="color:#94A3B8; font-weight:400;">(Opsional)</span></label>
                        <input class="form-control" type="file" name="foto" accept="image/*">
                    </div>

                    {{-- 4. Keterangan --}}
                    <div class="mb-3">
                        <x-textarea label="Keterangan" name="ket" placeholder="Jelaskan detail kerusakan atau masalah yang terjadi..." rows="5" />
                    </div>

                    <button class="btn btn-primary w-100">
                        <i class="bi bi-send me-2"></i>Kirim Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5 d-none d-md-block">
        <div class="card" style="background: linear-gradient(135deg, #EFF6FF, #F8FAFC);">
            <div class="card-body">
                <h6 style="font-weight:700; color:#0F172A; margin-bottom:16px;"><i class="bi bi-info-circle me-2" style="color:#2563EB;"></i>Tips Laporan yang Baik</h6>
                <div class="d-flex mb-3 align-items-start gap-3">
                    <div style="width:32px; height:32px; background:#2563EB; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:13px; flex-shrink:0;">1</div>
                    <div>
                        <div style="font-weight:600; font-size:13.5px; color:#0F172A;">Pilih lokasi yang tepat</div>
                        <div style="font-size:12px; color:#94A3B8; margin-top:2px;">Pilih lokasi agar kategori aspirasi muncul otomatis.</div>
                    </div>
                </div>
                <div class="d-flex mb-3 align-items-start gap-3">
                    <div style="width:32px; height:32px; background:#2563EB; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:13px; flex-shrink:0;">2</div>
                    <div>
                        <div style="font-weight:600; font-size:13.5px; color:#0F172A;">Sertakan foto bukti</div>
                        <div style="font-size:12px; color:#94A3B8; margin-top:2px;">Foto membantu admin memahami kondisi sebenarnya.</div>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3">
                    <div style="width:32px; height:32px; background:#2563EB; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:13px; flex-shrink:0;">3</div>
                    <div>
                        <div style="font-weight:600; font-size:13.5px; color:#0F172A;">Tulis keterangan jelas</div>
                        <div style="font-size:12px; color:#94A3B8; margin-top:2px;">Deskripsikan masalah secara spesifik dan lengkap.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lokasiSelect   = document.getElementById('lokasi-select');
    const kategoriSelect = document.getElementById('kategori-select');
    const kategoriLoading = document.getElementById('kategori-loading');
    const oldKategori    = "{{ old('kategori_aspirasi_id') }}";

    lokasiSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const lokasiId = selectedOption.getAttribute('data-id');

        // Reset kategori
        kategoriSelect.innerHTML = '<option value="">-- Memuat Kategori... --</option>';
        kategoriSelect.disabled = true;

        if (!lokasiId) {
            kategoriSelect.innerHTML = '<option value="">-- Pilih Lokasi Terlebih Dahulu --</option>';
            return;
        }

        // Show loading
        kategoriLoading.classList.add('show');

        // Fetch kategori berdasarkan lokasi
        fetch(`/api/lokasi/${lokasiId}/kategori`)
            .then(response => response.json())
            .then(data => {
                kategoriLoading.classList.remove('show');
                kategoriSelect.innerHTML = '<option value="">-- Pilih Kategori --</option>';

                if (data.length === 0) {
                    kategoriSelect.innerHTML = '<option value="">-- Tidak ada kategori di lokasi ini --</option>';
                    return;
                }

                data.forEach(kat => {
                    const option = document.createElement('option');
                    option.value = kat.id;
                    option.textContent = kat.nama_kategori;
                    if (oldKategori == kat.id) option.selected = true;
                    kategoriSelect.appendChild(option);
                });

                kategoriSelect.disabled = false;
            })
            .catch(() => {
                kategoriLoading.classList.remove('show');
                kategoriSelect.innerHTML = '<option value="">-- Gagal memuat kategori --</option>';
            });
    });

    // Trigger change jika ada old value
    if (lokasiSelect.value) {
        lokasiSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
