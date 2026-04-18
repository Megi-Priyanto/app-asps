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
}
