@extends('layouts.superadmin')

@section('title', 'Detail Laporan')

@section('content')

@if (session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        @include('superadmin.laporan.detil')
    </div>
    <div class="col-md-4">
        @include('superadmin.laporan.form-status')

        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-chat-dots-fill me-2" style="color:#2563EB;"></i>Ruang Diskusi
            </div>
            <div class="card-body p-0">
                <div style="max-height: 320px; overflow-y: auto; padding: 16px;">
                    @forelse($laporan->komentar as $komen)
                        <div class="mb-3 {{ $komen->sender_type == 'admin' ? 'd-flex flex-column align-items-end' : '' }}">
                            <div style="display:inline-block; max-width:85%; padding: 10px 14px; border-radius: 12px; {{ $komen->sender_type == 'admin' ? 'background:#EFF6FF; color:#1D4ED8;' : 'background:#F8FAFC; border: 1px solid #E2E8F0; color:#0F172A;' }}">
                                <small style="font-weight:700; font-size:11px; display:block; margin-bottom:3px;">
                                    {{ $komen->sender_type == 'admin' ? 'Admin' : ($laporan->reporter?->nama ?? $laporan->siswa?->nama ?? 'Pelapor') }}
                                </small>
                                <span style="font-size:13px;">{{ $komen->pesan }}</span>
                                <small style="display:block; font-size:10px; color:#94A3B8; margin-top:4px;">{{ $komen->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3" style="color:#94A3B8; font-size:13px;">Belum ada diskusi.</div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer" style="padding: 12px 16px;">
                <form action="{{ route('superadmin.laporan.komentar', $laporan->id) }}" method="POST">
                    @csrf
                    <div class="d-flex gap-2">
                        <input type="text" name="pesan" class="form-control" placeholder="Balas komentar..." required>
                        <button class="btn btn-primary" type="submit" style="padding: 8px 16px; flex-shrink:0;"><i class="bi bi-send"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
