<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role jika belum ada
        $adminRole = Role::firstOrCreate(['nama' => 'admin']);
        $userRole = Role::firstOrCreate(['nama' => 'user']);

        // Buat akun admin default
        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'id_role' => $adminRole->id
        ]);

        // Buat akun user default
        User::create([
            'nama' => 'Regular User',
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'id_role' => $userRole->id
        ]);
        User::create([
            'nama' => 'ucup',
            'username' => 'ucup',
            'email' => 'ucup@gmail.com',
            'password' => Hash::make('password'),
            'id_role' => $userRole->id
        ]);
    }
}
