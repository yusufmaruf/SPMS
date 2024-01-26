<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\PlanReceipt;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function data()
    {
        $plant = PlanReceipt::where('idUser', Auth::user()->idUser)
            ->with('product', 'bahanbaku')
            ->get();
        return datatables()
            ->of($plant)
            ->addIndexColumn()
            ->addColumn('aksi', function ($plant) {
                return '
                <div class="btn-group">             
                    <button onclick="deleteData(`'  . route('plantReceipt.destroy', ['plantReceipt' => $plant->idPlanReceipt]) . '`)" class="btn  btn-danger btn-flat">Hapus</button>
                   
                </div>
                ';
            })
            ->addColumn('quantity', function ($plant) {
                return '<input type="number" name="quantity" value="' . $plant->quantity . '" class="form-control" onchange="updateData(' . $plant->idPlanReceipt . ',this.value,`' . route('plantReceipt.update', ['plantReceipt' => $plant->idPlanReceipt]) . '`)">';
            })
            ->addColumn('product_name', function ($plant) {
                $productNames = $plant->product->pluck('name')->implode(', ');
                return $productNames;
            })
            ->addColumn('bahanbaku_name', function ($plant) {
                $bahanbakuNames = $plant->bahanbaku->pluck('name')->implode(', ');
                return $bahanbakuNames;
            })
            ->rawColumns(['aksi', 'product_name', 'quantity'])
            ->make(true);
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk = Product::all();
        $bahan = BahanBaku::all();
        return view('layouts.admin.receipt.createPlantReceipt', compact('produk', 'bahan'));
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
    public function show(PlanReceipt $planReceipt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlanReceipt $planReceipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlanReceipt $planReceipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlanReceipt $planReceipt)
    {
        //
    }
}
