<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SuperAdmin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'super_admins';

    protected $fillable = [
        'nama',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function nama(): Attribute
    {
        return Attribute::make(
            set: fn($value) => ucwords(strtolower(trim($value))),
        );
    }

    public function aspirasis()
    {
        return $this->morphMany(Aspirasi::class, 'responder');
    }

    public function isSuperAdmin()
    {
        return true;
    }
}
