<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class OfficerSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'officer'],
            [
                'password' => 'password123',
                'role' => 'officer',
            ],
        );
    }
}
