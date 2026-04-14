<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\SuperAdmin;
use App\Models\Kategori;

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

        // Admin Biasa
        Admin::create([
            'nama' => 'Pramu Bakti / TATA USAHA',
            'username' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // Kategori
        $kategoris = [
            'Fasilitas',
            'Keamanan',
            'Sarana umum',
            'Lab',
            'Kelas',
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create([
                'nama_kategori' => $kategori,
            ]);
        }
    }
}
