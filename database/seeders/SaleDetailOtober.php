<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SaleDetailOtober extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = database_path('seeders/salesDetails.csv');
        $csv = array_map('str_getcsv', file($csvFile));
        $headers = $csv[0];

        foreach (array_slice($csv, 1) as $row) {
            // Validasi jumlah kolom
            if (count($row) === count($headers)) {
                DB::table('sale_details')->insert(array_combine($headers, $row));
            } else {
                // Tangani kesalahan atau log pesan jika diperlukan
                echo "Jumlah kolom tidak sesuai.\n";
            }
        }
    }
}
