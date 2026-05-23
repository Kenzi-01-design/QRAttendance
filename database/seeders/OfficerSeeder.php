<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class OfficerSeeder extends Seeder
{
    public function run(): void
    {
        $password = (string) env('OFFICER_PASSWORD');

        if ($password === '') {
            throw new RuntimeException('Set OFFICER_PASSWORD in .env before seeding.');
        }

        User::updateOrCreate(
            ['username' => 'officer'],
            [
                'password' => $password,
                'role' => 'officer',
            ],
        );
    }
}
