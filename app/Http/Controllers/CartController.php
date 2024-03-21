<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $data = $request->all();
        $data['idUser'] = Auth::user()->idUser;
        $price = Product::where('idProduct', $data['idProduct'])->first();
        $data['total'] = $data['quantity'] * $price->price;
        Cart::create($data);
    }

    public function total()
    {

        $cart = 'Rp. ' . number_format(Cart::where('idUser', Auth::user()->idUser)->sum('total'), 0, ',', '.');
        return response()->json(
            [
                'content' => $cart,
                'message' => 'success'
            ],
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cart = Cart::where('idCart', $id)->first();
        $price = Product::where('idProduct', $cart->idProduct)->first();
        $cart->quantity = intval($request->quantity); // Pastikan $cart->quantity adalah bilangan bulat
        $cart->total = $cart->quantity * $price->price; // Menggunakan $price->price untuk mendapatkan nilai harga
        $cart->save();
        return response('success', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cart = Cart::where('idCart', $id)->first();
        $cart->delete();
        return response('success', 200);
    }
}
