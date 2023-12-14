<?php

namespace Database\Seeders;

use App\Models\BahanBaku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BahanBakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bahanbaku = [
            [
                'idBahan' => 1,
                'name' => 'Tortila Kecil',
                'slug' => 'tortila-kecil',
            ],
            [
                'idBahan' => 2,
                'name' => 'Tortila Medium',
                'slug' => 'tortila-medium',
            ],
            [
                'idBahan' => 3,
                'name' => 'Tortila Besar',
                'slug' => 'tortila-besar',
            ],
            [
                'idBahan' => 4,
                'name' => 'Saus Tomat',
                'slug' => 'saus-tomat',
            ],

        ];
        BahanBaku::insert($bahanbaku);
    }
}
