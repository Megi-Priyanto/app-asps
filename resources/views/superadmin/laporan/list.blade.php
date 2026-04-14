<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Pelapor</th>
            <th>Nama Pelapor</th>
            <th>Kategori</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Feedback</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($laporan as $item)
            <tr>
                <td style="color:#94A3B8; font-size:12px;">{{ $loop->iteration + $laporan->firstItem() - 1 }}</td>
                <td>
                    <span class="badge border text-dark" style="background:#F8FAFC; border-color:#E2E8F0 !important; font-size:11px;">
                        {{ ucfirst($item->reporter_type ?? 'siswa') }}
                    </span>
                </td>
                <td><span style="font-weight:600;">{{ $item->reporter?->nama ?? $item->siswa?->nama ?? 'Tidak Diketahui' }}</span></td>
                <td>
                    <span style="background:#EFF6FF; color:#2563EB; font-size:11.5px; font-weight:600; padding:3px 9px; border-radius:5px;">
                        {{ $item->kategori->nama_kategori ?? '-' }}
                    </span>
                </td>
                <td style="color:#64748B; max-width:220px;">{{ Str::limit($item->ket, 55) }}</td>
                <td>
                    @if ($item->status === 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @elseif ($item->status === 'proses')
                        <span class="badge bg-warning">Proses</span>
                    @else
                        <span class="badge bg-secondary">Belum Diproses</span>
                    @endif
                </td>
                <td style="color:#94A3B8; font-size:12.5px;">{{ $item->feedback ?? '-' }}</td>
                <td>
                    <a href="{{ route('superadmin.laporan.show', $item->id) }}" class="btn btn-sm btn-primary">Detail</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-4" style="color:#94A3B8;">Tidak ada laporan</td>
            </tr>
        @endforelse
    </tbody>
</table>
