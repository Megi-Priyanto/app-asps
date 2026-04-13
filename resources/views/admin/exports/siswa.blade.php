<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #2563EB; color: white; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Data Siswa</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $idx => $siswa)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $siswa->nama }}</td>
                <td>{{ $siswa->nis }}</td>
                <td>{{ $siswa->kelas }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
