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
            ], [
                'idBahan' => 4,
                'name' => 'Tortila jumbo',
                'slug' => 'tortila-jumbo',
            ],
            [
                'idBahan' => 5,
                'name' => 'Sosis',
                'slug' => 'sosis',
            ],
            [
                'idBahan' => 6,
                'name' => 'Keju',
                'slug' => 'keju',
            ],
            [
                'idBahan' => 7,
                'name' => 'Telur',
                'slug' => 'telur',
            ],
            [
                'idBahan' => 8,
                'name' => 'Kentang',
                'slug' => 'Kentang',
            ],
            [
                'idBahan' => 9,
                'name' => 'Daging Kebab Sapi',
                'slug' => 'daging-kebab-sapi',
            ],
            [
                'idBahan' => 10,
                'name' => 'Saus Tomat',
                'slug' => 'saus-tomat',
            ],
            [
                'idBahan' => 11,
                'name' => 'Saus pedas',
                'slug' => 'saus-pedas',
            ],
            [
                'idBahan' => 12,
                'name' => 'Mayonaise',
                'slug' => 'mayonaise',
            ],
            [
                'idBahan' => 13,
                'name' => 'Bumbu',
                'slug' => 'bumbu',
            ],

        ];
        BahanBaku::insert($bahanbaku);
    }
}
