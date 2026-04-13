<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomentarLaporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'sender_type',
        'sender_id',
        'pesan',
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanPengaduan::class, 'laporan_id');
    }

    public function sender()
    {
        return $this->morphTo(__FUNCTION__, 'sender_type', 'sender_id');
    }
}
