<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Siswa extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['nis', 'nama', 'kelas', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    protected function nama(): Attribute
    {
        return Attribute::make(
            set: fn($value) => ucwords(strtolower(trim($value)))
        );
    }

    protected function kelas(): Attribute
    {
        return Attribute::make(
            set: fn($value) => strtoupper(trim($value))
        );
    }

    // Relasi lama
    public function laporan()
    {
        return $this->hasMany(LaporanPengaduan::class);
    }

    // Relasi polymorphic
    public function laporanSebagaiReporter()
    {
        return $this->morphMany(LaporanPengaduan::class, 'reporter');
    }
}