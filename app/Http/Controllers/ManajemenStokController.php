<?php

namespace App\Http\Controllers;

use App\Models\SaleDetail;
use Illuminate\Http\Request;
use App\Models\ManajemenStok;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ManajemenStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tanggalPertamaMingguLalu = Carbon::now()->subWeek()->startOfWeek();
        $tanggalTerakhirMingguLalu = Carbon::now()->subWeek()->endOfWeek();
        $data = SaleDetail::selectRaw('idProduk, products.name as name, SUM(sale_details.quantity) as max_quantity')
            ->whereBetween('sale_details.created_at', ['2023-10-21', '2023-10-28'])
            ->groupBy('idProduk')
            ->join('products', 'sale_details.idProduk', '=', 'products.idproduct')
            ->get();
        $processedData = [];
        foreach ($data as $item) {
            $AVG_quantity = $item->max_quantity / 7;
            $safetystock = ($item->max_quantity - $AVG_quantity) * 1;
            $maximumStock = 2 * ($AVG_quantity * 1) + $safetystock;
            $minimumStock = ($AVG_quantity * 1) + $safetystock;
            $processedData[] = (object)[
                'name' => $item->name,
                'max_quantity' => $item->max_quantity,
                'AVG_quantity' => $AVG_quantity,
                'safetystock' => $safetystock,
                'minimumStock' => $minimumStock,
                'maximumStock' => $maximumStock,
            ];
        }

        $data2  = DB::table('sale_details as sd')
            ->join('receipts as r', 'sd.idProduk', '=', 'r.idProduct')
            ->join('bahan_bakus as bb', 'bb.idBahan', '=', 'r.idBahan')
            ->join('sales as s', 's.idSales', '=', 'sd.idSales')
            ->join('cabangs as c', 'c.idCabang', '=', 's.idCabang')
            ->whereBetween('sd.created_at', ['2023-10-21', '2023-10-28'])
            ->groupBy('bb.name', 'c.name')
            ->select(
                'bb.name',
                'c.name as cabang',
                DB::raw('SUM(sd.quantity * r.quantity) as total_quantity'),
            )
            ->get();


        $processedData2 = [];
        foreach ($data2 as $item) {
            $AVG_quantity = $item->total_quantity / 7;
            $safetystock = ($item->total_quantity - $AVG_quantity) * 1;
            $maximumStock = 2 * ($AVG_quantity * 1) + $safetystock;
            $minimumStock = ($AVG_quantity * 1) + $safetystock;
            $processedData2[] = (object)[
                'name' => $item->name,
                'cabang' => $item->cabang,
                'total_quantity' => $item->total_quantity,
                'AVG_quantity' => $AVG_quantity,
                'safetystock' => round($safetystock),
                'minimumStock' => round($minimumStock),
                'maximumStock' => round($maximumStock),
            ];
        }



        // dd($processedData);
        return view('layouts.admin.manajemenstok.index', ['processedData2' => $processedData2, 'processedData' => $processedData, 'tanggalAwal' => $tanggalPertamaMingguLalu, 'tanggalAkhir' => $tanggalTerakhirMingguLalu]);
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
