@extends('layouts.siswa')

@section('title', 'Detail Peminjaman - ' . $peminjamanBarang->nomor_transaksi)

@push('css')
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8FAFC; }
    .card { background: #fff; border: 1px solid #E2E8F0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04); }
    .card-header { background: transparent; border-bottom: 1px solid #E2E8F0; padding: 16px 20px; font-weight: 700; font-size: 14.5px; }
    .card-body { padding: 24px; }

    .info-label { font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .info-val   { font-size: 14px; font-weight: 600; color: #0F172A; }

    .pmj-badge{display:inline-block;padding:5px 14px;border-radius:6px;font-size:12px;font-weight:700;}
    .pmj-badge-warning{background:#FFFBEB;color:#B45309;}
    .pmj-badge-info{background:#F0F9FF;color:#0369A1;}
    .pmj-badge-blue{background:#EFF6FF;color:#1D4ED8;}
    .pmj-badge-success{background:#ECFDF5;color:#059669;}
    .pmj-badge-danger{background:#FEF2F2;color:#E11D48;}
    .pmj-badge-muted{background:#F1F5F9;color:#475569;}

    .divider { border: none; border-top: 1px solid #F1F5F9; margin: 16px 0; }

    /* ===== BUKTI SECTION ===== */
    #buktiPeminjaman {
        background: linear-gradient(135deg, #EFF6FF 0%, #fff 60%);
        border: 1.5px solid #BFDBFE;
        border-radius: 12px;
        padding: 28px;
        position: relative;
        overflow: hidden;
    }
    #buktiPeminjaman::before {
        content: '';
        position: absolute; top: 0; left: 0;
        width: 6px; height: 100%;
        background: linear-gradient(180deg, #2563EB, #60A5FA);
        border-radius: 12px 0 0 12px;
    }
    .bukti-school { font-size: 11px; color: #64748B; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
    .bukti-title  { font-size: 18px; font-weight: 800; color: #1E3A8A; margin-bottom: 2px; }
    .bukti-nomor  { font-size: 12px; color: #2563EB; font-weight: 700; margin-bottom: 20px; }
    .bukti-row    { display: flex; gap: 0; margin-bottom: 0; }
    .bukti-col    { flex: 1; padding: 8px 0; border-bottom: 1px solid #DBEAFE; }
    .bukti-col:last-child { padding-left: 20px; border-left: 1px solid #DBEAFE; }
    .bukti-lbl    { font-size: 10px; color: #94A3B8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .bukti-txt    { font-size: 13px; font-weight: 700; color: #0F172A; margin-top: 2px; }
    .bukti-footer { font-size: 11px; color: #64748B; margin-top: 16px; padding-top: 12px; border-top: 1px dashed #BFDBFE; }

    .btn-unduh {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        color: white; font-weight: 700; font-size: 14px;
        border: none; border-radius: 10px; padding: 12px 24px;
        cursor: pointer; transition: all 0.2s; text-decoration: none;
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    }
    .btn-unduh:hover { background: linear-gradient(135deg, #1D4ED8, #1e40af); color: white; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(37,99,235,0.4); }
    .btn-back { background: #F1F5F9; color: #475569; border: none; border-radius: 8px; padding: 9px 18px; font-weight: 600; font-size: 13.5px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.15s; }
    .btn-back:hover { background: #E2E8F0; color: #334155; }
</style>
@endpush

@section('content')

<div class="row g-4">

    {{-- ===== Detail Info ===== --}}
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-info-circle me-2" style="color:#2563EB;"></i>Detail Peminjaman</div>
            <div class="card-body">
                @php
                    $sc = match($peminjamanBarang->status) {
                        'Menunggu'           => 'pmj-badge-warning',
                        'Disetujui'          => 'pmj-badge-info',
                        'Sedang Dipinjam'    => 'pmj-badge-blue',
                        'Sudah Dikembalikan' => 'pmj-badge-success',
                        'Terlambat'          => 'pmj-badge-danger',
                        default              => 'pmj-badge-muted',
                    };
                @endphp

                <div class="info-label">Status</div>
                <div class="mb-3"><span class="pmj-badge {{ $sc }}">{{ $peminjamanBarang->status }}</span></div>

                <hr class="divider">

                <div class="row g-3">
                    <div class="col-12">
                        <div class="info-label">Barang</div>
                        <div class="info-val">{{ $peminjamanBarang->barang->nama_barang ?? '-' }}</div>
                        <div style="font-size:12px;color:#2563EB;">{{ $peminjamanBarang->barang->kode_barang ?? '' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Jumlah</div>
                        <div class="info-val">{{ $peminjamanBarang->jumlah_pinjam }} {{ $peminjamanBarang->barang->satuan ?? '' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">No. Transaksi</div>
                        <div style="font-size:12px;font-weight:700;color:#2563EB;">{{ $peminjamanBarang->nomor_transaksi }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Waktu Pinjam</div>
                        <div class="info-val" style="font-size:13px;">{{ $peminjamanBarang->tanggal_pinjam?->format('d M Y, H:i') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Batas Kembali</div>
                        <div class="info-val" style="font-size:13px;">{{ $peminjamanBarang->tanggal_kembali_rencana?->format('d M Y, H:i') }}</div>
                        @if($peminjamanBarang->terlambat)
                            <small class="text-danger" style="font-weight:600;"><i class="bi bi-exclamation-triangle me-1"></i>Terlambat {{ $peminjamanBarang->hari_terlambat }} hari</small>
                        @endif
                    </div>
                    <div class="col-12">
                        <div class="info-label">Keperluan</div>
                        <div class="info-val" style="font-size:13px;font-weight:400;">{{ $peminjamanBarang->keperluan ?? '-' }}</div>
                    </div>
                    @if($peminjamanBarang->catatan_admin)
                    <div class="col-12">
                        <div class="info-label">Catatan Petugas</div>
                        <div style="font-size:13px;background:#FFFBEB;border:1px solid #FDE68A;border-radius:8px;padding:10px 12px;color:#78350F;">{{ $peminjamanBarang->catatan_admin }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Bukti Peminjaman ===== --}}
    <div class="col-md-7">
        @if(in_array($peminjamanBarang->status, ['Disetujui', 'Sedang Dipinjam', 'Sudah Dikembalikan', 'Terlambat']))
        <div class="card">
            <div class="card-header"><i class="bi bi-file-earmark-check me-2" style="color:#059669;"></i>Bukti Peminjaman</div>
            <div class="card-body">
                {{-- Kartu bukti yang akan di-screenshot --}}
                <div id="buktiPeminjaman">
                    <div class="bukti-school">Bukti Peminjaman Barang — SARPRAS</div>
                    <div class="bukti-title">Surat Bukti Peminjaman</div>
                    <div class="bukti-nomor">{{ $peminjamanBarang->nomor_transaksi }}</div>

                    <div class="bukti-row">
                        <div class="bukti-col">
                            <div class="bukti-lbl">Nama Peminjam</div>
                            <div class="bukti-txt">{{ $peminjamanBarang->borrower_name }}</div>
                        </div>
                        <div class="bukti-col">
                            <div class="bukti-lbl">Status</div>
                            <div class="bukti-txt">{{ $peminjamanBarang->status }}</div>
                        </div>
                    </div>
                    <div class="bukti-row">
                        <div class="bukti-col">
                            <div class="bukti-lbl">Barang Dipinjam</div>
                            <div class="bukti-txt">{{ $peminjamanBarang->barang->nama_barang ?? '-' }}</div>
                        </div>
                        <div class="bukti-col">
                            <div class="bukti-lbl">Jumlah</div>
                            <div class="bukti-txt">{{ $peminjamanBarang->jumlah_pinjam }} {{ $peminjamanBarang->barang->satuan ?? '' }}</div>
                        </div>
                    </div>
                    <div class="bukti-row">
                        <div class="bukti-col">
                            <div class="bukti-lbl">Waktu Pinjam</div>
                            <div class="bukti-txt">{{ $peminjamanBarang->tanggal_pinjam?->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="bukti-col">
                            <div class="bukti-lbl">Batas Kembali</div>
                            <div class="bukti-txt">{{ $peminjamanBarang->tanggal_kembali_rencana?->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                    <div class="bukti-row" style="border-bottom:none;">
                        <div class="bukti-col" style="border-bottom:none;">
                            <div class="bukti-lbl">Keperluan</div>
                            <div class="bukti-txt" style="font-weight:400;">{{ $peminjamanBarang->keperluan }}</div>
                        </div>
                        <div class="bukti-col" style="border-bottom:none;">
                            <div class="bukti-lbl">Diproses</div>
                            <div class="bukti-txt" style="font-weight:400;">{{ $peminjamanBarang->updated_at?->format('d M Y, H:i') }}</div>
                        </div>
                    </div>

                    <div class="bukti-footer">
                        <i class="bi bi-shield-check me-1"></i>
                        Dokumen ini diterbitkan secara digital oleh sistem SARPRAS. Berlaku sebagai bukti peminjaman resmi.
                    </div>
                </div>

                <div class="mt-4 d-flex align-items-center gap-3">
                    <button class="btn-unduh" onclick="unduhBukti()">
                        <i class="bi bi-download"></i> Unduh Bukti (JPG)
                    </button>
                    <small class="text-muted" style="font-size:12px;"><i class="bi bi-info-circle me-1"></i>File akan tersimpan otomatis ke perangkat Anda</small>
                </div>
            </div>
        </div>
        @else
        <div class="card h-100" style="display:flex;align-items:center;justify-content:center;min-height:200px;">
            <div class="text-center p-5" style="color:#94A3B8;">
                @if($peminjamanBarang->status === 'Ditolak')
                    <i class="bi bi-x-circle fs-1 d-block mb-3" style="color:#FCA5A5;"></i>
                    <div style="font-weight:700;color:#DC2626;">Permintaan Ditolak</div>
                    <div style="font-size:13px;margin-top:6px;">Bukti peminjaman tidak tersedia karena permintaan Anda ditolak oleh petugas.</div>
                @else
                    <i class="bi bi-hourglass-split fs-1 d-block mb-3" style="color:#FCD34D;"></i>
                    <div style="font-weight:700;color:#92400E;">Menunggu Persetujuan</div>
                    <div style="font-size:13px;margin-top:6px;">Bukti peminjaman akan tersedia setelah permintaan Anda disetujui oleh petugas.</div>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function unduhBukti() {
    const el = document.getElementById('buktiPeminjaman');
    const btn = document.querySelector('.btn-unduh');
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
    btn.disabled = true;

    html2canvas(el, {
        scale: 2,
        useCORS: true,
        backgroundColor: '#EFF6FF',
        logging: false,
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'Bukti-Peminjaman-{{ $peminjamanBarang->nomor_transaksi }}.jpg';
        link.href = canvas.toDataURL('image/jpeg', 0.95);
        link.click();
        btn.innerHTML = '<i class="bi bi-download"></i> Unduh Bukti (JPG)';
        btn.disabled = false;
    }).catch(() => {
        alert('Gagal mengunduh bukti. Silakan coba lagi.');
        btn.innerHTML = '<i class="bi bi-download"></i> Unduh Bukti (JPG)';
        btn.disabled = false;
    });
}
</script>
@endpush
