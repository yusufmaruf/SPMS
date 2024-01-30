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
                return '<input type="number" name="quantity" value="' . $plant->Quantity . '" class="form-control" onchange="updateData(' . $plant->idPlanReceipt . ',this.value,`' . route('plantReceipt.update', ['plantReceipt' => $plant->idPlanReceipt]) . '`)">';
            })
            ->addColumn('product_name', function ($plant) {
                $productNames = $plant->product->name;
                return $productNames;
            })
            ->addColumn('bahanbaku_name', function ($plant) {
                $bahanbakuNames = $plant->bahanbaku->name;
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
        $plant = new PlanReceipt();
        $plant->idProduct = $request->idProduct;
        $plant->idBahan = $request->idBahan;
        $plant->Quantity = intval($request->quantity);
        $plant->idUser = Auth::user()->idUser;
        $plant->save();
        return response()->json('success', 200);
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
    public function update(Request $request, $id)
    {
        $plant = PlanReceipt::where('idPlanReceipt', $id)->first();
        $plant->Quantity = intval($request->quantity);
        $plant->save();
        return response()->json('success', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plant = PlanReceipt::where('idPlanReceipt', $id)->first();
        $plant->delete();
        return response()->json('success', 200);
    }
}
