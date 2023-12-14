<?php

namespace App\Http\Controllers;

use App\Models\SaleDetail;
use Illuminate\Http\Request;
use App\Models\ManajemenStok;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ManajemenStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // // get last week data 
        // $startWeek = Carbon::now()->subWeek()->startOfWeek(); 
        // $endWeek   = Carbon::now()->subWeek()->endOfWeek();
        // echo "Awal Minggu: $startWeek dan akhir Minggu: $endWeek";



        // $weeklySales = DB::table('sale_details')
        //     ->select(DB::raw('YEAR(created_at) AS tahun, MONTH(created_at) AS bulan, idProduk,
        //              FLOOR((DAY(created_at) - 1) / 8) + 1 AS minggu_ke, 
        //              SUM(quantity) AS total_penjualan'))
        //     ->groupBy('idProduk', 'tahun', 'bulan', 'minggu_ke')
        //     ->orderBy('idProduk', 'asc')
        //     ->orderBy('tahun', 'asc')
        //     ->orderBy('bulan', 'asc')
        //     ->orderBy('minggu_ke', 'asc')
        //     ->get();

        $data = SaleDetail::selectRaw('products.name as name, SUM(sale_details.quantity) as max_quantity, AVG(sale_details.quantity) as AVG_quantity')
            ->whereBetween('sale_details.created_at', ['2023-10-16 00:00:00', '2023-10-24 00:00:00'])
            ->groupBy('idProduk')
            ->join('products', 'sale_details.idProduk', '=', 'products.idproduct')
            ->get();

        foreach ($data as $item) {
            $safetystock = ($item->max_quantity - $item->AVG_quantity) * 1;
            $maximumStock = 2 * ($item->AVG_quantity * 1) + $safetystock;
            $minimum = ($item->AVG_quantity * 1) + $safetystock;
            echo ("Savety Maximum Penjualan " . $item->name . " = " . $item->max_quantity . "<br>");
            echo ("Savety rata-rata Penjualan " . $item->name . " = " . $item->AVG_quantity . "<br>");
            echo ("Savety Stock ID_Produk " . $item->name . " = " . $safetystock . "<br>");
            echo ("Minimum Stock ID_Produk " . $item->name . " = " . $minimum . "<br>");
            echo ("Maximum Stock ID_Produk " . $item->name . " = " . $maximumStock . "<br><br>");
        }







        // // Tampilkan hasil
        // foreach ($weeklySales as $sale) {
        //     echo "IDProduk: {$sale->idProduk} Tahun: {$sale->tahun}, Bulan: {$sale->bulan}, Minggu ke: {$sale->minggu_ke}, Total Penjualan: {$sale->total_penjualan}<br>";
        // }
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
    public function show(ManajemenStok $manajemenStok)
    {
        //
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
