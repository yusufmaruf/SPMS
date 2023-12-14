<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
                'idUser' => 1,
                'name' => 'manager',
                'email' => 'manager@manager',
                'password' => Hash::make('manager'),
                'role' => 'admin',
                'idCabang' => 1
            ],
            [
                'idUser' => 2,
                'name' => 'cabang1',
                'email' => 'cabang@cabang',
                'password' => Hash::make('cabang'),
                'role' => 'user',
                'idCabang' => 2
            ],
            [
                'idUser' => 3,
                'name' => 'cabang2',
                'email' => 'cabang2@cabang',
                'password' => Hash::make('cabang'),
                'role' => 'user',
                'idCabang' => 3
            ],
        ];
        User::insert($user);
    }
}
