<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

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
        $purchase = Purchase::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
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

        $purchase = Purchase::whereBetween('created_at', [$starDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return datatables()
            ->of($purchase)
            ->addIndexColumn()
            ->addColumn('cabang', function ($purchase) {
                return $purchase->cabang->name;
            })
            ->addColumn('tanggal', function ($purchase) {
                return $purchase->created_at->format('d-m-y');
            })
            ->addColumn('user', function ($purchase) {
                return $purchase->user->name;
            })
            ->rawColumns(['cabang', 'user', 'tanggal'])
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
