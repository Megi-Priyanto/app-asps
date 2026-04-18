<!DOCTYPE html>
<html>
<head>
    <title>Rekap Biaya Perbaikan Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 0; padding: 0; }
        .header p { margin: 5px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <h3>REKAPITULASI BIAYA PERBAIKAN BARANG</h3>
        <p>Tanggal Cetak: {{ date('d M Y') }}</p>
        @if(request('lokasi'))
            <p>Lokasi: {{ \App\Models\Lokasi::find(request('lokasi'))->nama_lokasi ?? '-' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">No. Perbaikan</th>
                <th width="15%">Lokasi</th>
                <th width="20%">Nama Barang</th>
                <th class="text-center" width="10%">Tgl Selesai</th>
                <th width="15%">Admin</th>
                <th class="text-right" width="20%">Biaya (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse($perbaikans as $index => $p)
                @php $total += $p->biaya_perbaikan; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $p->nomor_perbaikan }}</td>
                    <td>{{ $p->barang->lokasi->nama_lokasi ?? '-' }}</td>
                    <td>{{ $p->barang->nama_barang }} ({{ $p->jumlah_rusak }} {{ $p->barang->satuan }})</td>
                    <td class="text-center">{{ $p->tanggal_selesai ? \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $p->admin->nama ?? '-' }}</td>
                    <td class="text-right">
                        @if($p->biaya_perbaikan !== null)
                            {{ number_format($p->biaya_perbaikan, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data perbaikan selesai.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">TOTAL KESELURUHAN PENGELUARAN</th>
                <th class="text-right">{{ number_format($total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

</body>
</html>
