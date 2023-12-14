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
                'name' => 'Kebab Mini',
                'description' => 'ini kebab mini',
                'slug' => 'kebab-mini',
                'image' => 'kebab-mini.png',
                'price' => 10000,
            ],
            [
                'idProduct' => 2,
                'name' => 'Kebab Spesial',
                'description' => 'ini kebab Spesial',
                'slug' => 'kebab-spesial',
                'image' => 'kebab-spesial.png',
                'price' => 12000,
            ],
            [
                'idProduct' => 3,
                'name' => 'Kebab Telur',
                'description' => 'ini kebab Telur',
                'slug' => 'kebab-telur',
                'image' => 'kebab-telur.png',
                'price' => 11000,
            ],
        ];
        Product::insert($product);
    }
}
