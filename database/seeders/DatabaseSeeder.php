<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\SuperAdmin;
use App\Models\KategoriAspirasi;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Super Admin
        SuperAdmin::create([
            'nama' => 'Super Administrator',
            'username' => 'superadmin',
            'password' => bcrypt('password'),
        ]);

    }
}
