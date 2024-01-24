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
                return '
                <div class="btn-group">
               <a class="btn  btn-warning btn-flat" href="' . route('cabang.edit', $cabang->idCabang) . '">
                                        Sunting
                                    </a>
                    <button onclick="deleteData(`'  . route('cabang.destroy', ['cabang' => $cabang->idCabang]) . '`)" class="btn  btn-danger btn-flat">Hapus</button>
                   
                </div>
                ';
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
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['image'] = $request->file('image')->store('assets/cabang', 'public');
        Cabang::create($data);
        toast('Your Post as been submited!', 'success');
        return redirect()->route('cabang.index')->with('success', 'Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cabang $cabang)
    {
        //
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
        $data = $request->all();

        $item = Cabang::findOrFail($id);

        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }            // Upload foto baru
            $data['image'] = $request->file('image')->store('assets/category', 'public');
        }

        $data['slug'] = Str::slug($request->name);

        $item->update($data);

        return redirect()->route('cabang.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Cabang::where('idCabang', $id)->first();


        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return redirect()->route('cabang.index')->with('success', 'Berhasil Dihapus');
    }
}
