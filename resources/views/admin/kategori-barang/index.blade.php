@extends('layouts.admin')

@section('title', 'Kategori Barang Inventaris')

@section('content')

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
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>Daftar Kategori Barang Inventaris</span>
        <a href="{{ route('admin.kategori-barang.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
            <thead>
                <tr>
                    <th style="width:60px;">No</th>
                    <th>Nama Kategori</th>
                    <th width="160" class="text-end text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $k)
                <tr>
                    <td class="text-muted">{{ $loop->iteration + $kategoris->firstItem() - 1 }}</td>
                    <td><div style="font-weight:600;color:#0F172A;">{{ $k->nama_kategori }}</div></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.kategori-barang.edit', $k->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="{{ route('admin.kategori-barang.destroy', $k->id) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-5" style="color:#94A3B8;">
                        <i class="bi bi-tags fs-2 d-block mb-2"></i>
                        Belum ada kategori inventaris barang.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
    @if($kategoris->hasPages())
    <div class="card-footer">
        {{ $kategoris->links() }}
    </div>
    @endif
</div>

@endsection
