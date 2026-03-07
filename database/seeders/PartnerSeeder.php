<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partnerUsers = User::where('role', 'partner')->get();

        foreach ($partnerUsers as $user) {
            Partner::factory()->active()->create([
                'user_id' => $user->id,
            ]);
        }

        Partner::factory()->active()->count(3)->create();
    }
}
