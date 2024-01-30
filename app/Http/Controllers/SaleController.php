<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\SaleDetail;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::get();
        return view('layouts.admin.penjualan.index', compact('product'));
    }

    public function data()
    {
        $cart = Cart::where('idUser', Auth::user()->idUser)
            ->with('products')
            ->get();
        return datatables()
            ->of($cart)
            ->addIndexColumn()
            ->addColumn('aksi', function ($cart) {
                return '
                <div class="btn-group">             
                    <button onclick="deleteData(`'  . route('cart.destroy', ['cart' => $cart->idCart]) . '`)" class="btn  btn-danger btn-flat">Hapus</button>
                   
                </div>
                ';
            })
            ->addColumn('quantity', function ($cart) {
                return '<input type="number" name="quantity" value="' . $cart->quantity . '" class="form-control" onchange="updateData(' . $cart->idCart . ',this.value,`' . route('cart.update', ['cart' => $cart->idCart]) . '`)">';
            })
            ->addColumn('product_name', function ($cart) {
                $productNames = $cart->products->pluck('name')->implode(', ');
                return $productNames;
            })

            ->rawColumns(['aksi', 'product_name', 'quantity'])
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
        try {
            $idUser = Auth::user()->idUser;
            $idCabang = Auth::user()->idCabang;
            $subtotal = Cart::where('idUser', Auth::user()->idUser)->sum('total');
            $payment = $request->payment;
            Sale::create([
                'idUser' => Auth::user()->idUser,
                'idCabang' => $idCabang,
                'subtotal' => $subtotal,
                'payment' => $payment
            ]);

            try {
                //code...
                $cart = Cart::where('idUser', Auth::user()->idUser)->get();
                foreach ($cart as $item) {
                    SaleDetail::create([
                        'idSales' => Sale::latest()->first()->idSales,
                        'idProduk' => $item->idProduct,
                        'quantity' => $item->quantity,
                        'total' => $item->total
                    ]);
                    Cart::destroy($item->idCart);

                    try {
                        //code...
                        $Bahan = Receipt::where('idProduct', $item->idProduct)->get();
                        foreach ($Bahan as $receiptItem) {
                            $stokItem = Stok::where('idBahan', $receiptItem->idBahan)
                                ->where('idCabang', Auth::user()->idCabang)
                                ->first();

                            if ($stokItem) {
                                $pengurangan = $receiptItem->Quantity * $item->quantity;
                                // Gunakan decrement hanya jika $stokItem ditemukan
                                $stokItem->decrement('jumlah', $pengurangan);
                            } else {
                                // Handle jika $stokItem tidak ditemukan
                                // (Anda dapat menentukan tindakan yang sesuai, misalnya, log pesan atau memberi tahu pengguna)
                            }
                        }
                    } catch (\Throwable $th) {
                        throw $th;
                    }
                }
            } catch (\Throwable $th) {
                throw $th;
            }
            return redirect()->route('penjualan.index')->with('success', 'Berhasil Menambahkan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
