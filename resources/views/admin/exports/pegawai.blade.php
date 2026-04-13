<!DOCTYPE html>
<html>
<head>
    <title>Data Pegawai</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #2563EB; color: white; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Data Pegawai</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Username</th>
                <th>Jabatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawais as $idx => $pegawai)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $pegawai->nama }}</td>
                <td>{{ $pegawai->username }}</td>
                <td>{{ $pegawai->jabatan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
