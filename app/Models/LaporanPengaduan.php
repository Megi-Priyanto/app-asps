<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPengaduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'reporter_type',
        'reporter_id',
        'kategori_aspirasi_id',
        'ket',
        'lokasi',
        'foto',
    ];

    // Relasi lama (tetap dipertahankan untuk kompatibilitas)
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi polymorphic baru
    public function reporter()
    {
        return $this->morphTo();
    }

    public function kategoriAspirasi()
    {
        return $this->belongsTo(KategoriAspirasi::class);
    }

    public function aspirasi()
    {
        return $this->hasOne(Aspirasi::class, 'laporan_id');
    }

    public function komentar()
    {
        return $this->hasMany(KomentarLaporan::class, 'laporan_id');
    }
}