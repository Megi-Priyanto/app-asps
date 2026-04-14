@extends('layouts.superadmin')

@section('title', 'Pengguna - Admin')

@section('content')

@if (session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center gap-2">
        <span>Daftar Admin</span>
        <div class="ms-auto d-flex gap-2 flex-wrap">
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
                        <th>Kategori Tugas</th>
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
                                @if($item->kategori)
                                    <span class="badge" style="background:#F0FDF4;color:#16A34A;font-size:12px;font-weight:600;padding:5px 10px;border-radius:7px;">
                                        {{ $item->kategori->nama_kategori }}
                                    </span>
                                @else
                                    <span class="text-muted small"><em>Tidak ada kategori</em></span>
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

@endsection
