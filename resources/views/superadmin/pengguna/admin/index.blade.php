@extends('layouts.superadmin')

@section('title', 'Pengguna - Admin')

@section('content')

@if (session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
@endif

{{-- Tabel kegagalan import --}}
@if (session('import_admin_gagal'))
<div class="card mb-4" style="border-color:#FCA5A5;">
    <div class="card-header" style="background:#FEF2F2;color:#991B1B;font-weight:700;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>Baris Gagal Diimport
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" style="font-size:13px;">
                <thead>
                    <tr>
                        <th>Baris</th><th>Username</th><th>Nama</th><th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('import_admin_gagal') as $item)
                    <tr>
                        <td>{{ $item['baris'] }}</td>
                        <td>{{ $item['username'] }}</td>
                        <td>{{ $item['nama'] }}</td>
                        <td style="color:#DC2626;">{{ $item['pesan'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center gap-2">
        <span>Daftar Admin</span>
        <div class="ms-auto d-flex gap-2 flex-wrap">
            {{-- Download Template --}}
            <a href="{{ route('superadmin.admin.template') }}" class="btn btn-success d-inline-flex align-items-center justify-content-center" style="line-height: 1; height: 36px; font-size: 13px; font-weight: 600; padding: 0 16px; border-radius: 8px;">
                <i class="bi bi-file-earmark-excel me-1" style="font-size: 14px;"></i>Unduh Format Excel
            </a>

            {{-- Import Excel --}}
            <button type="button" class="btn btn-warning text-white d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#modalImportAdmin" style="line-height: 1; height: 36px; font-size: 13px; font-weight: 600; padding: 0 16px; border-radius: 8px;">
                <i class="bi bi-upload me-1" style="font-size: 14px;"></i>Import Excel
            </button>
            
            {{-- Dropdown Export --}}
            <div class="dropdown">
                <button class="btn dropdown-toggle d-inline-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background:#0F172A; border-color:#0F172A; color:white; line-height: 1; height: 36px; font-size: 13px; font-weight: 600; padding: 0 16px; border-radius: 8px;">
                    <i class="bi bi-download me-1" style="font-size: 14px;"></i>Export Data
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size:13px; border-radius:10px; border:1px solid #E2E8F0;">
                    <li><a class="dropdown-item py-2" href="{{ route('superadmin.admin.export.excel') }}"><i class="bi bi-file-earmark-excel text-success me-2"></i>Export Excel (.xlsx)</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('superadmin.admin.export.pdf') }}"><i class="bi bi-file-earmark-pdf text-danger me-2"></i>Export PDF (.pdf)</a></li>
                </ul>
            </div>

            <a href="{{ route('superadmin.admin.create') }}" class="btn btn-primary d-inline-flex align-items-center justify-content-center" style="line-height: 1; height: 36px; font-size: 13px; font-weight: 600; padding: 0 16px; border-radius: 8px;">
                <i class="bi bi-plus-lg me-1" style="font-size: 14px;"></i>Tambah Admin
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Lokasi Tugas</th>
                        <th>Dibuat</th>
                        <th width="160" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $item)
                        <tr>
                            <td style="color:#94A3B8;">{{ $loop->iteration }}</td>
                            <td><span style="font-weight:600;">{{ $item->nama }}</span></td>
                            <td>
                                <span class="badge" style="background:#EFF6FF;color:#2563EB;font-size:12px;font-weight:700;padding:5px 10px;border-radius:7px;">
                                    {{ $item->username }}
                                </span>
                            </td>
                            <td>
                                @if($item->lokasi)
                                    <span class="badge" style="background:#F0FDF4;color:#16A34A;font-size:12px;font-weight:600;padding:5px 10px;border-radius:7px;">
                                        {{ $item->lokasi->nama_lokasi }}
                                    </span>
                                @else
                                    <span class="text-muted small"><em>Tidak ada lokasi</em></span>
                                @endif
                            </td>
                            <td style="color:#94A3B8;font-size:13px;">{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('superadmin.admin.edit', $item->id) }}" class="btn btn-sm btn-secondary">
                                        Edit
                                    </a>
                                    <form action="{{ route('superadmin.admin.destroy', $item->id) }}" method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data admin {{ addslashes($item->nama) }}?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5" style="color:#94A3B8;">
                                <i class="bi bi-people" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                                Belum ada data admin
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Import Excel --}}
<div class="modal fade" id="modalImportAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-upload text-warning me-2"></i>Import Data Admin dari Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 mb-3 rounded-3" style="background:#FFFBEB;border:1px solid #FDE68A;">
                    <p class="mb-1" style="font-size:13px;font-weight:600;color:#92400E;">
                        <i class="bi bi-info-circle me-1"></i>Sebelum import, pastikan file menggunakan format yang benar.
                    </p>
                    <p class="mb-2" style="font-size:12.5px;color:#78350F;">
                        Download template terlebih dahulu, isi data yang diperlukan, lalu upload di sini. Pastikan nama lokasi yang diisikan benar-benar persis.
                    </p>
                    <a href="{{ route('superadmin.admin.template') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-file-earmark-excel me-1"></i>Unduh Format Excel
                    </a>
                </div>

                <form action="{{ route('superadmin.admin.import') }}" method="POST" enctype="multipart/form-data" id="formImportAdmin">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih File Excel</label>
                        <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls" required id="inputFileAdmin">
                        <div class="form-text">Format: .xlsx / .xls | Maksimal: 5MB</div>
                    </div>

                    <div id="previewFileAdmin" class="p-2 rounded-3 mb-2" style="display:none;background:#F0FDF4;border:1px solid #86EFAC;font-size:13px;color:#166534;">
                        <i class="bi bi-file-earmark-check me-1"></i>
                        <span id="namaFileAdmin"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning text-white" onclick="document.getElementById('formImportAdmin').submit()">
                    <i class="bi bi-upload me-1"></i>Import Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('inputFileAdmin').addEventListener('change', function () {
    const preview = document.getElementById('previewFileAdmin');
    const nama    = document.getElementById('namaFileAdmin');
    if (this.files.length > 0) {
        nama.textContent = this.files[0].name;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
});
</script>

@endsection
