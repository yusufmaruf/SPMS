<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts.admin.reportSales.index');
    }

    public function data()
    {
        if (Auth::user()->role == 'admin') {
            $sale = Sale::with('user', 'cabang')->orderBy('created_at', 'desc')->get();
        } else {
            $sale = Sale::where('idCabang', Auth::user()->idCabang)->with('user', 'cabang')->orderBy('created_at', 'desc')->get();
        }
        return datatables()
            ->of($sale)
            ->addIndexColumn()
            ->addColumn('aksi', function ($sale) {
                return '
                <div class="btn-group">
                    <a class="btn  btn-primary btn-flat" href="' . route('laporanpenjualan.show', $sale->idSales) . '">
                        Lihat Detail
                    </a>
                </div>
                ';
            })
            ->addColumn('desc', function ($sale) {
                return '<p>' . $sale->description . '</p>';
            })
            ->rawColumns(['aksi', 'desc', 'image'])
            ->make(true);
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
        $saleDetail = SaleDetail::where('idSales', $id)->with('product')->get();
        return view('layouts.admin.reportSales.show', [
            'saleDetail' => $saleDetail
        ]);
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
