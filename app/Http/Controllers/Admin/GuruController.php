<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        $gurus = $query->latest()->paginate(15)->withQueryString();

        return view('admin.pengguna.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.pengguna.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'nip'      => 'required|string|unique:gurus,nip|max:30',
            'jabatan'  => 'nullable|string|max:100',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nip.unique'         => 'NIP sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 6 karakter.',
        ]);

        Guru::create([
            'nama'     => $request->nama,
            'nip'      => $request->nip,
            'jabatan'  => $request->jabatan,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.pengguna.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        return view('admin.pengguna.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'nip'      => 'required|string|max:30|unique:gurus,nip,' . $guru->id,
            'jabatan'  => 'nullable|string|max:100',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'nip.unique'         => 'NIP sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 6 karakter.',
        ]);

        $data = [
            'nama'    => $request->nama,
            'nip'     => $request->nip,
            'jabatan' => $request->jabatan,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $guru->update($data);

        return redirect()->route('admin.pengguna.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        return redirect()->route('admin.pengguna.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }

    public function exportExcel()
    {
        $gurus = Guru::latest()->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Guru');

        $sheet->setCellValue('A1', 'No')
              ->setCellValue('B1', 'NAMA')
              ->setCellValue('C1', 'NIP')
              ->setCellValue('D1', 'JABATAN');

        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);

        $row = 2;
        $no = 1;
        foreach ($gurus as $guru) {
            $sheet->setCellValue('A' . $row, $no++)
                  ->setCellValue('B' . $row, $guru->nama)
                  ->setCellValue('C' . $row, $guru->nip)
                  ->setCellValue('D' . $row, $guru->jabatan);
            $row++;
        }

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'data_guru_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportPdf()
    {
        $gurus = Guru::latest()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.guru', compact('gurus'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('data_guru_' . date('Ymd_His') . '.pdf');
    }
}