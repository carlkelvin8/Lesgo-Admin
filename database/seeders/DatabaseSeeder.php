<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PartnerSeeder::class,
            ServiceSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
