<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PegawaiImportController extends Controller
{
    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pegawai');

        // ── Header kolom baris 1 ──────────────────────────────
        $sheet->setCellValue('A1', 'NAMA');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'JABATAN');
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
        $sheet->setCellValue('A2', 'Rahmat Hidayat');
        $sheet->setCellValue('B2', '1987654321');
        $sheet->setCellValue('C2', 'Staf TU');
        $sheet->setCellValue('D2', 'pegawai123');

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
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(20);

        // ── Freeze baris header ───────────────────────────────
        $sheet->freezePane('A2');

        // ── Stream download ───────────────────────────────────
        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'format_import_pegawai.xlsx';

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

            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex < 4) continue;

                $nama     = trim($row['A'] ?? '');
                $nip      = trim($row['B'] ?? '');
                $jabatan  = trim($row['C'] ?? '');
                $password = trim($row['D'] ?? '');

                if ($nama === '' && $nip === '') continue;

                $validator = Validator::make(
                    [
                        'nama'     => $nama,
                        'nip'      => $nip,
                        'jabatan'  => $jabatan,
                        'password' => $password,
                    ],
                    [
                        'nama'     => 'required|max:100',
                        'nip'      => 'required|max:20|unique:pegawais,nip',
                        'jabatan'  => 'nullable|max:100',
                        'password' => 'required|min:6',
                    ],
                    [
                        'nip.unique'   => 'NIP sudah terdaftar.',
                        'password.min' => 'Password minimal 6 karakter.',
                    ]
                );

                if ($validator->fails()) {
                    $gagal[] = [
                        'baris' => $rowIndex,
                        'nip'   => $nip ?: '-',
                        'nama'  => $nama ?: '-',
                        'pesan' => implode(', ', $validator->errors()->all()),
                    ];
                    continue;
                }

                Pegawai::create([
                    'nama'     => $nama,
                    'nip'      => $nip,
                    'jabatan'  => $jabatan,
                    'password' => Hash::make($password),
                ]);

                $berhasil++;
            }

            $message = "Import selesai. {$berhasil} data pegawai berhasil ditambahkan.";
            if (count($gagal) > 0) {
                $message .= ' ' . count($gagal) . ' baris gagal.';
                session(['import_pegawai_gagal' => $gagal]);
            }

            return redirect()->route('superadmin.pengguna.pegawai.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }
    }
}
