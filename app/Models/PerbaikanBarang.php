<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class PerbaikanBarang extends Model
{
    protected $fillable = [
        'nomor_perbaikan',
        'barang_id',
        'peminjaman_id',
        'jumlah_rusak',
        'tingkat_kerusakan',
        'keterangan_kerusakan',
        'tanggal_masuk',
        'tanggal_selesai',
        'status',
        'catatan_perbaikan',
        'biaya_perbaikan',
        'admin_id',
    ];

    protected $casts = [
        'tanggal_masuk'   => 'date',
        'tanggal_selesai' => 'date',
        'biaya_perbaikan' => 'decimal:2',
    ];

    // ==========================================
    // RELASI
    // ==========================================

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(PeminjamanBarang::class, 'peminjaman_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    // ==========================================
    // GENERATE NOMOR PERBAIKAN
    // ==========================================

    public static function generateNomor(): string
    {
        $last = self::whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();

        $seq = $last ? intval(substr($last->nomor_perbaikan, -3)) + 1 : 1;
        return 'PBK-' . Carbon::now()->format('Ymd') . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }

    // ==========================================
    // ACCESSOR
    // ==========================================

    public function getDurasiPerbaikanAttribute(): string
    {
        $end  = $this->tanggal_selesai ?? Carbon::now()->toDateString();
        $diff = $this->tanggal_masuk->diff($end);
        $parts = [];
        if ($diff->m > 0) $parts[] = $diff->m . ' bulan';
        if ($diff->d > 0) $parts[] = $diff->d . ' hari';
        return implode(' ', $parts) ?: '0 hari';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Menunggu'        => 'badge-warning',
            'Dalam Perbaikan' => 'badge-info',
            'Selesai'         => 'badge-success',
            default           => 'badge-secondary',
        };
    }

    public function getKerusakanBadgeClassAttribute(): string
    {
        return match ($this->tingkat_kerusakan) {
            'Rusak Ringan' => 'badge-warning',
            'Rusak Berat'  => 'badge-danger',
            default        => 'badge-secondary',
        };
    }

    // ==========================================
    // SCOPE
    // ==========================================

    public function scopeBelumSelesai($query)
    {
        return $query->whereIn('status', ['Menunggu', 'Dalam Perbaikan']);
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'Selesai');
    }
}
