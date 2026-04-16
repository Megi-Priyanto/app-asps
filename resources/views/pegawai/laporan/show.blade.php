@extends('layouts.pegawai')

@section('title', 'Detail Laporan')

@push('css')
<style>
    :root {
        --primary: #2563EB; --primary-light: #EFF6FF;
        --body-bg: #F8FAFC; --border: #E2E8F0;
        --text-primary: #0F172A; --text-secondary: #64748B;
        --radius: 12px; --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); }
    .card { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); }
    .card-header {
        background: transparent; border-bottom: 1px solid var(--border);
        padding: 15px 20px; font-weight: 600; font-size: 14px; color: var(--text-primary);
    }
    .card-body  { padding: 20px; }
    .card-footer { background: transparent; border-top: 1px solid var(--border); padding: 12px 16px; }
    .detail-row { display: flex; padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 13.5px; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { width: 160px; flex-shrink: 0; color: var(--text-secondary); font-weight: 600; font-size: 12.5px; padding-top: 2px; }
    .detail-val   { flex: 1; color: var(--text-primary); }
    .btn { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; font-size: 13px; border-radius: 8px; border: none; padding: 8px 16px; transition: all 0.15s; }
    .btn-primary  { background: var(--primary); color: white; }
    .btn-primary:hover  { background: #1D4ED8; color: white; }
    .form-control { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13.5px; border: 1.5px solid var(--border); border-radius: 8px; padding: 9px 13px; }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(124,58,237,0.1); outline: none; }
    .alert { border: none; border-radius: 8px; font-size: 13px; font-weight: 500; padding: 10px 14px; }
    .alert-success { background: #ECFDF5; color: #059669; }
    .page-back { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: var(--text-secondary); text-decoration: none; margin-bottom: 18px; transition: color .15s; }
    .page-back:hover { color: var(--primary); }
</style>
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:12px;"></button>
    </div>
@endif

<a href="{{ route('pegawai.laporan.index') }}" class="page-back">
    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Laporan
</a>

<div class="row g-3">
    {{-- ===== KOLOM KIRI: Detail + Status + Feedback ===== --}}
    <div class="col-md-7">

        {{-- Detail Laporan --}}
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-file-earmark-text me-2" style="color:#2563EB;"></i>Detail Laporan
            </div>
            <div class="card-body">
                <div class="detail-row">
                    <div class="detail-label">Kategori</div>
                    <div class="detail-val">
                        <span style="background:#EFF6FF; color:#2563EB; font-size:12px; font-weight:600; padding:3px 10px; border-radius:5px;">
                            {{ $laporan->kategoriAspirasi->nama_kategori ?? '-' }}
                        </span>
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
                    <div class="detail-label">Pelapor</div>
                    <div class="detail-val">
                        @if($laporan->is_anonim)
                            <span style="background:#F1F5F9; color:#475569; font-size:12px; font-weight:600; padding:3px 10px; border-radius:5px;">
                                <i class="bi bi-incognito me-1"></i>Anonim
                            </span>
                        @else
                            {{ $laporan->reporter->nama ?? '-' }}
                            <span style="font-size:11px; color:#94A3B8; margin-left:4px;">
                                ({{ ucfirst(class_basename($laporan->reporter_type)) }})
                            </span>
                        @endif
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Keterangan</div>
                    <div class="detail-val">{{ $laporan->ket }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Foto Bukti</div>
                    <div class="detail-val">
                        @if($laporan->foto)
                            <img src="{{ asset('storage/'.$laporan->foto) }}"
                                 alt="Bukti" class="img-fluid rounded"
                                 style="max-height:200px; border:1px solid #E2E8F0;">
                        @else
                            <span style="color:#94A3B8; font-size:13px;"><i class="bi bi-image me-1"></i>Tidak ada lampiran</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Laporan --}}
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-flag me-2" style="color:#2563EB;"></i>Status Laporan</div>
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    @if ($laporan->aspirasi?->status === 'selesai')
                        <span style="background:#ECFDF5; color:#059669; font-size:13px; font-weight:600; padding:6px 14px; border-radius:8px;">
                            <i class="bi bi-check-circle-fill me-2"></i>Selesai
                        </span>
                    @elseif ($laporan->aspirasi?->status === 'proses')
                        <span style="background:#FFFBEB; color:#B45309; font-size:13px; font-weight:600; padding:6px 14px; border-radius:8px;">
                            <i class="bi bi-hourglass-split me-2"></i>Sedang Diproses
                        </span>
                    @else
                        <span style="background:#F1F5F9; color:#475569; font-size:13px; font-weight:600; padding:6px 14px; border-radius:8px;">
                            <i class="bi bi-clock me-2"></i>Menunggu Tindakan
                        </span>
                    @endif
                </div>

                @if (!$laporan->aspirasi || $laporan->aspirasi?->status === null ||
                    ($laporan->aspirasi?->status !== 'proses' && $laporan->aspirasi?->status !== 'selesai'))
                    <form action="{{ route('pegawai.laporan.destroy', $laporan->id) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                        @csrf @method('DELETE')
                        <button class="btn" style="background:#FEF2F2; color:#EF4444; font-size:13px; font-weight:600; padding:6px 14px; border-radius:8px;">
                            <i class="bi bi-trash me-1"></i>Hapus Laporan
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Tanggapan Admin --}}
        @if($laporan->aspirasi)
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-megaphone me-2" style="color:#2563EB;"></i>Tanggapan Admin</div>
            <div class="card-body">
                @if($laporan->aspirasi->tanggapan)
                    <p style="font-size:13.5px; color:var(--text-primary); margin:0;">
                        {{ $laporan->aspirasi->tanggapan }}
                    </p>
                    <div style="font-size:12px; color:#94A3B8; margin-top:8px;">
                        Diperbarui: {{ $laporan->aspirasi->updated_at->format('d M Y, H:i') }}
                    </div>
                @else
                    <p style="color:#94A3B8; font-size:13px; margin:0;">
                        <i class="bi bi-clock me-1"></i>Belum ada tanggapan dari admin.
                    </p>
                @endif
            </div>
        </div>
        @endif

        {{-- Feedback Kepuasan (hanya jika selesai) --}}
        @if($laporan->aspirasi?->status === 'selesai')
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-star-fill me-2" style="color:#F59E0B;"></i>Feedback Kepuasan</div>
            <div class="card-body">
                @if($laporan->aspirasi->feedback)
                    <div style="background:#ECFDF5; color:#059669; padding:12px 16px; border-radius:8px; font-size:13.5px; font-weight:600; margin-bottom:12px;">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Feedback telah diberikan:
                        {{ [1=>'Tidak Puas',2=>'Kurang Puas',3=>'Cukup Puas',4=>'Puas',5=>'Sangat Puas'][$laporan->aspirasi->feedback] ?? '-' }}
                    </div>
                    @if($laporan->aspirasi->alasan)
                        <div style="background:#FFFBEB; color:#92400E; padding:12px 16px; border-radius:8px; font-size:13px;">
                            <strong>Alasan:</strong> {{ $laporan->aspirasi->alasan }}
                        </div>
                    @endif
                @else
                    <form action="{{ route('pegawai.laporan.feedback', $laporan->aspirasi->id) }}" method="POST">
                        @csrf
                        <p style="font-size:13.5px; color:#64748B; margin-bottom:14px;">Bagaimana kepuasan Anda terhadap penanganan laporan ini?</p>

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach([1=>'ðŸ˜ž Tidak Puas', 2=>'ðŸ˜ Kurang Puas', 3=>'ðŸ™‚ Cukup Puas', 4=>'ðŸ˜Š Puas', 5=>'ðŸ¤© Sangat Puas'] as $val => $label)
                                <label style="cursor:pointer;">
                                    <input type="radio" name="feedback" value="{{ $val }}" class="feedback-radio d-none" {{ old('feedback') == $val ? 'checked' : '' }}>
                                    <span class="feedback-opt" style="display:inline-block; padding:7px 14px; border-radius:20px; font-size:13px; font-weight:600; border:1.5px solid #E2E8F0; color:#64748B; transition:all 0.15s; user-select:none;">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('feedback')<div style="color:#EF4444; font-size:12px; margin-bottom:10px;">{{ $message }}</div>@enderror

                        <div id="alasan-container" style="display:{{ in_array(old('feedback'), ['1','2']) ? 'block' : 'none' }}; margin-bottom:14px;">
                            <textarea name="alasan" rows="3" class="form-control" placeholder="Jelaskan alasan ketidakpuasan Anda...">{{ old('alasan') }}</textarea>
                            @error('alasan')<div style="color:#EF4444; font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
                        </div>

                        <button class="btn btn-primary btn-sm" style="padding:8px 20px;">
                            <i class="bi bi-send me-1"></i>Kirim Feedback
                        </button>
                    </form>

                    <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const radios = document.querySelectorAll('.feedback-radio');
                        const alasanBox = document.getElementById('alasan-container');
                        const opts = document.querySelectorAll('.feedback-opt');
                        function updateStyles() {
                            radios.forEach((r, i) => {
                                opts[i].style.background  = r.checked ? '#EFF6FF' : '';
                                opts[i].style.borderColor = r.checked ? '#2563EB' : '#E2E8F0';
                                opts[i].style.color       = r.checked ? '#2563EB' : '#64748B';
                            });
                        }
                        radios.forEach(r => {
                            r.addEventListener('change', function () {
                                alasanBox.style.display = (this.value === '1' || this.value === '2') ? 'block' : 'none';
                                updateStyles();
                            });
                        });
                        updateStyles();
                    });
                    </script>
                @endif
            </div>
        </div>
        @endif

    </div>

    {{-- ===== KOLOM KANAN: Ruang Diskusi ===== --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-chat-dots-fill me-2" style="color:#2563EB;"></i>Ruang Diskusi
            </div>
            <div class="card-body p-0">
                <div style="max-height:400px; overflow-y:auto; padding:16px;" id="chat-box">
                    @forelse($laporan->komentar as $komen)
                        <div class="mb-3 {{ $komen->sender_type === 'pegawai' ? 'd-flex flex-column align-items-end' : '' }}">
                            <div style="display:inline-block; max-width:85%; padding:10px 14px; border-radius:12px;
                                {{ $komen->sender_type === 'pegawai'
                                    ? 'background:#EFF6FF; color:#1D4ED8;'
                                    : 'background:#F8FAFC; border:1px solid #E2E8F0; color:#0F172A;' }}">
                                <small style="font-weight:700; font-size:11px; display:block; margin-bottom:3px;">
                                    {{ $komen->sender_type === 'pegawai' ? 'Anda' : 'Admin' }}
                                </small>
                                <span style="font-size:13px;">{{ $komen->pesan }}</span>
                                <small style="display:block; font-size:10px; color:#94A3B8; margin-top:4px;">
                                    {{ $komen->created_at->format('d M Y, H:i') }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5" style="color:#94A3B8; font-size:13px;">
                            <i class="bi bi-chat-square fs-2 d-block mb-2" style="color:#E2E8F0;"></i>
                            Belum ada diskusi.
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer">
                <form action="{{ route('pegawai.laporan.komentar', $laporan->id) }}" method="POST">
                    @csrf
                    <div class="d-flex gap-2">
                        <input type="text" name="pesan" class="form-control"
                               placeholder="Tulis komentar..." required>
                        <button class="btn btn-primary" type="submit" style="flex-shrink:0;">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const chatBox = document.getElementById('chat-box');
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
</script>

@endsection
