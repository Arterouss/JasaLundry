<?php

namespace Database\Seeders;

use App\Models\Perfume;
use Illuminate\Database\Seeder;

class PerfumeSeeder extends Seeder
{
    public function run(): void
    {
        $perfumes = ['Lavender', 'Lily', 'Bubblegum', 'Vanila'];

        foreach ($perfumes as $name) {
            Perfume::create(['name' => $name]);
        }
    }
}