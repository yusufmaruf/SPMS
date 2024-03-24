<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Cabang;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;


class AdminReportSales extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cabang = Cabang::all();
        return view('layouts.admin.ReportSalesAdmin.index', compact('cabang'));
    }

    public function data(Request $request)
    {
        $starDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        if ($request->dari !== null) {
            $starDate = Carbon::parse($request->dari);
        }
        if ($request->sampai !== null) {
            $endDate = Carbon::parse($request->sampai);
        }
        if ($request->idCabang !== null) {
            $purchase = Sale::select(
                DB::raw('SUM(subtotal) as total_subtotal'),
                'idCabang',
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_created_at')
            )
                ->whereBetween('created_at', [$starDate, $endDate])
                ->where('idCabang', '=', $request->idCabang)
                ->groupBy('formatted_created_at', 'idCabang')
                ->get();
            return datatables()
                ->of($purchase)
                ->addIndexColumn()
                ->addColumn('total_subtotal', function ($purchase) {
                    return 'Rp. ' . number_format($purchase->total_subtotal, 0, ',', '.');
                })
                ->addColumn('cabang', function ($purchase) {
                    return $purchase->cabang->name;
                })
                ->addColumn('aksi', function ($purchase) {
                    return '<div class="btn-group">
                            <a href="' . route('adminReportSales.show', ['adminReportSale' => $purchase->formatted_created_at, 'idCabang' => $purchase->idCabang]) . '" class="btn  btn-primary btn-flat">Show</a>
                        </div>';
                })
                ->rawColumns(['cabang', 'total_subtotal', 'aksi'])
                ->make(true);
        } else {
            $purchase = Sale::select(
                DB::raw('SUM(subtotal) as total_subtotal'),
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_created_at')
            )
                ->whereBetween('created_at', [$starDate, $endDate])
                ->groupBy('formatted_created_at')
                ->get();
            return datatables()
                ->of($purchase)
                ->addIndexColumn()
                ->addColumn('total_subtotal', function ($purchase) {
                    return 'Rp. ' . number_format($purchase->total_subtotal, 0, ',', '.');
                })
                ->addColumn('cabang', function ($purchase) {
                    return "Semua Cabang";
                })
                ->addColumn('aksi', function ($purchase) {
                    return '<div class="btn-group">
                            <a href="' . route('adminReportSales.show', ['adminReportSale' => $purchase->formatted_created_at]) . '" class="btn  btn-primary btn-flat">Show</a>
                        </div>';
                })
                ->rawColumns(['cabang', 'total_subtotal', 'aksi'])
                ->make(true);
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
    public function show($id, Request $request)
    {
        if ($request->idCabang !== null) {
            $saleDetail = SaleDetail::Select(
                'sale_details.created_at',
                'sale_details.idProduk',
                'sale_details.quantity',
                'sale_details.total',
                'cabangs.name as cabang',
            )
                ->where(DB::raw("DATE_FORMAT(sale_details.created_at, '%Y-%m-%d')"), $id)
                ->join('sales', 'sale_details.idSales', '=', 'sales.idSales')
                ->join('cabangs', 'sales.idCabang', '=', 'cabangs.idCabang')
                ->where('sales.idCabang', '=', $request->idCabang)
                ->get();
            // dd($saleDetail);
            return view('layouts.admin.ReportSalesAdmin.show', compact('saleDetail'));
        } else {
            $purchase = Sale::select(
                DB::raw('SUM(subtotal) as total_subtotal'),
                'idCabang',
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_created_at')
            )
                ->groupBy('formatted_created_at', 'idCabang')
                ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), $id)
                ->get();
            return view('layouts.admin.ReportSalesAdmin.showCabang', compact('purchase'));
        }
    }
    public function print(Request $request)
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        if ($request->dari !== null) {
            $startDate = Carbon::parse($request->dari);
        }
        if ($request->sampai !== null) {
            $endDate = Carbon::parse($request->sampai);
        }
        if ($request->idCabang !== null) {
            $purchase = Sale::select(
                DB::raw('SUM(subtotal) as total_subtotal'),
                'idCabang',
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_created_at')
            )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('idCabang', '=', $request->idCabang)
                ->groupBy('formatted_created_at', 'idCabang')
                ->get();
        } else {
            $purchase = Sale::select(
                DB::raw('SUM(subtotal) as total_subtotal'),
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_created_at')
            )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('formatted_created_at')
                ->get();
        }
        // dd($purchase);

        // return view('layouts.admin.reportSalesAdmin.print', compact('purchase', 'startDate', 'endDate'));
        $pdf = FacadePdf::loadView('layouts.admin.reportSalesAdmin.print', compact('purchase', 'startDate', 'endDate'));
        return $pdf->download('sales' . Carbon::now()->format('Ymd') . '.pdf');
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
