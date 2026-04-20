<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Aspirasi extends Model
{
   use HasFactory;

   protected $fillable = [
      'laporan_id',
      'responder_id',
      'responder_type',
      'status',
      'feedback',
      'alasan',
   ];

   public function laporan()
   {
      return $this->belongsTo(LaporanPengaduan::class, 'laporan_id');
   }

   public function responder()
   {
      return $this->morphTo();
   }

   public function admin()
   {
      return $this->responder();
   }

   protected static function booted()
   {
       // Saat aspirasi pertama kali dibuat oleh Admin (artinya status awal langsung di-set)
       static::created(function ($aspirasi) {
           self::createAutoKomentar($aspirasi);
       });

       // Saat aspirasi di-update (terutama 'status' berubah)
       static::updated(function ($aspirasi) {
           if ($aspirasi->isDirty('status')) {
               self::createAutoKomentar($aspirasi);
           }
       });
   }

   private static function createAutoKomentar($aspirasi)
   {
       $statusText = match($aspirasi->status) {
           'proses'  => 'Sedang Diproses/Diperbaiki',
           'selesai' => 'Selesai',
           'menunggu'=> 'Menunggu',
           default   => ucfirst($aspirasi->status)
       };

       \App\Models\KomentarLaporan::create([
           'laporan_id'  => $aspirasi->laporan_id,
           'sender_type' => $aspirasi->responder_type ?? 'admin',
           // Default ID fallback misal jika tidak ada guard aktif di cronjob (contoh: 1).
           // Tapi karena create/update ini dari Controller Admin/Superadmin, auth guard biasanya aktif.
           'sender_id'   => $aspirasi->responder_id ?? 1,
           'pesan'       => "Sistem: Status laporan Anda telah diperbarui menjadi **{$statusText}**.",
       ]);
   }
}
