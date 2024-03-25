<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use App\Models\Cabang;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DahboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $awalBulan = Carbon::now()->startOfYear();
        $akhirBulan = Carbon::now();
        $totalProduk = Product::count();
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'manager') {
            $totalPenjualan = SaleDetail::whereBetween('created_at', [$awalBulan, $akhirBulan])->sum('quantity');
            $totalPegawai = User::count();
            $totalCabang = Cabang::where('idCabang', '!=', Auth::user()->idCabang)->count();
            if (Auth::user()->role == 'admin') {
            } else {
            }
        } else {
            $totalQuantityPenjualan = SaleDetail::whereBetween('created_at', [$awalBulan, $akhirBulan])
                ->whereHas('sale', function ($query) {
                    $query->where('idCabang', Auth::user()->idCabang);
                })
                ->sum('quantity');
            $totalPenjualan = Sale::whereBetween('created_at', [$awalBulan, $akhirBulan])->where('idCabang', Auth::user()->idCabang)->sum('subtotal');
            $totalPengeluaran = Purchase::whereBetween('created_at', [$awalBulan, $akhirBulan])->where('idCabang', Auth::user()->idCabang)->sum('total');
            $revenue = $totalPenjualan - $totalPengeluaran;
            $totalPegawai = User::where('idCabang', Auth::user()->idCabang)->count();

            $dataRevenue = [
                ['name' => 'Penjualan', 'y' => intval($totalPenjualan)],
                ['name' => 'Pengeluaran', 'y' => intval($totalPengeluaran)],
            ];

            $cash = Sale::whereBetween('created_at', [$awalBulan, $akhirBulan])->where('idCabang', Auth::user()->idCabang)->where('payment', 'cash')->count();
            $qris = Sale::whereBetween('created_at', [$awalBulan, $akhirBulan])->where('idCabang', Auth::user()->idCabang)->where('payment', 'qris')->count();

            $dataPembayaran = [
                ['name' => 'Cash', 'y' => intval($cash)],
                ['name' => 'QRIS', 'y' => intval($qris)],
            ];

            $sales = SaleDetail::select('name', DB::raw('SUM(quantity) as total_sales'))
                ->groupBy('name')
                ->whereBetween('sale_details.created_at', [$awalBulan, $akhirBulan])
                ->where('idCabang', Auth::user()->idCabang)
                ->join('sales', 'sale_details.idSales', '=', 'sales.idSales')
                ->join('products', 'sale_details.idProduk', '=', 'products.idProduct')
                ->get();
            $product = Product::select('name')->get();
            $productSales = [];
            foreach ($product as $p) {
                $productSales[$p->name] = 0;
                foreach ($sales as $s) {
                    if ($p->name == $s->name) {
                        $productSales[$p->name] = intval($s->total_sales);
                    }
                }
            }
            return view('layouts.admin.dashboard.index', compact('totalProduk', 'productSales', 'dataPembayaran', 'dataRevenue', 'totalQuantityPenjualan',  'totalPegawai', 'revenue'));
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
