<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    protected $primaryKey = 'idReceipt';
    protected $fillable = [
        'idProduct', 'idBahan', 'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'idProduct', 'idProduct');
    }

    public function bahanbaku()
    {
        return $this->belongsTo(BahanBaku::class, 'idBahan', 'idBahan');
    }
}
