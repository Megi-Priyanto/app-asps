<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Kategori;
use App\Models\Siswa;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LaporanPengaduan>
 */
class LaporanPengaduanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'siswa_id' => \App\Models\Siswa::factory(),
            'kategori_id' => \App\Models\Kategori::factory(),
            'ket' => $this->faker->sentence(12),
            'lokasi' => $this->faker->randomElement([
                'Ruang Kelas',
                'Laboratorium',
                'Perpustakaan',
                'Toilet',
                'Lapangan',
            ]),
        ];
    }
}
