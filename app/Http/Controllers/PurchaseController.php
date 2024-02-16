<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts.admin.Purchase.index');
    }

    public function data()
    {
        $purchase = Purchase::orderBy('idPurchase', 'desc')->get();
        return datatables()
            ->of($purchase)
            ->addIndexColumn()
            ->addColumn('aksi', function ($purchase) {
                return view('layouts.admin.Purchase.tombol', ['data' => $purchase]);
            })
            ->addColumn('cabang', function ($purchase) {
                return $purchase->cabang->name;
            })
            ->addColumn('user', function ($purchase) {
                return $purchase->user->name;
            })
            ->rawColumns(['aksi', 'cabang', 'user'])
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
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
