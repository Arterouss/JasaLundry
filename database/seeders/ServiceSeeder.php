<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Cuci Kering',
                'price_per_kg' => 7000, // Misal harganya 7k
                'estimated_minutes' => 60,
            ],
            [
                'name' => 'Setrika',
                'price_per_kg' => 6000, // Misal harganya 6k
                'estimated_minutes' => 200,
            ],
            [
                'name' => 'Cuci + Setrika',
                'price_per_kg' => 10000, // Sesuai contohmu 10k
                'estimated_minutes' => 220,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}