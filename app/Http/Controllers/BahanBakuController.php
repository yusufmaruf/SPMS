<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bahanBakus = BahanBaku::all();
        return view('layouts.admin.bahanbaku.index', compact('bahanBakus'));
    }

    public function data()
    {
        // $product = Product::all();
        $bahan = BahanBaku::orderBy('idBahan', 'desc')->get();
        return datatables()
            ->of($bahan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($bahan) {
                return view('layouts.admin.BahanBaku.tombol', ['data' => $bahan]);
            })
            ->rawColumns(['aksi', 'image'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.admin.bahanbaku.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        BahanBaku::create($data);
        $message = 'Berhasil Menambahkan Bahan Baku';
        return redirect()->route('bahanbaku.index')->with('success_message_create', 'Item has been added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = BahanBaku::where('idBahan', $id)->first();
        return response()->json(['result' => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = BahanBaku::where('idBahan', $id)->first();
        return response()->json(['result' => $data], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $bahanBaku->update($data);

        return redirect()->route('bahanbaku.index')->with('success', 'Berhasil DiUbah');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);
        $bahanBaku->delete();
        return redirect()->route('bahanbaku.index')->with('success_message_delete', 'Berhasil DiHapus');
    }
}
