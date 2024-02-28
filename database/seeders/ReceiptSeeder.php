<?php

namespace Database\Seeders;

use App\Models\Receipt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $receipt = [
            [
                'idReceipt' => 2,
                'idProduct' => 1,
                'idBahan' => 1,
                'Quantity' => 1,
            ],

        ];
        Receipt::insert($receipt);
    }
}
