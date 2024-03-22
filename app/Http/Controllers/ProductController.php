<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('layouts.admin.product.index', compact('products'));
    }
    public function data()
    {
        $product = Product::all();
        $product = Product::orderBy('idProduct', 'desc')->get();
        return datatables()
            ->of($product)
            ->addIndexColumn()
            ->addColumn('aksi', function ($product) {
                return view('layouts.admin.product.tombol', ['data' => $product]);
            })
            ->addColumn('image', function ($product) {
                return '<image src="' . Storage::url($product->image) . '" width="50px" class="img-circle elevation-2" alt="User Image">';
            })
            ->addColumn('desc', function ($product) {
                return '<p>' . $product->description . '</p>';
            })
            ->addColumn('price', function ($product) {
                return 'Rp ' . number_format($product->price, 0, ',', '.');
            })
            ->rawColumns(['aksi', 'desc', 'image'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:1',
        ]);
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['image'] = $request->file('image')->store('assets/category', 'public');
        Product::create($data);
        return redirect()->route('product.index')->with('success_message_create', 'Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $data = Product::where('idProduct', $product->idProduct)->first();
        return response()->json(['result' => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = Product::where('idProduct', $id)->first();
        return view('layouts.admin.product.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255|string',
            'description' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:1',
        ]);
        $data = $request->all();

        $item = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }            // Upload foto baru
            $data['image'] = $request->file('image')->store('assets/category', 'public');
        }

        $data['slug'] = Str::slug($request->name);

        $item->update($data);

        return redirect()->route('product.index')->with('success_message_update', 'Item Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $item = Product::where('idProduct', $id)->first();
            // dd($item->saleDetail()->count());
            if ($item->saleDetail()->count() > 0 && $item->receipt()->count() > 0) {
                return redirect()->route('product.index')->with('error_message_delete', 'Item Tidak karena terkait dengan yang lain');
            }
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $item->delete();
            return redirect()->route('product.index')->with('success_message_delete', 'Berhasil Dihapus');
        } catch (\Throwable $th) {
            return redirect()->route('product.index')->with('error_message_delete', $th->getMessage());
        }
    }
}
