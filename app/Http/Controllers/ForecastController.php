<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        $totalMape = 0;
        $totalPredictions = 0;
        $result = [];
        $result["data"] = [];

        $product = Product::all();
        $produkIds = SaleDetail::pluck('idProduk')->unique();
        $startDate = now()->subYear()->startOfYear();
        $endDate = now()->subWeek()->endOfWeek();

        foreach ($produkIds as $idProduk) {


            $productResult = [];

            $weeklySales = SaleDetail::select(
                DB::raw('YEARWEEK(sale_details.created_at) AS minggu_ke'),
                'idProduk',
                'products.name AS product_name',
                DB::raw('SUM(quantity) AS total_quantity'),
                DB::raw('COUNT(DISTINCT DAYOFWEEK(sale_details.created_at)) AS jumlah_hari_dalam_seminggu'), // Menghitung total hari dalam seminggu
                DB::raw('DATE_ADD(MIN(sale_details.created_at), INTERVAL(1-DAYOFWEEK(MIN(sale_details.created_at))) DAY) AS tanggal_awal_minggu'),
                DB::raw('DATE_ADD(MAX(sale_details.created_at), INTERVAL(7-DAYOFWEEK(MAX(sale_details.created_at))) DAY) AS tanggal_akhir_minggu')
            )
                ->join('products', 'sale_details.idProduk', '=', 'products.idProduct')
                ->whereBetween('sale_details.created_at', [$startDate, $endDate])
                ->groupBy('minggu_ke', 'idProduk')
                ->having('jumlah_hari_dalam_seminggu', '>=', 4) // Hanya data dengan minimal 3 hari dalam seminggu yang akan diproses
                ->orderBy('minggu_ke', 'asc')
                ->where('idProduk', $idProduk)
                ->get();

            $totalWeeks = $weeklySales->count();

            if ($totalWeeks > 8) {

                $productResult = [];

                for ($i = 0; $i < $totalWeeks - 8; $i++) {

                    $x = [];
                    $y = [];
                    $Xy = [];

                    $selectedWeeks = $weeklySales->slice(0, 8 + $i);
                    $actual = $weeklySales[$i + 8]->total_quantity;
                    $actualweeks = $weeklySales[$i + 8]->minggu_ke;

                    foreach ($selectedWeeks as $sale) {
                        $y[] = $sale->total_quantity;
                    }

                    $jumlahY = count($y);
                    $totY = array_sum($y);


                    $numX = count($selectedWeeks);

                    if ($numX % 2 == 0) {
                        $start = - (($numX - 2) / 2) - ($numX / 2);
                        for ($k = 0; $k < $numX; $k++) {
                            $x[] = $start;
                            $start += 2;
                        }
                    } else {
                        $start = - (($numX - 1) / 2);
                        for ($k = 0; $k < $numX; $k++) {
                            $x[] = $start;
                            $start++;
                        }
                    };

                    $totX = array_sum($x);

                    $xkuadrat = array_map(function ($value) {
                        return $value * $value;
                    }, $x);

                    $totXkuadrat = array_sum($xkuadrat);

                    for ($j = 0; $j < count($y); $j++) {
                        $Xy[] = $x[$j] * $y[$j];
                    }

                    $totXy = array_sum($Xy);

                    $a = round($totY / $jumlahY, 5);

                    $b = round($totXy / $totXkuadrat, 5);

                    $ramal = $x[$numX - 1];

                    if ($numX % 2 == 0) {
                        $c = round(($a + ($b * ($ramal + 2))));

                        $coba = $ramal + 2;
                    } else {
                        $c = round(($a + ($b * ($ramal + 1))));
                        $coba = $ramal + 1;
                    }

                    $d = ($actual - $c) / $actual;

                    $mape =  abs(round($d * 100));

                    $product_name = $weeklySales[$i + 8]->product_name;
                    // Tambahkan hasil perhitungan untuk setiap 8 minggu ke dalam array productResult
                    $productResult = [
                        'minggu_ke' => $actualweeks,
                        'idProduk' => $idProduk,
                        'nameProduk' => $product_name,
                        'coba' => $coba,
                        'ramal' => $ramal,
                        'numx' => $numX,
                        'totXkuadrat' => $totXkuadrat,
                        'a' => $a,
                        'b' => $b,
                        'predicted' => $c,
                        'actual' => $actual,
                        'mape' => $mape
                    ];

                    array_push($result['data'], $productResult);
                    $totalMape += $mape;
                    $totalPredictions++;
                }



                $x = [];
            }
        }
        // Hitung rata-rata MAPE
        $averageMape = round(abs($totalMape / $totalPredictions), 0);
        // dd($result);

        $result['average_mape'] = $averageMape;
        // return response()->json($result);
        return view('layouts.admin.Forecast.prediksi', ['result' => $result, 'average_mape' => $averageMape, 'products' => $product]);
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
    public function show($id)
    {
        return view('layouts.admin.Forecast.forecatdetail', ['id' => $id]);
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
