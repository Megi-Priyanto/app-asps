<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $siswas = $query->latest()->paginate(15)->withQueryString();

        return view('admin.pengguna.siswa.index', compact('siswas'));
    }

    public function create()
    {
        return view('admin.pengguna.siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis'      => 'required|string|unique:siswas,nis|max:20',
            'nama'     => 'required|string|max:100',
            'kelas'    => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nis.unique'           => 'NIS sudah terdaftar.',
            'password.confirmed'   => 'Konfirmasi password tidak cocok.',
            'password.min'         => 'Password minimal 6 karakter.',
        ]);

        Siswa::create([
            'nis'      => $request->nis,
            'nama'     => $request->nama,
            'kelas'    => $request->kelas,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.pengguna.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        return view('admin.pengguna.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nis'   => 'required|string|max:20|unique:siswas,nis,' . $siswa->id,
            'nama'  => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'nis.unique'         => 'NIS sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 6 karakter.',
        ]);

        $data = [
            'nis'   => $request->nis,
            'nama'  => $request->nama,
            'kelas' => $request->kelas,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $siswa->update($data);

        return redirect()->route('admin.pengguna.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('admin.pengguna.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    public function exportExcel()
    {
        $siswas = Siswa::latest()->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        $sheet->setCellValue('A1', 'No')
              ->setCellValue('B1', 'NAMA')
              ->setCellValue('C1', 'NIS')
              ->setCellValue('D1', 'KELAS');

        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);

        $row = 2;
        $no = 1;
        foreach ($siswas as $siswa) {
            $sheet->setCellValue('A' . $row, $no++)
                  ->setCellValue('B' . $row, $siswa->nama)
                  ->setCellValue('C' . $row, $siswa->nis)
                  ->setCellValue('D' . $row, $siswa->kelas);
            $row++;
        }

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'data_siswa_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportPdf()
    {
        $siswas = Siswa::latest()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.siswa', compact('siswas'));
        // Optional: set paper size
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('data_siswa_' . date('Ymd_His') . '.pdf');
    }
} 