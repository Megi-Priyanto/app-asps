@extends('layouts.pegawai')

@section('title', 'Peminjaman Barang')

@push('css')
<style>
    :root {
        --primary: #2563EB; --primary-light: #EFF6FF;
        --body-bg: #F8FAFC; --card-bg: #fff;
        --border: #E2E8F0; --text-primary: #0F172A;
        --text-secondary: #64748B; --text-muted: #94A3B8;
        --success: #10B981; --warning: #F59E0B; --danger: #EF4444;
        --radius: 12px; --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); }

    /* ===== PAGE HEADER ===== */
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 20px; flex-wrap: wrap; gap: 12px;
    }
    .page-header h1 { font-size: 20px; font-weight: 800; color: var(--text-primary); margin: 0; }
    .page-header p  { font-size: 13px; color: var(--text-secondary); margin: 2px 0 0; }

    /* ===== CARD & TABLE ===== */
    .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); }
    .card-header {
        background: transparent; border-bottom: 1px solid var(--border);
        padding: 14px 18px; font-weight: 700; color: var(--text-primary); font-size: 14px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .card-footer { background: transparent; border-top: 1px solid var(--border); padding: 12px 18px; }

    .table { margin: 0; font-size: 13px; width: 100%; border-collapse: collapse; }
    .table thead th {
        background: #F8FAFC; color: var(--text-secondary);
        font-weight: 700; font-size: 11px; text-transform: uppercase;
        letter-spacing: .5px; border-bottom: 1px solid var(--border);
        padding: 11px 16px; border-top: none; white-space: nowrap; text-align: left;
    }
    .table tbody td { padding: 13px 16px; border-bottom: 1px solid #F1F5F9; color: var(--text-primary); vertical-align: middle; }
    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover td { background: #FAFBFF; }

    /* ===== BADGES ===== */
    .pmj-badge{display:inline-block;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600;}
    .pmj-badge-warning{background:#FFFBEB;color:#B45309;}
    .pmj-badge-info{background:#F0F9FF;color:#0369A1;}
    .pmj-badge-blue{background:#EFF6FF;color:#1D4ED8;}
    .pmj-badge-success{background:#ECFDF5;color:#059669;}
    .pmj-badge-danger{background:#FEF2F2;color:#E11D48;}
    .pmj-badge-muted{background:#F1F5F9;color:#475569;}

    /* ===== EMPTY STATE ===== */
    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state .empty-icon { font-size: 48px; display: block; margin-bottom: 12px; color: #CBD5E1; }
    .empty-state h3 { font-size: 16px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; }
    .empty-state p  { font-size: 13px; color: var(--text-muted); margin-bottom: 20px; }

    /* ===== BUTTONS ===== */
    .btn { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; font-size: 13.5px; border-radius: 8px; border: none; transition: all .15s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; }
    .btn-primary { background: var(--primary); color: white; padding: 9px 18px; }
    .btn-primary:hover { background: #1D4ED8; color: white; }

    /* ===== ALERT ===== */
    .alert { border: none; border-radius: var(--radius); font-size: 13.5px; font-weight: 500; padding: 12px 16px; }
    .alert-success { background: #ECFDF5; color: #065F46; }
</style>
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:12px;"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mb-4 d-flex align-items-center gap-2" style="background:#FEF2F2; color:#991B1B;">
        <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:12px;"></button>
    </div>
@endif

{{-- ===== PAGE HEADER ===== --}}
<div class="page-header">
    <div>
        <h1><i class="bi bi-box-seam-fill me-2" style="color:#2563EB;"></i>Peminjaman Barang</h1>
        <p>Riwayat dan status permintaan peminjaman Anda</p>
    </div>
    <a href="{{ route('pegawai.peminjaman-barang.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Buat Permintaan
    </a>
</div>

{{-- ===== TABEL PEMINJAMAN ===== --}}
<div class="card" style="margin-top: 10px;">
    <div class="card-header">
        <span>
            <i class="bi bi-table me-2" style="color:#2563EB;"></i>
            Daftar Peminjaman
            <span style="font-size:12px;font-weight:500;color:var(--text-secondary);margin-left:6px;">
                ({{ $peminjamans->total() }} data ditemukan)
            </span>
        </span>
    </div>

    <div class="table-responsive">
        @if($peminjamans->isEmpty())
            <div class="empty-state">
                <i class="bi bi-box-seam empty-icon"></i>
                <h3>Belum ada permintaan peminjaman</h3>
                <p>Kamu belum pernah membuat permintaan peminjaman barang.</p>
                <a href="{{ route('pegawai.peminjaman-barang.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Buat Sekarang
                </a>
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>No. Transaksi</th>
                        <th>Barang</th>
                        <th>Jml</th>
                        <th>Tgl Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjamans as $i => $p)
                    @php
                        $sc = match($p->status) {
                            'Menunggu'=>'pmj-badge-warning','Disetujui'=>'pmj-badge-info',
                            'Sedang Dipinjam'=>'pmj-badge-blue','Sudah Dikembalikan'=>'pmj-badge-success',
                            'Terlambat'=>'pmj-badge-danger','Ditolak'=>'pmj-badge-muted', default=>'pmj-badge-muted'
                        };
                    @endphp
                    <tr>
                        <td style="color:var(--text-muted);font-weight:600;">
                            {{ $peminjamans->firstItem() + $i }}
                        </td>
                        <td><code style="font-size:11.5px;color:#2563EB;background:#EFF6FF;padding:3px 7px;border-radius:4px;">{{ $p->nomor_transaksi }}</code></td>
                        <td><div style="font-weight:600;color:var(--text-primary);">{{ $p->barang->nama_barang ?? '-' }}</div></td>
                        <td style="color:var(--text-secondary);">{{ $p->jumlah_pinjam }} {{ $p->barang->satuan ?? '' }}</td>
                        <td style="font-size:12.5px;color:var(--text-secondary);">{{ $p->tanggal_pinjam?->format('d M Y') }}</td>
                        <td style="font-size:12.5px;color:var(--text-secondary);">{{ $p->tanggal_kembali_rencana?->format('d M Y') }}
                            @if($p->terlambat)<br><small class="text-danger" style="font-weight:600;display:block;margin-top:2px;">Terlambat {{ $p->hari_terlambat }} hari</small>@endif
                        </td>
                        <td>
                            <span class="pmj-badge {{ $sc }}">{{ $p->status }}</span>
                            @if($p->status === 'Ditolak' && $p->catatan_admin)
                                <br><small class="text-muted" style="display:block;margin-top:4px;font-size:11px;max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $p->catatan_admin }}">{{ $p->catatan_admin }}</small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @if($peminjamans->hasPages())
    <div class="card-footer">
        {{ $peminjamans->links() }}
    </div>
    @endif
</div>

@endsection
