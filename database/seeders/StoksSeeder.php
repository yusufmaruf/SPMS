<?php

namespace Database\Seeders;

use App\Models\Stok;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stok = [
            [
                'idStok' => 1,
                'idBahan' => 1,
                'idCabang' => 1,
                'jumlah' => 100,
            ],
            [
                'idStok' => 2,
                'idBahan' => 2,
                'idCabang' => 1,
                'jumlah' => 100,
            ],
        ];
        Stok::insert($stok);
    }
}
