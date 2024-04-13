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
        $tanggalPertamaMingguLalu = Carbon::now()->subWeek()->startOfWeek();
        $tanggalTerakhirMingguLalu = Carbon::now()->subWeek()->endOfWeek();
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
            // Menghitung rata-rata
            $mean = $quantities->avg();
            // Hitung selisih kuadrat dari masing-masing data dengan rata-rata
            // $diffSquared = $quantities->map(function ($quantity) use ($mean) {
            //     return pow($quantity - $mean, 2);
            // });
            // // Hitung rata-rata selisih kuadrat
            // $meanDiffSquared = $diffSquared->avg();
            // // Hitung deviasi standar (akar kuadrat dari rata-rata selisih kuadrat)
            // $stdev = round(sqrt($meanDiffSquared), 0);
            // $safetystock = round(1.645 * $stdev * sqrt(2));
            $safetystock = ($maximumPermintaan - $mean) * 3;
            $maximumStock = 2 * ($mean * 3) + $safetystock;
            $minimumStock = ($mean * 3) + $safetystock;
            $pemesananKembali = round($maximumStock - $minimumStock);
            $processedData[] = (object)[
                'name' => $product->name,
                'totalPermintaanSebelumnya' => $totalPermintaanSebelumnya,
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
                ->where('sale_details.idProduk', $product->idProduct)
                ->groupByRaw('DAYOFWEEK(sale_details.created_at), sale_details.idProduk, products.name')
                ->get();
            $quantities = $data->pluck('quantity');
            $totalPermintaanSebelumnya = $quantities->sum();
            $maximumPermintaan = $quantities->max();
            // Menghitung rata-rata
            $mean = $quantities->avg();
            // Hitung selisih kuadrat dari masing-masing data dengan rata-rata
            // $diffSquared = $quantities->map(function ($quantity) use ($mean) {
            //     return pow($quantity - $mean, 2);
            // });
            // // Hitung rata-rata selisih kuadrat
            // $meanDiffSquared = $diffSquared->avg();
            // // Hitung deviasi standar (akar kuadrat dari rata-rata selisih kuadrat)
            // $stdev = round(sqrt($meanDiffSquared), 0);
            // $safetystock = round(1.645 * $stdev * sqrt(2));
            $safetystock = ($maximumPermintaan - $mean) * 3;
            $maximumStock = 2 * ($mean * 3) + $safetystock;
            $minimumStock = ($mean * 3) + $safetystock;
            $pemesananKembali = round($maximumStock - $minimumStock);
            $processedData[] = (object)[
                'name' => $product->name,
                'totalPermintaanSebelumnya' => $totalPermintaanSebelumnya,
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
