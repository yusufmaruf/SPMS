<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DahboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $awalBulan = Carbon::now()->startOfMonth();
        $akhirBulan = Carbon::now()->endOfMonth();
        if (Auth::user()->role == 'admin') {
            $totalPenjualan = SaleDetail::whereBetween('created_at', [$awalBulan, $akhirBulan])->sum('quantity');
            $totalCabang = Cabang::where('idCabang', '!=', Auth::user()->idCabang)->count();
            $totalProduk = Product::count();
            $revenue = Sale::whereBetween('created_at', [$awalBulan, $akhirBulan])->sum('subtotal');
            return view('layouts.admin.dashboard.index', compact('totalPenjualan', 'totalCabang', 'totalProduk', 'revenue'));
        } else {
            $totalPenjualan = SaleDetail::whereBetween('created_at', [$awalBulan, $akhirBulan])
                ->whereHas('sale', function ($query) {
                    $query->where('idCabang', Auth::user()->idCabang);
                })
                ->sum('quantity');
            $totalPegawai = User::where('idCabang', Auth::user()->idCabang)->count();
            $totalProduk = Product::count();
            $revenue = Sale::whereBetween('created_at', [$awalBulan, $akhirBulan])->where('idCabang', Auth::user()->idCabang)->sum('subtotal');
            return view('layouts.admin.dashboard.index', compact('totalPenjualan', 'totalProduk', 'revenue', 'totalPegawai'));
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
