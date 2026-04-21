<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $fillable = ['nama_lokasi'];

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

    public function kategoriAspirasis()
    {
        return $this->hasMany(KategoriAspirasi::class);
    }

    public function kategoriBarangs()
    {
        return $this->belongsToMany(KategoriBarang::class, 'kategori_barang_lokasi')->withTimestamps();
    }
}
