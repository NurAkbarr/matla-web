<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'sadmin@matla.id',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'super_admin'
        ]);

        \App\Models\User::create([
            'name' => 'Admin Kampus',
            'email' => 'admin@matla.id',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin'
        ]);

        \App\Models\User::create([
            'name' => 'Dosen Matla',
            'email' => 'dosen@matla.id',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'dosen'
        ]);

        \App\Models\User::create([
            'name' => 'Mahasiswa Matla',
            'email' => 'mahasiswa@matla.id',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'mahasiswa'
        ]);
    }

}
