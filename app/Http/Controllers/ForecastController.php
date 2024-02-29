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
        $weeklySales = SaleDetail::select(
            DB::raw('YEARWEEK(created_at) AS minggu_ke'),
            'idProduk',
            DB::raw('SUM(quantity) AS total_quantity'),
            DB::raw('DATE_ADD(MIN(created_at), INTERVAL(1-DAYOFWEEK(MIN(created_at))) DAY) AS tanggal_awal_minggu'),
            DB::raw('DATE_ADD(MAX(created_at), INTERVAL(7-DAYOFWEEK(MAX(created_at))) DAY) AS tanggal_akhir_minggu')
        )
            ->groupBy('minggu_ke', 'idProduk')
            ->orderBy('minggu_ke')
            ->where('idProduk', 1)
            ->get();
        $selectedWeeks = $weeklySales->slice(0, 8);
        $y = [];
        if ($selectedWeeks->count()  > 0) {
            foreach ($selectedWeeks as $sale) {
                $y[] = $sale->total_quantity;
            }
        }
        $x = [-7, -5, -3, -1, 1, 3, 5, 7];
        $totX = array_sum($x);
        $xkuadrat = [49, 25, 9, 1, 1, 9, 25, 49];
        $totXkuadrat = array_sum($xkuadrat);
        $totY = array_sum($y);
        $Xy = [];
        for ($i = 0; $i < count($x); $i++) {
            $Xy[] = $x[$i] * $y[$i];
        }
        $totXy = array_sum($Xy);
        $a = $totY / 8;
        $b = $totXy / $totXkuadrat;
        $c = ($a + ($b * 9));
        return response()->json($c);
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
