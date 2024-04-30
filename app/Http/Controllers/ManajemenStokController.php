<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use App\Models\ManajemenStok;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

class ManajemenStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cabang = Cabang::all();
        $tanggalPertamaMingguLalu = Carbon::now()->startOfWeek();
        $tanggalTerakhirMingguLalu = Carbon::now()->endOfWeek();
        $products = Product::all();
        $processedData = [];
        $processedData2 = [];

        foreach ($products as $product) {
            $data = SaleDetail::selectRaw('sale_details.idProduk, SUM(sale_details.quantity)as quantity, products.name as name')
                ->whereBetween('sale_details.created_at', ['2023-10-23', '2023-10-28'])
                ->join('products', 'sale_details.idProduk', '=', 'products.idproduct')
                ->where('sale_details.idProduk', $product->idProduct)
                ->groupByRaw('DAYOFWEEK(sale_details.created_at), sale_details.idProduk, products.name')
                ->get();
            $quantities = $data->pluck('quantity');
            $totalPermintaanSebelumnya = $quantities->sum();
            $maximumPermintaan = $quantities->max();
            $mean = $quantities->avg();
            $safetystock = ($maximumPermintaan - $mean) * 3;
            $maximumStock = 2 * ($mean * 3) + $safetystock;
            $minimumStock = ($mean * 3) + $safetystock;
            $pemesananKembali = round($maximumStock - $minimumStock);


            $startDate = now()->subYear()->startOfYear();
            $endDate = now()->subWeek()->endOfWeek();

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
                ->join('sales', 'sale_details.idSales', '=', 'sales.idSales')
                ->whereBetween('sale_details.created_at', [$startDate, $endDate])
                ->groupBy('minggu_ke', 'idProduk')
                ->having('jumlah_hari_dalam_seminggu', '>=', 4) // Hanya data dengan minimal 3 hari dalam seminggu yang akan diproses
                ->orderBy('minggu_ke', 'asc')
                ->where('idProduk', $product->idProduct)
                ->get();

            $totalWeeks = $weeklySales->count();
            if ($totalWeeks > 8) {
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
                    $minggu1 = round(($a + ($b * ($ramal + 2))));
                } else {
                    $minggu1 = round(($a + ($b * ($ramal + 1))));
                }
                $x = [];
                $y = [];
                $Xy = [];
            } else {
                $minggu1 = 0;
            }


            $processedData[] = (object)[
                'name' => $product->name,
                'totalPermintaanSebelumnya' => $totalPermintaanSebelumnya,
                'AVG_quantity' => $mean,
                'safetystock' => $safetystock,
                'minimumStock' => $minimumStock,
                'maximumStock' => $maximumStock,
                'rop' => $pemesananKembali,
                'minggu1' => $minggu1
            ];
        }


        $data2 = DB::table('sale_details as sd')
            ->join('receipts as r', 'sd.idProduk', '=', 'r.idProduct')
            ->join('bahan_bakus as bb', 'bb.idBahan', '=', 'r.idBahan')
            ->whereBetween('sd.created_at', ['2023-10-23', '2023-10-28'])
            ->select(
                'bb.name as bahan_baku',
                DB::raw('SUM(sd.quantity * r.quantity) as total_penggunaan')
            )
            ->groupBy('bahan_baku', DB::raw('DAYOFWEEK(sd.created_at)'))
            ->get();
        // dd($data2);
        $uniqueBahanBaku = $data2->pluck('bahan_baku')->unique();
        foreach ($uniqueBahanBaku as $bahanbaku) {
            $quantities = $data2->where('bahan_baku', $bahanbaku)->pluck('total_penggunaan');
            // Periksa apakah $quantities tidak kosong sebelum melakukan operasi
            if ($quantities->isNotEmpty()) {
                // Hitung total penggunaan sebelumnya
                $totalPermintaanSebelumnya = $quantities->sum();
                // Hitung rata-rata penggunaan
                $mean = $quantities->avg();
                // Hitung maksimum penggunaan
                $maximumPermintaan = $quantities->max();
                $safetystock = ($maximumPermintaan - $mean) * 3;
                $maximumStock = 2 * ($mean * 3) + $safetystock;
                $minimumStock = ($mean * 3) + $safetystock;
                $pemesananKembali = round($maximumStock - $minimumStock);

                $weeklySales = SaleDetail::select(
                    DB::raw('YEARWEEK(sale_details.created_at) AS minggu_ke'),
                    'bahan_bakus.name AS bahan_baku',
                    DB::raw('SUM(sale_details.quantity*receipts.quantity) AS total_quantity'),
                    DB::raw('COUNT(DISTINCT DAYOFWEEK(sale_details.created_at)) AS jumlah_hari_dalam_seminggu'),
                )
                    ->join('receipts', 'sale_details.idProduk', '=', 'receipts.idProduct')
                    ->join('bahan_bakus', 'bahan_bakus.idBahan', '=', 'receipts.idBahan')
                    ->join('products', 'sale_details.idProduk', '=', 'products.idProduct')
                    ->join('sales', 'sale_details.idSales', '=', 'sales.idSales')
                    ->whereBetween('sale_details.created_at', [
                        $startDate, $endDate
                    ])
                    ->groupBy('minggu_ke', 'bahan_baku')
                    ->having('jumlah_hari_dalam_seminggu', '>=', 4) // Hanya data dengan minimal 3 hari dalam seminggu yang akan diproses
                    ->orderBy('minggu_ke', 'asc')
                    ->orderBy('minggu_ke', 'asc')
                    ->where('bahan_bakus.name', $bahanbaku)
                    ->get();

                $totalWeeks = $weeklySales->count();
                if ($totalWeeks > 0) {
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

                    if (
                        $numX % 2 == 0
                    ) {
                        $minggu1 = round(($a + ($b * ($ramal + 2))));
                    } else {
                        $minggu1 = round(($a + ($b * ($ramal + 1))));
                    }
                    $x = [];
                    $y = [];
                    $Xy = [];
                } else {
                    $minggu1 = 0;
                }

                // Tambahkan data yang diproses ke dalam array
                $processedData2[] = (object)[
                    'name' => $bahanbaku,
                    'totalPermintaanSebelumnya' => $totalPermintaanSebelumnya,
                    'AVG_quantity' => $mean,
                    'maximumpermintaan' => $maximumPermintaan,
                    'safetystock' => $safetystock,
                    'minimumStock' => $minimumStock,
                    'maximumStock' => $maximumStock,
                    'rop' => $pemesananKembali,
                    'minggu1' => $minggu1,
                ];
            }
        }


        // $data2  = DB::table('sale_details as sd')
        //     ->join('receipts as r', 'sd.idProduk', '=', 'r.idProduct')
        //     ->join('bahan_bakus as bb', 'bb.idBahan', '=', 'r.idBahan')
        //     ->join('sales as s', 's.idSales', '=', 'sd.idSales')
        //     ->join('cabangs as c', 'c.idCabang', '=', 's.idCabang')
        //     ->whereBetween('sd.created_at', ['2023-10-23', '2023-10-28'])
        //     ->groupBy('bb.name', 'c.name')
        //     ->select(
        //         'bb.name',
        //         'c.name as cabang',
        //         DB::raw('SUM(sd.quantity * r.quantity) as total_quantity'),
        //     )
        //     ->get();


        // $processedData2 = [];
        // foreach ($data2 as $item) {
        //     $AVG_quantity = $item->total_quantity / 7;
        //     $safetystock = ($item->total_quantity - $AVG_quantity) * 1;
        //     $maximumStock = 2 * ($AVG_quantity * 1) + $safetystock;
        //     $minimumStock = ($AVG_quantity * 1) + $safetystock;
        //     $processedData2[] = (object)[
        //         'name' => $item->name,
        //         'cabang' => $item->cabang,
        //         'total_quantity' => $item->total_quantity,
        //         'AVG_quantity' => $AVG_quantity,
        //         'safetystock' => round($safetystock),
        //         'minimumStock' => round($minimumStock),
        //         'maximumStock' => round($maximumStock),
        //     ];
        // }



        // dd($processedData);
        return view('layouts.admin.manajemenstok.index', ['processedData2' => $processedData2, 'processedData' => $processedData, 'tanggalAwal' => $tanggalPertamaMingguLalu, 'tanggalAkhir' => $tanggalTerakhirMingguLalu, 'cabang' => $cabang]);
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
    public function show($id, Request $request)
    {
        $processedData = [];
        $processedData2 = [];
        $idCabang = intval($request->idCabang);
        $tanggalPertamaMingguLalu = Carbon::now()->subWeek()->startOfWeek();
        $tanggalTerakhirMingguLalu = Carbon::now()->subWeek()->endOfWeek();
        $products = Product::all();
        $idCabang = intval($request->idCabang);
        foreach ($products as $product) {
            $data = SaleDetail::selectRaw('sale_details.idProduk, SUM(sale_details.quantity)as quantity, products.name as name')
                ->whereBetween('sale_details.created_at', ['2023-10-23', '2023-10-28'])
                ->join('products', 'sale_details.idProduk', '=', 'products.idproduct')
                ->join('sales', 'sale_details.idSales', '=', 'sales.idSales')
                ->join('cabangs', 'sales.idCabang', '=', 'cabangs.idCabang')
                ->where('sale_details.idProduk', $product->idProduct)
                ->where('cabangs.idCabang', $idCabang)
                ->groupByRaw('DAYOFWEEK(sale_details.created_at), sale_details.idProduk, products.name')
                ->get();
            $quantities = $data->pluck('quantity');
            $totalPermintaanSebelumnya = $quantities->sum();
            $maximumPermintaan = $quantities->max();
            $mean = $quantities->avg();
            $safetystock = ($maximumPermintaan - $mean) * 3;
            $maximumStock = 2 * ($mean * 3) + $safetystock;
            $minimumStock = ($mean * 3) + $safetystock;
            $pemesananKembali = round($maximumStock - $minimumStock);


            $startDate = now()->subYear()->startOfYear();
            $endDate = now()->subWeek()->endOfWeek();

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
                ->join('sales', 'sale_details.idSales', '=', 'sales.idSales')
                ->whereBetween('sale_details.created_at', [$startDate, $endDate])
                ->groupBy('minggu_ke', 'idProduk')
                ->having('jumlah_hari_dalam_seminggu', '>=', 4) // Hanya data dengan minimal 3 hari dalam seminggu yang akan diproses
                ->orderBy('minggu_ke', 'asc')
                ->where('idProduk', $product->idProduct)
                ->where('idCabang', $idCabang)
                ->get();

            $totalWeeks = $weeklySales->count();
            if ($totalWeeks > 8) {
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
                    $minggu1 = round(($a + ($b * ($ramal + 2))));
                } else {
                    $minggu1 = round(($a + ($b * ($ramal + 1))));
                }
                $x = [];
                $y = [];
                $Xy = [];
            } else {
                $minggu1 = 0;
            }

            $processedData[] = (object)[
                'name' => $product->name,
                'totalPermintaanSebelumnya' => $totalPermintaanSebelumnya,
                'minggu1' => $minggu1,
                'AVG_quantity' => $mean,
                'safetystock' => $safetystock,
                'minimumStock' => $minimumStock,
                'maximumStock' => $maximumStock,
                'rop' => $pemesananKembali,
            ];
        }






        $data2 = DB::table('sale_details as sd')
            ->join('receipts as r', 'sd.idProduk', '=', 'r.idProduct')
            ->join('bahan_bakus as bb', 'bb.idBahan', '=', 'r.idBahan')
            ->join('sales as s', 's.idSales', '=', 'sd.idSales')
            ->join('cabangs as c', 'c.idCabang', '=', 's.idCabang')
            ->whereBetween('sd.created_at', ['2023-10-23', '2023-10-28'])
            ->select(
                'bb.name as bahan_baku',
                // DB::raw('DATE(sd.created_at) as tanggal'),
                DB::raw('SUM(sd.quantity * r.quantity) as total_penggunaan')
            )
            ->groupBy('bahan_baku', DB::raw('DAYOFWEEK(sd.created_at)'))
            ->where('s.idCabang', $idCabang)
            ->get();
        $uniqueBahanBaku = $data2->pluck('bahan_baku')->unique();

        foreach ($uniqueBahanBaku as $bahanbaku) {
            $quantities = $data2->where('bahan_baku', $bahanbaku)->pluck('total_penggunaan');

            // Periksa apakah $quantities tidak kosong sebelum melakukan operasi
            if ($quantities->isNotEmpty()) {
                // Hitung total penggunaan sebelumnya
                $totalPermintaanSebelumnya = $quantities->sum();
                // Hitung rata-rata penggunaan
                $mean = $quantities->avg();
                // Hitung maksimum penggunaan
                $maximumPermintaan = $quantities->max();
                $safetystock = ($maximumPermintaan - $mean) * 3;
                $maximumStock = 2 * ($mean * 3) + $safetystock;
                $minimumStock = ($mean * 3) + $safetystock;
                $pemesananKembali = round($maximumStock - $minimumStock);

                $weeklySales = SaleDetail::select(
                    DB::raw('YEARWEEK(sale_details.created_at) AS minggu_ke'),
                    'bahan_bakus.name AS bahan_baku',
                    DB::raw('SUM(sale_details.quantity*receipts.quantity) AS total_quantity'),
                    DB::raw('COUNT(DISTINCT DAYOFWEEK(sale_details.created_at)) AS jumlah_hari_dalam_seminggu'),
                )
                    ->join('receipts', 'sale_details.idProduk', '=', 'receipts.idProduct')
                    ->join('bahan_bakus', 'bahan_bakus.idBahan', '=', 'receipts.idBahan')
                    ->join('products', 'sale_details.idProduk', '=', 'products.idProduct')
                    ->join('sales', 'sale_details.idSales', '=', 'sales.idSales')
                    ->join('cabangs', 'sales.idCabang', '=', 'cabangs.idCabang')
                    ->whereBetween('sale_details.created_at', [
                        $startDate, $endDate
                    ])
                    ->groupBy('minggu_ke', 'bahan_baku')
                    ->having('jumlah_hari_dalam_seminggu', '>=', 4) // Hanya data dengan minimal 3 hari dalam seminggu yang akan diproses
                    ->orderBy('minggu_ke', 'asc')
                    ->orderBy('minggu_ke', 'asc')
                    ->where('bahan_bakus.name', $bahanbaku)
                    ->where('cabangs.idCabang', $idCabang)
                    ->get();

                $totalWeeks = $weeklySales->count();
                if ($totalWeeks > 0) {
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

                    $xkuadrat = array_map(
                        function ($value) {
                            return $value * $value;
                        },
                        $x
                    );

                    $totXkuadrat = array_sum($xkuadrat);

                    for ($j = 0; $j < count($y); $j++) {
                        $Xy[] = $x[$j] * $y[$j];
                    }

                    $totXy = array_sum($Xy);

                    $a = round($totY / $jumlahY, 5);

                    $b = round($totXy / $totXkuadrat, 5);

                    $ramal = $x[$numX - 1];

                    if (
                        $numX % 2 == 0
                    ) {
                        $minggu1 = round(($a + ($b * ($ramal + 2))));
                    } else {
                        $minggu1 = round(($a + ($b * ($ramal + 1))));
                    }
                    $x = [];
                    $y = [];
                    $Xy = [];
                } else {
                    $minggu1 = 0;
                }


                // Tambahkan data yang diproses ke dalam array
                $processedData2[] = (object)[
                    'name' => $bahanbaku,
                    'totalPermintaanSebelumnya' => $totalPermintaanSebelumnya,
                    'AVG_quantity' => $mean,
                    'maximumpermintaan' => $maximumPermintaan,
                    'safetystock' => $safetystock,
                    'minimumStock' => $minimumStock,
                    'maximumStock' => $maximumStock,
                    'minggu1' => $minggu1,
                    'rop' => $pemesananKembali,
                ];
            }
        }


        return view('layouts.admin.manajemenstok.indexdetail', ['tanggalAwal' => $tanggalPertamaMingguLalu, 'tanggalAkhir' => $tanggalTerakhirMingguLalu, 'processedData2' => $processedData2, 'processedData' => $processedData, 'cabang' => $idCabang]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ManajemenStok $manajemenStok)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ManajemenStok $manajemenStok)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManajemenStok $manajemenStok)
    {
        //
    }
}
