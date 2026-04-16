<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KategoriAspirasi>
 */
class KategoriAspirasiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_kategori' => ucfirst($this->faker->unique()->word()),
        ];
    }
}
