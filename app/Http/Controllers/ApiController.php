<?php

namespace App\Http\Controllers;

use App\Models\KategoriAspirasi;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * Mengembalikan list kategori aspirasi berdasarkan lokasi_id.
     */
    public function getKategoriByLokasi($lokasiId): JsonResponse
    {
        $kategori = KategoriAspirasi::where('lokasi_id', $lokasiId)
            ->orderBy('nama_kategori')
            ->get(['id', 'nama_kategori']);

        return response()->json($kategori);
    }
}
