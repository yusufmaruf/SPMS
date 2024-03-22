<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CabangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cabangs = Cabang::all();
        return view('layouts.admin.cabang.index', compact('cabangs'));
    }

    public function data()
    {
        // $product = Product::all();
        $cabang = Cabang::orderBy('idCabang', 'desc')->get();
        return datatables()
            ->of($cabang)
            ->addIndexColumn()
            ->addColumn('aksi', function ($cabang) {
                return view('layouts.admin.cabang.tombol', ['data' => $cabang]);
            })
            ->addColumn('image', function ($product) {
                return '<image src="' . Storage::url($product->image) . '" width="50px" class="img-circle elevation-2" alt="User Image">';
            })
            ->rawColumns(['aksi', 'image'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.admin.cabang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'location' => 'required|max:255|string',
            'phone' => 'required|max:255|string',
            'open' => 'required|max:255|string',
            'close' => 'required|max:255|string',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['image'] = $request->file('image')->store('assets/cabang', 'public');
        Cabang::create($data);
        return redirect()->route('cabang.index')->with('success_message_create', 'Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cabang $cabang)
    {
        $data = Cabang::where('idCabang', $cabang->idCabang)->first();
        return response()->json(['result' => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = Cabang::where('idCabang', $id)->first();
        return view('layouts.admin.cabang.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|max:255|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'location' => 'required|max:255|string',
            'phone' => 'required|max:255|string',
            'open' => 'required|max:255|string',
            'close' => 'required|max:255|string',
        ]);
        $data = $request->all();

        $item = Cabang::findOrFail($id);

        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }            // Upload foto baru
            $data['image'] = $request->file('image')->store('assets/cabang', 'public');
        }

        $data['slug'] = Str::slug($request->name);

        $item->update($data);

        return redirect()->route('cabang.index')->with('success_message_update', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $item = Cabang::where('idCabang', $id)->first();

            if ($item->penjualan()->count() > 0 || $item->stok()->count() > 0) {
                return redirect()->route('cabang.index')->with('error_message_delete', 'Gagal Menghapus Data Dikarenakan data terhubung dengan data lain');
            }
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $item->delete();
            return redirect()->route('cabang.index')->with('success_message_delete', 'Berhasil Dihapus');
        } catch (\Throwable $th) {
            return redirect()->route('cabang.index')->with('error_message_delete', 'Gagal Menghapus Data');
        }
    }
}
