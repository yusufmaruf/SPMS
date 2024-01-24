<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Receipt;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $receipt = Receipt::select('idProduct')
            ->groupBy('idProduct')
            ->with('product', 'bahanbaku')
            ->get();

        return view('layouts.admin.receipt.index', compact('receipt'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk = Product::all();
        $bahan = BahanBaku::all();
        return view('layouts.admin.receipt.create', compact('produk', 'bahan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        foreach ($request->idBahan as $key => $value) {
            $receipt = new Receipt();

            // Debug statements

            $receipt->idProduct = $request->idProduct;
            $receipt->quantity = $request->quantity[$key];
            $receipt->idBahan = $request->idBahan[$key];
            $receipt->save();
        }

        return redirect()->route('resep.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $name = Receipt::where('idProduct', $id)->with('product')->first();
        $receipt = Receipt::where('idProduct', $id)->with('product', 'bahanbaku')->get();
        return view('layouts.admin.receipt.show', compact('receipt', 'name'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $name = Receipt::where('idProduct', $id)->with('product')->first();
        if ($name == null) {
            return redirect()->route('resep.index');
        }
        $receipt = Receipt::where('idProduct', $id)->with('product', 'bahanbaku')->get();
        return view('layouts.admin.receipt.edit', compact('receipt', 'name'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $receipt = Receipt::where('idReceipt', $id)->first();
        $receipt->update($request->all());
        return redirect()->route('resep.edit', ['resep' => $receipt->idProduct]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Receipt::where('idReceipt', $id)->first();
        $item->delete();
        return redirect()->route('resep.edit', ['resep' => $item->idProduct])->with('success', 'Berhasil Dihapus');
    }
}
