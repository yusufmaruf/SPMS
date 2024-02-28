<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = [
            [
                'idProduct' => 1,
                'name' => 'Kebab Ciprets',
                'description' => 'ini kebab ciprets',
                'slug' => 'kebab-ciprets',
                'image' => 'kebab-ciprets.png',
                'price' => 7500,
            ],
            [
                'idProduct' => 2,
                'name' => 'Kebab mini',
                'description' => 'ini kebab mini',
                'slug' => 'kebab-mini',
                'image' => 'kebab-mini.png',
                'price' => 11000,
            ],
            [
                'idProduct' => 3,
                'name' => 'Kebab Telur',
                'description' => 'ini kebab Telur',
                'slug' => 'kebab-telur',
                'image' => 'kebab-telur.png',
                'price' => 12000,
            ],
            [
                'idProduct' => 4,
                'name' => 'Kebab Sosis',
                'description' => 'ini kebab Regular',
                'slug' => 'kebab-regular',
                'image' => 'kebab-regular.png',
                'price' => 12000,
            ],
            [
                'idProduct' => 5,
                'name' => 'Kebab Regular',
                'description' => 'ini kebab Regular',
                'slug' => 'kebab-regular',
                'image' => 'kebab-regular.png',
                'price' => 14000,
            ],
            [
                'idProduct' => 6,
                'name' => 'Kebab Spesial Keju',
                'description' => 'ini kebab Spesial Keju',
                'slug' => 'kebab-Spesial-Keju',
                'image' => 'kebab-Spesial-Keju.png',
                'price' => 17500,
            ],
            [
                'idProduct' => 7,
                'name' => 'Kebab Spesial Full Beef',
                'description' => 'ini kebab Spesial Full Beef',
                'slug' => 'kebab-Spesial-Full-Beef',
                'image' => 'kebab-Spesial-Full-Beef.png',
                'price' => 20000,
            ],
            [
                'idProduct' => 8,
                'name' => 'Kebab Spesial Telur',
                'description' => 'ini kebab Spesial Telur',
                'slug' => 'kebab-Spesial-Full-telur',
                'image' => 'kebab-Spesial-Full-telur.png',
                'price' => 25000,
            ],
        ];
        Product::insert($product);
    }
}
