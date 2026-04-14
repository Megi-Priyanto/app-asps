<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>Laporan Terbaru</span>
        <a href="{{ route('superadmin.laporan.index') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pelapor</th>
                        <th>Nama Pelapor</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporanTerbaru ?? [] as $item)
                    <tr>
                        <td style="color:#94A3B8; font-size:12px;">{{ $loop->iteration }}</td>
                        <td>
                            <span class="badge border text-dark" style="background:#F8FAFC; border-color:#E2E8F0 !important; font-size:11px;">
                                {{ ucfirst($item->reporter_type ?? 'siswa') }}
                            </span>
                        </td>
                        <td><span style="font-weight:600;">{{ $item->reporter?->nama ?? $item->siswa?->nama ?? 'Tidak Diketahui' }}</span></td>
                        <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                        <td>
                            @if($item->aspirasi?->status === 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($item->aspirasi?->status === 'proses')
                                <span class="badge bg-warning">Proses</span>
                            @else
                                <span class="badge bg-secondary">Baru</span>
                            @endif
                        </td>
                        <td style="color:#64748B;">{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('superadmin.laporan.show', $item->id) }}" class="btn btn-sm btn-primary">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4" style="color:#94A3B8;">Belum ada laporan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
