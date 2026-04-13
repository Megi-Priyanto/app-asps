<div class="card mb-3">
    <div class="card-header"><i class="bi bi-flag me-2" style="color:#2563EB;"></i>Status Laporan</div>
    <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            @if ($laporan->aspirasi?->status === 'selesai')
                <span style="background:#ECFDF5; color:#059669; font-size:13px; font-weight:600; padding:6px 14px; border-radius:8px;"><i class="bi bi-check-circle-fill me-2"></i>Selesai</span>
            @elseif ($laporan->aspirasi?->status === 'proses')
                <span style="background:#FFFBEB; color:#B45309; font-size:13px; font-weight:600; padding:6px 14px; border-radius:8px;"><i class="bi bi-hourglass-split me-2"></i>Sedang Diproses</span>
            @else
                <span style="background:#F1F5F9; color:#475569; font-size:13px; font-weight:600; padding:6px 14px; border-radius:8px;"><i class="bi bi-clock me-2"></i>Menunggu Tindakan</span>
            @endif
        </div>

        @if (!$laporan->aspirasi || $laporan->aspirasi?->status === null || ($laporan->aspirasi?->status !== 'proses' && $laporan->aspirasi?->status !== 'selesai'))
            <form action="{{ route('siswa.laporan.destroy', $laporan->id) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-link text-danger p-0" style="font-size:13px; font-weight:600;">
                    <i class="bi bi-trash me-1"></i>Hapus Laporan
                </button>
            </form>
        @endif
    </div>
</div>
