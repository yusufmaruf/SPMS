<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

use function Laravel\Prompts\select;

class PurchaseReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts.admin.ReportPurchase.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        $purchase = Purchase::select(
            DB::raw('SUM(total) as total_subtotal'),
            'idUser',
            'idCabang',
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_created_at')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('idCabang', '=', Auth()->user()->idCabang)
            ->groupBy('idUser',  'idCabang', 'formatted_created_at')
            ->get();
        // return view('layouts.admin.ReportPurchase.print', compact('purchase', 'startDate', 'endDate'));
        $pdf = FacadePdf::loadView('layouts.admin.ReportPurchase.print', compact('purchase', 'startDate', 'endDate'));
        return $pdf->download('purchase_' . Carbon::now()->format('Ymd') . '.pdf');
    }



    public function data(Request $request)
    {
        // ambil data untuk bulan ini
        $starDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        if ($request->dari !== null) {
            $starDate = Carbon::parse($request->dari);
        }
        if ($request->sampai !== null) {
            $endDate = Carbon::parse($request->sampai);
        }
        $purchase = Purchase::select(
            DB::raw('SUM(total) as total_subtotal'),
            'idUser',
            'idCabang',
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_created_at')
        )
            ->whereBetween('created_at', [$starDate, $endDate])
            ->where('idCabang', '=', Auth()->user()->idCabang)
            ->groupBy('idUser',  'idCabang', 'formatted_created_at')
            ->get();
        return datatables()
            ->of($purchase)
            ->addIndexColumn()
            ->addColumn('cabang', function ($purchase) {
                return $purchase->cabang->name;
            })
            ->addColumn(
                'user',
                function ($purchase) {
                    return $purchase->user->name;
                }
            )
            ->addColumn('total_subtotal', function ($purchase) {
                return 'Rp. ' . number_format($purchase->total_subtotal, 0, ',', '.');
            })
            ->addColumn('aksi', function ($purchase) {
                return '<div class="btn-group">
                            <a href="' . route('reportpurchase.show', ['reportpurchase' => $purchase->formatted_created_at]) . '" class="btn  btn-primary btn-flat">Show</a>
                        </div>';
            })
            ->rawColumns(['cabang', 'user', 'total_subtotal', 'aksi'])
            ->make(true);
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
        $purchase = Purchase::where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), $id)
            ->where('idUser', '=', Auth()->user()->idUser)->orderBy('created_at', 'desc')
            ->get();
        return view('layouts.admin.ReportPurchase.show', compact('purchase'));
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
