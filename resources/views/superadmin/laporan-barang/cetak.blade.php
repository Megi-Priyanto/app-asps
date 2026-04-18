<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Barang — {{ $lokasiFilter ? $lokasiFilter->nama_lokasi : 'Semua Lokasi' }}</title>
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
        .badge-lokasi {
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

        /* Ringkasan */
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
            width: 70%;
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
        .summary-table tbody td:not(:first-child) {
            text-align: center;
            font-weight: bold;
        }
        .row-total td {
            background: #e5e7eb !important;
            font-weight: bold;
            color: #1e40af;
        }

        /* Tabel Utama */
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

        .kondisi-baik   { color: #16a34a; font-weight: bold; }
        .kondisi-ringan { color: #d97706; font-weight: bold; }
        .kondisi-berat  { color: #dc2626; font-weight: bold; }

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

    {{-- Header --}}
    <div class="header">
        <h2>Laporan Data Barang</h2>
        @if($lokasiFilter)
            <p>Lokasi: <strong>{{ $lokasiFilter->nama_lokasi }}</strong></p>
            <span class="badge-lokasi">{{ $lokasiFilter->nama_lokasi }}</span>
        @else
            <p><strong>Semua Lokasi</strong></p>
            <span class="badge-lokasi">Laporan Global</span>
        @endif
        <p style="font-size:11px; color:#666; margin-top:8px;">
            Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
        </p>
    </div>

    {{-- Ringkasan per Lokasi --}}
    <div class="summary-section">
        <h3>Ringkasan per Lokasi</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Lokasi</th>
                    <th style="width:70px;">Total</th>
                    <th style="width:70px;">Baik</th>
                    <th style="width:70px;">Rusak</th>
                </tr>
            </thead>
            <tbody>
                @php $sumTotal = 0; $sumBaik = 0; $sumRusak = 0; @endphp
                @foreach($ringkasanLokasi as $rl)
                    @php
                        $sumTotal += $rl->total_barang;
                        $sumBaik  += $rl->barang_baik;
                        $sumRusak += $rl->barang_rusak;
                    @endphp
                    <tr>
                        <td>{{ $rl->nama_lokasi }}</td>
                        <td>{{ $rl->total_barang }}</td>
                        <td class="kondisi-baik">{{ $rl->barang_baik }}</td>
                        <td class="kondisi-berat">{{ $rl->barang_rusak }}</td>
                    </tr>
                @endforeach
                <tr class="row-total">
                    <td>Total</td>
                    <td>{{ $sumTotal }}</td>
                    <td>{{ $sumBaik }}</td>
                    <td>{{ $sumRusak }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Tabel Data --}}
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:30px;">No</th>
                <th style="width:70px;">Kode</th>
                <th>Nama Barang</th>
                <th style="width:70px;">Kategori</th>
                <th style="width:80px;">Lokasi</th>
                <th class="text-center" style="width:40px;">Baik</th>
                <th class="text-center" style="width:40px;">RR</th>
                <th class="text-center" style="width:40px;">RB</th>
                <th class="text-center" style="width:45px;">Total</th>
                <th style="width:45px;">Satuan</th>
                <th style="width:55px;">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangs as $key => $barang)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $barang->kode_barang }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kategoriBarang->nama_kategori ?? '-' }}</td>
                    <td>{{ $barang->lokasi->nama_lokasi ?? '-' }}</td>
                    <td class="text-center">{{ $barang->jumlah_baik }}</td>
                    <td class="text-center">{{ $barang->jumlah_rusak_ringan }}</td>
                    <td class="text-center">{{ $barang->jumlah_rusak_berat }}</td>
                    <td class="text-center" style="font-weight:bold;">{{ $barang->jumlah }}</td>
                    <td>{{ $barang->satuan }}</td>
                    <td>
                        @php
                            $kClass = match($barang->kondisi) {
                                'Baik' => 'kondisi-baik',
                                'Rusak Ringan' => 'kondisi-ringan',
                                'Rusak Berat' => 'kondisi-berat',
                                default => '',
                            };
                        @endphp
                        <span class="{{ $kClass }}">{{ $barang->kondisi }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center" style="padding:16px; color:#888;">
                        Tidak ada data barang.
                    </td>
                </tr>
            @endforelse

            @if($barangs->count() > 0)
            @php
                $sumJumlah = $barangs->sum('jumlah');
                $sumB      = $barangs->sum('jumlah_baik');
                $sumRR     = $barangs->sum('jumlah_rusak_ringan');
                $sumRB     = $barangs->sum('jumlah_rusak_berat');
            @endphp
            <tr style="font-weight:bold; background:#e5e7eb;">
                <td colspan="5" style="text-align:right;">Total:</td>
                <td class="text-center">{{ $sumB }}</td>
                <td class="text-center">{{ $sumRR }}</td>
                <td class="text-center">{{ $sumRB }}</td>
                <td class="text-center" style="color:#1e40af;">{{ $sumJumlah }}</td>
                <td colspan="2"></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer-info">
        <p>** Dokumen ini digenerate otomatis oleh sistem **</p>
    </div>

</body>
</html>
