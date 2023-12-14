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
}
