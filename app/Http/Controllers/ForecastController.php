<?php

namespace App\Http\Controllers;

use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForecastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua ID produk yang ada di dalam database
        $produkIds = SaleDetail::pluck('idProduk')->unique();
        $startDate = now()->subYear()->startOfYear();
        $endDate = now()->endOfMonth();
        $x = [];
        // dd($startDate, $endDate);
        // Inisialisasi array untuk menyimpan hasil untuk setiap produk
        $result = [];
        $totalMape = 0; // Inisialisasi total MAPE
        $totalPredictions = 0;
        // Inisialisasi total prediksi
        // Loop melalui setiap ID produk
        foreach ($produkIds as $idProduk) {
            $weeklySales = SaleDetail::select(
                DB::raw('YEARWEEK(created_at) AS minggu_ke'),
                'idProduk',
                DB::raw('SUM(quantity) AS total_quantity'),
                DB::raw('COUNT(DISTINCT DAYOFWEEK(created_at)) AS jumlah_hari_dalam_seminggu'), // Menghitung total hari dalam seminggu
                DB::raw('DATE_ADD(MIN(created_at), INTERVAL(1-DAYOFWEEK(MIN(created_at))) DAY) AS tanggal_awal_minggu'),
                DB::raw('DATE_ADD(MAX(created_at), INTERVAL(7-DAYOFWEEK(MAX(created_at))) DAY) AS tanggal_akhir_minggu')
            )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('minggu_ke', 'idProduk')
                ->having('jumlah_hari_dalam_seminggu', '>=', 4) // Hanya data dengan minimal 3 hari dalam seminggu yang akan diproses
                ->orderBy('minggu_ke', 'asc')
                ->where('idProduk', $idProduk)
                ->get();
            // dd($weeklySales);
            $totalWeeks = $weeklySales->count();
            // Cek apakah ada data penjualan lebih dari 8 minggu

            if ($totalWeeks > 8) {
                // Inisialisasi array untuk menyimpan hasil perhitungan untuk setiap 8 minggu
                $productResult = [];
                // Loop untuk menghitung MAPE untuk setiap 8 minggu
                for ($i = 0; $i < $totalWeeks - 8; $i++) {
                    $selectedWeeks = $weeklySales->slice($i, 8);
                    $actual = $weeklySales[$i + 8]->total_quantity;
                    $actualweeks = $weeklySales[$i + 8]->minggu_ke;
                    $y = [];
                    foreach ($selectedWeeks as $sale) {
                        $y[] = $sale->total_quantity;
                    }
                    $x = [-7, -5, -3, -1, 1, 3, 5, 7];
                    // $numX = count($selectedWeeks);
                    // if ($numX % 2 == 0) {
                    //     $start = - (($numX - 2) / 2) - ($numX / 2);
                    //     for ($i = 0; $i < $numX; $i++) {
                    //         $x[] = $start;
                    //         $start += 2;
                    //     }
                    // } else {
                    //     $start = - (($numX - 1) / 2);
                    //     for ($i = 0; $i < $numX; $i++) {
                    //         $x[] = $start;
                    //         $start++;
                    //     }
                    // };

                    $totX = array_sum($x);
                    $xkuadrat = array_map(function ($value) {
                        return $value * $value;
                    }, $x);
                    $totXkuadrat = array_sum($xkuadrat);
                    $totY = array_sum($y);
                    $Xy = [];
                    for ($j = 0; $j < count($x); $j++) {
                        $Xy[] = $x[$j] * $y[$j];
                    }
                    $totXy = array_sum($Xy);
                    $a = $totY / 8;
                    $b = $totXy / $totXkuadrat;
                    $c = round(($a + ($b * 9)));
                    $d = abs(($actual - $c) / $actual);
                    $mape =  $d * 100;

                    // Tambahkan hasil perhitungan untuk setiap 8 minggu ke dalam array productResult
                    $productResult[] = [
                        'minggu_ke' => $actualweeks,
                        'idProduk' => $idProduk,
                        'x' => $x,
                        'xkuadrat' => $xkuadrat,
                        'totXkuadrat' => $totXkuadrat,
                        'xy' => $Xy,
                        'a' => $a,
                        'b' => $b,
                        'y' => $y,
                        'predicted' => $c,
                        'actual' => $actual,
                        'mape' => $mape
                    ];
                    $totalMape += $mape;
                    $totalPredictions++;
                }

                // Tambahkan hasil perhitungan untuk setiap 8 minggu dari produk tersebut ke dalam array result
                $result[$idProduk] = $productResult;
                $x = [];
            }
        }
        // Hitung rata-rata MAPE
        $averageMape = $totalMape / $totalPredictions;
        $result['average_mape'] = $averageMape;
        return response()->json($result);
    }
    public function prediction()
    {    // Loop melalui setiap ID produk

        $weeklySales = SaleDetail::select(
            DB::raw('YEARWEEK(created_at) AS minggu_ke'),
            'idProduk',
            DB::raw('SUM(quantity) AS total_quantity'),
            DB::raw('DATE_ADD(MIN(created_at), INTERVAL(1-DAYOFWEEK(MIN(created_at))) DAY) AS tanggal_awal_minggu'),
            DB::raw('DATE_ADD(MAX(created_at), INTERVAL(7-DAYOFWEEK(MAX(created_at))) DAY) AS tanggal_akhir_minggu')
        )
            ->groupBy('minggu_ke', 'idProduk')
            ->orderBy('minggu_ke', 'desc')
            ->where('idProduk', 1)
            ->limit(8)
            ->get();
        $totalWeeks = $weeklySales->count();
        // Mengembalikan hasil dalam bentuk JSON
        if ($totalWeeks = 7) {

            $y = [];
            foreach ($weeklySales as $sale) {
                $y[] = $sale->total_quantity;
            }
            $x = [7, 5, 3, 1, -1, -3, -5, -7];
            $totX = array_sum($x);
            $xkuadrat = [49, 25, 9, 1, 1, 9, 25, 49];
            $totXkuadrat = array_sum($xkuadrat);
            $totY = array_sum($y);
            $Xy = [];
            for ($j = 0; $j < count($x); $j++) {
                $Xy[] = $x[$j] * $y[$j];
            }
            $totXy = array_sum($Xy);
            $a = $totY / 8;
            $b = $totXy / $totXkuadrat;
            $c = ($a + ($b * 9));
            return response()->json($weeklySales);
        } else {
            return response()->json(null);
        }
    }





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
