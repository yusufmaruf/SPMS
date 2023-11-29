<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\Cabang;
use App\Models\BahanBaku;
use App\Models\Product;
use Illuminate\Http\Request;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stoks = Stok::with('bahan', 'cabang')->get();
        return view('layouts.admin.stok.index', compact('stoks'));
    }
    public function data()
    {
        $stoks = Stok::with('bahan', 'cabang')->get();

        return datatables()
            ->of($stoks)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stoks) {
                return '
                <div class="btn-group">
                    <a class="btn  btn-warning btn-flat" href="' . route('stok.edit', $stoks->idStok) . '">Sunting</a>
                    <button onclick="deleteData(`' . route('stok.destroy', $stoks->idStok) . '`)" class="btn  btn-danger btn-flat">Hapus</button>               
                </div>
                ';
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
        return redirect()->route('stok.index')->with('success', 'Berhasil Ditambahkan');
    }


    /**
     * Display the specified resource.
     */
    public function show(Stok $stok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Stok::where('idStok', $id)->first();
        $bahan = BahanBaku::all();
        $cabang = Cabang::all();
        return view('layouts.admin.stok.edit', compact('data', 'bahan', 'cabang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $stock = Stok::where('idStok', $id)->first();
        $stock->update($request->all());
        return redirect()->route('stok.index')->with('success', 'Berhasil Diubah');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stock = Stok::where('idStok', $id)->first();
        $stock->delete();
        return redirect()->route('stok.index')->with('success', 'Berhasil Dihapus');
    }
}
