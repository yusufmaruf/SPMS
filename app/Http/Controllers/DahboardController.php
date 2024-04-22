<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Stok;
use App\Models\User;
use App\Models\Cabang;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Psy\Command\WhereamiCommand;

class DahboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $awalBulan = Carbon::now()->startOfMonth();
        $akhirBulan = Carbon::now();
        $totalProduk = Product::count();
        $message = [];

        if (Auth::user()->role == 'admin' || Auth::user()->role == 'manager') {
            $totalQuantityPenjualan = SaleDetail::whereBetween('created_at', [$awalBulan, $akhirBulan])->sum('quantity');
            $totalPegawai = User::count();
            $totalCabang = Cabang::where('idCabang', '!=', Auth::user()->idCabang)->count();
            $totalPenjualan = Sale::whereBetween('created_at', [$awalBulan, $akhirBulan])->sum('subtotal');
            $totalPengeluaran = Purchase::whereBetween('created_at', [$awalBulan, $akhirBulan])->sum('total');
            $revenue = $totalPenjualan - $totalPengeluaran;

            $dataRevenue = [
                ['name' => 'Penjualan', 'y' => intval($totalPenjualan)],
                ['name' => 'Pengeluaran', 'y' => intval($totalPengeluaran)],
            ];

            $cash = Sale::whereBetween('created_at', [$awalBulan, $akhirBulan])->where('payment', 'cash')->count();
            $qris = Sale::whereBetween('created_at', [$awalBulan, $akhirBulan])->where('payment', 'qris')->count();

            $dataPembayaran = [
                ['name' => 'Cash', 'y' => intval($cash)],
                ['name' => 'QRIS', 'y' => intval($qris)],
            ];

            $sales = SaleDetail::select('name', DB::raw('SUM(quantity) as total_sales'))
                ->groupBy('name')
                ->whereBetween('sale_details.created_at', [$awalBulan, $akhirBulan])
                ->join('sales', 'sale_details.idSales', '=', 'sales.idSales')
                ->join('products', 'sale_details.idProduk', '=', 'products.idProduct')
                ->get();
            $product = Product::select('name')->get();
            $productSales = [];
            foreach ($product as $p) {
                $productSales[$p->name] = 0;
                foreach ($sales as $s) {
                    if ($p->name == $s->name) {
                        $productSales[$p->name] = intval($s->total_sales);
                    }
                }
            }
            $cabang = Cabang::all();
            $cabang = $cabang->pluck('idCabang');
            foreach ($cabang as $c) {
                $data2 = DB::table('sale_details as sd')
                    ->join('receipts as r', 'sd.idProduk', '=', 'r.idProduct')
                    ->join('bahan_bakus as bb', 'bb.idBahan', '=', 'r.idBahan')
                    ->join('sales as s', 's.idSales', '=', 'sd.idSales')
                    ->join('cabangs as c', 'c.idCabang', '=', 's.idCabang')
                    ->whereBetween('sd.created_at', ['2023-10-23', '2023-10-28'])
                    ->select(
                        'bb.name as bahan_baku',
                        DB::raw('DATE(sd.created_at) as tanggal'),
                        DB::raw('SUM(sd.quantity * r.quantity) as total_penggunaan')
                    )
                    ->groupBy('bb.name', DB::raw('DATE(sd.created_at)'))
                    ->where('c.idCabang', '=', $c)
                    ->get();

                $uniqueBahanBaku = $data2->pluck('bahan_baku')->unique();
                foreach ($uniqueBahanBaku as $bahanbaku) {
                    $quantities = $data2->where('bahan_baku', $bahanbaku)->pluck('total_penggunaan');

                    // Periksa apakah $quantities tidak kosong sebelum melakukan operasi
                    if ($quantities->isNotEmpty()) {
                        // Hitung total penggunaan sebelumnya
                        $totalPermintaanSebelumnya = $quantities->sum();
                        // Hitung rata-rata penggunaan
                        $mean = $quantities->avg();
                        // Hitung maksimum penggunaan
                        $maximumPermintaan = $quantities->max();
                        $safetystock = ($maximumPermintaan - $mean) * 3;
                        $maximumStock = round(2 * ($mean * 3) + $safetystock, 0);
                        $minimumStock = round(($mean * 3) + $safetystock, 0);
                        $pemesananKembali = round($maximumStock - $minimumStock);

                        $stok = DB::table('stoks')
                            ->select('stoks.jumlah as quantity', 'bahan_bakus.name as name', 'cabangs.name as cabang')
                            ->join('bahan_bakus', 'bahan_bakus.idBahan', '=', 'stoks.idBahan')
                            ->join('cabangs', 'cabangs.idCabang', '=', 'stoks.idCabang')
                            ->where('bahan_bakus.name', $bahanbaku)
                            ->where('cabangs.idCabang', '=', $c);
                        $nameCabang = Cabang::where('idCabang', $c)->first();
                        if ($stok->count() == 0) {
                            $message[] = [
                                'color' => 'danger',
                                'message' => 'Stok ' . $bahanbaku . ' Pada ' . $nameCabang->name . ' tidak ada',
                            ];
                        } else if ($minimumStock > $stok->first()->quantity) {
                            $message[] = [
                                'color' => 'warning',
                                'message' => 'Stok ' . $bahanbaku . ' Pada ' . $nameCabang->name . ' kurang dari ' . $minimumStock,
                            ];
                        } else if ($maximumStock < $stok->first()->quantity) {
                            $message[] = [
                                'color' => 'warning',
                                'message' => 'Stok ' . $bahanbaku . ' Pada ' . $nameCabang->name . ' lebih dari ' . $maximumStock,
                            ];
                        }
                    }
                }
            }




            // dd($message);
            if (Auth::user()->role == 'admin') {
                return view('layouts.admin.dashboard.indexadmin', compact('totalProduk', 'productSales',  'dataPembayaran',  'dataRevenue',  'revenue', 'totalQuantityPenjualan', 'totalPenjualan', 'totalPegawai', 'totalCabang', 'message'));
            } else {
                return view('layouts.admin.dashboard.indexadmin', compact('totalProduk', 'productSales',  'dataPembayaran',  'dataRevenue',  'revenue', 'totalQuantityPenjualan', 'totalPenjualan', 'totalPegawai', 'totalCabang', 'message'));
            }
        } else {
            $totalQuantityPenjualan = SaleDetail::whereBetween('created_at', [$awalBulan, $akhirBulan])
                ->whereHas('sale', function ($query) {
                    $query->where('idCabang', Auth::user()->idCabang);
                })
                ->sum('quantity');
            $totalPenjualan = Sale::whereBetween('created_at', [$awalBulan, $akhirBulan])->where('idCabang', Auth::user()->idCabang)->sum('subtotal');
            $totalPengeluaran = Purchase::whereBetween('created_at', [$awalBulan, $akhirBulan])->where('idCabang', Auth::user()->idCabang)->sum('total');
            $revenue = $totalPenjualan - $totalPengeluaran;
            $totalPegawai = User::where('idCabang', Auth::user()->idCabang)->count();

            $dataRevenue = [
                ['name' => 'Penjualan', 'y' => intval($totalPenjualan)],
                ['name' => 'Pengeluaran', 'y' => intval($totalPengeluaran)],
            ];

            $cash = Sale::whereBetween('created_at', [$awalBulan, $akhirBulan])->where('idCabang', Auth::user()->idCabang)->where('payment', 'cash')->count();
            $qris = Sale::whereBetween('created_at', [$awalBulan, $akhirBulan])->where('idCabang', Auth::user()->idCabang)->where('payment', 'qris')->count();

            $dataPembayaran = [
                ['name' => 'Cash', 'y' => intval($cash)],
                ['name' => 'QRIS', 'y' => intval($qris)],
            ];

            $sales = SaleDetail::select('name', DB::raw('SUM(quantity) as total_sales'))
                ->groupBy('name')
                ->whereBetween('sale_details.created_at', [$awalBulan, $akhirBulan])
                ->where('idCabang', Auth::user()->idCabang)
                ->join('sales', 'sale_details.idSales', '=', 'sales.idSales')
                ->join('products', 'sale_details.idProduk', '=', 'products.idProduct')
                ->get();
            $product = Product::select('name')->get();
            $productSales = [];
            foreach ($product as $p) {
                $productSales[$p->name] = 0;
                foreach ($sales as $s) {
                    if ($p->name == $s->name) {
                        $productSales[$p->name] = intval($s->total_sales);
                    }
                }
            }
            return view('layouts.admin.dashboard.index', compact('totalProduk', 'productSales', 'dataPembayaran', 'dataRevenue', 'totalQuantityPenjualan',  'totalPegawai', 'revenue'));
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
