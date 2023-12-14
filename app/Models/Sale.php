<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $primaryKey = 'idSale';
    protected $fillable = [
        'idUser',    'idCabang', 'quantity', 'subtotal', 'payment'
    ];
}
