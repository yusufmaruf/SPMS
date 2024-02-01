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

            // Check stock availability before creating the sale
            if ($this->checkStockAvailability()) {
                // Proceed with creating the sale
                Sale::create([
                    'idUser' => $idUser,
                    'idCabang' => $idCabang,
                    'subtotal' => $subtotal,
                    'payment' => $payment
                ]);

                $cart = Cart::where('idUser', $idUser)->get();

                foreach ($cart as $item) {
                    // Create sale details
                    SaleDetail::create([
                        'idSales' => Sale::latest()->first()->idSales,
                        'idProduk' => $item->idProduct,
                        'quantity' => $item->quantity,
                        'total' => $item->total
                    ]);

                    // Remove the item from the cart
                    Cart::destroy($item->idCart);

                    // Update stock
                    $this->updateStock($item->idProduct, $item->quantity);
                }

                return redirect()->route('penjualan.index')->with('success', 'Berhasil Menambahkan');
            } else {
                // Handle insufficient stock situation (e.g., display an error message)
                return redirect()->route('penjualan.index')->with('error', 'Stok tidak mencukupi');
            }
        } catch (\Throwable $th) {
            // Handle other exceptions
            return redirect()->route('penjualan.index')->with('error', 'Terjadi kesalahan');
        }
    }

    private function checkStockAvailability()
    {
        $cart = Cart::where('idUser', Auth::user()->idUser)->get();

        foreach ($cart as $item) {
            $receipts = Receipt::where('idProduct', $item->idProduct)->get();

            foreach ($receipts as $receiptItem) {
                $stokItem = Stok::where('idBahan', $receiptItem->idBahan)
                    ->where('idCabang', Auth::user()->idCabang)
                    ->first();

                if (!$stokItem || $stokItem->jumlah < ($receiptItem->Quantity * $item->quantity)) {
                    return false; // Insufficient stock
                }
            }
        }

        return true; // Stock is available for all items in the cart
    }

    private function updateStock($productId, $quantity)
    {
        $receipts = Receipt::where('idProduct', $productId)->get();

        foreach ($receipts as $receiptItem) {
            $stokItem = Stok::where('idBahan', $receiptItem->idBahan)
                ->where('idCabang', Auth::user()->idCabang)
                ->first();

            if ($stokItem) {
                $pengurangan = $receiptItem->Quantity * $quantity;
                $stokItem->decrement('jumlah', $pengurangan);
            }
            // Note: If $stokItem is not found, it will be ignored here.
            // You may want to handle this situation depending on your requirements.
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
