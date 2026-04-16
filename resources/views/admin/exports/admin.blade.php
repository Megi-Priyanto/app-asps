<!DOCTYPE html>
<html>
<head>
    <title>Data Admin</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #2563EB; color: white; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Data Admin</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama </th>
                <th>Username</th>
                <th>Lokasi Tugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $idx => $admin)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $admin->nama }}</td>
                <td>{{ $admin->username }}</td>
                <td>{{ $admin->lokasi ? $admin->lokasi->nama_lokasi : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
