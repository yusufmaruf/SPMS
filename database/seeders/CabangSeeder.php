<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = [
            [
                'idCabang' => 1,
                'name' => 'pusat',
                'slug' => 'pusat',
                'image' => 'pusa.png',
                'location' => "Jl. Raya Wiyung No. 1, Wiyung, Surabaya",
                'phone' => '08123456789',
                'open' => '08.00',
                'close' => '17.00'
            ],
            [
                'idCabang' => 2,
                'name' => 'Cabang Gor Tuban',
                'slug' => 'gor-tuban',
                'image' => 'cabang-wiyung.png',
                'location' => "Gor Tuban",
                'phone' => '08123456789',
                'open' => '08.00',
                'close' => '17.00'
            ],
            [
                'idCabang' => 3,
                'name' => 'Cabang Merakurak',
                'slug' => 'cabang-Merakurak',
                'image' => 'cabang-merakurak.png',
                'location' => "Cabang Merakurak",
                'phone' => '08123456789',
                'open' => '08.00',
                'close' => '17.00'
            ],
            [
                'idCabang' => 4,
                'name' => 'Cabang Bogorejo',
                'slug' => 'cabang-Bogorejo',
                'image' => 'cabang-bogorejo.png',
                'location' => "Cabang Bogorejo",
                'phone' => '08123456789',
                'open' => '08.00',
                'close' => '17.00'
            ],
            [
                'idCabang' => 5,
                'name' => 'Cabang Wiyung',
                'slug' => 'cabang-wiyung',
                'image' => 'cabang-wiyung.png',
                'location' => "Cabang Wiyung",
                'phone' => '08123456789',
                'open' => '08.00',
                'close' => '17.00'
            ],
        ];
        Cabang::insert($cabang);
    }
}
