<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;
    protected $primaryKey = 'idSaleDetail';
    protected $fillable = [
        'idSales', 'idProduk', 'quantity', 'total'
    ];
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'idSales', 'idSales'); // Sesuaikan dengan nama kolom yang sesuai
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'idProduk', 'idProduct');
    }
}
