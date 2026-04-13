<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SiswaImportController extends Controller
{
    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        // ── Header kolom baris 1 ──────────────────────────────
        $sheet->setCellValue('A1', 'NAMA');
        $sheet->setCellValue('B1', 'NIS');
        $sheet->setCellValue('C1', 'KELAS');
        $sheet->setCellValue('D1', 'PASSWORD');

        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['rgb' => 'BFDBFE'],
                ],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // ── Contoh data baris 2 ───────────────────────────────
        $sheet->setCellValue('A2', 'Budi Santoso');
        $sheet->setCellValue('B2', '12345');
        $sheet->setCellValue('C2', 'X PPLG A');
        $sheet->setCellValue('D2', 'password123');

        $sheet->getStyle('A2:D2')->applyFromArray([
            'font' => [
                'italic' => true,
                'color'  => ['rgb' => '94A3B8'],
            ],
            'fill' => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F8FAFC'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);

        // ── Keterangan baris 3 ────────────────────────────────
        $sheet->mergeCells('A3:D3');
        $sheet->setCellValue('A3', '* Baris ke-2 adalah contoh, hapus sebelum diimport. Isi data mulai baris ke-4.');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'italic' => true,
                'size'   => 9,
                'color'  => ['rgb' => 'EF4444'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ]);

        // ── Lebar kolom ───────────────────────────────────────
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);

        // ── Freeze baris header ───────────────────────────────
        $sheet->freezePane('A2');

        // ── Stream download ───────────────────────────────────
        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'format_import_siswa.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls|max:5120',
        ], [
            'file_excel.required' => 'Pilih file Excel terlebih dahulu.',
            'file_excel.mimes'    => 'File harus berformat .xlsx atau .xls.',
            'file_excel.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            $file        = $request->file('file_excel');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, true);

            $berhasil = 0;
            $gagal    = [];

            // Data mulai baris ke-4 (1=header, 2=contoh, 3=keterangan)
            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex < 4) continue;

                $nama     = trim($row['A'] ?? '');
                $nis      = trim($row['B'] ?? '');
                $kelas    = trim($row['C'] ?? '');
                $password = trim($row['D'] ?? '');

                // Lewati baris kosong
                if ($nama === '' && $nis === '') continue;

                $validator = Validator::make(
                    [
                        'nama'     => $nama,
                        'nis'      => $nis,
                        'kelas'    => $kelas,
                        'password' => $password,
                    ],
                    [
                        'nama'     => 'required|max:100',
                        'nis'      => 'required|max:20|unique:siswas,nis',
                        'kelas'    => 'required|max:20',
                        'password' => 'required|min:6',
                    ],
                    [
                        'nis.unique'   => 'NIS sudah terdaftar.',
                        'password.min' => 'Password minimal 6 karakter.',
                    ]
                );

                if ($validator->fails()) {
                    $gagal[] = [
                        'baris' => $rowIndex,
                        'nis'   => $nis ?: '-',
                        'nama'  => $nama ?: '-',
                        'pesan' => implode(', ', $validator->errors()->all()),
                    ];
                    continue;
                }

                Siswa::create([
                    'nama'     => $nama,
                    'nis'      => $nis,
                    'kelas'    => $kelas,
                    'password' => Hash::make($password),
                ]);

                $berhasil++;
            }

            $message = "Import selesai. {$berhasil} data siswa berhasil ditambahkan.";
            if (count($gagal) > 0) {
                $message .= ' ' . count($gagal) . ' baris gagal.';
                session(['import_siswa_gagal' => $gagal]);
            }

            return redirect()->route('admin.pengguna.siswa.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }
    }
}