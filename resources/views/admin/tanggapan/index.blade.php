@extends('layouts.admin')

@section('title', 'Tanggapan Pengguna')

@section('content')

@if (session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>Rekapitulasi Tanggapan Pengguna (Aplikasi)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Pengirim</th>
                        <th>Peran</th>
                        <th>Isi Tanggapan/Catatan</th>
                        <th>Status Landing</th>
                        <th style="width: 180px;">Tanggal</th>
                        <th style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tanggapan as $item)
                        <tr class="{{ $item->is_tampil ? 'bg-light' : '' }}">
                            <td>{{ ($tanggapan->currentPage()-1) * $tanggapan->perPage() + $loop->iteration }}</td>
                            <td>
                                <span style="font-weight:700; color:#0F172A;">
                                    {{ $item->user->nama ?? 'Tidak Diketahui' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $roleClass = 'bg-secondary';
                                    $roleName = 'User';
                                    if ($item->user_type === 'App\Models\Siswa') {
                                        $roleClass = 'bg-primary';
                                        $roleName = 'Siswa';
                                    } elseif ($item->user_type === 'App\Models\Guru') {
                                        $roleClass = 'bg-success';
                                        $roleName = 'Guru';
                                    } elseif ($item->user_type === 'App\Models\Pegawai') {
                                        $roleClass = 'bg-info';
                                        $roleName = 'Pegawai';
                                    }
                                @endphp
                                <span class="badge {{ $roleClass }}">{{ $roleName }}</span>
                            </td>
                            <td style="line-height:1.6;">{{ $item->catatan }}</td>
                            <td>
                                @if($item->is_tampil)
                                    <span class="badge bg-success"><i class="bi bi-eye-fill me-1"></i> Tampil</span>
                                @else
                                    <span class="badge bg-light text-muted"><i class="bi bi-eye-slash-fill me-1"></i> Sembunyi</span>
                                @endif
                            </td>
                            <td style="color:#64748B; font-size:12px;">
                                {{ $item->created_at->translatedFormat('d F Y, H:i') }}
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.tanggapan-pengguna.toggle-status', $item->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm {{ $item->is_tampil ? 'btn-warning' : 'btn-outline-success' }}"
                                                title="{{ $item->is_tampil ? 'Sembunyikan' : 'Tampilkan di Landing Page' }}">
                                            <i class="bi {{ $item->is_tampil ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.tanggapan-pengguna.destroy', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus tanggapan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5" style="color:#94A3B8;">
                                <i class="bi bi-chat-dots" style="font-size:32px; display:block; margin-bottom:12px; opacity:.3;"></i>
                                Belum ada tanggapan pengguna yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($tanggapan->hasPages())
        <div class="card-footer">
            {{ $tanggapan->links() }}
        </div>
    @endif
</div>

@endsection
