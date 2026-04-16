<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
   use HasFactory, Notifiable;

   protected $fillable = [
      'nama',
      'username',
      'foto',
      'password',
      'lokasi_id',
   ];

   protected function nama(): Attribute
   {
      return Attribute::make(
         set: fn($value) => ucwords(strtolower(trim($value))),
      );
   }

   protected $hidden = [
      'password',
      'remember_token',
   ];

   public function aspirasis()
   {
      return $this->morphMany(Aspirasi::class, 'responder');
   }

   public function isSuperAdmin()
   {
       return false;
   }

   public function lokasi()
   {
       return $this->belongsTo(Lokasi::class);
   }
}
