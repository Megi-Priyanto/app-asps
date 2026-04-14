<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
