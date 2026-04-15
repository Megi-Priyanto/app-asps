<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_pengadaan' => 'date',
        'is_pinjaman'       => 'boolean',
    ];

    // ==========================================
    // RELASI
    // ==========================================

    public function kategoriBarang(): BelongsTo
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_barang_id');
    }

    public function peminjamanBarangs(): HasMany
    {
        return $this->hasMany(PeminjamanBarang::class, 'barang_id');
    }

    public function perbaikanBarangs(): HasMany
    {
        return $this->hasMany(PerbaikanBarang::class, 'barang_id');
    }

    // ==========================================
    // STOK & STATUS
    // ==========================================

    /**
     * Stok yang sebenarnya tersedia (jumlah_baik - yang sedang dipinjam aktif).
     */
    public function getStokTersediaAttribute(): int
    {
        $sedangDipinjam = $this->peminjamanBarangs()
            ->aktif()
            ->sum('jumlah_pinjam');

        return max(0, $this->jumlah_baik - $sedangDipinjam);
    }

    /**
     * Apakah barang bisa dipinjam sejumlah tertentu?
     */
    public function canBeBorrowed(int $jumlah = 1): bool
    {
        return $this->is_pinjaman && $this->stok_tersedia >= $jumlah;
    }

    public function getSedangDipinjamAttribute(): bool
    {
        return $this->peminjamanBarangs()->aktif()->exists();
    }

    public function getSedangDiperbaikiAttribute(): bool
    {
        return $this->perbaikanBarangs()->belumSelesai()->exists();
    }

    public function getJumlahDalamPerbaikanAttribute(): int
    {
        return $this->perbaikanBarangs()->belumSelesai()->sum('jumlah_rusak');
    }

    // ==========================================
    // KONDISI DOMINAN (untuk badge)
    // ==========================================

    public function getKondisiDominanAttribute(): string
    {
        $baik   = $this->jumlah_baik ?? 0;
        $ringan = $this->jumlah_rusak_ringan ?? 0;
        $berat  = $this->jumlah_rusak_berat ?? 0;

        if ($baik >= $ringan && $baik >= $berat) return 'Baik';
        if ($ringan >= $berat)                   return 'Rusak Ringan';
        return 'Rusak Berat';
    }

    public function getKondisiArrayAttribute(): array
    {
        $kondisi = [];
        if ($this->jumlah_baik > 0)
            $kondisi[] = ['label' => "Baik ({$this->jumlah_baik})", 'class' => 'badge-kondisi-baik'];
        if ($this->jumlah_rusak_ringan > 0)
            $kondisi[] = ['label' => "R. Ringan ({$this->jumlah_rusak_ringan})", 'class' => 'badge-kondisi-ringan'];
        if ($this->jumlah_rusak_berat > 0)
            $kondisi[] = ['label' => "R. Berat ({$this->jumlah_rusak_berat})", 'class' => 'badge-kondisi-berat'];

        return $kondisi;
    }

    // ==========================================
    // SCOPE
    // ==========================================

    public function scopeTersedia($query, int $jumlahMin = 1)
    {
        return $query
            ->where('is_pinjaman', true)
            ->whereRaw(
                '(jumlah_baik - COALESCE((
                    SELECT SUM(jumlah_pinjam)
                    FROM peminjaman_barangs
                    WHERE peminjaman_barangs.barang_id = barangs.id
                    AND tanggal_kembali_aktual IS NULL
                    AND status IN ("Disetujui","Sedang Dipinjam","Terlambat")
                ),0)) >= ?',
                [$jumlahMin]
            );
    }

    public function scopePerluPerbaikan($query)
    {
        return $query->where(function ($q) {
            $q->where('jumlah_rusak_ringan', '>', 0)
              ->orWhere('jumlah_rusak_berat', '>', 0);
        });
    }
}
