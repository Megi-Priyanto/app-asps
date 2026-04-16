@extends('layouts.superadmin')

@section('title', 'Lokasi Penugasan Admin')

@section('content')

@if (session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>Daftar Lokasi Penugasan Admin</span>
        <a href="{{ route('superadmin.lokasi.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Lokasi
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Lokasi</th>
                        <th width="160" class="text-end text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lokasi as $item)
                        <tr>
                            <td style="color:#94A3B8;">{{ $loop->iteration }}</td>
                            <td><span style="font-weight:600;">{{ $item->nama_lokasi }}</span></td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('superadmin.lokasi.edit', $item->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                                    <form action="{{ route('superadmin.lokasi.destroy', $item->id) }}" method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus lokasi ini?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center py-4" style="color:#94A3B8;">Belum ada Data Lokasi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">{{ $lokasi->links() }}</div>
</div>

@endsection
