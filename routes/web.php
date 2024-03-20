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
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\PlanReceiptController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReportController;
use App\Http\Controllers\ReportSalesController;
use App\Http\Controllers\UserController;

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
    route::get('/pembelian/data', [PurchaseController::class, 'data'])->name('purchase.data');
    route::get('/pengguna/data', [UserController::class, 'data'])->name('pengguna.data');
    route::get('/stok/data', [StokController::class, 'data'])->name('stok.data');
    route::get('/resep/data', [ReceiptController::class, 'data'])->name('resep.data');
    route::get('/cart/data', [SaleController::class, 'data'])->name('cart.data');
    route::get('/laporanpenjualan/data', [ReportSalesController::class, 'data'])->name('laporanpenjualan.data');
    route::get('/plantreceipt/data', [PlanReceiptController::class, 'data'])->name('plantreceipt.data');
    route::get('/totalcart', [CartController::class, 'total'])->name('cart.total');
    route::get('reportpurchase/data', [PurchaseReportController::class, 'data'])->name('reportpurchase.data');
    route::get('reportsales/print', [ReportSalesController::class, 'print'])->name('reportsales.print');
    route::get('reportpurchase/print', [PurchaseReportController::class, 'print'])->name('reportpurchase.print');
    route::get('forecast/predict', [ForecastController::class, 'prediction'])->name('forecast.prediction');
    route::get('forecast/prediction/{id}', [ForecastController::class, 'prediction'])->name('forecast.prediction');
    Route::get('data-chart/{id}', [ForecastController::class, 'getData'])->name('forecast.getdata');



    route::resource('stok', StokController::class);
    route::resource('pengguna', UserController::class);
    route::resource('product', ProductController::class);
    route::resource('resep', ReceiptController::class);
    route::resource('penjualan', SaleController::class);
    route::resource('cart', CartController::class);
    route::resource('laporanpenjualan', ReportSalesController::class);
    route::resource('plantReceipt', PlanReceiptController::class);
    route::resource('pembelian', PurchaseController::class);
    route::resource('reportpurchase', PurchaseReportController::class);
    route::resource('forecast', ForecastController::class);
    route::resource('prediksi', PrediksiController::class);


    route::prefix('admin')->middleware('admin')->group(function () {
        route::get('/bahan/data', [BahanBakuController::class, 'data'])->name('bahan.data');
        route::get('/cabang/data', [CabangController::class, 'data'])->name('cabang.data');
        route::resource('cabang', CabangController::class);
        route::resource('bahanbaku', BahanBakuController::class);
        route::resource('manajemenstok', ManajemenStokController::class);
    });
});
