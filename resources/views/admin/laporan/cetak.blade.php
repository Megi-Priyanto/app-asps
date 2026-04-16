<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan Pengaduan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 30px;
            color: #1a1a1a;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
        }
        .header h2 {
            margin: 0 0 6px 0;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 4px 0;
            font-size: 13px;
            color: #333;
        }
        .badge-periode {
            display: inline-block;
            background: #1d4ed8;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
            padding: 3px 12px;
            border-radius: 20px;
            margin-top: 6px;
            letter-spacing: 0.5px;
        }

        /* ===== TABEL RINGKASAN ===== */
        .summary-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .summary-section h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #ccc;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .summary-table {
            width: 50%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .summary-table th,
        .summary-table td {
            border: 1px solid #aaa;
            padding: 7px 12px;
            font-size: 12px;
        }
        .summary-table thead th {
            background: #1d4ed8;
            color: #fff;
            text-align: left;
        }
        .summary-table tbody td:last-child {
            text-align: center;
            font-weight: bold;
        }
        .status-menunggu-cell { color: #6b7280; }
        .status-proses-cell   { color: #d97706; }
        .status-selesai-cell  { color: #16a34a; }
        .row-total td {
            background: #e5e7eb !important;
            font-weight: bold;
            color: #1e40af;
        }

        /* ===== TABEL UTAMA ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #aaa;
        }
        th {
            background: #1d4ed8;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-size: 12px;
        }
        td {
            padding: 7px 10px;
            vertical-align: top;
            font-size: 12px;
        }
        tr:nth-child(even) td {
            background: #f3f4f6;
        }
        .text-center { text-align: center; }
        .status-selesai  { color: #16a34a; font-weight: bold; }
        .status-proses   { color: #d97706; font-weight: bold; }
        .status-menunggu { color: #6b7280; font-weight: bold; }
        .total-row td {
            font-weight: bold;
            background: #e5e7eb;
        }
        .footer-info {
            margin-top: 10px;
            font-size: 11px;
            color: #555;
            text-align: right;
        }
        @media print {
            @page { margin: 15mm; }
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    {{-- ====== HEADER ====== --}}
    <div class="header">
        <h2>Rekapitulasi Laporan Pengaduan &amp; Aspirasi</h2>

        @if($jenis === 'harian')
            @php $periodeLabel = \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y'); @endphp
            <p>Laporan Harian &mdash; <strong>{{ $periodeLabel }}</strong></p>
            <span class="badge-periode">Laporan Harian</span>

        @elseif($jenis === 'bulanan')
            @php $periodeLabel = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y'); @endphp
            <p>Laporan Bulanan &mdash; <strong>{{ $periodeLabel }}</strong></p>
            <span class="badge-periode">Laporan Bulanan</span>

        @elseif($jenis === 'tahunan')
            @php $periodeLabel = 'Tahun ' . $tahun; @endphp
            <p>Laporan Tahunan &mdash; <strong>{{ $periodeLabel }}</strong></p>
            <span class="badge-periode">Laporan Tahunan</span>
        @endif

        <p style="font-size:11px; color:#666; margin-top:8px;">
            Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
        </p>
    </div>

    {{-- ============ RINGKASAN PER STATUS (DI ATAS TABEL) ============ --}}
    @php
        $totalMenunggu = $laporan->filter(fn($item) =>
            !$item->aspirasi || $item->aspirasi->status === 'menunggu'
        )->count();

        $totalProses = $laporan->filter(fn($item) =>
            $item->aspirasi && $item->aspirasi->status === 'proses'
        )->count();

        $totalSelesai = $laporan->filter(fn($item) =>
            $item->aspirasi && $item->aspirasi->status === 'selesai'
        )->count();

        $grandTotal = $laporan->count();
    @endphp

    <div class="summary-section">
        <h3>Ringkasan Status Laporan</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th style="width:60px; text-align:center;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="status-menunggu-cell">Belum Diproses / Menunggu</td>
                    <td class="status-menunggu-cell">{{ $totalMenunggu }}</td>
                </tr>
                <tr>
                    <td class="status-proses-cell">Sedang Diproses</td>
                    <td class="status-proses-cell">{{ $totalProses }}</td>
                </tr>
                <tr>
                    <td class="status-selesai-cell">Selesai</td>
                    <td class="status-selesai-cell">{{ $totalSelesai }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ============ TABEL DATA LAPORAN ============ --}}
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:30px;">No</th>
                <th style="width:80px;">Tanggal</th>
                <th style="width:60px;">Pelapor</th>
                <th style="width:110px;">Nama Pelapor</th>
                <th style="width:80px;">Kategori</th>
                <th>Keterangan</th>
                <th style="width:80px;">Lokasi</th>
                <th style="width:60px;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item->created_at->format('d/m/Y') }}<br><small style="color:#666;">{{ $item->created_at->format('H:i') }}</small></td>
                    <td>{{ ucfirst($item->reporter_type ?? 'siswa') }}</td>
                    <td>{{ $item->reporter?->nama ?? $item->siswa?->nama ?? '-' }}</td>
                    <td>{{ $item->kategoriAspirasi->nama_kategori ?? '-' }}</td>
                    <td>{{ $item->ket }}</td>
                    <td>{{ $item->lokasi ?? '-' }}</td>
                    <td class="text-center">
                        @php $st = $item->aspirasi ? $item->aspirasi->status : 'menunggu'; @endphp
                        <span class="status-{{ $st }}">{{ ucfirst($st) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding:16px; color:#888;">
                        Tidak ada laporan pada periode ini.
                    </td>
                </tr>
            @endforelse

            @if($grandTotal > 0)
            <tr class="total-row">
                <td colspan="7" style="text-align:right;">Total Laporan:</td>
                <td class="text-center">{{ $grandTotal }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer-info">
        <p>** Dokumen ini digenerate otomatis oleh sistem **</p>
    </div>

</body>
</html>
