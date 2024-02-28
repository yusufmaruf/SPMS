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
                'email' => 'manager@kebabsiabah',
                'password' => Hash::make('manager'),
                'role' => 'admin',
                'idCabang' => 1
            ],
            [
                'idUser' => 2,
                'name' => 'admin',
                'email' => 'admin@kebabsiabah',
                'password' => Hash::make('admin'),
                'role' => 'user',
                'idCabang' => 2
            ],
            [
                'idUser' => 3,
                'name' => 'Gor Tuban',
                'email' => 'cabanggor@kebabsiabah',
                'password' => Hash::make('cabang'),
                'role' => 'user',
                'idCabang' => 3
            ],
            [
                'idUser' => 4,
                'name' => 'Cabang Merakurak',
                'email' => 'cabangMerakurak@kebabsiabah',
                'password' => Hash::make('cabang'),
                'role' => 'user',
                'idCabang' => 4
            ],
            [
                'idUser' => 5,
                'name' => 'Cabang Bogorejo',
                'email' => 'cabangBogorejo@kebabsiabah',
                'password' => Hash::make('cabang'),
                'role' => 'user',
                'idCabang' => 5
            ],
            [
                'idUser' => 6,
                'name' => 'Cabang Wiyung',
                'email' => 'cabangwiyung@kebabsiabah',
                'password' => Hash::make('cabang'),
                'role' => 'user',
                'idCabang' => 6
            ],
        ];
        User::insert($user);
    }
}
