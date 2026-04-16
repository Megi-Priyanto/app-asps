<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::with('lokasi')->latest()->get();
        return view('superadmin.pengguna.admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lokasis = Lokasi::all();
        return view('superadmin.pengguna.admin.create', compact('lokasis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'username' => 'required|string|max:20|unique:admins,username',
            'password' => 'required|string|min:8|max:10|confirmed',
            'lokasi_id' => 'required|exists:lokasis,id',
        ]);

        Admin::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'lokasi_id' => $request->lokasi_id,
        ]);

        return redirect()->route('superadmin.admin.index')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('superadmin.admin.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = Admin::findOrFail($id);
        $lokasis = Lokasi::all();
        return view('superadmin.pengguna.admin.edit', compact('admin', 'lokasis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:50',
            'username' => [
                'required',
                'string',
                'max:20',
                Rule::unique('admins', 'username')->ignore($admin->id),
            ],
            'password' => 'nullable|string|min:8|max:10|confirmed',
            'lokasi_id' => 'required|exists:lokasis,id',
        ]);

        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
            'lokasi_id' => $request->lokasi_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('superadmin.admin.index')
            ->with('success', 'Data Admin berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('superadmin.admin.index')
            ->with('success', 'Admin berhasil dihapus.');
    }

    public function exportExcel()
    {
        $admins = Admin::with('lokasi')->latest()->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Admin');

        $sheet->setCellValue('A1', 'No')
              ->setCellValue('B1', 'NAMA')
              ->setCellValue('C1', 'USERNAME')
              ->setCellValue('D1', 'LOKASI TUGAS');

        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);

        $row = 2;
        $no = 1;
        foreach ($admins as $admin) {
            $sheet->setCellValue('A' . $row, $no++)
                  ->setCellValue('B' . $row, $admin->nama)
                  ->setCellValue('C' . $row, $admin->username)
                  ->setCellValue('D' . $row, $admin->lokasi ? $admin->lokasi->nama_lokasi : '-');
            $row++;
        }

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'data_admin_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportPdf()
    {
        $admins = Admin::with('lokasi')->latest()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.admin', compact('admins'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('data_admin_' . date('Ymd_His') . '.pdf');
    }
}

