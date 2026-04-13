<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TanggapanAplikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'catatan',
        'is_tampil'
    ];

    /**
     * Get the parent user model (Siswa, Guru, or Pegawai).
     */
    public function user()
    {
        return $this->morphTo();
    }
}
