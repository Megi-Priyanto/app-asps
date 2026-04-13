<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Admin;
use App\Models\LaporanPengaduan;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aspirasi>
 */
class AspirasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'laporan_id' => \App\Models\LaporanPengaduan::factory(),
            'admin_id' => \App\Models\Admin::factory(),
            'status' => $this->faker->randomElement([
                'menunggu',
                'proses',
                'selesai',
            ]),
            'feedback' => $this->faker->optional()->numberBetween(1, 5),
        ];
    }
}
