<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Json;
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
        $totalMape = 0;
        $totalPredictions = 0;
        $result = [];
        $result["data"] = [];

        $produkIds = SaleDetail::pluck('idProduk')->unique();
        $startDate = now()->subYear()->startOfYear();
        $endDate = now()->subWeek()->endOfWeek();

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
            ->where('idProduk', $id)
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
                    'idProduk' => $id,
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

        // Hitung rata-rata MAPE
        if ($totalPredictions > 0) {
            $averageMape = round(abs($totalMape / $totalPredictions), 0);
        } else {
            $averageMape = 0;
        }
        // $averageMape = round(abs($totalMape / $totalPredictions), 0);
        // dd($result);

        $result['average_mape'] = $averageMape;
        $prediction = $this->prediction($id);



        return view('layouts.admin.Forecast.forecatdetail', ['id' => $id, 'data' => $result, 'average_mape' => $averageMape, 'prediksi' => $prediction]);
    }

    public function prediction($id)
    {
        $totalMape = 0;
        $totalPredictions = 0;
        $result = [];
        $result["data"] = [];

        $produkIds = SaleDetail::pluck('idProduk')->unique();
        $startDate = now()->subYear()->startOfYear();
        $endDate = now()->subWeek()->endOfWeek();

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
            ->where('idProduk', $id)
            ->get();

        $totalWeeks = $weeklySales->count();

        if ($totalWeeks > 8) {

            $productResult = [];

            $x = [];
            $y = [];
            $Xy = [];

            $selectedWeeks = $weeklySales->slice(0, $totalWeeks);

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

                $awalminggu1 = Carbon::now()->startOfWeek()->addWeek()->format('d-m-Y');
                $akhirminggu1 = Carbon::now()->endOfWeek()->addWeek()->format('d-m-Y');
                $minggu1 = round(($a + ($b * ($ramal + 2))));

                $awalminggu2 = Carbon::now()->startOfWeek()->addWeek(2)->format('d-m-Y');
                $akhirminggu2 = Carbon::now()->endOfWeek()->addWeek(2)->format('d-m-Y');
                $minggu2 = round(($a + ($b * ($ramal + 4))));

                $awalminggu3 = Carbon::now()->startOfWeek()->addWeek(3)->format('d-m-Y');
                $akhirminggu3 = Carbon::now()->endOfWeek()->addWeek(3)->format('d-m-Y');
                $minggu3 = round(($a + ($b * ($ramal + 6))));
                $product_name = $weeklySales[0]->product_name;
            } else {
                $awalminggu1 = Carbon::now()->startOfWeek()->addWeek()->format('d-m-Y');
                $akhirminggu1 = Carbon::now()->endOfWeek()->addWeek()->format('d-m-Y');
                $minggu1 = round(($a + ($b * ($ramal + 1))));

                $awalminggu2 = Carbon::now()->startOfWeek()->addWeek(2)->format('d-m-Y');
                $akhirminggu2 = Carbon::now()->endOfWeek()->addWeek(2)->format('d-m-Y');
                $minggu2 = round(($a + ($b * ($ramal + 2))));

                $awalminggu3 = Carbon::now()->startOfWeek()->addWeek(3)->format('d-m-Y');
                $akhirminggu3 = Carbon::now()->endOfWeek()->addWeek(3)->format('d-m-Y');
                $minggu3 = round(($a + ($b * ($ramal + 3))));
                $product_name = $weeklySales[0]->product_name;
            }

            // Tambahkan hasil perhitungan untuk setiap 8 minggu ke dalam array productResult
            $productResult = [
                'nameProduk' => $product_name,
                'idProduk' => $id,
                'awalminggu1' => $awalminggu1,
                'akhirminggu1' => $akhirminggu1,
                'minggu1' => $minggu1,
                'awalminggu2' => $awalminggu2,
                'akhirminggu2' => $akhirminggu2,
                'minggu2' => $minggu2,
                'awalminggu3' => $awalminggu3,
                'akhirminggu3' => $akhirminggu3,
                'minggu3' => $minggu3
            ];

            array_push($result['data'], $productResult);




            $x = [];
        }

        return $result['data'];
    }

    public function getdata($id)
    {

        $totalMape = 0;
        $totalPredictions = 0;
        $result = [];
        $result["data"] = [];
        $predictions = [];
        $weeks = [];
        $actuals = [];

        $produkIds = SaleDetail::pluck('idProduk')->unique();
        $startDate = now()->subYear()->startOfYear();
        $endDate = now()->subWeek()->endOfWeek();

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
            ->where('idProduk', $id)
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
                $actuals[] = intval($actual);
                $actualweeks = $weeklySales[$i + 8]->minggu_ke;
                $weeks[] = $actualweeks;


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
                } else {
                    $c = round(($a + ($b * ($ramal + 1))));
                }

                $predictions[] = $c;

                // Tambahkan hasil perhitungan untuk setiap 8 minggu ke dalam array productResult



            }


            $x = [];
        }
        return response()->json(['predictions' => $predictions, 'actuals' => $actuals, 'weeks' => $weeks], 200);
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
