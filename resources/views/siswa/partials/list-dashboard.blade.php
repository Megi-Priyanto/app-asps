<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Keterangan Laporan</th>
            <th>Kategori</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($laporan as $item)
            <tr>
                <td style="color:#94A3B8; font-size:12px;">{{ $loop->iteration + ($laporan->firstItem() - 1) }}</td>
                <td>
                    <div style="font-weight:600; color:#0F172A; margin-bottom:3px;">{{ Str::limit($item->ket, 60) }}</div>
                    <small style="color:#94A3B8;">
                        <i class="bi bi-calendar3 me-1"></i>{{ $item->created_at->format('d M Y') }}
                        &nbsp;Â·&nbsp;
                        <i class="bi bi-geo-alt me-1"></i>{{ $item->lokasi }}
                        @if($item->feedback)
                            &nbsp;Â·&nbsp; Feedback: {{ $item->feedback }}
                        @endif
                    </small>
                </td>
                <td>
                    <span style="background:#EFF6FF; color:#2563EB; font-size:11.5px; font-weight:600; padding:3px 9px; border-radius:5px;">
                        {{ $item->kategoriAspirasi->nama_kategori ?? '-' }}
                    </span>
                </td>
                <td>
                    @if ($item->status == 'proses')
                        <span class="badge bg-warning">Diproses</span>
                    @elseif ($item->status == 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-secondary">Menunggu</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('siswa.laporan.show', $item->id) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye me-1"></i>Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-5" style="color:#94A3B8;">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    Belum ada laporan yang dibuat.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
