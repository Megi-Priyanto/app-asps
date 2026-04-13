@extends('layouts.siswa')

@section('title', 'Tanggapan Aplikasi')

@push('css')
<style>
    :root {
        --primary: #2563EB; --primary-light: #EFF6FF;
        --body-bg: #F8FAFC; --card-bg: #fff;
        --border: #E2E8F0; --text-primary: #0F172A;
        --text-secondary: #64748B; --text-muted: #94A3B8;
        --radius: 12px; --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    }

    .form-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 24px;
        margin-bottom: 24px;
    }

    .form-title { font-size: 18px; font-weight: 800; color: var(--text-primary); margin-bottom: 8px; }
    .form-desc { font-size: 13px; color: var(--text-secondary); margin-bottom: 20px; }

    .history-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .history-header {
        padding: 16px 20px;
        background: #F8FAFC;
        border-bottom: 1px solid var(--border);
        font-weight: 700;
        font-size: 14px;
        color: var(--text-primary);
    }

    .table th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--text-muted);
        font-weight: 700;
        border-top: none;
        padding: 12px 20px;
    }

    .table td {
        padding: 14px 20px;
        font-size: 13.5px;
        color: var(--text-secondary);
        vertical-align: middle;
    }

    .empty-state {
        padding: 40px;
        text-align: center;
        color: var(--text-muted);
    }
</style>
@endpush

@section('content')

@if (session('success'))
    <div class="alert alert-success mb-4 d-flex align-items-center gap-2" style="border:none; border-radius:12px; background:#ECFDF5; color:#065F46; padding:12px 16px; font-size:13.5px;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:10px;"></button>
    </div>
@endif

<div class="form-card">
    <h2 class="form-title">Tanggapan Anda Terhadap Aplikasi</h2>
    <p class="form-desc">Sampaikan saran, kritik, atau pengalaman Anda dalam menggunakan aplikasi ini untuk membantu kami menjadi lebih baik.</p>
    
    <form action="{{ route('siswa.tanggapan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label" style="font-weight:600; font-size:13px; color:var(--text-primary);">Isi Tanggapan/Catatan</label>
            <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                      rows="4" placeholder="Tuliskan saran atau deskripsi di sini..." required>{{ old('catatan') }}</textarea>
            @error('catatan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary" style="padding:10px 24px; border-radius:10px; font-weight:700; font-size:14px;">
            <i class="bi bi-send-fill me-2"></i> Kirim Tanggapan
        </button>
    </form>
</div>

<div class="history-card">
    <div class="history-header">
        <i class="bi bi-clock-history me-2" style="color:var(--primary);"></i> Riwayat Tanggapan Saya
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Isi Tanggapan/Catatan</th>
                    <th style="width: 180px;">Tanggal Dikirim</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tanggapan as $item)
                    <tr>
                        <td>{{ ($tanggapan->currentPage()-1) * $tanggapan->perPage() + $loop->iteration }}</td>
                        <td style="line-height:1.6; color:var(--text-primary);">{{ $item->catatan }}</td>
                        <td style="color:var(--text-muted); font-size:12px;">
                            {{ $item->created_at->translatedFormat('d F Y, H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="empty-state">
                                <i class="bi bi-chat-left-dots" style="font-size:32px; display:block; margin-bottom:12px; opacity:.3;"></i>
                                Belum ada tanggapan yang Anda kirimkan.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tanggapan->hasPages())
        <div class="p-3 border-top">
            {{ $tanggapan->links() }}
        </div>
    @endif
</div>

@endsection
