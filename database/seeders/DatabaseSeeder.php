<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin Laundry',
            'email' => 'admin@laundry.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Akun Pelanggan (Customer) untuk testing order
        User::create([
            'name' => 'Bayu Anggara',
            'email' => 'bayu@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->call([
        ServiceSeeder::class,
        PerfumeSeeder::class,
    ]);
    }
}
