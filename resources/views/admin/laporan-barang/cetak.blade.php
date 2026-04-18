<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Barang — {{ $lokasi->nama_lokasi ?? '-' }}</title>
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
        <p>Lokasi: <strong>{{ $lokasi->nama_lokasi ?? '-' }}</strong></p>
        <span class="badge-lokasi">{{ $lokasi->nama_lokasi ?? '-' }}</span>
        <p style="font-size:11px; color:#666; margin-top:8px;">
            Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
        </p>
    </div>

    {{-- Ringkasan --}}
    @php
        $totalBaik   = $barangs->where('kondisi', 'Baik')->count();
        $totalRingan = $barangs->where('kondisi', 'Rusak Ringan')->count();
        $totalBerat  = $barangs->where('kondisi', 'Rusak Berat')->count();
        $grandTotal  = $barangs->count();

        $sumJumlah     = $barangs->sum('jumlah');
        $sumBaik       = $barangs->sum('jumlah_baik');
        $sumRusakR     = $barangs->sum('jumlah_rusak_ringan');
        $sumRusakB     = $barangs->sum('jumlah_rusak_berat');
    @endphp

    <div class="summary-section">
        <h3>Ringkasan Kondisi Barang</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Kondisi</th>
                    <th style="width:80px; text-align:center;">Jenis</th>
                    <th style="width:80px; text-align:center;">Unit</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="kondisi-baik">Baik</td>
                    <td>{{ $totalBaik }}</td>
                    <td>{{ $sumBaik }}</td>
                </tr>
                <tr>
                    <td class="kondisi-ringan">Rusak Ringan</td>
                    <td>{{ $totalRingan }}</td>
                    <td>{{ $sumRusakR }}</td>
                </tr>
                <tr>
                    <td class="kondisi-berat">Rusak Berat</td>
                    <td>{{ $totalBerat }}</td>
                    <td>{{ $sumRusakB }}</td>
                </tr>
                <tr class="row-total">
                    <td>Total</td>
                    <td>{{ $grandTotal }}</td>
                    <td>{{ $sumJumlah }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Tabel Data --}}
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:30px;">No</th>
                <th style="width:80px;">Kode</th>
                <th>Nama Barang</th>
                <th style="width:80px;">Kategori</th>
                <th class="text-center" style="width:50px;">Baik</th>
                <th class="text-center" style="width:50px;">RR</th>
                <th class="text-center" style="width:50px;">RB</th>
                <th class="text-center" style="width:50px;">Total</th>
                <th style="width:50px;">Satuan</th>
                <th style="width:60px;">Kondisi</th>
                <th style="width:80px;">Sumber</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangs as $key => $barang)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $barang->kode_barang }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kategoriBarang->nama_kategori ?? '-' }}</td>
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
                    <td>{{ $barang->sumber ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center" style="padding:16px; color:#888;">
                        Tidak ada data barang di lokasi ini.
                    </td>
                </tr>
            @endforelse

            @if($grandTotal > 0)
            <tr style="font-weight:bold; background:#e5e7eb;">
                <td colspan="4" style="text-align:right;">Total:</td>
                <td class="text-center">{{ $sumBaik }}</td>
                <td class="text-center">{{ $sumRusakR }}</td>
                <td class="text-center">{{ $sumRusakB }}</td>
                <td class="text-center" style="color:#1e40af;">{{ $sumJumlah }}</td>
                <td colspan="3"></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer-info">
        <p>** Dokumen ini digenerate otomatis oleh sistem **</p>
    </div>

</body>
</html>
