<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Kategori;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin
        Admin::factory()->create([
            'nama' => 'Administrator',
            'username' => 'admin',
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
