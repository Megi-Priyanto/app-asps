@extends('layouts.superadmin')

@section('title', 'Kategori Barang Inventaris')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Kategori Barang Inventaris</h5>
        <div style="font-size:12px;color:#94A3B8;">Kelola kategori untuk data inventaris dan sarpras</div>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-3"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:60px;">No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th style="width:180px;text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $k)
                <tr>
                    <td class="text-muted">{{ $loop->iteration + $kategoris->firstItem() - 1 }}</td>
                    <td><div style="font-weight:600;color:#0F172A;">{{ $k->nama_kategori }}</div></td>
                    <td class="text-muted" style="font-size:13px;">{{ $k->deskripsi ?? '-' }}</td>
                    <td style="text-align:right;">
                        <button class="btn btn-sm" style="background:#EFF6FF;color:#2563EB;" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $k->id }}">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        <form action="{{ route('superadmin.kategori-barang.destroy', $k->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>

                {{-- Modal Edit --}}
                <div class="modal fade" id="modalEdit{{ $k->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="border-radius:16px;border:none;">
                            <div class="modal-header" style="border-bottom:1px solid #F1F5F9;padding:18px 24px;">
                                <h5 class="modal-title" style="font-weight:700;font-size:16px;">Edit Kategori Barang</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('superadmin.kategori-barang.update', $k->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body" style="padding:24px;">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_kategori" class="form-control" value="{{ $k->nama_kategori }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="3">{{ $k->deskripsi }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer" style="border-top:1px solid #F1F5F9;padding:16px 24px;">
                                    <button type="button" class="btn btn-sm" style="background:#F1F5F9;color:#64748B;" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5" style="color:#94A3B8;">
                        <i class="bi bi-tags fs-2 d-block mb-2"></i>
                        Belum ada kategori inventaris barang.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($kategoris->hasPages())
    <div class="card-footer">
        {{ $kategoris->links() }}
    </div>
    @endif
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #F1F5F9;padding:18px 24px;">
                <h5 class="modal-title" style="font-weight:700;font-size:16px;">Tambah Kategori Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('superadmin.kategori-barang.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="padding:24px;">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Elektronik" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Opsional..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #F1F5F9;padding:16px 24px;">
                    <button type="button" class="btn btn-sm" style="background:#F1F5F9;color:#64748B;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
