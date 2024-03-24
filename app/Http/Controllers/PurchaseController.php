<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $purchase = Purchase::where('idUser', '=', Auth()->user()->idUser)->orderBy('created_at', 'desc')->get();
        return datatables()
            ->of($purchase)
            ->addIndexColumn()
            ->addColumn('aksi', function ($purchase) {
                return view('layouts.admin.Purchase.tombol', ['data' => $purchase]);
            })
            ->addColumn('cabang', function ($purchase) {
                return $purchase->cabang->name;
            })
            ->addColumn('tanggal', function ($purchase) {
                return $purchase->created_at->format('d-m-y');
            })
            ->addColumn('user', function ($purchase) {
                return $purchase->user->name;
            })
            ->addColumn('harga', function ($purchase) {
                return 'Rp ' . number_format($purchase->total, 0, ',', '.');
            })
            ->rawColumns(['aksi', 'cabang', 'user', 'tanggal', 'harga'])
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
        $request->validate(
            [
                'name' => 'required|max:255|string',
                'total' => 'required|integer',
            ]
        );
        $request['idUser'] = Auth::user()->idUser;
        $request['idCabang'] = Auth::user()->idCabang;
        $request['idTransaction'] = 2;
        $data = $request->all();
        Purchase::create($data);
        $message = 'Berhasil Menambahkan Purchase';
        return redirect()->route('pembelian.index')->with('success_message_create', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Purchase::where('idPurchase', $id)->first();
        return response()->json(['result' => $data], 200);
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
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|max:255|string',
                'total' => 'required|integer',
            ]
        );
        $purchase = Purchase::findOrFail($id);
        $data = $request->all();
        $purchase->update($data);
        $message = 'Berhasil Mengedit Purchase';
        return redirect()->route('pembelian.index')->with('success_message_update', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();
        $message = 'Berhasil Menghapus Purchase';
        return redirect()->route('pembelian.index')->with('success_message_delete', $message);
    }
}
