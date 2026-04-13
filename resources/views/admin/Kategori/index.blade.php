@extends('layouts.admin')

@section('title', 'Kategori')

@section('content')

@if (session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>Daftar Kategori</span>
        <a href="{{ route('admin.kategori.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Kategori</th>
                        <th width="160" class="text-end text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategori as $item)
                        <tr>
                            <td style="color:#94A3B8;">{{ $loop->iteration }}</td>
                            <td><span style="font-weight:600;">{{ $item->nama_kategori }}</span></td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.kategori.edit', $item->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                                    <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori ini?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center py-4" style="color:#94A3B8;">Belum ada kategori</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">{{ $kategori->links() }}</div>
</div>

@endsection
