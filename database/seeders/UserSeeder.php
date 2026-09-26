<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'email'    => 'ptbumimayavaluta@gmail.com',
            'password' => Hash::make('bmex2026'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Abdullah Suyono',
            'email'    => 'yon212yon@gmail.com',
            'password' => Hash::make('bmex2026'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Umar Hidayat',
            'email'    => 'umarhidaayat@gmail.com',
            'password' => Hash::make('bmex2026'),
            'role'     => 'kasir',
        ]);
    }
}