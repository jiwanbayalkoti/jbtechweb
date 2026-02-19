<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@jbtechnepal.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@jbtechnepal.com',
                'password' => Hash::make('password'),
                'is_super_admin' => true,
            ]
        );
    }
}
