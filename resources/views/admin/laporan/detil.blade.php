<div class="card">
    <div class="card-header"><i class="bi bi-file-earmark-text-fill me-2" style="color:#2563EB;"></i>Detail Laporan</div>
    <div class="card-body p-0">
        <table class="table table-borderless mb-0" style="font-size:13.5px;">
            <tr>
                <td style="padding:14px 20px; width:180px; color:#64748B; font-weight:600; vertical-align:top;">Nama Pelapor</td>
                <td style="padding:14px 20px; vertical-align:top; border-left:1px solid #F1F5F9;">
                    @php
                        $pelaporModel = $laporan->reporter ?? $laporan->siswa;
                        $namaPelapor = $pelaporModel?->nama ?? 'Tidak Diketahui';
                        
                        $identitas = 'Tidak Diketahui';
                        if ($pelaporModel) {
                            if ($pelaporModel instanceof \App\Models\Siswa) {
                                $identitas = 'NIS: ' . ($pelaporModel->nis ?? '-') . ' &nbsp;|&nbsp; Kelas: ' . ($pelaporModel->kelas ?? '-');
                            } elseif ($pelaporModel instanceof \App\Models\Guru) {
                                $identitas = 'NIP: ' . ($pelaporModel->nip ?? '-') . ' &nbsp;|&nbsp; Jabatan: ' . ($pelaporModel->jabatan ?? '-');
                            } elseif ($pelaporModel instanceof \App\Models\Pegawai) {
                                $identitas = 'Jabatan: ' . ($pelaporModel->jabatan ?? '-');
                            } else {
                                $identitas = 'Tipe Pengguna: ' . class_basename($pelaporModel);
                            }
                        }
                    @endphp
                    <span style="font-weight:700;">{!! $namaPelapor !!}</span>
                    <br><small style="color:#94A3B8;">{!! $identitas !!}</small>
                </td>
            </tr>
            <tr style="background:#FAFBFF;">
                <td style="padding:14px 20px; color:#64748B; font-weight:600;">Kategori</td>
                <td style="padding:14px 20px; border-left:1px solid #F1F5F9;">
                    <span style="background:#EFF6FF; color:#2563EB; font-size:12px; font-weight:600; padding:4px 10px; border-radius:6px;">{{ $laporan->kategori->nama_kategori }}</span>
                </td>
            </tr>
            <tr>
                <td style="padding:14px 20px; color:#64748B; font-weight:600; vertical-align:top;">Keterangan</td>
                <td style="padding:14px 20px; border-left:1px solid #F1F5F9;">{{ $laporan->ket }}</td>
            </tr>
            <tr style="background:#FAFBFF;">
                <td style="padding:14px 20px; color:#64748B; font-weight:600;">Lokasi</td>
                <td style="padding:14px 20px; border-left:1px solid #F1F5F9;"><i class="bi bi-geo-alt me-1" style="color:#94A3B8;"></i>{{ $laporan->lokasi }}</td>
            </tr>
            <tr>
                <td style="padding:14px 20px; color:#64748B; font-weight:600; vertical-align:top;">Bukti Foto</td>
                <td style="padding:14px 20px; border-left:1px solid #F1F5F9;">
                    @if($laporan->foto)
                        <img src="{{ asset('storage/'.$laporan->foto) }}" alt="Bukti" class="img-fluid rounded" style="max-height:220px; border:1px solid #E2E8F0;">
                    @else
                        <span style="color:#94A3B8; font-size:13px;"><i class="bi bi-image me-1"></i>Tidak ada lampiran</span>
                    @endif
                </td>
            </tr>
            <tr style="background:#FAFBFF;">
                <td style="padding:14px 20px; color:#64748B; font-weight:600;">Status</td>
                <td style="padding:14px 20px; border-left:1px solid #F1F5F9;">
                    @if ($laporan->aspirasi?->status === 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @elseif ($laporan->aspirasi?->status === 'proses')
                        <span class="badge bg-warning">Proses</span>
                    @else
                        <span class="badge bg-secondary">Belum Diproses</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding:14px 20px; color:#64748B; font-weight:600;">Feedback</td>
                <td style="padding:14px 20px; border-left:1px solid #F1F5F9;">
                    <span class="badge bg-info">{{ $laporan->feedback ?: '-' }}</span>
                </td>
            </tr>
            @if ($laporan->aspirasi?->alasan)
            <tr style="background:#FAFBFF;">
                <td style="padding:14px 20px; color:#64748B; font-weight:600; vertical-align:top;">Alasan</td>
                <td style="padding:14px 20px; border-left:1px solid #F1F5F9;">
                    <div style="background:#FFFBEB; color:#92400E; padding:10px 14px; border-radius:8px; font-size:13px;">{{ $laporan->aspirasi->alasan }}</div>
                </td>
            </tr>
            @endif
        </table>
    </div>
</div>
