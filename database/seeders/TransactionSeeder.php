<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            [
                'idTransaction' => 1,
                'name' => 'sale',
            ],
            [
                'idTransaction' => 2,
                'name' => 'purchase',
            ],
        ];
        Transaction::insert($transactions);
    }
}
