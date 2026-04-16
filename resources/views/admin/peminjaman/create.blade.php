@extends('layouts.admin')

@section('title', 'Buat Permintaan Peminjaman')

@push('css')
<style>
    :root { --primary: #0d6efd; --body-bg: #F8FAFC; --border: #E2E8F0; --text-primary: #0F172A; --text-secondary: #64748B; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); }
    .card { background: #fff; border: 1px solid var(--border); border-radius: 6px; box-shadow: none; margin-bottom: 2rem; }
    .card-header { background: #FAFAFA; border-bottom: 1px solid var(--border); padding: 14px 20px; font-weight: 500; font-size: 14px; color: #333; }
    .card-header-section { background: #F8FAFC; border-bottom: 1px solid var(--border); border-top: 1px solid var(--border); padding: 10px 20px; font-weight: 600; font-size: 12px; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 -24px 20px; }
    .card-body { padding: 24px; }
    .form-label { font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px; }
    .form-control, .form-select { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; border: 1px solid #ced4da; border-radius: 6px; padding: 10px 14px; color: #495057; transition: border-color 0.15s, box-shadow 0.15s; }
    .form-control:focus, .form-select:focus { border-color: #86b7fe; box-shadow: 0 0 0 .25rem rgba(13,110,253,.25); outline: none; }
    .btn { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 500; font-size: 14px; border-radius: 6px; border: none; padding: 10px 20px; transition: all 0.15s; }
    .btn-primary { background: #0d6efd; color: white; }
    .btn-primary:hover { background: #0b5ed7; color: white; }
    .invalid-feedback { font-size: 12px; }

    /* Role selector pill buttons */
    .role-selector { display: flex; gap: 8px; flex-wrap: wrap; }
    .role-btn { cursor: pointer; border: 1.5px solid var(--border); border-radius: 20px; padding: 6px 18px; font-size: 13px; font-weight: 600; color: #64748B; background: #fff; transition: all 0.15s; }
    .role-btn:hover { border-color: #0d6efd; color: #0d6efd; background: #EFF6FF; }
    .role-btn.active { border-color: #0d6efd; color: #fff; background: #0d6efd; }
    .role-btn.active.guru  { background: #2563EB; border-color: #2563EB; }
    .role-btn.active.pegawai { background: #7C3AED; border-color: #7C3AED; }
    .role-btn.active.siswa { background: #059669; border-color: #059669; }

    .identitas-fields { display: none; }
    .identitas-fields.show { display: block; }

    .divider-label { display: flex; align-items: center; gap: 10px; margin: 20px 0 16px; color: #94A3B8; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .divider-label::before, .divider-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }
</style>
@endpush

@section('content')

@if(session('error'))
    <div class="alert alert-danger mb-3"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}</div>
@endif

<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                Form Permintaan Peminjaman
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0" style="font-size:13px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.peminjaman-barang.store') }}" id="formPeminjaman">
                    @csrf

                    {{-- ===== BAGIAN IDENTITAS PEMINJAM ===== --}}
                    <div class="divider-label"><i class="bi bi-person-badge me-1"></i> Identitas Peminjam</div>

                    {{-- Pilih Peran --}}
                    <div class="mb-3">
                        <label class="form-label">Peran Peminjam</label>
                        <div class="role-selector mb-1" id="roleSelector">
                            <span class="role-btn siswa {{ old('peran_peminjam') == 'Siswa' ? 'active' : '' }}" data-role="Siswa"><i class="bi bi-mortarboard me-1"></i>Siswa</span>
                            <span class="role-btn guru {{ old('peran_peminjam') == 'Guru' ? 'active' : '' }}" data-role="Guru"><i class="bi bi-person-workspace me-1"></i>Guru</span>
                            <span class="role-btn pegawai {{ old('peran_peminjam') == 'Pegawai' ? 'active' : '' }}" data-role="Pegawai"><i class="bi bi-briefcase me-1"></i>Pegawai</span>
                        </div>
                        <input type="hidden" name="peran_peminjam" id="peranPeminjam" value="{{ old('peran_peminjam') }}">
                        @error('peran_peminjam')<div class="text-danger" style="font-size:12px;">{{ $message }}</div>@enderror
                    </div>

                    {{-- Form Siswa --}}
                    <div class="identitas-fields row g-3 mb-3 {{ old('peran_peminjam') == 'Siswa' ? 'show' : '' }}" id="fieldsSiswa">
                        <div class="col-md-6">
                            <label class="form-label">Nama Siswa</label>
                            <input type="text" id="namaSiswa" class="form-control" placeholder="Nama lengkap siswa" value="{{ old('peran_peminjam') == 'Siswa' ? old('nama_peminjam') : '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kelas</label>
                            <input type="text" id="detailSiswa" class="form-control" placeholder="Contoh: X RPL A" value="{{ old('peran_peminjam') == 'Siswa' ? old('detail_peminjam') : '' }}">
                        </div>
                    </div>

                    {{-- Form Guru --}}
                    <div class="identitas-fields row g-3 mb-3 {{ old('peran_peminjam') == 'Guru' ? 'show' : '' }}" id="fieldsGuru">
                        <div class="col-md-6">
                            <label class="form-label">Nama Guru</label>
                            <input type="text" id="namaGuru" class="form-control" placeholder="Nama lengkap guru" value="{{ old('peran_peminjam') == 'Guru' ? old('nama_peminjam') : '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan / Mata Pelajaran</label>
                            <input type="text" id="detailGuru" class="form-control" placeholder="Contoh: Guru Matematika" value="{{ old('peran_peminjam') == 'Guru' ? old('detail_peminjam') : '' }}">
                        </div>
                    </div>

                    {{-- Form Pegawai --}}
                    <div class="identitas-fields row g-3 mb-3 {{ old('peran_peminjam') == 'Pegawai' ? 'show' : '' }}" id="fieldsPegawai">
                        <div class="col-md-6">
                            <label class="form-label">Nama Pegawai</label>
                            <input type="text" id="namaPegawai" class="form-control" placeholder="Nama lengkap pegawai" value="{{ old('peran_peminjam') == 'Pegawai' ? old('nama_peminjam') : '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan di Sekolah</label>
                            <input type="text" id="detailPegawai" class="form-control" placeholder="Contoh: Staf TU" value="{{ old('peran_peminjam') == 'Pegawai' ? old('detail_peminjam') : '' }}">
                        </div>
                    </div>

                    {{-- Hidden fields untuk submit --}}
                    <input type="hidden" name="nama_peminjam" id="namaPeminjamHidden" value="{{ old('nama_peminjam') }}">
                    <input type="hidden" name="detail_peminjam" id="detailPeminjamHidden" value="{{ old('detail_peminjam') }}">

                    {{-- ===== BAGIAN DATA PEMINJAMAN ===== --}}
                    <div class="divider-label"><i class="bi bi-box-seam me-1"></i> Data Peminjaman</div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Barang</label>
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
                        <div style="background:#F0F9FF;border:1px solid #BAE6FD;border-radius:6px;padding:12px 14px;">
                            <div style="font-size:13px;color:#0C4A6E;">
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
                            <label class="form-label">Tgl Pinjam</label>
                            <input type="datetime-local" name="tanggal_pinjam"
                                class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                value="{{ old('tanggal_pinjam', date('Y-m-d\TH:i')) }}" required>
                            @error('tanggal_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Rencana Kembali</label>
                            <input type="datetime-local" name="tanggal_kembali_rencana"
                                class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror"
                                value="{{ old('tanggal_kembali_rencana') }}" required>
                            @error('tanggal_kembali_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4 mt-3">
                        <label class="form-label">Keperluan</label>
                        <textarea name="keperluan" class="form-control @error('keperluan') is-invalid @enderror"
                            rows="3" required placeholder="Jelaskan tujuan peminjaman...">{{ old('keperluan') }}</textarea>
                        @error('keperluan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-send me-2"></i>Kirim Permintaan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ---- Barang info ----
    const select   = document.getElementById('selectBarang');
    const infoBox  = document.getElementById('infoBarang');
    const infoStok = document.getElementById('infoStok');
    const infoSat  = document.getElementById('infoSatuan');
    const infoKat  = document.getElementById('infoKategori');
    const jmlInput = document.getElementById('jumlahPinjam');

    select.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        if (this.value) {
            infoStok.textContent = opt.dataset.stok;
            infoSat.textContent  = opt.dataset.satuan;
            infoKat.textContent  = opt.dataset.kategori;
            infoBox.classList.remove('d-none');
            jmlInput.max = opt.dataset.stok;
        } else {
            infoBox.classList.add('d-none');
            jmlInput.removeAttribute('max');
        }
    });

    // ---- Role selector ----
    const roleBtns = document.querySelectorAll('.role-btn');
    const peranInput        = document.getElementById('peranPeminjam');
    const namaHidden        = document.getElementById('namaPeminjamHidden');
    const detailHidden      = document.getElementById('detailPeminjamHidden');

    const fieldMap = {
        'Siswa'   : { fields: 'fieldsSiswa',   nama: 'namaSiswa',   detail: 'detailSiswa' },
        'Guru'    : { fields: 'fieldsGuru',     nama: 'namaGuru',    detail: 'detailGuru' },
        'Pegawai' : { fields: 'fieldsPegawai',  nama: 'namaPegawai', detail: 'detailPegawai' },
    };

    function switchRole(role) {
        // Update button styles
        roleBtns.forEach(b => b.classList.remove('active'));
        document.querySelector(`.role-btn[data-role="${role}"]`)?.classList.add('active');

        // Show/hide fields
        document.querySelectorAll('.identitas-fields').forEach(el => el.classList.remove('show'));
        const cfg = fieldMap[role];
        if (cfg) document.getElementById(cfg.fields).classList.add('show');

        peranInput.value = role;
        syncHidden(role);
    }

    function syncHidden(role) {
        const cfg = fieldMap[role];
        if (!cfg) { namaHidden.value = ''; detailHidden.value = ''; return; }
        const namaEl   = document.getElementById(cfg.nama);
        const detailEl = document.getElementById(cfg.detail);
        namaHidden.value   = namaEl   ? namaEl.value   : '';
        detailHidden.value = detailEl ? detailEl.value : '';
    }

    // Listen to text input changes in visible fields
    Object.values(fieldMap).forEach(cfg => {
        const namaEl   = document.getElementById(cfg.nama);
        const detailEl = document.getElementById(cfg.detail);
        namaEl?.addEventListener('input',   () => { namaHidden.value   = namaEl.value; });
        detailEl?.addEventListener('input', () => { detailHidden.value = detailEl.value; });
    });

    roleBtns.forEach(btn => {
        btn.addEventListener('click', () => switchRole(btn.dataset.role));
    });

    // Init dengan old value
    const oldRole = peranInput.value;
    if (oldRole) switchRole(oldRole);
});
</script>
@endpush
