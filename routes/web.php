<?php

use App\Models\Receipt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\ManajemenStokController;
use App\Http\Controllers\DahboardController;
use App\Http\Controllers\ReportSalesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


route::get('/', function () {
    return view('login');
});

Auth::routes();

route::middleware('auth')->group(function () {
    route::get('/dashboard', [DahboardController::class, 'index']);
    route::get('/product/data', [ProductController::class, 'data'])->name('product.data');
    route::get('/stok/data', [StokController::class, 'data'])->name('stok.data');
    route::get('/resep/data', [ReceiptController::class, 'data'])->name('resep.data');
    route::get('/cart/data', [SaleController::class, 'data'])->name('cart.data');
    route::get('/laporanpenjualan/data', [ReportSalesController::class, 'data'])->name('laporanpenjualan.data');
    route::get('/totalcart', [CartController::class, 'total'])->name('cart.total');

    route::resource('stok', StokController::class);
    route::resource('product', ProductController::class);
    route::resource('resep', ReceiptController::class);
    route::resource('penjualan', SaleController::class);
    route::resource('cart', CartController::class);
    route::resource('laporanpenjualan', ReportSalesController::class);

    route::prefix('admin')->middleware('admin')->group(function () {
        route::get('/bahan/data', [BahanBakuController::class, 'data'])->name('bahan.data');
        route::get('/cabang/data', [CabangController::class, 'data'])->name('cabang.data');
        route::resource('cabang', CabangController::class);
        route::resource('bahanbaku', BahanBakuController::class);
        route::resource('manajemenstok', ManajemenStokController::class);
    });
});
