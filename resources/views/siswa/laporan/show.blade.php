@extends('layouts.siswa')

@section('title', 'Detail Laporan')

@push('css')
<style>
    :root { --primary: #2563EB; --body-bg: #F8FAFC; --border: #E2E8F0; --text-primary: #0F172A; --text-secondary: #64748B; --radius: 12px; --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04); }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); }
    .card { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); }
    .card-header { background: transparent; border-bottom: 1px solid var(--border); padding: 15px 20px; font-weight: 600; font-size: 14px; color: var(--text-primary); }
    .card-body { padding: 20px; }
    .card-footer { background: transparent; border-top: 1px solid var(--border); padding: 12px 16px; }
    .detail-row { display: flex; padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 13.5px; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { width: 160px; flex-shrink: 0; color: var(--text-secondary); font-weight: 600; font-size: 12.5px; padding-top: 2px; }
    .detail-val { flex: 1; color: var(--text-primary); }
    .badge { font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 6px; }
    .badge.bg-success { background: #ECFDF5 !important; color: #059669 !important; }
    .badge.bg-warning { background: #FFFBEB !important; color: #B45309 !important; }
    .badge.bg-secondary, .badge.bg-light { background: #F1F5F9 !important; color: #475569 !important; }
    .badge.bg-info { background: #ECFEFF !important; color: #0891B2 !important; }
    .btn { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; font-size: 13px; border-radius: 8px; border: none; padding: 8px 16px; transition: all 0.15s; }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: #1D4ED8; color: white; }
    .btn-secondary { background: #F1F5F9; color: var(--text-secondary); }
    .btn-secondary:hover { background: #E2E8F0; color: var(--text-primary); }
    .btn-link { color: #EF4444; text-decoration: none; font-size: 13px; font-weight: 600; }
    .btn-link:hover { color: #DC2626; }
    .form-control { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13.5px; border: 1.5px solid var(--border); border-radius: 8px; padding: 9px 13px; }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); outline: none; }
    .alert { border: none; border-radius: 8px; font-size: 13px; font-weight: 500; padding: 10px 14px; }
    .alert-success { background: #ECFDF5; color: #065F46; }
    .alert-warning { background: #FFFBEB; color: #92400E; }
</style>
@endpush

@section('content')

<div class="row g-3">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-file-earmark-text me-2" style="color:#2563EB;"></i>Detail Laporan</div>
            <div class="card-body">
                <div class="detail-row">
                    <div class="detail-label">Kategori</div>
                    <div class="detail-val">
                        <span style="background:#EFF6FF; color:#2563EB; font-size:12px; font-weight:600; padding:3px 10px; border-radius:5px;">{{ $laporan->kategoriAspirasi->nama_kategori ?? '-' }}</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Lokasi</div>
                    <div class="detail-val"><i class="bi bi-geo-alt me-1" style="color:#94A3B8;"></i>{{ $laporan->lokasi }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Tanggal Kirim</div>
                    <div class="detail-val" style="color:#64748B;">{{ $laporan->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Keterangan</div>
                    <div class="detail-val">{{ $laporan->ket }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Foto Bukti</div>
                    <div class="detail-val">
                        @if($laporan->foto)
                            <img src="{{ asset('storage/'.$laporan->foto) }}" alt="Bukti" class="img-fluid rounded" style="max-height:200px; border:1px solid #E2E8F0;">
                        @else
                            <span style="color:#94A3B8; font-size:13px;"><i class="bi bi-image me-1"></i>Tidak ada lampiran</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Tanggapan / Status --}}
        @include('siswa.laporan.tanggapan')

        {{-- Feedback --}}
        @if ($laporan->aspirasi?->status == 'selesai')
            @include('siswa.laporan.feedback')
        @endif
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-chat-dots-fill me-2" style="color:#2563EB;"></i>Ruang Diskusi</div>
            <div class="card-body p-0">
                <div style="max-height:360px; overflow-y:auto; padding:16px;">
                    @forelse($laporan->komentar as $komen)
                        <div class="mb-3 {{ $komen->sender_type == 'siswa' ? 'd-flex flex-column align-items-end' : '' }}">
                            <div style="display:inline-block; max-width:85%; padding:10px 14px; border-radius:12px; {{ $komen->sender_type == 'siswa' ? 'background:#EFF6FF; color:#1D4ED8;' : 'background:#F8FAFC; border:1px solid #E2E8F0; color:#0F172A;' }}">
                                <small style="font-weight:700; font-size:11px; display:block; margin-bottom:3px;">{{ $komen->sender_type == 'siswa' ? 'Anda' : 'Admin' }}</small>
                                <span style="font-size:13px;">{{ $komen->pesan }}</span>
                                <small style="display:block; font-size:10px; color:#94A3B8; margin-top:4px;">{{ $komen->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4" style="color:#94A3B8; font-size:13px;">
                            <i class="bi bi-chat-square fs-2 d-block mb-2" style="color:#E2E8F0;"></i>Belum ada diskusi.
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer">
                <form action="{{ route('siswa.laporan.komentar', $laporan->id) }}" method="POST">
                    @csrf
                    <div class="d-flex gap-2">
                        <input type="text" name="pesan" class="form-control" placeholder="Tulis komentar..." required>
                        <button class="btn btn-primary" type="submit" style="flex-shrink:0;"><i class="bi bi-send-fill"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
