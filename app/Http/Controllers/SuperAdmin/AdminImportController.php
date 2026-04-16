<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AdminImportController extends Controller
{
    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Admin');

        // ── Header kolom baris 1 ──────────────────────────────
        $sheet->setCellValue('A1', 'NAMA');
        $sheet->setCellValue('B1', 'USERNAME');
        $sheet->setCellValue('C1', 'PASSWORD');
        $sheet->setCellValue('D1', 'NAMA LOKASI');

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
        $sheet->setCellValue('A2', 'Admin Lab Komputer');
        $sheet->setCellValue('B2', 'admin_lab');
        $sheet->setCellValue('C2', 'password123');
        $sheet->setCellValue('D2', 'Lab Komputer');

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
        $sheet->setCellValue('A3', '* Baris ke-2 adalah contoh, hapus sebelum diimport. Isi data mulai baris ke-4. "NAMA LOKASI" harus sesuai nama persis yang ada di database.');
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
        $sheet->getColumnDimension('D')->setWidth(40);

        // ── Freeze baris header ───────────────────────────────
        $sheet->freezePane('A2');

        // ── Stream download ───────────────────────────────────
        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'format_import_admin.xlsx';

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

                $nama        = trim($row['A'] ?? '');
                $username    = trim($row['B'] ?? '');
                $password    = trim($row['C'] ?? '');
                $nama_lokasi = trim($row['D'] ?? '');

                // Lewati baris kosong
                if ($nama === '' && $username === '') continue;

                $validator = Validator::make(
                    [
                        'nama'        => $nama,
                        'username'    => $username,
                        'password'    => $password,
                        'nama_lokasi' => $nama_lokasi,
                    ],
                    [
                        'nama'        => 'required|max:50',
                        'username'    => 'required|max:20|unique:admins,username',
                        'password'    => 'required|min:8|max:10',
                        'nama_lokasi' => 'required',
                    ],
                    [
                        'username.unique' => 'Username sudah terdaftar.',
                        'password.min'    => 'Password minimal 8 karakter.',
                        'password.max'    => 'Password maksimal 10 karakter.',
                    ]
                );

                if ($validator->fails()) {
                    $gagal[] = [
                        'baris' => $rowIndex,
                        'username' => $username ?: '-',
                        'nama'  => $nama ?: '-',
                        'pesan' => implode(', ', $validator->errors()->all()),
                    ];
                    continue;
                }

                // Cari Lokasi
                $lokasi = Lokasi::where('nama_lokasi', $nama_lokasi)->first();
                if (!$lokasi) {
                    $gagal[] = [
                        'baris' => $rowIndex,
                        'username' => $username,
                        'nama'  => $nama,
                        'pesan' => "Lokasi Tugas '$nama_lokasi' tidak ada di database.",
                    ];
                    continue;
                }

                Admin::create([
                    'nama'      => $nama,
                    'username'  => $username,
                    'password'  => Hash::make($password),
                    'lokasi_id' => $lokasi->id,
                ]);

                $berhasil++;
            }

            $message = "Import selesai. {$berhasil} data admin berhasil ditambahkan.";
            if (count($gagal) > 0) {
                $message .= ' ' . count($gagal) . ' baris gagal.';
                session(['import_admin_gagal' => $gagal]);
            }

            return redirect()->route('superadmin.admin.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }
    }
}
