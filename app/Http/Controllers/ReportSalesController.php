<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;


class ReportSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts.admin.reportSales.index');
    }

    public function print(Request $request)
    {
        $starDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        if ($request->dari !== null) {
            $starDate = Carbon::parse($request->dari);
        }
        if ($request->sampai !== null) {
            $endDate = Carbon::parse($request->sampai);
        }

        $purchase = Sale::select(
            DB::raw('SUM(subtotal) as total_subtotal'),
            'idUser',
            'detailTransactionSale',
            'idCabang',
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_created_at')
        )
            ->whereBetween('created_at', [$starDate, $endDate])
            ->groupBy('idUser', 'detailTransactionSale', 'idCabang', 'formatted_created_at')
            ->where('idCabang', '=', Auth()->user()->idCabang)
            ->get();

        if (Auth()->user()->idCabang == 1) {
            $purchase = Sale::select(
                DB::raw('SUM(subtotal) as total_subtotal'),
                'idUser',
                'detailTransactionSale',
                'idCabang',
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_created_at')
            )
                ->whereBetween('created_at', [$starDate, $endDate])
                ->groupBy('idUser', 'detailTransactionSale', 'idCabang', 'formatted_created_at')
                ->get();
        }
        // return view('layouts.admin.ReportSales.print', compact('purchase', 'starDate', 'endDate'));

        $pdf = FacadePdf::loadView('layouts.admin.ReportSales.print', compact('purchase', 'starDate', 'endDate'));
        return $pdf->download('sales_' . Carbon::now()->format('Ymd') . '.pdf');
    }

    public function data(Request $request)
    { // ambil data untuk bulan ini
        $starDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        if ($request->dari !== null) {
            $starDate = Carbon::parse($request->dari);
        }
        if ($request->sampai !== null) {
            $endDate = Carbon::parse($request->sampai);
        }

        $purchase = Sale::select(
            DB::raw('SUM(subtotal) as total_subtotal'),
            'idUser',
            'detailTransactionSale',
            'idCabang',
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_created_at')
        )
            ->whereBetween('created_at', [$starDate, $endDate])
            ->where('idCabang', '=', Auth()->user()->idCabang)
            ->groupBy('idUser', 'detailTransactionSale', 'idCabang', 'formatted_created_at')
            ->get();

        return datatables()
            ->of($purchase)
            ->addIndexColumn()
            ->addColumn('cabang', function ($purchase) {
                return $purchase->cabang->name;
            })
            ->addColumn('user', function ($purchase) {
                return $purchase->user->name;
            })
            ->addColumn('total_subtotal', function ($purchase) {
                return 'Rp. ' . number_format($purchase->total_subtotal, 0, ',', '.');
            })
            ->addColumn('aksi', function ($purchase) {
                return '<div class="btn-group">
                            <a href="' . route('laporanpenjualan.show', ['laporanpenjualan' => $purchase->formatted_created_at]) . '" class="btn  btn-primary btn-flat">Show</a>
                        </div>';
            })
            ->rawColumns(['cabang', 'user', 'total_subtotal', 'aksi'])
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
        $saleDetail = SaleDetail::where(DB::raw("DATE_FORMAT(sale_details.created_at, '%Y-%m-%d')"), $id)
            ->join('sales', 'sale_details.idSales', '=', 'sales.idSales')
            ->where('sales.idCabang', '=', Auth()->user()->idCabang)->get();
        return view('layouts.admin.ReportSales.show', compact('saleDetail'));
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
