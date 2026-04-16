<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class PeminjamanBarang extends Model
{
    protected $fillable = [
        'nomor_transaksi',
        'barang_id',
        'borrower_type',
        'borrower_id',
        'jumlah_pinjam',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'keperluan',
        'kondisi_barang',
        'admin_id',
        'catatan_admin',
        'nama_peminjam',
        'peran_peminjam',
        'detail_peminjam',
    ];

    protected $casts = [
        'tanggal_pinjam'          => 'datetime',
        'tanggal_kembali_rencana' => 'datetime',
        'tanggal_kembali_aktual'  => 'datetime',
    ];

    // ==========================================
    // RELASI
    // ==========================================

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /** Dinamis ke Guru / Siswa / Pegawai */
    public function borrower()
    {
        return $this->morphTo();
    }

    public function perbaikanBarang(): HasOne
    {
        return $this->hasOne(PerbaikanBarang::class, 'peminjaman_id');
    }

    // ==========================================
    // GENERATE NOMOR TRANSAKSI
    // ==========================================

    public static function generateNomorTransaksi(): string
    {
        $last = self::whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();

        $seq = $last ? intval(substr($last->nomor_transaksi, -3)) + 1 : 1;

        return 'PMJ-' . Carbon::now()->format('Ymd') . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }

    // ==========================================
    // ACCESSOR
    // ==========================================

    public function getTerlambatAttribute(): bool
    {
        if (!$this->tanggal_kembali_rencana) return false;
        if ($this->tanggal_kembali_aktual)   return false;
        return Carbon::now()->greaterThan($this->tanggal_kembali_rencana);
    }

    public function getHariTerlambatAttribute(): int
    {
        if (!$this->terlambat) return 0;
        $jam = $this->tanggal_kembali_rencana->diffInHours(Carbon::now());
        return (int) ceil($jam / 24);
    }

    public function getDurasiPeminjamanAttribute(): string
    {
        if (!$this->tanggal_pinjam) return '-';
        $end  = $this->tanggal_kembali_rencana ?: ($this->tanggal_kembali_aktual ?: Carbon::now());
        $diff = $this->tanggal_pinjam->diff($end);
        $parts = [];
        if ($diff->d > 0) $parts[] = "{$diff->d} Hari";
        if ($diff->h > 0) $parts[] = "{$diff->h} Jam";
        if ($diff->i > 0) $parts[] = "{$diff->i} Menit";
        return implode(' ', $parts) ?: '0 Menit';
    }

    public function getBorrowerNameAttribute(): string
    {
        $borrower = $this->borrower;
        if (!$borrower) return '-';
        return $borrower->nama ?? $borrower->name ?? '-';
    }

    public function getBorrowerRoleAttribute(): string
    {
        return match ($this->borrower_type) {
            'App\Models\Admin'   => 'Admin',
            'App\Models\Guru'    => 'Guru',
            'App\Models\Siswa'   => 'Siswa',
            'App\Models\Pegawai' => 'Pegawai',
            default              => 'Pengguna',
        };
    }

    // ==========================================
    // SCOPE
    // ==========================================

    /** Peminjaman yang masih aktif (belum dikembalikan) */
    public function scopeAktif($query)
    {
        return $query->whereNull('tanggal_kembali_aktual')
            ->whereIn('status', ['Disetujui', 'Sedang Dipinjam', 'Terlambat']);
    }

    public function scopeTerlambat($query)
    {
        return $query->where('tanggal_kembali_rencana', '<', Carbon::now())
            ->whereNull('tanggal_kembali_aktual');
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'Menunggu');
    }

    // ==========================================
    // BOOT — Auto update status
    // ==========================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($p) {
            // Jika sudah dikembalikan, skip auto-status
            if ($p->tanggal_kembali_aktual) {
                if ($p->status !== 'Sudah Dikembalikan') {
                    $p->status = 'Sudah Dikembalikan';
                }
                return;
            }
            // Auto terlambat
            if ($p->tanggal_kembali_rencana &&
                Carbon::now()->greaterThan($p->tanggal_kembali_rencana) &&
                in_array($p->status, ['Disetujui', 'Sedang Dipinjam', 'Terlambat'])) {
                $p->status = 'Terlambat';
            }
        });
    }
}
