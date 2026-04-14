<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        $pegawais = $query->latest()->paginate(15)->withQueryString();

        return view('superadmin.pengguna.pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('superadmin.pengguna.pegawai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:pegawais,username|max:20',
            'nama'     => 'required|string|max:50',
            'jabatan'  => 'nullable|string|max:50',
            'password' => 'required|string|min:6|max:10|confirmed',
        ], [
            'username.unique'      => 'Username sudah terdaftar.',
            'password.confirmed'   => 'Konfirmasi password tidak cocok.',
            'password.min'         => 'Password minimal 6 karakter.',
            'password.max'         => 'Password maksimal 10 karakter.',
        ]);

        Pegawai::create([
            'username' => $request->username,
            'nama'     => $request->nama,
            'jabatan'  => $request->jabatan,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('superadmin.pengguna.pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        return view('superadmin.pengguna.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'username' => 'required|string|max:20|unique:pegawais,username,' . $pegawai->id,
            'nama'     => 'required|string|max:50',
            'jabatan'  => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6|max:10|confirmed',
        ], [
            'username.unique'     => 'Username sudah terdaftar.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'password.min'        => 'Password minimal 6 karakter.',
            'password.max'        => 'Password maksimal 10 karakter.',
        ]);

        $data = [
            'username' => $request->username,
            'nama'     => $request->nama,
            'jabatan'  => $request->jabatan,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pegawai->update($data);

        return redirect()->route('superadmin.pengguna.pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        return redirect()->route('superadmin.pengguna.pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }

    public function exportExcel()
    {
        $pegawais = Pegawai::latest()->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pegawai');

        $sheet->setCellValue('A1', 'No')
              ->setCellValue('B1', 'NAMA')
              ->setCellValue('C1', 'USERNAME')
              ->setCellValue('D1', 'JABATAN');

        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);

        $row = 2;
        $no = 1;
        foreach ($pegawais as $pegawai) {
            $sheet->setCellValue('A' . $row, $no++)
                  ->setCellValue('B' . $row, $pegawai->nama)
                  ->setCellValue('C' . $row, $pegawai->username)
                  ->setCellValue('D' . $row, $pegawai->jabatan);
            $row++;
        }

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'data_pegawai_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportPdf()
    {
        $pegawais = Pegawai::latest()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.pegawai', compact('pegawais'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('data_pegawai_' . date('Ymd_His') . '.pdf');
    }
}
