<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\Cabang;
use App\Models\BahanBaku;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StokController extends Controller
{
    public function index()
    {
        $stoks = Stok::with('bahan', 'cabang')->get();
        return view('layouts.admin.stok.index', compact('stoks'));
    }
    public function data()
    {
        if (Auth::user()->role == 'admin') {
            $stoks = Stok::with('bahan', 'cabang')->get();
        } else {
            $stoks = Stok::with('bahan', 'cabang')->where('idCabang', Auth::user()->idCabang)->get();
        }

        return datatables()
            ->of($stoks)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stoks) {
                return view('layouts.admin.Stok.tombol', ['data' => $stoks]);
            })
            ->addColumn('nameBahan', function ($stoks) {
                return $stoks->bahan->name;
            })
            ->addColumn('nameCabang', function ($stoks) {
                return $stoks->cabang->name;
            })
            ->rawColumns(['aksi', 'nameBahan', 'nameCabang'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bahan = BahanBaku::all();
        $cabang = Cabang::all();
        return view('layouts.admin.stok.create', compact('bahan', 'cabang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idBahan' => 'required|exists:bahan_bakus,idBahan',
            'idCabang' => 'required|exists:cabangs,idCabang',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $stock = Stok::where('idBahan', $request->idBahan)->where('idCabang', $request->idCabang)->first();
        if ($stock) {
            $stock->update([
                'jumlah' => $stock->jumlah + $request->jumlah
            ]);
        } else {
            Stok::create([
                'idBahan' => $request->idBahan,
                'idCabang' => $request->idCabang,
                'jumlah' => $request->jumlah
            ]);
        }
        return redirect()->route('stok.index')->with('success_message_create', 'Berhasil Ditambahkan');
    }


    /**
     * Display the specified resource.
     */
    public function show(Stok $stok)
    {
        $data = Stok::where('idStok', $stok->idStok)->first();
        return response()->json(['result' => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $data = Stok::where('idStok', $id)->first();
            $bahan = BahanBaku::all();
            $cabang = Cabang::all();
            return view('layouts.admin.stok.edit', compact('data', 'bahan', 'cabang'))->with('success_message_update', 'Berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->route('stok.index')->with('error_message_update', 'Item Tidak Ditemukan');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'idBahan' => 'required|exists:bahan_bakus,idBahan',
            'idCabang' => 'required|exists:cabangs,idCabang',
            'jumlah' => 'required|numeric|min:1',
        ]);
        try {
            $stock = Stok::where('idStok', $id)->first();
            $stock->update($request->all());
            return redirect()->route('stok.index')->with('success_message_update', 'Berhasil Diubah');
        } catch (\Throwable $th) {
            return redirect()->route('stok.index')->with('error_message_update', 'Item Tidak Dapat Diubah');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $item = Stok::where('idStok', $id)->first();
            $item->delete();
            return redirect()->route('stok.index')->with('success_message_delete', 'Berhasil Dihapus');
        } catch (\Throwable $th) {
            return redirect()->route('stok.index')->with('error_message_delete', 'Item Tidak Dapat Dihapus');
        }
    }
}
