@extends('layouts.admin')

@section('title', 'Laporan & Aspirasi')

@section('content')

@if (session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center gap-2">
        <span>Daftar Laporan & Aspirasi</span>
        <div class="ms-auto d-flex gap-2 flex-wrap">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="d-flex gap-2">
                {{-- Filter Peran --}}
                <select name="role" class="form-select form-select-sm" style="width:180px;" onchange="this.form.submit()">
                    <option value="">Semua Peran</option>
                    <option value="guru" {{ request('role') === 'guru' ? 'selected' : '' }}>Laporan Guru</option>
                    <option value="pegawai" {{ request('role') === 'pegawai' ? 'selected' : '' }}>Laporan Pegawai</option>
                    <option value="siswa" {{ request('role') === 'siswa' ? 'selected' : '' }}>Laporan Siswa</option>
                </select>

                {{-- Filter Status --}}
                <select name="status" class="form-select status-select form-select-sm" style="width:180px;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum Diproses</option>
                    <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @if(request('status') || request('role'))
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                @endif
            </form>
            {{-- Tombol buka modal export PDF --}}
            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalExportPdf">
                <i class="bi bi-printer me-1"></i>Cetak PDF
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            @include('admin.laporan.list')
        </div>
    </div>
    <div class="card-footer">{{ $laporan->links() }}</div>
</div>

{{-- ======= MODAL EXPORT PDF ======= --}}
<div class="modal fade" id="modalExportPdf" tabindex="-1" aria-labelledby="modalExportLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalExportLabel">
                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i>Export Laporan PDF
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Pilih Jenis Laporan --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Laporan</label>
                    <div class="d-flex gap-2 flex-wrap">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_laporan" id="r_harian" value="harian" checked onchange="toggleForm()">
                            <label class="form-check-label" for="r_harian">📅 Harian</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_laporan" id="r_bulanan" value="bulanan" onchange="toggleForm()">
                            <label class="form-check-label" for="r_bulanan">📆 Bulanan</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_laporan" id="r_tahunan" value="tahunan" onchange="toggleForm()">
                            <label class="form-check-label" for="r_tahunan">🗓️ Tahunan</label>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Form Harian --}}
                <div id="form_harian">
                    <label class="form-label fw-semibold">Pilih Tanggal</label>
                    <input type="date" id="input_tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                    <small class="text-muted">Laporan akan dicetak untuk tanggal yang dipilih.</small>
                </div>

                {{-- Form Bulanan --}}
                <div id="form_bulanan" style="display:none;">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Bulan</label>
                            <select id="input_bulan" class="form-select">
                                @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $num => $nama)
                                    <option value="{{ $num }}" {{ date('n') == $num ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Tahun</label>
                            <select id="input_bulan_tahun" class="form-select">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <small class="text-muted mt-1 d-block">Laporan seluruh data dalam bulan yang dipilih.</small>
                </div>

                {{-- Form Tahunan --}}
                <div id="form_tahunan" style="display:none;">
                    <label class="form-label fw-semibold">Pilih Tahun</label>
                    <select id="input_tahun" class="form-select">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <small class="text-muted mt-1 d-block">Laporan seluruh data dalam tahun yang dipilih.</small>
                </div>

            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="doCetak()">
                    <i class="bi bi-printer me-1"></i>Cetak PDF
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleForm() {
        const jenis = document.querySelector('input[name="jenis_laporan"]:checked').value;
        document.getElementById('form_harian').style.display  = (jenis === 'harian')  ? '' : 'none';
        document.getElementById('form_bulanan').style.display = (jenis === 'bulanan') ? '' : 'none';
        document.getElementById('form_tahunan').style.display = (jenis === 'tahunan') ? '' : 'none';
    }

    function doCetak() {
        const jenis = document.querySelector('input[name="jenis_laporan"]:checked').value;
        const base  = '{{ route('admin.laporan.cetak') }}';
        let url = '';

        if (jenis === 'harian') {
            const tanggal = document.getElementById('input_tanggal').value;
            if (!tanggal) { alert('Pilih tanggal terlebih dahulu!'); return; }
            url = base + '?jenis=harian&tanggal=' + tanggal;

        } else if (jenis === 'bulanan') {
            const bulan = document.getElementById('input_bulan').value;
            const tahun = document.getElementById('input_bulan_tahun').value;
            url = base + '?jenis=bulanan&bulan=' + bulan + '&tahun=' + tahun;

        } else if (jenis === 'tahunan') {
            const tahun = document.getElementById('input_tahun').value;
            url = base + '?jenis=tahunan&tahun=' + tahun;
        }

        // Buka PDF di tab baru
        window.open(url, '_blank');

        // Tutup modal secara paksa agar tidak stuck setelah kembali ke halaman ini
        const modalEl = document.getElementById('modalExportPdf');
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) {
            modalInstance.hide();
        }

        // Bersihkan backdrop & scroll body jika masih tertinggal
        setTimeout(function () {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }, 300);
    }
</script>

@endsection
